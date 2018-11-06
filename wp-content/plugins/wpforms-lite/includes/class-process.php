<?php
/**
 * Process and validate form entries.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */
class WPForms_Process {

	/**
	 * Holds errors.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $errors;

	/**
	 * Holds formatted fields.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * Holds the ID of a successful entry.
	 *
	 * @since 1.2.3
	 *
	 * @var int
	 */
	public $entry_id = 0;

	/**
	 * Holds form data.
	 *
	 * @since 1.4.5
	 *
	 * @var array
	 */
	public $form_data;

	/**
	 * If a valid return has was processed.
	 *
	 * @since 1.4.5
	 *
	 * @var bool
	 */
	public $valid_hash = false;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'wp', array( $this, 'listen' ) );
	}

	/**
	 * Listen to see if this is a return callback or a posted form entry.
	 *
	 * @since 1.0.0
	 */
	public function listen() {

		if ( ! empty( $_GET['wpforms_return'] ) ) {
			$this->entry_confirmation_redirect( '', $_GET['wpforms_return'] );
		}

		if ( ! empty( $_POST['wpforms']['id'] ) ) {
			$this->process( stripslashes_deep( $_POST['wpforms'] ) );
		}
	}

	/**
	 * Process the form entry.
	 *
	 * @since 1.0.0
	 *
	 * @param array $entry $_POST object.
	 */
	public function process( $entry ) {

		$this->errors = array();
		$this->fields = array();
		$form_id      = absint( $entry['id'] );
		$form         = wpforms()->form->get( $form_id );
		$honeypot     = false;

		// Validate form is real and active (published).
		if ( ! $form || 'publish' !== $form->post_status ) {
			$this->errors[ $form_id ]['header'] = esc_html__( 'Invalid form.', 'wpforms' );
			return;
		}

		// Formatted form data for hooks.
		$form_data = apply_filters( 'wpforms_process_before_form_data', wpforms_decode( $form->post_content ), $entry );

		// Pre-process/validate hooks and filter. Data is not validated or
		// cleaned yet so use with caution.
		$entry = apply_filters( 'wpforms_process_before_filter', $entry, $form_data );

		do_action( 'wpforms_process_before', $entry, $form_data );
		do_action( "wpforms_process_before_{$form_id}", $entry, $form_data );

		// Validate fields.
		foreach ( $form_data['fields'] as $field ) {

			$field_id     = $field['id'];
			$field_type   = $field['type'];
			$field_submit = isset( $entry['fields'][ $field_id ] ) ? $entry['fields'][ $field_id ] : '';

			do_action( "wpforms_process_validate_{$field_type}", $field_id, $field_submit, $form_data );
		}

		// reCAPTCHA check.
		$site_key   = wpforms_setting( 'recaptcha-site-key', '' );
		$secret_key = wpforms_setting( 'recaptcha-secret-key', '' );
		if (
			! empty( $site_key ) &&
			! empty( $secret_key ) &&
			isset( $form_data['settings']['recaptcha'] ) &&
			'1' == $form_data['settings']['recaptcha']
		) {
			if ( ! empty( $_POST['g-recaptcha-response'] ) ) {
				$data  = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response'] );
				$data  = json_decode( wp_remote_retrieve_body( $data ) );
				if ( empty( $data->success ) ) {
					$this->errors[ $form_id ]['recaptcha'] = esc_html__( 'Incorrect reCAPTCHA, please try again.', 'wpforms' );
				}
			} else {
				$this->errors[ $form_id ]['recaptcha'] = esc_html__( 'reCAPTCHA is required.', 'wpforms' );
			}
		}

		// Initial error check.
		// Don't proceed if there are any errors thus far. We provide a filter
		// so that other features, such as conditional logic, have the ability
		// to adjust blocking errors.
		$errors = apply_filters( 'wpforms_process_initial_errors', $this->errors, $form_data );

		if ( ! empty( $errors[ $form_id ] ) ) {
			if ( empty( $this->errors[ $form_id ]['header'] ) ) {
				$errors[ $form_id ]['header'] = esc_html__( 'Form has not been submitted, please see the errors below.', 'wpforms' );
			}
			$this->errors = $errors;
			return;
		}

		// Validate honeypot early - before actual processing.
		if (
			! empty( $form_data['settings']['honeypot'] ) &&
			'1' == $form_data['settings']['honeypot'] &&
			! empty( $entry['hp'] )
		) {
			$honeypot = esc_html__( 'WPForms honeypot field triggered.', 'wpforms' );
		}

		$honeypot = apply_filters( 'wpforms_process_honeypot', $honeypot, $this->fields, $entry, $form_data );

		// If spam - return early.
		if ( $honeypot ) {
			// Logs spam entry depending on log levels set.
			wpforms_log(
				'Spam Entry ' . uniqid(),
				array( $honeypot, $entry ),
				array(
					'type'    => array( 'spam' ),
					'form_id' => $form_data['id'],
				)
			);

			return;
		}

		// Pass the form created date into the form data.
		$form_data['created'] = $form->post_date;

		// Format fields.
		foreach ( (array) $form_data['fields'] as $field ) {

			$field_id     = $field['id'];
			$field_type   = $field['type'];
			$field_submit = isset( $entry['fields'][ $field_id ] ) ? $entry['fields'][ $field_id ] : '';

			do_action( "wpforms_process_format_{$field_type}", $field_id, $field_submit, $form_data );
		}

		// This hook is for internal purposes and should not be leveraged.
		do_action( 'wpforms_process_format_after', $form_data );

		// Process hooks/filter - this is where most addons should hook
		// because at this point we have completed all field validation and
		// formatted the data.
		$this->fields = apply_filters( 'wpforms_process_filter', $this->fields, $entry, $form_data );

		do_action( 'wpforms_process', $this->fields, $entry, $form_data );
		do_action( "wpforms_process_{$form_id}", $this->fields, $entry, $form_data );

		$this->fields = apply_filters( 'wpforms_process_after_filter', $this->fields, $entry, $form_data );

		// One last error check - don't proceed if there are any errors.
		if ( ! empty( $this->errors[ $form_id ] ) ) {
			if ( empty( $this->errors[ $form_id ]['header'] ) ) {
				$this->errors[ $form_id ]['header'] = esc_html__( 'Form has not been submitted, please see the errors below.', 'wpforms' );
			}
			return;
		}

		// Success - add entry to database.
		$entry_id = $this->entry_save( $this->fields, $entry, $form_data['id'], $form_data );

		// Success - send email notification.
		$this->entry_email( $this->fields, $entry, $form_data, $entry_id, 'entry' );

		// Pass completed and formatted fields in POST.
		$_POST['wpforms']['complete'] = $this->fields;

		// Pass entry ID in POST.
		$_POST['wpforms']['entry_id'] = $entry_id;

		// Logs entry depending on log levels set.
		wpforms_log(
			$entry_id ? "Entry {$entry_id}" : 'Entry',
			$this->fields,
			array(
				'type'    => array( 'entry' ),
				'parent'  => $entry_id,
				'form_id' => $form_data['id'],
			)
		);

		// Post-process hooks.
		do_action( 'wpforms_process_complete', $this->fields, $entry, $form_data, $entry_id );
		do_action( "wpforms_process_complete_{$form_id}", $this->fields, $entry, $form_data, $entry_id );

		$this->entry_confirmation_redirect( $form_data );
	}

	/**
	 * Validate the form return hash.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hash
	 * @return mixed false for invalid or form id
	 */
	public function validate_return_hash( $hash = '' ) {

		$query_args = base64_decode( $hash );

		parse_str( $query_args, $output );

		// Verify hash matches.
		if ( wp_hash( $output['form_id'] . ',' . $output['entry_id'] ) !== $output['hash'] ) {
			return false;
		}

		// Get lead and verify it is attached to the form we received with it.
		$entry = wpforms()->entry->get( $output['entry_id'] );

		if ( $output['form_id'] != $entry->form_id ) {
			return false;
		}

		return array(
			'form_id'  => absint( $output['form_id'] ),
			'entry_id' => absint( $output['form_id'] ),
			'fields'   => $entry->fields,
		);
	}

	/**
	 * Redirects user to a page or URL specified in the form confirmation settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array|string $form_data
	 * @param string $hash
	 */
	public function entry_confirmation_redirect( $form_data = array(), $hash = '' ) {

		// Maybe process return hash.
		if ( ! empty( $hash ) ) {

			$hash_data = $this->validate_return_hash( $hash );

			if ( ! $hash_data || ! is_array( $hash_data ) ) {
				return;
			}

			$this->valid_hash = true;
			$this->entry_id   = absint( $hash_data['entry_id'] );
			$this->fields     = json_decode( $hash_data['fields'], true );
			$this->form_data  = wpforms()->form->get( absint( $hash_data['form_id'] ), array(
				'content_only' => true,
			) );

		} else {

			$this->form_data = $form_data;
		}

		// Backward compatibility.
		if ( empty( $this->form_data['settings']['confirmations'] ) ) {
			$this->form_data['settings']['confirmations'][1]['type']           = ! empty( $this->form_data['settings']['confirmation_type'] ) ? $this->form_data['settings']['confirmation_type'] : 'message';
			$this->form_data['settings']['confirmations'][1]['message']        = ! empty( $this->form_data['settings']['confirmation_message'] ) ? $this->form_data['settings']['confirmation_message'] : esc_html__( 'Thanks for contacting us! We will be in touch with you shortly.', 'wpforms' );
			$this->form_data['settings']['confirmations'][1]['message_scroll'] = ! empty( $this->form_data['settings']['confirmation_message_scroll'] ) ? $this->form_data['settings']['confirmation_message_scroll'] : 1;
			$this->form_data['settings']['confirmations'][1]['page']           = ! empty( $this->form_data['settings']['confirmation_page'] ) ? $this->form_data['settings']['confirmation_page'] : '';
			$this->form_data['settings']['confirmations'][1]['redirect']       = ! empty( $this->form_data['settings']['confirmation_redirect'] ) ? $this->form_data['settings']['confirmation_redirect'] : '';
		}

		if ( empty( $this->form_data['settings']['confirmations'] ) || ! is_array( $this->form_data['settings']['confirmations'] ) ) {
			return;
		}

		$confirmations = $this->form_data['settings']['confirmations'];

		// Reverse sort confirmations by id to process newer ones first.
		krsort( $confirmations );

		$default_confirmation_key = min( array_keys( $confirmations ) );

		foreach ( $confirmations as $confirmation_id => $confirmation ) {
			// Last confirmation should execute in any case.
			if ( $default_confirmation_key === $confirmation_id ) {
				break;
			}
			$process_confirmation = apply_filters( 'wpforms_entry_confirmation_process', true, $this->fields, $form_data, $confirmation_id );
			if ( $process_confirmation ) {
				break;
			}
		}

		$url = '';
		// Redirect if needed, to either a page or URL, after form processing.
		if ( ! empty( $confirmations[ $confirmation_id ]['type'] ) && 'message' !== $confirmations[ $confirmation_id ]['type'] ) {

			if ( 'redirect' === $confirmations[ $confirmation_id ]['type'] ) {
				$url = apply_filters( 'wpforms_process_smart_tags', $confirmations[ $confirmation_id ]['redirect'], $this->form_data, $this->fields, $this->entry_id );
			}

			if ( 'page' === $confirmations[ $confirmation_id ]['type'] ) {
				$url = get_permalink( (int) $confirmations[ $confirmation_id ]['page'] );
			}
		}

		if ( ! empty( $url ) ) {
			$url = apply_filters( 'wpforms_process_redirect_url', $url, $this->form_data['id'], $this->fields, $this->form_data, $this->entry_id );
			wp_redirect( esc_url_raw( $url ) );
			do_action( 'wpforms_process_redirect', $this->form_data['id'] );
			do_action( "wpforms_process_redirect_{$this->form_data['id']}", $this->form_data['id'] );
			exit;
		}

		// Pass a message to a frontend if no redirection happened.
		if ( ! empty( $confirmations[ $confirmation_id ]['type'] ) && 'message' === $confirmations[ $confirmation_id ]['type'] ) {
			wpforms()->frontend->confirmation_message = $confirmations[ $confirmation_id ]['message'];

			if ( ! empty( $confirmations[ $confirmation_id ]['message_scroll'] ) ) {
				wpforms()->frontend->confirmation_message_scroll = true;
			}
		}
	}

	/**
	 * Sends entry email notifications.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields
	 * @param array $entry
	 * @param array $form_data
	 * @param int $entry_id
	 * @param string $context
	 */
	public function entry_email( $fields, $entry, $form_data, $entry_id, $context = '' ) {

		// Check that the form was configured for email notifications.
		if (
			empty( $form_data['settings']['notification_enable'] ) ||
			'1' != $form_data['settings']['notification_enable']
		) {
			return;
		}

		// Provide the opportunity to override via a filter.
		if ( ! apply_filters( 'wpforms_entry_email', true, $fields, $entry, $form_data ) ) {
			return;
		}

		// Make sure we have and entry id.
		if ( empty( $this->entry_id ) ) {
			$this->entry_id = (int) $entry_id;
		}

		$fields = apply_filters( 'wpforms_entry_email_data', $fields, $entry, $form_data );

		// Backwards compatibility for notifications before v1.2.3.
		if ( empty( $form_data['settings']['notifications'] ) ) {
			$notifications[1] = array(
				'email'          => $form_data['settings']['notification_email'],
				'subject'        => $form_data['settings']['notification_subject'],
				'sender_name'    => $form_data['settings']['notification_fromname'],
				'sender_address' => $form_data['settings']['notification_fromaddress'],
				'replyto'        => $form_data['settings']['notification_replyto'],
				'message'        => '{all_fields}',
			);
		} else {
			$notifications = $form_data['settings']['notifications'];
		}

		foreach ( $notifications as $notification_id => $notification ) {

			if ( empty( $notification['email'] ) ) {
				continue;
			}

			$process_email = apply_filters( 'wpforms_entry_email_process', true, $fields, $form_data, $notification_id, $context );

			if ( ! $process_email ) {
				continue;
			}

			$email = array();

			// Setup email properties.
			/* translators: %s - form name. */
			$email['subject']        = ! empty( $notification['subject'] ) ? $notification['subject'] : sprintf( esc_html__( 'New %s Entry', 'wpforms' ), $form_data['settings']['form_title'] );
			$email['address']        = explode( ',', apply_filters( 'wpforms_process_smart_tags', $notification['email'], $form_data, $fields, $this->entry_id ) );
			$email['address']        = array_map( 'sanitize_email', $email['address'] );
			$email['sender_address'] = ! empty( $notification['sender_address'] ) ? $notification['sender_address'] : get_option( 'admin_email' );
			$email['sender_name']    = ! empty( $notification['sender_name'] ) ? $notification['sender_name'] : get_bloginfo( 'name' );
			$email['replyto']        = ! empty( $notification['replyto'] ) ? $notification['replyto'] : false;
			$email['message']        = ! empty( $notification['message'] ) ? $notification['message'] : '{all_fields}';
			$email                   = apply_filters( 'wpforms_entry_email_atts', $email, $fields, $entry, $form_data, $notification_id );

			// Create new email.
			$emails = new WPForms_WP_Emails();
			$emails->__set( 'form_data', $form_data );
			$emails->__set( 'fields', $fields );
			$emails->__set( 'entry_id', $this->entry_id );
			$emails->__set( 'from_name', $email['sender_name'] );
			$emails->__set( 'from_address', $email['sender_address'] );
			$emails->__set( 'reply_to', $email['replyto'] );

			// Maybe include CC.
			if ( ! empty( $notification['carboncopy'] ) && wpforms_setting( 'email-carbon-copy', false ) ) {
				$emails->__set( 'cc', $notification['carboncopy'] );
			}

			// Go.
			foreach ( $email['address'] as $address ) {
				$emails->send( trim( $address ), $email['subject'], $email['message'] );
			}
		} // End foreach().
	}

	/**
	 * Saves entry to database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields
	 * @param array $entry
	 * @param int $form_id
	 * @param array|string $form_data
	 *
	 * @return int
	 */
	public function entry_save( $fields, $entry, $form_id, $form_data = '' ) {

		do_action( 'wpforms_process_entry_save', $fields, $entry, $form_id, $form_data );

		return $this->entry_id;
	}
}

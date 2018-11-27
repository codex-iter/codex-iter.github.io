<?php
/**
 * Smart tag functionality.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */
class WPForms_Smart_Tags {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'wpforms_process_smart_tags', array( $this, 'process' ), 10, 4 );
	}

	/**
	 * Approved smart tags.
	 *
	 * @since 1.0.0
	 *
	 * @param string $return Type of data to return.
	 *
	 * @return string|array
	 */
	public function get( $return = 'array' ) {

		$tags = array(
			'admin_email'         => esc_html__( 'Site Administrator Email', 'wpforms' ),
			'entry_id'            => esc_html__( 'Entry ID', 'wpforms' ),
			'form_id'             => esc_html__( 'Form ID', 'wpforms' ),
			'form_name'           => esc_html__( 'Form Name', 'wpforms' ),
			'page_title'          => esc_html__( 'Embedded Post/Page Title', 'wpforms' ),
			'page_url'            => esc_html__( 'Embedded Post/Page URL', 'wpforms' ),
			'page_id'             => esc_html__( 'Embedded Post/Page ID', 'wpforms' ),
			'date format="m/d/Y"' => esc_html__( 'Date', 'wpforms' ),
			'query_var key=""'    => esc_html__( 'Query String Variable', 'wpforms' ),
			'user_ip'             => esc_html__( 'User IP Address', 'wpforms' ),
			'user_id'             => esc_html__( 'User ID', 'wpforms' ),
			'user_display'        => esc_html__( 'User Display Name', 'wpforms' ),
			'user_full_name'      => esc_html__( 'User Full Name', 'wpforms' ),
			'user_email'          => esc_html__( 'User Email', 'wpforms' ),
			'author_id'           => esc_html__( 'Author ID', 'wpforms' ),
			'author_display'      => esc_html__( 'Author Name', 'wpforms' ),
			'author_email'        => esc_html__( 'Author Email', 'wpforms' ),
			'url_referer'         => esc_html__( 'Referrer URL', 'wpforms' ),
			'url_login'           => esc_html__( 'Login URL', 'wpforms' ),
			'url_logout'          => esc_html__( 'Logout URL', 'wpforms' ),
			'url_register'        => esc_html__( 'Register URL', 'wpforms' ),
			'url_lost_password'   => esc_html__( 'Lost Password URL', 'wpforms' ),
		);

		$tags = apply_filters( 'wpforms_smart_tags', $tags );

		if ( 'list' === $return ) {

			// Return formatted list.
			$output = '<ul class="smart-tags-list">';
			foreach ( $tags as $key => $tag ) {
				$output .= '<li><a href="#" data-value="' . esc_attr( $key ) . '">' . esc_html( $tag ) . '</a></li>';
			}
			$output .= '</ul>';

			return $output;

		} else {

			// Return raw array.
			return $tags;
		}
	}

	/**
	 * Process and parse smart tags.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content The string to preprocess.
	 * @param array $form_data Array of the form data.
	 * @param string|array $fields Form fields.
	 * @param int|string $entry_id Entry ID.
	 *
	 * @return string
	 */
	public function process( $content, $form_data, $fields = '', $entry_id = '' ) {

		// Basic smart tags.
		preg_match_all( "/\{(.+?)\}/", $content, $tags );

		if ( ! empty( $tags[1] ) ) {

			foreach ( $tags[1] as $key => $tag ) {

				switch ( $tag ) {

					case 'admin_email':
						$content = str_replace( '{' . $tag . '}', sanitize_email( get_option( 'admin_email' ) ), $content );
						break;

					case 'entry_id':
						$content = str_replace( '{' . $tag . '}', absint( $entry_id ), $content );
						break;

					case 'form_id':
						$content = str_replace( '{' . $tag . '}', absint( $form_data['id'] ), $content );
						break;

					case 'form_name':
						if ( isset( $form_data['settings']['form_title'] ) && ! empty( $form_data['settings']['form_title'] ) ) {
							$name = $form_data['settings']['form_title'];
						} else {
							$name = '';
						}
						$content = str_replace( '{' . $tag . '}', sanitize_text_field( $name ), $content );
						break;

					case 'page_title':
						$title   = get_the_ID() ? get_the_title( get_the_ID() ) : '';
						$content = str_replace( '{' . $tag . '}', $title, $content );
						break;

					case 'page_url':
						$url     = get_the_ID() ? get_permalink( get_the_ID() ) : '';
						$content = str_replace( '{' . $tag . '}', $url, $content );
						break;

					case 'page_id':
						$id      = get_the_ID() ? get_the_ID() : '';
						$content = str_replace( '{' . $tag . '}', $id, $content );
						break;

					case 'user_ip':
						$ip      = ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
						$content = str_replace( '{' . $tag . '}', sanitize_text_field( $ip ), $content );
						break;

					case 'user_id':
						$id      = is_user_logged_in() ? get_current_user_id() : '';
						$content = str_replace( '{' . $tag . '}', $id, $content );
						break;

					case 'user_display':
						if ( is_user_logged_in() ) {
							$user = wp_get_current_user();
							$name = sanitize_text_field( $user->display_name );
						} else {
							$name = '';
						}
						$content = str_replace( '{' . $tag . '}', $name, $content );
						break;

					case 'user_full_name':
						if ( is_user_logged_in() ) {
							$user = wp_get_current_user();
							$name = sanitize_text_field( $user->user_firstname . ' ' . $user->user_lastname );
						} else {
							$name = '';
						}
						$content = str_replace( '{' . $tag . '}', $name, $content );
						break;

					case 'user_email':
						if ( is_user_logged_in() ) {
							$user  = wp_get_current_user();
							$email = sanitize_email( $user->user_email );
						} else {
							$email = '';
						}
						$content = str_replace( '{' . $tag . '}', $email, $content );
						break;

					case 'author_id':
						$id = get_the_author_meta( 'ID' );
						if ( empty( $id ) && ! empty( $_POST['wpforms']['author'] ) ) {
							$id = get_the_author_meta( 'ID', absint( $_POST['wpforms']['author'] ) );
						}
						$id      = absint( $id );
						$content = str_replace( '{' . $tag . '}', $id, $content );
						break;

					case 'author_display':
						$name = get_the_author();
						if ( empty( $name ) && ! empty( $_POST['wpforms']['author'] ) ) {
							$name = get_the_author_meta( 'display_name', absint( $_POST['wpforms']['author'] ) );
						}
						$name    = ! empty( $name ) ? sanitize_text_field( $name ) : '';
						$content = str_replace( '{' . $tag . '}', $name, $content );
						break;

					case 'author_email':
						$email = get_the_author_meta( 'user_email' );
						if ( empty( $email ) && ! empty( $_POST['wpforms']['author'] ) ) {
							$email = get_the_author_meta( 'user_email', absint( $_POST['wpforms']['author'] ) );
						}
						$email   = sanitize_email( $email );
						$content = str_replace( '{' . $tag . '}', $email, $content );
						break;

					case 'url_referer':
						$referer = ! empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
						$content = str_replace( '{' . $tag . '}', sanitize_text_field( $referer ), $content );
						break;

					case 'url_login':
						$content = str_replace( '{' . $tag . '}', wp_login_url(), $content );
						break;

					case 'url_logout':
						$content = str_replace( '{' . $tag . '}', wp_logout_url(), $content );
						break;

					case 'url_register':
						$content = str_replace( '{' . $tag . '}', wp_registration_url(), $content );
						break;

					case 'url_lost_password':
						$content = str_replace( '{' . $tag . '}', wp_lostpassword_url(), $content );
						break;

					default:
						$content = apply_filters( 'wpforms_smart_tag_process', $content, $tag );
						break;
				}
			}
		}

		// Query string var smart tags.
		preg_match_all( "/\{query_var key=\"(.+?)\"\}/", $content, $query_vars );

		if ( ! empty( $query_vars[1] ) ) {

			foreach ( $query_vars[1] as $key => $query_var ) {

				$value   = ! empty( $_GET[ $query_var ] ) ? sanitize_text_field( $_GET[ $query_var ] ) : '';
				$content = str_replace( $query_vars[0][ $key ], $value, $content );
			}
		}

		// Date smart tags.
		preg_match_all( "/\{date format=\"(.+?)\"\}/", $content, $dates );

		if ( ! empty( $dates[1] ) ) {

			foreach ( $dates[1] as $key => $date ) {

				$value   = date( $date, time() + ( get_option( 'gmt_offset' ) * 3600 ) );
				$content = str_replace( $dates[0][ $key ], $value, $content );
			}
		}

		// Field smart tags (settings, etc).
		preg_match_all( "/\{field_id=\"(.+?)\"\}/", $content, $ids );

		// We can only process field smart tags if we have $fields
		if ( ! empty( $ids[1] ) && ! empty( $fields ) ) {

			foreach ( $ids[1] as $key => $field_id ) {
				$value = ! empty( $fields[ $field_id ]['value'] ) ? wpforms_sanitize_textarea_field( $fields[ $field_id ]['value'] ) : '';

				$content = str_replace( '{field_id="' . $field_id . '"}', $value, $content );
			}
		}

		// Field value smart tags (settings, etc).
		preg_match_all( "/\{field_value_id=\"(.+?)\"\}/", $content, $value_ids );

		// We can only process field smart tags if we have $fields.
		if ( ! empty( $value_ids[1] ) && ! empty( $fields ) ) {

			foreach ( $value_ids[1] as $key => $field_id ) {

				if ( ! empty( $fields[ $field_id ]['value_raw'] ) ) {
					$value = sanitize_text_field( $fields[ $field_id ]['value_raw'] );
				} else {
					$value = ! empty( $fields[ $field_id ]['value'] ) ? sanitize_text_field( $fields[ $field_id ]['value'] ) : '';
				}

				$content = str_replace( '{field_value_id="' . $field_id . '"}', $value, $content );
			}
		}

		return $content;
	}
}

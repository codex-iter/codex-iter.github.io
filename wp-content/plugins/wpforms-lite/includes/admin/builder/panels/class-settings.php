<?php

/**
 * Settings management panel.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */
class WPForms_Builder_Panel_Settings extends WPForms_Builder_Panel {

	/**
	 * All systems go.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define panel information.
		$this->name    = esc_html__( 'Settings', 'wpforms' );
		$this->slug    = 'settings';
		$this->icon    = 'fa-sliders';
		$this->order   = 10;
		$this->sidebar = true;
	}

	/**
	 * Outputs the Settings panel sidebar.
	 *
	 * @since 1.0.0
	 */
	public function panel_sidebar() {

		// Sidebar contents are not valid unless we have a form.
		if ( ! $this->form ) {
			return;
		}

		$sections = array(
			'general'       => esc_html__( 'General', 'wpforms' ),
			'notifications' => esc_html__( 'Notifications', 'wpforms' ),
			'confirmation'  => esc_html__( 'Confirmation', 'wpforms' ),
		);
		$sections = apply_filters( 'wpforms_builder_settings_sections', $sections, $this->form_data );
		foreach ( $sections as $slug => $section ) {
			$this->panel_sidebar_section( $section, $slug );
		}
	}

	/**
	 * Outputs the Settings panel primary content.
	 *
	 * @since 1.0.0
	 */
	public function panel_content() {

		// Check if there is a form created.
		if ( ! $this->form ) {
			echo '<div class="wpforms-alert wpforms-alert-info">';
			echo wp_kses(
				__( 'You need to <a href="#" class="wpforms-panel-switch" data-panel="setup">setup your form</a> before you can manage the settings.', 'wpforms' ),
				array(
					'a' => array(
						'href'       => array(),
						'class'      => array(),
						'data-panel' => array(),
					),
				)
			);
			echo '</div>';

			return;
		}

		// --------------------------------------------------------------------//
		// General.
		// --------------------------------------------------------------------//
		echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-general">';
		echo '<div class="wpforms-panel-content-section-title">';
		esc_html_e( 'General', 'wpforms' );
		echo '</div>';
		wpforms_panel_field(
			'text',
			'settings',
			'form_title',
			$this->form_data,
			esc_html__( 'Form Name', 'wpforms' ),
			array(
				'default' => $this->form->post_title,
			)
		);
		wpforms_panel_field(
			'textarea',
			'settings',
			'form_desc',
			$this->form_data,
			esc_html__( 'Form Description', 'wpforms' )
		);
		wpforms_panel_field(
			'text',
			'settings',
			'form_class',
			$this->form_data,
			esc_html__( 'Form CSS Class', 'wpforms' ),
			array(
				'tooltip' => esc_html__( 'Enter CSS class names for the form wrapper. Multiple class names should be separated with spaces.', 'wpforms' ),
			)
		);
		wpforms_panel_field(
			'text',
			'settings',
			'submit_text',
			$this->form_data,
			esc_html__( 'Submit Button Text', 'wpforms' ),
			array(
				'default' => esc_html__( 'Submit', 'wpforms' ),
			)
		);
		wpforms_panel_field(
			'text',
			'settings',
			'submit_text_processing',
			$this->form_data,
			esc_html__( 'Submit Button Processing Text', 'wpforms' ),
			array(
				'tooltip' => esc_html__( 'Enter the submit button text you would like the button display while the form submit is processing.', 'wpforms' ),
			)
		);
		wpforms_panel_field(
			'text',
			'settings',
			'submit_class',
			$this->form_data,
			esc_html__( 'Submit Button CSS Class', 'wpforms' ),
			array(
				'tooltip' => esc_html__( 'Enter CSS class names for the form submit button. Multiple names should be separated with spaces.', 'wpforms' ),
			)
		);
		wpforms_panel_field(
			'checkbox',
			'settings',
			'honeypot',
			$this->form_data,
			esc_html__( 'Enable anti-spam honeypot', 'wpforms' )
		);
		$recaptcha_key    = wpforms_setting( 'recaptcha-site-key' );
		$recaptcha_secret = wpforms_setting( 'recaptcha-secret-key' );
		$recaptcha_type   = wpforms_setting( 'recaptcha-type' );
		if ( ! empty( $recaptcha_key ) && ! empty( $recaptcha_secret ) ) {
			wpforms_panel_field(
				'checkbox',
				'settings',
				'recaptcha',
				$this->form_data,
				'invisible' === $recaptcha_type ? esc_html__( 'Enable Google invisible reCAPTCHA', 'wpforms' ) : esc_html__( 'Enable Google reCAPTCHA (v2)', 'wpforms' )
			);
		}
		do_action( 'wpforms_form_settings_general', $this );
		echo '</div>';

		// --------------------------------------------------------------------//
		// Notifications.
		// --------------------------------------------------------------------//
		echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-notifications">';
		do_action( 'wpforms_form_settings_notifications', $this );
		echo '</div>';

		// --------------------------------------------------------------------//
		// Confirmations.
		// --------------------------------------------------------------------//
		echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-confirmation">';
		do_action( 'wpforms_form_settings_confirmations', $this );
		echo '</div>';

		do_action( 'wpforms_form_settings_panel_content', $this );
	}
}

new WPForms_Builder_Panel_Settings;

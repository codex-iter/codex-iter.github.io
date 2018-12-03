<?php

namespace WPForms\Integrations\Gutenberg;

use WPForms\Integrations\IntegrationInterface;

/**
 * Form Selector Gutenberg block with live preview.
 *
 * @package    WPForms\Integrations\Gutenberg
 * @author     WPForms
 * @since      1.4.8
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2018, WPForms LLC
 */
class FormSelector implements IntegrationInterface {

	/**
	 * Indicates if current integration is allowed to load.
	 *
	 * @since 1.4.8
	 *
	 * @return bool
	 */
	public function allow_load() {
		return \function_exists( 'register_block_type' );
	}

	/**
	 * Loads an integration.
	 *
	 * @since 1.4.8
	 */
	public function load() {
		$this->hooks();
	}

	/**
	 * Integration hooks.
	 *
	 * @since 1.4.8
	 */
	protected function hooks() {

		\add_action( 'init', array( $this, 'register_block' ) );
		\add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
	}

	/**
	 * Register WPForms Gutenberg block on the backend.
	 *
	 * @since 1.4.8
	 */
	public function register_block() {

		// Load CSS per global setting.
		if ( \wpforms_setting( 'disable-css', '1' ) === '1' ) {
			\wp_register_style(
				'wpforms-gutenberg-form-selector',
				WPFORMS_PLUGIN_URL . 'assets/css/wpforms-full.css',
				array( 'wp-edit-blocks' ),
				WPFORMS_VERSION
			);
		}

		if ( \wpforms_setting( 'disable-css', '1' ) === '2' ) {
			\wp_register_style(
				'wpforms-gutenberg-form-selector',
				WPFORMS_PLUGIN_URL . 'assets/css/wpforms-base.css',
				array( 'wp-edit-blocks' ),
				WPFORMS_VERSION
			);
		}

		\register_block_type( 'wpforms/form-selector', array(
			'attributes'      => array(
				'formId'       => array(
					'type' => 'string',
				),
				'displayTitle' => array(
					'type' => 'boolean',
				),
				'displayDesc'  => array(
					'type' => 'boolean',
				),
			),
			'editor_style'    => 'wpforms-gutenberg-form-selector',
			'render_callback' => array( $this, 'get_form_html' ),
		) );
	}

	/**
	 * Load WPForms Gutenberg block scripts.
	 *
	 * @since 1.4.8
	 */
	public function enqueue_block_editor_assets() {

		$i18n = array(
			'title'            => \esc_html__( 'WPForms', 'wpforms-lite' ),
			'description'      => \esc_html__( 'Select and display one of your forms.', 'wpforms-lite' ),
			'form_select'      => \esc_html__( 'Select a Form', 'wpforms-lite' ),
			'form_settings'    => \esc_html__( 'Form Settings', 'wpforms-lite' ),
			'form_selected'    => \esc_html__( 'Form', 'wpforms-lite' ),
			'show_title'       => \esc_html__( 'Show Title', 'wpforms-lite' ),
			'show_description' => \esc_html__( 'Show Description', 'wpforms-lite' ),
		);

		\wp_enqueue_script(
			'wpforms-gutenberg-form-selector',
			WPFORMS_PLUGIN_URL . 'assets/js/components/admin/gutenberg/formselector.min.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			WPFORMS_VERSION,
			true
		);

		$forms = \wpforms()->form->get( '', array( 'order' => 'DESC' ) );

		\wp_localize_script(
			'wpforms-gutenberg-form-selector',
			'wpforms_gutenberg_form_selector',
			array(
				'logo_url' => WPFORMS_PLUGIN_URL . 'assets/images/sullie-vc.png',
				'wpnonce'  => \wp_create_nonce( 'wpforms-gutenberg-form-selector' ),
				'forms'    => ! empty( $forms ) ? $forms : array(),
				'i18n'     => $i18n,
			)
		);
	}

	/**
	 * Get form HTML to display in a WPForms Gutenberg block.
	 *
	 * @param array $attr Attributes passed by WPForms Gutenberg block.
	 *
	 * @since 1.4.8
	 *
	 * @return string
	 */
	public function get_form_html( $attr ) {

		$id = ! empty( $attr['formId'] ) ? \absint( $attr['formId'] ) : 0;

		if ( empty( $id ) ) {
			return '';
		}

		$is_gb_editor = \defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];

		$title = ! empty( $attr['displayTitle'] ) ? true : false;
		$desc  = ! empty( $attr['displayDesc'] ) ? true : false;

		// Disable form fields if called from the Gutenberg editor.
		if ( $is_gb_editor ) {
			\add_filter( 'wpforms_frontend_container_class', function ( $classes ) {
				$classes[] = 'wpforms-gutenberg-form-selector';
				return $classes;
			} );
			\add_action( 'wpforms_frontend_output', function () {
				echo '<fieldset disabled>';
			}, 3 );
			\add_action( 'wpforms_frontend_output', function () {
				echo '</fieldset>';
			}, 30 );
		}

		\ob_start();
		\wpforms_display( $id, $title, $desc );

		return \ob_get_clean();
	}
}

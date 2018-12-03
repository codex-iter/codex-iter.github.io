<?php

/**
 * Fields management panel.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */
class WPForms_Builder_Panel_Fields extends WPForms_Builder_Panel {

	/**
	 * All systems go.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define panel information.
		$this->name    = esc_html__( 'Fields', 'wpforms-lite' );
		$this->slug    = 'fields';
		$this->icon    = 'fa-list-alt';
		$this->order   = 10;
		$this->sidebar = true;

		if ( $this->form ) {
			add_action( 'wpforms_builder_fields', array( $this, 'fields' ) );
			add_action( 'wpforms_builder_fields_options', array( $this, 'fields_options' ) );
			add_action( 'wpforms_builder_preview', array( $this, 'preview' ) );

			// Template for form builder previews.
			add_action( 'wpforms_builder_print_footer_scripts', array( $this, 'field_preview_templates' ) );
		}
	}

	/**
	 * Enqueue assets for the Fields panel.
	 *
	 * @since 1.0.0
	 */
	public function enqueues() {

		// CSS.
		wp_enqueue_style(
			'wpforms-builder-fields',
			WPFORMS_PLUGIN_URL . 'assets/css/admin-builder-fields.css',
			null,
			WPFORMS_VERSION
		);
	}

	/**
	 * Outputs the Field panel sidebar.
	 *
	 * @since 1.0.0
	 */
	public function panel_sidebar() {

		// Sidebar contents are not valid unless we have a form.
		if ( ! $this->form ) {
			return;
		}
		?>
		<ul class="wpforms-tabs wpforms-clear">

			<li class="wpforms-tab" id="add-fields">
				<a href="#" class="active">
					<?php esc_html_e( 'Add Fields', 'wpforms-lite' ); ?>
					<i class="fa fa-angle-down"></i>
				</a>
			</li>

			<li class="wpforms-tab" id="field-options">
				<a href="#">
					<?php esc_html_e( 'Field Options', 'wpforms-lite' ); ?>
					<i class="fa fa-angle-right"></i>
				</a>
			</li>

		</ul>

		<div class="wpforms-add-fields wpforms-tab-content">
			<?php do_action( 'wpforms_builder_fields', $this->form ); ?>
		</div>

		<div id="wpforms-field-options" class="wpforms-field-options wpforms-tab-content">
			<?php do_action( 'wpforms_builder_fields_options', $this->form ); ?>
		</div>
		<?php
	}

	/**
	 * Outputs the Field panel primary content.
	 *
	 * @since 1.0.0
	 */
	public function panel_content() {

		// Check if there is a form created.
		if ( ! $this->form ) {
			echo '<div class="wpforms-alert wpforms-alert-info">';
			echo wp_kses(
				__( 'You need to <a href="#" class="wpforms-panel-switch" data-panel="setup">setup your form</a> before you can manage the fields.', 'wpforms-lite' ),
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

		$recaptcha = wpforms_setting( 'recaptcha-type', 'v2' );
		?>

		<div class="wpforms-preview-wrap">

			<div class="wpforms-preview">

				<div class="wpforms-title-desc">
					<h2 class="wpforms-form-name"><?php echo esc_html( $this->form->post_title ); ?></h2>
					<span class="wpforms-form-desc"><?php echo $this->form->post_excerpt; ?></span>
				</div>

				<div class="wpforms-field-wrap">
					<?php do_action( 'wpforms_builder_preview', $this->form ); ?>
				</div>

				<?php if ( 'invisible' !== $recaptcha ) : ?>
				<p class="wpforms-field-recaptcha">
					<img src="<?php echo WPFORMS_PLUGIN_URL; ?>/assets/images/recaptcha-placeholder.png" style="max-width: 304px;">
				</p>
				<?php endif; ?>

				<?php
				$submit = ! empty( $this->form_data['settings']['submit_text'] ) ? $this->form_data['settings']['submit_text'] : esc_html__( 'Submit', 'wpforms-lite' );
				printf( '<p class="wpforms-field-submit"><input type="submit" value="%s" class="wpforms-field-submit-button"></p>', esc_attr( $submit ) );
				?>

				<?php wpforms_debug_data( $this->form_data ); ?>
			</div>

		</div>

		<?php
	}

	/**
	 * Builder field buttons.
	 *
	 * @since 1.0.0
	 */
	public function fields() {

		$fields = array(
			'standard' => array(
				'group_name' => esc_html__( 'Standard Fields', 'wpforms-lite' ),
				'fields'     => array(),
			),
			'fancy'    => array(
				'group_name' => esc_html__( 'Fancy Fields', 'wpforms-lite' ),
				'fields'     => array(),
			),
			'payment'  => array(
				'group_name' => esc_html__( 'Payment Fields', 'wpforms-lite' ),
				'fields'     => array(),
			),
		);
		$fields = apply_filters( 'wpforms_builder_fields_buttons', $fields );

		// Output the buttons.
		foreach ( $fields as $id => $group ) {

			usort( $group['fields'], array( $this, 'field_order' ) );

			echo '<div class="wpforms-add-fields-group">';

			echo '<a href="#" class="wpforms-add-fields-heading" data-group="' . esc_attr( $id ) . '">';

			echo '<span>' . esc_html( $group['group_name'] ) . '</span>';

			echo '<i class="fa fa-angle-down"></i>';

			echo '</a>';

			echo '<div class="wpforms-add-fields-buttons">';

			foreach ( $group['fields'] as $field ) {

				$class = ! empty( $field['class'] ) ? sanitize_html_class( $field['class'] ) : '';

				echo '<button class="wpforms-add-fields-button ' . $class . '" id="wpforms-add-fields-' . esc_attr( $field['type'] ) . '" data-field-type="' . esc_attr( $field['type'] ) . '">';
				if ( $field['icon'] ) {
					echo '<i class="fa ' . esc_attr( $field['icon'] ) . '"></i> ';
				}
				echo esc_html( $field['name'] );
				echo '</button>';
			}

			echo '</div>';

			echo '</div>';
		}
	}

	/**
	 * Editor Field Options.
	 *
	 * @since 1.0.0
	 */
	public function fields_options() {

		// Check to make sure the form actually has fields created already.
		if ( empty( $this->form_data['fields'] ) ) {
			printf( '<p class="no-fields">%s</p>', esc_html__( 'You don\'t have any fields yet.', 'wpforms-lite' ) );

			return;
		}

		$fields = $this->form_data['fields'];

		foreach ( $fields as $field ) {

			$class = apply_filters( 'wpforms_builder_field_option_class', '', $field );

			printf( '<div class="wpforms-field-option wpforms-field-option-%s %s" id="wpforms-field-option-%d" data-field-id="%d">', esc_attr( $field['type'] ), $class, $field['id'], $field['id'] );

			printf( '<input type="hidden" name="fields[%d][id]" value="%d" class="wpforms-field-option-hidden-id">', $field['id'], $field['id'] );

			printf( '<input type="hidden" name="fields[%d][type]" value="%s" class="wpforms-field-option-hidden-type">', $field['id'], esc_attr( $field['type'] ) );

			do_action( "wpforms_builder_fields_options_{$field['type']}", $field );

			echo '</div>';
		}
	}

	/**
	 * Editor preview (right pane).
	 *
	 * @since 1.0.0
	 */
	public function preview() {

		// Check to make sure the form actually has fields created already.
		if ( empty( $this->form_data['fields'] ) ) {
			printf( '<p class="no-fields-preview">%s</p>', esc_html__( 'You don\'t have any fields yet. Add some!', 'wpforms-lite' ) );

			return;
		}

		$fields = $this->form_data['fields'];

		foreach ( $fields as $field ) {

			$css  = ! empty( $field['size'] ) ? 'size-' . esc_attr( $field['size'] ) : '';
			$css .= ! empty( $field['label_hide'] ) && $field['label_hide'] == '1' ? ' label_hide' : '';
			$css .= ! empty( $field['sublabel_hide'] ) && $field['sublabel_hide'] == '1' ? ' sublabel_hide' : '';
			$css .= ! empty( $field['required'] ) && $field['required'] == '1' ? ' required' : '';
			$css .= ! empty( $field['input_columns'] ) && $field['input_columns'] === '2' ? ' wpforms-list-2-columns' : '';
			$css .= ! empty( $field['input_columns'] ) && $field['input_columns'] === '3' ? ' wpforms-list-3-columns' : '';
			$css .= ! empty( $field['input_columns'] ) && $field['input_columns'] === 'inline' ? ' wpforms-list-inline' : '';
			$css .= isset( $field['meta']['delete'] ) && $field['meta']['delete'] === false ? ' no-delete' : '';

			$css = apply_filters( 'wpforms_field_preview_class', $css, $field );

			printf( '<div class="wpforms-field wpforms-field-%s %s" id="wpforms-field-%d" data-field-id="%d" data-field-type="%s">', $field['type'], $css, $field['id'], $field['id'], $field['type'] );

			printf( '<a href="#" class="wpforms-field-duplicate" title="%s"><i class="fa fa-files-o" aria-hidden="true"></i></a>', esc_html__( 'Duplicate Field', 'wpforms-lite' ) );

			printf( '<a href="#" class="wpforms-field-delete" title="%s"><i class="fa fa-times-circle" aria-hidden="true"></i></a>', esc_html__( 'Delete Field', 'wpforms-lite' ) );

			printf( '<span class="wpforms-field-helper">%s</span>', esc_html__( 'Click to edit. Drag to reorder.', 'wpforms-lite' ) );

			do_action( "wpforms_builder_fields_previews_{$field['type']}", $field );

			echo '</div>';
		}
	}

	/**
	 * Sort Add Field buttons by order provided.
	 *
	 * @since 1.0.0
	 *
	 * @param array $a
	 * @param array $b
	 *
	 * @return array
	 */
	public function field_order( $a, $b ) {
		return $a['order'] - $b['order'];
	}

	/**
	 * Template for form builder preview.
	 *
	 * @since 1.4.5
	 */
	public function field_preview_templates() {

		// Checkbox, Radio, and Payment Multiple field choices.
		?>
		<script type="text/html" id="tmpl-wpforms-field-preview-checkbox-radio-payment-multiple">
			<# if ( data.settings.choices_images ) { #>
			<ul class="primary-input wpforms-image-choices wpforms-image-choices-{{ data.settings.choices_images_style }}">
				<# _.each( data.order, function( choiceID, key ) {  #>
				<li class="wpforms-image-choices-item<# if ( 1 === data.settings.choices[choiceID].default ) { print( ' wpforms-selected' ); } #>">
					<label>
						<span class="wpforms-image-choices-image">
							<# if ( ! _.isEmpty( data.settings.choices[choiceID].image ) ) { #>
							<img src="{{ data.settings.choices[choiceID].image }}" alt="{{ data.settings.choices[choiceID].label }}"<# if ( data.settings.choices[choiceID].label ) { print( ' title="{{ data.settings.choices[choiceID].label }}"' ); } #>>
							<# } else { #>
							<img src="{{ wpforms_builder.image_placeholder }}" alt="{{ data.settings.choices[choiceID].label }}"<# if ( data.settings.choices[choiceID].label ) { print( ' title="{{ data.settings.choices[choiceID].label }}"' ); } #>>
							<# } #>
						</span>
						<# if ( 'none' === data.settings.choices_images_style ) { #>
							<br>
							<input type="{{ data.type }}" disabled<# if ( 1 === data.settings.choices[choiceID].default ) { print( ' checked' ); } #>>
						<# } else { #>
							<input class="wpforms-screen-reader-element" type="{{ data.type }}" disabled<# if ( 1 === data.settings.choices[choiceID].default ) { print( ' checked' ); } #>>
						<# } #>
						<span class="wpforms-image-choices-label">{{{ data.settings.choices[choiceID].label }}}</span>
					</label>
				</li>
				<# }) #>
			</ul>
			<# } else { #>
			<ul class="primary-input">
				<# _.each( data.order, function( choiceID, key ) {  #>
				<li>
					<input type="{{ data.type }}" disabled<# if ( 1 === data.settings.choices[choiceID].default ) { print( ' checked' ); } #>>{{{ data.settings.choices[choiceID].label }}}
				</li>
				<# }) #>
			</ul>
			<# } #>
		</script>
		<?php
	}

}

new WPForms_Builder_Panel_Fields;

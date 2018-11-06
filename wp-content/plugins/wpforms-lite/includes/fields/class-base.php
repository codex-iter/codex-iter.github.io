<?php
/**
 * Base field template.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */
abstract class WPForms_Field {

	/**
	 * Full name of the field type, eg "Paragraph Text".
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $name;

	/**
	 * Type of the field, eg "textarea".
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $type;

	/**
	 * Font Awesome Icon used for the editor button, eg "fa-list".
	 *
	 * @since 1.0.0
	 * @var mixed
	 */
	public $icon = false;

	/**
	 * Priority order the field button should show inside the "Add Fields" tab.
	 *
	 * @since 1.0.0
	 * @var integer
	 */
	public $order = 20;

	/**
	 * Field group the field belongs to.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $group = 'standard';

	/**
	 * Placeholder to hold default value(s) for some field types.
	 *
	 * @since 1.0.0
	 * @var mixed
	 */
	public $defaults;

	/**
	 * Current form ID in the admin builder.
	 *
	 * @since 1.1.1
	 * @var mixed, int or false
	 */
	public $form_id;

	/**
	 * Current form data in admin builder.
	 *
	 * @since 1.1.1
	 * @var mixed, int or false
	 */
	public $form_data;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $init
	 */
	public function __construct( $init = true ) {

		if ( ! $init ) {
			return;
		}

		// The form ID is to be accessed in the builder.
		$this->form_id = isset( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : false;

		// Bootstrap.
		$this->init();

		// Add fields tab.
		add_filter( 'wpforms_builder_fields_buttons', array( $this, 'field_button' ), 15 );

		// Field options tab.
		add_action( "wpforms_builder_fields_options_{$this->type}", array( $this, 'field_options' ), 10 );

		// Preview fields.
		add_action( "wpforms_builder_fields_previews_{$this->type}", array( $this, 'field_preview' ), 10 );

		// AJAX Add new field.
		add_action( "wp_ajax_wpforms_new_field_{$this->type}", array( $this, 'field_new' ) );

		// Display field input elements on front-end.
		add_action( "wpforms_display_field_{$this->type}", array( $this, 'field_display' ), 10, 3 );

		// Validation on submit.
		add_action( "wpforms_process_validate_{$this->type}", array( $this, 'validate' ), 10, 3 );

		// Format.
		add_action( "wpforms_process_format_{$this->type}", array( $this, 'format' ), 10, 3 );
	}

	/**
	 * All systems go. Used by subclasses.
	 *
	 * @since 1.0.0
	 */
	public function init() {
	}

	/**
	 * Create the button for the 'Add Fields' tab, inside the form editor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	public function field_button( $fields ) {

		// Add field information to fields array.
		$fields[ $this->group ]['fields'][] = array(
			'order' => $this->order,
			'name'  => $this->name,
			'type'  => $this->type,
			'icon'  => $this->icon,
		);

		// Wipe hands clean.
		return $fields;
	}

	/**
	 * Creates the field options panel. Used by subclasses.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 */
	public function field_options( $field ) {
	}

	/**
	 * Creates the field preview. Used by subclasses.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 */
	public function field_preview( $field ) {
	}

	/**
	 * Helper function to create field option elements.
	 *
	 * Field option elements are pieces that help create a field option.
	 * They are used to quickly build field options.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option
	 * @param array $field
	 * @param array $args
	 * @param boolean $echo
	 *
	 * @return mixed echo or return string
	 */
	public function field_element( $option, $field, $args = array(), $echo = true ) {

		$id     = (int) $field['id'];
		$class  = ! empty( $args['class'] ) ? sanitize_html_class( $args['class'] ) : '';
		$slug   = ! empty( $args['slug'] ) ? sanitize_title( $args['slug'] ) : '';
		$data   = '';
		$output = '';

		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $val ) {
				if ( is_array( $val ) ) {
					$val = wp_json_encode( $val );
				}
				$data .= ' data-' . $key . '=\'' . $val . '\'';
			}
		}

		switch ( $option ) {

			// Row.
			case 'row':
				$output = sprintf( '<div class="wpforms-field-option-row wpforms-field-option-row-%s %s" id="wpforms-field-option-row-%d-%s" data-field-id="%d">%s</div>', $slug, $class, $id, $slug, $id, $args['content'] );
				break;

			// Label.
			case 'label':
				$output = sprintf( '<label for="wpforms-field-option-%d-%s">%s', $id, $slug, esc_html( $args['value'] ) );
				if ( isset( $args['tooltip'] ) && ! empty( $args['tooltip'] ) ) {
					$output .= ' ' . sprintf( '<i class="fa fa-question-circle wpforms-help-tooltip" title="%s"></i>', esc_attr( $args['tooltip'] ) );
				}
				if ( isset( $args['after_tooltip'] ) && ! empty( $args['after_tooltip'] ) ) {
					$output .= $args['after_tooltip'];
				}
				$output .= '</label>';
				break;

			// Text input.
			case 'text':
				$type        = ! empty( $args['type'] ) ? esc_attr( $args['type'] ) : 'text';
				$placeholder = ! empty( $args['placeholder'] ) ? esc_attr( $args['placeholder'] ) : '';
				$before      = ! empty( $args['before'] ) ? '<span class="before-input">' . esc_html( $args['before'] ) . '</span>' : '';
				if ( ! empty( $before ) ) {
					$class .= ' has-before';
				}
				$output      = sprintf( '%s<input type="%s" class="%s" id="wpforms-field-option-%d-%s" name="fields[%d][%s]" value="%s" placeholder="%s" %s>', $before, $type, $class, $id, $slug, $id, $slug, esc_attr( $args['value'] ), $placeholder, $data );
				break;

			// Textarea.
			case 'textarea':
				$rows   = ! empty( $args['rows'] ) ? (int) $args['rows'] : '3';
				$output = sprintf( '<textarea class="%s" id="wpforms-field-option-%d-%s" name="fields[%d][%s]" rows="%d" %s>%s</textarea>', $class, $id, $slug, $id, $slug, $rows, $data, $args['value'] );
				break;

			// Checkbox.
			case 'checkbox':
				$checked = checked( '1', $args['value'], false );
				$output  = sprintf( '<input type="checkbox" class="%s" id="wpforms-field-option-%d-%s" name="fields[%d][%s]" value="1" %s %s>', $class, $id, $slug, $id, $slug, $checked, $data );
				$output .= sprintf( '<label for="wpforms-field-option-%d-%s" class="inline">%s', $id, $slug, $args['desc'] );
				if ( isset( $args['tooltip'] ) && ! empty( $args['tooltip'] ) ) {
					$output .= ' ' . sprintf( '<i class="fa fa-question-circle wpforms-help-tooltip" title="%s"></i>', esc_attr( $args['tooltip'] ) );
				}
				$output .= '</label>';
				break;

			// Toggle.
			case 'toggle':
				$checked = checked( '1', $args['value'], false );
				$icon    = $args['value'] ? 'fa-toggle-on' : 'fa-toggle-off';
				$cls     = $args['value'] ? 'wpforms-on' : 'wpforms-off';
				$status  = $args['value'] ? esc_html__( 'On', 'wpforms' ) : esc_html__( 'Off', 'wpforms' );
				$output  = sprintf( '<span class="wpforms-toggle-icon %s"><i class="fa %s" aria-hidden="true"></i> <span class="wpforms-toggle-icon-label">%s</span>', $cls, $icon, $status );
				$output .= sprintf( '<input type="checkbox" class="%s" id="wpforms-field-option-%d-%s" name="fields[%d][%s]" value="1" %s %s></span>', $class, $id, $slug, $id, $slug, $checked, $data );
				break;

			// Select.
			case 'select':
				$options = $args['options'];
				$value   = isset( $args['value'] ) ? $args['value'] : '';
				$output  = sprintf( '<select class="%s" id="wpforms-field-option-%d-%s" name="fields[%d][%s]" %s>', $class, $id, $slug, $id, $slug, $data );
				foreach ( $options as $key => $option ) {
					$output .= sprintf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $key, $value, false ), $option );
				}
				$output .= '</select>';
				break;
		} // End switch().

		if ( $echo ) {
			echo $output; // WPCS: XSS ok.
		} else {
			return $output;
		}
	}

	/**
	 * Helper function to create common field options that are used frequently.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option
	 * @param array $field
	 * @param array $args
	 * @param boolean $echo
	 *
	 * @return mixed echo or return string
	 */
	public function field_option( $option, $field, $args = array(), $echo = true ) {

		switch ( $option ) {

			// --------------------------------------------------------------//
			// Basic Fields.
			// --------------------------------------------------------------//

			// Basic Options markup. ------------------------------------------//

			case 'basic-options':
				$markup = ! empty( $args['markup'] ) ? $args['markup'] : 'open';
				$class  = ! empty( $args['class'] ) ? esc_html( $args['class'] ) : '';
				if ( 'open' === $markup ) {
					$output   = sprintf( '<div class="wpforms-field-option-group wpforms-field-option-group-basic" id="wpforms-field-option-basic-%d">', $field['id'] );
					$output  .= sprintf( '<a href="#" class="wpforms-field-option-group-toggle">%s <span>(ID #%d)</span> <i class="fa fa-angle-down"></i></a>', $this->name, $field['id'] );
					$output  .= sprintf( '<div class="wpforms-field-option-group-inner %s">', $class );
				} else {
					$output   = '</div></div>';
				}
				break;

			// Field Label. ---------------------------------------------------//

			case 'label':
				$value   = ! empty( $field['label'] ) ? esc_attr( $field['label'] ) : '';
				$tooltip = esc_html__( 'Enter text for the form field label. Field labels are recommended and can be hidden in the Advanced Settings.', 'wpforms' );
				$output  = $this->field_element( 'label', $field, array( 'slug' => 'label', 'value' => esc_html__( 'Label', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output .= $this->field_element( 'text',  $field, array( 'slug' => 'label', 'value' => $value ), false );
				$output  = $this->field_element( 'row',   $field, array( 'slug' => 'label', 'content' => $output ), false );
				break;

			// Field Description. ---------------------------------------------//

			case 'description':
				$value   = ! empty( $field['description'] ) ? esc_attr( $field['description'] ) : '';
				$tooltip = esc_html__( 'Enter text for the form field description.', 'wpforms' );
				$output  = $this->field_element( 'label',    $field, array( 'slug' => 'description', 'value' => esc_html__( 'Description', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output .= $this->field_element( 'textarea', $field, array( 'slug' => 'description', 'value' => $value ), false );
				$output  = $this->field_element( 'row',      $field, array( 'slug' => 'description', 'content' => $output ), false );
				break;

			// Field Required toggle. -----------------------------------------//

			case 'required':
				$default = ! empty( $args['default'] ) ? $args['default'] : '0';
				$value   = isset( $field['required'] ) ? $field['required'] : $default;
				$tooltip = esc_html__( 'Check this option to mark the field required. A form will not submit unless all required fields are provided.', 'wpforms' );
				$output  = $this->field_element( 'checkbox', $field, array( 'slug' => 'required', 'value' => $value, 'desc' => esc_html__( 'Required', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output  = $this->field_element( 'row',      $field, array( 'slug' => 'required', 'content' => $output ), false );
				break;

			// Field Meta (field type and ID). --------------------------------//

			case 'meta':
				$output  = sprintf( '<label>%s</label>', 'Type' );
				$output .= sprintf( '<p class="meta">%s <span class="id">(ID #%d)</span></p>', $this->name, $field['id'] );
				$output  = $this->field_element( 'row', $field, array( 'slug' => 'meta', 'content' => $output ), false );
				break;

			// Code Block. ----------------------------------------------------//

			case 'code':
				$value   = ! empty( $field['code'] ) ? esc_textarea( $field['code'] ) : '';
				$tooltip = esc_html__( 'Enter code for the form field.', 'wpforms' );
				$output  = $this->field_element( 'label',    $field, array( 'slug' => 'code', 'value' => esc_html__( 'Code', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output .= $this->field_element( 'textarea', $field, array( 'slug' => 'code', 'value' => $value ), false );
				$output  = $this->field_element( 'row',      $field, array( 'slug' => 'code', 'content' => $output ), false );
				break;

			// Choices. ------------------------------------------------------//

			case 'choices':
				$values  = ! empty( $field['choices'] ) ? $field['choices'] : $this->defaults;
				$label   = ! empty( $args['label'] ) ? esc_html( $args['label'] ) : esc_html__( 'Choices', 'wpforms' );
				$class   = array();

				if ( ! empty( $field['show_values'] ) ) {
					$class[] = 'show-values';
				}
				if ( ! empty( $field['dynamic_choices'] ) ) {
					$class[] = 'wpforms-hidden';
				}
				if ( ! empty( $field['choices_images'] ) ) {
					$class[] = 'show-images';
				}

				// Field label.
				$lbl = $this->field_element(
					'label',
					$field,
					array(
						'slug'          => 'choices',
						'value'         => $label,
						'tooltip'       => esc_html__( 'Add choices for the form field.', 'wpforms' ),
						'after_tooltip' => '<a href="#" class="toggle-bulk-add-display"><i class="fa fa-download"></i> <span>' . esc_html__( 'Bulk Add', 'wpforms' ) . '</span></a>',
					),
					false
				);

				// Field contents.
				$fld = sprintf( '<ul data-next-id="%s" class="choices-list %s" data-field-id="%d" data-field-type="%s">',
					max( array_keys( $values ) ) + 1,
					wpforms_sanitize_classes( $class, true ),
					$field['id'],
					$this->type
				);
				foreach ( $values as $key => $value ) {
					$default   = ! empty( $value['default'] ) ? $value['default'] : '' ;
					$base      =  sprintf( 'fields[%s][choices][%s]', $field['id'], $key );
					$image     = ! empty( $value['image'] ) ? $value['image'] : '';
					$image_btn = '';

					$fld .= '<li data-key="' . absint( $key ) . '">';
					$fld .= sprintf( '<input type="%s" name="%s[default]" class="default" value="1" %s>',
						'checkbox' === $this->type ? 'checkbox' : 'radio',
						$base,
						checked( '1', $default, false )
					);
					$fld .= '<span class="move"><i class="fa fa-bars"></i></span>';
					$fld .= sprintf( '<input type="text" name="%s[label]" value="%s" class="label">',
						$base,
						esc_attr( $value['label'] )
					);
					$fld .= '<a class="add" href="#"><i class="fa fa-plus-circle"></i></a><a class="remove" href="#"><i class="fa fa-minus-circle"></i></a>';
					$fld .= sprintf( '<input type="text" name="%s[value]" value="%s" class="value">',
						$base,
						esc_attr( $value['value'] )
					);
					$fld .= '<div class="wpforms-image-upload">';
					$fld .= '<div class="preview">';
					if ( ! empty( $image ) ) {
						$fld .= sprintf( '<a href="#" title="%s" class="wpforms-image-upload-remove"><img src="%s"></a>',
							esc_attr__( 'Remove Image', 'wpforms' ),
							esc_url_raw( $image )
						);
						$image_btn = ' style="display:none;"';
					}
					$fld .= '</div>';
					$fld .= sprintf( '<button class="wpforms-btn wpforms-btn-md wpforms-btn-light-grey wpforms-btn-block wpforms-image-upload-add" data-after-upload="hide"%s>%s</button>',
						$image_btn,
						esc_html__( 'Upload Image', 'wpforms' )
					);
					$fld .= sprintf( '<input type="hidden" name="%s[image]" value="%s" class="source">',
						$base,
						esc_url_raw( $image )
					);
					$fld .= '</div>';
					$fld .= '</li>';
				}
				$fld .= '</ul>';

				// Field note: dynamic status.
				$source  = '';
				$type    = '';
				$dynamic = ! empty( $field['dynamic_choices'] ) ? esc_html( $field['dynamic_choices'] ) : '';

				if ( 'post_type' === $dynamic && ! empty( $field[ 'dynamic_' . $dynamic ] ) ) {
					$type   = esc_html__( 'post type', 'wpforms' );
					$pt     = get_post_type_object( $field[ 'dynamic_' . $dynamic ] );
					$source = $pt->labels->name;
				} elseif ( 'taxonomy' === $dynamic && ! empty( $field[ 'dynamic_' . $dynamic ] ) ) {
					$type   = esc_html__( 'taxonomy', 'wpforms' );
					$tax    = get_taxonomy( $field[ 'dynamic_' . $dynamic ] );
					$source = $tax->labels->name;
				}

				$note = sprintf( '<div class="wpforms-alert-warning wpforms-alert-small wpforms-alert %s">',
					empty( $dynamic ) && ! empty( $field[ 'dynamic_' . $dynamic ] ) ? '' : 'wpforms-hidden'
				);
				/* translators: %1$s - source name; %2$s - type name. */
				$note .= sprintf(
					esc_html__(
						'Choices are dynamically populated from the %1$s %2$s.',
						'wpforms'
					),
					'<span class="dynamic-name">' . $source . '</span>',
					'<span class="dynamic-type">' . $type . '</span>'
				);
				$note .= '</div>';

				// Final field output.
				$output = $this->field_element(
					'row',
					$field,
					array(
						'slug'    => 'choices',
						'content' => $lbl . $fld . $note,
					),
					false
				);
				break;

			// Choices for payments. -----------------------------------------//

			case 'choices_payments':
				$values = ! empty( $field['choices'] ) ? $field['choices'] : $this->defaults;
				$class  = array();

				if ( ! empty( $field['choices_images'] ) ) {
					$class[] = 'show-images';
				}

				// Field label.
				$lbl = $this->field_element(
					'label',
					$field,
					array(
						'slug'    => 'choices',
						'value'   => esc_html__( 'Items', 'wpforms' ),
						'tooltip' =>  esc_html__( 'Add choices for the form field.', 'wpforms' ),
					),
					false
				);

				// Field contents.
				$fld = sprintf( '<ul data-next-id="%s" class="choices-list %s" data-field-id="%d" data-field-type="%s">',
					max( array_keys( $values ) ) + 1,
					wpforms_sanitize_classes( $class, true ),
					$field['id'],
					$this->type
				);
				foreach ( $values as $key => $value ) {
					$default   = ! empty( $value['default'] ) ? $value['default'] : '' ;
					$base      =  sprintf( 'fields[%s][choices][%s]', $field['id'], $key );
					$image     = ! empty( $value['image'] ) ? $value['image'] : '';
					$image_btn = '';

					$fld .= '<li data-key="' . absint( $key ) . '">';
					$fld .= sprintf( '<input type="radio" name="%s[default]" class="default" value="1" %s>',
						$base,
						checked( '1', $default, false )
					);
					$fld .= '<span class="move"><i class="fa fa-bars"></i></span>';
					$fld .= sprintf( '<input type="text" name="%s[label]" value="%s" class="label">',
						$base,
						esc_attr( $value['label'] )
					);
					$fld .= sprintf( '<input type="text" name="%s[value]" value="%s" class="value value wpforms-money-input" placeholder="%s">',
						$base,
						esc_attr( $value['value'] ),
						wpforms_format_amount( 0 )
					);
					$fld .= '<a class="add" href="#"><i class="fa fa-plus-circle"></i></a><a class="remove" href="#"><i class="fa fa-minus-circle"></i></a>';
					$fld .= '<div class="wpforms-image-upload">';
					$fld .= '<div class="preview">';
					if ( ! empty( $image ) ) {
						$fld .= sprintf( '<a href="#" title="%s" class="wpforms-image-upload-remove"><img src="%s"></a>',
							esc_attr__( 'Remove Image', 'wpforms' ),
							esc_url_raw( $image )
						);
						$image_btn = ' style="display:none;"';
					}
					$fld .= '</div>';
					$fld .= sprintf( '<button class="wpforms-btn wpforms-btn-md wpforms-btn-light-grey wpforms-btn-block wpforms-image-upload-add" data-after-upload="hide"%s>%s</button>',
						$image_btn,
						esc_html__( 'Upload Image', 'wpforms' )
					);
					$fld .= sprintf( '<input type="hidden" name="%s[image]" value="%s" class="source">',
						$base,
						esc_url_raw( $image )
					);
					$fld .= '</div>';
					$fld .= '</li>';
				}
				$fld .= '</ul>';

				// Final field output.
				$output = $this->field_element(
					'row',
					$field,
					array(
						'slug'    => 'choices',
						'content' => $lbl . $fld,
					),
					false
				);
				break;

			// Choices Images ------------------------------------------------//

			case 'choices_images':

				// Field note: Image tips.
				$note = sprintf( '<div class="wpforms-alert-warning wpforms-alert-small wpforms-alert %s">',
					! empty( $field['choices_images'] ) ? '' : 'wpforms-hidden'
				);
				$note .= esc_html__( 'Images are not cropped or resized. For best results, they should be the same size and 250x250 pixels or smaller.', 'wpforms' );
				$note .= '</div>';

				// Field contents.
				$fld = $this->field_element(
					'checkbox',
					$field,
					array(
						'slug'    => 'choices_images',
						'value'   => isset( $field['choices_images'] ) ? '1' : '0',
						'desc'    => esc_html__( 'Use image choices', 'wpforms' ),
						'tooltip' => esc_html__( 'Check this option to enable using images with the choices.', 'wpforms' ),
					),
					false
				);

				// Final field output.
				$output = $this->field_element(
					'row',
					$field,
					array(
						'slug'    => 'choices_images',
						'class'   => ! empty( $field['dynamic_choices'] ) ? 'wpforms-hidden' : '',
						'content' => $note . $fld,
					),
					false
				);
				break;

			// Choices Images Style ------------------------------------------//

			case 'choices_images_style':

				// Field label.
				$lbl = $this->field_element(
					'label',
					$field,
					array(
						'slug'    => 'choices_images_style',
						'value'   => esc_html__( 'Image Choice Style', 'wpforms' ),
						'tooltip' => esc_html__( 'Select the style for the image choices.', 'wpforms' ),
					),
					false
				);

				// Field contents.
				$fld = $this->field_element(
					'select',
					$field,
					array(
						'slug'    => 'choices_images_style',
						'value'   => ! empty( $field['choices_images_style'] ) ? esc_attr( $field['choices_images_style'] ) : 'modern',
						'options' => array(
							'modern'  => esc_html__( 'Modern', 'wpforms' ),
							'classic' => esc_html__( 'Classic', 'wpforms' ),
							'none'    => esc_html__( 'None', 'wpforms' ),
						),
					),
					false
				);

				// Final field output.
				$output = $this->field_element(
					'row',
					$field,
					array(
						'slug'    => 'choices_images_style',
						'content' => $lbl . $fld,
						'class'   => ! empty( $field['choices_images'] ) ? '' : 'wpforms-hidden',
					),
					false
				);
				break;

			// ---------------------------------------------------------------//
			// Advanced Fields.
			// ---------------------------------------------------------------//

			// Default value. -------------------------------------------------//

			case 'default_value':
				$value   = ! empty( $field['default_value'] ) ? esc_attr( $field['default_value'] ) : '';
				$tooltip = esc_html__( 'Enter text for the default form field value.', 'wpforms' );
				$toggle  = '<a href="#" class="toggle-smart-tag-display" data-type="other"><i class="fa fa-tags"></i> <span>' . esc_html__( 'Show Smart Tags', 'wpforms' ) . '</span></a>';
				$output  = $this->field_element( 'label', $field, array( 'slug' => 'default_value', 'value' => esc_html__( 'Default Value', 'wpforms' ), 'tooltip' => $tooltip, 'after_tooltip' => $toggle ), false );
				$output .= $this->field_element( 'text',  $field, array( 'slug' => 'default_value', 'value' => $value ), false );
				$output  = $this->field_element( 'row',   $field, array( 'slug' => 'default_value', 'content' => $output ), false );
				break;

			// Size. ----------------------------------------------------------//

			case 'size':
				$value   = ! empty( $field['size'] ) ? esc_attr( $field['size'] ) : 'medium';
				$class   = ! empty( $args['class'] ) ? esc_html( $args['class'] ) : '';
				$tooltip = esc_html__( 'Select the default form field size.', 'wpforms' );
				$options = array(
					'small'  => esc_html__( 'Small', 'wpforms' ),
					'medium' => esc_html__( 'Medium', 'wpforms' ),
					'large'  => esc_html__( 'Large', 'wpforms' ),
				);
				$output  = $this->field_element( 'label',  $field, array( 'slug' => 'size', 'value' => esc_html__( 'Field Size', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output .= $this->field_element( 'select', $field, array( 'slug' => 'size', 'value' => $value, 'options' => $options ), false );
				$output  = $this->field_element( 'row',    $field, array( 'slug' => 'size', 'content' => $output, 'class' => $class ), false );
				break;

			// Advanced Options markup. ---------------------------------------//

			case 'advanced-options':
				$markup = ! empty( $args['markup'] ) ? $args['markup'] : 'open';
				if ( 'open' === $markup ) {
					$override = apply_filters( 'wpforms_advanced_options_override', false );
					$override = ! empty( $override ) ? 'style="display:' . $override . ';"' : '';
					$output   = sprintf( '<div class="wpforms-field-option-group wpforms-field-option-group-advanced wpforms-hide" id="wpforms-field-option-advanced-%d" %s>', $field['id'], $override );
					$output  .= sprintf( '<a href="#" class="wpforms-field-option-group-toggle">%s <i class="fa fa-angle-right"></i></a>', esc_html__( 'Advanced Options', 'wpforms' ) );
					$output  .= '<div class="wpforms-field-option-group-inner">';
				} else {
					$output   = '</div></div>';
				}
				break;

			// Placeholder. ---------------------------------------------------//

			case 'placeholder':
				$value   = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
				$tooltip = esc_html__( 'Enter text for the form field placeholder.', 'wpforms' );
				$output  = $this->field_element( 'label', $field, array( 'slug' => 'placeholder', 'value' => esc_html__( 'Placeholder Text', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output .= $this->field_element( 'text',  $field, array( 'slug' => 'placeholder', 'value' => $value ), false );
				$output  = $this->field_element( 'row',   $field, array( 'slug' => 'placeholder', 'content' => $output ), false );
				break;

			// CSS classes. ---------------------------------------------------//

			case 'css':
				$toggle  = '';
				$value   = ! empty( $field['css'] ) ? esc_attr( $field['css'] ) : '';
				$tooltip = esc_html__( 'Enter CSS class names for the form field container. Class names should be separated with spaces.', 'wpforms' );
				if ( ! in_array( $field['type'], array( 'pagebreak' ), true ) ) {
					$toggle  = '<a href="#" class="toggle-layout-selector-display"><i class="fa fa-th-large"></i> <span>' . esc_html__( 'Show Layouts', 'wpforms' ) . '</span></a>';
				}
				// Build output
				$output  = $this->field_element( 'label', $field, array( 'slug' => 'css', 'value' => esc_html__( 'CSS Classes', 'wpforms' ), 'tooltip' => $tooltip, 'after_tooltip' => $toggle ), false );
				$output .= $this->field_element( 'text',  $field, array( 'slug' => 'css', 'value' => $value ), false );
				$output  = $this->field_element( 'row',   $field, array( 'slug' => 'css', 'content' => $output ), false );
				break;

			// Hide Label. ----------------------------------------------------//

			case 'label_hide':
				$value   = isset( $field['label_hide'] ) ? $field['label_hide'] : '0';
				$tooltip = esc_html__( 'Check this option to hide the form field label.', 'wpforms' );
				// Build output
				$output  = $this->field_element( 'checkbox', $field, array( 'slug' => 'label_hide', 'value' => $value, 'desc' => esc_html__( 'Hide Label', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output  = $this->field_element( 'row',      $field, array( 'slug' => 'label_hide', 'content' => $output ), false );
				break;

			// Hide Sub-Labels. -----------------------------------------------//

			case 'sublabel_hide':
				$value   = isset( $field['sublabel_hide'] ) ? $field['sublabel_hide'] : '0';
				$tooltip = esc_html__( 'Check this option to hide the form field sub-label.', 'wpforms' );
				// Build output
				$output  = $this->field_element( 'checkbox', $field, array( 'slug' => 'sublabel_hide', 'value' => $value, 'desc' => esc_html__( 'Hide Sub-Labels', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output  = $this->field_element( 'row',      $field, array( 'slug' => 'sublabel_hide', 'content' => $output ), false );
				break;

			// Input Columns. -------------------------------------------------//

			case 'input_columns':
				$value   = ! empty( $field['input_columns'] ) ? esc_attr( $field['input_columns'] ) : '';
				$tooltip = esc_html__( 'Select the layout for displaying field choices.', 'wpforms' );
				$options = array(
					''       => esc_html__( 'One Column', 'wpforms' ),
					'2'      => esc_html__( 'Two Columns', 'wpforms' ),
					'3'      => esc_html__( 'Three Columns', 'wpforms' ),
					'inline' => esc_html__( 'Inline', 'wpforms' ),
				);
				$output  = $this->field_element( 'label',  $field, array( 'slug' => 'input_columns', 'value' => esc_html__( 'Choice Layout', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output .= $this->field_element( 'select', $field, array( 'slug' => 'input_columns', 'value' => $value, 'options' => $options ), false );
				$output  = $this->field_element( 'row',    $field, array( 'slug' => 'input_columns', 'content' => $output ), false );
				break;

			// Dynamic Choices. -----------------------------------------------//

			case 'dynamic_choices':
				$value   = ! empty( $field['dynamic_choices'] ) ? esc_attr( $field['dynamic_choices'] ) : '';
				$tooltip = esc_html__( 'Select auto-populate method to use.', 'wpforms' );
				$options = array(
					''          => esc_html__( 'Off', 'wpforms' ),
					'post_type' => esc_html__( 'Post Type', 'wpforms' ),
					'taxonomy'  => esc_html__( 'Taxonomy', 'wpforms' ),
				);
				$output  = $this->field_element( 'label',  $field, array( 'slug' => 'dynamic_choices', 'value' => esc_html__( 'Dynamic Choices', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output .= $this->field_element( 'select', $field, array( 'slug' => 'dynamic_choices', 'value' => $value, 'options' => $options ), false );
				$output  = $this->field_element( 'row',    $field, array( 'slug' => 'dynamic_choices', 'content' => $output ), false );
				break;

			// Dynamic Choices Source. ----------------------------------------//

			case 'dynamic_choices_source':
				$output = '';
				$type   = ! empty( $field['dynamic_choices'] ) ? esc_attr( $field['dynamic_choices'] ) : '';

				if ( ! empty( $type ) ) {

					$type_name = '';
					$items     = array();

					if ( 'post_type' === $type ) {

						$type_name = esc_html__( 'Post Type', 'wpforms' );
						$items     = get_post_types(
							array(
								'public' => true,
							),
							'objects'
						);
						unset( $items['attachment'] );

					} elseif ( 'taxonomy' === $type ) {

						$type_name = esc_html__( 'Taxonomy', 'wpforms' );
						$items     = get_taxonomies(
							array(
								'public' => true,
							),
							'objects'
						);
						unset( $items['post_format'] );
					}

					/* translators: %s - dynamic source type name. */
					$tooltip = sprintf( esc_html__( 'Select %s to use for auto-populating field choices.', 'wpforms' ), $type_name );
					/* translators: %s - dynamic source type name. */
					$label   = sprintf( esc_html__( 'Dynamic %s Source', 'wpforms' ), $type_name );
					$options = array();
					$source  = ! empty( $field[ 'dynamic_' . $type ] ) ? esc_attr( $field[ 'dynamic_' . $type ] ) : '';

					foreach ( $items as $key => $item ) {
						$options[ $key ] = $item->labels->name;
					}

					// Field option label.
					$option_label  = $this->field_element(
						'label',
						$field,
						array(
							'slug'    => 'dynamic_' . $type,
							'value'   => $label,
							'tooltip' => $tooltip,
						),
						false
					);

					// Field option select input.
					$option_input = $this->field_element(
						'select',
						$field,
						array(
							'slug'    => 'dynamic_' . $type,
							'options' => $options,
							'value'   => $source,
						),
						false
					);

					// Field option row (markup) including label and input.
					$output  = $this->field_element(
						'row',
						$field,
						array(
							'slug'    => 'dynamic_' . $type,
							'content' => $option_label . $option_input,
						),
						false
					);
				} // End if().
				break;
		} // End switch().

		if ( $echo ) {

			if ( in_array( $option, array( 'basic-options', 'advanced-options' ), true ) ) {

				if ( 'open' === $markup ) {
					do_action( "wpforms_field_options_before_{$option}", $field, $this );
				}

				if ( 'close' === $markup ) {
					do_action( "wpforms_field_options_bottom_{$option}", $field, $this );
				}

				echo $output; // WPCS: XSS ok.

				if ( 'open' === $markup ) {
					do_action( "wpforms_field_options_top_{$option}", $field, $this );
				}

				if ( 'close' === $markup ) {
					do_action( "wpforms_field_options_after_{$option}", $field, $this );
				}
			} else {
				echo $output; // WPCS: XSS ok.
			}
		} else {
			return $output;
		}
	}

	/**
	 * Helper function to create common field options that are used frequently
	 * in the field preview.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option
	 * @param array $field
	 * @param array $args
	 * @param boolean $echo
	 *
	 * @return mixed echo or return string
	 */
	public function field_preview_option( $option, $field, $args = array(), $echo = true ) {

		$class = ! empty( $args['class'] ) ? wpforms_sanitize_classes( $args['class'] ) : '';

		switch ( $option ) {

			case 'label':
				$label  = isset( $field['label'] ) && ! empty( $field['label'] ) ? esc_html( $field['label'] ) : '';
				$output = sprintf( '<label class="label-title %s"><span class="text">%s</span><span class="required">*</span></label>', $class, $label );
				break;

			case 'description':
				$description = isset( $field['description'] ) && ! empty( $field['description'] ) ? $field['description'] : '';
				$description = strpos( $class, 'nl2br' ) !== false ? nl2br( $description ) : $description;
				$output      = sprintf( '<div class="description %s">%s</div>', $class, $description );
				break;

			case 'choices':
				$values  = ! empty( $field['choices'] ) ? $field['choices'] : $this->defaults;
				$dynamic = ! empty( $field['dynamic_choices'] ) ? $field['dynamic_choices'] : false;
				$total   = false;

				// Check to see if this field is configured for Dynamic Choices,
				// either auto populating from a post type or a taxonomy.
				if ( 'post_type' === $dynamic && ! empty( $field['dynamic_post_type'] ) ) {

					// Post type dynamic populating.
					$total  = wp_count_posts( $field['dynamic_post_type'] );
					$total  = $total->publish;
					$values = array();
					$posts  = wpforms_get_hierarchical_object(
						apply_filters( 'wpforms_dynamic_choice_post_type_args',
							array(
								'post_type'      => $field['dynamic_post_type'],
								'posts_per_page' => -1,
								'orderby'        => 'title',
								'order'          => 'ASC',
							),
							$field,
							$this->form_id
						),
						true
					);

					foreach ( $posts as $post ) {
						$values[] = array(
							'label' => $post->post_title,
						);
					}
				} elseif ( 'taxonomy' === $dynamic && ! empty( $field['dynamic_taxonomy'] ) ) {

					// Taxonomy dynamic populating.
					$total  = wp_count_terms( $field['dynamic_taxonomy'] );
					$values = array();
					$terms  = wpforms_get_hierarchical_object(
						apply_filters( 'wpforms_dynamic_choice_taxonomy_args',
							array(
								'taxonomy'   => $field['dynamic_taxonomy'],
								'hide_empty' => false,
							),
							$field,
							$this->form_id
						),
						true
					);

					foreach ( $terms as $term ) {
						$values[] = array(
							'label' => $term->name,
						);
					}
				}

				// Notify if currently empty.
				if ( empty( $values ) ) {
					$values = array(
						'label' => esc_html__( '(empty)', 'wpforms' ),
					);
				}

				// Build output.
				if ( in_array( $field['type'], array( 'checkbox', 'gdpr-checkbox', 'payment-multiple', 'radio' ), true ) ) {

					if ( in_array( $field['type'], array( 'checkbox', 'gdpr-checkbox' ), true ) ) {
						$type = 'checkbox';
					} else {
						$type = 'radio';
					}

					$list_class = array( 'primary-input' );
					$images     = empty( $field['dynamic_choices'] ) && ! empty( $field['choices_images'] );

					if ( $images ) {
						$list_class[] = 'wpforms-image-choices';
						$list_class[] = 'wpforms-image-choices-' . sanitize_html_class( $field['choices_images_style'] );
					}

					$output = sprintf( '<ul class="%s">',
						wpforms_sanitize_classes( $list_class, true )
					);

					foreach ( $values as $key => $value ) {

						$default     = isset( $value['default'] ) ? $value['default'] : '';
						$selected    = checked( '1', $default, false );
						$input_class = array();
						$item_class  = array();

						if ( ! empty( $value['default'] ) ) {
							$item_class[] = 'wpforms-selected';
						}

						if ( $images ) {
							$item_class[] = 'wpforms-image-choices-item';
						}

						$output .= sprintf( '<li class="%s">',
							wpforms_sanitize_classes( $item_class, true )
						);

							if ( $images ) {

								if ( in_array( $field['choices_images_style'], array( 'modern', 'classic' ), true ) ) {
									$input_class[] = 'wpforms-screen-reader-element';
								}

								$output .= '<label>';

									$output .= sprintf( '<span class="wpforms-image-choices-image"><img src="%s" alt="%s"%s></span>',
										! empty( $value['image'] ) ? esc_url( $value['image'] ) : WPFORMS_PLUGIN_URL . 'assets/images/placeholder-200x125.png',
										esc_attr( $value['label'] ),
										! empty( $value['label'] ) ? ' title="' . esc_attr( $value['label'] ) . '"' : ''
									);

									if ( 'none' === $field['choices_images_style'] ) {
										$output .= '<br>';
									}

									$output .= sprintf( '<input type="%s" class="%s" %s disabled>',
										$type,
										wpforms_sanitize_classes( $input_class, true ),
										$selected
									);

									$output .= '<span class="wpforms-image-choices-label">' . wp_kses_post( $value['label'] ) . '</span>';

								$output .= '</label>';

							} else {
								$output .= sprintf( '<input type="%s" %s disabled>%s</li>',
									$type,
									$selected,
									$value['label']
								);
							}

						$output .= '</li>';
					}

					$output .= '</ul>';
				}

				// Dynamic population is enabled and contains more than 20 items,
				// include a note about results displayed.
				if ( $total && $total > 20 ) {
					$output .= '<div class="wpforms-alert-dynamic wpforms-alert wpforms-alert-warning">';
					/* translators: %d - total amount of choices. */
					$output .= sprintf(
						wp_kses(
							__( 'Showing the first 20 choices.<br> All %d choices will be displayed when viewing the form.', 'wpforms' ),
							array(
								'br' => array()
							)
						),
						absint( $total )
					);
					$output .= '</div>';
				}
				break;
		}

		if ( $echo ) {
			echo $output; // WPCS: XSS ok.
		} else {
			return $output;
		}
	}

	/**
	 * Create a new field in the admin AJAX editor.
	 *
	 * @since 1.0.0
	 */
	public function field_new() {

		// Run a security check.
		check_ajax_referer( 'wpforms-builder', 'nonce' );

		// Check for permissions.
		if ( ! wpforms_current_user_can() ) {
			die( esc_html__( 'You do not have permission.', 'wpforms' ) );
		}

		// Check for form ID.
		if ( ! isset( $_POST['id'] ) || empty( $_POST['id'] ) ) {
			die( esc_html__( 'No form ID found', 'wpforms' ) );
		}

		// Check for field type to add.
		if ( ! isset( $_POST['type'] ) || empty( $_POST['type'] ) ) {
			die( esc_html__( 'No field type found', 'wpforms' ) );
		}

		// Grab field data.
		$field_args     = ! empty( $_POST['defaults'] ) ? (array) $_POST['defaults'] : array();
		$field_type     = esc_attr( $_POST['type'] );
		$field_id       = wpforms()->form->next_field_id( $_POST['id'] );
		$field          = array(
			'id'          => $field_id,
			'type'        => $field_type,
			'label'       => $this->name,
			'description' => '',
		);
		$field          = wp_parse_args( $field_args, $field );
		$field          = apply_filters( 'wpforms_field_new_default', $field );
		$field_required = apply_filters( 'wpforms_field_new_required', '', $field );
		$field_class    = apply_filters( 'wpforms_field_new_class', '', $field );

		// Field types that default to required.
		if ( ! empty( $field_required ) ) {
			$field_required = 'required';
			$field['required'] = '1';
		}

		// Build Preview.
		ob_start();
		$this->field_preview( $field );
		$prev     = ob_get_clean();
		$preview  = sprintf( '<div class="wpforms-field wpforms-field-%s %s %s" id="wpforms-field-%d" data-field-id="%d" data-field-type="%s">', $field_type, $field_required, $field_class, $field['id'], $field['id'], $field_type );
		$preview .= sprintf( '<a href="#" class="wpforms-field-duplicate" title="%s"><i class="fa fa-files-o" aria-hidden="true"></i></a>', esc_attr__( 'Duplicate Field', 'wpforms' ) );
		$preview .= sprintf( '<a href="#" class="wpforms-field-delete" title="%s"><i class="fa fa-times-circle"></i></a>', esc_attr__( 'Delete Field', 'wpforms' ) );
		$preview .= sprintf( '<span class="wpforms-field-helper">%s</span>', esc_html__( 'Click to edit. Drag to reorder.', 'wpforms' ) );
		$preview .= $prev;
		$preview .= '</div>';

		// Build Options.
		$options  = sprintf( '<div class="wpforms-field-option wpforms-field-option-%s" id="wpforms-field-option-%d" data-field-id="%d">', esc_attr( $field['type'] ), $field['id'], $field['id'] );
		$options .= sprintf( '<input type="hidden" name="fields[%d][id]" value="%d" class="wpforms-field-option-hidden-id">', $field['id'], $field['id'] );
		$options .= sprintf( '<input type="hidden" name="fields[%d][type]" value="%s" class="wpforms-field-option-hidden-type">', $field['id'], esc_attr( $field['type'] ) );
		ob_start();
		$this->field_options( $field );
		$options .= ob_get_clean();
		$options .= '</div>';

		// Prepare to return compiled results.
		wp_send_json_success(
			array(
				'form_id' => $_POST['id'],
				'field'   => $field,
				'preview' => $preview,
				'options' => $options,
			)
		);
	}

	/**
	 * Display the field input elements on the frontend.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 * @param array $field_atts
	 * @param array $form_data
	 */
	public function field_display( $field, $field_atts, $form_data ) {
	}

	/**
	 * Display field input errors if present.
	 *
	 * @since 1.3.7
	 *
	 * @param string $key
	 * @param array $field
	 */
	public function field_display_error( $key, $field ) {

		// Need an error.
		if ( empty( $field['properties']['error']['value'][ $key ] ) ) {
			return;
		}

		printf(
			'<label class="wpforms-error" for="%s">%s</label>',
			esc_attr( $field['properties']['inputs'][ $key ]['id'] ),
			esc_html( $field['properties']['error']['value'][ $key ] )
		);
	}

	/**
	 * Display field input sublabel if present.
	 *
	 * @since 1.3.7
	 *
	 * @param string $key
	 * @param string $position
	 * @param array $field
	 */
	public function field_display_sublabel( $key, $position, $field ) {

		// Need a sublabel value.
		if ( empty( $field['properties']['inputs'][ $key ]['sublabel']['value'] ) ) {
			return;
		}

		$pos    = ! empty( $field['properties']['inputs'][ $key ]['sublabel']['position'] ) ? $field['properties']['inputs'][ $key ]['sublabel']['position'] : 'after';
		$hidden = ! empty( $field['properties']['inputs'][ $key ]['sublabel']['hidden'] ) ? 'wpforms-sublabel-hide' : '';

		if ( $pos !== $position ) {
			return;
		}

		printf(
			'<label for="%s" class="wpforms-field-sublabel %s %s">%s</label>',
			esc_attr( $field['properties']['inputs'][ $key ]['id'] ),
			sanitize_html_class( $pos ),
			$hidden,
			$field['properties']['inputs'][ $key ]['sublabel']['value']
		);
	}

	/**
	 * Validates field on form submit.
	 *
	 * @since 1.0.0
	 *
	 * @param int $field_id
	 * @param array $field_submit
	 * @param array $form_data
	 */
	public function validate( $field_id, $field_submit, $form_data ) {

		// Basic required check - If field is marked as required, check for entry data.
		if ( ! empty( $form_data['fields'][ $field_id ]['required'] ) && empty( $field_submit ) && '0' != $field_submit ) {
			wpforms()->process->errors[ $form_data['id'] ][ $field_id ] = wpforms_get_required_label();
		}
	}

	/**
	 * Formats and sanitizes field.
	 *
	 * @since 1.0.0
	 *
	 * @param int $field_id
	 * @param array $field_submit
	 * @param array $form_data
	 */
	public function format( $field_id, $field_submit, $form_data ) {

		if ( is_array( $field_submit ) ) {
			$field_submit = array_filter( $field_submit );
			$field_submit = implode( "\r\n", $field_submit );
		}

		$name  = ! empty( $form_data['fields'][ $field_id ]['label'] ) ? sanitize_text_field( $form_data['fields'][ $field_id ]['label'] ) : '';

		// Sanitize but keep line breaks.
		$value = wpforms_sanitize_textarea_field( $field_submit );

		wpforms()->process->fields[ $field_id ] = array(
			'name'  => $name,
			'value' => $value,
			'id'    => absint( $field_id ),
			'type'  => $this->type,
		);
	}
}

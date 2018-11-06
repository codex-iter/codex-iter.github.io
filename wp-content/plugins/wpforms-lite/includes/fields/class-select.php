<?php
/**
 * Dropdown field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */
class WPForms_Field_Select extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information.
		$this->name     = esc_html__( 'Dropdown', 'wpforms' );
		$this->type     = 'select';
		$this->icon     = 'fa-caret-square-o-down';
		$this->order    = 7;
		$this->defaults = array(
			1 => array(
				'label'   => esc_html__( 'First Choice', 'wpforms' ),
				'value'   => '',
				'default' => '',
			),
			2 => array(
				'label'   => esc_html__( 'Second Choice', 'wpforms' ),
				'value'   => '',
				'default' => '',
			),
			3 => array(
				'label'   => esc_html__( 'Third Choice', 'wpforms' ),
				'value'   => '',
				'default' => '',
			),
		);
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 */
	public function field_options( $field ) {

		// --------------------------------------------------------------------//
		// Basic field options.
		// --------------------------------------------------------------------//

		// Options open markup.
		$this->field_option( 'basic-options', $field, array(
			'markup' => 'open',
		) );

		// Label.
		$this->field_option( 'label', $field );

		// Choices.
		$this->field_option( 'choices', $field );

		// Description.
		$this->field_option( 'description', $field );

		// Required toggle.
		$this->field_option( 'required', $field );

		// Options close markup.
		$this->field_option( 'basic-options', $field, array(
			'markup' => 'close',
		) );

		// --------------------------------------------------------------------//
		// Advanced field options.
		// --------------------------------------------------------------------//

		// Options open markup.
		$this->field_option( 'advanced-options', $field, array(
			'markup' => 'open',
		) );

		// Show Values toggle option. This option will only show if already used
		// or if manually enabled by a filter.
		if ( ! empty( $field['show_values'] ) || apply_filters( 'wpforms_fields_show_options_setting', false ) ) {
			$show_values = $this->field_element(
				'checkbox',
				$field,
				array(
					'slug'    => 'show_values',
					'value'   => isset( $field['show_values'] ) ? $field['show_values'] : '0',
					'desc'    => esc_html__( 'Show Values', 'wpforms' ),
					'tooltip' => esc_html__( 'Check this to manually set form field values.', 'wpforms' ),
				),
				false
			);
			$this->field_element( 'row', $field, array(
				'slug'    => 'show_values',
				'content' => $show_values,
			) );
		}

		// Size.
		$this->field_option( 'size', $field );

		// Placeholder.
		$this->field_option( 'placeholder', $field );

		// Hide label.
		$this->field_option( 'label_hide', $field );

		// Custom CSS classes.
		$this->field_option( 'css', $field );

		// Dynamic choice auto-populating toggle.
		$this->field_option( 'dynamic_choices', $field );

		// Dynamic choice source.
		$this->field_option( 'dynamic_choices_source', $field );

		// Options close markup.
		$this->field_option( 'advanced-options', $field, array(
			'markup' => 'close',
		) );
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 */
	public function field_preview( $field ) {

		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
		$values      = ! empty( $field['choices'] ) ? $field['choices'] : $this->defaults;
		$dynamic     = ! empty( $field['dynamic_choices'] ) ? $field['dynamic_choices'] : false;

		// Label.
		$this->field_preview_option( 'label', $field );

		// Field select element.
		echo '<select class="primary-input" disabled>';

			// Optional placeholder.
			if ( ! empty( $placeholder ) ) {
				printf( '<option value="" class="placeholder">%s</option>', $placeholder );
			}

			// Check to see if this field is configured for Dynamic Choices,
			// either auto populating from a post type or a taxonomy.
			if ( 'post_type' === $dynamic && ! empty( $field['dynamic_post_type'] ) ) {

				// Post type dynamic populating.
				$source = $field['dynamic_post_type'];
				$args   = array(
					'post_type'      => $source,
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
				);
				$posts  = wpforms_get_hierarchical_object( apply_filters( 'wpforms_dynamic_choice_post_type_args', $args, $field, $this->form_id ), true );
				$values = array();

				foreach ( $posts as $post ) {
					$values[] = array(
						'label' => $post->post_title,
					);
				}
			} elseif ( 'taxonomy' === $dynamic && ! empty( $field['dynamic_taxonomy'] ) ) {

				// Taxonomy dynamic populating.
				$source = $field['dynamic_taxonomy'];
				$args   = array(
					'taxonomy'   => $source,
					'hide_empty' => false,
				);
				$terms = wpforms_get_hierarchical_object(
					apply_filters( 'wpforms_dynamic_choice_taxonomy_args', $args, $field, $this->form_id ),
					true
				);
				$values = array();

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

			// Build the select options (even though user can only see 1st option).
			foreach ( $values as $key => $value ) {

				$default  = isset( $value['default'] ) ? $value['default'] : '';
				$selected = ! empty( $placeholder ) ? '' : selected( '1', $default, false );

				printf( '<option %s>%s</option>', $selected, $value['label'] );
			}

		echo '</select>';

		// Description.
		$this->field_preview_option( 'description', $field );
	}

	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 * @param array $field_atts
	 * @param array $form_data
	 */
	public function field_display( $field, $field_atts, $form_data ) {

		// Setup and sanitize the necessary data.
		$field             = apply_filters( 'wpforms_select_field_display', $field, $field_atts, $form_data );
		$field_placeholder = ! empty( $field['placeholder']) ? esc_attr( $field['placeholder'] ) : '';
		$field_required    = ! empty( $field['required'] ) ? ' required' : '';
		$field_class       = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id          = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_id'] ) );
		$field_data        = '';
		$dynamic           = ! empty( $field['dynamic_choices'] ) ? $field['dynamic_choices'] : false;
		$choices           = $field['choices'];
		$has_default       = false;

		if ( ! empty( $field_atts['input_data'] ) ) {
			foreach ( $field_atts['input_data'] as $key => $val ) {
			  $field_data .= ' data-' . $key . '="' . $val . '"';
			}
		}

		// Check to see if any of the options have selected by default.
		foreach ( $choices as $choice ) {
			if ( isset( $choice['default'] ) ) {
				$has_default = true;
				break;
			}
		}

		// Primary select field.
		printf( '<select name="wpforms[fields][%d]" id="%s" class="%s" %s %s>',
			$field['id'],
			$field_id,
			$field_class,
			$field_required,
			$field_data
		);

			// Optional placeholder.
			if ( ! empty( $field_placeholder ) ) {
				printf( '<option value="" class="placeholder" disabled %s>%s</option>', selected( false, $has_default, false ), $field_placeholder );
			}

			// Check to see if this field is configured for Dynamic Choices,
			// either auto populating from a post type or a taxonomy.
			if ( 'post_type' === $dynamic && ! empty( $field['dynamic_post_type'] ) ) {

				// Post type dynamic populating.
				$source = $field['dynamic_post_type'];
				$args   = array(
					'post_type'      => $source,
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
				);
				$posts   = wpforms_get_hierarchical_object( apply_filters( 'wpforms_dynamic_choice_post_type_args', $args, $field, $form_data['id'] ), true );
				$choices = array();

				foreach ( $posts as $post ) {
					$choices[] = array(
						'value' => $post->ID,
						'label' => $post->post_title,
					);
				}

				$field['show_values'] = true;

			} elseif ( 'taxonomy' === $dynamic && ! empty( $field['dynamic_taxonomy'] ) ) {

				// Taxonomy dynamic populating.
				$source = $field['dynamic_taxonomy'];
				$args   = array(
					'taxonomy'   => $source,
					'hide_empty' => false,
				);
				$terms = wpforms_get_hierarchical_object(
					apply_filters( 'wpforms_dynamic_choice_taxonomy_args', $args, $field, $form_data['id'] ),
					true
				);
				$choices = array();

				foreach ( $terms as $term ) {
					$choices[] = array(
						'value' => $term->term_id,
						'label' => $term->name,
					);
				}

				$field['show_values'] = true;
			}

			// Build the select options.
			foreach ( $choices as $key => $choice ) {

				$selected = isset( $choice['default'] ) && empty( $field_placeholder ) ? '1' : '0' ;
				$val      = isset( $field['show_values'] ) ? esc_attr( $choice['value'] ) : esc_attr( $choice['label'] );

				printf( '<option value="%s" %s>%s</option>', $val, selected( '1', $selected, false ), $choice['label'] );
			}

		echo '</select>';
	}

	/**
	 * Formats and sanitizes field.
	 *
	 * @since 1.0.2
	 *
	 * @param int $field_id
	 * @param string $field_submit
	 * @param array $form_data
	 */
	public function format( $field_id, $field_submit, $form_data ) {

		$field     = $form_data['fields'][ $field_id ];
		$dynamic   = ! empty( $field['dynamic_choices'] ) ? $field['dynamic_choices'] : false;
		$name      = sanitize_text_field( $field['label'] );
		$value_raw = sanitize_text_field( $field_submit );
		$value     = '';

		$data = array(
			'name'      => $name,
			'value'     => '',
			'value_raw' => $value_raw,
			'id'        => absint( $field_id ),
			'type'      => $this->type,
		);

		if ( 'post_type' === $dynamic && ! empty( $field['dynamic_post_type'] ) ) {

			// Dynamic population is enabled using post type
			$data['dynamic']           = 'post_type';
			$data['dynamic_items']     = absint( $value_raw );
			$data['dynamic_post_type'] = $field['dynamic_post_type'];
			$post                      = get_post( $value_raw );

			if ( ! is_wp_error( $post ) && ! empty( $post ) && $data['dynamic_post_type'] === $post->post_type ) {
				$data['value'] = esc_html( $post->post_title );
			}
		} elseif ( 'taxonomy' === $dynamic && ! empty( $field['dynamic_taxonomy'] ) ) {

			// Dynamic population is enabled using taxonomy
			$data['dynamic']          = 'taxonomy';
			$data['dynamic_items']    = absint( $value_raw );
			$data['dynamic_taxonomy'] = $field['dynamic_taxonomy'];
			$term                     = get_term( $value_raw, $data['dynamic_taxonomy'] );

			if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
				$data['value'] = esc_html( $term->name );
			}
		} else {

			// Normal processing, dynamic population is off.

			// If show_values is true, that means values posted are the raw values
			// and not the labels. So we need to get the label values.
			if ( ! empty( $field['show_values'] ) && '1' == $field['show_values'] ) {

				foreach ( $field['choices'] as $choice ) {
					if ( $choice['value'] === $field_submit ) {
						$value = $choice['label'];
						break;
					}
				}

				$data['value'] = sanitize_text_field( $value );

			} else {
				$data['value'] = $value_raw;
			}
		}

		// Push field details to be saved
		wpforms()->process->fields[ $field_id ] = $data;
	}
}

new WPForms_Field_Select();

<?php
/**
 * Custom Controls
 *
 * @package Audioman
 */

/**
 * Add Custom Controls
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_custom_controls( $wp_customize ) {
	// Custom control for Important Links.
	class Audioman_Important_Links_Control extends WP_Customize_Control {
		public $type = 'important-links';

		public function render_content() {
			// Add Theme instruction, Support Forum, Changelog, Donate link, Review, Facebook, Twitter, Google+, Pinterest links.
			$important_links = array(
				'theme_instructions' => array(
					'link'  => esc_url( 'https://catchthemes.com/themes/audioman/#theme-instructions/' ),
					'text'  => esc_html__( 'Theme Instructions', 'audioman' ),
					),
				'support' => array(
					'link'  => esc_url( 'https://catchthemes.com/support/' ),
					'text'  => esc_html__( 'Support', 'audioman' ),
					),
				'changelog' => array(
					'link'  => esc_url( 'https://catchthemes.com/themes/audioman/#changelog' ),
					'text'  => esc_html__( 'Changelog', 'audioman' ),
					),
				'facebook' => array(
					'link'  => esc_url( 'https://www.facebook.com/catchthemes/' ),
					'text'  => esc_html__( 'Facebook', 'audioman' ),
					),
				'twitter' => array(
					'link'  => esc_url( 'https://twitter.com/catchthemes/' ),
					'text'  => esc_html__( 'Twitter', 'audioman' ),
					),
				'gplus' => array(
					'link'  => esc_url( 'https://plus.google.com/+Catchthemes/' ),
					'text'  => esc_html__( 'Google+', 'audioman' ),
					),
				'pinterest' => array(
					'link'  => esc_url( 'http://www.pinterest.com/catchthemes/' ),
					'text'  => esc_html__( 'Pinterest', 'audioman' ),
					),
			);

			foreach ( $important_links as $important_link ) {
				echo '<p><a target="_blank" href="' . $important_link['link'] . '" >' . $important_link['text'] . ' </a></p>';
			}
		}
	}

	// Custom control for dropdown category multiple select.
	class Audioman_Multi_Categories_Control extends WP_Customize_Control {
		public $type = 'dropdown-categories';

		public function render_content() {
			$dropdown = wp_dropdown_categories(
				array(
					'name'             => $this->id,
					'echo'             => 0,
					'hide_empty'       => false,
					'show_option_none' => false,
					'hide_if_empty'    => false,
					'show_option_all'  => esc_html__( 'All Categories', 'audioman' ),
				)
			);

			$dropdown = str_replace( '<select', '<select multiple = "multiple" style = "height:150px;" ' . $this->get_link(), $dropdown );

			printf(
				'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
				$this->label,
				$dropdown
			);

			echo '<p class="description">' . esc_html__( 'Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.', 'audioman' ) . '</p>';
		}
	}

	// Custom control for any note, use label as output description.
	class Audioman_Note_Control extends WP_Customize_Control {
		public $type = 'description';

		public function render_content() {
			echo '<h2 class="description">' . $this->label . '</h2>';
		}
	}

	/**
	 * Customize Custom Background Control class.
	 *
	 * @since 1.0.0
	 *
	 * @see WP_Customize_Upload_Control
	 */
	class Audioman_Background_Control extends WP_Customize_Upload_Control {

		/**
		 * The type of customize control being rendered.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $type = 'background-image';

		/**
		 * Mime type for upload control.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $mime_type = 'image';

		/**
		 * Labels for upload control buttons.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $button_labels = array();

		/**
		 * Field labels
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $field_labels = array();

		/**
		 * Background choices for the select fields.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $background_choices = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 * @uses WP_Customize_Upload_Control::__construct()
		 *
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Optional. Arguments to override class property defaults.
		 */
		public function __construct( $manager, $id, $args = array() ) {

			// Calls the parent __construct
			parent::__construct( $manager, $id, $args );

			// Set button labels for image uploader
			$button_labels = $this->get_button_labels();
			$this->button_labels = apply_filters( 'customizer_background_button_labels', $button_labels, $id );

			// Set field labels
			$field_labels = $this->get_field_labels();
			$this->field_labels = apply_filters( 'customizer_background_field_labels', $field_labels, $id );

			// Set background choices
			$background_choices = $this->get_background_choices();
			$this->background_choices = apply_filters( 'customizer_background_choices', $background_choices, $id );

		}

		/**
		 * Add custom parameters to pass to the JS via JSON.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function to_json() {

			parent::to_json();

			$background_choices = $this->background_choices;
			$field_labels = $this->field_labels;

			// Loop through each of the settings and set up the data for it.
			foreach ( $this->settings as $setting_key => $setting_id ) {

				$this->json[ $setting_key ] = array(
					'link'  => $this->get_link( $setting_key ),
					'value' => $this->value( $setting_key ),
					'label' => isset( $field_labels[ $setting_key ] ) ? $field_labels[ $setting_key ] : ''
				);

				if ( 'image_url' === $setting_key ) {
					if ( $this->value( $setting_key ) ) {
						// Get the attachment model for the existing file.
						$attachment_id = attachment_url_to_postid( $this->value( $setting_key ) );
						if ( $attachment_id ) {
							$this->json['attachment'] = wp_prepare_attachment_for_js( $attachment_id );
						}
					}
				}
				elseif ( 'repeat' === $setting_key ) {
					$this->json[ $setting_key ]['choices'] = $background_choices['repeat'];
				}
				elseif ( 'size' === $setting_key ) {
					$this->json[ $setting_key ]['choices'] = $background_choices['size'];
				}
				elseif ( 'position' === $setting_key ) {
					$this->json[ $setting_key ]['choices'] = $background_choices['position'];
				}
				elseif ( 'attach' === $setting_key ) {
					$this->json[ $setting_key ]['choices'] = $background_choices['attach'];
				}
			}

		}

		/**
		 * Render a JS template for the content of the media control.
		 *
		 * @since 1.0.0
		 */
		public function content_template() {

			parent::content_template();
			?>

			<div class="background-image-fields">
			<# if ( data.attachment && data.repeat && data.repeat.choices ) { #>
				<li class="background-image-repeat">
					<# if ( data.repeat.label ) { #>
						<span class="customize-control-title">{{ data.repeat.label }}</span>
					<# } #>
					<select {{{ data.repeat.link }}}>
						<# _.each( data.repeat.choices, function( label, choice ) { #>
							<option value="{{ choice }}" <# if ( choice === data.repeat.value ) { #> selected="selected" <# } #>>{{ label }}</option>
						<# } ) #>
					</select>
				</li>
			<# } #>

			<# if ( data.attachment && data.size && data.size.choices ) { #>
				<li class="background-image-size">
					<# if ( data.size.label ) { #>
						<span class="customize-control-title">{{ data.size.label }}</span>
					<# } #>
					<select {{{ data.size.link }}}>
						<# _.each( data.size.choices, function( label, choice ) { #>
							<option value="{{ choice }}" <# if ( choice === data.size.value ) { #> selected="selected" <# } #>>{{ label }}</option>
						<# } ) #>
					</select>
				</li>
			<# } #>

			<# if ( data.attachment && data.position && data.position.choices ) { #>
				<li class="background-image-position">
					<# if ( data.position.label ) { #>
						<span class="customize-control-title">{{ data.position.label }}</span>
					<# } #>
					<select {{{ data.position.link }}}>
						<# _.each( data.position.choices, function( label, choice ) { #>
							<option value="{{ choice }}" <# if ( choice === data.position.value ) { #> selected="selected" <# } #>>{{ label }}</option>
						<# } ) #>
					</select>
				</li>
			<# } #>

			<# if ( data.attachment && data.attach && data.attach.choices ) { #>
				<li class="background-image-attach">
					<# if ( data.attach.label ) { #>
						<span class="customize-control-title">{{ data.attach.label }}</span>
					<# } #>
					<select {{{ data.attach.link }}}>
						<# _.each( data.attach.choices, function( label, choice ) { #>
							<option value="{{ choice }}" <# if ( choice === data.attach.value ) { #> selected="selected" <# } #>>{{ label }}</option>
						<# } ) #>
					</select>
				</li>
			<# } #>

			</div>

			<?php
		}

		/**
		 * Returns button labels.
		 *
		 * @since 1.0.0
		 */
		public static function get_button_labels() {

			$button_labels = array(
				'select'       => esc_html__( 'Select Image', 'audioman' ),
				'change'       => esc_html__( 'Change Image', 'audioman' ),
				'remove'       => esc_html__( 'Remove', 'audioman' ),
				'default'      => esc_html__( 'Default', 'audioman' ),
				'placeholder'  => esc_html__( 'No image selected', 'audioman' ),
				'frame_title'  => esc_html__( 'Select Image', 'audioman' ),
				'frame_button' => esc_html__( 'Choose Image', 'audioman' ),
			);

			return $button_labels;

		}

		/**
		 * Returns field labels.
		 *
		 * @since 1.0.0
		 */
		public static function get_field_labels() {

			$field_labels = array(
				'repeat'	=> esc_html__( 'Background Repeat', 'audioman' ),
				'size'		=> esc_html__( 'Background Size', 'audioman' ),
				'position'	=> esc_html__( 'Background Position', 'audioman' ),
				'attach'	=> esc_html__( 'Background Attachment', 'audioman' )
			);

			return $field_labels;

		}

		/**
		 * Returns the background choices.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public static function get_background_choices() {

			$choices = array(
				'repeat' => array(
					'no-repeat' => esc_html__( 'No Repeat', 'audioman' ),
					'repeat'    => esc_html__( 'Tile', 'audioman' ),
					'repeat-x'  => esc_html__( 'Tile Horizontally', 'audioman' ),
					'repeat-y'  => esc_html__( 'Tile Vertically', 'audioman' )
				),
				'size' => array(
					'auto'    => esc_html__( 'Default', 'audioman' ),
					'cover'   => esc_html__( 'Cover', 'audioman' ),
					'contain' => esc_html__( 'Contain', 'audioman' )
				),
				'position' => array(
					'left-top'      => esc_html__( 'Left Top', 'audioman' ),
					'left-center'   => esc_html__( 'Left Center', 'audioman' ),
					'left-bottom'   => esc_html__( 'Left Bottom', 'audioman' ),
					'right-top'     => esc_html__( 'Right Top', 'audioman' ),
					'right-center'  => esc_html__( 'Right Center', 'audioman' ),
					'right-bottom'  => esc_html__( 'Right Bottom', 'audioman' ),
					'center-top'    => esc_html__( 'Center Top', 'audioman' ),
					'center-center' => esc_html__( 'Center Center', 'audioman' ),
					'center-bottom' => esc_html__( 'Center Bottom', 'audioman' )
				),
				'attach' => array(
					'fixed'   => esc_html__( 'Fixed', 'audioman' ),
					'scroll'  => esc_html__( 'Scroll', 'audioman' )
				)
			);

			return $choices;

		}

	}

}
add_action( 'customize_register', 'audioman_custom_controls', 1 );

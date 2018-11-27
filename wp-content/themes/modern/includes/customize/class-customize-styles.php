<?php
/**
 * Customized Styles Class
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.0
 *
 * Contents:
 *
 *   0) Init
 *  10) CSS output
 *  20) Enqueue
 *  30) Setup
 * 100) Helpers
 */
class Modern_Customize_Styles {





	/**
	 * 0) Init
	 */

		private static $instance;



		/**
		 * Constructor
		 *
		 * @uses  `wmhook_modern_custom_styles` global hook
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		private function __construct() {

			// Requirements check

				if ( ! is_callable( 'Modern_Library_Customize_Styles::custom_styles' ) ) {
					/**
					 * There is really no point of running this
					 * if we don't have the functionality needed.
					 */
					return;
				}


			// Processing

				// Hooks

					// Actions

						add_action( 'wp_enqueue_scripts', __CLASS__ . '::inline_styles', 109 );

						add_action( 'wp_ajax_modern_editor_styles',         __CLASS__ . '::get_editor_custom_stylesheet' );
						add_action( 'wp_ajax_no_priv_modern_editor_styles', __CLASS__ . '::get_editor_custom_stylesheet' );

					// Filters

						add_filter( 'wmhook_modern_custom_styles', __CLASS__ . '::get_css_raw', 10, 2 );

						add_filter( 'wmhook_modern_assets_editor', __CLASS__ . '::editor_stylesheet' );

		} // /__construct



		/**
		 * Initialization (get instance)
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function init() {

			// Processing

				if ( null === self::$instance ) {
					self::$instance = new self;
				}


			// Output

				return self::$instance;

		} // /init





	/**
	 * 10) CSS output
	 */

		/**
		 * Get processed CSS string
		 *
		 * Note that this CSS output is not being escaped with `wmhook_modern_esc_css` global hook!
		 * You need to escape this CSS string on your own just before outputting it.
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $scope
		 */
		public static function get_css( $scope = '' ) {

			// Helper variables

				$output = '';


			// Processing

				if ( is_callable( 'Modern_Library_Customize_Styles::custom_styles' ) ) {
					/**
					 * self::get_css_raw() is hooked into the Modern_Library_Customize_Styles::custom_styles()
					 * and we get processed CSS string from it here.
					 */
					$output .= Modern_Library_Customize_Styles::custom_styles( $output, $scope );
				}


			// Output

				return (string) apply_filters( 'wmhook_modern_customize_styles_get_styles', $output );

		} // /get_css



		/**
		 * Get unprocessed, raw custom styles
		 *
		 * @uses  `wmhook_modern_generate_css_replacements` global hook
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $css
		 * @param  string $scope
		 */
		public static function get_css_raw( $css = '', $scope = '' ) {

			// Pre

				$pre = apply_filters( 'wmhook_modern_customize_styles_get_css_raw_pre', false, $css, $scope );

				if ( false !== $pre ) {
					return $pre;
				}


			// Helper variables

				$output = '';

				$replacements = (array) apply_filters( 'wmhook_modern_generate_css_replacements', array() );


			// Processing

				// Add CSS generated from array

					$output .= self::generate_css_from_array( (array) self::get_custom_styles_array( $scope ) );

				// Add stylesheets containing custom CSS variables

					$output .= "\r\n\r\n" . self::get_variable_styles( $scope );

				// Filter the output

					$output = (string) apply_filters( 'wmhook_modern_customize_styles_get_css_raw', $output, $scope );

				// Apply replacements

					if ( ! empty( $replacements ) ) {
						$output = strtr( $output, $replacements );
					}

				// CSS generator info comment

					$output .= "\r\n\r\n\r\n" . '/* ';
					$output .= sprintf( 'Using Modern theme by WebMan Design (https://www.webmandesign.eu), version %s.', MODERN_THEME_VERSION );
					$output .= ' ';
					$output .= sprintf( 'CSS generated on %s.', gmdate( 'Y/m/d H:i, e' ) );
					$output .= ' */';


			// Output

				return $output;

		} // /get_css_raw





	/**
	 * 20) Enqueue
	 */

		/**
		 * Enqueue HTML head inline styles
		 *
		 * @uses  `wmhook_modern_esc_css` global hook
		 *
		 * @since    2.0.0
		 * @version  2.2.0
		 */
		public static function inline_styles() {

			// Requirements check

				/**
				 * If `stylesheet-generator` is supported,
				 * we only enqueue styles in Customizer preview screen.
				 */
				if (
					current_theme_supports( 'stylesheet-generator' )
					&& ! is_customize_preview()
				) {
					return;
				}


			// Helper variables

				$output = trim( (string) self::get_css() );


			// Processing

				if ( ! empty( $output ) ) {

					wp_add_inline_style(
						'modern',
						(string) apply_filters( 'wmhook_modern_esc_css', $output )
					);

				}

		} // /inline_styles



		/**
		 * Enqueue custom styles into Visual editor using Ajax
		 *
		 * This only runs if the theme does not support `stylesheet-generator`.
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $visual_editor_stylesheets
		 */
		public static function editor_stylesheet( $visual_editor_stylesheets = array() ) {

			// Processing

				if ( ! current_theme_supports( 'stylesheet-generator' ) ) {
					/**
					 * @see  `stargazer_get_editor_styles` https://github.com/justintadlock/stargazer/blob/master/inc/stargazer.php
					 */
					$visual_editor_stylesheets[20] = add_query_arg( 'action', 'modern_editor_styles', admin_url( 'admin-ajax.php' ) );
				}


			// Output

				return $visual_editor_stylesheets;

		} // /editor_stylesheet



		/**
		 * Ajax callback for outputting custom styles for Visual editor
		 *
		 * This only runs if the theme does not support `stylesheet-generator`.
		 *
		 * @see  https://github.com/justintadlock/stargazer/blob/master/inc/custom-colors.php
		 * @uses  `wmhook_modern_esc_css` global hook
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function get_editor_custom_stylesheet() {

			// Requirements check

				if ( current_theme_supports( 'stylesheet-generator' ) ) {
					return;
				}


			// Processing

				header( 'Content-type: text/css' );

				echo apply_filters( 'wmhook_modern_esc_css', self::get_css( 'editor' ) );

				die();

		} // /get_editor_custom_stylesheet





	/**
	 * 30) Setup
	 */

		/**
		 * Getting array of more advanced custom styles
		 *
		 * These styles are set in array as they require more calculation
		 * and processing in oppose to custom CSS variables.
		 *
		 * @since    2.0.0
		 * @version  2.2.0
		 *
		 * @param  string $scope
		 */
		public static function get_custom_styles_array( $scope = '' ) {

			// Helper variables

				$output = array();

				$helper = apply_filters( 'wmhook_modern_customize_styles_get_custom_styles_array_helper', array(
					'layout_width_content' => 1200,
					'typography_size_html' => 16,
				), $scope );


			// Processing

				if ( empty( $scope ) ) {
				// Frontend styles

					$output = array(

						'customizer-styles-title' => array(
							'custom' => '/* Customizer styles: calculated, frontend */',
						),

						/**
						 * Typography
						 */

							'typography' => array(
								'custom' => '/* Typography */',
							),

							'typography-media-query-open' => array(
								'custom' => "\t" . '@media only screen and (min-width: 28em) {',
							),

								'typography-font-size-html' => array(
									'selector' => 'html',
									'styles'   => array(
										'font-size' => ( $helper['typography_size_html'] / 16 * 100 ) . '%',
									),
								),

							'typography-media-query-close' => array(
								'custom' => "\t" . '}',
							),

						/**
						 * Layout
						 */

							'layout' => array(
								'custom' => '/* Layout */',
							),

							'layout-width-content' => array(
								'selector' => implode( ', ', array(
									// All the selectors with `@extend %content_width;` from SASS files // $content_width
									'.site-header-inner',
									'.intro-inner',
									'.site-content-inner',
									'.front-page-section-inner',
									'.site-footer-area-inner',
								) ),
								'styles'   => array(
									'max-width|1' => $helper['layout_width_content'] . 'px',
									'max-width|2' => ( $helper['layout_width_content'] / $helper['typography_size_html'] ) . 'rem',
								),
							),

					);

				} else {
				// Visual Editor styles

					$output = array(

						'editor-' . 'customizer-styles-title' => array(
							'custom' => '/* Customizer styles: calculated, visual editor */',
						),

						/**
						 * Typography
						 */

							'editor-' . 'typography' => array(
								'custom' => '/* Typography */',
							),

							'editor-' . 'typography-font-size-html' => array(
								'selector' => 'html',
								'styles'   => array(
									'font-size' => '100%', // First, we have to reset the initial font size
								),
							),

							'editor-' . 'typography-media-query-open' => array(
								'custom' => "\t" . '@media only screen and (min-width: 28em) {',
							),

								'editor-' . 'typography-font-size-body' => array(
									'selector' => '.mce-content-body',
									'styles'   => array(
										'font-size' => ( $helper['typography_size_html'] / 16 * 100 ) . '%',
									),
								),

							'editor-' . 'typography-media-query-close' => array(
								'custom' => "\t" . '}',
							),

					);

				}


			// Output

				return (array) apply_filters( 'wmhook_modern_customize_styles_get_custom_styles_array', $output, $scope, $helper );

		} // /get_custom_styles_array



		/**
		 * Get styles from variable stylesheets
		 *
		 * The stylesheets containing custom CSS variables
		 * are enqueued and processed here,
		 * and pure CSS string is outputted.
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $scope
		 */
		public static function get_variable_styles( $scope = '' ) {

			// Requirements check

				if ( ! is_callable( 'Modern_Library_Customize_Styles::custom_styles' ) ) {
					return '';
				}


			// Helper variables

				/**
				 * These files are being loaded from a child theme first, if found.
				 * You can simply override these stylesheet files (`assets/css/custom-styles-$scope-$type.css`)
				 * by redefining them in your child theme.
				 *
				 * But it is probably better to just define a new additional stylesheet file of the scope in your
				 * child theme, such as `child-theme/assets/css/custom-styles-add.css` and do the magic there.
				 * IMPORTANT: So, do not create additional stylesheet files in parent theme's `assets/css/` folder!
				 */
				$types = apply_filters( 'wmhook_modern_customize_styles_get_variable_styles_types', array(

					// Frontend ($scope='') stylesheet types
					'' => array(
						'',    // Default stylesheet file of the scope.
						'add', // Additional stylesheet file of the scope.
					),

					// Visual editor ($scope='editor') stylesheet types
					'editor' => array(
						'',    // Default stylesheet file of the scope.
						'add', // Additional stylesheet file of the scope.
					),

				) );


			// Processing

				ob_start();

				if ( isset( $types[ $scope ] ) ) {
					foreach ( (array) $types[ $scope ] as $type ) {

						$file_path = 'assets/css/custom-styles-' . sanitize_file_name( $scope ) . '-' . sanitize_file_name( $type ) . '.css';
						$file_path = str_replace(
							array( '--', '-.' ),
							array( '-', '.' ),
							$file_path
						);

						locate_template( $file_path, true, false );

					}
				}


			// Output

				return ob_get_clean();

		} // /get_variable_styles





	/**
	 * 100) Helpers
	 */

		/**
		 * Turns styles array into CSS string
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $styles_array
		 */
		public static function generate_css_from_array( $styles_array = array() ) {

			// Requirements check

				if (
					empty( $styles_array )
					|| ! is_array( $styles_array )
				) {
					return '';
				}


			// Helper variables

				$output = '';


			// Processing

				foreach ( $styles_array as $selector ) {

					// Check condition first, if set

						if (
							isset( $selector['condition'] )
							&& ! trim( $selector['condition'] )
						) {
							continue;
						}

					// Process the array

						if (
							isset( $selector['selector'] )
							&& $selector['selector']
							&& isset( $selector['styles'] )
							&& is_array( $selector['styles'] )
							&& ! empty( $selector['styles'] )
						) {

							// When CSS selector and styles set up

								$selector_styles = $prepend = '';

								$prepend = ( ! isset( $selector['prepend'] ) ) ? ( "\t\t" ) : ( $selector['prepend'] );

								if ( is_array( $selector['selector'] ) ) {

									// Replace placeholders in selector string
									// array( 'selector string with {p}', 'placeholder' )

										$selector['selector'] = str_replace( '{p}', $selector['selector'][1], $selector['selector'][0] );

								}

								$selector['selector'] = str_replace( ', ', ",\r\n" . $prepend, $selector['selector'] );

								foreach ( $selector['styles'] as $property => $style ) {
									if ( trim( $style ) ) {

										// This is for multiple overridden properties

											if ( strpos( $property, '|' ) ) {
												$property = explode( '|', $property );
												$property = $property[0];
											}

										$selector_styles .= $prepend . "\t" . $property . ': ' . trim( $style ) . ';' . "\r\n";

									}
								} // /foreach

								if ( $selector_styles ) {
									$output .= $prepend . $selector['selector'] . ' {';
									$output .= "\r\n" . $selector_styles;
									$output .= $prepend . '}' . "\r\n\r\n";
								}

						} elseif (
							isset( $selector['custom'] )
							&& $selector['custom']
						) {

							// Custom texts

								$output .= "\r\n\t" . $selector['custom'] . "\r\n\r\n";

						}

				} // /foreach


			// Output

				return $output;

		} // /generate_css_from_array





} // /Modern_Customize_Styles

add_action( 'after_setup_theme', 'Modern_Customize_Styles::init' );

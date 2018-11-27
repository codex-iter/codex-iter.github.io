<?php
/**
 * Theme Customization Class
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.2
 *
 * Contents:
 *
 *   0) Init
 *  10) Options
 *  20) Replacements
 *  30) Active callbacks
 *  40) Partial refresh
 * 100) Helpers
 */
class Modern_Customize {





	/**
	 * 0) Init
	 */

		private static $instance;

		public static $transient_upgrade = 'display_upgrade_notice';



		/**
		 * Constructor
		 *
		 * @uses  `wmhook_modern_theme_options` global hook
		 * @uses  `wmhook_modern_generate_css_replacements` global hook
		 * @uses  `wmhook_modern_custom_styles_alphas` global hook
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		private function __construct() {

			// Processing

				// Setup

					// Indicate widget sidebars can use selective refresh in the Customizer

						add_theme_support( 'customize-selective-refresh-widgets' );

				// Hooks

					// Actions

						add_action( 'customize_register', __CLASS__ . '::setup' );

						add_action( 'wmhook_modern_library_theme_upgrade', __CLASS__ . '::upgrade_options', 10, 2 );

					// Filters

						add_filter( 'wmhook_modern_theme_options', __CLASS__ . '::options', 5 );

						add_filter( 'wmhook_modern_generate_css_replacements', __CLASS__ . '::css_replacements' );

						add_filter( 'wmhook_modern_custom_styles_alphas', __CLASS__ . '::rgba_alphas' );

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
	 * 10) Options
	 */

		/**
		 * Modify native WordPress options and setup partial refresh
		 *
		 * @since    2.0.0
		 * @version  2.2.0
		 *
		 * @param  object $wp_customize  WP customizer object.
		 */
		public static function setup( $wp_customize ) {

			// Processing

				// Move the custom logo option down

					$wp_customize->get_control( 'custom_logo' )->priority = 101;

				// Remove header color in favor of theme options

					$wp_customize->remove_control( 'header_textcolor' );

				// Partial refresh

					// Site title

						$wp_customize->selective_refresh->add_partial( 'blogname', array(
							'selector'        => '.site-title',
							'render_callback' => __CLASS__ . '::partial_blogname',
						) );

					// Site description

						$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
							'selector'        => '.site-description',
							'render_callback' => __CLASS__ . '::partial_blogdescription',
						) );

					// Site info (footer credits)

						$wp_customize->selective_refresh->add_partial( 'texts_site_info', array(
							'selector'            => '.site-info',
							'render_callback'     => __CLASS__ . '::partial_texts_site_info',
							'container_inclusive' => false,
						) );

					// Option pointers only

						// Posts columns

							$wp_customize->selective_refresh->add_partial( 'layout_posts_columns', array(
								'selector' => 'body:not(.page-template-_front) .posts',
							) );

						// "Front page" page template sections options

							$wp_customize->selective_refresh->add_partial( 'layout_location_front_blog', array(
								'selector' => '.front-page-section-type-post .front-page-section-inner',
							) );

						/**
						 * IMPORTANT
						 *
						 * For options with `preview_js` (ones that do not require page or partial refresh),
						 * we need to add a custom helper HTML so the option pointer can be hooked there.
						 * Here is an example of such setup.
						 *
						 * We need to add a helper HTML not to trigger content or page refresh with this option pointer.
						 * Only required for options with `preview_js` set.
						 */

							// Header image (we need to add this as the theme styles make the original pointer inaccessible)

								$wp_customize->selective_refresh->add_partial( 'header_image', array(
									'selector'            => '#intro-container .option-pointer',
									'render_callback'     => '__return_empty_string',
									'fallback_refresh'    => false,
									'container_inclusive' => false,
								) );

								add_action( 'wmhook_modern_intro_before', __CLASS__ . '::option_pointer_' . 'header_image', 0 );

							// Blog front page default intro title text

								$wp_customize->selective_refresh->add_partial( 'texts_intro', array(
									'selector'            => '.home.blog .intro-title .option-pointer',
									'render_callback'     => '__return_empty_string',
									'fallback_refresh'    => false,
									'container_inclusive' => false,
								) );

								add_filter( 'wmhook_modern_intro_title', __CLASS__ . '::option_pointer_' . 'texts_intro', 0 );

		} // /setup



			/**
			 * This is only required for options with `preview_js` set.
			 * Outputs a helper HTML for our option pointer so we don't trigger
			 * any content or page refresh.
			 */

				/**
				 * Option pointer: header_image
				 *
				 * @since    2.0.0
				 * @version  2.0.0
				 */
				public static function option_pointer_header_image() {

					// Output

						if ( is_customize_preview() ) {
							echo '<small class="option-pointer"></small>';
						}

				} // /option_pointer_header_image



				/**
				 * Option pointer: texts_intro
				 *
				 * @since    2.0.0
				 * @version  2.0.0
				 */
				public static function option_pointer_texts_intro( $title ) {

					// Processing

						if (
							is_customize_preview()
							&& is_front_page()
							&& is_home()
						) {
							$title = '<small class="option-pointer"></small>' . $title;
						}


					// Output

						return $title;

				} // /option_pointer_texts_intro



		/**
		 * Set theme options array
		 *
		 * @since    2.0.0
		 * @version  2.2.2
		 *
		 * @param  array $options
		 */
		public static function options( $options = array() ) {

			// Helper variables

				$alpha = (array) self::rgba_alphas();

				// Helper CSS selectors for `preview_js` (the "@" will be replaced with `selector_replace`)

					$h_tags  =   '@h1, @.h1';
					$h_tags .= ', @h2, @.h2';
					$h_tags .= ', @h3, @.h3';
					$h_tags .= ', @h4, @.h4';
					$h_tags .= ', @h5, @.h5';
					$h_tags .= ', @h6, @.h6';


			// Processing

				/**
				 * Theme customizer options array
				 */

					$options = array(

						/**
						 * Site identity: Logo image size
						 */
						'0' . 10 . 'logo' . 10 => array(
							'id'          => 'custom_logo_dimenstions_info',
							'section'     => 'title_tagline',
							'priority'    => 100,
							'type'        => 'html',
							'content'     => '<h3>' . esc_html__( 'Logo image', 'modern' ) . '</h3>',
							'description' => esc_html__( 'Please, do not forget to set the logo max height.', 'modern' ) . ' ' . esc_html__( 'To make your logo image ready for high DPI screens, please upload twice as big image.', 'modern' ),
						),

							'0' . 10 . 'logo' . 20 => array(
								'section'           => 'title_tagline',
								'priority'          => 102,
								'type'              => 'text',
								'id'                => 'custom_logo_height',
								'label'             => esc_html__( 'Max logo image height (px)', 'modern' ),
								'default'           => 100,
								'sanitize_callback' => 'absint',
								'input_attrs'       => array(
									'size'     => 5,
									'maxwidth' => 3,
								),
								'preview_js'        => array(
									'custom' => "jQuery( '.custom-logo' ).css( 'max-height', to + 'px' );",
								),
							),



						/**
						 * Theme credits
						 */
						'0' . 90 . 'placeholder' => array(
							'id'                   => 'placeholder',
							'type'                 => 'section',
							'create_section'       => '',
							'in_panel'             => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
							'in_panel-description' => '<h3>' . esc_html__( 'Theme Credits', 'modern' ) . '</h3>'
								. '<p class="description">'
								. sprintf(
									esc_html_x( '%1$s is a WordPress theme developed by %2$s.', '1: linked theme name, 2: theme author name.', 'modern' ),
									'<a href="' . esc_url( wp_get_theme( 'modern' )->get( 'ThemeURI' ) ) . '"><strong>' . esc_html( wp_get_theme( 'modern' )->get( 'Name' ) ) . '</strong></a>',
									'<strong>' . esc_html( wp_get_theme( 'modern' )->get( 'Author' ) ) . '</strong>'
								)
								. '</p>'
								. '<p class="description">'
								. sprintf(
									esc_html_x( 'You can obtain other professional WordPress themes at %s.', '%s: theme author link.', 'modern' ),
									'<strong><a href="' . esc_url( wp_get_theme( 'modern' )->get( 'AuthorURI' ) ) . '">' . esc_html( str_replace( 'http://', '', untrailingslashit( wp_get_theme( 'modern' )->get( 'AuthorURI' ) ) ) ) . '</a></strong>'
								)
								. '</p>'
								. '<p class="description">'
								. esc_html__( 'Thank you for using a theme by WebMan Design!', 'modern' )
								. '</p>',
						),



						/**
						 * Colors: Accents and predefined colors
						 *
						 * Don't use `preview_js` here as these colors affect too many elements.
						 */
						100 . 'colors' . 10 => array(
							'id'             => 'colors-accents',
							'type'           => 'section',
							'create_section' => sprintf( esc_html_x( 'Colors: %s', '%s = section name. Customizer section title.', 'modern' ), esc_html_x( 'Accents', 'Customizer color section title', 'modern' ) ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),



							/**
							 * Accent colors
							 */

								100 . 'colors' . 10 . 100 => array(
									'type'    => 'html',
									'content' => '<p class="description">' . esc_html__( 'These colors affect links, buttons and other elements.', 'modern' ) . '</p>',
								),

								100 . 'colors' . 10 . 200 => array(
									'type'    => 'html',
									'content' => '<h3>' . esc_html__( 'Primary accent color', 'modern' ) . '</h3>',
								),

									100 . 'colors' . 10 . 210 => array(
										'type'    => 'color',
										'id'      => 'color_accent',
										'label'   => esc_html__( 'Accent color', 'modern' ),
										'default' => '#00855b', // Changed in theme version 2.0.0 from #0aac8e to make it more accessible.
									),
									100 . 'colors' . 10 . 220 => array(
										'type'        => 'color',
										'id'          => 'color_accent_text',
										'label'       => esc_html__( 'Accent text color', 'modern' ),
										'description' => esc_html__( 'Color of text on accent color background.', 'modern' ),
										'default'     => '#ffffff',
									),



						/**
						 * Colors: Header
						 */
						100 . 'colors' . 20 => array(
							'id'             => 'colors-header',
							'type'           => 'section',
							'create_section' => sprintf( esc_html_x( 'Colors: %s', '%s = section name. Customizer section title.', 'modern' ), esc_html_x( 'Header', 'Customizer color section title', 'modern' ) ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),



							/**
							 * Header colors
							 */

								100 . 'colors' . 20 . 100 => array(
									'type'    => 'html',
									'content' => '<h3>' . esc_html__( 'Header', 'modern' ) . '</h3>',
								),

									100 . 'colors' . 20 . 110 => array(
										'type'        => 'color',
										'id'          => 'color_header_background',
										'label'       => esc_html__( 'Background color', 'modern' ),
										'description' => esc_html__( 'This color is also used to style a mobile device browser address bar.', 'modern' ) . ' <a href="https://wordpress.org/plugins/chrome-theme-color-changer/">' . esc_html__( 'You can further customize it with a dedicated plugin.', 'modern' ) . '</a>',
										'default'     => '#0a0c0e',
										'preview_js'  => array(
											'css' => array(

												'.site-header-navigation, .site-header-navigation::before' => array(
													'background-color'
												),
												'.main-navigation-container li ul' => array(
													'selector_before' => '@media only screen and (min-width: 55em) { ',
													'selector_after'  => ' }',
													'background-color',
												),
												'.has-navigation-mobile .main-navigation-container' => array(
													'selector_before' => '@media only screen and (max-width: 54.9375em) { ',
													'selector_after'  => ' }',
													'background-color',
												),

											),
										),
									),
									100 . 'colors' . 20 . 120 => array(
										'type'        => 'color',
										'id'          => 'color_header_text',
										'label'       => esc_html__( 'Text color', 'modern' ),
										'description' => esc_html__( 'This color affects navigation menu only.', 'modern' ) . ' ' . esc_html__( 'If you want to set a different header logo and social icons color, please see Colors: Intro options.', 'modern' ),
										'default'     => '#ffffff',
										'preview_js'  => array(
											'css' => array(

												'.site-header-navigation, .site-header-navigation::before' => array(
													'color',
													array(
														'property'         => 'border-color',
														'prefix'           => 'rgba(',
														'suffix'           => ',.' . $alpha[0] . ')',
														'process_callback' => 'modern.Customize.hexToRgbJoin',
													),
												),
												'.main-navigation-container li ul' => array(
													'selector_before' => '@media only screen and (min-width: 55em) { ',
													'selector_after'  => ' }',
													'color',
												),
												'.has-navigation-mobile .main-navigation-container' => array(
													'selector_before' => '@media only screen and (max-width: 54.9375em) { ',
													'selector_after'  => ' }',
													'color',
													array(
														'property'         => 'border-color',
														'prefix'           => 'rgba(',
														'suffix'           => ',.' . $alpha[0] . ')',
														'process_callback' => 'modern.Customize.hexToRgbJoin',
													),
												),

											),
										),
									),



						/**
						 * Colors: Intro
						 */
						100 . 'colors' . 25 => array(
							'id'             => 'colors-intro',
							'type'           => 'section',
							'create_section' => sprintf( esc_html_x( 'Colors: %s', '%s = section name. Customizer section title.', 'modern' ), esc_html_x( 'Intro', 'Customizer color section title', 'modern' ) ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),



							/**
							 * Intro title area colors
							 */

								100 . 'colors' . 25 . 100 => array(
									'type'        => 'html',
									'content'     => '<h3>' . esc_html__( 'Intro', 'modern' ) . '</h3>',
									'description' => esc_html__( 'This is a specially styled, main, dominant intro section (with optional slideshow) on front page, or site background image section on other pages.', 'modern' ) . '<br>' . esc_html__( 'The text color below also controls other text color on the website - see below.', 'modern' ),
								),

									100 . 'colors' . 25 . 110 => array(
										'type'        => 'color',
										'id'          => 'color_intro_background',
										'label'       => esc_html__( 'Background color', 'modern' ),
										'description' => esc_html__( 'This also controls the intro image overlay color.', 'modern' ),
										'default'     => '#1a1c1e',
										'preview_js'  => array(
											'css' => array(

												'.intro-media' => array(
													'background-color'
												),

											),
										),
									),
									100 . 'colors' . 25 . 120 => array(
										'type'        => 'color',
										'id'          => 'color_intro_text',
										'label'       => esc_html__( 'Text color', 'modern' ),
										'description' => esc_html__( 'This color is also applied on main site container, and thus on all elements outside the actual post or page content area (the elements that are displayed over the intro section or the website background, such as header logo and social icons).', 'modern' ),
										'default'     => '#ffffff',
										'preview_js'  => array(
											'css' => array(

												'.site-header-content, .intro-container, .intro-media' => array(
													'color',
												),

												'.site' => array(
													'color',
												),

											),
										),
									),



						/**
						 * Colors: Content
						 */
						100 . 'colors' . 30 => array(
							'id'             => 'colors-content',
							'type'           => 'section',
							'create_section' => sprintf( esc_html_x( 'Colors: %s', '%s = section name. Customizer section title.', 'modern' ), esc_html_x( 'Content', 'Customizer color section title', 'modern' ) ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),



							/**
							 * Content colors
							 */

								100 . 'colors' . 30 . 100 => array(
									'type'        => 'html',
									'content'     => '<h3>' . esc_html__( 'Content', 'modern' ) . '</h3>',
									'description' => esc_html__( 'These are colors for actual post or page content area (both in single post/page display and in posts lists).', 'modern' ),
								),

									100 . 'colors' . 30 . 110 => array(
										'type'       => 'color',
										'id'         => 'color_content_background',
										'label'      => esc_html__( 'Background color', 'modern' ),
										'default'    => '#ffffff',
										'preview_js' => array(
											'css' => array(

												'.is-singular .content-area, .page-template-_front .comments-area, .entry, .sidebar .widget' => array(
													'background-color'
												),

											),
										),
									),
									100 . 'colors' . 30 . 120 => array(
										'type'       => 'color',
										'id'         => 'color_content_text',
										'label'      => esc_html__( 'Text color', 'modern' ),
										'default'    => '#6a6c6e',
										'preview_js' => array(
											'css' => array(

												'.is-singular .content-area, .page-template-_front .comments-area, .entry, .sidebar .widget' => array(
													'color',
													array(
														'property'         => 'border-color',
														'prefix'           => 'rgba(',
														'suffix'           => ',.' . $alpha[0] . ')',
														'process_callback' => 'modern.Customize.hexToRgbJoin',
													),
												),

											),
										),
									),
									100 . 'colors' . 30 . 130 => array(
										'type'       => 'color',
										'id'         => 'color_content_headings',
										'label'      => esc_html__( 'Headings color', 'modern' ),
										'default'    => '#1a1c1e',
										'preview_js' => array(
											'css' => array(

												$h_tags . ', @.post-navigation, @.dropcap-text::first-letter, .dropcap-text::first-letter' => array(
													'selector_replace' => array(
														'.is-singular .content-area ',
														'.page-template-_front .comments-area ',
														'.entry ',
														'.sidebar .widget ',
													),
													'color'
												),

											),
										),
									),



						/**
						 * Colors: Footer
						 */
						100 . 'colors' . 40 => array(
							'id'             => 'colors-footer',
							'type'           => 'section',
							'create_section' => sprintf( esc_html_x( 'Colors: %s', '%s = section name. Customizer section title.', 'modern' ), esc_html_x( 'Footer', 'Customizer color section title', 'modern' ) ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),



							/**
							 * Footer colors
							 */

								100 . 'colors' . 40 . 100 => array(
									'type'        => 'html',
									'content'     => '<h3>' . esc_html__( 'Footer', 'modern' ) . '</h3>',
									'description' => esc_html__( 'The main footer widgets area is displayed only if it contains some widgets.', 'modern' ),
								),

									100 . 'colors' . 40 . 110 => array(
										'type'       => 'color',
										'id'         => 'color_footer_background',
										'label'      => esc_html__( 'Background color', 'modern' ),
										'default'    => '#eaecee',
										'preview_js' => array(
											'css' => array(

												'.site-footer' => array(
													'background-color'
												),
												'.site-footer mark, .site-footer #page mark, .site-footer .highlight, .site-footer #page .highlight, .site-footer .pagination .current, .site-footer .bypostauthor > .comment-body .comment-author::before, .site-footer .widget_calendar tbody a, .site-footer .widget .tagcloud a:hover, .site-footer .widget .tagcloud a:focus, .site-footer .widget .tagcloud a:active' => array(
													'color'
												),
												'.site-footer .button:hover, .site-footer .button:active, .site-footer .button:focus, .site-footer button:hover, .site-footer button:active, .site-footer button:focus, .site-footer input[type="button"]:hover, .site-footer input[type="button"]:active, .site-footer input[type="button"]:focus, .site-footer input[type="reset"]:hover, .site-footer input[type="reset"]:active, .site-footer input[type="reset"]:focus, .site-footer input[type="submit"]:hover, .site-footer input[type="submit"]:active, .site-footer input[type="submit"]:focus' => array(
													'color'
												),

											),
										),
									),
									100 . 'colors' . 40 . 120 => array(
										'type'       => 'color',
										'id'         => 'color_footer_text',
										'label'      => esc_html__( 'Text color', 'modern' ),
										'default'    => '#5a5c5e', // Changed in theme version 2.0.0 from #6a6c6e to make it more accessible.
										'preview_js' => array(
											'css' => array(

												'.site-footer' => array(
													'color',
													array(
														'property'         => 'border-color',
														'prefix'           => 'rgba(',
														'suffix'           => ',.' . $alpha[0] . ')',
														'process_callback' => 'modern.Customize.hexToRgbJoin',
													),
												),

											),
										),
									),
									100 . 'colors' . 40 . 130 => array(
										'type'       => 'color',
										'id'         => 'color_footer_headings',
										'label'      => esc_html__( 'Headings color', 'modern' ),
										'default'    => '#1a1c1e',
										'preview_js' => array(
											'css' => array(

												$h_tags . ', @a, @.accent-color' => array(
													'selector_replace' => '.site-footer ',
													'color'
												),
												'.site-footer mark, .site-footer #page mark, .site-footer .highlight, .site-footer #page .highlight, .site-footer .pagination .current, .site-footer .bypostauthor > .comment-body .comment-author::before, .site-footer .widget_calendar tbody a, .site-footer .widget .tagcloud a:hover, .site-footer .widget .tagcloud a:focus, .site-footer .widget .tagcloud a:active' => array(
													'background-color'
												),
												'.site-footer .button:hover, .site-footer .button:active, .site-footer .button:focus, .site-footer button:hover, .site-footer button:active, .site-footer button:focus, .site-footer input[type="button"]:hover, .site-footer input[type="button"]:active, .site-footer input[type="button"]:focus, .site-footer input[type="reset"]:hover, .site-footer input[type="reset"]:active, .site-footer input[type="reset"]:focus, .site-footer input[type="submit"]:hover, .site-footer input[type="submit"]:active, .site-footer input[type="submit"]:focus' => array(
													'background-color'
												),

											),
										),
									),



						/**
						 * Layout
						 */
						300 . 'layout' => array(
							'id'             => 'layout',
							'type'           => 'section',
							'create_section' => esc_html_x( 'Layout', 'Customizer section title.', 'modern' ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),

							300 . 'layout' . 100 => array(
								'type'        => 'radio',
								'id'          => 'layout_posts',
								'label'       => esc_html__( 'Posts list style', 'modern' ),
								'description' => esc_html__( 'Sets how posts, portfolio projects and testimonials are listed.', 'modern' ),
								'default'     => 'equal-height',
								'choices'     => array(
									'equal-height' => esc_html__( 'Equal row height list', 'modern' ),
									'masonry'      => esc_html__( 'Masonry list', 'modern' ),
								),
							),

							300 . 'layout' . 110 => array(
								'type'        => 'range',
								'id'          => 'layout_posts_columns',
								'label'       => esc_html__( 'Posts list columns', 'modern' ),
								'description' => esc_html__( 'Number of columns displayed in list of posts.', 'modern' ) .
								                 ' ' .
								                 esc_html__( 'Please note that sidebar affects the columns count if it is displayed on the page with list of posts.', 'modern' ) .
								                 '<br>' .
								                 esc_html__( 'You may also need to rise the thumbnail image size if you set this to 2 columns (see Settings &rarr; Media).', 'modern' ),
								'default'     => 3,
								'min'         => 2,
								'max'         => 4,
								'step'        => 1,
							),



							/**
							 * Front page template blog section
							 */

								300 . 'layout' . 200 => array(
									'type'    => 'html',
									'content' => '<h3>' .
									             '<small>' . esc_html__( 'Front page:', 'modern' ) . '</small> ' .
									             esc_html__( 'Blog section', 'modern' ) .
									             '</h3>' .
									             '<p class="description">' .
									             esc_html__( 'Options for setting up blog posts section on "Front page" template.', 'modern' ) .
									             '</p>',
								),

									300 . 'layout' . 210 => array(
										'type'    => 'select',
										'id'      => 'layout_location_front_blog',
										'label'   => esc_html__( 'Display location', 'modern' ),
										'choices' => self::front_page_section_locations(),
										'default' => 'tha_content_before|20',
									),

									300 . 'layout' . 220 => array(
										'type'    => 'range',
										'id'      => 'layout_posts_per_page_front_blog',
										'label'   => esc_html__( 'Posts count', 'modern' ),
										'default' => 6,
										'min'     => 0,
										'max'     => 12,
										'step'    => 1,
									),



						/**
						 * Texts
						 *
						 * Don't use `preview_js` here as it outputs escaped HTML.
						 */
						800 . 'texts' => array(
							'id'             => 'texts',
							'type'           => 'section',
							'create_section' => esc_html_x( 'Texts', 'Customizer section title.', 'modern' ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),

							800 . 'texts' . 100 => array(
								'type'              => 'textarea',
								'id'                => 'texts_intro',
								'label'             => esc_html__( 'Default blog intro text', 'modern' ),
								'description'       => esc_html__( 'This text will be displayed in intro section of your website front page only if latest posts are displayed there.', 'modern' ),
								'default'           => esc_html__( 'Welcome to our site!', 'modern' ),
								'sanitize_callback' => 'wp_kses_post',
								'preview_js'        => array(
									'custom' => "jQuery( '.home.blog .intro-title' ).html( '<small class=\"option-pointer\"></small>' + to ); if ( '' === to ) { jQuery( '.home.blog .intro-title' ).hide(); } else { jQuery( '.home.blog .intro-title:hidden' ).show(); }",
								),
								'active_callback'   => __CLASS__ . '::is_blog_front_page',
							),

							800 . 'texts' . 500 => array(
								'type'              => 'textarea',
								'id'                => 'texts_site_info',
								'label'             => esc_html__( 'Footer credits (copyright)', 'modern' ),
								'description'       => sprintf( esc_html__( 'Set %s to disable this area.', 'modern' ), '<code>-</code>' ) . ' ' . esc_html__( 'Leaving the field empty will fall back to default theme setting.', 'modern' ) . ' ' . sprintf( esc_html__( 'You can use %s to display dynamic, always current year.', 'modern' ), '<code>[year]</code>' ),
								'default'           => '',
								'sanitize_callback' => 'wp_kses_post',
								'preview_js'        => array(
									'custom' => "jQuery( '.site-info' ).html( to ); if ( '-' === to ) { jQuery( '.footer-area-site-info' ).hide(); } else { jQuery( '.footer-area-site-info:hidden' ).show(); }",
								),
							),



						/**
						 * Typography
						 */
						900 . 'typography' => array(
							'id'             => 'typography',
							'type'           => 'section',
							'create_section' => esc_html_x( 'Typography', 'Customizer section title.', 'modern' ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),

							900 . 'typography' . 100 => array(
								'type'              => 'range',
								'id'                => 'typography_size_html',
								'label'             => esc_html__( 'Basic font size in px', 'modern' ),
								'description'       => esc_html__( 'All other font sizes are calculated automatically from this basic font size.', 'modern' ),
								'default'           => 16,
								'min'               => 12,
								'max'               => 24,
								'step'              => 1,
								'suffix'            => 'px',
								'sanitize_callback' => 'absint',
								'preview_js'        => array(
									'css' => array(

										'html' => array(
											array(
												'property' => 'font-size',
												'suffix'   => 'px',
											),
										),

									),
								),
							),

							900 . 'typography' . 200 => array(
								'type'             => 'checkbox',
								'id'               => 'typography_custom_fonts',
								'label'            => esc_html__( 'Use custom fonts', 'modern' ),
								'default'          => false,
								'is_css_condition' => true,
							),

								900 . 'typography' . 210 => array(
									'type'    => 'html',
									'content' => '<h3>' . esc_html__( 'Custom fonts setup', 'modern' ) . '</h3>' .
									             '<p class="description">' .
									             sprintf(
									             		esc_html_x( 'This theme does not restrict you to choose from a predefined set of fonts. Instead, please use any font service (such as %s) plugin you like.', '%s: linked examples of web fonts libraries such as Google Fonts or Adobe Typekit.', 'modern' ),
									             		'<a href="http://www.google.com/fonts"><strong>Google Fonts</strong></a>, <a href="https://typekit.com/fonts"><strong>Adobe Typekit</strong></a>'
									             ) . '</p>' .
									             '<p class="description">' .
									             esc_html__( 'You can set your fonts plugin according to information provided below, or insert your custom font names (a value of "font-family" CSS property) directly into input fields (you still need to use a plugin to load those fonts on the website).', 'modern' ) .
									             '</p>',
									'active_callback' => __CLASS__ . '::is_typography_custom_fonts',
								),

								900 . 'typography' . 220 => array(
									'type'              => 'text',
									'id'                => 'typography_fonts_text',
									'label'             => esc_html__( 'General text font', 'modern' ),
									'default'           => "'Fira Sans', sans-serif",
									'input_attrs'       => array(
										'placeholder' => "'Fira Sans', sans-serif",
									),
									'active_callback'   => __CLASS__ . '::is_typography_custom_fonts',
									'sanitize_callback' => 'Modern_Library_Sanitize::fonts',
								),

								900 . 'typography' . 230 => array(
									'type'              => 'text',
									'id'                => 'typography_fonts_headings',
									'label'             => esc_html__( 'Headings font', 'modern' ),
									'default'           => "'Fira Sans', sans-serif",
									'input_attrs'       => array(
										'placeholder' => "'Fira Sans', sans-serif",
									),
									'active_callback'   => __CLASS__ . '::is_typography_custom_fonts',
									'sanitize_callback' => 'Modern_Library_Sanitize::fonts',
								),

								900 . 'typography' . 240 => array(
									'type'              => 'text',
									'id'                => 'typography_fonts_logo',
									'label'             => esc_html__( 'Logo font', 'modern' ),
									'default'           => "'Fira Sans', sans-serif",
									'input_attrs'       => array(
										'placeholder' => "'Fira Sans', sans-serif",
									),
									'active_callback'   => __CLASS__ . '::is_typography_custom_fonts',
									'sanitize_callback' => 'Modern_Library_Sanitize::fonts',
								),

								900 . 'typography' . 290 => array(
									'type'            => 'html',
									'content'         => '<h3>' . esc_html__( 'Info: CSS selectors', 'modern' ) . '</h3>'
										. '<p class="description">'
										. esc_html__( 'Here you can find CSS selectors list associated with each font group in the theme. You can use these in your custom font plugin settings.', 'modern' )
										. '</p>'

										. '<p>'
										. '<strong>' . esc_html__( 'General text font CSS selectors:', 'modern' ) . '</strong>'
										. '</p>'
										. '<pre>'
										. implode( ', ', array(
											'html',
											'.site .font-body',
										) )
										. '</pre>'

										. '<p>'
										. '<strong>' . esc_html__( 'Headings font CSS selectors:', 'modern' ) . '</strong>'
										. '</p>'
										. '<pre>'
										. implode( ', ', array(
											'.site .font-headings',
											'.site .font-headings-primary',

											'h1, .h1',
											'h2, .h2',
											'h3, .h3',
											'h4, .h4',
											'h5, .h5',
											'h6, .h6',
										) )
										. '</pre>'

										. '<p>'
										. '<strong>' . esc_html__( 'Logo font CSS selectors:', 'modern' ) . '</strong>'
										. '</p>'
										. '<pre>'
										. implode( ', ', array(
											'.site-title',
											'.site .font-logo',
											'.site .font-headings-secondary',

											'h1.display-1',
											'h1.display-2',
											'h1.display-3',
											'h1.display-4',

											'h2.display-1',
											'h2.display-2',
											'h2.display-3',
											'h2.display-4',

											'h3.display-1',
											'h3.display-2',
											'h3.display-3',
											'h3.display-4',

											'.h1.display-1',
											'.h1.display-2',
											'.h1.display-3',
											'.h1.display-4',

											'.h2.display-1',
											'.h2.display-2',
											'.h2.display-3',
											'.h2.display-4',

											'.h3.display-1',
											'.h3.display-2',
											'.h3.display-3',
											'.h3.display-4',
										) )
										. '</pre>',
									'active_callback' => __CLASS__ . '::is_typography_custom_fonts',
								),



						/**
						 * Others
						 */
						950 . 'others' => array(
							'id'             => 'others',
							'type'           => 'section',
							'create_section' => esc_html_x( 'Others', 'Customizer section title.', 'modern' ),
							'in_panel'       => esc_html_x( 'Theme Options', 'Customizer panel title.', 'modern' ),
						),

							950 . 'others' . 100 => array(
								'type'        => 'checkbox',
								'id'          => 'admin_welcome_page',
								'label'       => esc_html__( 'Show "Welcome" page', 'modern' ),
								'description' => esc_html__( 'Under "Appearance" WordPress dashboard menu.', 'modern' ),
								'default'     => true,
								'preview_js'  => false, // This is to prevent customizer preview reload
							),

							950 . 'others' . 110 => array(
								'type'        => 'checkbox',
								'id'          => 'navigation_mobile',
								'label'       => esc_html__( 'Enable mobile navigation', 'modern' ),
								'description' => esc_html__( 'If your website navigation is very simple and you do not want to use the mobile navigation functionality, you can disable it here.', 'modern' ),
								'default'     => true,
							),



					);


			// Output

				return $options;

		} // /options





	/**
	 * 20) Replacements
	 */

		/**
		 * CSS generator replacements
		 *
		 * You can also use a `SLASH**if(option_id)` and `endif(option_id)**SLASH`
		 * conditional CSS replacements. These CSS comments will get uncommented
		 * once there is a value set to `option_id`.
		 * (Don't forget to replace `SLASH` with `/` above when used in CSS.)
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $replacements
		 */
		public static function css_replacements( $replacements = array() ) {

			// Processing

				$replacements = array(
					'/*[*/'                            => '/** ', // Open a comment
					'/*]*/'                            => ' **/', // Close a comment
					'/*//'                             => '', // Remove a comment opening
					'//*/'                             => '', // Remove a comment closing
					'[[get_template_directory]]'       => untrailingslashit( get_template_directory() ),
					'[[get_stylesheet_directory]]'     => untrailingslashit( get_stylesheet_directory() ),
					'[[get_template_directory_uri]]'   => untrailingslashit( get_template_directory_uri() ),
					'[[get_stylesheet_directory_uri]]' => untrailingslashit( get_stylesheet_directory_uri() ),
				);


			// Output

				return $replacements;

		} // /css_replacements





	/**
	 * 30) Active callbacks
	 */

		/**
		 * Do you want to use custom fonts?
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $control
		 */
		public static function is_typography_custom_fonts( $control ) {

			// Helper variables

				$option = $control->manager->get_setting( 'typography_custom_fonts' );


			// Output

				return (bool) $option->value();

		} // /is_typography_custom_fonts



		/**
		 * Do we display latest posts on front page?
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function is_blog_front_page() {

			// Output

				return (bool) is_front_page() && is_home();

		} // /is_blog_front_page





	/**
	 * 40) Partial refresh
	 */

		/**
		 * Render the site title for the selective refresh partial
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function partial_blogname() {

			// Output

				bloginfo( 'name' );

		} // /partial_blogname



		/**
		 * Render the site tagline for the selective refresh partial
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function partial_blogdescription() {

			// Output

				bloginfo( 'description' );

		} // /partial_blogdescription



		/**
		 * Render the site info in the footer for the selective refresh partial
		 *
		 * @since    2.0.0
		 * @version  2.2.0
		 */
		public static function partial_texts_site_info() {

			// Helper variables

				$site_info_text = trim( Modern_Library_Customize::get_theme_mod( 'texts_site_info' ) );


			// Output

				if ( empty( $site_info_text ) ) {
					esc_html_e( 'Please set your website credits text or the theme default one will be displayed.', 'modern' );
				} else {
					echo str_replace(
						'[year]',
						esc_html( date_i18n( 'Y' ) ),
						(string) $site_info_text
					);
				}

		} // /partial_texts_site_info





	/**
	 * 100) Helpers
	 */

		/**
		 * Alpha values (%) for generating rgba() colors
		 *
		 * Values taken from `assets/scss/_setup.scss` file's `$border_opacity_from_text` variable.
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $alphas
		 */
		public static function rgba_alphas( $alphas = array() ) {

			// Output

				return array( 20 );

		} // /rgba_alphas



		/**
		 * Front page template sections locations
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function front_page_section_locations() {

			// Output

				return array(

					'' => esc_html__( 'Do not display', 'modern' ),

					'tha_content_before|10' => sprintf( esc_html_x( 'Above page content, position %d', '%d: Position priority number.', 'modern' ), 1 ),
					'tha_content_before|20' => sprintf( esc_html_x( 'Above page content, position %d', '%d: Position priority number.', 'modern' ), 2 ),
					'tha_content_before|30' => sprintf( esc_html_x( 'Above page content, position %d', '%d: Position priority number.', 'modern' ), 3 ),

					'tha_content_after|10' => sprintf( esc_html_x( 'Below page content, position %d', '%d: Position priority number.', 'modern' ), 1 ),
					'tha_content_after|20' => sprintf( esc_html_x( 'Below page content, position %d', '%d: Position priority number.', 'modern' ), 2 ),
					'tha_content_after|30' => sprintf( esc_html_x( 'Below page content, position %d', '%d: Position priority number.', 'modern' ), 3 ),

				);

		} // /front_page_section_locations



		/**
		 * Upgrade theme options
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $version_old
		 * @param  string $version_new
		 */
		public static function upgrade_options( $version_old, $version_new ) {

			// Helper variables

				$theme_mods = get_theme_mods();

				$version_mod_name  = '__theme_version';
				$version_mod_value = ( isset( $theme_mods[ $version_mod_name ] ) ) ? ( $theme_mods[ $version_mod_name ] ) : ( '0' );


			// Processing

				if ( version_compare( $version_mod_value, '2.0.0', '<' ) ) {

					$theme_mods_font_family = array();

					// Rename and remove options

						$theme_mods_rename = array(
							'color-text'           => 'color_intro_text',
							'color-accent'         => 'color_accent',
							'color-accent-text'    => 'color_accent_text',
							'banner-text'          => 'texts_intro',
							'font-size-body'       => 'typography_size_html',
							'font-family-body'     => 'typography_fonts_text',
							'font-family-headings' => 'typography_fonts_headings',
							'font-family-logo'     => 'typography_fonts_logo',
							'font-subset'          => false, // Just remove.
						);

						foreach ( $theme_mods_rename as $old => $new ) {
							if ( isset( $theme_mods[ $old ] ) ) {

								// Apply new option name

									if ( ! empty( $new ) ) {
										set_theme_mod( $new, $theme_mods[ $old ] );
										$theme_mods[ $new ] = $theme_mods[ $old ];

										if ( false !== strpos( $old, 'font-family' ) ) {
											$theme_mods_font_family[] = $new;
										}
									}

								// Remove old option

									remove_theme_mod( $old );
									unset( $theme_mods[ $old ] );

							}
						}

					// Upgrade typography options

						if ( ! empty( $theme_mods_font_family ) ) {

							$typography_custom = false;

							foreach ( $theme_mods_font_family as $mod ) {
								if ( isset( $theme_mods[ $mod ] ) ) {
									$css_font_family = explode( ':', (string) $theme_mods[ $mod ] );
									$css_font_family = $css_font_family[0] . ', sans-serif';
									set_theme_mod( $mod, $css_font_family );
									$typography_custom = true;
								}
							}

							set_theme_mod( 'typography_custom_fonts', $typography_custom );

							// Make sure we display 2.0.0 upgrade notice (that contains typography options upgrade info)

								$upgrade_notice   = (array) get_transient( self::$transient_upgrade );
								$upgrade_notice[] = '2.0.0'; // What admin notice to display?
								set_transient(
									self::$transient_upgrade,
									$upgrade_notice,
									7 * 24 * 60 * 60
								);

						}

				}

				// Save new version in theme mods

					set_theme_mod( $version_mod_name, $version_new );

		} // /upgrade_options





} // /Modern_Customize

add_action( 'after_setup_theme', 'Modern_Customize::init' );

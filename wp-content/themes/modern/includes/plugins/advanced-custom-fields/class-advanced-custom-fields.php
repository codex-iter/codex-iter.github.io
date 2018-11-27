<?php
/**
 * Advanced Custom Fields Class
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.1
 *
 * Contents:
 *
 *  0) Init
 * 10) Intro section
 * 20) Post formats section
 * 30) Any page builder setup
 */
class Modern_Advanced_Custom_Fields {





	/**
	 * 0) Init
	 */

		private static $instance;



		/**
		 * Constructor
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		private function __construct() {

			// Requirements check

				if ( ! is_admin() ) {
					return;
				}


			// Processing

				// Hooks

					// Actions

						add_action( 'init', __CLASS__ . '::intro' );

						add_action( 'init', __CLASS__ . '::post_format' );

						add_action( 'init', __CLASS__ . '::any_page_builder' );

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
	 * 10) Intro section
	 */

		/**
		 * Intro metabox
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function intro() {

			// Helper variables

				$group_no = 0;


			// Processing

				register_field_group( (array) apply_filters( 'wmhook_modern_acf_register_field_group', array(
					'id'     => 'modern_intro_options',
					'title'  => esc_html__( 'Intro options', 'modern' ),
					'fields' => array(

						// Custom intro text

							100 => array(
								'key'          => 'modern_intro_text',
								'label'        => esc_html__( 'Custom intro text', 'modern' ),
								'instructions' => esc_html__( 'Here you can override the default intro section text with a custom one (no HTML supported).', 'modern' ) . '<br>' . esc_html__( 'Works only when this page is set as static front page, or when this post is displayed in front page slideshow.', 'modern' ),
								'name'         => 'banner_text', // Using old name "banner_text" for backwards compatibility.
								'type'         => 'text',
							),

						// Custom intro image

							200 => array(
								'key'          => 'modern_intro_image',
								'label'        => esc_html__( 'Custom intro image', 'modern' ),
								'instructions' => esc_html__( 'Here you can override the default intro section image with a custom one.', 'modern' ),
								'name'         => 'banner_image', // Using old name "banner_image" for backwards compatibility.
								'type'         => 'image',
								'save_format'  => 'id',
								'preview_size' => 'thumbnail',
								'library'      => 'all',
							),

					),
					'location' => array(

						// Display everywhere except:
						// - Attachments CPT,
						// - Beaver Builder/Themer CPTs,
						// - WooCommerce orders CPT,
						// - WooSidebars related CPTs,

							100 => array(

								// CPTs

									100 => array(
										'param'    => 'post_type',
										'operator' => '!=',
										'value'    => 'attachment',
										'order_no' => 0,
										'group_no' => $group_no++,
									),

									200 => array(
										'param'    => 'post_type',
										'operator' => '!=',
										'value'    => 'fl-builder-template',
										'order_no' => 0,
										'group_no' => $group_no++,
									),

										210 => array(
											'param'    => 'post_type',
											'operator' => '!=',
											'value'    => 'fl-theme-layout',
											'order_no' => 0,
											'group_no' => $group_no++,
										),

									300 => array(
										'param'    => 'post_type',
										'operator' => '!=',
										'value'    => 'shop_order',
										'order_no' => 0,
										'group_no' => $group_no++,
									),

									400 => array(
										'param'    => 'post_type',
										'operator' => '!=',
										'value'    => 'sidebar',
										'order_no' => 0,
										'group_no' => $group_no++,
									),

							),

					),
					'options' => array(
						'position'       => 'normal',
						'layout'         => 'default',
						'hide_on_screen' => array(),
					),
					'menu_order' => 20,
				), 'intro', $group_no ) );

		} // /intro





	/**
	 * 20) Post formats section
	 */

		/**
		 * Post formats metabox
		 *
		 * @since    2.0.0
		 * @version  2.0.1
		 */
		public static function post_format() {

			// Helper variables

				$group_no = 0;


			// Processing

				register_field_group( (array) apply_filters( 'wmhook_modern_acf_register_field_group', array(
					'id'     => 'modern_page_format_options',
					'title'  => esc_html__( 'Quote post format', 'modern' ),
					'fields' => array(

						// Quote source

							100 => array(
								'key'          => 'modern_quote_source',
								'label'        => esc_html__( 'Quote source', 'modern' ),
								'instructions' => esc_html__( 'No HTML tags are supported here.', 'modern' ),
								'name'         => 'quote_source',
								'type'         => 'text',
							),

					),
					'location' => array(

						// Display on Pages

							100 => array(

								100 => array(
									'param'    => 'post_format',
									'operator' => '==',
									'value'    => 'quote',
									'order_no' => 0,
									'group_no' => $group_no++,
								),

							),

					),
					'options' => array(
						'position'       => 'normal',
						'layout'         => 'default',
						'hide_on_screen' => array(),
					),
					'menu_order' => 20,
				), 'post_format', $group_no ) );

		} // /post_format





	/**
	 * 30) Any page builder setup
	 */

		/**
		 * Post modifiers to support any page builder
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function any_page_builder() {

			// Helper variables

				$group_no = 0;


			// Processing

				register_field_group( (array) apply_filters( 'wmhook_modern_acf_register_field_group', array(
					'id'     => 'modern_any_page_builder',
					'title'  => esc_html__( 'Page builder layout', 'modern' ),
					'fields' => array(

						100 => array(
							'key'           => 'modern_content_layout',
							'label'         => esc_html__( 'Content area layout', 'modern' ),
							'name'          => 'content_layout',
							'type'          => 'radio',
							'choices'       => array(
								''            => esc_html__( 'Leave as is', 'modern' ),
								/**
								 * Not needed in this theme due to its design: it's always a boxed layout only,
								 * and it makes no sense to have the `no-paddings` options when it has basically
								 * the same effect as `stretched` one, while `stretched` option description
								 * is more imaginable, more user friendly.
								 */
								// 'no-paddings' => esc_html__( 'Remove content paddings only', 'modern' ),
								'stretched'   => esc_html__( 'Fullwidth content with no paddings', 'modern' ),
							),
							'instructions'  => esc_html__( 'As every page builder plugin works differently, set this according to your needs.', 'modern' ),
							'default_value' => '',
						),

					),
					'location' => array(

						// Display everywhere except:
						// - blog page,
						// - Attachments CPT,
						// - Jetpack CPTs,
						// - WooCommerce orders CPT,
						// - WooSidebars related CPTs,

							100 => array(

								100 => array(
									'param'    => 'page_type',
									'operator' => '!=',
									'value'    => 'posts_page',
									'order_no' => 0,
									'group_no' => $group_no++,
								),

								200 => array(
									'param'    => 'post_type',
									'operator' => '!=',
									'value'    => 'attachment',
									'order_no' => 0,
									'group_no' => $group_no++,
								),

								300 => array(
									'param'    => 'post_type',
									'operator' => '!=',
									'value'    => 'jetpack-testimonial',
									'order_no' => 0,
									'group_no' => $group_no++,
								),

								400 => array(
									'param'    => 'post_type',
									'operator' => '!=',
									'value'    => 'shop_order',
									'order_no' => 0,
									'group_no' => $group_no++,
								),

								500 => array(
									'param'    => 'post_type',
									'operator' => '!=',
									'value'    => 'sidebar',
									'order_no' => 0,
									'group_no' => $group_no++,
								),

							),

					),
					'options' => array(
						'position'       => 'side',
						'layout'         => 'default',
						'hide_on_screen' => array(),
					),
					'menu_order' => 20,
				), 'any_page_builder', $group_no ) );

		} // /any_page_builder





} // /Modern_Advanced_Custom_Fields

add_action( 'after_setup_theme', 'Modern_Advanced_Custom_Fields::init' );

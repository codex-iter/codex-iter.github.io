<?php
/**
 * Loading theme functionality
 *
 * @link  https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  2.0.0
 *
 * Contents:
 *
 *   0) Paths
 *   1) Theme framework
 *  10) Theme setup
 *  20) Frontend
 *  30) Features
 * 999) Plugins integration
 */





/**
 * 0) Paths
 */

	// Theme directory path

		define( 'MODERN_PATH', trailingslashit( get_template_directory() ) );

	// Includes path

		define( 'MODERN_PATH_INCLUDES', trailingslashit( MODERN_PATH . 'includes' ) );

		// Plugin compatibility files

			define( 'MODERN_PATH_PLUGINS', trailingslashit( MODERN_PATH_INCLUDES . 'plugins' ) );





/**
 * 1) Theme framework
 */

	require MODERN_PATH . 'library/init.php';





/**
 * 10) Theme setup
 */

	require MODERN_PATH_INCLUDES . 'setup/class-setup.php';

	require MODERN_PATH_INCLUDES . 'starter-content/class-starter-content.php';





/**
 * 20) Frontend
 */

	// Theme Hook Alliance

		require MODERN_PATH_INCLUDES . 'frontend/theme-hook-alliance.php';

	// SVG

		require MODERN_PATH_INCLUDES . 'frontend/class-svg.php';

	// Assets (styles and scripts)

		require MODERN_PATH_INCLUDES . 'frontend/class-assets.php';

	// Header

		require MODERN_PATH_INCLUDES . 'frontend/class-header.php';

	// Menu

		require MODERN_PATH_INCLUDES . 'frontend/class-menu.php';

	// Content

		require MODERN_PATH_INCLUDES . 'frontend/class-content.php';

	// Loop

		require MODERN_PATH_INCLUDES . 'frontend/class-loop.php';

	// Post

		require MODERN_PATH_INCLUDES . 'frontend/class-post.php';
		require MODERN_PATH_INCLUDES . 'frontend/class-post-summary.php';
		require MODERN_PATH_INCLUDES . 'frontend/class-post-media.php';

	// Footer

		require MODERN_PATH_INCLUDES . 'frontend/class-footer.php';

	// Sidebars (widget areas)

		require MODERN_PATH_INCLUDES . 'frontend/class-sidebar.php';





/**
 * 30) Features
 */

	// Theme Customization

		require MODERN_PATH_INCLUDES . 'customize/class-customize.php';
		require MODERN_PATH_INCLUDES . 'customize/class-customize-styles.php';

	// Custom Header / Intro

		require MODERN_PATH_INCLUDES . 'custom-header/class-intro.php';

	// Post Formats

		require MODERN_PATH_INCLUDES . 'post-formats/class-post-formats.php';





/**
 * 999) Plugins integration
 */

	// Advanced Custom Fields

		if ( function_exists( 'register_field_group' ) && is_admin() ) {
			require MODERN_PATH_PLUGINS . 'advanced-custom-fields/advanced-custom-fields.php';
		}

	// Beaver Builder

		if ( class_exists( 'FLBuilder' ) ) {
			/**
			 * Upgrade link URL
			 *
			 * @since    2.0.0
			 * @version  2.0.0
			 *
			 * @param  string $url
			 */
			function modern_beaver_builder_upgrade_url( $url ) {
				return esc_url( add_query_arg( 'fla', '67', $url ) );
			}
			add_filter( 'fl_builder_upgrade_url', 'modern_beaver_builder_upgrade_url' );
		}

	// Breadcrumb NavXT

		if ( function_exists( 'bcn_display' ) ) {
			require MODERN_PATH_PLUGINS . 'breadcrumb-navxt/breadcrumb-navxt.php';
		}

	// Jetpack

		if ( class_exists( 'Jetpack' ) ) {
			require MODERN_PATH_PLUGINS . 'jetpack/jetpack.php';
		}

	// NS Featured Posts

		if ( class_exists( 'NS_Featured_Posts' ) ) {
			require MODERN_PATH_PLUGINS . 'ns-featured-posts/ns-featured-posts.php';
		}

	// One Click Demo Import

		if ( class_exists( 'OCDI_Plugin' ) && is_admin() ) {
			require MODERN_PATH_PLUGINS . 'one-click-demo-import/one-click-demo-import.php';
		}

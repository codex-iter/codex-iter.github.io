<?php
/**
 * Plugin compatibility file.
 *
 * NS Featured Posts
 *
 * @link  https://wordpress.org/plugins/ns-featured-posts/
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 *
 * Contents:
 *
 *  1) Requirements check
 * 10) Plugin integration
 */





/**
 * 1) Requirements check
 */

	if ( ! class_exists( 'NS_Featured_Posts' ) ) {
		return;
	}





/**
 * 20) Plugin integration
 */

	require MODERN_PATH_PLUGINS . 'ns-featured-posts/class-ns-featured-posts.php';

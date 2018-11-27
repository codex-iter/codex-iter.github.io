<?php
/**
 * Plugin compatibility file.
 *
 * One Click Demo Import
 *
 * @link  https://wordpress.org/plugins/one-click-demo-import/
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

	if (
		! class_exists( 'OCDI_Plugin' )
		|| ! is_admin()
	) {
		return;
	}





/**
 * 20) Plugin integration
 */

	require MODERN_PATH_PLUGINS . 'one-click-demo-import/class-one-click-demo-import.php';

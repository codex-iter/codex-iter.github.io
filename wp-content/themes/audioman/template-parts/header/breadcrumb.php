<?php
/**
 * Display Breadcrumb
 *
 * @package Audioman
 */
?>

<?php

if ( ! get_theme_mod( 'audioman_breadcrumb_option', 1 ) ) {
	// Bail if breadcrumb is disabled.
	return;
}
	audioman_breadcrumb();
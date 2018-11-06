<?php
/**
 * Uninstalls WPForms.
 *
 * Removes:
 * - Entries table
 * - Entry Meta table
 * - Entry fields table
 * - Form Preview page
 * - wpforms_log post type posts and post_meta
 * - wpforms post type posts and post_meta
 * - WPForms settings/options
 * - WPForms Uploads
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.4.5
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2018, WPForms LLC
 */

 // Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Confirm user has decided to remove all data, otherwise stop.
$settings = get_option( 'wpforms_settings', array() );
if ( empty( $settings['uninstall-data'] ) ) {
	return;
}

global $wpdb;

// Delete entries table.
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'wpforms_entries' );

// Delete entry meta table.
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'wpforms_entry_meta' );

// Delete entry fields table.
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'wpforms_entry_fields' );

// Delete Preview page.
$preview = get_option( 'wpforms_preview_page', false );
if ( ! empty( $preview ) ) {
	wp_delete_post( $preview, true );
}

// Delete wpform and wpform_log post type posts/post_meta.
$posts = get_posts( array(
	'post_type'   => array( 'wpforms_log', 'wpforms' ),
	'post_status' => 'any',
	'numberposts' => -1,
	'fields'      => 'ids',
) );
if ( $posts ) {
	foreach ( $posts as $post ) {
		wp_delete_post( $post, true);
	}
}

// Delete plugin settings.
delete_option( 'wpforms_version' );
delete_option( 'wpforms_providers' );
delete_option( 'wpforms_license' );
delete_option( 'wpforms_license_updates' );
delete_option( 'wpforms_settings' );
delete_option( 'wpforms_version_upgraded_from' );
delete_option( 'wpforms_preview_page' );
delete_option( 'wpforms_zapier_apikey' );
delete_option( 'wpforms_activated' );
delete_option( 'wpforms_review' );
delete_option( 'wpforms_imported' );

// Remove any transients we've left behind.
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_wpforms\_%'" );
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_site\_transient\_wpforms\_%'" );
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_timeout\_wpforms\_%'" );
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_site\_transient\_timeout\_wpforms\_%'" );

// Remove uploaded files.
$uploads_directory = wp_upload_dir();
if ( ! empty( $uploads_directory['error'] ) ) {
	return;
}
global $wp_filesystem;
$wp_filesystem->rmdir( $uploads_directory['basedir'] . '/wpforms/', true );

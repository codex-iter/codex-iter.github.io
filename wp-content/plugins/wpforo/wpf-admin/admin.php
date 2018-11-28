<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpforo_add_menu() {

	if ( WPF()->tools_antispam['spam_file_scanner'] ) {
		WPF()->moderation->spam_attachment();
	}

	$mod_count     = WPF()->post->unapproved_count();
	$mod_count_num = intval( $mod_count );
	$mod_count     = ( $mod_count ) ? ' <span class="awaiting-mod count-1"><span class="pending-count">' . intval( $mod_count ) . '</span></span> ' : '';
	$ban_count     = WPF()->member->banned_count();
	$ban_count_num = intval( $ban_count );
	$ban_count     = ( $ban_count ) ? ' <span class="awaiting-mod count-1" style="background-color:#777777;"><span class="pending-count" style="color:#ffffff;">' . intval( $ban_count ) . '</span></span> ' : '';
	$all_count     = $mod_count_num;
	$all_count     = ( $all_count ) ? ' <span class="awaiting-mod count-1"><span class="pending-count">' . intval( $all_count ) . '</span></span> ' : '';

	$position = ( isset( WPF()->general_options['menu_position'] ) && WPF()->general_options['menu_position'] > 0 ) ? WPF()->general_options['menu_position'] : 23;

	if ( wpforo_current_user_is( 'admin' ) || WPF()->perm->usergroup_can( 'mf' )  ||
            WPF()->perm->usergroup_can( 'ms' )  || WPF()->perm->usergroup_can( 'vm' )  ||
            WPF()->perm->usergroup_can( 'mp' )  || WPF()->perm->usergroup_can( 'aum' ) ||
            WPF()->perm->usergroup_can( 'vmg' ) || WPF()->perm->usergroup_can( 'mth' )
    ) {
		add_menu_page( __( 'Dashboard', 'wpforo' ), __( 'Forums', 'wpforo' ) . $all_count, 'read', 'wpforo-community', 'wpforo_toplevel_page', 'dashicons-format-chat', $position );
		add_submenu_page( 'wpforo-community', __( 'Dashboard', 'wpforo' ), __( 'Dashboard', 'wpforo' ), 'read', 'wpforo-community', 'wpforo_toplevel_page' );
	}
	if ( WPF()->perm->usergroup_can( 'mf' ) || wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Forums', 'wpforo' ), __( 'Forums', 'wpforo' ), 'read', 'wpforo-forums', 'wpforo_forum_menu' );
	}
	if ( WPF()->perm->usergroup_can( 'ms' ) || wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Settings', 'wpforo' ), __( 'Settings', 'wpforo' ), 'read', 'wpforo-settings', 'wpforo_settings' );
	}
	if ( WPF()->perm->usergroup_can( 'mt' ) || wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Tools', 'wpforo' ), __( 'Tools', 'wpforo' ), 'read', 'wpforo-tools', 'wpforo_tools' );
	}
	if ( WPF()->perm->usergroup_can( 'aum' ) || wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Moderation', 'wpforo' ), __( 'Moderation', 'wpforo' ) . $mod_count, 'read', 'wpforo-moderations', 'wpforo_moderations' );
	}
	if ( WPF()->perm->usergroup_can( 'vm' ) || wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Members', 'wpforo' ), __( 'Members', 'wpforo' ) . $ban_count, 'read', 'wpforo-members', 'wpforo_member_menu' );
	}
	if ( WPF()->perm->usergroup_can( 'vmg' ) || wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Usergroups', 'wpforo' ), __( 'Usergroups', 'wpforo' ), 'read', 'wpforo-usergroups', 'wpforo_usergroups_menu' );
	}
	if ( WPF()->perm->usergroup_can( 'mp' ) || wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Phrases', 'wpforo' ), __( 'Phrases', 'wpforo' ), 'read', 'wpforo-phrases', 'wpforo_phrases' );
	}
	if ( WPF()->perm->usergroup_can( 'mth' ) || wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Themes', 'wpforo' ), __( 'Themes', 'wpforo' ), 'read', 'wpforo-themes', 'wpforo_themes' );
	}
	if ( wpforo_current_user_is( 'admin' ) ) {
		add_submenu_page( 'wpforo-community', __( 'Addons', 'wpforo' ), __( 'Addons', 'wpforo' ), 'read', 'wpforo-addons', 'wpforo_addons' );
	}
}

add_action( 'admin_menu', 'wpforo_add_menu', 39 );

function wpforo_toplevel_page() {
	require( WPFORO_DIR . '/wpf-admin/dashboard.php' );
}

function wpforo_forum_menu() {
	require( WPFORO_DIR . '/wpf-admin/forum.php' );
}

function wpforo_member_menu() {
	require( WPFORO_DIR . '/wpf-admin/member.php' );
}

function wpforo_usergroups_menu() {
	require( WPFORO_DIR . '/wpf-admin/usergroup.php' );
}

function wpforo_settings() {
	require( WPFORO_DIR . '/wpf-admin/options.php' );
}

function wpforo_themes() {
	require( WPFORO_DIR . '/wpf-admin/themes.php' );
}

function wpforo_phrases() {
	require( WPFORO_DIR . '/wpf-admin/phrase.php' );
}

function wpforo_integrations() {
	require( WPFORO_DIR . '/wpf-admin/integration.php' );
}

function wpforo_addons() {
	require( WPFORO_DIR . '/wpf-admin/addons.php' );
}

function wpforo_tools() {
	require( WPFORO_DIR . '/wpf-admin/tools.php' );
}

function wpforo_moderations() {
	require( WPFORO_DIR . '/wpf-admin/moderation.php' );
}

function wpforo_deactivation_dialog() {
	if ( ! get_option( 'wpforo_deactivation_dialog_never_show' ) && ( strpos( wpforo_get_request_uri(), '/plugins.php' ) !== false ) ) {
		require( WPFORO_DIR . '/wpf-admin/deactivation-dialog.php' );
	}
}

add_action( 'admin_footer', 'wpforo_deactivation_dialog' );
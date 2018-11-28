<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('vm') ) exit;
?>

<div id="wpf-admin-wrap" class="wrap">
	<?php wpforo_screen_option() ?>
	<div id="icon-users" class="icon32"><br></div>
	<h2 style="padding:30px 0px 0px 0px;line-height: 20px; margin-bottom:15px;"><?php _e('Members', 'wpforo'); ?></h2>
	<?php WPF()->notice->show(FALSE) ?>
	<?php if(!isset( $_GET['action'] ) || ( isset( $_GET['action']) &&  $_GET['action'] == -1 ) ) : ?>
		<?php 
			$fields[] = 'display_name';
			$search_fields[] = 'title';
			$search_fields[] = 'display_name';
			$filter_fields = array();
			if(WPF()->perm->usergroup_can('vmu')){
				$fields[] = 'user_login';
				$search_fields[] = 'user_login';
			}
			if(WPF()->perm->usergroup_can('vmm')){
				$fields[] = 'user_email';
				$search_fields[] = 'user_email';
			}
			if(WPF()->perm->usergroup_can('vmg')){
				$fields[] = 'groupid';
				$filter_fields[] = 'groupid';
			}
			if( WPF()->perm->usergroup_can('bm') ){
				$fields[] = 'status';
				$filter_fields[] = 'status';
			}
            $fields[] = 'last_login';
            $fields[] = 'posts';
			if(WPF()->perm->usergroup_can('vms')){
				$search_fields[] = 'signature';
			}
			$actions = array('button');
			if( WPF()->perm->usergroup_can('em') ) $actions = array('edit_user', 'edit_profile');
			if( WPF()->perm->usergroup_can('bm') ){
				$actions[] = 'ban';
				$bulk_actions[] = 'ban';
				$bulk_actions[] = 'unban';
			}
			if( WPF()->perm->usergroup_can('dm') ){
				$actions[] = 'user_delete';
				$bulk_actions[] = 'del';
			}
			wpforo_create_form_table('member', 'userid', $fields, $search_fields, $filter_fields, $actions, $bulk_actions);
		?>
	<?php endif; ?>
</div>
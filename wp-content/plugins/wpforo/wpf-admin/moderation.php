<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('aum') ) exit;
?>

<div id="wpf-admin-wrap" class="wrap" style="margin-top: 0px">
	<?php wpforo_screen_option() ?>
	<div id="icon-users" class="icon32"><br></div>
	<h2 style="padding:30px 0px 0px 0px;line-height: 20px; margin-bottom:15px;"><?php _e('Topic and Post Moderation', 'wpforo'); ?></h2>
	<?php WPF()->notice->show(FALSE) ?>
	<?php
		if( !((isset($_GET['action']) && $_GET['action'] != '-1') || (isset($_GET['action2']) && $_GET['action2'] != '-1')) ){
			$fields = array( 'title', 'is_first_post', 'userid', 'created' );
			$search_fields = array( 'title', 'body' );
			$filter_fields = array( 'status', 'userid' );
			$actions = array('view', 'approve', 'delete');
			$bulk_actions = array('approve', 'unapprove', 'del');
			wpforo_create_form_table( 'moderation', 'postid', $fields, $search_fields, $filter_fields, $actions, $bulk_actions);
		}
	?>
</div>
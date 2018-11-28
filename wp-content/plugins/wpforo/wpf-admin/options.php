<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('ms') ) exit;
?>

<?php $plugins = true; ?>
<div id="icon-tools" class="icon32"><br></div><div class="wrap"><h2 style="padding:20px 0px 30px 0px;line-height: 20px;"><?php _e('Forum Settings', 'wpforo') ?></h2></div>
<?php WPF()->notice->show(FALSE) ?>
<?php do_action('wpforo_settings_page_top') ?>
<div id="wpf-admin-wrap" class="wrap"><div id="icon-users" class="icon32"><br /></div>
<?php
	$tabs = array( 
		'general' => __('General', 'wpforo'), 
		'forums' => __('Forums', 'wpforo'), 
		'accesses' => __('Forum Accesses', 'wpforo'),
		'posts' => __('Topics &amp; Posts', 'wpforo'), 
		'members' => __('Members', 'wpforo'),
		'emails' => __('Emails', 'wpforo'),
		'features' => __('Features', 'wpforo'),
	);
	if( !empty( WPF()->tpl->options['styles'] ) ) $tabs['styles'] = __('Styles', 'wpforo');
	$tabs['api'] = __('API\'s', 'wpforo');
	if( $plugins ) $tabs['plugins'] = __('Addons', 'wpforo');
	wpforo_admin_options_tabs( $tabs, ( isset($_GET['tab']) ? $_GET['tab'] : 'general' ) ); 
	?>
    <div class="wpf-info-bar"><br />
		<?php 
			if(isset($_GET['tab'])){
				switch($_GET['tab']){
					case 'accesses':
						include( 'options-tabs/accesses.php' );
					break;
					case 'posts':
						include( 'options-tabs/posts.php' );
					break;
					case 'forums':
						include( 'options-tabs/forums.php' );
					break;
					case 'members':
						include( 'options-tabs/members.php' );
					break;
					case 'features':
						include( 'options-tabs/features.php' );
					break;
					case 'styles':
						include( 'options-tabs/styles.php' );
					break;
					case 'emails':
						include( 'options-tabs/emails.php' );
					break;
					case 'api':
						include( 'options-tabs/api.php' );
					break;
					case 'plugins':
						include( 'options-tabs/plugins.php' );
					break;
					default:
					include_once( 'options-tabs/general.php' );
				}
			}else{
				include_once( 'options-tabs/general.php' );
			}
		?>
	</div>
</div>
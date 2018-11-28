<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('mt') ) exit;
?>

<?php $plugins = true; ?>
<div class="wrap"><h2 style="padding:0px 0px 30px 0px;line-height: 20px;"><?php _e('Forum Tools') ?></h2></div>
<?php WPF()->notice->show(FALSE) ?>
<?php do_action('wpforo_tools_page_top') ?>
<div id="wpf-admin-wrap" class="wrap"><div id="icon-users" class="icon32"><br /></div>
<?php
	$tabs = array( 
		'antispam' => __('Antispam', 'wpforo'),
        'legal' => __('Privacy &amp; Rules', 'wpforo'),
        'debug' => __('Debug', 'wpforo'),
        'misc' => __('Misc', 'wpforo'),
        //'cleanup' => __('Cleanup', 'wpforo'),
	);
	wpforo_admin_tools_tabs( $tabs, ( isset($_GET['tab']) ? $_GET['tab'] : 'antispam' ) ); 
	?>
    <div class="wpf-info-bar" style="padding:1% 2%;">
		<?php
            $includefile = 'tools-tabs/antispam.php';
			if(!empty($_GET['tab'])){
				switch($_GET['tab']){
                    case 'legal':
                        $includefile = 'tools-tabs/legal.php';
                        break;
					case 'misc':
						$includefile = 'tools-tabs/misc.php';
					break;
                    case 'debug':
                        $includefile = 'tools-tabs/debug.php';
                    break;
                    //case 'cleanup':
                    //$includefile = 'tools-tabs/cleanup.php';
                    //break;
				}
			}
            include_once($includefile);
        ?>
	</div>
</div>
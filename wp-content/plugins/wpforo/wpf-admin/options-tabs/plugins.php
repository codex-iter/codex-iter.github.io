<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('ms') ) exit;
?>


<p style="padding:0px 10px 0px 10px; font-style:italic;">
	<?php _e('Thank you for using wpForo. wpForo is a premium forum plugin which will always be available for free. There will never be paid and pro versions of this forum board. However this is a very large and hard project so we also develop paid addons (extensions), which will financially help us to keep improving and adding new features to the free wpForo plugin. Forum addons will also be actively developed. The first addons "Advanced Media Uploader", "Polls", "Private Messages" and "Ad Manager" will be available very soon. Once you got some addon and activated that, you will find settings in vertical subTabs here.', 'wpforo'); ?> 
</p>
<p>&nbsp;</p>

<?php

	$tabs = array();
	$tabs = apply_filters('wpforo_plugins_tabs_array_filter', $tabs);
	if( !empty($tabs) && isset($_GET['tab']) ){
		$subTab = ( !isset($_GET['subtab']) ) ? key($tabs) : $_GET['subtab'];
		$_GET['subtab'] = $subTab;
		wpforo_admin_options_tabs( $tabs, $_GET['tab'], TRUE, $subTab );
	}
    
    $option_files = array();
    $option_files = apply_filters('wpforo_plugins_option_files_array_filter', $option_files);
    if( isset($_GET['subtab']) && isset($option_files[$_GET['subtab']]) ){
        ?>
        <div class="plugins-tab-wrap">
            <?php include_once( $option_files[$_GET['subtab']] ); ?>
        </div>
        <?php
    }
    
?>

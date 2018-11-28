<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<div id="wpf-admin-wrap" class="wrap">
	<h1 style="padding:30px 0px 10px 0px;"><?php _e('wpForo Dashboard', 'wpforo'); ?></h1>
	<?php WPF()->notice->show(FALSE) ?>
   
   
    <div id="dashboard-widgets-wrap" style="padding-top:10px;">
        <div class="metabox-holder" id="dashboard-widgets">
            
            
            <div class="postbox-container" id="postbox-container-0" style="width:100%;">
                <div class="meta-box-sortables ui-sortable" id="normal-sortables" style="min-height:60px;">
                
                	<div class="postbox" id="wpforo_dashboard_widget_0">
                        <button aria-expanded="true" class="handlediv button-link" type="button">
                            <span class="screen-reader-text">&nbsp;</span>
                            <span class="toggle-indicator"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle"><span><?php _e('Welcome Message', 'wpforo'); ?></span></h2>
                        <div class="inside">
                            <div class="main" style="padding:5px 15px 15px 15px;">
                            	<div style="float:left; vertical-align:top; width:calc(100% - 300px);;">
                                	<p style="font-size:30px; margin:0px 0px 10px 0px; font-family:Constantia, 'Lucida Bright', 'DejaVu Serif', Georgia, serif"><?php _e('Welcome to wpForo', 'wpforo'); echo ' ' . esc_html(WPFORO_VERSION) ?></p>
                                	<p style="margin:0px; font-size:14px;font-family:'Lucida Bright', 'DejaVu Serif', Georgia, serif">
                                    <?php _e('Thank you for using wpForo! wpForo is a professional bulletin board for WorPress, and the only forum software which comes with Multi-layout template system.
                                    The "Extended", "Simplified" and "Question &amp Answer" layouts fit almost all type of discussions needs. You can use wpForo for small and extremely large communities. 
                                    <br />If you found some issue or bug please open a support topic in plugin page or in our support forum at gVectors.com. If you liked wpForo please leave some good review for this plugin. We really need your good reviews. 
                                    If you didn\'t like wpForo please leave a list of issues and requirements you\'d like us to fix and add in near future. We\'re here to help you and improve wpForo as much as possible.', 'wpforo'); ?></p>
                                </div>
                            	<div style="float:right; vertical-align:top; padding-right:20px; width:280px; text-align:right; padding-top:20px;">
                                	<img class="wpforo-dashboard-logo" src="<?php echo WPFORO_URL ?>/wpf-assets/images/wpforo-logo.png"/>
                                    <p style="font-size:11px; color:#B1B1B1; font-style:italic; text-align:right; line-height:14px; padding-top:15px; margin:0px;">
                                        <?php _e('Thank you!<br> Sincerely yours,<br> gVectors Team', 'wpforo'); ?>&nbsp;
                                    </p>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                    </div><!-- widget / postbox -->
                
                </div>
            </div>
            
            <?php if(current_user_can('administrator') || current_user_can('editor') || current_user_can('author') ): ?>
                <div class="postbox-container" id="postbox-container-1">
                    <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                        
                        <div class="postbox" id="wpforo_dashboard_widget_1">
                            <button aria-expanded="true" class="handlediv button-link" type="button">
                                <span class="screen-reader-text">Toggle panel: General Information</span>
                                <span class="toggle-indicator"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><span><?php _e('General Information', 'wpforo'); ?></span></h2>
                            <div class="inside">
                                <div class="main">
                                    <ul>
                                        <li class="post-count"><strong><?php _e('You are currently running', 'wpforo'); ?> wpForo <?php echo esc_html(WPFORO_VERSION) ?></strong></li>
                                        <li class="page-count"><?php _e('Active Theme', 'wpforo'); ?>: Classic</li>
                                        <li class="page-count"><?php _e('wpForo Website', 'wpforo'); ?>: <a href="https://wpforo.com">wpForo.com</a></li>
                                        <li class="page-count"><?php _e('Support Forum', 'wpforo'); ?>: <a href="https://wordpress.org/support/plugin/<?php echo WPFORO_FOLDER; ?>/">WordPress.org Forum</a></li>
                                     </ul>
                                </div>
                            </div>
                        </div><!-- widget / postbox -->
                        
                        <div class="postbox" id="wpforo_dashboard_widget_server">
                            <button aria-expanded="true" class="handlediv button-link" type="button">
                                <span class="screen-reader-text">Toggle panel: Server Information</span>
                                <span class="toggle-indicator"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><span><?php _e('Server Information', 'wpforo'); ?></span></h2>
                            <div class="inside">
                                <div class="main">
                                    <table style="width:98%; margin:0px auto; text-align:left;">
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td">USER AGENT</td>
                                            <td class="wpf-dw-td-value"><?php echo $_SERVER['HTTP_USER_AGENT'] ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td">Web Server</td>
                                            <td class="wpf-dw-td-value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td">PHP Version</td>
                                            <td class="wpf-dw-td-value"><?php echo phpversion(); ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td">MySQL Version</td>
                                            <td class="wpf-dw-td-value"><?php echo WPF()->db->db_version(); ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td">PHP Max Post Size</td>
                                            <td class="wpf-dw-td-value"><?php echo ini_get('post_max_size'); ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td">PHP Max Upload Size</td>
                                            <td class="wpf-dw-td-value"><?php echo ini_get('upload_max_filesize'); ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td">PHP Memory Limit</td>
                                            <td class="wpf-dw-td-value"><?php echo ini_get('memory_limit'); ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td">PHP DateTime Class</td>
                                            <td class="wpf-dw-td-value" style="line-height: 18px!important;">
                                                <?php echo (class_exists('DateTime') && class_exists('DateTimeZone') && method_exists('DateTime', 'setTimestamp')) ? '<span class="wpf-green">' . __('Available', 'wpforo') . '</span>' : '<span class="wpf-red">' . __('The setTimestamp() method of PHP DateTime class is not available. Please make sure you use PHP 5.4 and higher version on your hosting service.', 'wpforo') . '</span> | <a href="http://php.net/manual/en/datetime.settimestamp.php" target="_blank">more info&raquo;</a>'; ?> </td>
                                        </tr>
                                        <?php do_action('wpforo_dashboard_widget_server') ?>
                                    </table>
                                </div>
                            </div>
                        </div><!-- widget / postbox -->
                        
                        <?php do_action('wpforo_dashboard_widgets_col1'); ?>
                        
                    </div><!-- normal-sortables -->
                </div><!-- wpforo_postbox_container -->
                
                <div class="postbox-container" id="postbox-container-2">
                    <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                    
                        <div class="postbox" id="wpforo_dashboard_widget_statistic">
                            <button aria-expanded="true" class="handlediv button-link" type="button">
                                <span class="screen-reader-text">Toggle panel: Board Statistic</span>
                                <span class="toggle-indicator"></span>
                            </button>
                            <h2 class="hndle ui-sortable-handle"><span><?php _e('Board Statistic', 'wpforo'); ?></span></h2>
                            <div class="inside">
                                <div class="main">
                                    <table style="width:98%; margin:0px auto; text-align:left;">
                                        <?php $statistic = WPF()->statistic();  ?>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td"><?php _e('Forums', 'wpforo'); ?></td>
                                            <td class="wpf-dw-td-value"><?php echo intval($statistic['forums']) ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td"><?php _e('Topics', 'wpforo'); ?></td>
                                            <td class="wpf-dw-td-value"><?php echo intval($statistic['topics']) ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td"><?php _e('Posts', 'wpforo'); ?></td>
                                            <td class="wpf-dw-td-value"><?php echo intval($statistic['posts']) ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td"><?php _e('Members', 'wpforo'); ?></td>
                                            <td class="wpf-dw-td-value"><?php echo intval($statistic['members']) ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td"><?php _e('Members Online', 'wpforo'); ?></td>
                                            <td class="wpf-dw-td-value"><?php echo intval($statistic['online_members_count']) ?></td>
                                        </tr>
										<?php 
										$size_da = 0; $size_aa = 0;
                                        $upload_dir = wp_upload_dir();
                                        if( is_dir(  $upload_dir['basedir'] .  '/wpforo/avatars/') ) $size_avatar = wpforo_dir_size( $upload_dir['basedir'] .  '/wpforo/avatars' ); 
                                        if( is_dir(  $upload_dir['basedir'] .  '/wpforo/default_attachments/') ) $size_da = wpforo_dir_size( $upload_dir['basedir'] .  '/wpforo/default_attachments' ); 
                                        ?>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td"><?php _e('Avatars Size', 'wpforo'); ?></td>
                                            <td class="wpf-dw-td-value"><?php echo wpforo_human_filesize( $size_avatar ); ?></td>
                                        </tr>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td"><?php _e('Default Attachments Size', 'wpforo'); ?></td>
                                            <td class="wpf-dw-td-value"><?php echo wpforo_human_filesize( $size_da ); ?></td>
                                        </tr>
                                        <?php if( isset($statistic['attachments']) && $statistic['attachment_sizes'] ) : ?>
                                        <?php $size_aa = 0; $upload_dir = wp_upload_dir(); if( is_dir(  $upload_dir['basedir'] .  '/wpforo/attachments/') ) $size_aa = wpforo_dir_size( $upload_dir['basedir'] .  '/wpforo/attachments' ); ?>
                                            <tr class="wpf-dw-tr">
                                                <td class="wpf-dw-td"><?php _e('Advanced Attachments', 'wpforo'); ?></td>
                                                <td class="wpf-dw-td-value"><?php echo esc_html($statistic['attachments']) ?> <?php _e('file(s)', 'wpforo'); ?></td>
                                            </tr>
                                            <tr class="wpf-dw-tr">
                                                <td class="wpf-dw-td"><?php _e('Advanced Attachments Size', 'wpforo'); ?></td>
                                                <td class="wpf-dw-td-value"><?php echo wpforo_human_filesize( $size_aa ); ?></td>
                                            </tr>
                                        <?php endif ?>
                                        <tr class="wpf-dw-tr">
                                            <td class="wpf-dw-td"><?php _e('Total Size', 'wpforo'); ?></td>
                                            <td class="wpf-dw-td-value">
											<strong style="font-size:14px;"><?php $total = (int)$size_avatar + (int)$size_da + ((isset($size_aa))?(int)$size_aa:0); echo wpforo_human_filesize( $total ); ?></strong>
                            				</td>
                                        </tr>

                                        <?php if( wpforo_current_user_is('admin') ) : ?>
                                            <tr>
                                                <td colspan="2">
                                                    <p class="hndle" style="padding:25px 0px 5px 0px; margin:0px; font-size:14px; font-weight:bold;">
                                                        <?php _e('Forum Maintenance', 'wpforo'); ?>
                                                    </p>
                                                    <p class="wpf-info" style="padding:5px 0px;"><?php _e("This process may take a few seconds or dozens of minutes, please be patient and don't close this page. If you got 500 Server Error please don't worry, the data updating process is still working in MySQL server.", 'wpforo'); ?></p>
                                                    <div style="width:100%; padding:7px 0px;">
                                                        <?php
                                                            $reset_cache = wp_nonce_url( admin_url( 'admin.php?page=wpforo-community&action=reset_cache' ), 'wpforo_reset_cache' );
                                                            $reset_forums_stat_url = wp_nonce_url( admin_url( 'admin.php?page=wpforo-community&action=reset_fstat' ), 'wpforo_reset_forums_stat' );
                                                            $reset_topics_stat_url = wp_nonce_url( admin_url( 'admin.php?page=wpforo-community&action=reset_tstat' ), 'wpforo_reset_topics_stat' );
                                                            $reset_users_stat_url = wp_nonce_url( admin_url( 'admin.php?page=wpforo-community&action=reset_ustat' ), 'wpforo_reset_users_stat' );
                                                            $reset_phrase_cache = wp_nonce_url( admin_url( 'admin.php?page=wpforo-community&action=reset_phrase_cache' ), 'wpforo_reset_phrase_cache' );
                                                            $reset_user_cache = wp_nonce_url( admin_url( 'admin.php?page=wpforo-community&action=reset_user_cache' ), 'wpforo_reset_user_cache' );
                                                            $synch_user_profile = wp_nonce_url( admin_url( 'admin.php?page=wpforo-community&action=synch_user_profile' ), 'wpforo_synch_user_profile' );
                                                        ?>
                                                        <a href="<?php echo esc_url($reset_cache); ?>" style="min-width:160px; margin-bottom:10px; text-align:center;" class="button button-secondary"><?php _e('Delete All Caches', 'wpforo'); ?></a>&nbsp;
                                                        <a href="<?php echo esc_url($reset_forums_stat_url); ?>" style="min-width:160px; margin-bottom:10px; text-align:center;" class="button button-secondary"><?php _e('Update Forums Statistic', 'wpforo'); ?></a>&nbsp;
                                                        <a href="<?php echo esc_url($reset_topics_stat_url); ?>" style="min-width:160px; margin-bottom:10px; text-align:center;" class="button button-secondary"><?php _e('Update Topics Statistic', 'wpforo'); ?></a>&nbsp;
                                                        <a href="<?php echo esc_url($reset_users_stat_url); ?>" style="min-width:160px; margin-bottom:10px; text-align:center;" class="button button-secondary"><?php _e('Update Users Statistic', 'wpforo'); ?></a>&nbsp;
                                                        <a href="<?php echo esc_url($reset_phrase_cache); ?>" style="min-width:160px; margin-bottom:10px; text-align:center;" class="button button-secondary"><?php _e('Delete Phrase Cache', 'wpforo'); ?></a>&nbsp;
                                                        <a href="<?php echo esc_url($reset_user_cache); ?>" style="min-width:160px; margin-bottom:10px; text-align:center;" class="button button-secondary"><?php _e('Delete User Cache', 'wpforo'); ?></a>&nbsp;
                                                        <a href="<?php echo esc_url($synch_user_profile); ?>" style="min-width:160px; margin-bottom:10px; text-align:center;" class="button button-secondary"><?php _e('Synch User Profiles', 'wpforo'); ?></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif ?>
                                        
                                    </table>
                                </div>
                            </div>
                        </div><!-- widget / postbox -->
                        
                        <?php do_action( 'wpforo_dashboard_widgets_col2', WPF() ); ?>
                        
                    </div><!-- normal-sortables -->
                </div><!-- wpforo_postbox_container -->
			<?php endif; ?>
            
            <div class="postbox-container" id="postbox-container-3">
                <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                    
                    <?php do_action( 'wpforo_dashboard_widgets_col3', WPF() ); ?>
                    
                </div><!-- normal-sortables -->
            </div><!-- wpforo_postbox_container -->
            
            
            
        </div><!-- dashboard-widgets -->
    </div><!-- dashboard-widgets-wrap -->
    
</div><!-- wpwrap -->


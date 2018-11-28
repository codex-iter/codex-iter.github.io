<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('mth') ) exit;
?>

<div id="wpf-admin-wrap" class="wrap wpforo-themes">
	<h2 style="padding:20px 0px 0px 0px;line-height: 20px;  margin-bottom:15px;">
		<?php _e('Forum Themes', 'wpforo'); ?>
		<a href="<?php echo admin_url( 'admin.php?page=wpforo-themes&action=add' ) ?>" class="add-new-h2" style="margin-left:10px; display:none;"><?php _e('Add New', 'wpforo'); ?></a>
	</h2>
    <?php WPF()->notice->show(FALSE) ?>
	
    <div style="box-shadow:none; margin:25px 0px 20px 0px;" class="wpf-info-bar">
        <p style="font-size:13px; padding:0px; margin:10px;">
        wpForo theme files contain the markup and template structure for frontend of your forum. 
        Theme files can be found within the <span style="color:#43A6DF">/wpforo/wpf-themes/</span> directory, in current active theme folder, for example <span style="color:#43A6DF">/classic/</span>.
        You can edit these files in an upgrade-safe way using overrides. 
        For example, copy the certain or all files of <span style="color:#43A6DF">/classic/</span> folder into a folder within your current active WordPress theme named <span style="color:#43A6DF">/wpforo/</span>, keeping the same file structure.<br>
        </p>
        <div style="background:#F5F5F5; border:#ddd 1px dotted; padding:10px 15px; margin:5px 0px; font-size:13px; line-height:18px;">Example: To override the "Topic List" template file of the Extended (#1) forum layout, copy according file: <span style="color:#43A6DF">plugins/wpforo/wpf-themes/classic/<span style="color:#C98B4C">layouts/1/topic.php</span></span> to <span style="color:#43A6DF">themes/yourtheme/wpforo/<span style="color:#C98B4C">layouts/1/topic.php</span></span></div>
        <p style="font-size:13px; padding:0px; margin:10px;">
        The copied file will now automatically override the wpForo default theme file. All changes in this file will not be lost on forum update.
        <div style="background:#F6F7D8; border:#ddd 1px dotted; padding:10px 15px; margin:5px 0px; font-size:13px; line-height:18px;">Do not edit these files within the core plugin itself as they are overwritten during the upgrade process and any customizations will be lost.</div>
        </p>
    </div>
    
	<?php 
    $themes = WPF()->tpl->find_themes();
	$theme_count = count($themes);
	WPF()->tpl->theme;
	if(!empty($themes)):
		foreach( $themes as $main_file => $theme ): 
			$theme_folder = trim(basename(dirname($main_file)), '/');
			$theme_url = WPFORO_THEME_URL . '/' . $theme_folder;
			$layouts = WPF()->tpl->find_themes('/'.$theme_folder.'/layouts', 'php', 'layout');
			$styles = WPF()->tpl->find_styles( $theme_folder );
			$is_active = ( WPF()->tpl->theme == $theme_folder ) ? true : false;
			$theme_archive = get_option( 'wpforo_theme_archive_' . $theme_folder );
			$has_archive = (!empty($theme_archive)) ? true : false;
			?>
			<div class="wpf-div-table">
				<div class="wpf-div-tr">
					<div class="wpf-div-td" style="width:50%; border-bottom:1px dotted #ddd;">
					 <?php if( $is_active ): ?>
                     	<span style="color:#279800; font-weight:bold; text-transform:uppercase;"><?php _e('Current active theme', 'wpforo'); ?></span>
                     <?php else: ?>
                     	<span style="color:#555555; font-weight:bold; text-transform:uppercase;"><?php _e('Inactive', 'wpforo'); ?></span>
                     <?php endif; ?>
					</div>
					<div class="wpf-div-td" style="width:50%; border-bottom:1px dotted #ddd;">
						<?php _e('LAYOUTS', 'wpforo'); ?> (<?php echo count($layouts) ?>)
					</div>
				</div>
				<div class="wpf-div-tr">
					<div class="wpf-div-td" style="width:60%;">
						<div class="wpf-theme-screenshot" style="background:url('<?php echo esc_url($theme_url) ?>/screenshot.png') 0 0 no-repeat;"></div>
						<div class="wpf-theme-info">
							<h3 style="margin-top:5px; margin-bottom:10px;"><?php echo esc_html(wpforo_text( $theme['name']['value'], 30, false )) ?> | <?php echo ($theme['version']['value']) ? 'version ' . esc_html($theme['version']['value']) : ''; ?></h3>
							<p style="font-size:14px;" title="<?php echo esc_attr($theme['author']['value']) ?>"><?php echo ($theme['author']['value']) ? '<strong>Author:</strong>&nbsp; ' . esc_html(wpforo_text( $theme['author']['value'], 30, false )) : ''; ?></p>
							<p style="font-size:14px;" title="<?php echo esc_attr($theme['theme_url']['value']) ?>"><?php echo ($theme['theme_url']['value']) ? '<strong>URI:</strong>&nbsp; <a href="'.esc_url($theme['theme_url']['value']).'" target="_blank">' . mb_substr( $theme['theme_url']['value'], 0, 30 ) . '</a>' : ''; ?></p>
							<p style="margin-top:5px;"><?php echo ($theme['description']['value']) ? esc_html(wpforo_text($theme['description']['value'], 200, false)) : ''; ?></p>
						</div>
						<div class="wpf-theme-actions">
                        <?php if( $theme_count > 1 ): ?>
							<?php if( !$is_active ): ?>
                                <?php if($has_archive): ?>
                                	<a href="<?php echo admin_url( 'admin.php?page=wpforo-themes&action=activate&theme=' . sanitize_text_field($theme_folder) ) ?>" class="wpf-action button"><?php _e('Activate', 'wpforo'); ?></a>
									<a href="<?php echo admin_url( 'admin.php?page=wpforo-themes&action=install&theme=' . sanitize_text_field($theme_folder) ) ?>" class="wpf-action button"><?php _e('Fresh Installation', 'wpforo'); ?></a>  
								<?php else: ?>
									<a href="<?php echo admin_url( 'admin.php?page=wpforo-themes&action=install&theme=' . sanitize_text_field($theme_folder) ) ?>" class="wpf-action button"><?php _e('Install', 'wpforo'); ?></a>  
								<?php endif; ?>
                                <a href="<?php echo admin_url( 'admin.php?page=wpforo-themes&action=delete&theme=' . sanitize_text_field($theme_folder) ) ?>" class="wpf-delete button" onclick = "if (!confirm('<?php _e('Are you sure you want to delete this theme files?'); ?>')) { return false; }"><?php _e('Delete', 'wpforo'); ?></a>
                            <?php else: ?>
                            	<?php if($has_archive): ?>
                            		<a href="<?php echo admin_url( 'admin.php?page=wpforo-themes&action=reset&theme=' . sanitize_text_field($theme_folder) ) ?>" class="wpf-action button" onclick = "if (!confirm('<?php _e('Are you sure you want to reset all settings and style colors to default?'); ?>')) { return false; }"><?php _e('Reset Settings', 'wpforo'); ?></a>  
								<?php endif; ?>
							<?php endif; ?>
                        <?php endif; ?>
						</div>
					</div>
					<div class="wpf-div-td" style="width:40%; border-left: 1px dotted #ddd;">
					<?php 
					if(!empty($layouts)){
						foreach( $layouts as $layout ){
							?>
							<div class="wpf-layout-info" style="display:block; border-bottom:1px dotted #ddd; padding:0px 0px 10px 0px; margin:0px 0px 10px 0px;">
								<h4 style="margin:1px 0px;"><?php echo esc_html(wpforo_text( $layout['name']['value'], 30, false )) ?> <?php echo ($layout['version']['value']) ? '(' . esc_html($layout['version']['value']) .')' : ''; ?> | <?php echo ($layout['author']['value']) ? '<a href="'.esc_url($layout['layout_url']['value']).'" target="_blank">' .esc_html( wpforo_text( $layout['author']['value'], 25, false ) ) . '</a>' : ''; ?></h4>
								<p><?php echo ($layout['description']['value']) ? esc_html(wpforo_text($layout['description']['value'], 120, false)) : ''; ?></p>
								<!-- <p><a href="#" class="wpf-action">Deactivate</a> | <a href="#" class="wpf-delete">Delete</a></p> -->
							</div>
							<?php
						}
					}
					else{ 
						?><div class="wpf-layout-info"><p style="text-align:center;"><? _e('No layout found', 'wpforo'); ?></p></div><?php
                    } 
                    ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
    <?php else: ?>
    	<div class="wpf-div-table">
			<div class="wpf-div-tr">
				<div class="wpf-div-td">
					<p style="text-align:center;"><?php _e('No theme found', 'wpforo'); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
	
</div>
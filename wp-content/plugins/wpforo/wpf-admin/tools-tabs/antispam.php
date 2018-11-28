<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !current_user_can('administrator') ) exit;
?>

	<?php if( !isset( $_GET['action'] ) ): ?>
    
    	<?php if (!class_exists('Akismet')): ?>
    		<div style="width:94%; clear:both; margin:0px 0 15px 0; text-align:center; line-height:22px; font-size:14px; color:#D35206; border:1px dotted #ccc; padding:10px 20px 10px 20px;; background:#F7F5F5;">
				<a href="https://wordpress.org/plugins/akismet/" target="_blank">Akismet</a> <?php _e('is not installed! For an advanced Spam Control please install Akismet antispam plugin, it works well with wpForo Spam Control system. Akismet is already integrated with wpForo. It\'ll help to filter posts and protect forum against spam attacks.', 'wpforo'); ?>
            </div>
    	<?php else: ?>
        	
		<?php endif; ?>
        
    	<form action="" method="POST" class="validate">
            <?php wp_nonce_field( 'wpforo-tools-antispam' ); ?>
            <div class="wpf-tool-box wpf-spam-attach right-box">
            	<h3>
				<?php _e('Spam Control', 'wpforo'); ?>
                <p class="wpf-info"><?php _e('Some useful options to limit just registered users and minimize spam. This control don\'t affect users whose Usergroup has "Can edit member" and "Can pass moderation" permissions.', 'wpforo'); ?></p>
                </h3>
                <div style="margin-top:10px; clear:both;">
                	<table style="width:100%;">
                      <tbody>
                        <tr>
                            <th><label><?php _e('Enable wpForo Spam Control','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="spam_filter_yes" type="radio" name="wpforo_tools_antispam[spam_filter]" value="1" <?php wpfo_check(WPF()->tools_antispam['spam_filter'], 1); ?>/><label for="spam_filter_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="spam_filter_no" type="radio" name="wpforo_tools_antispam[spam_filter]" value="0" <?php wpfo_check(WPF()->tools_antispam['spam_filter'], 0); ?>/><label for="spam_filter_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php _e('Ban user when spam is suspected','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="spam_user_ban_yes" type="radio" name="wpforo_tools_antispam[spam_user_ban]" value="1" <?php wpfo_check(WPF()->tools_antispam['spam_user_ban'], 1); ?>/><label for="spam_user_ban_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="spam_user_ban_no" type="radio" name="wpforo_tools_antispam[spam_user_ban]" value="0" <?php wpfo_check(WPF()->tools_antispam['spam_user_ban'], 0); ?>/><label for="spam_user_ban_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>  	
                        <tr style="visibility:hidden;">
                            <th><label><?php _e('Notify via email when new user is banned','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="spam_user_ban_notification_yes" type="radio" name="wpforo_tools_antispam[spam_user_ban_notification]" value="1" <?php wpfo_check(WPF()->tools_antispam['spam_user_ban_notification'], 1); ?>/><label for="spam_user_ban_notification_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="spam_user_ban_notification_no" type="radio" name="wpforo_tools_antispam[spam_user_ban_notification]" value="0" <?php wpfo_check(WPF()->tools_antispam['spam_user_ban_notification'], 0); ?>/><label for="spam_user_ban_notification_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr> 
                        <tr>
                            <th><label ><?php _e('Spam Suspicion Level for Topics', 'wpforo'); ?></label></th>
                            <td><input type="number" min="0" max="100" name="wpforo_tools_antispam[spam_filter_level_topic]" value="<?php wpfo(WPF()->tools_antispam['spam_filter_level_topic']) ?>" class="wpf-field" /></td>
                        </tr> 	
                        <tr>
                            <th><label ><?php _e('Spam Suspicion Level for Posts', 'wpforo'); ?></label></th>
                            <td><input type="number" min="0" max="100" name="wpforo_tools_antispam[spam_filter_level_post]" value="<?php wpfo(WPF()->tools_antispam['spam_filter_level_post']) ?>" class="wpf-field" /></td>
                        </tr> 
                        <?php if (class_exists('Akismet')): ?>
                        <tr>
                            <td colspan="2" style="color:#fff; background:#7C9B2E; font-size:20px; padding:10px 10px; text-align:center; font-family:'Lucida Grande', 'Lucida Sans Unicode'"><strong>A&middot;kis&middot;met</strong> <?php _e(' is enabled','wpforo'); ?></td>
                        </tr> 
                        <?php endif; ?>	
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="wpf-tool-box wpf-spam-attach left-box">
            	<h3>
				<?php _e('New Registered User', 'wpforo'); ?>
                <p class="wpf-info"><?php _e('Some useful options to limit just registered users and minimize spam. These options don\'t affect users whose Usergroup has "Can edit member" and "Can pass moderation" permissions.', 'wpforo'); ?></p>
                </h3>
                <div style="margin-top:10px; clear:both;">
                	<table style="width:100%;">
                      <tbody>
                        <tr>
                            <th style="width:65%;">
                            	<label ><?php _e('User is New (under hard spam control) during', 'wpforo'); ?></label>
                            </th>
                            <td><?php _e('first', 'wpforo'); ?> <input type="number" min="0" name="wpforo_tools_antispam[new_user_max_posts]" value="<?php wpfo(WPF()->tools_antispam['new_user_max_posts']) ?>" class="wpf-field" style="width:50px;" /> <?php _e('posts', 'wpforo'); ?></td>
                        </tr>
                        <tr>
                            <th style="width:65%;"><label ><?php _e('Min number of posts to be able attach files', 'wpforo'); ?></label></th>
                            <td><input type="number" min="0" name="wpforo_tools_antispam[min_number_post_to_attach]" value="<?php wpfo(WPF()->tools_antispam['min_number_post_to_attach']) ?>" class="wpf-field" style="max-width:80px;" /></td>
                        </tr>
                        <tr>
                            <th><label><?php _e('Min number of posts to be able post links', 'wpforo'); ?></label></th>
                            <td><input type="number" min="0" name="wpforo_tools_antispam[min_number_post_to_link]" value="<?php wpfo(WPF()->tools_antispam['min_number_post_to_link']) ?>" class="wpf-field" style="max-width:80px;" /></td>
                        </tr>
                        <tr>
                            <th colspan="2">
                            <label><?php _e('Do not allow to attach files with following extensions:', 'wpforo'); ?></label>
                            <textarea name="wpforo_tools_antispam[limited_file_ext]" style="width:100%; height:60px; margin-top:10px;  color:#666666; background:#fdfdfd;"><?php echo esc_textarea(stripslashes(WPF()->tools_antispam['limited_file_ext'])); ?></textarea></td>
                        </tr>	  	
                      </tbody>
                    </table>
                </div>
            </div>



			<div class="wpf-tool-box wpf-spam-attach right-box" style="max-height: inherit;">
            	<h3>
				<?php _e('Google reCAPTCHA', 'wpforo'); ?>
                <p class="wpf-info"><?php _e('reCAPTCHA protects you against spam and other types of automated abuse. It makes secure topic and post editors when Guest Posting is allowed, also it protects login and registration forms against spam attacks.', 'wpforo'); ?></p>
                </h3>
				<div style="padding-top: 3px;"><strong><?php _e('reCAPTCHA API keys', 'wpforo'); ?></strong></div>
                <p class="wpf-info">
					<?php _e('To start using reCAPTCHA, you need to sign up for an API key pair for your site.', 'wpforo'); ?> <br />
					<a href="http://www.google.com/recaptcha/admin" target="_blank"><?php _e('Register your site and get API keys here &raquo;', 'wpforo'); ?></a>
				</p>
                <div style="margin-top:10px; clear:both;">
                	<table style="width:100%;">
                      <tbody>
                        <tr>
                            <th style="width:35%;">
                            	<label ><?php _e('Site Key', 'wpforo'); ?>:</label>
                            </th>
                            <td><input type="text" name="wpforo_tools_antispam[rc_site_key]" value="<?php wpfo(WPF()->tools_antispam['rc_site_key']) ?>" class="wpf-field" style="width: 100%" /></td>
                        </tr>
                        <tr>
                            <th style="width:35%;">
                            	<label ><?php _e('Secret Key', 'wpforo'); ?>:</label>
                            </th>
                            <td><input type="text" name="wpforo_tools_antispam[rc_secret_key]" value="<?php wpfo(WPF()->tools_antispam['rc_secret_key']) ?>" class="wpf-field" style="width: 100%" /></td>
                        </tr>	  	
                      </tbody>
                    </table>
                </div>
				<div style="padding-top: 3px; border-bottom: 1px dotted #ccc;"> <strong><?php _e('reCAPTCHA Settings', 'wpforo'); ?></strong></div>
				<div style="margin-top:10px; clear:both;">
                	<table style="width:100%;">
                      <tbody> 
						 <tr>
                            <th style="width:50%;"><label><?php _e('reCAPTCHA Theme','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_theme_light" type="radio" name="wpforo_tools_antispam[rc_theme]" value="light" <?php wpfo_check(WPF()->tools_antispam['rc_theme'], 'light'); ?>/><label for="rc_theme_light"><?php _e('Light','wpforo'); ?></label> &nbsp;
                                    <input id="rc_theme_dark" type="radio" name="wpforo_tools_antispam[rc_theme]" value="dark" <?php wpfo_check(WPF()->tools_antispam['rc_theme'], 'dark'); ?>/><label for="rc_theme_dark"><?php _e('Dark','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
						<tr>
                            <th style="width:50%;"><label><?php _e('Guest Topic Editor','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_topic_editor_yes" type="radio" name="wpforo_tools_antispam[rc_topic_editor]" value="1" <?php wpfo_check(WPF()->tools_antispam['rc_topic_editor'], 1); ?>/><label for="rc_topic_editor_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="rc_topic_editor_no" type="radio" name="wpforo_tools_antispam[rc_topic_editor]" value="0" <?php wpfo_check(WPF()->tools_antispam['rc_topic_editor'], 0); ?>/><label for="rc_topic_editor_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
						<tr>
                            <th style="width:50%;"><label><?php _e('Guest Post Editor','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_post_editor_yes" type="radio" name="wpforo_tools_antispam[rc_post_editor]" value="1" <?php wpfo_check(WPF()->tools_antispam['rc_post_editor'], 1); ?>/><label for="rc_post_editor_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="rc_post_editor_no" type="radio" name="wpforo_tools_antispam[rc_post_editor]" value="0" <?php wpfo_check(WPF()->tools_antispam['rc_post_editor'], 0); ?>/><label for="rc_post_editor_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
						
						<tr>
                            <th style="width:50%;"><label><?php _e('wpForo Login Form','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_wpf_login_form_yes" type="radio" name="wpforo_tools_antispam[rc_wpf_login_form]" value="1" <?php wpfo_check(WPF()->tools_antispam['rc_wpf_login_form'], 1); ?>/><label for="rc_wpf_login_form_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="rc_wpf_login_form_no" type="radio" name="wpforo_tools_antispam[rc_wpf_login_form]" value="0" <?php wpfo_check(WPF()->tools_antispam['rc_wpf_login_form'], 0); ?>/><label for="rc_wpf_login_form_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
						<tr>
                            <th style="width:50%;"><label><?php _e('wpForo Registration Form','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_wpf_reg_form_yes" type="radio" name="wpforo_tools_antispam[rc_wpf_reg_form]" value="1" <?php wpfo_check(WPF()->tools_antispam['rc_wpf_reg_form'], 1); ?>/><label for="rc_wpf_reg_form_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="rc_wpf_reg_form_no" type="radio" name="wpforo_tools_antispam[rc_wpf_reg_form]" value="0" <?php wpfo_check(WPF()->tools_antispam['rc_wpf_reg_form'], 0); ?>/><label for="rc_wpf_reg_form_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
						<tr>
                            <th style="width:50%;"><label><?php _e('wpForo Reset Password Form','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_wpf_lostpass_form_yes" type="radio" name="wpforo_tools_antispam[rc_wpf_lostpass_form]" value="1" <?php wpfo_check(WPF()->tools_antispam['rc_wpf_lostpass_form'], 1); ?>/><label for="rc_wpf_lostpass_form_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="rc_wpf_lostpass_form_no" type="radio" name="wpforo_tools_antispam[rc_wpf_lostpass_form]" value="0" <?php wpfo_check(WPF()->tools_antispam['rc_wpf_lostpass_form'], 0); ?>/><label for="rc_wpf_lostpass_form_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
						<tr>
                            <th style="width:50%;"><label><?php _e('WordPress Login Form','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_login_form_yes" type="radio" name="wpforo_tools_antispam[rc_login_form]" value="1" <?php wpfo_check(WPF()->tools_antispam['rc_login_form'], 1); ?>/><label for="rc_login_form_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="rc_login_form_no" type="radio" name="wpforo_tools_antispam[rc_login_form]" value="0" <?php wpfo_check(WPF()->tools_antispam['rc_login_form'], 0); ?>/><label for="rc_login_form_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
						<tr>
                            <th style="width:50%;"><label><?php _e('WordPress Registration Form','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_reg_form_yes" type="radio" name="wpforo_tools_antispam[rc_reg_form]" value="1" <?php wpfo_check(WPF()->tools_antispam['rc_reg_form'], 1); ?>/><label for="rc_reg_form_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="rc_reg_form_no" type="radio" name="wpforo_tools_antispam[rc_reg_form]" value="0" <?php wpfo_check(WPF()->tools_antispam['rc_reg_form'], 0); ?>/><label for="rc_reg_form_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
						<tr>
                            <th style="width:50%;"><label><?php _e('WordPress Reset Password Form','wpforo'); ?>:</label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="rc_lostpass_form_yes" type="radio" name="wpforo_tools_antispam[rc_lostpass_form]" value="1" <?php wpfo_check(WPF()->tools_antispam['rc_lostpass_form'], 1); ?>/><label for="rc_lostpass_form_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="rc_lostpass_form_no" type="radio" name="wpforo_tools_antispam[rc_lostpass_form]" value="0" <?php wpfo_check(WPF()->tools_antispam['rc_lostpass_form'], 0); ?>/><label for="rc_lostpass_form_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>

            <div class="wpf-tool-box wpf-spam-attach left-box" style="max-height: inherit;">
                <h3>
                    <?php _e('Post Content', 'wpforo'); ?>
                    <p class="wpf-info"><?php _e('Options to control and filter post content', 'wpforo'); ?></p>
                </h3>
                <div style="margin-top:0px; clear:both;">
                    <table style="width:100%;">
                        <tbody>
                        <tr>
                            <td>
                                <label style="padding-bottom:5px; display:block;"><strong><?php _e('Allow extra HTML tags', 'wpforo'); ?>:</strong></label>
                                <p class="wpf-info"><?php _e('By default wpForo allows all secure HTML tags in post content. Allowing a new HTML tag may affect your forum security. For example the &lt;iframe&gt; and &lt;script&gt; HTML tags may be used by spammers and hackers to load 3rd party ads and viruses to forum.', 'wpforo'); ?></p>
                                <p class="wpf-info" style="font-style: normal; line-height: 22px;"><?php _e('Example of adding a new HTML tags: ', 'wpforo'); ?><code>b, em, p, code, style, a(href title), img(src alt title), embed(src width height) ...</code></p>
                                <br>
                                <textarea name="wpforo_tools_antispam[html]" style="font-size: 13px; display:block; width:100%; height:120px;" placeholder="example.com" /><?php wpfo(WPF()->tools_antispam['html']) ?></textarea></td>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

			<div class="wpf-tool-box wpf-spam-attach left-box" id="spam-files">
				<?php 
				$site = get_bloginfo('url');
                $upload_dir = wp_upload_dir();
                $default_attachments_dir =  $upload_dir['basedir'] . '/wpforo/default_attachments/';
                ?>
            	<h3>
				<?php _e('Possible Spam Attachments', 'wpforo'); ?>
                <p class="wpf-info"><?php _e('This tool is designed to find attachment which have been uploaded by spammers. The tool checks most common spammer filenames and suggest to delete but you should check one by one and make sure those are spam files before deleting.', 'wpforo'); ?></p>
                </h3>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th style="width:50%; padding: 15px 0px;"><label><?php _e('Enable File Scanner','wpforo'); ?>:</label></th>
                        <td style=" padding: 10px 0px 5px 0px;">
                            <div class="wpf-switch-field">
                                <input id="spam_file_scanner_yes" type="radio" name="wpforo_tools_antispam[spam_file_scanner]" value="1" <?php wpfo_check(WPF()->tools_antispam['spam_file_scanner'], 1); ?>/><label for="spam_file_scanner_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                <input id="spam_file_scanner_no" type="radio" name="wpforo_tools_antispam[spam_file_scanner]" value="0" <?php wpfo_check(WPF()->tools_antispam['spam_file_scanner'], 0); ?>/><label for="spam_file_scanner_no"><?php _e('No','wpforo'); ?></label>
                            </div>
                        </td>
                    </tr>
                        <tr>
                            <td colspan="2" style="width:50%;">
                                <label style="padding-bottom:5px; display:block;"><strong><?php _e('Exclude file extensions', 'wpforo'); ?>:</strong></label>
                                <textarea name="wpforo_tools_antispam[exclude_file_ext]" style="font-size: 13px; display:block; width:100%; height:60px;" placeholder="example.com" /><?php wpfo(WPF()->tools_antispam['exclude_file_ext']) ?></textarea></td>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="wpf-spam-attach-dir"><?php _e('Directory', 'wpforo'); ?>: <?php echo str_replace($site, '', $upload_dir['baseurl']); ?>/wpforo/default_attachments/&nbsp;</div>
                <div style="margin-top:10px; clear:both;">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <?php 
						if( is_dir($default_attachments_dir) && WPF()->tools_antispam['spam_file_scanner'] ):
							if ($handle = opendir($default_attachments_dir)):
								while (false !== ($filename = readdir($handle))):
                                    if( $filename == '.' ||  $filename == '..') continue;

                                    $level = 0;  $color ='';
                                    $file = $default_attachments_dir . '/' . $filename;
                                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
									if( !$level = WPF()->moderation->spam_file($filename) ) continue;
									if( $level == 2 ) $color = 'style="color:#EE9900;"';
									if( $level == 3 ) $color = 'style="color:#FF0000;"';
									if( $level == 4 ) $color = 'style="color:#BB0000;"';
									?>
										<tr>
                                          <td class="wpf-spam-item" <?php echo $color; ?> title="<?php echo $upload_dir['baseurl'] .'/wpforo/default_attachments/'. $filename ?>">
										  	<?php if( WPF()->moderation->spam_file($filename, 'file-open') ): ?>
                                            	<a href="<?php echo $upload_dir['baseurl'] .'/wpforo/default_attachments/'. $filename ?>" target="_blank" <?php echo $color ?>><?php echo wpforo_text($filename, 50, false); ?></a>
                                            <?php else: ?>
                                            	<?php echo $filename; ?>
                                            <?php endif; ?>
												<?php echo ' (' . strtoupper($extension) . ' | ' . wpforo_human_filesize(filesize($file), 1) . ')'; ?>
                                          </td>
										  <td class="wpf-actions"><a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=wpforo-tools&tab=antispam&action=delete-spam-file&sfname=' . urlencode($filename) ), 'wpforo_tools_antispam_files' ); ?>" title="<?php _e('Delete this file', 'wpforo'); ?>"  onclick="return confirm('<?php _e('Are you sure you want to permanently delete this file?', 'wpforo'); ?>');"><?php _e('Delete', 'wpforo'); ?></a></td>
										</tr>
									<?php 
								endwhile;
								closedir($handle);
							endif;
						endif;
						?>
                        <tr style="background:#fff;">
                          <td colspan="2" class="wpf-actions" style="padding-top:20px; text-align:right;">
                          	<a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=wpforo-tools&tab=antispam&action=delete-all&level=1' ), 'wpforo_tools_antispam_files' ); ?>" 
                            	title="<?php _e('Click to delete Blue marked files', 'wpforo'); ?>" 
                                   onclick="return confirm('<?php _e('Are you sure you want to delete all BLUE marked files listed here. Please download Wordpress /wp-content/uploads/wpforo/ folder to your local computer before deleting files, this is not undoable.', 'wpforo'); ?>');">
								<?php _e('Delete All', 'wpforo'); ?>
                            </a> | 
                            <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=wpforo-tools&tab=antispam&action=delete-all&level=2' ), 'wpforo_tools_antispam_files' ); ?>" 
                            	title="<?php _e('Click to delete Orange marked files', 'wpforo'); ?>" 
                                	style="color:#EE9900;" 
                                    	onclick="return confirm('<?php _e('Are you sure you want to delete all ORANGE marked files listed here. Please download Wordpress /wp-content/uploads/wpforo/ folder to your local computer before deleting files, this is not undoable.', 'wpforo'); ?>');">
								<?php _e('Delete All', 'wpforo'); ?>
                            </a> | 
                            <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=wpforo-tools&tab=antispam&action=delete-all&level=3' ), 'wpforo_tools_antispam_files' ); ?>" 
                            	title="<?php _e('Click to delete Red marked files', 'wpforo'); ?>" 
                                	style="color:#FF0000;" 
                                    	onclick="return confirm('<?php _e('Are you sure you want to delete all RED marked files listed here. Please download Wordpress /wp-content/uploads/wpforo/ folder to your local computer before deleting files, this is not undoable.', 'wpforo'); ?>');">
								<?php _e('Delete All', 'wpforo'); ?>
                            </a> | 
                            <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=wpforo-tools&tab=antispam&action=delete-all&level=4' ), 'wpforo_tools_antispam_files' ); ?>" 
                            	title="<?php _e('Click to delete Dark Red marked files', 'wpforo'); ?>" 
                                	style="color:#BB0000;" 
                                    	onclick="return confirm('<?php _e('Are you sure you want to delete all DARK RED marked files listed here. Please download Wordpress /wp-content/uploads/wpforo/ folder to your local computer before deleting files, this is not undoable.', 'wpforo'); ?>');">
								<?php _e('Delete All', 'wpforo'); ?>
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
            <div style="clear:both;"></div>
            <div class="wpforo_settings_foot" style="clear:both; margin-top:20px;">
                <input type="submit" class="button button-primary" value="<?php _e('Update Options', 'wpforo'); ?>" />
            </div>
		</form>
	<?php endif ?>
<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !current_user_can('administrator') ) exit;
?>

	<?php if( !isset( $_GET['action'] ) ): ?>
    	
    	<form action="" method="POST" class="validate">
        	<?php wp_nonce_field( 'wpforo-tools-cleanup' ); ?>
			<div class="wpf-tool-box" style="width:50%;">
            	<h3><?php _e('Delete Inactive Users', 'wpforo'); ?>
                <p class="wpf-info"><?php _e('Inactive users are the account owners who have no topics, posts, comments and subscriptions for new content. In 99&#37; cases this kind of accounts are being registered by Spammers. This tool allows you to only keep active and known inactive users.', 'wpforo'); ?></p>
                </h3>
                <div style="margin-top:10px; clear:both;">
                <table style="width:100%;">
                    <tbody>
                        <tr>
                            <th><label><?php _e('Inactive users who have been registered more than', 'wpforo'); ?></label></th>
                            <td><input type="number" min="0" max="100" name="wpforo_tools_cleanup[user_reg_days_ago]" value="<?php wpfo(WPF()->tools_cleanup['user_reg_days_ago']) ?>" class="wpf-field" style="width:70px;"/> <span style="white-space:nowrap;"><?php _e('days ago', 'wpforo'); ?></span></td>
                        </tr>
                        <?php $ugroups = WPF()->usergroup->usergroup_list_data(); ?>
                        <tr>
                          <td colspan="2">
                          <label style="display:inline-block; border-bottom:1px solid #ccc; padding:0px 50px 5px 10px; margin-bottom:5px;"> <?php _e('Filter by Usergroups', 'wpforo'); ?></label><br>
                          <?php 
							foreach($ugroups as $ugroup){
								if( $ugroup['groupid'] == 4 ) continue;
								$value = ( isset(WPF()->tools_cleanup['usergroup'][$ugroup['groupid']]) ) ? WPF()->tools_cleanup['usergroup'][$ugroup['groupid']] : 0;
								echo '<label style="display:inline-block; width:23%; text-align:center;"><input name="wpforo_tools_cleanup[usergroup]['.intval($ugroup['groupid']).']" value="1" type="checkbox" ' . wpfo_check(1, $value, 'checked', false) . ' style="font-size: 12px; height: 24px; width: 100%;" /> '.esc_html($ugroup['name']).'</label>';
							}
						  ?>
                          </td>
                        </tr>
                        <tr>
                            <th><label><?php _e('Enable Auto-cleanup of inactive users', 'wpforo'); ?></label></th>
                            <td>
                                <div class="wpf-switch-field">
                                    <input id="auto_cleanup_users_yes" type="radio" name="wpforo_tools_cleanup[auto_cleanup_users]" value="1" <?php wpfo_check(WPF()->tools_cleanup['auto_cleanup_users'], 1); ?>/><label for="auto_cleanup_users_yes"><?php _e('Yes','wpforo'); ?></label> &nbsp;
                                    <input id="auto_cleanup_users_no" type="radio" name="wpforo_tools_cleanup[auto_cleanup_users]" value="0" <?php wpfo_check(WPF()->tools_cleanup['auto_cleanup_users'], 0); ?>/><label for="auto_cleanup_users_no"><?php _e('No','wpforo'); ?></label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="wpforo_settings_foot" style="clear:both; margin-top:20px;">
                <input type="submit" class="button button-primary" value="<?php _e('Update Options', 'wpforo'); ?>" />
            </div>
		</form>
	<?php endif ?>
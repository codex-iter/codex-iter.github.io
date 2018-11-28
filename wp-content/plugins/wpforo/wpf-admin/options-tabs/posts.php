<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('ms') ) exit;
?>

<?php if(!isset(WPF()->post->options['max_upload_size'])){ $upload_max_filesize = @ini_get('upload_max_filesize'); $upload_max_filesize = wpforo_human_size_to_bytes($upload_max_filesize); if( !$upload_max_filesize || $upload_max_filesize > 10485760 ) $upload_max_filesize = 10485760; WPF()->post->options['max_upload_size'] = $upload_max_filesize; } ?>
<form action="" method="POST" class="validate">
	<?php wp_nonce_field( 'wpforo-settings-posts' ); ?>
    <table class="wpforo_settings_table">
		<tbody>
        	<?php do_action( 'wpforo_settings_post_top'); ?>
            <tr>
                <th><label><?php _e('Recent Posts Display Type','wpforo'); ?></label></th>
                <td>
                    <div class="wpf-switch-field">
                        <input id="rpt-topics" type="radio" name="wpforo_post_options[recent_posts_type]" value="topics" <?php wpfo_check(WPF()->post->options['recent_posts_type'], 'topics'); ?>/><label for="rpt-topics"><?php _e('Topics','wpforo'); ?></label> &nbsp;
                        <input id="rpt-posts" type="radio" name="wpforo_post_options[recent_posts_type]" value="posts" <?php wpfo_check(WPF()->post->options['recent_posts_type'], 'posts'); ?>/><label for="rpt-posts"><?php _e('Posts','wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label><?php _e('Enable Topic Tags','wpforo'); ?></label></th>
                <td>
                    <div class="wpf-switch-field">
                        <input id="topic_tags_1" type="radio" name="wpforo_post_options[tags]" value="1" <?php wpfo_check(WPF()->post->options['tags'], 1); ?>/><label for="topic_tags_1"><?php _e('Enable','wpforo'); ?></label> &nbsp;
                        <input id="topic_tags_0" type="radio" name="wpforo_post_options[tags]" value="0" <?php wpfo_check(WPF()->post->options['tags'], 0); ?>/><label for="topic_tags_0"><?php _e('Disable','wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="topic_tags_max"><?php _e('Maximum Number of Tags per Topic', 'wpforo'); ?></label></th>
                <td><input id="topic_tags_max" type="number" min="1" name="wpforo_post_options[max_tags]" value="<?php wpfo(WPF()->post->options['max_tags']) ?>" class="wpf-field-small" /></td>
            </tr>
            <tr>
                <th><label for="tags_per_page"><?php _e('Number of Tags per Page', 'wpforo'); ?> </th>
                <td><input id="tags_per_page" type="number" min="1" name="wpforo_post_options[tags_per_page]" value="<?php wpfo(WPF()->post->options['tags_per_page']) ?>" class="wpf-field-small" /></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e('Display Topic Editing Information','wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('The post edit logging information "This post was modified 2 hours ago by John" is displayed under modified topic first post content..', 'wpforo') ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input id="edit_topic_1" type="radio" name="wpforo_post_options[edit_topic]" value="1" <?php wpfo_check(WPF()->activity->options['edit_topic'], 1); ?>/><label for="edit_topic_1"><?php _e('Enable','wpforo'); ?></label> &nbsp;
                        <input id="edit_topic_0" type="radio" name="wpforo_post_options[edit_topic]" value="0" <?php wpfo_check(WPF()->activity->options['edit_topic'], 0); ?>/><label for="edit_topic_0"><?php _e('Disable','wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label><?php _e('Display Post Editing Information','wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('The post edit logging information "This post was modified 2 hours ago by John" is displayed under modified post content..', 'wpforo') ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input id="edit_post_1" type="radio" name="wpforo_post_options[edit_post]" value="1" <?php wpfo_check(WPF()->activity->options['edit_post'], 1); ?>/><label for="edit_post_1"><?php _e('Enable','wpforo'); ?></label> &nbsp;
                        <input id="edit_post_0" type="radio" name="wpforo_post_options[edit_post]" value="0" <?php wpfo_check(WPF()->activity->options['edit_post'], 0); ?>/><label for="edit_post_0"><?php _e('Disable','wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="tdcs"><?php _e('Limit Post Editing Information','wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('Limit the post edit logging information "This post was modified 2 hours ago by John"', 'wpforo') ?></p>
                    <p class="wpf-info"><?php _e('Set this option value 0 if you want to disable limiting', 'wpforo') ?></p>
                </th>
                <td>
                    <input id="tdcs" name="wpforo_post_options[edit_log_display_limit]" type="number" min="0" value="<?php wpfo(WPF()->activity->options['edit_log_display_limit']) ?>" class="wpf-field-small">
                </td>
            </tr>
            <tr>
                <th>
                    <label><?php _e('Display Topic Current Viewers','wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('Displays information about topic current viewers (users and guests)', 'wpforo') ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input id="display_current_viewers_1" type="radio" name="wpforo_post_options[display_current_viewers]" value="1" <?php wpfo_check(WPF()->post->options['display_current_viewers'], 1); ?>/><label for="display_current_viewers_1"><?php _e('Enable','wpforo'); ?></label> &nbsp;
                        <input id="display_current_viewers_0" type="radio" name="wpforo_post_options[display_current_viewers]" value="0" <?php wpfo_check(WPF()->post->options['display_current_viewers'], 0); ?>/><label for="display_current_viewers_0"><?php _e('Disable','wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label><?php _e('Display Topic Recent Viewers','wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('Displays information about topic recent viewers (users visited within last one hour)', 'wpforo') ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input id="display_recent_viewers_1" type="radio" name="wpforo_post_options[display_recent_viewers]" value="1" <?php wpfo_check(WPF()->post->options['display_recent_viewers'], 1); ?>/><label for="display_recent_viewers_1"><?php _e('Enable','wpforo'); ?></label> &nbsp;
                        <input id="display_recent_viewers_0" type="radio" name="wpforo_post_options[display_recent_viewers]" value="0" <?php wpfo_check(WPF()->post->options['display_recent_viewers'], 0); ?>/><label for="display_recent_viewers_0"><?php _e('Disable','wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label><?php _e('Display Admins with Topic Viewers','wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('By disabling this option you can exclude forum administrators from topic viewers list.', 'wpforo') ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input id="display_admin_viewers_1" type="radio" name="wpforo_post_options[display_admin_viewers]" value="1" <?php wpfo_check(WPF()->post->options['display_admin_viewers'], 1); ?>/><label for="display_admin_viewers_1"><?php _e('Enable','wpforo'); ?></label> &nbsp;
                        <input id="display_admin_viewers_0" type="radio" name="wpforo_post_options[display_admin_viewers]" value="0" <?php wpfo_check(WPF()->post->options['display_admin_viewers'], 0); ?>/><label for="display_admin_viewers_0"><?php _e('Disable','wpforo'); ?></label>
                    </div>
                </td>
            </tr>

			<tr>
				<th><label for="topics_per_page"><?php _e('Number of Topics per Page', 'wpforo'); ?> <a href="https://wpforo.com/docs/root/wpforo-settings/topic-post-settings/#item-per-page" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank"><i class="far fa-question-circle"></i></a></label></th>
				<td><input id="topics_per_page" type="number" min="1" name="wpforo_post_options[topics_per_page]" value="<?php wpfo(WPF()->post->options['topics_per_page']) ?>" class="wpf-field-small" /></td>
			</tr>
			<tr>
				<th>
                    <label for="eot_durr"><?php _e('Allow Edit Own Topic for', 'wpforo'); ?> <a href="https://wpforo.com/docs/root/wpforo-settings/topic-post-settings/#edit-delete-timeout" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank"><i class="far fa-question-circle"></i></a></label>
                    <p class="wpf-info"><?php _e('Set this option value 0 if you want to remove time limit.', 'wpforo') ?></p>
                </th>
				<td><input id="eot_durr" type="number" name="wpforo_post_options[eot_durr]" value="<?php wpfo(WPF()->post->options['eot_durr']/60) ?>" class="wpf-field-small" />&nbsp; <?php _e('minutes', 'wpforo') ?></td>
			</tr>
			<tr>
				<th>
                    <label for="dot_durr"><?php _e('Allow Delete Own Topic for', 'wpforo'); ?> <a href="https://wpforo.com/docs/root/wpforo-settings/topic-post-settings/#edit-delete-timeout" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank"><i class="far fa-question-circle"></i></a></label>
                    <p class="wpf-info"><?php _e('Set this option value 0 if you want to remove time limit.', 'wpforo') ?></p>
                </th>
				<td><input id="dot_durr" type="number" name="wpforo_post_options[dot_durr]" value="<?php wpfo(WPF()->post->options['dot_durr']/60) ?>" class="wpf-field-small" />&nbsp; <?php _e('minutes', 'wpforo') ?></td>
			</tr>
			<tr>
				<th><label for="posts_per_page"><?php _e('Number of Posts per Page', 'wpforo'); ?> <a href="https://wpforo.com/docs/root/wpforo-settings/topic-post-settings/#item-per-page" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank"><i class="far fa-question-circle"></i></a></label></th>
				<td><input id="posts_per_page" type="number" min="1" name="wpforo_post_options[posts_per_page]" value="<?php wpfo(WPF()->post->options['posts_per_page']) ?>" class="wpf-field-small" /></td>
			</tr>
			<tr>
				<th>
                    <label for="eor_durr"><?php _e('Allow Edit Own Post for', 'wpforo'); ?> <a href="https://wpforo.com/docs/root/wpforo-settings/topic-post-settings/#edit-delete-timeout" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank"><i class="far fa-question-circle"></i></a></label>
                    <p class="wpf-info"><?php _e('Set this option value 0 if you want to remove time limit.', 'wpforo') ?></p>
                </th>
				<td><input id="eor_durr" type="number" name="wpforo_post_options[eor_durr]" value="<?php wpfo(WPF()->post->options['eor_durr']/60) ?>" class="wpf-field-small" />&nbsp; <?php _e('minutes', 'wpforo') ?></td>
			</tr>
			<tr>
				<th>
                    <label for="dor_durr"><?php _e('Allow Delete Own post for', 'wpforo'); ?> <a href="https://wpforo.com/docs/root/wpforo-settings/topic-post-settings/#edit-delete-timeout" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank"><i class="far fa-question-circle"></i></a></label>
                    <p class="wpf-info"><?php _e('Set this option value 0 if you want to remove time limit.', 'wpforo') ?></p>
                </th>
				<td><input id="dor_durr" type="number" name="wpforo_post_options[dor_durr]" value="<?php wpfo(WPF()->post->options['dor_durr']/60) ?>" class="wpf-field-small" />&nbsp; <?php _e('minutes', 'wpforo') ?></td>
			</tr>
            
            <tr>
				<th>
                	<label><?php _e('Maximum upload file size', 'wpforo'); ?> <a href="https://wpforo.com/docs/root/wpforo-settings/topic-post-settings/#max-file-size" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank"><i class="far fa-question-circle"></i></a></label>
                	<p class="wpf-info"><?php _e('You can not set this value more than "upload_max_filesize" and "post_max_size". If you want to increase server parameters please contact to your hosting service support.', 'wpforo'); ?></p>
                </th>
				<td>
                	<input type="number" min="1" name="wpforo_post_options[max_upload_size]" value="<?php echo wpforo_print_size(WPF()->post->options['max_upload_size'], false) ?>" class="wpf-field-small" />&nbsp; <?php _e('MB', 'wpforo') ?>
                	<p class="wpf-info">
                     	<?php
							_e('Server "upload_max_filesize" is '); echo ini_get('upload_max_filesize') . '<br/>';
							_e('Server "post_max_size" is '); echo ini_get('post_max_size');
                        ?>
                    </p>
                </td>
			</tr> 
			
			<tr>
				<th>
                	<label><?php _e('Attachment click - message for non-permitted users', 'wpforo'); ?> <a href="https://wpforo.com/docs/root/wpforo-settings/topic-post-settings/#no-attach-message" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank"><i class="far fa-question-circle"></i></a></label>
                	<p class="wpf-info"><?php _e('This message will be displayed when a non-permitted forum member clicks on attached file link in topic and posts.', 'wpforo'); ?></p>
                </th>
				<td>
					<textarea name="wpforo_post_options[attach_cant_view_msg]"><?php echo esc_textarea( ( !empty( WPF()->post->options['attach_cant_view_msg'] ) ? WPF()->post->options['attach_cant_view_msg'] : '' ) ) ?></textarea>
                </td>
			</tr>
            <?php do_action('wpforo_settings_post_bottom'); ?>
		</tbody>
	</table>
    <div class="wpforo_settings_foot">
        <input type="submit" class="button button-primary" value="<?php _e('Update Options', 'wpforo'); ?>" />
    </div>
</form>
<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('ms') ) exit;
?>


<form action="" method="POST" class="validate">
	<?php wp_nonce_field( 'wpforo-settings-forums' ); ?>
	<table class="wpforo_settings_table">
		<tbody>
            <?php do_action('wpforo_settings_forums'); ?>
            <tr>
                <th>
                    <label><?php _e('Display Forum Current Viewers','wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('Displays information about forum current viewers (x viewing) next to forum title.', 'wpforo') ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input id="display_current_viewers_1" type="radio" name="wpforo_forum_options[display_current_viewers]" value="1" <?php wpfo_check(WPF()->forum->options['display_current_viewers'], 1); ?>/><label for="display_current_viewers_1"><?php _e('Enable','wpforo'); ?></label> &nbsp;
                        <input id="display_current_viewers_0" type="radio" name="wpforo_forum_options[display_current_viewers]" value="0" <?php wpfo_check(WPF()->forum->options['display_current_viewers'], 0); ?>/><label for="display_current_viewers_0"><?php _e('Disable','wpforo'); ?></label>
                    </div>
                </td>
            </tr>
		</tbody>
	</table>
    <div class="wpforo_settings_foot">
        <input type="submit" class="button button-primary" value="<?php _e('Update Options', 'wpforo'); ?>" />
    </div>
</form>

<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('ms') ) exit;
?>

<form action="" method="POST" class="validate">
	<?php wp_nonce_field( 'wpforo-settings-api' ); ?>
	<table class="wpforo_settings_table">
		<tbody>
        	<?php do_action('wpforo_settings_api_top'); ?>
            <tr>
                <td colspan="2" style="border-bottom:3px solid #395598;">
                	<h3 style="font-weight:600; padding:0px 0px 0px 0px; margin:0px; text-align:right; color:#666666;">
                    	<div style="float:left; height:30px; line-height:25px;"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/fb-m.png' ?>" align="middle" style="height: 100%"/></div>
                    	Facebook API &nbsp;
                    </h3>
                </td>
            </tr>
            <tr>
				<th style="padding-top:15px;">
                	<label><?php _e('Facebook API Configuration', 'wpforo'); ?></label>
                	<p class="wpf-info"><?php _e('In order to get an App ID and Secret Key from Facebook, you’ll need to register a new application. Don’t worry – its very easy, and your application doesn\'t need to do anything. We only need the keys.', 'wpforo'); ?> <a href="https://wpforo.com/community/faq/how-to-get-facebook-app-id-and-secret-key/" target="_blank"><?php _e('Please follow to this instruction', 'wpforo'); ?> &raquo;</a></p>
                </th>
				<td style="padding-top:15px;">
					<input name="wpforo_api_options[fb_api_id]" placeholder="<?php _e('App ID', 'wpforo'); ?>" type="text" value="<?php echo trim(WPF()->api->options['fb_api_id']); ?>"/>&nbsp; <?php _e('App ID', 'wpforo'); ?><br />
                    <input name="wpforo_api_options[fb_api_secret]" placeholder="<?php _e('App Secret', 'wpforo'); ?>" type="text" value="<?php echo trim(WPF()->api->options['fb_api_secret']); ?>"/>&nbsp; <?php _e('App Secret', 'wpforo'); ?>
				</td>
			</tr>
            <tr>
                <th>
                	<label><?php _e('Facebook Login', 'wpforo'); ?></label>
                	<p class="wpf-info"><?php _e('Adds Facebook Login button on Registration and Login pages.', 'wpforo'); ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input type="radio" value="1" name="wpforo_api_options[fb_login]" id="fb_login_1" <?php wpfo_check(WPF()->api->options['fb_login'], 1); ?>><label for="fb_login_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_api_options[fb_login]" id="fb_login_0" <?php wpfo_check(WPF()->api->options['fb_login'], 0); ?>><label for="fb_login_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                	<label><?php _e('Facebook SDK for JavaScript', 'wpforo'); ?></label>
                	<p class="wpf-info"><?php _e('Facebook API connection script (sharing, login, cross-posting...)', 'wpforo'); ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input type="radio" value="1" name="wpforo_api_options[fb_load_sdk]" id="fb_load_sdk_1" <?php wpfo_check(WPF()->api->options['fb_load_sdk'], 1); ?>><label for="fb_load_sdk_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_api_options[fb_load_sdk]" id="fb_load_sdk_0" <?php wpfo_check(WPF()->api->options['fb_load_sdk'], 0); ?>><label for="fb_load_sdk_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                	<label><?php _e('Facebook Login button on User Login page', 'wpforo'); ?></label>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input type="radio" value="1" name="wpforo_api_options[fb_lb_on_lp]" id="fb_lb_on_lp_1" <?php wpfo_check(WPF()->api->options['fb_lb_on_lp'], 1); ?>><label for="fb_lb_on_lp_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_api_options[fb_lb_on_lp]" id="fb_lb_on_lp_0" <?php wpfo_check(WPF()->api->options['fb_lb_on_lp'], 0); ?>><label for="fb_lb_on_lp_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                	<label><?php _e('Facebook Login button on User Registration page', 'wpforo'); ?></label>
                </th>
                <td>
                    <div class="wpf-switch-field">
                        <input type="radio" value="1" name="wpforo_api_options[fb_lb_on_rp]" id="fb_lb_on_rp_1" <?php wpfo_check(WPF()->api->options['fb_lb_on_rp'], 1); ?>><label for="fb_lb_on_rp_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_api_options[fb_lb_on_rp]" id="fb_lb_on_rp_0" <?php wpfo_check(WPF()->api->options['fb_lb_on_rp'], 0); ?>><label for="fb_lb_on_rp_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                	<label><?php _e('Redirect to this page after success login', 'wpforo'); ?></label>
                </th>
                <td>
                    <div class="wpf-switch-field">
                           <input type="radio" value="profile" name="wpforo_api_options[fb_redirect]" id="fb_redirect_2" <?php wpfo_check(WPF()->api->options['fb_redirect'], 'profile'); ?>><label for="fb_redirect_2">&nbsp;<?php _e('Profile', 'wpforo'); ?>&nbsp;</label>
                    	   <input type="radio" value="home" name="wpforo_api_options[fb_redirect]" id="fb_redirect_1" <?php wpfo_check(WPF()->api->options['fb_redirect'], 'home'); ?>><label for="fb_redirect_1">&nbsp;<?php _e('Forums', 'wpforo'); ?>&nbsp;</label> &nbsp;
                    	   <input type="radio" value="custom" name="wpforo_api_options[fb_redirect]" id="fb_redirect_3" <?php wpfo_check(WPF()->api->options['fb_redirect'], 'custom'); ?>><label for="fb_redirect_3">&nbsp;<?php _e('Custom', 'wpforo'); ?>&nbsp;</label> &nbsp;
                    </div>
                    <input style="margin-top:10px; padding:3px 5px; font-size:13px; width:48%;" name="wpforo_api_options[fb_redirect_url]" placeholder="<?php _e('Custom URL, e.g.: http://example.com/my-page/', 'wpforo'); ?>" type="text" value="<?php echo trim(WPF()->api->options['fb_redirect_url']); ?>"/>&nbsp; <?php _e('Custom URL', 'wpforo'); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-bottom:3px solid #00C000;">
                    <h3 style="font-weight:600; padding:0px 0px 0px 0px; margin:0px; text-align:right; color:#666666;">
                        <div style="float:left; height:30px; line-height:25px;"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/share-m.png' ?>" align="middle" style="height: 100%" /></div>
                        <?php _e('Share Buttons', 'wpforo'); ?> &nbsp;
                    </h3>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 2px"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e('Active Share Buttons', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('Check the checkbox below share options to activate. <b>Please note, that the Facebook share button cannot be activated without Facebook API ID.</b> Please follow to the "Facebook API Configuration" option instruction above and fill the API ID field in order to activate Facebook Share button.', 'wpforo'); ?></p>
                </th>
                <td>
                    <?php $_sb = WPF()->api->options['sb']; ?>
                    <input type="hidden" name="wpforo_api_options[sb][x]" value="1">
                    <div style="float: left; width: 40px; text-align: center; background: #3B5A9A; padding: 1px 18px 3px 18px; margin: 10px 5px; margin-left: 0;">
                        <label for="sb_fb"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/fb-m.png' ?>" align="middle" style="width: 30px" /></label><br>
                        <input id="sb_fb" type="checkbox" name="wpforo_api_options[sb][fb]" value="1" <?php if(isset($_sb['fb']) && $_sb['fb'] ) echo 'checked'; ?> <?php if(!WPF()->api->options['fb_api_id']) echo 'disabled'; ?>/>
                    </div>
                    <div style="float: left; width: 40px; text-align: center; background: #00A3F5; padding: 1px 18px 3px 18px; margin: 10px 5px;">
                        <label for="sb_tw"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/tw-m.png' ?>" align="middle" style="width: 30px" /></label><br>
                        &nbsp;<input id="sb_tw" type="checkbox" name="wpforo_api_options[sb][tw]" value="1" <?php if(isset($_sb['tw']) && $_sb['tw'] ) echo 'checked'; ?> />
                    </div>
                    <div style="float: left; width: 40px; text-align: center; background: #FF492D; padding: 1px 18px 3px 18px; margin: 10px 5px;">
                        <label for="sb_gg"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/gg-m.png' ?>" align="middle" style="width: 30px" /></label><br>
                        &nbsp;<input id="sb_gg" type="checkbox" name="wpforo_api_options[sb][gg]" value="1" <?php if(isset($_sb['gg']) && $_sb['gg'] ) echo 'checked'; ?> />
                    </div>
                    <div style="float: left; width: 40px; text-align: center; background: #2D76A6; padding: 1px 18px 3px 18px; margin: 10px 5px;">
                        <label for="sb_vk"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/vk-m.png' ?>" align="middle" style="width: 30px" /></label><br>
                        &nbsp;&nbsp;<input id="sb_vk" type="checkbox" name="wpforo_api_options[sb][vk]" value="1" <?php if(isset($_sb['vk']) && $_sb['vk'] ) echo 'checked'; ?> />
                    </div>
                    <div style="float: left; width: 40px; text-align: center; background: #FF7800; padding: 1px 18px 3px 18px; margin: 10px 5px;">
                        <label for="sb_ok"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/ok-m.png' ?>" align="middle" style="width: 30px" /></label><br>
                        &nbsp;&nbsp;<input id="sb_ok" type="checkbox" name="wpforo_api_options[sb][ok]" value="1" <?php if(isset($_sb['ok']) && $_sb['ok'] ) echo 'checked'; ?> />
                    </div>
                    <div style="clear: both;"></div>
                </td>
            </tr>
            <tr>
                <th style="padding-top:5px;">
                    <label><?php _e('Enable Share Buttons', 'wpforo'); ?></label>
                    <p class="wpf-info"></p>
                </th>
                <td style="padding-top:10px; margin-right: 5px;">
                    <div style="float: left;">
                        <p style="margin: 0px; font-size: 14px;"><?php _e('General Share Buttons', 'wpforo'); ?></p>
                        <div class="wpf-switch-field" style="margin-top: 10px; margin-right: 5px;">
                            <input type="radio" value="1" name="wpforo_api_options[sb_on]" id="sb_on_2" <?php wpfo_check(WPF()->api->options['sb_on'], 1); ?>><label for="sb_on_2" style="width: 100px;">&nbsp;<?php _e('Enable', 'wpforo'); ?>&nbsp;</label> &nbsp;
                            <input type="radio" value="0" name="wpforo_api_options[sb_on]" id="sb_on_1" <?php wpfo_check(WPF()->api->options['sb_on'], 0); ?>><label for="sb_on_1" style="width: 100px;">&nbsp;<?php _e('Disable', 'wpforo'); ?>&nbsp;</label>
                        </div>
                    </div>
                    <div style="float: left; margin-left: 10px;">
                        <p style="margin: 0px; font-size: 14px;"><?php _e('Post Sharing Toggle', 'wpforo'); ?></p>
                        <div class="wpf-switch-field" style="margin-top: 10px; margin-bottom: 5px;">
                            <input type="radio" value="1" name="wpforo_api_options[sb_toggle_on]" id="sb_toggle_on_2" <?php wpfo_check(WPF()->api->options['sb_toggle_on'], 1); ?>><label for="sb_toggle_on_2" style="width: 100px;">&nbsp;<?php _e('Enable', 'wpforo'); ?>&nbsp;</label> &nbsp;
                            <input type="radio" value="0" name="wpforo_api_options[sb_toggle_on]" id="sb_toggle_on_1" <?php wpfo_check(WPF()->api->options['sb_toggle_on'], 0); ?>><label for="sb_toggle_on_1" style="width: 100px;">&nbsp;<?php _e('Disable', 'wpforo'); ?>&nbsp;</label>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </td>
            </tr>
            <tr>
                <th style="padding-top:5px;">
                    <label><?php _e('General Share Buttons', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('General share buttons are forum and topic sharing buttons. They are located on the top and the bottom of each page. You can manage location of these buttons using "Share Buttons Location" options bellow.', 'wpforo'); ?></p>
                </th>
                <td style="padding-top:20px;">
                    <div class="wpf-switch-field" style="margin-bottom: 12px;">
                        <input type="radio" value="grey" name="wpforo_api_options[sb_style]" id="sb_style_1" <?php wpfo_check(WPF()->api->options['sb_style'], 'grey'); ?>><label for="sb_style_1" style="width: 100px;">&nbsp;<?php _e('Grey', 'wpforo'); ?>&nbsp;</label> &nbsp;
                        <input type="radio" value="colored" name="wpforo_api_options[sb_style]" id="sb_style_2" <?php wpfo_check(WPF()->api->options['sb_style'], 'colored'); ?>><label for="sb_style_2" style="width: 100px;">&nbsp;<?php _e('Colored', 'wpforo'); ?>&nbsp;</label> &nbsp;
                    </div>
                    <div class="wpf-switch-field">
                        <input type="radio" value="icon" name="wpforo_api_options[sb_type]" id="sb_type_2" <?php wpfo_check(WPF()->api->options['sb_type'], 'icon'); ?>><label for="sb_type_2" style="width: 100px;">&nbsp;<?php _e('Icon', 'wpforo'); ?>&nbsp;</label>
                        <input type="radio" value="button" name="wpforo_api_options[sb_type]" id="sb_type_1" <?php wpfo_check(WPF()->api->options['sb_type'], 'button'); ?>><label for="sb_type_1" style="width: 100px;">&nbsp;<?php _e('Button', 'wpforo'); ?>&nbsp;</label> &nbsp;
                        <input type="radio" value="button_count" name="wpforo_api_options[sb_type]" id="sb_type_3" <?php wpfo_check(WPF()->api->options['sb_type'], 'button_count'); ?>><label for="sb_type_3" style="width: 150px;">&nbsp;<?php _e('Button &amp; Count', 'wpforo'); ?>&nbsp;</label> &nbsp;
                    </div>
                </td>
            </tr>
            <tr>
                <th style="padding-top:5px;">
                    <label><?php _e('Post Sharing Toggle', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('Post sharing toggle allows you to share posts individually. You can see post sharing toggles on the left, right side or in top bar of each post. The toggle blue color is the current primary color (#12) of your forum style. For example, if you use the red forum style, the color of all share toggles will be red. This doesn\'t affect share button colors. They are always grey with original colors on mouse hover.', 'wpforo'); ?></p>
                </th>
                <td style="padding-top:5px;">
                    <div style="float: left; background: #fff; width: 75px; text-align: center; padding: 1px 18px 3px 18px; margin: 10px 5px 10px 0px; border: 1px solid #ddd;">
                        <label for="sb_toggle_1"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/toggle-1.png' ?>" align="middle"/></label><br>
                        &nbsp;&nbsp;<input id="sb_toggle_1" type="radio" name="wpforo_api_options[sb_toggle]" value="1" <?php wpfo_check(WPF()->api->options['sb_toggle'], 1); ?> />
                    </div>
                    <div style="float: left; background: #fff; width: 75px; text-align: center; padding: 1px 18px 3px 18px; margin: 10px 5px; border: 1px solid #ddd;">
                        <label for="sb_toggle_2"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/toggle-2.png' ?>" align="middle"/></label><br>
                        &nbsp;&nbsp;<input id="sb_toggle_2" type="radio" name="wpforo_api_options[sb_toggle]" value="2" <?php wpfo_check(WPF()->api->options['sb_toggle'], 2); ?> />
                    </div>
                    <div style="float: left; background: #fff; width: 75px; text-align: center; padding: 1px 18px 3px 18px; margin: 10px 5px; border: 1px solid #ddd;">
                        <label for="sb_toggle_3"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/toggle-3.png' ?>" align="middle"/></label><br>
                        &nbsp;&nbsp;<input id="sb_toggle_3" type="radio" name="wpforo_api_options[sb_toggle]" value="3" <?php wpfo_check(WPF()->api->options['sb_toggle'], 3); ?> />
                    </div>
                    <div style="float: left; background: #fff; width: 75px; text-align: center; padding: 1px 18px 3px 18px; margin: 10px 5px; border: 1px solid #ddd;">
                        <label for="sb_toggle_4"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/toggle-4.png' ?>" align="middle"/></label><br>
                        &nbsp;&nbsp;<input id="sb_toggle_4" type="radio" name="wpforo_api_options[sb_toggle]" value="4" <?php wpfo_check(WPF()->api->options['sb_toggle'], 4); ?> />
                    </div>
                    <div style="clear: both;"></div>
                    <div class="wpf-switch-field" style="margin-top: 10px; margin-bottom: 5px;">
                        <input type="radio" value="collapsed" name="wpforo_api_options[sb_toggle_type]" id="sb_toggle_type_2" <?php wpfo_check(WPF()->api->options['sb_toggle_type'], 'collapsed'); ?>><label for="sb_toggle_type_2" style="width: 100px;">&nbsp;<?php _e('Collapsed', 'wpforo'); ?>&nbsp;</label> &nbsp;
                        <input type="radio" value="expanded" name="wpforo_api_options[sb_toggle_type]" id="sb_toggle_type_1" <?php wpfo_check(WPF()->api->options['sb_toggle_type'], 'expanded'); ?>><label for="sb_toggle_type_1" style="width: 100px;">&nbsp;<?php _e('Expanded', 'wpforo'); ?>&nbsp;</label>
                    </div>
                    <div class="wpf-switch-field" style="margin-top: 12px; margin-bottom: 5px;">
                        <input type="radio" value="mixed" name="wpforo_api_options[sb_icon]" id="sb_sb_icon_3" <?php wpfo_check(WPF()->api->options['sb_icon'], 'mixed'); ?>><label for="sb_sb_icon_3" style="width: 100px;"><?php _e('Mixed', 'wpforo'); ?>&nbsp;</label>
                        <input type="radio" value="figure" name="wpforo_api_options[sb_icon]" id="sb_sb_icon_1" <?php wpfo_check(WPF()->api->options['sb_icon'], 'figure'); ?>><label for="sb_sb_icon_1" style="width: 100px;">&nbsp;<i class="fab fa-facebook-f" style="font-size: 13px;"></i> &nbsp;<?php _e('Figure', 'wpforo'); ?>&nbsp;</label>
                        <input type="radio" value="square" name="wpforo_api_options[sb_icon]" id="sb_sb_icon_2" <?php wpfo_check(WPF()->api->options['sb_icon'], 'square'); ?>><label for="sb_sb_icon_2" style="width: 100px;">&nbsp;<i class="fab fa-facebook-square" style="font-size: 14px;"></i> &nbsp;<?php _e('Square', 'wpforo'); ?>&nbsp;</label> &nbsp;
                    </div>
                </td>
            </tr>
            <tr>
                <th style="padding-top:15px;" colspan="2">
                    <label><?php _e('Share Button Locations', 'wpforo'); ?></label>
                    <p class="wpf-info" style="margin-bottom: 5px;"><?php _e('The post sharing toggle can be displayed either on the left side or on the top of each post. The general share buttons can be displayed on both (top and bottom) locations.', 'wpforo'); ?></p>
                    <?php $_lc = WPF()->api->options['sb_location']; ?>
                    <input type="hidden" name="wpforo_api_options[sb_location][x]" value="1">
                    <div style="padding-right: 10px; display: inline-block; width: auto; border-right: 1px solid #ccc; ">
                        <p style="text-align: center; margin: 0px; font-weight: normal; font-size: 14px;"><?php _e('General Share Buttons', 'wpforo'); ?></p>
                        <div style="float: left; background: #fff; display: inline-block; text-align: center; padding: 1px 5px 3px 5px; margin: 10px 5px 10px 0px;">
                            <label for="sb_location_4"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/location-3.png' ?>" align="middle" style="width: 180px"/></label><br>
                            &nbsp;&nbsp;<input id="sb_location_4" type="checkbox" name="wpforo_api_options[sb_location][top]" value="1" <?php if(isset($_lc['top']) && $_lc['top'] ) echo 'checked'; ?>/>
                        </div>
                        <div style="float: left; background: #fff; display: inline-block; text-align: center; padding: 1px 5px 3px 5px; margin: 10px 5px 10px 0px;">
                            <label for="sb_location_5"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/location-5.png' ?>" align="middle" style="width: 180px"/></label><br>
                            &nbsp;&nbsp;<input id="sb_location_5" type="checkbox" name="wpforo_api_options[sb_location][bottom]" value="1" <?php if(isset($_lc['bottom']) && $_lc['bottom'] ) echo 'checked'; ?>/>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="padding-left: 10px; display: inline-block; width: auto;">
                        <p style="text-align: center; margin: 0px; font-weight: normal; font-size: 14px;"><?php _e('Post Sharing Toggle', 'wpforo'); ?></p>
                        <div style="float: left; background: #fff; display: inline-block; text-align: center; padding: 1px 5px 3px 5px; margin: 10px 5px 10px 0px;">
                            <label for="sb_location_1"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/location-1.png' ?>" align="middle" style="width: 180px"/></label><br>
                            &nbsp;&nbsp;<input id="sb_location_1" type="radio" name="wpforo_api_options[sb_location_toggle]" value="left" <?php wpfo_check(WPF()->api->options['sb_location_toggle'], 'left'); ?> />
                        </div>
                        <div style="float: left; background: #fff; display: inline-block; text-align: center; padding: 1px 5px 3px 5px; margin: 10px 5px 10px 0px;">
                            <label for="sb_location_3"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/location-6.png' ?>" align="middle" style="width: 180px"/></label><br>
                            &nbsp;&nbsp;<input id="sb_location_3" type="radio" name="wpforo_api_options[sb_location_toggle]" value="right" <?php wpfo_check(WPF()->api->options['sb_location_toggle'], 'right'); ?> />
                        </div>
                        <div style="float: left; background: #fff; display: inline-block; text-align: center; padding: 1px 5px 3px 5px; margin: 10px 5px 10px 0px;">
                            <label for="sb_location_2"><img src="<?php echo WPFORO_URL . '/wpf-assets/images/sn/location-2.png' ?>" align="middle" style="width: 180px"/></label><br>
                            &nbsp;&nbsp;<input id="sb_location_2" type="radio" name="wpforo_api_options[sb_location_toggle]" value="top" <?php wpfo_check(WPF()->api->options['sb_location_toggle'], 'top'); ?> />
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </td>
            </tr>
            <?php do_action('wpforo_settings_api_bottom'); ?>
		</tbody>
	</table>
    <div class="wpforo_settings_foot">
        <input type="submit" class="button button-primary" value="<?php _e('Update Options', 'wpforo'); ?>" />
    </div>
</form>
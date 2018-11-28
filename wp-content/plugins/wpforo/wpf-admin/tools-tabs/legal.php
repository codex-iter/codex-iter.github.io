<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;
if( !current_user_can('administrator') ) exit;
?>

<form action="" method="POST" class="validate">
    <?php wp_nonce_field( 'wpforo-tools-legal' ); ?>
    <table class="wpforo_settings_table">
        <tbody>
            <tr></tr>
            <tr>
                <td colspan="2" style="border-bottom:3px solid #43A6DF; padding-top: 20px; padding-bottom: 15px;">
                    <h3 style="font-weight:600; padding:0px 0px 5px 0px; margin:0px; color:#666666; font-size: 18px;">
                        <?php _e('Forum Privacy Policy and GDPR compliant', 'wpforo') ?> &nbsp;|&nbsp; <a href="https://wpforo.com/docs/root/gdpr/" rel="noreferrer" style="text-decoration: none; font-weight: normal;" target="_blank"><?php _e('Documentation', 'wpforo'); ?></a>
                    </h3>
                    <p class="wpf-info">
                        <?php _e('The General Data Protection Regulation (GDPR) (Regulation (EU) 2016/679) is a regulation by which the European Parliament, the Council of the European Union and the European Commission intend to strengthen and unify data protection for all individuals within the European Union (EU). After four years of preparation and debate the GDPR was finally approved by the EU Parliament on 14 April 2016. Enforcement date: 25 May 2018 - at which time those organizations in non-compliance may face heavy fines. More info at', 'wpforo'); ?>
                        <a href="https://www.eugdpr.org/key-changes.html" title="<?php _e('GDPR Key Changes', 'wpforo') ?>" target="_blank" rel="noreferrer">GDPR Portal</a>
                    </p>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 10px; ">
                    <label><?php _e('Contact Information', 'wpforo'); ?></label>
                    <p class="wpf-info">
                        <?php _e('According to the GDPR, all users should have an option to contact website administrator in following cases:', 'wpforo'); ?>
                    <ul class="wpf-info" style="list-style: disc; padding: 5px 0px 0px 0px; margin: 0px 20px; line-height: 14px;">
                        <li><?php _e('Obtain personal data and created content', 'wpforo') ?></li>
                        <li><?php _e('Delete account with created content', 'wpforo') ?></li>
                        <li><?php _e('Report user data access and control issue', 'wpforo') ?></li>
                        <li><?php _e('Report user rights violation', 'wpforo') ?></li>
                    </ul>
                    </p>
                </th>
                <td style="padding-top:35px;">
                    <input name="wpforo_tools_legal[contact_page_url]" placeholder="<?php _e('URL to - Contact Us - page', 'wpforo'); ?>" type="text" value="<?php echo trim(WPF()->tools_legal['contact_page_url']); ?>" style="width: 80%; margin-bottom: 10px;"/>&nbsp;
                    <br><?php _e('Please insert a page URL, where user can find a contact form or an information to contact the forum administrator.', 'wpforo'); ?><br />
                </td>
            </tr>
            <tr>
                <th style="padding-top: 10px;">
                    <label><?php _e('Checkbox: I Accept Website Terms and Privacy Policy', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('If this option is enabled, users must accept forum Terms and Privacy Policy by checking the required checkbox on registration form to be able create a forum account. The checkbox label can be managed in Forums > Phrases admin page.', 'wpforo'); ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field" style="padding-top: 30px;">
                        <input type="radio" value="1" name="wpforo_tools_legal[checkbox_terms_privacy]" id="checkbox_terms_privacy_1" <?php wpfo_check(WPF()->tools_legal['checkbox_terms_privacy'], 1); ?>><label for="checkbox_terms_privacy_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_tools_legal[checkbox_terms_privacy]" id="checkbox_terms_privacy_0" <?php wpfo_check(WPF()->tools_legal['checkbox_terms_privacy'], 0); ?>><label for="checkbox_terms_privacy_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 10px; ">
                    <label><?php _e('Checkbox: I Agree to Receive an Email Confirmation', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('If this option is enabled, users must agree to receive an email confirmation with a link to set a password by checking the required checkbox on registration form to be able create a forum account. The checkbox label can be managed in Forums > Phrases admin page.', 'wpforo'); ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field" style="padding-top: 30px;">
                        <input type="radio" value="1" name="wpforo_tools_legal[checkbox_email_password]" id="checkbox_email_password_1" <?php wpfo_check(WPF()->tools_legal['checkbox_email_password'], 1); ?>><label for="checkbox_email_password_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_tools_legal[checkbox_email_password]" id="checkbox_email_password_0" <?php wpfo_check(WPF()->tools_legal['checkbox_email_password'], 0); ?>><label for="checkbox_email_password_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 10px; ">
                    <label><?php _e('Website Terms and Privacy Policy Pages', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('Please insert URLs to your website Terms and Privacy Policy pages. Links to these pages will be included in registration form checkbox label (I\'m agree with website terms and privacy policy) and in Forum Privacy Policy. The forum Privacy Policy does not cover your whole website, it is just an extension of your website main Privacy Policy. Thus it should be linked to according pages.', 'wpforo'); ?></p>
                </th>
                <td style="padding-top:35px;">
                    <input name="wpforo_tools_legal[page_terms]" placeholder="<?php _e('URL to Website Terms page', 'wpforo'); ?>" type="text" value="<?php echo trim(WPF()->tools_legal['page_terms']); ?>" style="width: 50%; margin-bottom: 10px;"/>&nbsp; <?php _e('Terms Page URL', 'wpforo'); ?><br />
                    <input name="wpforo_tools_legal[page_privacy]" placeholder="<?php _e('URL to Website Privacy Policy page', 'wpforo'); ?>" type="text" value="<?php echo trim(WPF()->tools_legal['page_privacy']); ?>" style="width: 50%; margin-bottom: 5px;"/>&nbsp; <?php _e('Privacy Policy Page URL', 'wpforo'); ?>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 10px;">
                    <label><?php _e('Checkbox: I Agree to Forum Privacy Policy', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('If this option is enabled, users must accept forum Terms and Privacy Policy by checking the required checkbox on registration form to be able create a forum account. The checkbox label can be managed in Forums > Phrases admin page.', 'wpforo'); ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field" style="padding-top: 30px;">
                        <input type="radio" value="1" name="wpforo_tools_legal[checkbox_forum_privacy]" id="checkbox_forum_privacy_1" <?php wpfo_check(WPF()->tools_legal['checkbox_forum_privacy'], 1); ?>><label for="checkbox_forum_privacy_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_tools_legal[checkbox_forum_privacy]" id="checkbox_forum_privacy_0" <?php wpfo_check(WPF()->tools_legal['checkbox_forum_privacy'], 0); ?>><label for="checkbox_forum_privacy_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 10px;" colspan="2">
                    <label><?php _e('Forum Privacy Policy with GDPR compliant Template', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('This is an example of forum Privacy Policy with GDPR compliant. It adapted to wpForo plugin functions and features. <u>In case you enable this privacy policy template you become responsible for the content of this template.</u>  Please read this text carefully and make sure it suits your community Privacy Policy. If it doesn\'t, you should edit this text and adapt it to your community rules. This template includes shortcodes [forum-name] and [forum-url]. They are automatically replaced on registration page with current forum details. Don\'t forget to add an information about your organization, location and contacting ways (page, email, phone, etc...). Also if you have a separate privacy policy page for website please add a link to that page.', 'wpforo'); ?></p>
                    <div style="margin-top: ">
                        <?php
                        $value = WPF()->tools_legal['forum_privacy_text'];
                        if(is_null($value)){
                            $file = WPFORO_DIR . '/wpf-admin/html/privacy-policy-gdpr.html';
                            $value = wpforo_get_file_content( $file );
                        }
                        $args = array(
                            'teeny' => false,
                            'media_buttons' => false,
                            'textarea_rows' => '12',
                            'tinymce'       => true,
                            'quicktags'     => array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close' ),
                            'textarea_name' => 'wpforo_tools_legal[forum_privacy_text]',
                        );
                        wp_editor( wp_unslash($value), 'wpforo_tools_legal_forum_privacy_text', $args ); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 20px;">
                    <label><?php _e('Checkbox: I Agree to create a forum account on Facebook Login', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('If this option is enabled, the Facebook Login button becomes not-clickable until user accept automatic account creation process based on his/her Facebook public profile information. This checkbox and appropriate information will be displayed with Facebook Login button to comply with the GDPR', 'wpforo'); ?> <a href="https://gdpr-info.eu/art-22-gdpr/" target="_blank" rel="noreferrer">(Article 22)</a> <br><?php wpforo_phrase('The note text and the label of this checkbox can be managed in Forums > Phrases admin page. Search the label phrase, click on edit button and change it.') ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field" style="padding-top: 40px;">
                        <input type="radio" value="1" name="wpforo_tools_legal[checkbox_fb_login]" id="checkbox_fb_login_1" <?php wpfo_check(WPF()->tools_legal['checkbox_fb_login'], 1); ?>><label for="checkbox_fb_login_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_tools_legal[checkbox_fb_login]" id="checkbox_fb_login_0" <?php wpfo_check(WPF()->tools_legal['checkbox_fb_login'], 0); ?>><label for="checkbox_fb_login_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 20px;">
                    <label><?php _e('Forum Cookies', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('Please note, that this option is only related to wpForo cookies. This doesn\'t disable WordPress and other plugins cookies. wpForo stores a small amount of data in cookies, it used to track visited forums and topics (bold and normal titles). Also when a guest (not registered user) creates a topic or post a reply, wpForo stores guest name and email address in cookies. wpForo uses this information to detect current guest content (topics, posts) and display it to the guest even if the content is under moderation (not approved by moderators). Also wpForo stores guest name and email in cookies to keep filled these fields when he/she posts a new reply or creates a new topic.', 'wpforo'); ?> </p>
                </th>
                <td>
                    <div class="wpf-switch-field" style="padding-top: 40px;">
                        <input type="radio" value="1" name="wpforo_tools_legal[cookies]" id="cookies_1" <?php wpfo_check(WPF()->tools_legal['cookies'], 1); ?>><label for="cookies_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_tools_legal[cookies]" id="cookies_0" <?php wpfo_check(WPF()->tools_legal['cookies'], 0); ?>><label for="cookies_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-bottom:3px solid #43A6DF; padding-top: 30px;">
                    <h3 style="font-weight:600; padding:0px 0px 0px 0px; margin:0px; color:#666666; font-size: 18px;">
                        <?php _e('Forum Rules', 'wpforo') ?>
                    </h3>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 10px;">
                    <label><?php _e('Checkbox: I Accept Forum Rules', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('If this option is enabled, users must accept forum rules by checking the required checkbox on registration form to be able create a forum account. The label text of this checkbox can be managed in Forums > Phrases admin page. Search the label phrase, click on edit button and change it.', 'wpforo'); ?></p>
                </th>
                <td>
                    <div class="wpf-switch-field" style="padding-top: 30px;">
                        <input type="radio" value="1" name="wpforo_tools_legal[rules_checkbox]" id="rules_checkbox_1" <?php wpfo_check(WPF()->tools_legal['rules_checkbox'], 1); ?>><label for="rules_checkbox_1"><?php _e('Enable', 'wpforo'); ?></label> &nbsp;
                        <input type="radio" value="0" name="wpforo_tools_legal[rules_checkbox]" id="rules_checkbox_0" <?php wpfo_check(WPF()->tools_legal['rules_checkbox'], 0); ?>><label for="rules_checkbox_0"><?php _e('Disable', 'wpforo'); ?></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th style="padding-top: 10px;" colspan="2">
                    <label><?php _e('Forum Rules Text', 'wpforo'); ?></label>
                    <p class="wpf-info"><?php _e('This is a basic example of forum rules provided by', 'wpforo'); ?> <a href="https://www.wikihow.com/Sample/Forum-Rules" target="_blank" title="Sample Forum Rules" rel="noreferrer">wikihow.com</a> . <?php _e('You should edit this text and adapt it to your community rules.', 'wpforo'); ?></p>
                    <div style="margin-top: ">
                        <?php
                        $value = WPF()->tools_legal['rules_text'];
                        if(is_null($value)){
                            $file = WPFORO_DIR . '/wpf-admin/html/simple-forum-rules.html';
                            $value = wpforo_get_file_content( $file );
                        }
                        $args = array(
                            'teeny' => false,
                            'media_buttons' => false,
                            'textarea_rows' => '8',
                            'tinymce'       => true,
                            'quicktags'     => array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close' ),
                            'textarea_name' => 'wpforo_tools_legal[rules_text]',
                        );
                        wp_editor( wp_unslash($value), 'wpforo_tools_legal_rules_text', $args ); ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="wpforo_settings_foot" style="clear:both; margin-top:20px;">
        <input type="submit" class="button button-primary" value="<?php _e('Update Options', 'wpforo'); ?>" />
    </div>
</form>
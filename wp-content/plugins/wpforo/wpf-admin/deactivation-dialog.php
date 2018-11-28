<?php
if (!defined('ABSPATH')) {
    exit();
}
?>
<div id="wpf_deactivation_dialog_wrap">
    <div id='wpf_deactivation_dialog'>
        <div id="wpf_deactivation_dialog_header">
            <h2 class="wpf-deactivation-reason-modal-title" style="float: left"><?php _e('Plugin Usage Feedback', 'wpforo'); ?></h2>
            <button id="wpf_deactivation_dialog_close" style="float: right">X</button>
            <hr style="clear: both"/>
        </div>
        <div class="wpf_deactivation_dialog_body">
            <form method="post" action="" class="wpf-deactivation-reason-form">

                <div class="wpf-deactivation-reason-desc">
                    <p class="wpforo-desc">
                        <strong><?php _e('Please let us know why you are deactivating. Choosing one of the options below you will help us make it better for you and for other users.', 'wpforo'); ?></strong>
                    </p>
                </div>
                <div class="wpf-deactivation-reasons">
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="I'll reactivate it later" name="wpforo_deactivation_reason" id="wpf-reactivate_later" class="wpf-deactivation-reason"/>
                        <label for="wpf-reactivate_later"><?php _e('I\'ll reactivate it later', 'wpforo'); ?></label>
                    </div>
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="The plugin is not working" name="wpforo_deactivation_reason" id="wpf-not_working" class="wpf-deactivation-reason"/>
                        <label for="wpf-not_working"><?php _e('The plugin is not working', 'wpforo'); ?></label>
                        <div class="wpf-deactivation-reason-more-info">
                            <textarea class="wpf_dr_more_info" required="required" name="wpforo_deactivation_reason_desc" rows="3" placeholder="<?php _e('What kind of problems do you have?', 'wpforo'); ?>"></textarea>
                            <div class="wpforo_deactivation_feedback">
                                <p class="wpf-info"><?php _e('If you want us to contact you please click on "I agree to receive email" checkbox, then fill out your email. We\'ll try to do our best to help you with problems.', 'wpforo'); ?></p>
                                <div class="wpfdf_left">
                                    <label for="wpf-not_working-deactivation_feedback" class="wpf-info"><?php _e('I agree to receive email', 'wpforo'); ?>
                                        <input id="wpf-not_working-deactivation_feedback" type="checkbox" name="wpforo_deactivation_feedback">
                                    </label>
                                </div>
                                <div class="wpfdf_right">
                                    <input type="email" name="wpforo_deactivation_feedback_email" autocomplete="off" placeholder="<?php _e('email for feedback', 'wpforo'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="It's not what I was looking for" name="wpforo_deactivation_reason" id="wpf-not_what_i_looking_for" class="wpf-deactivation-reason"/>
                        <label for="wpf-not_what_i_looking_for"><?php _e('It\'s not what I was looking for', 'wpforo'); ?></label>
                    </div>
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="I couldn't understand how to make it work" name="wpforo_deactivation_reason" id="wpf-how_to_make_it_work" class="wpf-deactivation-reason"/>
                        <label for="wpf-how_to_make_it_work"><?php _e('I couldn\'t understand how to make it work', 'wpforo'); ?></label>
                        <div class="wpf-deactivation-reason-more-info">
                            <textarea class="wpf_dr_more_info" required="required" name="wpforo_deactivation_reason_desc" rows="3" placeholder="<?php _e('What type of features you want to be in the plugin?', 'wpforo'); ?>"></textarea>
                            <div class="wpforo_deactivation_feedback">
                                <p class="wpf-info"><?php _e('If you want us to contact you please click on "I agree to receive email" checkbox, then fill out your email. We\'ll try to do our best to help you with problems.', 'wpforo'); ?></p>
                                <div class="wpfdf_left">
                                    <label for="wpf-how_to_make_it_work-deactivation_feedback" class="wpf-info"><?php _e('I agree to receive email', 'wpforo'); ?>
                                        <input id="wpf-how_to_make_it_work-deactivation_feedback" type="checkbox" name="wpforo_deactivation_feedback">
                                    </label>
                                </div>
                                <div class="wpfdf_right">
                                    <input type="email" name="wpforo_deactivation_feedback_email" autocomplete="off" placeholder="<?php _e('email for feedback', 'wpforo'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="The plugin is great, but I need specific features" name="wpforo_deactivation_reason" id="wpf-need_specific_features" class="wpf-deactivation-reason"/>
                        <label for="wpf-need_specific_features"><?php _e('The plugin is great, but I need specific features', 'wpforo'); ?></label>
                        <div class="wpf-deactivation-reason-more-info">
                            <textarea class="wpf_dr_more_info" required="required" name="wpforo_deactivation_reason_desc" rows="3" placeholder="<?php _e('What type of features you want to be in the plugin?', 'wpforo'); ?>"></textarea>
                            <div class="wpforo_deactivation_feedback">
                                <p class="wpf-info"><?php _e('If you want us to contact you please click on "I agree to receive email" checkbox, then fill out your email. We\'ll try to do our best to help you with problems.', 'wpforo'); ?></p>
                                <div class="wpfdf_left">
                                    <label for="wpf-need_specific_features-deactivation_feedback" class="wpf-info"><?php _e('I agree to receive email', 'wpforo'); ?>
                                        <input id="wpf-need_specific_features-deactivation_feedback" type="checkbox" name="wpforo_deactivation_feedback">
                                    </label>
                                </div>
                                <div class="wpfdf_right">
                                    <input type="email" name="wpforo_deactivation_feedback_email" autocomplete="off" placeholder="<?php _e('email for feedback', 'wpforo'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="I didn't like plugin design" name="wpforo_deactivation_reason" id="wpf-did_not_like_design" class="wpf-deactivation-reason"/>
                        <label for="wpf-did_not_like_design"><?php _e('I didn\'t like plugin design', 'wpforo'); ?></label>
                        <div class="wpf-deactivation-reason-more-info">
                            <textarea class="wpf_dr_more_info" required="required" name="wpforo_deactivation_reason_desc" rows="3" placeholder="<?php _e('What part of design you don\'t like or want to change?', 'wpforo'); ?>"></textarea>
                            <div class="wpforo_deactivation_feedback">
                                <p class="wpf-info"><?php _e('If you want us to contact you please click on "I agree to receive email" checkbox, then fill out your email. We\'ll try to do our best to help you with problems.', 'wpforo'); ?></p>
                                <div class="wpfdf_left">
                                    <label for="wpf-did_not_like_design-deactivation_feedback" class="wpf-info"><?php _e('I agree to receive email', 'wpforo'); ?>
                                        <input id="wpf-did_not_like_design-deactivation_feedback" type="checkbox" name="wpforo_deactivation_feedback">
                                    </label>
                                </div>
                                <div class="wpfdf_right">
                                    <input type="email" name="wpforo_deactivation_feedback_email" autocomplete="off" placeholder="<?php _e('email for feedback', 'wpforo'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="The plugin works very slow" name="wpforo_deactivation_reason" id="wpf-works_very_slow" class="wpf-deactivation-reason"/>
                        <label for="wpf-works_very_slow"><?php _e('The plugin works very slow', 'wpforo'); ?></label>
                        <div class="wpf-deactivation-reason-more-info">
                            <textarea class="wpf_dr_more_info" required="required" name="wpforo_deactivation_reason_desc" rows="3" placeholder="<?php _e('Could you please describe which features of the plugin slows down your website?', 'wpforo'); ?>"></textarea>
                            <div class="wpforo_deactivation_feedback">
                                <p class="wpf-info"><?php _e('If you want us to contact you please click on "I agree to receive email" checkbox, then fill out your email. We\'ll try to do our best to help you with problems.', 'wpforo'); ?></p>
                                <div class="wpfdf_left">
                                    <label for="wpf-works_very_slow-deactivation_feedback" class="wpf-info"><?php _e('I agree to receive email', 'wpforo'); ?>
                                        <input id="wpf-works_very_slow-deactivation_feedback" type="checkbox" name="wpforo_deactivation_feedback">
                                    </label>
                                </div>
                                <div class="wpfdf_right">
                                    <input type="email" name="wpforo_deactivation_feedback_email" autocomplete="off" placeholder="<?php _e('email for feedback', 'wpforo'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="I found a better plugin" name="wpforo_deactivation_reason" id="wpf-found_better" class="wpf-deactivation-reason"/>
                        <label for="wpf-found_better"><?php _e('I found a better plugin', 'wpforo'); ?></label>
                        <div class="wpf-deactivation-reason-more-info">
                            <textarea class="wpf_dr_more_info" required="required" name="wpforo_deactivation_reason_desc" rows="3" placeholder="<?php _e('Please provide a plugin name or URL', 'wpforo'); ?>"></textarea>
                            <div class="wpforo_deactivation_feedback">
                                <p class="wpf-info"><?php _e('If you want us to contact you please click on "I agree to receive email" checkbox, then fill out your email. We\'ll try to do our best to help you with problems.', 'wpforo'); ?></p>
                                <div class="wpfdf_left">
                                    <label for="wpf-found_better-deactivation_feedback" class="wpf-info"><?php _e('I agree to receive email', 'wpforo'); ?>
                                        <input id="wpf-found_better-deactivation_feedback" type="checkbox" name="wpforo_deactivation_feedback">
                                    </label>
                                </div>
                                <div class="wpfdf_right">
                                    <input type="email" name="wpforo_deactivation_feedback_email" autocomplete="off" placeholder="<?php _e('email for feedback', 'wpforo'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpf-deactivation-reason-item">
                        <input type="radio" value="Other" name="wpforo_deactivation_reason" id="wpf-other" class="wpf-deactivation-reason"/>
                        <label for="wpf-other"><?php _e('Other', 'wpforo'); ?></label>
                        <div class="wpf-deactivation-reason-more-info">
                            <textarea class="wpf_dr_more_info" name="wpforo_deactivation_reason_desc" rows="3" placeholder="<?php _e('Please provide more information', 'wpforo'); ?>"></textarea>
                            <div class="wpforo_deactivation_feedback">
                                <p class="wpf-info"><?php _e('If you want us to contact you please click on "I agree to receive email" checkbox, then fill out your email. We\'ll try to do our best to help you with problems.', 'wpforo'); ?></p>
                                <div class="wpfdf_left">
                                    <label for="wpf-other-deactivation_feedback" class="wpf-info"><?php _e('I agree to receive email', 'wpforo'); ?>
                                        <input id="wpf-other-deactivation_feedback" type="checkbox" name="wpforo_deactivation_feedback">
                                    </label>
                                </div>
                                <div class="wpfdf_right">
                                    <input type="email" name="wpforo_deactivation_feedback_email" autocomplete="off" placeholder="<?php _e('email for feedback', 'wpforo'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpf-deactivation-reasons-actions">
                    <button type="button" class="button button-secondary wpf-dismiss wpf-deactivate"><?php _e('Dismiss and never show again', 'wpforo'); ?></button>
                    <button type="button" class="button button-primary wpf-submit wpf-deactivate"><?php _e('Submit &amp; Deactivate'); ?><i class="fas fa-pulse fa-spinner wpf-loading wpforo-hidden"></i></button>
                </div>
            </form>
            <h2 class="wpforo-thankyou wpforo-hidden"><?php _e('Thank you for your feedback!', 'wpforo'); ?></h2>
        </div>
    </div>
</div>
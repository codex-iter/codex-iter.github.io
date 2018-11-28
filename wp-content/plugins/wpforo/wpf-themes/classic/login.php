<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<p id="wpforo-title"><?php wpforo_phrase('Forum - Login') ?></p>
 
<form name="wpflogin" action="" method="POST">
  <div class="wpforo-login-wrap wpfbg-9">
    <div class="wpforo-login-content">
		<h3><?php wpforo_phrase('Welcome!') ?></h3>
        <div class="wpforo-table wpforo-login-table">
          <div class="wpf-tr row-0">
            <div class="wpf-td wpfw-1 row_0-col_0" style="padding-top:10px;">
              <div class="wpf-field wpf-field-type-text">
                <div class="wpf-field-wrap">
                	<i class="fas fa-user wpf-field-icon"></i>
                    <input autofocus placeholder="<?php wpforo_phrase('Username') ?>" required="TRUE" type="text" name="log" class="wpf-login-text" />
                </div>
                <div class="wpf-field-cl"></div>
              </div>
              <div class="wpf-field wpf-field-type-password">
                <div class="wpf-field-wrap"> 
                	<i class="fas fa-key wpf-field-icon"></i>
                  	<input placeholder="<?php wpforo_phrase('Password') ?>" required="TRUE" type="password" name="pwd" class="wpf-login-text" />
                  	<i class="fas fa-eye-slash wpf-show-password"></i>
                </div>
                <div class="wpf-field-cl"></div>
              </div>
			  <div class="wpf-field wpf-field-type-text wpf-field-hook">
                <div class="wpf-field-wrap">
					<?php do_action('login_form') ?><div class="wpf-field-cl"></div>
                </div>
                <div class="wpf-field-cl"></div>
              </div>
              <div></div>
              <div class="wpf-field">
                <div class="wpf-field-wrap" style="text-align:center; width:100%;">
                    <p class="wpf-extra wpfcl-1">
                    <input type="checkbox" value="1" name="rememberme" id="wpf-login-remember"> 
                    <label for="wpf-login-remember"><?php wpforo_phrase('Remember Me') ?> | </label>
                    <a href="<?php echo wpforo_lostpass_url(); ?>" class="wpf-forgot-pass"><?php wpforo_phrase('Lost your password?') ?></a> 
                    </p>
                    <input type="submit" name="wpforologin" value="<?php wpforo_phrase('Sign In') ?>" />
                </div>
                <div class="wpf-field-cl"></div>
              </div>
              <div class="wpf-field wpf-extra-field-end">
              	<div class="wpf-field-wrap" style="text-align:center; width:100%;">
              		<?php do_action('wpforo_login_form_end') ?>
                    <div class="wpf-field-cl"></div>
                </div>
              </div>
              <div class="wpf-cl"></div>
            </div>
          </div>
        </div>
  	</div>
  </div>
</form>
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
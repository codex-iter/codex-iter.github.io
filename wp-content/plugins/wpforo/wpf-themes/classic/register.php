<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

    $fields = wpforo_register_fields();
?>

<p id="wpforo-title"><?php wpforo_phrase('Forum - Registration') ?></p>

<?php if( wpforo_feature('user-register') ): ?>
    <form name="wpfreg" action="" enctype="multipart/form-data" method="POST">
      <div class="wpforo-register-wrap wpfbg-9">
        <div class="wpforo-register-content">
		<h3><?php wpforo_phrase('Join us today!') ?></h3>
         <div class="wpf-table wpforo-register-table wpfbg-9" style="padding-bottom:0px;">

			  <?php wpforo_fields( $fields ); ?>
              
              <div class="wpf-tr">
              		<div class="wpf-td wpfw-1"><?php do_action('register_form') ?></div>
                    <div class="wpf-cl"></div>
              </div>
              <?php if( wpforo_feature('user-register-email-confirm') ): ?>
                  <div class="wpf-tr">
                        <div class="wpf-td wpfw-1">
                            <i class="fas fa-info-circle wpfcl-5 wpf-reg-info" aria-hidden="true" style="font-size:16px;"></i> &nbsp;<?php wpforo_phrase('After registration you will receive an email confirmation with a link to set a new password') ?>
                        </div>
                  		<div class="wpf-cl"></div>
                  </div>
              <?php endif; ?>
              <div class="wpf-tr">
                    <div class="wpf-td wpfw-1">
                        <div class="wpf-field wpf-field-type-submit" style="text-align:center; width:100%;">
                            <input type="submit" name="wpfororeg" value="<?php wpforo_phrase('Register') ?>" />
                        </div>
                        <div class="wpf-field wpf-extra-field-end">
                            <div class="wpf-field-wrap" style="text-align:center; width:100%;">
                                <?php do_action('wpforo_register_form_end') ?>
                                <div class="wpf-field-cl"></div>
                            </div>
                        </div>
                        <div class="wpf-field-cl"></div>
                    </div>
                	<div class="wpf-cl"></div>
                </div>
                <div class="wpf-cl"></div>
              </div>
        </div>
      </div>
    </form>
<?php else: ?>
<div class="wpforo-register-wrap">
    <div class="wpforo-register-content">
    	<p class="wpf-p-error"><?php wpforo_phrase('User registration is disabled') ?></p>
    </div>
</div>
<?php endif; ?>
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
<div class="wd-opacity wd-<?php echo $wd_options->prefix; ?>-opacity"></div>
<div class="wd-deactivate-popup wd-<?php echo $wd_options->prefix; ?>-deactivate-popup">
	<div class="wd-deactivate-popup-opacity wd-deactivate-popup-opacity-<?php echo $wd_options->prefix; ?>">
		<img src="<?php echo $wd_options->wd_url_img . '/spinner.gif'; ?>" class="wd-img-loader" >
	</div>
	<form method="post" id="<?php echo $wd_options->prefix; ?>_deactivate_form">
		<div class="wd-deactivate-popup-header">
			<?php _e( "Please let us know why you are deactivating. Your answer will help us to provide you support or sometimes offer discounts. (Optional)", $wd_options->prefix ); ?>:
            <span class="wd-deactivate-popup-close-btn"></span>
		</div>

		<div class="wd-deactivate-popup-body">
			<?php foreach( $deactivate_reasons as $deactivate_reason_slug => $deactivate_reason ) { ?>
				<div class="wd-<?php echo $wd_options->prefix; ?>-reasons">
					<input type="radio" value="<?php echo $deactivate_reason["id"];?>" id="<?php echo $wd_options->prefix . "-" .$deactivate_reason["id"]; ?>" name="<?php echo $wd_options->prefix; ?>_reasons" >
					<label for="<?php echo $wd_options->prefix . "-" . $deactivate_reason["id"]; ?>"><?php echo $deactivate_reason["text"];?></label>
				</div>
			<?php } ?>
			<div class="<?php echo $wd_options->prefix; ?>_additional_details_wrap"></div>
		</div>
		<div class="wd-btns">
			<a href="<?php echo $deactivate_url; ?>" data-val="1" class="button button-secondary button-close" id="wd-<?php echo $wd_options->prefix; ?>-deactivate"><?php _e( "Skip and Deactivate" , $wd_options->prefix ); ?></a>
			<a href="<?php echo $deactivate_url; ?>" data-val="2" class="button button-primary button-primary-disabled button-close wd-<?php echo $wd_options->prefix; ?>-deactivate" id="wd-<?php echo $wd_options->prefix; ?>-submit-and-deactivate"><?php _e( "Submit and Deactivate" , $wd_options->prefix ); ?></a>
		</div>
		<input type="hidden" name="<?php echo $wd_options->prefix . "_submit_and_deactivate"; ?>" value="" >
		<?php wp_nonce_field( $wd_options->prefix . '_save_form', $wd_options->prefix . '_save_form_fild'); ?>
	</form>
</div>

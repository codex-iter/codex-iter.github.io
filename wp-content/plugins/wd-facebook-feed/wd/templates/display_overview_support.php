<div id="support">
    <p class="wd-support-main">
        <?php echo sprintf( __("You may contact us by filling in this form or by email %s any time you need professional support or have any questions. You can also fill in the form to leave your comments or feedback.", $wd_options->prefix), "<span class='support_email'>(support@web-dorado.com)</span>") ;?>
    </p>
	<div class="wd-overview-site-deatils">
		<h2><?php _e( "Site Details", $wd_options->prefix ); ?></h2>
		<p>
			<?php _e( "When contacting support, consider copying and pasting this information in your support request.", $wd_options->prefix ); ?>
			<br>
			<?php _e( "It helps us troubleshoot more quickly.", $wd_options->prefix ); ?>
		</p>
		<?php 
			if ( function_exists('current_user_can' ) ) {
				if ( current_user_can('manage_options') ) {
				?>
					<div class="wd-site-deatils wd-table">
						<button id="wd-copy"><?php _e( "Copy to Clipboard", $wd_options->prefix ); ?></button>
						<div id="wd-site-deatils">
							<textarea rows="10" id="wd-site-deatils-textarea"><?php
									_e( "Server Settings", $wd_options->prefix );
									echo '&#13;&#10;&#13;&#10;';
									foreach( $server_info as $key => $val ){ 
										echo $key . ": " . $val . '&#13;&#10;';
									}
									echo '&#13;&#10;';
									_e( "Graphic Library", $wd_options->prefix );
									echo '&#13;&#10;&#13;&#10;';
									foreach( $gd_info as $key => $val ){
										echo $key . ": " . $val . '&#13;&#10;';
									}
                                    echo '&#13;&#10;';
                                    _e("Active Plugins", $wd_options->prefix);
                                    echo '&#13;&#10;';
                                    $activepl = get_option('active_plugins');
                                    $plugins = get_plugins();
                                    $activated_plugins = array();
                                    foreach ( $activepl as $p ) {
                                        if ( isset($plugins[$p]) ) {
                                            array_push($activated_plugins, $plugins[$p]);
                                            echo '&#13;&#10;' . $plugins[$p]['Name'];
                                        }
                                    }
                                    echo '&#13;&#10;&#13;&#10;';
                                    _e("Active theme", $wd_options->prefix);
                                    echo '&#13;&#10;&#13;&#10;';
                                    echo wp_get_theme();
                                ?></textarea>
						</div>	
					</div>
				<?php	
				}
			}
		?>	

	</div>
    <div class="contact_us_wrap">
        <a href="https://web-dorado.com/support/contact-us.html" target="_blank" class="contact_us"><?php _e("Contact us", $wd_options->prefix); ?></a>
    </div>
</div>
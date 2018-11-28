<div id="deals">

    <div id="plugins">
        <div class="deals_header deals_header_plugins">
			<a href="https://web-dorado.com/wordpress-plugins-bundle.html" target="_blank">
				<div class="wd-table">
					<div class="wd-cell wd-text-right wd-valign-middle">
						<img src="<?php echo $wd_options->wd_url_img . '/' . $wd_options->prefix . '_main_plugin.png'; ?>" width="100px">
					</div>
					<div class="wd-cell wd-valign-middle">
						<div class="titles_wrap">
							<div class="deals_main_title"><?php echo sprintf( __( "Get %s", $wd_options->prefix ), $wd_options->plugin_title ); ?></div>
							<div class="deals_main_title"><span class="higlight">+27 </span><?php echo __( "plugins", $wd_options->prefix ); ?></div>
							<div class="deals_secondary_title"><?php echo __( "for", $wd_options->prefix ); ?><span class="higlight"> $99 </span><?php echo __( "only", $wd_options->prefix ); ?></div>
						</div>
						<div class="deals_save"><?php echo __( "Save 80%", $wd_options->prefix ); ?></div>
					</div>            
				</div>
			</a>
        </div>
        <div class="deals_content">
            <?php foreach( $plugins as $wp_slug => &$plugin ){ ?>
            
                <div class="deal_block">
                    <div class="deal_title">
                        <a href="<?php echo $plugin["href"]; ?>" target="_blank" >
                            <img src = "<?php echo $wd_options->wd_url_img . '/plugins/' . $wp_slug . '.png'; ?>">
                            <h2><?php echo $plugin["title"]; ?></h2>
                        </a>
                    </div>
                    <div class="deal_desc">
                        <p><?php echo $plugin["content"]; ?></p>
                        <div class="deal_desc_footer">
                            <div class="download_btn">
                                <a href="<?php echo $plugin["href"]; ?>" target="_blank" ><?php _e( "Download", $wd_options->prefix ); ?></a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            <?php } ?>
        </div>
        <div class="get_all_deals">
            <a href="https://web-dorado.com/wordpress-plugins-bundle.html" target="_blank" >
                <?php _e( "Get all plugins", $wd_options->prefix ); ?>
            </a>
        </div>
    </div>
    <div id="themes">
        <div class="deals_header deals_header_themes">
			<a href="https://web-dorado.com/wordpress-themes-bundle.html" target="_blank">
				<div class="wd-table">
					<div class="wd-cell wd-text-right wd-valign-middle">
					</div>
					<div class="wd-cell wd-valign-middle">
						<div class="titles_wrap">
							<div class="deals_main_title"><?php echo  __( "Get all 11 themes", $wd_options->prefix ); ?></div>
							<div class="deals_main_title"><?php echo __( "for", $wd_options->prefix ); ?><span class="higlight"> $40 </span><?php echo __( "only", $wd_options->prefix ); ?></div>
						</div>
						<div class="deals_save"><?php echo __( "Save 70%", $wd_options->prefix ); ?></div>
					</div>            
				</div>
			</a>
        </div> 
        <div class="deals_content">
            <?php foreach( $themes as $slug => $theme ){ ?>
                <div class="theme_block">
                    <a href="<?php echo $theme["href"]; ?>" target="_blank" >
						<img src = "<?php echo $wd_options->wd_url_img . '/plugins/' . $slug . '.png'; ?>" width="100%;">
                        <div><?php echo $theme["title"]; ?></div>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="get_all_deals">
            <a href="https://web-dorado.com/wordpress-themes-bundle.html" target="_blank" >
                <?php _e( "Find out more", $wd_options->prefix ); ?>
            </a>
        </div>        
    </div>
</div>
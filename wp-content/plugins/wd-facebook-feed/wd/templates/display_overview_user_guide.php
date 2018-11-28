<div id="user_guide">
    <div class="wd-table">
        <div class="wd-cell">
            <?php for( $i = 0; $i < ceil( count($user_guide) / 2 ); $i++) { ?>
                <div class="user_guide_item">
                    <a href="<?php echo $user_guide[$i]["url"]; ?>" class="user_guide_title" target="_blank"><?php echo $user_guide[$i]["main_title"]; ?></a>
                    <?php foreach( $user_guide[$i]["titles"] as $title ) { ?>
                        <div><a href="<?php echo $title["url"]; ?>" target="_blank" class="user_guide_titles"><?php echo $title["title"]; ?></a></div>
                     <?php } ?>
                 </div>    
            <?php } ?>
        </div>
        <div class="wd-cell">
            <?php for( $i = $i; $i < count($user_guide); $i++) { ?>
                <div class="user_guide_item">
                    <a href="<?php echo $user_guide[$i]["url"]; ?>" class="user_guide_title" target="_blank"><?php echo $user_guide[$i]["main_title"]; ?></a>
                    <?php foreach( $user_guide[$i]["titles"] as $title ) { ?>
                        <div><a href="<?php echo $title["url"]; ?>" target="_blank" class="user_guide_titles"><?php echo $title["title"]; ?></a></div>
                     <?php } ?>
                 </div>    
            <?php } ?>        
        </div>
        <div class="wd-cell">
            <?php if($wd_options->plugin_wd_demo_link) { ?>
                <a href="<?php echo $wd_options->plugin_wd_demo_link; ?>" class="user_guide_demo" target="_blank">
                    <?php _e( "Demo", $wd_options->prefix ); ?>
                </a>
            <?php } ?>
            <a href="<?php echo $wd_options->plugin_wd_url; ?>" class="user_guide_plugin" target="_blank">
                <?php echo $wd_options->plugin_title; ?>
            </a>
            <a href="https://wordpress.org/support/plugin/<?php echo $wd_options->plugin_wordpress_slug; ?>" class="user_guide_support_forum" target="_blank">
                <?php _e( "Support Forum", $wd_options->prefix ); ?>
            </a>
            <a href="https://web-dorado.com/support/faq.html" class="user_guide_faq" target="_blank">
                <?php _e( "FAQ", $wd_options->prefix ); ?>
            </a>
            <?php if($wd_options->plugin_wd_addons_link) { ?>           
                <a href="<?php echo $wd_options->plugin_wd_addons_link; ?>" class="user_guide_addons" target="_blank">
                    <?php _e( "Addons", $wd_options->prefix ); ?>
                </a>
            <?php } ?>
        </div>        
    </div>
</div>


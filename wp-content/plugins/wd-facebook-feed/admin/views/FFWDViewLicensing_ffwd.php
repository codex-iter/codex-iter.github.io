<?php

class FFWDViewLicensing_ffwd {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    ?>


    <div id="ffwd_featurs_tables">
      <div id="ffwd_featurs_table1">
        <span>WordPress 3.4+  <?php _e("ready", 'ffwd'); ?></span>

        <span><?php _e("Responsive layout", 'ffwd'); ?></span>
        <span><?php _e("Page feeds", 'ffwd'); ?></span>
        <span><?php _e("Public Group feeds", 'ffwd'); ?></span>
        <span><?php _e("Auto-update for feeds", 'ffwd'); ?></span>
        <span><?php _e("Unlimited Facebook feeds per page/post", 'ffwd'); ?></span>
        <span><?php _e("Lightbox", 'ffwd'); ?></span>
        <span><?php _e("Top Level & Stream type comment display", 'ffwd'); ?></span>
        <span><?php _e("Facebook redirection option", 'ffwd'); ?></span>
        <span><?php _e("Shortcode", 'ffwd'); ?></span>
        <span><?php _e("Facebook feed widget", 'ffwd'); ?></span>


        <span><?php _e("15 lightbox effects", 'ffwd'); ?></span>
        <span><?php _e("Profile Feed", 'ffwd'); ?></span>
        <span><?php _e("Specific content feeds", 'ffwd'); ?></span>
        <span><?php _e("Advanced lightbox", 'ffwd'); ?></span>
        <span><?php _e("Filmstrip for lightbox", 'ffwd'); ?></span>
        <span><?php _e("Themes", 'ffwd'); ?></span>
        <span><?php _e("Multiple layouts", 'ffwd'); ?></span>
        <span><?php _e("Google+, Twitter, Facebook Sharing", 'ffwd'); ?></span>
        <span><?php _e("Support / Updates", 'ffwd'); ?></span>

      </div>
      <div id="ffwd_featurs_table2">
        <span style="padding-top: 18px;height: 39px;"><?php _e("Free", 'ffwd'); ?></span>

        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>

        <span> <?php _e('Only Bug Fixes',"wdi"); ?> </span>
      </div>
      <div id="ffwd_featurs_table3">
        <span><?php _e("Pro Version", 'ffwd'); ?></span>

        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>

        <span> <?php _e('Full Support',"wdi"); ?> </span>
      </div>

      <div class="ffwd_upgrade ffwd-clear">
        <div class="ffwd-right">
          <a href="https://web-dorado.com/files/fromFacebookFeed.php" target="_blank">
            <div class="ffwd-table">
              <div class="ffwd-cell ffwd-cell-valign-middle">
                Upgrade to paid version                    </div>

              <div class="ffwd-cell ffwd-cell-valign-middle">
                <img src="<?php echo WD_FFWD_URL ?>/images/web-dorado.png">
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>


    <div style="clear: both;">
      <p><?php _e("After purchasing the commercial version follow these steps:", 'ffwd'); ?></p>
      <ol>
        <li><?php _e("Deactivate Facebook Feed WD plugin.", 'ffwd'); ?></li>
        <li><?php _e("Delete Facebook Feed WD plugin.", 'ffwd'); ?></li>
        <li><?php _e("Install the downloaded commercial version of the plugin.", 'ffwd'); ?></li>
      </ol>
    </div>
    <?php
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}
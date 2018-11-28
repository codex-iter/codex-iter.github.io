<?php

class FFWDViewUninstall_ffwd {
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
    global $wpdb;
    $prefix = $wpdb->prefix;
    ?>
    <form method="post" action="admin.php?page=uninstall_ffwd" style="width:99%;">
      <?php wp_nonce_field( 'uninstall_ffwd', 'ffwd_nonce' ); ?>
      <div class="wrap">
          <h2></h2>
        <span class="uninstall_icon"></span>
        <h2>Uninstall Facebook Feed WD</h2>
        <p>
          Deactivating Facebook Feed WD plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.
        </p>
        <p style="color: red;">
          <strong>WARNING:</strong>
          Once uninstalled, this can't be undone. You should use a Database Backup plugin of WordPress to back up all the data first.
        </p>
        <p style="color: red">
          <strong>The following Database Tables will be deleted:</strong>
        </p>
        <table class="widefat">
          <thead>
            <tr>
              <th>Database Tables</th>
            </tr>
          </thead>
          <tr>
            <td valign="top">
              <ol>
                  <li><?php echo $prefix; ?>wd_fb_info</li>
                  <li><?php echo $prefix; ?>wd_fb_data</li>
                  <li><?php echo $prefix; ?>wd_fb_option</li>
                  <li><?php echo $prefix; ?>wd_fb_theme</li>
                  <li><?php echo $prefix; ?>wd_fb_shortcode</li>
              </ol>
            </td>
          </tr>
        </table>
        <p style="text-align: center;">
          Do you really want to uninstall Facebook Feed WD?
        </p>
        <p style="text-align: center;">
          <input type="checkbox" name="Facebook Feed WD" id="check_yes" value="yes" />&nbsp;<label for="check_yes">Yes</label>
        </p>
        <p style="text-align: center;">
          <input type="submit" value="UNINSTALL" class="button-primary" onclick="if (check_yes.checked) { 
                                                                                    if (confirm('You are About to Uninstall Facebook Feed WD from WordPress.\nThis Action Is Not Reversible.')) {
                                                                                        spider_set_input_value('task', 'uninstall');
                                                                                    } else {
                                                                                        return false;
                                                                                    }
                                                                                  }
                                                                                  else {
                                                                                    return false;
                                                                                  }" />
        </p>
      </div>
      <input id="task" name="task" type="hidden" value="" />
    </form>
  <?php
  }

  public function uninstall() {
    $flag = TRUE;
    global $wpdb;
    $this->model->delete_db_tables();
    $prefix = $wpdb->prefix;
    $deactivate_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=wd-facebook-feed/facebook-feed-wd.php', 'deactivate-plugin_wd-facebook-feed/facebook-feed-wd.php');
    ?>
    <div id="message" class="updated fade">
      <p>The following Database Tables successfully deleted:</p>
      <p><?php echo $prefix; ?>wd_fb_info,</p>
      <p><?php echo $prefix; ?>wd_fb_data,</p>
      <p><?php echo $prefix; ?>wd_fb_option,</p>
      <p><?php echo $prefix; ?>wd_fb_theme,</p>
      <p><?php echo $prefix; ?>wd_fb_shortcode,</p>
    </div>
    <div class="wrap">
      <h2>Uninstall Facebook Feed WD</h2>
      <p><strong><a href="<?php echo $deactivate_url; ?>" class="ffwd_deactivate_link" data-uninstall="1">Click Here</a> To Finish the Uninstallation and Facebook Feed WD will be Deactivated Automatically.</strong></p>
      <input id="task" name="task" type="hidden" value="" />
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
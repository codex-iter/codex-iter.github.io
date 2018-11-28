<div class="overview_welcome">
  <div class="wd-cell wd-valign-middle">
    <a href="<?php echo $wd_options->plugin_wd_url; ?>" target="_blank"><img
        src="<?php echo $wd_options->wd_url_img . '/' . $wd_options->prefix . '_main_plugin.png'; ?>"></a>
  </div>
  <div class="wd-cell wd-valign-middle">

    <h2><?php echo sprintf(__("Welcome to %s", $wd_options->prefix), $wd_options->plugin_title); ?> <?php if (!empty($start_using_url)) { ?>
        <div class="wd-start-using-button">
          <a href="<?php echo $start_using_url; ?>" class="button button-primary button-large">Start using</a>
        </div>
      <?php } ?></h2>

    <div class="overview_welcome_text">

      <div><?php echo sprintf(__("CONGRATS! You've successfully installed %s WordPress plugin.", $wd_options->prefix), $wd_options->plugin_title); ?></div>
      <div><?php echo $wd_options->description; ?></div>
    </div>
  </div>
</div>
<div class="overview_wrap">
  <ul class="overview_tabs">
    <?php
    foreach ($tabs as $tab_key => $tab) {
      $href = $tab['view'] !== false ? "#" . $tab_key : $tab_key;
      $target = $tab['view'] == false ? 'target="_blank" class="not_tab"' : '';
      $overview_tab_active_class = $tab_key == 'welcome' ? 'class="overview_tab_active"' : ''
      ?>
      <li class=""><a
          href="<?php echo $href; ?>" <?php echo $overview_tab_active_class; ?> <?php echo $target; ?>><?php echo esc_html($tab['name']); ?></a>
      </li>
    <?php } ?>
  </ul>
  <div class="overview_content">
    <?php
    foreach ($tabs as $tab_key => $tab) {
      if ($tab['view'] !== false) {
        echo call_user_func($tab['view']);
      }
    }
    ?>
  </div>
</div>
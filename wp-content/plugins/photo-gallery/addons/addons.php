<?php
function bwg_addons_display() {
  wp_enqueue_style(BWG()->prefix . '_addons');
  $addons = array(
    'photo-gallery-ecomerce'   => array(
      'name'        => __('Photo Gallery Ecommerce', BWG()->prefix),
      'url'         => 'https://10web.io/plugins/wordpress-photo-gallery/',
      'description' => __('Using Photo Gallery Ecommerce you can sell images included in galleries either as digital downloads or products/prints via Paypal or Stripe.', BWG()->prefix),
      'icon'        => '',
      'image'       => BWG()->plugin_url . '/addons/images/ecommerce.png',
    ),  
/*    'photo-gallery-facebook'   => array(
      'name'        => __('Photo Gallery Facebook', BWG()->prefix),
      'url'         => 'https://10web.io/plugins/wordpress-photo-gallery/',
      'description' => __('Photo Gallery Facebook is an add-on, which helps to display Facebook photos and videos within Photo Gallery plugin. You can create Facebook-only galleries, embed individual images and videos or include Facebook albums within mixed type albums.', BWG()->prefix),
      'icon'        => '',
      'image'       => BWG()->plugin_url . '/addons/images/facebook.png',
    ),*/
    'ngitopg'   => array(
      'name'        => __('NextGen Gallery Import to Photo Gallery', BWG()->prefix),
      'url'         => 'https://10web.io/plugins/wordpress-photo-gallery/',
      'description' => __('This addon integrates NextGen with Photo Gallery allowing to import images and related data from NextGen to use with Photo Gallery', BWG()->prefix),
      'icon'        => '',
      'image'       => BWG()->plugin_url . '/addons/images/nextgen_gallery.png',
    ),
    'photo-gallery-export'   => array(
      'name'        => __('Photo Gallery Export / Import', BWG()->prefix),
      'url'         => 'https://10web.io/plugins/wordpress-photo-gallery/',
      'description' => __('Photo Gallery Export/Import helps to move created galleries and gallery groups from one site to another. This way you can save the gallery/album options and manual modifications.', BWG()->prefix),
      'icon'        => '',
      'image'       => BWG()->plugin_url . '/addons/images/import_export.png',
    ),  
  );
  ?>
  <div class="wrap">
    <h1 class="bwg-head-notice">&nbsp;</h1>
    <div id="settings">
      <div id="settings-content" >
        <h2 id="add_on_title"><?php _e('Photo Gallery Add-ons', BWG()->prefix); ?></h2>
        <?php
        if ($addons) {
          foreach ($addons as $name => $addon) {
            ?>
            <div class="add-on">
              <h2><?php echo $addon['name']; ?></h2>
              <figure class="figure">
                <div  class="figure-img">
                  <a href="<?php echo $addon['url']; ?>" target="_blank">
                    <?php
                    if ($addon['image']) {
                      ?>
                      <img src="<?php echo $addon['image']; ?>" />
                      <?php
                    }
                    ?>
                  </a>
                </div>
                <figcaption class="addon-descr figcaption">
                  <?php
                  if ($addon['icon']) {
                    ?>
                    <img src="<?php echo $addon['icon']; ?>" />
                    <?php
                  }
                  ?>
                  <?php echo $addon['description']; ?>
                </figcaption>
              </figure>
              <?php
              if ($addon['url'] !== '#') {
                ?>
              <a href="<?php echo $addon['url']; ?>" target="_blank" class="addon"><span><?php _e('GET THIS ADD ON', BWG()->prefix); ?></span></a>
                <?php
              }
              else {
                ?>
              <div class="ecwd_coming_soon">
                <img src="<?php echo BWG()->plugin_url . '/addons/images/coming_soon.png'; ?>" />
              </div>
                <?php
              }
              ?>
            </div>
            <?php
          }
        }
        ?>
      </div>
    </div>
  </div>
  <?php
}
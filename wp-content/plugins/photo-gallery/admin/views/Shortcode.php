<?php

class ShortcodeView_bwg extends AdminView_bwg {
  public function display( $params = array() ) {
    $from_menu = $params['from_menu'];
    if ( !$from_menu ) {
      BWG()->register_admin_scripts();
      wp_print_scripts('jquery-ui-tooltip');
      wp_print_scripts(BWG()->prefix . '_shortcode');
      wp_print_scripts(BWG()->prefix . '_jscolor');
      wp_print_styles(BWG()->prefix . '_shortcode');
      wp_print_styles(BWG()->prefix . '_tables');
      wp_print_scripts('jquery-ui-tabs');
    }
    else {
      wp_enqueue_script(BWG()->prefix . '_shortcode');
      wp_enqueue_style(BWG()->prefix . '_shortcode');
      wp_enqueue_script(BWG()->prefix . '_jscolor');
      wp_enqueue_script('jquery-ui-tabs');
    }
    require_once BWG()->plugin_dir . '/admin/views/Options.php';
    ob_start();
    echo $this->body($params);
    // Pass the content to form.
    $form_attr = array(
      'id' => BWG()->prefix . '_shortcode_form',
      'name' => BWG()->prefix . '_shortcode_form',
      'class' => BWG()->prefix . '_shortcode_form wd-form wp-core-ui js hidden',
      'action' => '#',
    );
    echo $this->form(ob_get_clean(), $form_attr);
    echo $this->generate_script($params);
    if ( !$from_menu ) {
      wp_die();
    }
  }

  public function body( $params = array() ) {
    $gallery_rows = $params['gallery_rows'];
    $album_rows = $params['album_rows'];
    $theme_rows = $params['theme_rows'];
    $from_menu = $params['from_menu'];
    $tag_rows = $params['tag_rows'];
    $watermark_fonts = $params['watermark_fonts'];
    $gallery_types_name = $params['gallery_types_name'];
    $album_types_name = $params['album_types_name'];
    $shortcodes = $params['shortcodes'];
    ?>
    <input type="hidden" id="tagtext" name="tagtext" value="" />
    <input type="hidden" id="currrent_id" name="currrent_id" value="" />
    <input type="hidden" id="title" name="title" value="" />
    <input type="hidden" id="bwg_insert" name="bwg_insert" value="" />
    <div>
      <div class="bwg_tabs meta-box-sortables">
        <ul class="bwg-tabs">
          <li class="tabs">
            <a href="#bwg_tab_galleries_content" class="bwg-tablink"><?php _e('Gallery', BWG()->prefix); ?></a>
          </li>
          <li class="tabs">
            <a href="#bwg_tab_albums_content" class="bwg-tablink"><?php _e('Gallery group', BWG()->prefix); ?></a>
          </li>
        </ul>
        <div id="bwg_tab_galleries_content" class="bwg-section bwg-no-bottom-border wd-box-content">
          <div class="bwg_change_gallery_type">
            <span class="gallery_type" onClick="bwg_gallery_type('thumbnails')">
              <div><label for="thumbnails"><img id="display_thumb" src="<?php echo BWG()->plugin_url . '/images/thumbnails.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="thumbnails" name="gallery_type" value="thumbnails" /><label class="gallery_type_label" for="thumbnails"><?php echo __('Thumbnails', BWG()->prefix); ?></label>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('thumbnails_masonry')">
              <div>
                <label for="thumbnails_masonry"><img src="<?php echo BWG()->plugin_url . '/images/thumbnails_masonry.jpg'; ?>" /></label>
              </div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="thumbnails_masonry" name="gallery_type" value="thumbnails_masonry" /><label class="gallery_type_label" for="thumbnails_masonry"><?php echo __('Masonry', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('thumbnails_mosaic')">
              <div><label for="thumbnails_mosaic"><img src="<?php echo BWG()->plugin_url . '/images/thumbnails_mosaic.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="thumbnails_mosaic" name="gallery_type" value="thumbnails_mosaic" /><label class="gallery_type_label" for="thumbnails_mosaic"><?php echo __('Mosaic', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('slideshow')">
              <div><label for="slideshow"><img src="<?php echo BWG()->plugin_url . '/images/slideshow.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="slideshow" name="gallery_type" value="slideshow" /><label class="gallery_type_label" for="slideshow"><?php echo __('Slideshow', BWG()->prefix); ?></label>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('image_browser')">
              <div><label for="image_browser"><img src="<?php echo BWG()->plugin_url . '/images/image_browser.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="image_browser" name="gallery_type" value="image_browser" /><label class="gallery_type_label" for="image_browser"><?php echo __('Image Browser', BWG()->prefix); ?></label>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('blog_style')">
              <div><label for="blog_style"><img src="<?php echo BWG()->plugin_url . '/images/blog_style.jpg'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input type="radio" class="gallery_type_radio" id="blog_style" name="gallery_type" value="blog_style" /><label class="gallery_type_label" for="blog_style"><?php echo __('Blog Style', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('carousel')">
              <div><label for="carousel"><img src="<?php echo BWG()->plugin_url . '/images/Carousel.png'; ?>" /></label></div>
              <div class="gallery_type_div">
                <input class="gallery_type_radio" type="radio" id="carousel" name="gallery_type" value="carousel" /><label class="gallery_type_label" for="carousel"><?php echo __('Carousel', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
          </div>
          <div class="bwg_select_gallery_type" style="display:none;">
            <label class="wd-label" for="gallery_types_name"><?php _e('View type', BWG()->prefix); ?></label>
            <select name="gallery_types_name" id="gallery_types_name" onchange="bwg_gallery_type(jQuery(this).val());">
              <?php
              foreach ( $gallery_types_name as $key => $album_type_name ) {
                ?>
                <option <?php echo selected($album_type_name, TRUE); ?> value="<?php echo $key; ?>"><?php echo $album_type_name; ?></option>
                <?php
              }
              ?>
            </select>
          </div>
        </div>
        <div id="bwg_tab_albums_content" class="bwg-section bwg-no-bottom-border wd-box-content">
          <div class="bwg_change_gallery_type">
            <span class="gallery_type" onClick="bwg_gallery_type('album_compact_preview')">
              <div><label for="album_compact_preview"><img src="<?php echo BWG()->plugin_url . '/images/album_compact_preview.jpg'; ?>" /></label></div>
              <div class="album_type_div">
                <input type="radio" class="album_type_radio" id="album_compact_preview" name="gallery_type" value="album_compact_preview" /><label class="album_type_label" for="album_compact_preview"><?php echo __('Compact', BWG()->prefix); ?></label>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('album_masonry_preview')">
              <div><label for="album_masonry_preview"><img src="<?php echo BWG()->plugin_url . '/images/thumbnails_masonry.jpg'; ?>" /></label></div>
              <div class="album_type_div">
                <input type="radio" class="album_type_radio" id="album_masonry_preview" name="gallery_type" value="album_masonry_preview" /><label class="album_type_label" for="album_masonry_preview"><?php echo __('Masonry', BWG()->prefix); ?></label>
                <?php if ( !BWG()->is_pro ) { ?>
                  <span class="pro_btn">Paid</span>
                <?php } ?>
              </div>
            </span>
            <span class="gallery_type" onClick="bwg_gallery_type('album_extended_preview')">
              <div><label for="album_extended_preview"><img src="<?php echo BWG()->plugin_url . '/images/album_extended_preview.jpg'; ?>" /></label></div>
              <div class="album_type_div">
                <input type="radio" class="album_type_radio" id="album_extended_preview" name="gallery_type" value="album_extended_preview" /><label class="album_type_label" for="album_extended_preview"><?php echo __('Extended', BWG()->prefix); ?></label>
              </div>
            </span>
          </div>
          <div class="bwg_select_gallery_type" style="display:none;">
            <label class="wd-label" for="gallery_types_name"><?php _e('View type', BWG()->prefix); ?></label>
            <select name="gallery_types_name" id="gallery_types_name" onchange="bwg_gallery_type(jQuery(this).val());">
              <?php
              foreach ( $album_types_name as $key => $album_type_name ) {
                ?>
                <option <?php echo selected($album_type_name, TRUE); ?> value="<?php echo $key; ?>"><?php echo $album_type_name; ?></option>
                <?php
              }
              ?>
            </select>
          </div>
        </div>
        <?php
        if ( !BWG()->is_pro ) {
          ?>
          <div class="wd-box-content wd-width-100 wd-free-msg">
            <?php
            echo WDWLibrary::message_id(0, __('This view is not available in free version.', BWG()->prefix), 'error');
            ?>
          </div>
          <?php
        }
        ?>
        <div class="bwg-pro-views bwg-section bwg-no-top-border bwg-flex-wrap">
          <div class="wd-box-content wd-width-33">
            <div class="wd-group" id="tr_gallery">
              <label class="wd-label" for="gallery"><?php _e('Gallery', BWG()->prefix); ?></label>
              <div>
                <select name="gallery" id="gallery">
                  <?php
                  foreach ( $gallery_rows as $id => $name ) {
                    ?>
                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <p class="description"><?php _e('Select the gallery to display.', BWG()->prefix) ?></p>
            </div>
            <div class="wd-group" id="tr_album">
              <label class="wd-label" for="album"><?php _e('Gallery Group', BWG()->prefix); ?></label>
              <div>
                <select name="album" id="album">
                  <?php
                  foreach ( $album_rows as $id => $name ) {
                    ?>
                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <p class="description"><?php _e('Select the gallery group to display.', BWG()->prefix); ?></p>
              </div>
            </div>
          </div>
          <div class="wd-box-content wd-width-33" id="tr_tag">
            <div class="wd-group">
              <label class="wd-label" for="tag"><?php _e('Tag', BWG()->prefix); ?></label>
              <div>
                <select name="tag" id="tag">
                  <?php
                  foreach ( $tag_rows as $id => $name ) {
                    ?>
                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <p class="description"><?php _e('Filter gallery images by this tag.', BWG()->prefix) ?></p>
            </div>
          </div>
          <div class="wd-box-content wd-width-33">
            <div class="wd-group" id="tr_theme">
              <label class="wd-label" for="theme"><?php _e('Theme', BWG()->prefix); ?></label>
              <div>
                <select name="theme" id="theme">
                  <?php
                  foreach ( $theme_rows as $id => $name ) {
                    ?>
                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <p class="description"><?php _e('Choose the theme for your gallery.', BWG()->prefix) ?></p>
            </div>
          </div>
          <div class="wd-box-content wd-width-100">
            <div class="wd-group">
              <input id="use_option_defaults" class="wd-radio" value="1" name="use_option_defaults" type="checkbox" checked="checked" />
              <label class="wd-label-radio" for="use_option_defaults"><?php _e('Use default options', BWG()->prefix); ?></label>
              <p class="description"><?php _e('Mark this option to use default settings configured in Photo Gallery Options.', BWG()->prefix) ?><br><?php echo sprintf(__('You can change the default options %s.', BWG()->prefix), '<a id="options_link" data-href="' . admin_url('admin.php?page=options_' . BWG()->prefix) . '" href="' . admin_url('admin.php?page=options_' . BWG()->prefix) . '" target="_blank">' . __('here', BWG()->prefix) . '</a>'); ?></p>
            </div>
          </div>
          <div id="custom_options_conainer" class="wd-box-content wd-width-100">
            <div class="postbox">
              <button class="button-link handlediv" type="button" aria-expanded="true">
                <span class="screen-reader-text"><?php _e('Toggle panel:', BWG()->prefix); ?></span>
                <span class="toggle-indicator" aria-hidden="false"></span>
              </button>
              <h2 class="hndle">
                <span id="bwg_basic_metabox_title" data-title-gallery="<?php _e('Gallery', BWG()->prefix); ?>" data-title-album="<?php _e('Gallery group', BWG()->prefix); ?>"></span>
              </h2>
              <div class="inside bwg-flex-wrap">
                <?php
                OptionsView_bwg::gallery_options(BWG()->options);
                OptionsView_bwg::gallery_group_options(BWG()->options);
                ?>
              </div>
            </div>
            <div class="postbox closed">
              <button class="button-link handlediv" type="button" aria-expanded="true">
                <span class="screen-reader-text"><?php _e('Toggle panel:', BWG()->prefix); ?></span>
                <span class="toggle-indicator" aria-hidden="false"></span>
              </button>
              <h2 class="hndle">
                <span><?php _e('Action on image click', BWG()->prefix); ?></span>
              </h2>
              <div class="inside">
                <?php
                OptionsView_bwg::lightbox_options(BWG()->options);
                ?>
              </div>
            </div>
            <div class="postbox closed">
              <button class="button-link handlediv" type="button" aria-expanded="true">
                <span class="screen-reader-text"><?php _e('Toggle panel', BWG()->prefix); ?></span>
                <span class="toggle-indicator" aria-hidden="false"></span>
              </button>
              <h2 class="hndle">
                <span><?php _e('Advanced', BWG()->prefix); ?></span>
              </h2>
              <div class="inside bwg-flex-wrap">
                <div class="wd-box-content wd-width-100" id="tr_watermark_type">
                  <div class="wd-group">
                    <label class="wd-label"><?php _e('Advertisement type', BWG()->prefix); ?></label>
                    <input type="radio" class="wd-radio" name="watermark_type" id="watermark_type_none" value="none" onClick="bwg_watermark('watermark_type_none')" <?php echo (BWG()->options->watermark_type == 'none') ? 'checked' : ''; ?> /><label for="watermark_type_none" class="wd-radio-label"><?php _e('None', BWG()->prefix); ?></label>
                    <input type="radio" class="wd-radio" name="watermark_type" id="watermark_type_text" value="text" onClick="bwg_watermark('watermark_type_text')" <?php echo (BWG()->options->watermark_type == 'text') ? 'checked' : ''; ?> /><label for="watermark_type_text" class="wd-radio-label"><?php _e('Text', BWG()->prefix); ?></label>
                    <input type="radio" class="wd-radio" name="watermark_type" id="watermark_type_image" value="image" onClick="bwg_watermark('watermark_type_image')" <?php echo (BWG()->options->watermark_type == 'image') ? 'checked' : ''; ?> /><label for="watermark_type_image" class="wd-radio-label"><?php _e('Image', BWG()->prefix); ?></label>
                    <p class="description"><?php _e("Add Text or Image advertisement to your images with this option.", BWG()->prefix); ?></p>
                  </div>
                </div>
                <div class="wd-box-content wd-width-33">
                  <div class="wd-box-content wd-width-100" id="tr_watermark_link">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_link"><?php _e('Advertisement link', BWG()->prefix); ?></label>
                      <input type="text" id="watermark_link" name="watermark_link" value="<?php echo BWG()->options->watermark_link; ?>" />
                      <p class="description"><?php _e("Provide the link to be added to advertisement on images.", BWG()->prefix); ?>, e.g. http://www.example.com</p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_url">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_url"><?php _e('Advertisement URL', BWG()->prefix); ?></label>
                      <input type="text" id="watermark_url" name="watermark_url" value="<?php echo BWG()->options->watermark_url; ?>" />
                      <p class="description"><?php _e("Provide the absolute URL of the image you would like to use as advertisement.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_text">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_text"><?php _e('Advertisement text', BWG()->prefix); ?></label>
                      <input type="text" name="watermark_text" id="watermark_text" value="<?php echo BWG()->options->watermark_text; ?>" />
                      <p class="description"><?php _e("Write the text to add to images as advertisement.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                </div>
                <div class="wd-box-content wd-width-33">
                  <div class="wd-box-content wd-width-100" id="tr_watermark_font">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_font"><?php _e('Advertisement font style', BWG()->prefix); ?></label>
                      <select name="watermark_font" id="watermark_font">
                        <?php
                        $google_fonts = WDWLibrary::get_google_fonts();
                        $is_google_fonts = (in_array(BWG()->options->watermark_font, $google_fonts)) ? TRUE : FALSE;
                        $watermark_font_families = $is_google_fonts ? $google_fonts : $watermark_fonts;
                        foreach ( $watermark_font_families as $key => $watermark_font ) {
                          ?>
                          <option value="<?php echo $watermark_font; ?>" <?php echo (BWG()->options->watermark_font == $watermark_font) ? 'selected="selected"' : ''; ?>><?php echo $watermark_font; ?></option>
                          <?php
                        }
                        ?>
                      </select>
                      <input type="radio" class="wd-radio" name="watermark_google_fonts" id="watermark_google_fonts1" onchange="bwg_change_fonts('watermark_font', jQuery(this).attr('id'))" value="1" <?php if ($is_google_fonts) echo 'checked="checked"'; ?> />
                      <label for="watermark_google_fonts1" id="watermark_google_fonts1_lbl" class="wd-radio-label"><?php _e('Google fonts', BWG()->prefix); ?></label>
                      <input type="radio" class="wd-radio" name="watermark_google_fonts" id="watermark_google_fonts0" onchange="bwg_change_fonts('watermark_font', '')" value="0" <?php if (!$is_google_fonts) echo 'checked="checked"'; ?> />
                      <label for="watermark_google_fonts0" id="watermark_google_fonts0_lbl" class="wd-radio-label"><?php _e('Default', BWG()->prefix); ?></label>
                      <p class="description"><?php _e("Select the font family of the advertisement text.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_font_size">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_font_size"><?php _e('Advertisement font size', BWG()->prefix); ?></label>
                      <div class="bwg-flex">
                        <input type="text" name="watermark_font_size" id="watermark_font_size" value="<?php echo BWG()->options->watermark_font_size; ?>" class="spider_int_input" /><span>px</span>
                      </div>
                      <p class="description"><?php _e("Specify the font size of the advertisement text.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_width_height">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_width"><?php _e('Advertisement dimensions', BWG()->prefix); ?></label>
                      <div class="bwg-flex">
                        <input type="text" name="watermark_width" id="watermark_width" value="<?php echo BWG()->options->watermark_width; ?>" class="spider_int_input" /><span>x</span>
                        <input type="text" name="watermark_height" id="watermark_height" value="<?php echo BWG()->options->watermark_height; ?>" class="spider_int_input" /><span>px</span>
                      </div>
                      <p class="description"><?php _e("Select the dimensions of the advertisement image.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_color">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_color"><?php _e('Advertisement color', BWG()->prefix); ?></label>
                      <input type="text" name="watermark_color" id="watermark_color" value="<?php echo BWG()->options->watermark_color; ?>" class="color" />
                      <p class="description"><?php _e("Choose the color for the advertisement text on images.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                </div>
                <div class="wd-box-content wd-width-33">
                  <div class="wd-box-content wd-width-100" id="tr_watermark_opacity">
                    <div class="wd-group">
                      <label class="wd-label" for="watermark_opacity"><?php _e('Advertisement opacity', BWG()->prefix); ?></label>
                      <div class="bwg-flex">
                        <input type="text" name="watermark_opacity" id="watermark_opacity" value="<?php echo BWG()->options->watermark_opacity; ?>" class="spider_int_input" /><span>%</span>
                      </div>
                      <p class="description"><?php echo __("Specify the opacity of the advertisement. The value must be between 0 to 100.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                  <div class="wd-box-content wd-width-100" id="tr_watermark_position">
                    <div class="wd-group">
                      <label class="wd-label"><?php _e('Advertisement position', BWG()->prefix); ?></label>
                      <table class="bws_position_table">
                        <tbody>
                        <tr>
                          <td><input type="radio" class="wd-radio" value="top-left" id="watermark_top-left" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'top-left') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="top-center" id="watermark_top-center" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'top-center') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="top-right" id="watermark_top-right" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'top-right') ? 'checked' : ''; ?>></td>
                        </tr>
                        <tr>
                          <td><input type="radio" class="wd-radio" value="middle-left" id="watermark_middle-left" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'middle-left') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="middle-center" id="watermark_middle-center" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'middle-center') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="middle-right" id="watermark_middle-right" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'middle-right') ? 'checked' : ''; ?>></td>
                        </tr>
                        <tr>
                          <td><input type="radio" class="wd-radio" value="bottom-left" id="watermark_bottom-left" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'bottom-left') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="bottom-center" id="watermark_bottom-center" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'bottom-center') ? 'checked' : ''; ?>></td>
                          <td><input type="radio" class="wd-radio" value="bottom-right" id="watermark_bottom-right" name="watermark_position" <?php echo (BWG()->options->watermark_position == 'bottom-right') ? 'checked' : ''; ?>></td>
                        </tr>
                        </tbody>
                      </table>
                      <p class="description"><?php echo __("Mark the position where the advertisement should appear on images.", BWG()->prefix); ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      if ( !$from_menu ) {
        ?>
        <div class="media-frame-toolbar">
          <div class="media-toolbar">
            <div class="media-toolbar-primary search-form">
              <button class="button media-button button-primary button-large media-button-insert button-hero" type="button" id="insert" name="insert" onClick="bwg_insert_shortcode('');"><?php _e('Insert into post', BWG()->prefix); ?></button>
            </div>
            <?php if ( !BWG()->is_pro ) { ?>
              <div class="media-toolbar-primary search-form" style="float: left;">
            <span class="media-button button-large">
              <a id="bwg_pro_version_link" class="bwg_link_shortcode" target="_blank" href="https://demo.10web.io/photo-gallery/"><?php _e('Please see ', BWG()->prefix) ?><span id="bwg_pro_version"><?php _e('Thumbnails', BWG()->prefix) ?></span> <?php _e('View in Premium version', BWG()->prefix) ?></a>
            </span>
              </div>
            <?php } ?>
          </div>
        </div>
        <?php
      }
      else {
        $tagtext = '';
        $tagfunction = '';
        if ( isset($_POST['currrent_id']) ) {
          $currrent_id = stripslashes($_POST['currrent_id']);
          $title = ((isset($_POST['title'])) ? stripslashes($_POST['title']) : '');
          $tagtext = '[Best_Wordpress_Gallery id="' . $currrent_id . '"' . $title . ']';
          $tagfunction = "<?php echo photo_gallery(" . $currrent_id . "); ?>";
        }
        ?>
        <hr />
        <div id="generate_button" class="wd-box-content wd-width-100">
          <div class="wd-box-content wd-width-50 bwg-flex">
            <select name="shortcode" id="shortcode" onchange="bwg_update_shortcode()">
              <option value=""><?php _e('New shortcode', BWG()->prefix); ?></option>
              <?php
              foreach ( $shortcodes as $shortcode ) {
                ?>
                <option value="<?php echo $shortcode->id; ?>">[Best_Wordpress_Gallery id="<?php echo $shortcode->id; ?>"]</option>
                <?php
              }
              ?>
            </select>
            <button class="button media-button button-primary button-large media-button-insert" type="button" id="insert" name="insert" onClick="jQuery('#loading_div').show(); bwg_insert_shortcode('');"><?php _e('Generate', BWG()->prefix); ?></button>
          </div>
          <p class="description"><?php _e('If you would like to edit an existing shortcode, use this dropdown box to select it.', BWG()->prefix) ?></p>
          <div class="wd-box-content wd-width-100 bwg-flex-wrap">
            <div class="wd-box-content wd-width-50">
              <div class="wd-group">
                <label class="wd-label" for="bwg_shortcode"><?php _e('Shortcode', BWG()->prefix); ?></label>
                <input type="text" id="bwg_shortcode" name="bwg_shortcode" value='<?php echo $tagtext; ?>' onclick="spider_select_value(this)" readonly="readonly" />
                <p class="description"><?php _e('Add the selected gallery or gallery group to any WordPress page or post. Simply copy the generated shortcode and paste it in the content of page/post editor.', BWG()->prefix) ?></p>
              </div>
            </div>
            <div class="wd-box-content wd-width-50">
              <div class="wd-group">
                <label class="wd-label" for="bwg_function"><?php _e('PHP function', BWG()->prefix); ?></label>
                <input type="text" id="bwg_function" name="bwg_function" value="<?php echo $tagfunction; ?>" onclick="spider_select_value(this)" readonly="readonly" />
                <p class="description"><?php _e('Use generated PHP function to call the selected gallery or gallery group on a custom PHP template.', BWG()->prefix) ?></p>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
    <div id="loading_div" <?php echo ( $from_menu ) ? 'class="bwg_show"' : ''; ?>></div>
    <?php
  }

  public function generate_script( $params = array() ) {
    $from_menu = $params['from_menu'];
    $shortcodes = $params['shortcodes'];
    $shortcode_max_id = $params['shortcode_max_id'];
    ob_start();
    ?>
    <script type="text/javascript">
      var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
      var shortcodes = [];
      var shortcode_id = 1;
      var temp_shortcode_id = 0;
      <?php
      foreach ($shortcodes as $shortcode) {
      ?>
      shortcodes[<?php echo $shortcode->id; ?>] = '<?php echo addslashes($shortcode->tagtext); ?>';
      <?php
      }
      ?>
      shortcode_id = <?php echo $shortcode_max_id + 1; ?>;
      var params = get_params("Best_Wordpress_Gallery");
      var bwg_insert = 1;
      <?php
      if ($params['gutenberg_callback']) {
      if ($params['gutenberg_id'] == 0) {
      ?>
      var content = '';
      <?php
      }
      else {
      ?>
      var content = '[Best_Wordpress_Gallery id="<?php echo $params['gutenberg_id']; ?>"]';
      <?php
      }
      }
      elseif (!$from_menu) { ?>
      var content;
      if (top.tinyMCE.activeEditor && !top.tinyMCE.activeEditor.hidden && top.tinyMCE.activeEditor.selection) {
        content = top.tinyMCE.activeEditor.selection.getContent();
      }
      else {
        content = bwg_get_textarea_selection(top.wpActiveEditor);
      }
      <?php } else { ?>
      var content = jQuery("#bwg_shortcode").val();
      <?php } ?>
      function bwg_update_shortcode() {
        params = get_params("Best_Wordpress_Gallery");
        if (!params) { // Insert.
          <?php if ($from_menu) { ?>
          jQuery('#insert').text('<?php _e('Generate', BWG()->prefix); ?>');
          bwg_insert = 1;
          if (temp_shortcode_id !== 0) {
            shortcode_id = temp_shortcode_id;
          }
          <?php
          }
          ?>
          jQuery("#use_option_defaults").prop('checked', true).trigger('change');
          jQuery("#bwg_shortcode").val('');
          jQuery("#bwg_function").val('');
          jQuery(".bwg_tabs").tabs({active: 0});
          bwg_gallery_type('thumbnails');
        }
        else { // Update.
          if (params['id']) {
            shortcode_id = params['id'];
            if (typeof shortcodes[shortcode_id] === 'undefined') {
              alert("<?php echo addslashes(__('There is no shortcode with such ID!', BWG()->prefix)); ?>");
              bwg_gallery_type('thumbnails');
              return 0;
            }
            var short_code = get_short_params(shortcodes[shortcode_id]);
            bwg_insert = 0;
            jQuery("#bwg_shortcode").val('[Best_Wordpress_Gallery id="' + shortcode_id + '"]');
            var str = "&#60;?php echo photo_gallery(" + shortcode_id + "); ?&#62;";
            jQuery("#bwg_function").val(str.replace("&#60;", '<').replace("&#62;", '>'));
          }
          else {
            var short_code = get_params("Best_Wordpress_Gallery");
          }
          jQuery('#insert').text('<?php _e('Update', BWG()->prefix); ?>');
          <?php if ($from_menu) { ?>
          content = jQuery("#bwg_shortcode").val();
          <?php } ?>
          jQuery('#insert').attr('onclick', "jQuery('#loading_div').show(); bwg_insert_shortcode(content);");
          jQuery("select[id=theme] option[value='" + short_code['theme_id'] + "']").attr('selected', 'selected');
          jQuery("select[id=gallery_types_name] option[value='" + short_code['gallery_type'] + "']").attr('selected', 'selected');
          jQuery("#use_option_defaults").prop('checked', true).trigger('change');
          if (short_code['type'] == 'album' || short_code['gallery_type'] == 'album_compact_preview' || short_code['gallery_type'] == 'album_masonry_preview' || short_code['gallery_type'] == 'album_extended_preview') {
            short_code['type'] = 'album';
            jQuery(".bwg_tabs").tabs({active: 1});
          }
          else {
            short_code['type'] = 'gallery';
            jQuery(".bwg_tabs").tabs({active: 0});
          }
          jQuery("select[id=gallery] option[value='" + short_code['gallery_id'] + "']").attr('selected', 'selected');
          jQuery("select[id=album] option[value='" + short_code['album_id'] + "']").attr('selected', 'selected');
          jQuery("select[id=tag] option[value='" + short_code['tag'] + "']").attr('selected', 'selected');
          bwg_gallery_type(short_code['gallery_type']);
          if (short_code['use_option_defaults'] == 1) {
            return false;
          }
          jQuery("#use_option_defaults").prop('checked', false).trigger('change');
          switch (short_code['gallery_type']) {
            case 'thumbnails': {
              jQuery("#thumb_width").val(short_code['thumb_width']);
              jQuery("#thumb_height").val(short_code['thumb_height']);
              jQuery("#image_column_number").val(short_code['image_column_number']);
              if (short_code['image_enable_page'] == 1) {
                jQuery("#image_enable_page_1").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 0) {
                jQuery("#image_enable_page_0").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 2) {
                jQuery("#image_enable_page_2").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 3) {
                jQuery("#image_enable_page_3").attr('checked', 'checked');
              }
              jQuery("#images_per_page").val(short_code['images_per_page']);
              jQuery("#load_more_image_count").val(short_code['load_more_image_count']);
              jQuery("select[id=sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['image_title'] == 'hover') {
                jQuery("#image_title_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['image_title'] == 'show') {
                jQuery("#image_title_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#image_title_show_hover_2").attr('checked', 'checked');
              }
              if (short_code['play_icon'] == 1) {
                jQuery("#play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['ecommerce_icon'] == 'show') {
                jQuery("#ecommerce_icon_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'thumbnails_masonry': {
              if (short_code['masonry_hor_ver'] == 'horizontal') {
                jQuery("#masonry_0").prop('checked', false).removeAttr('checked');
                jQuery("#masonry_1").attr('checked', 'checked');
                jQuery("#masonry_thumb_size").val(short_code['thumb_height']);
                jQuery('.masonry_col_num').hide();
                jQuery('.masonry_row_num').show();
              }
              else {
                jQuery("#masonry_0").attr('checked', 'checked');
                jQuery("#masonry_thumb_size").val(short_code['thumb_width']);
                jQuery('.masonry_row_num').hide();
                jQuery('.masonry_col_num').show();
              }
                if (short_code['image_title'] == 'hover') {
                  jQuery("#masonry_image_title_0").attr('checked', 'checked');
                }
                else if (short_code['image_title'] == 'show') {
                  jQuery("#masonry_image_title_1").attr('checked', 'checked');
                }
                else {
                  jQuery("#masonry_image_title_2").attr('checked', 'checked');
                }
                if (short_code['show_masonry_thumb_description'] == 1) {
                  jQuery("#masonry_thumb_desc_1").attr('checked', 'checked');
                }
                else {
                  jQuery("#masonry_thumb_desc_0").attr('checked', 'checked');
                }
              jQuery("#masonry_image_column_number").val(short_code['image_column_number']);
              if (short_code['image_enable_page'] == 1) {
                jQuery("#masonry_image_enable_page_1").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 0) {
                jQuery("#masonry_image_enable_page_0").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 2) {
                jQuery("#masonry_image_enable_page_2").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 3) {
                jQuery("#masonry_image_enable_page_3").attr('checked', 'checked');
              }
              jQuery("#masonry_images_per_page").val(short_code['images_per_page']);
              jQuery("#masonry_load_more_image_count").val(short_code['load_more_image_count']);
              jQuery("select[id=masonry_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#masonry_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#masonry_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#masonry_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#masonry_search_box_width").val(short_code['search_box_width']);
              }
              else if (short_code['image_enable_page'] == 2) {
                jQuery("#masonry_image_page_loadmore").attr('checked', 'checked');
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#masonry_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#masonry_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#masonry_thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#masonry_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['play_icon'] == 1) {
                jQuery("#masonry_play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#masonry_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#masonry_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else {
                jQuery("#masonry_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'thumbnails_mosaic': {
              if (short_code['mosaic_hor_ver'] == 'horizontal') {
                jQuery("#mosaic_1").attr('checked', 'checked');
                jQuery("#mosaic_thumb_size").val(short_code['thumb_height']);
              }
              else {
                jQuery("#mosaic_0").attr('checked', 'checked');
                jQuery("#mosaic_thumb_size").val(short_code['thumb_width']);
              }
              if (short_code['resizable_mosaic'] == 1) {
                jQuery("#resizable_mosaic_1").attr('checked', 'checked');
              }
              else {
                jQuery("#resizable_mosaic_0").attr('checked', 'checked');
              }
              jQuery("#mosaic_total_width").val(short_code['mosaic_total_width']);
              if (short_code['image_enable_page'] == 1) {
                jQuery("#mosaic_image_enable_page_1").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 0) {
                jQuery("#mosaic_image_enable_page_0").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 2) {
                jQuery("#mosaic_image_enable_page_2").attr('checked', 'checked');
              }
              else if (short_code['image_enable_page'] == 3) {
                jQuery("#mosaic_image_enable_page_3").attr('checked', 'checked');
              }
              jQuery("#mosaic_images_per_page").val(short_code['images_per_page']);
              jQuery("#mosaic_load_more_image_count").val(short_code['load_more_image_count']);
              jQuery("select[id=mosaic_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#mosaic_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#mosaic_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#mosaic_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#mosaic_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#mosaic_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#mosaic_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#mosaic_thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#mosaic_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['image_title'] == 'hover') {
                jQuery("#mosaic_image_title_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['image_title'] == 'show') {
                jQuery("#mosaic_image_title_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_image_title_show_hover_2").attr('checked', 'checked');
              }
              if (short_code['play_icon'] == 1) {
                jQuery("#mosaic_play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#mosaic_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#mosaic_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else {
                jQuery("#mosaic_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'slideshow': {
              jQuery("select[id=slideshow_type] option[value='" + short_code['slideshow_effect'] + "']").attr('selected', 'selected');
              jQuery("#slideshow_interval").val(short_code['slideshow_interval']);
              jQuery("#slideshow_width").val(short_code['slideshow_width']);
              jQuery("#slideshow_height").val(short_code['slideshow_height']);
              jQuery("select[id=slideshow_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#slideshow_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_order_by_0").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_autoplay'] == 1) {
                jQuery("#slideshow_enable_autoplay_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_autoplay_no").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_shuffle'] == 1) {
                jQuery("#slideshow_enable_shuffle_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_shuffle_no").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_ctrl'] == 1) {
                jQuery("#slideshow_enable_ctrl_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_ctrl_no").attr('checked', 'checked');
              }
              if (short_code['autohide_slideshow_navigation'] == 1) {
                jQuery("#autohide_slideshow_navigation_1").attr('checked', 'checked');
              }
              else {
                jQuery("#autohide_slideshow_navigation_0").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_filmstrip'] == 1) {
                jQuery("#slideshow_enable_filmstrip_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_filmstrip_no").attr('checked', 'checked');
              }
              if (short_code['slideshow_filmstrip_height']) {
                jQuery( "#slideshow_filmstrip_height" ).val( short_code['slideshow_filmstrip_height'] );
              }
              if (short_code['slideshow_enable_title'] == 1) {
                jQuery("#slideshow_enable_title_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_title_no").attr('checked', 'checked');
              }
              if (short_code['slideshow_title_position']) {
                jQuery( "input[name=slideshow_title_position][value=" + short_code['slideshow_title_position'] + "]" ).attr( 'checked', 'checked' );
              }
              if (short_code['slideshow_title_full_width']) {
                jQuery( "#slideshow_title_full_width_" + short_code['slideshow_title_full_width'] ).attr( 'checked', 'checked' );
              }
              if (short_code['slideshow_enable_description'] == 1) {
                jQuery("#slideshow_enable_description_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_description_no").attr('checked', 'checked');
              }
              if (short_code['slideshow_description_position']) {
                jQuery("input[name=slideshow_description_position][value=" + short_code['slideshow_description_position'] + "]").attr('checked', 'checked');
              }
              if (short_code['enable_slideshow_music'] == 1) {
                jQuery("#slideshow_enable_music_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_enable_music_no").attr('checked', 'checked');
              }
              if (short_code['slideshow_music_url']) {
                jQuery("#slideshow_audio_url").val(short_code['slideshow_music_url']);
              }
              jQuery("#slideshow_effect_duration").val(short_code['slideshow_effect_duration']);
              if (short_code['gallery_download'] == 1) {
                jQuery("#slideshow_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#slideshow_gallery_download_0").attr('checked', 'checked');
              }
              break;
            }
            case 'image_browser': {
              jQuery("#image_browser_width").val(short_code['image_browser_width']);
              if (short_code['image_browser_title_enable']) {
                jQuery("#image_browser_title_enable_" + short_code['image_browser_title_enable']).attr('checked', 'checked');
              }
              if (short_code['image_browser_description_enable']) {
                jQuery("#image_browser_description_enable_" + short_code['image_browser_description_enable']).attr('checked', 'checked');
              }
              jQuery("select[id=image_browser_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#image_browser_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#image_browser_order_by_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#image_browser_thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#image_browser_thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description']) {
                jQuery("#image_browser_show_gallery_description_" + short_code['show_gallery_description']).attr('checked', 'checked');
              }
              if (short_code['show_search_box']) {
                jQuery("#image_browser_show_search_box_" + short_code['show_search_box']).attr('checked', 'checked');
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#image_browser_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#image_browser_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#image_browser_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#image_browser_show_tag_box_0").attr('checked', 'checked');
              }

              if (short_code['placeholder']) {
                jQuery("#image_browser_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#image_browser_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#image_browser_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#image_browser_gallery_download_0").attr('checked', 'checked');
              }
              break;
            }
            case 'blog_style': {
              jQuery("#blog_style_width").val(short_code['blog_style_width']);
              if (short_code['blog_style_title_enable'] == 1) {
                jQuery("#blog_style_title_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_title_enable_0").attr('checked', 'checked');
              }
              jQuery("#blog_style_images_per_page").val(short_code['blog_style_images_per_page']);
              jQuery("#blog_style_load_more_image_count").val(short_code['blog_style_load_more_image_count']);
              if (short_code['blog_style_enable_page'] == 1) {
                jQuery("#blog_style_enable_page_1").attr('checked', 'checked');
              }
              else if (short_code['blog_style_enable_page'] == 0) {
                jQuery("#blog_style_enable_page_0").attr('checked', 'checked');
              }
              else if (short_code['blog_style_enable_page'] == 2) {
                jQuery("#blog_style_enable_page_2").attr('checked', 'checked');
              }
              else if (short_code['blog_style_enable_page'] == 3) {
                jQuery("#blog_style_enable_page_3").attr('checked', 'checked');
              }
              if (short_code['blog_style_description_enable'] == 1) {
                jQuery("#blog_style_description_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_description_enable_0").attr('checked', 'checked');
              }
              jQuery("select[id=blog_style_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#blog_style_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_order_by_0").attr('checked', 'checked');
              }
              if (short_code['showthumbs_name'] == 1) {
                jQuery("#blog_style_thumb_name_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_thumb_name_no").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#blog_style_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#blog_style_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#blog_style_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#blog_style_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#blog_style_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#blog_style_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#blog_style_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#blog_style_gallery_download_0").attr('checked', 'checked');
              }
              break;
            }
            case 'carousel': {
              jQuery("#carousel_interval").val(short_code['carousel_interval']);
              jQuery("#carousel_width").val(short_code['carousel_width']);
              jQuery("#carousel_height").val(short_code['carousel_height']);
              jQuery("#carousel_image_column_number").val(short_code['carousel_image_column_number']);
              jQuery("#carousel_image_par").val(short_code['carousel_image_par']);
              if (short_code['enable_carousel_title'] == 1) {
                jQuery("#carousel_enable_title_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_enable_title_no").attr('checked', 'checked');
              }
              if (short_code['enable_carousel_autoplay'] == 1) {
                jQuery("#carousel_enable_autoplay_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_enable_autoplay_no").attr('checked', 'checked');
              }
              jQuery("#carousel_r_width").val(short_code['carousel_r_width']);
              if (short_code['carousel_fit_containerWidth'] == 1) {
                jQuery("#carousel_fit_containerWidth_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_fit_containerWidth_no").attr('checked', 'checked');
              }
              if (short_code['carousel_prev_next_butt'] == 1) {
                jQuery("#carousel_prev_next_butt_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_prev_next_butt_no").attr('checked', 'checked');
              }
              if (short_code['carousel_play_pause_butt'] == 1) {
                jQuery("#carousel_play_pause_butt_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_play_pause_butt_no").attr('checked', 'checked');
              }
              jQuery("select[id=carousel_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#carousel_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_order_by_0").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#carousel_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#carousel_gallery_download_0").attr('checked', 'checked');
              }
              break;
            }
            case 'album_compact_preview': {
              jQuery("#album_column_number").val(short_code['compuct_album_column_number']);
              jQuery("#album_thumb_width").val(short_code['compuct_album_thumb_width']);
              jQuery("#album_thumb_height").val(short_code['compuct_album_thumb_height']);
              jQuery("#album_image_column_number").val(short_code['compuct_album_image_column_number']);
              jQuery("#album_image_thumb_width").val(short_code['compuct_album_image_thumb_width']);
              jQuery("#album_image_thumb_height").val(short_code['compuct_album_image_thumb_height']);
              if (short_code['compuct_album_enable_page']) {
                jQuery("#album_enable_page_" + short_code['compuct_album_enable_page']).attr('checked', 'checked');
              }
              jQuery("#albums_per_page").val(short_code['compuct_albums_per_page']);
              jQuery("#album_images_per_page").val(short_code['compuct_album_images_per_page']);
              jQuery("select[id=album_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#album_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#album_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#album_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#album_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#album_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#album_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['show_album_name'] == 1) {
                jQuery("#show_album_name_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_album_name_enable_0").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#album_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_show_gallery_description_0").attr('checked', 'checked');
              }
              jQuery("input[name=album_title_show_hover][value=" + short_code['compuct_album_title'] + "]").attr('checked', 'checked');
              if (short_code['compuct_album_view_type'] == 'thumbnail') {
                jQuery("#album_view_type_1").attr('checked', 'checked');
                jQuery("#for_album_image_title_show_hover_0").show();
                jQuery("#for_album_ecommerce_icon_show_hover_0").show();
              }
              else if (short_code['compuct_album_view_type'] == 'masonry') {
                jQuery("#album_view_type_0").attr('checked', 'checked');
                jQuery("#for_album_image_title_show_hover_0").show();
                jQuery("#for_album_ecommerce_icon_show_hover_0").show();
              }
              else {
                jQuery("#album_view_type_2").attr('checked', 'checked');
                jQuery("#for_album_image_title_show_hover_0").hide();
                jQuery("#for_album_ecommerce_icon_show_hover_0").hide();
              }
              jQuery("input[name='album_image_title_show_hover'][value='" + short_code['compuct_album_image_title'] + "']").attr('checked', 'checked');
              if (short_code['compuct_album_mosaic_hor_ver'] == "vertical") {
                jQuery("#album_mosaic_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_mosaic_1").attr('checked', 'checked');
              }
              if (short_code['compuct_album_resizable_mosaic'] == 1) {
                jQuery("#album_resizable_mosaic_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_resizable_mosaic_0").attr('checked', 'checked');
              }
              jQuery("#album_mosaic_total_width").val(short_code['compuct_album_mosaic_total_width']);
              if (short_code['play_icon'] == 1) {
                jQuery("#album_play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#album_play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#album_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#album_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['ecommerce_icon'] == 'show') {
                jQuery("#album_ecommerce_icon_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'album_masonry_preview': {
              jQuery("#album_masonry_column_number").val(short_code['masonry_album_column_number']);
              jQuery("#album_masonry_thumb_width").val(short_code['masonry_album_thumb_width']);
              jQuery("#album_masonry_image_column_number").val(short_code['masonry_album_image_column_number']);
              jQuery("#album_masonry_image_thumb_width").val(short_code['masonry_album_image_thumb_width']);
              if (short_code['masonry_album_enable_page']) {
                jQuery("#album_masonry_enable_page_" + short_code['masonry_album_enable_page']).attr('checked', 'checked');
              }
              jQuery("#albums_masonry_per_page").val(short_code['masonry_albums_per_page']);
              jQuery("#album_masonry_images_per_page").val(short_code['masonry_album_images_per_page']);
              jQuery("select[id=album_masonry_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#album_masonry_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#album_masonry_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#album_masonry_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#album_masonry_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#album_masonry_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#album_masonry_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['show_album_name'] == 1) {
                jQuery("#show_album_masonry_name_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_album_masonry_name_enable_0").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#album_masonry_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_show_gallery_description_0").attr('checked', 'checked');
              }
              jQuery("input[name='album_masonry_image_title'][value='" + short_code['image_title'] + "']").attr('checked', 'checked');
              if (short_code['gallery_download'] == 1) {
                jQuery("#album_masonry_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#album_masonry_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_masonry_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
            case 'album_extended_preview': {
              jQuery("#extended_album_height").val(short_code['extended_album_height']);
              jQuery("#album_extended_thumb_width").val(short_code['extended_album_thumb_width']);
              jQuery("#album_extended_thumb_height").val(short_code['extended_album_thumb_height']);
              jQuery("#album_extended_image_column_number").val(short_code['extended_album_image_column_number']);
              jQuery("#album_extended_image_thumb_width").val(short_code['extended_album_image_thumb_width']);
              jQuery("#album_extended_image_thumb_height").val(short_code['extended_album_image_thumb_height']);
              if (short_code['extended_album_enable_page']) {
                jQuery("#album_extended_enable_page_" + short_code['extended_album_enable_page']).attr('checked', 'checked');
              }
              jQuery("#albums_extended_per_page").val(short_code['extended_albums_per_page']);
              jQuery("#album_extended_images_per_page").val(short_code['extended_album_images_per_page']);
              jQuery("select[id=album_extended_sort_by] option[value='" + short_code['sort_by'] + "']").attr('selected', 'selected');
              if (short_code['order_by'] == 'asc') {
                jQuery("#album_extended_order_by_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_order_by_0").attr('checked', 'checked');
              }
              if (short_code['show_search_box'] == 1) {
                jQuery("#album_extended_show_search_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_show_search_box_0").attr('checked', 'checked');
              }
              if (short_code['placeholder']) {
                jQuery("#album_extended_placeholder").val(short_code['placeholder']);
              }
              if (short_code['search_box_width']) {
                jQuery("#album_extended_search_box_width").val(short_code['search_box_width']);
              }
              if (short_code['show_sort_images'] == 1) {
                jQuery("#album_extended_show_sort_images_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_show_sort_images_0").attr('checked', 'checked');
              }
              if (short_code['show_tag_box'] == 1) {
                jQuery("#album_extended_show_tag_box_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_show_tag_box_0").attr('checked', 'checked');
              }
              if (short_code['show_album_name'] == 1) {
                jQuery("#show_album_extended_name_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_album_extended_name_enable_0").attr('checked', 'checked');
              }
              if (short_code['extended_album_description_enable'] == 1) {
                jQuery("#extended_album_description_enable_1").attr('checked', 'checked');
              }
              else {
                jQuery("#extended_album_description_enable_0").attr('checked', 'checked');
              }
              if (short_code['show_gallery_description'] == 1) {
                jQuery("#album_extended_show_gallery_description_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_show_gallery_description_0").attr('checked', 'checked');
              }
              if (short_code['extended_album_view_type'] == 'thumbnail') {
                jQuery("#album_extended_view_type_1").attr('checked', 'checked');
                jQuery("#for_album_extended_image_title_show_hover_0").show();
                jQuery("#for_album_extended_ecommerce_icon_show_hover_0").show();
              }
              else if (short_code['extended_album_view_type'] == 'masonry') {
                jQuery("#album_extended_view_type_0").attr('checked', 'checked');
                jQuery("#for_album_extended_image_title_show_hover_0").show();
                jQuery("#for_album_extended_ecommerce_icon_show_hover_0").show();
              }
              else {
                jQuery("#album_extended_view_type_2").attr('checked', 'checked');
                jQuery("#for_album_extended_image_title_show_hover_0").hide();
                jQuery("#for_album_extended_ecommerce_icon_show_hover_0").hide();
              }
              jQuery("input[name='album_extended_image_title_show_hover'][value='" + short_code['extended_album_image_title'] + "']").attr('checked', 'checked');
              if (short_code['extended_album_mosaic_hor_ver'] == "vertical") {
                jQuery("#album_extended_mosaic_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_mosaic_1").attr('checked', 'checked');
              }
              if (short_code['extended_album_resizable_mosaic'] == 1) {
                jQuery("#album_extended_resizable_mosaic_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_resizable_mosaic_0").attr('checked', 'checked');
              }
              jQuery("#album_extended_mosaic_total_width").val(short_code['extended_album_mosaic_total_width']);
              if (short_code['play_icon'] == 1) {
                jQuery("#album_extended_play_icon_yes").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_play_icon_no").attr('checked', 'checked');
              }
              if (short_code['gallery_download'] == 1) {
                jQuery("#album_extended_gallery_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_gallery_download_0").attr('checked', 'checked');
              }
              if (short_code['ecommerce_icon'] == 'hover') {
                jQuery("#album_extended_ecommerce_icon_show_hover_1").attr('checked', 'checked');
              }
              else if (short_code['ecommerce_icon'] == 'show') {
                jQuery("#album_extended_ecommerce_icon_show_hover_0").attr('checked', 'checked');
              }
              else {
                jQuery("#album_extended_ecommerce_icon_show_hover_2").attr('checked', 'checked');
              }
              break;
            }
          }
          // Lightbox.
          if (!short_code['thumb_click_action'] || short_code['thumb_click_action'] == 'undefined' || short_code['thumb_click_action'] == 'do_nothing') {
            jQuery("#thumb_click_action_3").attr('checked', 'checked');
          }
          else if (short_code['thumb_click_action'] == 'redirect_to_url') {
            jQuery("#thumb_click_action_2").attr('checked', 'checked');
          }
          else if (short_code['thumb_click_action'] == 'open_lightbox') {
            jQuery("#thumb_click_action_1").attr('checked', 'checked');
          }
          if (short_code['thumb_link_target'] == 1 || !short_code['thumb_link_target'] || short_code['thumb_link_target'] == 'undefined') {
            jQuery("#thumb_link_target_yes").attr('checked', 'checked');
          }
          else {
            jQuery("#thumb_link_target_no").attr('checked', 'checked');
          }
          if (short_code['popup_fullscreen'] != undefined) {
            if (short_code['popup_fullscreen'] == 1) {
              jQuery("#popup_fullscreen_1").attr('checked', 'checked');
            }
            else {
              jQuery("#popup_fullscreen_0").attr('checked', 'checked');
            }
          }
          if (short_code['popup_width'] != undefined) {
            jQuery("#popup_width").val(short_code['popup_width']);
          }
          if (short_code['popup_height'] != undefined) {
            jQuery("#popup_height").val(short_code['popup_height']);
          }
          if (short_code['popup_effect'] != undefined) {
            jQuery("select[id=popup_type] option[value='" + short_code['popup_effect'] + "']").attr('selected', 'selected');
          }
          if (short_code['popup_effect_duration'] != undefined) {
            jQuery("#popup_effect_duration").val(short_code['popup_effect_duration']);
          }
          if (short_code['popup_autoplay'] != undefined) {
            if (short_code['popup_autoplay'] == 1) {
              jQuery("#popup_autoplay_1").attr('checked', 'checked');
            }
            else {
              jQuery("#popup_autoplay_0").attr('checked', 'checked');
            }
          }
          if (short_code['popup_interval'] != undefined) {
            jQuery("#popup_interval").val(short_code['popup_interval']);
          }
          if (short_code['popup_enable_filmstrip'] != undefined) {
            if (short_code['popup_enable_filmstrip'] == 1) {
              jQuery("#popup_enable_filmstrip_1").attr('checked', 'checked');
            }
            else {
              jQuery("#popup_enable_filmstrip_0").attr('checked', 'checked');
            }
            jQuery("#popup_filmstrip_height").val(short_code['popup_filmstrip_height']);
          }
          if (short_code['popup_enable_ctrl_btn'] != undefined) {
            if (short_code['popup_enable_ctrl_btn'] == 1) {
              jQuery("#popup_enable_ctrl_btn_1").attr('checked', 'checked');
              bwg_enable_disable('', 'tbody_popup_ctrl_btn1', 'popup_ctrl_btn_1');
              bwg_enable_disable('', 'tbody_popup_ctrl_btn2', 'popup_ctrl_btn_1');
              if (short_code['popup_enable_fullscreen'] == 1) {
                jQuery("#popup_enable_fullscreen_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_fullscreen_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_comment'] == 1) {
                jQuery("#popup_enable_comment_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_comment_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_email'] == 1) {
                jQuery("#popup_enable_email_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_email_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_captcha'] == 1) {
                jQuery("#popup_enable_captcha_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_captcha_0").attr('checked', 'checked');
              }
              if (short_code['comment_moderation'] == 1) {
                jQuery("#comment_moderation_1").attr('checked', 'checked');
              }
              else {
                jQuery("#comment_moderation_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_info'] == 1 || !short_code['popup_enable_info']) {
                jQuery("#popup_enable_info_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_info_0").attr('checked', 'checked');
              }
              if (short_code['popup_info_always_show'] == 1 && short_code['popup_info_always_show']) {
                jQuery("#popup_info_always_show_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_info_always_show_0").attr('checked', 'checked');
              }
              if (short_code['popup_info_full_width'] == 1) {
                jQuery("#popup_info_full_width_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_info_full_width_0").attr('checked', 'checked');
              }
              if (short_code['autohide_lightbox_navigation'] == 1) {
                jQuery("#autohide_lightbox_navigation_1").attr('checked', 'checked');
              }
              else {
                jQuery("#autohide_lightbox_navigation_0").attr('checked', 'checked');
              }
              if (short_code['popup_hit_counter'] == 1 && short_code['popup_hit_counter']) {
                jQuery("#popup_hit_counter_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_hit_counter_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_rate'] == 1 && short_code['popup_enable_rate']) {
                jQuery("#popup_enable_rate_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_rate_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_fullsize_image'] == 1) {
                jQuery("#popup_enable_fullsize_image_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_fullsize_image_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_download'] == 1) {
                jQuery("#popup_enable_download_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_download_0").attr('checked', 'checked');
              }
              if (short_code['show_image_counts'] == 1) {
                jQuery("#show_image_counts_current_image_number_1").attr('checked', 'checked');
              }
              else {
                jQuery("#show_image_counts_current_image_number_0").attr('checked', 'checked');
              }
              if (short_code['enable_loop'] == 1) {
                jQuery("#enable_loop_1").attr('checked', 'checked');
              }
              else {
                jQuery("#enable_loop_0").attr('checked', 'checked');
              }
              if (short_code['enable_addthis'] == 1) {
                jQuery("#enable_addthis_1").attr('checked', 'checked');
              }
              else {
                jQuery("#enable_addthis_0").attr('checked', 'checked');
              }
              if (short_code['addthis_profile_id'] != 'undefined') {
                jQuery("#addthis_profile_id").val(short_code['addthis_profile_id']);
              }
              if (short_code['popup_enable_facebook'] == 1) {
                jQuery("#popup_enable_facebook_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_facebook_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_twitter'] == 1) {
                jQuery("#popup_enable_twitter_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_twitter_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_google'] == 1) {
                jQuery("#popup_enable_google_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_google_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_pinterest'] == 1) {
                jQuery("#popup_enable_pinterest_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_pinterest_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_tumblr'] == 1) {
                jQuery("#popup_enable_tumblr_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_enable_tumblr_0").attr('checked', 'checked');
              }
              if (short_code['popup_enable_ecommerce'] == 1) {
                jQuery("#popup_ecommerce_1").attr('checked', 'checked');
              }
              else {
                jQuery("#popup_ecommerce_0").attr('checked', 'checked');
              }
            }
            else {
              jQuery("#popup_enable_ctrl_btn_0").attr('checked', 'checked');
            }
          }
          bwg_lightbox_hide_show_params();
          // Watermark.
          if (short_code['watermark_type'] == 'text') {
            jQuery("#watermark_type_text").attr('checked', 'checked');
            jQuery("#watermark_link").val(decodeURIComponent(short_code['watermark_link']));
            jQuery("#watermark_text").val(short_code['watermark_text']);
            jQuery("#watermark_font_size").val(short_code['watermark_font_size']);
            if (in_array(short_code['watermark_font'], bwg_objectGGF)) {
              jQuery("#watermark_google_fonts1").attr('checked', 'checked');
              bwg_change_fonts('watermark_font', 'watermark_google_fonts1');
            }
            else {
              jQuery("#watermark_google_fonts0").attr('checked', 'checked');
              bwg_change_fonts('watermark_font', '');
            }
            jQuery("select[id=watermark_font] option[value='" + short_code['watermark_font'] + "']").attr('selected', 'selected');
            jQuery("#watermark_color").val(short_code['watermark_color']);
            jQuery("#watermark_opacity").val(short_code['watermark_opacity']);
            jQuery("#watermark_type_text").attr('checked', 'checked');
            jQuery("#watermark_" + short_code['watermark_position']).attr('checked', 'checked');
          }
          else if (short_code['watermark_type'] == 'image') {
            jQuery("#watermark_type_image").attr('checked', 'checked');
            jQuery("#watermark_link").val(decodeURIComponent(short_code['watermark_link']));
            jQuery("#watermark_url").val(short_code['watermark_url']);
            jQuery("#watermark_width").val(short_code['watermark_width']);
            jQuery("#watermark_height").val(short_code['watermark_height']);
            jQuery("#watermark_opacity").val(short_code['watermark_opacity']);
            jQuery("#watermark_type_image").attr('checked', 'checked');
            jQuery("#watermark_" + short_code['watermark_position']).attr('checked', 'checked');
          }
          else {
            jQuery("#watermark_type_none").attr('checked', 'checked');
          }
          bwg_watermark('watermark_type_' + short_code['watermark_type']);
        }
      }

      // in_array
      function in_array(what, where) {
        var t = false;
        for (var i in where) {
          if (what == where[i]) {
            t = true;
            break;
          }
        }
        if (t == true) {
          return true;
        }
        else {
          return false;
        }
      }

      // Get shortcodes attributes.
      function get_params(module_name) {
        <?php if ($params['gutenberg_callback']) {
        if ( $params['gutenberg_id'] == 0) {
        ?>
        return false;
        <?php
        }
        ?>
        var short_code_attr = new Array();
        short_code_attr['id'] = <?php echo (int) $params['gutenberg_id']; ?>;
        return short_code_attr;
        <?php
        }
        elseif (!$from_menu) { ?>
        var selected_text;
        if (top.tinyMCE.activeEditor && !top.tinyMCE.activeEditor.hidden && top.tinyMCE.activeEditor.selection) {
          selected_text = top.tinyMCE.activeEditor.selection.getContent();
        }
        else {
          selected_text = bwg_get_textarea_selection(top.wpActiveEditor);
        }
        <?php } else { ?>
        var shortcode_val = jQuery("#shortcode").val();
        var selected_text = shortcode_val ? '[Best_Wordpress_Gallery id="' + shortcode_val + '"]' : '';
        <?php } ?>
        var module_start_index = selected_text.indexOf("[" + module_name);
        var module_end_index = selected_text.indexOf("]", module_start_index);
        var module_str = "";
        if ((module_start_index >= 0) && (module_end_index >= 0)) {
          module_str = selected_text.substring(module_start_index + 1, module_end_index);
        }
        else {
          return false;
        }
        var params_str = module_str.substring(module_str.indexOf(" ") + 1);
        var key_values = params_str.split('" ');
        var short_code_attr = new Array();
        for (var key in key_values) {
          var short_code_index = key_values[key].split('=')[0];
          var short_code_value = key_values[key].split('=')[1];
          short_code_value = short_code_value.replace(/\"/g, '');
          short_code_attr[short_code_index] = short_code_value;
        }
        return short_code_attr;
      }

      function get_short_params(tagtext) {
        var params_str = tagtext.substring(tagtext.indexOf(" ") + 1);
        var key_values = params_str.split('" ');
        var short_code_attr = new Array();
        for (var key in key_values) {
          var short_code_index = key_values[key].split('=')[0];
          var short_code_value = key_values[key].split('=')[1];
          short_code_value = short_code_value.replace(/\"/g, '');
          short_code_attr[short_code_index] = short_code_value;
        }
        return short_code_attr;
      }

      function bwg_insert_shortcode(content) {
        var page_builder_activated = bwg_before_shortcode_add_builder_editor();

        window.parent.window.jQuery(window.parent.document).trigger("onOpenShortcode");
        var gallery_type = jQuery("input[name=gallery_type]:checked").val();
        var theme = jQuery("#theme").val();
        var use_options_defaults = jQuery("#use_option_defaults").prop('checked') ? 1 : 0;
        var title = "";
        var short_code = '[Best_Wordpress_Gallery';
        var tagtext = ' gallery_type="' + gallery_type + '" theme_id="' + theme + '"';
        tagtext += ' use_option_defaults="' + use_options_defaults + '"';
        switch (gallery_type) {
          case 'thumbnails': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' thumb_width="' + jQuery("#thumb_width").val() + '"';
            tagtext += ' thumb_height="' + jQuery("#thumb_height").val() + '"';
            tagtext += ' image_column_number="' + jQuery("#image_column_number").val() + '"';
            tagtext += ' image_enable_page="' + jQuery("input[name=image_enable_page]:checked").val() + '"';
            tagtext += ' images_per_page="' + jQuery("#images_per_page").val() + '"';
            tagtext += ' load_more_image_count="' + jQuery("#load_more_image_count").val() + '"';
            tagtext += ' sort_by="' + jQuery("#sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=show_tag_box]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=showthumbs_name]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=show_gallery_description]:checked").val() + '"';
            tagtext += ' image_title="' + jQuery("input[name=image_title_show_hover]:checked").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'thumbnails_masonry': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' masonry_hor_ver="' + jQuery("input[name=masonry]:checked").val() + '"';
            tagtext += ' show_masonry_thumb_description="' + jQuery("input[name=show_masonry_thumb_description]:checked").val() + '"';
            tagtext += ' thumb_width="' + jQuery("#masonry_thumb_size").val() + '"';
            tagtext += ' thumb_height="' + jQuery("#masonry_thumb_size").val() + '"';
            tagtext += ' image_column_number="' + jQuery("#masonry_image_column_number").val() + '"';
            tagtext += ' image_enable_page="' + jQuery("input[name=masonry_image_enable_page]:checked").val() + '"';
            tagtext += ' images_per_page="' + jQuery("#masonry_images_per_page").val() + '"';
            tagtext += ' load_more_image_count="' + jQuery("#masonry_load_more_image_count").val() + '"';
            tagtext += ' sort_by="' + jQuery("#masonry_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=masonry_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=masonry_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#masonry_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#masonry_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=masonry_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=masonry_show_tag_box]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=masonry_show_gallery_title]:checked").val() + '"';
            tagtext += ' image_title="' + jQuery("input[name=masonry_image_title]:checked").val() + '"';
		      	tagtext += ' show_gallery_description="' + jQuery("input[name=masonry_show_gallery_description]:checked").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=masonry_play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=masonry_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=masonry_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'thumbnails_mosaic': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' mosaic_hor_ver="' + jQuery("input[name=mosaic]:checked").val() + '"';
            tagtext += ' resizable_mosaic="' + jQuery("input[name=resizable_mosaic]:checked").val() + '"';
            tagtext += ' mosaic_total_width="' + jQuery("#mosaic_total_width").val() + '"';
            tagtext += ' thumb_width="' + jQuery("#mosaic_thumb_size").val() + '"';
            tagtext += ' thumb_height="' + jQuery("#mosaic_thumb_size").val() + '"';
            tagtext += ' image_enable_page="' + jQuery("input[name=mosaic_image_enable_page]:checked").val() + '"';
            tagtext += ' images_per_page="' + jQuery("#mosaic_images_per_page").val() + '"';
            tagtext += ' load_more_image_count="' + jQuery("#mosaic_load_more_image_count").val() + '"';
            tagtext += ' sort_by="' + jQuery("#mosaic_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=mosaic_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=mosaic_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#mosaic_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#mosaic_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=mosaic_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=mosaic_show_tag_box]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=mosaic_show_gallery_title]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=mosaic_show_gallery_description]:checked").val() + '"';
            tagtext += ' image_title="' + jQuery("input[name=mosaic_image_title_show_hover]:checked").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=mosaic_play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=mosaic_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=mosaic_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'slideshow': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';
            tagtext += ' slideshow_effect="' + jQuery("#slideshow_type").val() + '"';
            tagtext += ' slideshow_interval="' + jQuery("#slideshow_interval").val() + '"';
            tagtext += ' slideshow_width="' + jQuery("#slideshow_width").val() + '"';
            tagtext += ' slideshow_height="' + jQuery("#slideshow_height").val() + '"';
            tagtext += ' sort_by="' + jQuery("#slideshow_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=slideshow_order_by]:checked").val() + '"';
            tagtext += ' enable_slideshow_autoplay="' + jQuery("input[name=slideshow_enable_autoplay]:checked").val() + '"';
            tagtext += ' enable_slideshow_shuffle="' + jQuery("input[name=slideshow_enable_shuffle]:checked").val() + '"';
            tagtext += ' enable_slideshow_ctrl="' + jQuery("input[name=slideshow_enable_ctrl]:checked").val() + '"';
            tagtext += ' autohide_slideshow_navigation="' + jQuery("input[name=autohide_slideshow_navigation]:checked").val() + '"';
            tagtext += ' enable_slideshow_filmstrip="' + jQuery("input[name=slideshow_enable_filmstrip]:checked").val() + '"';
            tagtext += ' slideshow_filmstrip_height="' + jQuery("#slideshow_filmstrip_height").val() + '"';
            tagtext += ' slideshow_enable_title="' + jQuery("input[name=slideshow_enable_title]:checked").val() + '"';
            tagtext += ' slideshow_title_position="' + jQuery("input[name=slideshow_title_position]:checked").val() + '"';
            tagtext += ' slideshow_title_full_width="' + jQuery("input[name=slideshow_title_full_width]:checked").val() + '"';
            tagtext += ' slideshow_enable_description="' + jQuery("input[name=slideshow_enable_description]:checked").val() + '"';
            tagtext += ' slideshow_description_position="' + jQuery("input[name=slideshow_description_position]:checked").val() + '"';
            tagtext += ' enable_slideshow_music="' + jQuery("input[name=slideshow_enable_music]:checked").val() + '"';
            tagtext += ' slideshow_music_url="' + jQuery("#slideshow_audio_url").val() + '"';
            tagtext += ' slideshow_effect_duration="' + jQuery("#slideshow_effect_duration").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=slideshow_gallery_download]:checked").val() + '"';
            break;
          }
          case 'image_browser': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' image_browser_width="' + jQuery("#image_browser_width").val() + '"';
            tagtext += ' image_browser_title_enable="' + jQuery("input[name=image_browser_title_enable]:checked").val() + '"';
            tagtext += ' image_browser_description_enable="' + jQuery("input[name=image_browser_description_enable]:checked").val() + '"';
            tagtext += ' sort_by="' + jQuery("#image_browser_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=image_browser_order_by]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=image_browser_show_gallery_title]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=image_browser_show_gallery_description]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=image_browser_show_search_box]:checked").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=image_browser_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=image_browser_show_tag_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#image_browser_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#image_browser_search_box_width").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=image_browser_gallery_download]:checked").val() + '"';
            break;
          }
          case 'blog_style': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' blog_style_width="' + jQuery("#blog_style_width").val() + '"';
            tagtext += ' blog_style_title_enable="' + jQuery("input[name=blog_style_title_enable]:checked").val() + '"';
            tagtext += ' blog_style_images_per_page="' + jQuery("#blog_style_images_per_page").val() + '"';
            tagtext += ' blog_style_load_more_image_count="' + jQuery("#blog_style_load_more_image_count").val() + '"';
            tagtext += ' blog_style_enable_page="' + jQuery("input[name=blog_style_enable_page]:checked").val() + '"';
            tagtext += ' blog_style_description_enable="' + jQuery("input[name=blog_style_description_enable]:checked").val() + '"';
            tagtext += ' sort_by="' + jQuery("#blog_style_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=blog_style_order_by]:checked").val() + '"';
            tagtext += ' showthumbs_name="' + jQuery("input[name=blog_style_show_gallery_title]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=blog_style_show_gallery_description]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=blog_style_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#blog_style_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#blog_style_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=blog_style_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=blog_style_show_tag_box]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=blog_style_gallery_download]:checked").val() + '"';
            break;
          }
          case 'carousel': {
            title = ' gal_title="' + jQuery.trim(jQuery('#gallery option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' gallery_id="' + jQuery("#gallery").val() + '"';
            tagtext += ' tag="' + jQuery("#tag").val() + '"';

            tagtext += ' carousel_interval="' + jQuery("#carousel_interval").val() + '"';
            tagtext += ' carousel_width="' + jQuery("#carousel_width").val() + '"';
            tagtext += ' carousel_height="' + jQuery("#carousel_height").val() + '"';
            tagtext += ' carousel_image_column_number="' + jQuery("#carousel_image_column_number").val() + '"';
            tagtext += ' carousel_image_par="' + jQuery("#carousel_image_par").val() + '"';
            tagtext += ' enable_carousel_title="' + jQuery("input[name=carousel_enable_title]:checked").val() + '"';
            tagtext += ' enable_carousel_autoplay="' + jQuery("input[name=carousel_enable_autoplay]:checked").val() + '"';
            tagtext += ' carousel_r_width="' + jQuery("#carousel_r_width").val() + '"';
            tagtext += ' carousel_fit_containerWidth="' + jQuery("input[name=carousel_fit_containerWidth]:checked").val() + '"';
            tagtext += ' carousel_prev_next_butt="' + jQuery("input[name=carousel_prev_next_butt]:checked").val() + '"';
            tagtext += ' carousel_play_pause_butt="' + jQuery("input[name=carousel_play_pause_butt]:checked").val() + '"';
            tagtext += ' sort_by="' + jQuery("#carousel_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=carousel_order_by]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=carousel_gallery_download]:checked").val() + '"';
            break;
          }
          case 'album_compact_preview': {
            title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' album_id="' + jQuery("#album").val() + '"';

            tagtext += ' compuct_album_column_number="' + jQuery("#album_column_number").val() + '"';
            tagtext += ' compuct_album_thumb_width="' + jQuery("#album_thumb_width").val() + '"';
            tagtext += ' compuct_album_thumb_height="' + jQuery("#album_thumb_height").val() + '"';
            tagtext += ' compuct_album_image_column_number="' + jQuery("#album_image_column_number").val() + '"';
            tagtext += ' compuct_album_image_thumb_width="' + jQuery("#album_image_thumb_width").val() + '"';
            tagtext += ' compuct_album_image_thumb_height="' + jQuery("#album_image_thumb_height").val() + '"';
            tagtext += ' compuct_album_enable_page="' + jQuery("input[name=album_enable_page]:checked").val() + '"';
            tagtext += ' compuct_albums_per_page="' + jQuery("#albums_per_page").val() + '"';
            tagtext += ' compuct_album_images_per_page="' + jQuery("#album_images_per_page").val() + '"';
            tagtext += ' sort_by="' + jQuery("#album_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=album_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=album_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#album_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#album_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=album_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=album_show_tag_box]:checked").val() + '"';
            tagtext += ' show_album_name="' + jQuery("input[name=show_album_name]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=album_show_gallery_description]:checked").val() + '"';
            tagtext += ' compuct_album_title="' + jQuery("input[name=album_title_show_hover]:checked").val() + '"';
            tagtext += ' compuct_album_view_type="' + jQuery("input[name=album_view_type]:checked").val() + '"';
            tagtext += ' compuct_album_image_title="' + jQuery("input[name=album_image_title_show_hover]:checked").val() + '"';
            tagtext += ' compuct_album_mosaic_hor_ver="' + jQuery("input[name=album_mosaic]:checked").val() + '"';
            tagtext += ' compuct_album_resizable_mosaic="' + jQuery("input[name=album_resizable_mosaic]:checked").val() + '"';
            tagtext += ' compuct_album_mosaic_total_width="' + jQuery("#album_mosaic_total_width").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=album_play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=album_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=album_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'album_masonry_preview' : {
            title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' album_id="' + jQuery("#album").val() + '"';

            tagtext += ' masonry_album_column_number="' + jQuery("#album_masonry_column_number").val() + '"';
            tagtext += ' masonry_album_thumb_width="' + jQuery("#album_masonry_thumb_width").val() + '"';
            tagtext += ' masonry_album_image_column_number="' + jQuery("#album_masonry_image_column_number").val() + '"';
            tagtext += ' masonry_album_image_thumb_width="' + jQuery("#album_masonry_image_thumb_width").val() + '"';
            tagtext += ' masonry_album_enable_page="' + jQuery("input[name=album_masonry_enable_page]:checked").val() + '"';
            tagtext += ' masonry_albums_per_page="' + jQuery("#albums_masonry_per_page").val() + '"';
            tagtext += ' masonry_album_images_per_page="' + jQuery("#album_masonry_images_per_page").val() + '"';
            tagtext += ' sort_by="' + jQuery("#album_masonry_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=album_masonry_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=album_masonry_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#album_masonry_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#album_masonry_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=album_masonry_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=album_masonry_show_tag_box]:checked").val() + '"';
            tagtext += ' show_album_name="' + jQuery("input[name=show_album_masonry_name]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=album_masonry_show_gallery_description]:checked").val() + '"';
            tagtext += ' image_title="' + jQuery("input[name=album_masonry_image_title]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=album_masonry_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=album_masonry_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
          case 'album_extended_preview': {
            title = ' gal_title="' + jQuery.trim(jQuery('#album option:selected').text().replace("'", "").replace('"', '')) + '"';
            tagtext += ' album_id="' + jQuery("#album").val() + '"';

            tagtext += ' extended_album_height="' + jQuery("#extended_album_height").val() + '"';
            tagtext += ' extended_album_thumb_width="' + jQuery("#album_extended_thumb_width").val() + '"';
            tagtext += ' extended_album_thumb_height="' + jQuery("#album_extended_thumb_height").val() + '"';
            tagtext += ' extended_album_image_column_number="' + jQuery("#album_extended_image_column_number").val() + '"';
            tagtext += ' extended_album_image_thumb_width="' + jQuery("#album_extended_image_thumb_width").val() + '"';
            tagtext += ' extended_album_image_thumb_height="' + jQuery("#album_extended_image_thumb_height").val() + '"';
            tagtext += ' extended_album_enable_page="' + jQuery("input[name=album_extended_enable_page]:checked").val() + '"';
            tagtext += ' extended_albums_per_page="' + jQuery("#albums_extended_per_page").val() + '"';
            tagtext += ' extended_album_images_per_page="' + jQuery("#album_extended_images_per_page").val() + '"';
            tagtext += ' sort_by="' + jQuery("#album_extended_sort_by").val() + '"';
            tagtext += ' order_by="' + jQuery("input[name=album_extended_order_by]:checked").val() + '"';
            tagtext += ' show_search_box="' + jQuery("input[name=album_extended_show_search_box]:checked").val() + '"';
            tagtext += ' placeholder="' + jQuery("#album_extended_placeholder").val() + '"';
            tagtext += ' search_box_width="' + jQuery("#album_extended_search_box_width").val() + '"';
            tagtext += ' show_sort_images="' + jQuery("input[name=album_extended_show_sort_images]:checked").val() + '"';
            tagtext += ' show_tag_box="' + jQuery("input[name=album_extended_show_tag_box]:checked").val() + '"';
            tagtext += ' show_album_name="' + jQuery("input[name=show_album_extended_name]:checked").val() + '"';
            tagtext += ' extended_album_description_enable="' + jQuery("input[name=extended_album_description_enable]:checked").val() + '"';
            tagtext += ' show_gallery_description="' + jQuery("input[name=album_extended_show_gallery_description]:checked").val() + '"';
            tagtext += ' extended_album_view_type="' + jQuery("input[name=album_extended_view_type]:checked").val() + '"';
            tagtext += ' extended_album_image_title="' + jQuery("input[name=album_extended_image_title_show_hover]:checked").val() + '"';
            tagtext += ' extended_album_mosaic_hor_ver="' + jQuery("input[name=album_extended_mosaic]:checked").val() + '"';
            tagtext += ' extended_album_resizable_mosaic="' + jQuery("input[name=album_extended_resizable_mosaic]:checked").val() + '"';
            tagtext += ' extended_album_mosaic_total_width="' + jQuery("#album_extended_mosaic_total_width").val() + '"';
            tagtext += ' play_icon="' + jQuery("input[name=album_extended_play_icon]:checked").val() + '"';
            tagtext += ' gallery_download="' + jQuery("input[name=album_extended_gallery_download]:checked").val() + '"';
            tagtext += ' ecommerce_icon="' + jQuery("input[name=album_extended_ecommerce_icon_show_hover]:checked").val() + '"';
            break;
          }
        }
        // Lightbox paramteres.
        tagtext += ' thumb_click_action="' + jQuery("input[name=thumb_click_action]:checked").val() + '"';
        tagtext += ' thumb_link_target="' + jQuery("input[name=thumb_link_target]:checked").val() + '"';
        tagtext += ' popup_fullscreen="' + jQuery("input[name=popup_fullscreen]:checked").val() + '"';
        tagtext += ' popup_width="' + jQuery("#popup_width").val() + '"';
        tagtext += ' popup_height="' + jQuery("#popup_height").val() + '"';
        tagtext += ' popup_effect="' + jQuery("#popup_type").val() + '"';
        tagtext += ' popup_effect_duration="' + jQuery("#popup_effect_duration").val() + '"';
        tagtext += ' popup_autoplay="' + jQuery("input[name=popup_autoplay]:checked").val() + '"';
        tagtext += ' popup_interval="' + jQuery("#popup_interval").val() + '"';
        tagtext += ' popup_enable_filmstrip="' + jQuery("input[name=popup_enable_filmstrip]:checked").val() + '"';
        tagtext += ' popup_filmstrip_height="' + jQuery("#popup_filmstrip_height").val() + '"';
        tagtext += ' popup_enable_ctrl_btn="' + jQuery("input[name=popup_enable_ctrl_btn]:checked").val() + '"';
        tagtext += ' popup_enable_fullscreen="' + jQuery("input[name=popup_enable_fullscreen]:checked").val() + '"';
        tagtext += ' popup_enable_comment="' + jQuery("input[name=popup_enable_comment]:checked").val() + '"';
        tagtext += ' popup_enable_email="' + jQuery("input[name=popup_enable_email]:checked").val() + '"';
        tagtext += ' popup_enable_captcha="' + jQuery("input[name=popup_enable_captcha]:checked").val() + '"';
        tagtext += ' comment_moderation="' + jQuery("input[name=comment_moderation]:checked").val() + '"';
        tagtext += ' popup_enable_info="' + jQuery("input[name=popup_enable_info]:checked").val() + '"';
        tagtext += ' popup_info_always_show="' + jQuery("input[name=popup_info_always_show]:checked").val() + '"';
        tagtext += ' popup_info_full_width="' + jQuery("input[name=popup_info_full_width]:checked").val() + '"';
        tagtext += ' autohide_lightbox_navigation="' + jQuery("input[name=autohide_lightbox_navigation]:checked").val() + '"';
        tagtext += ' popup_hit_counter="' + jQuery("input[name=popup_hit_counter]:checked").val() + '"';
        tagtext += ' popup_enable_rate="' + jQuery("input[name=popup_enable_rate]:checked").val() + '"';
        tagtext += ' popup_enable_fullsize_image="' + jQuery("input[name=popup_enable_fullsize_image]:checked").val() + '"';
        tagtext += ' popup_enable_download="' + jQuery("input[name=popup_enable_download]:checked").val() + '"';
        tagtext += ' show_image_counts="' + jQuery("input[name=show_image_counts]:checked").val() + '"';
        tagtext += ' enable_loop="' + jQuery("input[name=enable_loop]:checked").val() + '"';
        tagtext += ' enable_addthis="' + jQuery("input[name=enable_addthis]:checked").val() + '"';
        tagtext += ' addthis_profile_id="' + jQuery("#addthis_profile_id").val() + '"';
        tagtext += ' popup_enable_facebook="' + jQuery("input[name=popup_enable_facebook]:checked").val() + '"';
        tagtext += ' popup_enable_twitter="' + jQuery("input[name=popup_enable_twitter]:checked").val() + '"';
        tagtext += ' popup_enable_google="' + jQuery("input[name=popup_enable_google]:checked").val() + '"';
        tagtext += ' popup_enable_pinterest="' + jQuery("input[name=popup_enable_pinterest]:checked").val() + '"';
        tagtext += ' popup_enable_tumblr="' + jQuery("input[name=popup_enable_tumblr]:checked").val() + '"';
        tagtext += ' popup_enable_ecommerce="' + jQuery("input[name=popup_enable_ecommerce]:checked").val() + '"';
        // Watermark parameters.
        tagtext += ' watermark_type="' + jQuery("input[name=watermark_type]:checked").val() + '"';
        tagtext += ' watermark_link="' + (jQuery("#watermark_link").val()) + '"';
        if (jQuery("input[name=watermark_type]:checked").val() == 'text') {
          tagtext += ' watermark_text="' + jQuery("#watermark_text").val() + '"';
          tagtext += ' watermark_font_size="' + jQuery("#watermark_font_size").val() + '"';
          tagtext += ' watermark_font="' + jQuery("#watermark_font").val() + '"';
          tagtext += ' watermark_color="' + jQuery("#watermark_color").val() + '"';
          tagtext += ' watermark_opacity="' + jQuery("#watermark_opacity").val() + '"';
          tagtext += ' watermark_position="' + jQuery("input[name=watermark_position]:checked").val() + '"';
        }
        else if (jQuery("input[name=watermark_type]:checked").val() == 'image') {
          tagtext += ' watermark_url="' + jQuery("#watermark_url").val() + '"';
          tagtext += ' watermark_width="' + jQuery("#watermark_width").val() + '"';
          tagtext += ' watermark_height="' + jQuery("#watermark_height").val() + '"';
          tagtext += ' watermark_opacity="' + jQuery("#watermark_opacity").val() + '"';
          tagtext += ' watermark_position="' + jQuery("input[name=watermark_position]:checked").val() + '"';
        }
        short_code += ' id="' + shortcode_id + '"' + title + ']';
        var short_id = ' id="' + shortcode_id + '"' + title;
        <?php if (!$from_menu && !$params['gutenberg_callback']) { ?>
        if (top.tinyMCE.activeEditor && !top.tinyMCE.activeEditor.hidden) {
          // If there is no builder, then shortcode replace to image.
          if( !page_builder_activated ) {
            short_code = short_code.replace(/\[Best_Wordpress_Gallery([^\]]*)\]/g, function (d, c) {
              return "<img src='<?php echo BWG()->plugin_url; ?>/images/icons/gallery-icon.png' class='bwg_shortcode mceItem' title='Best_Wordpress_Gallery" + short_id + "' />";
            });
          }
        }
        var post_data = {};
        var url = '<?php echo add_query_arg(array( 'action' => 'shortcode_bwg' ), admin_url('admin-ajax.php')); ?>';
        post_data['bwg_nonce'] = jQuery("#bwg_nonce").val();
        post_data['task'] = "save";
        post_data['tagtext'] = tagtext;
        post_data['currrent_id'] = shortcode_id;
        post_data['title'] = title;
        post_data['bwg_insert'] = (content && !bwg_insert) ? 0 : 1;
        var use_options_defaults = jQuery("#use_option_defaults").prop('checked') ? 1 : 0;
        post_data['use_option_defaults'] = use_options_defaults;
        jQuery.post(
          url,
          post_data
        ).success(function (data, textStatus, errorThrown) {
          if (top.tinymce.isIE && content) {
            // IE and Update.
            var all_content = top.tinyMCE.activeEditor.getContent();
            all_content = all_content.replace('<p></p><p>[Best_Wordpress_Gallery', '<p>[Best_Wordpress_Gallery');
            top.tinyMCE.activeEditor.setContent(all_content.replace(content, '[Best_Wordpress_Gallery id="' + shortcode_id + '"' + title + ']'));
          }
          else {
            top.send_to_editor(short_code);
          }
          top.tinyMCE.execCommand('mceRepaint');
          /* Close shortcode editor after insert.*/
          if (top.tinyMCE.activeEditor) {
            top.tinyMCE.activeEditor.windowManager.close(window);
          }
          top.tb_remove();
          jQuery('#loading_div').hide();
        });
        <?php } else { ?>
        var post_data = {};
        var url = '<?php echo add_query_arg(array( 'action' => 'shortcode_bwg' ), admin_url('admin-ajax.php')); ?>';
        post_data['bwg_nonce'] = jQuery("#bwg_nonce").val();
        post_data['task'] = "save";
        post_data['tagtext'] = tagtext;
        post_data['currrent_id'] = shortcode_id;
        post_data['title'] = title;
        post_data['bwg_insert'] = (content && !bwg_insert) ? 0 : 1;
        var use_options_defaults = jQuery("#use_option_defaults").prop('checked') ? 1 : 0;
        post_data['use_option_defaults'] = use_options_defaults;
        jQuery.post(
          url,
          post_data
        ).success(function (data, textStatus, errorThrown) {
          content = '[Best_Wordpress_Gallery id="' + shortcode_id + '"' + title + ']';
          <?php
          if ( $params['gutenberg_callback'] ) {
          ?>
          window.parent['<?php echo $params['gutenberg_callback']; ?>'](content, shortcode_id);
          return;
          <?php
          }
          ?>
          if (bwg_insert) {
            jQuery('#shortcode').append('<option value="' + shortcode_id + '">[Best_Wordpress_Gallery id="' + shortcode_id + '"]</option>').val(shortcode_id);
          }
          jQuery('#insert').text('<?php _e('Update', BWG()->prefix); ?>');
          jQuery('#insert').attr('onclick', 'jQuery("#loading_div").show(); bwg_insert_shortcode(content);');
          jQuery("#bwg_shortcode").val(content);
          var str = "&#60;?php echo photo_gallery(" + shortcode_id + "); ?&#62;";
          jQuery("#bwg_function").val(str.replace("&#60;", '<').replace("&#62;", '>'));
          shortcodes[shortcode_id] = tagtext;
          temp_shortcode_id = ++shortcode_id;
          bwg_update_shortcode();
          jQuery('#loading_div').hide();
        });
        <?php } ?>
        return;
      }
      function bwg_before_shortcode_add_builder_editor() {
        if ( top.jQuery('body').hasClass('elementor-editor-active') || top.jQuery('body').hasClass('fl-builder') ) {
          return true;
        }
        return false;
      }
      jQuery(document).ready(function () {
        bwg_shortcode_hide_show_params();
        bwg_change_tab();
      });
      jQuery(window).resize(function () {
        bwg_change_tab();
      });
      var bwg_image_thumb = '<?php echo addslashes(__('Thumbnail dimensions', BWG()->prefix)); ?>';
      var bwg_image_thumb_width = '<?php echo addslashes(__('Image thumbnail width ', BWG()->prefix)); ?>';
      var bwg_max_column = '<?php echo addslashes(__('Number of image columns', BWG()->prefix)); ?>';
      var bwg_image_thumb_height = '<?php echo addslashes(__('Image thumbnail height', BWG()->prefix)); ?>';
      var bwg_number_of_image_rows = '<?php echo addslashes(__('Number of image rows', BWG()->prefix)); ?>';
    </script>
    <?php
    return ob_get_clean();
  }
}

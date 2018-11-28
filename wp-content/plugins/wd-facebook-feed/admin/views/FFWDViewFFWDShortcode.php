<?php

class FFWDViewFFWDShortcode
{
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
  public function __construct($model)
  {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display()
  {
    $wd_fb_rows = $this->model->get_wd_fb_data();
    ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>WD Facebook Feed</title>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	  <link rel="stylesheet" href="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/plugins/compat3x/css/dialog.css" type="text/css" media="all">		  
      <?php
      wp_print_scripts('jquery');
      wp_print_scripts('jquery-ui-core');
      wp_print_scripts('jquery-ui-widget');
      wp_print_scripts('jquery-ui-position');
      wp_print_scripts('jquery-ui-tooltip');
      ?>
      <link rel="stylesheet" href="<?php echo WD_FFWD_URL . '/css/ffwd_shortcode.css?ver=' . ffwd_version(); ?>">
      <link rel="stylesheet" href="<?php echo WD_FFWD_URL . '/css/jquery-ui-1.10.3.custom.css'; ?>">
      <script language="javascript" type="text/javascript" src="<?php echo WD_FFWD_URL . '/js/ffwd_shortcode.js?ver=' . ffwd_version(); ?>"></script>
      <script language="javascript" type="text/javascript" src="<?php echo WD_FFWD_URL . '/js/jscolor/jscolor.js?ver=' . ffwd_version(); ?>"></script>
      <base target="_self">
    </head>
    <body id="link" dir="ltr" class="forceColors">
    <?php /* if (isset($_POST['tagtext'])) {
      echo '<script>tinyMCEPopup.close();</script></body></html>';
      die();
    }  */?>
    <form method="post" action="#" id="bwg_shortcode_form">
      <?php wp_nonce_field('FFWDShortcode', 'ffwd_nonce'); ?>
      <div class="tabs" role="tablist" tabindex="-1">
       <h4>WD Facebook Feed</h4>
      </div>
      <div class="panel_wrapper">
        <div id="display_panel" class="panel current">
          <div style="">
            <div style="float:left">
              <div class="gallery_type" style="border-style:none">
                <select name="wd_fb_feed" id="wd_fb_feed" onchange="wd_fb_insert_btn_disable(this)">
                  <option value="0" fb_content_type="0" selected="selected">Select Facebook Feed </option>
                  <?php foreach ($wd_fb_rows as $gallery_row) { ?>
                    <option value="<?php echo $gallery_row->id; ?>"
						fb_type="<?php echo $gallery_row->type; ?>"
						fb_content_type="<?php echo $gallery_row->content_type; ?>"
						fb_content="<?php echo $gallery_row->content; ?>"> <?php echo $gallery_row->name; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div style="clear:both"></div>
          </div>
        </div>
      </div>
      <div class="mceActionPanel">
        <div style="float:left;">
          <input type="button" id="cancel" name="cancel" value="Cancel" onClick="top.tinyMCE.activeEditor.windowManager.close(window);"/>
        </div>
        <div style="float:right;">
          <input type="button" id="insert" name="insert" value="Insert" onClick="bwg_insert_shortcode('wd_fb', '');"/>
        </div>
        <div style="clear:both"></div>
      </div>
      <input type="hidden" id="tagtext" name="tagtext" value=""/>
      <input type="hidden" id="currrent_id" name="currrent_id" value=""/>
      <input type="hidden" id="bwg_insert" name="bwg_insert" value=""/>
      <input type="hidden" id="task" name="task" value=""/>
    </form>
    <script type="text/javascript">


      var params = get_params("WD_FB");

      var bwg_insert = 1;

      var content = top.tinyMCE.activeEditor.selection.getContent();


      // Get shortcodes attributes.
      function get_params(module_name) {
        var selected_text = top.tinyMCE.activeEditor.selection.getContent();
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
          short_code_attr['id'] = short_code_value;
        }
        return short_code_attr;
      }


      function bwg_insert_shortcode(wd_fb_prefix, content) {

        short_code = '[WD_FB';


        short_code += ' id="' + jQuery("#wd_fb_feed").val() + '"]';
        var short_id = ' id="' + jQuery("#wd_fb_feed").val() + '"';
        short_code = short_code.replace(/\[WD_FB([^\]]*)\]/g, function (d, c) {
          return "<img src='<?php echo WD_FFWD_URL; ?>/images/ffwd/ffwd_logo_large.png' class='wd_fb_shortcode mceItem' title='WD_FB" + short_id + "' />";
        });

        jQuery("#bwg_shortcode_form").submit();
        if (top.tinymce.isIE && content) {
          // IE and Update.
          var all_content = top.tinyMCE.activeEditor.getContent();
          all_content = all_content.replace('<p></p><p>[WD_FB', '<p>[WD_FB');
          top.tinyMCE.activeEditor.setContent(all_content.replace(content, '[WD_FB id="' + jQuery("#wd_fb_feed").val() + '"]'));
        }
        else {
          top.tinyMCE.execCommand('mceInsertContent', false, short_code);
        }
        //tinyMCEPopup.editor.execCommand('mceRepaint');
		top.tinyMCE.activeEditor.windowManager.close(window);
      }


      params = get_params('WD_FB');
      if (params['id']) {
          jQuery('#wd_fb_feed').val(params['id']);
      }

      function wd_fb_insert_btn_disable(){
          if(jQuery('#wd_fb_feed').val() == 0){
              jQuery('#insert').attr('disabled', true);
              jQuery('#insert').css('opacity', 0.5);
          }else{
              jQuery('#insert').attr('disabled', false);
              jQuery('#insert').css('opacity', 1);
          }
      }
      wd_fb_insert_btn_disable();

    </script>
    </body>
    </html>
    <?php
    die();
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

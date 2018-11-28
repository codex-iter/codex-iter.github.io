<?php

class  FFWDViewThemes_ffwd
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
        ?>
        <div class="ffwd_upgrade wd-clear" >
            <div class="ffwd-left">

               

            </div>
            <div class="ffwd-right">
                <div class="wd-table">
                    <div class="wd-cell wd-cell-valign-middle">
                        <a href="https://wordpress.org/support/plugin/wd-facebook-feed" target="_blank">
                            <img src="<?php echo WD_FFWD_URL; ?>/images/i_support.png" >
											    <?php _e("Support Forum", "gmwd"); ?>
                        </a>
                    </div>
                    <div class="wd-cell wd-cell-valign-middle">
                        <a href="https://web-dorado.com/files/fromFacebookFeed.php" target="_blank">
											    <?php _e("UPGRADE TO PAID VERSION", "gmwd"); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div style="clear: both;float: right;color: #15699F; font-size: 20px; margin-top:10px; padding:8px 15px;">
            This is FREE version, Customizing themes is available only in the PAID version.
        </div>

        <img src='<?php echo plugins_url('../../images/themes/thumbnail.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/masonry.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/album.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/blog.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/lightbox.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/pagenav.png', __FILE__) ?>'/><br>
        <?php

    }

    public function edit($id, $reset)
    {
        $row = $this->model->get_row_data($id, $reset);
        $page_title = (($id != 0) ? 'Edit theme ' . $row->name : 'Create new theme');
        $current_type = WDW_FFWD_Library::get('current_type', 'Thumbnail');
        $border_styles = array(
            'none' => 'None',
            'solid' => 'Solid',
            'dotted' => 'Dotted',
            'dashed' => 'Dashed',
            'double' => 'Double',
            'groove' => 'Groove',
            'ridge' => 'Ridge',
            'inset' => 'Inset',
            'outset' => 'Outset',
        );
        $font_families = array(
            'inherit' => 'Inherit',
            'arial' => 'Arial',
            'lucida grande' => 'Lucida grande',
            'segoe ui' => 'Segoe ui',
            'tahoma' => 'Tahoma',
            'trebuchet ms' => 'Trebuchet ms',
            'verdana' => 'Verdana',
            'cursive' => 'Cursive',
            'fantasy' => 'Fantasy',
            'monospace' => 'Monospace',
            'serif' => 'Serif',
            // 'helvetica, arial, sans-serif' => 'Helvetica',
            'helvetica' => 'Helvetica',
        );
        $aligns = array(
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        );
        $font_weights = array(
            'lighter' => 'Lighter',
            'normal' => 'Normal',
            'bold' => 'Bold',
        );
        $fd_header_icons = array(
            'fa fa-vimeo' => 'Vimeo',
            'fa fa-wordpress' => 'Wordpress',
            'fa fa-facebook' => 'Facebook',
            'fa fa-android' => 'Android',
            'fa fa-apple' => 'Apple',
            'fa fa-youtube' => 'Youtube',
            'fa fa-facebook-official' => 'Facebook official',
            'fa fa-calendar' => 'Event',
            'fa fa-calendar-plus-o' => 'Calendar plus',
            'fa fa-calendar-check-o' => 'Calendar check',
            'fa fa-camera' => 'Camera',
            'fa fa-hand-peace-o' => 'Hand-peace',
            'fa fa-futbol-o' => 'Soccer',
            'fa fa-car' => 'Car',
            'fa fa-instagram' => 'Instagram',
        );
        $hover_effects = array(
            'none' => 'None',
            'rotate' => 'Rotate',
            'scale' => 'Scale',
            'skew' => 'Skew',
        );
        $button_styles = array(
            'fa-chevron' => 'Chevron',
            'fa-angle' => 'Angle',
            'fa-angle-double' => 'Double',
        );
        $rate_icons = array(
            'star' => 'Star',
            'bell' => 'Bell',
            'circle' => 'Circle',
            'flag' => 'Flag',
            'heart' => 'Heart',
            'square' => 'Square',
        );
        ?>
        <div style="font-size: 14px; font-weight: bold;">
            This section allows you to add/edit theme.
            <a style="color: blue; text-decoration: none;" target="_blank"
               href="http://web-dorado.com/wordpress-gallery-guide-step-6/6-1.html">Read More in User Manual</a>
        </div>
        <form class="wrap" method="post" id="themes_form" action="admin.php?page=themes_ffwd" style="width:99%;">
            <?php wp_nonce_field('themes_ffwd', 'ffwd_nonce'); ?>
            <h2></h2>

            <div class="ffwd_plugin_header">
                <span class="theme_icon"></span>

                <h2 class="ffwd_page_name"><?php echo $page_title; ?></h2>
            </div>
            <div style="float: right; margin: 0 5px 0 0;padding-top: 10px">
                <input class="ffwd-button-primary ffwd-button-save" type="submit"
                       onclick="if (spider_check_required('name', 'Name')) {return false;}; spider_set_input_value('task', 'save')"
                       value="Save"/>
                <input class="ffwd-button-primary ffwd-button-apply" type="submit"
                       onclick="if (spider_check_required('name', 'Name')) {return false;}; spider_set_input_value('task', 'apply')"
                       value="Apply"/>
                <input class="ffwd-button-secondary ffwd-button-cancel" type="submit"
                       onclick="spider_set_input_value('task', 'cancel')"
                       value="Cancel"/>
                <input title="Reset to default theme" class="ffwd-button-primary ffwd-button-reset" type="submit"
                       onclick="if (confirm('Do you want to reset to default?')) {
                                                                 spider_set_input_value('task', 'reset');
                                                               } else {
                                                                 return false;
                                                               }" value="Reset"/>
            </div>
            <div style="float: left; margin: 10px 0 0; display: none;" id="type_menu">
                <div id="type_Thumbnail" class="theme_type" onclick="bwg_change_theme_type('Thumbnail')">Thumbnails
                </div>
                <div id="type_Masonry" class="theme_type" onclick="bwg_change_theme_type('Masonry')">Masonry</div>
                <div id="type_Compact_album" class="theme_type" onclick="bwg_change_theme_type('Compact_album')">Compact
                    Album
                </div>
                <div id="type_Blog_style" class="theme_type" onclick="bwg_change_theme_type('Blog_style')">Blog Style
                </div>
                <div id="type_Lightbox" class="theme_type" onclick="bwg_change_theme_type('Lightbox')">Lightbox</div>
                <div id="type_Navigation" class="theme_type" onclick="bwg_change_theme_type('Navigation')">Page
                    Navigation
                </div>
                <input type="hidden" id="current_type" name="current_type" value="<?php echo $current_type; ?>"/>
            </div>
            <fieldset class="spider_fieldset">

                <table style="clear:both;">
                    <tbody>
                    <tr>
                        <td class="spider_label"><label for="name">Name: <span style="color:#FF0000;"> * </span>
                            </label></td>
                        <td><input type="text" id="name" name="name" value="<?php echo $row->name; ?>"
                                   class="spider_text_input"/></td>
                    </tr>
                    </tbody>
                </table>

                <fieldset class="spider_type_fieldset" id="Thumbnail">
                    <fieldset class="spider_child_fieldset" id="Thumbnail_1">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="thumb_margin">Margin: </label></td>
                                <td>
                                    <input type="text" name="thumb_margin" id="thumb_margin"
                                           value="<?php echo $row->thumb_margin; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_padding">Padding: </label></td>
                                <td>
                                    <input type="text" name="thumb_padding" id="thumb_padding"
                                           value="<?php echo $row->thumb_padding; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_border_width">Border width: </label></td>
                                <td>
                                    <input type="text" name="thumb_border_width" id="thumb_border_width"
                                           value="<?php echo $row->thumb_border_width; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_border_style">Border style: </label></td>
                                <td>
                                    <select name="thumb_border_style" id="thumb_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->thumb_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_border_color">Border color:</label></td>
                                <td>
                                    <input type="text" name="thumb_border_color" id="thumb_border_color"
                                           value="<?php echo $row->thumb_border_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_border_radius">Border radius: </label></td>
                                <td>
                                    <input type="text" name="thumb_border_radius" id="thumb_border_radius"
                                           value="<?php echo $row->thumb_border_radius; ?>" class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_box_shadow">Shadow: </label></td>
                                <td>
                                    <input type="text" name="thumb_box_shadow" id="thumb_box_shadow"
                                           value="<?php echo $row->thumb_box_shadow; ?>" class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_hover_effect">Hover effect: </label></td>
                                <td>
                                    <select name="thumb_hover_effect" id="thumb_hover_effect" class="spider_int_input">
                                        <?php
                                        foreach ($hover_effects as $key => $hover_effect) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->thumb_hover_effect == $key) ? 'selected="selected"' : ''); ?>><?php echo $hover_effect; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_hover_effect_value">Hover effect
                                        value: </label></td>
                                <td>
                                    <input type="text" name="thumb_hover_effect_value" id="thumb_hover_effect_value"
                                           value="<?php echo $row->thumb_hover_effect_value; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">E.g. Rotate: 10deg, Scale: 1.5, Skew: 10deg.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label>Transition: </label></td>
                                <td id="thumb_transition">
                                    <input type="radio" name="thumb_transition" id="thumb_transition1"
                                           value="1"<?php if ($row->thumb_transition == 1) echo 'checked="checked"'; ?> />
                                    <label for="thumb_transition1" id="thumb_transition1_lbl">Yes</label>
                                    <input type="radio" name="thumb_transition" id="thumb_transition0"
                                           value="0"<?php if ($row->thumb_transition == 0) echo 'checked="checked"'; ?> />
                                    <label for="thumb_transition0" id="thumb_transition0_lbl">No</label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Thumbnail_2">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label">
                                    <label for="thumb_bg_color">Thumbnail background color: </label>
                                </td>
                                <td>
                                    <input type="text" name="thumb_bg_color" id="thumb_bg_color"
                                           value="<?php echo $row->thumb_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_transparent">Thumbnail transparency: </label>
                                </td>
                                <td>
                                    <input type="text" name="thumb_transparent" id="thumb_transparent"
                                           value="<?php echo $row->thumb_transparent; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumbs_bg_color">Full background color: </label>
                                </td>
                                <td>
                                    <input type="text" name="thumbs_bg_color" id="thumbs_bg_color"
                                           value="<?php echo $row->thumbs_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_bg_transparent">Full background
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="thumb_bg_transparent" id="thumb_bg_transparent"
                                           value="<?php echo $row->thumb_bg_transparent; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_align">Alignment: </label></td>
                                <td>
                                    <select name="thumb_align" id="thumb_align">
                                        <?php
                                        foreach ($aligns as $key => $align) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->thumb_align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Thumbnail_3">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label>Title position: </label></td>
                                <td>
                                    <input type="radio" name="thumb_title_pos" id="thumb_title_pos1"
                                           value="top" <?php if ($row->thumb_title_pos == "top") echo 'checked="checked"'; ?> />
                                    <label for="thumb_title_pos1" id="thumb_title_pos1_lbl">Top</label>
                                    <input type="radio" name="thumb_title_pos" id="thumb_title_pos0"
                                           value="bottom" <?php if ($row->thumb_title_pos == "bottom") echo 'checked="checked"'; ?> />
                                    <label for="thumb_title_pos0" id="thumb_title_pos0_lbl">Bottom</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_title_font_size">Title font size: </label>
                                </td>
                                <td>
                                    <input type="text" name="thumb_title_font_size" id="thumb_title_font_size"
                                           value="<?php echo $row->thumb_title_font_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_title_font_color">Title font color: </label>
                                </td>
                                <td>
                                    <input type="text" name="thumb_title_font_color" id="thumb_title_font_color"
                                           value="<?php echo $row->thumb_title_font_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_title_font_style">Title font family: </label>
                                </td>
                                <td>
                                    <select name="thumb_title_font_style" id="thumb_title_font_style">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->thumb_title_font_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_title_font_weight">Title font
                                        weight: </label></td>
                                <td>
                                    <select name="thumb_title_font_weight" id="thumb_title_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->thumb_title_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_title_shadow">Title box shadow: </label></td>
                                <td>
                                    <input type="text" name="thumb_title_shadow" id="thumb_title_shadow"
                                           value="<?php echo $row->thumb_title_shadow; ?>" class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_title_margin">Title margin: </label></td>
                                <td>
                                    <input type="text" name="thumb_title_margin" id="thumb_title_margin"
                                           value="<?php echo $row->thumb_title_margin; ?>" class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>

                    <fieldset class="spider_child_fieldset" id="Thumbnail_4">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label>Like , comment position: </label></td>
                                <td>
                                    <input type="radio" name="thumb_like_comm_pos" id="thumb_like_comm_pos1"
                                           value="top" <?php if ($row->thumb_like_comm_pos == "top") echo 'checked="checked"'; ?> />
                                    <label for="thumb_like_comm_pos1" id="thumb_like_comm_pos1_lbl">Top</label>
                                    <input type="radio" name="thumb_like_comm_pos" id="thumb_like_comm_pos0"
                                           value="bottom" <?php if ($row->thumb_like_comm_pos == "bottom") echo 'checked="checked"'; ?> />
                                    <label for="thumb_like_comm_pos0" id="thumb_like_comm_pos0_lbl">Bottom</label>
                                </td>
                            </tr>
                            <!-- <tr>
                  <td class="spider_label"><label for="thumb_like_comm_c_bg_color">Like , comment count background color: </label></td>
                  <td>
                    <input type="text" name="thumb_like_comm_c_bg_color" id="thumb_like_comm_c_bg_color" value="" class="color" />
                  </td> -->
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_like_comm_font_size">Like , comment font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="thumb_like_comm_font_size" id="thumb_like_comm_font_size"
                                           value="<?php echo $row->thumb_like_comm_font_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_like_comm_font_color">Like , comment font
                                        color: </label></td>
                                <td>
                                    <input type="text" name="thumb_like_comm_font_color" id="thumb_like_comm_font_color"
                                           value="<?php echo $row->thumb_like_comm_font_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_like_comm_font_style">Like , comment font
                                        family: </label></td>
                                <td>
                                    <select name="thumb_like_comm_font_style" id="thumb_like_comm_font_style">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->thumb_like_comm_font_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_like_comm_font_weight">Like , comment font
                                        weight: </label></td>
                                <td>
                                    <select name="thumb_like_comm_font_weight" id="thumb_like_comm_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->thumb_like_comm_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="thumb_like_comm_shadow">Like , comment box
                                        shadow: </label></td>
                                <td>
                                    <input type="text" name="thumb_like_comm_shadow" id="thumb_like_comm_shadow"
                                           value="<?php echo $row->thumb_like_comm_shadow; ?>"
                                           class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>

                </fieldset>
                <fieldset class="spider_type_fieldset" id="Masonry">
                    <fieldset class="spider_child_fieldset" id="Masonry_1">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_padding">Padding: </label></td>
                                <td>
                                    <input type="text" name="masonry_thumb_padding" id="masonry_thumb_padding"
                                           value="<?php echo $row->masonry_thumb_padding; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_border_width">Border width: </label>
                                </td>
                                <td>
                                    <input type="text" name="masonry_thumb_border_width" id="masonry_thumb_border_width"
                                           value="<?php echo $row->masonry_thumb_border_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_border_style">Border style: </label>
                                </td>
                                <td>
                                    <select name="masonry_thumb_border_style" id="masonry_thumb_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->masonry_thumb_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_border_color">Border color: </label>
                                </td>
                                <td>
                                    <input type="text" name="masonry_thumb_border_color" id="masonry_thumb_border_color"
                                           value="<?php echo $row->masonry_thumb_border_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_border_radius">Border
                                        radius: </label></td>
                                <td>
                                    <input type="text" name="masonry_thumb_border_radius"
                                           id="masonry_thumb_border_radius"
                                           value="<?php echo $row->masonry_thumb_border_radius; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Masonry_2">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_transparent">Transparency: </label>
                                </td>
                                <td>
                                    <input type="text" name="masonry_thumb_transparent" id="masonry_thumb_transparent"
                                           value="<?php echo $row->masonry_thumb_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumbs_bg_color">Background color: </label>
                                </td>
                                <td>
                                    <input type="text" name="masonry_thumbs_bg_color" id="masonry_thumbs_bg_color"
                                           value="<?php echo $row->masonry_thumbs_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_bg_transparent">Background
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="masonry_thumb_bg_transparent"
                                           id="masonry_thumb_bg_transparent"
                                           value="<?php echo $row->masonry_thumb_bg_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_align0">Alignment: </label></td>
                                <td>
                                    <select name="masonry_thumb_align" id="masonry_thumb_align">
                                        <?php
                                        foreach ($aligns as $key => $align) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->masonry_thumb_align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Masonry_3">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_hover_effect">Hover effect: </label>
                                </td>
                                <td>
                                    <select name="masonry_thumb_hover_effect" id="masonry_thumb_hover_effect"
                                            class="spider_int_input">
                                        <?php
                                        foreach ($hover_effects as $key => $hover_effect) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->masonry_thumb_hover_effect == $key) ? 'selected="selected"' : ''); ?>><?php echo $hover_effect; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_thumb_hover_effect_value">Hover effect
                                        value: </label></td>
                                <td>
                                    <input type="text" name="masonry_thumb_hover_effect_value"
                                           id="masonry_thumb_hover_effect_value"
                                           value="<?php echo $row->masonry_thumb_hover_effect_value; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">E.g. Rotate: 10deg, Scale: 1.5, Skew: 10deg.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label>Transition: </label></td>
                                <td id="masonry_thumb_transition">
                                    <input type="radio" name="masonry_thumb_transition" id="masonry_thumb_transition1"
                                           value="1"<?php if ($row->masonry_thumb_transition == 1) echo 'checked="checked"'; ?> />
                                    <label for="masonry_thumb_transition1"
                                           id="masonry_thumb_transition1_lbl">Yes</label>
                                    <input type="radio" name="masonry_thumb_transition" id="masonry_thumb_transition0"
                                           value="0"<?php if ($row->masonry_thumb_transition == 0) echo 'checked="checked"'; ?> />
                                    <label for="masonry_thumb_transition0" id="masonry_thumb_transition0_lbl">No</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_description_font_size">Description font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="masonry_description_font_size"
                                           id="masonry_description_font_size"
                                           value="<?php echo $row->masonry_description_font_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_description_color">Description font
                                        color: </label></td>
                                <td>
                                    <input type="text" name="masonry_description_color" id="masonry_description_color"
                                           value="<?php echo $row->masonry_description_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_description_font_style">Description font
                                        family: </label></td>
                                <td>
                                    <select name="masonry_description_font_style" id="masonry_description_font_style">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->masonry_description_font_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Masonry_4">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label>Like , comment position: </label></td>
                                <td>
                                    <input type="radio" name="masonry_like_comm_pos" id="masonry_like_comm_pos1"
                                           value="top" <?php if ($row->masonry_like_comm_pos == "top") echo 'checked="checked"'; ?> />
                                    <label for="masonry_like_comm_pos1" id="masonry_like_comm_pos1_lbl">Top</label>
                                    <input type="radio" name="masonry_like_comm_pos" id="masonry_like_comm_pos0"
                                           value="bottom" <?php if ($row->masonry_like_comm_pos == "bottom") echo 'checked="checked"'; ?> />
                                    <label for="masonry_like_comm_pos0" id="masonry_like_comm_pos0_lbl">Bottom</label>
                                </td>
                            </tr>
                            <!-- <tr>
                  <td class="spider_label"><label for="masonry_like_comm_c_bg_color">Like , comment count background color: </label></td>
                  <td>
                    <input type="text" name="masonry_like_comm_c_bg_color" id="masonry_like_comm_c_bg_color" value="" class="color" />
                  </td> -->
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_like_comm_font_size">Like , comment font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="masonry_like_comm_font_size"
                                           id="masonry_like_comm_font_size"
                                           value="<?php echo $row->masonry_like_comm_font_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_like_comm_font_color">Like , comment font
                                        color: </label></td>
                                <td>
                                    <input type="text" name="masonry_like_comm_font_color"
                                           id="masonry_like_comm_font_color"
                                           value="<?php echo $row->masonry_like_comm_font_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_like_comm_font_style">Like , comment font
                                        family: </label></td>
                                <td>
                                    <select name="masonry_like_comm_font_style" id="masonry_like_comm_font_style">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->masonry_like_comm_font_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_like_comm_font_weight">Like , comment font
                                        weight: </label></td>
                                <td>
                                    <select name="masonry_like_comm_font_weight" id="masonry_like_comm_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->masonry_like_comm_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="masonry_like_comm_shadow">Like , comment box
                                        shadow: </label></td>
                                <td>
                                    <input type="text" name="masonry_like_comm_shadow" id="masonry_like_comm_shadow"
                                           value="<?php echo $row->masonry_like_comm_shadow; ?>"
                                           class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </fieldset>

                <fieldset class="spider_type_fieldset" id="Compact_album">
                    <fieldset class="spider_child_fieldset" id="Compact_album_1">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_padding">Padding: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_padding"
                                           id="album_compact_thumb_padding"
                                           value="<?php echo $row->album_compact_thumb_padding; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_margin">Margin: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_margin" id="album_compact_thumb_margin"
                                           value="<?php echo $row->album_compact_thumb_margin; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_border_width">Border
                                        width: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_border_width"
                                           id="album_compact_thumb_border_width"
                                           value="<?php echo $row->album_compact_thumb_border_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_border_style">Border
                                        style: </label></td>
                                <td>
                                    <select name="album_compact_thumb_border_style"
                                            id="album_compact_thumb_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->album_compact_thumb_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_border_color">Border
                                        color: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_border_color"
                                           id="album_compact_thumb_border_color"
                                           value="<?php echo $row->album_compact_thumb_border_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_border_radius">Border
                                        radius: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_border_radius"
                                           id="album_compact_thumb_border_radius"
                                           value="<?php echo $row->album_compact_thumb_border_radius; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_box_shadow">Shadow: </label>
                                </td>
                                <td>
                                    <input type="text" name="album_compact_thumb_box_shadow"
                                           id="album_compact_thumb_box_shadow"
                                           value="<?php echo $row->album_compact_thumb_box_shadow; ?>"
                                           class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_hover_effect">Hover
                                        effect: </label></td>
                                <td>
                                    <select name="album_compact_thumb_hover_effect"
                                            id="album_compact_thumb_hover_effect">
                                        <?php
                                        foreach ($hover_effects as $key => $hover_effect) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->album_compact_thumb_hover_effect == $key) ? 'selected="selected"' : ''); ?>><?php echo $hover_effect; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_hover_effect_value">Hover
                                        effect value: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_hover_effect_value"
                                           id="album_compact_thumb_hover_effect_value"
                                           value="<?php echo $row->album_compact_thumb_hover_effect_value; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">E.g. Rotate: 10deg, Scale: 1.5, Skew: 10deg.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label>Thumbnail transition: </label></td>
                                <td id="album_compact_thumb_transition">
                                    <input type="radio" name="album_compact_thumb_transition"
                                           id="album_compact_thumb_transition1"
                                           value="1"<?php if ($row->album_compact_thumb_transition == 1) echo 'checked="checked"'; ?> />
                                    <label for="album_compact_thumb_transition1"
                                           id="album_compact_thumb_transition1_lbl">Yes</label>
                                    <input type="radio" name="album_compact_thumb_transition"
                                           id="album_compact_thumb_transition0"
                                           value="0"<?php if ($row->album_compact_thumb_transition == 0) echo 'checked="checked"'; ?> />
                                    <label for="album_compact_thumb_transition0"
                                           id="album_compact_thumb_transition0_lbl">No</label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Compact_album_2">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_bg_color">Thumbnail background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_bg_color"
                                           id="album_compact_thumb_bg_color"
                                           value="<?php echo $row->album_compact_thumb_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_transparent">Thumbnail
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_transparent"
                                           id="album_compact_thumb_transparent"
                                           value="<?php echo $row->album_compact_thumb_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumbs_bg_color">Full background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumbs_bg_color"
                                           id="album_compact_thumbs_bg_color"
                                           value="<?php echo $row->album_compact_thumbs_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_bg_transparent">Full background
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="album_compact_thumb_bg_transparent"
                                           id="album_compact_thumb_bg_transparent"
                                           value="<?php echo $row->album_compact_thumb_bg_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_thumb_align0">Alignment: </label>
                                </td>
                                <td>
                                    <select name="album_compact_thumb_align" id="album_compact_thumb_align">
                                        <?php
                                        foreach ($aligns as $key => $align) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->album_compact_thumb_align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Compact_album_3">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label>Title position: </label></td>
                                <td>
                                    <input type="radio" name="album_compact_thumb_title_pos"
                                           id="album_compact_thumb_title_pos1"
                                           value="top" <?php if ($row->album_compact_thumb_title_pos == "top") echo 'checked="checked"'; ?> />
                                    <label for="album_compact_thumb_title_pos1" id="album_compact_thumb_title_pos1_lbl">Top</label>
                                    <input type="radio" name="album_compact_thumb_title_pos"
                                           id="album_compact_thumb_title_pos0"
                                           value="bottom" <?php if ($row->album_compact_thumb_title_pos == "bottom") echo 'checked="checked"'; ?> />
                                    <label for="album_compact_thumb_title_pos0" id="album_compact_thumb_title_pos0_lbl">Bottom</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_title_font_size">Title font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="album_compact_title_font_size"
                                           id="album_compact_title_font_size"
                                           value="<?php echo $row->album_compact_title_font_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_title_font_color">Title font
                                        color: </label></td>
                                <td>
                                    <input type="text" name="album_compact_title_font_color"
                                           id="album_compact_title_font_color"
                                           value="<?php echo $row->album_compact_title_font_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_title_font_style">Title font
                                        family: </label></td>
                                <td>
                                    <select name="album_compact_title_font_style" id="album_compact_title_font_style">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->album_compact_title_font_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_title_font_weight">Title font
                                        weight: </label></td>
                                <td>
                                    <select name="album_compact_title_font_weight" id="album_compact_title_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->album_compact_title_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_title_shadow">Title box
                                        shadow: </label></td>
                                <td>
                                    <input type="text" name="album_compact_title_shadow" id="album_compact_title_shadow"
                                           value="<?php echo $row->album_compact_title_shadow; ?>"
                                           class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_title_margin">Title margin: </label>
                                </td>
                                <td>
                                    <input type="text" name="album_compact_title_margin" id="album_compact_title_margin"
                                           value="<?php echo $row->album_compact_title_margin; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_back_font_size">Back button Font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="album_compact_back_font_size"
                                           id="album_compact_back_font_size"
                                           value="<?php echo $row->album_compact_back_font_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_back_font_color">Back button Font
                                        color: </label></td>
                                <td>
                                    <input type="text" name="album_compact_back_font_color"
                                           id="album_compact_back_font_color"
                                           value="<?php echo $row->album_compact_back_font_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_back_font_style">Back button Font
                                        family: </label></td>
                                <td>
                                    <select name="album_compact_back_font_style" id="album_compact_back_font_style">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->album_compact_back_font_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_back_font_weight">Back button Font
                                        weight: </label></td>
                                <td>
                                    <select name="album_compact_back_font_weight" id="album_compact_back_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->album_compact_back_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="album_compact_back_padding">Back button
                                        padding: </label></td>
                                <td>
                                    <input type="text" name="album_compact_back_padding" id="album_compact_back_padding"
                                           value="<?php echo $row->album_compact_back_padding; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </fieldset>

                <fieldset class="spider_type_fieldset" id="Blog_style">
                    <fieldset class="spider_child_fieldset" id="Blog_style_1">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="blog_style_align">Alignment: </label></td>
                                <td>
                                    <select name="blog_style_align" id="blog_style_align">
                                        <?php
                                        foreach ($aligns as $key => $align) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_bg_color">Background color: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_bg_color" id="blog_style_bg_color"
                                           value="<?php echo $row->blog_style_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_transparent">Background
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="blog_style_transparent" id="blog_style_transparent"
                                           value="<?php echo $row->blog_style_transparent; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>

                            <tr>
                                <td class="spider_label"><label for="blog_style_fd_name_bg_color">Feed name background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_fd_name_bg_color"
                                           id="blog_style_fd_name_bg_color"
                                           value="<?php echo $row->blog_style_fd_name_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_fd_name_align">Feed name
                                        alignment: </label></td>
                                <td>
                                    <select name="blog_style_fd_name_align" id="blog_style_fd_name_align">
                                        <?php
                                        foreach ($aligns as $key => $align) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_fd_name_align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_fd_name_padding">Feed name
                                        padding: </label></td>
                                <td>
                                    <input type="text" name="blog_style_fd_name_padding" id="blog_style_fd_name_padding"
                                           value="<?php echo $row->blog_style_fd_name_padding; ?>"
                                           class="spider_char_input"/>
                                    <div class="blog_style_fd_name_padding">Use CSS type values.</div>
                                </td>
                            </tr>

                            <tr>
                                <td class="spider_label"><label for="blog_style_fd_name_color">Feed name color: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_fd_name_color" id="blog_style_fd_name_color"
                                           value="<?php echo $row->blog_style_fd_name_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_fd_name_size">Feed name font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_fd_name_size" id="blog_style_fd_name_size"
                                           value="<?php echo $row->blog_style_fd_name_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_fd_name_font_weight">Feed name font
                                        weight: </label></td>
                                <td>
                                    <select name="blog_style_fd_name_font_weight" id="blog_style_fd_name_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_fd_name_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>


                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_img_align">Image(video)
                                        alignment: </label></td>
                                <td>
                                    <select name="blog_style_obj_img_align" id="blog_style_obj_img_align">
                                        <?php
                                        foreach ($aligns as $key => $align) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_img_align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_margin">Margin: </label></td>
                                <td>
                                    <input type="text" name="blog_style_margin" id="blog_style_margin"
                                           value="<?php echo $row->blog_style_margin; ?>" class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_box_shadow">Box shadow: </label></td>
                                <td>
                                    <input type="text" name="blog_style_box_shadow" id="blog_style_box_shadow"
                                           value="<?php echo $row->blog_style_box_shadow; ?>" class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_border_width">Border width: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_border_width" id="blog_style_border_width"
                                           value="<?php echo $row->blog_style_border_width; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_border_style">Border style: </label>
                                </td>
                                <td>
                                    <select name="blog_style_border_style" id="blog_style_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_border_color">Border color: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_border_color" id="blog_style_border_color"
                                           value="<?php echo $row->blog_style_border_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_border_type">Border type: </label></td>
                                <td>
                                    <select name="blog_style_border_type" id="blog_style_border_type">
                                        <option
                                            value="all" <?php echo(($row->blog_style_border_type == "all") ? 'selected="selected"' : ''); ?>>
                                            All
                                        </option>
                                        <option
                                            value="top" <?php echo(($row->blog_style_border_type == "top") ? 'selected="selected"' : ''); ?>>
                                            Top
                                        </option>
                                        <option
                                            value="right" <?php echo(($row->blog_style_border_type == "right") ? 'selected="selected"' : ''); ?>>
                                            Right
                                        </option>
                                        <option
                                            value="bottom" <?php echo(($row->blog_style_border_type == "bottom") ? 'selected="selected"' : ''); ?>>
                                            Bottom
                                        </option>
                                        <option
                                            value="left" <?php echo(($row->blog_style_border_type == "left") ? 'selected="selected"' : ''); ?>>
                                            Left
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_border_radius">Border radius: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_border_radius" id="blog_style_border_radius"
                                           value="<?php echo $row->blog_style_border_radius; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_icons_color">Icons color: </label>
                                </td>
                                <td>
                                    <select name="blog_style_obj_icons_color" id="blog_style_obj_icons_color">
                                        <option
                                            value="gray" <?php echo(($row->blog_style_obj_icons_color == "gray") ? 'selected="selected"' : ''); ?>>
                                            Gray
                                        </option>
                                        <option
                                            value="white" <?php echo(($row->blog_style_obj_icons_color == "white") ? 'selected="selected"' : ''); ?>>
                                            White
                                        </option>
                                        <option
                                            value="blue" <?php echo(($row->blog_style_obj_icons_color == "blue") ? 'selected="selected"' : ''); ?>>
                                            Blue
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_date_pos">Date position: </label>
                                </td>
                                <td>
                                    <select name="blog_style_obj_date_pos" id="blog_style_obj_date_pos">
                                        <option
                                            value="before" <?php echo(($row->blog_style_obj_date_pos == "before") ? 'selected="selected"' : ''); ?>>
                                            Before post author
                                        </option>
                                        <option
                                            value="after" <?php echo(($row->blog_style_obj_date_pos == "after") ? 'selected="selected"' : ''); ?>>
                                            After post author
                                        </option>
                                        <option
                                            value="bottom" <?php echo(($row->blog_style_obj_date_pos == "bottom") ? 'selected="selected"' : ''); ?>>
                                            At the bottom of the post
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_font_family">Font family: </label>
                                </td>
                                <td>
                                    <select name="blog_style_obj_font_family" id="blog_style_obj_font_family">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_font_family == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Blog_style_2">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_info_bg_color">Info background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_info_bg_color"
                                           id="blog_style_obj_info_bg_color"
                                           value="<?php echo $row->blog_style_obj_info_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_page_name_color">Page(group, profile)
                                        name color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_page_name_color" id="blog_style_page_name_color"
                                           value="<?php echo $row->blog_style_page_name_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_page_name_size">Page(group, profile)
                                        name font size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_page_name_size"
                                           id="blog_style_obj_page_name_size"
                                           value="<?php echo $row->blog_style_obj_page_name_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_page_name_font_weight">Page(group,
                                        profile) name font weight: </label></td>
                                <td>
                                    <select name="blog_style_obj_page_name_font_weight"
                                            id="blog_style_obj_page_name_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_page_name_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_story_color">Story color: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_obj_story_color" id="blog_style_obj_story_color"
                                           value="<?php echo $row->blog_style_obj_story_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_story_size">Story font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_story_size" id="blog_style_obj_story_size"
                                           value="<?php echo $row->blog_style_obj_story_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_story_font_weight">Story font
                                        weight: </label></td>
                                <td>
                                    <select name="blog_style_obj_story_font_weight"
                                            id="blog_style_obj_story_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_story_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_place_color">Place color: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_obj_place_color" id="blog_style_obj_place_color"
                                           value="<?php echo $row->blog_style_obj_place_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_place_size">Place font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_place_size" id="blog_style_obj_place_size"
                                           value="<?php echo $row->blog_style_obj_place_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_place_font_weight">Place font
                                        weight: </label></td>
                                <td>
                                    <select name="blog_style_obj_place_font_weight"
                                            id="blog_style_obj_place_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_place_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_name_color">Name color: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_obj_name_color" id="blog_style_obj_name_color"
                                           value="<?php echo $row->blog_style_obj_name_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_name_size">Name font size: </label>
                                </td>
                                <td>
                                    <input type="text" name="blog_style_obj_name_size" id="blog_style_obj_name_size"
                                           value="<?php echo $row->blog_style_obj_name_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_name_font_weight">Name font
                                        weight: </label></td>
                                <td>
                                    <select name="blog_style_obj_name_font_weight" id="blog_style_obj_name_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_name_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_str_color">Event place(street)
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_evt_str_color" id="blog_style_evt_str_color"
                                           value="<?php echo $row->blog_style_evt_str_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_str_size">Event place(street) font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_evt_str_size" id="blog_style_evt_str_size"
                                           value="<?php echo $row->blog_style_evt_str_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_str_font_weight">Event place(street)
                                        font weight: </label></td>
                                <td>
                                    <select name="blog_style_evt_str_font_weight" id="blog_style_evt_str_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_evt_str_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_ctzpcn_color">Event place(city, zip,
                                        country) color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_evt_ctzpcn_color"
                                           id="blog_style_evt_ctzpcn_color"
                                           value="<?php echo $row->blog_style_evt_ctzpcn_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_ctzpcn_size">Event place(city, zip,
                                        country) font size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_evt_ctzpcn_size" id="blog_style_evt_ctzpcn_size"
                                           value="<?php echo $row->blog_style_evt_ctzpcn_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_ctzpcn_font_weight">Event
                                        place(city, zip, country) font weight: </label></td>
                                <td>
                                    <select name="blog_style_evt_ctzpcn_font_weight"
                                            id="blog_style_evt_ctzpcn_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_evt_ctzpcn_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_map_color">Event place(map)
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_evt_map_color" id="blog_style_evt_map_color"
                                           value="<?php echo $row->blog_style_evt_map_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_map_size">Event place(map) font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_evt_map_size" id="blog_style_evt_map_size"
                                           value="<?php echo $row->blog_style_evt_map_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_map_font_weight">Event place(map)
                                        font weight: </label></td>
                                <td>
                                    <select name="blog_style_evt_map_font_weight" id="blog_style_evt_map_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_evt_map_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_date_color">Event date
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_evt_date_color" id="blog_style_evt_date_color"
                                           value="<?php echo $row->blog_style_evt_date_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_date_size">Event date font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_evt_date_size" id="blog_style_evt_date_size"
                                           value="<?php echo $row->blog_style_evt_date_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_date_font_weight">Event date font
                                        weight: </label></td>
                                <td>
                                    <select name="blog_style_evt_date_font_weight" id="blog_style_evt_date_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_evt_date_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_evt_info_font_family">Event font
                                        family: </label></td>
                                <td>
                                    <select name="blog_style_evt_info_font_family" id="blog_style_evt_info_font_family">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_evt_info_font_family == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Blog_style_3">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_message_color">Message and
                                        description color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_message_color"
                                           id="blog_style_obj_message_color"
                                           value="<?php echo $row->blog_style_obj_message_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_message_size">Message and
                                        description font size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_message_size"
                                           id="blog_style_obj_message_size"
                                           value="<?php echo $row->blog_style_obj_message_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_message_font_weight">Message and
                                        description font weight: </label></td>
                                <td>
                                    <select name="blog_style_obj_message_font_weight"
                                            id="blog_style_obj_message_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_message_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_hashtags_color">Hashtags
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_hashtags_color"
                                           id="blog_style_obj_hashtags_color"
                                           value="<?php echo $row->blog_style_obj_hashtags_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_hashtags_size">Hashtags font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_hashtags_size"
                                           id="blog_style_obj_hashtags_size"
                                           value="<?php echo $row->blog_style_obj_hashtags_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_hashtags_font_weight">Hashtags font
                                        weight: </label></td>
                                <td>
                                    <select name="blog_style_obj_hashtags_font_weight"
                                            id="blog_style_obj_hashtags_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_hashtags_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_likes_social_bg_color">Likes(share,
                                        comment) and social buttons background color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_likes_social_bg_color"
                                           id="blog_style_obj_likes_social_bg_color"
                                           value="<?php echo $row->blog_style_obj_likes_social_bg_color; ?>"
                                           class="color"/>
                                </td>
                            </tr>

                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_likes_social_color">Likes(share,
                                        comment) color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_likes_social_color"
                                           id="blog_style_obj_likes_social_color"
                                           value="<?php echo $row->blog_style_obj_likes_social_color; ?>"
                                           class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_likes_social_size">Likes(share,
                                        comment) and social size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_likes_social_size"
                                           id="blog_style_obj_likes_social_size"
                                           value="<?php echo $row->blog_style_obj_likes_social_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_likes_social_font_weight">Likes(share,
                                        comment) and social font weight: </label></td>
                                <td>
                                    <select name="blog_style_obj_likes_social_font_weight"
                                            id="blog_style_obj_likes_social_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_likes_social_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Blog_style_4">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comments_bg_color">Comments
                                        background color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_comments_bg_color"
                                           id="blog_style_obj_comments_bg_color"
                                           value="<?php echo $row->blog_style_obj_comments_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comments_color">Comments
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_comments_color"
                                           id="blog_style_obj_comments_color"
                                           value="<?php echo $row->blog_style_obj_comments_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comments_font_family">Comments font
                                        family: </label></td>
                                <td>
                                    <select name="blog_style_obj_comments_font_family"
                                            id="blog_style_obj_comments_font_family">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_comments_font_family == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comments_font_size">Comments font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_comments_font_size"
                                           id="blog_style_obj_comments_font_size"
                                           value="<?php echo $row->blog_style_obj_comments_font_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_users_font_color">Users
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_users_font_color"
                                           id="blog_style_obj_users_font_color"
                                           value="<?php echo $row->blog_style_obj_users_font_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comments_social_font_weight">Comments
                                        font weight: </label></td>
                                <td>
                                    <select name="blog_style_obj_comments_social_font_weight"
                                            id="blog_style_obj_comments_social_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_comments_social_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comment_border_width">Comment border
                                        width: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_comment_border_width"
                                           id="blog_style_obj_comment_border_width"
                                           value="<?php echo $row->blog_style_obj_comment_border_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comment_border_style">Comment border
                                        style: </label></td>
                                <td>
                                    <select name="blog_style_obj_comment_border_style"
                                            id="blog_style_obj_comment_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->blog_style_obj_comment_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comment_border_color">Comment border
                                        color: </label></td>
                                <td>
                                    <input type="text" name="blog_style_obj_comment_border_color"
                                           id="blog_style_obj_comment_border_color"
                                           value="<?php echo $row->blog_style_obj_comment_border_color; ?>"
                                           class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="blog_style_obj_comment_border_type">Comment border
                                        type: </label></td>
                                <td>
                                    <select name="blog_style_obj_comment_border_type" id="blog_style_border_type">
                                        <option
                                            value="all" <?php echo(($row->blog_style_obj_comment_border_type == "all") ? 'selected="selected"' : ''); ?>>
                                            All
                                        </option>
                                        <option
                                            value="top" <?php echo(($row->blog_style_obj_comment_border_type == "top") ? 'selected="selected"' : ''); ?>>
                                            Top
                                        </option>
                                        <option
                                            value="right" <?php echo(($row->blog_style_obj_comment_border_type == "right") ? 'selected="selected"' : ''); ?>>
                                            Right
                                        </option>
                                        <option
                                            value="bottom" <?php echo(($row->blog_style_obj_comment_border_type == "bottom") ? 'selected="selected"' : ''); ?>>
                                            Bottom
                                        </option>
                                        <option
                                            value="left" <?php echo(($row->blog_style_obj_comment_border_type == "left") ? 'selected="selected"' : ''); ?>>
                                            Left
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </fieldset>
                <fieldset class="spider_type_fieldset" id="Lightbox">
                    <fieldset class="spider_child_fieldset" id="Lightbox_1">
                        <table style="clear:both;">
                            <tbody>
                            <tr id="lightbox_overlay_bg">
                                <td class="spider_label"><label for="lightbox_overlay_bg_color">Overlay background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_overlay_bg_color" id="lightbox_overlay_bg_color"
                                           value="<?php echo $row->lightbox_overlay_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr id="lightbox_overlay">
                                <td class="spider_label"><label for="lightbox_overlay_bg_transparent">Overlay background
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="lightbox_overlay_bg_transparent"
                                           id="lightbox_overlay_bg_transparent"
                                           value="<?php echo $row->lightbox_overlay_bg_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_bg">
                                <td class="spider_label"><label for="lightbox_bg_color">Lightbox background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_bg_color" id="lightbox_bg_color"
                                           value="<?php echo $row->lightbox_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr id="lightbox_cntrl1">
                                <td class="spider_label"><label for="lightbox_ctrl_btn_height">Control buttons
                                        height: </label></td>
                                <td>
                                    <input type="text" name="lightbox_ctrl_btn_height" id="lightbox_ctrl_btn_height"
                                           value="<?php echo $row->lightbox_ctrl_btn_height; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_cntrl2">
                                <td class="spider_label"><label for="lightbox_ctrl_btn_margin_top">Control buttons
                                        margin (top): </label></td>
                                <td>
                                    <input type="text" name="lightbox_ctrl_btn_margin_top"
                                           id="lightbox_ctrl_btn_margin_top"
                                           value="<?php echo $row->lightbox_ctrl_btn_margin_top; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_cntrl3">
                                <td class="spider_label"><label for="lightbox_ctrl_btn_margin_left">Control buttons
                                        margin (left): </label></td>
                                <td>
                                    <input type="text" name="lightbox_ctrl_btn_margin_left"
                                           id="lightbox_ctrl_btn_margin_left"
                                           value="<?php echo $row->lightbox_ctrl_btn_margin_left; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_cntrl9">
                                <td class="spider_label"><label>Control buttons position: </label></td>
                                <td>
                                    <input type="radio" name="lightbox_ctrl_btn_pos" id="lightbox_ctrl_btn_pos1"
                                           value="top"<?php if ($row->lightbox_ctrl_btn_pos == "top") echo 'checked="checked"'; ?> />
                                    <label for="lightbox_ctrl_btn_pos1" id="lightbox_ctrl_btn_pos1_lbl">Top</label>
                                    <input type="radio" name="lightbox_ctrl_btn_pos" id="lightbox_ctrl_btn_pos0"
                                           value="bottom"<?php if ($row->lightbox_ctrl_btn_pos == "bottom") echo 'checked="checked"'; ?> />
                                    <label for="lightbox_ctrl_btn_pos0" id="lightbox_ctrl_btn_pos0_lbl">Bottom</label>
                                </td>
                            </tr>
                            <tr id="lightbox_cntrl8">
                                <td class="spider_label"><label for="lightbox_ctrl_cont_bg_color">Control buttons
                                        background color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_ctrl_cont_bg_color"
                                           id="lightbox_ctrl_cont_bg_color"
                                           value="<?php echo $row->lightbox_ctrl_cont_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr id="lightbox_cntrl5">
                                <td class="spider_label"><label for="lightbox_ctrl_cont_border_radius">Control buttons
                                        container border radius: </label></td>
                                <td>
                                    <input type="text" name="lightbox_ctrl_cont_border_radius"
                                           id="lightbox_ctrl_cont_border_radius"
                                           value="<?php echo $row->lightbox_ctrl_cont_border_radius; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_cntrl6">
                                <td class="spider_label"><label for="lightbox_ctrl_cont_transparent">Control buttons
                                        container background transparency: </label></td>
                                <td>
                                    <input type="text" name="lightbox_ctrl_cont_transparent"
                                           id="lightbox_ctrl_cont_transparent"
                                           value="<?php echo $row->lightbox_ctrl_cont_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_cntrl10">
                                <td class="spider_label"><label for="lightbox_ctrl_btn_align0">Control buttons
                                        alignment: </label></td>
                                <td>
                                    <select name="lightbox_ctrl_btn_align" id="lightbox_ctrl_btn_align">
                                        <?php
                                        foreach ($aligns as $key => $align) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_ctrl_btn_align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <!-- <tr id="lightbox_cntrl7">
                  <td class="spider_label"><label for="lightbox_ctrl_btn_color">Control buttons color: </label></td>
                  <td>
                    <input type="text" name="lightbox_ctrl_btn_color" id="lightbox_ctrl_btn_color" value="<?php echo $row->lightbox_ctrl_btn_color; ?>" class="color"/>
                  </td>
                </tr> -->
                            <tr id="lightbox_cntrl4">
                                <td class="spider_label"><label for="lightbox_ctrl_btn_transparent">Control buttons
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="lightbox_ctrl_btn_transparent"
                                           id="lightbox_ctrl_btn_transparent"
                                           value="<?php echo $row->lightbox_ctrl_btn_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_toggle1">
                                <td class="spider_label"><label for="lightbox_toggle_btn_height">Toggle button
                                        height: </label></td>
                                <td>
                                    <input type="text" name="lightbox_toggle_btn_height" id="lightbox_toggle_btn_height"
                                           value="<?php echo $row->lightbox_toggle_btn_height; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_toggle2">
                                <td class="spider_label"><label for="lightbox_toggle_btn_width">Toggle button
                                        width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_toggle_btn_width" id="lightbox_toggle_btn_width"
                                           value="<?php echo $row->lightbox_toggle_btn_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_close1">
                                <td class="spider_label"><label for="lightbox_close_btn_border_radius">Close button
                                        border radius: </label>
                                </td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_border_radius"
                                           id="lightbox_close_btn_border_radius"
                                           value="<?php echo $row->lightbox_close_btn_border_radius; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_close2">
                                <td class="spider_label"><label for="lightbox_close_btn_border_width">Close button
                                        border width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_border_width"
                                           id="lightbox_close_btn_border_width"
                                           value="<?php echo $row->lightbox_close_btn_border_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_close12">
                                <td class="spider_label"><label for="lightbox_close_btn_border_style">Close button
                                        border style: </label></td>
                                <td>
                                    <select name="lightbox_close_btn_border_style" id="lightbox_close_btn_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_close_btn_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="lightbox_close13">
                                <td class="spider_label"><label for="lightbox_close_btn_border_color">Close button
                                        border color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_border_color"
                                           id="lightbox_close_btn_border_color"
                                           value="<?php echo $row->lightbox_close_btn_border_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr id="lightbox_close3">
                                <td class="spider_label"><label for="lightbox_close_btn_box_shadow">Close button box
                                        shadow: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_box_shadow"
                                           id="lightbox_close_btn_box_shadow"
                                           value="<?php echo $row->lightbox_close_btn_box_shadow; ?>"
                                           class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_close11">
                                <td class="spider_label"><label for="lightbox_close_btn_bg_color">Close button
                                        background color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_bg_color"
                                           id="lightbox_close_btn_bg_color"
                                           value="<?php echo $row->lightbox_close_btn_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr id="lightbox_close9">
                                <td class="spider_label"><label for="lightbox_close_btn_transparent">Close button
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_transparent"
                                           id="lightbox_close_btn_transparent"
                                           value="<?php echo $row->lightbox_close_btn_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                </td>
                            </tr>
                            <tr id="lightbox_close5">
                                <td class="spider_label"><label for="lightbox_close_btn_width">Close button
                                        width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_width" id="lightbox_close_btn_width"
                                           value="<?php echo $row->lightbox_close_btn_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_close6">
                                <td class="spider_label"><label for="lightbox_close_btn_height">Close button
                                        height: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_height" id="lightbox_close_btn_height"
                                           value="<?php echo $row->lightbox_close_btn_height; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_close7">
                                <td class="spider_label"><label for="lightbox_close_btn_top">Close button top: </label>
                                </td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_top" id="lightbox_close_btn_top"
                                           value="<?php echo $row->lightbox_close_btn_top; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_close8">
                                <td class="spider_label"><label for="lightbox_close_btn_right">Close button
                                        right: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_right" id="lightbox_close_btn_right"
                                           value="<?php echo $row->lightbox_close_btn_right; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_close4">
                                <td class="spider_label"><label for="lightbox_close_btn_size">Close button
                                        size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_close_btn_size" id="lightbox_close_btn_size"
                                           value="<?php echo $row->lightbox_close_btn_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <!-- <tr id="lightbox_close14">
                  <td class="spider_label"><label for="lightbox_close_btn_color">Close button color: </label></td>
                  <td>
                    <input type="text" name="lightbox_close_btn_color" id="lightbox_close_btn_color" value="<?php echo $row->lightbox_close_btn_color; ?>" class="color"/>
                  </td>
                </tr>
                <tr id="lightbox_close10">
                  <td class="spider_label"><label for="lightbox_close_btn_full_color">Fullscreen close button color: </label></td>
                  <td>
                    <input type="text" name="lightbox_close_btn_full_color" id="lightbox_close_btn_full_color" value="<?php echo $row->lightbox_close_btn_full_color; ?>" class="color"/>
                  </td>
                </tr> -->
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Lightbox_2">
                        <table style="clear:both;">
                            <tbody>
                            <!-- <tr id="lightbox_right_left11">
                  <td class="spider_label"><label for="lightbox_rl_btn_style">Right, left buttons style: </label></td>
                  <td>
                    <select name="lightbox_rl_btn_style" id="lightbox_rl_btn_style" class="spider_int_input">
                      <?php
                            foreach ($button_styles as $key => $button_style) {
                                ?>
                        <option value="<?php echo $key; ?>" <?php echo(($row->lightbox_rl_btn_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $button_style; ?></option>
                        <?php
                            }
                            ?>
                    </select>
                  </td>
                </tr> -->
                            <tr id="lightbox_right_left7">
                                <td class="spider_label"><label for="lightbox_rl_btn_bg_color">Right, left buttons
                                        background color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_bg_color" id="lightbox_rl_btn_bg_color"
                                           value="<?php echo $row->lightbox_rl_btn_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_rl_btn_transparent">Right, left buttons
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_transparent"
                                           id="lightbox_rl_btn_transparent"
                                           value="<?php echo $row->lightbox_rl_btn_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                </td>
                            </tr>
                            <tr id="lightbox_right_left3">
                                <td class="spider_label"><label for="lightbox_rl_btn_box_shadow">Right, left buttons box
                                        shadow: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_box_shadow" id="lightbox_rl_btn_box_shadow"
                                           value="<?php echo $row->lightbox_rl_btn_box_shadow; ?>"
                                           class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_right_left4">
                                <td class="spider_label"><label for="lightbox_rl_btn_height">Right, left buttons
                                        height: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_height" id="lightbox_rl_btn_height"
                                           value="<?php echo $row->lightbox_rl_btn_height; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_right_left5">
                                <td class="spider_label"><label for="lightbox_rl_btn_width">Right, left buttons
                                        width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_width" id="lightbox_rl_btn_width"
                                           value="<?php echo $row->lightbox_rl_btn_width; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_right_left6">
                                <td class="spider_label"><label for="lightbox_rl_btn_size">Right, left buttons
                                        size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_size" id="lightbox_rl_btn_size"
                                           value="<?php echo $row->lightbox_rl_btn_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <!-- <tr id="lightbox_close15">
                  <td class="spider_label"><label for="lightbox_close_rl_btn_hover_color">Right, left, close buttons hover color: </label></td>
                  <td>
                    <input type="text" name="lightbox_close_rl_btn_hover_color" id="lightbox_close_rl_btn_hover_color" value="<?php echo $row->lightbox_close_rl_btn_hover_color; ?>" class="color" />
                  </td>
                </tr>
                <tr id="lightbox_right_left10">
                  <td class="spider_label"><label for="lightbox_rl_btn_color">Right, left buttons color: </label></td>
                  <td>
                    <input type="text" name="lightbox_rl_btn_color" id="lightbox_rl_btn_color" value="<?php echo $row->lightbox_rl_btn_color; ?>" class="color"/>
                  </td>
                </tr> -->
                            <tr id="lightbox_right_left1">
                                <td class="spider_label"><label for="lightbox_rl_btn_border_radius">Right, left buttons
                                        border radius: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_border_radius"
                                           id="lightbox_rl_btn_border_radius"
                                           value="<?php echo $row->lightbox_rl_btn_border_radius; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_right_left2">
                                <td class="spider_label"><label for="lightbox_rl_btn_border_width">Right, left buttons
                                        border width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_border_width"
                                           id="lightbox_rl_btn_border_width"
                                           value="<?php echo $row->lightbox_rl_btn_border_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_right_left8">
                                <td class="spider_label"><label for="lightbox_rl_btn_border_style">Right, left buttons
                                        border style: </label></td>
                                <td>
                                    <select name="lightbox_rl_btn_border_style" id="lightbox_rl_btn_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_rl_btn_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="lightbox_right_left9">
                                <td class="spider_label"><label for="lightbox_rl_btn_border_color">Right, left buttons
                                        border color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_rl_btn_border_color"
                                           id="lightbox_rl_btn_border_color"
                                           value="<?php echo $row->lightbox_rl_btn_border_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip12">
                                <td class="spider_label"><label>Filmstrip position: </label></td>
                                <td>
                                    <select name="lightbox_filmstrip_pos" id="lightbox_filmstrip_pos">
                                        <option
                                            value="top" <?php echo(($row->lightbox_filmstrip_pos == "top") ? 'selected="selected"' : ''); ?>>
                                            Top
                                        </option>
                                        <option
                                            value="right" <?php echo(($row->lightbox_filmstrip_pos == "right") ? 'selected="selected"' : ''); ?>>
                                            Right
                                        </option>
                                        <option
                                            value="bottom" <?php echo(($row->lightbox_filmstrip_pos == "bottom") ? 'selected="selected"' : ''); ?>>
                                            Bottom
                                        </option>
                                        <option
                                            value="left" <?php echo(($row->lightbox_filmstrip_pos == "left") ? 'selected="selected"' : ''); ?>>
                                            Left
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip2">
                                <td class="spider_label"><label for="lightbox_filmstrip_thumb_margin">Filmstrip
                                        thumbnail margin: </label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_thumb_margin"
                                           id="lightbox_filmstrip_thumb_margin"
                                           value="<?php echo $row->lightbox_filmstrip_thumb_margin; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip3">
                                <td class="spider_label"><label for="lightbox_filmstrip_thumb_border_width">Filmstrip
                                        thumbnail border width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_thumb_border_width"
                                           id="lightbox_filmstrip_thumb_border_width"
                                           value="<?php echo $row->lightbox_filmstrip_thumb_border_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip9">
                                <td class="spider_label"><label for="lightbox_filmstrip_thumb_border_style">Filmstrip
                                        thumbnail border style: </label></td>
                                <td>
                                    <select name="lightbox_filmstrip_thumb_border_style"
                                            id="lightbox_filmstrip_thumb_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_filmstrip_thumb_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip10">
                                <td class="spider_label"><label for="lightbox_filmstrip_thumb_border_color">Filmstrip
                                        thumbnail border color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_thumb_border_color"
                                           id="lightbox_filmstrip_thumb_border_color"
                                           value="<?php echo $row->lightbox_filmstrip_thumb_border_color; ?>"
                                           class="color"/>
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip4">
                                <td class="spider_label"><label for="lightbox_filmstrip_thumb_border_radius">Filmstrip
                                        thumbnail border radius: </label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_thumb_border_radius"
                                           id="lightbox_filmstrip_thumb_border_radius"
                                           value="<?php echo $row->lightbox_filmstrip_thumb_border_radius; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip6">
                                <td class="spider_label"><label for="lightbox_filmstrip_thumb_active_border_width">Filmstrip
                                        thumbnail active border width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_thumb_active_border_width"
                                           id="lightbox_filmstrip_thumb_active_border_width"
                                           value="<?php echo $row->lightbox_filmstrip_thumb_active_border_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip11">
                                <td class="spider_label"><label for="lightbox_filmstrip_thumb_active_border_color">Filmstrip
                                        thumbnail active border color:</label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_thumb_active_border_color"
                                           id="lightbox_filmstrip_thumb_active_border_color"
                                           value="<?php echo $row->lightbox_filmstrip_thumb_active_border_color; ?>"
                                           class="color"/>
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip5">
                                <td class="spider_label"><label for="lightbox_filmstrip_thumb_deactive_transparent">Filmstrip
                                        thumbnail deactive transparency: </label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_thumb_deactive_transparent"
                                           id="lightbox_filmstrip_thumb_deactive_transparent"
                                           value="<?php echo $row->lightbox_filmstrip_thumb_deactive_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr id="lightbox_filmstrip1">
                                <td class="spider_label"><label for="lightbox_filmstrip_rl_btn_size">Filmstrip right,
                                        left buttons size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_rl_btn_size"
                                           id="lightbox_filmstrip_rl_btn_size"
                                           value="<?php echo $row->lightbox_filmstrip_rl_btn_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <!-- <tr id="lightbox_filmstrip7">
                  <td class="spider_label"><label for="lightbox_filmstrip_rl_btn_color">Filmstrip right, left buttons color: </label></td>
                  <td>
                    <input type="text" name="lightbox_filmstrip_rl_btn_color" id="lightbox_filmstrip_rl_btn_color" value="<?php echo $row->lightbox_filmstrip_rl_btn_color; ?>" class="color"/>
                  </td>
                </tr> -->
                            <tr id="lightbox_filmstrip8">
                                <td class="spider_label"><label for="lightbox_filmstrip_rl_bg_color">Filmstrip right,
                                        left button background color:</label></td>
                                <td>
                                    <input type="text" name="lightbox_filmstrip_rl_bg_color"
                                           id="lightbox_filmstrip_rl_bg_color"
                                           value="<?php echo $row->lightbox_filmstrip_rl_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_str_color">Event place(street)
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_evt_str_color" id="lightbox_evt_str_color"
                                           value="<?php echo $row->lightbox_evt_str_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_str_size">Event place(street) font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_evt_str_size" id="lightbox_evt_str_size"
                                           value="<?php echo $row->lightbox_evt_str_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_str_font_weight">Event place(street)
                                        font weight: </label></td>
                                <td>
                                    <select name="lightbox_evt_str_font_weight" id="lightbox_evt_str_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_evt_str_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_ctzpcn_color">Event place(city, zip,
                                        country) color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_evt_ctzpcn_color" id="lightbox_evt_ctzpcn_color"
                                           value="<?php echo $row->lightbox_evt_ctzpcn_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_ctzpcn_size">Event place(city, zip,
                                        country) font size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_evt_ctzpcn_size" id="lightbox_evt_ctzpcn_size"
                                           value="<?php echo $row->lightbox_evt_ctzpcn_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_ctzpcn_font_weight">Event place(city,
                                        zip, country) font weight: </label></td>
                                <td>
                                    <select name="lightbox_evt_ctzpcn_font_weight" id="lightbox_evt_ctzpcn_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_evt_ctzpcn_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_map_color">Event place(map)
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_evt_map_color" id="lightbox_evt_map_color"
                                           value="<?php echo $row->lightbox_evt_map_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_map_size">Event place(map) font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_evt_map_size" id="lightbox_evt_map_size"
                                           value="<?php echo $row->lightbox_evt_map_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_map_font_weight">Event place(map) font
                                        weight: </label></td>
                                <td>
                                    <select name="lightbox_evt_map_font_weight" id="lightbox_evt_map_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_evt_map_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_date_color">Event date color: </label>
                                </td>
                                <td>
                                    <input type="text" name="lightbox_evt_date_color" id="lightbox_evt_date_color"
                                           value="<?php echo $row->lightbox_evt_date_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_date_size">Event date font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_evt_date_size" id="lightbox_evt_date_size"
                                           value="<?php echo $row->lightbox_evt_date_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_date_font_weight">Event date font
                                        weight: </label></td>
                                <td>
                                    <select name="lightbox_evt_date_font_weight" id="lightbox_evt_date_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_evt_date_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_evt_info_font_family">Event font
                                        family: </label></td>
                                <td>
                                    <select name="lightbox_evt_info_font_family" id="lightbox_evt_info_font_family">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_evt_info_font_family == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Lightbox_3">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_width">Info Width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_width" id="lightbox_obj_width"
                                           value="<?php echo $row->lightbox_obj_width; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr id="lightbox_comment25">
                                <td class="spider_label"><label>Info position: </label></td>
                                <td>
                                    <input type="radio" name="lightbox_obj_pos" id="lightbox_obj_pos1"
                                           value="left"<?php if ($row->lightbox_obj_pos == "left") echo 'checked="checked"'; ?> />
                                    <label for="lightbox_obj_pos1" id="lightbox_obj_pos1_lbl">Left</label>
                                    <input type="radio" name="lightbox_obj_pos" id="lightbox_obj_pos0"
                                           value="right"<?php if ($row->lightbox_obj_pos == "right") echo 'checked="checked"'; ?> />
                                    <label for="lightbox_obj_pos0" id="lightbox_obj_pos0_lbl">Right</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_info_bg_color">Info background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_info_bg_color" id="lightbox_obj_info_bg_color"
                                           value="<?php echo $row->lightbox_obj_info_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_icons_color">Info icons
                                        color: </label></td>
                                <td>
                                    <select name="lightbox_obj_icons_color" id="lightbox_obj_icons_color">
                                        <option
                                            value="gray" <?php echo(($row->lightbox_obj_icons_color == "gray") ? 'selected="selected"' : ''); ?>>
                                            Gray
                                        </option>
                                        <option
                                            value="white" <?php echo(($row->lightbox_obj_icons_color == "white") ? 'selected="selected"' : ''); ?>>
                                            White
                                        </option>
                                        <option
                                            value="blue" <?php echo(($row->lightbox_obj_icons_color == "blue") ? 'selected="selected"' : ''); ?>>
                                            Blue
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_icons_color_likes_comments_count">Info
                                        icons
                                        color _comments_count: </label></td>
                                <td>
                                    <select name="lightbox_obj_icons_color_likes_comments_count"
                                            id="lightbox_obj_icons_color_likes_comments_count">
                                        <option
                                            value="gray" <?php echo((isset($row->lightbox_obj_icons_color_likes_comments_count) && $row->lightbox_obj_icons_color_likes_comments_count == "gray") ? 'selected="selected"' : ''); ?>>
                                            Gray
                                        </option>
                                        <option
                                            value="white" <?php echo((isset($row->lightbox_obj_icons_color_likes_comments_count) && $row->lightbox_obj_icons_color_likes_comments_count == "white") ? 'selected="selected"' : ''); ?>>
                                            White
                                        </option>
                                        <option
                                            value="blue" <?php echo((isset($row->lightbox_obj_icons_color_likes_comments_count) && $row->lightbox_obj_icons_color_likes_comments_count == "blue") ? 'selected="selected"' : ''); ?>>
                                            Blue
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_font_family">Font family: </label>
                                </td>
                                <td>
                                    <select name="lightbox_obj_font_family" id="lightbox_obj_font_family">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_font_family == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_date_pos">Date position: </label></td>
                                <td>
                                    <select name="lightbox_obj_date_pos" id="lightbox_obj_date_pos">
                                        <option
                                            value="before" <?php echo(($row->lightbox_obj_date_pos == "before") ? 'selected="selected"' : ''); ?>>
                                            Before post author
                                        </option>
                                        <option
                                            value="after" <?php echo(($row->lightbox_obj_date_pos == "after") ? 'selected="selected"' : ''); ?>>
                                            After post author
                                        </option>
                                        <option
                                            value="bottom" <?php echo(($row->lightbox_obj_date_pos == "bottom") ? 'selected="selected"' : ''); ?>>
                                            At the bottom of the post
                                        </option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="spider_label"><label for="lightbox_page_name_color">Page(group, profile) name
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_page_name_color" id="lightbox_page_name_color"
                                           value="<?php echo $row->lightbox_page_name_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_page_name_size">Page(group, profile)
                                        name font size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_page_name_size"
                                           id="lightbox_obj_page_name_size"
                                           value="<?php echo $row->lightbox_obj_page_name_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_page_name_font_weight">Page(group,
                                        profile) name font weight: </label></td>
                                <td>
                                    <select name="lightbox_obj_page_name_font_weight"
                                            id="lightbox_obj_page_name_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_page_name_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_story_color">Story color: </label>
                                </td>
                                <td>
                                    <input type="text" name="lightbox_obj_story_color" id="lightbox_obj_story_color"
                                           value="<?php echo $row->lightbox_obj_story_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_story_size">Story font size: </label>
                                </td>
                                <td>
                                    <input type="text" name="lightbox_obj_story_size" id="lightbox_obj_story_size"
                                           value="<?php echo $row->lightbox_obj_story_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_story_font_weight">Story font
                                        weight: </label></td>
                                <td>
                                    <select name="lightbox_obj_story_font_weight" id="lightbox_obj_story_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_story_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_place_color">Place color: </label>
                                </td>
                                <td>
                                    <input type="text" name="lightbox_obj_place_color" id="lightbox_obj_place_color"
                                           value="<?php echo $row->lightbox_obj_place_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_place_size">Place font size: </label>
                                </td>
                                <td>
                                    <input type="text" name="lightbox_obj_place_size" id="lightbox_obj_place_size"
                                           value="<?php echo $row->lightbox_obj_place_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_place_font_weight">Place font
                                        weight: </label></td>
                                <td>
                                    <select name="lightbox_obj_place_font_weight" id="lightbox_obj_place_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_place_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_name_color">Name color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_name_color" id="lightbox_obj_name_color"
                                           value="<?php echo $row->lightbox_obj_name_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_name_size">Name font size: </label>
                                </td>
                                <td>
                                    <input type="text" name="lightbox_obj_name_size" id="lightbox_obj_name_size"
                                           value="<?php echo $row->lightbox_obj_name_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_name_font_weight">Name font
                                        weight: </label></td>
                                <td>
                                    <select name="lightbox_obj_name_font_weight" id="lightbox_obj_name_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_name_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Lightbox_4">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_message_color">Message and description
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_message_color" id="lightbox_obj_message_color"
                                           value="<?php echo $row->lightbox_obj_message_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_message_size">Message and description
                                        font size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_message_size" id="lightbox_obj_message_size"
                                           value="<?php echo $row->lightbox_obj_message_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_message_font_weight">Message and
                                        description font weight: </label></td>
                                <td>
                                    <select name="lightbox_obj_message_font_weight"
                                            id="lightbox_obj_message_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_message_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_hashtags_color">Hashtags
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_hashtags_color"
                                           id="lightbox_obj_hashtags_color"
                                           value="<?php echo $row->lightbox_obj_hashtags_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_hashtags_size">Hashtags font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_hashtags_size" id="lightbox_obj_hashtags_size"
                                           value="<?php echo $row->lightbox_obj_hashtags_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_hashtags_font_weight">Hashtags font
                                        weight: </label></td>
                                <td>
                                    <select name="lightbox_obj_hashtags_font_weight"
                                            id="lightbox_obj_hashtags_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_hashtags_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_likes_social_bg_color">Likes(share,
                                        comment) and social buttons background color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_likes_social_bg_color"
                                           id="lightbox_obj_likes_social_bg_color"
                                           value="<?php echo $row->lightbox_obj_likes_social_bg_color; ?>"
                                           class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_likes_social_color">Likes(share,
                                        comment) color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_likes_social_color"
                                           id="lightbox_obj_likes_social_color"
                                           value="<?php echo $row->lightbox_obj_likes_social_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_likes_social_size">Likes(share,
                                        comment) and social size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_likes_social_size"
                                           id="lightbox_obj_likes_social_size"
                                           value="<?php echo $row->lightbox_obj_likes_social_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_likes_social_font_weight">Likes(share,
                                        comment) and social font weight: </label></td>
                                <td>
                                    <select name="lightbox_obj_likes_social_font_weight"
                                            id="lightbox_obj_likes_social_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_likes_social_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Lightbox_5">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comments_bg_color">Comments background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_comments_bg_color"
                                           id="lightbox_obj_comments_bg_color"
                                           value="<?php echo $row->lightbox_obj_comments_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comments_color">Comments
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_comments_color"
                                           id="lightbox_obj_comments_color"
                                           value="<?php echo $row->lightbox_obj_comments_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comments_font_family">Comments font
                                        family: </label></td>
                                <td>
                                    <select name="lightbox_obj_comments_font_family"
                                            id="lightbox_obj_comments_font_family">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_comments_font_family == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comments_font_size">Comments font
                                        size: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_comments_font_size"
                                           id="lightbox_obj_comments_font_size"
                                           value="<?php echo $row->lightbox_obj_comments_font_size; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_users_font_color">Users
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_users_font_color"
                                           id="lightbox_obj_users_font_color"
                                           value="<?php echo $row->lightbox_obj_users_font_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comments_social_font_weight">Comments
                                        font weight: </label></td>
                                <td>
                                    <select name="lightbox_obj_comments_social_font_weight"
                                            id="lightbox_obj_comments_social_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_comments_social_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comment_border_width">Comment border
                                        width: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_comment_border_width"
                                           id="lightbox_obj_comment_border_width"
                                           value="<?php echo $row->lightbox_obj_comment_border_width; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comment_border_style">Comment border
                                        style: </label></td>
                                <td>
                                    <select name="lightbox_obj_comment_border_style"
                                            id="lightbox_obj_comment_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->lightbox_obj_comment_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comment_border_color">Comment border
                                        color: </label></td>
                                <td>
                                    <input type="text" name="lightbox_obj_comment_border_color"
                                           id="lightbox_obj_comment_border_color"
                                           value="<?php echo $row->lightbox_obj_comment_border_color; ?>"
                                           class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="lightbox_obj_comment_border_type">Comment border
                                        type: </label></td>
                                <td>
                                    <select name="lightbox_obj_comment_border_type" id="lightbox_border_type">
                                        <option
                                            value="all" <?php echo(($row->lightbox_obj_comment_border_type == "all") ? 'selected="selected"' : ''); ?>>
                                            All
                                        </option>
                                        <option
                                            value="top" <?php echo(($row->lightbox_obj_comment_border_type == "top") ? 'selected="selected"' : ''); ?>>
                                            Top
                                        </option>
                                        <option
                                            value="right" <?php echo(($row->lightbox_obj_comment_border_type == "right") ? 'selected="selected"' : ''); ?>>
                                            Right
                                        </option>
                                        <option
                                            value="bottom" <?php echo(($row->lightbox_obj_comment_border_type == "bottom") ? 'selected="selected"' : ''); ?>>
                                            Bottom
                                        </option>
                                        <option
                                            value="left" <?php echo(($row->lightbox_obj_comment_border_type == "left") ? 'selected="selected"' : ''); ?>>
                                            Left
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </fieldset>
                <fieldset class="spider_type_fieldset" id="Navigation">
                    <fieldset class="spider_child_fieldset" id="Navigation_1">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="page_nav_font_size">Font size: </label></td>
                                <td>
                                    <input type="text" name="page_nav_font_size" id="page_nav_font_size"
                                           value="<?php echo $row->page_nav_font_size; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_font_color">Font color: </label></td>
                                <td>
                                    <input type="text" name="page_nav_font_color" id="page_nav_font_color"
                                           value="<?php echo $row->page_nav_font_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_font_style">Font family: </label></td>
                                <td>
                                    <select name="page_nav_font_style" id="page_nav_font_style">
                                        <?php
                                        foreach ($font_families as $key => $font_family) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->page_nav_font_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_family; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_font_weight">Font weight: </label></td>
                                <td>
                                    <select name="page_nav_font_weight" id="page_nav_font_weight">
                                        <?php
                                        foreach ($font_weights as $key => $font_weight) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->page_nav_font_weight == $key) ? 'selected="selected"' : ''); ?>><?php echo $font_weight; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_border_width">Border width: </label></td>
                                <td>
                                    <input type="text" name="page_nav_border_width" id="page_nav_border_width"
                                           value="<?php echo $row->page_nav_border_width; ?>" class="spider_int_input"
                                           onkeypress="return spider_check_isnum(event)"/> px
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_border_style">Border style: </label></td>
                                <td>
                                    <select name="page_nav_border_style" id="page_nav_border_style">
                                        <?php
                                        foreach ($border_styles as $key => $border_style) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->page_nav_border_style == $key) ? 'selected="selected"' : ''); ?>><?php echo $border_style; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_border_color">Border color:</label></td>
                                <td>
                                    <input type="text" name="page_nav_border_color" id="page_nav_border_color"
                                           value="<?php echo $row->page_nav_border_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_border_radius">Border radius: </label>
                                </td>
                                <td>
                                    <input type="text" name="page_nav_border_radius" id="page_nav_border_radius"
                                           value="<?php echo $row->page_nav_border_radius; ?>"
                                           class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Navigation_2" style="display:block">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label for="page_nav_margin">Margin: </label></td>
                                <td>
                                    <input type="text" name="page_nav_margin" id="page_nav_margin"
                                           value="<?php echo $row->page_nav_margin; ?>" class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_padding">Padding: </label></td>
                                <td>
                                    <input type="text" name="page_nav_padding" id="page_nav_padding"
                                           value="<?php echo $row->page_nav_padding; ?>" class="spider_char_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_button_bg_color">Button background
                                        color: </label></td>
                                <td>
                                    <input type="text" name="page_nav_button_bg_color" id="page_nav_button_bg_color"
                                           value="<?php echo $row->page_nav_button_bg_color; ?>" class="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_button_bg_transparent">Button background
                                        transparency: </label></td>
                                <td>
                                    <input type="text" name="page_nav_button_bg_transparent"
                                           id="page_nav_button_bg_transparent"
                                           value="<?php echo $row->page_nav_button_bg_transparent; ?>"
                                           class="spider_int_input" onkeypress="return spider_check_isnum(event)"/> %
                                    <div class="spider_description">Value must be between 0 to 100.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label>Button transition: </label></td>
                                <td>
                                    <input type="radio" name="page_nav_button_transition"
                                           id="page_nav_button_transition1"
                                           value="1"<?php if ($row->page_nav_button_transition == 1) echo 'checked="checked"'; ?> />
                                    <label for="page_nav_button_transition1"
                                           id="page_nav_button_transition1_lbl">Yes</label>
                                    <input type="radio" name="page_nav_button_transition"
                                           id="page_nav_button_transition0"
                                           value="0"<?php if ($row->page_nav_button_transition == 0) echo 'checked="checked"'; ?> />
                                    <label for="page_nav_button_transition0"
                                           id="page_nav_button_transition0_lbl">No</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_box_shadow">Box shadow: </label></td>
                                <td>
                                    <input type="text" name="page_nav_box_shadow" id="page_nav_box_shadow"
                                           value="<?php echo $row->page_nav_box_shadow; ?>" class="spider_box_input"/>
                                    <div class="spider_description">Use CSS type values.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="spider_child_fieldset" id="Navigation_3">
                        <table style="clear:both;">
                            <tbody>
                            <tr>
                                <td class="spider_label"><label>Position: </label></td>
                                <td id="page_nav_position">
                                    <input type="radio" name="page_nav_position" id="page_nav_position1"
                                           value="top"<?php if ($row->page_nav_position == "top") echo 'checked="checked"'; ?> />
                                    <label for="page_nav_position1" id="page_nav_position1_lbl">Top</label>
                                    <input type="radio" name="page_nav_position" id="page_nav_position0"
                                           value="bottom"<?php if ($row->page_nav_position == "bottom") echo 'checked="checked"'; ?> />
                                    <label for="page_nav_position0" id="page_nav_position0_lbl">Bottom</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label for="page_nav_align0">Alignment: </label></td>
                                <td>
                                    <select name="page_nav_align" id="page_nav_align">
                                        <?php
                                        foreach ($aligns as $key => $align) {
                                            ?>
                                            <option
                                                value="<?php echo $key; ?>" <?php echo(($row->page_nav_align == $key) ? 'selected="selected"' : ''); ?>><?php echo $align; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label>Numbering: </label></td>
                                <td>
                                    <input type="radio" name="page_nav_number" id="page_nav_number1"
                                           value="1"<?php if ($row->page_nav_number == 1) echo 'checked="checked"'; ?> />
                                    <label for="page_nav_number1" id="page_nav_number1_lbl">Yes</label>
                                    <input type="radio" name="page_nav_number" id="page_nav_number0"
                                           value="0"<?php if ($row->page_nav_number == 0) echo 'checked="checked"'; ?> />
                                    <label for="page_nav_number0" id="page_nav_number0_lbl">No</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="spider_label"><label>Button text: </label></td>
                                <td>
                                    <input type="radio" name="page_nav_button_text" id="page_nav_button_text1"
                                           value="1"<?php if ($row->page_nav_button_text == 1) echo 'checked="checked"'; ?> />
                                    <label for="page_nav_button_text1" id="page_nav_button_text1_lbl">Text</label>
                                    <input type="radio" name="page_nav_button_text" id="page_nav_button_text0"
                                           value="0"<?php if ($row->page_nav_button_text == 0) echo 'checked="checked"'; ?> />
                                    <label for="page_nav_button_text0" id="page_nav_button_text0_lbl">Arrow</label>
                                    <div class="spider_description">Next, previous buttons values.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </fieldset>
            </fieldset>
            <input type="hidden" id="task" name="task" value=""/>
            <input type="hidden" id="current_id" name="current_id" value="<?php echo $row->id; ?>"/>
            <input type="hidden" id="default_theme" name="default_theme" value="<?php echo $row->default_theme; ?>"/>
            <script>
                window.onload = bwg_change_theme_type('<?php echo $current_type; ?>');
            </script>
        </form>
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

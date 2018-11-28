<?php

class FFWDViewInfo_ffwd
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

        $rows_data = $this->model->get_rows_data();
        $this->model->del_ffwd_objects();
        $page_nav = $this->model->page_nav();
        $search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
        $search_select_value = ((isset($_POST['search_select_value'])) ? (int)$_POST['search_select_value'] : 0);
        $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
        $order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'order');
        $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
        $ids_string = '';
        $per_page = $this->model->per_page();
        $pager = 0;
        ?>




        <div class="ffwd_upgrade wd-clear" >
            <div class="ffwd-left">

                <div style="font-size: 14px; ">
							    <?php _e(" This section allows you to create, edit and delete Facebook Feed WD.","ffwd");?>
                    <a style="color: #5CAEBD; text-decoration: none;border-bottom: 1px dotted;" target="_blank" href="https://web-dorado.com/wordpress-facebook-feed/creating-feed.html"><?php _e("Read More in User Manual.","ffwd");?></a>
                </div>

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



        <form class="wrap" id="ffwd_info_form" method="post" action="admin.php?page=info_ffwd" style="width:99%;">

<h2></h2>
            <?php wp_nonce_field('info_ffwd', 'ffwd_nonce'); ?>

            <div class="ffwd_plugin_header">
                <span class="wd-fb-icon"></span>
                <h2 class="ffwd_page_name">
                    Feeds
                    <a href="" class="ffwd-button-primary ffwd-button-add-new" onclick="spider_set_input_value('task', 'add');
                                               spider_form_submit(event, 'ffwd_info_form')"> Add new</a>


                </h2>
            </div>

            <div id="draganddrop" class="updated" style="display:none;"><strong><p>Changes made in this table should be
                        saved.</p></strong></div>





            <div class="buttons_div">
        <span class="ffwd-button-secondary non_selectable" onclick="spider_check_all_items()">
          <input type="checkbox" id="check_all_items" name="check_all_items" onclick="spider_check_all_items_checkbox()"
                 style="margin: 0; vertical-align: middle;"/>
          <span style="vertical-align: middle;">Select All</span>
        </span>




                <input id="show_hide_weights" class="ffwd-button-secondary ffwd-button-show-order" type="button"
                       onclick="spider_show_hide_weights();return false;" value="Hide order column"/>
                <input class="ffwd-button-secondary ffwd-button-save-order" type="submit"
                       onclick="spider_set_input_value('task', 'save_order')"
                       value="Save Order"/>
                <input class="ffwd-button-secondary ffwd-button-publish" type="submit"
                       onclick="spider_set_input_value('task', 'publish_all')"
                       value="Publish"/>
                <input class="ffwd-button-secondary ffwd-button-unpublish" type="submit"
                       onclick="spider_set_input_value('task', 'unpublish_all')"
                       value="Unpublish"/>
                <input class="ffwd-button-secondary ffwd-button-delete" type="submit" onclick="if (confirm('Do you want to delete selected items?')) {
                                                       spider_set_input_value('task', 'delete_all');
                                                     } else {
                                                       return false;
                                                     }" value="Delete"/>
            </div>
            <div class="tablenav top">
                <?php
                WDW_FFWD_Library::search('Name', $search_value, 'ffwd_info_form');
                WDW_FFWD_Library::html_page_nav($page_nav['total'], $pager++, $page_nav['limit'], 'ffwd_info_form', $per_page);
                ?>
            </div>
            <table class="wp-list-table widefat fixed pages">
                <thead>
                <th class="table_small_col"></th>
                <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox"
                                                                                        onclick="spider_check_all(this)"
                                                                                        style="margin:0;"/></th>
                <th class="table_small_col <?php if ($order_by == 'id') {
                    echo $order_class;
                } ?>">
                    <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'id');
                        spider_set_input_value('asc_or_desc', '<?php echo((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'ffwd_info_form')" href="">
                        <span>ID</span><span class="sorting-indicator"></span>
                    </a>
                </th>
                <th class="<?php if ($order_by == 'name') {
                    echo $order_class;
                } ?>">
                    <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'name');
                        spider_set_input_value('asc_or_desc', '<?php echo((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'ffwd_info_form')" href="">
                        <span>Name</span><span class="sorting-indicator"></span>
                    </a>
                </th>
                <th>Shortcode</th>
                <th id="th_order" class="table_medium_col <?php if ($order_by == 'order') {
                    echo $order_class;
                } ?>">
                    <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'order');
                        spider_set_input_value('asc_or_desc', '<?php echo((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'order') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'ffwd_info_form')" href="">
                        <span>Order</span><span class="sorting-indicator"></span>
                    </a>
                </th>
                <th class="table_big_col <?php if ($order_by == 'published') {
                    echo $order_class;
                } ?>">
                    <a onclick="spider_set_input_value('task', '');
                        spider_set_input_value('order_by', 'published');
                        spider_set_input_value('asc_or_desc', '<?php echo((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'published') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        spider_form_submit(event, 'ffwd_info_form')" href="">
                        <span>Published</span><span class="sorting-indicator"></span>
                    </a>
                </th>
                <th class="table_big_col">Edit</th>
                <th class="table_big_col">Delete</th>
                </thead>
                <tbody id="tbody_arr">
                <?php
                if ($rows_data) {
                    foreach ($rows_data as $row_data) {
                        $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
                        $published_image = (($row_data->published) ? '-413px' : '-383px');
                        $published = (($row_data->published) ? 'unpublish' : 'publish');
                        ?>
                        <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                            <td class="connectedSortable table_small_col">
                                <div title="Drag to re-order" class="handle" style="margin:5px auto 0 auto;"></div>
                            </td>
                            <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>"
                                                                            name="check_<?php echo $row_data->id; ?>"
                                                                            onclick="spider_check_all(this)"
                                                                            type="checkbox"/></td>
                            <td class="table_small_col"><?php echo $row_data->id; ?></td>
                            <td><a onclick="spider_set_input_value('task', 'edit');
                                    spider_set_input_value('page_number', '1');
                                    spider_set_input_value('search_value', '');
                                    spider_set_input_value('search_or_not', '');
                                    spider_set_input_value('asc_or_desc', 'asc');
                                    spider_set_input_value('order_by', 'order');
                                    spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                    spider_form_submit(event, 'ffwd_info_form')"
                                   href=""><?php echo $row_data->name; ?></a></td>
                            <td class="spider_order table_medium_col"><input
                                    id="order_input_<?php echo $row_data->id; ?>"
                                    name="order_input_<?php echo $row_data->id; ?>" type="text" size="1"
                                    value="<?php echo $row_data->order; ?>"/></td>
                            <td><input readonly type="text" onclick="jQuery(this).focus();jQuery(this).select();" value='[WD_FB id="<?php echo $row_data->id ?>"]' /></td>

                            <td class="table_big_col"><a
                                    onclick="spider_set_input_value('task', '<?php echo $published; ?>');spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');spider_form_submit(event, 'ffwd_info_form')"
                                    href="" style="display: block;height: 20px;overflow: hidden;"><img
                                        src="<?php echo WD_FFWD_URL . '/images/ffwd/button-icons2.png'; ?>"
                                        style="position: relative;top:<?php echo $published_image ?>"></img></a>
                            </td>
                            <td class="table_big_col"><a onclick="spider_set_input_value('task', 'edit');
                                    spider_set_input_value('page_number', '1');
                                    spider_set_input_value('search_value', '');
                                    spider_set_input_value('search_or_not', '');
                                    spider_set_input_value('asc_or_desc', 'asc');
                                    spider_set_input_value('order_by', 'order');
                                    spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                    spider_form_submit(event, 'ffwd_info_form')" href="">Edit</a></td>
                            <td class="table_big_col"><a
                                    onclick="if(! confirm('Are you sure you want to delete ?'))return false;
                                        spider_set_input_value('task', 'delete');
                                        spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                        spider_form_submit(event, 'ffwd_info_form')" href="">Delete</a></td>
                        </tr>
                        <?php
                        $ids_string .= $row_data->id . ',';
                    }
                }
                ?>
                </tbody>
            </table>
            <div class="tablenav bottom">
                <?php
                WDW_FFWD_Library::html_page_nav($page_nav['total'], $pager++, $page_nav['limit'], 'ffwd_info_form', $per_page);
                ?>
            </div>
            <input id="task" name="task" type="hidden" value=""/>
            <input id="current_id" name="current_id" type="hidden" value=""/>
            <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>"/>
            <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc"/>
            <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>"/>
            <!--<script>
                window.onload = spider_show_hide_weights;
                var dropped_ids = [],
                    ffwd_data = jQuery.parseJSON('<?php /*echo $this->model->ffwd_objects; */?>');
                for (var i = 0; i < ffwd_data.length; i++) {
                    for (var j = 0; j < ffwd_data[i].length; j++) {
                        jQuery.getJSON(
                            ffwd_data[i][j]['fb_graph_url'],
                            createSuccessCallback(i, j)
                        ).error(createCallback(i, j));
                    }
                }
                function createCallback(i, j) {
                    return function (e) {
                        do_something_with_data(i, j, e);
                    };
                }
                function do_something_with_data(i, j, e) {
                    var error = jQuery.parseJSON(e.responseText);
                    if (error == null) return;
                    if (error['error']['message'].indexOf('Unsupported get request.') != -1) {
                        var ffwd_nonce = jQuery("#ffwd_nonce").val();
                        var data = {};
                        data["action"] = 'dropp_objects';
                        data["ids"] = ffwd_data[i][j]['id'];
                        data["ffwd_nonce"] = ffwd_nonce;
                        jQuery.ajax({
                            method: "POST",
                            url: ajax_url,
                            data: data,
                            success: function (result) {
                                console.log(result);
                            }
                        });
                    }
                }
                function createSuccessCallback(i, j) {
                    return;
                }
            </script>-->
        </form>
        <?php
    }

    public function edit($id)
    {

///////////////////////////////
        $row = $this->model->get_row_data($id);
        $theme_rows = $this->model->get_theme_rows_data();
        $fb_glob_optons = $this->model->get_option_row_data();
        $page_title = (($id != 0) ? 'Edit Facebook Feed WD ' . $row->name : 'Create new Facebook Feed WD');
        $type = $row->type;
		if($type != 'profile' || $type != 'group' ){
			$type = 'page';
		}
        $content_url = $row->content_url;
        $disabled = ($id != 0) ? 'disabled' : '';

        $effects = array(
            'none' => 'None',
            'fade' => 'Fade',
            '0' => 'Cube Horizontal',
            '1' => 'Cube Vertical',

            '2' => 'Slice Horizontal',
            '3' => 'Slice Vertical',
            '4' => 'Slide Horizontal',
            '5' => 'Slide Vertical',
            '6' => 'Scale Out',
            '7' => 'Scale In',
            '8' => 'Block Scale',
            '9' => 'Kaleidoscope',
            '10' => 'Fan',
            '11' => 'Blind Horizontal',
            '12' => 'Blind Vertical',
            '13' => 'Random',
        );


        ?>
        <style>


            .ffwd_header {
                /*background-image: url("../images/ffwd_logo.png");*/
                background: url('<?php echo WD_FFWD_URL; ?>/images/ffwd_logo.png') no-repeat 0px center;
                padding: 20px 0px 20px 80px;
                background-size: 70px;
            }

            .ffwd_main {
                display: block;
                margin: 20px 0px 0px 0px;
            }

            .ffwd_main_set_c {
                width: 100%;
                /*height: 400px;*/
                padding: 9px;
            }

            .ffwd_lightbox_settings, .ffwd_comments_tab, .ffwd_page_plugin_tab, .ffwd_events_tab {
                display: none;
                margin: 20px 0px 0px 0px;
            }

            .ffwd_varied {
                display: none;
                margin: 20px 0px 0px 0px;
            }

            .ffwd_varied:after {
                visibility: hidden;
                display: block;
                font-size: 0;
                content: " ";
                clear: both;
                height: 0;
            }

            .ffwd_view {
                width: 120px;
                height: 133px;
                background-size: 120px;
                background-repeat: no-repeat;
                float: left;
                margin: 0px 10px 0px 0px;
                cursor: pointer;
            }

            .ffwd_view_t {
                background-image: url("<?php echo WD_FFWD_URL; ?>/images/ffwd/ffwd_thumb.png");
                background-size: 105px;
                background-position: center 24px;
            }

            .ffwd_view_m {
                background-image: url("<?php echo WD_FFWD_URL; ?>/images/ffwd/ffwd_masonry.png");
                background-size: 106px;
                background-position: center 23px;
            }

            .ffwd_view_bf {
                background-image: url("<?php echo WD_FFWD_URL; ?>/images/ffwd/ffwd_blog.png");
                background-position: center 24px;
            }

            .ffwd_view_bh {
                background-image: url("<?php echo WD_FFWD_URL; ?>/images/ffwd/ffwd_blog.png");
                background-position: center 24px;

            }

            .ffwd_view_a {
                background-image: url("<?php echo WD_FFWD_URL; ?>/images/ffwd/ffwd_album.png");
                background-position: center 24px;
            }

            }
            .ffwd_views_set {
                margin: 10px 0px 0px 0px;
                /*width: 860px;*/
            }

            .ffwd_view_l_s, .ffwd_varied_s {
                padding: 9px;
                box-sizing: border-box;
                width: 415px;
            }

            .ffwd_view_t_s, .ffwd_varied_f {
                padding: 9px;
                box-sizing: border-box;
                width: 660px;
                margin: 0px 10px 0px 0px;
            }

            .ffwd_views_set:after {
                visibility: hidden;
                display: block;
                font-size: 0;
                content: " ";
                clear: both;
                height: 0;
            }

            .ffwd_tab {
                font-size: 17px;
                float: left;
                padding: 10px;
                background-color: #fff;
                border: 1px solid #FBFBFB;
                border-bottom: none;
                margin-right: 0px;
                position: relative;
                z-index: 2;
                top: 2px;
            }

            .ffwd_tab:hover {
                cursor: pointer;
                background-color: #fff;
                border-bottom: none;
                margin-bottom: 1px;
            }

            .ffwd_tab_s {
                background-color: #fff !important;
                top: 3px;
                border-left: 1px solid #3b5a9a;
                border-right: 1px solid #3b5a9a;
                border-top: 1px solid #3b5a9a;
            }

            }

            .ffwd_tabs:after {
                visibility: hidden;
                display: block;
                font-size: 0;
                content: " ";
                clear: both;
                height: 0;
            }

            .ffwd_views_c:after {
                visibility: hidden;
                display: block;
                font-size: 0;
                content: " ";
                clear: both;
                height: 0;
            }

            .ffwd_button {

            }

            .ffwd_tabs_cont {
                float: left;
                margin: 17px 0px 0px 0px;
                position: relative;
                top: 4px;
            }

            .ffwd_butts_c {
                float: right;
                margin: 17px 0px 0px 0px;
            }

            .ffwd_tabs {
                margin: 0;
                padding: 0;
            }

            .ffwd_button {
                background: #0071BC;
                outline: none;
                padding: 7px;
                color: #ffffff;
                border: 0;
                margin: 0;
                cursor: pointer;
                box-shadow: 0px 0px 3px #FFFFFF;
            }

            .ffwd_button:hover {
                background-color: #47C6AB;
            }

            .ffwd_set_l {
                font-weight: bolder;
                padding: 4px;
                color: #4E4E4E;
            }

            .ffwd_set_i {
                vertical-align: middle;
            }

            .ffwd_header_l {
                color: #0071BC;
                font-size: 14px;
                font-weight: bolder;
                /*border-bottom-style: solid;
				border-width: 1px;
				border-color: white;*/
                padding: 10px 10px 10px 10px;
                background: #F5F5F5;
                display: block;
            }

            .ffwd_header_c {
                margin: 0px 0px 10px 0px;
            }

            .ffwd_sett_tabl {
                width: 100%;
            }

            .ffwd_view div {
                line-height: 22px;
                text-align: center;
                color: #000;

            }

            .ffwd_border_wrapper {

                border: 1px solid #3b5a9a;
                padding: 5px;
                background-color: #FFFFFF;

            }
        </style>


        <div id="message_div" class="updated" style="display: none;"></div>

        <div class="ffwd_upgrade wd-clear" >
            <div class="ffwd-left">

                <div style="font-size: 14px; ">
							    <?php _e("This section allows you to add/edit Facebook Feed WD","ffwd");?>
                    <a style="color: #5CAEBD; text-decoration: none;border-bottom: 1px dotted;" target="_blank" href="https://web-dorado.com/wordpress-facebook-feed/creating-feed.html"><?php _e("Read More in User Manual.","ffwd");?></a>
                </div>

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


        <script src="http://malsup.github.io/jquery.form.js"></script>
        <form class="wrap" method="post" id="ffwd_info_form" action="admin.php?page=info_ffwd" style="width:99%;">
            <h2></h2>
            <?php wp_nonce_field('info_ffwd', 'ffwd_nonce'); ?>
            <!-- <span class="wd-fb-icon"></span> -->
            <div class="ffwd_plugin_header">
                
                <span class="wd-fb-icon"></span>
                <h2 class="ffwd_page_name"><?php echo $page_title; ?></h2>
            </div>




            <div class="ffwd_tabs_cont" style="float:left;">

                <ul class="ffwd_tabs">
                    <li class="ffwd_tab ffwd_tab_s" show="main" onclick="ffwd_change_tab(this)">Main</li>
                    <li class="ffwd_tab" id="ffwd_tab_lightbox" show="lightbox_settings"
                        onclick="ffwd_change_tab(this)">Lightbox settings
                    </li>
                    <li class="ffwd_tab" id="ffwd_tab_comments" show="comments_tab" onclick="ffwd_change_tab(this)">
                        Comments
                    </li>
                    <li class="ffwd_tab" id="ffwd_tab_page_plugin" show="page_plugin_tab"
                        onclick="ffwd_change_tab(this)">Page plugin
                    </li>
                </ul>
            </div>




            <div class="ffwd_butts_c" style="float:right;">
                <input class=" ffwd-button-primary ffwd-button-save" type="button"
                       onclick="if (spider_check_required('name', 'Name') || spider_check_required('fb_page_id', 'Fb pages'))return false;
                           spider_set_input_value('task', 'save');
                           spider_ajax_save('<?php echo WD_FB_PREFIX; ?>');" value="Save"/>
                <input class=" ffwd-button-primary ffwd-button-apply" type="button"
                       onclick="if (spider_check_required('name', 'Name') || spider_check_required('fb_page_id', 'Fb pages')) return false;
                           spider_set_input_value('task', 'apply');
                           spider_ajax_save('<?php echo WD_FB_PREFIX; ?>');" value="Apply"/>
                <input class=" ffwd-button-secondary ffwd-button-cancel" type="submit" onclick="spider_set_input_value('page_number', '1');
																											 spider_set_input_value('task', 'cancel')"
                       value="Cancel"/>
            </div>
            <div style="clear:both"></div>

            <div class="ffwd_border_wrapper">
                <div class="ffwd_p ffwd_main">


                    <div class="ffwd_main_set_c">
                        <table>
                            <tbody>
                            <tr>
                                <td class="ffwd_set_l"><label for="name">Name: <span style="color:#FF0000;">*</span>
                                    </label></td>
                                <td><input type="text" id="name" name="name" value="<?php echo $row->name; ?>"
                                           size="39"/></td>
                            </tr>
                            <tr>
                                <td class="ffwd_set_l"><label for="<?php echo WD_FB_PREFIX; ?>_type">FB type: </label>
                                </td>
                                <td style="margin-bottom:15px">
                                    <select name="<?php echo WD_FB_PREFIX; ?>_type"
                                            id="<?php echo WD_FB_PREFIX; ?>_type" style="width:90px;"
                                            onchange="choose_fb_type('<?php echo WD_FB_PREFIX; ?>', jQuery(this).val());">
                                        <option value="selected" <?php if ($type == '') echo 'selected="selected"'; ?>>
                                            Select
                                        </option>
                                        <option value="page" <?php if ($type == 'page') echo 'selected="selected"'; ?>>
                                            Page
                                        </option>
                                        <option
                                            value="group" <?php if ($type == 'group') echo 'selected="selected"'; ?>>
                                            Group
                                        </option>
                                        <option disabled
                                            value="profile">
                                            Profile
                                        </option>
                                    </select>
                                    <br>
                                </td>
                            </tr>

                            </tbody>
                            <tbody id="ffwd_group_warning"
                                   style="display: <?php echo $type == 'group' ? 'block' : 'none' ?>;">
                            <tr>
                                <td colspan="2">

                                    <div style="width:99%" class="">
                                        <div class="ffwd_error" style="">
                                            <p>Facebook API has been changed recently, and now it does not allow
                                                bringing posts from public groups. You can find more information about
                                                this <a target="_blank"
                                                        href="https://developers.facebook.com/blog/post/2018/04/04/facebook-api-platform-product-changes/">here</a>.
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>

                            <tbody id="ffwd_profile_warning"
                                   style="display: <?php echo $type == 'profile' ? 'block' : 'none' ?>;">
                            <tr>
                                <td colspan="2">

                                    <div style="width:99%" class="">
                                        <div class="ffwd_error" style="">
                                            <p>According to Facebook's privacy policy, unfortunately, it is not possible
                                                to display posts from a personal profile. Facebook API provides data
                                                about public Facebook pages only.</p><br>
                                            <p>If you have a Facebook feed of a profile which represents your business
                                                or organization, it is recommended to <a target="_blank" href="https://www.facebook.com/help/175644189234902/">switch the profile to a public</a>
                                                page. Additionally, using Facebook pages over profiles brings many
                                                advantages and benefits.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>

                            <!-- fb type page -->
                            <tbody id="<?php echo WD_FB_PREFIX; ?>_type_page"
                                   style="display:<?php echo($type == 'page' ? 'table-row-group' : 'none'); ?>;">
                            <tr>
                                <td class="ffwd_set_l"><label for="fb_page_id">FB pages: <span
                                                style="color:#FF0000;">*</span>
                                    </label></td>
                                <td>
                                    <select name="fb_page_id" id="fb_page_id">
                                        <option value="">Choose page</option>
                                      <?php
                                      foreach($this->model->pages_list as $page) {
                                        $selected = (isset($row->fb_page_id) && $page->id == $row->fb_page_id) ? "selected" : "";
                                        echo "<option value='" . $page->id . "' " . $selected . ">" . $page->name . "</option>";
                                      }

                                      ?>
                                    </select>
                                    <img id="ffwd_page_img" src=""/>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                  <?php
                                  $pages = get_option('ffwd_pages_list');
                                  if(empty($pages)) {
                                    ?>
                                      <div class="ffwd_page_list_notice">
                                          <p style="color: red">
                                              Facebook Feed plugin has not got the list of your pages yet. Please
                                              <a href="admin.php?page=options_ffwd">get access token</a> first.
                                          </p>
                                      </div>
                                  <?php } ?>
                                </td>

                            </tr>
                            <tr style="display:none;">
                                <td class="ffwd_set_l"><label for="<?php echo WD_FB_PREFIX; ?>_page_exist_access_tok">Use
                                        Existing access token: </label></td>
                                <td style="margin-bottom:15px"><input type="checkbox"
                                                                      id="<?php echo WD_FB_PREFIX; ?>_page_exist_access_tok"
                                                                      name="<?php echo WD_FB_PREFIX; ?>_page_exist_access_tok"
                                                                      value="<?php echo $row->exist_access; ?>" <?php echo($row->exist_access ? 'checked="checked"' : ''); ?>
                                                                      onchange="if(jQuery('#<?php echo WD_FB_PREFIX; ?>_access_token_cont').css('display') == 'none') jQuery('#<?php echo WD_FB_PREFIX; ?>_access_token_cont').show(); else jQuery('#<?php echo WD_FB_PREFIX; ?>_access_token_cont').hide();"/>
                                </td>
                            </tr>
                            </tbody>
                            <!-- fb type group -->
                            <tbody id="<?php echo WD_FB_PREFIX; ?>_type_group"
                                   style="display:<?php echo($type == 'group' ? 'table-row-group' : 'none'); ?>;">
                            <tr>
                                <td class="ffwd_set_l"><label for="<?php echo WD_FB_PREFIX; ?>_group_url">Group
                                        id: </label></td>
                                <td style="margin-bottom:15px"><input type="text"
                                                                      id="<?php echo WD_FB_PREFIX; ?>_group_url"
                                                                      name="<?php echo WD_FB_PREFIX; ?>_group_url"
                                                                      value="<?php echo $row->content_url; ?>"
                                                                      size="18"/>
                                    <div class="spider_description">To get your Group ID copy the group URL and paste it to <a href="https://lookup-id.com/"  target="_blank">https://lookup-id.com/</a> and press Lookup button.</div>


                                </td>
                            </tr>
                            <tr style="display:none">
                                <td class="ffwd_set_l"><label for="<?php echo WD_FB_PREFIX; ?>_group_exist_access_tok">Use
                                        Existing access token: </label></td>
                                <td style="margin-bottom:15px"><input type="checkbox"
                                                                      id="<?php echo WD_FB_PREFIX; ?>_group_exist_access_tok"
                                                                      name="<?php echo WD_FB_PREFIX; ?>_group_exist_access_tok"
                                                                      value="<?php echo $row->exist_access; ?>" <?php echo($row->exist_access ? 'checked="checked"' : ''); ?>
                                                                      onchange="if(jQuery('#<?php echo WD_FB_PREFIX; ?>_access_token_cont').css('display') == 'none') jQuery('#<?php echo WD_FB_PREFIX; ?>_access_token_cont').show(); else jQuery('#<?php echo WD_FB_PREFIX; ?>_access_token_cont').hide();"/>
                                </td>
                            </tr>
                            </tbody>
                            <!-- fb type profile -->
                            <tbody id="<?php echo WD_FB_PREFIX; ?>_type_profile"
                                   style="display:<?php echo($type == 'profile' ? 'table-row-group' : 'none'); ?>;">
                            <?php if (!$this->model->check_logged_in_user()): ?>
                                <tr>
                                    <td class="ffwd_set_l" style="margin-bottom:15px;vertical-align:middle"></td>
                                    <td>
                                        <label for="wd_fb_facebook_log_in" class="wd_fb_facebook_log_in" style="">Please
                                            login. Press Options button bellow: </label>
                                        <a href="admin.php?page=options_ffwd" class="wd_fb_options_button" style="">
                                            Options </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                            <!-- fb content type -->
                            <tbody id="<?php echo WD_FB_PREFIX; ?>_content_type"
                                   style="display:<?php echo($type != '' ? 'table-row-group' : 'none'); ?>;">
                            <tr id="<?php echo WD_FB_PREFIX; ?>_access_token_cont"
                                style="display:<?php echo(!$row->exist_access ? 'table-row' : 'none'); ?>;">
                                <td class="ffwd_set_l"><label for="<?php echo WD_FB_PREFIX; ?>_access_token">Your access
                                        token: </label></td>
                                <td style="margin-bottom:15px"><input type="text"
                                                                      id="<?php echo WD_FB_PREFIX; ?>_access_token"
                                                                      name="<?php echo WD_FB_PREFIX; ?>_access_token"
                                                                      value="<?php echo $row->access_token; ?>"
                                                                      size="64"/></td>
                            </tr>
                            <tr>
                                <td class="ffwd_set_l"><label>Content type: </label></td>
                                <td style="margin-bottom:15px">
                                    <input type="radio" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_content_timeline"
                                           name="<?php echo WD_FB_PREFIX; ?>_content_type" checked="checked"
                                           value="timeline"
                                           onchange="choose_fb_content_type('<?php echo WD_FB_PREFIX; ?>', jQuery(this).val());">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_content_timeline">Timeline</label>&nbsp;
                                    <input disabled type="radio" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_content_specific"
                                           name="<?php echo WD_FB_PREFIX; ?>_content_type"
                                           value="specific"
                                           onchange="choose_fb_content_type('<?php echo WD_FB_PREFIX; ?>', jQuery(this).val());">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_content_specific">Specific</label>
                                    <br>
                                    <label for="" class="ffwd_pro_only">Specific Content Type is Available Only in PRO
                                        version</label>
                                </td>
                            </tr>
                            <tr <?php if($row->type=='group') echo 'style="display:none;"' ?> id="<?php echo WD_FB_PREFIX; ?>_content_type_timeline_type"
                                style="display:<?php echo($row->content_type == 'timeline' ? 'table-row' : 'none'); ?>;">
                                <td class="ffwd_set_l"><label>Show posts by: </label></td>
                                <td style="margin-bottom:15px">
                                    <select name="<?php echo WD_FB_PREFIX; ?>_timeline_type"
                                            id="<?php echo WD_FB_PREFIX; ?>_timeline_type" style="width:130px">
                                        <option
                                            value="posts" <?php if ($row->timeline_type == 'posts') echo 'selected="selected"'; ?>>
                                            Owner
                                        </option>
                                        <option
                                            value="others" <?php if ($row->timeline_type == 'others') echo 'selected="selected"'; ?>>
                                            Others
                                        </option>
                                        <option
                                            value="feed" <?php if ($row->timeline_type == 'feed') echo 'selected="selected"'; ?>>
                                            Owner and others
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="<?php echo WD_FB_PREFIX; ?>_content_type_timeline"
                                style="display:<?php echo($row->content_type == 'timeline' ? 'table-row' : 'none'); ?>;">
                                <td class="ffwd_set_l"><label>Post type: </label></td>
                                <td>
                                    <input disabled type="checkbox" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_timeline_statuses"
                                           name="<?php echo WD_FB_PREFIX; ?>_timeline_statuses" checked="checked"
                                           value="statuses">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_timeline_statuses">Statuses</label>&nbsp;
                                    <br>
                                    <input disabled type="checkbox" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_timeline_photos"
                                           name="<?php echo WD_FB_PREFIX; ?>_timeline_photos" checked="checked"
                                           value="photos">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_timeline_photos">Photos</label>
                                    <br>
                                    <input disabled type="checkbox" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_timeline_videos"
                                           name="<?php echo WD_FB_PREFIX; ?>_timeline_videos" checked="checked"
                                           value="videos">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_timeline_videos">Videos</label>
                                    <br>
                                    <input disabled type="checkbox" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_timeline_links"
                                           name="<?php echo WD_FB_PREFIX; ?>_timeline_links" checked="checked"
                                           value="links">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_timeline_links">Links</label>
                                    <br>
                                    <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                        version</label>

                                </td>
                            </tr>
                            <tr id="<?php echo WD_FB_PREFIX; ?>_content_type_specific"
                                style="display:<?php echo($row->content_type == 'specific' ? 'table-row' : 'none'); ?>;">
                                <td class="ffwd_set_l"><label>Use page's: </label></td>
                                <td>
                                    <input type="radio" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_specific_photos"
                                           onchange="choose_fb_content_type('<?php echo WD_FB_PREFIX; ?>', 'specific');"
                                           name="<?php echo WD_FB_PREFIX; ?>_specific" <?php echo (strpos($row->content, 'photos') !== false) ? 'checked="checked"' : ''; ?>
                                           value="photos">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_specific_photos">Photos</label>
                                    <br>
                                    <input type="radio" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_specific_videos"
                                           onchange="choose_fb_content_type('<?php echo WD_FB_PREFIX; ?>', 'specific');"
                                           name="<?php echo WD_FB_PREFIX; ?>_specific" <?php echo (strpos($row->content, 'videos') !== false) ? 'checked="checked"' : ''; ?>
                                           value="videos">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_specific_videos">Videos</label>
                                    <br>
                                    <input type="radio" class="inputbox"
                                           id="<?php echo WD_FB_PREFIX; ?>_specific_albums"
                                           onchange="choose_fb_content_type('<?php echo WD_FB_PREFIX; ?>', 'specific');"
                                           name="<?php echo WD_FB_PREFIX; ?>_specific" <?php echo (strpos($row->content, 'albums') !== false) ? 'checked="checked"' : ''; ?>
                                           value="albums">
                                    <label for="<?php echo WD_FB_PREFIX; ?>_specific_albums">Albums</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="ffwd_set_l"><label for="<?php echo WD_FB_PREFIX; ?>_limit">Number of posts: </label>
                                </td>
                                <td><input type="number" id="<?php echo WD_FB_PREFIX; ?>_limit"
                                           name="<?php echo WD_FB_PREFIX; ?>_limit" value="<?php echo $row->limit; ?>"
                                           size="19"/></td>
                            </tr>
                            <tr>
                                <td class="ffwd_set_l"><label>Published: </label></td>
                                <td>
                                    <input type="radio" class="inputbox" id="published0"
                                           name="published" <?php echo(($row->published) ? '' : 'checked="checked"'); ?>
                                           value="0">
                                    <label for="published0">No</label>
                                    <input type="radio" class="inputbox" id="published1"
                                           name="published" <?php echo(($row->published) ? 'checked="checked"' : ''); ?>
                                           value="1">
                                    <label for="published1">Yes</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="ffwd_set_l"><label>Update: </label></td>
                                <td>
                                    <input type="radio" class="inputbox" id="remove_old"
                                           name="update_mode" <?php echo(($row->update_mode == 'remove_old') ? 'checked="checked"' : ''); ?>
                                           value="remove_old">
                                    <label for="remove_old">Add new ones remove old</label><br>
                                    <input type="radio" class="inputbox" id="keep_old"
                                           name="update_mode" <?php echo(($row->update_mode == 'keep_old') ? 'checked="checked"' : ''); ?>
                                           value="keep_old">
                                    <label for="keep_old">Add new ones keep old</label><br>
                                    <input type="radio" class="inputbox" id="no_update"
                                           name="update_mode" <?php echo(($row->update_mode == 'no_update') ? 'checked="checked"' : ''); ?>
                                           value="no_update">
                                    <label for="no_update">No update</label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="ffwd_views_c" <?php if ($row->type == '') echo 'style="display:none"' ?>>

                        <div class="ffwd_view  ffwd_view_en ffwd_view_t ffwd_thumbnails"
                             onClick="ffwd_view_type('<?php echo WD_FB_PREFIX; ?>', 'thumbnails', this)">
                            <div><input type="radio" name="ffwd_view_select"/> Thumbnails View</div>
                        </div>
                        <div class="ffwd_view ffwd_view_m ffwd_thumbnails_masonry"
                             onClick="ffwd_view_type('<?php echo WD_FB_PREFIX; ?>', 'thumbnails_masonry', this)">
                            <div><input type="radio" name="ffwd_view_select"/> Masonry View</div>
                        </div>
                        <div class="ffwd_view ffwd_view_bh ffwd_blog_style"
                             onClick="ffwd_view_type('<?php echo WD_FB_PREFIX; ?>', 'blog_style', this)">
                            <div><input type="radio" name="ffwd_view_select"/>BlogStyle View</div>
                        </div>
                        <div class="ffwd_view ffwd_view_a ffwd_album_compact"
                             onClick="ffwd_view_type('<?php echo WD_FB_PREFIX; ?>', 'album_compact', this)">
                            <div><input type="radio" name="ffwd_view_select"/>Album View</div>
                        </div>
                        <input type="hidden" id="ffwd_fb_view_type" name="ffwd_fb_view_type"
                               value="<?php echo $row->fb_view_type ?>"/>
                    </div>
                    <div class="ffwd_views_set">
                        <div class="ffwd_header_c">
                            <label class="ffwd_header_l">View settings</label>
                        </div>
                        <div class="ffwd_view_t_s">

                            <table class="ffwd_sett_tabl">
                                <tbody>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_theme">
                                    <td class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_theme">Theme: </label></td>
                                    <td class="ffwd_set_i">
                                        <select disabled name="<?php echo WD_FB_PREFIX; ?>_theme"
                                                id="<?php echo WD_FB_PREFIX; ?>_theme" style="width:150px;">
                                            <option value="0" selected="selected">Select Theme</option>
                                            <?php
                                            foreach ($theme_rows as $theme_row) {
                                                ?>
                                                <option <?php
                                                
                                                ?>
                                                    value="<?php echo $theme_row->id; ?>"><?php echo $theme_row->name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <br>
                                        <label class="ffwd_pro_only">Changing Theme is Available Only in PRO version</label>
                                    </td>
                                </tr>
                                <!--Thumbnails, Masonry viewies-->
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_masonry_hor_ver">
                                    <td class="ffwd_set_l"><label>Masonry: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_masonry_hor_ver"
                                               id="<?php echo WD_FB_PREFIX; ?>_masonry_ver" value="vertical"
                                               onclick="ffwd_change_label('<?php echo WD_FB_PREFIX; ?>_image_max_columns_label', 'Max. number of image columns: ');
                                                   ffwd_change_label('<?php echo WD_FB_PREFIX; ?>_thumb_width_height_label', 'Image thumbnail width: ');
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_thumb_width').show();
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_thumb_height').hide();
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_tr_thumb_name').css('display', 'table-row');
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_pagination_type').children()[2].disabled = false;
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_pagination_type').children()[3].disabled = false;
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_thumb_width_height_separator').hide();"
                                               checked <?php checked($row->masonry_hor_ver, 'vertical') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_masonry_ver">Vertical</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_masonry_hor_ver"
                                               id="<?php echo WD_FB_PREFIX; ?>_masonry_hor" value="horizontal"
                                               onclick="ffwd_change_label('<?php echo WD_FB_PREFIX; ?>_image_max_columns_label', 'Number of image rows: ');
                                                   ffwd_change_label('<?php echo WD_FB_PREFIX; ?>_thumb_width_height_label', 'Image thumbnail Height: ');
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_thumb_width').hide();
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_thumb_height').show();
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_tr_thumb_name').css('display', 'none');
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_pagination_type').children()[1].selected = true;
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_pagination_type').children()[2].disabled = true;
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_pagination_type').children()[3].disabled = true;
                                                   jQuery('#<?php echo WD_FB_PREFIX; ?>_thumb_width_height_separator').hide();" <?php checked($row->masonry_hor_ver, 'horizontal') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_masonry_hor">Horizontal</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_image_max_columns">
                                    <td class="ffwd_set_l"><label
                                            id="<?php echo WD_FB_PREFIX; ?>_image_max_columns_label"
                                            for="<?php echo WD_FB_PREFIX; ?>_image_max_columns">Max. number of image
                                            columns: </label></td>
                                    <td><input type="text" name="<?php echo WD_FB_PREFIX; ?>_image_max_columns"
                                               id="<?php echo WD_FB_PREFIX; ?>_image_max_columns"
                                               value="<?php echo $row->image_max_columns == 0 ? '5' : $row->image_max_columns ?>"
                                               class="spider_int_input"/></td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_thumb_width_height">
                                    <td title="Maximum values for thumbnail dimension." class="ffwd_set_l"><label
                                            id="<?php echo WD_FB_PREFIX; ?>_thumb_width_height_label"
                                            for="<?php echo WD_FB_PREFIX; ?>_thumb_width">Image Thumbnail
                                            dimensions: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_thumb_width"
                                               id="<?php echo WD_FB_PREFIX; ?>_thumb_width"
                                               value="<?php echo $row->thumb_width == 0 ? '200' : $row->thumb_width ?>"
                                               class="spider_int_input"/><span
                                            id="<?php echo WD_FB_PREFIX; ?>_thumb_width_height_separator"> x </span>
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_thumb_height"
                                               id="<?php echo WD_FB_PREFIX; ?>_thumb_height"
                                               value="<?php echo $row->thumb_height == 0 ? '150' : $row->thumb_height ?>"
                                               class="spider_int_input"/> px
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_thumb_comments">
                                    <td title="Show comments" class="ffwd_set_l"><label>Show comments: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_thumb_comments"
                                               id="<?php echo WD_FB_PREFIX; ?>_thumb_comments_1" value="1"
                                               checked="checked" <?php checked($row->thumb_comments, 1) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_thumb_comments_yes">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_thumb_comments"
                                               id="<?php echo WD_FB_PREFIX; ?>_thumb_comments_0"
                                               value="0" <?php checked($row->thumb_comments, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_thumb_comments_no">No</label>
                                    </td>
                                </tr>
                                <!--<tr id="<?php /*echo WD_FB_PREFIX; */?>_tr_thumb_likes">
                                    <td title="Show likes" class="ffwd_set_l"><label>Show likes: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php /*echo WD_FB_PREFIX; */?>_thumb_likes"
                                               id="<?php /*echo WD_FB_PREFIX; */?>_thumb_likes_1" value="1"
                                               checked="checked" <?php /*checked($row->thumb_likes, 1) */?> /><label
                                            for="<?php /*echo WD_FB_PREFIX; */?>_thumb_likes_yes">Yes</label>
                                        <input type="radio" name="<?php /*echo WD_FB_PREFIX; */?>_thumb_likes"
                                               id="<?php /*echo WD_FB_PREFIX; */?>_thumb_likes_0"
                                               value="0" <?php /*checked($row->thumb_likes, 0) */?> /><label
                                            for="<?php /*echo WD_FB_PREFIX; */?>_thumb_likes_no">No</label>
                                    </td>
                                </tr>-->
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_thumb_name">
                                    <td title="Show likes" class="ffwd_set_l"><label>Show name: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_thumb_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_thumb_name_1" value="1"
                                               checked="checked" <?php checked($row->thumb_name, 1) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_thumb_name_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_thumb_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_thumb_name_0"
                                               value="0" <?php checked($row->thumb_name, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_thumb_name_0">No</label>
                                    </td>
                                </tr>
                                <!--Blog Style view-->
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_width">
                                    <td title="Maximum value for image width." class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_width">Width: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_blog_style_width"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_width"
                                               value="<?php echo $row->blog_style_width == 0 ? '700' : $row->blog_style_width ?>"
                                               class="spider_int_input"/>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_height">
                                    <td title="Maximum value for image height." class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_height">Height: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_blog_style_height"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_height"
                                               value="<?php echo $row->blog_style_height == 0 ? '' : $row->blog_style_height ?>"
                                               class="spider_int_input"/>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_view_type">
                                    <td title="Maximum value for image width." class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_view_type_1">View
                                            style: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_view_type"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_view_type_1" value="1"
                                               checked="checked" <?php checked($row->blog_style_view_type, 1) ?>
                                               onchange=""/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_view_type_1">Full width</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_view_type"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_view_type_0"
                                               value="0" <?php checked($row->blog_style_view_type, 0) ?>
                                               onchange=""/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_view_type_0">Half width</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_comments">
                                    <td title="Show comments" class="ffwd_set_l"><label>Show comments: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_comments"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_comments_1" value="1"
                                               checked="checked" <?php checked($row->blog_style_comments, 1) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_comments_yes">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_comments"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_comments_0"
                                               value="0" <?php checked($row->blog_style_comments, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_comments_no">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_likes">
                                    <td title="Show likes" class="ffwd_set_l"><label>Show likes: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_likes"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_likes_1"
                                               value="1" <?php checked($row->blog_style_likes, 1) ?> checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_likes_yes">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_likes"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_likes_0"
                                               value="0" <?php checked($row->blog_style_likes, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_likes_no">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_message_desc">
                                    <td title="Show likes" class="ffwd_set_l"><label>Show message(description): </label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_message_desc"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_message_desc_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_message_desc_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_message_desc"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_message_desc_0"
                                               value="0" <?php checked($row->blog_style_message_desc, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_message_desc_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_shares">
                                    <td title="Show share" class="ffwd_set_l"><label>Show share: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_shares"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_shares"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_0"
                                               value="0" <?php checked($row->blog_style_shares, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_shares_butt">
                                    <td title="Show share buttons" class="ffwd_set_l"><label>Show share
                                            buttons: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_butt"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_butt_1" value="1"
                                               /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_butt_1">Yes</label>
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_butt"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_butt_0"
                                               value="0" checked="checked" /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_shares_butt_0">No</label>
                                        <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_facebook">
                                    <td title="Show Facebook share button" class="ffwd_set_l"><label>Show Facebook
                                            button: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_facebook"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_facebook_1" value="1"
                                               /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_facebook_1">Yes</label>
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_facebook"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_facebook_0"
                                               value="0" checked="checked" /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_facebook_0">No</label>
                                        <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_twitter">
                                    <td title="Show Twitter share button" class="ffwd_set_l"><label>Show Twitter
                                            button: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_twitter"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_twitter_1" value="1"
                                               /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_twitter_1">Yes</label>
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_twitter"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_twitter_0"
                                               value="0" checked="checked" /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_twitter_0">No</label>
                                        <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_google">
                                    <td title="Show Google+ share button" class="ffwd_set_l"><label>Show Google+
                                            button: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_google"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_google_1" value="1"
                                               /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_google_1">Yes</label>
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_google"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_google_0"
                                               value="0" checked="checked" /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_google_0">No</label>
                                        <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_author">
                                    <td title="Show author" class="ffwd_set_l"><label>Show author: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_author"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_author_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_author_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_author"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_author_0"
                                               value="0" <?php checked($row->blog_style_author, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_author_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_name">
                                    <td title="Show post name" class="ffwd_set_l"><label>Show post name: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_name_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_name_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_name_0"
                                               value="0" <?php checked($row->blog_style_name, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_name_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_blog_style_place_name">
                                    <td title="Show place name" class="ffwd_set_l"><label>Show place name: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_place_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_place_name_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_place_name_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_blog_style_place_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_blog_style_place_name_0"
                                               value="0" <?php checked($row->blog_style_place_name, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_blog_style_place_name_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_fb_name">
                                    <td title="Show fb name" class="ffwd_set_l"><label>Show feed name: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_fb_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_fb_name_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_fb_name_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_fb_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_fb_name_0"
                                               value="0" <?php checked($row->fb_name, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_fb_name_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_fb_plugin">
                                    <td title="Displays page basic information and allows to like the page" class="ffwd_set_l"><label>Show Page Plugin: </label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_fb_plugin"
                                               id="<?php echo WD_FB_PREFIX; ?>_fb_plugin_1" value="1" checked="checked"
                                               onchange="ffwd_toggle_page_plugin(1)"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_fb_plugin_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_fb_plugin"
                                               id="<?php echo WD_FB_PREFIX; ?>_fb_plugin_0"
                                               value="0" <?php checked($row->fb_plugin, 0) ?>
                                               onchange="ffwd_toggle_page_plugin(0)"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_fb_plugin_0">No</label>
                                        <p class="description">Displays page basic information and allows to like the page</p>
                                    </td>
                                </tr>

                                <!--Compact Album view-->
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_compuct_album_column_number">
                                    <td class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_compuct_album_column_number">Max. number of
                                            album columns: </label></td>
                                    <td><input type="text"
                                               name="<?php echo WD_FB_PREFIX; ?>_compuct_album_column_number"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_column_number"
                                               value="<?php echo $row->album_max_columns == 0 ? '5' : $row->album_max_columns ?>"
                                               class="spider_int_input"/></td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_compuct_album_title_hover">
                                    <td class="ffwd_set_l"><label>Album title: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_compuct_album_title"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_title_hover" value="hover"
                                               checked/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_compuct_album_title_hover">Show on
                                            hover</label><br/>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_compuct_album_title"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_title_show"
                                               value="show" <?php checked($row->album_title, 'show') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_compuct_album_title_show">Always
                                            show</label><br/>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_compuct_album_title"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_title_none"
                                               value="none" <?php checked($row->album_title, 'none') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_compuct_album_title_none">Don't
                                            show</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_compuct_album_thumb_width_height">
                                    <td title="Maximum values for album thumb width and height." class="ffwd_set_l">
                                        <label for="<?php echo WD_FB_PREFIX; ?>_compuct_album_thumb_width">Album
                                            Thumbnail dimensions: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_compuct_album_thumb_width"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_thumb_width"
                                               value="<?php echo $row->album_thumb_width == 0 ? '200' : $row->album_thumb_width ?>"
                                               class="spider_int_input"/> x
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_compuct_album_thumb_height"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_thumb_height"
                                               value="<?php echo $row->album_thumb_height == 0 ? '150' : $row->album_thumb_height ?>"
                                               class="spider_int_input"/> px
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_compuct_album_image_column_number">
                                    <td class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_column_number">Max.
                                            number of image columns: </label></td>
                                    <td><input type="text"
                                               name="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_column_number"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_column_number"
                                               value="<?php echo $row->album_image_max_columns == 0 ? '5' : $row->album_image_max_columns ?>"
                                               class="spider_int_input"/></td>
                                </tr>
                                <!-- <tr id="<?php echo WD_FB_PREFIX; ?>_tr_compuct_album_image_title">
                    <td class="ffwd_set_l"><label>Image title: </label></td>
                    <td class="ffwd_set_i">
                      <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title" id="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title_hover" value="hover" checked /><label for="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title_hover">Show on hover</label><br />
                      <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title" id="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title_show" value="show"  /><label for="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title_show">Always show</label><br />
                      <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title" id="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title_none" value="none"  /><label for="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_title_none">Don't show</label>
                    </td>
                  </tr> -->
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_compuct_album_image_thumb_width_height">
                                    <td title="Maximum values for thumbnail width and height." class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_thumb_width"
                                            id="compuct_album_image_thumb_dimensions">Image thumbnail
                                            dimensions: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="text"
                                               name="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_thumb_width"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_thumb_width"
                                               value="<?php echo $row->album_image_thumb_width == 0 ? '200' : $row->album_image_thumb_width ?>"
                                               class="spider_int_input"/><span
                                            id="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_thumb_dimensions_x"> x </span>
                                        <input type="text"
                                               name="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_thumb_height"
                                               id="<?php echo WD_FB_PREFIX; ?>_compuct_album_image_thumb_height"
                                               value="<?php echo $row->album_image_thumb_height == 0 ? '150' : $row->album_image_thumb_height ?>"
                                               class="spider_int_input"/> px
                                    </td>
                                </tr>

                                <!-- For Pagination -->
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_pagination_type">
                                    <td title="If you want to display all images you should leave it blank or insert 0."
                                        class="ffwd_set_l"><label for="<?php echo WD_FB_PREFIX; ?>_pagination_type">Pagination
                                            type: </label></td>
                                    <td class="ffwd_set_i">
                                        <select name="<?php echo WD_FB_PREFIX; ?>_pagination_type"
                                                id="<?php echo WD_FB_PREFIX; ?>_pagination_type">
                                            <option value="0" <?php selected($row->pagination_type, 0) ?> >No</option>
                                            <option value="1" <?php selected($row->pagination_type, 1) ?> >Numbers
                                            </option>
                                            <option value="2" <?php selected($row->pagination_type, 2) ?> >Load more
                                            </option>
                                            <option value="3" <?php selected($row->pagination_type, 3) ?> >Infinite
                                                Scroll
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_objects_per_page">
                                    <td title="If you want to display all images you should leave it blank or insert 0."
                                        class="ffwd_set_l"><label for="<?php echo WD_FB_PREFIX; ?>_objects_per_page">Objects
                                            per page: </label></td>
                                    <td><input type="text" name="<?php echo WD_FB_PREFIX; ?>_objects_per_page"
                                               id="<?php echo WD_FB_PREFIX; ?>_objects_per_page"
                                               value="<?php echo $row->objects_per_page == 0 ? '20' : $row->objects_per_page ?>"
                                               class="spider_int_input"/></td>
                                </tr>


                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_image_onclick_action">
                                    <td class="ffwd_set_l"><label>Image Onclick: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_image_onclick_action"
                                               id="<?php echo WD_FB_PREFIX; ?>_image_onclick_action_lightbox"
                                               onchange="toggle_lightbox_tab('lightbox')"
                                               value="lightbox" <?php checked($row->image_onclick_action, 'lightbox') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_image_onclick_action_lightbox">Open
                                            Lightbox</label><br/>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_image_onclick_action"
                                               id="<?php echo WD_FB_PREFIX; ?>_image_onclick_action_facebook"
                                               onchange="toggle_lightbox_tab('facebook')"
                                               value="facebook" <?php checked($row->image_onclick_action, 'facebook') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_image_onclick_action_facebook">Redirect To
                                            Facebook</label><br/>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_image_onclick_action"
                                               id="<?php echo WD_FB_PREFIX; ?>_image_onclick_action_none"
                                               onchange="toggle_lightbox_tab('none')"
                                               value="none" <?php checked($row->image_onclick_action, 'none') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_image_onclick_action_none">Do
                                            Nothing </label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="ffwd_header_c">
                        <label class="ffwd_header_l">Post</label>
                    </div>
                    <div class="ffwd_varied_f">

                        <div class="ffwd_varied_f_p">

                            <table class="ffwd_sett_tabl">
                                <tr>
                                    <td class="ffwd_set_l">
                                        <label>Maximum Post message(description) Length:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_post_text_length"
                                               id="<?php echo WD_FB_PREFIX; ?>_post_text_length" size="10"
                                               value="<?php echo $row->post_text_length == '' ? '200' : $row->post_text_length; ?>"
                                               class=""/>
                                        <div class="spider_description"></div>
                                    </td>
                                </tr>
                                <tr id="tr_view_on_facebook">
                                    <td class="ffwd_set_l">
                                        <label>Link to facebook:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_view_on_fb"
                                               id="<?php echo WD_FB_PREFIX; ?>_view_on_fb_1"
                                               value="1" <?php if ($row->view_on_fb) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_view_on_fb_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_view_on_fb"
                                               id="<?php echo WD_FB_PREFIX; ?>_view_on_fb_0"
                                               value="0" <?php if (!$row->view_on_fb) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_view_on_fb_0">No</label>
                                        <div class="spider_description"></div>
                                    </td>
                                </tr>


                            </table>
                        </div>
                    </div>

                </div>
                <div class="ffwd_p ffwd_lightbox_settings">

                    <div class="ffwd_views_set">
                        <div class="ffwd_header_c">
                            <label class="ffwd_header_l">Lightbox settings</label>
                        </div>
                        <div class="ffwd_view_l_s">

                            <table class="ffwd_sett_tabl">
                                <!--Lightbox view-->
                                <tbody id="<?php echo WD_FB_PREFIX; ?>_tbody_popup">
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_fullscreen">
                                    <td title="Show full width feature for the lightbox." class="ffwd_set_l">
                                        <label>Full width lightbox:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_fullscreen"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_fullscreen_1"
                                               value="1" <?php checked($row->popup_fullscreen, 1) ?>
                                               onchange="bwg_popup_fullscreen(1)"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_fullscreen_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_fullscreen"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_fullscreen_0"
                                               value="0" <?php checked($row->popup_fullscreen, 0);
                                               checked($row->popup_fullscreen, '') ?>onchange="bwg_popup_fullscreen(0)"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_fullscreen_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_width_height">
                                    <td title="Maximum values for lightbox width and height." class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_width">Lightbox dimensions: </label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_popup_width"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_width"
                                               value="<?php echo $row->popup_width == 0 ? '800' : $row->popup_width ?>"
                                               class="spider_int_input"/> x
                                        <input type="text" name="<?php echo WD_FB_PREFIX; ?>_popup_height"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_height"
                                               value="<?php echo $row->popup_height == 0 ? '500' : $row->popup_height ?>"
                                               class="spider_int_input"/> px
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_effect">
                                    <td title="Lightbox slideshow effect." class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_effect">Lightbox effect: </label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <select name="<?php echo WD_FB_PREFIX; ?>_popup_effect"
                                                id="<?php echo WD_FB_PREFIX; ?>_popup_effect" style="width:150px;">
                                            <?php
                                            $i=0;
                                            foreach ($effects as $key => $effect) {
                                                ?>
                                                <option <?php if($i>1) echo 'disabled'; ?>
                                                    value="<?php echo $key; ?>" <?php selected($row->popup_effect, $key) ?>><?php echo $effect; ?></option>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_autoplay">
                                    <td class="ffwd_set_l">
                                        <label>Lightbox autoplay: </label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_autoplay"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_autoplay_1" <?php checked($row->popup_autoplay, 1) ?>
                                               value="1"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_autoplay_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_autoplay"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_autoplay_0"
                                               value="0" <?php checked($row->popup_autoplay, 0);
                                        checked($row->popup_autoplay, '') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_autoplay_0">No</label>
                                    </td>
                                </tr>


                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_interval">
                                    <td title="Interval between two images." class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_interval">Time interval: </label>
                                    </td>
                                    <td><input type="text" name="<?php echo WD_FB_PREFIX; ?>_popup_interval"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_interval"
                                               value="<?php echo $row->popup_interval == 0 ? '3' : $row->popup_interval ?>"
                                               class="spider_int_input"/> sec.
                                    </td>
                                </tr>

                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_open_commentbox">
                                    <td class="ffwd_set_l">
                                        <label>Open comment box :</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_open_commentbox"
                                               id="<?php echo WD_FB_PREFIX; ?>_open_commentbox_1" <?php checked($row->open_commentbox, 1) ?>
                                               value="1"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_open_commentbox_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_open_commentbox"
                                               id="<?php echo WD_FB_PREFIX; ?>_open_commentbox_0"
                                               value="0" <?php checked($row->open_commentbox, 0);
                                        checked($row->open_commentbox, '') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_open_commentbox_0">No</label>
                                    </td>
                                </tr>


                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_filmstrip">
                                    <td title="Show filmstrip view for images" class="ffwd_set_l"><label>Show filmstrip
                                            in lightbox: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio"
                                               name="<?php echo WD_FB_PREFIX; ?>_popup_enable_filmstrip"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_yes" value="1"

                                               onClick="bwg_enable_disable('', '<?php echo WD_FB_PREFIX; ?>_tr_popup_filmstrip_height', '<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_yes')"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_yes">Yes</label>
                                        <input disabled type="radio"
                                               name="<?php echo WD_FB_PREFIX; ?>_popup_enable_filmstrip"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_no"
                                               value="0" checked
                                               onClick="bwg_enable_disable('none', '<?php echo WD_FB_PREFIX; ?>_tr_popup_filmstrip_height', '<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_no')"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_no">No</label>
                                        <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_filmstrip_height">
                                    <td class="ffwd_set_l"><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_height">Filmstrip
                                            size: </label></td>
                                    <td><input type="text" name="<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_height"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_filmstrip_height"
                                               value="<?php if ($row->popup_filmstrip_height == 0) echo '75'; else echo $row->popup_filmstrip_height ?>"
                                               class="spider_int_input"/> px
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_comments">
                                    <td title="Show comments" class="ffwd_set_l"><label>Show comments: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_comments"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_comments_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_comments_yes">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_comments"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_comments_0"
                                               value="0" <?php checked($row->popup_comments, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_comments_no">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_likes">
                                    <td title="Show likes" class="ffwd_set_l"><label>Show likes: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_likes"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_likes_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_likes_yes">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_likes"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_likes_0"
                                               value="0" <?php checked($row->popup_likes, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_likes_no">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_shares">
                                    <td title="Show share" class="ffwd_set_l"><label>Show share: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_shares"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_shares_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_shares_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_shares"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_shares_0"
                                               value="0" <?php checked($row->popup_shares, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_shares_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_author">
                                    <td title="Show author" class="ffwd_set_l"><label>Show author: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_author"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_author_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_author_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_author"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_author_0"
                                               value="0" <?php checked($row->popup_author, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_author_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_name">
                                    <td title="Show post name" class="ffwd_set_l"><label>Show post name: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_name_1" value="1"
                                               checked="checked"/><label for="<?php echo WD_FB_PREFIX; ?>_popup_name_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_name_0"
                                               value="0" <?php checked($row->popup_name, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_name_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_place_name">
                                    <td title="Show place name" class="ffwd_set_l"><label>Show place name: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_place_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_place_name_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_place_name_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_place_name"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_place_name_0"
                                               value="0" <?php checked($row->popup_place_name, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_place_name_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_ctrl_btn">
                                    <td title="Show control buttons in lightbox" class="ffwd_set_l"><label>Show control
                                            buttons: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_ctrl_btn"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_ctrl_btn_yes" value="1"
                                               onClick="bwg_enable_disable('', '<?php echo WD_FB_PREFIX; ?>_tbody_popup_ctrl_btn', 'popup_ctrl_btn_yes');"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_ctrl_btn_yes">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_ctrl_btn"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_ctrl_btn_no"
                                               value="0" <?php checked($row->popup_enable_ctrl_btn, 0) ?>
                                               onClick="bwg_enable_disable('none', '<?php echo WD_FB_PREFIX; ?>_tbody_popup_ctrl_btn', 'popup_ctrl_btn_no');"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_ctrl_btn_no">No</label>
                                    </td>
                                </tr>
                                </tbody>
                                <tbody id="<?php echo WD_FB_PREFIX; ?>_tbody_popup_ctrl_btn">
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_fullscreen">
                                    <td title="Show fullscreen view for images" class="ffwd_set_l"><label>Show
                                            fullscreen: </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_fullscreen"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_enable_fullscreen_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_enable_fullscreen_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_fullscreen"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_enable_fullscreen_0" <?php checked($row->popup_enable_fullscreen, 0) ?>
                                               value="0"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_enable_fullscreen_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_info_btn">
                                    <td title="Show object info button for view name etc" class="ffwd_set_l"><label>Show
                                            info and comments: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_info"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_enable_info_1" value="1"
                                               /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_enable_info_1">Yes</label>
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_info"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_enable_info_0"
                                               value="0" checked="checked" /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_enable_info_0">No</label>
                                        <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_message_desc">
                                    <td title="Show object info button for view name etc" class="ffwd_set_l"><label>Show
                                            message(description): </label></td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_message_desc"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_message_desc_1" value="1"
                                               checked="checked"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_message_desc_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_message_desc"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_message_desc_0"
                                               value="0" <?php checked($row->popup_message_desc, 0) ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_message_desc_0">No</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_facebook">
                                    <td title="Show Facebook share button for images" class="ffwd_set_l"><label>Show
                                            Facebook button: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_facebook"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_facebook_1" value="1"
                                               /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_facebook_1">Yes</label>
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_facebook"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_facebook_0"
                                               value="0" checked="checked" /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_facebook_0">No</label>
                                       <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_twitter">
                                    <td title="Show Twitter share button for images" class="ffwd_set_l"><label>Show
                                            Twitter button: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_twitter"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_twitter_1" value="1"
                                               /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_twitter_1">Yes</label>
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_twitter"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_twitter_0"
                                               value="0" checked="checked" /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_twitter_0">No</label>
                                        <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_google">
                                    <td title="Show Google+ share button for images" class="ffwd_set_l"><label>Show
                                            Google+ button: </label></td>
                                    <td class="ffwd_set_i">
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_google"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_google_1" value="1"
                                               /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_google_1">Yes</label>
                                        <input disabled type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_google"
                                               id="<?php echo WD_FB_PREFIX; ?>_popup_google_0"
                                               value="0" checked="checked" /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_popup_google_0">No</label>
                                        <br>
                                        <label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
                                            version</label>
                                    </td>
                                </tr>
                                <!-- <tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_pinterest">
										<td title="Show Pinterest share button for images" class="ffwd_set_l"><label>Show Pinterest button: </label></td>
										<td class="ffwd_set_i">
											<input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_pinterest" id="<?php echo WD_FB_PREFIX; ?>_popup_pinterest_1" value="1" /><label for="<?php echo WD_FB_PREFIX; ?>_popup_pinterest_1">Yes</label>
											<input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_pinterest" id="<?php echo WD_FB_PREFIX; ?>_popup_pinterest_0" value="0" checked="checked" /><label for="<?php echo WD_FB_PREFIX; ?>_popup_pinterest_0">No</label>
										</td>
									</tr>
									<tr id="<?php echo WD_FB_PREFIX; ?>_tr_popup_enable_tumblr">
										<td title="Show Tumblr share button for images" class="ffwd_set_l"><label>Show Tumblr button: </label></td>
										<td class="ffwd_set_i">
											<input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_tumblr" id="<?php echo WD_FB_PREFIX; ?>_popup_tumblr_1" value="1" /><label for="<?php echo WD_FB_PREFIX; ?>_popup_tumblr_1">Yes</label>
											<input type="radio" name="<?php echo WD_FB_PREFIX; ?>_popup_enable_tumblr" id="<?php echo WD_FB_PREFIX; ?>_popup_tumblr_0" value="0" checked="checked" /><label for="<?php echo WD_FB_PREFIX; ?>_popup_tumblr_0">No</label>
										</td>
									</tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="ffwd_p ffwd_comments_tab">
                    <div class="ffwd_header_c">
                        <label class="ffwd_header_l">Comments</label>
                    </div>
                    <div class="ffwd_varied_s">

                        <div class="ffwd_varied_s_c">

                            <table class="ffwd_sett_tabl">
                                <tr>
                                    <td class="ffwd_set_l">
                                        <label>Comments filter:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_comments_filter"
                                               id="<?php echo WD_FB_PREFIX; ?>_comments_filter_1"
                                               value="toplevel" <?php checked($row->comments_filter, 'toplevel');
                                        checked($row->comments_filter, '') ?>
                                               onchange="ffwd_show_hide_options('tr_comments_replies', 'table-row')"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_comments_filter_1">Toplevel</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_comments_filter"
                                               id="<?php echo WD_FB_PREFIX; ?>_comments_filter_0"
                                               value="stream" <?php checked($row->comments_filter, 'stream') ?>
                                               onchange="ffwd_show_hide_options('tr_comments_replies', 'none')"/><label
                                            for="<?php echo WD_FB_PREFIX; ?>_comments_filter_0">Stream</label>
                                        <div class="spider_description">
                                            Toplevel - same structure as they appear on Facebook.
                                            Comments count (excluding replies).<br>
                                            Stream - all-level comments.
                                            Comments count (including replies).
                                            <!-- stream-total count including replies, toplevel-total count without replies -->
                                        </div>
                                    </td>
                                </tr>
                                <tr id="tr_comments_replies">
                                    <td class="ffwd_set_l">
                                        <label>Show replies:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_comments_replies"
                                               id="<?php echo WD_FB_PREFIX; ?>_comments_replies_1"
                                               value="1" <?php if ($row->comments_replies) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_comments_replies_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_comments_replies"
                                               id="<?php echo WD_FB_PREFIX; ?>_comments_replies_0"
                                               value="0" <?php if (!$row->comments_replies) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_comments_replies_0">No</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ffwd_set_l">
                                        <label>Comments order:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_comments_order"
                                               id="<?php echo WD_FB_PREFIX; ?>_comments_order_1"
                                               value="chronological" <?php checked($row->comments_order, "chronological");
                                        checked($row->comments_order, "") ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_comments_order_1">Chronological</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_comments_order"
                                               id="<?php echo WD_FB_PREFIX; ?>_comments_order_0"
                                               value="reverse_chronological" <?php checked($row->comments_order, "reverse_chronological") ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_comments_order_0">Reverse
                                            chronological</label>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>


                <div class="ffwd_p ffwd_page_plugin_tab">
                    <div class="ffwd_header_c">
                        <label class="ffwd_header_l">Page plugin</label>
                    </div>
                    <div class="ffwd_varied_f">

                        <div class="ffwd_varied_f_pp">

                            <table class="ffwd_sett_tabl">
                                <tr id="tr_page_plugin_pos">
                                    <td class="ffwd_set_l">
                                        <label>Position:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_page_plugin_pos"
                                               id="<?php echo WD_FB_PREFIX; ?>_page_plugin_pos_1"
                                               value="top" <?php checked($row->page_plugin_pos, 'top');
                                        checked($row->page_plugin_pos, '') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_page_plugin_pos_1">Top</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_page_plugin_pos"
                                               id="<?php echo WD_FB_PREFIX; ?>_page_plugin_pos_0"
                                               value="bottom" <?php checked($row->page_plugin_pos, 'bottom') ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_page_plugin_pos_0">Bottom</label>
                                        <!-- <div class="spider_description"></div> -->
                                    </td>
                                </tr>
                                <tr id="tr_page_plugin_fans">
                                    <td class="ffwd_set_l">
                                        <label>Show fans:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_page_plugin_fans"
                                               id="<?php echo WD_FB_PREFIX; ?>_page_plugin_fans_1"
                                               value="1" <?php if ($row->page_plugin_fans) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_page_plugin_fans_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_page_plugin_fans"
                                               id="<?php echo WD_FB_PREFIX; ?>_page_plugin_fans_0"
                                               value="0" <?php if (!$row->page_plugin_fans) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_page_plugin_fans_0">No</label>
                                    </td>
                                </tr>
                                <tr id="tr_page_plugin_width">
                                    <td class="ffwd_set_l">
                                        <label>Width:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="number" id="<?php echo WD_FB_PREFIX; ?>_page_plugin_width"
                                               class="spider_int_input"
                                               name="<?php echo WD_FB_PREFIX; ?>_page_plugin_width" min="0" max="500"
                                               value="<?php echo $row->page_plugin_width == '' ? '380' : $row->page_plugin_width; ?>"/>px
                                    </td>
                                </tr>
                                <tr id="tr_page_plugin_cover">
                                    <td class="ffwd_set_l">
                                        <label>Hide cover photo:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_page_plugin_cover"
                                               id="<?php echo WD_FB_PREFIX; ?>_page_plugin_cover_1"
                                               value="1" <?php if ($row->page_plugin_cover) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_page_plugin_cover_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_page_plugin_cover"
                                               id="<?php echo WD_FB_PREFIX; ?>_page_plugin_cover_0"
                                               value="0" <?php if (!$row->page_plugin_cover) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_page_plugin_cover_0">No</label>
                                    </td>
                                </tr>
                                <tr id="tr_page_plugin_header">
                                    <td class="ffwd_set_l">
                                        <label>Use Small Header:</label>
                                    </td>
                                    <td class="ffwd_set_i">
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_page_plugin_header"
                                               id="<?php echo WD_FB_PREFIX; ?>_page_plugin_header_1"
                                               value="1" <?php if ($row->page_plugin_header) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_page_plugin_header_1">Yes</label>
                                        <input type="radio" name="<?php echo WD_FB_PREFIX; ?>_page_plugin_header"
                                               id="<?php echo WD_FB_PREFIX; ?>_page_plugin_header_0"
                                               value="0" <?php if (!$row->page_plugin_header) echo 'checked="checked"'; ?> /><label
                                            for="<?php echo WD_FB_PREFIX; ?>_page_plugin_header_0">No</label>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <input id="task" name="task" type="hidden" value=""/>
            <input id="current_id" name="current_id" type="hidden" value="<?php echo $row->id; ?>"/>
            <div id="opacity_div"
                 style="display: none; background-color: rgba(0, 0, 0, 0.2); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99998;"></div>
            <div id="loading_div"
                 style="display:none; text-align: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999;">
                <img src="<?php echo WD_FFWD_URL . '/images/ajax_loader.png'; ?>" class="spider_ajax_loading"
                     style="margin-top: 200px; width:50px;">
            </div>
        </form>
        <script>
            function ffwd_change_tab(that) {
                var show = that.getAttribute("show");
                jQuery('.ffwd_tab').removeClass('ffwd_tab_s');
                jQuery(that).addClass('ffwd_tab_s');
                jQuery('.ffwd_p').css('display', 'none');
                jQuery('.ffwd_' + show).css('display', 'block')
            }
            function ffwd_change_label(id, text) {
                jQuery('#' + id).html(text);
            }
            function ffwd_view_type(wd_fb_prefix, view_type, that) {
                var fb_type = jQuery("select[id=" + wd_fb_prefix + "_type]").find(":selected").val(),
                    fb_content_type = jQuery("input[name=" + wd_fb_prefix + "_content_type]:checked").val(),
                    fb_content = (fb_content_type == 'specific') ? jQuery('input[name=' + wd_fb_prefix + '_specific]:checked').val() : 'timeline_content';
                /*jQuery("#" + wd_fb_prefix + view_type).prop('checked', true);*/

                jQuery('.ffwd_view').removeClass('ffwd_view_s');
                jQuery(that).addClass('ffwd_view_s');
                jQuery(that).find('input[type="radio"]').attr('checked', 'checked');
                // Thumbnails, Masonry.
                jQuery("#" + wd_fb_prefix + "_tr_masonry_hor_ver").css('display', 'none');
                ffwd_change_label("col_num_label", 'Max. number of image columns');
                jQuery("#" + wd_fb_prefix + "_tr_image_max_columns").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_thumb_width_height").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_thumb_comments").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_thumb_likes").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_thumb_name").css('display', 'none');

                // Blog Style.
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_view_type").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_comments").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_likes").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_message_desc").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_shares").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_shares_butt").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_width").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_height").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_author").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_name").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_place_name").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_facebook").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_twitter").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_blog_style_google").css('display', 'none');


                //Album compact
                jQuery("#" + wd_fb_prefix + "_tr_compuct_album_column_number").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_compuct_albums_per_page").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_compuct_album_title_hover").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_compuct_album_thumb_width_height").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_compuct_album_image_column_number").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_compuct_album_images_per_page").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_compuct_album_image_title").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_compuct_album_image_thumb_width_height").css('display', 'none');

                // For all
                jQuery("#" + wd_fb_prefix + "_tr_fb_plugin").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_fb_name").css('display', 'none');

                // Popup.
                jQuery("#" + wd_fb_prefix + "_trtbody_popup").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_width_height").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_effect").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_interval").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_filmstrip").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_filmstrip_height").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_ctrl_btn").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_fullscreen").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_info_btn").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_facebook").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_twitter").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_google").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_pinterest").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_tumblr").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_comments").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_likes").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_shares").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_author").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_name").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_place_name").css('display', 'none');
                jQuery("#" + wd_fb_prefix + "_tr_popup_message_desc").css('display', 'none');

                switch (view_type) {
                    case 'thumbnails':
                    {
                        ffwd_change_label(wd_fb_prefix + '_image_max_columns_label', 'Max. number of image columns: ');
                        ffwd_change_label(wd_fb_prefix + '_thumb_width_height_label', 'Image thumbnail dimensions: ');
                        jQuery('#' + wd_fb_prefix + '_thumb_width').show();
                        jQuery('#' + wd_fb_prefix + '_thumb_height').show();
                        jQuery("#" + wd_fb_prefix + "_tr_image_max_columns").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_thumb_width_height").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_thumb_comments").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_thumb_likes").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_thumb_name").css('display', '');
                        break;

                    }
                    case 'thumbnails_masonry':
                    {
                        if (jQuery("input[name=" + wd_fb_prefix + "_masonry_hor_ver]:checked").val() == 'horizontal') {
                            ffwd_change_label(wd_fb_prefix + '_image_max_columns_label', 'Number of image rows: ');
                            ffwd_change_label(wd_fb_prefix + '_thumb_width_height_label', 'Image thumbnail height: ');
                            jQuery('#' + wd_fb_prefix + '_thumb_width').hide();
                            jQuery('#' + wd_fb_prefix + '_thumb_height').show();
                        }
                        else {
                            ffwd_change_label(wd_fb_prefix + '_image_max_columns_label', 'Max. number of image columns: ');
                            ffwd_change_label(wd_fb_prefix + '_thumb_width_height_label', 'Image thumbnail width: ');
                            jQuery('#' + wd_fb_prefix + '_thumb_width').show();
                            jQuery('#' + wd_fb_prefix + '_thumb_height').hide();
                            jQuery("#" + wd_fb_prefix + "_tr_thumb_name").css('display', '');
                        }
                        jQuery("#" + wd_fb_prefix + "_tr_masonry_hor_ver").css('display', '');
                        jQuery('#' + wd_fb_prefix + '_thumb_width_height_separator').hide();
                        jQuery("#" + wd_fb_prefix + "_tr_image_max_columns").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_thumb_width_height").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_thumb_comments").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_thumb_likes").css('display', '');
                        /*jQuery("#"+wd_fb_prefix+"_tr_thumb_name").css('display', '');*/
                        break;

                    }

                    case 'blog_style':
                    {
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_view_type").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_view_type").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_comments").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_likes").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_message_desc").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_shares").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_shares_butt").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_width").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_height").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_author").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_name").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_place_name").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_facebook").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_twitter").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_blog_style_google").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_popup_filmstrip_height").css('display', '');

                        break;
                    }

                    case 'album_compact':
                    {
                        jQuery("#" + wd_fb_prefix + "_tr_compuct_album_column_number").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_compuct_albums_per_page").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_compuct_album_title_hover").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_compuct_album_thumb_width_height").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_compuct_album_image_column_number").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_compuct_album_images_per_page").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_compuct_album_image_title").css('display', '');
                        jQuery("#" + wd_fb_prefix + "_tr_compuct_album_image_thumb_width_height").css('display', '');

                        break;
                    }
                }

                jQuery("#" + wd_fb_prefix + "_tr_fb_name").css('display', '');
                if (fb_type == "page")
                    jQuery("#" + wd_fb_prefix + "_tr_fb_plugin").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tbody_popup").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_width_height").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_effect").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_interval").css('display', '');
                if (view_type != 'blog_style' && view_type != 'album_compact' || fb_content_type == 'timeline') {
                    jQuery("#" + wd_fb_prefix + "_tr_popup_enable_filmstrip").css('display', '');
                    if (jQuery("input[name=" + wd_fb_prefix + "_popup_enable_filmstrip]:checked").val() == 1) {
                        bwg_enable_disable('', wd_fb_prefix + '_tr_popup_filmstrip_height', wd_fb_prefix + '_popup_filmstrip_yes');
                    }
                    else {
                        bwg_enable_disable('none', wd_fb_prefix + '_tr_popup_filmstrip_height', wd_fb_prefix + '_popup_filmstrip_no');
                    }
                } else {

                    bwg_enable_disable('none', wd_fb_prefix + '_tr_popup_filmstrip_height', wd_fb_prefix + '_popup_filmstrip_no');
                }
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_ctrl_btn").css('display', '');
                if (jQuery("input[name=" + wd_fb_prefix + "_popup_enable_ctrl_btn]:checked").val() == 1) {
                    bwg_enable_disable('', wd_fb_prefix + '_tbody_popup_ctrl_btn', wd_fb_prefix + '_popup_ctrl_btn_yes');
                }
                else {
                    bwg_enable_disable('none', wd_fb_prefix + '_tbody_popup_ctrl_btn', wd_fb_prefix + '_popup_ctrl_btn_no');
                }
                jQuery("#" + wd_fb_prefix + "_tr_popup_author").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_fullscreen").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_info_btn").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_facebook").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_twitter").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_google").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_pinterest").css('display', '');
                jQuery("#" + wd_fb_prefix + "_tr_popup_enable_tumblr").css('display', '');
                if (fb_content_type == "timeline") {
                    jQuery("#" + wd_fb_prefix + "_tr_popup_name").css('display', '');
                    jQuery("#" + wd_fb_prefix + "_tr_popup_place_name").css('display', '');
                    jQuery("#" + wd_fb_prefix + "_tr_popup_comments").css('display', '');
                    jQuery("#" + wd_fb_prefix + "_tr_popup_likes").css('display', '');
                    jQuery("#" + wd_fb_prefix + "_tr_popup_shares").css('display', '');
                    jQuery("#" + wd_fb_prefix + "_tr_popup_message_desc").css('display', '');
                    jQuery("#" + wd_fb_prefix + "_tr_popup_enable_filmstrip").css('display', '');

                    if (jQuery("input[name=" + wd_fb_prefix + "_popup_enable_filmstrip]:checked").val() == 1) {
                        bwg_enable_disable('', wd_fb_prefix + '_tr_popup_filmstrip_height', wd_fb_prefix + '_popup_filmstrip_yes');
                    }
                    else {
                        bwg_enable_disable('none', wd_fb_prefix + '_tr_popup_filmstrip_height', wd_fb_prefix + '_popup_filmstrip_no');
                    }
                }
                else {
                    switch (fb_content) {
                        case "photos":
                        case "videos":
                        case "albums":
                            jQuery("#" + wd_fb_prefix + "_tr_popup_name").css('display', '');
                            jQuery("#" + wd_fb_prefix + "_tr_popup_comments").css('display', '');
                            jQuery("#" + wd_fb_prefix + "_tr_popup_likes").css('display', '');
                            jQuery("#" + wd_fb_prefix + "_tr_popup_message_desc").css('display', '');
                            break;
                        case "events":
                            jQuery("#" + wd_fb_prefix + "_tr_popup_name").css('display', '');
                            jQuery("#" + wd_fb_prefix + "_tr_popup_comments").css('display', '');
                            jQuery("#" + wd_fb_prefix + "_tr_popup_message_desc").css('display', '');
                            jQuery("#" + wd_fb_prefix + "_tr_popup_place_name").css('display', '');
                            break;
                    }
                }
                ;


                jQuery('#ffwd_fb_view_type').val(view_type);
            }

            function toggle_lightbox_tab(val) {
                if (val == 'lightbox') {
                    jQuery('#ffwd_tab_lightbox').css('display', '');

                }
                else {

                    jQuery('#ffwd_tab_lightbox').css('display', 'none');

                }


            }


            function ffwd_toggle_page_plugin(val) {

                if (val == '1') {
                    jQuery('#ffwd_tab_page_plugin').css('display', '');

                }
                else {

                    jQuery('#ffwd_tab_page_plugin').css('display', 'none');

                }


            }


            jQuery(document).ready(function () {
                ffwd_view_type('<?php echo WD_FB_PREFIX; ?>', '<?php echo $row->fb_view_type ?>', jQuery('.<?php echo WD_FB_PREFIX; ?>_<?php echo $row->fb_view_type ?>'))
                choose_fb_content_type('<?php echo WD_FB_PREFIX; ?>', '<?php echo $row->content_type ?>')
                /*jQuery('body').on('click', '.ffwd_header_c', function () {
                 var elem = jQuery(this).parent().find('.ffwd_sett_tabl'),
                 d = (elem.css('display') == 'none') ? 'block' : 'none';
                 elem.css('display', d);
                 })*/


                bwg_popup_fullscreen(<?php echo $row->popup_fullscreen ?>);

                <?php

                if($row->comments_filter == 'stream')
                {
                ?>
                ffwd_show_hide_options('tr_comments_replies', 'none');

                <?php
                }

                ?>



                toggle_lightbox_tab('<?php echo $row->image_onclick_action ?>');
                ffwd_toggle_page_plugin('<?php echo $row->fb_plugin ?>');


            });

            function ffwd_show_hide_options(id, display) {
                jQuery("#" + id).css("display", display);
            }


        </script>
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

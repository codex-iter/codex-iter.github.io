<?php

class WDW_FFWD_Library {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  public static function get($key, $default_value = '') {
    if (isset($_GET[$key])) {
      $value = $_GET[$key];
    }
    elseif (isset($_POST[$key])) {
      $value = $_POST[$key];
    }
    else {
      $value = '';
    }
    if (!$value) {
      $value = $default_value;
    }
    return esc_html($value);
  }

  public static function message_id($message_id) {
    if ($message_id) {
      switch($message_id) {
        case 1: {
          $message = 'Item Succesfully Saved.';
          $type = 'updated';
          break;

        }
        case 2: {
          $message = 'Error. Please install plugin again.';
          $type = 'error';
          break;

        }
        case 3: {
          $message = 'Item Succesfully Deleted.';
          $type = 'updated';
          break;

        }
        case 4: {
          $message = "You can't delete default theme";
          $type = 'error';
          break;

        }
        case 5: {
          $message = 'Items Succesfully Deleted.';
          $type = 'updated';
          break;

        }
        case 6: {
          $message = 'You must select at least one item.';
          $type = 'error';
          break;

        }
        case 7: {
          $message = 'The item is successfully set as default.';
          $type = 'updated';
          break;

        }
        case 8: {
          $message = 'Options Succesfully Saved.';
          $type = 'updated';
          break;

        }
        case 9: {
          $message = 'Item Succesfully Published.';
          $type = 'updated';
          break;

        }
        case 10: {
          $message = 'Items Succesfully Published.';
          $type = 'updated';
          break;

        }
        case 11: {
          $message = 'Item Succesfully Unpublished.';
          $type = 'updated';
          break;

        }
        case 12: {
          $message = 'Items Succesfully Unpublished.';
          $type = 'updated';
          break;

        }
        case 13: {
          $message = 'Ordering Succesfully Saved.';
          $type = 'updated';
          break;

        }
        case 14: {
          $message = 'A term with the name provided already exists.';
          $type = 'error';
          break;

        }
        case 15: {
          $message = 'Name field is required.';
          $type = 'error';
          break;

        }
        case 16: {
          $message = 'The slug must be unique.';
          $type = 'error';
          break;

        }
        case 17: {
          $message = 'Changes must be saved.';
          $type = 'error';
          break;

        }
      }
      return '<div style="width:99%"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
    }
  }

  public static function message($message, $type) {
    return '<div style="width:99%"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
  }

  public static function search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function spider_search() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
        function spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          if (document.getElementById("search_select_value")) {
            document.getElementById("search_select_value").value = 0;
          }
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
        function check_search_key(e, that) {
          var key_code = (e.keyCode ? e.keyCode : e.which);
          if (key_code == 13) { /*Enter keycode*/
            spider_search();
            return false;
          }
          return true;
        }
      </script>
      <div class="alignleft actions" style="">
        <label for="search_value" style="font-size:14px; width:50px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <input type="text" id="search_value" name="search_value" class="spider_search_value" onkeypress="return check_search_key(event, this);" value="<?php echo esc_html($search_value); ?>" style="width: 150px;<?php echo (get_bloginfo('version') > '3.7') ? ' height: 28px;' : ''; ?>" />
      </div>
      <div class="alignleft actions">
        <input type="button" value="Search" onclick="spider_search()" class="ffwd-button-primary action">
        <input type="button" value="Reset" onclick="spider_reset()" class="ffwd-button-primary action">
      </div>
    </div>
    <?php
  }

  public static function search_select($search_by, $search_select_id = 'search_select_value', $search_select_value, $playlists, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function spider_search_select() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          document.getElementById("<?php echo $form_id; ?>").submit();
        }
      </script>
      <div class="alignleft actions" >
        <label for="<?php echo $search_select_id; ?>" style="font-size:14px; width:50px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <select id="<?php echo $search_select_id; ?>" name="<?php echo $search_select_id; ?>" onchange="spider_search_select();" style="float: none; width: 150px;">
        <?php
          foreach ($playlists as $id => $playlist) {
            ?>
            <option value="<?php echo $id; ?>" <?php echo (($search_select_value == $id) ? 'selected="selected"' : ''); ?>><?php echo $playlist; ?></option>
            <?php
          }
        ?>
        </select>
      </div>
    </div>
    <?php
  }

  public static function html_page_nav($count_items, $pager, $page_number, $form_id, $items_per_page = 20) {
    $limit = $items_per_page;
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    if (!$pager) {
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function spider_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        document.getElementById('<?php echo $form_id; ?>').submit();
      }
      function check_enter_key(e, that) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery(that).val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery(that).val();
          }
          document.getElementById('<?php echo $form_id; ?>').submit();
        }
        return true;
      }
    </script>
    <?php } ?>
    <div class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $items_per_page) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" href="javascript:spider_page(<?php echo $page_number; ?>,-2);">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" href="javascript:spider_page(<?php echo $page_number; ?>,-1);">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event, this)" title="Go to the page" type="text" size="1" />
        </span> of
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" href="javascript:spider_page(<?php echo $page_number; ?>,1);">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" href="javascript:spider_page(<?php echo $page_number; ?>,2);">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <?php if (!$pager) { ?>
    <input type="hidden" id="page_number"  name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
    }
  }
  public static function ajax_search($search_by, $search_value, $form_id) {
    ?>
    <div class="alignleft actions" style="clear:both;">
      <script>
        function spider_search() {
          document.getElementById("page_number").value = "1";
          document.getElementById("search_or_not").value = "search";
          spider_ajax_save('<?php echo $form_id; ?>');
        }
        function spider_reset() {
          if (document.getElementById("search_value")) {
            document.getElementById("search_value").value = "";
          }
          spider_ajax_save('<?php echo $form_id; ?>');
        }
        function check_search_key(e, that) {
          var key_code = (e.keyCode ? e.keyCode : e.which);
          if (key_code == 13) { /*Enter keycode*/
            spider_search();
            return false;
          }
          return true;
        }
      </script>
      <div class="alignleft actions" style="">
        <label for="search_value" style="font-size:14px; width:60px; display:inline-block;"><?php echo $search_by; ?>:</label>
        <input type="text" id="search_value" name="search_value" class="spider_search_value" onkeypress="return check_search_key(event, this);" value="<?php echo esc_html($search_value); ?>" style="width: 150px;<?php echo (get_bloginfo('version') > '3.7') ? ' height: 28px;' : ''; ?>" />
      </div>
      <div class="alignleft actions">
        <input type="button" value="Search" onclick="spider_search()" class="button-secondary action">
        <input type="button" value="Reset" onclick="spider_reset()" class="button-secondary action">
      </div>
    </div>
    <?php
  }

  public static function ajax_html_page_nav($count_items, $page_number, $form_id, $items_per_page = 20, $pager = 0) {
    $limit = $items_per_page;

    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    if (!$pager) {
    ?>
    <script type="text/javascript">
      var items_county = <?php echo $items_county; ?>;
      function spider_page(x, y) {
        switch (y) {
          case 1:
            if (x >= items_county) {
              document.getElementById('page_number').value = items_county;
            }
            else {
              document.getElementById('page_number').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number').value = items_county;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number').value = 1;
            }
            else {
              document.getElementById('page_number').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number').value = 1;
            break;
          default:
            document.getElementById('page_number').value = 1;
        }
        spider_ajax_save('<?php echo $form_id; ?>');
      }
      function check_enter_key(e, that) {
        var key_code = (e.keyCode ? e.keyCode : e.which);
        if (key_code == 13) { /*Enter keycode*/
          if (jQuery(that).val() >= items_county) {
           document.getElementById('page_number').value = items_county;
          }
          else {
           document.getElementById('page_number').value = jQuery(that).val();
          }
          spider_ajax_save('<?php echo $form_id; ?>');
          return false;
        }
       return true;
      }
    </script>
    <?php } ?>
    <div id="tablenav-pages" class="tablenav-pages">
      <span class="displaying-num">
        <?php
        if ($count_items != 0) {
          echo $count_items; ?> item<?php echo (($count_items == 1) ? '' : 's');
        }
        ?>
      </span>
      <?php
      if ($count_items > $limit) {
        $first_page = "first-page";
        $prev_page = "prev-page";
        $next_page = "next-page";
        $last_page = "last-page";
        if ($page_number == 1) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
          $next_page = "next-page";
          $last_page = "last-page";
        }
        if ($page_number >= $items_county) {
          $first_page = "first-page ";
          $prev_page = "prev-page";
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
      ?>
      <span class="pagination-links">
        <a class="<?php echo $first_page; ?>" title="Go to the first page" onclick="spider_page(<?php echo $page_number; ?>,-2)">«</a>
        <a class="<?php echo $prev_page; ?>" title="Go to the previous page" onclick="spider_page(<?php echo $page_number; ?>,-1)">‹</a>
        <span class="paging-input">
          <span class="total-pages">
          <input class="current_page" id="current_page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return check_enter_key(event, this)" title="Go to the page" type="text" size="1" />
        </span> of
        <span class="total-pages">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="Go to the next page" onclick="spider_page(<?php echo $page_number; ?>,1)">›</a>
        <a class="<?php echo $last_page ?>" title="Go to the last page" onclick="spider_page(<?php echo $page_number; ?>,2)">»</a>
        <?php
      }
      ?>
      </span>
    </div>
    <?php if (!$pager) { ?>
    <input type="hidden" id="page_number" name="page_number" value="<?php echo ((isset($_POST['page_number'])) ? (int) $_POST['page_number'] : 1); ?>" />
    <input type="hidden" id="search_or_not" name="search_or_not" value="<?php echo ((isset($_POST['search_or_not'])) ? esc_html($_POST['search_or_not']) : ''); ?>"/>
    <?php
    }
  }

	public static function ajax_html_frontend_page_nav($theme_row, $count_items, $page_number, $form_id, $items_per_page, $current_view, $id, $cur_alb_gal_id = 0, $type = 'album', $enable_seo = false, $pagination = 1) {
    $limit = $items_per_page;
    $limit = $limit ? $limit : 1;
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : $type);
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : $cur_alb_gal_id);
    if ($count_items) {
      if ($count_items % $limit) {
        $items_county = ($count_items - $count_items % $limit) / $limit + 1;
      }
      else {
        $items_county = ($count_items - $count_items % $limit) / $limit;
      }
    }
    else {
      $items_county = 1;
    }
    if ($page_number > $items_county) {
      return;
    }
    $first_page = "first-page-" . $current_view;
    $prev_page = "prev-page-" . $current_view;
    $next_page = "next-page-" . $current_view;
    $last_page = "last-page-" . $current_view;
    ?>
    <span class="ffwd_nav_cont_<?php echo $current_view; ?>">
			<?php
			if ($pagination == 1) {
				?>
				<div class="tablenav-pages_<?php echo $current_view; ?>">
					<?php
					if ($theme_row->page_nav_number) {
					?>
					<span class="displaying-num_<?php echo $current_view; ?>"><?php echo $count_items . __(' item(s)', 'bwg'); ?></span>
					<?php
					}
					if ($count_items > $limit) {
						if ($theme_row->page_nav_button_text) {
							$first_button = __('First', 'bwg');
							$previous_button = __('Previous', 'bwg');
							$next_button = __('Next', 'bwg');
							$last_button = __('Last', 'bwg');
						}
						else {
							$first_button = '«';
							$previous_button = '‹';
							$next_button = '›';
							$last_button = '»';
						}
						if ($page_number == 1) {
							$first_page = "first-page disabled";
							$prev_page = "prev-page disabled";
						}
						if ($page_number >= $items_county) {
							$next_page = "next-page disabled";
							$last_page = "last-page disabled";
						}
					?>
					<span class="pagination-links_<?php echo $current_view; ?>">
						<a class="<?php echo $first_page; ?>" title="<?php echo __('Go to the first page', 'bwg'); ?>"><?php echo $first_button; ?></a>
						<a class="<?php echo $prev_page; ?>" title="<?php echo __('Go to the previous page', 'bwg'); ?>" <?php echo  $page_number > 1 && $enable_seo ? 'href="' . add_query_arg(array("page_number_" . $current_view => $page_number - 1), $_SERVER['REQUEST_URI']) . '"' : ""; ?>><?php echo $previous_button; ?></a>
						<span class="paging-input_<?php echo $current_view; ?>">
							<span class="total-pages_<?php echo $current_view; ?>"><?php echo $page_number; ?></span> <?php echo __('of', 'bwg'); ?> <span class="total-pages_<?php echo $current_view; ?>">
								<?php echo $items_county; ?>
							</span>
						</span>
						<a class="<?php echo $next_page ?>" title="<?php echo __('Go to the next page', 'bwg'); ?>" <?php echo  $page_number + 1 <= $items_county && $enable_seo ? 'href="' . add_query_arg(array("page_number_" . $current_view => $page_number + 1), $_SERVER['REQUEST_URI']) . '"' : ""; ?>><?php echo $next_button; ?></a>
						<a class="<?php echo $last_page ?>" title="<?php echo __('Go to the last page', 'bwg'); ?>"><?php echo $last_button; ?></a>
					</span>
					<?php
					}
					?>
				</div>
				<?php
			}
			elseif ($pagination == 2) {
				if ($count_items > ($limit * ($page_number - 1)) + $items_per_page) {
					?>
					<div id="ffwd_load_<?php echo $current_view; ?>" class="tablenav-pages_<?php echo $current_view; ?>">
						<a class="ffwd_load_btn_<?php echo $current_view; ?> bwg_load_btn" href="javascript:void(0);"><?php echo __('Load More...', 'bwg'); ?></a>
						<input type="hidden" id="ffwd_load_more_<?php echo $current_view; ?>" name="ffwd_load_more_<?php echo $current_view; ?>" value="on" />
					</div>
					<?php
				}
			}
			elseif ($pagination == 3) {
				if ($count_items > $limit * $page_number) {
					?>
					<script type="text/javascript">
						if(jQuery('.blog_style_objects_conteiner_1_<?php echo $current_view; ?>').css('overflow') == 'auto'){
							jQuery('.blog_style_objects_conteiner_1_<?php echo $current_view; ?>').on("scroll", function() {
								if (jQuery('.blog_style_objects_conteiner_1_<?php echo $current_view; ?>').scrollTop() + (jQuery('.blog_style_objects_conteiner_1_<?php echo $current_view; ?>').height()) >= jQuery('.blog_style_objects_<?php echo $current_view; ?>').height()) {
									spider_page_<?php echo $current_view; ?>('', <?php echo $page_number; ?>, 1, true);
									jQuery('.blog_style_objects_conteiner_1_<?php echo $current_view; ?>').off("scroll");
									return false;
								}
							});
						}
						else
							jQuery(window).on("scroll", function() {
								if (jQuery(document).scrollTop() + jQuery(window).height() > (jQuery('#<?php echo $form_id; ?>').offset().top + jQuery('#<?php echo $form_id; ?>').height())) {
									spider_page_<?php echo $current_view; ?>('', <?php echo $page_number; ?>, 1, true);
									jQuery(window).off("scroll");
									return false;
								}
							});
					</script>
					<?php
				}
			}
			?>
			<input type="hidden" id="page_number_<?php echo $current_view; ?>" name="page_number_<?php echo $current_view; ?>" value="<?php echo ((isset($_POST['page_number_' . $current_view])) ? (int) $_POST['page_number_' . $current_view] : 1); ?>" />
			<script type="text/javascript">
				function spider_page_<?php echo $current_view; ?>(cur, x, y, load_more) {
					if (typeof load_more == "undefined") {
						var load_more = false;
					}
					if (jQuery(cur).hasClass('disabled')) {
						return false;
					}
					var items_county_<?php echo $current_view; ?> = <?php echo $items_county; ?>;
					switch (y) {
						case 1:
							if (x >= items_county_<?php echo $current_view; ?>) {
								document.getElementById('page_number_<?php echo $current_view; ?>').value = items_county_<?php echo $current_view; ?>;
							}
							else {
								document.getElementById('page_number_<?php echo $current_view; ?>').value = x + 1;
							}
							break;
						case 2:
							document.getElementById('page_number_<?php echo $current_view; ?>').value = items_county_<?php echo $current_view; ?>;
							break;
						case -1:
							if (x == 1) {
								document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
							}
							else {
								document.getElementById('page_number_<?php echo $current_view; ?>').value = x - 1;
							}
							break;
						case -2:
							document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
							break;
						default:
							document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
					}
					ffwd_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $id; ?>', '<?php echo $album_gallery_id; ?>', '', '<?php echo $type; ?>', 0, '', '', load_more);
				}
				jQuery(document).ready(function() {
					jQuery('.<?php echo $first_page; ?>').on('click', function() {
						spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, -2);
					});
					jQuery('.<?php echo $prev_page; ?>').on('click', function() {
						spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, -1);
						return false;
					});
					jQuery('.<?php echo $next_page; ?>').on('click', function() {
						spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 1);
						return false;
					});
					jQuery('.<?php echo $last_page; ?>').on('click', function() {
						spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 2);
					});
					jQuery('.ffwd_load_btn_<?php echo $current_view; ?>').on('click', function() {
						spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 1, true);
						return false;
					});
				});
			</script>
    </span>
    <?php
  }


  public static function ajax_html_frontend_search_box($form_id, $current_view, $cur_gal_id, $images_count, $search_box_width = 180) {
    $bwg_search = ((isset($_POST['bwg_search_' . $current_view]) && esc_html($_POST['bwg_search_' . $current_view]) != '') ? esc_html($_POST['bwg_search_' . $current_view]) : '');
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : 'album');
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : 0);
    ?>
    <style>
      .bwg_search_container_1 {
        display: inline-block;
        width: 100%;
        text-align: right;
        margin: 0 5px 20px 5px;
        background-color: rgba(0,0,0,0);
      }
      .bwg_search_container_2 {
        display: inline-block;
        position: relative;
        border-radius: 4px;
        box-shadow: 0 0 3px 1px #CCCCCC;
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        width: <?php echo $search_box_width; ?>px;
        max-width: 100%;
      }
      #bwg_search_container_1_<?php echo $current_view; ?> #bwg_search_container_2_<?php echo $current_view; ?> .bwg_search_input_container {
        display: block;
        margin-right: 45px;
      }
      #bwg_search_container_1_<?php echo $current_view; ?> #bwg_search_container_2_<?php echo $current_view; ?> .bwg_search_loupe_container {
        display: inline-block;
        margin-right: 1px;
        vertical-align: middle;
        float: right;
        padding-top: 3px;
      }
      #bwg_search_container_1_<?php echo $current_view; ?> #bwg_search_container_2_<?php echo $current_view; ?> .bwg_search_reset_container {
        display: inline-block;
        margin-right: 5px;
        vertical-align: middle;
        float: right;
        padding-top: 3px;
      }
      #bwg_search_container_1_<?php echo $current_view; ?> #bwg_search_container_2_<?php echo $current_view; ?> .bwg_search,
      #bwg_search_container_1_<?php echo $current_view; ?> #bwg_search_container_2_<?php echo $current_view; ?> .bwg_reset {
        font-size: 18px;
        color: #CCCCCC;
        cursor: pointer;
      }
      #bwg_search_container_1_<?php echo $current_view; ?> #bwg_search_container_2_<?php echo $current_view; ?> .bwg_search_input_<?php echo $current_view; ?>,
      #bwg_search_container_1_<?php echo $current_view; ?> #bwg_search_container_2_<?php echo $current_view; ?> .bwg_search_input_<?php echo $current_view; ?>:focus {
        color: hsl(0, 1%, 3%);
        outline: none;
        border: none;
        box-shadow: none;
        background: none;
        padding: 0 5px;
        font-family: inherit;
        width: 100%;
      }
    </style>
    <script type="text/javascript">
      function clear_input_<?php echo $current_view; ?> (current_view) {
        jQuery("#bwg_search_input_" + current_view).val('');
      }
      function check_enter_key(e) {
        var key_code = e.which || e.keyCode;
        if (key_code == 13) {
          ffwd_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1);
          return false;
        }
        return true;
      }
    </script>
    <div class="bwg_search_container_1" id="bwg_search_container_1_<?php echo $current_view; ?>">
      <div class="bwg_search_container_2" id="bwg_search_container_2_<?php echo $current_view; ?>">
        <span class="bwg_search_reset_container" >
          <i title="<?php echo __('Reset', 'bwg'); ?>" class="bwg_reset fa fa-times" onclick="clear_input_<?php echo $current_view; ?>('<?php echo $current_view; ?>'),ffwd_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1)"></i>
        </span>
        <span class="bwg_search_loupe_container" >
          <i title="<?php echo __('Search', 'bwg'); ?>" class="bwg_search fa fa-search" onclick="ffwd_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1)"></i>
        </span>
        <span class="bwg_search_input_container">
          <input id="bwg_search_input_<?php echo $current_view; ?>" class="bwg_search_input_<?php echo $current_view; ?>" type="text" onkeypress="return check_enter_key(event)" name="bwg_search_<?php echo $current_view; ?>" value="<?php echo $bwg_search; ?>" >
          <input id="bwg_images_count_<?php echo $current_view; ?>" class="bwg_search_input" type="hidden" name="bwg_images_count_<?php echo $current_view; ?>" value="<?php echo $images_count; ?>" >
        </span>
      </div>
    </div>
    <?php
  }

  public static function ajax_html_frontend_sort_box($form_id, $current_view, $cur_gal_id, $sort_by = '', $search_box_width = 180) {
    $bwg_search = ((isset($_POST['bwg_search_' . $current_view]) && esc_html($_POST['bwg_search_' . $current_view]) != '') ? esc_html($_POST['bwg_search_' . $current_view]) : '');
    $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : 'album');
    $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : 0);
    ?>
    <style>
      .bwg_order_cont_<?php echo $current_view; ?> {
        background-color: rgba(0,0,0,0);
        display: block;
        margin: 0 5px 20px 5px;
        text-align: right;
        width: 100%;
      }
      .bwg_order_label_<?php echo $current_view; ?> {
        border: none;
        box-shadow: none;
        color: #BBBBBB;
        font-family: inherit;
        font-weight: bold;
        outline: none;
      }
      .bwg_order_<?php echo $current_view; ?> {
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        box-shadow: 0 0 3px 1px #CCCCCC;
        border-radius: 4px;
        max-width: 100%;
        width: <?php echo $search_box_width; ?>px;
      }
    </style>
    <div class="bwg_order_cont_<?php echo $current_view; ?>">
      <span class="bwg_order_label_<?php echo $current_view; ?>"><?php echo __('Order by: ', 'bwg'); ?></span>
      <select class="bwg_order_<?php echo $current_view; ?>" onchange="ffwd_frontend_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1, '', this.value)">
        <option <?php if ($sort_by == 'default') echo 'selected'; ?> value="default"><?php echo __('Default', 'bwg'); ?></option>
        <option <?php if ($sort_by == 'filename') echo 'selected'; ?> value="filename"><?php echo __('Filename', 'bwg'); ?></option>
        <option <?php if ($sort_by == 'size') echo 'selected'; ?> value="size"><?php echo __('Size', 'bwg'); ?></option>
        <option <?php if ($sort_by == 'RAND()') echo 'selected'; ?> value="random"><?php echo __('Random', 'bwg'); ?></option>
      </select>
    </div>
    <?php
  }

  public static function spider_hex2rgb($colour) {
    if ($colour[0] == '#') {
      $colour = substr( $colour, 1 );
    }
    if (strlen($colour) == 6) {
      list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    }
    else if (strlen($colour) == 3) {
      list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    }
    else {
      return FALSE;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array('red' => $r, 'green' => $g, 'blue' => $b);
  }

  public static function spider_redirect($url) {
    ?>
    <script>
      window.location = "<?php echo $url; ?>";
    </script>
    <?php
    exit();
  }

 /**
  *  If string argument passed, put it into delimiters for AJAX response to separate from other data.
  */

  public static function delimit_wd_output($data) {

    if(is_string ( $data )){
      return "WD_delimiter_start". $data . "WD_delimiter_end";
    }
    else{
      return $data;
    }
  }

  public static function verify_nonce($page){
    $nonce_verified = false;
    if ( isset( $_GET['ffwd_nonce'] ) && wp_verify_nonce( $_GET['ffwd_nonce'], $page )) {
      $nonce_verified = true;
    }
    elseif ( isset( $_POST['ffwd_nonce'] ) && wp_verify_nonce( $_POST['ffwd_nonce'], $page )) {
      $nonce_verified = true;
    }
    return $nonce_verified;
  }

	public static function filter_params($params) {
		global $wpdb;
		$fb_id = isset($params['fb_id']) ? $params['fb_id'] : 0;
		$from = (isset($params['from']) ? esc_html($params['from']) : 0);
		$ffwd_info = $wpdb->get_row($wpdb->prepare("SELECT content_type,content FROM " . $wpdb->prefix . "wd_fb_info WHERE id='%d'", $fb_id));
		if($ffwd_info)
			switch($ffwd_info->content_type) {
				case "specific" :
					$is_ok = (($params['fb_view_type'] == "thumbnails"  && ($ffwd_info->content == "photos" || $ffwd_info->content == "events" || $ffwd_info->content == "videos")) ||
										($params['fb_view_type'] == "thumbnails_masonry" && ($ffwd_info->content == "photos" || $ffwd_info->content == "videos")) ||
										($params['fb_view_type'] == "blog_style" && ($ffwd_info->content == "events")) ||
										($params['fb_view_type'] == "album_compact" && $ffwd_info->content == "albums"));

					$params['fb_view_type'] = ($is_ok) ? $params['fb_view_type'] : (($ffwd_info->content == "albums") ? "album_compact" : "thumbnails");
				break;
				case "timeline" :
					$params['fb_view_type'] = "blog_style";
				break;
				default:
					echo WDW_FFWD_Library::message(__('Please update your shortcode.', 'bwg'), 'error');
					return 0;
				break;
			}
		else {
			echo WDW_FFWD_Library::message(__('There is no facebook feed selected or it was deleted.', 'bwg'), 'error');
			return 0;
		}
		$main_default_array = array(
			'fb_id' => 1,
			'theme_id' => 1,
			'ffwd_fb_plugin' => 0,
			'ffwd_fb_name' => 1,
			'pagination_type' => 0,
			'objects_per_page' => 10,
		);
		switch ($params['fb_view_type']) {
			case 'thumbnails': {
				$view_default_array = array(
					'fb_view_type' => 'thumbnails',
					'image_max_columns' => 5,
					'thumb_width' => 200,
					'thumb_height' => 150,
					'thumb_comments' => 1,
					'thumb_likes' => 1,
					'thumb_name' => 1,
				);
				break;
			}
			case 'thumbnails_masonry': {
				$view_default_array = array(
					'fb_view_type' => 'thumbnails_masonry',
					'image_max_columns' => 5,
					'thumb_width' => 200,
					'thumb_height' => 150,
					'masonry_hor_ver' => 'vertical',
					'thumb_comments' => 1,
					'thumb_likes' => 1,
					'thumb_name' => 0,
				);
				break;

			}
			case 'blog_style': {
				$view_default_array = array(
					'fb_view_type' => 'blog_style',
					'blog_style_width' => 700,
					'blog_style_height' => '',
					'blog_style_view_type' => 1,
					'blog_style_comments' => 1,
					'blog_style_likes' => 1,
					'blog_style_message_desc' => 1,
					'blog_style_shares_butt' => 1,
					'blog_style_shares' => 1,
					'blog_style_author' => 1,
					'blog_style_name' => 1,
					'blog_style_place_name' => 1,
					'blog_style_facebook' => 1,
					'blog_style_twitter' => 1,
					'blog_style_google' => 1,
				);
				break;
			}
			case 'album_compact': {
				$view_default_array = array(
					'fb_view_type' => 'album_compact',
					'album_max_columns' => 5,
					'album_thumb_width' => 200,
					'album_thumb_height' => 150,
					'album_title' => "hover",
					'thumb_width' => 200,
					'thumb_height' => 150,
					'image_max_columns' => 5,
				);
				break;
			}
			default: {
				$view_default_array = array(
					'fb_view_type' => 'thumbnails',
					'image_max_columns' => 5,
					'thumb_width' => 200,
					'thumb_height' => 150,
					'thumb_comments' => 1,
					'thumb_likes' => 1,
					'thumb_name' => 1,
				);
				break;
			}
		}
		$popup_default_array = array(
			'popup_fullscreen' => 0,
			'popup_autoplay' => 0,
			'popup_width' => 800,
			'popup_height' => 600,
			'popup_effect' => 'fade',
			'popup_interval' => 5,
			'popup_enable_filmstrip' => 0,
			'popup_filmstrip_height' => 70,
			'popup_enable_comments' => 1,
			'popup_enable_likes' => 1,
			'popup_enable_shares' => 1,
			'popup_enable_author' => 1,
			'popup_enable_name' => 1,
			'popup_enable_place_name' => 1,
			'popup_enable_message_desc' => 1,
			'popup_enable_ctrl_btn' => 1,
			'popup_enable_fullscreen' => 1,
			'popup_enable_info' => 1,
			'popup_enable_facebook' => 1,
			'popup_enable_twitter' => 1,
			'popup_enable_google' => 1,
			'popup_enable_pinterest' => 0,
			'popup_enable_tumblr' => 0,
		);

		$default_params_array = array_merge($main_default_array, $view_default_array, $popup_default_array);
    	$default_params_array=array($params);
		return shortcode_atts($default_params_array, $params);
	}
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}

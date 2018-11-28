<?php

class FFWDViewWidget
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
	}

	function widget($args, $instance)
	{
		extract($args);
		$title = (isset($instance['title']) ? $instance['title'] : "");
		$id = (isset($instance['id']) ? $instance['id'] : 0);
		$ffwd_info = $this->model->get_ffwd_feed($id);

      if(!isset($ffwd_info)) {
        echo "Please select facebook feed.";
        return;
      }


      $count = (isset($instance['count']) ? $instance['count'] : 4);
		$width = (isset($instance['width']) ? $instance['width'] : 100);
		$height = (isset($instance['height']) ? $instance['height'] : 100);
		$theme_id = $ffwd_info->theme;
		// Before widget.
		echo $before_widget;
		// Title of widget.
		if ($title) {
			echo $before_title . $title . $after_title;
		}


		// Widget output.
		global $ffwd;
		// Collapse right dimmensions
		switch ($ffwd_info->fb_view_type) {
			case 'thumbnails': {
				$dimmensions = array(
					'thumb_width' => $width,
					'thumb_height' => $height,
				);
				break;
			}
			case 'thumbnails_masonry': {
				$dimmensions = array(
					'thumb_width' => $width,
					'thumb_height' => $height,
				);
				break;

			}
			case 'blog_style': {
				$dimmensions = array(
					'thumb_width' => $width,
					'thumb_height' => $height,
				);
				break;
			}
			case 'album_compact': {
				$dimmensions = array(
					'album_thumb_width' => $width,
					'album_thumb_height' => $height,
					'thumb_width' => $width,
					'thumb_height' => $height,
				);
				break;
			}
			default: {
				$dimmensions = array(
					'width' => $width,
					'height' => $height,
				);
				break;
			}
		}
		$params = array(
			'from' => 'widget',
			'fb_id' => $id,
			'theme_id' => $theme_id,
			'objects_per_page' => $count
		);

		$params = array_merge($params, $dimmensions);

		require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
		//$params = WDW_FFWD_Library::filter_params($params);

		if ($ffwd_info->fb_view_type == 'thumbnails' || $ffwd_info->fb_view_type == 'thumbnails_masonry' || $ffwd_info->fb_view_type == 'album_compact' || $ffwd_info->fb_view_type == 'blog_style') {
			require_once(WD_FFWD_DIR . '/frontend/controllers/FFWDControllerMain.php');
			$controller = new FFWDControllerMain($params, 1, $ffwd, ucfirst($ffwd_info->fb_view_type));
			$ffwd++;
		} else {

			echo "Please select facebook feed.";
		}
		// After widget.
		echo $after_widget;
	}

	// Widget Control Panel.
	function form($instance, $id_title, $name_title, $id, $ffwd_feed_name, $id_count, $name_count, $id_width, $name_width, $id_height, $name_height, $id_theme_id, $name_theme_id, $id_view_type, $name_view_type)
	{
		$defaults = array(
			'title' => 'Facebook feed',
			'id' => 0,
			'count' => 4,
			'width' => 100,
			'height' => 100,
		);

		$instance = wp_parse_args((array)$instance, $defaults);
		$ffwd_feeds = $this->model->get_ffwd_feeds();
		?>
		<script>
			function ffwd_view_type(select) {
				var sel_option = select.options[select.options.selectedIndex],
					sel_option_con = sel_option.getAttribute("content"),
					sel_option_con_type = sel_option.getAttribute("content_type");
				jQuery(".ffwd_sel_thumbnail_view_cont").hide();
				jQuery(".ffwd_sel_masonry_view_cont").hide();
				jQuery(".ffwd_sel_album_compact_view_cont").hide();
				jQuery(".ffwd_sel_blog_style_view_cont").hide();
				if (sel_option_con_type == "timeline") {
					jQuery(".ffwd_sel_blog_style_view_cont").show();
				}
				else if (sel_option_con_type == "specific") {
					switch (sel_option_con) {
						case "albums":
							jQuery(".ffwd_sel_album_compact_view_cont").show();
							break;
						case "photos":
							jQuery(".ffwd_sel_thumbnail_view_cont").show();
							jQuery(".ffwd_sel_masonry_view_cont").show();
							break;
						case "videos":
							jQuery(".ffwd_sel_thumbnail_view_cont").show();
							jQuery(".ffwd_sel_masonry_view_cont").show();
							break;
						case "events":
							jQuery(".ffwd_sel_blog_style_view_cont").show();
							jQuery(".ffwd_sel_thumbnail_view_cont").show();
							break;
						default:
							jQuery(".ffwd_sel_thumbnail_view_cont").show();
							break;
					}
				}
			}
		</script>
		<p>
			<label for="<?php echo $id_title; ?>">Title:</label>
			<input class="widefat" id="<?php echo $id_title; ?>" name="<?php echo $name_title; ?>'" type="text"
				   value="<?php echo $instance['title']; ?>"/>
		</p>
		<p id="p_ffwd_feeds">
			<select name="<?php echo $ffwd_feed_name; ?>" id="<?php echo $id; ?>" class="widefat"
					onchange="ffwd_view_type(this)">
				<option value="0">Select facebook feed</option>
				<?php
				foreach ($ffwd_feeds as $ffwd_feed) {
					?>
					<option
						value="<?php echo $ffwd_feed->id; ?>" <?php echo(($instance['id'] == $ffwd_feed->id) ? 'selected="selected"' : ''); ?>
						content_type="<?php echo $ffwd_feed->content_type; ?>"
						content="<?php echo $ffwd_feed->content; ?>"><?php echo $ffwd_feed->name; ?></option>
					<?php
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $id_count; ?>">Count:</label>
			<input class="widefat" style="width:25%;" id="<?php echo $id_count; ?>" name="<?php echo $name_count; ?>'"
				   type="text" value="<?php echo $instance['count']; ?>"/>
		</p>
		<p>
			<label style="display: block;" for="<?php echo $id_width; ?>">Dimensions <br>(Leave height empty blank if you do not intend to limit the height of the widget.):</label>
			<input class="widefat" style="width:25%;" id="<?php echo $id_width; ?>" name="<?php echo $name_width; ?>'"
			       type="text" value="<?php echo $instance['width']; ?>"/> x
			<input class="widefat" style="width:25%;" id="<?php echo $id_height; ?>" name="<?php echo $name_height; ?>'"
			       type="text" value="<?php echo $instance['height']; ?>"/> px
		</p>

		<script>
			ffwd_view_type(document.getElementById("<?php echo $id; ?>"));
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

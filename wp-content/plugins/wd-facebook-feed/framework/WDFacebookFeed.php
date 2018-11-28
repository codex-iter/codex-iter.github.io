<?php


class WDFacebookFeed {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
	protected static $fb_type;
  protected static $facebook_sdk;
  protected static $graph_url = 'https://graph.facebook.com/{FB_ID}/{EDGE}?{ACCESS_TOKEN}{FIELDS}{LIMIT}{OTHER}';
  protected static $id;
  protected static $fb_valid_types = array('page', 'group', 'profile');
  protected static $valid_content_types = array('timeline', 'specific');
  protected static $content_url;
  protected static $content_type;
	protected static $timeline_type;
  protected static $content;
  protected static $limit;
  protected static $data;
	// For collapse timeline data matching content requirements
	protected static $complite_timeline_data = array();
	// Maximum graph call integer for timeline type
	protected static $timeline_max_call_count = 10;
  protected static $valid_content = array('statuses', 'photos', 'videos', 'links', 'events', 'albums');
  protected static $access_token;
  protected static $exist_access = false;
    protected static $auto_update_feed=0;

    protected static $updateOnVersionChange=false;



    // Existing app ids and app secrets
  protected static $access_tokens = array ();
	protected static $save = true;
	protected static $edit_feed = false;
	protected static $update_mode = 'keep_old';
	protected static $fb_id;
	private static $ffwd_fb_massage = true;

	public static $client_side_check = array();
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

  public static function execute() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        if (defined( 'DOING_AJAX' ) && DOING_AJAX )
        {
          die('Access Denied');
        }
      }
    }
    else {
        die('Access Denied');
    }
    require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
    $action = WDW_FFWD_Library::get('action');

    if(!WDW_FFWD_Library::verify_nonce('')){
      die(WDW_FFWD_Library::delimit_wd_output(json_encode(array("error", "Sorry, your nonce did not verify."))));
    }

		if (method_exists('WDFacebookFeed', $action)) {
			call_user_func(array('WDFacebookFeed', $action));
    }
    else {
      call_user_func(array('WDFacebookFeed', 'wd_fb_massage'), array('error', 'Unknown action'));
    }
  }

	public static function save_facebook_feed() {
		$id = (isset($_POST['current_id']) && $_POST['current_id'] != '') ? (int) esc_html(stripslashes($_POST['current_id'])) : 0;
		if($id) {
			self::$fb_id = $id;
			self::$edit_feed = true;
			self::$save = false;
		}
		else {
			self::$save = true;
		}
		self::check_fb_type();
	}

	// Edit facebook feed
	public static function edit_feed() {
		global $wpdb;
		$update_wd_fb_data = false;
		$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', self::$fb_id));
///////////////////////Araqel
$ffwd_info_options=array();
$ffwd_info_options['theme']=((isset($_POST['theme'])) ? esc_html(stripslashes($_POST['theme'])) : '');
$ffwd_info_options['masonry_hor_ver']=((isset($_POST['masonry_hor_ver'])) ? esc_html(stripslashes($_POST['masonry_hor_ver'])) : '');
$ffwd_info_options['image_max_columns']=((isset($_POST['image_max_columns'])) ? esc_html(stripslashes($_POST['image_max_columns'])) : '');
$ffwd_info_options['thumb_width']=((isset($_POST['thumb_width'])) ? esc_html(stripslashes($_POST['thumb_width'])) : '');
$ffwd_info_options['thumb_height']=((isset($_POST['thumb_height'])) ? esc_html(stripslashes($_POST['thumb_height'])) : '');
$ffwd_info_options['thumb_comments']=((isset($_POST['thumb_comments'])) ? esc_html(stripslashes($_POST['thumb_comments'])) : '');
$ffwd_info_options['thumb_likes']=((isset($_POST['thumb_likes'])) ? esc_html(stripslashes($_POST['thumb_likes'])) : '');
$ffwd_info_options['thumb_name']=((isset($_POST['thumb_name'])) ? esc_html(stripslashes($_POST['thumb_name'])) : '');
$ffwd_info_options['blog_style_width']=((isset($_POST['blog_style_width'])) ? esc_html(stripslashes($_POST['blog_style_width'])) : '');
$ffwd_info_options['blog_style_height']=((isset($_POST['blog_style_height'])) ? esc_html(stripslashes($_POST['blog_style_height'])) : '');
$ffwd_info_options['blog_style_view_type']=((isset($_POST['blog_style_view_type'])) ? esc_html(stripslashes($_POST['blog_style_view_type'])) : '');
$ffwd_info_options['blog_style_comments']=((isset($_POST['blog_style_comments'])) ? esc_html(stripslashes($_POST['blog_style_comments'])) : '');
$ffwd_info_options['blog_style_likes']=((isset($_POST['blog_style_likes'])) ? esc_html(stripslashes($_POST['blog_style_likes'])) : '');
$ffwd_info_options['blog_style_message_desc']=((isset($_POST['blog_style_message_desc'])) ? esc_html(stripslashes($_POST['blog_style_message_desc'])) : '');
$ffwd_info_options['blog_style_shares']=((isset($_POST['blog_style_shares'])) ? esc_html(stripslashes($_POST['blog_style_shares'])) : '');
$ffwd_info_options['blog_style_shares_butt']=((isset($_POST['blog_style_shares_butt'])) ? esc_html(stripslashes($_POST['blog_style_shares_butt'])) : '');
$ffwd_info_options['blog_style_facebook']=((isset($_POST['blog_style_facebook'])) ? esc_html(stripslashes($_POST['blog_style_facebook'])) : '');
$ffwd_info_options['blog_style_twitter']=((isset($_POST['blog_style_twitter'])) ? esc_html(stripslashes($_POST['blog_style_twitter'])) : '');
$ffwd_info_options['blog_style_google']=((isset($_POST['blog_style_google'])) ? esc_html(stripslashes($_POST['blog_style_google'])) : '');
$ffwd_info_options['blog_style_author']=((isset($_POST['blog_style_author'])) ? esc_html(stripslashes($_POST['blog_style_author'])) : '');
$ffwd_info_options['blog_style_name']=((isset($_POST['blog_style_name'])) ? esc_html(stripslashes($_POST['blog_style_name'])) : '');
$ffwd_info_options['blog_style_place_name']=((isset($_POST['blog_style_place_name'])) ? esc_html(stripslashes($_POST['blog_style_place_name'])) : '');
$ffwd_info_options['fb_name']=((isset($_POST['fb_name'])) ? esc_html(stripslashes($_POST['fb_name'])) : '');
$ffwd_info_options['fb_plugin']=((isset($_POST['fb_plugin'])) ? esc_html(stripslashes($_POST['fb_plugin'])) : '');
$ffwd_info_options['album_max_columns']=((isset($_POST['album_max_columns'])) ? esc_html(stripslashes($_POST['album_max_columns'])) : '');
$ffwd_info_options['album_title']=((isset($_POST['album_title'])) ? esc_html(stripslashes($_POST['album_title'])) : '');
$ffwd_info_options['album_thumb_width']=((isset($_POST['album_thumb_width'])) ? esc_html(stripslashes($_POST['album_thumb_width'])) : '');
$ffwd_info_options['album_thumb_height']=((isset($_POST['album_thumb_height'])) ? esc_html(stripslashes($_POST['album_thumb_height'])) : '');
$ffwd_info_options['album_image_max_columns']=((isset($_POST['album_image_max_columns'])) ? esc_html(stripslashes($_POST['album_image_max_columns'])) : '');
$ffwd_info_options['album_image_thumb_width']=((isset($_POST['album_image_thumb_width'])) ? esc_html(stripslashes($_POST['album_image_thumb_width'])) : '');
$ffwd_info_options['album_image_thumb_height']=((isset($_POST['album_image_thumb_height'])) ? esc_html(stripslashes($_POST['album_image_thumb_height'])) : '');
$ffwd_info_options['pagination_type']=((isset($_POST['pagination_type'])) ? esc_html(stripslashes($_POST['pagination_type'])) : '');
$ffwd_info_options['objects_per_page']=((isset($_POST['objects_per_page'])) ? esc_html(stripslashes($_POST['objects_per_page'])) : '');
$ffwd_info_options['popup_fullscreen']=((isset($_POST['popup_fullscreen'])) ? esc_html(stripslashes($_POST['popup_fullscreen'])) : '');
$ffwd_info_options['popup_height']=((isset($_POST['popup_height'])) ? esc_html(stripslashes($_POST['popup_height'])) : '');
$ffwd_info_options['popup_width']=((isset($_POST['popup_width'])) ? esc_html(stripslashes($_POST['popup_width'])) : '');
$ffwd_info_options['popup_effect']=((isset($_POST['popup_effect'])) ? esc_html(stripslashes($_POST['popup_effect'])) : '');
$ffwd_info_options['popup_autoplay']=((isset($_POST['popup_autoplay'])) ? esc_html(stripslashes($_POST['popup_autoplay'])) : '');
$ffwd_info_options['open_commentbox']=((isset($_POST['open_commentbox'])) ? esc_html(stripslashes($_POST['open_commentbox'])) : '');
$ffwd_info_options['popup_interval']=((isset($_POST['popup_interval'])) ? esc_html(stripslashes($_POST['popup_interval'])) : '');
$ffwd_info_options['popup_enable_filmstrip']=((isset($_POST['popup_enable_filmstrip'])) ? esc_html(stripslashes($_POST['popup_enable_filmstrip'])) : '');
$ffwd_info_options['popup_filmstrip_height']=((isset($_POST['popup_filmstrip_height'])) ? esc_html(stripslashes($_POST['popup_filmstrip_height'])) : '');
$ffwd_info_options['popup_comments']=((isset($_POST['popup_comments'])) ? esc_html(stripslashes($_POST['popup_comments'])) : '');
$ffwd_info_options['popup_likes']=((isset($_POST['popup_likes'])) ? esc_html(stripslashes($_POST['popup_likes'])) : '');
$ffwd_info_options['popup_shares']=((isset($_POST['popup_shares'])) ? esc_html(stripslashes($_POST['popup_shares'])) : '');
$ffwd_info_options['popup_author']=((isset($_POST['popup_author'])) ? esc_html(stripslashes($_POST['popup_author'])) : '');
$ffwd_info_options['popup_name']=((isset($_POST['popup_name'])) ? esc_html(stripslashes($_POST['popup_name'])) : '');
$ffwd_info_options['popup_place_name']=((isset($_POST['popup_place_name'])) ? esc_html(stripslashes($_POST['popup_place_name'])) : '');
$ffwd_info_options['popup_enable_ctrl_btn']=((isset($_POST['popup_enable_ctrl_btn'])) ? esc_html(stripslashes($_POST['popup_enable_ctrl_btn'])) : '');
$ffwd_info_options['popup_enable_fullscreen']=((isset($_POST['popup_enable_fullscreen'])) ? esc_html(stripslashes($_POST['popup_enable_fullscreen'])) : '');
$ffwd_info_options['popup_enable_info_btn']=((isset($_POST['popup_enable_info_btn'])) ? esc_html(stripslashes($_POST['popup_enable_info_btn'])) : '');
$ffwd_info_options['popup_message_desc']=((isset($_POST['popup_message_desc'])) ? esc_html(stripslashes($_POST['popup_message_desc'])) : '');
$ffwd_info_options['popup_enable_facebook']=((isset($_POST['popup_enable_facebook'])) ? esc_html(stripslashes($_POST['popup_enable_facebook'])) : '');
$ffwd_info_options['popup_enable_twitter']=((isset($_POST['popup_enable_twitter'])) ? esc_html(stripslashes($_POST['popup_enable_twitter'])) : '');
$ffwd_info_options['popup_enable_google']=((isset($_POST['popup_enable_google'])) ? esc_html(stripslashes($_POST['popup_enable_google'])) : '');
$ffwd_info_options['fb_view_type']=((isset($_POST['fb_view_type'])) ? esc_html(stripslashes($_POST['fb_view_type'])) : '');
$ffwd_info_options['image_onclick_action']=((isset($_POST['image_onclick_action'])) ? esc_html(stripslashes($_POST['image_onclick_action'])) : 'lightbox');

$ffwd_options_db=array('view_on_fb','post_text_length','event_street','event_city','event_country','event_zip','event_map','event_date','event_desp_length','comments_replies','comments_filter','comments_order','page_plugin_pos','page_plugin_fans','page_plugin_cover','page_plugin_header','page_plugin_width', 'fb_page_id');

foreach($ffwd_options_db as $ffwd_option_db)
{

$ffwd_info_options[$ffwd_option_db]	=((isset($_POST[$ffwd_option_db])) ? esc_html(stripslashes($_POST[$ffwd_option_db])) : '');
}

////////////////////////
		$name = ((isset($_POST['name'])) ? esc_html(stripslashes($_POST['name'])) : '');
    $page_access_token = ((isset($_POST['page_access_token'])) ? esc_html(stripslashes($_POST['page_access_token'])) : '');

//    $new_access_token = self::update_page_access_token($page_access_token);
//    if($new_access_token["success"]){
//      $page_access_token = $new_access_token["new_token"];
//    }
    $update_mode = ((isset($_POST['update_mode'])) ? esc_html(stripslashes($_POST['update_mode'])) : '');
		$published = ((isset($_POST['published'])) ? (int) esc_html(stripslashes($_POST['published'])) : 1);
    $content = implode(",", self::$content);
		$from = self::$id;
		$update_wd_fb_data =  (
														($row->type != self::$fb_type) ||
														($row->content_type != self::$content_type) ||
														($row->content != $content) ||
														($row->from != $from) ||
														($row->timeline_type != self::$timeline_type)||
                                                        (self::$auto_update_feed != 0) ||
														($row->limit != self::$limit)
													);


		if(self::$fb_type=='group')
			self::$timeline_type='feed';
		$save = $wpdb->update($wpdb->prefix . 'wd_fb_info', array(
			'name' => $name,
      'page_access_token'   => $page_access_token,
      'type' => self::$fb_type,
      'content_type' => self::$content_type,
      'content' => $content,
      'content_url' => self::$content_url,
      'timeline_type' => self::$timeline_type,
      'from' => $from,
      'limit' => self::$limit,
      'app_id' => '',
      'app_secret' => '',
      'exist_access' => 1,
      'access_token' => self::$access_token,
      'published' => $published,
			'update_mode' => $update_mode,
			),
			array('id' => self::$fb_id)
		);


		if ($save !== FALSE) {
			self::update_wd_fb_info_options($ffwd_info_options);
			if($update_wd_fb_data) {
				$delete_query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d"', self::$fb_id);
				$delete = $wpdb->query($delete_query);
				if ($delete === false) {
					self::wd_fb_massage('error', 'Something went wrong (trying delete wd_fb_data)');
				} else {
				  $data = self::$data['data'];
				  self::insert_wd_fb_data($data);
				}
			}
			self::wd_fb_massage('success', self::$fb_id);
		}
		else {
			self::wd_fb_massage('error', 'Something went wrong (trying edit feed)');
		}
	}

	function insert_wd_fb_info_options($options)
	{	global $wpdb;



	}


	static function update_wd_fb_info_options($options)
	{	global $wpdb;



		$save = $wpdb->update($wpdb->prefix . 'wd_fb_info', array(

			'theme' =>$options['theme'],
			'masonry_hor_ver' =>$options['masonry_hor_ver'],
			'image_max_columns' =>$options['image_max_columns'],
			'thumb_width' =>$options['thumb_width'],
			'thumb_height' =>$options['thumb_height'],
			'thumb_comments' =>$options['thumb_comments'],
			'thumb_likes' =>$options['thumb_likes'],
			'thumb_name' =>$options['thumb_name'],
			'blog_style_width' =>$options['blog_style_width'],
			'blog_style_height' =>$options['blog_style_height'],
			'blog_style_view_type' =>$options['blog_style_view_type'],
			'blog_style_comments' =>$options['blog_style_comments'],
			'blog_style_likes' =>$options['blog_style_likes'],
			'blog_style_message_desc' =>$options['blog_style_message_desc'],
			'blog_style_shares' =>$options['blog_style_shares'],
			'blog_style_shares_butt' =>$options['blog_style_shares_butt'],
			'blog_style_facebook' =>$options['blog_style_facebook'],
			'blog_style_twitter' =>$options['blog_style_twitter'],
			'blog_style_google' =>$options['blog_style_google'],
			'blog_style_author' =>$options['blog_style_author'],
			'blog_style_name' =>$options['blog_style_name'],
			'blog_style_place_name' =>$options['blog_style_place_name'],
			'fb_name' =>$options['fb_name'],
			'fb_plugin' =>$options['fb_plugin'],
			'album_max_columns' =>$options['album_max_columns'],
			'album_title' =>$options['album_title'],
			'album_thumb_width' =>$options['album_thumb_width'],
			'album_thumb_height' =>$options['album_thumb_height'],
			'album_image_max_columns' =>$options['album_image_max_columns'],
			'album_image_thumb_width' =>$options['album_image_thumb_width'],
			'album_image_thumb_height' =>$options['album_image_thumb_height'],
			'pagination_type' =>$options['pagination_type'],
			'objects_per_page' =>$options['objects_per_page'],
			'popup_fullscreen' =>$options['popup_fullscreen'],
			'popup_height' =>$options['popup_height'],
			'popup_width' =>$options['popup_width'],
			'popup_effect' =>$options['popup_effect'],
			'popup_autoplay' =>$options['popup_autoplay'],
			'open_commentbox' =>$options['open_commentbox'],
			'popup_interval' =>$options['popup_interval'],
			'popup_enable_filmstrip' =>$options['popup_enable_filmstrip'],
			'popup_filmstrip_height' =>$options['popup_filmstrip_height'],
			'popup_comments' =>$options['popup_comments'],
			'popup_likes' =>$options['popup_likes'],
			'popup_shares' =>$options['popup_shares'],
			'popup_author' =>$options['popup_author'],
			'popup_name' =>$options['popup_name'],
			'popup_place_name' =>$options['popup_place_name'],
			'popup_enable_ctrl_btn' =>$options['popup_enable_ctrl_btn'],
			'popup_enable_fullscreen' =>$options['popup_enable_fullscreen'],
			'popup_enable_info_btn' =>$options['popup_enable_info_btn'],
			'popup_message_desc' =>$options['popup_message_desc'],
			'popup_enable_facebook' =>$options['popup_enable_facebook'],
			'popup_enable_twitter' =>$options['popup_enable_twitter'],
			'popup_enable_google' =>$options['popup_enable_google']	,
			'fb_view_type' =>$options['fb_view_type'],
			'view_on_fb' =>$options['view_on_fb'],
			'post_text_length' =>$options['post_text_length'],
			'event_street' =>$options['event_street'],
			'event_city' =>$options['event_city'],
			'event_country' =>$options['event_country'],
			'event_zip' =>$options['event_zip'],
			'event_map' =>$options['event_map'],
			'event_date' =>$options['event_date'],
			'event_desp_length' =>$options['event_desp_length'],
			'comments_replies' =>$options['comments_replies'],
			'comments_filter' =>$options['comments_filter'],
			'comments_order' =>$options['comments_order'],
			'page_plugin_pos' =>$options['page_plugin_pos'],
			'page_plugin_fans' =>$options['page_plugin_fans'],
			'page_plugin_cover' =>$options['page_plugin_cover'],
			'page_plugin_header' =>$options['page_plugin_header'],
			'page_plugin_width'	 =>$options['page_plugin_width'],
			'image_onclick_action'	 =>$options['image_onclick_action'],
      'fb_page_id'               => $options['fb_page_id'],
		),
			array('id' => self::$fb_id)
		);
	}




	// Prepare to delete
	public static function prepare_to_delete($rows = array()) {
		foreach($rows as $row) {
			self::$fb_id = isset($row->id) ? $row->id : '';
			self::$fb_type = isset($row->type) ? $row->type : '';
			self::$content_type = isset($row->content_type) ? $row->content_type : '';
			self::$content = isset($row->content) ? explode(",", $row->content) : array();
			self::$content_url = isset($row->content_url) ? $row->content_url : '';
			self::$limit = isset($row->limit) ? $row->limit : '';
			self::$id = isset($row->from) ? $row->from : '';
			self::$access_token = isset($row->page_access_token) ? $row->page_access_token : '';

			self::$update_mode = isset($row->update_mode) ? $row->update_mode : self::$update_mode;
			self::get_rows_for_delete();
		}
  }

	public static function get_rows_for_delete($rows = array()) {
	  global $wpdb;
		$id = self::$fb_id;
		$rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` ASC ', $id));
		$client_side_check = array();
		foreach($rows as $row) {
			$data_for_client_side = new stdclass();
			$fields = 'fields=id&';
			$object_id = $row->object_id;

			$fb_graph_url = str_replace (
				array('{FB_ID}', '{EDGE}','{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
				array($object_id, '', 'access_token='.self::$access_token.'&', $fields, '', ''),
				self::$graph_url
			);
			$data_for_client_side->id = $row->id;
			$data_for_client_side->fb_graph_url = $fb_graph_url;

			array_push($client_side_check, $data_for_client_side);
		}
		array_push(self::$client_side_check, $client_side_check);
	}


  // Auto update
	public static function update_from_shedule($rows = array()) {

		self::$save = false;
		self::$edit_feed = false;
        self::$auto_update_feed = 1;

		foreach($rows as $row) {
			self::$fb_id = isset($row->id) ? $row->id : '';
			self::$fb_type = isset($row->type) ? $row->type : '';
			self::$content_type = isset($row->content_type) ? $row->content_type : '';
			self::$content = isset($row->content) ? explode(",", $row->content) : array();
			self::$content_url = isset($row->content_url) ? $row->content_url : '';
			self::$timeline_type = isset($row->timeline_type) ? $row->timeline_type : 'posts';
			self::$limit = isset($row->limit) ? $row->limit : '';
			self::$id = isset($row->from) ? $row->from : '';
			self::$access_token = isset($row->page_access_token) ? $row->page_access_token : '';
			self::$update_mode = isset($row->update_mode) ? $row->update_mode : self::$update_mode;
						$function_name = self::$content_type;
			self::$function_name();
		}
  }


    public static function updateOnVersionChange($rows = array())
    {

        self::$save = false;
        self::$edit_feed = false;
        self::$auto_update_feed = 0;
        self::$updateOnVersionChange = true;

        foreach ($rows as $row) {
            self::$fb_id = isset($row->id) ? $row->id : '';
            self::$fb_type = isset($row->type) ? $row->type : '';
            self::$content_type = isset($row->content_type) ? $row->content_type : '';
            self::$content = isset($row->content) ? explode(",", $row->content) : array();
            self::$content_url = isset($row->content_url) ? $row->content_url : '';
            self::$timeline_type = isset($row->timeline_type) ? $row->timeline_type : 'posts';
            self::$limit = isset($row->limit) ? $row->limit : '';
            self::$id = isset($row->from) ? $row->from : '';
            self::$access_token = isset($row->page_access_token) ? $row->page_access_token : '';
            self::$update_mode = isset($row->update_mode) ? $row->update_mode : self::$update_mode;
            $function_name = self::$content_type;
            self::$function_name();


        }
    }

	public static function update_db() {
    if(!isset(self::$data['data'])){
      return;
    }
		global $wpdb;
    $data = self::$data['data'];
		$id = self::$fb_id;
		$rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` ASC ', $id));
		$to_drop = array();
		$to_insert = array();
    $del_count = 0;
		// Store content array as string.
		$content = implode(",", self::$content);
		foreach($data as $next) {
			$exists = false;
			$is_newer_then_any_of_olds = true;
			$created_time = array_key_exists ( 'created_time' , $next ) ? strtotime($next['created_time']) : '';
			$created_time = ($created_time == '' && array_key_exists ( 'start_time' , $next )) ? strtotime($next['start_time']) : $created_time;
			foreach($rows as $row) {
			  if($row->object_id == $next['id']) {
					$exists = true;
				}

				if($created_time < $row->created_time_number) {
					$is_newer_then_any_of_olds = false;
				}
		  }
			if(!$exists && $is_newer_then_any_of_olds) {
				if(self::$content_type == 'timeline') {
					$from = array_key_exists ( 'from' , $next ) ? $next['from']['id'] : '';
					if(strpos($content, $next['type']) === false)
						continue;

					if(self::$timeline_type == "posts") {
						if($from != self::$id)
							continue;
					}
					elseif(self::$timeline_type == "others") {
						if($from == self::$id)
							continue;
					}
        }
				array_push($to_insert, $next);
			}
		}
		$exist_count = count($rows);
		$insert_count = count($to_insert);
		if((self::$update_mode == 'remove_old') && ($insert_count + $exist_count) > self::$limit) {
			$del_count =  ($insert_count + $exist_count) - self::$limit;
			$ids = array();
			$results = $wpdb->get_results($wpdb->prepare('SELECT `id` FROM `' . $wpdb->prefix . 'wd_fb_data` WHERE  `fb_id` = "%d" ORDER BY `created_time_number` ASC LIMIT ' . $del_count, self::$fb_id));
			foreach($results as $row) {
				array_push($ids, $row->id);
			}
			$ids = implode(',', $ids);
			$delete = $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE `id` IN ('.$ids.') AND `fb_id` = "%d"', self::$fb_id));
		}
		if($insert_count)
			self::insert_wd_fb_data($to_insert);
	}

  public static function page() {
    $page_id = $_POST['fb_page_id'];
    $pages_list = get_option('ffwd_pages_list', array());

    $fb_page = null;
    foreach($pages_list as $page){
      if($page->id === $page_id){
        $fb_page = $page;
        break;
      }
    }

    if($fb_page == null){
      die(0);
    }

    $_POST['content_url'] = "https://www.facebook.com/" . $fb_page->name . "-" . $fb_page->id . "/";
    $_POST['page_access_token'] = $fb_page->access_token;


    self::$content_url = ((isset($_POST['content_url'])) ? esc_html(stripslashes($_POST['content_url'])) : '');
    self::$limit = ((isset($_POST['limit'])) ? esc_html(stripslashes($_POST['limit'])) : '');
    self::set_access_token();
    self::check_fb_page_url();
  // If user exists => set content.
  self::set_content();
      // If right content => set access_token.
    // If right access_token => call function.
    $function_name = self::$content_type;
    self::$function_name();
  }


    public static function update_version()
    {

        global $wpdb;
        $data = self::$data['data'];
        $id = self::$fb_id;
        $delete = $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE `fb_id` = "%d"', self::$fb_id));



        self::insert_wd_fb_data($data);

    }

  public static function group() {
    self::$content_url = ((isset($_POST['content_url'])) ? esc_html(stripslashes($_POST['content_url'])) : '');
    self::$limit = ((isset($_POST['limit'])) ? esc_html(stripslashes($_POST['limit'])) : '');
    self::check_fb_group_url();
    self::set_content();
    self::set_access_token();
    self::timeline();
  }

  public static function profile() {
    self::$content_url = '';
		self::$limit = ((isset($_POST['limit'])) ? esc_html(stripslashes($_POST['limit'])) : '');
    self::check_fb_user();
    self::set_content();
    self::set_access_token();
    $function_name = self::$content_type;
    self::$function_name();
  }

  public static function check_fb_user() {
    if(!class_exists('Facebook'))
	  include WD_FFWD_DIR . "/framework/facebook-sdk/facebook.php";
    global $wpdb;
    $fb_option_data = self::get_fb_option_data();
    $app_id = $fb_option_data->app_id;
    $app_secret = $fb_option_data->app_secret;
    self::$facebook_sdk = new Facebook(array(
      'appId'  => $app_id,
      'secret' => $app_secret,
		));
    $user = self::$facebook_sdk->getUser();
    if (!$user) {
      self::wd_fb_massage('error', 'Please login first');
      }
    else {
      self::$id = $user;
    }
  }

  public static function get_fb_option_data() {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_option WHERE id="%d"', 1));
    return $row;
  }

  public static function set_content() {
    $content_type = ((isset($_POST['content_type'])) ? esc_html(stripslashes($_POST['content_type'])) : '');
    $content = (isset($_POST['content'])) ? $_POST['content'] : array();
    self::$content_type = in_array($content_type, self::$valid_content_types) ? $content_type : false;
		// If right content type
    if(self::$content_type)
			self::$content = $content;
    else
      self::wd_fb_massage('error', 'Invalid content type');
  }

	public static function set_access_token() {
    if(isset($_POST["page_access_token"]) && $_POST["page_access_token"] != ""){
      self::$access_token = $_POST["page_access_token"];
      self::$exist_access = true;
    }else{
      if(!isset(self::$access_token) || empty(self::$access_token)){
        $rand_key = array_rand(self::$access_tokens);
        self::$access_token = self::$access_tokens[$rand_key];
      }
    }
	}

	public static function check_fb_page_url() {
    $first_token  = strtok(self::$content_url, '/');
    $second_token = strtok('/');
		$third_token = strtok('/');
    $fourth = strtok('/');
    $fifth = strtok('/');
    // Check if it's facebook url
    if($second_token === 'www.facebook.com') {
      if($third_token == 'pages') {
				$fifth = explode('?', $fifth);
				self::$id = $fifth[0];
      }
      else {
        // If page's id not showing in url (trying to get id by it's name)
				$third_token = explode('-', $third_token);
				if(count($third_token) > 0) {
					$last = count($third_token)-1;
					$name_id = $third_token[$last];
				}
				else
					$name_id = $third_token[0];
        // If not set access token , get random from our's
        if (empty(self::$access_token)) {
          $rand_key = array_rand(self::$access_tokens);
          $access_token = self::$access_tokens[$rand_key];
        } else {
          $access_token = self::$access_token;
        }
				$fb_graph_url = str_replace (
					array('{FB_ID}', '{EDGE}','{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
					array($name_id, '', 'access_token='.$access_token.'&', 'fields=id&', 'limit=10', ''),
					self::$graph_url
				);
				$data = self::decap_do_curl($fb_graph_url);
				// Check id existing
				if(array_key_exists("id", $data)) {
					self::$id = $data['id'];
				}
				// Check if exist error
				elseif(array_key_exists("error", $data)) {
                    if( $data['error']['code']==4)
                        update_option('ffwd_limit_notice',1);
					self::wd_fb_massage('error', $data['error']['message']);
				}
      }
    }
    else
      self::wd_fb_massage('error', 'not Facebook url');
  }

  public static function check_fb_group_url() {
		// Help tool for find your group id http://lookup-id.com/
    $first_token  = strtok(self::$content_url, '/');
		$id = $first_token;
		// If not set access token , get random from our's
		$rand_key = array_rand(self::$access_tokens);
		$access_token = self::$access_tokens[$rand_key];
		$fb_graph_url = str_replace (
			array('{FB_ID}', '{EDGE}','{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
			array($id, '', 'access_token='.$access_token.'&', '', '', ''),
			self::$graph_url
		);
		// Check if no errors with that id
		$data = self::decap_do_curl($fb_graph_url);
		if(array_key_exists("error", $data)) {
            if( $data['error']['code']==4)
                update_option('ffwd_limit_notice',1);
			self::wd_fb_massage('error', $data['error']['message']);
		}
		else {
			self::$id = $id;
			return;
		}
  }

  public static function complite_timeline($fb_graph_url) {
		$content = implode(",", self::$content);
		$data = self::decap_do_curl($fb_graph_url);
		// If error exist
    if(array_key_exists("error", $data)) {
        if( $data['error']['code']==4)
            update_option('ffwd_limit_notice',1);
      self::wd_fb_massage('error', $data['error']['message']);
    }else{
      // Set next page if it exists
      $paging = array_key_exists ( 'paging' , $data ) ? $data['paging'] : array();
      $next_page = array_key_exists ( 'next' , $paging ) ? $paging['next'] : 0;
			$post_data = isset($data['data']) ? $data['data'] : array();
      foreach($post_data as $next) {
        if(strpos($content, $next['type']) === false)
          continue;
        if(self::$timeline_type == 'others' && self::$id == $next['from']['id'])
          continue;

        if($next['type']=='status' && !isset($next['description']) && !isset($next['message']) && !isset($next['name']))
          continue;

        if(count(self::$complite_timeline_data) < self::$limit)
          array_push(self::$complite_timeline_data, $next);
      }
      if(count(self::$complite_timeline_data) < self::$limit && self::$timeline_max_call_count > 0 && $next_page) {
        self::$timeline_max_call_count--;
        return self::complite_timeline($next_page);
      }
      else {
        return self::$complite_timeline_data;
      }
    }


	}

  public static function timeline() {
		/**
		 * Set timeline type.
		 * Set complite_timeline_data empty array.
		 * Message_tags in message, with_tags in story.
		 * Check if fb_type is group so set `feed` for edge, else set `posts`.
		 * Replace params in graph url.
		*/
		self::set_timeline_type();
		self::$complite_timeline_data = array();
		$data = array();
    self::set_access_token();
      $fields = 'fields=comments.limit(25).summary(true){parent.fields(id),created_time,from,like_count,message,comment_count},attachments,shares,id,name,story,link,created_time,updated_time,from{picture,name,link},message,type,source,place,message_tags,with_tags,story_tags,description,status_type&';		$edge = (self::$fb_type == 'group') ? 'feed' : (self::$timeline_type == 'feed' || self::$timeline_type == 'others') ? 'feed' : 'posts';
		$fb_graph_url = str_replace (
      array('{FB_ID}', '{EDGE}','{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
            array(self::$id, $edge, 'access_token=' . self::$access_token . '&', $fields, 'locale='.get_locale().'&', ''),      self::$graph_url
    );


      /*print_r($fb_graph_url);
        wp_die();*/

      if (self::$auto_update_feed == 1) {
          global $wpdb;

          $id = self::$fb_id;
          $update_ids = array();
          $rows = $wpdb->get_results($wpdb->prepare('SELECT object_id,id FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` ASC ', $id));
          foreach ($rows as $row) {
              $update_ids[$row->object_id] = $row->id;
          }
          $fb_graph_url_update = str_replace(
              array('{FB_ID}', '{EDGE}', '{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
              array('', '', 'ids=' . implode(array_keys($update_ids), ',') . '&access_token=' . self::$access_token . '&', $fields, 'locale=' . get_locale() . '&', ''),
              self::$graph_url
          );

          $update_data = self::decap_do_curl($fb_graph_url_update);
          self::update_wd_fb_data($update_data, $update_ids);

      }


		$data['data'] = self::complite_timeline($fb_graph_url);
		self::$data = $data;



          if (self::$save)
              self::save_db();
          elseif (self::$edit_feed)
              self::edit_feed();
          elseif (self::$updateOnVersionChange)
              self::update_version();
          else {

              self::update_db();

          }
  }


    public static function update_wd_fb_data($data, $ids)
    {
        global $wpdb;
        $content = implode(",", self::$content);
        $success = 'no_data';


        foreach ($data as $key => $next) {

          if(!isset($ids[$key])){
            continue;
          }
            /**
             * check if content_type is timeline dont save wd_fb_data if
             * $content string not contain $next['type']
             */
            if (self::$content_type == 'timeline') {
                if (strpos($content, $next['type']) === false)
                    continue;
                $type = $next['type'];

                if (self::$timeline_type == 'others')
                    if (self::$id == $next['from']['id'])
                        continue;
            } else
                $type = self::$content[0];

            // Use this var for check if album imgs count not 0
            $album_imgs_exists = true;

            switch ($type) {
                case 'photos': {
                    /**
                     * If object type is photo(photos, video, videos,
                     * album, event cover photo etc ) so trying to
                     * check the count of resolution types
                     * and store source for thumb and main size
                     */
                    if (array_key_exists('images', $next)) {
                        $img_res_count = count($next['images']);
                        if ($img_res_count > 6) {
                            $thumb_url = $next['images'][$img_res_count - 1]['source'];
                            $main_url = $next['images'][0]['source'];
                        } else {
                            $thumb_url = $next['images'][0]['source'];
                            $main_url = $next['images'][0]['source'];
                        }
                        $width = $next['images'][0]['width'];
                        $height = $next['images'][0]['height'];
                    }
                    break;
                }
                case 'videos': {
                    if (array_key_exists('format', $next)) {
                        $img_res_count = count($next['format']);
                        if ($img_res_count > 2) {
                            $main_url = $next['format'][$img_res_count - 1]['picture'];
                            $thumb_url = $next['format'][1]['picture'];
                        } else {
                            $thumb_url = $next['format'][$img_res_count - 1]['picture'];
                            $main_url = $next['format'][$img_res_count - 1]['picture'];
                        }
                        $width = $next['format'][$img_res_count - 1]['width'];
                        $height = $next['format'][$img_res_count - 1]['height'];
                    }
                    break;
                }
                case 'albums': {
                    if (array_key_exists('count', $next)) {
                        $album_imgs_count = $next['count'];
                        if ($album_imgs_count == 0) {
                            $album_imgs_exists = false;
                        }
                    }
                    break;
                }
                default: {
                    $thumb_url = '';
                    $main_url = '';
                }
            }
            if ($type == "albums" && !$album_imgs_exists)
                continue;
            // Check if exists such keys in $next array
            $object_id = array_key_exists('id', $next) ? $next['id'] : '';
            $name = array_key_exists('name', $next) ? addcslashes($next['name'], '\\') : '';
            $description = array_key_exists('description', $next) ? addcslashes($next['description'], '\\') : '';
            $link = array_key_exists('link', $next) ? $next['link'] : '';
            $status_type = array_key_exists('status_type', $next) ? $next['status_type'] : '';
            $message = array_key_exists('message', $next) ? addcslashes($next['message'], '\\') : '';
            $story = array_key_exists('story', $next) ? $next['story'] : '';
            $place = array_key_exists('place', $next) ? json_encode($next['place']) : '';
            $message_tags = array_key_exists('message_tags', $next) ? json_encode($next['message_tags']) : '';
            $with_tags = array_key_exists('with_tags', $next) ? json_encode($next['with_tags']) : '';
            $story_tags = array_key_exists('story_tags', $next) ? json_encode($next['story_tags']) : '';
            $reactions = array_key_exists('reactions', $next) ? json_encode($next['reactions']) : '';
            $comments = array_key_exists('comments', $next) ? json_encode($next['comments']) : '';
            $shares = array_key_exists('shares', $next) ? json_encode($next['shares']) : '';
            $attachments = array_key_exists('attachments', $next) ? json_encode($next['attachments']) : '';
            $from_json = array_key_exists('from', $next) ? json_encode($next['from']) : '';
            if ($type == "events")
                $from_json = array_key_exists('owner', $next) ? json_encode($next['owner']) : '';


            $reactions = array_key_exists('reactions', $next) ? json_encode($next['reactions']) : '';

            // When content is events some fields have different names, so check them.
            if ($type == 'events') {
                $source = array_key_exists('cover', $next) ? $next['cover']['source'] : '';
                $created_time = array_key_exists('start_time', $next) ? $next['start_time'] : '';
                $from = array_key_exists('owner', $next) ? $next['owner']['id'] : '';

                $main_url = $source;
                $thumb_url = $main_url;
                // Store event end time in update_time field
                $updated_time = array_key_exists('end_time', $next) ? $next['end_time'] : '';
            } else {
                $source = array_key_exists('source', $next) ? $next['source'] : '';
                $created_time = array_key_exists('created_time', $next) ? $next['created_time'] : '';
                $from = array_key_exists('from', $next) ? $next['from']['id'] : '';

                //check if thumb and main urls is set (if no , so set them source )
                $thumb_url = isset($thumb_url) ? $thumb_url : $source;
                $main_url = isset($main_url) ? $main_url : $source;
                $updated_time = array_key_exists('updated_time', $next) ? $next['updated_time'] : '';
            }
            $width = isset($width) ? $width : '';
            $height = isset($height) ? $height : '';
            $created_time_number = ($created_time != '') ? strtotime($created_time) : 0;

            $save_fb_data = $wpdb->update($wpdb->prefix . 'wd_fb_data', array(
                'fb_id' => self::$fb_id,
                // 'object_id' => $object_id,
                // 'from' => $from,
                'name' => $name,
                'description' => $description,
                'type' => $type,
                'message' => $message,
                'story' => $story,
                'place' => $place,
                'message_tags' => $message_tags,
                'with_tags' => $with_tags,
                'story_tags' => $story_tags,
                'status_type' => $status_type,
                'link' => $link,
                'source' => $source,
                'thumb_url' => $thumb_url,
                'main_url' => $main_url,
                'width' => $width,
                'height' => $height,
                'created_time' => $created_time,
                'updated_time' => $updated_time,
                'created_time_number' => $created_time_number,
                'comments' => $comments,
                'shares' => $shares,
                'attachments' => $attachments,
                'who_post' => $from_json,
                'reactions' => $reactions,
            ), array('id' => $ids[$key]));

        }

    }



    public static function ffwd_event_data_sort($a, $b)
	{
		$date1 = strtotime($a['start_time']);
		$date2 = strtotime($b['start_time']);

		if ($date1 == $date2) {
			return 0;
		}
		return ($date1 < $date2) ? -1 : 1;
	}


	public static function filter_upcoming_events($data)
	{

		foreach ($data['data'] as $key => $event) {

			$event_start_time = strtotime($event['start_time']);
			$now = strtotime(date("Y-m-d H:i:s"));
			if ($event_start_time < $now) {


				unset($data['data'][$key]);
			}
		}

		return $data;
	}


  public static function specific() {
		/**
		 * Define timeline type only for not being null.
		 * Set fields.
		 * Chaek if content is photo or videos, so replace {other} => type=uploaded.
		 * Replace params in graph url.
		 * Check errors.
		*/
		self::$timeline_type = '';
		$other = '';
    if(self::$content[0] == 'albums')
        $fields = 'fields=name,created_time,updated_time,from{picture,name,link},link,count&';
    elseif(self::$content[0] == 'photos')
        $fields = 'fields=source,name,created_time,updated_time,from{picture,name,link},link,images&';
    elseif(self::$content[0] == 'videos')
        $fields = 'fields=source,created_time,updated_time,from{picture,name,link},description,format&';
    elseif (self::$content[0] == 'events') {
        if (self::$upcoming_events == 1)
            $fields = 'fields=comments.limit(25).summary(true){parent.fields(id),created_time,from,like_count,message,comment_count},name,start_time,end_time,description,cover,owner{picture,name,link},place&since=' . strtotime(date('Y-m-d')) . '&';
        else
            $fields = 'fields=comments.limit(25).summary(true){parent.fields(id),created_time,from,like_count,message,comment_count},name,start_time,end_time,description,cover,owner{picture,name,link},place&';
    }
    else
        $fields = 'fields=source,name,story,created_time,updated_time,from{picture,name,link},link&';

    if(self::$content[0] == 'photos' || self::$content[0] == 'videos')
      $other = 'type=uploaded';
    $fb_graph_url = str_replace (
      array('{FB_ID}', '{EDGE}','{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
      array(self::$id, self::$content[0], 'access_token='.self::$access_token.'&', $fields, 'limit='.self::$limit . '&locale='.get_locale().'&', $other),
      self::$graph_url
    );

		// print_r($fb_graph_url);
    // wp_die();
      if (self::$auto_update_feed == 1) {
          global $wpdb;

          $id = self::$fb_id;
          $update_ids = array();
          $rows = $wpdb->get_results($wpdb->prepare('SELECT object_id,id FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` ASC ', $id));
          foreach ($rows as $row) {
              $update_ids[$row->object_id] = $row->id;
          }
          $fb_graph_url_update = str_replace(
              array('{FB_ID}', '{EDGE}', '{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
              array('', '', 'ids=' . implode(array_keys($update_ids), ',') . '&access_token=' . self::$access_token . '&', $fields, 'locale=' . get_locale() . '&', ''),
              self::$graph_url
          );

          $update_data = self::decap_do_curl($fb_graph_url_update);
          self::update_wd_fb_data($update_data, $update_ids);

      }
    $data = self::decap_do_curl($fb_graph_url);


	  /////////////EVENT SORTING


	  if (self::$content[0] == 'events') {

		  if (self::$event_order == 1) {

			  uasort($data['data'], 'self::ffwd_event_data_sort');
		  }





	  }



    if(array_key_exists("error", $data)) {
        if( $data['error']['code']==4)
            update_option('ffwd_limit_notice',1);
      self::wd_fb_massage('error', $data['error']['message']);
    }
    else {
      self::$data = $data;
        if (self::$save)
            self::save_db();
        elseif (self::$edit_feed)
            self::edit_feed();
        elseif (self::$updateOnVersionChange)
            self::update_version();
        else
            self::update_db();
    }
  }

	public static function set_timeline_type() {
		/**
		 * Set timeline type.
		 * Posts by owner (so edge is posts).
		 * Posts by others (so edge is feed).
		 * Posts by owner and others (so edge is feed (but data must be filtered by from atribute not equal to owner ID)).
		*/
		if(self::$save || self::$edit_feed)
		  self::$timeline_type = (isset($_POST['timeline_type']) && $_POST['timeline_type'] != '') ? esc_html(stripcslashes($_POST['timeline_type'])) : 'posts';
	  return;
	}

  public static function save_db() {
    global $wpdb;
    $name = ((isset($_POST['name'])) ? esc_html(stripslashes($_POST['name'])) : '');
    $page_access_token = ((isset($_POST['page_access_token'])) ? esc_html(stripslashes($_POST['page_access_token'])) : '');
    $update_mode = ((isset($_POST['update_mode'])) ? esc_html(stripslashes($_POST['update_mode'])) : '');
    // Collapse content types (multiple when content type is timeline, one when specific)
    $content = implode(",", self::$content);
		$from = self::$id;
		$data = self::$data['data'];
		// If there is no data
		if(!count($data)) {
			self::wd_fb_massage('error', 'There is no data matching your choice.');
		}

		///////////////////////Araqel
		$ffwd_info_options=array();
		$ffwd_info_options['theme']=((isset($_POST['theme'])) ? esc_html(stripslashes($_POST['theme'])) : '');
		$ffwd_info_options['masonry_hor_ver']=((isset($_POST['masonry_hor_ver'])) ? esc_html(stripslashes($_POST['masonry_hor_ver'])) : '');
		$ffwd_info_options['image_max_columns']=((isset($_POST['image_max_columns'])) ? esc_html(stripslashes($_POST['image_max_columns'])) : '');
		$ffwd_info_options['thumb_width']=((isset($_POST['thumb_width'])) ? esc_html(stripslashes($_POST['thumb_width'])) : '');
		$ffwd_info_options['thumb_height']=((isset($_POST['thumb_height'])) ? esc_html(stripslashes($_POST['thumb_height'])) : '');
		$ffwd_info_options['thumb_comments']=((isset($_POST['thumb_comments'])) ? esc_html(stripslashes($_POST['thumb_comments'])) : '');
		$ffwd_info_options['thumb_likes']=((isset($_POST['thumb_likes'])) ? esc_html(stripslashes($_POST['thumb_likes'])) : '');
		$ffwd_info_options['thumb_name']=((isset($_POST['thumb_name'])) ? esc_html(stripslashes($_POST['thumb_name'])) : '');
		$ffwd_info_options['blog_style_width']=((isset($_POST['blog_style_width'])) ? esc_html(stripslashes($_POST['blog_style_width'])) : '');
		$ffwd_info_options['blog_style_height']=((isset($_POST['blog_style_height'])) ? esc_html(stripslashes($_POST['blog_style_height'])) : '');
		$ffwd_info_options['blog_style_view_type']=((isset($_POST['blog_style_view_type'])) ? esc_html(stripslashes($_POST['blog_style_view_type'])) : '');
		$ffwd_info_options['blog_style_comments']=((isset($_POST['blog_style_comments'])) ? esc_html(stripslashes($_POST['blog_style_comments'])) : '');
		$ffwd_info_options['blog_style_likes']=((isset($_POST['blog_style_likes'])) ? esc_html(stripslashes($_POST['blog_style_likes'])) : '');
		$ffwd_info_options['blog_style_message_desc']=((isset($_POST['blog_style_message_desc'])) ? esc_html(stripslashes($_POST['blog_style_message_desc'])) : '');
		$ffwd_info_options['blog_style_shares']=((isset($_POST['blog_style_shares'])) ? esc_html(stripslashes($_POST['blog_style_shares'])) : '');
		$ffwd_info_options['blog_style_shares_butt']=((isset($_POST['blog_style_shares_butt'])) ? esc_html(stripslashes($_POST['blog_style_shares_butt'])) : '');
		$ffwd_info_options['blog_style_facebook']=((isset($_POST['blog_style_facebook'])) ? esc_html(stripslashes($_POST['blog_style_facebook'])) : '');
		$ffwd_info_options['blog_style_twitter']=((isset($_POST['blog_style_twitter'])) ? esc_html(stripslashes($_POST['blog_style_twitter'])) : '');
		$ffwd_info_options['blog_style_google']=((isset($_POST['blog_style_google'])) ? esc_html(stripslashes($_POST['blog_style_google'])) : '');
		$ffwd_info_options['blog_style_author']=((isset($_POST['blog_style_author'])) ? esc_html(stripslashes($_POST['blog_style_author'])) : '');
		$ffwd_info_options['blog_style_name']=((isset($_POST['blog_style_name'])) ? esc_html(stripslashes($_POST['blog_style_name'])) : '');
		$ffwd_info_options['blog_style_place_name']=((isset($_POST['blog_style_place_name'])) ? esc_html(stripslashes($_POST['blog_style_place_name'])) : '');
		$ffwd_info_options['fb_name']=((isset($_POST['fb_name'])) ? esc_html(stripslashes($_POST['fb_name'])) : '');
		$ffwd_info_options['fb_plugin']=((isset($_POST['fb_plugin'])) ? esc_html(stripslashes($_POST['fb_plugin'])) : '');
		$ffwd_info_options['album_max_columns']=((isset($_POST['album_max_columns'])) ? esc_html(stripslashes($_POST['album_max_columns'])) : '');
		$ffwd_info_options['album_title']=((isset($_POST['album_title'])) ? esc_html(stripslashes($_POST['album_title'])) : '');
		$ffwd_info_options['album_thumb_width']=((isset($_POST['album_thumb_width'])) ? esc_html(stripslashes($_POST['album_thumb_width'])) : '');
		$ffwd_info_options['album_thumb_height']=((isset($_POST['album_thumb_height'])) ? esc_html(stripslashes($_POST['album_thumb_height'])) : '');
		$ffwd_info_options['album_image_max_columns']=((isset($_POST['album_image_max_columns'])) ? esc_html(stripslashes($_POST['album_image_max_columns'])) : '');
		$ffwd_info_options['album_image_thumb_width']=((isset($_POST['album_image_thumb_width'])) ? esc_html(stripslashes($_POST['album_image_thumb_width'])) : '');
		$ffwd_info_options['album_image_thumb_height']=((isset($_POST['album_image_thumb_height'])) ? esc_html(stripslashes($_POST['album_image_thumb_height'])) : '');
		$ffwd_info_options['pagination_type']=((isset($_POST['pagination_type'])) ? esc_html(stripslashes($_POST['pagination_type'])) : '');
		$ffwd_info_options['objects_per_page']=((isset($_POST['objects_per_page'])) ? esc_html(stripslashes($_POST['objects_per_page'])) : '');
		$ffwd_info_options['popup_fullscreen']=((isset($_POST['popup_fullscreen'])) ? esc_html(stripslashes($_POST['popup_fullscreen'])) : '');
		$ffwd_info_options['popup_height']=((isset($_POST['popup_height'])) ? esc_html(stripslashes($_POST['popup_height'])) : '');
		$ffwd_info_options['popup_width']=((isset($_POST['popup_width'])) ? esc_html(stripslashes($_POST['popup_width'])) : '');
		$ffwd_info_options['popup_effect']=((isset($_POST['popup_effect'])) ? esc_html(stripslashes($_POST['popup_effect'])) : '');
		$ffwd_info_options['popup_autoplay']=((isset($_POST['popup_autoplay'])) ? esc_html(stripslashes($_POST['popup_autoplay'])) : '');
		$ffwd_info_options['open_commentbox']=((isset($_POST['open_commentbox'])) ? esc_html(stripslashes($_POST['open_commentbox'])) : '');
		$ffwd_info_options['popup_interval']=((isset($_POST['popup_interval'])) ? esc_html(stripslashes($_POST['popup_interval'])) : '');
		$ffwd_info_options['popup_enable_filmstrip']=((isset($_POST['popup_enable_filmstrip'])) ? esc_html(stripslashes($_POST['popup_enable_filmstrip'])) : '');
		$ffwd_info_options['popup_filmstrip_height']=((isset($_POST['popup_filmstrip_height'])) ? esc_html(stripslashes($_POST['popup_filmstrip_height'])) : '');
		$ffwd_info_options['popup_comments']=((isset($_POST['popup_comments'])) ? esc_html(stripslashes($_POST['popup_comments'])) : '');
		$ffwd_info_options['popup_likes']=((isset($_POST['popup_likes'])) ? esc_html(stripslashes($_POST['popup_likes'])) : '');
		$ffwd_info_options['popup_shares']=((isset($_POST['popup_shares'])) ? esc_html(stripslashes($_POST['popup_shares'])) : '');
		$ffwd_info_options['popup_author']=((isset($_POST['popup_author'])) ? esc_html(stripslashes($_POST['popup_author'])) : '');
		$ffwd_info_options['popup_name']=((isset($_POST['popup_name'])) ? esc_html(stripslashes($_POST['popup_name'])) : '');
		$ffwd_info_options['popup_place_name']=((isset($_POST['popup_place_name'])) ? esc_html(stripslashes($_POST['popup_place_name'])) : '');
		$ffwd_info_options['popup_enable_ctrl_btn']=((isset($_POST['popup_enable_ctrl_btn'])) ? esc_html(stripslashes($_POST['popup_enable_ctrl_btn'])) : '');
		$ffwd_info_options['popup_enable_fullscreen']=((isset($_POST['popup_enable_fullscreen'])) ? esc_html(stripslashes($_POST['popup_enable_fullscreen'])) : '');
		$ffwd_info_options['popup_enable_info_btn']=((isset($_POST['popup_enable_info_btn'])) ? esc_html(stripslashes($_POST['popup_enable_info_btn'])) : '');
		$ffwd_info_options['popup_message_desc']=((isset($_POST['popup_message_desc'])) ? esc_html(stripslashes($_POST['popup_message_desc'])) : '');
		$ffwd_info_options['popup_enable_facebook']=((isset($_POST['popup_enable_facebook'])) ? esc_html(stripslashes($_POST['popup_enable_facebook'])) : '');
		$ffwd_info_options['popup_enable_twitter']=((isset($_POST['popup_enable_twitter'])) ? esc_html(stripslashes($_POST['popup_enable_twitter'])) : '');
		$ffwd_info_options['popup_enable_google']=((isset($_POST['popup_enable_google'])) ? esc_html(stripslashes($_POST['popup_enable_google'])) : '');
		$ffwd_info_options['fb_view_type']=((isset($_POST['fb_view_type'])) ? esc_html(stripslashes($_POST['fb_view_type'])) : '');
		$ffwd_info_options['image_onclick_action']=((isset($_POST['image_onclick_action'])) ? esc_html(stripslashes($_POST['image_onclick_action'])) : 'lightbox');

		$ffwd_options_db=array('view_on_fb','post_text_length','event_street','event_city','event_country','event_zip','event_map','event_date','event_desp_length','comments_replies','comments_filter','comments_order','page_plugin_pos','page_plugin_fans','page_plugin_cover','page_plugin_header','page_plugin_width', 'fb_page_id');

		foreach($ffwd_options_db as $ffwd_option_db)
		{

		$ffwd_info_options[$ffwd_option_db]	=((isset($_POST[$ffwd_option_db])) ? esc_html(stripslashes($_POST[$ffwd_option_db])) : '');
		}


		////////////////////////



	  if(self::$fb_type=='group')
		  self::$timeline_type='feed';
    $save_fb_info = $wpdb->insert($wpdb->prefix . 'wd_fb_info', array(
      'name' => $name,
      'page_access_token'        => $page_access_token,
      'type' => self::$fb_type,
      'content_type' => 'timeline',
      'content' => $content,
      'content_url' => self::$content_url,
      'timeline_type' => self::$timeline_type,
      'from' => $from,
      'limit' => self::$limit,
      'app_id' => '',
      'app_secret' => '',
      'exist_access' => 1,
      'access_token' => self::$access_token,
      'order' => ((int) $wpdb->get_var('SELECT MAX(`order`) FROM ' . $wpdb->prefix . 'wd_fb_info')) + 1,
      'published' => 1,
			'update_mode' => $update_mode,
			'theme' =>$ffwd_info_options['theme'],
			'masonry_hor_ver' =>$ffwd_info_options['masonry_hor_ver'],
			'image_max_columns' =>$ffwd_info_options['image_max_columns'],
			'thumb_width' =>$ffwd_info_options['thumb_width'],
			'thumb_height' =>$ffwd_info_options['thumb_height'],
			'thumb_comments' =>$ffwd_info_options['thumb_comments'],
			'thumb_likes' =>$ffwd_info_options['thumb_likes'],
			'thumb_name' =>$ffwd_info_options['thumb_name'],
			'blog_style_width' =>$ffwd_info_options['blog_style_width'],
			'blog_style_height' =>$ffwd_info_options['blog_style_height'],
			'blog_style_view_type' =>$ffwd_info_options['blog_style_view_type'],
			'blog_style_comments' =>$ffwd_info_options['blog_style_comments'],
			'blog_style_likes' =>$ffwd_info_options['blog_style_likes'],
			'blog_style_message_desc' =>$ffwd_info_options['blog_style_message_desc'],
			'blog_style_shares' =>$ffwd_info_options['blog_style_shares'],
			'blog_style_shares_butt' =>0,
			'blog_style_facebook' =>0,
			'blog_style_twitter' =>0,
			'blog_style_google' =>0,
			'blog_style_author' =>$ffwd_info_options['blog_style_author'],
			'blog_style_name' =>$ffwd_info_options['blog_style_name'],
			'blog_style_place_name' =>$ffwd_info_options['blog_style_place_name'],
			'fb_name' =>$ffwd_info_options['fb_name'],
			'fb_plugin' =>$ffwd_info_options['fb_plugin'],
			'album_max_columns' =>$ffwd_info_options['album_max_columns'],
			'album_title' =>$ffwd_info_options['album_title'],
			'album_thumb_width' =>$ffwd_info_options['album_thumb_width'],
			'album_thumb_height' =>$ffwd_info_options['album_thumb_height'],
			'album_image_max_columns' =>$ffwd_info_options['album_image_max_columns'],
			'album_image_thumb_width' =>$ffwd_info_options['album_image_thumb_width'],
			'album_image_thumb_height' =>$ffwd_info_options['album_image_thumb_height'],
			'pagination_type' =>$ffwd_info_options['pagination_type'],
			'objects_per_page' =>$ffwd_info_options['objects_per_page'],
			'popup_fullscreen' =>$ffwd_info_options['popup_fullscreen'],
			'popup_height' =>$ffwd_info_options['popup_height'],
			'popup_width' =>$ffwd_info_options['popup_width'],
			'popup_effect' =>$ffwd_info_options['popup_effect'],
			'popup_autoplay' =>$ffwd_info_options['popup_autoplay'],
			'open_commentbox' =>$ffwd_info_options['open_commentbox'],
			'popup_interval' =>$ffwd_info_options['popup_interval'],
			'popup_enable_filmstrip' =>0,
			'popup_filmstrip_height' =>$ffwd_info_options['popup_filmstrip_height'],
			'popup_comments' =>$ffwd_info_options['popup_comments'],
			'popup_likes' =>$ffwd_info_options['popup_likes'],
			'popup_shares' =>$ffwd_info_options['popup_shares'],
			'popup_author' =>$ffwd_info_options['popup_author'],
			'popup_name' =>$ffwd_info_options['popup_name'],
			'popup_place_name' =>$ffwd_info_options['popup_place_name'],
			'popup_enable_ctrl_btn' =>$ffwd_info_options['popup_enable_ctrl_btn'],
			'popup_enable_fullscreen' =>$ffwd_info_options['popup_enable_fullscreen'],
			'popup_enable_info_btn' =>0,
			'popup_message_desc' =>$ffwd_info_options['popup_message_desc'],
			'popup_enable_facebook' =>0,
			'popup_enable_twitter' =>0,
			'popup_enable_google' =>0	,
			'fb_view_type' =>$ffwd_info_options['fb_view_type'],
			'view_on_fb' =>$ffwd_info_options['view_on_fb'],
			'post_text_length' =>$ffwd_info_options['post_text_length'],
			'event_street' =>$ffwd_info_options['event_street'],
			'event_city' =>$ffwd_info_options['event_city'],
			'event_country' =>$ffwd_info_options['event_country'],
			'event_zip' =>$ffwd_info_options['event_zip'],
			'event_map' =>$ffwd_info_options['event_map'],
			'event_date' =>$ffwd_info_options['event_date'],
			'event_desp_length' =>$ffwd_info_options['event_desp_length'],
			'comments_replies' =>$ffwd_info_options['comments_replies'],
			'comments_filter' =>$ffwd_info_options['comments_filter'],
			'comments_order' =>$ffwd_info_options['comments_order'],
			'page_plugin_pos' =>$ffwd_info_options['page_plugin_pos'],
			'page_plugin_fans' =>$ffwd_info_options['page_plugin_fans'],
			'page_plugin_cover' =>$ffwd_info_options['page_plugin_cover'],
			'page_plugin_header' =>$ffwd_info_options['page_plugin_header'],
			'page_plugin_width'	 =>$ffwd_info_options['page_plugin_width'],
			'image_onclick_action'	 =>$ffwd_info_options['image_onclick_action'],
			'fb_page_id'	 =>$ffwd_info_options['fb_page_id'],
    ), array(
      '%s',//name
      '%s',//type
      '%s',//content_type
      '%s',//content
      '%s',//content_url
      '%s',//timeline_type
      '%s',//from
      '%d',//limit
      '%s',//app_id
      '%s',//app_secret
      '%d',//exist_access
      '%s',//access_token
      '%d',//order
      '%d',//published
			'%s',//update_mode
    ));

    /**
     * Get last inserted id from wd_fb_info for table bellow.
     * Insert into type column the
     * first and only value of self::$content array.
     * Escape paging in self::data
     */
    self::$fb_id = $wpdb->insert_id;
    if($save_fb_info !== FALSE) {
      self::insert_wd_fb_data($data);
      self::insert_wd_fb_info_options($ffwd_info_options);
			//save options
    }
    else {
			self::wd_fb_massage('error', 'Problem with save fb feed');
    }
  }


	public static function insert_wd_fb_data($data) {
    global $wpdb;
		$content = implode(",", self::$content);
		$success = 'no_data';

        		foreach($data as $next) {
		  /**
			 * check if content_type is timeline dont save wd_fb_data if
			 * $content string not contain $next['type']
			 */
			if(self::$content_type == 'timeline') {
				if(strpos($content, $next['type']) === false)
					continue;
				$type = $next['type'];

				if(self::$timeline_type == 'others')
					if(self::$id == $next['from']['id'])
						continue;
			}
			else
				$type = self::$content[0];

			// Use this var for check if album imgs count not 0
			$album_imgs_exists = true;

			switch($type) {
				case 'photos': {
					/**
					 * If object type is photo(photos, video, videos,
					 * album, event cover photo etc ) so trying to
					 * check the count of resolution types
					 * and store source for thumb and main size
					 */
					if(array_key_exists ( 'images' , $next )) {
						$img_res_count = count($next['images']);
						if($img_res_count > 6) {
							$thumb_url = $next['images'][$img_res_count - 1]['source'];
							$main_url = $next['images'][0]['source'];
						}
						else {
							$thumb_url = $next['images'][0]['source'];
							$main_url = $next['images'][0]['source'];
						}
						$width = $next['images'][0]['width'];
						$height = $next['images'][0]['height'];
					}
					break;
				}
				case 'videos': {
					if(array_key_exists ( 'format' , $next )) {
						$img_res_count = count($next['format']);
						if($img_res_count > 2) {
							$main_url = $next['format'][$img_res_count - 1]['picture'];
							$thumb_url = $next['format'][1]['picture'];
						}
						else {
							$thumb_url = $next['format'][$img_res_count - 1]['picture'];
							$main_url = $next['format'][$img_res_count - 1]['picture'];
						}
						$width = $next['format'][$img_res_count - 1]['width'];
						$height = $next['format'][$img_res_count - 1]['height'];
					}
					break;
				}
				case 'albums': {
					if(array_key_exists ( 'count' , $next )) {
						$album_imgs_count = $next['count'];
						if($album_imgs_count == 0) {
							$album_imgs_exists = false;
						}
					}
					break;
				}
				default: {
					$thumb_url = '';
					$main_url = '';
				}
			}
			if($type == "albums" && !$album_imgs_exists)
				continue;
			// Check if exists such keys in $next array
			$object_id = array_key_exists ( 'id' , $next ) ? $next['id'] : '';
			$name = array_key_exists ( 'name' , $next ) ? addcslashes($next['name'], '\\') : '';
			$description = array_key_exists ( 'description', $next ) ? addcslashes($next['description'], '\\') : '';
			$link = array_key_exists ( 'link' , $next ) ? $next['link'] : '';
			$status_type = array_key_exists ( 'status_type' , $next ) ? $next['status_type'] : '';
			$message = array_key_exists ( 'message' , $next ) ? addcslashes($next['message'], '\\') : '';
			$story = array_key_exists ( 'story' , $next ) ? $next['story'] : '';
			$place = array_key_exists ( 'place' , $next ) ? json_encode($next['place']) : '';
			$message_tags = array_key_exists ( 'message_tags' , $next ) ? json_encode($next['message_tags']) : '';
			$with_tags = array_key_exists ( 'with_tags' , $next ) ? json_encode($next['with_tags']) : '';
			$story_tags = array_key_exists ( 'story_tags' , $next ) ? json_encode($next['story_tags']) : '';
            $reactions = array_key_exists('reactions', $next) ? json_encode($next['reactions']) : '';
            $comments = array_key_exists('comments', $next) ? json_encode($next['comments']) : '';
            $shares = array_key_exists('shares', $next) ? json_encode($next['shares']) : '';
            $attachments = array_key_exists('attachments', $next) ? json_encode($next['attachments']) : '';
            $from_json = array_key_exists('from', $next) ? json_encode($next['from']) : '';
            if($type=="events")
                $from_json = array_key_exists('owner', $next) ? json_encode($next['owner']) : '';




            $reactions = array_key_exists('reactions', $next) ? json_encode($next['reactions']) : '';



			// When content is events some fields have different names, so check them.
			if($type == 'events') {
				$source = array_key_exists ( 'cover' , $next ) ? $next['cover']['source'] : '';
				$created_time = array_key_exists ( 'start_time' , $next ) ? $next['start_time'] : '';
				$from = array_key_exists ( 'owner' , $next ) ? $next['owner']['id'] : '';

				$main_url = $source;
				$thumb_url = $main_url;
				// Store event end time in update_time field
				$updated_time = array_key_exists ( 'end_time' , $next ) ? $next['end_time'] : '';
			}
			else {
				$source = array_key_exists ( 'source' , $next ) ? $next['source'] : '';
				$created_time = array_key_exists ( 'created_time' , $next ) ? $next['created_time'] : '';
				$from = array_key_exists ( 'from' , $next ) ? $next['from']['id'] : '';

				//check if thumb and main urls is set (if no , so set them source )
				$thumb_url = isset($thumb_url) ? $thumb_url : $source;
				$main_url = isset($main_url) ? $main_url : $source;
				$updated_time = array_key_exists ( 'updated_time' , $next ) ? $next['updated_time'] : '';
			}
			$width = isset($width) ? $width : '';
			$height = isset($height) ? $height : '';
			$created_time_number = ($created_time != '') ? strtotime($created_time) : 0;

			$save_fb_data = $wpdb->insert($wpdb->prefix . 'wd_fb_data', array(
				'fb_id' => self::$fb_id,
				'object_id' => $object_id,
				'from' => $from,
				'name' => $name,
				'description' => $description,
				'type' => $type,
				'message' => $message,
				'story' => $story,
				'place' => $place,
				'message_tags' => $message_tags,
				'with_tags' => $with_tags,
				'story_tags' => $story_tags,
				'status_type' => $status_type,
				'link' => $link,
				'source' => $source,
				'thumb_url' => $thumb_url,
				'main_url' => $main_url,
				'width' => $width,
				'height' => $height,
				'created_time' => $created_time,
				'updated_time' => $updated_time,
				'created_time_number' => $created_time_number,
                'comments' => $comments,
                'shares' => $shares,
                'attachments' => $attachments,
                'who_post' => $from_json,
                'reactions' => $reactions,
				), array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
			));
			if ($save_fb_data !== FALSE) {
				$success = 'success';
			}
			else {
				$success = 'error';
				break;
			}
		}
    if($success == 'success') {
			if(self::$save || self::$edit_feed)
				self::wd_fb_massage('success', self::$fb_id);
		}
		elseif($success == 'error' || $success == 'no_data' && (self::$save || self::$edit_feed)) {
			$message = ($success == 'error') ? 'Problem with save' : 'There is no data matching your choice.';
			self::wd_fb_massage('error', $message);
		}
		else {
			if(self::$save || self::$edit_feed)
				self::wd_fb_massage('error', 'Problem with save');
		}
	}

	public static function check_app() {
		global $wpdb;

		if(!class_exists('Facebook'))
			include WD_FFWD_DIR . "/framework/facebook-sdk/facebook.php";
		$app_id = ((isset($_POST['app_id'])) ? esc_html(stripslashes($_POST['app_id'])) : '');
		$app_secret = ((isset($_POST['app_secret'])) ? esc_html(stripslashes($_POST['app_secret'])) : '');
		//prepare params for graph api call







		$fb_graph_url = str_replace (
			array('{FB_ID}', '{EDGE}','{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
			array($app_id, '', 'access_token=' . self::$access_token . '&', 'fields=roles&', '', ''),
			self::$graph_url
		);

		$data = self::decap_do_curl($fb_graph_url);
		//check if exists app with such app_id and app_secret
		if(array_key_exists("id", $data) && array_key_exists("roles", $data)) {
			//create facebook object
			self::$facebook_sdk = new Facebook(array(
				'appId'  => $app_id,
				'secret' => $app_secret,
			));

			$response_url='https://graph.facebook.com/oauth/access_token?client_id='.$app_id.'&client_secret='.$app_secret.'&grant_type=client_credentials';
			$response = wp_remote_get( $response_url);

			$access_token=explode('=',$response['body']);
			$access_token=$access_token[1];



			//save app id and app secret
			$save = $wpdb->update($wpdb->prefix . 'wd_fb_option', array(
				'app_id' => $app_id,
				'app_secret' => $app_secret,
				'access_token' => $access_token,
			),
				array('id' => 1)
			);

			//checked logged in user
			$user = self::$facebook_sdk->getUser();
			if (!$user) {
				$app_link_url = self::$facebook_sdk->getLoginUrl(array( 'scope' => 'user_photos,user_videos,user_posts','redirect_uri' => admin_url() . 'admin.php?page=options_ffwd'));
				$app_link_text = __('Log into Facebook with your app', 'bwg');
				self::wd_fb_massage('success', $app_link_url);
			}
			else {
				self::wd_fb_massage('success', admin_url() . 'admin.php?page=options_ffwd');
			}
		}
		//check if exist error
		elseif(array_key_exists("error", $data)) {
			$save = $wpdb->update($wpdb->prefix . 'wd_fb_option', array(

				'access_token' => '',
			),
				array('id' => 1)
			);

            if( $data['error']['code']==4)
                update_option('ffwd_limit_notice',1);

			self::wd_fb_massage('error', $data['error']['message'].'asdasd');
		}
		else {
			self::wd_fb_massage('error', 'Something went wrong');
		}
	}

  public static function dropp_objects() {
    global $wpdb;
		$dropped_id = (isset($_POST['ids']) && $_POST['ids'] != '') ? $_POST['ids'] : '';
		$yes = $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE `id` = "%d"', $dropped_id));
		echo $yes;
    if (defined( 'DOING_AJAX' ) && DOING_AJAX )
    {
      die();
    }
  }

	public static function check_logged_in_user() {
		global $wpdb;
		if(!class_exists('Facebook'))
			include WD_FFWD_DIR . "/framework/facebook-sdk/facebook.php";
		$fb_option_data = self::get_fb_option_data();
		// Create facebook object
		self::$facebook_sdk = new Facebook(array(
			'appId'  => $fb_option_data->app_id,
			'secret' => $fb_option_data->app_secret,
		));
		// Checked logged in user
		$user = self::$facebook_sdk->getUser();
		if (!$user) {
			return 0;
		}
		else {
			return 1;
		}
	}

  public static function wd_fb_massage($mood, $massage)
  {
    if(self::$ffwd_fb_massage){
      echo json_encode(array($mood, $massage));
      self::$ffwd_fb_massage = false;

      if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX )
      {
        wp_die();
      }
    }else{
      return;
    }
  }

  public static function check_fb_type() {
    $fb_type = ((isset($_POST['fb_type'])) ? esc_html(stripslashes($_POST['fb_type'])) : '');
    self::$fb_type = in_array($fb_type, self::$fb_valid_types) ? $fb_type : false;
    if(self::$fb_type)
      self::$fb_type();
    else
      self::wd_fb_massage('error', 'no such FB type');
  }

  public static function decap_do_curl($uri)
  {
    $facebook_graph_results = null;
    $facebook_graph_url = $uri; //TODO: Add URL checking here, else error out

    //Attempt CURL
    if (extension_loaded('curl')) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $facebook_graph_url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      if (!$facebook_graph_results = curl_exec($ch)) {
        printf('<p>cURL Error: %1$s %2$s</p>', curl_errno($ch), curl_error($ch));
        printf('<p>Please try entering <strong>%s</strong> into your URL bar and seeing if the page loads.', $facebook_graph_url);
      }
      if (curl_errno($ch) == 7) {
        print '<p><strong>Your server cannot communicate with Facebook\'s servers. This means your server does not support IPv6 or is having issues resolving facebook.com. Please contact your hosting provider.';
      }
      curl_close($ch);
    } else {
      self::wd_fb_massage('error', 'Sorry, your server does not allow remote fopen or have CURL');
    }


    $facebook_graph_results = json_decode($facebook_graph_results, true);
    if (array_key_exists("error", $facebook_graph_results)) {
      if ($facebook_graph_results['error']['code'] == 2) {
        return self::decap_do_curl($facebook_graph_url);
      }
    }
    /*        if(isset($facebook_graph_results["error"]) && isset($facebook_graph_results["error"]['code']) && $facebook_graph_results["error"]['code']===190){
              if(self::$exist_access){
                $fb_option_data = self::get_fb_option_data();

                if(isset($fb_option_data->app_id) && isset($fb_option_data->app_secret)){
                  $app_id = $fb_option_data->app_id;
                  $app_secret = $fb_option_data->app_secret;
                  if(!empty($app_id) && !empty($app_id)){
                    $res = self::update_page_access_token(self::$access_token);
                    if($res["success"]){
                      self::$access_token = $res["success"];
                    }
                  }
                }
              }
            }*/



    if(isset($facebook_graph_results["error"]) && count(self::$access_tokens)>1){
      if (($key = array_search(self::$access_token, self::$access_tokens)) !== false) {
        unset(self::$access_tokens[$key]);
        self::$access_token = null;
      }
      $rand_key = array_rand(self::$access_tokens);
      self::$access_token = self::$access_tokens[$rand_key];

      $parts = parse_url($uri);
      $queryParams = array();
      parse_str($parts['query'], $queryParams);
      $queryParams["access_token"] = self::$access_token;

      $queryString = http_build_query($queryParams);
      $url = "https://graph.facebook.com".$parts['path'] . '?' . $queryString;
      return self::decap_do_curl($url);
    }
    return $facebook_graph_results;
  }
  private static function update_page_access_token($old_access_token){
    global $wpdb;
    $fb_option_data = self::get_fb_option_data();

    $return_data = array(
      'success'=>false,
      'new_token'=>null,
    );
    if(isset($fb_option_data->app_id) && isset($fb_option_data->app_secret)) {
      $app_id = $fb_option_data->app_id;
      $app_secret = $fb_option_data->app_secret;
      $url = "https://graph.facebook.com/oauth/access_token?client_id=" . $app_id . "&client_secret=" . $app_secret . "&grant_type=fb_exchange_token&fb_exchange_token=" . $old_access_token;
      $response = wp_remote_get($url);
      if (isset($response['body'])) {
        $data = json_decode($response['body'], true);
        if (isset($data["access_token"]) && !empty($data["access_token"])) {
          $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "wd_fb_info SET page_access_token  = %s WHERE page_access_token = %s", $data["access_token"], $old_access_token));
          $return_data["success"]   = true;
          $return_data["new_token"] = $data["access_token"];
        }
      }
    }
    return $return_data;
  }
  public static function get_autoupdate_interval(){
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_option WHERE id="%d"', 1));
    if(!isset($row)){
      return 30;
    }
    if(!isset($row->autoupdate_interval)){
      return 30;
    }
    $autoupdate_interval = $row->autoupdate_interval;
    return $autoupdate_interval;
  }

  public static function get_auth_url(){
    $app_id = '457830911380339';
    $redirect_uri = 'https://api.web-dorado.com/fb/';

    $admin_url = admin_url('admin.php?page=options_ffwd');

    $state = array(
      'wp_site_url' => $admin_url
    );

    $fb_url = add_query_arg(array(
      'client_id' => $app_id,
      'redirect_uri' => $redirect_uri,
      'scope' => 'manage_pages',
    ), "https://www.facebook.com/dialog/oauth");

    $fb_url .= '&state=' . base64_encode(json_encode($state));
    return $fb_url;
  }

  public static function save_pages($access_token){

    $url = 'https://graph.facebook.com/me/accounts?limit=500&access_token=' . $access_token;
    $response = wp_remote_get($url);

    if(!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {

      $pages = json_decode($response['body']);
      update_option('ffwd_pages_list', $pages->data);
      update_option("ffwd_pages_list_success", "1");
      self::update_access_tokens();
      return true;
    }

    return false;
  }

  private static function update_access_tokens(){
    global $wpdb;

    $pages = get_option('ffwd_pages_list', array());

    foreach($pages as $page) {
      $wpdb->update($wpdb->prefix . 'wd_fb_info', array(
        'page_access_token' => $page->access_token
      ), array('fb_page_id' => $page->id));
    }

  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}

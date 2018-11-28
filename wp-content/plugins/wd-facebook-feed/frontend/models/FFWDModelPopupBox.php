<?php

class FFWDModelPopupBox {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  public $access_token;
  public $graph_url = 'https://graph.facebook.com/{FB_ID}/{EDGE}?{ACCESS_TOKEN}{FIELDS}{LIMIT}{OTHER}';
  public $page_user_group;
	public $options;
	public $date_offset;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function get_theme_row_data($id) {
    global $wpdb;
    if ($id) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE id="%d"', $id));
    }
    else {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE default_theme="%d"', 1));
    }
    $row = (object) array_merge((array)$row, (array)json_decode(  $row->params));
    unset($row->params);


    return $row;
  }

  public function get_option_row_data() {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_option WHERE id="%d"', 1));
    $this->options = $row;
		return $row;
  }

  public function get_image_rows_data($fb_id, $sort_by, $order_by = 'asc') {
    global $wpdb;
    if ($sort_by == 'size' || $sort_by == 'resolution') {
      $sort_by = ' CAST(' . $sort_by . ' AS SIGNED) ';
    }
    elseif (($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype') && ($sort_by != 'filename')) {
      //$sort_by = '`order`';
      $sort_by = '`id`';
    }
    if (preg_replace('/\s+/', '', $order_by) != 'asc') {
      $order_by = 'desc';
    }

		$sort_by = '`created_time_number`';
		$order_by = 'DESC';

    $row = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY ' . $sort_by . ' ' . $order_by, $fb_id));
    return $row;
  }

  public function get_ffwd_info_data($id) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_info WHERE published=1 AND id="%d"', $id));
    // set access token
    $this->access_token = $row->access_token;
    $graph_url_for_page_info = str_replace (
      array('{FB_ID}', '{EDGE}', '{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'),
      array($row->from, '', 'access_token=' . $this->access_token . '&', 'fields=picture,name,link&', '', ''),
      $this->graph_url
    );
    $this->graph_url = str_replace (
      array('{ACCESS_TOKEN}', '{LIMIT}'),
      array('access_token=' . $this->access_token . '&', ''),
      $this->graph_url
    );
    $this->page_user_group = self::decap_do_curl($graph_url_for_page_info);
		$this->page_user_group = json_encode($this->page_user_group);


    return $row;
  }

  public static function decap_do_curl($uri) {
    $facebook_graph_results = null;
    $facebook_graph_url = $uri; //TODO: Add URL checking here, else error out
      //Attempt CURL
      if (extension_loaded('curl')){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $facebook_graph_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if(!$facebook_graph_results = curl_exec($ch)) {
          printf('<p>cURL Error: %1$s %2$s</p>', curl_errno($ch), curl_error($ch));
          printf('<p>Please try entering <strong>%s</strong> into your URL bar and seeing if the page loads.', $facebook_graph_url);
        }
        if(curl_errno($ch) == 7) {
          print '<p><strong>Your server cannot communicate with Facebook\'s servers. This means your server does not support IPv6 or is having issues resolving facebook.com. Please contact your hosting provider.';
        }
        curl_close($ch);
      } else {
        print ('Sorry, your server does not allow remote fopen or have CURL');
      }
    $facebook_graph_results = json_decode($facebook_graph_results, true);
    return $facebook_graph_results;
  }

	public function ffwd_set_date_timezone_offset(){
        $date_timezone = isset($this->options->date_timezone) && $this->options->date_timezone!='' ? $this->options->date_timezone : date_default_timezone_get() ;
		$date_for_offset = new DateTimeZone($date_timezone);
		$date_offset = new DateTime("now", $date_for_offset);
		$offset = $date_offset->getOffset();
		$offset /= 3600;
		$this->date_offset = $offset;
	}

	public function ffwd_time($created_time, $updated_time, $type) {
		$event_date_format = $this->options->event_date_format;
		$post_date_format = $this->options->post_date_format;
        $date_timezone = isset($this->options->date_timezone) && $this->options->date_timezone!='' ? $this->options->date_timezone : date_default_timezone_get() ;
		$date_create = new DateTime($created_time);
		$date_create->setTimezone(new DateTimeZone($date_timezone));
		$date_update = new DateTime($updated_time);
		$date_update->setTimezone(new DateTimeZone($date_timezone));
		if($type == "events") {
			if($this->options->event_date) {
				?>
				<div class="ffwd_popup_from_time_event" style="">
					<?php
						echo $date_create->format($event_date_format) . '<br>';
						//echo  'Start - ' . $date_create->format($event_date_format) . '<br>' .
									//'End -   ' . $date_update->format($event_date_format);
					?>
				</div>
				<?php
		  }
		}
		else {
			?>
			<div class="ffwd_popup_from_time_post" style="">
				<?php
				if($post_date_format == 'ago') {
					$time = strtotime($created_time);
					echo $this->humanTiming($time);
				}
				else
					echo $date_create->format($post_date_format);
				?>
			</div>
			<?php
		}
	}

    public function humanTiming ($time) {
        $time = time() - $time;
        $tokens = array (
            31536000 => __('year','ffwd'),
            2592000 => __('month','ffwd'),
            604800 => __('week','ffwd'),
            86400 =>  __('day','ffwd'),
            3600 => __('hour','ffwd'),
            60 => __('minute','ffwd'),
            1 => __('second','ffwd')
        );

        $tokens_s = array (
            31536000 => __('years','ffwd'),
            2592000 => __('months','ffwd'),
            604800 => __('weeks','ffwd'),
            86400 =>  __('days','ffwd'),
            3600 => __('hours','ffwd'),
            60 => __('minutes','ffwd'),
            1 => __('seconds','ffwd')
        );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            if($numberOfUnits>1)
                return $numberOfUnits.' '.$tokens_s[$unit] . __(' ago','ffwd');

            return $numberOfUnits.' '.$text. __(' ago','ffwd');
        }
    }

  public function fill_hashtags ($str) {
    $str = preg_replace("/\n/", " \n", $str);
    $str = explode(' ', $str);
    for($i=0; $i<count($str); $i++) {
      if(strpos($str[$i], '#') !== false) {
        $hashtag = str_replace('#', '<a class="ffwd_hashtag" target="_blank" href="https://www.facebook.com/hashtag/', $str[$i]);
        $word = explode('#', $str[$i]);
        $word = '#' . $word[1];
        $hashtag .= '">' . $word . '</a>';
        $str[$i] = $hashtag;
      }
    }
    $str = implode(' ', $str);
    return $str;
  }

  public function see_less_more($string, $type, $row_type) {
    $string = strip_tags($string);
    $new_string = $string;
    $hide_text_paragraph = '';
    $length = strlen($string);
		/*var_dump($length);*/
		if($row_type == 'events')
      $text_length = isset($this->options->event_desp_length) ? $this->options->event_desp_length : 200;
		else
      $text_length = isset($this->options->post_text_length) ? $this->options->post_text_length : 200;
    if ($length > $text_length) {
      // Truncate string
      $stringCut = substr($string, 0, $text_length);//var_dump($stringCut);
      // Make sure it ends in a word so football doesn't footba ass...
      $last_whitespace_in_string_cut = strrpos($stringCut, ' ');
			$last_whitespace_in_string_cut = ($last_whitespace_in_string_cut === false) ? 0 : $last_whitespace_in_string_cut;
      // Get hide text
      $hide_text_length = $length - $last_whitespace_in_string_cut;
      $hide_text = substr($string, $last_whitespace_in_string_cut, $hide_text_length);
      $hide_text_paragraph = ' <span style="display:none" class="ffwd_object_'.$type.'_hide" >' . $hide_text . ' </span>';
      $new_string = substr($stringCut, 0, $last_whitespace_in_string_cut) . $hide_text_paragraph . ' <span class="ffwd_more_dotes" > ... </span> <a href="" class="ffwd_see_more ffwd_see_more_'.$type.'">See more</a>';
    }
    return $new_string;
  }

	public function fill_tags($string, $message_tags) {
		$message_tags = json_decode(str_replace("'", esc_html("'"), $message_tags));
		if($message_tags)
			foreach($message_tags as $message_tag) {
				$type = gettype ( $message_tag );
				$tag = ($type == "object") ? $message_tag : $message_tag["0"];
				if(strpos($string, $tag->name) !== false) {
					$string = str_replace($tag->name, '<a class="ffwd_message_tag" target="_blank" href="https://www.facebook.com/' . $tag->id . '" >'.$tag->name.'</a>', $string);
				}
			}
		return $string;
  }

  public function get_option_json_data() {

		if(isset($this->options) && $this->options != NULL)
			return stripslashes(json_encode($this->options));
  }

  public function ffwd_story($story, $place) {
    $enable_place_name = (isset($_GET['enable_place_name']) ? esc_html($_GET['enable_place_name']) : 0);
		//$enable_author = (isset($_GET['enable_author']) ? esc_html($_GET['enable_author']) : 0);
		$story = str_replace($this->page_user_group['name'], "", $story);
    $place = json_decode($place);
    if($place != null) {
      $place_name = $place->name;
      $story = str_replace(
        $place_name,
        '<a class="ffwd_place_name" href="https://www.facebook.com/' . $place->id . '" target="_blank">' . $place_name . '</a>',
        $story );
    }
    return $story;
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

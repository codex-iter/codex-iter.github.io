<?php

class FFWDModelOptions_ffwd {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $facebook_sdk;
  private $app_id;
  private $app_secret;
  private $access_token;
	public  $date_timezones = array(
						'Pacific/Midway'       => "(GMT-11:00) Midway Island",
						'US/Samoa'             => "(GMT-11:00) Samoa",
						'US/Hawaii'            => "(GMT-10:00) Hawaii",
						'US/Alaska'            => "(GMT-09:00) Alaska",
						'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
						'America/Tijuana'      => "(GMT-08:00) Tijuana",
						'US/Arizona'           => "(GMT-07:00) Arizona",
						'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
						'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
						'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
						'America/Mexico_City'  => "(GMT-06:00) Mexico City",
						'America/Monterrey'    => "(GMT-06:00) Monterrey",
						'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
						'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
						'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
						'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
						'America/Bogota'       => "(GMT-05:00) Bogota",
						'America/Lima'         => "(GMT-05:00) Lima",
						'America/Caracas'      => "(GMT-04:30) Caracas",
						'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
						'America/La_Paz'       => "(GMT-04:00) La Paz",
						'America/Santiago'     => "(GMT-04:00) Santiago",
						'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
						'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
						'Greenland'            => "(GMT-03:00) Greenland",
						'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
						'Atlantic/Azores'      => "(GMT-01:00) Azores",
						'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
						'Africa/Casablanca'    => "(GMT) Casablanca",
						'Europe/Dublin'        => "(GMT) Dublin",
						'Europe/Lisbon'        => "(GMT) Lisbon",
						'Europe/London'        => "(GMT) London",
						'Africa/Monrovia'      => "(GMT) Monrovia",
						'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
						'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
						'Europe/Berlin'        => "(GMT+01:00) Berlin",
						'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
						'Europe/Brussels'      => "(GMT+01:00) Brussels",
						'Europe/Budapest'      => "(GMT+01:00) Budapest",
						'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
						'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
						'Europe/Madrid'        => "(GMT+01:00) Madrid",
						'Europe/Paris'         => "(GMT+01:00) Paris",
						'Europe/Prague'        => "(GMT+01:00) Prague",
						'Europe/Rome'          => "(GMT+01:00) Rome",
						'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
						'Europe/Skopje'        => "(GMT+01:00) Skopje",
						'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
						'Europe/Vienna'        => "(GMT+01:00) Vienna",
						'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
						'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
						'Europe/Athens'        => "(GMT+02:00) Athens",
						'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
						'Africa/Cairo'         => "(GMT+02:00) Cairo",
						'Africa/Harare'        => "(GMT+02:00) Harare",
						'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
						'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
						'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
						'Europe/Kiev'          => "(GMT+02:00) Kyiv",
						'Europe/Minsk'         => "(GMT+02:00) Minsk",
						'Europe/Riga'          => "(GMT+02:00) Riga",
						'Europe/Sofia'         => "(GMT+02:00) Sofia",
						'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
						'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
						'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
						'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
						'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
						'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
						'Europe/Moscow'        => "(GMT+03:00) Moscow",
						'Asia/Tehran'          => "(GMT+03:30) Tehran",
						'Asia/Baku'            => "(GMT+04:00) Baku",
						'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
						'Asia/Muscat'          => "(GMT+04:00) Muscat",
						'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
						'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
						'Asia/Kabul'           => "(GMT+04:30) Kabul",
						'Asia/Karachi'         => "(GMT+05:00) Karachi",
						'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
						'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
						'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
						'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
						'Asia/Almaty'          => "(GMT+06:00) Almaty",
						'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
						'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
						'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
						'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
						'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
						'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
						'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
						'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
						'Australia/Perth'      => "(GMT+08:00) Perth",
						'Asia/Singapore'       => "(GMT+08:00) Singapore",
						'Asia/Taipei'          => "(GMT+08:00) Taipei",
						'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
						'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
						'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
						'Asia/Seoul'           => "(GMT+09:00) Seoul",
						'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
						'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
						'Australia/Darwin'     => "(GMT+09:30) Darwin",
						'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
						'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
						'Australia/Canberra'   => "(GMT+10:00) Canberra",
						'Pacific/Guam'         => "(GMT+10:00) Guam",
						'Australia/Hobart'     => "(GMT+10:00) Hobart",
						'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
						'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
						'Australia/Sydney'     => "(GMT+10:00) Sydney",
						'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
						'Asia/Magadan'         => "(GMT+12:00) Magadan",
						'Pacific/Auckland'     => "(GMT+12:00) Auckland",
						'Pacific/Fiji'         => "(GMT+12:00) Fiji",
					);
	public  $date_formats = array(
						"ago" => "2 days ago",
						"F j, Y, g:i a" => "March 10, 2015, 5:16 pm",
						"F j, Y" => "March 10, 2015",
						"l, F jS, Y" => "Tuesday, March 10th, 2015",
						"l, F jS, Y, g:i a" => "Tuesday, March 10th, 2015, 5:16 pm",
						"Y/m/d \a\\t g:i a" => "2015/03/10 at 12:50 AM",
						"Y/m/d" => " 2015/03/10",
                        "d/m/Y" => " 10/03/2015",
						"Y.m.d" => " 2015.03.10",
					);
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function get_facebook_data() {
    global $wpdb;
		if(!class_exists('Facebook'))
			include WD_FFWD_DIR . "/framework/facebook-sdk/facebook.php";
		
		$row = $this->get_row_data(false);
		$this->app_id = $row->app_id;
		$this->app_secret = $row->app_secret;
		//$this->access_token = $row->user_access_token;
		$this->facebook_sdk = new Facebook(array(	
			'appId'  => $this->app_id,
			'secret' => $this->app_secret,
			));	  
		if(isset($_POST['app_log_out'])) {
			//setcookie('fbs_'.$this->facebook_sdk->getAppId(), '', time()-100, '/', 'http://localhost/wordpress_rfe/');
			session_destroy();
		}
		if($this->facebook_sdk->getUser()) {
			try{
			}
			catch (FacebookApiException $e) {
				echo "<!--DEBUG: ".$e." :END-->";
				error_log($e);
			}
    }
		//echo $this->facebook_sdk->getAccessToken();
		return $this->facebook_sdk->getUser();
  }
  
  public function log_in_log_out() {
		global $wpdb;
		$this->get_facebook_data();
		$user = $this->facebook_sdk->getUser();
		if ($user) {
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$user_profile = $this->facebook_sdk->api('/me');
				$this->facebook_sdk->setExtendedAccessToken();
				$access_token = $this->facebook_sdk->getAccessToken();
			} catch (FacebookApiException $e) {
				echo '<div class="error"><p><strong>OAuth Error</strong>Error added to error_log: '.$e.'</p></div>';
				error_log($e);
				$user = null;
			}
		}
		// Login or logout url will be needed depending on current user state.
		$app_link_text = $app_link_url = null;
		if ($user && !isset($_POST['app_log_out'])) {
			$app_link_url = '#';
			$app_link_text = __("Logout of your app", 'facebook-albums');
		} else {
			$app_link_url = '#';
			$app_link_text = __('Log into Facebook with your app', 'facebook-albums');
		} ?>
	  <?php 
		if($user && !isset($_POST['app_log_out'])) : 
			?>
			<script> 
				wd_fb_log_in = true; 
			</script>
			<div style="float: right;">
				<span style="margin: 0 10px;"><?php echo $user_profile['name']; ?></span>
				<img src="https://graph.facebook.com/<?php echo $user_profile['id']; ?>/picture?type=square" style="vertical-align: middle"/>
			</div>
			<ul style="margin:0px;list-style-type:none">
				<li>
					<a href="https://developers.facebook.com/apps/<?php echo $this->app_id; ?>" target="_blank"><?php _e("View your application's settings.", 'facebook-albums'); ?></a>

				</li>
				<input class="button-primary" type="submit" name="app_log_out" value="Log out from app" />
			</ul>
	  <?php 
		else :
      if(isset($_POST['app_log_out'])) {
				?> 
				  <script> 
					  window.location = '<?php echo admin_url() . 'admin.php?page=options_ffwd'; ?>';
				  </script> 
			  <?php
			}
			?>
	    <a id="<?php echo WD_FB_PREFIX; ?>_login_button" class="<?php echo WD_FB_PREFIX; ?>_login_button" href="<?php echo $app_link_url; ?>"><?php echo $app_link_text; ?></a>
			<br>
			<label for="" class="ffwd_pro_only">This Feature is Available Only in PRO
				version</label>

			<?php
		endif; 
		?>
	  <div style="clear: both;">&nbsp;</div>	  
	  <?php
  }
  
  public function get_row_data($reset) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_option WHERE id="%d"', 1));
    if ($reset) {
      $row->autoupdate_interval = 90;
			$row->app_id = '';
			$row->app_secret = '';
			$row->view_on_fb = 1;
			$row->post_text_length = 200;
			$row->post_date_format = '';
			$row->date_timezone = 'Pacific/Midway';
			$row->event_street = 1;
			$row->event_city = 1;
			$row->event_country = 1;
			$row->event_zip = 1;
			$row->event_map = 1;
			$row->event_date = 1;
			$row->event_date_format = '';
			$row->event_desp_length = 200;
			$row->comments_replies = 1;
			$row->comments_filter = 'toplevel';
			$row->comments_order = 'chronological';
			$row->page_plugin_pos = 'bottom';
			$row->page_plugin_fans = 1;
			$row->page_plugin_cover = 1;
			$row->page_plugin_header = 0;
			$row->page_plugin_width = 380;

      $wpdb->update($wpdb->prefix . 'wd_fb_option', array(
        'autoupdate_interval' => $row->autoupdate_interval,
        'app_id' => $row->app_id,
        'app_secret' => $row->app_secret,
        'date_timezone' => $row->date_timezone,
        'post_date_format' => $row->post_date_format,
        'event_date_format' =>$row->event_date_format,
      ), array('id' => 1));
    }
    return $row;
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
<?php
/**
* @param version without first '1' or '2'
*
*/

function ffwd_update_diff($new_v, $old_v = 0.0){
	global $wpdb;


	if(version_compare($old_v,'1.0.2','<')) {
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . "wd_fb_option ADD `access_token` varchar(255) NOT NULL DEFAULT ''");

	}

	if(version_compare($old_v,'1.0.3','<')) {
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . "wd_fb_info ADD `event_order`  tinyint(4) NOT NULL DEFAULT 0");
		$wpdb->query("ALTER TABLE " . $wpdb->prefix . "wd_fb_info ADD `upcoming_events`  tinyint(4) NOT NULL DEFAULT 0");

	}


    if(version_compare($old_v,'1.0.12','<')) {

        $check_columns= $wpdb->get_results('SHOW COLUMNS FROM '. $wpdb->prefix . 'wd_fb_data LIKE "comments"');
        if(empty($check_columns)) {
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wd_fb_data ADD `reactions`  text NOT NULL DEFAULT ''");
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wd_fb_data ADD `comments`  text NOT NULL DEFAULT ''");
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wd_fb_data ADD `shares`  text NOT NULL DEFAULT ''");
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wd_fb_data ADD `attachments`  text NOT NULL DEFAULT ''");
            $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wd_fb_data ADD `who_post`  text NOT NULL DEFAULT ''");


            $query = "SELECT * FROM " . $wpdb->prefix . "wd_fb_info";
            $rows = $wpdb->get_results($query);
            require_once(WD_FFWD_DIR . '/framework/WDFacebookFeed.php');


            WDFacebookFeed::updateOnVersionChange($rows);
        }

    }




}


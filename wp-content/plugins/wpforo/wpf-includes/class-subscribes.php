<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
 

class wpForoSubscribe{
    public $default;
    public $options;

    public $already_sent_emails = array();

	static $cache = array( 'subscribe' => array() );
	
	function __construct(){
        $this->init_defaults();
        $this->init_options();
	}

    private function init_defaults(){
        $blogname = get_option('blogname', '');
        $adminemail = get_option('admin_email');

        $this->default = new stdClass;

        $this->default->options = array (
            'from_name' =>  $blogname . ' - ' . __('Forum', 'wpforo'),
            'from_email' =>  $adminemail,
            'admin_emails' => $adminemail,
            'new_topic_notify' => 1,
            'new_reply_notify' => 0,
            'confirmation_email_subject' =>  __("Please confirm subscription to [entry_title]", 'wpforo'),
            'confirmation_email_message' =>  __("Hello [member_name]!<br>\r\n Thank you for subscribing.<br>\r\n This is an automated response.<br>\r\n We are glad to inform you that after confirmation you will get updates from - [entry_title].<br>\r\n Please click on link below to complete this step.<br>\r\n [confirm_link]", 'wpforo') ,
            'new_topic_notification_email_subject' =>  __("New Topic", 'wpforo') ,
            'new_topic_notification_email_message' =>  __("Hello [member_name]!<br>\r\n New topic has been created on your subscribed forum - [forum].\r\n <br><br>\r\n <strong>[topic_title]</strong>\r\n <blockquote>\r\n [topic_desc]\r\n </blockquote>\r\n <br><hr>\r\n If you want to unsubscribe from this forum please use the link below.<br>\r\n [unsubscribe_link]", 'wpforo') ,
            'new_post_notification_email_subject' =>  __("New Reply", 'wpforo') ,
            'new_post_notification_email_message' =>  __("Hello [member_name]!<br>\r\n New reply has been posted on your subscribed topic - [topic].\r\n <br><br>\r\n <strong>[reply_title]</strong>\r\n <blockquote >\r\n [reply_desc]\r\n </blockquote>\r\n <br><hr>\r\n If you want to unsubscribe from this topic please use the link below.<br>\r\n [unsubscribe_link]", 'wpforo') ,
            'report_email_subject' => __("Forum Post Report", 'wpforo'),
            'report_email_message' => __("<strong>Report details:</strong>\r\n Reporter: [reporter], <br>\r\n Message: [message],<br>\r\n <br>\r\n [post_url]", 'wpforo'),
            'wp_new_user_notification_email_admin_subject' => __("[blogname] New User Registration", 'wpforo'),
            'wp_new_user_notification_email_admin_message' => __("New user registration on your site [blogname]:\r\n\r\nUsername: [user_login]\r\n\r\nEmail: [user_email]\r\n", 'wpforo'),
            'wp_new_user_notification_email_subject' => __("[blogname] Your username and password info", 'wpforo'),
            'wp_new_user_notification_email_message' => __("Username: [user_login]\r\n\r\nTo set your password, visit the following address:\r\n\r\n[set_password_url]\r\n\r\n", 'wpforo'),
            'reset_password_email_message' => __("Hello! <br>\r\n\r\n You asked us to reset your password for your account using the email address [user_login]. <br>\r\n\r\n If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen. <br>\r\n\r\n To reset your password, visit the following address: <br>\r\n\r\n [reset_password_url] <br>\r\n\r\n Thanks!", 'wpforo'),
            //'spam_notification_email_subject' => 'New Banned User',
            //'spam_notification_email_message' => "Hello [member_name]!<br>\r\n Please check this user's topics/posts and consider to Delete or Unban.<br>\r\n User Activity: [profile_activity_url]",
            'update' =>  '1',
            'user_mention_notify' => 1,
            'user_mention_email_subject' => __("You have been mentioned in forum post", 'wpforo'),
            'user_mention_email_message' => __("Hi [mentioned-user-name]! <br>\r\n\r\n You have been mentioned in a post on \"[topic-title]\" by [author-user-name].<br/><br/>\r\n\r\n Post URL: [post-url]", 'wpforo')
        );
    }

    private function init_options(){
        $this->options = get_wpf_option('wpforo_subscribe_options', $this->default->options);
    }
 	
 	function get_confirm_key(){
		return substr(md5(rand().time()), 0, 32);
	}
 	
	function add( $args = array() ){
		if( empty($args) && empty($_REQUEST['sbscrb']) ) return FALSE;
		if( empty($args) && !empty($_REQUEST['sbscrb']) ) $args = $_REQUEST['sbscrb']; 
		if( !isset($args['active']) || !$args['active'] ) $args['active'] = 0;
		
		extract( $args );
		if( !isset($itemid) || !( (isset($userid) && $userid) || (isset($user_email) && $user_email) ) || !isset($type) || !$type ) return FALSE;
		
		if( empty($confirmkey) ) $confirmkey = $this->get_confirm_key();
		
		if(WPF()->db->insert(
			WPF()->tables->subscribes,
			array( 
				'itemid' => wpforo_bigintval($itemid),
				'type' => sanitize_text_field($type),
				'confirmkey' => sanitize_text_field($confirmkey), 
				'userid' => wpforo_bigintval($userid),
				'active' => $active,
                'user_name' => ( isset($user_name) && $user_name ? $user_name : ''),
                'user_email' => ( isset($user_email) && $user_email ? $user_email : '')
			),
			array( 
				'%d',
				'%s', 
				'%s', 
				'%d',
				'%d',
                '%s',
                '%s'
			)
		)){
			if( isset($active) && $active == 1 ){
				WPF()->notice->add('You have been successfully subscribed', 'success');
			}else{
				WPF()->notice->add('Success! Thank you. Please check your email and click confirmation link below to complete this step.', 'success');
			}
			return $confirmkey;
		}
		
		WPF()->notice->add('Can\'t subscribe to this item', 'error');
		return FALSE;
	}
	
	function edit( $confirmkey = '' ){
		if( !$confirmkey && isset($_REQUEST['key']) && $_REQUEST['key'] ) $confirmkey = $_REQUEST['key']; 
		if( !$confirmkey ){
			WPF()->notice->add('Invalid request!', 'error');
			return FALSE;
		}
		
		if( WPF()->db->update(
			WPF()->tables->subscribes,
			array( 'active' => 1 ), 
			array( 'confirmkey' => sanitize_text_field($confirmkey) ),
			array( '%d' ),
			array( '%s' )
		) ){
            if( $sbs = $this->get_subscribe($confirmkey) ) WPF()->member->edit_is_email_confirmed($sbs['userid'], 1);

			WPF()->notice->add('You have been successfully subscribed', 'success');
			return TRUE;
		}
		
		WPF()->notice->add('Your subscription for this item could not be confirmed', 'error');
		return FALSE;
	}

	public function reset($data = array(), $all = '', $user = null){
        if(!$user && !WPF()->current_userid && !WPF()->current_user_email) return false;
        if (!$user) $user = ( WPF()->current_userid ? WPF()->current_userid : WPF()->current_user_email );
        $data = array_filter( (array) $data );
        $args = array(
            'itemid' => 0,
            'type'   => '',
            'userid' => 0,
            'active' => ( wpforo_feature('subscribe_conf') ? 0 : 1 ),
            'user_name' => '',
            'user_email' => ''
        );
        if( is_numeric($user) ){
            $args['userid'] = $user;
            $where = WPF()->db->prepare("`userid` = %d", $user);
        }else{
            $args['user_email'] = $user;
            $args['user_name'] = WPF()->current_user_display_name;
            $where = WPF()->db->prepare("`user_email` = %s", $user);
        }

        if( $this->is_email_confirmed($user) ) $args['active'] = 1;

        $types = array('forum', 'forum-topic');
        if( !$all ) array_push($types, 'forums', 'forums-topics');
        $sql = "DELETE FROM `".WPF()->tables->subscribes."` WHERE `type` IN('".implode("','", $types)."') AND " . $where;
        if(!$all && $data){
            $forumids = array_keys($data);
            $sql .= " AND `itemid` NOT IN(".implode(',', $forumids).")";
        }
        WPF()->db->query($sql);

        if(!$all && $data){
            foreach ($data as $forumid => $type){
                $sql = "SELECT `subid` FROM `".WPF()->tables->subscribes."` WHERE `type` IN('forum', 'forum-topic') AND `itemid` = %d AND " . $where;
                $sql = WPF()->db->prepare($sql, $forumid);
                if( $subid = WPF()->db->get_var($sql) ){
                    WPF()->db->update(
                        WPF()->tables->subscribes,
                        array('type' => sanitize_text_field($type), 'active' => $args['active']),
                        array('subid' => $subid),
                        array('%s', '%d'),
                        array('%d')
                    );
                }else{
                    $args['itemid'] = intval($forumid);
                    $args['type'] = sanitize_text_field($type);
                    $this->add($args);
                }
            }
        }

        if ($all){
            $sql = "SELECT `subid` FROM `".WPF()->tables->subscribes."` WHERE `type` IN('forums', 'forums-topics') AND `itemid` = 0 AND " . $where;
            if( $subid = WPF()->db->get_var($sql) ){
                WPF()->db->update(
                    WPF()->tables->subscribes,
                    array('type' => sanitize_text_field($all), 'active' => $args['active']),
                    array('subid' => $subid),
                    array('%s', '%d'),
                    array('%d')
                );
            }else{
                $args['itemid'] = 0;
                $args['type'] = sanitize_text_field($all);
                $this->add($args);
            }
        }

        return true;
    }
	
	function delete( $confirmkey = '' ){
		if( !$confirmkey && isset($_REQUEST['confirmkey']) && $_REQUEST['confirmkey'] ) $confirmkey = $_REQUEST['confirmkey'];
		if( !$confirmkey ){
			WPF()->notice->add('Invalid request!', 'error');
			return FALSE;
		}
		if( WPF()->db->delete( WPF()->tables->subscribes, array( 'confirmkey' => sanitize_text_field($confirmkey) ), array( '%s' ) ) ){
			WPF()->notice->add('You have been successfully unsubscribed', 'success');
			return TRUE;
		}
		
		WPF()->notice->add('Could not be unsubscribe from this item', 'error');
		return FALSE;
	}
	
	function get_subscribe( $args = array() ){
		
		$cache = WPF()->cache->on('memory_cashe');
		
		if( is_string($args) ) $args = array("confirmkey" => sanitize_text_field($args));
		if( empty($args) && !empty($_REQUEST['sbscrb']) ) $args = $_REQUEST['sbscrb']; 
		if( empty($args) ) return FALSE;
		extract( $args, EXTR_OVERWRITE );
		if( (!isset($itemid) || !$itemid || !( (isset($userid) && $userid) || (isset($user_email) && $user_email) ) || !isset($type) || !$type)
            && (!isset($confirmkey) || !$confirmkey) ) return FALSE;
		if( isset($confirmkey) && $confirmkey){
			$where = " `confirmkey` = '".esc_sql(sanitize_text_field($confirmkey))."'";
		}elseif( isset($itemid) && $itemid && isset($userid) && $userid && isset($type) && $type ){
			$where = " `itemid` = ".wpforo_bigintval($itemid)." AND `userid` = ".wpforo_bigintval($userid)." AND `type` = '".esc_sql(sanitize_text_field($type))."'";
		}elseif( isset($itemid) && $itemid && isset($user_email) && $user_email && isset($type) && $type ){
			$where = " `itemid` = ".wpforo_bigintval($itemid)." AND `user_email` = '".esc_sql($user_email)."' AND `type` = '".esc_sql(sanitize_text_field($type))."'";
		}else{
			return FALSE;
		}
		$UID = ( $userid ? $userid : $user_email );
		if( $cache && isset(self::$cache['subscribe'][$itemid][$UID][$type]) ){
			return self::$cache['subscribe'][$itemid][$UID][$type];
		}
		$sql = "SELECT * FROM `".WPF()->tables->subscribes."` WHERE " . $where;
		$subscribe = WPF()->db->get_row($sql, ARRAY_A);
		if($cache && !empty($subscribe)){
			self::$cache['subscribe'][$itemid][$UID][$type] = $subscribe;
		}
		return $subscribe;
	}
	
	function get_subscribes( $args = array(), &$items_count = 0 ){
		
		$default = array( 
		  'itemid' => NULL,
		  'type' => array(),  // topic | forum
		  'userid' => NULL, //
		  'active' => 1,
		  'orderby' => 'subid', // order by `field`
		  'order' => 'DESC', // ASC DESC
		  'offset' => NULL, // OFFSET
		  'row_count' => NULL, // ROW COUNT
		);
		
		$args = wpforo_parse_args( $args, $default );
        extract($args);

        $sql = "SELECT * FROM `".WPF()->tables->subscribes."`";
        $wheres = array();

        if( $type ) $wheres[] = " `type` IN( '" . implode("','", array_map('esc_sql', (array) $type ) ) . "')";
        if( !is_null($active) )   $wheres[] = " `active` = "   . intval($active);
        if( !is_null($itemid) )   $wheres[] = " `itemid` = "   . wpforo_bigintval($itemid);
        if( !is_null($userid) )   $wheres[] = " `userid` = "   . wpforo_bigintval($userid);

        if(!empty($wheres)) $sql .= " WHERE " . implode( " AND ", $wheres );

        $item_count_sql = preg_replace('#SELECT.+?FROM#isu', 'SELECT count(*) FROM', $sql);
        $item_count_sql = preg_replace('#ORDER.+$#is', '', $item_count_sql);
        if( $item_count_sql ) $items_count = WPF()->db->get_var($item_count_sql);

        $sql .= " ORDER BY `$orderby` " . $order;

        if( !is_null($row_count) ){
            if( !is_null($offset) ){
                $sql .= esc_sql(" LIMIT $offset,$row_count");
            }else{
                $sql .= esc_sql(" LIMIT $row_count");
            }
        }
        return WPF()->db->get_results($sql, ARRAY_A);

	}
	
	function get_confirm_link($args){
		if(is_string($args)) return wpforo_home_url( "?wpforo=sbscrbconfirm&key=" . sanitize_text_field($args) );
		
		if($args['type'] == 'forum'){
			$url = WPF()->forum->get_forum_url($args['itemid']) . '/';
		}elseif($args['type'] == 'topic'){
			$url = WPF()->topic->get_topic_url($args['itemid']) . '/';
		}else{
			$url = wpforo_home_url();
		}
		return wpforo_home_url( $url . "?wpforo=sbscrbconfirm&key=" . sanitize_text_field($args['confirmkey']) );
	}
	
	function get_unsubscribe_link($confirmkey){
		return wpforo_home_url( "?wpforo=unsbscrb&key=" . sanitize_text_field($confirmkey) );
	}

	public function is_email_confirmed($user = null){
        if(!$user && !WPF()->current_userid && !WPF()->current_user_email) return false;
        if (!$user) $user = ( WPF()->current_userid ? WPF()->current_userid : WPF()->current_user_email );

        $sql = ( is_numeric($user) ? "`userid` = %d" : "`user_email` = %s" );
        $where = WPF()->db->prepare($sql, $user);

        if( WPF()->current_userid === $user && wpfval(WPF()->current_user, 'is_email_confirmed') ){
            $has_confirmed = WPF()->current_user['is_email_confirmed'];
        }elseif( is_numeric($user) ){
            $has_confirmed = WPF()->member->get_is_email_confirmed(WPF()->current_userid);
        }else{
            $has_confirmed = WPF()->db->get_var( "SELECT `subid` FROM `".WPF()->tables->subscribes."` WHERE `active` = 1 AND " . $where );
        }

        return  (bool) $has_confirmed;
    }
	
}
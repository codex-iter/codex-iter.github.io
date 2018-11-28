<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

class wpForoLogs{

    public $default;

	public function __construct(){
		$this->init_defaults();
	}
	
	private function init_defaults(){
	    $this->default = array();
    }

    public function read(){

        $data = WPF()->current_object;

        if( wpforo_feature('view-logging') && wpfval($data, 'template') ){
            if( $data['template'] == 'post' ){
                if( wpfval($data, 'topicid') && wpfval($data, 'topic', 'last_post') ) {
                    $this->read_item( $data['topicid'], $data['topic']['last_post'], 'wpf_read_topics' );
                }
                if( wpfval($data, 'forumid') ) {
                    $end_date = time() - (14 * 24 * 60 * 60);
                    $args = array( 'read' => false, 'forumid'=> $data['forumid'], 'orderby' => 'modified', 'order' => 'ASC', 'row_count' => 1, 'where' => "`modified` > '" . date( 'Y-m-d H:i:s', $end_date ) . "'" );
                    $topics = WPF()->topic->get_topics($args, $items_count);
                    if( empty($topics) ){
                        if( wpfval($data, 'forum', 'last_postid') ){
                            $last_postid = $data['forum']['last_postid'];
                        } elseif( wpfval($data, 'topic', 'last_post') ) {
                            $last_postid = $data['topic']['last_post'];
                        }
                        $this->read_item( $data['forumid'], $last_postid, 'wpf_read_forums' );
                    } else {
                        foreach( $topics as $topic ){
                            if( wpfval($topic, 'last_post') ){
                                if( $items_count == 1 && $topic['topicid'] == $data['topicid'] ){
                                    if( wpfval($data, 'forum', 'last_postid') ) {
                                        $last_postid = $data['forum']['last_postid'];
                                    } elseif( wpfval($data, 'topic', 'last_post') ) {
                                        $last_postid = $data['topic']['last_post'];
                                    } elseif( wpfval($topic, 'last_post') ) {
                                        $last_postid = $topic['last_post'];
                                    }
                                } else {
                                    $last_postid = ($topic['last_post'] - 1);
                                }
                                if( isset($last_postid) ){
                                    $this->read_item( $data['forumid'], $last_postid, 'wpf_read_forums' );
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function read_item( $itemid, $item_last_postid, $key ){

	    $log = false;
        $logdb = false;
        $read_ids = array();
        $read_db_ids = array();
        $login = is_user_logged_in();

        if( $itemid && $key ) {
            if( WPF()->tools_legal['cookies'] ) $read_ids = wpforo_getcookie( $key, false );
            if( wpfval(WPF()->current_usermeta, $key) ) $read_db_ids = wpforo_current_usermeta( $key );
            if( !$read_ids || !is_array($read_ids) ) $read_ids = array();
            if( !$read_db_ids || !is_array($read_ids) ) $read_db_ids = array();

            if( empty($read_ids) || ($login && empty($read_db_ids)) ) {
                if( empty($read_ids) ) $log = true;
                if( empty($read_db_ids) && $login ) $logdb = true;
                $read_ids = array( $itemid => $item_last_postid );
            }
            elseif( !wpfval($read_ids, $itemid) ||
                !wpfval($read_db_ids, $itemid) ||
                (int) $read_ids[$itemid] < (int) $item_last_postid ||
                (int) $read_db_ids[$itemid] < (int) $item_last_postid
            ){
                $log = true;
                if( $login ) $logdb = true;
                if( is_array($read_ids) && is_array($read_db_ids) ){
                    $keep_guest_read = apply_filters( 'wpforo_keep_guest_read', false );
                    $merge_guest_read = apply_filters( 'wpforo_merge_guest_read', true );
                    if( $merge_guest_read ){
                        $read_ids = ( $keep_guest_read ) ? $read_ids + $read_db_ids : $read_db_ids + $read_ids;
                    } else {
                        $read_ids = ( $login ) ? $read_db_ids : $read_ids;
                    }
                }
                $read_ids[$itemid] = $item_last_postid;
            }
            $this->log_item( $read_ids, $key, $logdb, $log );
        }
    }

    public function log_item( $read_ids, $key, $logdb = true, $log = true ){
        if( $log && WPF()->tools_legal['cookies'] ){
            wpforo_setcookie( $key, $read_ids, false );
        }
        if( $logdb && is_user_logged_in() ){
            $max = apply_filters( 'wpforo_max_logged_topics', 200 );
            $num = count($read_ids);
            if( $num > $max ){
                $delta = $num - $max; if( $delta > 0 ) $read_ids = array_slice($read_ids, $delta, null, true);
            }
            update_user_meta( WPF()->current_userid, $key, $read_ids );
        }
    }

    public function unread( $id, $in = 'forum' ){

        $new = false;
        if( !wpforo_feature('view-logging') ) return false;

	    if( $id ){
	        if( $in == 'forum' ){
	            $last_postid = wpforo_forum( $id, 'last_postid' );
	            if( !$last_postid ){
                    return false;
                }
                //Pass all posts created before "Mark all read" action
                $last_read_postid = $this->get_all_read( 'post' );
                if( $last_read_postid ){
                    if( (int) $last_postid > (int) $last_read_postid ){
                        $new = true;
                    } else {
                        $new = false;
                    }
                }
                //Check the last read post of current forum
                if( !$last_read_postid || ($last_read_postid && $new) ){
                    $read_forums = $this->get_read_forums();
                    if( wpfkey($read_forums, $id) ){
                        $last_read_postid = $read_forums[ $id ];
                        if( (int) $last_postid > (int) $last_read_postid ){
                            $new = true;
                        } else {
                            $new = false;
                        }
                    } else{
                        $new = true;
                    }
                }
                $new = apply_filters( 'wpforo_new_in_forum', $new, $id );
            }
            elseif( $in == 'topic' ){
                $last_postid = wpforo_topic( $id, 'last_post' );
                if( !$last_postid ){
                    return false;
                }
                //Pass all posts created before "Mark all read" action
                $last_read_postid = $this->get_all_read( 'post' );
                if( $last_read_postid ){
                    if( (int) $last_postid > (int) $last_read_postid ){
                        $new = true;
                    } else {
                        $new = false;
                    }
                }
                //Check the last read post of current forum
                if( !$last_read_postid || ($last_read_postid && $new) ){
                    $read_topics = $this->get_read_topics();
                    if( wpfkey($read_topics, $id) ){
                        $last_read_postid = $read_topics[ $id ];
                        if( (int) $last_postid > (int) $last_read_postid ){
                            $new = true;
                        } else{
                            $new = false;
                        }
                    } else{
                        $new = true;
                    }
                }
                $new = apply_filters( 'wpforo_new_in_topic', $new, $id );
            }
        }
        return $new;
    }

    public function get_read( $return = 'topicid' ){
        $topic_ids = array();
	    $read_topics = $this->get_read_topics();
        if( !empty($read_topics) && is_array($read_topics) ){
            $last_read_postid = $this->get_all_read( 'post' );
            foreach( $read_topics as $topicid => $postid ){
                if( $last_read_postid && (int) $postid <= (int) $last_read_postid ) {
                    unset( $read_topics[ $topicid ] );
                } else {
                    $current_last_postid = wpforo_topic( $topicid, 'last_post' );
                    if( $current_last_postid ){
                        if( (int) $current_last_postid <= (int) $postid ){
                            $topic_ids[] = $topicid;
                        }
                    } else{
                        $topic_ids[] = $topicid;
                    }
                }
            }
        }
        if( $return == 'topicid' ){
            return $topic_ids;
        } else {
            return $read_topics;
        }
    }

    public function get_read_forums(){
        if( is_user_logged_in() && wpfval(WPF()->current_usermeta, 'wpf_read_forums') ){
            $read_forums = wpforo_current_usermeta( 'wpf_read_forums' );
        } else {
            $read_forums = wpforo_getcookie( 'wpf_read_forums', false );
        }
        return $read_forums;
    }

    public function get_read_topics(){
        if( is_user_logged_in() && wpfval(WPF()->current_usermeta, 'wpf_read_topics') ){
            $read_topics = wpforo_current_usermeta( 'wpf_read_topics' );
        } else {
            $read_topics = wpforo_getcookie( 'wpf_read_topics', false );
        }
        return $read_topics;
    }

    public function last( $item ){
        if( $item == 'forum'){
            $id = WPF()->db->get_var("SELECT MAX(`forumid`) FROM " . WPF()->tables->forums );
        } elseif( $item == 'topic' ){
            $id = WPF()->db->get_var("SELECT MAX(`topicid`) FROM " . WPF()->tables->topics );
        } elseif( $item == 'post' ){
            $id = WPF()->db->get_var("SELECT MAX(`postid`) FROM " . WPF()->tables->posts );
        } else {
            $id = 0;
        }
        return intval($id);
    }

    public function mark_all_read(){
        $last_forumid = $this->last('forum');
        $last_topicid = $this->last('topic');
        $last_postid = $this->last('post');
        $last = array('forum' => $last_forumid, 'topic' => $last_topicid, 'post' => $last_postid);
        if( is_user_logged_in() ){
            update_user_meta( WPF()->current_userid, 'wpf_all_read', $last );
            update_user_meta( WPF()->current_userid, 'wpf_read_forums', array() );
            update_user_meta( WPF()->current_userid, 'wpf_read_topics', array() );
        }
        wpforo_setcookie( 'wpf_all_read', $last );
        wpforo_setcookie( 'wpf_read_forums', '' );
        wpforo_setcookie( 'wpf_read_topics', '' );
    }

    public function get_all_read( $item ){
        $last = false;
        if( is_user_logged_in() ){
            $last = wpforo_current_usermeta( 'wpf_all_read' );
        } else {
            $last = wpforo_getcookie('wpf_all_read');
        }
        if( $item && wpfval($last, $item) ){
            return intval($last[ $item ]);
        }
        return false;
    }

    public function visitors( $item ){
        $data = array();
        $visitors = array();
        if( wpfval( $item, 'topicid') ){
            $keep_vistors_data = apply_filters('wpforo_keep_visitors_data', 4000);
            $time = (int) current_time('timestamp', 1 ) - (int) $keep_vistors_data;
            $visitors = WPF()->db->get_results("SELECT * FROM `" . WPF()->tables->visits . "` WHERE `topicid` = " . intval($item['topicid']) . " AND `time` > " . intval( $time ) . " ORDER BY `id` DESC" , ARRAY_A);
        } elseif ( wpfval($item, 'forumid') ){
            $keep_vistors_data = apply_filters('wpforo_keep_visitors_data', 4000);
            $time = (int) current_time('timestamp', 1 ) - (int) $keep_vistors_data;
            $visitors = WPF()->db->get_results("SELECT * FROM `" . WPF()->tables->visits . "` WHERE `forumid` = " . intval($item['forumid']) . " AND `time` > " . intval( $time ) . " ORDER BY `id` DESC" , ARRAY_A);
        }
        if( !empty($visitors) ){
	        $online_period = (int) current_time('timestamp', 1 ) - (int) WPF()->member->options['online_status_timeout'];
            foreach( $visitors as $visitor ){
                if( wpfval($visitor, 'userid') ){
                    if( (int) $visitor['time'] < (int) $online_period ){
                        $data['users']['viewed'][] = $visitor;
                    } else {
                        $gone = WPF()->db->get_var("SELECT `id` FROM `" . WPF()->tables->visits . "` WHERE `userid` = " . intval($visitor['userid']) . " AND `time` > " . intval($visitor['time']) . " LIMIT 1" );
                        if( $gone ){
                            $data['users']['viewed'][] = $visitor;
                        } else {
                            $data['users']['viewing'][] = $visitor;
                        }
                    }
                } elseif( (int) $visitor['time'] > $online_period ) {
                    $data['guests'][] = $visitor;
                }
            }
        }
        return $data;
    }

    public function visit(){
	    if( wpforo_feature('track-logging') ){
	        if( ( wpforo_current_user_is('admin') ||
                    WPF()->current_user_groupid == 1 ) &&
                        !WPF()->post->options['display_admin_viewers'] ){
                            return false;
            }
            $data = WPF()->current_object;
            $visitor = WPF()->current_user;
            $template = ( wpfval($data, 'template') ) ? $data['template'] : '';
            $templates = array('forum', 'topic', 'post');
            $templates = apply_filters('wpforo_track_visitors_in_pages', $templates);
            if( $template ){
                if( $template == 'post' && in_array($template, $templates) ){
                    //topic page (post list)
                    if( wpfval($data, 'topic', 'topicid') && wpfval($data, 'topic', 'forumid') ){
                        $forumid = intval( $data['topic']['forumid'] );
                        $topicid = intval( $data['topic']['topicid'] );
                        $this->add_visit( $visitor, $forumid, $topicid );
                    }
                }
                elseif( $template == 'topic' && in_array($template, $templates) ) {
                    //forum page (topic list)
                    if( wpfval($data, 'forum', 'forumid') ){
                        $forumid = intval( $data['forum']['forumid'] );
                        $this->add_visit( $visitor, $forumid );
                    }
                }
                elseif( $template == 'forum' && in_array($template, $templates) ) {
                    //forum home page
                    $this->add_visit( $visitor );
                }
                elseif( in_array($template, $templates) ) {
                    //other pages (profile, search, members, etc..)
                    $this->add_visit( $visitor );
                }
            }
        }
    }

    public function add_visit( $visitor, $forumid = 0, $topicid = 0 ){
        $time = esc_sql(current_time('timestamp', 1 ));
        if( WPF()->current_userid ){
            $userid = ( wpfval($visitor, 'userid') ) ? intval($visitor['userid']) : 0;
            $name = ( wpfval($visitor, 'display_name') ) ? esc_sql($visitor['display_name']) : '';
            WPF()->db->query("INSERT INTO `".WPF()->tables->visits."` (`userid`, `name`, `time`, `topicid`, `forumid`) VALUES( " . $userid . ", '" . $name . "', '" . $time . "', " . $topicid . ", " . $forumid . " ) ON DUPLICATE KEY UPDATE `time` = '" . $time . "'");
        } elseif( !wpforo_is_bot() ) {
            $ip = ( wpfval( $_SERVER, 'REMOTE_ADDR' ) ) ? substr( md5( $_SERVER['REMOTE_ADDR'] ),0, 15 ) : '-noip-';
            if( $ip ){
                $guest = WPF()->db->get_var("SELECT `id` FROM `".WPF()->tables->visits."` WHERE `ip` = '" . esc_sql($ip) . "' LIMIT 1");
                if( $guest ){
                    WPF()->db->query("UPDATE `".WPF()->tables->visits."` SET `topicid` = " . intval($topicid) . ", `forumid` = " . intval($forumid) . ", `time` = '" . $time . "' WHERE `id` = " . intval($guest) );
                } else {
                    WPF()->db->query("INSERT INTO `".WPF()->tables->visits."` (`ip`, `time`, `topicid`, `forumid`) VALUES( '" . esc_sql($ip) . "', '" . $time . "', " . $topicid . ", " . $forumid . " )");
                }
            }
        }
    }

}
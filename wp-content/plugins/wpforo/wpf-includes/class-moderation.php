<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class wpForoModeration
{
    public $post_statuses;

    public function __construct(){
        $this->post_statuses = apply_filters('wpforo_post_statuses', array('approved', 'unapproved'));
    }

	public function init(){
		if( !WPF()->perm->usergroup_can( 'aup' ) ){
			add_filter('wpforo_add_topic_data_filter', array(&$this, 'auto_moderate'));
			add_filter('wpforo_add_post_data_filter', array(&$this, 'auto_moderate'));
		}
		else{
			if( WPF()->member->current_user_is_new() ){
				if (class_exists('Akismet')) {
					add_filter('wpforo_add_topic_data_filter', array(&$this, 'akismet_topic'), 8);
					add_filter('wpforo_edit_topic_data_filter', array(&$this, 'akismet_topic'), 8);
					add_filter('wpforo_add_post_data_filter', array(&$this, 'akismet_post'), 8);
					add_filter('wpforo_edit_post_data_filter', array(&$this, 'akismet_post'), 8);
				}
				if ( WPF()->tools_antispam['spam_filter'] ) {
					add_filter('wpforo_add_topic_data_filter', array(&$this, 'spam_topic'), 9);
					add_filter('wpforo_edit_topic_data_filter', array(&$this, 'spam_topic'), 9);
					add_filter('wpforo_add_topic_data_filter', array(&$this, 'spam_post'), 9);
					add_filter('wpforo_edit_topic_data_filter', array(&$this, 'spam_post'), 9);
					add_filter('wpforo_add_post_data_filter', array(&$this, 'spam_post'), 9);
					add_filter('wpforo_edit_post_data_filter', array(&$this, 'spam_post'), 9);
				}
			}
			if ( WPF()->tools_antispam['spam_filter'] ) {
				add_filter('wpforo_add_topic_data_filter', array(&$this, 'auto_moderate'), 10);
				add_filter('wpforo_add_post_data_filter', array(&$this, 'auto_moderate'), 10);
			}
            if( !WPF()->perm->can_link() ){
                add_filter('wpforo_add_topic_data_filter', array(&$this, 'remove_links'), 20);
                add_filter('wpforo_edit_topic_data_filter', array(&$this, 'remove_links'), 20);
                add_filter('wpforo_add_post_data_filter', array(&$this, 'remove_links'), 20);
                add_filter('wpforo_edit_post_data_filter', array(&$this, 'remove_links'), 20);
            }
        }
	}    
	
	public function get_post_status_dname($status)
    {
        $status = intval($status);
        return (isset($this->post_statuses[$status]) ? $this->post_statuses[$status] : $status);
    }

    public function get_moderations($args, &$items_count = 0)
    {
        if (isset($_GET['filter_by_userid']) && wpforo_bigintval($_GET['filter_by_userid'])) $args['userid'] = wpforo_bigintval($_GET['filter_by_userid']);
        $filter_by_status = intval((isset($_GET['filter_by_status']) ? $_GET['filter_by_status'] : 1));
        $args['status'] = $filter_by_status;
		if( !isset($_GET['order']) ) $args['orderby'] = '`created` DESC, `postid` DESC';
        $posts = WPF()->post->get_posts($args, $items_count);
        return $posts;
    }

    public function search($needle, $fields = array())
    {
        $posts = WPF()->post->search($needle);
        $pids = array();
        foreach ($posts as $post) $pids[] = $post['postid'];
        return $pids;
    }

    public function post_approve($postid)
    {
        return WPF()->post->status($postid, 0);
    }

    public function post_unapprove($postid)
    {
        return WPF()->post->status($postid, 1);
    }

    public function get_view_url($arg)
    {
        return WPF()->post->get_post_url($arg);
    }

    public function akismet_topic($item)
    {
        $post = array();
        $post['user_ip'] = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null);
        $post['user_agent'] = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null);
        $post['referrer'] = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
        $post['blog'] = get_option('home');
        $post['blog_lang'] = get_locale();
        $post['blog_charset'] = get_option('blog_charset');
        $post['comment_type'] = 'forum-post';

        if (empty($item['forumid'])) {
            $topic = WPF()->topic->get_topic($item['topicid']);
            $item['forumid'] = $topic['forumid'];
        }

        $post['comment_author'] = WPF()->current_user['user_nicename'];
        $post['comment_author_email'] = WPF()->current_user['user_email'];
        $post['comment_author_url'] = WPF()->member->get_profile_url(WPF()->current_userid);
        $post['comment_post_modified_gmt'] = current_time('mysql', 1);
        $post['comment_content'] = $item['title'] . "  \r\n  " . $item['body'];
        $post['permalink'] = WPF()->forum->get_forum_url($item['forumid']);

       $response = Akismet::http_post(Akismet::build_query($post), 'comment-check');
        if ($response[1] == 'true') {
			$this->ban_for_spam( WPF()->current_userid );
            $item['status'] = 1;
        }

        return $item;
    }

    public function akismet_post($item)
    {
        $post = array();
        $post['user_ip'] = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null);
        $post['user_agent'] = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null);
        $post['referrer'] = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
        $post['blog'] = get_option('home');
        $post['blog_lang'] = get_locale();
        $post['blog_charset'] = get_option('blog_charset');
        $post['comment_type'] = 'forum-post';

        $topic = WPF()->topic->get_topic($item['topicid']);
        
        $post['comment_author'] = WPF()->current_user['user_nicename'];
        $post['comment_author_email'] = WPF()->current_user['user_email'];
        $post['comment_author_url'] = WPF()->member->get_profile_url(WPF()->current_userid);
        $post['comment_post_modified_gmt'] = $topic['modified'];
        $post['comment_content'] = $item['body'];
        $post['permalink'] = WPF()->topic->get_topic_url($item['topicid']);

        $response = Akismet::http_post(Akismet::build_query($post), 'comment-check');
        if ($response[1] == 'true') {
			$this->ban_for_spam( WPF()->current_userid );
            $item['status'] = 1;
        }

        return $item;
    }
	
	public function spam_attachment(){
		$upload_dir = wp_upload_dir();
		$default_attachments_dir =  $upload_dir['basedir'] . '/wpforo/default_attachments/';
		if(is_dir($default_attachments_dir)){
			if ($handle = opendir($default_attachments_dir)){
				while (false !== ($filename = readdir($handle))){
					$file = $default_attachments_dir . '/' . $filename;
					if( $filename == '.' ||  $filename == '..') continue;
					$level = $this->spam_file($filename); 
					if( $level > 2 ){
						$link = '<a href="' . admin_url('admin.php?page=wpforo-tools&tab=antispam#spam-files') . '"><strong>&gt;&gt;</strong></a>';
						$phrase = '<strong>SPAM! - </strong>' . sprintf( __('Probably spam file attachments have been detected by wpForo Spam Control. Please moderate suspected files in Forums &gt; Tools &gt; Antispam Tab.', 'wpforo'), $link);
						WPF()->notice->add( $phrase, 'error' );
						return true;
					}
				}
			}
		}
		return false;
	}
	
	public function spam_file( $item, $type = 'file' ){
		if( !isset($item) || !$item ) return false;
		$level = 0;
		$item = strtolower($item);
		$spam_file_phrases = array(
			0 => array( 'watch', 'movie'),
			1 => array( 'download', 'free')
		);
		if($type == 'file'){
            $ext_whitelist = explode('|', WPF()->tools_antispam['exclude_file_ext'] );
            $ext_whitelist = array_map('trim', $ext_whitelist);
            $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
            $ext_risk = array('pdf', 'doc', 'docx', 'txt', 'htm', 'html', 'rtf', 'xml', 'xls', 'xlsx', 'php', 'cgi');
            $ext_risk = wpforo_clear_array($ext_risk, $ext_whitelist);
            $ext_high_risk = array('php', 'cgi', 'exe');
            $ext_high_risk = wpforo_clear_array($ext_high_risk, $ext_whitelist);
            if( in_array($ext, $ext_risk) ){
				$has_post = WPF()->db->get_var( "SELECT `postid` FROM `".WPF()->tables->posts."` WHERE `body` LIKE '%" . esc_sql( $item ) . "%' LIMIT 1" );
				foreach($spam_file_phrases as $phrases){
					foreach($phrases as $phrase){
						if( strpos($item, $phrase) !== FALSE ){
							if( !$has_post ){
								$level = 4; break 2;
							}
							else{
								$level = 2; break 2;
							}
						}
					}
				}
				if( !$level ){
					if( !$has_post ){
						$level = 3;
					}
					else{
						if( in_array($ext, $ext_high_risk) ){
							$level = 5;
						}
						else{
							$level = 1;
						}
					}
				}
			}
			return $level;
		}
		elseif($type == 'file-open'){
			$ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
			$allow_to_open = array('pdf', 'doc', 'docx', 'txt', 'rtf', 'xls', 'xlsx');
			if( in_array($ext, $allow_to_open) ){
				return true;
			}
			else{
				return false;
			}
		}
		return 0;
	}
	
	public function spam_topic($topic)
	{
		if( empty($topic) ) return $topic;
		if( isset($topic['title']) ){
			$item = $topic['title'];
		}
		else{
			return $topic;
		}
		$len = wpforo_strlen($item);
		if( $len < 10 ) return $topic;
		$item = strip_tags($item);
		$is_similar = false;
		$topic_args = array( 'userid' => $topic['userid'] );
		$topics = WPF()->topic->get_topics($topic_args);
		$sc_level = ( isset(WPF()->tools_antispam['spam_filter_level_topic'])) ? intval(WPF()->tools_antispam['spam_filter_level_topic']) : 100;
		if( $sc_level > 100 ) $sc_level = 60; $sc_level = (101 - $sc_level);
		if( !empty($topics) ){
			$count = count($topics);
			$keys[0] = array_rand($topics); if( $count > 1) $keys[1] = array_rand($topics);
			$check_1 = (isset($keys[0])) ? strip_tags($topics[$keys[0]]['title']) : '';
			$check_2 = (isset($keys[1])) ? strip_tags($topics[$keys[1]]['title']) : '';
			if($check_1){ similar_text($item, $check_1, $percent); if( $percent > $sc_level ) $is_similar = true; }
			if($check_2 && !$is_similar){ similar_text($item, $check_2, $percent); if( $percent > $sc_level ) $is_similar = true; }
			if( $is_similar ){
				$this->ban_for_spam( WPF()->current_userid );
				$topic['status'] = 1;
			}
		}
		return $topic;
	}
	
	public function spam_post($post)
	{
		if( empty($post) ) return $post;
		if( isset($post['body']) ){
			$item = $post['body'];
		}
		else{
			return $post;
		}

		$item = strip_tags($item);
		$is_similar = false;
		$post_args = array( 'userid' => $post['userid'] );
		$posts = WPF()->post->get_posts($post_args);
		$sc_level = ( isset(WPF()->tools_antispam['spam_filter_level_post'])) ? intval(WPF()->tools_antispam['spam_filter_level_post']) : 100;
		if( $sc_level > 100 ) $sc_level = 70; $sc_level = (101 - $sc_level);
		if( !empty($posts) ){
			$count = count($posts);
			$keys[0] = array_rand($posts); if( $count > 1) $keys[1] = array_rand($posts);
			$check_1 = (isset($keys[0])) ? strip_tags($posts[$keys[0]]['body']) : '';
			$check_2 = (isset($keys[1])) ? strip_tags($posts[$keys[1]]['body']) : '';
			if($check_1){ similar_text($item, $check_1, $percent); if( isset($percent) && $percent > $sc_level ) $is_similar = true; }
			if($check_2 && !$is_similar){ similar_text($item, $check_2, $percent); if( isset($percent) && $percent > $sc_level ) $is_similar = true; }
			if( $is_similar ){
				$this->ban_for_spam( WPF()->current_userid );
				$post['status'] = 1;
			}
		}
		return $post;
	}
	
	public function auto_moderate($item){
		
		if( empty($item) ) return $item;
		if( WPF()->perm->usergroup_can( 'em' ) ){
			$item['status'] = 0;
			return $item;
		}
		if( !WPF()->perm->usergroup_can( 'aup' ) ){
			$item['status'] = 1;
			return $item;
		}
		
		if( WPF()->member->current_user_is_new() ){
            $if_link_found = apply_filters('wpforo_new_user_post_unapproved_if_link_found', true );
			if( $if_link_found && isset($item['body']) && isset($item['title']) && ( $this->has_link($item['body']) || $this->has_link($item['title']) ) ){
				$item['status'] = 1;
			}
            $if_no_approved = apply_filters('wpforo_new_user_post_unapproved_if_no_approved', false );
            if( $if_no_approved && !$this->has_approved( WPF()->current_userid ) ){
                $item['status'] = 1;
            }
            $unapproved_all = apply_filters('wpforo_new_user_post_unapproved_all', true );
            if( $unapproved_all && ( ( isset($item['status']) && $item['status'] == 1 ) || $this->has_unapproved( WPF()->current_userid ) ) ){
                $this->set_all_unapproved( WPF()->current_userid );
                $item['status'] = 1;
            }
		}

        $must_have_one_approved = apply_filters('wpforo_post_moderation_must_have_one_approved', false );
        if( $must_have_one_approved && !$this->has_approved( WPF()->current_userid ) ){
            $item['status'] = 1;
        }

		return $item;
	}
	
	public function has_approved($user){
		if( !$user ) return false;
		if( isset($user['ID']) ){
			$userid = intval($user['ID']);
		}
		else{
			$userid = intval($user);
		}
		$has_approved_post = WPF()->db->get_var( "SELECT `postid` FROM `".WPF()->tables->posts."` WHERE `userid` = '" . intval($userid) . "' AND `status` = 0 LIMIT 1" );
		if( $has_approved_post ){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function has_unapproved($user){
		if( empty($user) ) return false;
		if( isset($user['ID']) ){
			$userid = intval($user['ID']);
		}
		else{
			$userid = intval($user);
		}
		$has_unapproved_post = WPF()->db->get_var( "SELECT `postid` FROM `".WPF()->tables->posts."` WHERE `userid` = '" . intval($userid) . "' AND `status` = 1 LIMIT 1" );
		if( $has_unapproved_post ){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function ban_for_spam( $userid ){
		if ( isset($userid) && WPF()->tools_antispam['spam_user_ban'] ) {
			if( !$this->has_approved( WPF()->current_userid ) ){
				WPF()->member->autoban( $userid );
			}
		}
	}
	
	public function set_all_unapproved( $userid ){
		if ( isset($userid) ) {
			WPF()->db->update( WPF()->tables->topics, array('status' => 1), array('userid' => intval($userid)), array('%d'), array('%d'));
			WPF()->db->update( WPF()->tables->posts, array('status' => 1), array('userid' => intval($userid)), array('%d'), array('%d'));
		}
	}
	
	public function remove_links( $item ){
		if( wpfval($item, 'body') ){
		    $domain = wpforo_get_request_uri();
            $urls = wp_extract_urls( $item['body'] );
            if( !empty($urls) ){
                foreach( $urls as $k => $url ){
                    $url = parse_url( $url );
                    if( wpfval($url, 'host') ){
                        if( strpos( $domain, $url['host'] ) !== FALSE ) unset($urls[$k]);
                    }
                }
                if( !empty($urls) ){
                    $item['body'] = str_replace($urls, ' <span style="color:#aaa;">' . wpforo_phrase('removed link', false, false) . '</span> ', $item['body']);
                }
            }
		}
        if( wpfval($item, 'title') ){
            $domain = wpforo_get_request_uri();
            $urls = wp_extract_urls( $item['title'] );
            if( !empty($urls) ){
                foreach( $urls as $k => $url ){
                    $url = parse_url( $url );
                    if( wpfval($url, 'host') ){
                        if( strpos( $domain, $url['host'] ) !== FALSE ) unset($urls[$k]);
                    }
                }
                if( !empty($urls) ){
                    $item['title'] = str_replace($urls, ' -' . wpforo_phrase('removed link', false, false) . '- ', $item['title']);
                }
            }
        }
		return $item;
	}

	public function has_link( $content ){
        $domain = wpforo_get_request_uri();
        $urls = wp_extract_urls( $content );
        if( !empty($urls) ){
            foreach( $urls as $k => $url ){
                $url = parse_url( $url );
                if( wpfval($url, 'host') ){
                    if( strpos( $domain, $url['host'] ) !== FALSE ) unset($urls[$k]);
                }
            }
        }
        if( !empty($urls) ){
            return true;
        }
		return false;
	}
	
}
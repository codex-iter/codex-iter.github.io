<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

function wpforo_actions(){	
	do_action( 'wpforo_actions' );

	add_action( 'delete_attachment', 'wpforo_delete_attachment', 10 );
	if( has_action('wpforo_topic_form_extra_fields_after', array(WPF()->tpl, 'add_default_attach_input')) ){
		add_filter( 'wpforo_add_topic_data_filter', 'wpforo_add_default_attachment' );
		add_filter( 'wpforo_edit_topic_data_filter', 'wpforo_add_default_attachment' );
		add_filter( 'wpforo_add_post_data_filter', 'wpforo_add_default_attachment' );
		add_filter( 'wpforo_edit_post_data_filter', 'wpforo_add_default_attachment' );
		add_filter( 'wpforo_body_text_filter', 'wpforo_default_attachments_filter');
	}

	if( !class_exists('wpForoSmiles') ){
		add_filter('wpforo_body_text_filter', 'wp_encode_emoji', 9);
		add_filter('wpforo_body_text_filter', 'convert_smilies');
	}

	if( isset($_POST['wpfreg']) && !empty($_POST['wpfreg']) && $userid = WPF()->member->create( $_POST )){
		wpforo_verify_form('ref');
        WPF()->member->reset($userid);
        if( WPF()->member->options['redirect_url_after_register'] ){
            $redirect_url = WPF()->member->options['redirect_url_after_register'];
        }else{
            $redirect_url = ( wpforo_feature('user-register-email-confirm') ? wpforo_home_url() : WPF()->member->get_profile_url( $userid, 'account' ) );
        }
		wp_redirect($redirect_url);
		exit();
	}
	
	if(isset($_POST['wpforologin']) && isset($_POST['log']) && isset($_POST['pwd'])){
		wpforo_verify_form('ref');
		if ( !is_wp_error( $user = wp_signon() ) ) {
			$wpf_login_times = intval( get_user_meta($user->ID, '_wpf_login_times', true) );
			if( isset($user->ID) && $wpf_login_times >= 1) {
				$name = ( isset($user->data->display_name) ) ? $user->data->display_name : '';
				WPF()->notice->add( 'Welcome back %s!', 'success', $name);
			}
			else{
				WPF()->notice->add('Welcome to our Community!', 'success');
			}
			$wpf_login_times++;
			update_user_meta( $user->ID, '_wpf_login_times', $wpf_login_times );
			if( WPF()->member->options['redirect_url_after_login'] ){
                $redirect_url = WPF()->member->options['redirect_url_after_login'];
            }else{
                $redirect_url = wpforo_home_url( preg_replace('#\?.*$#is', '', wpforo_get_request_uri()) );
            }
			wp_redirect($redirect_url);
			exit();
		}else{
			$args = array();
			foreach($user->errors as $u_err) $args[] = $u_err[0];
			WPF()->notice->add($args, 'error');
			wp_redirect( wpforo_get_request_uri() );
			exit();
		}
	}

	extract(WPF()->current_object, EXTR_OVERWRITE);

	if( in_array( $template, array('profile', 'account', 'activity', 'subscriptions') ) && !isset($user_nicename) && !isset($userid) ){
		wp_redirect( wpforo_home_url() );
		exit();
	}

    if( wpfval($_GET, 'foro') && $_GET['foro'] == 'allread' ){
        if( wpfval($_GET, 'foro_n') && wp_verify_nonce($_GET['foro_n'], 'wpforo_mark_all_read') ){
	        WPF()->log->mark_all_read();
            $current_url = wpforo_get_request_uri();
            $current_url = strtok( $current_url, '?');
            wp_redirect( $current_url );
            exit();
        }
    }

	if( wpfval($_GET, 'foro_f') && wpfval($_GET, 'foro_u') && wpfval($_GET, 'foro_n') ){
	    if( wp_verify_nonce($_GET['foro_n'], 'wpforo_delete_profile_field') ){
            $userid = intval( $_GET['foro_u'] );
            $field = sanitize_title( $_GET['foro_f'] );
            $wpudir = wp_upload_dir();
            if( $file = WPF()->member->get_custom_field( $userid, $field ) ){
                $file = $wpudir['basedir'] . $file;
                $result = WPF()->member->update_custom_field( $userid, $field, '' );
                if( $result ){
                    @unlink($file);
                    WPF()->phrase->clear_cache();
                    WPF()->notice->add('Deleted Successfully!', 'success');
                } else {
                    WPF()->notice->clear();
                    WPF()->notice->add('Sorry, this file cannot be deleted', 'error');
                }
            }
        }
    }

	if(isset($_POST['wpforo_member_submit'])){
		if( wpfval($_POST, 'member', 'userid') ){
            wpforo_verify_form();
		    $uid = intval($_POST['member']['userid']);
			if( !( intval($_POST['member']['userid']) == WPF()->current_userid ||
				( WPF()->perm->usergroup_can('em') && WPF()->perm->user_can_manage_user( WPF()->current_userid, $uid )) ) ){
				WPF()->notice->clear();
				WPF()->notice->add('Permission denied', 'error');
				wp_redirect(wpforo_get_request_uri());
				exit();
			}
            $user = WPF()->member->update( $_POST );
            $uid = ( wpfval($user, 'user_nicename') ) ? $user['user_nicename'] : $uid;
			if( $profile_url = WPF()->member->get_profile_url( $uid, 'account') ){
			    wp_redirect($profile_url);
				exit();
			}
		}
		wp_redirect(wpforo_get_request_uri());
		exit();
	}

	if( isset($_POST['topic']['save']) && isset($_REQUEST['topic']['action']) ){
		if( $_REQUEST['topic']['action'] == 'add' ){
			wpforo_verify_form();
			if( $topicid = WPF()->topic->add() ){
				wp_redirect( WPF()->topic->get_topic_url($topicid) );
				exit();
			}
		}elseif( $_REQUEST['topic']['action'] == 'edit' ){
			wpforo_verify_form();
			if( $topicid = WPF()->topic->edit() ){
				wp_redirect( WPF()->topic->get_topic_url($topicid) );
				exit();
			}
		}
		wp_redirect( wpforo_get_request_uri() );
		exit();
	}
	
	if( isset($_POST['post']['save']) ){
		if( $_POST['post']['save'] != 'move' && isset($_REQUEST['post']['action']) ){
			if($_REQUEST['post']['action'] == 'add'){
				wpforo_verify_form();
				if( $postid = WPF()->post->add() ){
					wp_redirect( WPF()->post->get_post_url( $postid ) );
					exit();
				}
			}elseif($_REQUEST['post']['action'] == 'edit'){
				wpforo_verify_form();
				if( $postid = WPF()->post->edit() ){
					wp_redirect( WPF()->post->get_post_url( $postid ) );
					exit();
				}
			}
		}
		
		if($_POST['post']['save'] == 'move' && isset($_POST['movetopicid']) && isset($_POST['topic']['forumid'])){
			wpforo_verify_form();
			$move_topicid = intval($_POST['movetopicid']);
			$move_forumid = intval($_POST['topic']['forumid']);
			WPF()->topic->move( $move_topicid, $move_forumid );
			wp_redirect( WPF()->topic->get_topic_url($move_topicid) );
			exit();
		}
		
		wp_redirect( wpforo_get_request_uri() );
		exit();
	}

	## TOPIC MERGE
    if( !empty($_POST['wpforo']['topic_merge']) && !empty(WPF()->current_object['topic']) ){
		wpforo_verify_form();
	    $redirect_to = wpforo_get_request_uri();
        if( !empty($_POST['wpforo']['target_topic_url']) ){
            $target_slug = esc_url( $_POST['wpforo']['target_topic_url']);
            if( preg_match('#^[\r\n\t\s]*https?://[^\r\n\t\s]+?/[^/]+/([^/]+?)(?:/?[\r\n\t\s]*$|/?\#post-\d+/?[\r\n\t\s]*$)#isu', $target_slug, $match) )
                if( is_wpforo_url($target_slug) ) $target_slug = $match[1];
            if ( strpos($target_slug, '/') === false && $target = WPF()->topic->get_topic($target_slug) ){
                $append = (empty($_POST['wpforo']['update_date_and_append']) ? 0 : 1);
                $to_target_title = (empty($_POST['wpforo']['to_target_title']) ? 0 : 1);

                if( WPF()->topic->merge( WPF()->current_object['topic'], $target, array(), $to_target_title, $append ) )
                    $redirect_to = WPF()->topic->get_topic_url($target);
            }else{
                WPF()->notice->add('Target Topic not found', 'error');
            }
        }
        wp_redirect( $redirect_to );
        exit();
    }

    ## TOPIC SPLIT
    if( !empty($_POST['wpforo']['topic_split']) && !empty(WPF()->current_object['topic']) ){
		wpforo_verify_form();
        $redirect_to = wpforo_get_request_uri();
        if( !empty($_POST['wpforo']['create_new']) ){
            $args = array(
                'title'     => sanitize_text_field( $_POST['wpforo']['new_topic_title']),
                'forumid'   => intval( $_POST['wpforo']['new_topic_forumid']),
                'postids'   => array_map( 'intval', $_POST['wpforo']['posts'] )
            );
            $to_target_title = (empty($_POST['wpforo']['to_target_title']) ? 0 : 1);
            if( $topicid = WPF()->topic->split($args, $to_target_title) )
                $redirect_to = WPF()->topic->get_topic_url($topicid);
        }else{
            if( !empty($_POST['wpforo']['target_topic_url']) && !empty($_POST['wpforo']['posts']) ){
                $target_slug = esc_url( $_POST['wpforo']['target_topic_url'] );
                if( preg_match('#^[\r\n\t\s]*https?://[^\r\n\t\s]+?/[^/]+/([^/]+?)(?:/?[\r\n\t\s]*$|/?\#post-\d+/?[\r\n\t\s]*$)#isu', $target_slug, $match) )
                    if( is_wpforo_url($target_slug) ) $target_slug = $match[1];
                if ( strpos($target_slug, '/') === false && $target = WPF()->topic->get_topic($target_slug) ){
                    $append = (empty($_POST['wpforo']['update_date_and_append']) ? 0 : 1);
                    $to_target_title = (empty($_POST['wpforo']['to_target_title']) ? 0 : 1);
                    $posts = array_map( 'intval', $_POST['wpforo']['posts']);
                    if( WPF()->topic->merge( WPF()->current_object['topic'], $target, $posts, $to_target_title, $append ) )
                        $redirect_to = WPF()->topic->get_topic_url($target);
                }else{
                    WPF()->notice->add('Target Topic not found', 'error');
                }
            }
        }

        wp_redirect( $redirect_to );
        exit();
    }
	
	## Subscriptions
	if( isset($_GET['wpforo']) && ($_GET['wpforo'] == 'sbscrbconfirm' || $_GET['wpforo'] == 'unsbscrb') && isset($_GET['key']) && $_GET['key'] ){
		$sbs_key = sanitize_text_field($_GET['key']);
		if( $_GET['wpforo'] == 'sbscrbconfirm' ){
			WPF()->sbscrb->edit($sbs_key);
		}else{
			WPF()->sbscrb->delete($sbs_key);
		}
        $redirect_url = wpforo_home_url( preg_replace('#\?.*$#is', '', wpforo_get_request_uri()) );
        if( WPF()->member->options['redirect_url_after_confirm_sbscrb'] ) $redirect_url = WPF()->member->options['redirect_url_after_confirm_sbscrb'];
		wp_redirect($redirect_url);
		exit();
	}

	if( !empty( $_POST['wpforo_subscribe_manager']) ){

	    $data = ( !empty($_POST['wpforo']['forums']) ? array_map( 'sanitize_title', $_POST['wpforo']['forums'] ) : array() );
        $all = ( !empty($_POST['wpforo']['check_all']) ? sanitize_title($_POST['wpforo']['check_all']) : '' );

        WPF()->sbscrb->reset($data, $all);
        wp_redirect( wpforo_home_url(WPF()->tpl->slugs['subscriptions']) );
        exit();
    }

	## Resolved
	if( isset($_GET['wpforo']) && $_GET['wpforo'] == 'solved' && $_GET['tid'] ){
		$topicid = intval($_GET['tid']);
		wpforo_clean_cache( 'topic-soft', $topicid );
		wp_redirect( wpforo_home_url( preg_replace('#\?.*$#is', '', wpforo_get_request_uri()) ) );
		exit();
	}
	
	## Private
	if( isset($_GET['wpforo']) && $_GET['wpforo'] == 'private' && $_GET['tid'] ){
		$topicid = intval($_GET['tid']);
		wpforo_clean_cache('topic', $topicid);
		wp_redirect( wpforo_home_url( preg_replace('#\?.*$#is', '', wpforo_get_request_uri()) ) );
		exit();
	}
	
	###############################################################
	/**
	* 
	* BACK-END
	* 
	*/

	##check if wpforo page has been deleted, restore or create new wpforo page
	if( wpforo_is_admin() && isset($_GET['page']) && ($_GET['page'] == 'wpforo-community' || $_GET['page'] == 'wpforo-settings' ) ){
        if( !WPF()->pageid || !WPF()->db->get_var("SELECT `ID` FROM `".WPF()->db->posts."` WHERE `ID` = ".intval(WPF()->pageid)." AND `post_content` LIKE '%[wpforo%' AND `post_status` LIKE 'publish' AND `post_type` IN('post', 'page')") )
            wpforo_create_forum_page();
    }
	
	##Settings action
	if( wpforo_is_admin() && isset($_POST['wpforo_screen_option']['value']) ){
		if(!current_user_can('administrator')) return;
		update_option('wpforo_count_per_page', intval($_POST['wpforo_screen_option']['value']) );
	}
	
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-community' && isset($_GET['action']) && $_GET['action'] ){
		if(!current_user_can('administrator')){ 
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}

        // -- START -- set unlimited time limits
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        // -- END -- set unlimited time limits

        if( $_GET['action'] == 'synch' ){
			if( function_exists('set_time_limit') ) set_time_limit( 3600 );
			WPF()->member->synchronize_users();
            WPF()->member->clear_db_cache();
			wp_redirect(admin_url('admin.php?page=wpforo-community'));
			exit();
		}

        if( $_GET['action'] == 'synch_user_profile' && check_admin_referer( 'wpforo_synch_user_profile' ) ){
            if( function_exists('set_time_limit') ) set_time_limit( 3600 );
            WPF()->member->synchronize_users();
            WPF()->member->clear_db_cache();
            WPF()->notice->add('Synched Successfully!', 'success');
        }

		if( $_GET['action'] == 'wpfdb' ){
			if( function_exists('set_time_limit') ) set_time_limit( 3600 ); wpforo_update_db();
			wp_redirect(admin_url('admin.php?page=wpforo-community'));
			exit();
		}
		if( $_GET['action'] == 'reset_fstat' && check_admin_referer( 'wpforo_reset_forums_stat' ) ){
            $forumids = WPF()->db->get_col("SELECT `forumid` FROM ".WPF()->tables->forums." WHERE `is_cat` = 0 ORDER BY `forumid` ASC");
			if(!empty($forumids)){
				foreach($forumids as $forumid){
					WPF()->forum->rebuild_stats($forumid);
				}
                WPF()->db->query("DELETE FROM `" . WPF()->db->options."` WHERE `option_name` LIKE 'wpforo_stat%'" );
				WPF()->forum->delete_tree_cache();
				WPF()->notice->add('Updated Successfully!', 'success');
			}
		}
		if( $_GET['action'] == 'reset_tstat' && check_admin_referer( 'wpforo_reset_topics_stat' ) ){
            $topicids = WPF()->db->get_col("SELECT `topicid` FROM ".WPF()->tables->topics." ORDER BY `topicid` ASC");
			if(!empty($topicids)){
				foreach($topicids as $topicid){
                    $topic = WPF()->topic->get_topic($topicid);
                    WPF()->topic->rebuild_first_last($topic);
                    WPF()->topic->rebuild_stats($topic);
				}
                WPF()->db->query("DELETE FROM `" . WPF()->db->options."` WHERE `option_name` LIKE 'wpforo_stat%'" );
				WPF()->notice->add('Updated Successfully!', 'success');
			}
		}
		if( $_GET['action'] == 'reset_ustat' && check_admin_referer( 'wpforo_reset_users_stat' ) ){
			$users = WPF()->db->get_results("SELECT `userid` FROM ".WPF()->tables->profiles." ORDER BY `posts` DESC", ARRAY_A);
			if(!empty($users)){
				foreach($users as $user){
					$questions = WPF()->member->get_questions_count( $user['userid'] );
					$answers = WPF()->member->get_answers_count( $user['userid'] );
					$posts = WPF()->member->get_replies_count( $user['userid'] );
					$question_comments = WPF()->member->get_question_comments_count( $user['userid'] );
					WPF()->db->query("UPDATE `".WPF()->tables->profiles."` 
											SET `posts` = " . intval($posts) . ", `answers` = " . intval($answers) . ", `comments` = " . intval($question_comments) . ", `questions` = " . intval($questions) . " 
																WHERE `userid` = " . intval( $user['userid'] ) );
				}
				WPF()->notice->add('Updated Successfully!', 'success');
			}
		}
		if( $_GET['action'] == 'reset_phrase_cache' && check_admin_referer( 'wpforo_reset_phrase_cache' ) ){
			WPF()->phrase->clear_cache();
			WPF()->notice->add('Deleted Successfully!', 'success');
		}
		if( $_GET['action'] == 'reset_user_cache' && check_admin_referer( 'wpforo_reset_user_cache' ) ){
			WPF()->member->clear_db_cache();
			WPF()->notice->add('Deleted Successfully!', 'success');
		}
		if( $_GET['action'] == 'reset_cache' && check_admin_referer( 'wpforo_reset_cache' ) ){
			WPF()->phrase->clear_cache();
			WPF()->member->clear_db_cache();
			wpforo_clean_cache();
			$current_time = time();
			$month_ago = $current_time - 2592000;
			WPF()->db->query("DELETE FROM `".WPF()->tables->views."` WHERE `created` < " . intval($month_ago) );
			WPF()->notice->add('Deleted Successfully!', 'success');
		}
        wp_redirect(admin_url('admin.php?page=wpforo-community'));
        exit();
	}
	
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-settings' ){
		
		if(!WPF()->perm->usergroup_can('ms')){
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}
		
		##General options
		if( isset($_POST['wpforo_general_options']) ){
			check_admin_referer( 'wpforo-settings-general' );
			
			if( isset($_POST['wpforo_use_home_url']) && $_POST['wpforo_use_home_url'] ){
				$wpforo_use_home_url = 1;
				if( isset($_POST['wpforo_excld_urls']) && $_POST['wpforo_excld_urls'] )
					update_option('wpforo_excld_urls', sanitize_textarea_field($_POST['wpforo_excld_urls']));
			}else{
				$wpforo_use_home_url = 0;
			}
			update_option('wpforo_use_home_url', $wpforo_use_home_url);

			if( isset($_POST['wpforo_url']) && $permastruct = utf8_uri_encode( $_POST['wpforo_url'] ) ){
				$permastruct = preg_replace('#^/?index\.php/?#isu', '', $permastruct);
				$permastruct = trim($permastruct, '/');
				
				if( update_option('wpforo_url', esc_url( home_url($permastruct) ) )
					&& update_option('wpforo_permastruct', $permastruct) ){
					WPF()->notice->add('Forum Base URL successfully updated', 'success');
				}else{
					WPF()->notice->add('Successfully updated', 'success');
				}

				WPF()->permastruct = $permastruct;
				flush_rewrite_rules(FALSE);
				nocache_headers();
			}

			if( $wpforo_use_home_url == 0 && !isset($_POST['wpforo_url']) ){
				WPF()->permastruct = trim( get_wpf_option('wpforo_permastruct'), '/\\' );
				WPF()->permastruct = preg_replace('#^/?index\.php/?#isu', '', WPF()->permastruct);
				WPF()->permastruct = trim(WPF()->permastruct, '/\\');
				WPF()->pageid = get_wpf_option( 'wpforo_pageid');
				flush_rewrite_rules(FALSE);
				nocache_headers();
			}

			$general_options = array(
                'title' => sanitize_text_field($_POST['wpforo_general_options']['title']),
                'description' => sanitize_text_field($_POST['wpforo_general_options']['description']),
                'menu_position' => intval($_POST['wpforo_general_options']['menu_position']),
                'lang' => intval($_POST['wpforo_general_options']['lang'])
            );

			if( update_option('wpforo_general_options', $general_options) ){
				WPF()->notice->add('General options successfully updated', 'success');
			}else{
				WPF()->notice->add('Successfully updated', 'success');
			}

            if( !empty($_POST['wpforo_tpl_slugs']) ){
                $wpforo_tpl_slugs = array_filter( array_map('sanitize_title', $_POST['wpforo_tpl_slugs']) );
			    $wpforo_tpl_slugs = array_merge(WPF()->tpl->slugs, $wpforo_tpl_slugs);
			    if( $wpforo_tpl_slugs == ( array_unique($wpforo_tpl_slugs) ) ){
                    update_option('wpforo_tpl_slugs', $wpforo_tpl_slugs);
                }else{
			        WPF()->notice->add('Please save "Forum template slugs" uniqueness', 'error');
                }
            }

			WPF()->member->clear_db_cache();
			wpforo_clean_cache();
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=general' ) );
			exit();
		}
		
		##add new lang action 
		if( isset($_FILES['add_lang']) ){
			check_admin_referer( 'wpforo-settings-language' );
			WPF()->phrase->add_lang();
			wpforo_clean_cache();
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=general' ) );
			exit();
		}
		
		##Forums
		if( isset($_POST['wpforo_forum_options']) ){
			check_admin_referer( 'wpforo-settings-forums' );

			if( update_option('wpforo_forum_options', array_map( 'intval', $_POST['wpforo_forum_options']) ) ){
				WPF()->notice->add('Forum options successfully updated', 'success');
			}else{
				WPF()->notice->add('Forum options successfully updated, but previous value not changed', 'success');
			}
			wpforo_clean_cache();
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=forums' ) );
			exit();
		}
		
		##Posts
		if( isset($_POST['wpforo_post_options']) ){
			check_admin_referer( 'wpforo-settings-posts' );
			$_POST['wpforo_post_options']['eot_durr'] = intval($_POST['wpforo_post_options']['eot_durr']) * 60;
			$_POST['wpforo_post_options']['dot_durr'] = intval($_POST['wpforo_post_options']['dot_durr']) * 60;
			$_POST['wpforo_post_options']['eor_durr'] = intval($_POST['wpforo_post_options']['eor_durr']) * 60;
			$_POST['wpforo_post_options']['dor_durr'] = intval($_POST['wpforo_post_options']['dor_durr']) * 60;
			$_POST['wpforo_post_options']['max_upload_size'] = intval(wpforo_human_size_to_bytes($_POST['wpforo_post_options']['max_upload_size'].'M')); 
			if( update_option('wpforo_post_options', $_POST['wpforo_post_options']) ){
				WPF()->notice->add('Post options successfully updated', 'success');
			}else{
				WPF()->notice->add('Post options successfully updated, but previous value not changed', 'success');
			}
			wpforo_clean_cache();
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=posts' ) );
			exit();
		}
		
		##Members
		if( isset($_POST['wpforo_member_options']) ){
			check_admin_referer( 'wpforo-settings-members' );
			$_POST['wpforo_member_options']['online_status_timeout'] = intval($_POST['wpforo_member_options']['online_status_timeout']) * 60;
            $_POST['wpforo_member_options']['url_structure'] = sanitize_title($_POST['wpforo_member_options']['url_structure']);
            $_POST['wpforo_member_options']['search_type'] = sanitize_title($_POST['wpforo_member_options']['search_type']);
            $_POST['wpforo_member_options']['login_url'] = esc_url_raw($_POST['wpforo_member_options']['login_url']);
            $_POST['wpforo_member_options']['register_url'] = esc_url_raw($_POST['wpforo_member_options']['register_url']);
            $_POST['wpforo_member_options']['lost_password_url'] = esc_url_raw($_POST['wpforo_member_options']['lost_password_url']);
            $_POST['wpforo_member_options']['redirect_url_after_login'] = esc_url_raw($_POST['wpforo_member_options']['redirect_url_after_login']);
            $_POST['wpforo_member_options']['redirect_url_after_register'] = esc_url_raw($_POST['wpforo_member_options']['redirect_url_after_register']);
            $_POST['wpforo_member_options']['redirect_url_after_confirm_sbscrb'] = esc_url_raw($_POST['wpforo_member_options']['redirect_url_after_confirm_sbscrb']);
            $_POST['wpforo_member_options']['custom_title_is_on'] = intval($_POST['wpforo_member_options']['custom_title_is_on']);
            $_POST['wpforo_member_options']['default_title'] = sanitize_text_field($_POST['wpforo_member_options']['default_title']);
            $_POST['wpforo_member_options']['rating_title_ug'] = array_map('intval', $_POST['wpforo_member_options']['rating_title_ug']);
            $_POST['wpforo_member_options']['rating_badge_ug'] = array_map('intval', $_POST['wpforo_member_options']['rating_badge_ug']);
            $_POST['wpforo_member_options']['title_usergroup'] = array_map('intval', $_POST['wpforo_member_options']['title_usergroup']);
            foreach($_POST['wpforo_member_options']['rating'] as $key => $rating){
                $_POST['wpforo_member_options']['rating'][$key] = array_map('sanitize_text_field', $rating);
                $_POST['wpforo_member_options']['rating'][$key]['points'] = intval($rating['points']);
            }
			if( update_option('wpforo_member_options', $_POST['wpforo_member_options']) ){
				WPF()->notice->add('Member options successfully updated', 'success');
			}else{
				WPF()->notice->add('Member options successfully updated, but previous value not changed', 'success');
			}
			wpforo_clean_cache();
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=members' ) );
			exit();
		}
		
		##Features
		if( isset($_POST['wpforo_features']) ){
			check_admin_referer( 'wpforo-features' );
			if( update_option('wpforo_features', array_map('intval', $_POST['wpforo_features']) ) ){
				WPF()->notice->add('Features successfully updated', 'success');
			}else{
				WPF()->notice->add('Features successfully updated, but previous value not changed', 'success');
			}
			wpforo_clean_cache();
            WPF()->seo->clear_cache();
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=features' ) );
			exit();
		}
		
		##APIs
		if( isset($_POST['wpforo_api_options']) ){
			check_admin_referer( 'wpforo-settings-api' );
			if( update_option('wpforo_api_options', $_POST['wpforo_api_options']) ){
				WPF()->notice->add('API options successfully updated', 'success');
			}else{
				WPF()->notice->add('API options successfully updated, but previous value not changed', 'success');
			}
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=api' ) );
			exit();
		}
		
		##Theme options
		if( isset($_POST['wpforo_theme_options']) && isset($_POST['wpforo_style_options']) ){
			check_admin_referer( 'wpforo-settings-styles' );
			//Theme Options//////////////////////////////////////////////////////////////////////
            $_POST['wpforo_theme_options']['style'] = sanitize_title($_POST['wpforo_theme_options']['style']);
            foreach($_POST['wpforo_theme_options']['styles'] as $key => $rating){
                $_POST['wpforo_theme_options']['styles'][$key] = array_map('sanitize_text_field', $rating);
            }
            WPF()->tpl->options['style'] = sanitize_text_field($_POST['wpforo_theme_options']['style']);
            WPF()->tpl->options['styles'] = $_POST['wpforo_theme_options']['styles'];
            //Style Options/////////////////////////////////////////////////////////////////////
            $_POST['wpforo_style_options']['font_size_forum'] = intval($_POST['wpforo_style_options']['font_size_forum']);
            $_POST['wpforo_style_options']['font_size_topic'] = intval($_POST['wpforo_style_options']['font_size_topic']);
            $_POST['wpforo_style_options']['font_size_post_content'] = intval($_POST['wpforo_style_options']['font_size_post_content']);
            $_POST['wpforo_style_options']['custom_css'] = sanitize_textarea_field($_POST['wpforo_style_options']['custom_css']);
            ////////////////////////////////////////////////////////////////////////////////////
			update_option('wpforo_style_options', $_POST['wpforo_style_options']);
			update_option('wpforo_theme_options', WPF()->tpl->options);
			WPF()->notice->add('Theme options successfully updated', 'success');
			wpforo_clean_cache();
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=styles' ) );
			exit();
		}
		
		##Subscription
		if( isset($_POST['wpforo_subscribe_options']) ){
			check_admin_referer( 'wpforo-settings-emails' );
            $_POST['wpforo_subscribe_options']['from_name'] = sanitize_text_field($_POST['wpforo_subscribe_options']['from_name']);
            $_POST['wpforo_subscribe_options']['from_email'] = sanitize_text_field($_POST['wpforo_subscribe_options']['from_email']);
            $_POST['wpforo_subscribe_options']['admin_emails'] = sanitize_text_field($_POST['wpforo_subscribe_options']['admin_emails']);
            $_POST['wpforo_subscribe_options']['new_topic_notify'] = intval($_POST['wpforo_subscribe_options']['new_topic_notify']);
            $_POST['wpforo_subscribe_options']['new_reply_notify'] = intval($_POST['wpforo_subscribe_options']['new_reply_notify']);
            $_POST['wpforo_subscribe_options']['confirmation_email_subject'] = sanitize_text_field($_POST['wpforo_subscribe_options']['confirmation_email_subject']);
            $_POST['wpforo_subscribe_options']['confirmation_email_message'] = wpforo_kses($_POST['wpforo_subscribe_options']['confirmation_email_message'], 'email');
            $_POST['wpforo_subscribe_options']['new_topic_notification_email_subject'] = sanitize_text_field($_POST['wpforo_subscribe_options']['new_topic_notification_email_subject']);
            $_POST['wpforo_subscribe_options']['new_topic_notification_email_message'] = wpforo_kses($_POST['wpforo_subscribe_options']['new_topic_notification_email_message'], 'email');
            $_POST['wpforo_subscribe_options']['new_post_notification_email_subject'] = sanitize_text_field($_POST['wpforo_subscribe_options']['new_post_notification_email_subject']);
            $_POST['wpforo_subscribe_options']['new_post_notification_email_message'] = wpforo_kses($_POST['wpforo_subscribe_options']['new_post_notification_email_message'], 'email');
            $_POST['wpforo_subscribe_options']['report_email_subject'] = sanitize_text_field($_POST['wpforo_subscribe_options']['report_email_subject']);
            $_POST['wpforo_subscribe_options']['report_email_message'] = wpforo_kses($_POST['wpforo_subscribe_options']['report_email_message'], 'email');
            $_POST['wpforo_subscribe_options']['reset_password_email_message'] = wpforo_kses($_POST['wpforo_subscribe_options']['reset_password_email_message'], 'email');
            $_POST['wpforo_subscribe_options']['user_mention_notify'] = intval($_POST['wpforo_subscribe_options']['user_mention_notify']);
            $_POST['wpforo_subscribe_options']['user_mention_email_subject'] = sanitize_text_field($_POST['wpforo_subscribe_options']['user_mention_email_subject']);
            $_POST['wpforo_subscribe_options']['user_mention_email_message'] = wpforo_kses($_POST['wpforo_subscribe_options']['user_mention_email_message'], 'email');
			if( update_option('wpforo_subscribe_options', $_POST['wpforo_subscribe_options']) ){
				WPF()->notice->add('Subscribe options successfully updated', 'success');
			}else{
				WPF()->notice->add('Subscribe options successfully updated, but previous value not changed', 'success');
			}
			wpforo_clean_cache();
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=emails' ) );
			exit();
		}
	}
	
	### forum action ###
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-forums' ){
		
		if(!WPF()->forum->manage() ){
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}
		
		if( isset($_POST['wpforo_submit']) && isset($_REQUEST['forum']) && isset($_GET['action']) ){
			check_admin_referer( 'wpforo-forum-addedit' );
			if( $_GET['action'] == 'add' ){
				if( $forumid = WPF()->forum->add() ){
					wp_redirect( admin_url( 'admin.php?page=wpforo-forums' ) );
					exit();
				}
			}elseif( $_GET['action'] == 'edit' && isset($_GET['id']) ){
				$forumid = WPF()->forum->edit();
			}
			if( isset($forumid) && $forumid ){
				wp_redirect( admin_url( 'admin.php?page=wpforo-forums&id=' . intval($forumid) . '&action=edit' ) );
			}else{
				wp_redirect( wpforo_get_request_uri() );
			}
			exit();
		}
		
		if(isset($_POST['wpforo_delete']) && $_GET['action'] == 'del' && isset($_REQUEST['forum']['delete'])){
			check_admin_referer( 'wpforo-forum-delete' );
			if( intval($_REQUEST['forum']['delete']) == 1 ){
				WPF()->forum->delete();
			}elseif( intval($_REQUEST['forum']['delete']) == 0 ){
				WPF()->forum->merge();
			}
			wp_redirect( admin_url( 'admin.php?page=wpforo-forums' ) );
			exit();
		}
		
		if(isset($_POST['forums_hierarchy_submit'])){
			check_admin_referer( 'wpforo-forums-hierarchy' );
			WPF()->forum->update_hierarchy();
			wpforo_clean_cache('forum');
			wp_redirect( admin_url( 'admin.php?page=wpforo-forums' ) );
			exit();
		}
	}
	
	##Moderation
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-moderations' ){
		
		if(!WPF()->perm->usergroup_can('aum')){
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}

        $u_action = '';
        if( !empty($_GET['action']) && $_GET['action'] != '-1' ){
            $u_action = sanitize_textarea_field($_GET['action']);
        }elseif( !empty($_GET['action2']) && $_GET['action2'] != '-1' ){
            $u_action = sanitize_textarea_field($_GET['action2']);
        }
        $bulk = FALSE;
        $pids = array();
        if( !empty($_GET['id']) && ($pid = wpforo_bigintval($_GET['id'])) ){
            $pids = (array) $pid;
        }elseif( !empty($_GET['ids']) && ($ids = trim($_GET['ids'])) ){
            $bulk = TRUE;
            $ids = explode(',', urldecode($ids));
            $pids = array_map('wpforo_bigintval', array_filter($ids));
        }

        if( $u_action && !empty($pids) ) {
            if ($u_action == 'del') {
                if( $bulk ){
                    !check_admin_referer( 'bulk_action_moderation' );
                }else{
                    !check_admin_referer( 'wpforo_admin_table_action_delete' );
                }
                foreach ($pids as $pid) WPF()->post->delete($pid);
                wp_redirect(admin_url('admin.php?page=wpforo-moderations'));
                exit();
            } elseif ($u_action == 'approve') {
                if( $bulk ){
                    !check_admin_referer( 'bulk_action_moderation' );
                }else{
                    !check_admin_referer( 'wpforo_admin_table_action_approve' );
                }
                foreach ($pids as $pid) {
					if( $pid ){
						WPF()->moderation->post_approve($pid);
						//Email Notification ////////////////////////////////////////////////////////////
						$post = WPF()->post->get_post($pid);
						wpforo_clean_cache('post', $pid, $post);
						if( !empty($post) && isset($post['is_first_post']) && $post['is_first_post'] ){
                            wpforo_send_mail_to_mentioned_users( $post );
                            if( isset($post['topicid']) && $post['topicid'] ){
                                $topic = WPF()->topic->get_topic($post['topicid']);
                                if( !empty($topic) ){
									wpforo_forum_subscribers_mail_sender( $topic );
								}
							}
						}
						/////////////////////////////////////////////////////////////////////////////////
					}
				}
				wp_redirect(admin_url('admin.php?page=wpforo-moderations'));
                exit();
            } elseif ($u_action == 'unapprove') {
                if( $bulk ){
                    !check_admin_referer( 'bulk_action_moderation' );
                }else{
                    !check_admin_referer( 'wpforo_admin_table_action_approve' );
                }
                foreach ($pids as $pid) {
					WPF()->moderation->post_unapprove($pid);
					wpforo_clean_cache('post', $pid);
                }
				wp_redirect(admin_url('admin.php?page=wpforo-moderations'));
                exit();
            }
        }
    }

	##Phrases
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-phrases' ){

		if(!WPF()->perm->usergroup_can('mp')){
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}

		if(isset($_POST['phrase']['save'])){
			check_admin_referer( 'wpforo-phrases-edit' );
			WPF()->phrase->edit();
			wp_redirect( admin_url( 'admin.php?page=wpforo-phrases' ) );
			exit();
		}

		if( isset($_POST['phrase']['add']) && !empty($_POST['phrase']['value']) ){
			check_admin_referer( 'wpforo-phrase-add' );
			WPF()->phrase->add();
			wp_redirect( admin_url( 'admin.php?page=wpforo-phrases' ) );
			exit();
		}
	}
	
	
	##Members
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-members' ){
		$u_action = '';
		if( !empty($_GET['action']) && $_GET['action'] != '-1' ){
			$u_action = sanitize_textarea_field($_GET['action']);
		}elseif( !empty($_GET['action2']) && $_GET['action2'] != '-1' ){
			$u_action = sanitize_textarea_field($_GET['action2']);
		}
		$bulk = FALSE;
		$uids = array();
		if( !empty($_GET['id']) && ($uid = intval($_GET['id'])) ){
			$uids = (array) $uid;
		}elseif( !empty($_GET['ids']) && ($ids = trim($_GET['ids'])) ){
			$bulk = TRUE;
			$ids = explode(',', urldecode($ids));
			$uids = array_map('intval', array_filter($ids));
		}
		$uids = array_diff($uids, (array) WPF()->current_userid);
		
		if( $u_action && !empty($uids) ){
			
			if($u_action == 'del'){
				$url = self_admin_url( 'users.php?action=delete&users[]=' . implode( '&users[]=', $uids ) );
				$url = str_replace( '&amp;', '&', wp_nonce_url( $url, 'bulk-users' ) );
				wp_redirect( $url );
				exit();
			}elseif($u_action == 'ban'){
				if( $bulk ){
					!check_admin_referer( 'bulk_action_member' );
				}else{
					!check_admin_referer( 'wpforo_admin_table_action_ban' );
				}
				foreach($uids as $uid) WPF()->member->ban($uid);
			}elseif($u_action == 'unban'){
				if( $bulk ){
					!check_admin_referer( 'bulk_action_member' );
				}else{
					!check_admin_referer( 'wpforo_admin_table_action_ban' );
				}
				foreach($uids as $uid) WPF()->member->unban($uid);
			}
			wpforo_clean_cache('user');
			wp_redirect( admin_url( 'admin.php?page=wpforo-members' ) );
			exit();
		}
	}
	
	
	##Usergroups
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-usergroups' ){
		
		if(!WPF()->perm->usergroup_can('vmg')){
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}
		
		if(isset( $_POST['usergroup']['action'] ) && ( $_POST['usergroup']['action'] == 'add' || $_POST['usergroup']['action'] == 'edit' ) ){
            $insert_usergroup_id = 0;
            $insert_usergroup_role = false;
		    check_admin_referer( 'wpforo-usergroup-addedit' );
			$board_cans = ( isset($_POST['cans']) ? $_POST['cans'] : array() );
			if( $_POST['usergroup']['action'] == 'add' ){
				$insert_usergroup_name = sanitize_text_field($_POST['usergroup']['name']);
				$insert_usergroup_role = sanitize_text_field($_POST['usergroup']['role']);
				$insert_usergroup_access = sanitize_text_field($_POST['usergroup']['access']);
                $insert_usergroup_color = ( isset($_POST['wpfugc']) && $_POST['wpfugc'] ) ? '' : sanitize_text_field($_POST['usergroup']['color']);
				$insert_usergroup_visible = intval($_POST['usergroup']['visible']);
                $insert_usergroup_secondary = intval($_POST['usergroup']['secondary']);
				$insert_usergroup_id = WPF()->usergroup->add( $insert_usergroup_name, $board_cans, '', $insert_usergroup_role, $insert_usergroup_access, $insert_usergroup_color, $insert_usergroup_visible, $insert_usergroup_secondary );
				if( $$insert_usergroup_id ) wpforo_clean_cache( 'loop', $insert_usergroup_id );
			}elseif( $_POST['usergroup']['action'] == 'edit' ){
				$insert_usergroup_id = intval($_GET['gid']);
				$insert_usergroup_name = sanitize_text_field($_POST['usergroup']['name']);
				$insert_usergroup_role = sanitize_text_field($_POST['usergroup']['role']);
				$insert_usergroup_color = ( isset($_POST['wpfugc']) && $_POST['wpfugc'] ) ? '' : sanitize_text_field($_POST['usergroup']['color']);
				$insert_usergroup_visible = intval($_POST['usergroup']['visible']);
                $insert_usergroup_secondary = intval($_POST['usergroup']['secondary']);
				WPF()->usergroup->edit( $insert_usergroup_id, $insert_usergroup_name, $board_cans, '', $insert_usergroup_role, NULL, $insert_usergroup_color, $insert_usergroup_visible, $insert_usergroup_secondary );
				if( $insert_usergroup_id ) wpforo_clean_cache( 'loop', $insert_usergroup_id );
			}
            if( wpforo_feature('role-synch') && $insert_usergroup_id && $insert_usergroup_role ){
			    $args = array( 'role' => $insert_usergroup_role );
			    $users = get_users( $args );
			    $users_count = count( $users );
                $users_count_limit = apply_filters('wpforo_synch_roles_users_limit', 5000);
                $ug_role = array( $insert_usergroup_id => $insert_usergroup_role);
                if( !empty($users) ){
                    if( $users_count <= $users_count_limit ){
                        $status = wpforo_synch_role( $ug_role, $users );
                        wpforo_clean_cache('user');
                        if( wpfval($status, 'error') && $status['error'] ){
                            WPF()->notice->add($status['error'], 'error');
                        }
                    }
                    else{
                        WPF()->notice->add('Please make sure you don\'t have not-synched Roles in the "User Roles" table below, then click on the [Synchronize] button to update users Usergroup IDs.' , 'error');
                    }
                }
            }
            wp_redirect( admin_url( 'admin.php?page=wpforo-usergroups' ) );
            exit();
		}
		if(isset($_GET['action']) && $_GET['action']=='del' && isset($_POST['usergroup']['submit'])){
			check_admin_referer( 'wpforo-usergroup-delete' );
			WPF()->usergroup->delete();
			wpforo_clean_cache('user');
			wp_redirect( admin_url( 'admin.php?page=wpforo-usergroups' ) );
			exit();
		}
		if( isset($_GET['default']) ){
		    $wpforo_default_groupid = intval($_GET['default']);
		    update_option('wpforo_default_groupid', $wpforo_default_groupid);
            wp_redirect( admin_url( 'admin.php?page=wpforo-usergroups' ) );
            exit();
        }
	}
	
	##### Admin Accesses action ######
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-settings' && isset($_GET['tab']) && $_GET['tab'] == 'accesses' ){
		
		if(!WPF()->perm->usergroup_can('ms')){
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}
		
		if( isset( $_POST['access'] ) && $_POST['access']['action'] == 'add' ){
			check_admin_referer( 'wpforo-access-addedit' );
			$cans = ( isset($_POST['cans'] ) ? $_POST['cans'] : array() );
			$insert_access_name = sanitize_text_field($_POST['access']['name']);
			WPF()->perm->add( $insert_access_name, $cans );
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=accesses' ) );
			exit();
		}elseif( isset( $_POST['access'] ) && $_POST['access']['action'] == 'edit' ){
			check_admin_referer( 'wpforo-access-addedit' );
			$cans = ( isset($_POST['cans'] ) ? $_POST['cans'] : array() );
			$insert_access_key = sanitize_text_field($_POST['access']['key']);
			$insert_access_name = sanitize_text_field($_POST['access']['name']);
			WPF()->perm->edit( $insert_access_name, $cans, $insert_access_key );
			wpforo_clean_cache('loop');
			wp_redirect( wpforo_get_request_uri() );
			exit();
		}elseif( isset($_GET['action']) && $_GET['action'] == 'del' && isset($_GET['accessid']) ){
			
			if( !check_admin_referer( 'wpforo_access_delete' )){ 
				WPF()->notice->add('Permission denied', 'error');
				wp_redirect(admin_url());
				exit();
			}
			
			$insert_access_id = intval($_GET['accessid']);
			WPF()->perm->delete( $insert_access_id );
			wpforo_clean_cache('loop');
			wp_redirect( admin_url( 'admin.php?page=wpforo-settings&tab=accesses' ) );
			exit();
		}
	}
	
	##Themes
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-themes' && isset($_GET['theme']) ){
		
		if(!WPF()->perm->usergroup_can('mth')){
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}
		
		$theme = sanitize_text_field( $_GET['theme'] );
		if( $_GET['action'] == 'activate' || $_GET['action'] == 'install' || $_GET['action'] == 'reset' ){
			if( $_GET['action'] == 'activate' ){
				$new_theme = get_option( 'wpforo_theme_archive_' . $theme );
			}
			elseif( $_GET['action'] == 'install' || $_GET['action'] == 'reset' ){
				$new_theme = WPF()->tpl->find_theme( $theme );
				if( $_GET['action'] == 'reset' ){
					delete_option( 'wpforo_theme_archive_' . $theme );
				}
			}
			$current_theme = WPF()->tpl->options;
			if( !empty($new_theme) ){
				update_option( 'wpforo_theme_options', $new_theme );
				if( $_GET['action'] != 'reset' ){
					update_option( 'wpforo_theme_archive_' . WPF()->tpl->theme, $current_theme );
				}
			}
			wp_redirect( admin_url( 'admin.php?page=wpforo-themes' ) );
			exit();
		}
		if( $_GET['action'] == 'delete' ){
			$remove_dir = WPFORO_THEME_DIR . '/' . $theme;
			if( is_dir($remove_dir) && strlen($theme) > 0 ){
				wpforo_remove_directory( $remove_dir );
			}
			wp_redirect( admin_url( 'admin.php?page=wpforo-themes' ) );
			exit();
		}
	}
	
	
	if( isset($_GET['forum']) && $_GET['forum'] && isset($_GET['type']) && $_GET['type'] == 'rss2' ){
		
		$forum_rss_items = 10;
		$topic_rss_items = 10;
		
		if( $_GET['forum'] == 'g' ){
			$forum = array();
            $forum['forumurl'] = wpforo_home_url();
            $forum['title'] = '';
		}
		else{
			$forumid = intval($_GET['forum']);
			$forum = wpforo_forum($forumid);
			$forum['forumurl'] = $forum['url'];
		}
		
		if(isset($_GET['topic']) && $_GET['topic']){
			if( $_GET['topic'] == 'g' ){
				$posts = WPF()->post->get_posts( array( 'row_count' => $topic_rss_items, 'orderby' => '`created` DESC, `postid` DESC', 'check_private' => true ) );
				$topic['title'] = '';
				$topic['topicurl'] = wpforo_home_url();
			}
			else{
				$topicid = intval($_GET['topic']);
				$topic = wpforo_topic($topicid); //WPF()->topic->get_topic($topicid);
				$topic['topicurl'] = ( $topic['url'] ) ? $topic['url'] : WPF()->topic->get_topic_url($topicid);
				$posts = WPF()->post->get_posts( array( 'topicid' => $topicid, 'row_count' => $topic_rss_items, 'orderby' => '`created` DESC, `postid` DESC', 'check_private' => true ) );
			}
			foreach($posts as $key => $post){
				$member = wpforo_member( $post );
				$posts[$key]['description'] = wpforo_text( trim(strip_tags($post['body'])), 190, false );
				$posts[$key]['content'] = trim($post['body']);
				$posts[$key]['posturl'] = WPF()->post->get_post_url( $post['postid'] );
				$posts[$key]['author'] = $member['display_name'];
			}
			WPF()->feed->rss2_topic($forum, $topic, $posts);
		}
		else{
			if( $_GET['forum'] == 'g' ){
				$topics = WPF()->topic->get_topics( array( 'row_count' => $forum_rss_items, 'orderby' => 'created', 'order' => 'DESC' ) );
			}
			else{
				$topics = WPF()->topic->get_topics( array( 'forumid' => $forumid, 'row_count' => $forum_rss_items, 'orderby' => 'created', 'order' => 'DESC' ) );
			}
			foreach($topics as $key => $topic){
				$post = wpforo_post($topic['first_postid']);
				$member = wpforo_member($topic);
				$topics[$key]['description'] = wpforo_text( trim(strip_tags($post['body'])), 190, false );
				$topics[$key]['content'] = trim($post['body']);
				$topics[$key]['topicurl'] = WPF()->topic->get_topic_url($topic['topicid']);
				$topics[$key]['author'] = $member['display_name'];
			}
			WPF()->feed->rss2_forum($forum, $topics);
		}
		exit();
	}
	
	##Tools
	if( wpforo_is_admin() && isset($_GET['page']) && $_GET['page'] == 'wpforo-tools' ){
		
		if(!WPF()->perm->usergroup_can('mt')){
			WPF()->notice->add('Permission denied', 'error');
			wp_redirect(admin_url());
			exit();
		}
		
		if( isset($_POST['wpforo_tools_antispam']) ){
			check_admin_referer( 'wpforo-tools-antispam' );
            $_POST['wpforo_tools_antispam']['spam_filter'] = intval($_POST['wpforo_tools_antispam']['spam_filter']);
            $_POST['wpforo_tools_antispam']['spam_user_ban'] = intval($_POST['wpforo_tools_antispam']['spam_user_ban']);
            $_POST['wpforo_tools_antispam']['spam_user_ban_notification'] = intval($_POST['wpforo_tools_antispam']['spam_user_ban_notification']);
            $_POST['wpforo_tools_antispam']['spam_filter_level_topic'] = intval($_POST['wpforo_tools_antispam']['spam_filter_level_topic']);
            $_POST['wpforo_tools_antispam']['spam_filter_level_post'] = intval($_POST['wpforo_tools_antispam']['spam_filter_level_post']);
            $_POST['wpforo_tools_antispam']['new_user_max_posts'] = intval($_POST['wpforo_tools_antispam']['new_user_max_posts']);
            $_POST['wpforo_tools_antispam']['min_number_post_to_attach'] = intval($_POST['wpforo_tools_antispam']['min_number_post_to_attach']);
            $_POST['wpforo_tools_antispam']['min_number_post_to_link'] = intval($_POST['wpforo_tools_antispam']['min_number_post_to_link']);
            $_POST['wpforo_tools_antispam']['limited_file_ext'] = sanitize_textarea_field($_POST['wpforo_tools_antispam']['limited_file_ext']);
            $_POST['wpforo_tools_antispam']['rc_site_key'] = sanitize_text_field($_POST['wpforo_tools_antispam']['rc_site_key']);
            $_POST['wpforo_tools_antispam']['rc_secret_key'] = sanitize_text_field($_POST['wpforo_tools_antispam']['rc_secret_key']);
            $_POST['wpforo_tools_antispam']['rc_theme'] = sanitize_text_field($_POST['wpforo_tools_antispam']['rc_theme']);
            $_POST['wpforo_tools_antispam']['rc_topic_editor'] = intval($_POST['wpforo_tools_antispam']['rc_topic_editor']);
            $_POST['wpforo_tools_antispam']['rc_post_editor'] = intval($_POST['wpforo_tools_antispam']['rc_post_editor']);
            $_POST['wpforo_tools_antispam']['rc_wpf_login_form'] = intval($_POST['wpforo_tools_antispam']['rc_wpf_login_form']);
            $_POST['wpforo_tools_antispam']['rc_wpf_reg_form'] = intval($_POST['wpforo_tools_antispam']['rc_wpf_reg_form']);
            $_POST['wpforo_tools_antispam']['rc_wpf_lostpass_form'] = intval($_POST['wpforo_tools_antispam']['rc_wpf_lostpass_form']);
            $_POST['wpforo_tools_antispam']['rc_login_form'] = intval($_POST['wpforo_tools_antispam']['rc_login_form']);
            $_POST['wpforo_tools_antispam']['rc_reg_form'] = intval($_POST['wpforo_tools_antispam']['rc_reg_form']);
            $_POST['wpforo_tools_antispam']['rc_lostpass_form'] = intval($_POST['wpforo_tools_antispam']['rc_lostpass_form']);
            $_POST['wpforo_tools_antispam']['html'] = sanitize_textarea_field($_POST['wpforo_tools_antispam']['html']);
            $_POST['wpforo_tools_antispam']['spam_file_scanner'] = intval($_POST['wpforo_tools_antispam']['spam_file_scanner']);
            $_POST['wpforo_tools_antispam']['exclude_file_ext'] = sanitize_textarea_field($_POST['wpforo_tools_antispam']['exclude_file_ext']);
            if( update_option('wpforo_tools_antispam', $_POST['wpforo_tools_antispam']) ){
				WPF()->notice->add('Settings successfully updated', 'success');
			}
			wp_redirect( admin_url( 'admin.php?page=wpforo-tools&tab=antispam' ) );
			exit();
		}
		
		if( isset($_POST['wpforo_tools_cleanup']) ){
			//check_admin_referer( 'wpforo-tools-cleanup' );
			//if( update_option('wpforo_tools_cleanup', $_POST['wpforo_tools_cleanup']) ){
				//WPF()->notice->add('Settings successfully updated', 'success');
			//}
			//wp_redirect( admin_url( 'admin.php?page=wpforo-tools&tab=cleanup' ) );
			//exit();
		}
		
		if( isset($_POST['wpforo_tools_misc']) ){
			check_admin_referer( 'wpforo-tools-misc' );
            $_POST['wpforo_tools_misc']['dofollow'] = sanitize_textarea_field($_POST['wpforo_tools_misc']['dofollow']);
            $_POST['wpforo_tools_misc']['noindex'] = sanitize_textarea_field($_POST['wpforo_tools_misc']['noindex']);
            $_POST['wpforo_tools_misc']['admin_note'] = wpforo_kses($_POST['wpforo_tools_misc']['admin_note']);
            $_POST['wpforo_tools_misc']['admin_note_groups'] = ( wpfval($_POST, 'wpforo_tools_misc', 'admin_note_groups' ) ) ? array_map( 'intval', $_POST['wpforo_tools_misc']['admin_note_groups'] ) : array();
            $_POST['wpforo_tools_misc']['admin_note_pages'] = ( wpfval($_POST, 'wpforo_tools_misc', 'admin_note_pages' ) ) ? array_map( 'sanitize_textarea_field', $_POST['wpforo_tools_misc']['admin_note_pages'] ) : array();
            if( update_option('wpforo_tools_misc', $_POST['wpforo_tools_misc']) ){
                wpforo_clean_cache('forum-soft');
				WPF()->notice->add('Settings successfully updated', 'success');
			}
			wp_redirect( admin_url( 'admin.php?page=wpforo-tools&tab=misc' ) );
			exit();
		}

        if( isset($_POST['wpforo_tools_legal']) ){
            check_admin_referer( 'wpforo-tools-legal' );
            $_POST['wpforo_tools_legal']['contact_page_url'] = esc_url($_POST['wpforo_tools_legal']['contact_page_url']);
            $_POST['wpforo_tools_legal']['checkbox_terms_privacy'] = intval($_POST['wpforo_tools_legal']['checkbox_terms_privacy']);
            $_POST['wpforo_tools_legal']['checkbox_email_password'] = intval($_POST['wpforo_tools_legal']['checkbox_email_password']);
            $_POST['wpforo_tools_legal']['page_terms'] = esc_url($_POST['wpforo_tools_legal']['page_terms']);
            $_POST['wpforo_tools_legal']['page_privacy'] = esc_url($_POST['wpforo_tools_legal']['page_privacy']);
            $_POST['wpforo_tools_legal']['checkbox_forum_privacy'] = intval($_POST['wpforo_tools_legal']['checkbox_forum_privacy']);
            $_POST['wpforo_tools_legal']['forum_privacy_text'] = wpforo_kses($_POST['wpforo_tools_legal']['forum_privacy_text'], 'post');
            $_POST['wpforo_tools_legal']['checkbox_fb_login'] = intval($_POST['wpforo_tools_legal']['checkbox_fb_login']);
            $_POST['wpforo_tools_legal']['cookies'] = intval($_POST['wpforo_tools_legal']['cookies']);
            $_POST['wpforo_tools_legal']['rules_checkbox'] = intval($_POST['wpforo_tools_legal']['rules_checkbox']);
            $_POST['wpforo_tools_legal']['rules_text'] = wpforo_kses($_POST['wpforo_tools_legal']['rules_text'], 'post');
            if( update_option('wpforo_tools_legal', $_POST['wpforo_tools_legal']) ){
                WPF()->notice->add('Settings successfully updated', 'success');
            }
            wp_redirect( admin_url( 'admin.php?page=wpforo-tools&tab=legal' ) );
            exit();
        }
		
		if(isset($_GET['action']) && $_GET['action']=='delete-spam-file' && isset($_GET['sfname']) && $_GET['sfname']){
			$filename = sanitize_file_name($_GET['sfname']);
			if(check_admin_referer( 'wpforo_tools_antispam_files')){
				if(!empty($filename)){
					$filename = str_replace( array('../', './', '/'), '', $filename );
					$filename = urldecode( $filename );
					$upload_dir = wp_upload_dir();
                	$default_attachments_dir =  $upload_dir['basedir'] . '/wpforo/default_attachments/';
					$file = $default_attachments_dir . $filename;
					$attachmentid = WPF()->post->get_attachment_id( '/' . $filename );
					if ( !wp_delete_attachment( $attachmentid ) ){
						@unlink($file); 
					}
					WPF()->notice->add( 'Deleted', 'success' );
					wp_redirect( admin_url( 'admin.php?page=wpforo-tools&tab=antispam' ) );
					exit();
				}
			}
			wp_redirect( admin_url( 'admin.php?page=wpforo-tools&tab=antispam' ) );
			exit();
		}
		
		if(isset($_GET['action']) && $_GET['action']=='delete-all' && isset($_GET['level']) && $_GET['level']){
			if(check_admin_referer( 'wpforo_tools_antispam_files')){
				$delete_level = intval($_GET['level']);
				$upload_dir = wp_upload_dir();
                $default_attachments_dir =  $upload_dir['basedir'] . '/wpforo/default_attachments/';
				if(is_dir($default_attachments_dir)){
					if ($handle = opendir($default_attachments_dir)){
						while (false !== ($filename = readdir($handle))){
							$level = 0;
							if( $filename == '.' ||  $filename == '..') continue;
							if( !$level = WPF()->moderation->spam_file($filename) ) continue;
							if( $delete_level == $level ){
								$attachmentid = WPF()->post->get_attachment_id( '/' . $filename );
								if ( !wp_delete_attachment( $attachmentid ) ){
									$file = $default_attachments_dir . $filename; @unlink($file); 
								}
							}
						}
						closedir($handle);
						WPF()->notice->add( 'Deleted', 'success' );
						wp_redirect( admin_url( 'admin.php?page=wpforo-tools&tab=antispam' ) );
						exit();
					}
				}
			}
			wp_redirect( admin_url( 'admin.php?page=wpforo-tools&tab=antispam' ) );
			exit();
		}
	}
	
	do_action( 'wpforo_actions_end' );
	
}
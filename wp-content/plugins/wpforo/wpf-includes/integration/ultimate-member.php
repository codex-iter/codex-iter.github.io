<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if( class_exists('UM') ){

    class wpForo_UM {

        private static $instance;

        static public function instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        function __construct() {
            add_action('init', array( $this, 'init' ), 100 );
            add_action('wp_enqueue_scripts', array($this, 'um_frontend_enqueue'), 100 );
        }

        function init() {
            $this->um_init();
            if( wpforo_feature('um_forum_tab') ){
                add_filter('um_profile_tabs', array($this, 'um_tabs'), 999 );
                add_filter('um_user_profile_tabs', array($this, 'um_user_tabs'), 999 );
                add_action('um_profile_content_foro_default', array($this, 'um_default_tab_content') );
                add_action('um_profile_content_foro_topics', array($this, 'um_user_topics') );
                add_action('um_profile_content_foro_replies', array($this, 'um_user_replies') );
                add_action('um_profile_content_foro_favorites', array($this, 'um_user_favorites') );
                add_action('um_profile_content_foro_subscriptions', array($this, 'um_user_subscriptions') );
            }
            if( wpforo_feature('um_profile') ){
                add_filter('wpforo_member_profile_url', array($this, 'um_profile_url'), 10, 3 );
            }
            if( wpforo_feature('um_notification') && class_exists( 'UM_Notifications_API') )  {
                add_filter('um_notifications_core_log_types', array($this, 'um_notification_types'), 500, 1 );
                add_filter('um_notifications_get_icon', array($this, 'um_notification_icon'), 10, 2 );
                add_action('wpforo_after_add_post', array($this, 'um_add_notification'), 10, 2 );
                add_action('wpforo_post_status_update', array($this, 'um_notification_on_post_status_change'), 10, 2 );
            }
        }

        function um_init(){

            $options = get_option( 'um_options' );

            if( wpfkey($options, 'profile_tab_foro') ||
                wpfkey($options, 'log_wpforo_user_reply')
            ){
                if( !empty( $options ) ){
                    //Set default options for Tab Settings
                    $tab_options = array( 'profile_tab_foro' => 1, 'profile_tab_foro_privacy' => 0 );
                    foreach( $tab_options as $key => $value ) {
                        if(!isset($options[$key])) $options[$key] = $value;
                    }
                    //Set default options for Notification Settings
                    $notification_options = array();
                    $notification_options['wpforo_user_reply'] = array(
                        'title' => __('User leaves a reply to wpForo topic', 'wpforo'),
                        'template' => '<strong>{member}</strong> has <strong>replied</strong> to a topic you started on the forum.',
                        'account_desc' => __('When a member replies to one of my forum topics', 'wpforo')
                    );
                    $notification_options['wpforo_user_reply_to_reply'] = array(
                        'title' => __('User replied to wpForo post', 'wpforo'),
                        'template' => '<strong>{member}</strong> has <strong>replied</strong> to your post on the forum.',
                        'account_desc' => __('When a member replies to one of my post in forum topics', 'wpforo')
                    );
                    foreach( $notification_options as $type => $note ) {
                        if(!isset($options['log_' . $type])) $options['log_' . $type] = 1;
                        if(!isset( $options['log_' . $type . '_template'])) $options['log_' . $type . '_template'] = $note['template'];
                    }
                    update_option( 'um_options', $options );
                }
            }
        }

        function um_tabs( $tabs ) {
            $user_id = um_user('ID');
            if( $user_id ){
                $member = wpforo_member( $user_id );
                $topics = 0; $posts = 0; $likes = 0; $subscriptions = 0;
                if( wpfval( $member, 'stat' ) ){
                    if( wpfval($member, 'stat', 'topics') ) $topics = $member['stat']['topics'];
                    if( wpfval($member, 'stat', 'posts') ) $posts = $member['stat']['posts'];
                    if( wpfval($member, 'stat', 'likes') ) $likes = $member['stat']['likes'];
                }
                $args = array('userid' => $user_id);
                $subs = WPF()->sbscrb->get_subscribes( $args);
                if( !empty($subs) ) $subscriptions = count($subs);
                $tabs['foro'] = array(
                    'name' => wpforo_phrase('Forums', false),
                    'icon' => 'um-faicon-comments',
                    'subnav' => array(
                        'topics' => wpforo_phrase('Topics Started', false) . '<span>' . intval($topics) . '</span>',
                        'replies' => wpforo_phrase('Replies Created', false) . '<span>' . intval($posts) . '</span>',
                        'favorites' => wpforo_phrase('Liked Posts', false) . '<span>' . intval($likes) . '</span>',
                        'subscriptions' => wpforo_phrase('Subscriptions', false) . '<span>' . intval($subscriptions) . '</span>',
                    ),
                    'subnav_default' => 'topics'
                );
            }
            return $tabs;
        }

        function um_user_tabs( $tabs ) {
            if( wpfval($tabs, 'foro','subnav_default') &&
                wpfval($tabs, 'foro', 'subnav', $tabs['foro']['subnav_default'])
            ) {
                $i = 0;
                if ( isset( $tabs['foro']['subnav'] ) ) {
                    foreach( $tabs['foro']['subnav'] as $id => $data ) {
                        $i++;
                        if ( $i == 1 ) {
                            $tabs['foro']['subnav_default'] = $id;
                        }
                    }
                }
            }
            return $tabs;
        }

        function um_default_tab_content( $args ) {
            $this->um_user_topics( $args );
        }

        function um_user_topics( $args ) {
            $user_id = um_user('ID');
            if(isset($_GET['wpfpaged']) && intval($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
            $paged = (isset($paged) && $paged) ? $paged : 1;
            $args = array(
                'offset' => ($paged - 1) * WPF()->post->options['posts_per_page'],
                'row_count' => WPF()->post->options['posts_per_page'],
                'userid' => $user_id,
                'orderby' => 'modified',
                'check_private' => true
            );
            $activities = WPF()->topic->get_topics( $args, $items_count);
            ?>
            <div id="wpforo-topics" class="wpforo-activity">
                <h3 class="wpf-activity-head"><?php wpforo_phrase('Forum Topics Started'); ?></h3>
                <?php if(empty($activities)) : ?>
                    <p class="wpf-p-error"> <?php wpforo_phrase('No activity found for this member.') ?> </p>
                <?php else: ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php $bg = FALSE; foreach( $activities as $activity ) : ?>
                            <tr>
                                <td class="wpf-activity-title">
                                    <span class="dashicons dashicons-admin-comments"></span>
                                    <?php
                                    $topic = wpforo_topic($activity['topicid']);
                                    if( !empty($topic)){ $topic_url = $topic['url']; $topic_title = $topic['title']; if(!$topic_url) $topic_url = '#'; if(!$topic_title) $topic_title = wpforo_phrase('Topic link');
                                        ?><a href="<?php echo esc_url($topic_url) ?>" class="wpf-item-title"><?php echo $topic_title ?></a><?php
                                    }
                                    if( wpfval($topic, 'forumid') ){
                                        $forum = wpforo_forum($topic['forumid']); $forum_url = $forum['url']; $forum_title = $forum['title']; if(!$forum_url) $forum_url = '#'; if(!$forum_title) $forum_url = wpforo_phrase('Forum link');
                                        ?><p style="font-style: italic"><span><?php echo wpforo_phrase('in forum', false) ?></span> <a href="<?php echo esc_url($forum_url) ?>"><?php echo $forum_title ?></a></p><?php
                                    }
                                    ?>
                                </td>
                                <td class="wpf-activity-users">
                                    <?php $members = WPF()->topic->members($topic['topicid'], 3); ?>
                                    <?php if(!empty($members)): foreach( $members as $member ): ?>
                                        <?php if(!empty($member)): ?>
                                            <a href="<?php echo um_user_profile_url($member['ID']) ?>" title="<?php echo esc_attr(um_get_display_name($member['ID'])); ?>"><?php echo WPF()->member->avatar($member, 'alt="'.esc_attr($member['display_name']).'"', 30) ?></a>
                                        <?php endif; ?>
                                    <?php endforeach; endif; ?>
                                </td>
                                <td class="wpf-activity-posts">
                                    <?php echo $activity['posts']; ?> <?php wpforo_phrase('posts'); ?>
                                </td>
                                <td class="wpf-activity-date"><?php wpforo_date($topic['created']); ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                    <div class="wpf-activity-foot"><?php WPF()->tpl->pagenavi( $paged, $items_count, false ); ?></div>
                    <div style="clear: both"></div>
                <?php endif; ?>
            </div>
            <?php

        }

        function um_user_replies( $args ) {
            $user_id = um_user('ID');
            if(isset($_GET['wpfpaged']) && intval($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
            $paged = (isset($paged) && $paged) ? $paged : 1;
            $args = array(
                'offset' => ($paged - 1) * WPF()->post->options['posts_per_page'],
                'row_count' => WPF()->post->options['posts_per_page'],
                'userid' => $user_id,
                'orderby' => '`created` DESC',
                'check_private' => true
            );
            $activities = WPF()->post->get_posts( $args, $items_count);
            ?>
            <div id="wpforo-posts" class="wpforo-activity">
                <h3 class="wpf-activity-head"><?php wpforo_phrase('Forum Replies Created'); ?></h3>
                <?php if(empty($activities)) : ?>
                    <p class="wpf-p-error"> <?php wpforo_phrase('No activity found for this member.') ?> </p>
                <?php else: ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php $bg = FALSE; foreach( $activities as $activity ) : ?>
                            <tr>
                                <td class="wpf-activity-title">
                                    <span class="dashicons dashicons-format-chat"></span>
                                    <?php
                                    $post = wpforo_post($activity['postid']);
                                    if( !empty($post)){ $post_url = $post['url']; $post_title = $post['title']; if(!$post_url) $post_url = '#'; if(!$post_title) $post_title = wpforo_phrase('Post link');
                                        ?><a href="<?php echo esc_url($post_url) ?>" class="wpf-item-title"><?php echo $post_title ?></a><?php
                                    }
                                    ?>
                                    <?php if(wpfval($post, 'body')): ?>
                                        <p class="wpf-post-excerpt" style="font-style: italic">
                                            <?php
                                            $body = wpforo_content_filter( $post['body'] );
                                            $body = preg_replace('#\[attach\][^\[\]]*\[\/attach\]#is', '', strip_shortcodes(strip_tags($body)));
                                            wpforo_text($body, 200);
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td class="wpf-activity-forum">
                                    <?php
                                    if( wpfval($post, 'forumid') ){
                                        $forum = wpforo_forum($post['forumid']); $forum_url = $forum['url']; $forum_title = $forum['title']; if(!$forum_url) $forum_url = '#'; if(!$forum_title) $forum_url = wpforo_phrase('Forum link');
                                        ?><p style="font-style: italic"><span><?php echo wpforo_phrase('in forum', false) ?></span> <a href="<?php echo esc_url($forum_url) ?>"><?php echo $forum_title ?></a></p><?php
                                    }
                                    ?>
                                </td>
                                <td class="wpf-activity-date"><?php wpforo_date($post['created']); ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                    <div class="wpf-activity-foot"><?php WPF()->tpl->pagenavi( $paged, $items_count, false ); ?></div>
                    <div style="clear: both"></div>
                <?php endif; ?>
            </div>
            <?php
        }

        function um_user_favorites( $args ) {
            $user_id = um_user('ID');
            if(isset($_GET['wpfpaged']) && intval($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
            $paged = (isset($paged) && $paged) ? $paged : 1;
            $args = array(
                'userid'        => $user_id,
                'offset'        => ($paged - 1) * WPF()->post->options['posts_per_page'],
                'row_count'     => WPF()->post->options['posts_per_page'],
                'var'           => 'postid'
            );
            $activities = WPF()->post->get_liked_posts( $args, $items_count);
            ?>
            <div id="wpforo-liked-posts" class="wpforo-activity">
                <h3 class="wpf-activity-head"><?php wpforo_phrase('Liked Forum Posts'); ?></h3>
                <?php if(empty($activities)) : ?>
                    <p class="wpf-p-error"> <?php wpforo_phrase('No activity found for this member.') ?> </p>
                <?php else: ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php $bg = FALSE; foreach( $activities as $postid ) : ?>
                            <tr>
                                <td class="wpf-activity-title">
                                    <span class="dashicons dashicons-thumbs-up"></span>
                                    <?php
                                    $post = wpforo_post($postid);
                                    if( !empty($post)){ $post_url = $post['url']; $post_title = $post['title']; if(!$post_url) $post_url = '#'; if(!$post_title) $post_title = wpforo_phrase('Post link');
                                        ?><a href="<?php echo esc_url($post_url) ?>" class="wpf-item-title"><?php echo $post_title ?></a><?php
                                    }
                                    ?>
                                    <?php if(wpfval($post, 'body')): ?>
                                        <p class="wpf-post-excerpt" style="font-style: italic">
                                            <?php
                                            $body = wpforo_content_filter( $post['body'] );
                                            $body = preg_replace('#\[attach\][^\[\]]*\[\/attach\]#is', '', strip_shortcodes(strip_tags($body)));
                                            wpforo_text($body, 200);
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td class="wpf-activity-forum">
                                    <?php
                                    if( wpfval($post, 'forumid') ){
                                        $forum = wpforo_forum($post['forumid']); $forum_url = $forum['url']; $forum_title = $forum['title']; if(!$forum_url) $forum_url = '#'; if(!$forum_title) $forum_url = wpforo_phrase('Forum link');
                                        ?><p style="font-style: italic"><span><?php echo wpforo_phrase('in forum', false) ?></span> <a href="<?php echo esc_url($forum_url) ?>"><?php echo $forum_title ?></a></p><?php
                                    }
                                    ?>
                                </td>
                                <td class="wpf-activity-date"><?php wpforo_date($post['created']); ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                    <div class="wpf-activity-foot"><?php WPF()->tpl->pagenavi( $paged, $items_count, false ); ?></div>
                    <div style="clear: both"></div>
                <?php endif; ?>
            </div>
            <?php
        }

        function um_user_subscriptions( $args ) {
            $user_id = um_user('ID');
            if(isset($_GET['wpfpaged']) && intval($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
            $paged = (isset($paged) && $paged) ? $paged : 1;
            $args = array(
                'offset' => ($paged - 1) * WPF()->post->options['posts_per_page'],
                'row_count' => WPF()->post->options['posts_per_page'],
                'userid' => $user_id,
                'order' => 'DESC'
            );
            $activities = WPF()->sbscrb->get_subscribes( $args, $items_count);
            ?>
            <div id="wpforo-subscriptions" class="wpforo-activity">
                <h3 class="wpf-activity-head"><?php wpforo_phrase('Forum Subscriptions'); ?></h3>
                <?php if(empty($activities)) : ?>
                    <p class="wpf-p-error"> <?php wpforo_phrase('No activity found for this member.') ?> </p>
                <?php else: ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php $bg = FALSE; foreach( $activities as $activity ) : ?>
                            <tr>
                                <td class="wpf-activity-title">
                                    <span class="dashicons <?php echo ($activity['type'] == 'forum') ? 'dashicons-category' : 'dashicons-admin-comments' ; ?>"></span>
                                    <?php
                                    if( in_array($activity['type'], array('forum', 'forum-topic')) ){
                                        $item = wpforo_forum($activity['itemid']);
                                        $item_url = $item['url'];
                                    }elseif($activity['type'] == 'topic'){
                                        $item = wpforo_topic($activity['itemid']);
                                        $item_url = $item['url'];
                                    }elseif ( in_array($activity['type'], array('forums', 'forums-topics')) ){
                                        $item = array('title' => wpforo_phrase('All ' . $activity['type'], false));
                                        $item_url = '#';
                                    }
                                    if(empty($item)) continue;
                                    ?>
                                    <a href="<?php echo esc_url($item_url) ?>" class="wpf-item-title"><?php echo esc_html($item['title']) ?></a>
                                </td>
                                <td class="wpf-activity-unsb"><a href="<?php echo esc_url(WPF()->sbscrb->get_unsubscribe_link($activity['confirmkey'])) ?>"><?php wpforo_phrase('Unsubscribe'); ?></a></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                    <div class="wpf-activity-foot"><?php WPF()->tpl->pagenavi( $paged, $items_count, false ); ?></div>
                    <div style="clear: both"></div>
                <?php endif; ?>
            </div>
            <?php
        }

        function um_profile_url( $url = '', $member = array(), $template = 'profile' ){
            if( !wpforo_is_admin() && wpfval($member, 'ID') ){
                $user_domain = $this->um_profile_permalink( $member['ID'] );
                if( isset($user_domain) ){
                    $user_domain = ( $user_domain ) ? $user_domain : get_author_posts_url( $member['ID'] );
                    $user_domain = strtok( $user_domain, '?');
                    if( $user_domain ){
                        if( $template == 'account' ){
                            $url = $user_domain . '?profiletab=main&um_action=edit';
                        }
                        elseif( $template == 'activity' ){
                            $url = $user_domain . '?profiletab=foro';
                        }
                        elseif( $template == 'subscriptions' ){
                            $url = $user_domain . '?profiletab=foro&subnav=subscriptions';
                        }
                        elseif( $template == 'profile' ) {
                            $url = $user_domain;
                        }
                    }
                }
            }
            if( isset($user_domain) ){
                return apply_filters('wpforo_um_member_profile_url', $url, $member, $template);
            } else{
                return $url;
            }
        }

        function um_profile_permalink( $user_id ){
            if( !$user_id ) return false;
            $permalink_base = UM()->options()->get( 'permalink_base' );
            $slug = get_user_meta( $user_id, "um_user_profile_url_slug_{$permalink_base}", true );
            if ( empty( $slug ) ) {
                if ( $permalink_base != 'user_login' ) {
                    $slug = get_user_meta( $user_id, "um_user_profile_url_slug_user_login", true );
                }
                if ( empty( $slug ) ) {
                    return false;
                }
            }
            $um = get_option('um_options');
            $page_id = ( wpfval($um, 'core_user') ) ? $um['core_user'] : '';
            $profile_url = get_permalink( $page_id );
            $profile_url = apply_filters( 'um_localize_permalink_filter', $profile_url, $page_id );
            if ( get_option('permalink_structure') ) {
                $profile_url = trailingslashit( untrailingslashit( $profile_url ) );
                $profile_url = $profile_url . strtolower( $slug ). '/';
            } else {
                $profile_url =  add_query_arg( 'um_user', strtolower( $slug ), $profile_url );
            }
            return ! empty( $profile_url ) ? $profile_url : '';
        }

        function um_notification_types( $array ){
            $array['wpforo_user_reply'] = array(
                'title' => __('User leaves a reply to wpForo topic', 'wpforo'),
                'template' => __('<strong>{member}</strong> has <strong>replied</strong> to a topic you started on the forum.', 'wpforo'),
                'account_desc' => __('When a member replies to one of my forum topics', 'wpforo')
            );
            $array['wpforo_user_reply_to_reply'] = array(
                'title' => __('User replied to wpForo post', 'wpforo'),
                'template' => __('<strong>{member}</strong> has <strong>replied</strong> to your post on the forum.', 'wpforo'),
                'account_desc' => __('When a member replies to one of my post in forum topics', 'wpforo')
            );
            return $array;
        }

        function um_notification_icon( $output, $type ) {
            if ( $type == 'wpforo_user_reply' ) {
                $output = '<i class="um-faicon-comments" style="color: #43A6DF"></i>';
            }
            if ( $type == 'wpforo_user_reply_to_reply' ) {
                $output = '<i class="um-faicon-comment" style="color: #43A6DF"></i>';
            }
            return $output;
        }

        function um_add_notification( $post = array(), $topic = array() ) {

            //Get reply data
            if( !wpfval($post,'postid') ) return;
            if( !wpfval($topic,'topicid') ) return;

            //Don't notify if a new reply is unapproved
            if( wpfval($post,'status') )  return false;
            if( wpfval($post, 'is_first_post'))  return false;

            //Get author information
            $author_id = $post['userid'];
            $topic_author_id = $topic['userid'];
            $reply_to_item_author_id = 0;
            um_fetch_user( $author_id );

            // Hierarchical replies
            if ( wpfval($post, 'parentid') ) {
                $reply_to_item_author_id = wpforo_post( $post['parentid'], 'userid' );
            }

            // Notify the topic author if not the current reply author
            if ( $author_id != $topic_author_id ) {
                $vars['photo'] = um_get_avatar_url( get_avatar( $author_id, 40 ) );
                $vars['member'] = um_user('display_name');
                $vars['notification_uri'] = esc_url_raw( $post['posturl'] );
                UM()->Notifications_API()->api()->store_notification( $topic_author_id, 'wpforo_user_reply', $vars );
            }
            // Notify the immediate reply author if not the current reply author

            if( $reply_to_item_author_id &&
                wpfval($post, 'parentid') &&
                $author_id != $reply_to_item_author_id &&
                $topic_author_id != $reply_to_item_author_id )
            {
                $vars['photo'] = um_get_avatar_url( get_avatar( $author_id, 40 ) );
                $vars['member'] = um_user('display_name');
                $vars['notification_uri'] = esc_url_raw( $post['posturl'] );
                UM()->Notifications_API()->api()->store_notification( $reply_to_item_author_id, 'wpforo_user_reply_to_reply', $vars );
            }

        }

        function um_notification_on_post_status_change( $reply_id, $status = 0 ) {
            if( !$reply_id || !wpforo_feature('bp_notification') ) return;
            $post = WPF()->post->get_post($reply_id);
            $post['status'] = $status;
            $post['posturl'] = WPF()->post->get_post_url( $post['postid'] );
            if( wpfval($post,'topicid') ) {
                $topic = WPF()->topic->get_topic($post['topicid']);
            } else {
                return false;
            }
            if( !$status ){
                $this->um_add_notification( $post, $topic );
            }
        }

        function um_frontend_enqueue(){
            if (is_rtl()) {
                wp_register_style('wpforo-um-rtl', WPFORO_TEMPLATE_URL . '/integration/ultimate-member/style-rtl.css', false, WPFORO_VERSION );
                wp_enqueue_style('wpforo-um-rtl');
            }
            else{
                wp_register_style('wpforo-um', WPFORO_TEMPLATE_URL . '/integration/ultimate-member/style.css', false, WPFORO_VERSION );
                wp_enqueue_style('wpforo-um');
            }
            if(!is_wpforo_page()){
                wp_enqueue_style( 'dashicons' );
            }
        }

    }

    $WPFUM = new wpForo_UM();

}
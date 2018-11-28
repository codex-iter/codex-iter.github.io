<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'WPF_Forums_Component' ) ){

    class WPF_Forums_Component extends BP_Component {

        public function __construct() {
            parent::start( 'community', __( 'Forums', 'wpforo' ), '' );
            $this->includes();
            $this->setup_globals();
            $this->setup_actions();
        }

        public function includes( $includes = array() ) {
            $includes[] = 'functions.php';
            $includes[] = 'members.php';
            if ( bp_is_active( 'notifications' ) ) $includes[] = 'notifications.php';
            if ( bp_is_active( 'activity' ) ) $includes[] = 'activity.php';
            parent::includes( $includes );
        }

        public function setup_globals( $args = array() ) {
            $bp = buddypress();
            $wpfurl = get_wpf_option('wpforo_url');
            $wpfpath = trim(basename($wpfurl),'/');
            $args = array(
                'path'          => WPFORO_DIR,
                'slug'          => $wpfpath,
                'root_slug'     => isset( $bp->pages->forums->slug ) ? $bp->pages->forums->slug : $wpfpath,
                'has_directory' => false,
                'search_string' => __( 'Search Forums...', 'wpforo' ),
            );
            parent::setup_globals( $args );
        }

        public function setup_actions() {
            add_action( 'bp_init', array( $this, 'setup_components' ), 10 );
            parent::setup_actions();
        }

        public function setup_components() {
            // Create new activity class
            //if ( bp_is_active( 'activity' ) ) {
                //WPF()->add->buddypress->activity = new WPF_BuddyPress_Activity;
            //}
        }

        public function setup_nav( $main_nav = array(), $sub_nav = array() ) {
            if ( !is_user_logged_in() && !bp_displayed_user_id() ) return;
            $user_domain = '';

            // Add 'Forums' to the main navigation
            $main_nav = array(
                'name'                => __( 'Forums', 'wpforo' ),
                'slug'                => $this->slug,
                'position'            => 81,
                'screen_function'     => 'wpforo_bp_forums_screen_topics',
                'default_subnav_slug' => 'topics',
                'item_css_id'         => $this->id
            );

            // Determine user to use
            if ( bp_displayed_user_id() )
                $user_domain = bp_displayed_user_domain();
            elseif ( bp_loggedin_user_domain() )
                $user_domain = bp_loggedin_user_domain();
            else
                return;

            // User link
            $forums_link = trailingslashit( $user_domain . $this->slug );

            // Topics started
            $sub_nav[] = array(
                'name'            => __( 'Topics Started', 'wpforo' ),
                'slug'            => 'topics',
                'parent_url'      => $forums_link,
                'parent_slug'     => $this->slug,
                'screen_function' => 'wpforo_bp_forums_screen_topics',
                'position'        => 21,
                'item_css_id'     => 'wpf-topics'
            );

            // Replies to topics
            $sub_nav[] = array(
                'name'            => __( 'Replies Created', 'wpforo' ),
                'slug'            => 'replies',
                'parent_url'      => $forums_link,
                'parent_slug'     => $this->slug,
                'screen_function' => 'wpforo_bp_forums_screen_replies',
                'position'        => 41,
                'item_css_id'     => 'wpf-replies'
            );

            // Liked Posts
            $sub_nav[] = array(
                'name'            => __( 'Liked Posts', 'wpforo' ),
                'slug'            => 'likes',
                'parent_url'      => $forums_link,
                'parent_slug'     => $this->slug,
                'screen_function' => 'wpforo_bp_forums_screen_likes',
                'position'        => 61,
                'item_css_id'     => 'wpf-likes'
            );

            // Subscribed topics (my profile only)
            if ( bp_is_my_profile() ) {
                $sub_nav[] = array(
                    'name'            => __( 'Subscriptions', 'wpforo' ),
                    'slug'            => 'subscriptions',
                    'parent_url'      => $forums_link,
                    'parent_slug'     => $this->slug,
                    'screen_function' => 'wpforo_bp_forums_screen_subscriptions',
                    'position'        => 61,
                    'item_css_id'     => 'wpf-subscriptions'
                );
            }

            parent::setup_nav( $main_nav, $sub_nav );
        }

        public function setup_title() {
            $bp = buddypress();
            if ( bp_is_forums_component() ) {
                if ( bp_is_my_profile() ) {
                    $bp->bp_options_title = __( 'Forums', 'wpforo' );
                } elseif ( bp_is_user() ) {
                    $bp->bp_options_avatar = bp_core_fetch_avatar( array('item_id' => bp_displayed_user_id(), 'type' => 'thumb' ) );
                    $bp->bp_options_title = bp_get_displayed_user_fullname();
                }
            }
            parent::setup_title();
        }
    }

    function wpforo_bp_frontend_enqueue(){
        if (is_rtl()) {
            wp_register_style('wpforo-bp-rtl', WPFORO_TEMPLATE_URL . '/integration/buddypress/style-rtl.css', false, WPFORO_VERSION );
            wp_enqueue_style('wpforo-bp-rtl');
        }
        else{
            wp_register_style('wpforo-bp', WPFORO_TEMPLATE_URL . '/integration/buddypress/style.css', false, WPFORO_VERSION );
            wp_enqueue_style('wpforo-bp');
        }
        if(!is_wpforo_page()){
            wp_enqueue_style( 'dashicons' );
        }
    }
    add_action('wp_enqueue_scripts', 'wpforo_bp_frontend_enqueue');
}

/**
 * Insert BuddyPress Activity
 * @param array $args
 * @return bool|int|WP_Error
 */
function wpforo_bp_activity( $args = array() ){
    if( !function_exists('bp_activity_add') || !is_user_logged_in() ) return false;
    $default = array(   'action'            => '',
        'title'           	=> '',
        'content'           => '',
        'component'         => 'community',
        'type'              => false,
        'primary_link'      => '',
        'user_id'           => '',
        'item_id'           => false,
        'hide_sitewide'     => false,
        'is_spam'           => false);

    $args = wpforo_parse_args( $args, $default );
    if( function_exists('bp_activity_add') ){
        if( function_exists('bp_loggedin_user_domain')){
            $user_url = bp_loggedin_user_domain($args['user_id']);
            if(function_exists('bp_core_get_user_displayname')){
                $user_name = bp_core_get_user_displayname($args['user_id']);
                if( $user_url && $user_name ){
                    $user_link = '<a href="' . esc_url($user_url) . '">'. esc_html($user_name) .'</a>';
                    $content_link = ( $args['primary_link'] && $args['title']) ? '<a href="' . esc_url($args['primary_link']) . '">'. esc_html($args['title']) .'</a> - ' : $args['title'] . ' - ';
                    if( $args['type'] == 'wpforo_topic' ){
                        $args['action'] = sprintf( wpforo_phrase('%s posted a new topic %s', false), $user_link, $content_link);
                    }
                    elseif( $args['type'] == 'wpforo_post' ){
                        $args['action'] = sprintf( wpforo_phrase('%s replied to the topic %s', false), $user_link, $content_link);
                    }
                    elseif( $args['type'] == 'wpforo_like' ){
                        $args['action'] = sprintf( wpforo_phrase('%s liked forum post %s', false), $user_link, $content_link);
                    }
                }
            }
        }
        return $activity_id = bp_activity_add( $args );
    }
}

/**
 * Delete BuddyPress Activity
 * @param array $args
 * @return bool
 */
function wpforo_bp_activity_delete( $args = array() ){
    if( !function_exists('bp_activity_delete') || !is_user_logged_in() ) return false;
    $default = array(   'action'            => '',
        'title'           	=> '',
        'content'           => '',
        'component'         => 'community',
        'type'              => false,
        'primary_link'      => '',
        'user_id'           => '',
        'item_id'           => false,
        'hide_sitewide'     => false,
        'is_spam'           => false);

    $args = wpforo_parse_args( $args, $default );
    if( function_exists('bp_activity_delete') ){
        bp_activity_delete( $args );
    }
}

/**
 * Disable comment button for wpForo activity
 * @param bool $can_comment
 * @return bool
 */
function wpforo_bp_activity_disable_comment( $can_comment = true ){
    if ( false === $can_comment ) return $can_comment;
    if( function_exists('bp_get_activity_action_name') ){
        $action_name = bp_get_activity_type();
        $disabled_actions = array( 'wpforo_topic', 'wpforo_post', 'wpforo_like' );
        $disabled_actions = apply_filters( 'wpforo_bp_activity_disable_comment', $disabled_actions );
        if ( in_array( $action_name, $disabled_actions ) ) {
            $can_comment = false;
        }
    }
    return $can_comment;
}

/**
 * Register BuddyPress Activities
 */
function wpforo_bp_register_activity_actions() {
    bp_activity_set_action( 'community', 'wpforo_topic', wpforo_phrase( 'Forum topic', false ), '', wpforo_phrase( 'Forum topic', false ), array( 'member' ));
    bp_activity_set_action( 'community', 'wpforo_post', wpforo_phrase( 'Forum post', false ), '', wpforo_phrase( 'Forum post', false ), array( 'member' ));
    bp_activity_set_action( 'community', 'wpforo_like', wpforo_phrase( 'Forum post like', false ), '', wpforo_phrase( 'Forum post like', false ), array( 'member' ));
}
add_action( 'bp_register_activity_actions', 'wpforo_bp_register_activity_actions' );
add_filter( 'bp_activity_can_comment', 'wpforo_bp_activity_disable_comment');

function wpforo_bp_forums_screen_topics(){
    add_action( 'bp_template_content', 'wpforo_bp_member_forums_topics_content' );
    bp_core_load_template( apply_filters( 'wpforo_bp_forums_screen_topics', 'members/single/plugins' ) );
}

function wpforo_bp_member_forums_topics_content() {
    if(isset($_GET['wpfpaged']) && intval($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
    $paged = (isset($paged) && $paged) ? $paged : 1;
    $args = array(
        'offset' => ($paged - 1) * WPF()->post->options['posts_per_page'],
        'row_count' => WPF()->post->options['posts_per_page'],
        'userid' => bp_displayed_user_id(),
        'orderby' => 'modified',
        'check_private' => true
    );
    $activities = WPF()->topic->get_topics( $args, $items_count);
    ?>
    <div id="wpforo-topics" class="wpforo-activity">
        <h2 class="entry-title"><?php wpforo_phrase('Forum Topics Started'); ?></h2>
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
                                    <a href="<?php echo bp_core_get_user_domain($member['ID']) ?>" title="<?php echo esc_attr(bp_core_get_user_displayname($member['ID'])); ?>"><?php echo WPF()->member->avatar($member, 'alt="'.esc_attr($member['display_name']).'"', 30) ?></a>
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

function wpforo_bp_forums_screen_replies(){
    add_action( 'bp_template_content', 'wpforo_bp_member_forums_replies_content' );
    bp_core_load_template( apply_filters( 'wpforo_bp_forums_screen_replies', 'members/single/plugins' ) );
}

function wpforo_bp_member_forums_replies_content() {
    if(isset($_GET['wpfpaged']) && intval($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
    $paged = (isset($paged) && $paged) ? $paged : 1;
    $args = array(
        'offset' => ($paged - 1) * WPF()->post->options['posts_per_page'],
        'row_count' => WPF()->post->options['posts_per_page'],
        'userid' => bp_displayed_user_id(),
        'orderby' => '`created` DESC',
        'check_private' => true
    );
    $activities = WPF()->post->get_posts( $args, $items_count);
    ?>
    <div id="wpforo-posts" class="wpforo-activity">
        <h2 class="entry-title"><?php wpforo_phrase('Forum Replies Created'); ?></h2>
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

function wpforo_bp_forums_screen_likes(){
    add_action( 'bp_template_content', 'wpforo_bp_member_forums_liked_content' );
    bp_core_load_template( apply_filters( 'wpforo_bp_forums_screen_likes', 'members/single/plugins' ) );
}

function wpforo_bp_member_forums_liked_content() {
    if(isset($_GET['wpfpaged']) && intval($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
    $paged = (isset($paged) && $paged) ? $paged : 1;
    $args = array(
        'userid'        => bp_displayed_user_id(),
        'offset'        => ($paged - 1) * WPF()->post->options['posts_per_page'],
        'row_count'     => WPF()->post->options['posts_per_page'],
        'var'           => 'postid'
    );
    $activities = WPF()->post->get_liked_posts( $args, $items_count);
    ?>
    <div id="wpforo-liked-posts" class="wpforo-activity">
        <h2 class="entry-title"><?php wpforo_phrase('Liked Forum Posts'); ?></h2>
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

function wpforo_bp_forums_screen_subscriptions(){
    add_action( 'bp_template_content', 'wpforo_bp_member_forums_subscriptions_content' );
    bp_core_load_template( apply_filters( 'wpforo_bp_forums_screen_subscriptions', 'members/single/plugins' ) );
}

function wpforo_bp_member_forums_subscriptions_content() {
    if(isset($_GET['wpfpaged']) && intval($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
    $paged = (isset($paged) && $paged) ? $paged : 1;
    $args = array(
        'offset' => ($paged - 1) * WPF()->post->options['posts_per_page'],
        'row_count' => WPF()->post->options['posts_per_page'],
        'userid' => bp_displayed_user_id(),
        'order' => 'DESC'
    );
    $activities = WPF()->sbscrb->get_subscribes( $args, $items_count);
    ?>
    <div id="wpforo-subscriptions" class="wpforo-activity">
        <h2 class="entry-title"><?php wpforo_phrase('Forum Replies Created'); ?></h2>
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

/**
 * Filter registered notifications components, and add 'community' to the queried 'component_name' array.
 *
 * @since wpForo (1.4.8)
 *
 * @param array $component_names
 * @return array
 */
function wpforo_bp_filter_notifications_get_registered_components( $component_names = array() ) {
    if ( ! is_array( $component_names ) ) $component_names = array();
    array_push( $component_names, 'community' );
    return $component_names;
}
add_filter( 'bp_notifications_get_registered_components', 'wpforo_bp_filter_notifications_get_registered_components', 11 );

/**
 * Format the BuddyBar/Toolbar notifications
 *
 * @since wpForo (1.4.8)
 *
 * @param string $action The kind of notification being rendered
 * @param int $item_id The primary item id
 * @param int $secondary_item_id The secondary item id
 * @param int $total_items The total number of messaging-related notifications waiting for the user
 * @param string $format 'string' for BuddyBar-compatible notifications; 'array' for WP Toolbar
 */
function wpforo_bp_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
    // New reply notifications

    if ( 'wpforo_new_reply' === $action ) {

        $post = wpforo_post( $item_id );
        if(!wpfval($post, 'postid')) return false;
        $topic = wpforo_topic( $post['topicid'] );
        if(!wpfval($topic, 'topicid')) return false;

        $reply_id =  $post['postid'];
        $reply_url = $post['url'];
        $topic_title = $topic['title'];
        $reply_link  = wp_nonce_url( add_query_arg( array( 'action' => 'wpforo_mark_read', 'itemid' => $reply_id ), $reply_url ), 'wpforo_mark_topic_' . $reply_id );
        $title_attr  = __( 'Topic reply', 'wpforo' );

        if ( (int) $total_items > 1 ) {
            $text   = sprintf( __( 'You have %d new replies', 'wpforo' ), (int) $total_items );
            $filter = 'wpforo_bp_multiple_new_subscription_notification';
        } else {
            if ( !empty( $secondary_item_id ) ) {
                $text = sprintf( __( 'You have %d new reply to %2$s from %3$s', 'wpforo' ), (int) $total_items, $topic_title, bp_core_get_user_displayname( $secondary_item_id ) );
            } else {
                $text = sprintf( __( 'You have %d new reply to %s', 'wpforo' ), (int) $total_items, $topic_title );
            }
            $filter = 'wpforo_bp_single_new_subscription_notification';
        }
        // WordPress Toolbar
        if ( 'string' === $format ) {
            $return = apply_filters( $filter, '<a href="' . esc_url( $reply_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $reply_link );
        } else {
            $return = apply_filters( $filter, array('text' => $text, 'link' => $reply_link ), $reply_link, (int) $total_items, $text, $topic_title );
        }
        do_action( 'wpforo_bp_format_buddypress_notifications', $action, $item_id, $secondary_item_id, $total_items );
        return $return;
    }
}
add_filter( 'bp_notifications_get_notifications_for_user', 'wpforo_bp_format_buddypress_notifications', 11, 5 );

/**
 * Hooked into the new reply function, this notification action is responsible
 * for notifying topic and hierarchical reply authors of topic replies.
 *
 * @since wpForo (1.4.8)
 *
 * @param array $post
 * @param array $topic
 */
function wpforo_bp_add_notification( $post = array(), $topic = array() ) {

    if( !wpforo_feature('bp_notification') ) return;

    //Get reply data
    if( !wpfval($post,'postid') ) return;
    if( !wpfval($topic,'topicid') ) return;

    //Don't notify if a new reply is unapproved
    if( wpfval($post,'status') )  return false;
    if( wpfval($post, 'is_first_post'))  return false;

    //Get author information
    $author_id = $post['userid'];
    $topic_author_id = $topic['userid'];

    // Hierarchical replies
    if ( wpfval($post, 'parentid') ) {
        $reply_to_item_author_id = wpforo_post( $post['parentid'], 'userid' );
    }

    // Notify the topic author if not the current reply author
    if ( $author_id != $topic_author_id ) {
        $args = array(
            'user_id'           => $topic_author_id,
            'item_id'           => $post['postid'],
            'component_name'    => 'community',
            'component_action'  => 'wpforo_new_reply',
            'date_notified'     => $post['created'],
            'secondary_item_id' => $author_id
        );
        bp_notifications_add_notification( $args );
    }

    // Notify the immediate reply author if not the current reply author
    if (    isset($reply_to_item_author_id) &&
            wpfval($post, 'parentid') &&
            $topic_author_id != $reply_to_item_author_id &&
            $author_id != $reply_to_item_author_id )
        {
        $args = array(
            'user_id'           => $reply_to_item_author_id,
            'item_id'           => $post['postid'],
            'component_name'    => 'community',
            'component_action'  => 'wpforo_new_reply',
            'date_notified'     => $post['created'],
            'secondary_item_id' => $author_id
        );
        bp_notifications_add_notification( $args );
    }
}
add_action( 'wpforo_after_add_post', 'wpforo_bp_add_notification', 10, 2 );

/**
 * Remove notification when reply is set unapproved
 *
 * @since wpForo (1.4.8)
 *
 * @param array $post
 * @param array $topic
 */
function wpforo_bp_delete_notification( $post = array(), $topic = array() ) {

    if( !wpforo_feature('bp_notification') ) return;

    //Get reply data
    if( !wpfval($post,'postid') ) return;
    if( !wpfval($topic,'topicid') && wpfval($post,'topicid') ) {
        $topic = wpforo_topic($post['topicid']);
    }

    $reply_to_item_author_id = 0;
    if ( wpfval($post, 'parentid') ) {
        $reply_to_item_author_id = wpforo_post( $post['parentid'], 'userid' );
    }

    if ( wpfval($topic, 'userid') ) {
        bp_notifications_delete_notifications_by_item_id( $topic['userid'], $post['postid'], 'community', 'wpforo_new_reply' );
    }

    if( $reply_to_item_author_id && $topic['userid'] !== $reply_to_item_author_id ){
        bp_notifications_delete_notifications_by_item_id( $reply_to_item_author_id, $post['postid'], 'community', 'wpforo_new_reply' );
    }
}
add_action( 'wpforo_after_delete_post', 'wpforo_bp_delete_notification', 10 );

/**
 * Add / Remove buddypress notification based on post status (approve/unapprove)
 *
 * @since wpForo (1.4.8)
 *
 * @param int $reply_id
 * @param int $status    | 0 is approved, 1 is unapproved
 */
function wpforo_bp_notification_on_post_status_change( $reply_id, $status = 0 ) {
    if( !$reply_id || !wpforo_feature('bp_notification') ) return;
    $post = WPF()->post->get_post($reply_id);
    $post['status'] = $status;
    if( wpfval($post,'topicid') ) {
        $topic = WPF()->topic->get_topic($post['topicid']);
    } else {
        return false;
    }
    if( $status ){
        wpforo_bp_delete_notification( $post, $topic );
    }
    else{
        wpforo_bp_add_notification( $post, $topic );
    }
}
add_action( 'wpforo_post_status_update', 'wpforo_bp_notification_on_post_status_change', 10, 2 );

/**
 * Mark notifications as read when reading a topic
 *
 * @since wpForo (1.4.8)
 *
 * @return If not trying to mark a notification as read
 */
function wpforo_bp_buddypress_mark_notifications( $action = '' ) {

    if ( empty( $_GET['itemid'] ) || empty( $_GET['action'] ) ) return;
    if ( 'wpforo_mark_read' !== $_GET['action'] ) return;

    // Get required data
    $action = ($action) ? $action : $_GET['action'];
    $user_id  = bp_loggedin_user_id();
    $reply_id = intval( $_GET['itemid'] );

    // Check nonce
    $result = isset( $_REQUEST['_wpnonce'] ) ? wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpforo_mark_topic_' . $reply_id ) : false;

    if ( !$result ) {
        $wp_error = new WP_Error();
        $wp_error->add( 'wpforo_bp_notification_error', __( 'Are you sure you wanted to do that?', 'wpforo' ) );
        // Check current user's ability to edit the user
    } elseif ( !current_user_can( 'edit_user', $user_id ) ) {
        $wp_error = new WP_Error();
        $wp_error->add( 'wpforo_bp_notification_permissions', __( 'You do not have permission to mark notifications for that user.', 'wpforo' ) );
    }

    if ( isset($wp_error) && !is_wp_error($wp_error) ) {
        $success = bp_notifications_mark_notifications_by_item_id( $user_id, $reply_id, 'community', 'wpforo_new_reply' );
        do_action( 'wpforo_bp_notifications_handler', $success, $user_id, $reply_id, $action );
    }

    // Redirect to the topic
    $redirect = wpforo_post( $reply_id, 'url' );

    // Redirect
    wp_safe_redirect( $redirect );

    // For good measure
    exit();
}
add_action( 'template_redirect', 'wpforo_bp_buddypress_mark_notifications', 9 );

function wpforo_bp_profile_url( $url = '', $member = array(), $template = 'profile' ){

    if(wpfval($member, 'ID')){

        $user_domain = trim( bp_core_get_user_domain( $member['ID'] ), '/');
        $user_domain = strtok( $user_domain, '?');

        if( $user_domain ){
            if( $template == 'account' ){
                $url = $user_domain . '/profile/';
            }
            elseif( $template == 'activity' ){
                $url = $user_domain . '/community/';
            }
            elseif( $template == 'subscriptions' ){
                $url = $user_domain . '/community/subscriptions/';
            }
            elseif( $template == 'profile' ) {
                $url = $user_domain;
            }
        }
    }

    return apply_filters('wpforo_bp_member_profile_url', $url, $member, $template);
}

function wpforo_bp_profile_update( $userid ) {
    WPF()->member->reset( 'user', $userid );
}
add_action( 'profile_update', 'wpforo_bp_profile_update', 10 );
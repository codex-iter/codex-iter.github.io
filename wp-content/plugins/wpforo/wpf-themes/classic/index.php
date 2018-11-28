<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>
<?php
/**
 * Template Name:  WpForo Index (Forums List)
 */
?>
<?php if( WPF()->use_home_url ) get_header(); ?>
	<?php extract(WPF()->current_object, EXTR_OVERWRITE); ?>
	<?php include("header.php"); ?>
    <div class="wpforo-main <?php echo 'wpft-' . $template ?>">
    	<div class="wpforo-content <?php if(WPF()->api->options['sb_location_toggle'] == 'right') echo 'wpfrt' ?>" <?php echo is_active_sidebar('forum-sidebar') ? '' : 'style="width:100%"' ?>>
        <?php do_action( 'wpforo_content_start' ); ?>
		<?php if( WPF()->current_user_status == 'banned' || WPF()->current_user_status == 'trashed' ) : ?>
			<p class="wpf-p-error"><?php wpforo_phrase('You have been banned. Please contact to forum administrators for more information.') ?></p>
		<?php else : ?>
			<?php
                if( !WPF()->current_object['is_404'] ){

                    if($template == 'search'){
                        include( wpftpl('search.php') );
                    }elseif($template == 'recent'){
                        include( wpftpl('recent.php') );
                    }elseif($template == 'tags'){
                        include( wpftpl('tags.php') );
                    }elseif($template == 'register'){
                        include( wpftpl('register.php') );
                    }elseif($template == 'login'){
                        include( wpftpl('login.php') );
                    }elseif($template == 'lostpassword'){
                        do_shortcode('[wpforo-lostpassword]');
                    }elseif($template == 'resetpassword'){
                        do_shortcode('[wpforo-resetpassword]');
                    }elseif($template == 'page'){
                        wpforo_page();
                    }elseif($template == 'members'){
                        if( !empty($_GET['_wpfms']) ){
                            $users_include = array();
                            $search_fields_names = WPF()->member->get_search_fields_names(false);

                            $wpfms = (isset($_GET['wpfms'])) ? sanitize_text_field($_GET['wpfms']) : '';
                            if($wpfms){
                                $users_include = WPF()->member->search($wpfms, $search_fields_names);
                            }else{
                                if( $filters = array_filter($_GET) ){
                                    $args = array();
                                    foreach ($filters as $filter_key => $filter){
                                        if( in_array($filter_key, (array) $search_fields_names) && !is_array($filter) ){
                                            $args[$filter_key] = $filter;
                                        }
                                    }
                                    $users_include = WPF()->member->filter($args);
                                }
                            }

                            $users_include = apply_filters('wpforo_member_search_users_include', $users_include);
                        }
                        $args = array(
                            'offset' => ($paged - 1) * WPF()->member->options['members_per_page'],
                            'row_count' => WPF()->member->options['members_per_page'],
                            'orderby' => 'posts',
                            'order' => 'DESC',
                            'groupids' => WPF()->usergroup->get_visible_usergroup_ids()
                        );
                        if(!empty($users_include)) $args['include'] = $users_include;
                        $items_count = 0;
                        $members = WPF()->member->get_members($args, $items_count);
                        if(isset($users_include) && empty($users_include)){ $members = array(); $items_count = 0; }

                        include( wpftpl('members.php') );
                    }elseif( isset(WPF()->member_tpls[$template]) && WPF()->member_tpls[$template] ){
                        include( wpftpl('profile.php') );
                    }else{
                        if( $template == 'forum' || $template == 'topic' ) : ?>
                            <?php if(!isset($forum_slug)) : ?>
                                <h1 id="wpforo-title">
                                    <?php echo esc_html(WPF()->general_options['title']) ?>
                                        <div class="wpforo-feed">
                                            <span class="wpf-unread-posts">
                                                <a href="<?php echo esc_url(wpforo_home_url(WPF()->tpl->slugs['recent'] . '?view=unread' )) ?>">
                                                    <i class="fas fa-layer-group" style="padding-right: 1px; font-size: 13px;"></i> <span><?php wpforo_phrase('Unread Posts') ?></span>
                                                </a>
                                            </span>
                                            <?php if( wpforo_feature('rss-feed') ): ?>
                                                <sep> &nbsp;|&nbsp; </sep>
                                            <span class="wpf-feed-forums">
                                                <a href="<?php WPF()->feed->rss2_url( true, 'forum' ); ?>"  title="<?php wpforo_phrase('Forums RSS Feed') ?>" target="_blank">
                                                    <span><?php wpforo_phrase('Forums') ?></span> <i class="fas fa-rss wpfsx"></i>
                                                </a>
                                            </span><sep> &nbsp;|&nbsp; </sep>
                                                <span class="wpf-feed-topics">
                                                <a href="<?php WPF()->feed->rss2_url( true, 'topic' ); ?>"  title="<?php wpforo_phrase('Topics RSS Feed') ?>" target="_blank">
                                                    <span><?php wpforo_phrase('Topics') ?></span> <i class="fas fa-rss wpfsx"></i>
                                                </a>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                </h1>
                            <?php elseif($template == 'topic' && $forum['is_cat']) : ?>
                                <h1 id="wpforo-title"><?php echo esc_html($forum['title']) ?></h1>
                            <?php endif; ?>
                            <?php $cats = WPF()->forum->get_forums( (isset($forum_slug) &&  $forum_slug != '' ? array( "parent_slug" => $forum_slug ) : array( "type" => 'category' ) ) ); ?>

                            <?php if(is_array($cats) && !empty($cats)) : ?>

                                <?php foreach($cats as $key => $cat) : ?>
                                    <?php if( WPF()->perm->forum_can( 'vf', $cat['forumid'] ) ): ?>
                                        <?php $forums = WPF()->forum->get_forums( array( "parentid" => $cat['forumid'], "type" => 'forum' ) ); ?>
                                        <?php if(is_array($forums) && !empty($forums)) : ?>
                                            <?php do_action( 'wpforo_category_loop_start', $cat, $key ) ?>
                                            <?php include( wpftpl('layouts/'.($cat['cat_layout'] ? $cat['cat_layout'] : 1).'/forum.php') ); ?>
                                            <?php do_action( 'wpforo_category_loop_end', $cat, $key ) ?>
                                        <?php endif; ?>
                                    <?php endif; //checking forum permissions (can view forum) ?>
                                <?php endforeach; //$cats as $cat ?>

                            <?php else : ?>
                                <p class="wpf-p-error"><?php wpforo_phrase('No forums were found here.') ?></p>
                            <?php endif; //is_array($cats) && !empty($cats) ?>
                            <?php if( $template == 'topic' ) : ?><div class="wpf-subforum-sep" style="height:20px;"></div><?php endif; ?>

                        <?php endif; //Forum template ?>

                        <?php if( $template == 'topic' ) : ?>
                            <?php if( is_array($cats) && !empty($cats) && $cat['is_cat'] == 0 ) : ?>

                                <?php if( isset($forum_slug) && $forum_slug ) : ?>

                                    <?php $forum = WPF()->forum->get_forum( array( 'slug' => $forum_slug ) );  ?>

                                    <?php if(is_array($forum) && !empty($forum)) : ?>

                                        <?php if( WPF()->perm->forum_can( 'vf', $forum['forumid'] ) ): ?>

                                            <div class="wpf-head-bar">
                                                <div class="wpf-head-bar-left">
                                                    <h1 id="wpforo-title"><?php echo esc_html($forum['title']) ?></h1>
                                                    <?php if( $forum['description'] ): ?>
                                                        <div id="wpforo-description"><?php echo $forum['description'] ?></div>
                                                    <?php endif; ?>
                                                    <div class="wpf-action-link">
                                                        <?php WPF()->tpl->forum_subscribe_link() ?>
                                                        <?php if( wpforo_feature('rss-feed') ): ?>
                                                            <span class="wpf-feed">| <a href="<?php WPF()->feed->rss2_url(); ?>" title="<?php wpforo_phrase('Forum RSS Feed') ?>" target="_blank"><span style="text-transform: uppercase;"><?php wpforo_phrase('RSS') ?></span> <i class="fas fa-rss wpfsx"></i></a></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php if( WPF()->perm->forum_can( 'ct', $cat['forumid']) ): ?>
                                                    <div class="wpf-head-bar-right"><button class="wpf-button" id="add_wpftopic"><?php wpforo_phrase( ( WPF()->forum->get_layout() == 3 ? 'Ask a question' : 'Add topic' ) ) ?></button></div>
                                                <?php elseif( WPF()->current_user_groupid == 4 ) : ?>
                                                    <div class="wpf-head-bar-right"><button class="wpf-button not_reg_user" id="add_wpftopic"><?php wpforo_phrase('Add topic') ?></button></div>
                                                <?php endif; ?>
                                                <div class="wpf-clear"></div>
                                            </div>

                                            <?php if( WPF()->perm->forum_can( 'ct', $forum['forumid'] ) ) WPF()->tpl->topic_form($forum['forumid']); ?>

                                            <?php
                                            $args = array(
                                                'offset' => ($paged - 1) * WPF()->post->options['topics_per_page'],
                                                'row_count' => WPF()->post->options['topics_per_page'],
                                                'forumid' => $cat['forumid'],
                                                'orderby' => 'type, modified',
                                                'order' => 'DESC'
                                            );
                                            $items_count = 0;
                                            $topics = WPF()->topic->get_topics( $args, $items_count );
                                            ?>

                                            <?php if( is_array($topics) && !empty($topics) ) : ?>

                                                <?php WPF()->tpl->pagenavi($paged, $items_count, true, 'wpf-navi-topic-top'); ?>

                                                <?php include( wpftpl('layouts/'.($cat['cat_layout'] ? $cat['cat_layout'] : 1).'/topic.php') ); ?>

                                                <?php WPF()->tpl->pagenavi($paged, $items_count, true, 'wpf-navi-topic-bottom'); ?>

                                                <?php do_action( 'wpforo_topic_list_footer' ) ?>

                                            <?php else : ?>
                                                <p class="wpf-p-error"><?php wpforo_phrase('No topics were found here') ?>  </p>
                                            <?php endif; ?>

                                        <?php else : ?>
                                            <p class="wpf-p-error"><?php wpforo_phrase('You don\'t have permissions to see this page, please register or login for further information') ?></p>
                                        <?php endif; //chekcing permissions (can view forum) ?>

                                    <?php else : ?>
                                        <?php include( wpftpl('404.php') ) ?>
                                    <?php endif; ?>

                                <?php else : ?>
                                    <?php include( wpftpl('404.php') ) ?>
                                <?php endif; ?>

                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if( $template == 'post' ) : ?>
                            <?php
                            if( is_array($forum) && !empty($forum) ) :

                                if( WPF()->perm->forum_can( 'vt', $forum['forumid'] ) ):

                                    if( is_array($topic) && !empty($topic) ) : ?>

                                        <?php $owner = wpforo_is_owner($topic['userid'], $topic['email']); ?>
                                        <?php if( isset($topic['private']) && $topic['private'] && !$owner && !WPF()->perm->forum_can( 'vp', $forum['forumid'] ) ): ?>
                                            <p class="wpf-p-error"><?php wpforo_phrase('Topic are private, please register or login for further information') ?></p>
                                        <?php else: ?>

                                            <?php
                                            $cat_layout = WPF()->forum->get_layout( array( 'topicid' =>  $topic['topicid'] ) );
                                            $args = array(
                                                'offset' => ($paged - 1) * WPF()->post->options['posts_per_page'],
                                                'row_count' => WPF()->post->options['posts_per_page'],
                                                'topicid' => $topic['topicid'],
                                                'forumid' => $forum['forumid'],
                                                //'owner' => $owner
                                            );
                                            $items_count = 0;
                                            $posts = WPF()->post->get_posts( $args, $items_count);
                                            ?>

                                            <?php if( is_array($posts) && !empty($posts) ) : ?>
                                                <div class="wpf-head-bar">
                                                    <h1 id="wpforo-title"><?php $icon_title = WPF()->tpl->icon('topic', $topic, false, 'title'); if( $icon_title ) echo '<span class="wpf-status-title">[' . esc_html($icon_title) . ']</span> ' ?><?php echo esc_html( wpforo_text($topic['title'], 0, false) ) ?>&nbsp;&nbsp;</h1>
                                                    <div class="wpf-action-link"><?php WPF()->tpl->topic_subscribe_link() ?></div>
                                                </div>

                                                <?php WPF()->tpl->pagenavi( $paged, $items_count, true, 'wpf-navi-post-top' ); ?>

                                                <?php include( wpftpl('layouts/'.($cat_layout ? $cat_layout : 1).'/post.php') ); ?>

                                                <?php WPF()->tpl->pagenavi($paged, $items_count, true, 'wpf-navi-post-bottom'); ?>

                                                <?php
                                                if( WPF()->perm->forum_can( 'cr', $forum['forumid'] ) ) {
                                                    $default = array(
                                                        "topic_closed" => $topic['closed'],
                                                        "topicid" => $topic['topicid'],
                                                        "forumid" => $forum['forumid'],
                                                        "layout" => ($cat_layout ? $cat_layout : 1),
                                                        "topic_title" => wpforo_text($topic['title'], 0, false)
                                                    );
                                                    WPF()->tpl->reply_form( $default );
                                                }
                                                ?>

                                                <?php do_action( 'wpforo_post_list_footer' ) ?>

                                            <?php else : ?>
                                                <p class="wpf-p-error"><?php wpforo_phrase('You don\'t have permissions to see this page, please register or login for further information') ?></p>
                                            <?php endif; ?>

                                        <?php endif; ?>

                                    <?php else : ?>
                                        <p class="wpf-p-error"><?php wpforo_phrase('This topic doesn\'t exist or you don\'t have permissions to see that.') ?></p>
                                    <?php endif; ?>

                                <?php else : ?>
                                    <p class="wpf-p-error"><?php wpforo_phrase('You don\'t have permissions to see this page, please register or login for further information') ?></p>
                                <?php endif; //checking permission can view topic ?>

                            <?php else : ?>
                                <?php include( wpftpl('404.php') ) ?>
                            <?php endif ?>

                        <?php endif; ?>
                    <?php }

                }else{
                    include( wpftpl('404.php') );
                } ?>

	           </div>
	           <?php if (is_active_sidebar('forum-sidebar')) : ?>
		           <div class="wpforo-right-sidebar">
		           		<?php dynamic_sidebar('forum-sidebar') ?>
		           </div>
	           <?php endif; ?>
	        <?php endif; ?>
           <div class="wpf-clear"></div>
      </div>
<?php include("footer.php") ?>

<?php if( WPF()->use_home_url ) get_footer() ?>
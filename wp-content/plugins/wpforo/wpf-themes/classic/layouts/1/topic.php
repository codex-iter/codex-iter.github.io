<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<div class="wpfl-1">
          <div class="wpforo-topic-head">
              <div class="head-title"><?php wpforo_phrase('Topic title') ?></div>
              <div class="head-stat-views"><?php wpforo_phrase('Views') ?></div>
              <div class="head-stat-posts"><?php wpforo_phrase('Posts') ?></div>
              <br class="wpf-clear">
          </div>
		  
	<?php foreach($topics as $key => $topic) : ?>
		
		<?php 
			$member = wpforo_member($topic);
			if(isset($topic['last_post']) && $topic['last_post'] != 0){ 
				$last_post = wpforo_post($topic['last_post']);
				$last_poster = wpforo_member($last_post);
			}
			if(isset($topic['first_postid']) && $topic['first_postid'] != 0){
				$first_post = wpforo_post($topic['first_postid']);
				$intro_posts = WPF()->post->options['layout_extended_intro_posts_count']; if( $intro_posts < 1 ){ $intro_posts = NULL; } else { $intro_posts = ($intro_posts > 1) ? ($intro_posts - 1) : $intro_posts = 0; }
				$first_poster = wpforo_member($first_post); 
				$posts = WPF()->post->get_posts( array('topicid' => $topic['topicid'], 'exclude' => $topic['first_postid'], 'orderby' => '`is_first_post` ASC, `created` DESC, `postid` DESC', 'row_count' => $intro_posts) );
				$posts = array_reverse($posts);
			}
			$topic_url = wpforo_topic($topic['topicid'], 'url');
			$post_toglle = WPF()->post->options['layout_extended_intro_posts_toggle'];
		?>
		
		<div class="topic-wrap <?php wpforo_unread($topic['topicid'], 'topic'); ?>">
          <div class="wpforo-topic">
              <div class="wpforo-topic-icon">
                  <?php wpforo_topic_icon($topic, 'base'); ?>
              </div>
              <div class="wpforo-topic-info">
                <p class="wpforo-topic-title"><?php wpforo_topic_icon($topic, 'status'); ?> <a href="<?php echo esc_url($topic_url) ?>"><?php echo esc_html($topic['title']); ?></a> <?php wpforo_viewing( $topic ); ?></p>
                <p class="wpforo-topic-start-info wpfcl-1">
                <span class="wpfcl-5"><?php wpforo_phrase('First post and replies') ?></span>&nbsp; <i id="button-arrow-<?php echo intval($topic['topicid']) ?>" class="topictoggle wpfcl-a fas fa-chevron-<?php echo ( $post_toglle == 1 ? 'up' : 'down' ) ?>"></i>
				<?php if(isset($last_post) && !empty($last_post)) : ?>
                	<span class="wpf-vsep">|</span>
                    <span class="wpf-last-post-by"><a href="<?php echo esc_url( $last_post['url'] ) ?>"><?php echo sprintf( wpforo_phrase('Last post by %s', FALSE), $last_poster['display_name'] ) ?>, <?php wpforo_date($last_post['created']); ?> <i class="fas fa-chevron-right fa-sx wpfcl-a"></i></a></span>
                <?php endif; ?>
                </p> 
                <div class="wpforo-topic-badges"><?php wpforo_hook('wpforo_topic_info_end', $topic); ?></div>
              </div>
              <div class="wpforo-topic-stat-views"><?php echo intval($topic['views']) ?></div>
              <div class="wpforo-topic-stat-posts"><?php echo intval($topic['posts']) ?></div>
              <br class="wpf-clear">
          </div><!-- wpforo-topic -->
          <div class="wpforo-last-posts-<?php echo intval($topic['topicid']) ?>" style="display: <?php echo ( $post_toglle == 1 ? 'block' : 'none' ); ?>">
              <div class="wpforo-last-posts-tab">&nbsp;</div>
              <div class="wpforo-last-posts-list">
                <ul>
                    <li> 
                        <div class="wpforo-last-post-title"><i class="fas fa-comments fa-flip-horizontal wpfsx wpfcl-0"></i> &nbsp; <a href="<?php echo esc_url($first_post['url']) ?>"><?php echo esc_html( wpforo_text($first_post['body'], WPF()->post->options['layout_extended_intro_posts_length'], false)) ?></a></div>
                        <div class="wpforo-last-post-user"><?php wpforo_member_link($first_poster, 'by %s', 9); ?></div>
                        <div class="wpforo-last-post-date"><?php wpforo_date($first_post['created']); ?></div> 
                        <br class="wpf-clear">
                    </li>
					<?php if(!empty($posts) && is_array($posts)) : ?>
						<?php foreach($posts as $post) : ?>
							<?php $poster = wpforo_member($post); ?>
		                    <li>
		                        <div class="wpforo-last-post-title"><i class="fas fa-reply fa-rotate-180 wpfsx wpfcl-0"></i> &nbsp; <a href="<?php echo esc_url( wpforo_post($post['postid'], 'url') ); ?>" title="<?php wpforo_phrase('REPLY:') ?> <?php echo esc_html( wpforo_text($post['body'], 100, false)) ?>"><?php echo (( $post_body = esc_html(wpforo_text($post['body'], WPF()->post->options['layout_extended_intro_posts_length'], FALSE)) ) ? $post_body : esc_html($post['title'])) ?></a></div>
		                        <div class="wpforo-last-post-user"><?php wpforo_member_link($poster, 'by %s', 9); ?></div>
		                        <div class="wpforo-last-post-date"><?php wpforo_date($post['created']); ?></div> 
		                        <br class="wpf-clear">
		                    </li>
						<?php endforeach ?>
                        <?php if(intval($topic['posts']) > ($intro_posts+1)): ?>
                            <li style="text-align:right;"><a href="<?php echo esc_url($topic_url) ?>"><?php wpforo_phrase('view all posts', true, 'lower');  ?> <i class="fas fa-angle-right" aria-hidden="true"></i></a></li>
                        <?php endif ?>
					<?php endif ?>
                </ul>
              </div><!-- wpforo-last-posts-list -->
              <br class="wpf-clear">
          </div><!-- wpforo-last-posts -->
	    </div>
		
		<?php do_action( 'wpforo_loop_hook', $key ) ?>
		
	<?php endforeach; ?>
</div> <!-- wpfl-1 -->

<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

/**
* 
* @layout: Extended
* @url: http://gvectors.com/
* @version: 1.0.0
* @author: gVectors Team
* @description: Extended layout displays one level deeper information in advance.
* 
*/
?>

<div class="wpfl-1">
	<div class="wpforo-category">
	    <div class="cat-title"><?php echo esc_html($cat['title']); ?></div>
	    <div class="cat-stat-posts"><?php wpforo_phrase('Posts'); ?></div>
	    <div class="cat-stat-topics"><?php wpforo_phrase('Topics'); ?></div>
	    <br class="wpf-clear" />
	</div><!-- wpforo-category -->
	<?php foreach($forums as $key => $forum) : 
		if( !WPF()->perm->forum_can( 'vf', $forum['forumid'] ) ) continue;
		$sub_forums = WPF()->forum->get_forums( array( "parentid" => $forum['forumid'], "type" => 'forum' ) );
		$has_sub_forums = ( is_array($sub_forums) && !empty($sub_forums) ? TRUE : FALSE );
		
		$data = wpforo_forum($forum['forumid'], 'childs');
		$counts = wpforo_forum($forum['forumid'], 'counts');
		$topics = WPF()->topic->get_topics( array("forumids" => $data, "orderby" => "type, modified", "order" => "DESC", "row_count" => WPF()->forum->options['layout_extended_intro_topics_count'] ) );
		
		$has_topics = ( is_array($topics) && !empty($topics) ? TRUE : FALSE );
		
		$forum_url = wpforo_forum($forum['forumid'], 'url');
		$topic_toglle = WPF()->forum->options['layout_extended_intro_topics_toggle'];

		if( !empty($forum['icon']) ){
            $forum['icon'] = trim($forum['icon']);
		    if( strpos($forum['icon'], ' ') === false ) $forum['icon'] = 'fas ' . $forum['icon'];
        }
		$forum_icon = ( !empty($forum['icon']) ) ? $forum['icon'] : 'fas fa-comments';
		?>
	    <div id="wpf-forum-<?php echo $forum['forumid'] ?>" class="forum-wrap <?php wpforo_unread($forum['forumid'], 'forum') ?>">
	       <div class="wpforo-forum">
	         <div class="wpforo-forum-icon"><i class="<?php echo esc_attr($forum_icon) ?> wpfcl-0"></i></div>
	         <div class="wpforo-forum-info">
	            <h3 class="wpforo-forum-title"><a href="<?php echo esc_url($forum_url) ?>"><?php echo esc_html($forum['title']); ?></a> <?php wpforo_viewing( $forum ); ?></h3>
	            <p class="wpforo-forum-description"><?php echo $forum['description']; ?></p>

	            <?php if($has_sub_forums) : ?>

	                <div class="wpforo-subforum">
	                   <ul>
	                    	<li class="first wpfcl-2"><?php wpforo_phrase('Subforums'); ?>:</li>
	                    	<?php foreach($sub_forums as $sub_forum) :
	                    		if( !WPF()->perm->forum_can( 'vf', $sub_forum['forumid'] ) ) continue;
                                if( !empty($sub_forum['icon']) ){
                                    $sub_forum['icon'] = trim($sub_forum['icon']);
                                    if( strpos($sub_forum['icon'], ' ') === false ) $sub_forum['icon'] = 'fas ' . $sub_forum['icon'];
                                }
								$sub_forum_icon = ( isset($sub_forum['icon']) && $sub_forum['icon']) ? $sub_forum['icon'] : 'fas fa-comments'; ?>
	                    		<li class="<?php wpforo_unread($sub_forum['forumid'], 'forum') ?>"><i class="<?php echo esc_attr($sub_forum_icon) ?> wpfcl-0"></i>&nbsp;<a href="<?php echo esc_url( wpforo_forum($sub_forum['forumid'], 'url') ) ?>"><?php echo esc_html($sub_forum['title']); ?></a> <?php wpforo_viewing( $sub_forum ); ?></li>

	                    	<?php endforeach; ?>

	                   </ul>
	                   <br class="wpf-clear" />
	                </div><!-- wpforo-subforum -->

	            <?php endif; ?>
				
				<?php if($has_topics) : ?>
					
		            <div class="wpforo-forum-footer">
		                <span class="wpfcl-5"><?php wpforo_phrase('Recent Topics'); ?></span> &nbsp; 
		                <i id="img-arrow-<?php echo intval($forum['forumid']) ?>" class="topictoggle fas fa-chevron-<?php echo ( $topic_toglle == 1 ? 'up' : 'down' ) ?>" style="color: rgb(67, 166, 223);font-size: 14px; cursor: pointer;"></i>
		            </div>
		            
		        <?php endif ?>
		        
	         </div><!-- wpforo-forum-info -->
	         
	         <div class="wpforo-forum-stat-posts"><?php echo wpforo_print_number($counts['posts']) ?></div>
	         <div class="wpforo-forum-stat-topics"><?php echo wpforo_print_number($counts['topics']) ?></div>
				
	         <br class="wpf-clear" />
	       </div><!-- wpforo-forum -->
		   
			<?php if($has_topics) : ?>
				
	           <div class="wpforo-last-topics-<?php echo intval($forum['forumid']) ?>" style="display: <?php echo ( $topic_toglle == 1 ? 'block' : 'none' ) ?>;">
	              <div class="wpforo-last-topics-tab">&nbsp;</div>
	              <div class="wpforo-last-topics-list">
	                <ul>
						<?php foreach($topics as $topic) : ?>
                        	<?php $last_post = wpforo_post($topic['last_post']) ?>
							<?php $member = wpforo_member($last_post); ?>
	                        <li class="<?php wpforo_unread($topic['topicid'], 'topic') ?>">
	                            <div class="wpforo-last-topic-title">
                                    <?php wpforo_topic_icon($topic); ?>&nbsp;<a href="<?php echo esc_url($last_post['url']) ?>">
                                    <?php echo esc_html(wpforo_text($topic['title'], WPF()->forum->options['layout_extended_intro_topics_length'], false)) ?></a>
                                </div>
	                            <div class="wpforo-last-topic-user" title="<?php echo esc_attr($member['display_name']) ?>"><?php wpforo_member_link($member, 'by', 9); ?></div>
	                            <div class="wpforo-last-topic-date"><?php wpforo_date($topic['modified']); ?></div> 
	                            <br class="wpf-clear" />
	                        </li>
						<?php endforeach; ?>
						<?php if( intval($forum['topics']) > WPF()->forum->options['layout_extended_intro_topics_count'] ): ?>
                            <li>
                                <div class="wpforo-last-topic-user wpf-vat">
                                	<a href="<?php echo esc_url($forum_url) ?>"><?php wpforo_phrase('view all topics', true, 'lower');  ?> <i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                </div>
                                <br class="wpf-clear" />
                            </li>
						<?php endif ?>
	                </ul>
	              </div>
	              <br class="wpf-clear" />
	           </div><!-- wpforo-last-topics -->
			   
			<?php endif; ?>
		   
	    </div><!-- forum-wrap -->
        
        <?php do_action( 'wpforo_loop_hook', $key ) ?>
	    
	<?php endforeach; ?> <!-- $forums as $forum -->
</div><!-- wpfl-1 -->
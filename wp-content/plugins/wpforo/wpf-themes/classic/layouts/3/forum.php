<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

/**
* 
* @layout: QA
* @url: http://gvectors.com/
* @version: 1.0.0
* @author: gVectors Team
* @description: Q&A layout turns your forum to a powerful question and answer discussion board.
* 
*/
?>

<div class="wpfl-3">
	<div class="wpforo-category">
	    <div class="cat-title"><?php echo esc_html($cat['title']); ?></div>
	    <div class="cat-stat-posts"><?php wpforo_phrase('Posts') ?></div>
	    <div class="cat-stat-answers"><?php wpforo_phrase('Answers') ?></div>
	    <div class="cat-stat-questions"><?php wpforo_phrase('Questions') ?></div>
	    <br class="wpf-clear" />
	</div>
	
	<?php foreach($forums as $key =>$forum) : 
		if( !WPF()->perm->forum_can( 'vf', $forum['forumid'] ) ) continue;
		
		$sub_forums = WPF()->forum->get_forums( array( "parentid" => $forum['forumid'], "type" => 'forum' ) );
		$has_sub_forums = ( is_array($sub_forums) && !empty($sub_forums) ? TRUE : FALSE );
		
		$topics = WPF()->topic->get_topics( array("forumid" => $forum['forumid'], "orderby" => "type, modified", "order" => "DESC", "row_count" => WPF()->forum->options['layout_qa_intro_topics_count'] ) );
		$has_topics = ( is_array($topics) && !empty($topics) ? TRUE : FALSE );
		
		$data = wpforo_forum($forum['forumid'], 'childs');
		$counts = wpforo_forum($forum['forumid'], 'counts');
		
		$forum_url = wpforo_forum($forum['forumid'],'url');
		$topic_toglle = WPF()->forum->options['layout_qa_intro_topics_toggle'];

        if( !empty($forum['icon']) ){
            $forum['icon'] = trim($forum['icon']);
            if( strpos($forum['icon'], ' ') === false ) $forum['icon'] = 'fas ' . $forum['icon'];
        }
		$forum_icon = ( isset($forum['icon']) && $forum['icon']) ? $forum['icon'] : 'fas fa-comments';
		?>
		
		<div class="forum-wrap <?php wpforo_unread($forum['forumid'], 'forum') ?>">
		    <div class="wpforo-forum">
		        <div class="wpforo-forum-icon"><i class="<?php echo esc_attr($forum_icon) ?> wpfcl-0"></i></div>
		        <div class="wpforo-forum-info">
					<h3 class="wpforo-forum-title"><a href="<?php echo esc_url($forum_url) ?>"><?php echo esc_html($forum['title']); ?></a> <?php wpforo_viewing( $forum ); ?></h3>
					<p class="wpforo-forum-description"><?php echo $forum['description'] ?></p>
					
	            	<?php if($has_sub_forums) : ?>
						
						<div class="wpforo-subforum">
			                <ul>
			                    <li class="first"><?php wpforo_phrase('Subforums') ?>:</li>
			                    
								<?php foreach($sub_forums as $sub_forum) : 
									if( !WPF()->perm->forum_can( 'vf', $sub_forum['forumid'] ) ) continue;
                                    if( !empty($sub_forum['icon']) ){
                                        $sub_forum['icon'] = trim($sub_forum['icon']);
                                        if( strpos($sub_forum['icon'], ' ') === false ) $sub_forum['icon'] = 'fas ' . $sub_forum['icon'];
                                    }
									$sub_forum_icon = ( isset($sub_forum['icon']) && $sub_forum['icon']) ? $sub_forum['icon'] : 'fas fa-comments'; ?>
									
		                    		<li class="<?php wpforo_unread($sub_forum['forumid'], 'forum') ?>"><i class="<?php echo esc_attr($sub_forum_icon) ?> wpfcl-0"></i>&nbsp;<a href="<?php echo esc_url( wpforo_forum($sub_forum['forumid'],'url') ) ?>"><?php echo esc_html($sub_forum['title']); ?></a> <?php wpforo_viewing( $sub_forum ); ?></li>
									
		                    	<?php endforeach; ?>
								
			                </ul>
			                <br class="wpf-clear" />
			            </div><!-- wpforo-subforum -->
						
					<?php endif; ?>
					
					<?php if($has_topics) : ?>
						
						<div class="wpforo-forum-footer">
			            	<span class="wpfcl-5"><?php wpforo_phrase('Recent Questions') ?></span> &nbsp;
			            	 <i id="img-arrow-<?php echo intval($forum['forumid']) ?>" class="topictoggle fas fa-chevron-<?php echo ( $topic_toglle == 1 ? 'up' : 'down' ); ?>" style="color: rgb(67, 166, 223);font-size: 14px; cursor: pointer;"></i> &nbsp;&nbsp;
						</div>
						
					<?php endif ?>
					
		        </div><!-- wpforo-forum-info -->
				
		        <div class="wpforo-forum-stat-questions"><?php echo wpforo_print_number($counts['topics']) ?></div>
		        <div class="wpforo-forum-stat-answers"><?php echo wpforo_print_number(WPF()->topic->get_sum_answer($data)) ?></div>
		        <div class="wpforo-forum-stat-posts"><?php echo wpforo_print_number($counts['posts']) ?></div>
		    </div><!-- wpforo-forum -->
			
			
			<?php if($has_topics) : ?>
				
			    <div class="wpforo-last-topics-<?php echo intval($forum['forumid']) ?>" style="display: <?php echo ( $topic_toglle ? 'block' : 'none' ); ?>;">
			        <div class="wpforo-last-topics-tab"></div>
			        <div class="wpforo-last-topics-list">
			            <ul>
							<?php foreach($topics as $topic) : ?>
								<?php $member = wpforo_member($topic); ?>
				                <li class="<?php wpforo_unread($topic['topicid'], 'topic') ?>">
				                    <div class="wpforo-last-topic wpfcl-2">
				                    	<div class="votes"><div class="count <?php echo $topic['votes'] == 0 ? "wpfcl-6" : "wpfbg-4 wpfcl-3" ?>"><?php echo intval($topic['votes']) ?></div><div class="wpforo-label <?php echo $topic['votes'] == 0 ? "wpfcl-6" : "wpfbg-4 wpfcl-3" ?>"><?php wpforo_phrase('Votes') ?></div></div>
				                        <div class="answers"><div class="count <?php echo $topic['answers'] == 0 ? "wpfcl-5" : "wpfbg-5 wpfcl-3" ?>"><?php echo intval($topic['answers']) ?></div><div class="wpforo-label <?php echo $topic['answers'] == 0 ? "wpfcl-5" : "wpfbg-5 wpfcl-3" ?>"><?php wpforo_phrase('Answers') ?></div></div>
				                        <div class="views"><div class="count"><?php echo intval($topic['views']) ?></div><div class="wpforo-label"><?php wpforo_phrase('Views') ?></div></div>
				                    </div>
				                    <div class="wpforo-last-topic-title">
				                    	<a href="<?php echo esc_url( wpforo_topic($topic['topicid'], 'url') ) ?>"><?php echo esc_html(wpforo_text($topic['title'], WPF()->forum->options['layout_qa_intro_topics_length'], false)) ?></a><br />
				                    	<span class="wpforo-last-topic-info wpfcl-2"><?php wpforo_member_link($member, 'by'); ?>, <?php wpforo_date($topic['modified']); ?></span>
				                    </div> 
				                    <div class="wpforo-last-topic-posts wpfcl-2"><div class="count"><?php echo intval($topic['posts']) ?></div><div class="wpforo-label"><?php wpforo_phrase('replies') ?></div></div>
				                </li>
			                <?php endforeach; ?>
                            <?php if( intval($forum['topics']) > WPF()->forum->options['layout_qa_intro_topics_count'] ): ?>
                            <li>
                            	<div class="wpforo-last-topic-title wpf-vat">
                                	<a href="<?php echo esc_url($forum_url) ?>"><?php wpforo_phrase('view all questions', true, 'lower');  ?> <i class="fas fa-angle-right" aria-hidden="true"></i></a>
                                </div>
                            </li>
						<?php endif ?>
			            </ul>
			        </div><!-- wpforo-last-topics-list -->
			        <br class="wpf-clear" />
			    </div><!-- wpforo-last-topics -->
				
			<?php endif; ?>
			
		</div><!-- forum-wrap -->
        
        <?php do_action( 'wpforo_loop_hook', $key ) ?>
		
	<?php endforeach; ?> <!-- $forums as $forum -->
</div>
<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

/**
* 
* @layout: Simplified
* @url: http://gvectors.com/
* @version: 1.0.0
* @author: gVectors Team
* @description: Simplified layout looks simple and clean.
* 
*/
?>

<div class="wpfl-2">
	<div class="wpforo-category">
	    <div class="cat-title"><?php echo esc_html($cat['title']); ?></div>
	    <div class="cat-lastpostinfo"><?php wpforo_phrase('Last Post Info'); ?></div>
	    <br class="wpf-clear" />
	</div><!-- wpforo-category -->
  
  	<?php foreach($forums as $key => $forum) : 
  		if( !WPF()->perm->forum_can( 'vf', $forum['forumid'] ) ) continue;
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
		        	<h3 class="wpforo-forum-title"><a href="<?php echo esc_url( wpforo_forum($forum['forumid'],'url') ) ?>"><?php echo esc_html($forum['title']); ?></a> <?php wpforo_viewing( $forum ); ?></h3>
		        	<p class="wpforo-forum-description"><?php echo $forum['description'] ?></p>
					<?php
					 	$data = wpforo_forum($forum['forumid'], 'childs');
						$counts = wpforo_forum($forum['forumid'], 'counts');
						if(!isset($forum['last_postid']) || !$forum['last_postid']){ 
							$lastinfo = WPF()->forum->get_lastinfo( $data );
							if(!empty($lastinfo) && is_array($lastinfo)) $forum = array_merge($forum, $lastinfo);
						}
					?>
		        	<span class="wpforo-forum-stat">
		            	<?php wpforo_phrase('Topics') ?>: <?php echo wpforo_print_number($counts['topics']) ?> &nbsp;<span class="wpfcl-1">|</span>&nbsp; <?php wpforo_phrase('Posts'); ?>: <?php echo wpforo_print_number($counts['posts']) ?>
		        	</span>
					
					<?php $sub_forums = WPF()->forum->get_forums( array( "parentid" => $forum['forumid'], "type" => 'forum' ) ); ?>
		            <?php if(is_array($sub_forums) && !empty($sub_forums)) : ?>
						
			            <div class="wpforo-subforum">
			                <ul>
			                    <li class="first wpfcl-0"><?php wpforo_phrase('Subforums'); ?>: </li>
								
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
					
		    	</div><!-- wpforo-forum-info -->
				
				<?php if($forum['last_postid'] != 0) : ?>
					<?php @$last_post = wpforo_post($forum['last_postid']); ?>
					<?php @$last_post_topic = wpforo_topic($last_post['topicid']) ?>
                    <?php $member = wpforo_member($last_post) ?>
			        <div class="wpforo-last-post">
			            <p class="wpforo-last-post-title"><a href="<?php echo esc_url($last_post['url']) ?>"><?php if(isset($last_post_topic['title'])) wpforo_text($last_post_topic['title'], 30); ?></a></p>
			            <p class="wpforo-last-post-info"><?php wpforo_member_link($member, 'by'); ?>, <?php wpforo_date($forum['last_post_date']) ?></p>
			        </div>
			        <?php if( WPF()->perm->usergroup_can('va') && wpforo_feature('avatars') ): ?>
                    	<div class="wpforo-last-post-avatar">
                        	<?php if( isset($member['profile_url']) && $member['profile_url'] ): ?>
                                <a href="<?php echo esc_url($member['profile_url']) ?>">
                                    <?php echo WPF()->member->get_avatar($forum['last_userid'], 'alt="'.esc_attr($member['display_name']).'" title="'.esc_attr($member['display_name']).'"', 40) ?>
                                </a>
                            <?php else: ?>
                            	<?php echo WPF()->member->avatar($member, 'alt="'.esc_attr($member['display_name']).'" title="'.esc_attr($member['display_name']).'"', 40) ?>
                            <?php endif; ?>
						</div>
					<?php endif; ?>
			        <br class="wpf-clear" />
				<?php else: ?>
                    <div class="wpforo-last-post">
                        <p class="wpforo-last-post-title"><br /><?php wpforo_phrase('Forum is empty'); ?></p>
                    </div>
                    <div class="wpforo-last-post-avatar">&nbsp;</div>
                    <br class="wpf-clear" />
				<?php endif ?>
				
	      	</div><!-- wpforo-forum -->
	  	</div><!-- forum-wrap -->
	  
      	<?php do_action( 'wpforo_loop_hook', $key ) ?>
      
    <?php endforeach; ?> <!-- $forums as $forum -->
</div><!-- wpfl-2 -->


<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<div class="wpfl-3">
	<div class="wpforo-topic-head">
	    <div class="head-title"><?php wpforo_phrase('Topic Title') ?></div>
	    <div class="head-stat-lastpost"><?php wpforo_phrase('Last Post') ?></div>
	    <br class="wpf-clear">
	</div>
    
	<?php foreach($topics as $key => $topic) : ?>
		
		<?php 
			$member = wpforo_member($topic);
			if(isset($topic['last_post']) && $topic['last_post']){
				$last_post = wpforo_post($topic['last_post']);
				$last_poster = wpforo_member($last_post);
			}
			$topic_url = wpforo_topic($topic['topicid'], 'url');
		?>
      <div class="topic-wrap <?php wpforo_unread($topic['topicid'], 'topic') ?>">
          <div class="wpforo-topic">
          	  <?php if( WPF()->perm->usergroup_can('va') && wpforo_feature('avatars') ): ?>
              	<div class="wpforo-topic-avatar"><?php echo WPF()->member->avatar($member) ?></div>
              <?php endif; ?>
              <div class="wpforo-topic-info">
                <p class="wpforo-topic-title">
                    <a href="<?php echo esc_url($topic_url) ?>"><?php wpforo_topic_icon($topic); ?><?php wpforo_text($topic['title'], 70); ?></a> <?php wpforo_viewing( $topic ); ?></p>
                <p class="wpforo-topic-start-info wpfcl-2"><?php wpforo_member_link($member); ?>, <?php wpforo_date($topic['created']); ?></p>
              	<div class="wpforo-topic-badges"><?php wpforo_hook('wpforo_topic_info_end', $topic); ?></div>
              </div>
              <div class="wpforo-topic-status wpfcl-2">
				<div class="votes"><div class="count <?php echo $topic['votes'] == 0 ? 'wpfcl-6' : 'wpfbg-4 wpfcl-3' ?>"><?php echo intval($topic['votes']) ?></div><div class="wpforo-label <?php echo $topic['votes'] == 0 ? 'wpfcl-6' : 'wpfbg-4 wpfcl-3' ?>"><?php wpforo_phrase('Votes') ?></div></div>
                <div class="answers"><div class="count <?php echo $topic['answers'] == 0 ? 'wpfcl-5' : 'wpfbg-5 wpfcl-3' ?>"><?php echo intval($topic['answers']) ?></div><div class="wpforo-label <?php echo $topic['answers'] == 0 ? 'wpfcl-5' : 'wpfbg-5 wpfcl-3' ?>"><?php wpforo_phrase('Answers') ?></div></div>
				<div class="views"><div class="count"><?php echo intval($topic['views']) ?></div><div class="wpforo-label"><?php wpforo_phrase('Views') ?></div></div>
              </div>
			   <?php if(isset($topic['last_post']) && $topic['last_post']) : ?>
              		<div class="wpforo-topic-stat-lastpost"><span style="white-space:nowrap"><?php wpforo_phrase('by') ?>&nbsp;<?php wpforo_member_link($last_poster, '', 9); ?> <a href="<?php echo esc_url($last_post['url']) ?>" title="<?php wpforo_phrase('View the latest post') ?>"><i class="fas fa-chevron-right fa-sx wpfcl-a"></i></a></span><br> <?php wpforo_date($last_post['created']); ?></div>
			  <?php else: ?>
			  		<div class="wpforo-topic-stat-lastpost"><?php wpforo_phrase('Replies not found') ?></div>
			  <?php endif; ?>
          </div><!-- wpforo-topic -->
      </div><!-- topic-wrap -->
	  
	  <?php do_action( 'wpforo_loop_hook', $key ) ?>
	  
    <?php endforeach; ?>
</div><!-- topic-wrap -->

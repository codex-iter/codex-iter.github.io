<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<div class="wpforo-activity-content">
	<?php if(empty($activities)) : ?><p class="wpf-p-error"> <?php wpforo_phrase('No activity found for this member.') ?> </p><?php endif ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	   <?php $bg = FALSE; foreach( $activities as $activity ) : ?>
		  <tr<?php echo ( $bg ? ' class="wpfbg-9"' : '' ) ?>>
			<td class="activity-icon"><?php wpforo_topic_icon( $activity['topicid'], 'mixed' ); ?></td>
			<td class="activity-title">
                <?php
                    if( $activity['is_first_post'] ){
                        $href = esc_url(WPF()->topic->get_topic_url($activity['topicid']));
                        $link_text = wpforo_text( $activity['title'], 60, FALSE );
                    }else{
                        $href = esc_url(WPF()->post->get_post_url($activity['postid']));
                        if( !$link_text = wpforo_text( $activity['body'], 60, FALSE ) ){
                            $link_text = wpforo_text( $activity['title'], 60, FALSE );
                        }
                    }
                    if(!$href) $href = '#';
                    if(!$link_text) $link_text = wpforo_phrase('post link', false);

                    printf('<a style="font-size: 17px;" href="%s">%s</a>', $href, $link_text);

                    if( $activity['forumid'] ){
                        $href = wpforo_forum($activity['forumid'], 'url');
                        $link_text = wpforo_forum($activity['forumid'], 'title');

                        if(!$href) $href = '#';
                        if(!$link_text) $link_text = wpforo_phrase('forum link', false);

                        printf('<p style="font-style: italic"><span>%s</span> <a href="%s">%s</a></p>', wpforo_phrase('in forum', false), $href, $link_text);
                    }
                ?>
            </td>
			<td class="activity-date"><?php wpforo_date($activity['created']); ?></td>
		  </tr>
		<?php $bg = ( $bg ? FALSE : TRUE ); endforeach ?>
   </table>
   <div class="activity-foot">
        <?php WPF()->tpl->pagenavi( $paged, $items_count ); ?>
    </div>
</div>
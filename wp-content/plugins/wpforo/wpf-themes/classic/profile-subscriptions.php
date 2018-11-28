<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<div class="wpforo-sbn-content">
    <?php wpforo_subscription_tools(); ?>
    <?php if(empty($subscribes)) : ?><p class="wpf-p-error"> <?php wpforo_phrase('No subscriptions found for this member.') ?> </p><?php endif; ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php $bg = FALSE; foreach( $subscribes as $subscribe ) : ?>
            <?php 
                if( in_array($subscribe['type'], array('forum', 'forum-topic')) ){
                    $item = WPF()->forum->get_forum($subscribe['itemid']);
                    $item_url = WPF()->forum->get_forum_url($item['forumid']);
                }elseif($subscribe['type'] == 'topic'){
                    $item = WPF()->topic->get_topic($subscribe['itemid']);
                    $item_url = WPF()->topic->get_topic_url($item['topicid']);
                }elseif ( in_array($subscribe['type'], array('forums', 'forums-topics')) ){
                    $item = array('title' => wpforo_phrase('All ' . $subscribe['type'], false));
                    $item_url = '#';
                }
				if(empty($item)) continue;
            ?>
          <tr<?php echo ( $bg ? ' class="wpfbg-9"' : '' ) ?>>
            <td class="sbn-icon"><i class="fas fa-1x <?php echo ($subscribe['type'] == 'forum') ? 'fa-comments' : 'fa-file-alt' ; ?>"></i></td>
            <td class="sbn-title"><a href="<?php echo esc_url($item_url) ?>"><?php echo esc_html($item['title']) ?></a></td>
            <?php if($userid == WPF()->current_userid) : ?>
                <td class="sbn-action"><a href="<?php echo esc_url(WPF()->sbscrb->get_unsubscribe_link($subscribe['confirmkey'])) ?>"><?php wpforo_phrase('Unsubscribe'); ?></a></td>
            <?php else : ?>
                <td>&nbsp;</td>
            <?php endif ?>
          </tr>
        <?php $bg = ( $bg ? FALSE : TRUE ); endforeach ?>
   </table>
   <div class="sbn-foot">
        <?php WPF()->tpl->pagenavi( $paged, $items_count ); ?>
    </div>
</div>
	
  
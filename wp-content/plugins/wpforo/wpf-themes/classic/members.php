<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

    $fields = wpforo_search_fields();
?>
<h2 id="wpforo-title"><?php wpforo_phrase('Forum Members') ?></h2>
<div class="wpforo-members-wrap">
	<?php if( WPF()->perm->usergroup_can('vmem') ): ?>
	    <div class="wpf-table wpforo-members-search wpfbg-9">
	        <form action="<?php echo wpforo_home_url(WPF()->tpl->slugs['members']) ?>" method="get">
	        	<?php wpforo_make_hidden_fields_from_url( wpforo_home_url(WPF()->tpl->slugs['members']) ) ?>
                <?php wpforo_fields( $fields ); ?>
                <div class="wpf-tr">
                    <div class="wpf-td wpfw-1">
                        <div class="wpf-field wpf-field-type-submit">
                        	<a href="<?php echo wpforo_home_url(WPF()->tpl->slugs['members']) ?>"><input type="button" class="wpf-member-search wpfbg-7 wpfcl-1" value="<?php wpforo_phrase('Reset Search') ?>" /></a>
							<?php if( WPF()->member->options['search_type'] == 'filter' ): ?>
                                <a href="<?php echo wpforo_home_url(WPF()->tpl->slugs['members']) ?>"><input type="reset" class="wpf-member-search wpfbg-7 wpfcl-1" value="<?php wpforo_phrase('Reset Fields') ?>" /></a>
                            <?php endif; ?>
                            <input type="submit" class="wpf-member-search" name="_wpfms" value="<?php wpforo_phrase('Search') ?>"/>
                        </div>
                        <div class="wpf-field-cl"></div>
                    </div>
                    <div class="wpf-cl"></div>
                </div>
	        </form>
	     </div>
        <p>&nbsp;</p>
	    <div class="wpforo-members-content wpfbg-7">
	         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%; display:table;">
	            <tr class="wpfbg-3">
	                <?php if(  WPF()->perm->usergroup_can('va') && wpforo_feature('avatars') ): ?>
	                    <th class="wpf-members-avatar"><?php wpforo_phrase('Avatar') ?></th>
	                <?php endif; ?>
	                <th class="wpf-members-info1"><?php wpforo_phrase('Member information') ?></th>
	                <th class="wpf-members-regdate"><?php wpforo_phrase('Registered date') ?></th>
	            </tr>
	          
	            <?php if(!empty($members)) : ?>
	                
	                <?php $bg = FALSE; foreach($members as $member) : ?>
	                  
	                  <tr<?php echo ( $bg ? ' style="background:#F7F7F7"' : '' ) ?>>
	                    <?php if( WPF()->perm->usergroup_can('va') && wpforo_feature('avatars') ): ?>
	                        <td class="wpf-members-avatar"><?php echo WPF()->member->avatar($member); ?></td>
	                    <?php endif; ?>
	                    <td class="wpf-members-info">
                        	<span style="font-size:16px;"><?php WPF()->member->show_online_indicator($member['ID']) ?>&nbsp;</span>
                            <?php $member['profile_url'] = esc_url(WPF()->member->profile_url($member)) ?>
	                        <?php wpforo_member_link( $member, '', 50, ' wpf-member-name '); ?> <?php wpforo_member_nicename($member, '@'); ?>
	                        <?php do_action('wpforo_after_member_badge', $member) ?>
	                        <br />
	                        <?php $enabled_for_usergroup = ( isset(WPF()->member->options['rating_badge_ug'][$member['groupid']]) && WPF()->member->options['rating_badge_ug'][$member['groupid']] ) ? true : false ; ?>
	                        <span class="wpf-member-info wpfcl-1"> <i class="fas fa-users" title="<?php wpforo_phrase('Usergroup') ?>"></i>&nbsp; <?php wpforo_phrase($member['groupname']) ?> | <?php if( wpforo_feature('rating') && $enabled_for_usergroup ): ?><i class="fas fa-star" title="<?php wpforo_phrase('Rating') ?>"></i>&nbsp;<?php echo WPF()->member->rating_level( $member['posts'], FALSE ) ?>/10  |<?php endif; ?> <?php wpforo_phrase('Posts') ?>: <?php echo intval($member['posts']) ?></span>
	                    	| <div class="wpf-member-profile-buttons" style="display:inline-block;">
                                <?php WPF()->tpl->member_buttons($member) ?>
                            </div>
                        </td>
	                    <td class="wpf-members-regdate wpfcl-1"><?php wpforo_date($member['user_registered'], 'F j, Y') ?></td>
	                  </tr>
	                    
	                <?php $bg = ( $bg ? FALSE : TRUE ); endforeach; ?>
	              
	            <?php else : ?>
	                
	                <tr>
	                    <td colspan="3"><p class="wpf-p-error"> <?php wpforo_phrase('Members not found') ?> </p></td>
	                </tr>
	                
	            <?php endif ?>
	            
	         </table>
	    </div>
	    <div class="wpf-members-foot">
	        <?php WPF()->tpl->pagenavi( $paged, $items_count, FALSE ); ?>
	    </div>
	<?php else : ?>
		<p class="wpf-p-error"> <?php wpforo_phrase('You do not have permission to view this page') ?> </p>
	<?php endif; ?>
</div>
<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<div class="wpforo-profile-wrap">
	<?php if( !empty($user) && ( WPF()->current_userid == $user['userid'] || WPF()->perm->usergroup_can('vprf')) ) :
		$filtered_user = apply_filters('wpforo_profile_header_obj', $user);
		extract($filtered_user);
	    $rating_enabled = ( wpforo_feature('rating') && isset(WPF()->member->options['rating_badge_ug'][$groupid]) && WPF()->member->options['rating_badge_ug'][$groupid] ) ? true : false ;
		
	?>
                                
	    <div class="wpforo-profile-head-wrap">
            <?php $avatar_image_html = wpforo_user_avatar($filtered_user, 150, 'alt="' . esc_html($display_name) . '"', true);
            $avatar_image_url = wpforo_avatar_url($avatar_image_html);;
            $bg = ($avatar_image_url) ? "background-image:url('" . esc_url($avatar_image_url) . "');" : ''; ?>
            <div class="wpforo-profile-head-bg" style="<?php echo $bg ?>">
                <div class="wpfx"></div>
            </div>
            <div id="m_" class="wpforo-profile-head">
                <?php do_action( 'wpforo_profile_plugin_menu_action', $userid ); ?>
                <div class="h-header">
                	<div class="wpfy" <?php if( !$rating_enabled ) echo ' style="height:140px;" ' ?>></div>
                    <div class="wpf-profile-info-wrap">
                    
                        <div class="h-picture">
							<?php if( WPF()->perm->usergroup_can('va') && wpforo_feature('avatars') ): ?>
                                <div class="wpf-profile-img-wrap">
									<?php echo $avatar_image_html; ?>
                                </div>
                            <?php else: ''; endif; ?>
                            <div class="wpf-profile-data-wrap">
                                <div class="profile-display-name">
                                    <?php WPF()->member->show_online_indicator($userid) ?>
                                    <?php echo $display_name ? esc_html($display_name) : esc_html(urldecode($user_nicename)) ?>
                                    <div class="profile-stat-data-item"><?php wpforo_phrase('Group') ?>: <?php wpforo_phrase($groupname) ?></div>
                                	<div class="profile-stat-data-item"><?php wpforo_phrase('Joined') ?>: <?php wpforo_date($user_registered, 'Y/m/d') ?></div>
                                	<div class="profile-stat-data-item"><?php wpforo_member_title($filtered_user, true, wpforo_phrase('Title', false) . ': '); ?></div>
                                </div>
                            </div>
                            <div class="wpf-cl"></div>
						</div>
                    
                    <div class="h-header-info">
                        <div class="h-top">
                            <div class="profile-stat-data">
                                <?php do_action( 'wpforo_profile_data_item', WPF()->current_object ) ?>
                                <?php if( $rating_enabled ): ?>
                                    <div class="profile-rating-bar">
                                        <div class="profile-rating-bar-wrap" title="<?php wpforo_phrase('Member Rating') ?>">
                                            <?php $levels = WPF()->member->levels(); ?>
                                            <?php $rating_level = WPF()->member->rating_level( $posts, false );?>
                                            <?php for( $a=1; $a <= $rating_level; $a++ ): ?>
                                                <div class="rating-bar-cell" style="background-color:<?php echo esc_attr($filtered_user['stat']['color']); ?>;">
                                                    <i class="<?php echo WPF()->member->rating($a, 'icon') ?>"></i>
                                                </div>
                                            <?php endfor; ?>
                                            <?php for( $i = ($rating_level+1); $i <= (count($levels)-1); $i++ ): ?>
                                                <div class="wpfbg-7 rating-bar-cell" >
                                                    <i class="<?php echo WPF()->member->rating($i, 'icon') ?>"></i>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="wpf-profile-badge" title="<?php wpforo_phrase('Rating Badge') ?>" style="background-color:<?php echo esc_attr($filtered_user['stat']['color']); ?>;">
                                        <?php echo WPF()->member->rating_badge($rating_level, 'short'); ?>
                                    </div>
                                <?php endif; ?>
                                <?php do_action('wpforo_after_member_badge', $filtered_user); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wpf-clear"></div>
                </div>
                </div>
                <div class="h-footer">
                    <div class="h-bottom">
                        <?php WPF()->tpl->member_menu($userid) ?>
                        <div class="wpf-clear"></div>
                    </div>
                </div>
            </div>
        </div>
	    <div class="wpforo-profile-content">
	    	<?php WPF()->tpl->member_template() ?>
	    </div>
	<?php elseif( !empty($user) && !( WPF()->current_userid == $user['userid'] || WPF()->perm->usergroup_can('vprf')) ) : ?>
		<div class="wpforo-profile-content wpfbg-7">
			<div class="wpfbg-7 wpf-page-message-wrap">
				<div class="wpf-page-message-text">
					<?php wpforo_phrase('You do not have permission to view this page') ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="wpforo-profile-content wpfbg-7">
			<div class="wpfbg-7 wpf-page-message-wrap">
				<div class="wpf-page-message-text">
					<?php WPF()->tpl->member_error() ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
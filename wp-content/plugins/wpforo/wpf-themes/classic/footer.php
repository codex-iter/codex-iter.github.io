<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>


<?php do_action( 'wpforo_footer_hook' ) ?>

<!-- forum statistic -->
	<div class="wpf-clear"></div>

    <?php wpforo_share_buttons('bottom'); ?>

	<div id="wpforo-footer">
    	<?php do_action( 'wpforo_stat_bar_start', WPF() ); ?>
     	<?php if( wpforo_feature('footer-stat') ): ?>
            <div id="wpforo-stat-header">
                <i class="far fa-chart-bar"></i>
	            <?php if( WPF()->perm->usergroup_can('view_stat') ) : ?>
                    &nbsp; <span><?php wpforo_phrase('Forum Statistics') ?></span>
                <?php endif ?>
            </div>
            <div id="wpforo-stat-body">
                <?php $stat = WPF()->statistic();  ?>
                <div class="wpforo-stat-table">
                    <?php if( WPF()->perm->usergroup_can('view_stat') ) : ?>
                        <div class="wpf-row wpf-stat-data">
                            <div class="wpf-stat-item">
                                <i class="fas fa-comments"></i>
                                <span class="wpf-stat-value"><?php echo wpforo_print_number($stat['forums']) ?></span>
                                <span class="wpf-stat-label"><?php wpforo_phrase('Forums') ?></span>
                            </div>
                            <div class="wpf-stat-item">
                                <i class="fas fa-file-alt"></i>
                                <span class="wpf-stat-value"><?php echo wpforo_print_number($stat['topics']) ?></span>
                                <span class="wpf-stat-label"><?php wpforo_phrase('Topics') ?></span>
                            </div>
                            <div class="wpf-stat-item">
                                <i class="fas fa-reply fa-rotate-180"></i>
                                <span class="wpf-stat-value"><?php echo wpforo_print_number($stat['posts']) ?></span>
                                <span class="wpf-stat-label"><?php wpforo_phrase('Posts') ?></span>
                            </div>
                            <div class="wpf-stat-item">
                                <i class="far fa-lightbulb"></i>
                                <span class="wpf-stat-value"><?php echo wpforo_print_number($stat['online_members_count']) ?></span>
                                <span class="wpf-stat-label"><?php wpforo_phrase('Online') ?></span>
                            </div>
                            <div class="wpf-stat-item">
                                <i class="fas fa-user"></i>
                                <span class="wpf-stat-value"><?php echo wpforo_print_number($stat['members']) ?></span>
                                <span class="wpf-stat-label"><?php wpforo_phrase('Members') ?></span>
                            </div>
                        </div>
                    <?php endif ?>
                    <div class="wpf-row wpf-last-info">
                    	<?php if(isset($stat['last_post_title']) && $stat['last_post_title']): ?>
                        <p class="wpf-stat-other">
                            <?php if( isset($stat['posts']) && $stat['posts'] ): ?><span ><i class="fas fa-pencil-alt"></i> <?php wpforo_phrase('Latest Post') ?>: <a href="<?php echo esc_url($stat['last_post_url']) ?>"><?php echo esc_html($stat['last_post_title']) ?></a></span><?php endif; ?>
                            <span><i class="fas fa-user-plus"></i> <?php wpforo_phrase('Our newest member') ?>: <a href="<?php echo esc_url($stat['newest_member_profile_url']) ?>"><?php echo esc_html($stat['newest_member_dname']) ?></a></span>
                        	<?php if( isset($stat['posts']) && $stat['posts'] ): ?><span class="wpf-stat-recent-posts"><i class="fas fa-list-ul"></i> <a href="<?php echo esc_url(wpforo_home_url(WPF()->tpl->slugs['recent'])) ?>"><?php wpforo_phrase('Recent Posts') ?></a></span><?php endif; ?>
                            <?php if( isset($stat['posts']) && $stat['posts'] ): ?><span class="wpf-stat-unread-posts"><i class="fas fa-layer-group"></i> <a href="<?php echo esc_url(wpforo_home_url(WPF()->tpl->slugs['recent'] . '?view=unread' )) ?>"><?php wpforo_phrase('Unread Posts') ?></a></span><?php endif; ?>
                            <?php if( WPF()->post->options['tags'] ): ?><span class="wpf-stat-tags"><i class="fas fa-tag"></i> <a href="<?php echo esc_url(wpforo_home_url(WPF()->tpl->slugs['tags'])) ?>"><?php wpforo_phrase('Tags') ?></a></span><?php endif; ?>
                        </p>
                        <?php endif; ?>
                        <p class="wpf-forum-icons">
                            <span class="wpf-stat-label"><?php wpforo_phrase('Forum Icons') ?>:</span>
                            <span class="wpf-no-new"><i class="fas fa-comments wpfcl-0"></i> <?php wpforo_phrase('Forum contains no unread posts') ?></span>
                            <span class="wpf-new"><i class="fas fa-comments"></i> <?php wpforo_phrase('Forum contains unread posts') ?></span>
                            <span class="wpf-all-read"><a href="<?php echo wp_nonce_url( '?foro=allread' , 'wpforo_mark_all_read', 'foro_n' ); ?>" rel="nofollow"><i class="fas fa-check"></i> <?php wpforo_phrase('Mark all read') ?></a></span>
                        </p>
                        <p class="wpf-topic-icons">
                        	<span class="wpf-stat-label"><?php wpforo_phrase('Topic Icons') ?>:</span>
                            <span><i class="far fa-file wpfcl-2"></i> <?php wpforo_phrase('Not Replied') ?></span>
                            <span><i class="far fa-file-alt wpfcl-2"></i> <?php wpforo_phrase('Replied') ?></span>
                            <span><i class="fas fa-file-alt wpfcl-2"></i> <?php wpforo_phrase('Active') ?></span>
                            <span><i class="fas fa-file-alt wpfcl-5"></i> <?php wpforo_phrase('Hot') ?></span>
                            <span><i class="fas fa-thumbtack wpfcl-5"></i> <?php wpforo_phrase('Sticky') ?></span>
                            <span><i class="fas fa-exclamation-circle wpfcl-5"></i> <?php wpforo_phrase('Unapproved') ?></span>
                            <span><i class="fas fa-check-circle wpfcl-8"></i> <?php wpforo_phrase('Solved') ?></span>
                            <span><i class="fas fa-eye-slash wpfcl-1"></i> <?php wpforo_phrase('Private') ?></span>
                            <span><i class="fas fa-lock wpfcl-1"></i> <?php wpforo_phrase('Closed') ?></span>
                        </p>
                    </div>
                </div>
            </div>
		<?php endif; ?>
        <?php WPF()->tpl->copyright() ?>
        <?php do_action( 'wpforo_stat_bar_end'); ?>
  	</div>	<!-- wpforo-footer -->
  	
  	<?php do_action( 'wpforo_bottom_hook' ) ?>
    <?php wpforo_debug(); ?>
    
</div><!-- wpforo-wrap -->
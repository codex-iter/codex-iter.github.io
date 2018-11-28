<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<div id="wpforo-wrap" class="<?php do_action('wpforo_wrap_class'); ?>">

	<?php do_action( 'wpforo_top_hook' ); ?>
	
    <?php if( wpforo_feature('top-bar') ): ?>
        <div id="wpforo-menu" style="display:table; width:100%;">
            <?php do_action( 'wpforo_menu_bar_start' ); ?>
            <div class="wpf-left" style="display:table-cell">
                <?php if(WPF()->tpl->has_menu()): ?>
                    <span class="wpf-res-menu"><i class="fas fa-bars"></i></span>
                    <?php WPF()->tpl->nav_menu() ?>
                <?php endif; ?>
            </div>
            <div class="wpf-right wpf-search" style="display:table-cell; text-align:right; position:relative;">
                <?php if( wpforo_feature('top-bar-search') ): ?>
                    <form action="<?php echo wpforo_home_url() ?>" method="get">
                        <?php wpforo_make_hidden_fields_from_url( wpforo_home_url() ) ?>
                        <i class="fas fa-search"></i><input class="wpf-search-field" name="wpfs" type="text" value="" style="margin-right:10px;" />
                    </form>
                <?php endif; ?>
            </div>
            <?php do_action('wpforo_menu_bar_end'); ?>
        </div>
     <?php endif; ?>
    <div class="wpforo-subtop">
        <?php if( wpforo_feature('breadcrumb') ): ?>
            <?php WPF()->tpl->breadcrumb(WPF()->current_object) ?>
        <?php endif; ?>
        <?php wpforo_share_buttons('top'); ?>
        <div class="wpf-clear"></div>
    </div>
	<?php do_action( 'wpforo_header_hook' ); ?>
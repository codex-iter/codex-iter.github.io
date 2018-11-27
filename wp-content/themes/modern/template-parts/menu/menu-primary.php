<?php
/**
 * Primary menu template
 *
 * Accessibility markup applied (ARIA).
 *
 * @link  http://a11yproject.com/patterns/
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.0
 */





// Helper variables

	$is_mobile_nav_enabled = Modern_Library_Customize::get_theme_mod( 'navigation_mobile' );


?>

<div class="site-header-navigation"><div class="site-header-inner">

<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'modern' ); ?>">

	<?php if ( $is_mobile_nav_enabled ) : ?>
	<button id="menu-toggle" class="menu-toggle" aria-controls="menu-primary" aria-expanded="false"><?php echo esc_html_x( 'Menu', 'Mobile navigation toggle button title.', 'modern' ); ?></button>

	<?php endif; ?>
	<div id="site-navigation-container" class="main-navigation-container">
		<?php wp_nav_menu( Modern_Menu::primary_menu_args( $is_mobile_nav_enabled ) ); ?>
	</div>

</nav>

</div></div>

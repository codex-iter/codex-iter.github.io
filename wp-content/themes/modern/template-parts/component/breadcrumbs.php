<?php
/**
 * Breadcrumbs content
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Requirements check

	if (
		! function_exists( 'bcn_display' )
		|| apply_filters( 'wmhook_modern_breadcrumb_navxt_disabled', false )
	) {
		return;
	}


?>

<?php do_action( 'wmhook_modern_breadcrumb_navxt_before' ); ?>

<div class="breadcrumbs-container">
	<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumbs navigation', 'modern' ); ?>">

		<?php bcn_display(); ?>

	</nav>
</div>

<?php do_action( 'wmhook_modern_breadcrumb_navxt_after' ); ?>

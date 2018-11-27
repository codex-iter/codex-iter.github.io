<?php
/**
 * The template for displaying portfolio items
 *
 * @package Audioman
 */
?>

<?php
$enable = get_theme_mod( 'audioman_portfolio_option', 'disabled' );

if ( ! audioman_check_section( $enable ) ) {
	// Bail if portfolio section is disabled.
	return;
}
	$title     = get_option( 'jetpack_portfolio_title', esc_html__( 'Projects', 'audioman' ) );
	$sub_title = get_option( 'jetpack_portfolio_content' );

$layout = 'layout-five';

$classes[] = $layout;
$classes[] = 'jetpack-portfolio';
$classes[] = 'section';

?>

<div id="portfolio-content-section" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
		<?php if ( '' != $title || $sub_title ) : ?>
			<div class="section-heading-wrapper portfolio-section-headline">
				<?php if ( '' != $title ) : ?>
					<div class="section-title-wrapper">
						<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
					</div><!-- .section-title-wrapper -->
				<?php endif; ?>

				<?php if ( $sub_title ) : ?>
					<div class="taxonomy-description-wrapper">
						<p class="section-subtitle">
							<?php echo wp_kses_post( $sub_title ); ?>
						</p>
					</div><!-- .taxonomy-description-wrapper -->
				<?php endif; ?>
			</div><!-- .section-heading-wrapper -->
		<?php endif; ?>

		<div class="section-content-wrapper portfolio-content-wrapper <?php echo esc_attr( $layout ); ?>">
			<?php
				get_template_part( 'template-parts/portfolio/post-types', 'portfolio' );
			?>
		</div><!-- .section-content-wrap -->
	</div><!-- .wrapper -->
</div><!-- #portfolio-section -->

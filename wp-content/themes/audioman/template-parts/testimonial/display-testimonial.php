<?php
/**
 * The template for displaying testimonial items
 *
 * @package Audioman
 */
?>

<?php
$enable = get_theme_mod( 'audioman_testimonial_option', 'disabled' );

if ( ! audioman_check_section( $enable ) ) {
	// Bail if featured content is disabled
	return;
}

$type = 'jetpack-testimonial';

	$jetpack_defaults = array(
		'page-title' => esc_html__( 'In The News', 'audioman' ),
	);

	// Get Jetpack options for testimonial.
	$jetpack_options = get_theme_mod( 'jetpack_testimonials', $jetpack_defaults );

	$headline = isset( $jetpack_options['page-title'] ) ? $jetpack_options['page-title'] : esc_html__( 'Testimonials', 'audioman' );

	$subheadline = isset( $jetpack_options['page-content'] ) ? $jetpack_options['page-content'] : '';


$layouts = 1;

$classes[] = 'section testimonial-content-section';

$classes[] ='layout-one';

if ( ! $headline && ! $subheadline ) {
	$classes[] = 'no-headline';
}
?>

<div id="testimonial-content-section" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
		<?php if ( $headline || $subheadline ) : ?>
		<div class="section-heading-wrapper">
			<?php if ( $headline ) : ?>
				<div class="section-title-wrapper">
					<h2 class="section-title"><?php echo wp_kses_post( $headline ); ?></h2>
				</div><!-- .section-title-wrapper -->
			<?php endif; ?>

			<?php if ( $subheadline ) : ?>
				<div class="taxonomy-description-wrapper">
					<p class="section-subtitle"><?php echo wp_kses_post( $subheadline ); ?></p>
				</div><!-- .taxonomy-description-wrapper -->
			<?php endif; ?>
		</div><!-- .section-heading-wrap -->
		<?php endif; ?>

		<div class="section-content-wrapper testimonial-content-wrapper">
		
			<div class="controller">
				<!-- prev link -->
				<button id="testimonial-slider-prev" class="cycle-prev" aria-label="<?php esc_attr_e( 'Previous', 'audioman' ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Previous Slide', 'audioman' ); ?></span></button>

				<!-- empty element for pager links -->
				<div id="testimonial-slider-pager" class="cycle-pager"></div>


				<!-- next link -->
				<button id="testimonial-slider-next" class="cycle-next" aria-label="<?php esc_attr_e( 'Next', 'audioman' ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Next Slide', 'audioman' ); ?></span></button>
				
			</div><!-- #controller-->

			<div class="cycle-slideshow"
				data-cycle-log            = "false"
				data-cycle-fx             = "scrollHorz"
				data-cycle-timeout        = "4000"
				data-cycle-pause-on-hover = "true"
				data-cycle-swipe          = "true"
				data-cycle-auto-height    = container
				data-cycle-loader         = false
				data-cycle-slides         = ".testimonial_slider_wrap"
				data-cycle-prev           = .cycle-prev
				data-cycle-next           = .cycle-next
				data-cycle-pager          = "#testimonial-slider-pager"
				data-cycle-prev           = "#testimonial-slider-prev"
				data-cycle-next           = "#testimonial-slider-next"
				>

				<div class="testimonial_slider_wrap">

			<?php
				get_template_part( 'template-parts/testimonial/post-types', 'testimonial' );
			?>

				</div><!-- .testimonial_slider_wrap -->
			</div><!-- .cycle-slideshow -->
		</div><!-- .section-content-wrap -->
	</div><!-- .wrapper -->
</div><!-- .testimonial-content-section -->

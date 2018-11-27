<?php
/**
 * The template used for displaying hero content
 *
 * @package Audioman
 */
?>

<?php

  $id = get_theme_mod( 'audioman_hero_content' ); 
	$args['page_id'] = absint( $id );

$img_alignment  = 'content-align-right';
$text_alignment = 'text-align-center';
// If $args is empty return false
if ( empty( $args ) ) {
	return;
}

// Create a new WP_Query using the argument previously created
$hero_query = new WP_Query( $args );
if ( $hero_query->have_posts() ) :
	while ( $hero_query->have_posts() ) :
		$hero_query->the_post();
		?>
		<div id="hero-section" class="hero-section section <?php echo esc_attr( $img_alignment . ' ' . $text_alignment ); ?>">
			<div class="wrapper">
				<div class="section-content-wrapper hero-content-wrapper">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="hentry-inner">
							<?php if ( has_post_thumbnail() ) :
								$thumb = get_the_post_thumbnail_url( get_the_ID(), 'audioman-hero' );
								?>
								<div class="post-thumbnail" style="background-image: url( '<?php echo esc_url( $thumb ); ?>' )">
									<a class="cover-link" href="<?php the_permalink(); ?>"></a>
								</div><!-- .post-thumbnail --> 
							<div class="entry-container">
						<?php else : ?>
							<div class="entry-container full-width">
						<?php endif; ?>
							<?php
							if ( ! get_theme_mod( 'audioman_disable_hero_content_title' ) ) : ?>
								<header class="entry-header">
									<h2 class="entry-title section-title">
										<?php the_title(); ?>
									</h2>
								</header><!-- .entry-header -->
							<?php endif; ?>

							<div class="entry-content">
								<?php

									the_content();

									wp_link_pages( array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'audioman' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span class="page-number">',
										'link_after'  => '</span>',
										'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'audioman' ) . ' </span>%',
										'separator'   => '<span class="screen-reader-text">, </span>',
									) );
								?>
							</div><!-- .entry-content -->

							<?php if ( get_edit_post_link() ) : ?>
								<footer class="entry-footer">
									<div class="entry-meta">
										<?php
											edit_post_link(
												sprintf(
													/* translators: %s: Name of current post */
													esc_html__( 'Edit %s', 'audioman' ),
													the_title( '<span class="screen-reader-text">"', '"</span>', false )
												),
												'<span class="edit-link">',
												'</span>'
											);
										?>
									</div>	<!-- .entry-meta -->
								</footer><!-- .entry-footer -->
							<?php endif; ?>
						</div><!-- .hentry-inner -->
					</article>
				</div><!-- .section-content-wrapper -->
			</div><!-- .wrapper -->
		</div><!-- .section -->
	<?php
	endwhile;

	wp_reset_postdata();
endif;

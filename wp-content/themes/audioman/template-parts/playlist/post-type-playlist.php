<?php
/**
 * The template used for displaying playlist
 *
 * @package Audioman
 */
?>

<?php
$id = get_theme_mod( 'audioman_playlist' ); 
	$args['page_id'] = absint( $id );
	
$img_alig      = 'content-align-right';
$section_title = get_theme_mod( 'audioman_playlist_section_title', esc_html__( 'New Releases', 'audioman' ) );

// If $args is empty return false
if ( empty( $args ) ) {
	return;
}

// Create a new WP_Query using the argument previously created
$playlist_query = new WP_Query( $args );
if ( $playlist_query->have_posts() ) :
	while ( $playlist_query->have_posts() ) :
		$playlist_query->the_post();

		$thumb = get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' );
		?>
		<div id="playlist-section" class="section <?php echo esc_attr( $img_alig ); ?>">
			<div class="wrapper">
				<?php if ( $section_title ) : ?>
				<div class="section-heading-wrapper playlist-headline">
					<div class="section-title-wrapper">
						<h2 class="section-title"><?php echo wp_kses_post( $section_title ); ?></h2>
					</div><!-- .page-title-wrapper -->
				</div>
				<?php endif; ?>

				<div class="section-content-wrapper playlist-content-wrapper <?php echo esc_attr( $img_alig ); ?>">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="hentry-inner">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="post-thumbnail" style="background-image: url( '<?php echo esc_url( $thumb ); ?>' )">
									<a class="cover-link" href="<?php the_permalink(); ?>"></a>
								</div><!-- .post-thumbnail -->
								<div class="entry-container">
							<?php else : ?>
								<div class="entry-container full-width">
							<?php endif; ?>
								<header class="entry-header">
									<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
								</header><!-- .entry-header -->
								<div class="entry-content">
									<?php the_content(); ?>
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
										</div>
									</footer><!-- .entry-footer -->
								<?php endif; ?>
							</div><!-- .entry-container -->
						</div><!-- .hentry-inner -->
					</article><!-- #post-## -->
				</div><!-- .wrapper -->
			</div><!-- .section-content -->
		</div><!-- #playlist-section -->
	<?php
	endwhile;

	wp_reset_postdata();
endif;

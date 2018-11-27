<?php
/**
 * The template used for displaying projects on index view
 *
 * @package Audioman
 */
?>
<article id="portfolio-post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="hentry-inner">
		<div class="portfolio-thumbnail post-thumbnail">
			<a class="post-thumbnail" href="<?php the_permalink(); ?>">
				<?php
					// Output the featured image.
					if ( has_post_thumbnail() ) {
						the_post_thumbnail();
					} else {
						echo '<img src="' .  trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/no-thumb-640x640.jpg"/>';
					}
				?>
			</a>
		</div><!-- .portfolio-content-image -->

		<div class="entry-container caption">
			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
				<div class="entry-meta">
					<?php audioman_posted_on(); ?>
				</div>
			</header>
		</div><!-- .entry-container -->
	</div><!-- .hentry-inner -->
</article>

<?php
/**
 * The template used for displaying testimonial on front page
 *
 * @package Audioman
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="hentry-inner">
		<div class="entry-container">
			<?php
			$show_content = 'full-content'; ?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="post-thumbnail testimonial-thumbnail">
					<?php the_post_thumbnail( 'audioman-testimonial' ); ?>
				</div>
			<?php endif; ?>

			<?php $position = get_post_meta( get_the_id(), 'ect_testimonial_position', true ); ?>

			<?php if (  $position ) : ?>
				<header class="entry-header">
					<?php
						the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
						<p class="entry-meta"><span class="position"><?php echo esc_html( $position ); ?></span></p>
				</header>
			<?php endif;?>
		</div><!-- .entry-container -->
	</div><!-- .hentry-inner -->
</article>

<?php
/**
 * Front page template section loop
 *
 * We are using generic, global hook names in this file, but passing
 * a file name as a hook context/scope parameter you can check for.
 *
 * This file is being included (using PHP statement `include`) so we
 * can pass required variables below.
 *
 * @see  `template-parts/loop/loop-front-blog.php`
 * @see  `template-parts/loop/loop-front-portfolio.php`
 * @see  `template-parts/loop/loop-front-testimonials.php`
 *
 * @link  http://php.net/manual/en/function.include.php
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Requirements check

	if (
		! isset( $post_type )
		|| ! isset( $context )
		|| ! isset( $query )
		|| ! is_callable( array( $query, 'have_posts' ) )
		|| ! $query->have_posts()
	) {
		return;
	}


?>

<section class="front-page-section front-page-section-type-<?php echo esc_attr( $post_type ); ?>">
	<div class="front-page-section-inner">

		<?php do_action( 'wmhook_modern_postslist_before', $context ); ?>

		<div class="posts posts-list">

			<?php

			do_action( 'tha_content_while_before', $context );

			while ( $query->have_posts() ) : $query->the_post();

				get_template_part(
					'template-parts/content/content',
					apply_filters( 'wmhook_modern_loop_content_type', get_post_format(), $context )
				);

			endwhile;

			do_action( 'tha_content_while_after', $context );

			?>

		</div>

		<?php do_action( 'wmhook_modern_postslist_after', $context ); ?>

	</div>
</section>

<?php

wp_reset_postdata();

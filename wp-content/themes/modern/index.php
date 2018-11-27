<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link  https://codex.wordpress.org/Template_Hierarchy
 * @uses  `wmhook_modern_title_primary_disable` global hook to disable `#primary` section H1
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  2.2.1
 */





get_header();

	if ( have_posts() ) :

		if ( is_home() && ! is_front_page() && ! (bool) apply_filters( 'wmhook_modern_title_primary_disable', false ) ) :
		// Blog front page

			?>

			<header class="page-header">
				<h1 class="page-title"><?php single_post_title(); ?></h1>
				<?php
				$page_for_posts = absint( get_option( 'page_for_posts' ) );

				if ( ! Modern_Post::is_paged() && has_excerpt( $page_for_posts ) ) :
					?>
					<div class="archive-description">
						<?php echo get_the_excerpt( $page_for_posts ); ?>
					</div>
					<?php
				endif;
				?>
			</header>

			<?php

		endif;

		get_template_part( 'template-parts/loop/loop', 'index' );

	else :

		get_template_part( 'template-parts/content/content', 'none' );

	endif;

get_footer();

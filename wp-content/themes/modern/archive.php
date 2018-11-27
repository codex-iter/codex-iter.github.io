<?php
/**
 * The template for displaying archive pages
 *
 * @link  https://codex.wordpress.org/Template_Hierarchy
 * @uses  `wmhook_modern_title_primary_disable` global hook to disable `#primary` section H1
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  2.0.0
 */





get_header();

	if ( have_posts() ) :

		if ( ! (bool) apply_filters( 'wmhook_modern_title_primary_disable', false ) ) :

			?>

			<header class="page-header">
				<?php

				$paged_suffix = Modern_Library::get_the_paginated_suffix( 'small' );

				the_archive_title( '<h1 class="page-title">', $paged_suffix . '</h1>' );

				if ( empty( $paged_suffix ) ) {
					the_archive_description( '<div class="archive-description">', '</div>' );
				}

				?>
			</header>

			<?php

		endif;

		get_template_part( 'template-parts/loop/loop', 'archive' );

	else :

		get_template_part( 'template-parts/content/content', 'none' );

	endif;

get_footer();

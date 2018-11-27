<?php
/**
 * The template for displaying the Slider
 *
 * @package Audioman
 */

if ( ! function_exists( 'audioman_featured_slider' ) ) :
	/**
	 * Add slider.
	 *
	 * @uses action hook audioman_before_content.
	 *
	 * @since Audioman 1.0
	 */
	function audioman_featured_slider() {
		$enable_slider = get_theme_mod( 'audioman_slider_option', 'disabled' );

		if ( audioman_check_section( $enable_slider ) ) {
			$output = '
				<div id="feature-slider-section" class="section">
					<div class="wrapper">
						<div class="cycle-slideshow"
							data-cycle-log="false"
							data-cycle-pause-on-hover="true"
							data-cycle-swipe="true"
							data-cycle-auto-height=container
							data-cycle-fx="fade"
							data-cycle-speed="1000"
							data-cycle-timeout="4000"
							data-cycle-loader=false
							data-cycle-slides="> article"
							>

							<!-- prev/next links -->
							<button class="cycle-prev" aria-label="Previous"><span class="screen-reader-text">' . esc_html__( 'Previous Slide', 'audioman' ) . '</span>' . '</button>
							<button class="cycle-next" aria-label="Next"><span class="screen-reader-text">' . esc_html__( 'Next Slide', 'audioman' ) . '</span>' . '</button>


							<!-- empty element for pager links -->
							<div class="cycle-pager"></div>';
							// Select Slider
				$output .= audioman_post_page_category_slider();
			$output .= '
						</div><!-- .cycle-slideshow -->
					</div><!-- .wrapper -->
				</div><!-- #feature-slider -->';

			echo $output;
		} // End if().
	}
	endif;
add_action( 'audioman_slider', 'audioman_featured_slider', 10 );


if ( ! function_exists( 'audioman_post_page_category_slider' ) ) :
	/**
	 * This function to display featured posts/page/category slider
	 *
	 * @param $options: audioman_theme_options from customizer
	 *
	 * @since Audioman 1.0
	 */
	function audioman_post_page_category_slider() {
		$quantity     = get_theme_mod( 'audioman_slider_number', 4 );
		$no_of_post   = 0; // for number of posts
		$post_list    = array();// list of valid post/page ids
		$output       = '';

		$args = array(
			'post_type'           => 'any',
			'orderby'             => 'post__in',
			'ignore_sticky_posts' => 1, // ignore sticky posts
		);

			for ( $i = 1; $i <= $quantity; $i++ ) {
				$post_id = '';

				$post_id = get_theme_mod( 'audioman_slider_page_' . $i );

				if ( $post_id && '' !== $post_id ) {
					$post_list = array_merge( $post_list, array( $post_id ) );

					$no_of_post++;
				}
			}

			$args['post__in'] = $post_list;

		if ( ! $no_of_post ) {
			return;
		}

		$args['posts_per_page'] = $no_of_post;

		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) :
			$loop->the_post();

			$title_attribute = the_title_attribute( 'echo=0' );

			if ( 0 === $loop->current_post ) {
				$classes = 'post post-' . esc_attr( get_the_ID() ) . ' hentry slides displayblock';
			} else {
				$classes = 'post post-' . esc_attr( get_the_ID() ) . ' hentry slides displaynone';
			}

			// Default value if there is no featurd image or first image.
			$image_url =  trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/no-thumb-1920x954.jpg';

			if ( has_post_thumbnail() ) {
				$image_url = get_the_post_thumbnail_url( get_the_ID(), 'audioman-slider' );
			} else {
				// Get the first image in page, returns false if there is no image.
				$first_image_url = audioman_get_first_image( get_the_ID(), 'audioman-slider', '', true );

				// Set value of image as first image if there is an image present in the page.
				if ( $first_image_url ) {
					$image_url = $first_image_url;
				}
			}

			$output .= '
			<article class="' . $classes . '">';
				$output .= '
				<div class="slider-image-wrapper">
					<a href="' . esc_url( get_permalink() ) . '" title="' . $title_attribute . '">
							<img src="' . esc_url( $image_url ) . '" class="wp-post-image" alt="' . $title_attribute . '">
						</a>
				</div><!-- .slider-image-wrapper -->

				<div class="slider-content-wrapper">
					<div class="entry-container">
						<header class="entry-header">';
							$output .= '<h2 class="entry-title">
								<a title="' . $title_attribute . '" href="' . esc_url( get_permalink() ) . '">' . the_title( '<span>','</span>', false ) . '</a>
							</h2>';
							$output .='
						</header>
							';
							$excerpt = get_the_excerpt();

							$output .= '<div class="entry-summary"><p>' . $excerpt . '</p></div><!-- .entry-summary -->';
						$output .= '
					</div><!-- .entry-container -->
				</div><!-- .slider-content-wrapper -->
			</article><!-- .slides -->';
		endwhile;

		wp_reset_postdata();

		return $output;
	}
endif; // audioman_post_page_category_slider.
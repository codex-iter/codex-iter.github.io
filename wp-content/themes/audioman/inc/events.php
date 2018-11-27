<?php
/**
 * The template for displaying the Events
 *
 * @package Audioman
 */


if ( ! function_exists( 'audioman_events_display' ) ) :
	/**
	* Add Events
	*
	* @uses action hook audioman_before_content.
	*
	* @since Audioman 1.0
	*/
	function audioman_events_display() {
		$enable = get_theme_mod( 'audioman_events_option', 'homepage' );

		if ( audioman_check_section( $enable ) ) {
			$title          = get_theme_mod( 'audioman_events_headline', esc_html( 'Upcoming Events', 'audioman' ) );
			$sub_title      = get_theme_mod( 'audioman_events_subheadline', esc_html( 'Discover our newest albums and singles', 'audioman' ) );
			$content_select = get_theme_mod( 'audioman_events_type', 'demo' );
			$layout         = get_theme_mod( 'audioman_events_layout', 'layout-four' );
			$background = get_theme_mod( 'audioman_events_bg_image', trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/events-section-bg.jpg' );

			$foreground = get_theme_mod( 'audioman_events_fg_image', trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/events-section-fg.png' );
			$classes[] = $layout;
			$classes[] = $content_select;

			if ( $background ) {
				$classes[] = 'has-background-image';
			}

			if ( $foreground ) {
				$classes[] = 'has-foreground-image';
			}

			echo '<!-- refreshing cache -->';

			//$classes = $content_select . ' ' . $classes;

			$output ='
				<div id="events-section" class="section ' . esc_attr( implode( ' ', $classes ) ) . '">
					<div class="wrapper">';
						if ( $title || $sub_title ) {
							$output .='<div class="section-heading-wrapper events-section-headline">';

							if ( '' !== $title ) {
								$output .='<div class="section-title-wrapper"><h2 class="section-title">' . wp_kses_post( $title ) . '</h2></div>';
							}

							if ( $sub_title )  {
								$output .='<div class="taxonomy-description-wrapper"><p class="section-subtitle">' . wp_kses_post( $sub_title ) . '</p></div>';
							}

							$output .='</div><!-- .section-heading-wrap -->';
						}

						$output .='
						<div class="events-content-wrapper section-content-wrapper ' . esc_attr( implode( ' ', $classes ) ) . '">';
						//$classes = $content_select . ' ' . $layout;
							// Select content
							if ( 'demo' === $content_select ) {
								$output .= audioman_demo_events();
							} elseif ( 'post' === $content_select || 'page' === $content_select || 'category' === $content_select ) {
								$output .= audioman_post_page_category_events();
							} elseif ( 'custom' === $content_select ) {
								$output .= audioman_custom_events();
							}

			$target = get_theme_mod( 'audioman_events_target' ) ? '_blank': '_self';
			$link   = get_theme_mod( 'audioman_events_link', '#' );
			$text   = get_theme_mod( 'audioman_events_text', esc_html__( 'View More', 'audioman' ) );
			$output .='</div><!-- .section-content-wrap -->';

			if ( $text ) {
				$output .= '
				<p class="view-more">
					<a class="button" target="' . $target . '" href="' . esc_url( $link ) . '">' . esc_html( $text ) . '</a>
				</p>';
			}
					$output .='</div><!-- .wrapper -->
				</div><!-- #events-section -->';

			echo $output;
		}
	}
endif;

if ( ! function_exists( 'audioman_demo_events' ) ) :
	/**
	 * Display Demo Events
	 *
	 * @since Audioman 1.0
	 *
	 */
	function audioman_demo_events() {
		return '
		<article id="events-post-1"  class="event-list-item hentry">
			<div class="entry-container">
				<div class="entry-meta">
					<span class="posted-on">
						<a href="#">
							<time class="entry-date">
								<span class="date-week-day">Oct</span>
								<span class="date-month">07</span>
							</time>
						</a>
					</span>
				</div><!-- .entry-meta -->

				<div class="event-list-description">
					<div class="event-title">
						<h2 class="entry-title">
							<a href="#">Voice From The Stone</a>
						</h2>
					</div>

					<div class="entry-summary">
						<p>Nawalparasi, Nepal</p>
					</div>
				</div>

				<p href="#" class="more-link"><a class="readmore">Buy Tickets</a></p>
			</div>
		</article>

		<article id="events-post-2" class="event-list-item hentry">
			<div class="entry-container">
				<div class="entry-meta">
					<span class="posted-on">
						<a href="#">
							<time class="entry-date">
								<span class="date-week-day">Oct</span>
								<span class="date-month">14</span>
							</time>
						</a>
					</span>
				</div><!-- .entry-meta -->

				<div class="event-list-description">
					<div class="event-title">
						<h2 class="entry-title">
							<a href="#">Under The Skin</a>
						</h2>
					</div>

					<div class="entry-summary">
						<p>Kumaripati, Lalitpur</p>
					</div>
				</div>

				<p href="#" class="more-link"><a class="readmore">Buy Tickets</a></p>
			</div>
		</article>

		<article id="events-post-3" class="event-list-item hentry">
			<div class="entry-container">
				<div class="entry-meta">
					<span class="posted-on">
						<a href="#">
							<time class="entry-date">
								<span class="date-week-day">Oct</span>
								<span class="date-month">23</span>
							</time>
						</a>
					</span>
				</div><!-- .entry-meta -->

				<div class="event-list-description">
					<div class="event-title">
						<h2 class="entry-title">
							<a href="#">All Nepal Tour</a>
						</h2>
					</div>

					<div class="entry-summary">
						<p>Nawalparasi, Nepal</p>
					</div>
				</div>

				<p href="#" class="more-link"><a class="readmore">Buy Tickets</a></p>
			</div>
		</article>

		<article id="events-post-4" class="event-list-item hentry">
			<div class="entry-container">
				<div class="entry-meta">
					<span class="posted-on">
						<a href="#">
							<time class="entry-date">
								<span class="date-week-day">Oct</span>
								<span class="date-month">29</span>
							</time>
						</a>
					</span>
				</div><!-- .entry-meta -->

				<div class="event-list-description">
					<div class="event-title">
						<h2 class="entry-title">
							<a href="#">Urban Folk</a>
						</h2>
					</div>

					<div class="entry-summary">
						Lollapalooza Brasil
					</div>
				</div>

				<p href="#" class="more-link"><a class="readmore">Buy Tickets</a></p>
			</div>
		</article>';
	}
endif; // audioman_demo_events


if ( ! function_exists( 'audioman_post_page_category_events' ) ) :
	/**
	 * Display Page/Post/Category Events
	 *
	 * @since Audioman 1.0
	 */
	function audioman_post_page_category_events() {
		global $post;

		$quantity   = get_theme_mod( 'audioman_events_number', 4 );
		$no_of_post = 0; // for number of posts
		$post_list  = array();// list of valid post/page ids
		$type       = get_theme_mod( 'audioman_events_type', 'demo' );
		$output     = '';

		$args = array(
			'post_type'           => 'any',
			'orderby'             => 'post__in',
			'ignore_sticky_posts' => 1 // ignore sticky posts
		);

		//Get valid number of posts
		if ( 'post' == $type || 'page' == $type  ) {
			for( $i = 1; $i <= $quantity; $i++ ){
				$post_id = '';

				if ( 'post' == $type ) {
					$post_id = get_theme_mod( 'audioman_events_post_' . $i );
				} elseif ( 'page' == $type ) {
					$post_id = get_theme_mod( 'audioman_events_page_' . $i ) ;
				}

				if ( $post_id ) {
					if ( class_exists( 'Polylang' ) ) {
						$post_id = pll_get_post( $post_id, pll_current_language() );
					}

					$post_list = array_merge( $post_list, array( $post_id ) );

					$no_of_post++;
				}
			}

			$args['post__in'] = $post_list;
		} elseif ( 'category' == $type ) {
			$no_of_post = $quantity;

			if ( get_theme_mod( 'audioman_events_select_category' ) ) {
				$args['category__in'] = (array) get_theme_mod( 'audioman_events_select_category' );
			}

			$args['post_type'] = 'post';
		}

		if ( 0 == $no_of_post ) {
			return;
		}

		$args['posts_per_page'] = $no_of_post;

		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) {
			$loop->the_post();

			$title_attribute = the_title_attribute( 'echo=0' );

				$output .= '
				<article id="event-post-' . esc_attr( $loop->current_post + 1 ) . '" class="event-list-item post hentry post">
					<div class="entry-container">';

				if ( ! get_theme_mod( 'audioman_events_hide_date' ) ) {
					$event_date_day   = get_the_date( 'j' );
					$event_date_month = get_the_date( 'M' );
					$event_date_day_meta = get_post_meta( $post->ID, 'audioman-event-date-day', true );
					$event_date_month_meta = get_post_meta( $post->ID, 'audioman-event-date-month', true );

					if ( '' !== $event_date_day_meta ) {
						$event_date_day = $event_date_day_meta;
					}

					if ( '' !== $event_date_month_meta ) {
						$event_date_month = $event_date_month_meta;
					}

					$output .= '<div class="entry-meta"><span class="posted-on"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><span class="date-month">' . esc_html( $event_date_day ) . '</span>' . '<span class="date-week-day">' . '</span>' . esc_html( $event_date_month ) . '</span>' . '</a></span></div>';
				}

				$output .= '<div class="event-list-description">';

				if ( get_theme_mod( 'audioman_events_enable_title' ) ) {
					$output .= '
					<div class="event-title">
						<h2 class="entry-title">
							' . the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a>', false ) . '
						</h2>
					</div>';
				}

				$content = apply_filters( 'the_content', get_the_content() );
				$content = str_replace( ']]>', ']]&gt;', $content );

				$output .= '<div class="entry-summary">' . wp_kses_post( $content ) . '</div><!-- .entry-summary -->';

				$output .= '</div><!-- .event-list-description -->';

				$text = get_theme_mod( 'audioman_events_individual_text_' . absint( $loop->current_post + 1 )  );

				$output .= '
						<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="more-link"><span class="more-button">' . esc_html( $text ) . '</span></a>
					</div><!-- .entry-container -->
				</article><!-- .event-post-' . esc_attr( $loop->current_post + 1 ) . ' -->';
			} //endwhile

		wp_reset_postdata();

		return $output;
	}
endif; // audioman_post_page_category_events


if ( ! function_exists( 'audioman_custom_events' ) ) :
	/**
	 * Display Custom Events
	 *
	 * @since Audioman 1.0
	 */
	function audioman_custom_events() {
		$quantity = get_theme_mod( 'audioman_events_number', 4 );
		$output   = '';

		for ( $i = 1; $i <= $quantity; $i++ ) {
			$target = get_theme_mod( 'audioman_events_target_' . $i ) ? '_blank' : '_self';

			$link = get_theme_mod( 'audioman_events_link_' . $i, '#' );

			//support qTranslate plugin
			if ( function_exists( 'qtrans_convertURL' ) ) {
				$link = qtrans_convertURL( $link );
			}

			$title = get_theme_mod( 'audioman_events_title_' . $i );

			if ( class_exists( 'Polylang' ) ) {
				$title = pll__( esc_attr( $title ) );
			}

			$date_day = get_theme_mod( 'audioman_events_date_day_' . $i );

			$date_month = get_theme_mod( 'audioman_events_date_month_' . $i );

			if ( $date_month ) {
				// Convert 1 to Jan, 2 to Feb and so on
				$date_month = date( 'M', mktime(0, 0, 0, $date_month, 10 ) );
			}

			$output .= '
				<article id="event-post-' . esc_html( $i ) . '" class="event-list-item post hentry image">
					<div class="entry-container">';

					if ( $date_day || $date_month ) {
						$output .= '<div class="entry-meta"><span class="posted-on"><a target="' . $target . '" href="' . esc_url( $link ) . '" rel="bookmark"><time class="entry-date"><span class="date-month">' . esc_html( $date_day ) . '</span>' . esc_html( $date_month ) . '</time></a></span></div>';
					}

					$content = get_theme_mod( 'audioman_events_content_' . $i );

					if ( $title || $content ) {
						$output .= '<div class="event-list-description">';
					}

					if ( $title ) {
						$output .= '
								<div class="event-title">
									<h2 class="entry-title">
										' . wp_kses_post( $title ) . '
									</h2>
								</div>';
					}


					if ( $content ) {
						$output .= '<div class="entry-summary"><p>' . $content . '</p></div><!-- .entry-summary -->';
					}

					if ( $title || $content ) {
						$output .= '</div><!-- .event-list-description -->';
					}

					$text = get_theme_mod( 'audioman_events_individual_text_' . $i );

					if ( $text ) {
						$output .= '
							<a target="' . $target . '" href="' . esc_url( $link ) . '" rel="bookmark" class="more-link"><span class="more-button">' . esc_html( $text ) . '</span></a>';
					}

				$output .= '
					</div><!-- .entry-container -->
				</article><!-- .event-post-' . esc_attr( $i ) . ' -->';
		}
		return $output;
	}
endif; //audioman_custom_events

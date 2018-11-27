<?php
/**
 * Prints list of `jetpack-portfolio-type` taxonomy terms links
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Requirements check

	$post_type = 'jetpack-portfolio';

	if (
		! (
			$post_type === get_post_type()
			|| is_page_template( 'page-template/_front.php' )
		)
		|| is_home()
		|| is_search()
	) {
		return;
	}


// Helper variables

	$taxonomy = 'jetpack-portfolio-type';
	$terms    = get_terms( $taxonomy, (array) apply_filters( 'wmhook_modern_list_terms_args', array(), $taxonomy ) );


if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) :

?>

<ul class="taxonomy-terms taxonomy-<?php echo esc_attr( $taxonomy ); ?>">
	<?php

	if ( apply_filters( 'wmhook_modern_list_terms_all_enabled', true, $taxonomy ) ) :

		$post_type_labels = get_post_type_labels( get_post_type_object( $post_type ) );
		$item_class       = 'taxonomy-terms-item taxonomy-terms-item-all';

		if ( is_post_type_archive( $post_type ) ) {
			$item_class .= ' is-active';
		}

	?>

	<li class="<?php echo esc_attr( $item_class ); ?>">
		<a href="<?php echo esc_url( get_post_type_archive_link( $post_type ) ); ?>" class="term-link button">
			<?php

			printf(
				/* translators: 1: Portfolio post type plural label. */
				esc_html__( 'All %s', 'modern' ),
				esc_html( $post_type_labels->name )
			);

			?>
		</a>
	</li>

	<?php endif;

	foreach ( $terms as $term ) :

		$term_link = get_term_link( $term, $taxonomy );
		if ( is_wp_error( $term_link ) ) {
			continue;
		}

		$item_class = 'taxonomy-terms-item taxonomy-terms-item-slug-' . esc_attr( $term->slug ) . ' taxonomy-terms-item-id-' . esc_attr( $term->term_id );
		if ( is_tax( $taxonomy, $term->term_id ) ) {
			$item_class .= ' is-active';
		}

		?>

		<li class="<?php echo esc_attr( $item_class ); ?>">
			<a href="<?php echo esc_url( $term_link ); ?>" class="term-link button">
				<?php echo esc_html( $term->name ); ?>
			</a>
		</li>

		<?php

	endforeach;

	?>
</ul>

<?php

endif;

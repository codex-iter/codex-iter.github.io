<?php
/**
 * Post meta: Comments count
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Requirements check

	if ( ! comments_open( get_the_ID() ) ) {
		return;
	}


// Helper variables

	$comments_number = absint( get_comments_number( get_the_ID() ) );


?>

<span class="entry-meta-element comments-link">
	<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>#comments" title="<?php echo esc_attr( sprintf( esc_html_x( 'Comments: %s', '%s: number of comments.', 'modern' ), number_format_i18n( $comments_number ) ) ); ?>">
		<span class="entry-meta-description">
			<?php echo esc_html_x( 'Comments:', 'Post meta info description: comments count.', 'modern' ); ?>
		</span>
		<span class="comments-count">
			<?php echo $comments_number; ?>
		</span>
	</a>
</span>

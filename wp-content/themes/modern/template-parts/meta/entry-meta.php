<?php
/**
 * Post meta, top
 *
 * We are using generic, global hook names in this file, but passing
 * a file name as a hook context/scope parameter you can check for.
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Requirements check

	if (
		! is_single( get_the_ID() )
		|| post_password_required()
		|| ! in_array(
			get_post_type( get_the_ID() ),
			(array) apply_filters( 'wmhook_modern_entry_meta_post_type', array( 'post' ), basename( __FILE__ ) )
		)
	) {
		return;
	}


?>

<footer class="entry-meta entry-meta-top"><?php

	get_template_part( 'template-parts/meta/entry-meta-element', 'date' );
	get_template_part( 'template-parts/meta/entry-meta-element', 'comments' );
	get_template_part( 'template-parts/meta/entry-meta-element', 'category' );
	get_template_part( 'template-parts/meta/entry-meta-element', 'author' );

?></footer>

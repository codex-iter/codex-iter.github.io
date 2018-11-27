<?php
/**
 * Template for displaying woocommerce search form
 *
* @package Audioman
 */
?>

<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<label class="screen-reader-text" for="s"><?php esc_html_e( 'Search for:', 'audioman' ); ?></label>
	<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'audioman' ); ?>" value="<?php the_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'audioman' ); ?>" />
	<button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo _x( 'Search Products', 'submit button', 'audioman' ); ?></span></button>
	<input type="hidden" name="post_type" value="product" />
</form>

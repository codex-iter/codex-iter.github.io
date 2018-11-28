<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
?>

<p id="wpforo-title"><?php wpforo_phrase('Forum - Page Not Found') ?></p>

<div class="wpforo-404-wrap">
	<div class="wpforo-404-content">
		<div class="wpf-404 wpfcl-b">404</div>
		<p class="wpfcl-5" style="text-align:center; font-size:18px;"><?php wpforo_phrase('Oops! The page you requested was not found!') ?></p>
		<p class="wpf-404-desc">
		<?php echo sprintf( wpforo_phrase('You can go to %s page or Search here', FALSE), '<a href="' . wpforo_home_url() . '">' . wpforo_phrase('Forum Home', FALSE) . '</a>' ); ?></p>
		<div class="wpf-search-box wpfbg-9">
			<form action="<?php echo wpforo_home_url() ?>" method="get">
				<?php wpforo_make_hidden_fields_from_url( wpforo_home_url() ) ?>
				<p><input type="text" name="wpfs" class="wpf-search-field wpfw-60" /> <input type="submit" class="wpf-search" value="<?php wpforo_phrase('Search') ?> &raquo;" /></p>
			</form>
		</div>
	</div>
</div>
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
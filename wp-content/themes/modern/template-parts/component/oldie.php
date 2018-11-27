<?php
/**
 * Old Internet Explorer version message
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Helper variables

	$version_supported = 11;


?>

<!--[if lt IE <?php echo esc_attr( $version_supported ); ?>]>
	<div class="message-oldie">
		<?php

		printf(
			esc_html_x( 'We are sorry, but this website works with Internet Explorer version %s or newer.', '%s: Version number', 'modern' ),
			$version_supported
		);

		?>
		<br>
		<a href="http://windows.microsoft.com/ie"><?php esc_html_e( 'Please, upgrade your Internet Explorer.', 'modern' ); ?></a>
		<a href="http://outdatedbrowser.com/"><?php esc_html_e( 'Or, switch to another browser.', 'modern' ); ?></a>
	</div>
<![endif]-->

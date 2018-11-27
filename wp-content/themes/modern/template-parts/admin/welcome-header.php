<?php
/**
 * Admin "Welcome" page content component
 *
 * Header.
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





?>

<div class="wm-notes special">

	<h1>
		<?php

		printf(
			esc_html_x( 'Welcome to %1$s %2$s', '1: theme name, 2: theme version number.', 'modern' ),
			'<strong>' . wp_get_theme( 'modern' )->get( 'Name' ) . '</strong>',
			'<small>' . MODERN_THEME_VERSION . '</small>'
		);

		?>
	</h1>

	<div class="welcome-text about-text">
		<?php

		printf(
			esc_html_x( 'Thank you for using %1$s WordPress theme by %2$s!', '1: theme name, 2: theme developer link.', 'modern' ),
			'<strong>' . wp_get_theme( 'modern' )->get( 'Name' ) . '</strong>',
			'<a href="' . esc_url( wp_get_theme( 'modern' )->get( 'AuthorURI' ) ) . '"><strong>WebMan Design</strong></a>'
		);

		?>
		<br>
		<?php esc_html_e( 'Please take time to read the steps below to set up your website.', 'modern' ); ?>
	</div>

	<p>

		<a href="https://webmandesign.github.io/docs/modern/" class="button button-primary button-hero"><?php esc_html_e( 'Theme Documentation', 'modern' ); ?></a>

		<a href="https://www.webmandesign.eu/reference/#links-support" class="button button-hero"><?php esc_html_e( 'Support Center', 'modern' ); ?></a>

	</p>

</div>

<div class="welcome-content">

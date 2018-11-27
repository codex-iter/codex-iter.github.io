<?php
/**
 * Admin "Welcome" page content component
 *
 * Promo.
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





?>

<div class="welcome-upgrade">

	<h2><strong><?php esc_html_e( 'Do you like this theme?', 'modern' ); ?></strong></h2>

	<p>
		<?php esc_html_e( 'If you like this free WordPress theme, please, consider supporting its development by purchasing one of my premium products.', 'modern' ); ?>
		(<a href="https://www.webmandesign.eu" target="_blank"><?php esc_html_e( 'Go to WebMan Design website &raquo;', 'modern' ); ?></a>)
		<?php esc_html_e( 'Or perhaps you are considering a small donation?', 'modern' ); ?>
		&rarr;
		<a href="https://www.paypal.me/webmandesign/20" target="_blank"><em><?php esc_html_e( '"Hey Oliver, have a gallon of coffee on me :)"', 'modern' ); ?></em></a>
	</p>

	<p>
		<?php esc_html_e( 'You can also rate the theme at WordPress repository page.', 'modern' ); ?>
		<a href="https://wordpress.org/support/theme/modern/reviews/?filter=5">
			<?php esc_html_e( "Let's go and rate the theme &#9733;&#9733;&#9733;&#9733;&#9733; :)", 'modern' ); ?>
		</a>
	</p>

	<p>
		<a href="https://www.paypal.me/webmandesign/20" target="_blank" class="button button-primary button-hero"><?php esc_html_e( 'Support theme development', 'modern' ); ?></a>
	</p>

	<p class="welcome-upgrade-thanks">
		<?php esc_html_e( 'Thank you!', 'modern' ); ?>
	</p>

</div>

<style>

	.welcome-upgrade {
		position: relative;
		padding: 2.62em;
		background-color: #1a1c1e;
		background-image: url('<?php echo esc_url_raw( get_header_image() ); ?>');
		background-position: 50% 50%;
		background-size: cover;
		color: #fff;
		z-index: 1;
	}

		.welcome-upgrade::before {
			content: '';
			position: absolute;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			background-color: inherit;
			opacity: .85;
			z-index: -1;
		}

	.welcome-upgrade h2 {
		margin: 0 0 1em;
		font-size: 2.058em;
		font-weight: 700;
		color: inherit;
	}

	.welcome-upgrade p {
		font-size: inherit;
	}

		.welcome-upgrade .welcome-upgrade-thanks {
			margin: 1.62rem 0 0;
			font-family: Georgia, serif;
			font-size: 2.058em;
			font-style: italic;
		}

	.welcome-upgrade a {
		color: inherit;
	}

		.welcome-upgrade a:hover {
			text-decoration: none;
		}

</style>

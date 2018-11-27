<?php
/**
 * Admin "Welcome" page content component
 *
 * Quickstart guide.
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Helper variables

	$step = 0;


?>

<h2 class="screen-reader-text"><?php esc_html_e( 'Quickstart Guide', 'modern' ); ?></h2>

<div class="feature-section three-col">

	<div class="first-feature col">

		<span class="dropcap"><?php echo ++$step; ?></span>

		<h3><?php esc_html_e( 'WordPress settings', 'modern' ); ?></h3>

		<p>
			<?php esc_html_e( 'Do not forget to set up your WordPress in "Settings" section of the WordPress dashboard.', 'modern' ); ?>
			<?php esc_html_e( 'Please go through all the subsections and options.', 'modern' ); ?>
			<?php esc_html_e( 'This step is required for all WordPress websites.', 'modern' ); ?>
		</p>

		<p>
			<strong><?php esc_html_e( 'Please, pay special attention to image sizes setup under Settings &rarr; Media.', 'modern' ); ?></strong>
		</p>

		<a class="button" href="<?php echo esc_url( admin_url( 'options-general.php' ) ); ?>"><strong><?php esc_html_e( 'Set up your WordPress &raquo;', 'modern' ); ?></strong></a>

	</div>

	<div class="second-feature col">

		<span class="dropcap"><?php echo ++$step; ?></span>

		<h3><?php esc_html_e( 'Jetpack plugin', 'modern' ); ?></h3>

		<p>
			<?php esc_html_e( 'This theme works best with Jetpack plugin.', 'modern' ); ?><br>
			<?php esc_html_e( 'It adds Portfolios, Testimonials custom content types, additional theme options and functionality such as tiled galleries and infinite scroll.', 'modern' ); ?>
		</p>

		<?php if ( ! class_exists( 'Jetpack' ) ) : ?>

			<a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>" class="button"><?php printf( esc_html_x( 'Install %s &raquo;', '%s: Jetpack plugin name.', 'modern' ), '<strong>Jetpack</strong>' ); ?></a>

		<?php else : ?>

			<p style="margin-top: 2em;">
				<span style="display: inline-block; float: left; width: 2em; height: 2em; margin: 0 .62em 1em; line-height: 2em; text-align: center; box-shadow: inset 0 0 0 2px;">&#10004;</span>
				<?php esc_html_e( 'Great! Jetpack is active. Make sure it is connected to use its features.', 'modern' ); ?>
			</p>

		<?php endif; ?>

	</div>

	<div class="last-feature col">

		<span class="dropcap"><?php echo ++$step; ?></span>

		<h3><?php esc_html_e( 'Customize the theme', 'modern' ); ?></h3>

		<p>
			<?php esc_html_e( 'You can customize the theme using live-preview editor.', 'modern' ); ?>
			<?php esc_html_e( 'Customization changes will go live only after you save them!', 'modern' ); ?>
		</p>

		<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary button-hero"><?php esc_html_e( 'Customize the Theme &raquo;', 'modern' ); ?></a>

	</div>

</div>

<hr>

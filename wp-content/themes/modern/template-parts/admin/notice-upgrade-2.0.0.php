<?php
/**
 * Admin notice: Upgrade: 2.0.0
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





?>

<div class="updated notice is-dismissible theme-upgrade-notice theme-upgrade-notice-info">
	<h2>
		<?php esc_html_e( 'IMPORTANT: Typography options change', 'modern' ); ?>
	</h2>
	<p>
		<?php esc_html_e( 'Please note that custom typography options have been changed in this theme update to provide more flexible typography setup.', 'modern' ); ?>
		<?php esc_html_e( 'You will most likely need to load your custom fonts using additional plugin.', 'modern' ); ?>
		<?php esc_html_e( 'Please read the information in Appearance &rarr; Customize &rarr; Theme Options &rarr; Typography for more details on custom typography setup.', 'modern' ); ?>
	</p>
	<p>
		<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button">
			<?php esc_html_e( 'Set customize typography now &raquo;', 'modern' ); ?>
		</a>
	</p>

	<p>
		<a href="<?php echo esc_url( admin_url( 'themes.php?page=modern-welcome' ) ); ?>" class="button button-primary">
			<?php

			$theme_name = wp_get_theme( 'modern' )->get( 'Name' );

			printf(
				esc_html_x( 'Get started with %s', '%s: Theme name.', 'modern' ),
				$theme_name
			);

			?>
		</a>
	</p>
</div>

<style type="text/css" media="screen">

	.notice.theme-upgrade-notice-info {
		padding: 1.62em;
		background: #ffeeba;
		color: #856404;
		border: .5em solid rgba(0,0,0,.1);
	}

	.notice.theme-upgrade-notice-info h2 {
		margin-top: 0;
		font-weight: 800;
		color: inherit;
	}

	.notice.theme-upgrade-notice-info p {
		max-width: 40em;
		margin-bottom: 0;
	}

</style>

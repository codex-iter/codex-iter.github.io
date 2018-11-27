<?php
/**
 * Admin "Settings > Media" custom image sizes info
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Helper variables

	$default_image_size_names = array(
		'thumbnail' => esc_html_x( 'Thumbnail size', 'WordPress predefined image size name.', 'modern' ),
		'medium'    => esc_html_x( 'Medium size', 'WordPress predefined image size name.', 'modern' ),
		'large'     => esc_html_x( 'Large size', 'WordPress predefined image size name.', 'modern' ),
	);

	$image_sizes = array_filter( apply_filters( 'wmhook_modern_setup_image_sizes', array() ) );


// Requirements check

	if ( empty( $image_sizes ) ) {
		return;
	}


?>

<div class="recommended-image-sizes">

	<?php do_action( 'wmhook_modern_image_sizes_notice_html_top' ); ?>

	<h3><?php esc_html_e( 'Recommended image sizes', 'modern' ); ?></h3>

	<p><?php esc_html_e( 'For the optimal theme display, please, set image sizes recommended in the table below.', 'modern' ); ?></p>

	<p>
		<?php esc_html_e( 'Do you already have images uploaded to your website and want to resize them?', 'modern' ); ?>
		<a href="https://wordpress.org/plugins/search/regenerate+thumbnails/"><?php esc_html_e( 'Use a plugin &raquo;', 'modern' ); ?></a>
	</p>

	<table>

		<thead>
			<tr>
			<th><?php esc_html_e( 'Size name', 'modern' ); ?></th>
			<th><?php esc_html_e( 'Size parameters', 'modern' ); ?></th>
			<th><?php esc_html_e( 'Theme usage', 'modern' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php

			foreach ( $image_sizes as $size => $setup ) {

				if ( 'medium_large' === $size ) {
					continue;
				}

				$crop = ( $setup[2] ) ? ( esc_html__( 'cropped', 'modern' ) ) : ( esc_html__( 'scaled', 'modern' ) );

				?>

				<?php if ( isset( $default_image_size_names[ $size ] ) ) : ?>

				<tr>

					<th><?php echo esc_html( $default_image_size_names[ $size ] ); ?>:</th>

				<?php else : ?>

				<tr title="<?php esc_attr_e( 'Additional image size added by the theme. Can not be changed on this page.', 'modern' ); ?>">

					<th><code><?php echo esc_html( $size ); ?></code>:</th>

				<?php endif; ?>

					<td>
						<?php

						printf(
								esc_html_x( '%1$d &times; %2$d, %3$s', '1: image width, 2: image height, 3: cropped or scaled?', 'modern' ),
								absint( $setup[0] ),
								absint( $setup[1] ),
								$crop
							);

						?>
					</td>

					<td class="small">
						<?php

						if ( isset( $setup[3] ) ) {
							echo $setup[3];
						} else {
							echo '&mdash;';
						}

						?>
					</td>

				</tr>

				<?php

			} // /foreach

			?>
		</tbody>

	</table>

	<?php do_action( 'wmhook_modern_image_sizes_notice_html_bottom' ); ?>

	<style type="text/css" media="screen">

		.recommended-image-sizes {
			display: inline-block;
			padding: 1.62em;
			border: 2px solid #dadcde;
		}

		.recommended-image-sizes h3:first-child {
			margin-top: 0;
		}

		.recommended-image-sizes table {
			margin-top: 1.62em;
		}

		.recommended-image-sizes th,
		.recommended-image-sizes td {
			width: auto;
			padding: .38em 1em;
			border-bottom: 2px dotted #dadcde;
			vertical-align: top;
		}

		.recommended-image-sizes thead th {
			padding: .62em 1em;
			text-transform: uppercase;
			font-size: .81em;
			border-bottom-style: solid;
		}

		.recommended-image-sizes tr[title] {
			cursor: help;
		}

	</style>

</div>

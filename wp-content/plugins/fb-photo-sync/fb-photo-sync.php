<?php
/*
 * Plugin Name: FB Photo Sync
 * Description: Import and manage Facebook photo ablums on your WordPress website.
 * Author: Mike Auteri
 * Version: 0.5.9
 * Author URI: http://www.mikeauteri.com/
 * Plugin URI: http://www.mikeauteri.com/portfolio/fb-photo-sync
 */

class FB_Photo_Sync {

	var $version = '0.5.9';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fbps_scripts' ) );
		add_action( 'wp_ajax_fbps_save_album', array( $this, 'ajax_save_photos' ) );
		add_action( 'wp_ajax_fbps_save_app', array( $this, 'ajax_save_app' ) );
		add_action( 'wp_ajax_fbps_remove_app', array( $this, 'ajax_remove_app' ) );
		add_action( 'wp_ajax_fbps_delete_album', array( $this, 'ajax_delete_photos' ) );

		add_shortcode( 'fb_album', array( $this, 'fb_album_shortcode' ) );
	}

	public function admin_scripts() {
		$ver = $this->version;
		wp_enqueue_style( 'fbps-admin-styles', plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $ver );
		wp_enqueue_script( 'fbps-zero-clipboard', plugin_dir_url( __FILE__ ) . 'js/jquery.zclip.js', array( 'jquery' ), $ver, true );
		wp_enqueue_script( 'fbps-admin-script', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery', 'fbps-zero-clipboard' ), $ver, true );
	}

	public function fbps_scripts() {
		$ver = $this->version;
		wp_enqueue_style( 'fbps-styles', plugin_dir_url( __FILE__ ) . 'css/styles.css', array(), $ver );
		wp_enqueue_style( 'light-gallery-css', plugin_dir_url( __FILE__ ) . 'light-gallery/css/lightGallery.css', array(), $ver );
		wp_enqueue_script( 'light-gallery-js', plugin_dir_url( __FILE__ ) . 'light-gallery/js/lightGallery.min.js', array( 'jquery' ), $ver, false );
		wp_enqueue_script( 'lazyload', plugin_dir_url( __FILE__ ) . 'js/jquery.lazyload.min.js', array( 'jquery' ), $ver, false );
	}

	public function closest_image_size( $width, $height, $photos ) {
		$current = null;

		foreach( $photos as $photo ) {
			if( !$this->valid_image_size( $width, $height, $photo ) ) {
				continue;
			}

			$current = $this->get_closest_image( $width, $height, $photo, $current );
		}

		if( $current == null ) {
			$current['source'] = $photos[0]['source'];
		}

		return $current['source'];
	}

	private function valid_image_size( $width, $height, $photo ) {
		return $width <= $photo['width'] && $height <= $photo['height'];
	}

	private function get_image_diff_sum( $width, $height, $photo ) {
		$width_diff = $photo['width'] - $width;
		$height_diff = $photo['height'] - $height;

		return $width_diff + $height_diff;
	}

	private function get_closest_image( $width, $height, $photo, $current = null ) {
		if( ! $current ) {
			return $photo;
		}

		$photo_sum = $this->get_image_diff_sum( $width, $height, $photo );
		$current_sum = $this->get_image_diff_sum( $width, $height, $current );

		if( $current_sum > $photo_sum ) {
			return $photo;
		}

		return $current;
	}

	public function save_image( $file, $desc ) {
		// unattached to a post
		$post_id = 0;
		// Download file to temp location
		$tmp = download_url( $file );

		// Set variables for storage
		// fix file filename for query strings
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}

		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $post_id, $desc );
		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			@unlink($file_array['tmp_name']);
			return $id;
		}
		return $id;
	}

	public function fb_album_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'id' => '',
			'width' => 130,
			'height' => 130,
			'order' => 'desc',
			'wp_photos' => false,
			'lazy_load' => 'true',
			'ignore' => '',
			'limit' => ''
		), $atts );

		$atts['lazy_load'] = strtolower( $atts['lazy_load'] ) == 'false' ? false : true;

		$atts['ignore'] = explode( ',', $atts['ignore'] );
		$atts['ignore'] = array_map( 'trim', $atts['ignore'] );

		if( empty( $atts['id'] ) || is_Nan( $atts['id'] ) ) {
			return;
		}

		$album = get_option( 'fbps_album_'.$atts['id'] );

		if( !isset( $album['items'] ) )
			return;
		ob_start();
		?>
		<div id="fbps-album-<?php echo esc_attr( $album['id']	); ?>" class="fbps-album">
			<h3><?php echo esc_html( $album['name'] ); ?></h3>
			<ul>
			<?php
			if( trim( strtolower( $atts['order'] ) ) == 'desc' ) {
				$album['items'] = array_reverse( $album['items'] );
			}
			$limit = intval( $atts['limit'] );
			$limit = $limit > 0 ? $limit : 0;
			$i = 1;
			foreach( $album['items'] as $item ) {
				if( in_array( $item['id'], $atts['ignore'] ) ) {
					continue;
				}
				$item_name = isset( $item['name'] ) ? $item['name'] : '';
				$thumbnail = $this->closest_image_size( $atts['width'], $atts['height'], $item['photos'] );
				$image = $this->closest_image_size( 960, 960, $item['photos'] );
				if( $atts['wp_photos'] == 'true' && isset( $item['wp_photo_id'] ) ) {
					$wp_thumbnail = wp_get_attachment_image_src( $item['wp_photo_id'], array( $atts['width'], $atts['height'] ) );
					$wp_image = wp_get_attachment_image_src( $item['wp_photo_id'], array(960, 960) );
					if( is_array( $wp_thumbnail ) ) {
						$thumbnail = $wp_thumbnail[0];
					}
					if( is_array( $wp_image ) ) {
						$image = $wp_image[0];
					}
				}
				$image_loaded = !$atts['lazy_load'] ? ' background-image: url(' . esc_url( $thumbnail ) . '); ' : '';
				?>
				<li id="fbps-photo-<?php echo esc_attr( $item['id'] ); ?>"  class="fbps-photo" data-src="<?php echo esc_url( $image ); ?>">
					<div style="width: <?php echo esc_attr( $atts['width'] ); ?>px; height: <?php echo intval( $atts['height'] ); ?>px; background-color: #ccc;<?php echo esc_attr( $image_loaded ); ?>" data-original="<?php echo esc_url( $thumbnail ); ?>"></div>
					<img src="<?php echo esc_url( $thumbnail ); ?>" style="display: none;" />
					<div class="lg-sub-html"><p class="lg-fbps"><?php echo esc_html( $item_name ); ?></p></div>
				</li>
				<?php
				if ( $limit && $limit === $i ) {
					break;
				}
				$i++;

			}
			?>
			</ul>
			<div style="clear: both;"></div>
		</div>
		<script type="text/javascript">
			(function($) {
				<?php if( $atts['lazy_load'] ) { ?>
				$('#fbps-album-<?php echo esc_js( $album['id']	); ?> li.fbps-photo > div').lazyload({
					effect: 'fadeIn'
				});
				<?php } ?>
				$('#fbps-album-<?php echo esc_js( $album['id']	); ?> > ul').lightGallery();
			})(jQuery);
		</script>
		<?php
		return ob_get_clean();
	}

	public function admin_tabs( $current = 'import' ) {
		$tabs = array( 'import' => 'Import', 'albums' => 'Albums' );
		echo '<div class="wrap">';
		echo '<h2>FB Photo Sync</h2>';
		echo '<div id="icon-themes" class="icon32"><br /></div>';
		echo '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $name ){
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=fb-photo-sync&tab=$tab'>$name</a>";
		}
		echo '</h2>';
	}

	public function admin_content( $current = 'import' ) {
		switch( $current ) {
			case 'import':
				$this->import_page();
				break;
			case 'albums':
				$this->albums_page();
				break;
			default:
				$this->import_page();
		}
		?>
		<hr style="clear: both;" />
		<input type="hidden" id="nonce" value="<?php echo wp_create_nonce( 'fb-photo-sync' ); ?>" />
		<div style="width: 400px;">
			<form  style="float:right" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
				">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
			<h3 style="margin:0 0 1px;padding:0;">Like this plugin?</h3>
			<p style="margin:0;padding:0;">Many hours have gone into its development. Please consider a small donation for continued support.</p>
		</div>
		<?php
		echo '</div>'; // wrap
	}

	public function import_page() {
		global $facebook;
		$app_id = get_option( 'fbps_app_id' );
		?>
		<h3>Facebook Application</h3>
		<div id="fbps-app"></div>
		<?php if( ! $app_id ) { ?>
			<p><input type="text" class="regular-text" id="fbps-app-id" placeholder="Facebook App ID" />
			<input type="button" id="fbps-app-id-submit" class="button" value="Add App" /></p>
			<hr />
			<div class="instructions">
				<h3>Instructions:</h3>
				<ol>
					<li>Head to <a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a></li>
					<li>Select your app or create a new app</li>
					<li>Click on <strong>Settings</strong> in your app, then at the bottom, click <strong>Add Platform</strong></li>
					<li>Under the <strong>Select Platform</strong> modal, click <strong>Website</strong></li>
					<li>Set the <strong>Site URL</strong> in your Facebook App to your domain: <strong><?php echo home_url('/'); ?></strong></li>
					<li>Copy and Paste the App ID in the text space above and click <strong>Add App</strong></li>
				</ol>
			</div>
		<?php } else { ?>
			<p><input type="button" id="fbps-app-id-remove" class="button" value="Remove App" /></p>
			<div id="status"></div>
			<p><fb:login-button scope="user_photos" onlogin="checkLoginState();" auto_logout_link="true"></fb:login-button></p>
			<p class="description">Having issues? Click <strong>Remove App</strong> above and follow the provided instructions.</p>
			<p class="description">Still having issues? <a href="https://wordpress.org/support/plugin/fb-photo-sync" target="_blank">Search FB Photo Sync support page or post a new topic</a>.</p>
		<?php } ?>
		<?php if( $app_id ) { ?>
			<div class="fbps-enabled">
				<hr />
				<div class="fbps-row">
					<h3>Find Albums on a Public Page</h3>
					<p>Type in a Page ID below and click the <strong>Find Albums</strong> button to pull in available albums.
					<p>Check the albums you want to import into WordPress, and then click the <strong>Import Albums</strong> button below to import them.</p>
					<p>When completed, click the <strong>Albums</strong> tab for the album shortcode to include in your post or page.</p>
					<p>
						<input type="text" class="regular-text" id="fbps-page-input" placeholder="Enter Facebook Page ID" />
						<input type="button" name="fbps-load-albums" id="fbps-load-albums" class="button" value="Find Albums" />
					</p>
					<p class="description">http://facebook.com/<strong><u>this-is-the-page-id</u></strong></p>
				</div>
				<div class="fbps-row">
					<h3>Import Your Personal Facebook Albums</h3>
					<p>Click the <strong>Show Albums</strong> button below for a list of your personal albums you can import to your WordPress website.</p>
					<p>
						<input type="button" name="fbps-show-albums" id="fbps-show-albums" class="button" value="Show Albums" />
					</p>
				</div>
				<div style="clear:both;"></div>
				<hr />
				<h3>Import Facebook Albums</h3>
				<ul id="fbps-page-album-list" class="fbps-list">
					<li>None selected</li>
				</ul>
				<div id="import-form">
					<h3>Albums to Import</h3>
					<ul id="fbps-import-list" class="fbps-list">
					</ul>
					<p>
						<label for="fbps-wp-images"><input type="checkbox" checked="checked" name="fbps-wp-images" id="fbps-wp-images" /> Import images to WordPress media library?</label>
					</p>
					<p class="description">Checking the box above will import and save images from Facebook to your WordPress site. Import will take longer, so be patient.</p>
					<p class="submit">
						<input type="button" id="import-button" class="button button-primary" value="Import Albums">
					</p>
				</div>
			</div>
		<?php
		}
	}

	public function albums_page() {
		global $wpdb;

		$query = $wpdb->prepare( "
			SELECT option_name, option_value
			FROM {$wpdb->options}
			WHERE
			option_name LIKE %s
			", 'fbps_album_%' );

		$albums = $wpdb->get_results( $query );
		echo '<ul id="fbps-album-list" class="fbps-list">';
		foreach( $albums as $album ) {
			$dump = unserialize( $album->option_value );
			$wp_photos = isset( $dump['items'][0]['wp_photo_id'] ) && (bool) $dump['items'][0]['wp_photo_id'] ? 'checked="checked"' : '';
			?>
			<li data-id="<?php echo esc_attr( $dump['id'] ); ?>">
				<h3><?php echo esc_html( $dump['name'] ); ?></h3>
				<a href="#" class="fbps-image-sample" style="background-image: url(<?php echo esc_url( $dump['picture'] ); ?>);"></a>
				<div class="fbps-options">
					<p><code>[fb_album id="<?php echo esc_attr( $dump['id'] ); ?>"<?php echo $wp_photos != '' ? ' wp_photos="true"' : ''; ?>]</code></p>
					<p><label><input type="checkbox" <?php echo $wp_photos; ?> class="fbps-wp-photos" /> Import images to media library?</label>
					<p><span class="fbps-counter"><span>0</span> of </span><?php echo intval( count( $dump['items'] ) ); ?> Photos | <a href="#" class="delete-album">Delete</a> | <a href="#" class="sync-album">Sync</a></p>
				</div>
			</li>
			<?php
		}
		echo '</ul>';
	}

	private function search_array( $id, $items ) {
		foreach( $items as $key => $item ) {
			if( $item['id'] == $id ) {
				return $key;
			}
		}
		return false;
	}

	public function ajax_save_app() {
		if( !check_ajax_referer( 'fb-photo-sync', 'nonce', false ) || !current_user_can( 'manage_options' ) || !isset( $_POST['id'] ) || !is_numeric( $_POST['id'] ) ) {
			wp_send_json_error();
		}
		$update = update_option( 'fbps_app_id', sanitize_text_field( $_POST['id'] ) );
		if( $update ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	public function ajax_remove_app() {
		if( !check_ajax_referer( 'fb-photo-sync', 'nonce', false ) || !current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		$remove = delete_option( 'fbps_app_id' );
		if( $remove ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	public function ajax_save_photos() {
		if( !check_ajax_referer( 'fb-photo-sync', 'nonce', false ) || !current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		$album = json_decode( stripslashes( $_POST['album'] ), true );

		if( is_array( $album ) ) {
			$wp_photos = $_POST['wp_photos'] == 'true' ? true : false;

			$saved_album = get_option( 'fbps_album_' . $album['id'] ) ? get_option( 'fbps_album_' . $album['id'] ) : $album;

			if( isset( $saved_album['items'] ) && is_array( $saved_album['items'] ) ) {
				$saved_album['name'] = $album['name'];
				$saved_album['picture'] = $album['picture'];

				$i = $this->search_array( $album['items'][0]['id'], $saved_album['items'] );

				if( $i === false ) {
					$item = $album['items'][0];
				} else {
					$item = array_merge( $saved_album['items'][$i], $album['items'][0] );
				}

				$id = $item['id'];
				if( isset( $item['wp_photo_id'] ) && wp_get_attachment_image( $item['wp_photo_id'] ) != null ) {
					if( !$wp_photos ) {
						wp_delete_attachment( $item['wp_photo_id'], true );
					}
				} else if( $wp_photos ) {
					$photo = $this->closest_image_size( 1000, 1000, $item['photos'] );
					$name = isset( $item['name'] ) ? $item['name'] : '';
					$image_id = $this->save_image( $photo, $name );
					$item['wp_photo_id'] = $image_id;
				}

				$key = $this->search_array( $item['id'], $saved_album['items'] );
				if( $key === false ) {
					array_push( $saved_album['items'], $item );
				} else {
					$saved_album['items'][$key] = $item;
				}
			}
			update_option( 'fbps_album_' . esc_attr( $saved_album['id'] ), $saved_album );
			$data = array(
				'id' => $album['id'],
				'wp_photos' => $wp_photos,
				'album' => $album
			);
			wp_send_json_success( $data );
		} else {
				$data = array(
					'id' => $album['id'],
					'wp_photos' => $wp_photos
				);
				wp_send_json_error( $data );
		}
	}

	public function ajax_delete_photos() {
		if( !check_ajax_referer( 'fb-photo-sync', 'nonce', false ) ) {
			wp_send_json_error();
		}
		$id = $_POST['id'];
		if( isset( $id ) && current_user_can( 'manage_options' ) ) {
			$saved_album = get_option( 'fbps_album_' . $id );
			if( isset( $saved_album['items'] ) ) {
				foreach( $saved_album['items'] as $saved_item ) {
					if( isset( $saved_item['wp_photo_id'] ) ) {
						wp_delete_attachment( $saved_item['wp_photo_id'], true );
					}
				}
			}
			delete_option( 'fbps_album_' . $id );
			$data = array(
				'id' => esc_attr( $id )
			);
			wp_send_json_success( $data );
		} else {
			$data = array(
				'id' => esc_attr( $id )
			);
			wp_send_json_error( $data );
		}
	}

	public function add_menu_page() {
		add_options_page( 'FB Photo Sync', 'FB Photo Sync', 'manage_options', 'fb-photo-sync', array( $this, 'display_options_page' ) );
	}

	public function display_options_page() {
		$current = isset( $_GET['tab'] ) ? $_GET['tab'] : 'import';
		$this->admin_tabs( $current );
		$this->admin_content( $current );
		?>
		<div id="fb-root"></div>
		<script>
			var fbps_app_id = "<?php echo esc_js( get_option( 'fbps_app_id' ) ); ?>";
			function statusChangeCallback(response) {
				if (response.status === 'connected') {
					testAPI();
				} else if (response.status === 'unknown') {
					document.getElementById('status').innerHTML = '<p>Please log ' + 'into this app.</p>';
					jQuery('.fbps-enabled').hide();
				} else {
					document.getElementById('status').innerHTML = '<p>Please log ' + 'into Facebook.</p>';
					jQuery('.fbps-enabled').hide();
				}
			}

			function checkLoginState() {
				FB.getLoginStatus(function(response) {
					statusChangeCallback(response);
				});
			}

			window.fbAsyncInit = function() {
				FB.init({
					appId: fbps_app_id,
					status: true,
					cookie: true,
					xfbml: true,
					version: 'v2.12'
				});

				FB.getLoginStatus(function(response) {
					statusChangeCallback(response);
				});
			};

			(function(d, s, id){
				var js, fjs = d.getElementsByTagName(s)[0];
				if(d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

			function testAPI() {
				FB.api('/me', function(response) {
					document.getElementById('status').innerHTML = '<p>Thanks for logging in, <a href="' + response.link + '" target="_blank">' + response.name + '</a>!</p>';
				});

				FB.api('/' + fbps_app_id + '?fields=id,icon_url,name', function(response) {
					document.getElementById('fbps-app').innerHTML = '<p><img src="' + response.icon_url + '" /> ' + response.name + '</p>';
				});

				jQuery('.fbps-enabled').show();
			}
		</script>
		<?php
	}
}
new FB_Photo_Sync();

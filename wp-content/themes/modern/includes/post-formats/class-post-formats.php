<?php
/**
 * Get post formats media
 *
 * Used development prefixes:
 * - version_since
 * - version
 * - prefix_hook
 * - theme_name
 * - prefix_class
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





/**
 * Post formats media
 *
 * WordPress audio, gallery, image and video post format media generator.
 *
 * Custom hooks naming convention:
 * - `wmhook_modern_pf_` - Modern_Post_Formats class specific hooks
 *
 * @copyright  WebMan Design, Oliver Juhas
 * @license    GPL-3.0, http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @link  https://github.com/webmandesign/wp-post-formats
 * @link  http://www.webmandesign.eu
 *
 * @version  3.0.0
 *
 *
 * GENERATED MEDIA
 * ===============
 *
 * Media generated from post content for supported post formats:
 *
 *   Audio post format
 *   - first `[audio]` or `[playlist]` shortcode
 *   - or first embed media URL
 *
 *   Gallery post format
 *   - coma separated string of image IDs from first `[gallery]` shortcode
 *   - or coma separated string of attached images IDs
 *
 *   Image post format
 *   - ID of the first image in post content (for uploaded images)
 *   - or URL of the first image in post content
 *
 *   Video post format
 *   - first `[video]`, `[playlist]` or `[wpvideo]` shortcode
 *   - or first embed media URL
 *
 *
 * CUSTOM META FIELDS
 * ==================
 *
 * If no media saved in custom meta field, these functions will attempt to
 * generate the media and save them in a hidden custom meta field.
 * Also, regeneration occurs on every post saving or update.
 * You can override the generated media with a custom `post_format_media`
 * custom meta field setup.
 *
 * @link  http://codex.wordpress.org/Custom_Fields
 *
 *
 * IMPLEMENTATION EXAMPLE
 * ======================
 *
 * Copy this file into your WordPress theme's root directory and include
 * it in your theme's `functions.php` file like so:
 *
 * @example
 *
 *   require_once 'class-post-formats.php';
 *
 * Then, use this code in your `content-audio.php` file, for example:
 *
 * @example
 *
 *   $post_format_media = (string) Modern_Post_Formats::get();
 *
 *   if ( 0 === strpos( $post_format_media, '[' ) ) {
 *     $post_format_media = do_shortcode( $post_format_media );
 *   } else {
 *     $post_format_media = wp_oembed_get( $post_format_media );
 *   }
 *
 *   echo $post_format_media;
 *
 *
 * OTHER NOTES
 * ===========
 *
 * Please note that this file does not register post formats for your theme.
 * Register post formats in your theme according to WordPress Codex instructions:
 * @link  http://codex.wordpress.org/Post_Formats#Adding_Theme_Support
 * @link  http://codex.wordpress.org/Post_Formats
 *
 *
 * Contents:
 *
 *   0) Init
 *  10) Getter
 *  20) Core
 * 100) Helpers
 */
class Modern_Post_Formats {





	/**
	 * 0) Init
	 */

		private static $instance;



		/**
		 * Constructor
		 *
		 * @since    2.3.0
		 * @version  3.0.0
		 */
		private function __construct() {

			// Processing

				// Actions

					add_action( 'save_post', __CLASS__ . '::format_media' );

				// Filters

					add_filter( 'wmhook_modern_pf_format_media_output', __CLASS__ . '::fix_ssl_urls', 9999 );

		} // /__construct



		/**
		 * Class initialization
		 *
		 * @since    1.0.0
		 * @version  2.3.0
		 */
		static public function init() {

			// Processing

				if ( null === self::$instance ) {
					self::$instance = new self;
				}


			// Output

				return self::$instance;

		} // /init





	/**
	 * 10) Getter
	 */

		/**
		 * Get the post format media
		 *
		 * Supported post formats: audio, gallery, image, video.
		 * Must be inside the loop.
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 *
		 * @param  string $format
		 *
		 * @return  string  Post format media (see `format_media()` below).
		 */
		static public function get( $format = null ) {

			// Pre

				$pre = apply_filters( 'wmhook_modern_pf_get_pre', false, $format );

				if ( false !== $pre ) {
					return $pre;
				}


			// Helper variables

				if ( empty( $format ) ) {
					$format = get_post_format();
				}


			// Output

				return (string) self::format_media( get_the_ID(), $format );

		} // /get





	/**
	 * 20) Core
	 */

		/**
		 * Get/set the post format media
		 *
		 * If not set already, get the post media from the post content and save
		 * it in a hidden custom meta field. But, allow user to bypass by setting
		 * a `post_format_media` custom meta field, too.
		 *
		 * The function is triggered also on every post save to refresh the hidden
		 * post media custom meta field.
		 *
		 * @since    1.0.0
		 * @version  3.0.0
		 *
		 * @param  int    $post_id
		 * @param  string $format
		 *
		 * @return  string  Post format media. Should always be a string so user can override this in a custom field.
		 */
		static public function format_media( $post_id = null, $format = null ) {

			// Pre

				$pre = apply_filters( 'wmhook_modern_pf_format_media_pre', false, $post_id, $format );

				if ( false !== $pre ) {
					return $pre;
				}


			// Requirements check

				if ( empty( $post_id ) ) {
					$post_id = get_the_ID();
				}

				if (
					empty( $post_id )
					|| (
						// Exit early for no-post_format post types
						is_admin()
						&& isset( $_REQUEST )
						&& ! isset( $_REQUEST['post_format'] )
					)
				) {
					return false;
				}


			// Helper variables

				$post_id   = absint( $post_id );
				$format    = ( empty( $format ) ) ? ( get_post_format( $post_id ) ) : ( $format );
				$meta_name = (string) apply_filters( 'wmhook_modern_pf_format_media_meta_name', 'post_format_media' );

				$supported_formats = (array) apply_filters( 'wmhook_modern_pf_format_media_formats', array(
					'audio',
					'gallery',
					'image',
					'video',
				) );

				// Requirements check

					if ( ! in_array( $format, $supported_formats ) ) {
						return;
					}

				// Allow users to set custom field first

					$output = get_post_meta( $post_id, $meta_name, true );

				// If no user custom field set, get the previously generated one (from hidden custom field)

					if ( empty( $output ) ) {
						$output = get_post_meta( $post_id, '_' . $meta_name, true );
					}

				// Premature output filtering

					$output = (string) apply_filters( 'wmhook_modern_pf_format_media_output_pre', $output, $post_id, $format );

				// Force refresh (regenerate and re-save) the post media meta field

					if (
						// When forced
						apply_filters( 'wmhook_modern_pf_format_media_force_refresh', false, $post_id, $format )
						// When no media saved
						|| empty( $output )
						// When saving post (no need for checking nonce as this can be triggered anywhere...)
						|| (
							is_admin()
							&& current_user_can( 'edit_posts', $post_id )
							&& ! wp_is_post_revision( $post_id )
							&& isset( $_REQUEST['post_format'] ) && ! empty( $_REQUEST['post_format'] )
						)
					) {
						$output = '';
					}

				// Return if we have output

					if ( $output ) {
						return (string) apply_filters( 'wmhook_modern_pf_format_media_output', $output, $post_id, $format );
					}


			// Processing

				/**
				 * The code below is being triggered when forced to refresh only.
				 */

				switch ( $format ) {

					case 'audio':
					case 'video':

							$output = self::get_media_audio_video( $post_id );

					break;
					case 'gallery':

							$output = self::get_media_gallery( $post_id );

					break;
					case 'image':

							$output = self::get_media_image( $post_id );

					break;

					default:
					break;

				}

				// Filter the output

					$output = (string) apply_filters( 'wmhook_modern_pf_format_media_output', $output, $post_id, $format );

				// Save the post media meta field

					update_post_meta( $post_id, '_' . $meta_name, $output );

				// Custom action hook

					do_action( 'wmhook_modern_pf_format_media', $output, $post_id, $format, $meta_name );


			// Output

				return (string) $output;

		} // /format_media





	/**
	 * 100) Helpers
	 */

		/**
		 * Fixing URLs in `is_ssl()` returns TRUE
		 *
		 * @since    2.4.0
		 * @version  3.0.0
		 *
		 * @param  string $content
		 *
		 * @return  string  Content with SSL ready URLs.
		 */
		static public function fix_ssl_urls( $content = '' ) {

			// Processing

				if ( is_ssl() ) {
					$content = str_ireplace(
						'http:',
						'https:',
						(string) $content
					);
				}


			// Output

				return (string) $content;

		} // /fix_ssl_urls



		/**
		 * Get the post format media: audio, video
		 *
		 * Searches for media shortcode or URL in the post content.
		 *
		 * @since    1.0.0
		 * @version  3.0.0
		 *
		 * @param  int $post_id
		 *
		 * @return  string  Audio/video/playlist shortcode or oembed media URL.
		 */
		static public function get_media_audio_video( $post_id ) {

			// Pre

				$pre = apply_filters( 'wmhook_modern_pf_get_media_audio_video_pre', false, $post_id );

				if ( false !== $pre ) {
					return $pre;
				}


			// Requirements check

				if ( empty( $post_id ) ) {
					return;
				}


			// Helper variables

				$output  = '';
				$post    = get_post( $post_id );
				$content = $post->post_content;
				$pattern = ( 'video' === get_post_format( $post_id ) ) ? ( 'video|playlist|wpvideo' ) : ( 'audio|playlist' );


			// Processing

				/**
				 * Info:
				 *
				 * preg_match() sufixes:
				 * @link  http://php.net/manual/en/function.preg-match.php#102214
				 * @link  http://php.net/manual/en/function.preg-match.php#111573
				 */

				preg_match(
					'/\[(' . $pattern . ')(.*)\]/u',
					wp_strip_all_tags( $content ),
					$matches
				);

				if ( isset( $matches[0] ) ) {
				// Look for shortcodes first.

					$output = trim( $matches[0] );

				} elseif ( false !== strpos( $content, 'http' ) ) {
				// Then look for oembed media URL.

					// First, get all URLs from the content

						preg_match_all(
							'/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/',
							wp_strip_all_tags( $content ),
							$matches
						);

					// And now, just return the first oembed compatible URL

						if (
							isset( $matches[0] )
							&& is_array( $matches[0] )
						) {
							$matches = array_unique( $matches[0] );

							foreach ( $matches as $url ) {
								if ( wp_oembed_get( esc_url( $url ) ) ) {
									$output = $url;
									break;
								}
							}
						}

				}


			// Output

				return (string) $output;

		} // /get_media_audio_video



		/**
		 * Get the post format media: gallery
		 *
		 * Get images from the first [gallery] shortcode found in the post content.
		 *
		 * @since    1.0.0
		 * @version  3.0.0
		 *
		 * @param  int $post_id
		 *
		 * @return  string  Comma separated list of gallery image IDs.
		 */
		static public function get_media_gallery( $post_id ) {

			// Pre

				$pre = apply_filters( 'wmhook_modern_pf_get_media_gallery_pre', false, $post_id );

				if ( false !== $pre ) {
					return $pre;
				}


			// Requirements check

				if ( empty( $post_id ) ) {
					return;
				}


			// Helper variables

				$output = get_post_gallery( $post_id, false );


			// Requirements check

				if ( ! isset( $output['ids'] ) ) {
					return;
				}


			// Output

				return trim( (string) $output['ids'] );

		} // /get_media_gallery



		/**
		 * Get the post format media: image
		 *
		 * Searches for the image in the post content.
		 *
		 * @since    1.0.0
		 * @version  3.0.0
		 *
		 * @param  int $post_id
		 *
		 * @return  string  Image ID (for uploaded image) or image URL.
		 */
		static public function get_media_image( $post_id ) {

			// Pre

				$pre = apply_filters( 'wmhook_modern_pf_get_media_image_pre', false, $post_id );

				if ( false !== $pre ) {
					return $pre;
				}


			// Requirements check

				if ( empty( $post_id ) ) {
					return;
				}


			// Helper variables

				$output  = '';
				$post    = get_post( $post_id );
				$content = $post->post_content;


			// Processing

				/**
				 * Info:
				 *
				 * preg_match() sufixes:
				 * @link  http://php.net/manual/en/function.preg-match.php#example-4907
				 */

				preg_match(
					'/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',
					$content,
					$matches
				);

				if ( isset( $matches[1] ) ) {
					$output = esc_url( trim( $matches[1] ) );
				}

				// Try to get the image ID.

					if ( $image_id = attachment_url_to_postid( $output ) ) {
						$output = absint( $image_id );
					}


			// Output

				return (string) $output;

		} // /get_media_image





} // /Modern_Post_Formats

add_action( 'after_setup_theme', 'Modern_Post_Formats::init' );

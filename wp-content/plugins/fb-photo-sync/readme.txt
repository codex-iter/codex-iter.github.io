=== FB Photo Sync ===
Contributors: mauteri
Tags: facebook, photos, gallery, albums, facebook albums, image gallery, photo gallery, fancybox, lightbox
Requires at least: 3.5
Tested up to: 4.9.4
Stable tag: trunk

Import and manage Facebook photo albums on your WordPress website.

== Description ==

Easily import Facebook photo albums from a public page into your WordPress website. Simple to set up, manage, and display on any post or page with use of the fb_album shortcode.

Things to expect in future releases:

* Auto syncing albums.
* Multiple display options.
* Album editing.

== Installation ==

1. Unzip the plugin and Upload the directory 'fb-photo-sync' to '/wp-content/plugins/' on your site
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! Get started under WP Admin Panel > Settings > FB Photo Sync

== Screenshots ==

1. Importing Facebook albums.
2. List of available albums that have been imported.
3. List of photos from album after adding shortcode to a post or page.
4. Fancybox modal after clicking on a photo.

== Changelog ==

= 0.5.9 =
* Bug: Fix to error in Facebook JavaScript SDK that was preventing importing.

= 0.5.8 =
* Enhancement: Ability to set a limit for number of photos to display. Example limit=5

= 0.5.7 =
* Change: Included middle part of the ternary operator for PHP 5.2 and less.

= 0.5.6 =
* Bug: Fix to small CSS issue with slide display

= 0.5.5 =
* Enhancement: Ability to ignore photos in shortcode with comma-separated list of image IDs. Example ignore="326020210891369,320328328127224"

= 0.5.4 =
* Change: Updated plugin to use Facebook Graph API v2.4

= 0.5.3 =
* Enhancement: Ability to turn off lazy load in shortcode with lazy_load="false"

= 0.5.2 =
* Bug: Removed function only available in PHP 5.5

= 0.5.1 =
* Enhancement: Uploads one item at a time for performance
* Enhancement: Better Facebook App instructions
* Enhancement: Slightly improved Admin UI

= 0.5 =
* Enhancement: Updated to Facebook API version 2.3
* Enhancement: Allow private photo syncing
* Change: Functionality now requires Facebook App

= 0.4.1 =
* Enhancement: More error handling when importing albums.
* Bug: Fixed lightGallery lightbbox text display issue.

= 0.4 =
* Enhancement: Cache buster.
* Change: Replaced Fancybox with lightGallery lightbox.
* Bug: Fixed import problem of large albums.
* Enhancement: Improved loader when importing.
* Enhancement: Added Lazy Load to images.

= 0.3.4 =
* Bug: Fixed 25-album-limit issue for pages with many albums.
* Enhancement: Added photo count to album list pre-import.
* Enhancement: Added nonce check for Ajax calls.

= 0.3.3 =
* Enhancement: Added order parameter (asc and desc) to shortcode [fb_album id="8720954366" wp_photos="true" order="asc"] (desc is default).

= 0.3.2 =
* Change: Project URL.
* Change: Updated ajax endpoints to use wp functions.
* Change: Slight UI update to importer page in admin.

= 0.3.1 =
* Change: Removed ColorBox and replaced it with FancyBox V1 which is GPL compatible license and better than ColorBox.

= 0.3 =
* Change: Removed FancyBox and replaced it with ColorBox. All software needs to be GPL (or compatible license).

= 0.2 =
* Enhancement: Added ability to import images from Facebook into WordPress media library.

= 0.1 =
* Initial release.

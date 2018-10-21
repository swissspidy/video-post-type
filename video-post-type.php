<?php
/**
 * Plugin Name: Video Post Type
 * Plugin URI:  https://github.com/swissspidy/video-post-type/
 * Description: Adds a simple video post type to the site.
 * Version:     1.0.0
 * Author:      Pascal Birchler
 * Author URI:  https://pascalbirchler.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: video-post-type
 * Domain Path: /languages
 *
 * @package VideoPostType
 */

namespace VideoPostType;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\bootstrap' );

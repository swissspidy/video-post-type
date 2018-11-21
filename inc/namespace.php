<?php
/**
 * Main plugin functionality.
 *
 * @package VideoPostType
 */

namespace VideoPostType;

use WP_Post;
const POST_TYPE_NAME = 'video';

/**
 * Initializes the plugin.
 */
function bootstrap() {
	add_action( 'init', __NAMESPACE__ . '\load_textdomain' );
	add_action( 'init', __NAMESPACE__ . '\register_post_type' );
	add_action( 'init', __NAMESPACE__ . '\register_post_meta' );
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_block_editor_assets' );

	add_action( 'widgets_init', __NAMESPACE__ . '\register_widget' );

	add_filter( 'write_your_story', __NAMESPACE__ . '\filter_body_placeholder', 10, 2 );
}

/**
 * Loads translations.
 */
function load_textdomain(): void {
	load_plugin_textdomain(
		'video-post-type',
		false,
		dirname( plugin_basename( __DIR__ ) ) . '/languages'
	);
}

/**
 * Registers the recommendation custom post type.
 */
function register_post_type() {
	$labels = [
		'name'               => _x( 'Videos', 'Post Type General Name', 'video-post-type' ),
		'singular_name'      => _x( 'Video', 'Post Type Singular Name', 'video-post-type' ),
		'menu_name'          => __( 'Videos', 'video-post-type' ),
		'all_items'          => __( 'All Videos', 'video-post-type' ),
		'view_item'          => __( 'View Video', 'video-post-type' ),
		'add_new_item'       => __( 'Add New Video', 'video-post-type' ),
		'add_new'            => __( 'New Video', 'video-post-type' ),
		'edit_item'          => __( 'Edit Video', 'video-post-type' ),
		'update_item'        => __( 'Update Video', 'video-post-type' ),
		'search_items'       => __( 'Search videos', 'video-post-type' ),
		'not_found'          => __( 'No videos found', 'video-post-type' ),
		'not_found_in_trash' => __( 'No videos found in Trash', 'video-post-type' ),
	];

	$args = [
		'label'             => __( 'Video', 'video-post-type' ),
		'description'       => __( 'Video Posts', 'video-post-type' ),
		'labels'            => $labels,
		'supports'          => [ 'title', 'excerpt', 'editor', 'thumbnail', 'comments', 'custom-fields' ],
		'menu_icon'         => 'dashicons-video-alt2',
		'public'            => true,
		'show_in_rest'      => true,
		'show_in_admin_bar' => false,
		'rewrite'           => [
			'slug'       => 'videos',
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		],
	];

	\register_post_type( POST_TYPE_NAME, $args );
}

/**
 * Registers the custom post meta fields needed by the post type.
 */
function register_post_meta() {
	\register_post_meta(
		POST_TYPE_NAME,
		'_video_url', [
			'show_in_rest'      => true,
			'type'              => 'string',
			'description'       => __( 'Video URL', 'video-post-type' ),
			'sanitize_callback' => 'sanitize_text_field',
			'single'            => true,
		]
	);
}

/**
 * Enqueues JavaScript and CSS for the block editor.
 */
function enqueue_block_editor_assets(): void {
	global $post_type;

	if ( POST_TYPE_NAME !== $post_type ) {
		return;
	}

	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	wp_enqueue_script(
		'video-post-type',
		plugins_url( 'assets/js/editor.js', __DIR__ ),
		[
			'wp-components',
			'wp-data',
			'wp-edit-post',
			'wp-editor',
			'wp-element',
			'wp-i18n',
			'wp-plugins',
		],
		'20181022',
		true
	);

	wp_enqueue_style(
		'video-post-type',
		plugins_url( 'assets/css/editor.css', __DIR__ ),
		[],
		'20181022'
	);

	if ( \function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'video-post-type', 'video-post-type', \dirname( __DIR__ ) . '/languages' );
	}
}

/**
 * Registers a custom widget to display ads.
 */
function register_widget(): void {
	\register_widget( new Widget() );
}


/**
 * Filters the body placeholder in the block editor.
 *
 * @param  string $text Body placeholder.
 * @param WP_Post $post Current post object.
 * @return string Modified body placeholder.
 */
function filter_body_placeholder( $text, WP_Post $post ): string {
	if ( POST_TYPE_NAME !== $post->post_type ) {
		return $text;
	}

	return __( 'Write something about the video', 'video-post-type' );
}

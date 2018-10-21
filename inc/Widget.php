<?php
/**
 * @package VideoPostType
 */

namespace VideoPostType;

use WP_Query;
use WP_Widget;

/**
 * Video widget class.
 */
class Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'video-post-type',
			__( 'Latest Video', 'video-post-type' )
		);
	}

	public $args = [
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
		'before_widget' => '<div class="widget-wrap">',
		'after_widget'  => '</div></div>',
	];

	/**
	 * Displays the ad widget on the front end.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ): void {
		$query_args = [
			'post_type'      => POST_TYPE_NAME,
			'posts_per_page' => 1,
		];

		/**
		 * Filters the query args in the Latest Video widget.
		 *
		 * @param array $query_args WP_Query args.
		 */
		$query = new WP_Query( apply_filters( 'video-post-type.widget_query_args', $query_args ) );

		if ( ! $query->have_posts() ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		echo '<div class="widget-content">';

		$template_part = [ 'templates/widget', 'video' ];

		/**
		 * Filters the theme template part used in the Latest Video widget.
		 *
		 * @see get_template_part()
		 *
		 * @param string|array $template_part Template part passed to get_template_part()
		 */
		$template_part = apply_filters( 'video-post-type.widget_template_part', $template_part );

		while ( $query->have_posts() ) {
			$query->the_post();

			call_user_func_array( 'get_template_part', (array) $template_part );
		}

		wp_reset_postdata();

		if ( $instance['show_archive_link'] ) {
			?>
			<a
				href="<?php echo get_post_type_archive_link( POST_TYPE_NAME ); ?>"
				class="post-type-archive-link post-type-archive-link-video"
			>
				<?php _e( 'All videos', 'video-post-type' ); ?>
			</a>
			<?php
		}

		echo '</div>';

		echo $args['after_widget'];
	}

	/**
	 * Displays the widget's settings form.
	 *
	 * @param array $instance
	 * @return string|void
	 */
	public function form( $instance ): void {
		$title             = $instance['title'] ?? _x( 'Latest Video', 'default widget title', 'video-post-type' );
		$show_archive_link = $instance['show_archive_link'] ?? false;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title', 'video-post-type' ); ?>
			</label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $title ); ?>"
			/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_archive_link' ) ); ?>">
				<input
					id="<?php echo esc_attr( $this->get_field_id( 'show_archive_link' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_archive_link' ) ); ?>"
					type="checkbox"
					value="<?php echo esc_attr( (bool) $show_archive_link ); ?>"
					<?php checked( $show_archive_link ); ?>
				/>
				<?php esc_attr_e( 'Show archive link', 'video-post-type' ); ?>
			</label>
		</p>
		<?php

	}

	/**
	 * Handles widget settings updates.
	 *
	 * @param array $new_instance Old widget instance.
	 * @param array $old_instance New widget instance.
	 * @return array Updated widget instance.
	 */
	public function update( $new_instance, $old_instance ): array {
		$instance = [];

		$instance['title']             = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['show_archive_link'] = isset( $new_instance['show_archive_link'] ) ? (bool) $new_instance['show_archive_link'] : false;

		return $instance;
	}
}

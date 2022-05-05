<?php
/**
* File: Single.php
*
* Create a post component.
*
* @since      1.0.0
* @package    Boldgrid_Components
* @subpackage Boldgrid_Components_Shortcode
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace Boldgrid\PPBP\Component;

/**
* Class: Single
*
* Create a post component.
*
* @since 1.0.0
*/
class Post extends \WP_Widget {

	/**
	 * Default widget wrappers.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $widgetArgs = array(
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
	);

	/**
	 * Default values.
	 *
	 * @since 1.0.0
	 * @var array Default values.
	 */
	public $defaults = [
		'selected_post' => '0',
		'show_title' => '1',
		'thumbnail' => '1',
		'excerpt' => '1',
		'date' => '1',
		'author' => '0'
	];

	/**
	 * Update a widget with a new configuration.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $new_instance New instance configuration.
	 * @param  array $old_instance Old instance configuration.
	 * @return array               Updated instance config.
	 */
	public function update( $new_instance, $old_instance ) {
		return $this->sanitize( $new_instance );
	}

	/**
	 * Santize a set of instance params.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $dirtyInstance Unsanitized Values.
	 * @return array                Sanitized Values.
	 */
	public function sanitize( $dirtyInstance ) {
		$instance['selected_post'] =  ! empty( $dirtyInstance['selected_post'] ) ?
			strip_tags( $dirtyInstance['selected_post'] ) : '';

		$checkboxes = [
			'show_title',
			'thumbnail',
			'excerpt',
			'author',
			'date',
		];

		foreach ( $checkboxes as $checkbox ) {
			$instance[ $checkbox ] = ! empty( $dirtyInstance[ $checkbox ] ) ? '1' : '';
		}

		return $instance;
	}

	/**
	 * Get the image size to use.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $instance Instance config.
	 * @return string          Image Size.
	 */
	protected function getImageSize( $instance ) {
		$imageSize = 'full';

		if ( ! empty( $instance['columns'] ) && 2 < $instance['columns'] ) {
			if ( in_array( 'large', get_intermediate_image_sizes() ) ) {
				$imageSize = 'large';
			}
		}

		return $imageSize;
	}

	/**
	 * Print the base post configuration inputs.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $instance Instance.
	 */
	protected function printFormInputs( $instance ) {
		global $_wp_additional_image_sizes;

		?>
		<p>
			<h4><?php _e( 'Choose the layout options:', 'boldgrid-editor' ) ?></h4>

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>"
				name="<?php echo $this->get_field_name( 'show_title' ); ?>"
				value="1" <?php checked( $instance['show_title'], '1' ); ?> />
			<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Title', 'boldgrid-editor' ) ?></label>
			<br>

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'excerpt' ); ?>"
				name="<?php echo $this->get_field_name( 'excerpt' ); ?>"
				value="1" <?php checked( $instance['excerpt'], '1' ); ?> />
			<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e( 'Post excerpt', 'boldgrid-editor' ) ?></label>
			<br>

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'date' ); ?>"
				name="<?php echo $this->get_field_name( 'date' ); ?>"
				value="1" <?php checked( $instance['date'], '1' ); ?> />
			<label for="<?php echo $this->get_field_id('date'); ?>"><?php _e( 'Date', 'boldgrid-editor' ) ?></label>
			<br>

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'author' ); ?>"
				name="<?php echo $this->get_field_name( 'author' ); ?>"
				value="1" <?php checked( $instance['author'], '1' ); ?> />
			<label for="<?php echo $this->get_field_id('author'); ?>"><?php _e( 'Author', 'boldgrid-editor' ) ?></label>
			<br>

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'thumbnail' ); ?>"
				name="<?php echo $this->get_field_name( 'thumbnail' ); ?>"
				value="1" <?php checked( $instance['thumbnail'], '1' ) ?> />
			<label for="<?php echo $this->get_field_id('thumbnail'); ?>"><?php _e( 'Post Image', 'boldgrid-editor' ) ?></label>
		</p>
	<?php  }

	/**
	 * Print a single post excerpt HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_Post $widgetPost    Post Object.
	 * @param  array $args      Widget arguments.
	 * @param  array $instance  Instance configuration.
	 */
	protected function printExcerpt( $widgetPost, $args, $instance ) {
		global $post;
		$currentPost = $post;

		// Set the current post to the post in the argument.
		$post = $widgetPost;
		setup_postdata( $widgetPost );
		$imageSize = $this->getImageSize( $instance );
		?>

		<div class="bgc-single-article">
			<?php if ( ! empty( $instance['thumbnail'] ) ) { ?>
				<a href="<?php the_permalink(); ?>">
					<div class="bgc-single-image" style="background-image: url(<?php print get_the_post_thumbnail_url( $widgetPost, $imageSize ) ?>)">
						<div class="image-opacity"></div>
						<?php if ( ! empty( $instance['date'] ) ) { ?>
						<div class="date">
							<div><?php print get_the_date('M') ?></div>
							<div><?php print get_the_date('d') ?></div>
						</div>
						<?php } ?>
					</div>
				</a>
			<?php } ?>
			<div class="bgc-single-body">
			<?php if ( ! empty( $instance['show_title'] ) ) { ?>
				<a class="bgc-single-title" href="<?php print get_the_permalink( $widgetPost ); ?>">
					<?php
						echo $args['before_title'];
						print get_the_title( $widgetPost );
						echo $args['after_title'];
					?>
				</a>
			<?php } ?>

			<?php if ( ! empty( $instance['author'] ) ) { ?>
				<p>By <?php the_author_posts_link()?> | <?php the_category( '&bull;' ) ?></p>
			<?php } ?>

			<?php if ( empty( $instance['thumbnail'] ) && ! empty( $instance['date'] ) ) { ?>
				<p><span><?php print get_the_date('M d, Y') ?></span></p>
			<?php } ?>

			<?php if ( ! empty( $instance['excerpt'] ) ) { ?>
				<div class="bgc-single-excerpt">
					<?php the_excerpt() ?>
				</div>
			<?php } ?>

			</div>
		</div>
	<?php

		// Revert the current post.
		if ( $currentPost ) {
			setup_postdata( $currentPost );
			$post = $currentPost;
		}
	}
}

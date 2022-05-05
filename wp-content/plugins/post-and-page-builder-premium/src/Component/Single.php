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
class Single extends Post {

	/**
	 * Setup the widget configurations.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$id = 'boldgrid_component_post';

		parent::__construct(
			$id,
			__( 'Single Post', 'boldgrid-editor' ),
			array(
				'classname' => 'bgc-single',
				'description' => __( 'A post excerpt.', 'boldgrid-editor' )
			)
		);
	}

	/**
	 * Render a widget.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args     General widget configurations.
	 * @param  array $instance Widget instance arguments.
	 */
	public function widget( $args, $instance )  {
		$args = array_merge( self::$widgetArgs, $args );

		echo $args['before_widget'];

		$post = ! empty( $instance['selected_post'] ) ? get_post( $instance['selected_post'] ) : null;

		if ( $post ) {
			$this->printExcerpt( $post, $args, $instance );

		} else {
			echo $args['before_title'];
			_e( 'No post selected yet', 'boldgrid-editor' );
			echo $args['after_title'];
		}

		echo $args['after_widget'];
	}

	/**
	 * Print our a form that allowing the widget configs to be updated.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $instance Widget instance configs.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>
		<p>
			<h4><?php _e( 'Select your post:', 'boldgrid-editor' ); ?></h4>

			<?php
			$wp_query = new \WP_Query( array(
				'post_type' => 'post',
				'posts_per_page' => -1
			) );

			if ( $wp_query->have_posts() ) { ?>
				<select style="width: 100%;"
					id='<?php echo $this->get_field_id( 'selected_post' ); ?>'
					name="<?php echo $this->get_field_name( 'selected_post' ); ?>">
				<option value="0" <?php selected( $instance['selected_post'], '0' );
					?>><?php _e( 'No Post Selected', 'boldgrid-editor' ); ?></option>
				<?php
				while ( $wp_query->have_posts() ) {
					$wp_query->the_post(); ?>
					<option value="<?php echo get_the_ID(); ?>"
						<?php selected( $instance['selected_post'], get_the_ID() );
							?>><?php echo get_the_title() ?: _( 'No Title' ) . ' - ' . 'ID: ' . get_the_ID(); ?></option>
				<?php } ?>
				</select>
				<?php
				$this->printFormInputs( $instance );
			} else {
				?><p><strong><?php _e( 'No posts found', 'boldgrid-editor' ); ?></strong></p><?php
			}
			?>
		</p>
		<?php
	}
}

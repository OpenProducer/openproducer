<?php
/**
* File: PostList.php
*
* Create a post component.
*
* @since      1.0.0
* @package    Boldgrid\PPBP
* @subpackage Component
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace Boldgrid\PPBP\Component;

/**
* Class: PostList
*
* Create a post component.
*
* @since 1.0.0
*/
class PostList extends Post {

	/**
	 * Default values.
	 *
	 * @since 1.0.0
	 * @var array Values.
	 */
	protected $listDefaults = [
		'selected_post' => 'all',
		'sorting' => 'newest',
		'limit' => 8,
		'columns' => 1
	];

	/**
	 * Max number of columns.
	 *
	 * @var integer
	 */
	protected $maxColumns = 4;

	/**
	 * Setup the widget configurations.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->updatedDefaults();
		$id = 'boldgrid_component_postlist';

		parent::__construct(
			$id,
			__( 'Post List', 'boldgrid-editor' ),
			array(
				'classname' => 'bgc-postlist',
				'description' => __( 'A list of post excerpts.', 'boldgrid-editor' )
			)
		);
	}

	/**
	 * Add the defaults needed for post list component to the base defaults.
	 *
	 * @since 1.0.0
	 */
	public function updatedDefaults() {
		$this->defaults = array_merge( $this->defaults, $this->listDefaults );
	}

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
		$instance = parent::update( $new_instance, $old_instance );

		$instance['limit'] = ! empty( $new_instance['limit'] ) ?
			intval( $new_instance['limit'] ) : $this->listDefaults['limit'];

		$instance['columns'] = ! empty( $new_instance['columns'] ) && $new_instance['columns'] <= $this->maxColumns ?
			intval( $new_instance['columns'] ) : $this->listDefaults['columns'];

		$instance['sorting'] = ! empty( $instance['sorting'] ) && in_array( $instance['sorting'], [ 'newest', 'oldest' ] ) ?
			$instance['sorting'] : $this->listDefaults['sorting'];

		return $instance;
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

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$posts = $this->getPosts( $instance );
		if ( ! empty( $posts ) ) {
			$columns = ! empty( $instance[ 'columns' ] ) ? $instance[ 'columns' ] : 1;
			echo '<div data-columns="' . esc_attr( $columns ) . '">';
			foreach ( $posts as $post ) {
				$this->printExcerpt( $post, $args, $instance );
			}
			echo '</div>';
		} else {
			echo $args['before_title'];
			_e( 'No posts found', 'boldgrid-editor' );
			echo $args['after_title'];
		}

		echo $args['after_widget'];
	}

	/**
	 * Get posts to display.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $instance Widget instance.
	 * @return array           Posts.
	 */
	public function getPosts( $instance ) {
		$selection = ! empty( $instance[ 'selected_post' ] ) ? $instance[ 'selected_post' ] : null;
		$sorting = ! empty( $instance[ 'sorting' ] ) ? $instance[ 'sorting' ] : null;
		$status = $this->getPostStatus();

		$args = [
			'numberposts' => intval( $instance[ 'limit' ] ),
			'orderby' => 'post_date',
			'order' => 'newest' === $sorting ? 'DESC' : 'ASC',
			'post_type' => 'post',
			'post_status' => $status
		];

		$posts = array();
		if ( 0 === strpos( $selection, 'tag-' ) ) {
			$selection = str_replace( 'tag-', '', $selection );
			$args['tag_id'] = intval( $selection );
		} else if ( 0 === strpos( $selection, 'category-' ) ) {
			$selection = str_replace( 'category-', '', $selection );
			$args['category'] = intval( $selection );
		}

		$posts = get_posts( $args );

		return $posts;
	}

	/**
	 * Print our a form that allowing the widget configs to be updated.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $instance Widget instance configs.
	 */
	public function form( $instance ) {
		$tags = get_tags();
		$categories = get_categories();

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>
		<p>
			<h4><?php _e( 'Select posts to display:', 'boldgrid-editor' ); ?></h4>
			<select style="width: 100%;"
				id='<?php echo $this->get_field_id( 'selected_post' ); ?>'
				name="<?php echo $this->get_field_name( 'selected_post' ); ?>">

			<optgroup label="General">
				<option value="all" <?php selected( $instance['selected_post'], 'all' );
					?>><?php _e( 'All Posts', 'boldgrid-editor' ); ?></option>
			</optgroup>
			<optgroup label="Categories">
			<?php
			foreach( $categories as $category ) {
				$value = 'category-' . $category->term_id;
				?>
				<option value="<?php print $value ?>" <?php selected( $value, $instance['selected_post'] );
					?>><?php echo esc_html( $category->cat_name ) ?></option>
			<?php }
			if ( empty( $categories ) ) { ?>
				<option value="category-0" <?php selected( 'category-0', $instance['selected_post'] );
					?>><?php _e( 'No Categories found', 'boldgrid-editor' ); ?></option>
			<?php } ?>
			</optgroup>

			<?php
			if ( empty( $tags ) ) { ?>
				<optgroup label="<?php _e( 'No Tags Found', 'boldgrid-editor' ); ?>"></optgroup>
			<?php } else { ?>
				<optgroup label="<?php _e( 'Tags', 'boldgrid-editor' ); ?>">
				<?php
				foreach( $tags as $tag ) {
					$value = 'tag-' . $tag->term_id;
					error_log( print_r( $tag->name, 1 ) );
					?>
					<option value="<?php print $value ?>"
						<?php selected( $value, $instance['selected_post'] );
						?>><?php echo esc_html( $tag->name ) ?></option>
				<?php } ?>
				</optgroup>
			<?php }?>

			</select>

			<h4><?php _e( 'Number of Columns:', 'boldgrid-editor' ); ?></h4>
			<p>
				<select
					id='<?php echo $this->get_field_id( 'columns' ); ?>'
					name="<?php echo $this->get_field_name( 'columns' ); ?>">
					<?php for( $count = 1; $count <= $this->maxColumns; $count++ ) { ?>
						<option value="<?php echo $count ?>"
							<?php selected( $instance['columns'], (string) $count );?>><?php echo $count ?></option>
					<?php } ?>
				</select>
			</p>

			<h4><?php _e( 'Max Posts:', 'boldgrid-editor' ); ?></h4>
			<p>
				<input id='<?php echo $this->get_field_id( 'limit' ); ?>'
					type="number" name="<?php echo $this->get_field_name( 'limit' ); ?>"
					value="<?php print $instance['limit'] ?>" min="1" max="50">
			</p>

			<h4><?php _e( 'Sorting:', 'boldgrid-editor' ); ?></h4>
			<p>
				<select
					id='<?php echo $this->get_field_id( 'sorting' ); ?>'
					name="<?php echo $this->get_field_name( 'sorting' ); ?>">
						<option value="oldest" <?php selected( $instance['sorting'], 'oldest' );?>><?php echo _e( 'Oldest First' ) ?></option>
						<option value="newest" <?php selected( $instance['sorting'], 'newest' );?>><?php echo _e( 'Newest First' ) ?></option>
				</select>
			</p>
			<?php
			$this->printFormInputs( $instance ); ?>
		</p>

		<?php
	}

	/**
	 * Get the post status to display for posts in the lists.
	 *
	 * This was introduced so that when a user is customizing an starter content draft,
	 * they can also view post drafts.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of posts status to display.
	 */
	protected function getPostStatus() {
		global $post;
		$post_id = ! empty( $_POST[ 'post_id' ] ) ? intval( $_POST[ 'post_id' ] ) : null;

		$status = [ 'publish' ];
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && $post_id ) {
			$post = get_post( $post_id );
		}

		$status = $post && in_array( $post->post_status, [ 'auto-draft', 'draft' ] ) &&
			get_post_meta( $post->ID, '_customize_changeset_uuid', true ) ?
			[ 'publish', 'auto-draft', 'draft' ] : $status;

		return $status;
	}
}

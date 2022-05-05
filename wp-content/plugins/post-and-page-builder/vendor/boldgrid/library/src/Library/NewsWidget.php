<?php
/**
 * BoldGrid Library News Widget Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library\License
 *
 * @version 2.9.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * BoldGrid Library News Widget Class.
 *
 * This class is responsible for rendering the "BoldGrid News" widget on the WordPress dashboard.
 *
 * @since 2.9.0
 */
class NewsWidget {
	/**
	 * An array of errors.
	 *
	 * @since 2.10.0
	 * @var array
	 */
	public $errors = [];

	/**
	 * Constructor.
	 *
	 * @since 2.9.0
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 2.9.0
	 *
	 * @param string $hook Hook.
	 */
	public function admin_enqueue_scripts( $hook ) {
		wp_register_style(
			'bglib-news-widget-css',
			Configs::get( 'libraryUrl' ) . 'src/assets/css/news-widget.css'
		);

		switch( $hook ) {
			case 'index.php':
				wp_enqueue_style( 'bglib-news-widget-css' );
				break;
		}
	}

	/**
	 * Display the inner contents of our news widgget.
	 *
	 * @since 2.9.0
	 */
	public function display() {
		// Get 2 posts.
		$posts = $this->getPosts( 2 );

		// If there are errors, abort.
		if ( ! empty( $this->errors ) ) {
			echo '<p>' . implode( '</p></p>', $this->errors ) . '</p>';
			return;
		}

		// If we have no news posts, abort.
		if( empty( $posts ) ) {
			echo '<p>' . esc_html__( 'There are no updates to show right now.', 'boldgrid-library' ) . '</p>';
			return;
		}

		// Finally, display the posts.
		echo '<ul>';
		foreach ( $posts as $post ) {
			// Get the url to the featured image.
			$featuredImage = '';
			if ( ! empty( $post->_embedded->{'wp:featuredmedia'}[0]->media_details->sizes->medium->source_url ) ) {
				$featuredImage = $post->_embedded->{'wp:featuredmedia'}[0]->media_details->sizes->medium->source_url;
			}

?>			<li title="<?php echo esc_attr( $post->excerpt->rendered ); ?>">
				<?php if ( ! empty( $featuredImage ) ) {
					echo '<img class="bglib-featured" src="' . esc_url( $featuredImage ) . '" />';
				} ?>
				<p class="bglib-date">
					<?php echo date( 'M jS, Y', strtotime( $post->date ) ); ?>
				</p>
				<p class="bglib-title">
					<a href='<?php echo $post->link; ?>' target='_blank'><?php echo $post->title->rendered; ?></a>
				</p>
			</li>
<?php
 		}
		echo '</ul><div style="clear:both;"></div>';
	}

	/**
	 * Get the id of a tag.
	 *
	 * @since 2.9.0
	 *
	 * @param  string $tag The tag name.
	 * @return int
	 */
	public function getTagId( $tag ) {
		$id = 0;

		$request = wp_remote_get( 'https://www.boldgrid.com/wp-json/wp/v2/tags/?slug=' . $tag );

		if( is_wp_error( $request ) ) {
			return $id;
		}

		$body = wp_remote_retrieve_body( $request );
		$body = json_decode( $body );

		$id = ! empty( $body[0]->id ) ? $body[0]->id : $id;

		return $id;
	}

	/**
	 * Get rss posts.
	 *
	 * @since 2.10.0
	 *
	 * @param  string $limit The number of posts to return.
	 * @return array         An array of posts.
	 */
	public function getPosts( $limit ) {
		// Get all posts tagged, "dashboard".
		$request = wp_remote_get( $this->getRssUrl() );

		// If we have an error, abort.
		if( is_wp_error( $request ) ) {
			if ( is_admin() || current_user_can( 'manage_options' ) ) {
				$this->errors[] = sprintf(
					// translators: An RSS error message.
					__( '<strong>RSS Error</strong>: %s', 'boldgrid-inspirations' ),
					esc_html__( $request->get_error_message() )
				);
			}

			return;
		}

		// Get our "dashboard" posts.
		$body  = wp_remote_retrieve_body( $request );
		$posts = (array) json_decode( $body );

		if ( $limit ) {
			$posts = array_slice( $posts, 0, $limit );
		}

		return $posts;
	}

	/**
	 * Get the URL for our rss feed.
	 *
	 * @since 2.10.0
	 *
	 * @return string
	 */
	public function getRssUrl() {
		// Adding _embed includes the featured image urls, without the need to make another call.
		$url = 'https://www.boldgrid.com/wp-json/wp/v2/posts/?_embed&tags=' . $this->getTagId( 'dashboard' );

		$url .= '&key=' . Configs::get('key');

		$plugins   = [];
		$bgPlugins = array_merge(
			array_keys( Configs::get('pluginInstaller')['plugins'] ),
			array_keys( Configs::get('pluginInstaller')['wporgPlugins'] )
		);

		// Get data for only BoldGrid plugins.
		foreach ( get_plugins() as $slug => $info ) {
			if ( preg_grep('~' . strtok($slug, '/') . '~', $bgPlugins) ) {
				$plugins[] = [
					'slug'    => $slug,
					'version' => $info['Version'],
					'active'  => is_plugin_active( $slug ),
				];
			}
		}

		$url .= '&data=' . rawurlencode( gzdeflate( wp_json_encode(
			[
				'locale'  => get_locale(),
				'plugins' => $plugins,
			]
		) ) );

		return $url;
	}

	/**
	 * Add our news widget to the dashboard.
	 *
	 * Priority 5 given for backwards compatibility with BoldGrid Inspirations. If Inspirations is
	 * trying to add the news widget, this method will go first and take precedence.
	 *
	 * @since 2.9.0
	 *
	 * @priority 5
	 */
	public function wp_dashboard_setup() {
		wp_add_dashboard_widget(
			'boldgrid_news_widget',
			esc_html__( 'BoldGrid News', 'boldgrid-library' ),
			[
				$this,
				'display',
			]
		);
	}
}

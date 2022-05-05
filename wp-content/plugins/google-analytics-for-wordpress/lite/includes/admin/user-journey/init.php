<?php
/**
 * Initialize Admin - User Journey.
 *
 * @since 1.0.0
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

/**
 * Admin functions and init functionality.
 *
 * @since 1.0.0
 */
final class MonsterInsights_Lite_User_Journey_Admin {

    /**
     * Screens on which we want to load the assets.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $screens = array( 'shop_order' );

    /**
	 * Holds singleton instance
	 *
	 * @since 1.0.0
     *
	 * @var MonsterInsights_User_Journey_Admin
	 */
	private static $instance;

	/**
	 * Return Singleton instance
	 *
	 * @since 1.0.0
     *
	 * @return MonsterInsights_User_Journey_Admin
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_head', array( $this, 'add_admin_scripts' ) );
    }

    /**
     * Add required admin scripts.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_admin_scripts() {
        $current_screen = get_current_screen();

		if ( is_object( $current_screen ) ) {
			if ( in_array( $current_screen->id, $this->screens ) ) {
				wp_enqueue_style( 'monsterinsights-lite-user-journey-admin', MONSTERINSIGHTS_PLUGIN_URL . 'lite/includes/admin/user-journey/assets/css/user-journey.css', MONSTERINSIGHTS_VERSION );
			}
		}
    }
}
// Initialize the class
MonsterInsights_Lite_User_Journey_Admin::get_instance();

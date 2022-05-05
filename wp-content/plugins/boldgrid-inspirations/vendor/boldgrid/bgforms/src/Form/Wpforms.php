<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid\Library\Form
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Form;

/**
 * BoldGrid Inspirations wpforms class.
 *
 * @since 1.0.0
 */
class Wpforms {
	/**
	 * WPForms package download URL address.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $package_url = 'https://downloads.wordpress.org/plugin/wpforms-lite.zip';

	/**
	 * Plugin titles to match.
	 *
	 * @since 1.0.0
	 *
	 * @staticvar array
	 */
	public static $match_names = array(
			'WPForms Ultimate',
			'WPForms Pro',
			'WPForms Plus',
			'WPForms Basic',
			'WPForms',
			'WPForms Lite',
		);

	/**
	 * BoldGrid WPForms forms.
	 *
	 * An array of WPForms as JSON object strings.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $forms = array();

	/**
	 * Get WPForms package download URL address.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_package_url() {
		return $package_url;
	}

	/**
	 * Get BoldGrid WPForms forms.
	 *
	 * @since 1.0.0
	 *
	 * @see \Boldgrid\Library\Form\Wpforms::retrieve_forms()
	 *
	 * @return array
	 */
	public function get_forms() {
		if ( empty( $this->forms ) ) {
			$this->retrieve_forms();
		}

		return $this->forms;
	}

	/**
	 * Retrieve WPF forms from our asset/api server.
	 *
	 * @since 1.0.0
	 *
	 * @see \Boldgrid\Library\Library\Configs::get()
	 *
	 * @access private
	 */
	private function retrieve_forms() {
		if ( ( $this->forms = get_site_transient( 'boldgrid_wpforms' ) ) ) {
			return $this->forms;
		}

		$this->forms = array();

		$url = \Boldgrid\Library\Library\Configs::get( 'api' ) . '/v1/forms';

		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {
			$this->forms = json_decode( $response['body'], true );
		}

		set_site_transient( 'boldgrid_wpforms' , $this->forms, 43200 );
	}

	/**
	 * Get a BoldGrid WPForms form.
	 *
	 * @since 1.0.0
	 *
	 * @see \Boldgrid\Library\Form\Wpforms::get_forms()
	 *
	 * @param int $form_id Form id number.
	 * @return string
	 */
	public function get_form( $form_id ) {
		$form_id = (string) $form_id;

		$json = '';

		$forms = $this->get_forms();

		foreach ( $forms as $form ) {
			if ( $form['form_id'] === $form_id ) {
				$json = $form['json'];
				break;
			}
		}

		return $json;
	}

	/**
	 * Import a form.
	 *
	 * @since 1.0.0
	 *
	 * @see \Boldgrid\Library\Form\Wpforms\get_form()
	 * @see esc_html()
	 * @see sanitize_text_field()
	 * @see \Boldgrid\Library\Form\Wpforms\get_id_by_title()
	 * @see wp_insert_post()
	 *
	 * @param  int $form_id Form id number.
	 * @return int          A post id for the form (which is also the WPForms form id).
	 */
	public function import_form( $form_id ) {
		$json = $this->get_form( $form_id );

		if ( empty( $json ) ) {
			return false;
		}

		$title = esc_html( sanitize_text_field( json_decode( $json )->settings->form_title ) );

		$this->register_cpt();

		// If the form already exists, then just return the form/post id.
		if ( ( $form_id = $this->get_id_by_title( $title ) ) ) {
			return $form_id;
		}

		$form = array(
			'post_title' => $title,
			'post_status' => 'publish',
			'post_type' => 'wpforms',
			'post_content' => $json,
		);

		$form_id = wp_insert_post( $form );

		// Update the content form id with the post id.
		$json_decoded = json_decode( $json, true );
		$json_decoded['id'] = $form_id;
		$json = json_encode( $json_decoded, JSON_HEX_QUOT );

		$form = array(
			'ID'           => $form_id,
			'post_content' => $json,
		);

		$form_id = wp_update_post( $form );

		return $form_id;
	}

	/**
	 * Import all available forms.
	 *
	 * @since 1.0.0
	 *
	 * @see \Boldgrid\Library\Form\Wpforms\get_forms()
	 * @see \Boldgrid\Library\Form\Wpforms\import_form()
	 */
	public function import_forms() {
		$forms = $this->get_forms();

		foreach ( $forms as $form ) {
			$status = $this->import_form( $form['form_id'] );
		}
	}

	/**
	 * Get a WPForms form id (post id).
	 *
	 * @since 1.0.0
	 *
	 * @see get_page_by_title()
	 *
	 * @return int|null The WPForms form id, which is also the post id.
	 */
	public function get_id_by_title( $title ) {
		$post_id = null;
		$post = get_page_by_title( $title, OBJECT, 'wpforms' );

		return ! empty( $post->ID ) ? $post->ID : null;
	}

	/**
	 * Get a WPForms form id (post id) from a BoldGrid form id.
	 *
	 * @sinec 1.0.0
	 *
	 * @see \Boldgrid\Library\Form\Wpforms\get_form()
	 * @see esc_html()
	 * @see sanitize_text_field()
	 * @see \Boldgrid\Library\Form\Wpforms\get_forms()
	 *
	 * @param int $id
	 * @return int
	 */
	public function get_post_id( $id ) {
		$json = $this->get_form( $id );

		$title = esc_html( sanitize_text_field( json_decode( $json )->settings->form_title ) );

		return $this->get_id_by_title( $title );
	}

	/**
	 * Install WPForms plugin.
	 *
	 * @since 1.0.0
	 *
	 * @see Plugin_Upgrader()
	 * @see Plugin_Installer_Skin()
	 * @see Plugin_Upgrader::install()
	 *
	 * @return bool
	 */
	public function install_plugin() {
		$bgforms = new Forms();

		if ( $bgforms->get_wpforms_slug() ) {
			// Already installed.
			return true;
		}

		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/misc.php';

		$upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin() );
		$result   = $upgrader->install( $this->package_url );

		if ( empty( $upgrader->skin->result ) || is_wp_error( $upgrader->skin->result ) ) {
				$result = false;
		}

		return true === $result;
	}

	/**
	 * Convert shortcodes for use with WPForms, for BoldGrid-deployed pages.
	 *
	 * @since 1.0.0
	 *
	 * @see \Boldgrid\Library\Form\Wpforms\get_post_id()
	 *
	 * @param array $post WP post array.
	 * @return array
	 */
	public function convert_nf_shortcodes( array $post ) {
		preg_match_all( '/\[ninja_forms id="(\d+)"\]/', $post['post_content'], $matches );

		foreach( $matches[1] as $form_id ) {
			$post_id = $this->get_post_id( $form_id );

			$post['post_content'] = str_replace(
				'[ninja_forms id="' . $form_id . '"]',
				'[wpforms id="' . $post_id . '"]',
				$post['post_content']
			);
		}

		return $post;
	}

	/**
	 * Registers the custom post type to be used for forms.
	 *
	 * @since 1.0.0
	 *
	 * @return null
	 */
	public function register_cpt() {
		if ( post_type_exists( 'wpforms' ) ) {
			return;
		}

		// Custom post type arguments, which can be filtered if needed.
		$args = apply_filters( 'wpforms_post_type_args',
			array(
				'labels'              => array(),
				'public'              => false,
				'exclude_from_search' => true,
				'show_ui'             => false,
				'show_in_admin_bar'   => false,
				'rewrite'             => false,
				'query_var'           => false,
				'can_export'          => false,
				'supports'            => array( 'title' ),
			)
		);

		// Register the post type
		register_post_type( 'wpforms', $args );
	}
}

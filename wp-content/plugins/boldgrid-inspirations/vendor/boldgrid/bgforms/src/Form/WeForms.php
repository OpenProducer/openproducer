<?php
/**
 * File: WeForms.php
 *
 * @package Boldgrid\Library\Form
 * @subpackage Boldgrid\Library\Form\WeForms
 * @copyright BoldGrid.com
 * @author BoldGrid.com <wpb@boldgrid.com>
 *
 * phpcs:disable WordPress.VIP
 */

namespace Boldgrid\Library\Form;

/**
 * Class: WeForms
 *
 * @since 1.2.0
 */
class WeForms {
	/**
	 * The weForms plugin package download URL address.
	 *
	 * @since 1.2.0
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $package_url = 'https://downloads.wordpress.org/plugin/weforms.zip';

	/**
	 * Plugin titles to match.
	 *
	 * @since 1.2.0
	 *
	 * @var array
	 * @static
	 */
	public static $match_names = [
		'weForms',
	];

	/**
	 * BoldGrid forms.
	 *
	 * An array of forms as JSON object strings.
	 *
	 * @since 1.2.0
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $forms = [];

	/**
	 * Conditional stub.
	 *
	 * Adopted from the weforms plugin 1.4.2.
	 *
	 * @since 1.2.0
	 *
	 * @var array
	 */
	public $conditionals = [
		'condition_status' => 'no',
		'cond_field'       => [],
		'cond_operator'    => [ '=' ],
		'cond_option'      => [ '- select -' ],
		'cond_logic'       => 'all',
	];

	/**
	 * Get package download URL address.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public function get_package_url() {
		return $package_url;
	}

	/**
	 * Get forms.
	 *
	 * @since 1.2.0
	 *
	 * @see \Boldgrid\Library\Form\Weforms::retrieve_forms()
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
	 * Retrieve forms from our api server.
	 *
	 * @since 1.2.0
	 *
	 * @see \Boldgrid\Library\Library\Configs::get()
	 *
	 * @access private
	 */
	private function retrieve_forms() {
		$this->forms = get_site_transient( 'boldgrid_weforms' );

		if ( $this->forms ) {
			return $this->forms;
		}

		$this->forms = [];

		$url = \Boldgrid\Library\Library\Configs::get( 'api' ) . '/v1/forms';

		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {
			$this->forms = json_decode( $response['body'], true );
		}

		set_site_transient( 'boldgrid_weforms', $this->forms, 43200 );
	}

	/**
	 * Get a form.
	 *
	 * @since 1.2.0
	 *
	 * @see \Boldgrid\Library\Form\Weforms::get_forms()
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
	 * @since 1.2.0
	 *
	 * @see self::get_form()
	 * @see esc_html()
	 * @see sanitize_text_field()
	 * @see self::get_id_by_title()
	 * @see wp_insert_post()
	 * @see get_form_settings()
	 * @see update_post_meta()
	 *
	 * @param  int $form_id Form id number.
	 * @return int          A post id for the form (which is also the weForms form id).
	 */
	public function import_form( $form_id ) {
		$json = $this->get_form( $form_id );

		if ( empty( $json ) ) {
			return false;
		}

		$title = esc_html( sanitize_text_field( json_decode( $json )->settings->form_title ) );

		$this->register_cpt();

		$form_id = $this->get_id_by_title( $title );

		// If the form already exists, then just return the form/post id.
		if ( $form_id ) {
			return $form_id;
		}

		$form = [
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_type'    => 'wpuf_contact_form',
			'post_content' => $json,
		];

		$form_id = wp_insert_post( $form );

		// Update the content form id with the post id.
		$json_decoded       = json_decode( $json, true );
		$json_decoded['id'] = $form_id;
		$json               = wp_json_encode( $json_decoded, JSON_HEX_QUOT );
		$form               = [
			'ID'           => $form_id,
			'post_content' => $json,
		];
		$form_id            = wp_update_post( $form );

		$form_fields = $this->get_form_fields( $json );
		foreach ( $form_fields as $menu_order => $field ) {
			$form_field = [
				'post_type'    => 'wpuf_input',
				'post_status'  => 'publish',
				'post_content' => maybe_serialize( $field ),
				'post_parent'  => $form_id,
				'menu_order'   => $menu_order,
			];
			wp_insert_post( $form_field );
		}

		$settings = $this->get_form_settings( $json );
		update_post_meta( $form_id, 'wpuf_form_settings', $settings );

		$notifications = $this->get_form_notifications( $json );
		update_post_meta( $form_id, 'notifications', $notifications );

		return $form_id;
	}

	/**
	 * Import all available forms.
	 *
	 * @since 1.2.0
	 *
	 * @see self::get_forms()
	 * @see self::import_form()
	 */
	public function import_forms() {
		$forms = $this->get_forms();

		foreach ( $forms as $form ) {
			$status = $this->import_form( $form['form_id'] );
		}
	}

	/**
	 * Get a form id (post id).
	 *
	 * @since 1.2.0
	 *
	 * @see get_page_by_title()
	 *
	 * @param  string $title Form title.
	 * @return int|null The form id, which is also the post id.
	 */
	public function get_id_by_title( $title ) {
		$post_id = null;
		$post    = get_page_by_title( $title, OBJECT, 'wpuf_contact_form' );

		return ! empty( $post->ID ) ? $post->ID : null;
	}

	/**
	 * Get a form id (post id) from a BoldGrid form id.
	 *
	 * @sinec 1.2.0
	 *
	 * @see self::get_form()
	 * @see esc_html()
	 * @see sanitize_text_field()
	 * @see self::get_forms()
	 *
	 * @param int $id Form/port id number.
	 * @return int
	 */
	public function get_post_id( $id ) {
		$json = $this->get_form( $id );

		$title = esc_html( sanitize_text_field( json_decode( $json )->settings->form_title ) );

		return $this->get_id_by_title( $title );
	}

	/**
	 * Install plugin.
	 *
	 * @since 1.2.0
	 *
	 * @see Plugin_Upgrader()
	 * @see Plugin_Installer_Skin()
	 * @see Plugin_Upgrader::install()
	 *
	 * @return bool
	 */
	public function install_plugin() {
		$bgforms = new Forms();

		if ( $bgforms->get_weforms_slug() ) {
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
	 * Convert shortcodes for use with weforms, for BoldGrid-deployed pages.
	 *
	 * @since 1.2.0
	 *
	 * @see self::get_post_id()
	 *
	 * @param  array $post WP post array.
	 * @return array
	 */
	public function convert_nf_shortcodes( array $post ) {
		preg_match_all( '/\[ninja_forms id="(\d+)"\]/', $post['post_content'], $matches );

		foreach ( $matches[1] as $form_id ) {
			$post_id = $this->get_post_id( $form_id );

			$post['post_content'] = str_replace(
				'[ninja_forms id="' . $form_id . '"]',
				'[weforms id="' . $post_id . '"]',
				$post['post_content']
			);
		}

		return $post;
	}

	/**
	 * Registers the custom post type to be used for forms.
	 *
	 * @since 1.2.0
	 */
	public function register_cpt() {
		if ( post_type_exists( 'wpuf_contact_form' ) ) {
			return;
		}

		// Custom post type arguments, which can be filtered if needed.
		$args = apply_filters( 'weforms_post_type_args',
			[
				'labels'              => [],
				'public'              => false,
				'exclude_from_search' => true,
				'show_ui'             => false,
				'show_in_admin_bar'   => false,
				'rewrite'             => false,
				'query_var'           => false,
				'can_export'          => false,
				'supports'            => [ 'title' ],
			]
		);

		register_post_type( 'wpuf_contact_form', $args );
	}

	/**
	 * Get all form fields of a form.
	 *
	 * Adopted from the weforms plugin 1.4.2.
	 *
	 * @since 1.2.0
	 *
	 * @param  string $form Form content in JSON format.
	 * @return array
	 */
	public function get_form_fields( $form ) {
		$form_fields = [];
		$parsed      = json_decode( $form, true );

		if ( empty( $parsed['fields'] ) ) {
			return $form_fields;
		}

		foreach ( $parsed['fields'] as $menu_order => $field ) {
			// Avoid empty meta_key.
			$field['id'] = $field['type'] . '_' . $field['id'];

			// Avoid empty placeholder.
			$field['placeholder'] = empty( $field['placeholder'] ) ? '' : $field['placeholder'];

			switch ( $field['type'] ) {
				case 'text':
				case 'email':
				case 'textarea':
					$form_fields[] = $this->get_form_field( $field['type'], [
						'required'    => ! empty( $field['required'] ) ? 'yes' : 'no',
						'label'       => $field['label'],
						'name'        => $field['id'],
						'help'        => $field['description'],
						'css_class'   => $field['css'],
						'placeholder' => $field['placeholder'],
						'default'     => ! empty( $field['default_value'] ) ? $field['default_value'] : '',
					] );
					break;

				case 'select':
				case 'radio':
				case 'checkbox':
					$form_fields[] = $this->get_form_field( $field['type'], [
						'required'    => ! empty( $field['required'] ) ? 'yes' : 'no',
						'label'       => $field['label'],
						'name'        => $field['id'],
						'help'        => $field['description'],
						'css_class'   => $field['css'],
						'placeholder' => $field['placeholder'],
						'selected'    => ! empty( $field['default_value'] ) ? $field['default_value'] : '',
						'options'     => $this->get_options( $field ),
					] );
					break;

				case 'name':
					$form_fields[] = $this->get_form_field( $field['type'], [
						'required'    => ! empty( $field['required'] ) ? 'yes' : 'no',
						'label'       => $field['label'],
						'name'        => $field['id'],
						'help'        => $field['description'],
						'css_class'   => $field['css'],
						'format'      => ( 'first-last' === $field['format'] ) ? 'first-last' : 'first-middle-last',
						'hide_subs'   => ! empty( $field['sublabel_hide'] ),
						'first_name'  => [
							'placeholder' => $field['first_placeholder'],
							'default'     => $field['first_default'],
							'sub'         => esc_html__( 'First', 'weforms' ),
						],
						'middle_name' => [
							'placeholder' => $field['middle_placeholder'],
							'default'     => $field['middle_default'],
							'sub'         => esc_html__( 'Middle', 'weforms' ),
						],
						'last_name'   => [
							'placeholder' => $field['last_placeholder'],
							'default'     => $field['last_default'],
							'sub'         => esc_html__( 'Last', 'weforms' ),
						],
					] );
					break;
			}
		}

		return $form_fields;
	}

	/**
	 * Get form field.
	 *
	 * Adopted from the weforms plugin 1.4.2.
	 *
	 * @since 1.2.0
	 *
	 * @param  string $type Field type.
	 * @param  array  $args Arguments.
	 * @return array
	 */
	public function get_form_field( $type, $args = [] ) {
		$defaults = [
			'required'           => 'no',
			'label'              => '',
			'name'               => '',
			'help'               => '',
			'css_class'          => '',
			'placeholder'        => '',
			'value'              => '',
			'default'            => '',
			'options'            => [],
			'step'               => '',
			'min'                => '',
			'max'                => '',
			'extension'          => '',
			'max_size'           => '',
			'size'               => '',
			'first_placeholder'  => '',
			'first_default'      => '',
			'middle_placeholder' => '',
			'middle_default'     => '',
			'last_placeholder'   => '',
			'last_default'       => '',
			'first_name'         => '',
			'middle_name'        => '',
			'last_name'          => '',
			'duplicate'          => null,
		];

		$args = wp_parse_args( $args, $defaults );

		switch ( $type ) {
			case 'text':
				$field_content = [
					'input_type'       => 'text',
					'template'         => 'text_field',
					'required'         => $args['required'],
					'label'            => $args['label'],
					'name'             => $args['name'],
					'is_meta'          => 'yes',
					'help'             => $args['help'],
					'css'              => $args['css_class'],
					'placeholder'      => $args['placeholder'],
					'default'          => $args['default'],
					'size'             => $args['size'],
					'word_restriction' => '',
					'wpuf_cond'        => $this->conditionals,
					'duplicate'        => $args['duplicate'],
				];
				break;

			case 'email':
				$field_content = [
					'input_type'       => 'email',
					'template'         => 'email_address',
					'required'         => $args['required'],
					'label'            => $args['label'],
					'name'             => $args['name'],
					'is_meta'          => 'yes',
					'help'             => $args['help'],
					'css'              => $args['css_class'],
					'placeholder'      => $args['placeholder'],
					'default'          => $args['default'],
					'size'             => $args['size'],
					'word_restriction' => '',
					'wpuf_cond'        => $this->conditionals,
					'duplicate'        => $args['duplicate'],
				];
				break;

			case 'textarea':
				$field_content = [
					'input_type'       => 'textarea',
					'template'         => 'textarea_field',
					'required'         => $args['required'],
					'label'            => $args['label'],
					'name'             => $args['name'],
					'is_meta'          => 'yes',
					'help'             => $args['help'],
					'css'              => $args['css_class'],
					'rows'             => 5,
					'cols'             => 25,
					'placeholder'      => $args['placeholder'],
					'default'          => $args['default'],
					'rich'             => 'no',
					'word_restriction' => '',
					'wpuf_cond'        => $this->conditionals,
				];
				break;

			case 'select':
				$field_content = [
					'input_type' => 'select',
					'template'   => 'dropdown_field',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'selected'   => '',
					'inline'     => 'no',
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'multiselect':
				$field_content = [
					'input_type' => 'multiselect',
					'template'   => 'multiple_select',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'selected'   => '',
					'first'      => __( '- select -', 'weforms' ),
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'date':
				$field_content = [
					'input_type'      => 'date',
					'template'        => 'date_field',
					'required'        => $args['required'],
					'label'           => $args['label'],
					'name'            => $args['name'],
					'is_meta'         => 'yes',
					'help'            => '',
					'css'             => $args['css_class'],
					'format'          => 'dd/mm/yy',
					'time'            => '',
					'is_publish_time' => '',
					'wpuf_cond'       => $this->conditionals,
				];
				break;

			case 'range':
			case 'number':
				$field_content = [
					'input_type'      => 'numeric_text',
					'template'        => 'numeric_text_field',
					'required'        => $args['required'],
					'label'           => $args['label'],
					'name'            => $args['name'],
					'is_meta'         => 'yes',
					'help'            => '',
					'css'             => $args['css_class'],
					'placeholder'     => $args['placeholder'],
					'default'         => $args['value'],
					'size'            => 40,
					'step_text_field' => $args['step'],
					'min_value_field' => $args['min'],
					'max_value_field' => $args['max'],
					'wpuf_cond'       => $this->conditionals,
				];
				break;

			case 'url':
				$field_content = [
					'input_type'       => 'url',
					'template'         => 'website_url',
					'required'         => $args['required'],
					'label'            => $args['label'],
					'name'             => $args['name'],
					'is_meta'          => 'yes',
					'help'             => '',
					'css'              => $args['css_class'],
					'placeholder'      => '',
					'default'          => '',
					'size'             => 40,
					'word_restriction' => '',
					'wpuf_cond'        => $this->conditionals,
					'duplicate'        => $args['duplicate'],
				];
				break;

			case 'checkbox':
				$field_content = [
					'input_type' => 'checkbox',
					'template'   => 'checkbox_field',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'selected'   => '',
					'inline'     => 'no',
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'radio':
				$field_content = [
					'input_type' => 'radio',
					'template'   => 'radio_field',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'selected'   => '',
					'inline'     => 'no',
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'hidden':
				$field_content = [
					'input_type' => 'hidden',
					'template'   => 'custom_hidden_field',
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'section_break':
				$field_content = [
					'input_type' => 'section_break',
					'template'   => 'section_break',
					'label'      => $args['label'],
					'name'       => $args['name'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'html':
				$field_content = [
					'input_type' => 'html',
					'template'   => 'custom_html',
					'label'      => $args['label'],
					'name'       => $args['name'],
					'html'       => $args['default'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'toc':
				$field_content = [
					'input_type'    => 'toc',
					'template'      => 'toc',
					'required'      => $args['required'],
					'name'          => $args['name'],
					'description'   => $args['label'],
					'is_meta'       => 'yes',
					'show_checkbox' => true,
					'wpuf_cond'     => $this->conditionals,
				];
				break;

			case 'recaptcha':
				$field_content = [
					'input_type'       => 'recaptcha',
					'template'         => 'recaptcha',
					'required'         => $args['required'],
					'label'            => $args['label'],
					'name'             => $args['name'],
					'recaptcha_type'   => 'enable_no_captcha',
					'is_meta'          => 'yes',
					'help'             => '',
					'css'              => $args['css_class'],
					'placeholder'      => '',
					'default'          => '',
					'size'             => 40,
					'word_restriction' => '',
					'wpuf_cond'        => $this->conditionals,
				];
				break;

			case 'file':
				$field_content = [
					'input_type' => 'file_upload',
					'template'   => 'file_upload',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => $args['help'],
					'css'        => $args['css_class'],
					'max_size'   => $args['max_size'],
					'count'      => '1',
					'extension'  => $args['extension'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'name':
				$field_content = [
					'input_type'  => 'name',
					'template'    => 'name_field',
					'required'    => $args['required'],
					'label'       => $args['label'],
					'name'        => $args['name'],
					'is_meta'     => 'yes',
					'format'      => $args['format'],
					'first_name'  => $args['first_name'],
					'middle_name' => $args['middle_name'],
					'last_name'   => $args['last_name'],
					'hide_subs'   => false,
					'help'        => $args['help'],
					'css'         => $args['css_class'],
					'wpuf_cond'   => $this->conditionals,
				];
				break;

			case 'ratings':
				$field_content = [
					'input_type' => 'ratings',
					'template'   => 'ratings',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'linear_scale':
				$field_content = [
					'input_type' => 'linear_scale',
					'template'   => 'linear_scale',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'checkbox_grid':
				$field_content = [
					'input_type' => 'checkbox_grid',
					'template'   => 'checkbox_grid',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'multiple_choice_grid':
				$field_content = [
					'input_type' => 'multiple_choice_grid',
					'template'   => 'multiple_choice_grid',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'single_product':
				$field_content = [
					'input_type' => 'single_product',
					'template'   => 'single_product',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'multiple_product':
				$field_content = [
					'input_type' => 'multiple_product',
					'template'   => 'multiple_product',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'payment_method':
				$field_content = [
					'input_type' => 'payment_method',
					'template'   => 'payment_method',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;

			case 'total':
				$field_content = [
					'input_type' => 'total',
					'template'   => 'total',
					'required'   => $args['required'],
					'label'      => $args['label'],
					'name'       => $args['name'],
					'is_meta'    => 'yes',
					'help'       => '',
					'css'        => $args['css_class'],
					'options'    => $args['options'],
					'wpuf_cond'  => $this->conditionals,
				];
				break;
		}

		return $field_content;
	}

	/**
	 * Translate to wpuf field options array.
	 *
	 * Adopted from the weforms plugin 1.4.2.
	 *
	 * @since 1.2.0
	 *
	 * @param  array $field Field options.
	 * @return array
	 */
	private function get_options( $field ) {
		$options = [];

		if ( ! $field['choices'] ) {
			return $options;
		}

		foreach ( $field['choices'] as $choice ) {
			$options[ $choice['label'] ] = $choice['label'];
		}

		return $options;
	}

	/**
	 * Default form settings.
	 *
	 * Adopted from the weforms plugin 1.4.2.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public function get_default_form_settings() {
		return [
			'redirect_to'        => 'same',
			'message'            => esc_html__( 'Thanks for contacting us! We will get in touch with you shortly.', 'weforms' ),
			'page_id'            => '',
			'url'                => '',
			'submit_text'        => esc_html__( 'Submit Query', 'weforms' ),
			'schedule_form'      => 'false',
			'schedule_start'     => '',
			'schedule_end'       => '',
			'sc_pending_message' => esc_html__( 'Form submission hasn\'t been started yet', 'weforms' ),
			'sc_expired_message' => esc_html__( 'Form submission is now closed.', 'weforms' ),
			'require_login'      => 'false',
			'req_login_message'  => esc_html__( 'You need to login to submit a query.', 'weforms' ),
			'limit_entries'      => 'false',
			'limit_number'       => '1000',
			'limit_message'      => esc_html__( 'Sorry, we have reached the maximum number of submissions.', 'weforms' ),
			'label_position'     => 'above',
		];
	}

	/**
	 * Get form settings.
	 *
	 * Adopted from the weforms plugin 1.4.2.
	 *
	 * @sinve 1.2.0
	 *
	 * @param  string $form Form content in JSON format.
	 * @return array
	 */
	public function get_form_settings( $form ) {
		$default  = $this->get_default_form_settings();
		$parsed   = json_decode( $form, true );
		$settings = $parsed['settings'];

		// Handle alternate confirmation format.
		if ( empty( $settings['confirmations'][1]['type'] ) && ! empty( $settings['confirmation_type'] ) ) {
			$settings['confirmations'][1] = [
				'type'     => $settings['confirmation_type'],
				'message'  => $settings['confirmation_message'],
				'page'     => $settings['confirmation_page'],
				'redirect' => $settings['confirmation_redirect'],
			];
		}

		switch ( $settings['confirmations'][1]['type'] ) {
			case 'redirect':
				$redirect_to = 'url';
				break;

			case 'page':
				$redirect_to = 'page';
				break;

			case 'message':
			default:
				$redirect_to = 'same';
				break;
		}

		$form_settings = wp_parse_args( [
			'message'     => $settings['confirmations'][1]['message'],
			'page_id'     => $settings['confirmations'][1]['page'],
			'url'         => $settings['confirmations'][1]['redirect'],
			'submit_text' => $settings['submit_text'],
			'redirect_to' => $redirect_to,
		], $default );

		return $form_settings;
	}

	/**
	 * Get form notifications of a form.
	 *
	 * Adopted from the weforms plugin 1.4.2.
	 *
	 * @since 1.2.0
	 * @param  string $form Form content in JSON format.
	 * @return array
	 */
	public function get_form_notifications( $form ) {
		$parsed       = json_decode( $form, true );
		$notification = array_pop( $parsed['settings']['notifications'] );

		return [
			[
				'active'      => (bool) $parsed['settings']['notification_enable'],
				'name'        => 'Admin Notification',
				'subject'     => $notification['subject'],
				'to'          => $notification['email'],
				'replyTo'     => $notification['replyto'],
				'message'     => $notification['message'],
				'fromName'    => $notification['sender_name'],
				'fromAddress' => $notification['sender_address'],
				'cc'          => '',
				'bcc'         => '',
			],
		];
	}

	/**
	 * Hide form notices.
	 *
	 * @since 1.2.0
	 */
	public function hide_notices() {
		add_filter(
			'pre_option_weforms_dismiss_xnotice_wpforms',
			function() {
				return 'yes';
			}
		);

		add_filter(
			'pre_option_weforms_promotional_offer_notice',
			function() {
				return 'hide';
			}
		);

		add_filter(
			'pre_option_weforms_review_notice_dismiss',
			function() {
				return 'yes';
			}
		);

		add_filter(
			'pre_option__transient_weforms_prevent_tracker_notice',
			function() {
				return '1';
			}
		);
	}
}

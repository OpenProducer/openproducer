<?php
/**
 * Class: Boldgrid_Editor_Builder_Styles
 *
 * Handle adding custom stylesheets to the editor.
 *
 * @since      1.6
 * @package    Boldgrid_Editor
 * @subpackage Boldgrid_Editor_Builder
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Editor_Builder_Styles
 *
 * Handle adding custom stylesheets to the editor.
 *
 * @since      1.6
 */
class Boldgrid_Editor_Builder_Styles {

	/**
	 * Get the html named input for the styles values.
	 *
	 * @since 1.6
	 *
	 * @return string HTML to render.
	 */
	public function get_input() {
		return "<input id='boldgrid-control-styles' style='display:none' name='boldgrid-control-styles'>";
	}

	/**
	 * Get the path we user for uploads.
	 *
	 * @since 1.0.0
	 *
	 * @return  string Upload path.
	 */
	public static function get_upload_path( $key = 'basedir' ) {
		$upload_dir = wp_upload_dir();
		return $upload_dir[ $key ] . '/boldgrid';
	}

	/**
	 * Get url info for the saved css file.
	 *
	 * @since 1.6
	 *
	 * @return array Properties of file.
	 */
	public static function get_url_info() {
		$option = self::get_option();
		$is_bg_theme = Boldgrid_Editor_Service::get( 'main' )->get_is_boldgrid_theme();
		$url = false;

		// Currently disabled for BG themes. BG themes should use the BG color palette system (theme switching).
		if ( ! $is_bg_theme ) {
			$filename = ! empty( $option['css_filename'] ) ? $option['css_filename'] : '';

			$editor_fs = new Boldgrid_Editor_Fs();
			$wp_filesystem = $editor_fs->get_wp_filesystem();
			if ( $filename && $wp_filesystem->exists( self::get_upload_path() . $filename ) ) {
				$url = self::get_upload_path( 'baseurl' ) . $filename;
			} else {
				$url = plugins_url( '/assets/css/custom-styles.css', BOLDGRID_EDITOR_ENTRY );
			}
		}

		return array(
			'url' => $url,
			'timestamp' => ! empty( $option['timestamp'] ) ? $option['timestamp'] : false,
		);
	}

	/**
	 * Check if the theme requires the default stylesheet.
	 *
	 * @since 1.6
	 *
	 * @return boolean.
	 */
	public function requires_default_styles() {
		$option = self::get_option();
		return empty( $option['css_filename'] ) && ! Boldgrid_Editor_Service::get( 'main' )->get_is_boldgrid_theme();
	}

	/**
	 * Check if the user has saved a specific type of custom style.
	 *
	 * @since 1.6
	 *
	 * @param  string  $name Name of custom style.
	 * @return boolean       Whether or not the style has been saved.
	 */
	public function has_custom_style( $name ) {
		$has_custom_style = false;
		$option = self::get_option();
		$configs = ! empty( $option['configuration'] ) ? $option['configuration'] : array();

		foreach( $configs as $config ) {
			if ( $name === $config['id'] ) {
				$has_custom_style = true;
				break;
			}
		}

		return $has_custom_style;
	}

	/**
	 * Get the option value we use to display styles.
	 *
	 * @since 1.6
	 *
	 * @return array
	 */
	public static function get_option() {
		$option_name = 'styles';
		if ( ! is_admin() && ! empty( $_GET['preview'] ) && 'true' === $_GET['preview'] ) {
			$option_name = 'preview_styles';
		}

		return Boldgrid_Editor_Option::get( $option_name, array() );
	}

	/**
	 * Create a string of the css created in the eidtor.
	 *
	 * @since 1.6
	 *
	 * @param  array $styles List of styles.
	 * @return string        CSS.
	 */
	public function create_css_string( $styles ) {
		$css = '';
		foreach( $styles as $style ) {
			$css .= $style['css'];
		}

		return $css;
	}

	/**
	 * Create the css file.
	 *
	 * @since 1.6
	 *
	 * @param  string $css CSS to save to a file.
	 * @return string      URL to new file.
	 */
	public function create_file( $css, $filename = '/custom-styles.css' ) {
		wp_mkdir_p( self::get_upload_path() );
		$new_filename = self::get_upload_path() . $filename;
		$editor_fs = new Boldgrid_Editor_Fs();
		$editor_fs->save( $css, $new_filename );

		return $filename;
	}

	/**
	 * Validate the CSS.
	 *
	 * @since 1.6
	 *
	 * @param  array $styles Unvalidated Styles.
	 * @return array         Validated Styles.
	 */
	public function validate( $styles ) {
		$validated_styles = array();
		foreach( $styles as &$style ) {
			if ( ! preg_match( '#</?\w+#', $style['css'] ) ) {
				$validated_styles[] = $style;
			}
		}

		return $validated_styles;
	}

	/**
	 * Save user styles created during edit process.
	 *
	 * @since 1.6
	 */
	public function save() {
		if ( isset( $_REQUEST['boldgrid-control-styles'] ) ) {
			$styles = ! empty( $_REQUEST['boldgrid-control-styles'] ) ?
				sanitize_text_field( wp_unslash( $_REQUEST['boldgrid-control-styles'] ) ) : '';

			$styles = json_decode( $styles, true );
			$styles = is_array( $styles ) ? $styles : array();
			$styles = $this->validate( $styles );

			// Create stylesheet.
			$css = $this->create_css_string( $styles );

			if ( empty( $css ) ) {
				return;
			}

			if ( ! empty( $_POST['wp-preview'] ) ) {
				// If previewing the page save to another option.
				$css_file = $this->create_file( $css, '/preview-custom-styles.css' );

				Boldgrid_Editor_Option::update( 'preview_styles', array(
					'configuration' => $styles,
					'css_filename' => $css_file,
					'timestamp' => time()
				) );

			} else {
				$css_file = $this->create_file( $css );

				Boldgrid_Editor_Option::update( 'styles', array(
					'configuration' => $styles,
					'css_filename' => $css_file,
					'timestamp' => time()
				) );
			}
		}
	}

}

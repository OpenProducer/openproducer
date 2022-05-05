<?php
/**
 * File: Editor.php
 *
 * BoldGrid Library -- Editor
 *
 * @package Boldgrid\Library
 *
 * @since 2.7.3
 * @author BoldGrid <support@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * Class: Editor
 *
 * This class is responsible for manipulating behaviors in the WordPress editor.
 *
 * @since 2.7.3
 */
class Editor {
	/**
	 * Constructor.
	 *
	 * @since 2.7.3
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Disable the Connect Key notice if Gutenberg is used.
	 *
	 * @since 2.7.3
	 *
	 * @hook: enqueue_block_editor_assets
	 */
	public function disableNotices() {
		add_filter( 'Boldgrid\Library\Library\Notice\KeyPrompt_display', '__return_false', 20 );
	}
}

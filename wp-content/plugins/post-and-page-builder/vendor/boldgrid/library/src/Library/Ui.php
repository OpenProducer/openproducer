<?php
/**
 * BoldGrid UI/UX.
 *
 * @package Boldgrid\Library
 * @subpackage \Library
 *
 * @version 1.1.7
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

use Boldgrid\Library\Library;

/**
 * BoldGrid UI/UX.
 *
 * This class is responsible for managing shared UI/UX.
 *
 * LEFT AND RIGHT COLS
 * <div class="col-container">
 *     <div class="col-left">
 *         <ul class="bg-left-nav">
 *             <li class="active" data-id="section_1">Section 1</li>
 *  		   <li                data-id="section_2">Section 2</li>
 *  	   </ul>
 *     </div>
 *     <div class="col-right">
 *         <div class="col-right-section" id="section_1">
 *             <div class="bg-box">
 *                 <div class="bg-box-top">
 *                     Title
 *                 </div>
 *                <div class="bg-box-bottom">
 *                     Content
 *                </div>
 *                <div class="bg-box-bottom premium">
 *                     <a href="" class="button button-success">Get Premium</a>
 *                </div>
 *             </div>
 *         </div>
 *         <div class="col-right-section" id="section_2">Content</div>
 *     </div>
 * </div>
 *
 * POSTBOX
 * <div class="postbox">
 *     <h2 class="hndle ui-sortable-handle"><span>Remote Storage</span></h2>
 *     <div class="inside">
 *         Main Content
 *     </div>
 *     <div class="inside premium wp-clearfix">
 *         <a href="" class="button button-success">Get Premium</a>
 *         Get Premium Message.
 *     </div>
 * </div>
 *
 * @since 1.1.7
 */
class Ui {

	/**
	 * Constructor.
	 *
	 * @since 1.1.7
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Adds the required CSS and JS to the WordPress dashboard.
	 *
	 * @since 1.1.7
	 *
	 * @hook: admin_enqueue_scripts
	 */
	public function enqueue() {
		wp_register_style(
			'bglib-ui-css',
			Library\Configs::get( 'libraryUrl' ) . 'src/assets/css/ui.css'
		);

		wp_register_script(
			'bglib-ui-js',
			Library\Configs::get( 'libraryUrl' ) . 'src/assets/js/ui.js',
			'jquery.sticky-kit'
		);

		wp_register_script(
			'bglib-sticky',
			Library\Configs::get( 'libraryUrl' ) . 'src/assets/js/sticky.js',
			'jquery'
		);

		wp_register_style(
			'bglib-attributes-css',
			Library\Configs::get( 'libraryUrl' ) . 'src/assets/css/attributes.css'
		);

		wp_register_script(
			'bglib-attributes-js',
			Library\Configs::get( 'libraryUrl' ) . 'src/assets/js/attributes.js',
			'jquery'
		);
	}

	/**
	 * Create markup for left / right columned container.
	 *
	 * @since 1.1.7
	 *
	 * @hook Boldgrid\Library\Ui\render_col_container
	 *
	 * @uses $_REQUEST['section'] Section to switch to on load.
	 *
	 * @param  array $sections {
	 *     An array of data used to create a left and right columned page.
	 *
	 *     @type array  $sections {
	 *         @type string $id      Section id.
	 *         @type string $title   Section title.
	 *         @type string $content Section content.
	 *     }
	 *     @type string $post_col_right Markup to display after all col-right
	 *                                  sections.
	 * }
	 * @return string
	 */
	public function render_col_container( $sections ) {

		if( empty( $sections['sections'] ) ) {
			return $sections;
		}

		$section_count = 0;

		$show_section = ! empty( $_REQUEST['section'] ) ?
			sanitize_key( $_REQUEST['section'] ) : null;

		$content = '';
		$navigation = '<ul class="bg-left-nav bg-before-ready">';
		foreach ( $sections['sections'] as $section ) {
			$section_count++;

			/*
			 * Determine which section should be visible first.
			 *
			 * You can pass in &section=section-id to choose a section other
			 * than the first to display by default.
			 */
			$is_first_section = is_null( $show_section ) && 1 === $section_count;
			$is_show_section = ! is_null( $show_section ) && $section['id'] === $show_section;
			$nav_class = '';
			$section_style = 'display:none;';
			if( $is_first_section || $is_show_section ) {
				$nav_class = 'active';
				$section_style = '';
			}

			$navigation .= sprintf( '
				<li class="%3$s" data-section-id="%1$s">%2$s</li>',
				$section['id'],
				$section['title'],
				$nav_class
			);

			$content .= sprintf( '
				<div class="col-right-section" id="%2$s" style="%3$s">
					%1$s
				</div>',
				$section['content'],
				$section['id'],
				$section_style
			);
		}
		$navigation .= '</ul>';

		$markup = sprintf( '
			<div id="col-container" class="wp-clearfix">
				<div id="col-left">
					%1$s
				</div>
				<div id="col-right">
					%2$s
					%3$s
				</div>
			</div>',
			$navigation,
			$content,
			! empty( $sections['post_col_right'] ) ? $sections['post_col_right'] : ''
		);

		return $markup;
	}
}

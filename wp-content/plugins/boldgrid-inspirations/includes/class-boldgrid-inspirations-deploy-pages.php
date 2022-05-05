<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Deploy
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * Class: Boldgrid_Inspirations_Deploy_Pages.
 *
 * Responsible for installing pages during deployment.
 *
 * @since 1.0.7
 * @package Boldgrid_Inspirations_Deploy_Pages.
 * @subpackage Boldgrid_Inspirations_Deploy_Pages.
 * @author BoldGrid <support@boldgrid.com>.
 *
 * @link https://boldgrid.com.
 */
class Boldgrid_Inspirations_Deploy_Pages {

	/**
	 * Variables relevant to page deploment.
	 *
	 * @since 1.0.7
	 * @access protected
	 * @var array $deployment_variables
	 */
	protected $deployment_variables = array ();

	/**
	 * Temporary pages created for the purpose of forcing image replacement.
	 *
	 * @since 1.0.7
	 * @access protected
	 * @var array $temp_pages
	 */
	protected $temp_pages;

	/**
	 * Pass deployment variables that are relevant to page installation
	 *
	 * @param array $deployment_variables
	 *        	Variables relevant to page installation
	 */
	public function __construct( $deployment_variables ) {
		$this->deployment_variables = $deployment_variables;
	}

	/**
	 * Create pages so that when the images are dynamically inserted,
	 * the content markup us updated with the image urls.
	 *
	 * @since 1.0.7
	 *
	 * @param array $full_page_list
	 *        	List of all pages returned from asset sever.
	 * @return array $installed_page_ids List of installed pages.
	 */
	public function deploy_temp_pages( $full_page_list, $installed_page_ids ) {
		$this->temp_pages = array ();
		$requested_ids = array_keys( $installed_page_ids );
		if ( ! empty( $full_page_list['pages']['additional'] ) ) {
			foreach ( $full_page_list['pages']['additional'] as $page ) {

				// Set Page variables
				$post['post_content'] = $page->code;
				$post['post_name'] = $page->page_slug;
				$post['post_title'] = $page->page_title;
				$post['post_status'] = $this->deployment_variables['post_status'];
				$post['post_type'] = $page->post_type;
				$post['comment_status'] = 'closed';

				// Insert Post
				$post_id = wp_insert_post( $post );

				$this->temp_pages[$page->id] = $post_id;

				/**
				 * Create an entry into installed page_ids
				 * This occurs AFTER this option is stored into the the DB,
				 * but before this class property is used to replace media images
				 */
				$installed_page_ids[$page->id] = $post_id;
			}
		}

		return $installed_page_ids;
	}

	/**
	 * Create pages so that when the images are dynamically inserted,
	 * the content markup us updated with the image urls.
	 *
	 * @since 1.0.7
	 *
	 * @param array $full_page_list
	 *        	List of all pages returned from asset sever.
	 * @param array $installed_page_ids
	 *        	List of installed pages.
	 */
	public function cleanup_temp_pages( $full_page_list, $installed_page_ids ) {
		$posts = array ();

		foreach ( $installed_page_ids as $page_id => $post_id ) {
			$post = get_post( $post_id );

			// Store updated Content
			$posts[$page_id] = $post->post_content;

			// Delete the temp post
			if ( ! empty( $this->temp_pages[$page_id] ) ) {
				wp_delete_post( $this->temp_pages[$page_id], true );
			}
		}

		foreach ( $full_page_list['pages']['pages_in_pageset'] as &$page ) {
			$page->code = ! empty( $posts[$page->id] ) ? $posts[$page->id] : '';
		}
		foreach ( $full_page_list['pages']['additional'] as &$page ) {
			$page->code = ! empty( $posts[$page->id] ) ? $posts[$page->id] : '';
		}

		update_option( 'boldgrid_static_pages', $full_page_list );
	}
}
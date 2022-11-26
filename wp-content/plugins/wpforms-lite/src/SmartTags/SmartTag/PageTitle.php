<?php

namespace WPForms\SmartTags\SmartTag;

/**
 * Class PageTitle.
 *
 * @since 1.6.7
 */
class PageTitle extends SmartTag {

	/**
	 * Get smart tag value.
	 *
	 * @since 1.6.7
	 *
	 * @param array  $form_data Form data.
	 * @param array  $fields    List of fields.
	 * @param string $entry_id  Entry ID.
	 *
	 * @return string
	 */
	public function get_value( $form_data, $fields = [], $entry_id = '' ) {

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! empty( $_POST['page_title'] ) ) {
			return sanitize_text_field( wp_unslash( $_POST['page_title'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		/*
		 * In most cases `wp_title()` returns the value we're going to use, except:
		 * - on static front page (we can use page title as a fallback),
		 * - on standard front page with the latest post (we can use the site name as a fallback).
		 */
		if ( is_front_page() ) {
			return wp_kses_post( is_page() ? get_the_title( get_the_ID() ) : get_bloginfo( 'name' ) );
		}

		global $wp_filter;

		// Back up all callbacks.
		$callbacks = $wp_filter['wp_title']->callbacks;

		// Unset all callbacks.
		$wp_filter['wp_title']->callbacks = [];

		// Get the raw value.
		$title = trim( wp_title( '', false ) );

		// Run through the default transformations WordPress does on this hook.
		$title = wptexturize( $title );
		$title = convert_chars( $title );
		$title = esc_html( $title );
		$title = capital_P_dangit( $title );

		// Restore all callbacks.
		$wp_filter['wp_title']->callbacks = $callbacks;

		return $title;
	}
}

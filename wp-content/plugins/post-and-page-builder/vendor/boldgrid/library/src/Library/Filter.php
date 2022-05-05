<?php
/**
 * BoldGrid Library Filter
 *
 * @package Boldgrid\Library
 * @subpackage \Library
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * BoldGrid Library Filter Class.
 *
 * This class is responsible for filter/action related methods used within
 * the BoldGrid Library. Special thanks to rarst and scribu for filter ideas.
 *
 * @since 1.0.0
 */
class Filter {

	/**
	 * Adds hooks.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $class Class to add hooks for.
	 *
	 * @return null
	 */
	public static function add( $class ) {
		self::doFilter( 'add_filter', $class );
	}

	/**
	 * Remove hooks.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $class Class to remove hooks from.
	 *
	 * @return null
	 */
	public static function remove( $class ) {
		self::doFilter( 'remove_filter', $class );
	}

	/**
	 * Process hooks.
	 *
	 * This sets up our automatic filter binding.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $action Action name.
	 * @param  string $class  Class name.
	 *
	 * @return null
	 */
	private static function doFilter( $action, $class ) {
		$reflection = new \ReflectionClass( $class );
		foreach ( $reflection->getMethods() as $method ) {
			if ( $method->isPublic() && ! $method->isConstructor() ) {
				$comment = $method->getDocComment();

				// No hooks.
				if ( preg_match( '/@nohook[ \t\*\n]+/', $comment ) ) {
					continue;
				}

				// Set hook.
				preg_match_all( '/@hook:?\s+([^\s]+)/', $comment, $matches ) ? $matches[1] : $method->name;
				if ( empty( $matches[1] ) ) {
					$hooks = array( $method->name );
				} else {
					$hooks = $matches[1];
				}

				// Allow setting priority.
				$priority = preg_match( '/@priority:?\s+(\d+)/', $comment, $matches ) ? $matches[1] : 10;

				// Fire.
				foreach ( $hooks as $hook ) {
					call_user_func( $action, $hook, array( $class, $method->name ), $priority, $method->getNumberOfParameters() );
				}
			}
		}
	}

	/**
	 * Removes an anonymous object filter.
	 *
	 * @since  1.0.0
	 *
	 * @global $wp_filter WordPress filter global.
	 *
	 * @param  string $tag      Hook name.
	 * @param  string $class    Class name
	 * @param  string $method   Method name
	 * @param  int    $priority Filter priority.
	 *
	 * @return bool             Success of removing filter.
	 */
	public static function removeHook( $tag, $class, $name, $priority = 10 ) {
		global $wp_filter;

		// Check that filter exists.
		if ( isset( $wp_filter[ $tag ] ) ) {

			/**
			 * If filter config is an object, means we're using WordPress 4.7+ and the config is no longer
			 * a simple array, and it is an object that implements the ArrayAccess interface.
			 *
			 * To be backwards compatible, we set $callbacks equal to the correct array as a reference (so $wp_filter is updated).
			 *
			 * @see https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
			 */
			if ( is_object( $wp_filter[ $tag ] ) && isset( $wp_filter[ $tag ]->callbacks ) ) {

				// Create $fob object from filter tag, to use below.
				$fob = $wp_filter[ $tag ];
				$callbacks = &$wp_filter[ $tag ]->callbacks;
			} else {
				$callbacks = &$wp_filter[ $tag ];
			}

			// Exit if there aren't any callbacks for specified priority.
			if ( ! isset( $callbacks[ $priority ] ) || empty( $callbacks[ $priority ] ) ) {
				return false;
			}

			// Loop through each filter for the specified priority, looking for our class & method.
			foreach( ( array ) $callbacks[ $priority ] as $filter_id => $filter ) {

				// Filter should always be an array - array( $this, 'method' ), if not goto next.
				if ( ! isset( $filter['function'] ) || ! is_array( $filter['function'] ) ) {
					continue;
				}

				// If first value in array is not an object, it can't be a class.
				if ( ! is_object( $filter['function'][0] ) ) {
					continue;
				}

				// Method doesn't match the one we're looking for, goto next.
				if ( $filter['function'][1] !== $name ) {
					continue;
				}

				// Callback method matched, so check class.
				if ( get_class( $filter['function'][0] ) === $class ) {

					// WordPress 4.7+ use core remove_filter() since we found the class object.
					if ( isset( $fob ) ) {

						// Handles removing filter, reseting callback priority keys mid-iteration, etc.
						$fob->remove_filter( $tag, $filter['function'], $priority );
					} else {

						// Use legacy removal process (pre 4.7).
						unset( $callbacks[ $priority ][ $filter_id ] );

						// If it was the only filter in that priority, unset that priority.
						if ( empty( $callbacks[ $priority ] ) ) {
							unset( $callbacks[ $priority ] );
						}

						// If the only filter for that tag, set the tag to an empty array.
						if ( empty( $callbacks ) ) {
							$callbacks = array();
						}

						// Remove this filter from merged_filters, which specifies if filters have been sorted.
						unset( $GLOBALS['merged_filters'][ $tag ] );
					}

					return true;
				}
			}
		}

		return false;
	}
}

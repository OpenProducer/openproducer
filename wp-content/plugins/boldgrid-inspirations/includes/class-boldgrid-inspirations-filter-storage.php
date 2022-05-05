<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Filter_Storage
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Filter Storage class.
 *
 * Stores a value and calls any existing function with this value.
 */
class Boldgrid_Filter_Storage {
	/**
	 * Filled by __construct().
	 * Used by __call().
	 *
	 * @type mixed Any type you need.
	 */
	private $values;
	private $class;

	/**
	 * Stores the values for later use.
	 *
	 * @param mixed $values
	 */
	public function __construct( $values, $class ) {
		$this->values = $values;
		$this->class = $class;
	}

	/**
	 * Catches all function calls except __construct().
	 *
	 * Be aware: Even if the function is called with just one string as an
	 * argument it will be sent as an array.
	 *
	 * @param string $callback
	 *        	Function name
	 * @param array $arguments
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function __call( $callback, $arguments ) {
		if ( is_callable( array (
			$this->class,
			$callback
		) ) ) {
			return call_user_func( array (
				$this->class,
				$callback
			), $arguments, $this->values );
		}

		// Wrong function called. No need to translate this error message.
		throw new InvalidArgumentException(
			sprintf(
				'File: %1$s<br>Line %2$d<br>Not callable: %3$s', __FILE__, __LINE__,
				print_r(
					array (
						$this->class,
						$callback,
					), true
				)
			)
		);
	}
}

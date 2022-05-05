<?php
/**
 * BoldGrid Library Key Validate
 *
 * @package Boldgrid\Library
 * @subpackage \Key
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Key;

/**
 * Boldgrid Library Key Validate Class.
 *
 * Creates object based on user input of BoldGrid
 * Connect Key.  Will check basic formatting of key
 * entered, and provides the key hash to store.
 *
 * @since 1.0.0
 */
class Validate {

	/**
	 * @access private
	 *
	 * @since 1.0.0
	 *
	 * @var string $key   User entered API key.
	 * @var string $hash  API key hash that will be stored.
	 * @var bool   $valid Is API key correctly formatted?
	 */
	private
		$key,
		$hash,
		$valid;

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key The API key to validate.
	 */
	public function __construct( $key ) {
		$this->setKey( $key );
		if ( $this->isValid() ) {
			$this->setValid( true );
			$this->setHash( $this->getKey() );
		}
	}

	/**
	 * Set the key class property.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $key The API key to set.
	 *
	 * @return string $key The key class property.
	 */
	public function setKey( $key ) {
		return $this->key = $this->sanitizeKey( $key );
	}

	/**
	 * Set the valid class property.
	 *
	 * @since  1.0.0
	 *
	 * @param  bool  $valid Valid key entry?
	 *
	 * @return bool  $valid The valid class property.
	 */
	public function setValid( $valid ) {
		return $this->valid = $valid;
	}

	/**
	 * Sets the hash class property.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $key API key to get hash of.
	 *
	 * @return object
	 */
	public function setHash( $key = null ) {
		$key = $key ? $this->sanitizeKey( $key ) : $this->getKey();
		return $this->hash = $this->hashKey( $key );
	}

	/**
	 * Sanitizes the key input for loose validation of format.
	 *
	 * @since  1.0.0
	 *
	 * @param  [type] $key [description]
	 *
	 * @return string $key Sanitized key.
	 */
	public function sanitizeKey( $key ) {
		$key = trim( strtolower( preg_replace( '/-/', '', $key ) ) );
		$key = implode( '-', str_split( $key, 8 ) );
		return sanitize_key( $key );
	}

	/**
	 * [hashKey description]
	 *
	 * @since  1.0.0
	 *
	 * @param  [type] $key [description]
	 *
	 * @return [type]      [description]
	 */
	public function hashKey( $key = null ) {
		$key = $key ? $this->sanitizeKey( $key ) : $this->getKey();
		return md5( $key );
	}

	/**
	 * Checks if key is valid length after format and sanitization is done.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $key The API key to check.
	 *
	 * @return bool        Is the key valid?
	 */
	public function isValid( $key = null ) {
		$key = $key ? $this->sanitizeKey( $key ) : $this->getKey();
		return strlen( $key ) === 35;
	}

	/**
	 * Get the key class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $key The key class property.
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Get the hash class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $hash The hash class property.
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Get the valid class property.
	 *
	 * @since  1.0.0
	 *
	 * @return bool  $valid The valid class property.
	 */
	public function getValid() {
		return $this->valid;
	}
}

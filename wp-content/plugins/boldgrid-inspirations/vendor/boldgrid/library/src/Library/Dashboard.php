<?php
/**
 * BoldGrid Library Dashboard Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library\License
 *
 * @version 2.9.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * BoldGrid Library Dashboard Class.
 *
 * This is a generic class used for managing the WordPress Dashboard.
 *
 * @since 2.9.0
 */
class Dashboard {
	/**
	 * Init.
	 *
	 * @since 2.9.0
	 */
	public function init() {
		/*
		 * Move BoldGrid Widgets to the top of the WordPress dashboard.
		 *
		 * This is not permanent. The user can move BoldGrid widgets elsewhere after the first load.
		 *
		 * sortWidgets class runs Filter::add($this) in __construct.
		 */
		$sortWidgets = new Dashboard\SortWidgets();
	}
}

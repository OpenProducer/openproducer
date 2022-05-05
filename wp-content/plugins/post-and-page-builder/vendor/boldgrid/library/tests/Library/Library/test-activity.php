<?php
/**
 * BoldGrid Library Activity Test Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library\License
 *
 * @version 2.7.7
 * @author BoldGrid <wpb@boldgrid.com>
 */

use Boldgrid\Library\Library;

/**
 * BoldGrid Library Rating Prompt Class.
 *
 * This class is responsible for displaying admin notices asking for feedback / a rating on wp.org.
 *
 * @since 2.7.7
 */
class Test_Activty extends WP_UnitTestCase {

	public $activity;

	public $activityClass;

	/**
	 *
	 */
	public function setup() {
		$this->reset();

		$this->activityClass = new \Boldgrid\Library\Library\Activity( 'boldgrid-backup' );
	}

	/**
	 *
	 */
	public function reset() {
		$this->activity = array(
			'boldgrid-backup' => array(
				'activityA' => 5,
				'activityB' => 10,
				'activityC' => 15,
			),
			'post-and-page-builder' => array(
				'activityA' => 20,
				'activityB' => 25,
				'activityC' => 30,
			),
		);

		update_option( 'bglib_activity', $this->activity );
	}

	/**
	 * Add / update an activity count.
	 *
	 * @since 2.7.7
	 *
	 * @param string $activity    The name of the activity.
	 * @param int    $count       The amount to increment the activity by.
	 * @param string $config_path The path to the config file for rating prompts. If the path is
	 *                            passed in, this method will also attempt to add a new prompt if
	 *                            the activities threshold has been reached.
	 * @return bool               Whether or not the activity was added.
	 */
	public function testAdd() {
		$this->reset();

		$activity = 'activityD';

		$this->activityClass->add( $activity );
		$this->assertEquals( 1, $this->activityClass->getActivityCount( $activity ) );

		$this->activityClass->add( $activity, 2 );
		$this->assertEquals( 3, $this->activityClass->getActivityCount( $activity ) );
	}

	/**
	 * Get all activities.
	 *
	 * @since 2.7.7
	 *
	 * @return array
	 */
	public function testGetActivities() {
		$this->reset();

		$this->assertEquals( $this->activity, $this->activityClass->getActivities() );
	}

	/**
	 * Get the count for an activity.
	 *
	 * @since 2.7.7
	 *
	 * @param  string $activity The name of the activity.
	 * @return int              The activity's count.
	 */
	public function testGetActivityCount() {
		$this->reset();

		$this->assertEquals( 10, $this->activityClass->getActivityCount( 'activityB' ) );
	}

	/**
	 * Get all activities for a plugin.
	 *
	 * @since 2.7.7
	 *
	 * @return array
	 */
	public function testGetPluginActivities() {
		$this->reset();

		$this->assertEquals( $this->activity['boldgrid-backup'], $this->activityClass->getPluginActivities() );
	}


	/**
	 * Save all activities.
	 *
	 * This overwrites all activites, not just the ones for this plugin.
	 *
	 * @since 2.7.7
	 *
	 * @param  array $activities An array of activities.
	 * @return bool              Whether or not the activities were updated successfully.
	 */
	public function testSaveActivities() {
		$this->reset();

		$this->activity['boldgrid-backup']['jump-10-times'] = 5;
		$this->activityClass->saveActivities( $this->activity );

		$this->assertEquals( $this->activity, $this->activityClass->getActivities() );
	}

	/**
	 * Save all activities for this plugin.
	 *
	 * @since 2.7.7
	 *
	 * @param  array $plugin_activities An array of activities.
	 * @return bool                     Whether or not the activities were updated successfully.
	 */
	public function testSavePluginActivities() {
		$activities = 5;

		$this->activityClass->savePluginActivities( $activities );

		$this->assertEquals( $activities, $this->activityClass->getPluginActivities() );
	}
}

<?php
/*
Plugin Name: MU WP Native PHP Sessions
Plugin URI: https://wordpress.org/plugins/wp-native-php-sessions/
Description: Building on Pantheon's and WordPress's strengths, together.
Version: 0.1
Author: Pantheon
Author URI: http://getpantheon.com
*/

if (isset($_ENV['PANTHEON_ENVIRONMENT']))
	require_once( 'wp-native-php-sessions/pantheon-sessions.php' );
?>
<?php
/**
 * Tz Weekly Radio Schedule 
 *
 * Plugin Name:       Tz Weekly Radio Schedule Core
 * Plugin URI:        https://wrs.tuzongo.com/
 * Description:       Provides an ajax-driven schedule page, creates team roles, presents up-to-date schedule information, allows easy allocation of slots and management of DJs / Presenter's personal slots.
 * Version:           1.8.1
 * Author:            TUZONGO Web Design
 * Author URI:        http://tuzongo.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       weekly-radio-schedule
 * Domain Path:       /languages
 *
 * @link              http://tuzongo.com/
 * @since             1.0.0
 * @package           Tz_Weekly_Radio_Schedule_Core
 *
 * @wordpress-plugin
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/** get paths and constants **/

if ( ! function_exists( 'get_plugins' ) )
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
$plugin_file = basename( ( __FILE__ ) );
$is_twentyseventeen = wp_get_theme()->get( 'Name' ) == 'Twenty Seventeen' ? 1 : 0;
$is_twentynineteen = wp_get_theme()->get( 'Name' ) == 'Twenty Nineteen' ? 1 : 0;
$is_twentytwenty = wp_get_theme()->get( 'Name' ) == 'Twenty Twenty' ? 1 : 0;
$is_twentytwentyone = wp_get_theme()->get( 'Name' ) == 'Twenty Twentyone' ? 1 : 0;

define( 'TZWRS_PLUGIN_RELEASE', 'BETA1' ); //ALPHA1, BETA1, RC1, '' for STABLE
define( 'TZWRS_VERSION', $plugin_folder[$plugin_file]['Version'] );
define( 'TZWRS_DIRECTORY', plugin_dir_path( __FILE__ ) );
define( 'TZWRS_DIRECTORY_URL', plugins_url( '', __FILE__ ) );
define( 'TZWRS_AUTHOR_LEVELS', array( 'wrs_dj', 'wrs_djoperator', 'wrs_manager' ) );
define( 'TZWRS_ADDME', esc_html__( 'Add me here', 'weekly-radio-schedule' ) );
define( 'TZWRS_CANCEL', esc_html__( 'Cancel', 'weekly-radio-schedule' ) );
define( 'TZWRS_AWAY', esc_html__( 'Mark me Away', 'weekly-radio-schedule' ) );
define( 'TZWRS_BACK', esc_html__( 'Mark me as Playing', 'weekly-radio-schedule' ) );
define( 'TZWRS_EVENT', esc_html__( 'Name of event', 'weekly-radio-schedule' ) );
define( 'TZWRS_CLEAR', esc_html__( 'Clear this slot', 'weekly-radio-schedule' ) );
define( 'TZWRS_DJBACK', esc_html__( 'Mark DJ as Playing', 'weekly-radio-schedule' ) );
define( 'TZWRS_DJAWAY', esc_html__( 'Mark DJ as Away', 'weekly-radio-schedule' ) );
define( 'TZWRS_APPROVE', esc_html__( 'Approve', 'weekly-radio-schedule' ) );
define( 'TZWRS_DENY', esc_html__( 'Deny', 'weekly-radio-schedule' ) );
define( 'TZWRS_IS_TWENTYSEVENTEEN', $is_twentyseventeen );
define( 'TZWRS_IS_TWENTYNINETEEN', $is_twentynineteen );
define( 'TZWRS_IS_TWENTYTWENTY', $is_twentytwenty );
define( 'TZWRS_IS_TWENTYTWENTYONE', $is_twentytwentyone );
if (!defined('TZWRS_OPTION_NAME')) {
	define( 'TZWRS_OPTION_NAME', 'tzwrs' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_tz_weekly_radio_schedule() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tz-weekly-radio-schedule-activator.php';
	Tz_Weekly_Radio_Schedule_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_tz_weekly_radio_schedule() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tz-weekly-radio-schedule-deactivator.php';
	Tz_Weekly_Radio_Schedule_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tz_weekly_radio_schedule' );
register_deactivation_hook( __FILE__, 'deactivate_tz_weekly_radio_schedule' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tz-weekly-radio-schedule.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tz_weekly_radio_schedule() {

	$plugin = new Tz_Weekly_Radio_Schedule();
	$plugin->run();

}
run_tz_weekly_radio_schedule();
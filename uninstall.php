<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://tuzongo.com
 * @since      1.0.0
 *
 * @package    Tz_Weekly_Radio_Schedule
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! defined( 'TZWRS_OPTION_NAME' ) ) {
	define( 'TZWRS_OPTION_NAME', 'tzwrs' );
}

function tzwrs_drop_tables() {
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wrs_next_week");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wrs_this_week");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wrs_author_subscribe");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wrs_author_followers");
}

function tzwrs_remove_wrs_roles() {
	remove_role( 'wrs_manager' );
	remove_role( 'wrs_djoperator' );
	remove_role( 'wrs_dj' );
	remove_role( 'wrs_operator' );
}

function tzwrs_tidy_up() {

    unregister_sidebar( 'wrs-side-bar' );

	// ========== 
	
	define( 'TZWRS_OPTION_NAME', 'tzwrs' );

	$option_name = TZWRS_OPTION_NAME . '_wrs_default_avatar_size';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_show_name_field_id';
	delete_option($option_name);
	
	$option_name = TZWRS_OPTION_NAME . '_wrs_send_slot_notifications';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_default_images';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_logo';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_send_post_notifications';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_send_follower_notifications';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_send_dj_weekly_notifications';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_max_name_chars';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_max_show_name_chars';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_max_desc_chars';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_need_approval';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_playnowtext';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_remove_peepso_about';
	delete_option($option_name);

	wp_delete_post( get_option( TZWRS_OPTION_NAME . '_wrs_signup_page_id' ), true);
	
	$option_name = TZWRS_OPTION_NAME . '_wrs_signup_page_id';
	delete_option($option_name);
	
	wp_delete_post( get_option( TZWRS_OPTION_NAME . '_wrs_schedule_page_id' ), true);
	
	$option_name = TZWRS_OPTION_NAME . '_wrs_schedule_page_id';
	delete_option($option_name);
	
	$option_name = TZWRS_OPTION_NAME . '_wrs_text_color';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_accent_color';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_secondary_color';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_border_color';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_background_color';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_header_footer_background_color';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_custom_color_scheme';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_color_scheme';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_cf7_join_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_cf7_join_modal_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_cf7_message_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_fbpage_field_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_fbprofile_field_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_instagram_field_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_mixcloud_field_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_pnouns_field_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_twitter_field_id';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_address';
	delete_option($option_name);

	$option_name = TZWRS_OPTION_NAME . '_wrs_audio_address';
	delete_option($option_name);

	// =============

}

// Drop tables 
tzwrs_drop_tables();

// remove WRS roles
tzwrs_remove_wrs_roles();

tzwrs_tidy_up();


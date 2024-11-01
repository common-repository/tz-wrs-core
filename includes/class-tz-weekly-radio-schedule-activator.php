<?php

/**
 * Fired during plugin activation
 *
 * @link       http://tuzongo.com
 * @since      1.0.0
 *
 * @package    Tz_Weekly_Radio_Schedule
 * @subpackage Tz_Weekly_Radio_Schedule/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @author     Sid Edwards <sid@tuzongo.com>
 */
class Tz_Weekly_Radio_Schedule_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	}	
	
	/**
		 * The options name to be used in this plugin
		 *
		 * @since  	1.0.0
		 * @access 	private
		 * @var  	string 		$option_name 	Option name of this plugin
		 */
	private $option_name = 'tzwrs';
	
	function register() {

		// create tables
		$this->tzwrs_create_tables();
		
		// create WRS roles
		$this->tzwrs_create_wrs_roles();

		// create WRS pages
		$this->tzwrs_create_schedule_page();
		$this->tzwrs_create_signup_page(); 
		$this->tzwrs_create_wrs_main_menu(); 
		
		// flush rewrite rules 
		flush_rewrite_rules();
	}
	
	/**
	 * Create WRS main menu
	 * 
	 * @param $items 
	 * @param $menu
	 */
	function tzwrs_create_wrs_main_menu() {
		// Check if the menu exists
		$menu_name   = 'WRS Main Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );
		 
		// If it doesn't exist, let's create it.
		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu($menu_name);
		 
			// Set up default menu items
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   =>  __( 'Home', 'weekly-radio-schedule' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/' ), 
				'menu-item-status'  => 'publish'
			) );
		 
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  =>  __( 'Schedule', 'weekly-radio-schedule' ),
				'menu-item-url'    => esc_url( get_page_link( get_option('tzwrs_wrs_schedule_page_id') ) ), 
				'menu-item-status' => 'publish'
			) );
		
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  =>  __( 'Join the Team', 'weekly-radio-schedule' ),
				'menu-item-url'    => esc_url( get_page_link( get_option('tzwrs_wrs_signup_page_id') ) ), 
				'menu-item-status' => 'publish'
			) );
		}
	}

	/**
	 * @usage Creates the Sign up page and sets option wrs_signup_page_id value to page id
	 */
	function tzwrs_create_signup_page()
	{
	
		// Initialize the post ID to -1. This indicates no action has been taken.
		$post_id = -1;

		// Setup the author, slug, and title for the post
		$author_id = 1;
		$slug = 'wrs-join-the-team';
		$title = 'Join the Team';

		// If the page doesn't already exist, then create it
		if( null == get_page_by_path( '/' . $slug . '/', OBJECT, 'page' ) ) {

			// Set the page ID so that we know the page was created successfully
			$post_content = '<!-- wp:columns --><div class="wp-block-columns joinup"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":5} --><h5><span class="joinup-icon alignleft"></span>We want to meet the needs of our listeners in every region. That means we need DJs and Presenters from all over the world to bring their local flavour.</h5><!-- /wp:heading --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":4} --><h4>General Policy</h4><!-- /wp:heading --><!-- wp:html --><p>We are dedicated to providing entertaining and informative radio. We therefore welcome DJs and Presenters to join our Team who wish to help us achieve our mission.</p><!-- /wp:html --><!-- wp:html --><p>We promote positivity and consciousness. This is reflected in the music played by all DJs and Presenters.</p><!-- /wp:html --><!-- wp:paragraph --><p>This means no promotion of negativity. For instance, guns, violence and disrespect of people.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Our DJs and Presenters are expected ensure the content of their shows are consistent with this policy.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>All views, opinions and comments on this are welcome.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->';
			$post_id = wp_insert_post(
				array(
					'comment_status'	=>	'closed',
					'ping_status'		=>	'closed',
					'post_author'		=>	$author_id,
					'post_name'			=>	$slug,
					'post_title'		=>	$title,
					'post_content'		=>	$post_content,
					'post_status'		=>	'publish',
					'post_type'			=>	'page'
				)
			); 
			update_option('tzwrs_wrs_signup_page_id', $post_id);
			$wrsSettings['tzwrs_wrs_signup_page_id'] = ( $post_id > 0 ) ? $post_id : '';
		// Otherwise, we'll stop and set a flag
		} else {
			$page = get_page_by_path( '/' . $slug . '/', OBJECT, 'page' );
			update_option('tzwrs_wrs_signup_page_id', $page->ID);
			$wrsSettings['tzwrs_wrs_signup_page_id'] = $page->ID;
			// Arbitrarily use -2 to indicate that the page with the title already exists
			$post_id = -2;

		} // end if
	}

	/**
	 * @usage Creates the Schedule page and sets option wrs_schedule_page_id value to page id
	 */
	function tzwrs_create_schedule_page()
	{
	
		// Initialize the post ID to -1. This indicates no action has been taken.
		$post_id = -1;

		// Setup the author, slug, and title for the post
		$author_id = 1;
		$slug = 'wrs-schedule';
		$title = 'Schedule';

		// If the page doesn't already exist, then create it
		if( null == get_page_by_path( '/' . $slug . '/', OBJECT, 'page' ) ) {

			// Set the page ID so that we know the page was created successfully
			$post_content = '<!-- wp:shortcode -->[schedule_page]<!-- /wp:shortcode -->';
			$post_id = wp_insert_post(
				array(
					'comment_status'	=>	'closed',
					'ping_status'		=>	'closed',
					'post_author'		=>	$author_id,
					'post_name'			=>	$slug,
					'post_title'		=>	$title,
					'post_content'		=> $post_content,
					'post_status'		=>	'publish',
					'post_type'			=>	'page'
				)
			); 
			update_option('tzwrs_wrs_schedule_page_id', $post_id);
			$wrsSettings['tzwrs_wrs_schedule_page_id'] = ( $post_id > 0 ) ? $post_id : '';
		// Otherwise, we'll stop and set a flag
		} else {
			$page = get_page_by_path( '/' . $slug . '/', OBJECT, 'page' );
			update_option('tzwrs_wrs_schedule_page_id', $page->ID);
			$wrsSettings['tzwrs_wrs_schedule_page_id'] = $page->ID;
			// Arbitrarily use -2 to indicate that the page with the title already exists
			$post_id = -2;

		} // end if
	}

	/**
	 * @usage Creates roles DJ, DJ Operator, Operator and Manager
	 */
	function tzwrs_create_wrs_roles() {
		// WRS roles
		add_role( 'wrs_operator', __( 'Operator', 'weekly-radio-schedule' ), array( 'read' => true, 'level_0' => true ) );
		add_role( 'wrs_dj', __( 'DJ', 'weekly-radio-schedule' ), array( 'read' => true, 'level_0' => true ) );
		add_role( 'wrs_djoperator', __( 'DJ Operator', 'weekly-radio-schedule' ), array( 'read' => true, 'level_0' => true ) );
		add_role( 'wrs_manager', __( 'Manager', 'weekly-radio-schedule' ), array( 'read' => true, 'level_0' => true ) );
		
		$role = get_role( 'wrs_operator' );
		$role->add_cap( 'operate', true );
		$role->add_cap( 'edit_published_posts', true );
		$role->add_cap( 'upload_files', true );
		$role->add_cap( 'publish_posts', true );
		$role->add_cap( 'delete_published_posts', true );
		$role->add_cap( 'edit_posts', true );
		$role->add_cap( 'delete_posts', true );
		$role->add_cap( 'read', true );
		
		$role = get_role( 'wrs_dj' );
		$role->add_cap( 'add_self_to_schedule', true );
		$role->add_cap( 'edit_published_posts', true );
		$role->add_cap( 'upload_files', true );
		$role->add_cap( 'publish_posts', true );
		$role->add_cap( 'delete_published_posts', true );
		$role->add_cap( 'edit_posts', true );
		$role->add_cap( 'delete_posts', true );
		$role->add_cap( 'read', true );
		
		$role = get_role( 'wrs_djoperator' );
		$role->add_cap( 'add_self_to_schedule', true );
		$role->add_cap( 'operate', true );
		$role->add_cap( 'edit_published_posts', true );
		$role->add_cap( 'upload_files', true );
		$role->add_cap( 'publish_posts', true );
		$role->add_cap( 'delete_published_posts', true );
		$role->add_cap( 'edit_posts', true );
		$role->add_cap( 'delete_posts', true );
		$role->add_cap( 'read', true );
		
		$role = get_role( 'wrs_manager' );
		$role->add_cap( 'add_self_to_schedule', true );
		$role->add_cap( 'run_tings', true );
		$role->add_cap( 'operate', true );
		$role->add_cap( 'edit_published_posts', true );
		$role->add_cap( 'upload_files', true );
		$role->add_cap( 'publish_posts', true );
		$role->add_cap( 'delete_published_posts', true );
		$role->add_cap( 'edit_posts', true );
		$role->add_cap( 'delete_posts', true );
		$role->add_cap( 'read', true );
		$role->add_cap( 'delete_others_pages', true );
		$role->add_cap( 'delete_others_posts', true );
		$role->add_cap( 'delete_pages', true );
		$role->add_cap( 'delete_private_pages', true );
		$role->add_cap( 'delete_private_posts', true );
		$role->add_cap( 'delete_published_pages', true );
		$role->add_cap( 'edit_others_pages', true );
		$role->add_cap( 'edit_others_posts', true );
		$role->add_cap( 'edit_pages', true );
		$role->add_cap( 'edit_private_pages', true );
		$role->add_cap( 'edit_private_posts', true );
		$role->add_cap( 'edit_published_pages', true );
		$role->add_cap( 'edit_published_posts', true );
		$role->add_cap( 'list_users', true );
		$role->add_cap( 'manage_categories', true );
		$role->add_cap( 'manage_links', true );
		$role->add_cap( 'manage_options', true );
		$role->add_cap( 'moderate_comments', true );
		$role->add_cap( 'promote_users', true );
		$role->add_cap( 'publish_pages', true );
		$role->add_cap( 'read_private_pages', true );
		$role->add_cap( 'read_private_posts', true );
		$role->add_cap( 'remove_users', true );
		$role->add_cap( 'upload_files', true );

		$role = get_role( 'administrator' );
		$role->add_cap( 'run_tings', true );
		$role->add_cap( 'add_self_to_schedule', true );
		$role->add_cap( 'operate', true );
	}

	/**
	 * @usage Creates WRS database tables.
	 */
	function tzwrs_create_tables() {
		global $wpdb;
		global $jal_db_version;

		$table_name = $wpdb->prefix . "wrs_this_week"; 
		$charset_collate = $wpdb->get_charset_collate();

		// WRS tables. This week, next, . . .
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  slot_id mediumint(8) NOT NULL DEFAULT '0',
		  user_id mediumint(8) DEFAULT '0',
		  hour mediumint(8) NOT NULL DEFAULT '0',
		  add_dj tinyint(1) DEFAULT NULL,
		  temp_user_id mediumint(8) DEFAULT '0',
		  attended tinyint(1) DEFAULT '0',
		  PRIMARY KEY  (slot_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$table_name = $wpdb->prefix . "wrs_next_week"; 
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  slot_id mediumint(8) NOT NULL DEFAULT '0',
		  user_id mediumint(8) DEFAULT '0',
		  hour mediumint(8) NOT NULL DEFAULT '0',
		  add_dj tinyint(1) DEFAULT NULL,
		  temp_user_id mediumint(8) DEFAULT '0',
		  attended tinyint(1) DEFAULT '0',
		  PRIMARY KEY  (slot_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		// WRS Follow tables. 
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wrs_author_subscribe");
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wrs_author_followers");

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . "wrs_author_subscribe"; 
		$sql1 = "CREATE TABLE IF NOT EXISTS $table_name (
		 id int(11) NOT NULL AUTO_INCREMENT,
		 activation_code varchar(255) NOT NULL,
		 email varchar(75) NOT NULL,
		 status int(11) NOT NULL,
		 followed_authors text NOT NULL,
		 PRIMARY KEY (id)
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql1);

		$table_name = $wpdb->prefix . "wrs_author_followers"; 
		$sql2 = "CREATE TABLE IF NOT EXISTS $table_name (
		 id int(11) NOT NULL AUTO_INCREMENT,
		 author_id int(11) NOT NULL,
		 followers_list text NOT NULL,
		 PRIMARY KEY (id)
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql2);

		$this->tzwrs_insert_empty_slots();
	}

	// ============ Weekly Radio Schedule ================
	// Handles upload and attachment of avatars for
	// demo users. Supports User Profile Picture
	// By Cozmoslabs
	// https://wordpress.org/plugins/metronet-profile-picture/
	// ==============================================

	function tzwrs_add_avatar($user_id)
	{
		global $wpdb;
		$user = get_user_by( 'ID', $user_id );
		$username = $user->user_login;
		
		$file = TZWRS_DIRECTORY_URL . '/images/' . $username .'.svg';
		$filename = basename($file);

		//Get/Create Profile Picture Post.
		$post_args = array(
			'post_type'   => 'mt_pp',
			'author'      => $user_id,
			'post_status' => 'publish',
		);
		$posts     = get_posts( $post_args );
		if ( ! $posts ) {
			$post_id = wp_insert_post(
				array(
					'post_author' => $user_id,
					'post_type'   => 'mt_pp',
					'post_status' => 'publish',
					'post_title'  => $user->data->display_name,
				)
			);
		} else {
			$post    = end( $posts );
			$post_id = $post->ID;
		}

		$upload_file = wp_upload_bits($filename, null, file_get_contents($file));
		if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent' => $post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
			}
		}

		// User Profile Picture plugin integration 
		update_user_option( $post_id, 'metronet_post_id', $post_id );
		update_user_option( $post_id, 'metronet_image_id', $attachment_id );
		update_user_option( $post_id, 'metronet_avatar_override', 'on' );
		set_post_thumbnail( $post_id, $attachment_id );
		return $attachment_id;
	}

	// ============ Weekly Radio Schedule ================
	// Create WRS tables for two weeks, this and next
	// To do: cater for up to 4 weeks
	// Creates demo users
	// Creates WRS roles
	// Inserts demo slots
	// ==============================================

	function tzwrs_insert_empty_slots() {
			global $wpdb;
			global $jal_db_version;

			// TZWRS empty slot data
			$wpwrs_wrs_this_week = array(
		  array('slot_id' => '1','user_id' => '0','hour' => '1','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '2','user_id' => '0','hour' => '25','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '3','user_id' => '0','hour' => '49','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '4','user_id' => '0','hour' => '73','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '5','user_id' => '0','hour' => '97','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '6','user_id' => '0','hour' => '121','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '7','user_id' => '0','hour' => '145','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '8','user_id' => '0','hour' => '2','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '9','user_id' => '0','hour' => '26','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '10','user_id' => '0','hour' => '50','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '11','user_id' => '0','hour' => '74','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '12','user_id' => '0','hour' => '98','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '13','user_id' => '0','hour' => '122','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '14','user_id' => '0','hour' => '146','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '15','user_id' => '0','hour' => '3','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '16','user_id' => '0','hour' => '27','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '17','user_id' => '0','hour' => '51','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '18','user_id' => '0','hour' => '75','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '19','user_id' => '0','hour' => '99','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '20','user_id' => '0','hour' => '123','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '21','user_id' => '0','hour' => '147','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '22','user_id' => '0','hour' => '4','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '23','user_id' => '0','hour' => '28','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '24','user_id' => '0','hour' => '52','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '25','user_id' => '0','hour' => '76','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '26','user_id' => '0','hour' => '100','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '27','user_id' => '0','hour' => '124','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '28','user_id' => '0','hour' => '148','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '29','user_id' => '0','hour' => '5','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '30','user_id' => '0','hour' => '29','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '31','user_id' => '0','hour' => '53','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '32','user_id' => '0','hour' => '77','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '33','user_id' => '0','hour' => '101','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '34','user_id' => '0','hour' => '125','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '35','user_id' => '0','hour' => '149','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '36','user_id' => '0','hour' => '6','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '37','user_id' => '0','hour' => '30','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '38','user_id' => '0','hour' => '54','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '39','user_id' => '0','hour' => '78','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '40','user_id' => '0','hour' => '102','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '41','user_id' => '0','hour' => '126','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '42','user_id' => '0','hour' => '150','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '43','user_id' => '0','hour' => '7','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '44','user_id' => '0','hour' => '31','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '45','user_id' => '0','hour' => '55','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '46','user_id' => '0','hour' => '79','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '47','user_id' => '0','hour' => '103','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '48','user_id' => '0','hour' => '127','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '49','user_id' => '0','hour' => '151','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '50','user_id' => '0','hour' => '8','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '51','user_id' => '0','hour' => '32','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '52','user_id' => '0','hour' => '56','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '53','user_id' => '0','hour' => '80','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '54','user_id' => '0','hour' => '104','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '55','user_id' => '0','hour' => '128','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '56','user_id' => '0','hour' => '152','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '57','user_id' => '0','hour' => '9','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '58','user_id' => '0','hour' => '33','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '59','user_id' => '0','hour' => '57','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '60','user_id' => '0','hour' => '81','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '61','user_id' => '0','hour' => '105','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '62','user_id' => '0','hour' => '129','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '63','user_id' => '0','hour' => '153','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '64','user_id' => '0','hour' => '10','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '65','user_id' => '0','hour' => '34','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '66','user_id' => '0','hour' => '58','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '67','user_id' => '0','hour' => '82','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '68','user_id' => '0','hour' => '106','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '69','user_id' => '0','hour' => '130','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '70','user_id' => '0','hour' => '154','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '71','user_id' => '0','hour' => '11','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '72','user_id' => '0','hour' => '35','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '73','user_id' => '0','hour' => '59','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '74','user_id' => '0','hour' => '83','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '75','user_id' => '0','hour' => '107','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '76','user_id' => '0','hour' => '131','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '77','user_id' => '0','hour' => '155','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '78','user_id' => '0','hour' => '12','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '79','user_id' => '0','hour' => '36','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '80','user_id' => '0','hour' => '60','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '81','user_id' => '0','hour' => '84','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '82','user_id' => '0','hour' => '108','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '83','user_id' => '0','hour' => '132','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '84','user_id' => '0','hour' => '156','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '85','user_id' => '0','hour' => '13','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '86','user_id' => '0','hour' => '37','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '87','user_id' => '0','hour' => '61','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '88','user_id' => '0','hour' => '85','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '89','user_id' => '0','hour' => '109','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '90','user_id' => '0','hour' => '133','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '91','user_id' => '0','hour' => '157','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '92','user_id' => '0','hour' => '14','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '93','user_id' => '0','hour' => '38','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '94','user_id' => '0','hour' => '62','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '95','user_id' => '0','hour' => '86','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '96','user_id' => '0','hour' => '110','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '97','user_id' => '0','hour' => '134','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '98','user_id' => '0','hour' => '158','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '99','user_id' => '0','hour' => '15','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '100','user_id' => '0','hour' => '39','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '101','user_id' => '0','hour' => '63','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '102','user_id' => '0','hour' => '87','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '103','user_id' => '0','hour' => '111','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '104','user_id' => '0','hour' => '135','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '105','user_id' => '0','hour' => '159','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '106','user_id' => '0','hour' => '16','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '107','user_id' => '0','hour' => '40','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '108','user_id' => '0','hour' => '64','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '109','user_id' => '0','hour' => '88','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '110','user_id' => '0','hour' => '112','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '111','user_id' => '0','hour' => '136','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '112','user_id' => '0','hour' => '160','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '113','user_id' => '0','hour' => '17','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '114','user_id' => '0','hour' => '41','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '115','user_id' => '0','hour' => '65','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '116','user_id' => '0','hour' => '89','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '117','user_id' => '0','hour' => '113','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '118','user_id' => '0','hour' => '137','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '119','user_id' => '0','hour' => '161','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '120','user_id' => '0','hour' => '18','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '121','user_id' => '0','hour' => '42','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '122','user_id' => '0','hour' => '66','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '123','user_id' => '0','hour' => '90','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '124','user_id' => '0','hour' => '114','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '125','user_id' => '0','hour' => '138','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '126','user_id' => '0','hour' => '162','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '127','user_id' => '0','hour' => '19','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '128','user_id' => '0','hour' => '43','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '129','user_id' => '0','hour' => '67','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '130','user_id' => '0','hour' => '91','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '131','user_id' => '0','hour' => '115','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '132','user_id' => '0','hour' => '139','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '133','user_id' => '0','hour' => '163','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '134','user_id' => '0','hour' => '20','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '135','user_id' => '0','hour' => '44','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '136','user_id' => '0','hour' => '68','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '137','user_id' => '0','hour' => '92','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '138','user_id' => '0','hour' => '116','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '139','user_id' => '0','hour' => '140','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '140','user_id' => '0','hour' => '164','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '141','user_id' => '0','hour' => '21','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '142','user_id' => '0','hour' => '45','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '143','user_id' => '0','hour' => '69','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '144','user_id' => '0','hour' => '93','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '145','user_id' => '0','hour' => '117','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '146','user_id' => '0','hour' => '141','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '147','user_id' => '0','hour' => '165','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '148','user_id' => '0','hour' => '22','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '149','user_id' => '0','hour' => '46','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '150','user_id' => '0','hour' => '70','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '151','user_id' => '0','hour' => '94','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '152','user_id' => '0','hour' => '118','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '153','user_id' => '0','hour' => '142','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '154','user_id' => '0','hour' => '166','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '155','user_id' => '0','hour' => '23','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '156','user_id' => '0','hour' => '47','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '157','user_id' => '0','hour' => '71','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '158','user_id' => '0','hour' => '95','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '159','user_id' => '0','hour' => '119','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '160','user_id' => '0','hour' => '143','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '161','user_id' => '0','hour' => '167','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '162','user_id' => '0','hour' => '24','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '163','user_id' => '0','hour' => '48','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '164','user_id' => '0','hour' => '72','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '165','user_id' => '0','hour' => '96','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '166','user_id' => '0','hour' => '120','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '167','user_id' => '0','hour' => '144','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '168','user_id' => '0','hour' => '168','add_dj' => '0','temp_user_id' => '0','attended' => '0')
		);

			$wpwrs_wrs_next_week = array(
		  array('slot_id' => '1','user_id' => '0','hour' => '1','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '2','user_id' => '0','hour' => '25','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '3','user_id' => '0','hour' => '49','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '4','user_id' => '0','hour' => '73','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '5','user_id' => '0','hour' => '97','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '6','user_id' => '0','hour' => '121','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '7','user_id' => '0','hour' => '145','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '8','user_id' => '0','hour' => '2','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '9','user_id' => '0','hour' => '26','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '10','user_id' => '0','hour' => '50','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '11','user_id' => '0','hour' => '74','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '12','user_id' => '0','hour' => '98','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '13','user_id' => '0','hour' => '122','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '14','user_id' => '0','hour' => '146','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '15','user_id' => '0','hour' => '3','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '16','user_id' => '0','hour' => '27','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '17','user_id' => '0','hour' => '51','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '18','user_id' => '0','hour' => '75','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '19','user_id' => '0','hour' => '99','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '20','user_id' => '0','hour' => '123','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '21','user_id' => '0','hour' => '147','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '22','user_id' => '0','hour' => '4','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '23','user_id' => '0','hour' => '28','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '24','user_id' => '0','hour' => '52','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '25','user_id' => '0','hour' => '76','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '26','user_id' => '0','hour' => '100','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '27','user_id' => '0','hour' => '124','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '28','user_id' => '0','hour' => '148','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '29','user_id' => '0','hour' => '5','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '30','user_id' => '0','hour' => '29','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '31','user_id' => '0','hour' => '53','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '32','user_id' => '0','hour' => '77','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '33','user_id' => '0','hour' => '101','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '34','user_id' => '0','hour' => '125','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '35','user_id' => '0','hour' => '149','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '36','user_id' => '0','hour' => '6','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '37','user_id' => '0','hour' => '30','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '38','user_id' => '0','hour' => '54','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '39','user_id' => '0','hour' => '78','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '40','user_id' => '0','hour' => '102','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '41','user_id' => '0','hour' => '126','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '42','user_id' => '0','hour' => '150','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '43','user_id' => '0','hour' => '7','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '44','user_id' => '0','hour' => '31','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '45','user_id' => '0','hour' => '55','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '46','user_id' => '0','hour' => '79','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '47','user_id' => '0','hour' => '103','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '48','user_id' => '0','hour' => '127','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '49','user_id' => '0','hour' => '151','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '50','user_id' => '0','hour' => '8','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '51','user_id' => '0','hour' => '32','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '52','user_id' => '0','hour' => '56','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '53','user_id' => '0','hour' => '80','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '54','user_id' => '0','hour' => '104','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '55','user_id' => '0','hour' => '128','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '56','user_id' => '0','hour' => '152','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '57','user_id' => '0','hour' => '9','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '58','user_id' => '0','hour' => '33','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '59','user_id' => '0','hour' => '57','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '60','user_id' => '0','hour' => '81','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '61','user_id' => '0','hour' => '105','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '62','user_id' => '0','hour' => '129','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '63','user_id' => '0','hour' => '153','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '64','user_id' => '0','hour' => '10','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '65','user_id' => '0','hour' => '34','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '66','user_id' => '0','hour' => '58','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '67','user_id' => '0','hour' => '82','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '68','user_id' => '0','hour' => '106','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '69','user_id' => '0','hour' => '130','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '70','user_id' => '0','hour' => '154','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '71','user_id' => '0','hour' => '11','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '72','user_id' => '0','hour' => '35','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '73','user_id' => '0','hour' => '59','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '74','user_id' => '0','hour' => '83','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '75','user_id' => '0','hour' => '107','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '76','user_id' => '0','hour' => '131','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '77','user_id' => '0','hour' => '155','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '78','user_id' => '0','hour' => '12','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '79','user_id' => '0','hour' => '36','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '80','user_id' => '0','hour' => '60','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '81','user_id' => '0','hour' => '84','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '82','user_id' => '0','hour' => '108','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '83','user_id' => '0','hour' => '132','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '84','user_id' => '0','hour' => '156','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '85','user_id' => '0','hour' => '13','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '86','user_id' => '0','hour' => '37','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '87','user_id' => '0','hour' => '61','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '88','user_id' => '0','hour' => '85','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '89','user_id' => '0','hour' => '109','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '90','user_id' => '0','hour' => '133','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '91','user_id' => '0','hour' => '157','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '92','user_id' => '0','hour' => '14','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '93','user_id' => '0','hour' => '38','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '94','user_id' => '0','hour' => '62','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '95','user_id' => '0','hour' => '86','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '96','user_id' => '0','hour' => '110','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '97','user_id' => '0','hour' => '134','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '98','user_id' => '0','hour' => '158','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '99','user_id' => '0','hour' => '15','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '100','user_id' => '0','hour' => '39','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '101','user_id' => '0','hour' => '63','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '102','user_id' => '0','hour' => '87','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '103','user_id' => '0','hour' => '111','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '104','user_id' => '0','hour' => '135','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '105','user_id' => '0','hour' => '159','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '106','user_id' => '0','hour' => '16','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '107','user_id' => '0','hour' => '40','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '108','user_id' => '0','hour' => '64','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '109','user_id' => '0','hour' => '88','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '110','user_id' => '0','hour' => '112','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '111','user_id' => '0','hour' => '136','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '112','user_id' => '0','hour' => '160','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '113','user_id' => '0','hour' => '17','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '114','user_id' => '0','hour' => '41','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '115','user_id' => '0','hour' => '65','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '116','user_id' => '0','hour' => '89','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '117','user_id' => '0','hour' => '113','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '118','user_id' => '0','hour' => '137','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '119','user_id' => '0','hour' => '161','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '120','user_id' => '0','hour' => '18','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '121','user_id' => '0','hour' => '42','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '122','user_id' => '0','hour' => '66','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '123','user_id' => '0','hour' => '90','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '124','user_id' => '0','hour' => '114','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '125','user_id' => '0','hour' => '138','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '126','user_id' => '0','hour' => '162','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '127','user_id' => '0','hour' => '19','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '128','user_id' => '0','hour' => '43','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '129','user_id' => '0','hour' => '67','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '130','user_id' => '0','hour' => '91','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '131','user_id' => '0','hour' => '115','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '132','user_id' => '0','hour' => '139','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '133','user_id' => '0','hour' => '163','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '134','user_id' => '0','hour' => '20','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '135','user_id' => '0','hour' => '44','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '136','user_id' => '0','hour' => '68','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '137','user_id' => '0','hour' => '92','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '138','user_id' => '0','hour' => '116','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '139','user_id' => '0','hour' => '140','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '140','user_id' => '0','hour' => '164','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '141','user_id' => '0','hour' => '21','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '142','user_id' => '0','hour' => '45','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '143','user_id' => '0','hour' => '69','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '144','user_id' => '0','hour' => '93','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '145','user_id' => '0','hour' => '117','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '146','user_id' => '0','hour' => '141','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '147','user_id' => '0','hour' => '165','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '148','user_id' => '0','hour' => '22','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '149','user_id' => '0','hour' => '46','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '150','user_id' => '0','hour' => '70','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '151','user_id' => '0','hour' => '94','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '152','user_id' => '0','hour' => '118','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '153','user_id' => '0','hour' => '142','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '154','user_id' => '0','hour' => '166','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '155','user_id' => '0','hour' => '23','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '156','user_id' => '0','hour' => '47','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '157','user_id' => '0','hour' => '71','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '158','user_id' => '0','hour' => '95','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '159','user_id' => '0','hour' => '119','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '160','user_id' => '0','hour' => '143','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '161','user_id' => '0','hour' => '167','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '162','user_id' => '0','hour' => '24','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '163','user_id' => '0','hour' => '48','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '164','user_id' => '0','hour' => '72','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '165','user_id' => '0','hour' => '96','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '166','user_id' => '0','hour' => '120','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '167','user_id' => '0','hour' => '144','add_dj' => '0','temp_user_id' => '0','attended' => '0'),
		  array('slot_id' => '168','user_id' => '0','hour' => '168','add_dj' => '0','temp_user_id' => '0','attended' => '0')
		);

			$table_name = $wpdb->prefix . "wrs_this_week"; 
			$count = $wpdb->get_var("SELECT COUNT(*)
			FROM {$table_name} WHERE slot_id IS NOT NULL");
			if($count == 0)
			{
				foreach( $wpwrs_wrs_this_week as $row )
				{
					$wpdb->insert( $table_name, $row);  
				}
			}
			
			$table_name = $wpdb->prefix . "wrs_next_week"; 
			$count = $wpdb->get_var("SELECT COUNT(*)
			FROM {$table_name} WHERE slot_id IS NOT NULL");
			if($count == 0)
			{
				foreach( $wpwrs_wrs_next_week as $row )
				{
					 $wpdb->insert( $table_name, $row);  
				}
			}
		}

}
$Tz_Weekly_Radio_Schedule_Activator = new Tz_Weekly_Radio_Schedule_Activator(); // creates an object

if ( class_exists( 'Tz_Weekly_Radio_Schedule_Activator' ) ) {
	$Tz_Weekly_Radio_Schedule_Activator->register();
}


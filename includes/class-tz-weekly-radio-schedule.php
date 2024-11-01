<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://tuzongo.com
 * @since      1.0.0
 *
 * @package    Tz_Weekly_Radio_Schedule
 * @subpackage Tz_Weekly_Radio_Schedule/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @author     Sid Edwards <sid@tuzongo.com>
 */
 

class Tz_Weekly_Radio_Schedule {

	/**
		 * The options name to be used in this plugin
		 *
		 * @since  	1.0.0
		 * @access 	private
		 * @var  	string 		$option_name 	Option name of this plugin
		 */
	private $option_name;
	
		
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TZ_WEEKLY_RADIO_SCHEDULE_VERSION' ) ) {
			$this->version = TZ_WEEKLY_RADIO_SCHEDULE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'tz-weekly-radio-schedule';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
        add_action( 'init', array($this, 'init_callback') );
		add_action( 'admin_init', array($this, 'tz_general_section') );
	}

	function tz_general_section() {  
		add_settings_section(  
			'my_settings_section', // Section ID 
			'', // Section Title
			array( $this, TZWRS_OPTION_NAME . '_wrs_address_section_options_cb' ), // Callback
			'general' // What Page?  This makes the section show up on the General Settings Page
		);

		add_settings_field( 
			TZWRS_OPTION_NAME . '_wrs_address', // Option ID
			'Address', // Label
			array( $this, TZWRS_OPTION_NAME . '_wrs_address_options_cb' ), // !important - This is where the args go!
			'general', // Page it will be displayed (General Settings)
			'my_settings_section', // Name of our section
			array( // The $args
				TZWRS_OPTION_NAME . '_wrs_address' // Should match Option ID
			)  
		); 

		register_setting('general', TZWRS_OPTION_NAME . '_wrs_address', 'esc_attr');
	}

	function tzwrs_wrs_address_section_options_cb() { // Section Callback
		//echo '<p>A little message on editing info</p>';  
	}

	function tzwrs_wrs_address_options_cb($args) {  // Textbox Callback
		$option = get_option($args[0]);
		echo '<textarea type="textarea" rows="6" cols="50" id="'. esc_attr($args[0]) .'" name="'. esc_attr($args[0]) .'" value="' . esc_attr($option) . '">' . esc_attr(get_option( OPTION_NAME . '_wrs_address', 'tuzongo.com' )) . '</textarea>';
	}

    /*
     * Called when WP is loaded; need to signal Add-on plugins that everything's ready
     */
    public function init_callback()
    {
        do_action('tzwrs_init');

    }

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tz-weekly-radio-schedule-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tz-weekly-radio-schedule-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tz-weekly-radio-schedule-admin.php';
		
		if ( file_exists(TZWRS_DIRECTORY . 'widgets/wrs-team-widget.php')) {
			require_once(TZWRS_DIRECTORY . 'widgets/wrs-team-widget.php');
		}else{
			exit;
		}

		if ( file_exists(TZWRS_DIRECTORY . 'widgets/wrs-day-widget.php')) {
			require_once(TZWRS_DIRECTORY . 'widgets/wrs-day-widget.php');
		}else{
			exit;
		}

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tz-weekly-radio-schedule-public.php';

		$this->loader = new Tz_Weekly_Radio_Schedule_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Tz_Weekly_Radio_Schedule_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Tz_Weekly_Radio_Schedule_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );

		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_show_name_field_id' );

		add_action( 'admin_bar_menu', array($plugin_admin, 'tzwrs_admin_bar' ), 40 );
		add_action('admin_head', array($plugin_admin, 'tzwrs_head_style' ), 6);
		//Add Settings link to the plugin
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'tzwrs_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'tzwrs_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'tzwrs_add_options_page' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'tzwrs_add_action_link' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'pluginRowMeta', 10, 4);	
		$this->loader->add_action( 'admin_init', $plugin_admin, 'tzwrs_register_setting' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'tzwrs_admin_footer_scripts' );
		$this->loader->add_action( 'admin_init', $this, 'tzwrs_options_setup' );
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'userMetaForm' ); // editing your own profile
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'userMetaForm' ); // editing another user
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'save_extra_user_profile_fields' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'save_extra_user_profile_fields' );
	} 

	public function tzwrs_options_setup() {
		global $pagenow;
		if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
			// Now we'll replace the 'Insert into Post Button' inside Thickbox
			add_filter( 'gettext', array($this, 'replace_thickbox_text'), 1, 3 );
		}
	}
 
	public function replace_thickbox_text($translated_text, $text, $domain) {
		if ('Insert into Post' == $text) {
			$referer = strpos( wp_get_referer(), 'tz-weekly-radio-schedule' );
			if ( $referer != '' ) {
				return esc_html__('I want this to be my logo!', 'weekly-radio-schedule' );
			}
		}
		return $translated_text;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Tz_Weekly_Radio_Schedule_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'tzwrs_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'tzwrs_enqueue_scripts' );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'tzwrs_sticky_clock' );
		$this->loader->add_action( 'wp_body_open', $plugin_public, 'tzwrs_accordion' );
		add_shortcode( 'shows_today', array( $plugin_public, 'tzwrs_today_gen' ) );
		add_shortcode( 'shows_coming', array( $plugin_public, 'tzwrs_shows_gen' ) );
		add_shortcode( 'wrs_this_person', array( $plugin_public, 'tzwrs_loggedonas' ) );
		add_shortcode( 'schedule_page', array( $plugin_public, 'tzwrs_schedule_page_shortcode' ) );
		add_shortcode( 'tabbed_coming_up', array( $plugin_public, 'tzwrs_tabbed_week_coming_up' ) );
		add_shortcode( 'tabbyending', array( $plugin_public, 'tzwrs_shortcode_tabbyending' ) );
		add_shortcode( 'tabby', array( $plugin_public, 'tzwrs_shortcode_tabby' ) );
		add_shortcode( 'daily_schedule', array( $plugin_public, 'tzwrs_dj_daily_schedule' ) );
		add_shortcode( 'wrs_twentynineteen_content', array($plugin_public, 'tzwrs_twentynineteen_content_shortcode' ) );
		$wrs_author_levels = TZWRS_AUTHOR_LEVELS;
		add_action( 'init', function() use ($wrs_author_levels){
			global $wp_rewrite;
			$wrs_author_levels[] = 'author';
			add_rewrite_tag( '%author_level%', '(' . implode( '|', $wrs_author_levels ) . ')' );
			$wp_rewrite->author_base = '%author_level%';
		});
		add_filter( 'author_rewrite_rules', function( $author_rewrite_rules ){
			foreach ( $author_rewrite_rules as $pattern => $substitution ) {
				if ( FALSE === strpos( $substitution, 'author_name' ) ) {
					unset( $author_rewrite_rules[$pattern] );
				}
			}
			return $author_rewrite_rules;
		}, 10, 1 );
		add_filter( 'author_link', function( $link, $author_id ) use ($wrs_author_levels) {
			$user_meta = get_userdata( $author_id );
			if ( $user_meta ) {
				foreach ($wrs_author_levels as $rol) {
					if (in_array($rol, $user_meta->roles)){
						return str_replace( '%author_level%', $rol, $link );
					}
				}
			}
			return str_replace( '%author_level%', 'author', $link );
		}, 10, 2 );	
		add_filter( 'get_the_archive_title_prefix', function ( $prefix ) use ($wrs_author_levels) {
			if (is_author()) {
				global $wp, $wp_roles;
				if ( isset($wp->query_vars['author_name']) ) {
					$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($wp->query_vars['author_name']));
					$curauth = get_user_by('slug', $wp->query_vars['author_name']);
				}
				else 
				{
					$url = PeepSoUrlSegments::get_instance();
					if ( $url->get(1) )
					{
						$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($url->get(1)));
						$curauth = get_user_by('ID', $author_id);
					}
				}

				$user_meta=get_userdata($author_id);
				if ( isset($user_meta->roles[0]) ) {
					if ( in_array( $user_roles=$user_meta->roles[0], $wrs_author_levels) ) {
						$prefix = esc_html__( 'Station ', 'weekly-radio-schedule' ) . strip_tags(translate_user_role( $wp_roles->roles[ $user_meta->roles[0] ]['name'] ) ) . ':';
					}
				}
			} 
			return $prefix;
		}, 10, 2 );
		add_action('wp_ajax_nopriv_tzwrs_schedule_alert', array( $plugin_public, 'tzwrs_schedule_alert') );
		add_action('wp_ajax_tzwrs_schedule_alert', array( $plugin_public, 'tzwrs_schedule_alert') );
		add_action('wp_ajax_nopriv_subscribe_to_wrs_djs', array( $plugin_public, 'subscribe_to_wrs_djs') );
		add_action('wp_ajax_subscribe_to_wrs_djs', array( $plugin_public, 'subscribe_to_wrs_djs') );
		add_action('wp_ajax_nopriv_tzwrs_unfollow_wrs_djs', array( $plugin_public, 'tzwrs_unfollow_wrs_djs') );
		add_action('wp_ajax_tzwrs_unfollow_wrs_djs', array( $plugin_public, 'tzwrs_unfollow_wrs_djs') );
		add_action('wp_ajax_nopriv_tzwrs_follow_wrs_djs', array( $plugin_public, 'tzwrs_follow_wrs_djs') );
		add_action('wp_ajax_tzwrs_follow_wrs_djs', array( $plugin_public, 'tzwrs_follow_wrs_djs') );
		add_action('wp_ajax_nopriv_tzwrs_get_followers', array( $plugin_public, 'tzwrs_get_followers') );
		add_action('wp_ajax_tzwrs_get_followers', array( $plugin_public, 'tzwrs_get_followers') );
		add_action('wp_ajax_nopriv_tzwrs_get_followees', array( $plugin_public, 'tzwrs_get_followees') );
		add_action('wp_ajax_tzwrs_get_followees', array( $plugin_public, 'tzwrs_get_followees') );
		add_action('wp_ajax_nopriv_load_subscribed_authors', array( $plugin_public, 'load_subscribed_authors') );
		add_action('wp_ajax_load_subscribed_authors', array( $plugin_public, 'load_subscribed_authors') );
		add_action( 'transition_post_status',  array( $plugin_public, 'tzwrs_mail_on_publish' ), 10, 3 );
		do_action( 'tzwrs_main_enque' );
		// flush rewrite rules 
		flush_rewrite_rules();

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

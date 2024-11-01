<?php
/**
 * The admin-specific functionality of the plugin. 
 *
 * @link       http://tuzongo.com
 * @since      1.0.0
 *
 * @package    Tz_Weekly_Radio_Schedule
 * @subpackage Tz_Weekly_Radio_Schedule/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Sid Edwards <sid@tuzongo.com>
 */
class Tz_Weekly_Radio_Schedule_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

    function pluginRowMeta($plugin_meta, $plugin_file, $plugin_data, $status){
        if ( strpos( $plugin_file, 'tz-wrs-core.php' ) !== false ) {
            $plugin_meta[] = "<a href='https://wrs.tuzongo.com/docs/' target='_blank'>" . __("Docs", "weekly-radio-schedule") . "</a>";
            $plugin_meta[] = "<a href='https://www.tuzongo.com/donate/' target='_blank'>" . __("Donate", "weekly-radio-schedule") . "</a>";
        }
        return $plugin_meta;
    }

	function tzwrs_add_action_link($actions) {

		$Tz_Weekly_Radio_Schedule = new Tz_Weekly_Radio_Schedule();
		$new_actions = array(
			'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $Tz_Weekly_Radio_Schedule->get_plugin_name() ) . '" title="' . esc_attr( __( 'View Tz Weekly Radio Schedule Settings', 'weekly-radio-schedule' ) ) . '">' . __( 'Settings', 'weekly-radio-schedule' ) . '</a>',
		);

		return array_merge( $new_actions, $actions );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function tzwrs_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tz-weekly-radio-schedule-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'followCSS', TZWRS_DIRECTORY_URL . '/public/css/follow.css', array(), $this->version );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function tzwrs_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        // Add the color picker css file       
		wp_enqueue_script( 'followJS', TZWRS_DIRECTORY_URL . '/public/js/follow.js', array( 'jquery' ), $this->version, false );
		$config_array = array('ajaxUrl' => admin_url('admin-ajax.php'), 'ajaxNonce' => wp_create_nonce('follow-nonce'),
			'currentURL' => $_SERVER['REQUEST_URI']);
		wp_localize_script('followJS', 'ajaxData', $config_array);
        wp_enqueue_style( 'wp-color-picker' ); 
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tz-weekly-radio-schedule-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );
		wp_enqueue_script('modernizr-js', plugin_dir_url( __FILE__ ) . 'js/modernizr.custom.js', array('jquery'), $this->version, false );

		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
	 
		wp_enqueue_script( 'tz-wrs-upload', TZWRS_DIRECTORY_URL .'/admin/js/tz-wrs-upload.js', array( 'jquery' ) );
	}

	/**
	 * @usage Customise adminbar menu items adds schedule alert, team modal link
	 * @param $wp_admin_bar
	 */
	function tzwrs_admin_bar( $wp_admin_bar ) {
		$current_user = wp_get_current_user();
		
		global $wpdb;
		$schedule_alerts = 0;
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$futures = array('this','next');
		foreach ( $futures as $future ) {
			$table_name = $wpdb->prefix . 'wrs_' . $future . '_week';
			$rows = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE add_dj = 1 AND hour >= ' . $this_hour );
			foreach ( $rows as $row )
			{
				if ( current_user_can( 'run_tings' ) || current_user_can( 'operate' ) ) {
					$schedule_alerts++;
				}
			}
		}

		$schedule_alert = $schedule_alerts ? '<span class="schedule_alert" title="' . esc_attr($schedule_alerts . ' ' . __( 'Slot Request', 'weekly-radio-schedule' ) ). '" alt="' . esc_attr($schedule_alerts . ' ' . __( 'Slot Request', 'weekly-radio-schedule' ) ). '">•</span>' : '<span class="no_schedule_alert"></span>';

		$wp_admin_bar->add_menu( array( 
			'id' => 'schedule', 
			'title' => '<span>' . __( 'Schedule', 'weekly-radio-schedule' ) . $schedule_alert . '</span>', 
			'href' => esc_url( get_page_link( intval(get_option(TZWRS_OPTION_NAME . '_wrs_schedule_page_id') ) ) ) )
		);

		$wp_admin_bar->add_menu( array( 
			'id' => 'the_team', 
			'title' => '<span data-currid="' . intval($current_user->ID) . '">' . __( 'Team', 'weekly-radio-schedule' ) . '</span>', 
			'href' => '#',
			'meta'  => array(
				'title' 	=> __('Team', 'weekly-radio-schedule'),
				'onclick'	=> 'document.getElementById("id04").style.display="block"',
			),
		));


	}
	
	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Tz Weekly Radio Schedule Settings', 'weekly-radio-schedule' ),
			__( 'Tz Weekly Radio Schedule', 'weekly-radio-schedule' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'tzwrs_display_options_page' )
		);
	}

	/**
	 * register settings sections
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_register_setting() {
		global $wpdb;

		// Add a General section
		add_settings_section(
			TZWRS_OPTION_NAME . '_general',
			__( 'WRS General settings', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_general_cb' ),
			$this->plugin_name
		);
	
		// Add a WRS Pages section
		add_settings_section(
			TZWRS_OPTION_NAME . '_wrspages',
			__( 'WRS Pages', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrspages_cb' ),
			$this->plugin_name
		);

		if (
			( in_array('peepso-core/peepso.php', apply_filters('active_plugins', get_option('active_plugins'))) && isset($links['about']) ) ||
			in_array('contact-form-7/wp-contact-form-7.php', apply_filters('active_plugins', get_option('active_plugins')))  ||
			in_array('wp-slick-slider-and-image-carousel/wp-slick-image-slider.php', apply_filters('active_plugins', get_option('active_plugins'))) ) 
		{
			// Add a Integrations section
			add_settings_section(
				TZWRS_OPTION_NAME . '_integrations',
				__( 'WRS Integrations settings', 'weekly-radio-schedule' ),
				array( $this, TZWRS_OPTION_NAME . '_integrations_cb' ),
				$this->plugin_name
			);
		}
	
		// Add a WRS SEO section
		add_settings_section(
			TZWRS_OPTION_NAME . '_wrsseo',
			__( 'WRS SEO', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_seo_section_cb' ),
			$this->plugin_name
		);
	
		// Add a WRS logo section
		add_settings_section(
			TZWRS_OPTION_NAME . '_wrsimages',
			__( 'WRS Images', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_logo_section_cb' ),
			$this->plugin_name
		);
	
		// Add a Notifications section
		add_settings_section(
			TZWRS_OPTION_NAME . '_notifications',
			__( 'WRS Notifications', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_notifications_cb' ),
			$this->plugin_name
		);
	
		// Add a contact form 7 integration section
		if(in_array('contact-form-7/wp-contact-form-7.php', apply_filters('active_plugins', get_option('active_plugins')))){
			$docs_cf7_forms = esc_url( 'https://wrs.tuzongo.com/docs/contact-form-7-integration/' );
			$docs_cf7_forms_link = sprintf( '<span class="label_span">' . __( 'Here you can set the integrations settings for Contact Form 7.<br />*Set <code>demo_mode: on</code> in ‘Additional Settings’.', 'weekly-radio-schedule' ) . ' <a href="%s">(see WRS Docs)</a></span>', $docs_cf7_forms );

			add_settings_field(
				TZWRS_OPTION_NAME . '_wrs_cf7_join_id',
				__( 'Contact Form 7 integration' . $docs_cf7_forms_link, 'weekly-radio-schedule' ),
				array( $this, TZWRS_OPTION_NAME . '_wrs_cf7_join_id_cb' ),
				$this->plugin_name,
				TZWRS_OPTION_NAME . '_integrations',
				array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_cf7_join_id' )
			);
		}

		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_send_follower_notifications',
			__( 'Enable Team Member follow notifications?', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_send_follower_notifications_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_notifications',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_send_follower_notifications' )
		);

		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_send_post_notifications',
			__( 'Enable Team Member post notifications?', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_send_post_notifications_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_notifications',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_send_post_notifications' )
		);

		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_send_slot_notifications',
			__( 'Enable Team Member slot notifications?', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_send_slot_notifications_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_notifications',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_send_slot_notifications' )
		);

		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_max_desc_chars',
			__( 'Max description characters', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_max_desc_chars_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_general',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_max_desc_chars' )
		);

		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_max_name_chars',
			__( 'Max name characters', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_max_name_chars_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_general',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_max_name_chars' )
		);

		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_max_show_name_chars',
			__( 'Max show name characters', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_max_show_name_chars_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_general',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' )
		);

		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_default_avatar_size',
			__( 'Default avatar size', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_default_avatar_size_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_general',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_default_avatar_size' )
		);

		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_need_approval',
			__( 'Do DJs / Presenters need approval to take slots?', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_need_approval_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_general',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_need_approval' )
		);
		
		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_schedule_page_id',
			__( 'Schedule Page', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_schedule_page_id_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_wrspages',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_schedule_page_id' )
		);
		
		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_color_scheme',
			__( 'Colour scheme', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_color_scheme_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_general',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_color_scheme' )
		);
		
		$tzwrs_logo = sprintf( '<span class="label_span">' . __( 'Select a square image to be displayed within the fly-out info panel when the current time slot is empty.', 'weekly-radio-schedule' ) . '</span>' );
		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_logo',
			__( 'Square Logo', 'weekly-radio-schedule' ) . $tzwrs_logo,
			array( $this, TZWRS_OPTION_NAME . '_wrs_logo_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_wrsimages',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_logo' )
		);
		
		$seo_address = sprintf( '<span class="label_span">' . __( 'Here you can set the location of the station. Can be postal address or something less precise.', 'weekly-radio-schedule' ) . '</span>' );
		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_address',
			__( 'Station Address' . $seo_address, 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_address_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_wrsseo',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_address' )
		);
		
		$seo_audio_address = sprintf( '<span class="label_span">' . __( 'Here you can set the URL of the page that provides audio to listeners.', 'weekly-radio-schedule' ) . '</span>' );
		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_audio_address',
			__( 'Audio Page URL' . $seo_audio_address, 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_audio_address_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_wrsseo',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_audio_address' )
		);
		
		add_settings_field(
			TZWRS_OPTION_NAME . '_wrs_playnowtext',
			__( '‘Play Now’ text', 'weekly-radio-schedule' ),
			array( $this, TZWRS_OPTION_NAME . '_wrs_playnowtext_cb' ),
			$this->plugin_name,
			TZWRS_OPTION_NAME . '_general',
			array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_playnowtext' )
		);

		if ( in_array('peepso-core/peepso.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {
			add_settings_field(
				TZWRS_OPTION_NAME . '_wrs_fields_enable_render',
				__( 'Peepso Show name field', 'weekly-radio-schedule' ),
				array( $this, TZWRS_OPTION_NAME . '_wrs_fields_enable_render_cb' ),
				$this->plugin_name,
				TZWRS_OPTION_NAME . '_integrations',
				array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_fields_enable_render' )
			);
		}

		function tzwrs_wrs_home_enable_cb() {
			$parentplugin = new Tz_Weekly_Radio_Schedule();
			add_settings_field(
				TZWRS_OPTION_NAME . '_wrs_home_enable',
				__( 'Enable WRS Home page', 'tzwrshome' ),
				array( $this, TZWRS_OPTION_NAME . '_wrs_home_enable_render_cb' ),
				'tz-weekly-radio-schedule',
				TZWRS_OPTION_NAME . '_wrspages',
				array( 'label_for' => TZWRS_OPTION_NAME . '_wrs_home_enable' )
			);
		}
	
		add_filter('admin_body_class', array($this,'tzwrs_class_to_body_admin') );

		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_playnowtext' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_logo' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_address' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_audio_address' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_default_images', TZWRS_OPTION_NAME . '_get_default_images' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_send_follower_notifications' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_send_post_notifications' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_send_slot_notifications' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_send_dj_weekly_notifications' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_text_color' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_accent_color' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_secondary_color' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_border_color' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_background_color' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_header_footer_background_color' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_color_scheme' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_schedule_page_id' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_need_approval' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_max_name_chars' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_show_name_field_id' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_max_desc_chars' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_default_avatar_size' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_cf7_join_id' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_cf7_join_modal_id' );
		register_setting( $this->plugin_name, TZWRS_OPTION_NAME . '_wrs_cf7_message_id' );
		
		add_option(TZWRS_OPTION_NAME . '_wrs_max_name_chars', 15);
		add_option(TZWRS_OPTION_NAME . '_wrs_max_show_name_chars', 32);
		add_option(TZWRS_OPTION_NAME . '_wrs_max_desc_chars', 184);
		add_option(TZWRS_OPTION_NAME . '_wrs_default_avatar_size', 64);
		add_option(TZWRS_OPTION_NAME . '_wrs_need_approval', 0);
		
		add_option(TZWRS_OPTION_NAME . '_wrs_color_scheme', '#000000,#45a6ad,#c6c6c6,#b1e2dd,#d1e4dd,#FFFFFF');
		add_option(TZWRS_OPTION_NAME . '_wrs_text_color', '#000000');
		add_option(TZWRS_OPTION_NAME . '_wrs_accent_color', '#45a6ad');
		add_option(TZWRS_OPTION_NAME . '_wrs_secondary_color', '#c6c6c6');
		add_option(TZWRS_OPTION_NAME . '_wrs_border_color', '#b1e2dd');
		add_option(TZWRS_OPTION_NAME . '_wrs_background_color', '#d1e4dd');
		add_option(TZWRS_OPTION_NAME . '_wrs_header_footer_background_color', '#FFFFFF');
		update_option(TZWRS_OPTION_NAME . '_wrs_color_scheme', get_option( TZWRS_OPTION_NAME . '_wrs_text_color' ) . ',' . get_option( TZWRS_OPTION_NAME . '_wrs_accent_color' ) . ',' . get_option( TZWRS_OPTION_NAME . '_wrs_secondary_color' ) . ',' . get_option( TZWRS_OPTION_NAME . '_wrs_border_color' ) . ',' . get_option( TZWRS_OPTION_NAME . '_wrs_background_color' ) . ',' . get_option( TZWRS_OPTION_NAME . '_wrs_header_footer_background_color' ));
		
	}

	/**
	 * Render the contact form 7 ids input for this plugin 
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_cf7_join_id_cb() {
		$wrs_cf7_join_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_cf7_join_id' ) );
		$wrs_cf7_join_modal_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_cf7_join_modal_id' ) );
		$wrs_cf7_message_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) );
		
		$allowed = array( 
			'select' => array(
				'id' => array(), 
				'name' => array(), 
				'data-form-type' => array(), 
				'option' => array(
					'id' => array(), 
					'value' => array()
				)
			),
			'option' => array(
				'selected' => array(), 
				'value' => array()
			)
		);
		$cf7posts = get_posts(
			array(
				'post_type'  => 'wpcf7_contact_form',
				'numberposts' => -1
			)
		);
		if( ! $cf7posts ) return;

		$joinout = '<select id="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_cf7_join_id' ) . '" name="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_cf7_join_id' ) . '"><option>Select a form</option>';
		foreach( $cf7posts as $p )
		{
			$selected = '';
			if ( $p->ID == $wrs_cf7_join_id ) { $selected = 'selected="selected"'; }
			$joinout .= '<option value="' . esc_attr($p->ID) . '" ' . $selected . '>' . esc_html( $p->post_title ) . '</option>';
		}
		$joinout .= '</select>';
		?>
		<fieldset class="wrs_fieldset cf7_integration">
			<legend class="screen-reader-text">
				<span><?php esc_html_e("Join Team Contact Form","weekly-radio-schedule") ?></span>
			</legend>
				<?php echo wp_kses( $joinout, $allowed ); ?>
				<?php // echo $joinout; ?>
			<label>
				<?php esc_html_e( 'Join Team Contact Form', 'weekly-radio-schedule' ); ?>
			</label><br />
			<?php

		$joinmodalout = '<select id="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_cf7_join_modal_id' ) . '" name="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_cf7_join_modal_id' ) . '"><option>Select a form</option>';
		foreach( $cf7posts as $p )
		{
			$selected = '';
			if ( $p->ID == $wrs_cf7_join_modal_id ) { $selected = 'selected="selected"'; }
			$joinmodalout .= '<option value="' . intval($p->ID) . '" ' . $selected .'>' . esc_html( $p->post_title ) . '</option>';
		}
		$joinmodalout .= '</select>';
		?>
			<legend class="screen-reader-text">
				<span><?php esc_html_e("Join Team Modal Contact Form*","weekly-radio-schedule") ?></span>
			</legend>
				<?php echo wp_kses( $joinmodalout, $allowed ); ?>
			<label>
				<?php esc_html_e( 'Join Team Modal Contact Form*', 'weekly-radio-schedule' ); ?>
			</label><br />
			<?php 

		$messageout = '<select id="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) . '" name="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) . '"><option>Select a form</option>';
		foreach( $cf7posts as $p )
		{
			$selected = '';
			if ( $p->ID == $wrs_cf7_message_id ) { $selected = 'selected="selected"'; }
			$messageout .= '<option value="' . intval($p->ID) . '" ' . $selected .'>' . esc_html( $p->post_title ) . '</option>';
		}
		$messageout .= '</select>';
		?>
			<legend class="screen-reader-text">
				<span><?php esc_html_e("Team message Contact Form*","weekly-radio-schedule") ?></span>
			</legend>
				<?php echo wp_kses( $messageout, $allowed ); ?>
			<label>
				<?php esc_html_e( 'Team message Contact Form*', 'weekly-radio-schedule' ); ?>
			</label>
		</fieldset>
		<?php 
	}


	function tzwrs_get_default_images() {
		$wrs_images = array(
			'square' => TZWRS_DIRECTORY_URL . '/images/wrs-square.svg'
		);
		return $wrs_images;
	}

	/**
	 * Render the Peepso Profile fields inputs for this plugin 
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_fields_enable_render_cb() {
		$wrs_show_name_field_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_show_name_field_id' ) );
		$allowed = array( 
			'select' => array(
				'id' => array(), 
				'name' => array(), 
				'data-form-type' => array(), 
				'option' => array(
					'id' => array(), 
					'value' => array()
				)
			),
			'option' => array(
				'selected' => array(), 
				'value' => array()
			)
		);
		$peepsoUserProfilePosts = get_posts(
			array(
				'post_type'  => 'peepso_user_field',
				'numberposts' => -1
			)
		);
		if( ! $peepsoUserProfilePosts ) return;
		?>
		<fieldset class="wrs_fieldset peepso_fields">
			<legend class="screen-reader-text">
				<span><?php esc_html_e("Peepso Integration", "weekly-radio-schedule") ?></span>
			</legend>
			<?php
		
			$show_nameout = '<select id="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_show_name_field_id' ) . '" name="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_show_name_field_id' ) . '"><option>' . esc_html_e("Select a field", "weekly-radio-schedule") . '</option>';
			foreach( $peepsoUserProfilePosts as $p )
			{
				$selected = '';
				if ( $p->ID == $wrs_show_name_field_id ) { $selected = 'selected="selected"'; }
				$show_nameout .= '<option value="' . intval($p->ID) . '" ' . $selected .'>' . esc_html( $p->post_title ) . '</option>';
			}
			$show_nameout .= '</select>';
			echo wp_kses( $show_nameout, $allowed ); ?>
			<label for="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_show_name_field_id' ); ?>"><?php esc_html_e("Set Peepso Show Name field.", "weekly-radio-schedule") ?></label><br />
				
		</fieldset>
		<?php 
	}

	/**
	 * Render the ‘Play Now’ text input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_playnowtext_cb() {
		$wrs_playnowtext = get_option( TZWRS_OPTION_NAME . '_wrs_playnowtext' ) ? strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_playnowtext' ) ) : esc_html__( 'You could be playing this slot', 'weekly-radio-schedule' );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("Set ‘Play Now’ text","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input type="text" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_playnowtext' ); ?>"  name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_playnowtext' ); ?>" class="form-control regular-text" value="<?php echo esc_attr( $wrs_playnowtext ); ?>" /> <label for="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_playnowtext' ); ?>"><?php esc_html_e("Set ‘Play Now’ text displayed in the fly-out info panel to Team members during empty slots.", "weekly-radio-schedule") ?></label>
				</label>
			</fieldset>
			<?php 
	}

	/**
	 * Render the default square logo input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_logo_cb() {

		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("Set Square Logo","weekly-radio-schedule") ?></span>
				</legend>
				<?php 
				$image_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_logo' ) );
				if( $image = wp_get_attachment_image_src( $image_id ) ) {
				 
					_e( '<a href="#" class="misha-upl"><img height="200" src="' . esc_url( $image[0] ) . '" /></a>
						  <a href="#" class="misha-rmv button-secondary">' . __("Remove image","weekly-radio-schedule") . '</a>
							<input id="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_logo' ) . '"  name="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_logo' ) . '" class="form-control" type="hidden" value="' . intval( $image_id ) . '" />');
				} else {
					$def_logo = $this->tzwrs_get_default_images()['square'];
					echo '<img class="def_image logo" height="200" src="' . esc_url( $def_logo ) . '" /><a href="#" class="misha-upl button-secondary">' . __("Upload image","weekly-radio-schedule") . '</a>
							<input id="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_logo' ) . '"  name="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_logo' ) . '" class="form-control" type="hidden" value="' . intval( $image_id ) . '" />
						  <a href="#" class="misha-rmv button-secondary" style="display:none">' . __("Remove image","weekly-radio-schedule") . '</a>';
				} ?>

				<label>
					<?php // esc_html_e( "Displayed in Audio Player and on open slots", 'weekly-radio-schedule' ); ?>
				</label>
			</fieldset>
			<?php 
	}
	
	/**
	 * Render the address input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_address_cb() {
		$wrs_address = strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) );
		?>
		<fieldset class="wrs_fieldset <?php echo TZWRS_OPTION_NAME; ?>_wrs_address">
			<legend class="screen-reader-text">
				<span><?php esc_html_e("Set Address","weekly-radio-schedule") ?></span>
			</legend>
				<?php 
				echo '<textarea type="textarea" rows="6" cols="50" id="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_address' ) . '" name="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_address' ) . '" value="' . esc_textarea( $wrs_address ) . '">' . esc_attr(get_option( TZWRS_OPTION_NAME . '_wrs_address' )) . '</textarea>';
				?>
			<label style="position: relative;">
			</label>
		</fieldset>
		<?php 
	}
	
	/**
	 * Render the audio address input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_audio_address_cb() {
		$wrs_audio_address = esc_url( get_option( TZWRS_OPTION_NAME . '_wrs_audio_address' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("Set Audio Address","weekly-radio-schedule") ?></span>
				</legend>

				<label>
					<?php
					echo '<input type="url" name="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_audio_address' ) . '" class="form-control regular-text" id="' . esc_attr( TZWRS_OPTION_NAME . '_wrs_audio_address' ) . '" value="' . esc_url( $wrs_audio_address ) . '">';
					?>
				</label>
			</fieldset>
			<?php 
	}
	
	/**
	 * Render the current colour scheme select for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_color_scheme_cb() {
		$wrs_text_color = sanitize_hex_color( get_option( TZWRS_OPTION_NAME . '_wrs_text_color' ) );
		$wrs_accent_color = sanitize_hex_color( get_option( TZWRS_OPTION_NAME . '_wrs_accent_color' ) );
		$wrs_secondary_color = sanitize_hex_color( get_option( TZWRS_OPTION_NAME . '_wrs_secondary_color' ) );
		$wrs_border_color = sanitize_hex_color( get_option( TZWRS_OPTION_NAME . '_wrs_border_color' ) );
		$wrs_background_color = sanitize_hex_color( get_option( TZWRS_OPTION_NAME . '_wrs_background_color' ) );
		$wrs_header_footer_background_color = sanitize_hex_color( get_option( TZWRS_OPTION_NAME . '_wrs_header_footer_background_color' ) );

		$scheme = wp_strip_all_tags( get_option( TZWRS_OPTION_NAME . '_wrs_color_scheme' ) );
		$scheme_array = explode( ',', $scheme );
		?>
		<fieldset class="wrs_fieldset">
			<legend class="screen-reader-text">
				<span><?php esc_html_e("Select colours for Weekly Radio Schedule pages, widgets and panels that match your theme.", "weekly-radio-schedule") ?></span>
			</legend>
			<span class="colour_head"><?php esc_html_e( "Select colours for Weekly Radio Schedule pages, widgets and panels that match your theme.", 'weekly-radio-schedule' ); ?></span>
			<div class="wrs_options_colours">
				<div class="wrs_options_colour">
					<span>Text</span>
					<input id="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_text_color" name="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_text_color" type="text" value="<?php echo esc_attr( $wrs_text_color ); ?>" class="text-color-field"  />
				</div>
				<div class="wrs_options_colour">
					<span>Accent</span>
					<input id="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_accent_color" name="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_accent_color" type="text" value="<?php echo esc_attr( $wrs_accent_color ); ?>" class="accent-color-field" />
				</div>
				<div class="wrs_options_colour">
					<span>Secondary</span>
					<input id="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_secondary_color" name="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_secondary_color" type="text" value="<?php echo esc_attr( $wrs_secondary_color ); ?>" class="secondary-color-field" />
				</div>
				<div class="wrs_options_colour">
					<span>Border</span>
					<input id="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_border_color" name="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_border_color" type="text" value="<?php echo esc_attr( $wrs_border_color ); ?>" class="border-color-field" />
				</div>
				<div class="wrs_options_colour">
					<span>Background</span>
					<input id="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_background_color" name="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_background_color" type="text" value="<?php echo esc_attr( $wrs_background_color ); ?>" class="background-color-field" />
				</div>
				<div class="wrs_options_colour">
					<span>Header Footer Background</span>
					<input id="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_header_footer_background_color" name="<?php echo esc_attr( TZWRS_OPTION_NAME ); ?>_wrs_header_footer_background_color" type="text" value="<?php echo esc_attr( $wrs_header_footer_background_color ); ?>" class="header-footer-background-color-field" />
				</div>
				<div class="colour_scheme_reset_wrap">
					<div class="wrs_options_colour reset">
					<input id="wrs_wp_picker_2017" name="wrs_wp_picker_2017" type="button" class="button button-small wp-picker-reset" value="<?php echo esc_html__( 'Twenty Seventeen', 'weekly-radio-schedule' ) ?>" aria-label="Twenty Seventeen colours">
						<table class="color-palette wrs-scheme">
						<tbody>
							<tr>
								<td style="background-color: #000000">&nbsp;</td>
								<td style="background-color: #767676">&nbsp;</td>
								<td style="background-color: #a8a8a8">&nbsp;</td>
								<td style="background-color: #dcd7ca">&nbsp;</td>
								<td style="background-color: #f5efe0">&nbsp;</td>
								<td style="background-color: #ffffff">&nbsp;</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="wrs_options_colour reset">
					<input id="wrs_wp_picker_2019" name="wrs_wp_picker_2019" type="button" class="button button-small wp-picker-reset" value="<?php echo esc_html__( 'Twenty Nineteen', 'weekly-radio-schedule' ) ?>" aria-label="Twenty Nineteen colours">
						<table class="color-palette wrs-scheme">
						<tbody>
							<tr>
								<td style="background-color: #000000">&nbsp;</td>
								<td style="background-color: #0073aa">&nbsp;</td>
								<td style="background-color: #6d6d6d">&nbsp;</td>
								<td style="background-color: #dcd7ca">&nbsp;</td>
								<td style="background-color: #f5efe0">&nbsp;</td>
								<td style="background-color: #ffffff">&nbsp;</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="wrs_options_colour reset">
					<input id="wrs_wp_picker_reset" name="wrs_wp_picker_reset" type="button" class="button button-small wp-picker-reset" value="<?php echo esc_html__( 'Twenty Twenty', 'weekly-radio-schedule' ) ?>" aria-label="Twenty Twenty colours">
						<table class="color-palette wrs-scheme">
						<tbody>
							<tr>
								<td style="background-color: #000000">&nbsp;</td>
								<td style="background-color: #CD2653">&nbsp;</td>
								<td style="background-color: #C4C4C4">&nbsp;</td>
								<td style="background-color: #dcd7ca">&nbsp;</td>
								<td style="background-color: #f5efe0">&nbsp;</td>
								<td style="background-color: #ffffff">&nbsp;</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="wrs_options_colour reset">
					<input id="wrs_wp_picker_2021" name="wrs_wp_picker_2021" type="button" class="button button-small wp-picker-reset" value="<?php echo esc_html__( 'Twenty Twentyone', 'weekly-radio-schedule' ) ?>" aria-label="Twenty Twentyone colours">
						<table class="color-palette wrs-scheme">
						<tbody>
							<tr>
								<td style="background-color: #000000">&nbsp;</td>
								<td style="background-color: #45a6ad">&nbsp;</td>
								<td style="background-color: #c6c6c6">&nbsp;</td>
								<td style="background-color: #b1e2dd">&nbsp;</td>
								<td style="background-color: #d1e4dd">&nbsp;</td>
								<td style="background-color: #ffffff">&nbsp;</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="wrs_options_colour reset">
					<input id="wrs_wp_picker_rs" name="wrs_wp_picker_rs" type="button" class="button button-small wp-picker-reset" value="<?php echo esc_html__( 'RS', 'weekly-radio-schedule' ) ?>" aria-label="RS colours">
						<table class="color-palette wrs-scheme">
						<tbody>
							<tr>
								<td style="background-color: #d3d3d3">&nbsp;</td>
								<td style="background-color: #edd83b">&nbsp;</td>
								<td style="background-color: #5b5a00">&nbsp;</td>
								<td style="background-color: #3f4233">&nbsp;</td>
								<td style="background-color: #6a6d64">&nbsp;</td>
								<td style="background-color: #000000">&nbsp;</td>
							</tr>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</fieldset>

		<?php 
	}

	/**
	 * Render the Schedule Page select for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_schedule_page_id_cb() {
		$wrs_schedule_page_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_schedule_page_id' ) );
		$schedule_dropdown_args = array(
			'selected'         => $wrs_schedule_page_id,
			'name'				=> TZWRS_OPTION_NAME . '_wrs_schedule_page_id',
			'echo'				=> 0,
		);
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("Weekly Radio Schedule's Schedule Page","weekly-radio-schedule") ?></span>
				</legend>
				<?php echo wp_dropdown_pages($schedule_dropdown_args); ?>
				<span class="wrs_schedule_page_view">
					<a href="<?php echo esc_url( get_page_link( $wrs_schedule_page_id ) ); ?>"><?php esc_html_e( "View", "weekly-radio-schedule" ) ?></a>
				</span>
				<span class="wrs_schedule_page_code">
				<pre><code>[schedule_page]</code></pre></span><?php 
				$post = get_post( $wrs_schedule_page_id ); 
				$content = $post->post_content;
				if ( has_shortcode( $content, 'schedule_page' ) ) {
					esc_html_e("Shortcode added to content of Schedule Page","weekly-radio-schedule"); ?> <span class="dashicons dashicons-yes-alt"></span></span> <?php 
				}
				else
				{
					esc_html_e("Add this shortcode to the content of that page","weekly-radio-schedule");
				} ?>
			</fieldset>
			<?php 
	}

	/**
	 * Render the name length input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_max_name_chars_cb() {
		$wrs_max_name_chars = intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("Used to trim long names, how many characters before '...'?","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input min="0" max="99" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ); ?>"  name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ); ?>" class="form-control" type="number" value="<?php echo intval( $wrs_max_name_chars ); ?>" />
					<?php esc_html_e( "Used to trim long names, how many characters before '...'?", 'weekly-radio-schedule' ); ?>
				</label>
			</fieldset>
			<?php 
	}
	
	/**
	 * Render the show name length input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_max_show_name_chars_cb() {
		$wrs_max_show_name_chars = intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("Used to trim long show names, how many characters before '...'?","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input min="0" max="99" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ); ?>"  name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ); ?>" class="form-control" type="number" value="<?php echo intval( $wrs_max_show_name_chars ); ?>" />
					<?php esc_html_e( "Used to trim long show names, how many characters before '...'?", 'weekly-radio-schedule' ); ?>
				</label>
			</fieldset>
			<?php 
	}
	
	/**
	 * Render the avatar size input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_default_avatar_size_cb() {
		$wrs_default_avatar_size = intval( get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("Used in various Team listings.","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input min="0" max="999" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_default_avatar_size' ); ?>"  name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_default_avatar_size' ); ?>" class="form-control" type="number" value="<?php echo intval( $wrs_default_avatar_size ); ?>" />
					<?php esc_html_e( "Used in various Team listings.", 'weekly-radio-schedule' ); ?>
				</label>
			</fieldset>
			<?php 
	}
	
	/**
	 * Render the description length input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_max_desc_chars_cb() {
		$wrs_max_desc_chars = intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("Used to trim long show descriptions, how many characters before '...'?","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input min="0" max="999" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ); ?>"  name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ); ?>" class="form-control" type="number" value="<?php echo intval( $wrs_max_desc_chars ); ?>" /> <label for="<?php echo TZWRS_OPTION_NAME . '_wrs_max_desc_chars'; ?>"><?php esc_html_e("Used to trim long show descriptions, how many characters before '...'?", "weekly-radio-schedule") ?></label>
				</label>
			</fieldset>
			<?php 
	}

	/**
	 * Render the need approval input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_need_approval_cb() {
		$wrs_need_approval = intval( get_option( TZWRS_OPTION_NAME . '_wrs_need_approval' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("If selected, names in slots taken are not displayed publicly until approved.","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_need_approval' ); ?>" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_need_approval' ); ?>" value="1" <?php checked( $wrs_need_approval, '1' ); ?>>
					<?php esc_html_e( 'If selected, names in slots taken are not displayed publicly until approved.' ); ?>
				</label>
			</fieldset>
			<?php 
	}

	/**
	 * Render the checkbox input field for Enable weekly DJ shows notifications? option
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_send_follower_notifications_cb() {
		$wrs_send_follower_notifications = intval( get_option( TZWRS_OPTION_NAME . '_wrs_send_follower_notifications' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("If selected, Team members can recieve an email when someone follows them.","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_send_follower_notifications' ); ?>" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_send_follower_notifications' ); ?>" value="1" <?php checked( $wrs_send_follower_notifications, '1' ); ?>>
					<?php esc_html_e( 'If selected, Team members can recieve an email when someone follows them.', 'weekly-radio-schedule' ); ?>
				</label>
			</fieldset>
		<?php
	}

	/**
	 * Render the checkbox input field for Enable weekly DJ shows notifications? option
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_send_post_notifications_cb() {
		$wrs_send_post_notifications = intval( get_option( TZWRS_OPTION_NAME . '_wrs_send_post_notifications' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("If selected, users can recieve an email when someone they follow publishes a post.","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_send_post_notifications' ); ?>" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_send_post_notifications' ); ?>" value="1" <?php checked( $wrs_send_post_notifications, '1' ); ?>>
					<?php esc_html_e( 'If selected, users can recieve an email when someone they follow publishes a post.', 'weekly-radio-schedule' ); ?>
				</label>
			</fieldset>
		<?php
	}

	/**
	 * Render the checkbox input field for Enable weekly DJ shows notifications? option
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_send_slot_notifications_cb() {
		$wrs_send_slot_notifications = intval( get_option( TZWRS_OPTION_NAME . '_wrs_send_slot_notifications' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("If selected, users can recieve an email when someone they follow takes a vacant slot.","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_send_slot_notifications' ); ?>" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_send_slot_notifications' ); ?>" value="1" <?php checked( $wrs_send_slot_notifications, '1' ); ?>>
					<?php esc_html_e( 'If selected, users can recieve an email when someone they follow takes a vacant slot.', 'weekly-radio-schedule' ); ?>
				</label>
			</fieldset>
		<?php
	}

	/**
	 * Render the checkbox input field for Enable weekly DJ shows notifications? option
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_send_dj_weekly_notifications_cb() {
		$wrs_send_dj_weekly_notifications = intval( get_option( TZWRS_OPTION_NAME . '_wrs_send_dj_weekly_notifications' ) );
		?>
			<fieldset class="wrs_fieldset">
				<legend class="screen-reader-text">
					<span><?php esc_html_e("If selected, users can recieve an email each week with the scheduled shows for the DJ(s) they follow.","weekly-radio-schedule") ?></span>
				</legend>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_send_dj_weekly_notifications' ); ?>" id="<?php echo esc_attr( TZWRS_OPTION_NAME . '_wrs_send_dj_weekly_notifications' ); ?>" value="1" <?php checked( $wrs_send_dj_weekly_notifications, '1' ); ?>>
					<?php esc_html_e( 'If selected, users can recieve an email each week with the scheduled shows for the DJ(s) they follow.', 'weekly-radio-schedule' ); ?>
				</label>
			</fieldset>
		<?php
	}

	/**
	 * Render the text for the integrations section
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_integrations_cb() {
	
		// Add a Integrationsl section
		echo '<p>' . esc_html__( 'Here you can set the integrations settings for Weekly Radio Schedule.', 'weekly-radio-schedule' ) . '</p>';
	
	}
	
	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_general_cb() {
	
		// Add a General section
		echo '<p>' . esc_html__( 'Here you can set the general settings for Weekly Radio Schedule.', 'weekly-radio-schedule' ) . '</p>';
	
	}
	
	/**
	 * Render the demo data section
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_demo_cb() {
	
		// Add a Demo section
		echo '<p id="demo_data">' . esc_html__( 'Here you can opt for Weekly Radio Schedule to create users and schedules slots that will demontrate features of the plugin.', 'weekly-radio-schedule' ) . '</p>';
	
	}
	
	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrspages_cb() {
	
		// Add a General section
		$menus_url = admin_url( 'nav-menus.php' );
		echo sprintf('<p>' . esc_html__( 'Weekly Radio Schedule creates a schedule page. Here you can opt to use it. A new optional WRS Main Menu is also created and can be selected ', 'weekly-radio-schedule' ) . '<a href="%s">here.</a></p>', $menus_url);
	}
	
	/**
	 * Render the text for the notifications section
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_notifications_cb() {
	
		// Add a Notifications section
		echo '<p>' . esc_html__( 'Weekly Radio Schedule can send notifications on certain events. These rely on a working website email function. Please change the notifications accordingly.', 'weekly-radio-schedule' ) . '</p>';
	
	}
	
	/**
	 * Render the text for the seo section
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_seo_section_cb() {
	
		echo '<p>' . esc_html__( 'Here you can set values used for search engine optimisation.', 'weekly-radio-schedule' ) . '</p>';
	
	}
	
	/**
	 * Render the text for the logo section
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_wrs_logo_section_cb() {
	
		// Add a Notifications section
		echo '<p>' . esc_html__( 'Here you can upload images that are displayed in various places.', 'weekly-radio-schedule' ) . '</p>';
	
	}
	
	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function tzwrs_display_options_page() {
		include_once 'partials/tz-weekly-radio-schedule-admin-display.php';
	}

	// add styles to head
	function tzwrs_head_style() {
		$stylee = ':root{--seven-opac: .71;--text-color:' . sanitize_hex_color(get_option(TZWRS_OPTION_NAME . '_wrs_text_color')) . ';--accent-color:' . sanitize_hex_color(get_option(TZWRS_OPTION_NAME . '_wrs_accent_color')) . ';--secondary-color:' . sanitize_hex_color(get_option(TZWRS_OPTION_NAME . '_wrs_secondary_color')) . ';--border-color:' . sanitize_hex_color(get_option(TZWRS_OPTION_NAME . '_wrs_border_color')) . ';--background-color:' . sanitize_hex_color(get_option(TZWRS_OPTION_NAME . '_wrs_background_color')) . ';--header-footer-background-color:' . sanitize_hex_color(get_option(TZWRS_OPTION_NAME . '_wrs_header_footer_background_color')) . ';}';

		echo '<style type="text/css">' . esc_html( $stylee ) . '</style>';
	}

	/**
	 * @usage Add user role class and user id to back-end body tag
	 * @param $classes
	 * @return $classes
	 */
	function tzwrs_class_to_body_admin($classes) {
		global $current_user;
		$user_role = array_shift($current_user->roles);
		/* Adds the user id to the admin body class array */
		$user_ID = $current_user->ID;
		$classes .= strip_tags($user_role) . ' user-id-' . intval($user_ID) . ' wrs ' . esc_html( wp_get_theme()->get( 'TextDomain' ) );
		return $classes;
	}

	// ============ Weekly Radio Schedule ================
	// Add user profile fields for team members
	// ==============================================
	/**
	 * @usage Add user profile fields for team members
	 * @param $user
	 */
	function userMetaForm(WP_User $user) {
		$wrs_author_levels = TZWRS_AUTHOR_LEVELS;
		$user_meta = get_userdata($user->ID);
		?>
		<table class="form-table">
			<?php
		if ( isset( $user_meta->roles[0] ) ) {
				if ( in_array( $user_roles = $user_meta->roles[0], $wrs_author_levels) )
				{ //teamster
					$wrs_show_year = esc_attr(get_user_meta($user->ID, 'user_showyear', true));
				?>
				<tr>
					<th><label for="user_birthday"><?php echo esc_html__( 'Birthday', 'weekly-radio-schedule' ) ?></label></th>
					<td>
						<input
							type="date"
							value="<?php echo esc_attr(get_user_meta($user->ID, 'user_birthday', true)); ?>"
							name="user_birthday"
							id="user_birthday"
						>
					<legend class="screen-reader-text">
						<span><?php echo esc_html__("Display year?","weekly-radio-schedule") ?></span>
					</legend>
					<label>
						<?php echo esc_html__( 'Display year?', 'weekly-radio-schedule' ); ?>
						<input 
							type="checkbox" 
							name="user_showyear" 
							id="user_showyear" 
							value="1" <?php checked( $wrs_show_year, '1' ); ?>
						>
					</label>

						<span class="description"></span>
					</td>
				</tr>
				<tr>
					<th><label for="user_show_name"><?php echo esc_html__( 'Show Name', 'weekly-radio-schedule' ) ?></label></th>
					<td>
						<input
							type="text"
							value="<?php echo esc_attr(get_user_meta($user->ID, 'user_show_name', true)); ?>"
							name="user_show_name"
							id="user_show_name"
						>
						<span class="description"><?php echo esc_html__( 'What is the name of your show?', 'weekly-radio-schedule' ) ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="user_pronoun"><?php echo esc_html__( 'Pronoun', 'weekly-radio-schedule' ) ?></label></th>
					<td>
					<?php 
						//get dropdown saved value
						$selected = get_the_author_meta( 'user_pronoun', $user->ID ); //there was an extra ) here that was not needed 
					?>
						<select name="user_pronoun" id="user_pronoun">
							<option value="" <?php echo ($selected == "")?  'selected="selected"' : '' ?>></option>
							<option value="she" <?php echo ($selected == "she")?  'selected="selected"' : '' ?>><?php echo esc_html__( 'she/her/hers', 'weekly-radio-schedule' ); ?></option>
							<option value="he" <?php echo ($selected == "he")?  'selected="selected"' : '' ?>><?php echo esc_html__( 'he/him/his', 'weekly-radio-schedule' ); ?></option>
							<option value="they" <?php echo ($selected == "they")?  'selected="selected"' : '' ?>><?php echo esc_html__( 'they/them/their', 'weekly-radio-schedule' ); ?></option>
						</select>

						<span class="description"><?php echo esc_html__( 'How are you referred to?', 'weekly-radio-schedule' ) ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="user_twitter"><?php echo esc_html__( 'Twitter', 'weekly-radio-schedule' ) ?></label></th>
					<td>
						<input
							type="text"
							value="<?php echo esc_attr(get_user_meta($user->ID, 'user_twitter', true)); ?>"
							name="user_twitter"
							id="user_twitter"
						>
						<span class="description"><?php echo esc_html__( 'Twitter handle i.e @Tuzongo', 'weekly-radio-schedule' ) ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="user_fbprofile"><?php echo esc_html__( 'Facebook Profile', 'weekly-radio-schedule' ) ?></label></th>
					<td>
						<input
							type="text"
							value="<?php echo esc_attr(get_user_meta($user->ID, 'user_fbprofile', true)); ?>"
							name="user_fbprofile"
							id="user_fbprofile"
						>
						<span class="description"><?php echo esc_html__( 'Facebook Profile i.e https://www.facebook.com/tu.zongo.50', 'weekly-radio-schedule' ) ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="user_fbpage"><?php echo esc_html__( 'Facebook Page', 'weekly-radio-schedule' ) ?></label></th>
					<td>
						<input
							type="text"
							value="<?php echo esc_attr(get_user_meta($user->ID, 'user_fbpage', true)); ?>"
							name="user_fbpage"
							id="user_fbpage"
						>
						<span class="description"><?php echo esc_html__( 'Facebook Page i.e https://www.facebook.com/Tuzongo/', 'weekly-radio-schedule' ) ?></span>
					</td>
				</tr>
				<tr>
						<th><label for="user_instagram"><?php echo esc_html__( 'Instagram', 'weekly-radio-schedule' ) ?></label></th>
						<td>
							<input
								type="text"
								value="<?php echo esc_attr(get_user_meta($user->ID, 'user_instagram', true)); ?>"
								name="user_instagram"
								id="user_instagram"
							>
							<span class="description"><?php echo esc_html__( 'Instagram handle i.e @tuzongo', 'weekly-radio-schedule' ) ?></span>
						</td>
					</tr>
				<tr>
						<th><label for="user_mixcloud"><?php echo esc_html__( 'MixCloud', 'weekly-radio-schedule' ) ?></label></th>
						<td>
							<input
								type="text"
								value="<?php echo esc_attr(get_user_meta($user->ID, 'user_mixcloud', true)); ?>"
								name="user_mixcloud"
								id="user_mixcloud"
							>
							<span class="description"><?php echo esc_html__( 'MixCloud username i.e @tuzongo', 'weekly-radio-schedule' ) ?></span>
						</td>
					</tr>
				<?php if ( get_option( TZWRS_OPTION_NAME . '_wrs_send_follower_notifications' ) ) { ?>
					<tr>
						<th><label for="user_follower_notes"><?php echo esc_html__( 'Followers', 'weekly-radio-schedule' ) ?></label></th>
						<td>
						<input type="checkbox" name="user_follower_notes" id="user_follower_notes" class="checkbox" <?php if(get_the_author_meta('user_follower_notes', $user->ID)=='on' ){ echo "checked"; } ?> /><label for="user_follower_notes"><?php esc_html_e( "Be notified of your new followers.","weekly-radio-schedule" ) ?></label><hr>
						</td>
					</tr>
				<?php } 
				}
		}
		$wrs_show_year = esc_attr(get_user_meta($user->ID, 'user_showyear', true));
		?>
		<tr>
			<th><label for="user_pronoun"><?php echo esc_html__( 'Pronoun', 'weekly-radio-schedule' ) ?></label></th>
			<td>
			<?php 
				//get dropdown saved value
				$selected = get_the_author_meta( 'user_pronoun', $user->ID ); 
			?>
				<select name="user_pronoun" id="user_pronoun">
					<option value="" <?php echo ($selected == "")?  'selected="selected"' : '' ?>></option>
					<option value="she" <?php echo ($selected == "she")?  'selected="selected"' : '' ?>><?php echo esc_html__( 'she/her/hers', 'weekly-radio-schedule' ); ?></option>
					<option value="he" <?php echo ($selected == "he")?  'selected="selected"' : '' ?>><?php echo esc_html__( 'he/him/his', 'weekly-radio-schedule' ); ?></option>
					<option value="they" <?php echo ($selected == "they")?  'selected="selected"' : '' ?>><?php echo esc_html__( 'they/them/their', 'weekly-radio-schedule' ); ?></option>
				</select>

				<span class="description"><?php echo esc_html__( 'How are you referred to?', 'weekly-radio-schedule' ) ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="user_birthday"><?php echo esc_html__( 'Birthday', 'weekly-radio-schedule' ) ?></label></th>
			<td>
				<input
					type="date"
					value="<?php echo esc_attr(get_user_meta($user->ID, 'user_birthday', true)); ?>"
					name="user_birthday"
					id="user_birthday"
				>
			<legend class="screen-reader-text">
				<span><?php echo esc_html__("Display year?","weekly-radio-schedule") ?></span>
			</legend>
			<label>
				<?php echo esc_html__( 'Display year?', 'weekly-radio-schedule' ); ?>
				<input 
					type="checkbox" 
					name="user_showyear" 
					id="user_showyear" 
					value="1" <?php checked( $wrs_show_year, '1' ); ?>
				>
			</label>

				<span class="description"></span>
			</td>
		</tr>
			<?php
		if ( get_option( TZWRS_OPTION_NAME . '_wrs_send_post_notifications' ) || get_option( TZWRS_OPTION_NAME . '_wrs_send_slot_notifications' ) || get_option( TZWRS_OPTION_NAME . '_wrs_send_dj_weekly_notifications' ) ) { ?>
			<tr>
				<th><label for="user_post_notes"><?php echo esc_html__( 'Notifications', 'weekly-radio-schedule' ) ?></label></th>
				<td> <?php
				if ( get_option( TZWRS_OPTION_NAME . '_wrs_send_post_notifications' ) ) { ?>
					<input type="checkbox" name="user_post_notes" id="user_post_notes" class="checkbox" <?php if(get_the_author_meta('user_post_notes', $user->ID)=='on' ){ echo "checked"; } ?> /><label for="user_post_notes"><?php esc_html_e( "Be notified of new posts by people you follow.","weekly-radio-schedule" ) ?></label><hr>
				<?php } 
				if ( get_option( TZWRS_OPTION_NAME . '_wrs_send_slot_notifications' ) ) { ?>
					<input type="checkbox" name="user_slot_notes" id="user_slot_notes" class="checkbox" <?php if(get_the_author_meta('user_slot_notes', $user->ID)=='on' ){ echo "checked"; } ?> /><label for="user_slot_notes"><?php esc_html_e( "Be notified when people you follow take a vacant slot.","weekly-radio-schedule" ) ?></label><hr>
				<?php } 
				if ( get_option( TZWRS_OPTION_NAME . '_wrs_send_dj_weekly_notifications' ) ) { ?>
					<input type="checkbox" name="user_digest_notes" id="user_digest_notes" class="checkbox" <?php if(get_the_author_meta('user_digest_notes', $user->ID)=='on' ){ echo "checked"; } ?> /><label for="user_digest_notes"><?php esc_html_e( "Be notified weekly of scheduled shows by people you follow.","weekly-radio-schedule" ) ?></label>
				<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</table>
		<?php
	}

	function save_extra_user_profile_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) { 
			return false; 
		}
		update_user_meta( $user_id, 'user_birthday', $_POST['user_birthday'] );
		update_user_meta( $user_id, 'user_showyear', $_POST['user_showyear'] );
		update_user_meta( $user_id, 'user_pronoun', $_POST['user_pronoun'] );
		update_user_meta( $user_id, 'user_twitter', $_POST['user_twitter'] );
		update_user_meta( $user_id, 'user_fbprofile', $_POST['user_fbprofile'] );
		update_user_meta( $user_id, 'user_show_name', $_POST['user_show_name'] );
		update_user_meta( $user_id, 'user_fbpage', $_POST['user_fbpage'] );
		update_user_meta( $user_id, 'user_instagram', $_POST['user_instagram'] );
		update_user_meta( $user_id, 'user_mixcloud', $_POST['user_mixcloud'] );
		update_user_meta( $user_id, 'user_follower_notes', $_POST['user_follower_notes'] );
		update_user_meta( $user_id, 'user_post_notes', $_POST['user_post_notes'] );
		update_user_meta( $user_id, 'user_slot_notes', $_POST['user_slot_notes'] );
		update_user_meta( $user_id, 'user_digest_notes', $_POST['user_digest_notes'] );
	}

	/**
	 * @usage Adds html and js to back-end footer
	 */
	function tzwrs_admin_footer_scripts(){
		global $wp_query;

		$current_user = wp_get_current_user();
		$day_mark = '#day' . gmdate("w",current_time( 'timestamp' ));
		$allowed = array( 'div' => array('class' => array(), 'itemtype' => array(), 'itemscope' => array()), 'meta' => array('itemprop' => array(), 'content' => array()), 'input' => array('type' => array(), 'class' => array(), 'value' => array(), 'data-author' => array()), 'span' => array('itemprop' => array()), 'img' => array('src' => array(), 'class' => array(), 'width' => array(), 'title' => array(), 'itemprop' => array()), 'h2' => array('class' => array()),  'a' => array('href' => array(), 'itemprop' => array(), 'title' => array()), 'strong' => array() );
		?>
		<div id="id04" class="modal teammodal" data-currid="<?php echo esc_attr($current_user->ID); ?>">
			<span onclick="document.getElementById('id04').style.display='none'" class="close" title="<?php echo esc_attr(__( 'Close', 'weekly-radio-schedule' ) ); ?>">&times;</span>
			<div class="modal-content animate">
				<div class="imgcontainer">
					<div id="team-modal-content">
						<div class="the_team_modal_wrap">
						<h1 class="team_title"><?php echo strip_tags(get_bloginfo()) . ' - ' . esc_html__( 'The Team', 'weekly-radio-schedule' ); ?></h3>
						 <?php echo wp_kses( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_the_team( 86, get_option( TZWRS_OPTION_NAME . 'wrs_max_desc_chars' ) ), $allowed); ?>
						<div class="after_msg_form">
						<p><?php echo strip_tags(get_bloginfo( 'description' )); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
		// Get the modal
		var modal4 = document.getElementById('id04');

		// When the user clicks anywhere outside of the modal4, close it
		window.onclick = function(event) {
			if (event.target == modal4) {
				modal4.style.display = "none";
			}
		}

	</script>

	<?php
	}

}

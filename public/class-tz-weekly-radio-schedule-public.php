<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://tuzongo.com
 * @since      1.0.0
 *
 * @package    Tz_Weekly_Radio_Schedule
 * @subpackage Tz_Weekly_Radio_Schedule/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Sid Edwards <sid@tuzongo.com>
 */
class Tz_Weekly_Radio_Schedule_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'wp_enqueue_scripts', array( $this, 'tzwrs_enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'tzwrs_enqueue_scripts' ) );
		add_shortcode( 'wrs_profile_showlist', array( $this, 'tzwrs_showlist_shortcode' ) );

		add_filter( 'show_admin_bar', array($this, 'tzwrs_show_admin_bar' ),99999 );
		add_filter( 'body_class', array($this,'tzwrs_class_to_body' ) );
		add_filter( 'wp_nav_menu_objects', array($this,'tzwrs_wp_nav_menu_objects' ) );
		add_shortcode( 'my_week_coming_up', array( $this, 'tzwrs_my_week_coming_up' ) );
		add_shortcode( 'on_air_ticker', array( $this, 'tzwrs_on_air_ticker' ) );
		add_shortcode( 'my_shows', array( $this, 'tzwrs_my_week_coming_up' ) );
		add_shortcode( 'on_air_player_panel', array( $this, 'tzwrs_on_air_player_panel' ) );
		add_shortcode( 'join_the_team', array( $this, 'tzwrs_join_the_team' ) );
		add_shortcode( 'add_me_now', array( $this, 'tzwrs_add_me_now' ) );
		add_shortcode( 'who_on_air', array( $this, 'tzwrs_who_on_air' ) );
		add_shortcode( 'who_up_next', array( $this, 'tzwrs_who_up_next' ) );
		add_shortcode( 'slot_gen', array( $this, 'tzwrs_slot_gen' ) );
		add_shortcode( 'the_team', array( $this, 'tzwrs_team_shortcode') );
		add_shortcode( 'shows_picker', array( $this, 'tzwrs_teamsters') );
		add_shortcode( 'zone_detail', array( $this, 'tzwrs_zone_details') );
		add_shortcode( 'on_air_panel', array( $this, 'tzwrs_on_air_panel' ) );
		add_shortcode( 'on_this_day', array( $this, 'tzwrs_on_this_day' ) );
		add_shortcode( 'wrs_followers', array( $this, 'tzwrs_dj_followers_shortcode' ) );
		
		add_action( 'tzwrs_reset_schedule_cron_hook', 'tzwrs_reset_schedule' );
		add_action( 'wp_head', array($this, 'tzwrs_head_style' ), 6 );
		add_action( 'widgets_init', array( $this, 'tzwrs_register_widgets' ) );
		add_action( 'wp_footer', array( $this, 'tzwrs_tabbytrigger' ), 20);
		add_action( 'init', array($this, 'tzwrs_change_author_permalinks' ) );
		add_action( 'wp_ajax_tzwrs_increment_love',array( $this, 'tzwrs_increment_love' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_increment_love', array( $this, 'tzwrs_increment_love' ) );
		add_action( 'wp_ajax_tzwrs_slot_gen',array( $this, 'tzwrs_slot_gen' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_slot_gen', array( $this, 'tzwrs_slot_gen' ) );
		add_action( 'wp_ajax_tzwrs_on_air_full_update', array( $this, 'tzwrs_on_air_full_update' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_on_air_full_update', array( $this, 'tzwrs_on_air_full_update' ) );
		add_action( 'wp_ajax_tzwrs_on_air_update', array( $this, 'tzwrs_on_air_update' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_on_air_update', array( $this, 'tzwrs_on_air_update' ) );
		add_action( 'wp_ajax_tzwrs_msgjoin', array( $this, 'tzwrs_msgjoin' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_msgjoin', array( $this, 'tzwrs_msgjoin' ) );
		add_action( 'wp_ajax_tzwrs_msgdj', array( $this, 'tzwrs_msgdj' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_msgdj', array( $this, 'tzwrs_msgdj' ) );
		add_action( 'wp_ajax_tzwrs_sendmsgdj', array( $this, 'tzwrs_sendmsgdj' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_sendmsgdj', array( $this, 'tzwrs_sendmsgdj' ) );
		add_action( 'wp_ajax_tzwrs_sendmsgjoin', array( $this, 'tzwrs_sendmsgjoin' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_sendmsgjoin', array( $this, 'tzwrs_sendmsgjoin' ) );
		add_action( 'wp_ajax_tzwrs_dj_dropdown', array( $this, 'tzwrs_dj_dropdown' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_dj_dropdown',  array( $this, 'tzwrs_dj_dropdown' ) );
		add_action( 'wp_ajax_tzwrs_updateSlot', array( $this, 'tzwrs_updateSlot' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_updateSlot', array( $this, 'tzwrs_updateSlot' ) );
		add_action( 'wp_ajax_tzwrs_updateShows', array( $this, 'tzwrs_updateShows' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_updateShows', array( $this, 'tzwrs_updateShows' ) );
		add_action( 'wp_ajax_tzwrs_update_cell', array( $this, 'tzwrs_update_cell' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_update_cell', array( $this, 'tzwrs_update_cell' ) );
		add_action( 'wp_ajax_tzwrs_confirm_first', array( $this, 'tzwrs_confirm_first' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_confirm_first', array( $this, 'tzwrs_confirm_first' ) );
		add_action( 'wp_ajax_tzwrs_shows_coming_up', array( $this, 'tzwrs_shows_coming_up' ) );
		add_action( 'wp_ajax_nopriv_tzwrs_shows_coming_up', array( $this, 'tzwrs_shows_coming_up' ) );
	}
	
	/**
	 * @usage Display on profile page number of followers for DJ
	 * @return mixed 
	 */
	function tzwrs_dj_followers_shortcode($atts) {
		$atts = shortcode_atts( array( 'user_id' => '' ), $atts );
		$user_id = intval( $atts['user_id'] );

		$data = array(
			'user_id'	=>	$user_id
		);

		if( ! class_exists( 'Gamajo_Template_Loader' ) ) {
			require plugin_dir_path( __FILE__ ) . 'class-gamajo-template-loader.php';
		}
		if( ! class_exists( 'WRS_Template_Loader' ) ) {
			require plugin_dir_path( __FILE__ ) . 'class-tz-weekly-radio-schedule-template-loader.php';
		}
		$templates = new WRS_Template_Loader;

		ob_start();
		$templates
			->set_template_data( $data )
			->get_template_part( 'content', 'followers' );
		return ob_get_clean();
	}

	/**
	 * @usage Display a list of shows on a particular day either this week or next
	 * @return mixed 
	 */
	function tzwrs_on_this_day($atts) {
		$local_time  = current_datetime();
		$current_time = $local_time->getTimestamp() + $local_time->getOffset();
		
		$atts = shortcode_atts( array( 'the_day' => date("j", $current_time), 'the_week' => 'this' ), $atts );
		
		$the_day = intval( $atts['the_day'] );
		$day_start = mktime(0, 0, 0, date('m'), $the_day, date('Y'));
		$sun_start = $this->tzwrs_start_of_the_week() - ( 24 * 60 * 60 ); //Timestamp of Sunday Midnight
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$text_for_day = array('','sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
		if ( ( $day_start - $sun_start ) < 0 )
		{
			// past
		}
		else 
		{
			if ( $the_day > date("j", $sun_start) + 13 )
			{
				echo '<span>' . esc_html( 'Date too far' ) . '</span>';
			}
			else 
			{
				if ( $the_day <= date("j", $sun_start) + 6 ) {
					$the_week = 'this';
					$hour = ( ( $day_start - $sun_start ) / ( 60 * 60 ) ) + 1;
				}
				else 
				{
					$the_week = 'next';
					$hour = ( ( $day_start - $sun_start ) / ( 60 * 60 ) ) - 167;
				}
			}
		} 

		global $wpdb;
		$approval = 0;
		$table_name = $wpdb->prefix . 'wrs_' . $the_week . '_week';
		if ( $hour )
		{
			$sql = "SELECT s.*, u.ID, u.user_nicename, u.display_name
				FROM " . $table_name . " s, " . $wpdb->prefix . "users u
				WHERE (s.hour >= " . $hour . "
					AND s.hour <= " . ( $hour + 24 ) . "
					AND s.user_id = u.ID
					AND s.add_dj = 0)
				OR (s.hour >= " . $hour . "
					AND s.hour <= " . ( $hour + 24 ) . "
					AND s.temp_user_id = u.ID
					AND s.add_dj > " . $approval . ")
					ORDER BY s.hour ASC";
			$results = $wpdb->get_results($sql);
		}

		if (count($results) > 0) {
			foreach ($results as $res) {
				$rowset[$res->hour] = array(
					'hour'				=> ($res->hour)-1,
					'user_id'			=> $res->user_id,
					'add_dj'			=> $res->add_dj,
					'temp_user_id'		=> $res->temp_user_id,
					'username' 			=> $res->display_name,
					'slot_id' 			=> $res->slot_id,
					'user_nicename' 	=> $res->user_nicename,
				);
			}
		} 
		$last_dj_id = $last_temp_dj_id = $last_dj_hour = 0;
		$wrs_weekly_shows = array();
		$wrs_weekly_shows[0] = $wrs_weekly_shows[1] = $wrs_weekly_shows[2] = $wrs_weekly_shows[3] = $wrs_weekly_shows[4] = $wrs_weekly_shows[5] = $wrs_weekly_shows[6] = '';
		$starting_slot = false;
		if (count($results) > 0) {
			$dj_array = $rowset;
			for ( $i = $hour; $i < $hour + 24; $i++)
			{
				$onAir = $i == $this_hour ? ' onAir' : '';
				$wrs_weekly_show = '';
				if ( !empty($dj_array[$i]['user_id']) ) {  } else { $dj_array[$i]['user_id'] = 0; }
				if ( !empty($dj_array[$i]['temp_user_id']) ) {  } else { $dj_array[$i]['temp_user_id'] = 0; }
				if ( $dj_array[$i]['user_id'] != 0 && $dj_array[$i]['temp_user_id'] != 0 ) 
				{
					$amal = $dj_array[$i]['temp_user_id'];
				}
				else
				{
					$amal = $dj_array[$i]['user_id'] + $dj_array[$i]['temp_user_id'];
				}
				$PeepSoUser = false;
				if ( $amal > 0 )
				{
					if ( ( $amal == $last_dj_id ) && $last_dj_hour == $dj_array[$i]['hour'] - 1 )
					{
						$last_dj_id = $amal;
						$last_dj_hour = $dj_array[$i]['hour'];
					} 
					else 
					{
						if ( ! $starting_slot ) $starting_slot = $dj_array[$i]['hour'];
						$day_numeric = (intval($dj_array[$i]['hour']) / 24)+1;
						$pic_width = intval( get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size') );
						if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
							$slot_user = get_user_by( 'slug', $dj_array[$i]['user_nicename']);
							$PeepSoUser = PeepSoUser::get_instance($slot_user->ID);
							$profile_url = esc_url( $PeepSoUser->get_profileurl() );
							$img = '<img itemprop="image" width="' . 2*$pic_width . '" class="avatar avatar-' . 2*$pic_width . '" src="' . esc_url($PeepSoUser->get_avatar()) . '" />';
						}
						else 
						{
							$slot_user = get_user_by( 'slug', $dj_array[$i]['user_nicename']);
							$profile_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $slot_user->ID ) ) );
							$img = '<img itemprop="image" width="' . 2*$pic_width . '" class="avatar avatar-' . 2*$pic_width . ' photo" src="' . esc_url(get_avatar_url( $slot_user->ID )) . '" />';
						}
						$the_time = $day_start + (intval($dj_array[$i]['hour']) * 60 * 60);
						$time_valid = mktime(0, 0, 0, date("n"), 1);

						$durAtion = 1;
						$plural = '';
						$currentHour = $i;
						$length = 10;

						if ( $i <= 168 ) 
						{
							while ( isset( $dj_array[$currentHour+1]['user_id'] ) )
							{
								if ( isset($dj_array[$currentHour+1]) ) {
									$currentHour++;
									if ( $dj_array[$currentHour]['user_id']+$dj_array[$currentHour]['temp_user_id'] == $amal )
									{
										$plural = 's';
										$durAtion++;
									}
								}
							}
						}

						$wrs_weekly_show .= '
						<div class="schedule_item item ' . $onAir . '">';
							if ( $PeepSoUser ) { 
								$wrs_weekly_show .= '<meta itemprop="image" content="' . esc_url($PeepSoUser->get_cover()) . '" />';
							}
							$wrs_weekly_show .= '
							<span class="">
							' . intval($dj_array[$i]['hour']) % 24 . ':00
							</span><span class="durAtion">' . intval($durAtion) . 'hr' . strip_tags($plural) . '</span>
							<a href="' . esc_url($profile_url) . '">
								<h4 class="daily_schedule_title">
								' . esc_html( $this->tzwrs_str_stop( $dj_array[$i]['username'], intval(get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) . '
								</h4>
							</a>
							<div class="" itemprop="performer" itemscope="" itemtype="https://schema.org/Person">
								<meta itemprop="name" content="' . esc_html( $this->tzwrs_str_stop( $dj_array[$i]['username'], intval(get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) ) ) . '" />
								<a itemprop="url" href="' . $profile_url . '">
								' . $img . '
								</a>';

								$about = $this->tzwrs_str_stop(trim(get_the_author_meta( 'description', $slot_user->ID )), intval(get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) );

								$wrs_weekly_show .= '<meta itemprop="description" class="show_snip" content="' . esc_attr($about) . '" />';

						$wrs_weekly_show .= '</div></div>';
						$last_dj_id = $amal;
						$last_dj_hour = $dj_array[$i]['hour'];
					}
					
				}

				if ( !empty($dj_array[$i]['hour']) ) 
				{
					if ($dj_array[$i]['hour'] < 25) 
					{
						$wrs_weekly_shows[0] .= $wrs_weekly_show;
					} 
					elseif ($dj_array[$i]['hour'] < 49) 
					{
						$wrs_weekly_shows[1] .= $wrs_weekly_show;
					} 
					elseif ($dj_array[$i]['hour'] < 73) 
					{
						$wrs_weekly_shows[2] .= $wrs_weekly_show;
					} 
					elseif ($dj_array[$i]['hour'] < 97) 
					{
						$wrs_weekly_shows[3] .= $wrs_weekly_show;
					} 
					elseif ($dj_array[$i]['hour'] < 121) 
					{
						$wrs_weekly_shows[4] .= $wrs_weekly_show;
					} 
					elseif ($dj_array[$i]['hour'] < 145) 
					{
						$wrs_weekly_shows[5] .= $wrs_weekly_show;
					} 
					elseif ($dj_array[$i]['hour'] < 169) 
					{
						$wrs_weekly_shows[0] .= $wrs_weekly_show;
					}
				}
			}
			$wrs_weekly0 = $wrs_weekly1 = $wrs_weekly2 = $wrs_weekly3 = $wrs_weekly4 = $wrs_weekly5 = $wrs_weekly6 = '';
			$start_time_text = '11 GMT (12 UK Time)';
			if ( $wrs_weekly_shows[0] ) {
				$wrs_weekly0 = '<span class="day_month" colspan="7">' . esc_html($start_time_text) . ' ' . gmdate("l jS F", $day_start) . '</span><div class="tabz schedule_items masonry">';
				$wrs_weekly0 .= $wrs_weekly_shows[0];
				$wrs_weekly0 .= '</div>';
				$wrs_weekly0 .= 	'<p class="tz_details">' . esc_html(Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details());
			}

			if ( $wrs_weekly_shows[1] ) {
				$wrs_weekly1 = '<span class="day_month" colspan="7">' . esc_html($start_time_text . ' ' . gmdate("l jS F", ( $day_start + ( 1 * 60 * 60 * 24 ) ) ) ) . '</span><div class="tabz schedule_items masonry">';
				$wrs_weekly1 .= $wrs_weekly_shows[1];
				$wrs_weekly1 .= '</div>';
				$wrs_weekly1 .= '<p class="tz_details">' . esc_html(Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details());
			}

			if ( $wrs_weekly_shows[2] ) {
				$wrs_weekly2 = '<span class="day_month" colspan="7">' . esc_html($start_time_text . ' ' . gmdate("l jS F", ( $day_start + ( 2 * 60 * 60 * 24 ) ) ) ) . '</span><div class="tabz schedule_items masonry">';
				$wrs_weekly2 .= $wrs_weekly_shows[2];
				$wrs_weekly2 .= '</div>';
				$wrs_weekly2 .= '<p class="tz_details">' . esc_html(Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details());
			}

			if ( $wrs_weekly_shows[3] ) {
				$wrs_weekly3 = '<span class="day_month" colspan="7">' . esc_html($start_time_text . ' ' . gmdate("l jS F", ( $day_start + ( 3 * 60 * 60 * 24 ) ) ) ) . '</span><div class="tabz schedule_items masonry">';
				$wrs_weekly3 .= $wrs_weekly_shows[3];
				$wrs_weekly3 .= '</div>';
				$wrs_weekly3 .= '<p class="tz_details">' . esc_html(Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details());
			}

			if ( $wrs_weekly_shows[4] ) {
				$wrs_weekly4 = '<span class="day_month" colspan="7">' . esc_html($start_time_text . ' ' . gmdate("l jS F", ( $day_start + ( 4 * 60 * 60 * 24 ) ) ) ) . '</span><div class="tabz schedule_items masonry">';
				$wrs_weekly4 .= $wrs_weekly_shows[4];
				$wrs_weekly4 .= '</div>';
				$wrs_weekly4 .= '<p class="tz_details">' . esc_html(Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details());
			}

			if ( $wrs_weekly_shows[5] ) {
				$wrs_weekly5 = '<span class="day_month" colspan="7">' . esc_html($start_time_text . ' ' . gmdate("l jS F", ( $day_start + ( 5 * 60 * 60 * 24 ) ) ) ) . '</span><div class="tabz schedule_items masonry">';
				$wrs_weekly5 .= $wrs_weekly_shows[5];
				$wrs_weekly5 .= '</div>';
				$wrs_weekly5 .= '<p class="tz_details">' . esc_html(Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details());
			}

			if ( $wrs_weekly_shows[6] ) {
				$wrs_weekly6 = '<span class="day_month" colspan="7">' . esc_html($start_time_text . ' ' . gmdate("l jS F", ( $day_start + ( 6 * 60 * 60 * 24 ) ) ) ) . '</span><div class="tabz schedule_items masonry">';
				$wrs_weekly6 .= $wrs_weekly_shows[6];
				$wrs_weekly6 .= '</div>';
				$wrs_weekly6 .= '<p class="tz_details">' . esc_html(Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details());
			}
		
			return '<div itemscope="22822" itemtype="https://schema.org/MusicEvent" class="event_performers">
			<meta itemprop="description" content="' . esc_attr( get_the_excerpt(), true ) . '" />
			<meta itemprop="image" content="' . esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')) . '" />
			<meta itemprop="eventStatus" content="EventScheduled" />
			<meta itemprop="name" content="'. esc_attr( get_the_title() ) . '" />
			<meta itemprop="eventAttendanceMode" content="https://schema.org/OnlineEventAttendanceMode" />
			<span class="meta" itemprop="organizer" itemscope="" itemtype="https://schema.org/Organization">
				<meta itemprop="name" content="'. esc_attr(get_bloginfo()) . '" />
				<meta itemprop="address" content="' . sanitize_text_field( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) ). '" />
				<meta itemprop="url" content="' . get_home_url() . '" />
			</span>
			<span class="meta" itemprop="location" itemscope="" itemtype="https://schema.org/MusicVenue">
				<meta itemprop="name" content="Crossroads"/>
				<meta itemprop="address" content="' . esc_attr( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) ) . '"/>
			</span>
			<span class="meta" itemprop="offers" itemscope="" itemtype="https://schema.org/Offer">
				<meta itemprop="price" content="0" />
				<meta itemprop="priceCurrency" content="USD" />
				<link itemprop="availability" href="https://schema.org/InStock" />
				<meta itemprop="url" content="' . esc_url( get_option( TZWRS_OPTION_NAME . '_wrs_audio_address' ) ) . '">
				<meta itemprop="validFrom" content="' . gmdate("c", $time_valid) . '">
			</span>
			<meta itemprop="startDate" content="' . gmdate("c", $day_start + ( ( $starting_slot % 24 ) * 60 * 60) ) . '" class="" />
			<meta itemprop="endDate" content="' . gmdate("c", $day_start + ( ( ( $last_dj_hour % 24 ) + 1) * 60 * 60) ) . '">
			' .$wrs_weekly0 . $wrs_weekly1 . $wrs_weekly2 . $wrs_weekly3 . $wrs_weekly4 . $wrs_weekly5 . $wrs_weekly6 . '</div>';
		}
		else
		{
			return;
		}
	}

	/**
	 * @usage Display on profile page a List of shows this week for DJ
	 * @return mixed 
	 */
	function tzwrs_showlist_shortcode($atts) {
		global $wp;
		$atts = shortcode_atts( array( 'profile' => '', 'id' => '' ), $atts );
		$author_id = $atts['id'];
		if ( !$author_id ) {
			if ( isset($wp->query_vars['author_name']) ) {
				$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($wp->query_vars['author_name']));
			}
			else 
			{
				if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
					$url = PeepSoUrlSegments::get_instance();
					if ( $url->get(1) )
					{
						$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($url->get(1)));
					}
				}
			}
		}

		if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_user_is_team($author_id) ) {
			$profile = $atts['profile'];
			$div_classes = $profile ? 'wrs_profile_showlist' : '';

			return '<div class="' . $div_classes . '">' . do_shortcode('[my_week_coming_up profile="' . $profile . '" picsize="64" textsize="' . intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) . '" id="' . intval( $author_id ) . '"]' ) . '</div>';
		}
	}

	/**
	 * @usage Return admin set 'play now text' or default
	 * @return mixed 
	 */
	static function tzwrs_you_can_play() {
		return get_option( TZWRS_OPTION_NAME . '_wrs_playnowtext' ) ? strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_playnowtext' ) ) : esc_html__( 'You could be playing this slot', 'weekly-radio-schedule' );
	}

	/**
	 * @usage Display Recently Registered User
	 * @return mixed 
	 */
	function wpb_recently_registered_users() { 

		global $wpdb;
		$recentusers = '<div class="recent-user">Newest member: ';
		$table_name = $wpdb->prefix . 'users';
		$usernames = $wpdb->get_results("SELECT ID, user_login, user_nicename, display_name, user_url, user_email FROM $table_name ORDER BY ID DESC LIMIT 1");
		foreach ($usernames as $username) {
			$ret = get_home_url();
			$user = get_user_by('id', $username->ID);

			if (FALSE !== $user) {
				if ( class_exists( 'PeepSo' ) ) {
					$ret .= '/' . PeepSo::get_option('page_profile') . '/';
					$ret .= $user->user_nicename . '/';
				}
				else 
				{
					$ret = get_author_posts_url( $username->ID );
				}
			}
			$recentuser_url = esc_url(apply_filters('peepso_username_link', $ret, $username->ID).'?date='.date("d-m-y", time()));

			$recentusers .= '<a href="' .$recentuser_url . '/">' . esc_html($username->user_login) . '</a>';
		}
		$recentusers .= '</div>';
		
		return $recentusers;  
	}

	/*
	 * Output a series of <li> with links for profile interactions
	 */
	static function wrs_interactions()
	{
		$PeepSoProfile = PeepSoProfile::get_instance();
		$aAct = array();

		// @todo privacy
		if (PeepSo::get_option('profile_sharing', TRUE)) {
			$aAct['share'] = array(
				'label' => __('Share', 'peepso-core'),
				'title' => __('Share this Profile', 'peepso-core'),
				'click' => 'share.share_url("' . $PeepSoProfile->user->get_profileurl() . '"); return false;',
				'icon' => 'gcis gci-share-alt',
				'class' => 'ps-focus__detail',
				'order' => 20
			);
		}


		if (is_user_logged_in()) {
			// Check whether the profile like button should be visible.
			$is_like_enabled = PeepSo::get_option('site_likes_profile', TRUE);
			if ($is_like_enabled) {
				$is_owner = $PeepSoProfile->user->get_id() == get_current_user_id();
				$is_likable = $PeepSoProfile->user->is_profile_likable();
				$is_like_enabled = $is_owner || $is_likable;
			}

			if ($is_like_enabled) {
				$peepso_like = PeepSoLike::get_instance();
				$likes = $peepso_like->get_like_count($PeepSoProfile->user->peepso_user['usr_id'], PeepSo::MODULE_ID);

				if (!$is_likable) {
					$like_icon = 'gcir gci-thumbs-up';
					$like_label = __('Like', 'peepso-core');
					$like_title = '';
					$like_liked = FALSE;
				} else if (FALSE === $peepso_like->user_liked($PeepSoProfile->user->peepso_user['usr_id'], PeepSo::MODULE_ID, get_current_user_id())) {
					$like_icon = 'gcir gci-thumbs-up';
					$like_label = __('Like', 'peepso-core');
					$like_title = __('Like this Profile', 'peepso-core');
					$like_liked = FALSE;
				} else {
					$like_icon = 'gcis gci-thumbs-up';
					$like_label = __('Like', 'peepso-core');
					$like_title = __('Unlike this Profile', 'peepso-core');
					$like_liked = TRUE;
				}

				$aAct['like'] = array(
					'label' => $like_label,
					'title' => $like_title,
					'click' => $is_likable ? 'profile.new_like();' : '',
					'icon' => $like_icon,
					'count' => (! empty($likes) ? $likes : 0),
					'class' => $like_liked ? 'ps-focus__like ps-focus__like--liked' : 'ps-focus__like',
					'order' => 30
				);
			}
		}

		$aAct['views'] = array(
			'label' => __('Views', 'peepso-core'),
			'title' => __('Profile Views', 'peepso-core'),
			'icon' => 'gcis gci-eye',
			'class' => 'ps-focus__detail',
			'count' => $PeepSoProfile->init()->get_view_count(), // PeepSoViewLog::get_views($this->user_id, PeepSo::MODULE_ID),
			'order' => 10,
			'all_values' => 1,
		);

		$aAct = apply_filters('peepso_user_activities_links', $aAct);

		$sort_col = array();

		foreach ($aAct as $item)
			$sort_col[] = (isset($item['order']) ? $item['order'] : 50);

		array_multisort($sort_col, SORT_ASC, $aAct);

		foreach ($aAct as $sName => $aAttr) {
			$withClick = (isset($aAttr['click']) && !empty($aAttr['click']));

			if ($withClick)
				echo '<a href="#" onclick="', esc_js(trim($aAttr['click'], ';')), '; return false;" ',
				(isset($aAttr['title']) ? ' title="' . esc_attr($aAttr['title']) . '" ' : ''),
				(isset($aAttr['class']) ? ' class="' . esc_attr($aAttr['class']) . '" ' : ''),
				'>', PHP_EOL;
			else
				echo '<span ',
				(isset($aAttr['title']) ? ' title="' . esc_attr($aAttr['title']) . '" ' : ''),
				' class="' . esc_attr($aAttr['class']) . '" ',
				'>', PHP_EOL;

			echo '<i class="', esc_attr($aAttr['icon']), '"></i>';
			if (isset($aAttr['count'])) {

				$count = $aAttr['count'];

				// if the key "all_values" is not present, values below 1 will not render
				if( $count<1 && (!array_key_exists('all_values', $aAttr) || FALSE === $aAttr['all_values'])) {
					$count = '';
				}

				echo '<span id="', $sName, '-count"><strong>', $count, '</strong>', esc_attr($aAttr['label']), '</span>';
			} else {
			  echo '<span>', esc_attr($aAttr['label']), '</span>';
			}
			echo ($withClick ? '</a>' : '</span>'), PHP_EOL;
		}
	}

	/**
	 * Social share networks with Link. Requires ESS_Social_Networks
	 *
	 * @param  string $network
	 * @param  string $media_url
	 * @param  int    $i
	 * @param  string $post_link
	 * @param  string $post_title
	 *
	 * @return string
	 */
	static public function tzwrs_ess_share_link( $network, $media_url = '', $i = 0, $post_link = '', $post_title = '' ) {
		if ( ! $network ) {
			return;
		}

		$link = '';

		if ( '' !== $post_link ) {
			$permalink = $post_link;
		} else {
			$permalink = ( class_exists( 'WooCommerce' ) && is_checkout() || is_front_page() ) ? get_bloginfo( 'url' ) : get_permalink();

			if ( class_exists( 'BuddyPress' ) && is_buddypress() ) {
				$permalink = bp_get_requested_url();
			}
		}

		$permalink = rawurlencode( $permalink );

		if ( '' !== $post_title ) {
			$title = $post_title;
		} else {
			$title = class_exists( 'WooCommerce' ) && is_checkout() || is_front_page() ? get_bloginfo( 'name' ) : get_the_title();
		}

		$title = rawurlencode( wp_strip_all_tags( html_entity_decode( $title, ENT_QUOTES, 'UTF-8' ) ) );

		$twitter_username = strip_tags( get_option( 'easy_social_sharing_twitter_username' ) );

		switch ( $network ) {
			case 'facebook' :
				$link = sprintf( 'http://www.facebook.com/sharer.php?u=%1$s&t=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'twitter' :
				$link = sprintf( 'http://twitter.com/share?text=%2$s&url=%1$s&via=%3$s', esc_attr( $permalink ), esc_attr( $title ), ! empty( $twitter_username ) ? esc_attr( $twitter_username ) : get_bloginfo( 'name' ) );
				break;
			case 'googleplus' :
				$link = sprintf( 'https://plus.google.com/share?url=%1$s&t=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'pinterest' :
				$link = $media_url ? sprintf( 'http://www.pinterest.com/pin/create/button/?url=%1$s&media=%2$s&description=%3$s', esc_attr( $permalink ), esc_attr( urlencode( $media_url ) ), esc_attr( $title ) ) : '#';
				break;
			case 'stumbleupon' :
				$link = sprintf( 'http://www.stumbleupon.com/badge?url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'tumblr' :
				$link = sprintf( 'https://www.tumblr.com/share?v=3&u=%1$s&t=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'blogger' :
				$link = sprintf( 'https://www.blogger.com/blog_this.pyra?t&u=%1$s&n=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'myspace' :
				$link = sprintf( 'https://myspace.com/post?u=%1$s', esc_attr( $permalink ) );
				break;
			case 'delicious' :
				$link = sprintf( 'https://delicious.com/post?url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'amazon' :
				$link = sprintf( 'http://www.amazon.com/gp/wishlist/static-add?u=%1$s&t=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'printfriendly' :
				$link = sprintf( 'http://www.printfriendly.com/print?url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'yahoomail' :
				$link = sprintf( 'http://compose.mail.yahoo.com/?body=%1$s', esc_attr( $permalink ) );
				break;
			case 'gmail' :
				$link = sprintf( 'https://mail.google.com/mail/u/0/?view=cm&fs=1&su=%2$s&body=%1$s&ui=2&tf=1', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'aol' :
				$link = sprintf( 'http://webmail.aol.com/Mail/ComposeMessage.aspx?subject=%2$s&body=%1$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'newsvine' :
				$link = sprintf( 'http://www.newsvine.com/_tools/seed&save?u=%1$s&h=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'hackernews' :
				$link = sprintf( 'https://news.ycombinator.com/submitlink?u=%1$s&t=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'evernote' :
				$link = sprintf( 'http://www.evernote.com/clip.action?url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'digg' :
				$link = sprintf( 'http://digg.com/submit?url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'livejournal' :
				$link = sprintf( 'http://www.livejournal.com/update.bml?subject=%2$s&event=%1$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'friendfeed' :
				$link = sprintf( 'http://friendfeed.com/?url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'buffer' :
				$link = sprintf( 'https://bufferapp.com/add?url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'reddit' :
				$link = sprintf( 'http://www.reddit.com/submit?url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
			case 'vkontakte' :
				$link = sprintf( 'http://vk.com/share.php?url=%1$s', esc_attr( $permalink ) );
				break;
			case 'linkedin' :
				$link = sprintf( 'http://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s', esc_attr( $permalink ), esc_attr( $title ) );
				break;
		}

		return $link;
	}

	/**
	 * Generate Inline Icons.
	 */
	static function tzwrs_generate_inline_icons( $place = 'panel', $class = 'ess-inline-top' ) {
		ob_start();
		$network_desc     = ESS_Social_Networks::get_network_desc();
		$network_count    = ESS_Social_Networks::get_network_count();
		$allowed_networks = ESS_Social_Networks::get_allowed_networks();

		if ( $allowed_networks ) {
			include( TZWRS_DIRECTORY . 'public/templates/tz-html-view-layout-inline.php' );
		}

		return ob_get_clean();
	}

	/**
	 * @usage Update db relating to specific slot allocated to DJ via AJAX
	 */
	function tzwrs_update_cell() {
		global $wpdb, $current_user;
		
		$weekmark = strip_tags( $_REQUEST['week'] );
		$table_name = $wpdb->prefix . 'wrs_' . $weekmark . '_week';
		$this_dj_id = intval( $current_user->ID );

		$slot_id = intval( $_REQUEST['slotid'] );
		$act = strip_tags( $_REQUEST['act'] );
		$hour_id = intval( $_REQUEST['hour_id'] );
		
		$hourmark = 'hx';
		if ( $weekmark === 'this' ) { $hourmark = 'h'; }
		
		if ( isset($_REQUEST['slotid']) ) {
			$row = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE slot_id = ' . $slot_id );
			$slot_user = intval( $row->user_id );
			$temp_user_id = intval( $row->temp_user_id );
			$add_dj = intval( $row->add_dj );
		}
		switch($act) :
			case 'wrs_join_the_team':
				if ( current_user_can('operate') || $slot_user == $this_dj_id )
				{

				}
			break;

			case 'wrs_mark_me_away':
				if ( current_user_can('operate') || $slot_user == $this_dj_id )
				{
					$add_dj = -1;
					//$slot_user = 0;
					$temp_user_id = 0;
				}
			break;

			case 'wrs_mark_dj_as_away':
				if ( current_user_can('operate') || $slot_user == $this_dj_id )
				{
					$add_dj = -1;
					//$slot_user = 0;
					$temp_user_id = 0;
				}
			break;
			
			case 'wrs_mark_me_as_playing':
			case 'wrs_mark_dj_as_playing':
				if ( current_user_can('operate') || $slot_user == $this_dj_id )
				{
					$add_dj = 0;
					//$slot_user = 0;
					$temp_user_id = 0;
				}
			break;
			
			case 'wrs_clear_this_slot':
				if ( current_user_can('operate') || $slot_user == $this_dj_id )
				{
					$add_dj = $slot_user > 0 ? -1 : 0;
					//$add_dj = -1;
					//$slot_user = 0;
					$temp_user_id = 0;
				}
			break;
			
			case 'wrs_approve':
				if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_is_manager() || current_user_can('administrator') )
				{
					$add_dj = 2;
					//$slot_user = 0;
					//$temp_user_id = 0;
				}
			break;
			
			case 'wrs_deny':
			case 'wrs_yes':
				if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_is_manager() )
				{
					$add_dj = $slot_user > 0 ? -1 : 0;
					//$add_dj = -1;
					//$slot_user = 0;
					$temp_user_id = 0;
				}
			break;
			
			case 'wrs_add_me_here':
			case 'wrs_add_me_now':
				if ( current_user_can( 'add_self_to_schedule' ) )
				{
					$add_dj = 1;
					//$slot_user = 0;
					$temp_user_id = $this_dj_id;
				}
			break;
			
			case 'wrs_cancel':
				if ( $temp_user_id == $this_dj_id )
				{
					$add_dj = $slot_user > 0 ? -1 : 0;
					//$add_dj = -1;
					//$slot_user = 0;
					$temp_user_id = 0;
				}
			break;
			
			case 'wrs_cancel_now':
			if ( current_user_can('operate') || $temp_user_id == $this_dj_id )
				{
					$add_dj = $slot_user > 0 ? -1 : 0;
					//$add_dj = -1;
					//$slot_user = 0;
					$temp_user_id = 0;
				}
			break;
			
			case 'wrs_no':
				if ( $temp_user_id == $this_dj_id )
				{
					//$add_dj = $slot_user > 0 ? -1 : 0;
					//$add_dj = -1;
					//$slot_user = 0;
					//$temp_user_id = 0;
				}
			break;
			
			default:
				// Oops!
			break;
		endswitch;

		$allowed = array( 
			'div' => array(
				'class' => array(), 
				'itemtype' => array(), 
				'itemscope' => array()
			), 
			'meta' => array(
				'itemprop' => array(), 
				'content' => array()
			), 
			'input' => array(
				'type' => array(), 
				'class' => array(), 
				'value' => array(), 
				'data-author' => array()
			), 
			'span' => array(
				'class' => array(), 
				'data-hour' => array(), 
				'data-slotid' => array(), 
				'data-act' => array(), 
				'itemprop' => array()
			), 
			'img' => array(
				'src' => array(), 
				'class' => array(), 
				'width' => array(), 
				'itemprop' => array()
			), 
			'h2' => array(
				'class' => array()
			),  
			'h4' => array(
				'class' => array()
			),  
			'h5' => array(
				'class' => array()
			),  
			'a' => array(
				'href' => array(), 
				'itemprop' => array(), 
				'title' => array()
			), 
			'strong' => array() 
		);

		switch($act) :
			case 'wrs_mark_me_away':
			case 'wrs_mark_dj_as_away':
			case 'wrs_mark_me_as_playing':
			case 'wrs_mark_dj_as_playing':
			case 'wrs_clear_this_slot':
				$rows_affected = $wpdb->update(
					$table_name, 
					array( 
						'add_dj' => $add_dj,
						'temp_user_id' => $temp_user_id
					), 
					array(
						'slot_id' => $slot_id
					) 
				);
				echo do_shortcode( '[slot_gen the_week="' . $weekmark . '" the_hour="' . $hour_id . '"]' );
			break;

			case 'wrs_approve':
			case 'wrs_yes':
			case 'wrs_deny':
				$rows_affected = $wpdb->update(
					$table_name, 
					array( 
						'add_dj' => $add_dj,
						'temp_user_id' => $temp_user_id
					), 
					array(
						'slot_id' => $slot_id
					) 
				);
				echo do_shortcode( '[slot_gen the_week="' . $weekmark . '" the_hour="' . $hour_id . '"]' );
			break;
			
			case 'wrs_add_me_here':
				$rows_affected = $wpdb->update(
					$table_name, 
					array( 
						'add_dj' => $add_dj,
						'temp_user_id' => $temp_user_id
					), 
					array(
						'slot_id' => $slot_id
					) 
				);
				echo do_shortcode( '[slot_gen the_week="' . $weekmark . '" the_hour="' . $hour_id . '"]' );
				Tz_Weekly_Radio_Schedule_Public::tzwrs_notify_new_slot($temp_user_id, $slot_id, $weekmark);
			break;
			
			case 'wrs_add_me_now':
				$rows_affected = $wpdb->update(
					$table_name, 
					array( 
						'add_dj' => $add_dj,
						'temp_user_id' => $temp_user_id
					), 
					array(
						'slot_id' => $slot_id
					) 
				);
				echo wp_kses( Tz_Weekly_Radio_Schedule_Public::tzwrs_on_air_full_update(), $allowed );
				Tz_Weekly_Radio_Schedule_Public::tzwrs_notify_new_slot($temp_user_id, $slot_id, $weekmark);
			break;
			
			case 'wrs_cancel':
				$rows_affected = $wpdb->update(
					$table_name, 
					array( 
						'add_dj' => $add_dj,
						'temp_user_id' => $temp_user_id
					), 
					array(
						'slot_id' => $slot_id
					) 
				);
				
				echo do_shortcode( '[slot_gen the_week="' . $weekmark . '" the_hour="' . $hour_id . '"]' );
			break;
			
			case 'wrs_cancel_now':
				$rows_affected = $wpdb->update(
					$table_name, 
					array( 
						'add_dj' => $add_dj,
						'temp_user_id' => $temp_user_id
					), 
					array(
						'slot_id' => $slot_id
					) 
				);
				echo wp_kses( Tz_Weekly_Radio_Schedule_Public::tzwrs_on_air_full_update(), $allowed );
			break;
			
			case 'wrs_join_the_team':
				echo wp_kses( Tz_Weekly_Radio_Schedule_Public::tzwrs_on_air_full_update(), $allowed );
			break;
			
			default:
				echo do_shortcode( '[slot_gen the_week="' . $weekmark . '" the_hour="' . $hour_id . '"]' );
			break;
		endswitch;
		die();
	} 

	/**
	 * @usage slot request indication
	 */
	function tzwrs_schedule_alert() {
		global $wpdb;
		$schedule_alerts = 0;
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$futures = array('this','next');
		foreach ( $futures as $future ) {
			$table_name = $wpdb->prefix . 'wrs_' . $future . '_week';
			$rows = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE add_dj = 1 AND hour >= ' . $this_hour );
			foreach ( $rows as $row )
			{
				$schedule_alerts++;
			}
		}

		if ( $schedule_alerts > 0 && ( current_user_can( 'run_tings' ) || current_user_can( 'operate' ) ) ) {
			echo esc_html__( 'Schedule', 'weekly-radio-schedule' ) . '<span class="schedule_alert" title="' . esc_attr( $schedule_alerts . ' ' . esc_html__( 'Slot Request', 'weekly-radio-schedule' ) ) . '" alt="' . esc_html__( 'Slot Request', 'weekly-radio-schedule' ) . '">•</span>';
		}
		else
		{
			echo esc_html__( 'Schedule', 'weekly-radio-schedule' ) . '<span class="no_schedule_alert"></span>';
		}
		die();
	}
	
	/**
	 * @usage Generate html for fly-out info panel
	 */
	static function tzwrs_accordion() {
		global $wpdb, $wp;
		$allowed  = array( 
			'ul' => array(
				'class' => array()
			), 
			'li' => array(
				'class' => array()
			),  
			'nav' => array(
				'class' => array()
			), 
			'div' => array(
				'id' => array(), 
				'class' => array(), 
				'itemtype' => array(), 
				'data-url' => array(), 
				'data-author' => array(), 
				'itemscope' => array()
			), 
			'meta' => array(
				'itemprop' => array(), 
				'content' => array()
			), 
			'input' => array(
				'type' => array(), 
				'class' => array(), 
				'value' => array(), 
				'data-author' => array()
			), 
			'span' => array(
				'id' => array(), 
				'title' => array(),
				'itemprop' => array(), 
				'data-dj' => array(), 
				'data-week' => array(),
				'data-tip' => array(), 
				'data-act' => array(), 
				'data-slotid' => array(), 
				'data-hour' => array(), 
				'class' => array()
			), 
			'img' => array(
				'src' => array(), 
				'class' => array(), 
				'width' => array(), 
				'itemprop' => array(), 
				'title' => array()
			), 
			'h5' => array(
				'class' => array()
			),  
			'h4' => array(
				'class' => array()
			),  
			'a' => array(
				'class' => array(), 
				'href' => array(), 
				'rel' => array(), 
				'data-social-name' => array(), 
				'onclick' => array(), 
				'data-min-count' => array(), 
				'data-post-id' => array(), 
				'data-location' => array(), 
				'itemprop' => array(), 
				'title' => array()
			), 
			'strong' => array(), 
		);
		$outtica = '';
		$slotid = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slotid() );
		$table_name = $wpdb->prefix . 'wrs_this_week';
		$row = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE slot_id = ' . ($slotid) );
		if ( $row ) {
			$slot_user = intval( $row->user_id );
			$temp_user_id = intval( $row->temp_user_id );
			$add_dj = intval( $row->add_dj );
			$current_user_id = '';
			if ( $slot_user && $add_dj == 0 ) 
			{
				$current_user_id = $slot_user;
			}
			if ( $temp_user_id && $add_dj > 0 )
			{
				$current_user_id = $temp_user_id;
			}

			$vscf = false;
			// check for contact form 7 message DJ form id
			if ( get_option( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) != 'Select a form' && get_option( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) != '' ) {
				$vscf = true;
			} 

			$place = $shareouttica = $msgouttica = '';
			$outtica = '<nav class="dr-menu">
				<div class="dr-trigger">
					<span class="dr-icon dr-icon-menu"></span>';

			$msgouttica = ($vscf ? '<span class="dr-icon dr-icon-message">' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ? '<a title="' . esc_html__( 'Message' ) . ' ' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air()) . '" href="#"  onclick="document.getElementById(&quot;id05&quot;).style.display=&quot;block&quot;"><span id="on_air_panel" class="' . ( $current_user_id ? 'message_dj' : 'nomessage_dj' ) . '" data-dj="' . $current_user_id . '">
			<span class="dashicons dashicons-format-chat"></span></span></a>
			' : '
			<span class="' . ( $current_user_id ? 'message_dj' : 'nomessage_dj' ) . '" data-dj="' . $current_user_id . '">&nbsp;</span>' ) . '</span>' : '');
			
			/**
			 * Detect plugin. For use on Front End only.
			 */
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( 'easy-social-sharing/easy-social-sharing.php' ) ) {
				$place = 'panel';
				$shareouttica = '<span class="dr-icon dr-icon-share">
				' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ? '<span id="ess-main-wrapper" title="' . esc_html__( 'Share' ) . ' ' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air()) . '" class="' . ( $current_user_id ? 'share_dj' : 'share_nodj' ) . '" data-dj="' . $current_user_id . '">
				<span class="dashicons dashicons-share-alt2"></span>' . Tz_Weekly_Radio_Schedule_Public::tzwrs_generate_inline_icons($place) . '</span>' : '<span class="' . ( $current_user_id ? 'share_dj' : 'share_nodj' ) . '" data-dj="' . $current_user_id . '">&nbsp;</span>' ) . '</span>';
			}
			$outtica .= '
			<span class="dr-label">
				<span class="panel_header">
					' . $msgouttica . $shareouttica . '
					<span class="panel_header_data one">
						<span class="wrs_tz">' . wp_timezone_string() . ' (GMT ' . ( get_option( 'gmt_offset' ) < 0 ? '' . get_option( 'gmt_offset' ) : '+' . get_option( 'gmt_offset' ) ) . ') </span>
						<span class="panel_header_on_air">' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ? 'On Air' : '&nbsp;' ) . '</span>
					</span>
				</span>
			</span>';
			$place = '';
			$outtica .= '</div><ul class="draw_one"><li>' . Tz_Weekly_Radio_Schedule_Public::tzwrs_on_air_panel($place) . '</li></ul>';
			$outtica .= '</nav>';
			do_action( 'tzwrs_after_fly_out_panel' );
		}
		echo wp_kses( $outtica, $allowed);
	}
	
	/**
	 * @usage Set 'Home' menu item url
	 */
	function tzwrs_wp_nav_menu_objects( $sorted_menu_items )
	{
		foreach ( $sorted_menu_items as $menu_item ) {

			if ( $menu_item->post_title == 'Home' ) {

				$menu_item->url = get_home_url();
				break;
			}
		}
		return $sorted_menu_items;
	}

	/**
	 * @usage Populate form to message DJ on air
	 */
	function tzwrs_msgdj() {
		$allowed  = array( 
			'img' => array(
				'src' => array(), 
				'class' => array(), 
				'width' => array(), 
				'itemprop' => array()
			)
		);
		$user = new WP_User( intval( $_REQUEST['dj'] ) );
		$display_name = $user->display_name;
		$mail_to = sanitize_email( $user->user_email );
		$img = '<img width="400" class="avatar avatar-400 photo" src="' . esc_url( get_avatar_url( intval( $_REQUEST['dj'] ) ) ) . '" />';
		echo esc_html__( 'Message' ) . ' ' . strip_tags($display_name) . '||' . wp_kses($img, $allowed) . '||' . do_shortcode( '[contact-form-7 id="' . intval( get_option( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) ) . '" title="Message DJ"]' ) . '||' . intval( $_REQUEST['dj'] );
		die;
	}

	/**
	 * @usage Populate form for message to join Team
	 */
	function tzwrs_msgjoin() {
		$allowed  = array( 
			'img' => array(
				'src' => array(), 
				'class' => array(), 
				'width' => array(), 
				'itemprop' => array()
			)
		);
		$tzwrs_logo = intval( get_option('tzwrs_wrs_logo') );
		if ( $tzwrs_logo ) {
			$cover_data = wp_get_attachment_image_src( $tzwrs_logo );
			$cover = $cover_data[0];
		}
		else
		{
			$plugin = new Tz_Weekly_Radio_Schedule();
			$plugin_admin = new Tz_Weekly_Radio_Schedule_Admin( $plugin->get_plugin_name(), $plugin->get_version() );
			$cover = $plugin_admin->tzwrs_get_default_images()['square'];
		}
		$display_name = sprintf( 'Join the %1s Team', esc_attr(get_bloginfo()) );
		$mail_to = sanitize_email( get_option( 'admin_email' ));
		$img = '<img width="400" class="avatar avatar-400 photo" src="' . esc_url( $cover ) . '" />';
		echo strip_tags($display_name) . '||' . wp_kses($img, $allowed) . '||' . do_shortcode( '[contact-form-7 id="' . intval( get_option( 'tzwrs_wrs_cf7_join_modal_id' ) ) . '" title="Join the Team"]' ) . '||99999';
		die;
	}

	/**
	 * @usage Send message to DJ on air
	 */
	function tzwrs_sendmsgdj() {
		$user = new WP_User( intval( $_REQUEST['towhom'] ) );
		$headers[] = 'Bcc: ' . sanitize_email( get_option( 'admin_email' ) );
		$mail_to = sanitize_email( $user->user_email );
		wp_mail( $mail_to, 'New message from ' . sanitize_text_field( $_REQUEST['yourName'] ), sanitize_text_field( $_REQUEST['yourName'] ) . ' ' . esc_html__( "say's: ", "weekly_radio_schedule" ) . '
' . sanitize_textarea_field( $_REQUEST['yourMessage'] ) . ' 

Email me: ' . sanitize_email( $_REQUEST['yourEmail'] ), $headers);
		echo sanitize_text_field( $_REQUEST['yourName'] ) . '||' . $mail_to . '||' . sanitize_textarea_field( $_REQUEST['yourMessage'] );
		die;
	}

	/**
	 * @usage Send message to admin about new DJ request
	 */
	function tzwrs_sendmsgjoin() {
		$mail_to = sanitize_email( get_option( 'admin_email' ) );
		wp_mail( $mail_to, 'New DJ: ' . sanitize_text_field( $_REQUEST['yourName'] ), sanitize_text_field( $_REQUEST['yourName'] ) . ' ' . esc_html__( "say's: ", "weekly_radio_schedule" ) . sanitize_textarea_field( $_REQUEST['yourMessage'] . '
		My sounds: ' . esc_url_raw( $_REQUEST['yourSounds'] ) . '		
		Email me: ' . sanitize_email( $_REQUEST['yourEmail'] ) ) );
		echo sanitize_text_field( $_REQUEST['yourName'] ) . '||' . $mail_to . '||' . sanitize_textarea_field( $_REQUEST['yourMessage'] );
		die;
	}

	/**
	 * @usage Update info panel via AJAX
	 * @return mixed 
	 */
	function tzwrs_on_air_update() {
		global $current_user, $wpdb;
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$this_dj = 0;
		$cancel = $follow_html = '';
		$this_slot_data = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_a_slot_data( $this_hour );
		$slots_array = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slot_data();
		if ( $slots_array[$this_hour] > 0 )
		{
			if ( ( $slots_array[$this_hour] == $current_user->ID || Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() ) && $this_slot_data->add_dj >  0 ) {
				$cancel = '<span class="action this wrs_cancel_now h-' . $this_hour . '" data-hour="' . esc_attr($this_hour) . '" data-week="this" data-slotid="' . esc_attr(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slotid()) . '" data-act="wrs_cancel_now">' . esc_html__( 'Cancel' ) . '</span>';
			}
			$this_dj = intval($slots_array[$this_hour]);
			$user = get_user_by( 'ID', $this_dj );
			$wrs_weekly_show = '';
			$about = get_the_author_meta( 'description', $this_dj ) ? Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(trim(get_the_author_meta( 'description', $this_dj )), intval(get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) ) : strip_tags(get_bloginfo( 'description' ));
			$wrs_weekly_show .= '<span class="wrs_panel_desc">' . strip_tags($about) . '</span>';
		}
		if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ) {
			if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
				$PeepSoUser = PeepSoUser::get_instance($this_dj);
				$img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url($PeepSoUser->get_avatar()) . '" />';
			}
			else 
			{ 
				$img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url(get_avatar_url( $this_dj)) . '" />';
			}
			$if_onair = esc_html__( 'On Air:', 'weekly-radio-schedule' );
			$upnext = esc_html__( 'Up next: ', 'weekly-radio-schedule' ) . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_up_next());
		}
		else
		{
			$tzwrs_logo = get_option('tzwrs_wrs_logo');
			if ( $tzwrs_logo ):
				$cover_data = wp_get_attachment_image_src( $tzwrs_logo );
                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url($cover_data[0]) . '" title="' . esc_attr(get_bloginfo()) . '" />';
				else:

				$plugin = new Tz_Weekly_Radio_Schedule();
				$plugin_admin = new Tz_Weekly_Radio_Schedule_Admin( $plugin->get_plugin_name(), $plugin->get_version() );

                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url($plugin_admin->tzwrs_get_default_images()['square']) . '" title="' . esc_attr(get_bloginfo()) . '" />';
            endif;

			$if_onair = esc_html__( 'Join the Team:', 'weekly-radio-schedule' );
			$upnext = esc_html__( 'Up next: ', 'weekly-radio-schedule' ) . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_up_next());
			$wrs_weekly_show = '<span class="wrs_panel_desc offAir">' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_you_can_play()) . '</span>' . Tz_Weekly_Radio_Schedule_Public::tzwrs_join_the_team();
		}
			
		$emailResult = $wpdb->get_results(
			$wpdb->prepare(
				"select * from {$wpdb->prefix}wrs_author_subscribe where email=%s", $current_user->user_email
			)
		);

		$following_class = 'dj_follow';
		$following_value = 'Follow';
		$follow_html = '<div class="dj_follow_case"><input type="button" class="logged_out" value="' . esc_attr($following_value) . '" data-author="' . esc_attr($this_dj) . '" data-url="' . esc_url( wp_login_url( get_permalink() ) ) . '" /></div>';
		if ( isset( $emailResult[0] ) ) {
			if ($emailResult[0]->followed_authors != '') {
				$authorSubscribersArray = explode(",", $emailResult[0]->followed_authors);
				if ((in_array($this_dj, $authorSubscribersArray))) {
					$following_class = 'dj_following following';
					$following_value = 'Following';
				}
			}
		}
		if ( is_user_logged_in() ) {
			$follow_html = '<div class="dj_follow_case"><input type="button" class="' . strip_tags($following_class) . ' ' . strip_tags($this_dj) . '" value="' . esc_attr($following_value) . '" data-author="' . esc_attr($this_dj) . '" /></div>';
		}
		$outage = '<div class="wrs_on_air_wrapper"><span class="wrs_panel_pic">' . $cancel . $img . '</span>' . ( $this_dj ? '<h4 class="wrs_on_air_text">' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air()) . '</h4>' . $follow_html : '' ) . $wrs_weekly_show . '<span class="wrs_panel_desc">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_add_me_now() . '</span><h5 class="wrs_on_air_text un">' . $upnext . '</h5></div>';

		$outtica = '';
		$place = '';
		if ( is_plugin_active( 'easy-social-sharing/easy-social-sharing.php' ) ) {
			$place = 'panel';
			$outtica = '<span class="dr-icon dr-icon-share">
			' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ? '<span id="ess-main-wrapper" title="' . esc_html__( 'Share' ) . ' ' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air()) . '" class="' . ( $this_dj ? 'share_dj' : 'share_nodj' ) . '" data-dj="' . esc_attr($this_dj) . '">
			<span class="dashicons dashicons-share-alt2"></span>' . Tz_Weekly_Radio_Schedule_Public::tzwrs_generate_inline_icons($place) . '</span>' : '<span class="' . strip_tags(( $this_dj ? 'share_dj' : 'share_nodj' )) . '" data-dj="' . esc_attr($this_dj) . '">&nbsp;</span>' ) . '</span>';
		}

		// check for contact form 7 message DJ form id
		$vscf = false;
		if ( get_option( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) != 'Select a form' && get_option( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) != '' ) {
				$vscf = true;
		} 

		$allowed  = array( 
			'ul' => array(
				'class' => array()
			), 
			'li' => array(
				'class' => array()
			),  
			'nav' => array(
				'class' => array()
			), 
			'div' => array(
				'id' => array(), 
				'class' => array(), 
				'itemtype' => array(), 
				'data-url' => array(), 
				'data-author' => array(), 
				'itemscope' => array()
			), 
			'meta' => array(
				'itemprop' => array(), 
				'content' => array()
			), 
			'input' => array(
				'type' => array(), 
				'class' => array(), 
				'value' => array(), 
				'data-author' => array()
			), 
			'span' => array(
				'id' => array(), 
				'title' => array(),
				'itemprop' => array(), 
				'data-dj' => array(), 
				'data-week' => array(),
				'data-tip' => array(), 
				'data-act' => array(), 
				'data-slotid' => array(), 
				'data-hour' => array(), 
				'class' => array()
			), 
			'img' => array(
				'src' => array(), 
				'class' => array(), 
				'width' => array(), 
				'itemprop' => array(), 
				'title' => array()
			), 
			'h5' => array(
				'class' => array()
			),  
			'h4' => array(
				'class' => array()
			),  
			'a' => array(
				'class' => array(), 
				'href' => array(), 
				'rel' => array(), 
				'data-social-name' => array(), 
				'onclick' => array(), 
				'data-min-count' => array(), 
				'data-post-id' => array(), 
				'data-location' => array(), 
				'itemprop' => array(), 
				'title' => array()
			), 
			'strong' => array()
		);
		echo wp_kses($outage . '||<span class="panel_header"><span class="dr-icon dr-icon-message"></span><span class="dr-icon dr-icon-share"></span>
		<span class="panel_header_data two">
			<span class="wrs_tz">' . wp_timezone_string() . ' (GMT ' . ( get_option( 'gmt_offset' ) < 0 ? '' . get_option( 'gmt_offset' ) : '+' . get_option( 'gmt_offset' ) ) . ') </span>
			<span class="panel_header_on_air">' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ? 'On Air' : '&nbsp;' ) . '</span>
		</span></span>||
			' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() && $vscf ? '<a title="' . esc_html__( 'Message' ) . ' ' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air()) . '" href="#" onclick="document.getElementById(&quot;id05&quot;).style.display=&quot;block&quot;"><span id="on_air_panel" class="' . strip_tags(( $this_dj ? 'message_dj' : 'nomessage_dj' )) . '" data-dj="' . esc_attr($this_dj) . '"><span class="dashicons dashicons-format-chat"></span></span></a>' : '<span class="' . ( $this_dj ? 'message_dj' : 'nomessage_dj' ) . '" data-dj="' . esc_attr($this_dj) . '">&nbsp;</span>' ) . '||' . $outtica, $allowed);
		die();
	}

	function tzwrs_on_air_full_update()
	{
		$upnext = esc_html__( 'Up next: ', 'weekly-radio-schedule' ) . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_up_next());
		$outage = '<div class="wrs_on_air_wrapper">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air_full() . '<span class="wrs_panel_desc">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_add_me_now() .  '</span><h5 class="wrs_on_air_text un">' . $upnext . '</h5></div>';
		return $outage;
		die();
	}

	static function tzwrs_who_on_air_full() 
	{
		global $current_user;
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$this_dj = 0;
		$cancel = '';
		$wrs_weekly_show = '';
		$this_slot_data = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_a_slot_data( $this_hour );
		$slots_array = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slot_data();
		
		if ( $slots_array[$this_hour] > 0 )
		{
			if ( ( $slots_array[$this_hour] == $current_user->ID || Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() ) && $this_slot_data->add_dj >  0 ) 
			{
				$cancel = '<span class="action this wrs_cancel_now h-' . $this_hour . '" data-hour="' . esc_attr($this_hour) . '" data-week="this" data-slotid="' . esc_attr(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slotid()) . '" data-act="wrs_cancel_now">' . esc_html__( 'Cancel', 'weekly-radio-schedule' ) . '</span>'; 
			}
			$this_dj = intval($slots_array[$this_hour]);
			$user = get_user_by( 'ID', $this_dj );
			$wrs_weekly_show .= get_the_author_meta( 'description', $this_dj ) ? Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(trim(strip_tags(get_the_author_meta( 'description', $this_dj )) ), intval(get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) ) : strip_tags(get_bloginfo( 'description' ));
			if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
				$PeepSoUser = PeepSoUser::get_instance($this_dj);
				$img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url($PeepSoUser->get_avatar()) . '" />';
				return  '<span class="wrs_panel_pic">' . $cancel . '<img width="96" class="avatar avatar-96 photo" src="' . esc_url($PeepSoUser->get_avatar()) . '" /></span><h4 class="wrs_on_air_text">' . strip_tags($user->display_name) . '</h4><span class="wrs_panel_desc">' . $wrs_weekly_show . '</span>';
			}
			else 
			{ 
				return  '<span class="wrs_panel_pic">' . $cancel . '<img width="96" class="avatar avatar-96 photo" src="' . get_avatar_url( $this_dj ) . '" /></span>
			<h4 class="wrs_on_air_text">' . strip_tags($user->display_name) . '</h4><span class="wrs_panel_desc">' . $wrs_weekly_show . '</span>';
			}
		}
		else
		{
			$tzwrs_logo = get_option('tzwrs_wrs_logo');
			if ( $tzwrs_logo ):
				$cover_data = wp_get_attachment_image_src( $tzwrs_logo );
                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url($cover_data[0]) . '" title="' . esc_attr(get_bloginfo()) . '" />';
				else:

				$plugin = new Tz_Weekly_Radio_Schedule();
				$plugin_admin = new Tz_Weekly_Radio_Schedule_Admin( $plugin->get_plugin_name(), $plugin->get_version() );

                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url($plugin_admin->tzwrs_get_default_images()['square']) . '" title="' . esc_attr(get_bloginfo()) . '" />';
            endif;
			return  '<span class="wrs_panel_pic">' . $cancel . $img . '</span>
			<h4 class="wrs_on_air_text">' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air()) . '</h4><span class="wrs_panel_desc offAir">' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_you_can_play()) . '</span>';
		}
	}

	/**
	 * @usage Generate a single Team schedule slot
	 * @param mixed $atts
	 * @return mixed $schedule
	 */
	function tzwrs_slot_gen($atts)
	{
		global $current_user, $wpdb;
		$allowed  = array( 
			'div' => array(
				'id' => array(), 
				'class' => array(), 
				'itemtype' => array(), 
				'itemscope' => array()
			), 
			'span' => array(
				'data-slotID' => array(), 
				'title' => array(),
				'data-act' => array(), 
				'data-hour' => array(), 
				'data-slotid' => array(), 
				'data-week' => array(), 
				'class' => array()
			), 
			'td' => array(
				'data-week' => array(), 
				'data-hour' => array(),
				'style' => array(), 
				'class' => array()
			), 
			'i' => array(
			) 
		);
		
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$schedule = $today = $week = $the_hour = '';
		$atts = shortcode_atts( array( 'the_week' => $week, 'the_hour' => $the_hour ), $atts ); 	
		$terms_status = 1; // [slot_gen the_day="" the_week="" hour=""]
		$i = $hourofweek = $atts['the_hour'];
		//$dayofweek = $atts['the_day'];
		$whichweek = strip_tags($atts['the_week']);
		$table_prefix = $wpdb->prefix;
		$table_name = $table_prefix . 'wrs_' . $whichweek . '_week' ;

		$weekmark = 'data-week="' . esc_attr($whichweek) . '" ';
		$weekclass = ' ' . $whichweek . ' ';
		if ( is_user_logged_in() )
		{
			$this_person_id = $current_user->ID;
			$this_person_display_name = Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(strip_tags($current_user->display_name), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) );
			$mon_start = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_start_of_the_week()); //Timestamp of Monday Midnight
			
			$schedule .= '';
			$gmp_calc = $i > 23 ? $i %24 : $i;
			$slot_start = $gmp_calc == 0 ? $gmp_calc + 23 : $gmp_calc - 1;
			$gmp = '<span ' . $weekmark . 'data-hour="' . esc_attr($i) . '" class="schedule_slot_time ' . strip_tags($whichweek) . ' ho-' . $i . '">' . strip_tags($slot_start . ':00 - ' . $gmp_calc . ':00') . '</span>';
			
			$schedule_slot_data = $wpdb->get_row( 'SELECT s.* FROM ' . $table_name . ' AS s LEFT JOIN ' . $table_prefix . 'users AS u ON u.ID = s.user_id WHERE s.hour = ' . $i );

			$temp_user_id = Tz_Weekly_Radio_Schedule_Public::tzwrs_user_id_exists($schedule_slot_data->temp_user_id) ? intval($schedule_slot_data->temp_user_id) : 0;
			$firm_user_id = Tz_Weekly_Radio_Schedule_Public::tzwrs_user_id_exists($schedule_slot_data->user_id) ? intval($schedule_slot_data->user_id) : 0;
			$schedule_slot_data_hour = intval($schedule_slot_data->hour);
			$schedule_slot_data_slot_id = intval($schedule_slot_data->slot_id);
			$schedule_slot_data_add_dj = intval($schedule_slot_data->add_dj);

			$slot_class = 'class="row1 caseSpace-' . $i . '"';
			$past = 0; // future
			if ( $schedule_slot_data_hour < $this_hour && $whichweek == 'this' )
			{
				$past = 1; // past
			}
			if ( $schedule_slot_data_hour == $this_hour && $whichweek == 'this' )
			{ 
				$slot_class = ' class="on_air ' . strip_tags($whichweek) . ' h-' . $i . ' caseSpace-' . $i . '"'; 
			}
			
			$tempr_username = $temp_user_id > 0 ? strip_tags($wpdb->get_var($wpdb->prepare("SELECT display_name FROM " . $table_prefix . "users WHERE ID = %d", $temp_user_id))) : '';
			$username = $firm_user_id > 0 ? strip_tags($wpdb->get_var($wpdb->prepare("SELECT display_name FROM " . $table_prefix . "users WHERE ID = %d", $firm_user_id))) : '';
			
			if ( $tempr_username == 'Event' ) { $tempr_username = TZWRS_EVENT; }
			$max_length = intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ); // Max length for username
			$need_approval = intval( get_option( TZWRS_OPTION_NAME . '_wrs_need_approval' ) ); // Do DJs need approval?
			$tempr_username_str = Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop($tempr_username, $max_length);
			$username_str = Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop($username, $max_length);
			if ( $firm_user_id > 0 ) // If its a firm slot
			{
				if ( $temp_user_id > 1 ) //  Firm slot - Temp DJ 
				{
					if ( $schedule_slot_data_add_dj == 1 ) // Firm slot - Reg DJ Away - Temp DJ Asking
					{
						$schedule .= '<td ' . $weekmark . 'data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp;
						if ( current_user_can( 'run_tings' ) && $this_person_id != $temp_user_id ) // user is Board have management link and italic
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"><i>' . $tempr_username_str .'?</i></span>';
							//if ( $past < 1 ) 
							$schedule .= '<span class="action ' . $whichweek . ' wrs_approve h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_approve">' . TZWRS_APPROVE . '</span><span class="action ' . $whichweek . ' wrs_deny h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_deny">' . TZWRS_DENY . '</span>';
						}
						elseif ( current_user_can( 'add_self_to_schedule' ) || current_user_can( 'operate' ) ) // otherwise, if its a requesting DJ have cancel link and italic
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"><i>' . $tempr_username_str . '</i></span>';
							if ( $this_person_id == $temp_user_id )
							{
								if ( $past < 1 ) $schedule .= '<span class="action ' . $whichweek . ' wrs_cancel h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_cancel">' . TZWRS_CANCEL . '</span>';
							}
						}
						$schedule .= '</td>';
					}
					else if ( $schedule_slot_data_add_dj == 2 ) // Firm slot - Reg DJ Away - Temp DJ - Accepted
					{
						$schedule .= '<td ' . $weekmark . 'data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp;
						
						$clear_temp_img = '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
						
						if ( current_user_can( 'run_tings' ) || $temp_user_id == Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() )
						{
							$clear_temp_img = '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							if ( current_user_can( 'run_tings' ) && $temp_user_id != Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() )
							{
								$clear_temp_img = '<span class="action ' . $whichweek . ' wrs_clear_this_slot h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_clear_this_slot">' . TZWRS_CLEAR . '</span>';
							}
							else
							{
								$clear_temp_img = '<span class="action ' . $whichweek . ' wrs_cancel h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_cancel">' . TZWRS_CANCEL . '</span>';
							}
							$schedule .= '
							<span class="' . $whichweek . ' nameinslot h-' . $i . '">
							' . $tempr_username_str . '
							</span>' . $clear_temp_img;
						}
						else
						{
							$schedule .= '
							<span class="' . $whichweek . ' nameinslot h-' . $i . '">
							' . $tempr_username_str . '
							</span>
							<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
						}
						$schedule .= '</td>';
					}
					else
					{
						$schedule .= '<td ' . $weekmark . 'data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp . '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $tempr_username_str . '</span><span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span></td>';
					}
				}
				else //  Firm slot - No Temp DJ
				{
					$schedule .= '<td ' . $weekmark . ' data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp . '<div class="wrs_slot_div">';
					
					if ( $past == 0 && current_user_can( 'add_self_to_schedule' ) && $schedule_slot_data_add_dj == -1 && $terms_status == 1 ) // Firm slot away - No Temp DJ - not Past - person is dj and agreed terms
					{
						$dj_back_img = '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
						if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() != $firm_user_id && Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() && !Tz_Weekly_Radio_Schedule_Public::tzwrs_is_djoperator() )
						{
							$dj_back_img = '<span class="action ' . $whichweek . ' wrs_mark_dj_as_playing h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_playing">' . sprintf( esc_html__( 'Mark %s as Playing' ), Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop($username, $max_length ) ) . '</span>';
						}
						elseif ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() != $firm_user_id && Tz_Weekly_Radio_Schedule_Public::tzwrs_is_djoperator() )
						{
							$dj_back_img = '<span class="action ' . $whichweek . ' wrs_mark_dj_as_playing h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_playing">' . sprintf( esc_html__( 'Mark %s as Playing' ), Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop($username, $max_length ) ) . '</span><span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
						}
						elseif ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() == $firm_user_id )
						{
							$dj_back_img = '<span class="action ' . $whichweek . ' wrs_mark_me_as_playing h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_me_as_playing">' . TZWRS_BACK . '</span>';
						}
						elseif ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() != $firm_user_id )
						{
							$dj_back_img = '<span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
						}
						$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() == $firm_user_id ? $username_str : '' ) . '</span>' . $dj_back_img;
					}
					else if ( $past == 0 && current_user_can( 'add_self_to_schedule' ) && $schedule_slot_data_add_dj == -1 && $terms_status != 1 ) // Firm slot away - No Temp DJ - not Past - person is dj but not agreed terms
					{
						$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span><span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
					}
					else if ( $past == 0 && !current_user_can( 'add_self_to_schedule' ) && $schedule_slot_data_add_dj == -1 ) // Firm slot away - No Temp DJ - not Past - person is not dj  
					{
						if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() != $temp_user_id && Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() )
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span><span class="action ' . $whichweek . ' wrs_mark_dj_as_playing h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_playing">Mark ' . $username_str . ' as Playing</span>';
						}
					}
					else if ( $past == 0 && !current_user_can( 'add_self_to_schedule' ) ) // Firm slot - No Temp DJ - not Past - person is not dj  
					{
						$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $username_str . '</span>';
						if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() != $firm_user_id && Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() )
						{
							$schedule .= '<span class="action ' . $whichweek . ' wrs_mark_dj_as_away h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_away">' . sprintf( esc_html__( 'Mark %s as Away' ), Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop($username, $max_length ) ) . '</span>';
						}
						elseif ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() == $firm_user_id || Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() )
						{
							$schedule .= '<span class="action ' . $whichweek . ' wrs_mark_me_away h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_me_away">' . TZWRS_AWAY  . '</span>';
						}
					}
					else if ( $past == 1 ) // Firm slot - No Temp DJ - past 
					{
						$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $username_str . '</span>';
							$schedule .= '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
					}
					else
					{
						if ( $schedule_slot_data_add_dj > -1 ) // not away
						{
							$dj_away_img = '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() != $firm_user_id && Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() )
							{
								$dj_away_img = '<span class="action ' . $whichweek . ' wrs_mark_dj_as_away h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_away">' . sprintf( esc_html__( 'Mark %s as Away' ), Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop($username, $max_length ) ) . '</span>';
							}
							elseif ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() == $firm_user_id || Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() )
							{
								$dj_away_img = '<span class="action ' . $whichweek . ' wrs_mark_me_away h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_me_away">' . TZWRS_AWAY  . '</span>';
							}
							$schedule .= '
							<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $username_str . '</span>';
							$schedule .= $dj_away_img;
						}
						else 
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $username_str . '</span>
							<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
						}
					}
					$schedule .= '</td>';
				}
			}
			else // If its a not a firm slot
			{
				if ( $temp_user_id > 1 ) //  its a not a Firm slot - Temp DJ 
				{
					$schedule .= '
					<td ' . $weekmark . ' data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp;
					
					if ( $schedule_slot_data_add_dj == 1 ) // its a not a Firm slot - Temp DJ Asking
					{
						if ( current_user_can( 'run_tings' ) && $this_person_id != $temp_user_id) // user is Board have management link and italic
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"><i>' . $tempr_username_str . '?</i></span>';
							if ( $past < 1 ) $schedule .= '<span class="action ' . $whichweek . ' wrs_approve h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_approve">' . TZWRS_APPROVE . '</span><span class="action ' . $whichweek . ' wrs_deny h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_deny">' . TZWRS_DENY . '</span>';
						}
						elseif ( current_user_can( 'add_self_to_schedule' ) || current_user_can( 'operate' ) ) // otherwise, if its a requesting DJ have cancel link and italic
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"><i>' . $tempr_username_str . '</i></span>';
							if ( $this_person_id == $temp_user_id )
							{
								//if ( $past < 1 ) 
								$schedule .= '<span class="action ' . $whichweek . ' wrs_cancel h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_cancel">' . TZWRS_CANCEL . '</span>';
							}
							else
							{
								$schedule .= '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							}
						}
					}
					else if ( $schedule_slot_data_add_dj == 2 ) // its a not a Firm slot - Temp DJ - Accepted
					{
						if ( current_user_can( 'run_tings' ) || $temp_user_id == Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() )
						{
							if ( $tempr_username == TZWRS_EVENT )
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $tempr_username_str . '</span>
								<span class="action ' . $whichweek . ' wrs_clear_this_slot h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_clear_this_slot">' . TZWRS_CLEAR . '</span>';
							}
							elseif ( $temp_user_id == Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() )
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $tempr_username_str . '</span>';
								$schedule .= '<span class="action ' . $whichweek . ' wrs_cancel h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_cancel">' . TZWRS_CANCEL . '</span>';
							}
							else
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $tempr_username_str . '</span>
								<span class="action ' . $whichweek . ' wrs_clear_this_slot h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_clear_this_slot">' . TZWRS_CLEAR . '</span>';
							}
						}
						else
						{
							if ( $tempr_username == TZWRS_EVENT )
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $tempr_username_str . '</span>
								<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							}
							else
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $tempr_username_str . '</span>
								<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							}
						}
					}
					else // its a not a Firm slot - No Temp DJ
					{
						if ( $past < 1 && current_user_can( 'add_self_to_schedule' ) && $terms_status == 1 )
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span>';
							$schedule .= '<span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
						}
						else if ( $past < 1 && current_user_can( 'add_self_to_schedule' ) && $terms_status != 1 )
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span>
							<span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
						}
						else
						{

							$schedule .= '
							<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $username_str . '</span>
							<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
						}
					}
					$schedule .= '</td>';
				}
				else // empty slot
				{
					$schedule .= '<td ' . $weekmark . ' data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp;
					if ( current_user_can( 'add_self_to_schedule' ) ) // Link to add me
					{
						if ( $past < 1 && current_user_can( 'add_self_to_schedule' ) && $terms_status == 1 )
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span>
							<span class="action ' . $weekclass . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
						}
						else if ( $past < 1 && current_user_can( 'add_self_to_schedule' ) && $terms_status != 1 )
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span>
							<span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
						}
						else
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $username_str . '</span>
							<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
						}
					}
					else // Empty slot user is not dj
					{
						 $schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $username_str . '</span>
						 <span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
					}
				}
			}
		}
		return wp_kses( $schedule, $allowed );
	}

	/**
	 * @usage Get display name of who plays next and at what time
	 * @return mixed $user->display_name . ' @' . $when
	 */
	static function tzwrs_who_up_next()	{
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$next_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() ) + 1;
		$nex_dj = 0;
		$slots_array = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slot_data();
		if ( !empty($slots_array) ) {
			for ( $i = $next_hour; $i < 337; $i++ )
			{
				if ( isset( $slots_array[$this_hour] ) ) {
					if ( $slots_array[$this_hour] != $slots_array[$i] && $slots_array[$i] > 0 && $nex_dj == 0)
					{
						$when = ( ($i - 1 ) % 24) . '.00';
						$nex_dj = $slots_array[$i];
					}
				}
			}
		}
		$user = get_user_by( 'ID', $nex_dj ); 

		return $user ? $user->display_name . ' @' . $when : '';
	}

	/**
	 * @usage Generate array of scheduled slots
	 */
	static function tzwrs_get_slot_data() {
		$slot_data = array();
		global $wpdb;
		
		for ( $i = 1; $i < 169; $i++ )
		{
			$approval = intval( get_option( TZWRS_OPTION_NAME . '_wrs_need_approval' ) );
			$table_prefix = $wpdb->prefix;
			$table_name = $table_prefix . 'wrs_this_week' ;
			$row = $wpdb->get_row( 'SELECT s.* FROM ' . $table_name . ' AS s WHERE s.hour = ' . $i );

			$relevant_id = 0;
			if ( ( $row->user_id > 0 && $row->add_dj > -1 ) && $row->temp_user_id < 1 )
			{
				if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_user_id_exists( $row->user_id ))
				$relevant_id = intval($row->user_id);
			}
			elseif ( $row->temp_user_id > 0 && $row->add_dj > $approval )
			{
				if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_user_id_exists( $row->temp_user_id ))
				$relevant_id = intval($row->temp_user_id);
			}
			$slot_data[$i] = $relevant_id;
		}
		for ( $i = 1; $i < 169; $i++ )
		{
			$table_prefix = $wpdb->prefix;
			$table_name = $table_prefix . 'wrs_next_week' ;
			$row = $wpdb->get_row( 'SELECT s.* FROM ' . $table_name . ' AS s WHERE s.hour = ' . $i );

			$relevant_id = 0;
			if ( ( $row->user_id > 0 && $row->add_dj > -1 ) && $row->temp_user_id < 1 )
			{
				if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_user_id_exists( $row->user_id ))
				$relevant_id = intval($row->user_id);
			}
			elseif ( $row->temp_user_id > 0 && $row->add_dj > $approval )
			{
				if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_user_id_exists( $row->temp_user_id ))
				$relevant_id = intval($row->temp_user_id);
			}
			$slot_data[$i+168] = $relevant_id;
		}
		return $slot_data;
	}

	/**
	 * @usage Get display name of who is on air now
	 * @return string $user->display_name
	 */
	static function tzwrs_who_on_air() {
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$this_dj = 0;
		$slots_array = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slot_data();
		if ( $slots_array[$this_hour] > 0 )
		{
			$this_dj = $slots_array[$this_hour];
			$user = get_user_by( 'ID', $this_dj );
			return $user->display_name;
		}
		return;
	}

	/**
	 * @usage Get display timezone details
	 * @return string $user->display_name
	 */
	static function tzwrs_zone_details() {
		return '<span class="wrs_tz">' . wp_timezone_string() . ' (GMT ' . ( get_option( 'gmt_offset' ) < 0 ? '' . get_option( 'gmt_offset' ) : '+' . get_option( 'gmt_offset' ) ) . ') </span>';
	}

	/**
	 * @usage Generate html Add Me Now button for fly-out panel
	 * @return mixed
	 */
	static function tzwrs_add_me_now() {
		if ( current_user_can( 'add_self_to_schedule' ) ) {
			$approval = intval( get_option( TZWRS_OPTION_NAME . '_wrs_need_approval' ) );
			$SlotData = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_a_slot_data( intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() ) );
			if ( ( $SlotData->temp_user_id < 1 && $SlotData->user_id < 1 ) || ( $SlotData->add_dj == -1 && $SlotData->temp_user_id < 1 ) )
			{
				return '<span class="action this wrs_add_me_now h-' . intval($SlotData->hour) . '" data-hour="' . esc_html($SlotData->hour) . '" data-week="this"  data-act="wrs_add_me_now" data-slotid="' . esc_html($SlotData->slot_id) . '">' . esc_html__( 'Add me now', 'weekly-radio-schedule' ) . '</span>';
			}
			elseif ( ( $SlotData->temp_user_id > 0 ) && ( $SlotData->add_dj == $approval ) )
			{
				return '<span class="this wrs_pending_now h-' . intval($SlotData->hour) . '" data-hour="' . esc_attr($SlotData->hour) . '" data-week="this"  data-act="wrs_pending_now" data-slotid="' . intval($SlotData->slot_id) . '">' . esc_html__( 'Pending . . .', 'weekly-radio-schedule' ) . '</span>';
			}
			else
			{
				return '';
			}
		}
	}

	/**
	 * @usage Generate html Join the Team button for fly-out panel
	 * @return mixed
	 */
	static function tzwrs_join_the_team() {
		if ( current_user_can( 'add_self_to_schedule' ) ) {
			return;
		}
		if ( is_user_logged_in() ) {
			return '<span class="wrs_panel_desc"><span class="button this wrs_join_the_team" data-act="wrs_join_the_team" data-week="this"><a href="#" onclick="document.getElementById(&quot;id05&quot;).style.display=&quot;block&quot;" title="' . esc_html__( 'Join the Team', 'weekly-radio-schedule' ) . '">' . esc_html__( 'Join the Team', 'weekly-radio-schedule' ) . '</a></span></span>';
		}
		else {
			return '<span class="wrs_panel_desc"><div class="button_wrap"><a class="button" href="' . esc_url( wp_login_url( home_url( add_query_arg( [], $GLOBALS['wp']->request ) ) ) ) . '">' . esc_html__( 'Login' ) . '</a></div></span>';
		}
	}

	/**
	 * @usage Get slot data for specific hour
	 * @param int $hour
	 * @return mixed $row
	 */
	static function tzwrs_get_a_slot_data( $hour ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wrs_this_week';
		$row = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE hour = ' . intval($hour) );
		return $row;
	}

	/**
	 * @usage Generate info panel html
	 * @return mixed 
	 */
    static function tzwrs_on_air_panel($atts) {
		global $current_user, $wpdb;
		$place2 = '';

		$atts = shortcode_atts( array( 'place' => $place2 ), $atts ); 
		$place2 = $atts['place'];

		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$this_dj = 0;
		$cancel = '';
		$this_slot_data = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_a_slot_data( $this_hour );
		$slots_array = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slot_data();
		if ( $slots_array[$this_hour] > 0 )
		{
			if ( ( $slots_array[$this_hour] == $current_user->ID || Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() ) && $this_slot_data->add_dj >  0 ) {
				$cancel = '<span class="action this wrs_cancel_now h-' . intval($this_hour) . '" data-hour="' . esc_attr($this_hour) . '" data-week="this" data-slotid="' . esc_attr(intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slotid())) . '" data-act="wrs_cancel_now">' . esc_html__( 'Cancel' ) . '</span>'; 
			}
			$this_dj = intval($slots_array[$this_hour]);
			$user = get_user_by( 'ID', $this_dj );
			$wrs_weekly_show = '';
			$about = get_the_author_meta( 'description', $this_dj ) ? Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(trim(get_the_author_meta( 'description', $this_dj )), intval(get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) ) : strip_tags(get_bloginfo( 'description' ));
			$wrs_weekly_show .= '<span class="wrs_panel_desc">' . $about . '</span>';			
		}
		if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() )
		{
			if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
				$PeepSoUser = PeepSoUser::get_instance($this_dj);
				$img = '<img width="96" class="avatar avatar-96 photo" src="' . get_avatar_url( $PeepSoUser->get_avatar() ) . '" />';
			}
			else 
			{ 
				$img = '<img width="96" class="avatar avatar-96 photo" src="' . get_avatar_url( $this_dj ) . '" />';
			}

			$if_onair = esc_html__( 'On Air:', 'weekly-radio-schedule' );
			$upnext = esc_html__( 'Up next: ', 'weekly-radio-schedule' ) . Tz_Weekly_Radio_Schedule_Public::tzwrs_who_up_next();
		}
		else
		{
			$tzwrs_logo = intval( get_option('tzwrs_wrs_logo') );
			if ( $tzwrs_logo ):
				$cover_data = wp_get_attachment_image_src( $tzwrs_logo );
                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url( $cover_data[0] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
				else:

				$plugin = new Tz_Weekly_Radio_Schedule();
				$plugin_admin = new Tz_Weekly_Radio_Schedule_Admin( $plugin->get_plugin_name(), $plugin->get_version() );

                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url( $plugin_admin->tzwrs_get_default_images()['square'] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
            endif;
			
			$if_onair = esc_html__( 'Join the Team:', 'weekly-radio-schedule' );
			$upnext = esc_html__( 'Up next: ', 'weekly-radio-schedule' ) . Tz_Weekly_Radio_Schedule_Public::tzwrs_who_up_next();
			$wrs_weekly_show = '<span class="wrs_panel_desc offAir">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_you_can_play() . '</span>' . Tz_Weekly_Radio_Schedule_Public::tzwrs_join_the_team();
		}

		$outtica = '';
		$slotid = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slotid() );
		$table_name = $wpdb->prefix . 'wrs_this_week';
		$row = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE slot_id = ' . ($slotid) );
		if ( $row ) {
			$slot_user = intval($row->user_id);
			$temp_user_id = intval($row->temp_user_id);
			$add_dj = intval($row->add_dj);
			$current_user_id = '';
			if ( $slot_user && $add_dj == 0 ) 
			{
				$current_user_id = $slot_user;
			}
			if ( $temp_user_id && $add_dj > 0 )
			{
				$current_user_id = $temp_user_id;
			}
		}

		/**
		 * Detect plugin. For use on Front End only.
		 */
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$vscf = false;
		// check for plugin using plugin name
		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			$vscf = true;
		} 

		$shareouttica = $msgouttica = '';

		$msgouttica = ($vscf ? '<span class="dr-icon dr-icon-message">' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ? '<a title="' . esc_html__( 'Message' ) . ' ' . Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() . '" href="#"  onclick="document.getElementById(&quot;id05&quot;).style.display=&quot;block&quot;"><span id="on_air_panel" class="' . ( $current_user_id ? 'message_dj' : 'nomessage_dj' ) . '" data-dj="' . esc_attr($current_user_id) . '"><span class="dashicons dashicons-format-chat"></span></span></a>
		' : '
		<span class="' . ( $current_user_id ? 'message_dj' : 'nomessage_dj' ) . '" data-dj="' . esc_attr($current_user_id) . '">&nbsp;</span>' ) . '</span>' : '');

		if ( is_plugin_active( 'easy-social-sharing/easy-social-sharing.php' ) ) {
			$place = 'panel';
			$shareouttica = '<span class="dr-icon dr-icon-share">
			' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ? '<span id="ess-main-wrapper" title="' . esc_html__( 'Share' ) . ' ' . Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() . '" class="' . ( $current_user_id ? 'share_dj' : 'share_nodj' ) . '" data-dj="' . esc_attr($current_user_id) . '"><span class="dashicons dashicons-share-alt2"></span>' . Tz_Weekly_Radio_Schedule_Public::tzwrs_generate_inline_icons($place) . '</span>' : '<span class="' . ( $current_user_id ? 'share_dj' : 'share_nodj' ) . '" data-dj="' . intval($current_user_id) . '">&nbsp;</span>' ) . '</span>';
		}
		$follow_html = '';
		$emailResult = $wpdb->get_results(
			$wpdb->prepare(
				"select * from {$wpdb->prefix}wrs_author_subscribe where email=%s", $current_user->user_email
			)
		);

		$following_class = 'dj_follow';
		$following_value = 'Follow';
		$follow_html = '<div class="dj_follow_case"><input type="button" class="logged_out" value="' . esc_attr($following_value) . '" data-author="' . intval($this_dj) . '" data-url="' . esc_url( wp_login_url( get_permalink() ) ) . '" /></div>';
		if ( isset( $emailResult[0] ) ) {
			if ($emailResult[0]->followed_authors != '') {
				$authorSubscribersArray = explode(",", $emailResult[0]->followed_authors);
				if ((in_array($this_dj, $authorSubscribersArray))) {
					$following_class = 'dj_following following';
					$following_value = 'Following';
				}
			}
		}
		if ( is_user_logged_in() ) {
			$follow_html = '<div class="dj_follow_case"><input type="button" class="' . $following_class . ' ' . $this_dj . '" value="' . esc_attr($following_value) . '" data-author="' . esc_attr($this_dj) . '" /></div>';
		}

		if ( $place2 == 'crossroads' ) {

			$outtica .= '
			<span class="dr-label">
				<span class="panel_header">
					' . $msgouttica . $shareouttica . '
					<span class="panel_header_data one onez">
						<span class="wrs_tz">' . wp_timezone_string() . ' (GMT ' . ( get_option( 'gmt_offset' ) < 0 ? '' . get_option( 'gmt_offset' ) : '+' . get_option( 'gmt_offset' ) ) . ') </span>
						<span class="panel_header_on_air">' . ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() ? 'On Air' : '&nbsp;' ) . '</span>
					</span>
				</span>
			</span>';

			return  '<div class="dr-crossroads dr-menu-open"><div class="dr-trigger">
					' . $outtica . '</div><ul class="draw_one zero"><li><div id="wrs_updateOnairCrossroads" class="wrs_on_air"><div class="wrs_on_air_wrapper"><span class="wrs_panel_pic">' . $cancel . $img . '</span>' . ( $this_dj ? '<h4 class="wrs_on_air_text">' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air()) . '</h4>' . $follow_html : '' ) . $wrs_weekly_show . '<span class="wrs_panel_desc">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_add_me_now() .  '</span><h5 class="wrs_on_air_text un">' . $upnext . '</h5></div></div></li></ul></div>';			
		}
		else
		{
			return  '<div id="wrs_updateOnair" class="wrs_on_air"><div class="wrs_on_air_wrapper"><span class="wrs_panel_pic">' . $cancel . $img . '</span>' . ( $this_dj ? '<h4 class="wrs_on_air_text">' . strip_tags(Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air()) . '</h4>' . $follow_html : '' ) . $wrs_weekly_show . '<span class="wrs_panel_desc">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_add_me_now() .  '</span><h5 class="wrs_on_air_text un">' . $upnext . '</h5></div></div>';			
		}
	}

	/**
	 * @usage Generate info panel html
	 * @return mixed 
	 */
    function tzwrs_on_air_player_panel() {
		global $current_user;
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$this_dj = 0;
		$cancel = '';
		$this_slot_data = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_a_slot_data( $this_hour );
		$slots_array = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slot_data();
		if ( $slots_array[$this_hour] > 0 )
		{
			if ( ( $slots_array[$this_hour] == $current_user->ID || Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() ) && $this_slot_data->add_dj >  0 ) {
				$cancel = '<span class="action this wrs_cancel_now h-' . $this_hour . '" data-hour="' . esc_attr($this_hour) . '" data-week="this" data-slotid="' . esc_attr(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slotid()) . '" data-act="wrs_cancel_now">' . esc_html__( 'Cancel' ) . '</span>'; }
			$this_dj = intval($slots_array[$this_hour]);
			$user = get_user_by( 'ID', $this_dj );
			$wrs_weekly_show = '';
			$about = Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(trim(strip_tags( get_the_author_meta( 'description', $this_dj ))), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) );
			$wrs_weekly_show .= '<span class="wrs_panel_desc">' . $about . '</span>';			
		}
		if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() )
		{
			if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
				$PeepSoUser = PeepSoUser::get_instance($this_dj);
				$img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url( $PeepSoUser->get_avatar() ) . '" />';
			}
			else 
			{ 
				$img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url( get_avatar_url( $this_dj ) ) . '" />';
			}

			$if_onair = esc_html__( 'On Air:', 'weekly-radio-schedule' );
			$upnext = esc_html__( 'Up next: ', 'weekly-radio-schedule' ) . Tz_Weekly_Radio_Schedule_Public::tzwrs_who_up_next();
		}
		else
		{
			$tzwrs_logo = get_option('tzwrs_wrs_logo');
			if ( $tzwrs_logo ):
				$cover_data = wp_get_attachment_image_src( $tzwrs_logo );
                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url( $cover_data[0] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
				else:

				$plugin = new Tz_Weekly_Radio_Schedule();
				$plugin_admin = new Tz_Weekly_Radio_Schedule_Admin( $plugin->get_plugin_name(), $plugin->get_version() );

                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url( $plugin_admin->tzwrs_get_default_images()['square'] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
            endif;
			
			$if_onair = esc_html__( 'Join the Team:', 'weekly-radio-schedule' );
			$upnext = esc_html__( 'Up next: ', 'weekly-radio-schedule' ) . Tz_Weekly_Radio_Schedule_Public::tzwrs_who_up_next();
			$wrs_weekly_show = '<span class="wrs_panel_desc offAir">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_you_can_play() . '</span>' . Tz_Weekly_Radio_Schedule_Public::tzwrs_join_the_team();
		}

		return  '<div id="wrs_updateOnair" class="wrs_on_air"><div class="wrs_on_air_wrapper"><span class="wrs_panel_pic">' . $cancel . $img . '</span><h4 class="wrs_on_air_text">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_who_on_air() . '</h4>' . $wrs_weekly_show . '<span class="wrs_panel_desc">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_add_me_now() .  '</span><h5 class="wrs_on_air_text un">' . $upnext . '</h5></div></div>';
	}

	/**
	 * @usage Generate shortcode for html of publicly displayed On Air ticker
	 * @param mixed 
	 * @return mixed $wrs_weekly
	 */
	function tzwrs_on_air_ticker($atts) {
		return do_shortcode( '[on_air_panel place="crossroads"]');
	}

	/**
	 * @usage Generate shortcode for html of publicly displayed vertical representation of a DJs shows this week
	 * @param mixed 
	 * @return mixed $wrs_weekly
	 */
	function tzwrs_my_week_coming_up($atts) {
		global $wp, $current_user;
		$author_id = '';
		$atts = shortcode_atts( array( 'textsize' => intval( get_option(TZWRS_OPTION_NAME . '_wrs_max_desc_chars') ), 'profile' => 0, 'id' => 1 ), $atts );
		
		if ( $atts['profile'] == 1 ) {
			if ( isset($wp->query_vars['author_name']) ) {
				$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($wp->query_vars['author_name']));
			}
			elseif ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
				$url = PeepSoUrlSegments::get_instance();
				$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($url->get(1)));
			}
		}
		else
		{
			if ( isset($_REQUEST['selecteddj']) ) {
				$author_id = intval( $_REQUEST['selecteddj'] );
				$user = new WP_User( $author_id );
				$img = '<img style="width:100%" class="avatar avatar-600 photo" src="' . get_avatar_url( intval( $_REQUEST['selecteddj'] ) ) . '" />';
			}
			elseif ( isset($atts['id']) ) {
				if ( $this->tzwrs_user_id_exists($atts['id']) )
					{
						$author_id = intval($atts['id']);
						$user = new WP_User( $author_id );
						$img = '<img style="width:100%" class="avatar avatar-600 photo" src="' . get_avatar_url( $atts['id'] ) . '" />';
					}
					else
					{
						return;
					}
			}
			
		}
		$textsize = intval($atts['textsize']);

		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		if ( $this_hour > 168 )
		{
			$this_hour = $this_hour - 168;
		}
		$last_dj_id = 0;
		
		$day_mark = gmdate('l', current_time( 'timestamp', 1 ));
		$text_for_day = array('','sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
		
		$dj_array = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_weekly_djs();
		$wrs_weekly = '';
		$wrs_weekly_shows = array();
		$wrs_weekly_shows[0] = $wrs_weekly_shows[1] = $wrs_weekly_shows[2] = $wrs_weekly_shows[3] = $wrs_weekly_shows[4] = $wrs_weekly_shows[5] = $wrs_weekly_shows[6] = '';
		$counter = 0;
		for ( $i = 1; $i < 168; $i++)
		{
			$wrs_weekly_show = '';
			if ( !empty($dj_array[$i]['user_id']) ) {  } else { $dj_array[$i]['user_id'] = 0; }
			if ( !empty($dj_array[$i]['temp_user_id']) ) {  } else { $dj_array[$i]['temp_user_id'] = 0; }
			
			if ( ($dj_array[$i]['user_id'] > 0) && ($dj_array[$i]['add_dj'] == 0) && ( $dj_array[$i]['user_id'] != $last_dj_id ) )
			{
				$whenever = '';
				if ($dj_array[$i]['hour'] % 24 == gmdate("H",current_time( 'timestamp' )))
				{
					$hour_text = 'On Air';
				}
				else
				{
					$hour_text = Tz_Weekly_Radio_Schedule_Public::tzwrs_time_ago2(gmdate("Y",current_time( 'timestamp' )).'-'.gmdate("m",current_time( 'timestamp' )).'-'.gmdate("d",current_time( 'timestamp' )).' '.(($dj_array[$i]['hour'] % 24)-5).':00:00');
				}
				if ( $whenever == 'Tomorrow' )
				{
					$hour_text = tzwrs_time_ago2(date("Y", (current_time('timestamp')+(24*60*60))).'-'.date("m", (current_time('timestamp')+(24*60*60))).'-'.date("d", (current_time('timestamp')+(24*60*60))).' '.(($dj_array[$i]['hour'] % 24)-5).':00:00');
				}
				
				$day_numeric = (intval ($dj_array[$i]['hour'] / 24))+1;
				$pic_width = intval( get_option( 'tzwrs_wrs_default_avatar_size') );

				if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
					$PeepSoUser = PeepSoUser::get_instance(intval($dj_array[$i]['user_id']));
					$profile_url = esc_url( $PeepSoUser->get_profileurl() );
				}
				else 
				{
					$profile_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $dj_array[$i]['user_id'] ) ) );
				}
				
				$wrs_weekly_show .= '<div class="schedule_item item"><span class="user_id">' . ucfirst($text_for_day[$day_numeric]) . ' ' . $dj_array[$i]['hour'] % 24 . ':00</span>';
				$wrs_weekly_show .= '</div>';
				$last_dj_id = intval($dj_array[$i]['user_id']);
			}
			elseif ( ($dj_array[$i]['temp_user_id'] > 0) && ( $dj_array[$i]['temp_user_id'] != $last_dj_id ) ) 
			{
				
				if ($dj_array[$i]['hour'] % 24 == gmdate("H",current_time( 'timestamp' )))
				{
					$hour_text = 'On Air';
				}
				else
				{
					$hour_text = Tz_Weekly_Radio_Schedule_Public::tzwrs_time_ago2(gmdate("Y",current_time( 'timestamp' )).'-'.gmdate("m",current_time( 'timestamp' )).'-'.gmdate("d",current_time( 'timestamp' )).' '.(($dj_array[$i]['hour'] % 24)-5).':00:00');
				}

				if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
					$PeepSoUser = PeepSoUser::get_instance($dj_array[$i]['temp_user_id']);
					$profile_url = esc_url( $PeepSoUser->get_profileurl() );
				}
				else 
				{
					$profile_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $dj_array[$i]['temp_user_id'] ) ) );
				}

				$day_numeric = (intval($dj_array[$i]['hour'] / 24))+1;

				$wrs_weekly_show .= '<div class="schedule_item item"><span class="temp_user_id">' . ucfirst($text_for_day[$day_numeric]) . ' ' . $dj_array[$i]['hour'] % 24 . ':00</span>';
				$wrs_weekly_show .= '</div>';
				$last_dj_id = intval($dj_array[$i]['temp_user_id']);
			}


			if ( 
				( $dj_array[$i]['user_id'] > 0 && $dj_array[$i]['user_id'] == $author_id ) 
				|| 
				( $dj_array[$i]['temp_user_id'] > 0 && $dj_array[$i]['temp_user_id'] == $author_id ) 
			) {
				if ( !empty( $dj_array[$i]['hour'] ) ) {
					if ($dj_array[$i]['hour'] < 25) {
						$wrs_weekly_shows[0] .= $wrs_weekly_show;
					} elseif ($dj_array[$i]['hour'] < 49) {
						$wrs_weekly_shows[1] .= $wrs_weekly_show;
					} elseif ($dj_array[$i]['hour'] < 73) {
						$wrs_weekly_shows[2] .= $wrs_weekly_show;
					} elseif ($dj_array[$i]['hour'] < 97) {
						$wrs_weekly_shows[3] .= $wrs_weekly_show;
					} elseif ($dj_array[$i]['hour'] < 121) {
						$wrs_weekly_shows[4] .= $wrs_weekly_show;
					} elseif ($dj_array[$i]['hour'] < 145) {
						$wrs_weekly_shows[5] .= $wrs_weekly_show;
					} elseif ($dj_array[$i]['hour'] < 169) {
						$wrs_weekly_shows[6] .= $wrs_weekly_show;
					}
				}

			}
		}
		if ( $atts['profile'] != 1 ) {
			$user = new WP_User( $author_id );
			if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
				$PeepSoUser = PeepSoUser::get_instance($author_id);
				$img = '<img style="width:100%" class="avatar avatar-600 photo" src="' . esc_url($PeepSoUser->get_avatar()) . '" />';
			}
			else
			{
				$img = '<img style="width:100%" class="avatar avatar-600 photo" src="' .  esc_url(get_avatar_url( $author_id )) . '" />';
			}

			$my_shows_class = $atts['id'] ? 'myshows ' : '';
			$wrs_weekly .= '<div class="daily_schedule_slots ' . $my_shows_class . 'section-inner medium"><h3>' . strip_tags($user->display_name) . '</h3>' . $img . '<span class="my_show_text">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(trim(strip_tags(get_the_author_meta( 'description', $author_id ))), $textsize ) . '</span>';
		}
		else 
		{
			if ( $wrs_weekly_shows[0] || $wrs_weekly_shows[1] || $wrs_weekly_shows[2] || $wrs_weekly_shows[3] || $wrs_weekly_shows[4] || $wrs_weekly_shows[5] || $wrs_weekly_shows[6] ) {
				$wrs_weekly .= '<div class="daily_schedule_slots djprofile section-inner medium"><h3>' . esc_html__( 'Shows', 'weekly-radio-schedule' ) . '</h3>';
			}
		}
		if ( $wrs_weekly_shows[0] ) {
			$counter++;
			$wrs_weekly .= '<div class="schedule_items chosen">';
			$wrs_weekly .= $wrs_weekly_shows[0];
			$wrs_weekly .= '</div>';
		}
		
		if ( $wrs_weekly_shows[1] ) {
			$counter++;
			$wrs_weekly .= '<div class="schedule_items chosen">';
			$wrs_weekly .= $wrs_weekly_shows[1];
			$wrs_weekly .= '</div>';
		}
			
		if ( $wrs_weekly_shows[2] ) {
			$counter++;
			$wrs_weekly .= '<div class="schedule_items chosen">';
			$wrs_weekly .= $wrs_weekly_shows[2];
			$wrs_weekly .= '</div>';
		}
			
		if ( $wrs_weekly_shows[3] ) {
			$counter++;
			$wrs_weekly .= '<div class="schedule_items chosen">';
			$wrs_weekly .= $wrs_weekly_shows[3];
			$wrs_weekly .= '</div>';
		}
			
		if ( $wrs_weekly_shows[4] ) {
			$counter++;
			$wrs_weekly .= '<div class="schedule_items chosen">';
			$wrs_weekly .= $wrs_weekly_shows[4];
			$wrs_weekly .= '</div>';
		}
			
		if ( $wrs_weekly_shows[5] ) {
			$counter++;
			$wrs_weekly .= '<div class="schedule_items chosen">';
			$wrs_weekly .= $wrs_weekly_shows[5];
			$wrs_weekly .= '</div>';
		}
			
		if ( $wrs_weekly_shows[6] ) {
			$counter++;
			$wrs_weekly .= '<div class="schedule_items chosen">';
			$wrs_weekly .= $wrs_weekly_shows[6];
			$wrs_weekly .= '</div>';
		}
		if ( $wrs_weekly_shows[0] || $wrs_weekly_shows[1] || $wrs_weekly_shows[2] || $wrs_weekly_shows[3] || $wrs_weekly_shows[4] || $wrs_weekly_shows[5] || $wrs_weekly_shows[6] ) {
			$wrs_weekly .= '</div>';
		}
		return $wrs_weekly;
	}

	/**
	 * @usage Get slot_id for current hour
	 * @return int $row->slot_id
	 */
	static function tzwrs_get_slotid() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wrs_this_week';
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		$row = $wpdb->get_row( 'SELECT slot_id FROM ' . $table_name . ' WHERE hour = ' . ($this_hour) );
		if ( $row ) {
			return intval($row->slot_id);
		}
		else {
			return;
		}
	}

	/**
	 * @usage Determine if current user is Team member
	 * @return bool 
	 */
	static function tzwrs_user_is_team($user_id) {
		if ( user_can( $user_id, 'add_self_to_schedule' ) )
		{
			return true;
		}
	}

	/**
	 * @usage Determine if current user is Team member
	 * @return bool 
	 */
	static function tzwrs_is_team() {
		if ( current_user_can( 'add_self_to_schedule' ) )
		{
			return true;
		}
	}

	/**
	 * @usage Generate shortcode for Team Schedule
	 * @return mixed
	 */
	static function tzwrs_schedule_page_shortcode() {
		$outage = '';
		if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_is_team() || current_user_can( 'operate' ))
		{
			$open0 = $open1 = $open2 = $open3 = $open4 = $open5 = $open6 = 0;

			switch ( gmdate( 'w', current_time( 'timestamp', 1 ) ) )
			{
				case 0:
					$open0 = 1;
				break;
				case 1:
					$open1 = 1;
				break;
				case 2:
					$open2 = 1;
				break;
				case 3:
					$open3 = 1;
				break;
				case 4:
					$open4 = 1;
				break;
				case 5:
					$open5 = 1;
				break;
				case 6:
					$open6 = 1;
				break;
			}
			$outage .= do_shortcode( '[tabby title="' . esc_html__( 'Sunday' ) . '" open="' . $open0 . '"][daily_schedule the_week="this" the_day="1"][tabby title="' . esc_html__( 'Monday' ) . '" open="' . $open1 . '"][daily_schedule the_week="this" the_day="2"][tabby title="' . esc_html__( 'Tuesday' ) . '" open="' . $open2 . '"][daily_schedule the_week="this" the_day="3"][tabby title="' . esc_html__( 'Wednesday' ) . '" open="' . $open3 . '"][daily_schedule the_week="this" the_day="4"][tabby title="' . esc_html__( 'Thursday' ) . '" open="' . $open4 . '"][daily_schedule the_week="this" the_day="5"][tabby title="' . esc_html__( 'Friday' ) . '" open="' . $open5 . '"][daily_schedule the_week="this" the_day="6"][tabby title="' . esc_html__( 'Saturday' ) . '" open="' . $open6 . '"][daily_schedule the_week="this" the_day="7"][tabbyending] [tabby title="' . esc_html__( 'Sunday' ) . '"][daily_schedule the_week="next" the_day="1"][tabby title="' . esc_html__( 'Monday' ) . '"][daily_schedule the_week="next" the_day="2"][tabby title="' . esc_html__( 'Tuesday' ) . '"][daily_schedule the_week="next" the_day="3"][tabby title="' . esc_html__( 'Wednesday' ) . '"][daily_schedule the_week="next" the_day="4"][tabby title="' . esc_html__( 'Thursday' ) . '"][daily_schedule the_week="next" the_day="5"][tabby title="' . esc_html__( 'Friday' ) . '"][daily_schedule the_week="next" the_day="6"][tabby title="' . esc_html__( 'Saturday' ) . '"][daily_schedule the_week="next" the_day="7"][tabbyending]' );
		}
		return $outage . do_shortcode( '[tabbed_coming_up]' );
	}

	/**
	 * @usage Generate html of dropdown of DJs for displaying shows via AJAX
	 * @return mixed
	 */
	static function tzwrs_teamsters() {
		global $wpdb;
		$users = get_users( [ 'role__in' => [ 'wrs_dj', 'wrs_djoperator', 'wrs_manager' ] ] );
		$djform = '<form data-week="this" method="post" style="position:relative;">
			<select id="selector_shows" class="djs this select-css">
				<option value="">-- ' . esc_html__( 'SELECT', 'weekly-radio-schedule' ) . ' --</option>
				<option value="0">' . esc_html__( 'What\'s On', 'weekly-radio-schedule' ) . '</option>';
			foreach ($users as $user) {
			  $djform .= '<option value="' . esc_attr($user->ID) . '">' . strip_tags($user->display_name) . '</option>';
			}
			$djform .= '</select>
			<span class="dashicons dashicons-arrow-down-alt2"></span>
			<div class="load-state teamstas">' . do_shortcode( '[shows_today title="' . esc_html__( 'What\'s On', 'weekly-radio-schedule' ) . '" pic_size="' . intval( get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size') ) . '" textsize="' . intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars') ) . '" pastshows="1"]' ) . '</div>
		</form>';
		
		return $djform;
	}

	/**
	 * @usage Generate html of dropdown of DJs for Managers to allocate to slots via AJAX
	 * @return mixed
	 */
	function tzwrs_dj_dropdown() {
		global $wpdb;
		if ( current_user_can( 'run_tings' ) ) {
			$users = get_users( [ 'role__in' => [ 'wrs_manager', 'wrs_dj', 'wrs_djoperator', 'wrs_operator' ] ] );

			$djform = '<form data-week="' . esc_attr( $_REQUEST['week'] ) . '" method="post">
			  <select id="selector_' . intval( $_REQUEST['hour_id'] ) . '" data-hour="' . esc_attr( $_REQUEST['hour_id'] ) . '" class="djs ' . strip_tags( $_REQUEST['week'] ) . '">
				<option value="">--' . esc_html__( 'SELECT DJ', 'weekly-radio-schedule' ) . '--</option>
				<option value="-1">' . esc_html__( 'CANCEL', 'weekly-radio-schedule' ) . '</option>
				<option value="0"></option>';
				foreach ($users as $user) {
				  $djform .= '<option value="' . esc_attr($user->ID) . '">' . strip_tags($user->display_name) . '</option>';
				}
			  $djform .= '</select>
			  <div class="load-state"></div>
			  <span class="' . strip_tags( $_REQUEST['week'] ) . ' pretext_' . intval( $_REQUEST['hour_id'] ) . '" style="display:none;">' . wp_unslash($_REQUEST['pretext']) . '</span>
			  <span class="' . strip_tags( $_REQUEST['week'] ) . ' precontent_' . intval( $_REQUEST['hour_id'] ) . '" style="display:none;">' . wp_unslash($_REQUEST['precontent']) . '</span>
			</form>';
			echo $djform;
		}	
		die();
	}

	/**
	 * @usage Update of shows display via AJAX
	 */
	function tzwrs_updateShows() {
		global $wpdb, $current_user;
		if ( $_REQUEST['selecteddj'] == 0 ) {
			echo do_shortcode( '[shows_today title="What\'s On" pic_size="' . intval( get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size') ) . '" max_chars="' . intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars') ) . '" pastshows="1"]' );
		}
		else 
		{
			echo do_shortcode('[my_week_coming_up profile="' . intval( $_REQUEST['selecteddj'] ) . '" picsize="' . intval( get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size') ) . '" textsize="' . intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) . '"]' );
		}
		die();
	}

	/**
	 * @usage Update db after Manager allocates slots via AJAX
	 */
	function tzwrs_updateSlot() {
		global $wpdb, $current_user;
		if ( $_REQUEST['selecteddj'] < 0 )
		{
			$up_date = intval( $_REQUEST['hour_id'] );
		}
		elseif ( $_REQUEST['selecteddj'] == 0 )
		{
			$table_name = $_REQUEST['week'] == 'this' ? $wpdb->prefix . 'wrs_this_week' : $wpdb->prefix . 'wrs_next_week';
			$up_date = intval( $_REQUEST['hour_id'] );
			$zero = 0;
			
			$rows_affected = $wpdb->update(
				$table_name, 
				array( 
					'user_id' => $zero,
					'temp_user_id' => $zero,
					'add_dj' => $zero
				), 
				array(
					'hour' => $up_date
				) 
			);
			
			if ( false === $rows_affected ) {
				//
			} 
			else
			{
				$action_class = 'wrs_empty';
				if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_is_team() )
				{
					$action_class = 'wrs_add_me_here';
				}
				else
				{
					//
				}
			}
		}
		else
		{
			$table_name = $_REQUEST['week'] == 'this' ? $wpdb->prefix . 'wrs_this_week' : $wpdb->prefix . 'wrs_next_week';

			$author_obj = get_user_by('id', intval( $_REQUEST['selecteddj'] ) );
			$this_dj = $author_obj->display_name;
			$up_date = intval( $_REQUEST['hour_id'] );

			$rows_affected = $wpdb->query(
			$wpdb->prepare( "UPDATE " . $table_name . " SET user_id = %d WHERE hour = $up_date;", intval( $_REQUEST['selecteddj'] ) )
			);

			if ( false === $rows_affected ) {
				//
			} else {
				if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() < $up_date || $_REQUEST['week'] != 'this' ) // i.e. future
				{
					if ( $_REQUEST['selecteddj'] == $current_user->data->ID )
					{
						//
					}
					else
					{
						//
					}
				}
				else
				{
					if ( $_REQUEST['selecteddj'] == $current_user->data->ID )
					{
						//
					}
					else
					{
						//
					}
				}
			}
		}
		echo do_shortcode( '[slot_gen the_week="' . esc_attr( $_REQUEST['week'] ) . '" the_hour="' . $up_date . '"]' );
		die();
	}

	/**
	 * @usage Confirmation before destructive change to Schedule via AJAX
	 */
	function tzwrs_confirm_first() {
		global $wpdb, $current_user;
		
		$weekmark = esc_attr( $_REQUEST['week'] );
		$table_name = $wpdb->prefix . 'wrs_' . $weekmark . '_week';
		$this_dj_id = intval($current_user->ID);
		$name = strip_tags( $_REQUEST['name'] );
		$slot_id = intval( $_REQUEST['slotid'] );
		$act = esc_attr( $_REQUEST['act'] );
		$hour_id = intval( $_REQUEST['hour_id'] );
		
		$hourmark = 'hx';
		if ( $weekmark === 'this' ) { $hourmark = 'h'; }
		$row = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE slot_id = ' . $slot_id );
		$slot_user = intval($row->user_id);
		$temp_user_id = intval($row->temp_user_id);
		$add_dj = intval($row->add_dj);
		if ( $act == 'wrs_deny' )
		echo '<td data-week="' . esc_attr($weekmark) . '" data-hour="' . esc_attr($hour_id) . '" class="row1 warn ' . strip_tags($weekmark) . ' ' . strip_tags($name) . ' caseSpace-' . intval($hour_id) . '" style="text-align:center">' . sprintf( esc_html__( 'Deny %s? ' ), $name ) . '<span class="decide"><span class="action ' . strip_tags($weekmark) . ' wrs_yes h-' . intval($hour_id) . '" data-hour="' . esc_attr($hour_id) . '" data-week="' . esc_attr($weekmark) . '" data-slotid="' . intval($slot_id) . '" data-act="wrs_yes">' . esc_html__( 'Yes', 'weekly-radio-schedule' ) . '</span><span class="action ' . strip_tags($weekmark) . ' wrs_no h-' . intval($hour_id) . '" data-hour="' . intval($hour_id) . '" data-week="' . esc_attr($weekmark) . '" data-slotid="' . intval($slot_id) . '" data-act="wrs_no">' . esc_html__( 'No', 'weekly-radio-schedule' ) . '</span></span></td>';
		if ( $act == 'wrs_clear_this_slot' )
		echo '<td data-week="' . strip_tags($weekmark) . '" data-hour="' . intval($hour_id) . '" class="row1 warn ' . strip_tags($weekmark) . ' ' . strip_tags($name) . ' caseSpace-' . intval($hour_id) . '" style="text-align:center">' . sprintf( esc_html__( 'Clear %s? ' ), $name ) . '<span class="action ' . strip_tags($weekmark) . ' wrs_yes h-' . intval($hour_id) . '" data-hour="' . intval($hour_id) . '" data-week="' . strip_tags($weekmark) . '" data-slotid="' . intval($slot_id) . '" data-act="wrs_yes">' . esc_html__( 'Yes', 'weekly-radio-schedule' ) . '</span><span class="action ' . strip_tags($weekmark) . ' wrs_no h-' . intval($hour_id) . '" data-hour="' . intval($hour_id) . '" data-week="' . strip_tags($weekmark) . '" data-slotid="' . intval($slot_id) . '" data-act="wrs_no">' . esc_html__( 'No', 'weekly-radio-schedule' ) . '</span></td>';

		die();
	}

	/**
	 * Determines if post thumbnail can be displayed.
	 */
	static function tzwrs_twentynineteen_can_show_post_thumbnail() {
		return apply_filters( 'twentynineteen_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() );
	}

	/**
	 * @usage Displays an optional post thumbnail. Wraps the post thumbnail in an anchor element on index views, or a div element when on single views.
	 * @return mixed 
	 */
	static function tzwrs_twentynineteen_post_thumbnail() {

		if ( ! Tz_Weekly_Radio_Schedule_Public::tzwrs_twentynineteen_can_show_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
		?>

			<figure class="post-thumbnail" template="plugin">
				<?php //the_post_thumbnail(); 
				if ( has_post_thumbnail() ) {
					the_post_thumbnail();
				}
				else {
					$plugin = new Tz_Weekly_Radio_Schedule_Home();
					$plugin_admin = new Tz_Weekly_Radio_Schedule_Home_Admin( $plugin->get_plugin_name(), $plugin->get_version() );
					echo '<img src="' . esc_url($plugin_admin->tzwrs_get_default_images()['bg']) . '" />';
				}
				?>
			</figure><!-- .post-thumbnail -->

		<?php
		else :
		?>

		<figure class="post-thumbnail" template="plugin">
			<a class="post-thumbnail-inner" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php the_post_thumbnail( 'post-thumbnail' ); ?>
			</a>
		</figure>

		<?php
		endif; // End is_singular().
	}

	/**
	 * @usage Generate shortcode for html of list of all schedule cells for a particular day of the week for use in Team schedule
	 * @param mixed 
	 * @return mixed 
	 */
	function tzwrs_dj_daily_schedule($atts) {
		global $current_user, $wpdb;

		$this_hour = intval( $this->tzwrs_get_this_hour() );
		$schedule = '';
		$today = '';
		$week = 'this';
		$atts = shortcode_atts( array( 'the_day' => $today, 'the_week' => $week ), $atts );
		if ( $atts['the_day'] == '' )
			return;
			   
		$terms_status = 1;
		$requires_approval = 0;
		$dayofweek = intval($atts['the_day']);
		$whichweek = strip_tags($atts['the_week']);
		$table_prefix = $wpdb->prefix;
		$table_name = $table_prefix . 'wrs_' . $whichweek . '_week' ;

		$weekmark = 'data-week="' . esc_html($whichweek) . '" ';
		$weekclass = ' ' . $whichweek . ' ';
		if ( is_user_logged_in() )
		{
			$this_person_id = $current_user->ID;
			$this_person_display_name = $this->tzwrs_str_stop(strip_tags($current_user->display_name), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) );
			$mon_start = intval($this->tzwrs_start_of_the_week()); //Timestamp of Monday Midnight
			switch ( $whichweek ) {
				case "this":
					$slot_start = $mon_start - (60*60*24) + ( ( $dayofweek - 1 ) * (60*60*24) ); // Timestamp for that day's 1st slot
					break;
				case "next":
					$slot_start = $mon_start - (60*60*24) + ( 7 * 24 * 60 *60 ) + ( ( $dayofweek - 1 ) * (60*60*24) ); /// Timestamp for that day's 1st slot
					break;
				case "upcoming":
					$slot_start = $mon_start - (60*60*24) + ( 14 * 24 * 60 *60 ) + ( ( $dayofweek - 1 ) * (60*60*24) ); // Timestamp for that day's 1st slot
					break;
				case "forthcoming":
					$slot_start = $mon_start - (60*60*24) + ( 21 * 24 * 60 *60 ) + ( ( $dayofweek - 1 ) * (60*60*24) ); // Timestamp for that day's 1st slot
					break;
				default:
					$slot_start = $mon_start - (60*60*24) + ( ( $dayofweek - 1 ) * (60*60*24) ); // Timestamp for that day's 1st slot
			}
			
			$the_day = gmdate("D", $slot_start);
			$the_date = gmdate("d", $slot_start);
			
			$schedule .= '<table class="wrstable" id="wrstable-' . $whichweek . '">
			<tr>
				<td class="day_month" colspan="7">' . gmdate("jS F", $slot_start) . '</td>
			</tr>
			<tr>';
			//for ( $i = 24*$dayofweek+1; $i < 24*$dayofweek+25; $i++ )
			for ( $i = (($dayofweek*24)-23); $i < (($dayofweek*24)+1); $i++)
			{
				$gmp_calc = $i > 23 ? $i %24 : $i;
				$slot_start = $gmp_calc == 0 ? $gmp_calc + 23 : $gmp_calc - 1;
				$gmp = '<span ' . $weekmark . 'data-hour="' . $i . '" class="schedule_slot_time ' . $whichweek . ' ho-' . $i . '">' .$slot_start . ':00 - ' . $gmp_calc . ':00</span>';
				
				$schedule_slot_data = $wpdb->get_row( 'SELECT s.* FROM ' . $table_name . ' AS s LEFT JOIN ' . $table_prefix . 'users AS u ON u.ID = s.user_id WHERE s.hour = ' . $i );

				$temp_user_id = $this->tzwrs_user_id_exists($schedule_slot_data->temp_user_id) ? intval($schedule_slot_data->temp_user_id) : 0;
				$firm_user_id = $this->tzwrs_user_id_exists($schedule_slot_data->user_id) ? intval($schedule_slot_data->user_id) : 0;
				$schedule_slot_data_hour = intval($schedule_slot_data->hour);
				$schedule_slot_data_slot_id = intval($schedule_slot_data->slot_id);
				$schedule_slot_data_add_dj = intval($schedule_slot_data->add_dj);

				$slot_class = 'class="row1 ' . strip_tags($whichweek) . ' caseSpace-' . $i . '"';
				$past = 0; // future
				if ( $schedule_slot_data_hour < $this_hour && $whichweek == 'this' )
				{
					$past = 1; // past
				}
				if ( $schedule_slot_data_hour == $this_hour && $whichweek == 'this' )
				{ 
					$slot_class = ' class="on_air ' . strip_tags($whichweek) . ' h-' . $i . ' caseSpace-' . $i . '"'; 
				}
				
				$tempr_username = $temp_user_id > 0 ? strip_tags($wpdb->get_var($wpdb->prepare("SELECT display_name FROM " . $table_prefix . "users WHERE ID = %d", $temp_user_id))) : '';
				$username = $firm_user_id > 0 ? strip_tags($wpdb->get_var($wpdb->prepare("SELECT display_name FROM " . $table_prefix . "users WHERE ID = %d", $firm_user_id))) : '';
				
				if ( $tempr_username == 'Event' ) { $tempr_username = TZWRS_EVENT; }
				$max_length = intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ); // Max length for username
				if ( $firm_user_id > 0 ) // If its a firm slot
				{
					if ( $temp_user_id > 1 ) //  Firm slot - Temp DJ 
					{
						if ( $schedule_slot_data_add_dj == 1 ) // Firm slot - Reg DJ Away - Temp DJ Asking
						{
							$schedule .= '<td ' . $weekmark . 'data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp;
							if ( current_user_can( 'run_tings' ) && $this_person_id != $temp_user_id ) // user is Manager - italic
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"><i>' . $this->tzwrs_str_stop($tempr_username, $max_length) .'?</i></span>';
								//if ( $past < 1 ) 
									$schedule .= '<span class="action ' . $whichweek . ' wrs_approve h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_approve">' . TZWRS_APPROVE . '</span><span class="action ' . $whichweek . ' wrs_deny h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_deny">' . TZWRS_DENY . '</span>';
							}
							elseif ( current_user_can( 'add_self_to_schedule' ) || current_user_can( 'operate' ) ) // otherwise, if its a requesting DJ have cancel link and italic
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"><i>' . $this->tzwrs_str_stop($tempr_username, $max_length) . '</i></span>';
								if ( $this_person_id == $temp_user_id )
								{
									//if ( $past < 1 ) 
									$schedule .= '<span class="action ' . $whichweek . ' wrs_cancel h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_cancel">' . TZWRS_CANCEL . '</span>';
								}
								else
								{
									$schedule .= '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
								}
							}
							$schedule .= '</td>';
						}
						else if ( $schedule_slot_data_add_dj == 2 ) // Firm slot - Reg DJ Away - Temp DJ - Accepted
						{
							$schedule .= '<td ' . $weekmark . 'data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp;
							
							$clear_temp_img = '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							
							if ( current_user_can( 'run_tings' ) || $temp_user_id == $this->tzwrs_get_current_id() )
							{
								$clear_temp_img = '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
								if ( current_user_can( 'run_tings' ) && $temp_user_id != $this->tzwrs_get_current_id() )
								{
									$clear_temp_img = '<span class="action ' . $whichweek . ' wrs_clear_this_slot h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_clear_this_slot">' . TZWRS_CLEAR . '</span>';
								}
								else
								{
									$clear_temp_img = '<span class="action ' . $whichweek . ' wrs_cancel h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_cancel">' . TZWRS_CANCEL . '</span>';
								}
								$schedule .= '
								<span class="' . $whichweek . ' nameinslot h-' . $i . '">
								' . $this->tzwrs_str_stop($tempr_username, $max_length) . '
								</span>' . $clear_temp_img;
							}
							else
							{
								$schedule .= '
								<span class="' . $whichweek . ' nameinslot h-' . $i . '">
								' . $this->tzwrs_str_stop($tempr_username, $max_length) . '
								</span>
								<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							}
							$schedule .= '</td>';
						}
						else
						{
							$schedule .= '<td ' . $weekmark . 'data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp . '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($tempr_username, $max_length) . '</span><span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span></td>';
						}
					}
					else //  Firm slot - No Temp DJ
					{
						$schedule .= '<td ' . $weekmark . ' data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp . '<div class="wrs_slot_div">';
						
						if ( $past == 0 && current_user_can( 'add_self_to_schedule' ) && $schedule_slot_data_add_dj == -1 && $terms_status == 1 ) // Firm slot away - No Temp DJ - not Past - person is dj and agreed terms
						{
							$dj_back_img = '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							if ( $this->tzwrs_get_current_id() != $firm_user_id && Tz_Weekly_Radio_Schedule_Public::tzwrs_is_operator() && !Tz_Weekly_Radio_Schedule_Public::tzwrs_is_djoperator() )
							{
								$dj_back_img = '<span class="action ' . $whichweek . ' wrs_mark_dj_as_playing h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_playing">Mark ' . $this->tzwrs_str_stop($username, $max_length) . ' as Playing</span>';
							}
							elseif ( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_current_id() != $firm_user_id && $this->tzwrs_is_djoperator() )
							{
								$dj_back_img = '<span class="action ' . $whichweek . ' wrs_mark_dj_as_playing h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_playing">Mark ' . $this->tzwrs_str_stop($username, $max_length) . ' as Playing</span><span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
							}
							elseif ( $this->tzwrs_get_current_id() == $firm_user_id )
							{
								$dj_back_img = '<span class="action ' . $whichweek . ' wrs_mark_me_as_playing h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_me_as_playing">' . TZWRS_BACK . '</span>';
							}
							elseif ( $this->tzwrs_get_current_id() != $firm_user_id )
							{
								$dj_back_img = '<span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
							}
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . ( $this->tzwrs_get_current_id() == $firm_user_id ? $this->tzwrs_str_stop($username, $max_length) : '' ) . '</span>' . $dj_back_img;
						}
						else if ( $past == 0 && current_user_can( 'add_self_to_schedule' ) && $schedule_slot_data_add_dj == -1 && $terms_status != 1 ) // Firm slot away - No Temp DJ - not Past - person is dj but not agreed terms
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span><span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
						}
						else if ( $past == 0 && !current_user_can( 'add_self_to_schedule' ) && $schedule_slot_data_add_dj == -1 ) // Firm slot away - No Temp DJ - not Past - person is not dj  
						{
							if ( $this->tzwrs_get_current_id() != $temp_user_id && $this->tzwrs_is_operator() )
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span><span class="action ' . $whichweek . ' wrs_mark_dj_as_playing h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_playing">Mark ' . $this->tzwrs_str_stop($username, $max_length) . ' as Playing</span>';
							}
						}
						else if ( $past == 0 && !current_user_can( 'add_self_to_schedule' ) ) // Firm slot - No Temp DJ - not Past - person is not dj  
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($username, $max_length) . '</span>';
							if ( $this->tzwrs_get_current_id() != $firm_user_id && $this->tzwrs_is_operator() )
							{
								$schedule .= '<span class="action ' . $whichweek . ' wrs_mark_dj_as_away h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_away">' . sprintf( esc_html__( 'Mark %s as Away' ), $this->tzwrs_str_stop($username, $max_length ) ) . '</span>';
							}
							elseif ( $this->tzwrs_get_current_id() == $firm_user_id || $this->tzwrs_is_operator() )
							{
								$schedule .= '<span class="action ' . $whichweek . ' wrs_mark_me_away h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_me_away">' . TZWRS_AWAY  . '</span>';
							}
						}
						else if ( $past == 1 ) // Firm slot - No Temp DJ - past 
						{
							$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($username, $max_length) . '</span>';
								$schedule .= '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
						}
						else
						{
							if ( $schedule_slot_data_add_dj > -1 ) // not away
							{
								$dj_away_img = '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
								if ( $this->tzwrs_get_current_id() != $firm_user_id && $this->tzwrs_is_operator() )
								{
									$dj_away_img = '<span class="action ' . $whichweek . ' wrs_mark_dj_as_away h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_dj_as_away">' . sprintf( esc_html__( 'Mark %s as Away' ), $this->tzwrs_str_stop($username, $max_length ) ) . '</span>';
								}
								elseif ( $this->tzwrs_get_current_id() == $firm_user_id || $this->tzwrs_is_operator() )
								{
									$dj_away_img = '<span class="action ' . $whichweek . ' wrs_mark_me_away h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_mark_me_away">' . TZWRS_AWAY  . '</span>';
								}
								$schedule .= '
								<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($username, $max_length) . '</span>';
								$schedule .= $dj_away_img;
							}
							else 
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($username, $max_length) . '</span>
								<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							}
						}
						$schedule .= '</td>';
					}
				}
				else // If its a not a firm slot
				{
					if ( $temp_user_id > 1 ) //  its a not a Firm slot - Temp DJ 
					{
						$schedule .= '
						<td ' . $weekmark . ' data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp;
						
						if ( $schedule_slot_data_add_dj == 1 ) // its a not a Firm slot - Temp DJ Asking
						{
							if ( current_user_can( 'run_tings' ) && $this_person_id != $temp_user_id) // user is Manager and italic
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"><i>' . $this->tzwrs_str_stop($tempr_username, $max_length) . '?</i></span>';
								//if ( $past < 1 ) 
								$schedule .= '<span class="action ' . $whichweek . ' wrs_approve h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_approve">' . TZWRS_APPROVE . '</span><span class="action ' . $whichweek . ' wrs_deny h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_deny">' . TZWRS_DENY . '</span>';
							}
							elseif ( current_user_can( 'add_self_to_schedule' ) || current_user_can( 'operate' ) ) // otherwise, if its a requesting DJ have cancel link and italic
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"><i>' . $this->tzwrs_str_stop($tempr_username, $max_length) . '</i></span>';
								if ( $this_person_id == $temp_user_id )
								{
									//if ( $past < 1 ) 
									$schedule .= '<span class="action ' . $whichweek . ' wrs_cancel h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_cancel">' . TZWRS_CANCEL . '</span>';
								}
								else
								{
									$schedule .= '<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
								}
							}
						}
						else if ( $schedule_slot_data_add_dj == 2 ) // its a not a Firm slot - Temp DJ - Accepted
						{
							if ( current_user_can( 'run_tings' ) || $temp_user_id == $this->tzwrs_get_current_id() )
							{
								if ( $tempr_username == TZWRS_EVENT )
								{
									$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($tempr_username, $max_length) . '</span>
									<span class="action ' . $whichweek . ' wrs_clear_this_slot h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_clear_this_slot">' . TZWRS_CLEAR . '</span>';
								}
								elseif ( $temp_user_id == $this->tzwrs_get_current_id() )
								{
									$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($tempr_username, $max_length) . '</span>
									<span class="action ' . $whichweek . ' wrs_cancel h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_cancel">' . TZWRS_CANCEL . '</span>';
								}
								else
								{
									$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($tempr_username, $max_length) . '</span>
									<span class="action ' . $whichweek . ' wrs_clear_this_slot h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_clear_this_slot">' . TZWRS_CLEAR . '</span>';
								}
							}
							else
							{
								if ( $tempr_username == TZWRS_EVENT )
								{
									$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($tempr_username, $max_length) . '</span>
									<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
								}
								else
								{
									$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($tempr_username, $max_length) . '</span>
									<span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
								}
							}
						}
						else // its a not a Firm slot - No Temp DJ
						{
							if ( $past < 1 && current_user_can( 'add_self_to_schedule' ) && $terms_status == 1 )
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span>';
								$schedule .= '<span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
							}
							else if ( $past < 1 && current_user_can( 'add_self_to_schedule' ) && $terms_status != 1 )
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span>
								<span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
							}
							else
							{

								$schedule .= '
								<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($username, $max_length) . '</span>
								<span class="k action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							}
						}
						$schedule .= '</td>';
					}
					else // empty slot
					{
						$schedule .= '<td ' . $weekmark . ' data-hour="' . $i . '" ' . $slot_class . ' style="text-align:center">' . $gmp;
						if ( current_user_can( 'add_self_to_schedule' ) ) // Link to add me
						{
							if ( $past < 1 && current_user_can( 'add_self_to_schedule' ) && $terms_status == 1 )
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span>
								<span class="action ' . $weekclass . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
							}
							else if ( $past < 1 && current_user_can( 'add_self_to_schedule' ) && $terms_status != 1 )
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '"></span>
								<span class="action ' . $whichweek . ' wrs_add_me_here h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_add_me_here">' . TZWRS_ADDME . '</span>';
							}
							else
							{
								$schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($username, $max_length) . '</span>
								<span class="l action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
							}
						}
						else // Empty slot user is not dj
						{
							 $schedule .= '<span class="' . $whichweek . ' nameinslot h-' . $i . '">' . $this->tzwrs_str_stop($username, $max_length) . '</span>
							 <span class="action ' . $whichweek . ' wrs_empty h-' . $i . '" data-hour="' . $i . '" ' . $weekmark . ' data-slotID="' . esc_attr($schedule_slot_data_slot_id) . '" data-act="wrs_empty"></span>';
						}
					}
				}
				if ( $schedule_slot_data_hour %24 == 0 ) // If its end of day
				{
					$schedule .= '';
				}
				else if ( $schedule_slot_data_hour %6 == 0 ) // If its end row
				{
					$schedule .= '</tr><tr>';
				}
			}
			$schedule .= '</tr></table>';
		}
		return $schedule;
	}
	
	/**
	 * @usage Determine whether a user_id exists
	 * @return bool 
	 */
	static function tzwrs_user_id_exists( $user_id ) {
		global $wpdb;
		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->users WHERE ID = %d", $user_id ) );
		return empty( $count ) || 1 > $count ? false : true;
	}

	/**
	 * @usage Determine if current user is a Manager
	 * @return bool 
	 */
	static function tzwrs_is_manager() {
		if ( current_user_can( 'run_tings' ) )
		{
			return true;
		}
	}

	/**
	 * @usage Determine if current user is a djoperator
	 * @return bool 
	 */
	static function tzwrs_is_djoperator() {
		if ( current_user_can( 'operate' ) && current_user_can( 'add_self_to_schedule' ) )
		{
			return true;
		}
	}

	/**
	 * @usage Determine if current user is an operator
	 * @return bool 
	 */
	static function tzwrs_is_operator() {
		if ( current_user_can( 'operate' ) )
		{
			return true;
		}
	}

	/**
	 * @usage Get current user ID
	 * @return int $current_user->data->ID
	 */
	function tzwrs_get_current_id() {
		global $current_user;
		return $current_user->data->ID;
	}

	/**
	 * @usage Trigger the script if it has not already been triggered on the page
	 */
	function tzwrs_tabbytrigger() {
		global $post;

		static $tabbytriggered = FALSE; // static so only sets the value the first time it is run
		
		if ( !empty($post->post_content) ) { 
			if ( $tabbytriggered == FALSE && ( strpos($post->post_content,'[tab') !== false || strpos($post->post_content,'[sch') !== false || strpos($post->post_content,'[faqs') !== false ) ) {

				echo "\n" . "<script>jQuery(document).ready(function($) { RESPONSIVEUI.responsiveTabs(); })</script>" .  "\n \n";

			$tabbytriggered = TRUE;
			}
		}
	}

	/**
	 * @usage SHORTCODE FOR TABBY use [tabby]
	 * @return mixed 
	 */
	function tzwrs_shortcode_tabby( $atts, $content = null ) {

		// initialise $firsttab flag so we can tell whether we are building the first tab

		global $reset_firsttab_flag;
		static $firsttab = TRUE;

		if ($GLOBALS["reset_firsttab_flag"] === TRUE) {
			$firsttab = TRUE;
			$GLOBALS["reset_firsttab_flag"] = FALSE;
		}

		// extract title & whether open
		extract(shortcode_atts(array(
			"title" => '',
			"open" => '',
			"icon" => '',
		), $atts, 'tabbytab'));

		$tabtarget = sanitize_title_with_dashes( remove_accents( wp_kses_decode_entities( $title ) ) );

		//initialise urltarget
		$urltarget = '';

		//grab the value of the 'target' url parameter if there is one
		if ( isset ( $_REQUEST['target'] ) ) {
			$urltarget = sanitize_title_with_dashes( $_REQUEST['target'] );
		}

		//	Set Tab Panel Class - add active class if the open attribute is set or the target url parameter matches the dashed version of the tab title
		$tabcontentclass = "tabcontent";

		if ( isset( $class ) ) {
			$tabcontentclass .= " " . $class . "-content";
		}

		if ( ( $open ) || ( isset( $urltarget ) && ( $urltarget == $tabtarget ) ) ) {
			$tabcontentclass .= " responsive-tabs__panel--active";
		}

		$addtabicon = '';

		if ( $icon ) {
			$addtabicon = '<span class="fa fa-' . $icon . '"></span>';
		}

	// test whether this is the first tab in the group
		if ( $firsttab ) {

	// Set flag so we know subsequent tabs are not the first in the tab group
			$firsttab = FALSE;

	// Build output if we are making the first tab
			return '<div class="responsive-tabs">' . "\n" . '<h2 class="tabtitle">' . $addtabicon . $title . '</h2>' . "\n" . '<div class="' . $tabcontentclass . '">' . "\n";
		}

		else {
	// Build output if we are making a non-first tab
			return  "\n" . '</div><h2 class="tabtitle">' . $addtabicon . $title . '</h2>' . "\n" . '<div class="' . $tabcontentclass . '">' . "\n";
		}
	}

	/**
	 * @usage SHORTCODE TO BE USED AFTER FINAL TABBY TAB use [tabbyending]
	 * @return mixed 
	 */
	function tzwrs_shortcode_tabbyending( $atts, $content = null ) {

		// add screen & print-only styles
		if ( wp_style_is( 'tabby', $list = 'registered' ) ) {
			wp_enqueue_style( 'tabby' );
		}

		wp_enqueue_style( 'tabby-print' );

		wp_enqueue_script('tabby', TZWRS_DIRECTORY_URL . '/public/js/tabby.js', array('jquery'), $this->version, true);

		

		$GLOBALS["reset_firsttab_flag"] = TRUE;

		global $cc_add_tabby_css;
		$cc_add_tabby_css = true;

		return '</div></div>';
	}

	/**
	 * @usage Generate timestamp for midnight Sat/Sun
	 * @return int timestamp
	 */
	static function tzwrs_start_of_the_week() {
		$local_time  = current_datetime();
		$current_time = $local_time->getTimestamp() + $local_time->getOffset();
		$day = gmdate("d",$current_time);
		$month = gmdate("m",$current_time);
		$year = gmdate("Y",$current_time);
		$w = gmdate('w',mktime(0,0,0,$month,$day,$year));
		return mktime(0, 0, 0, $month, $day-$w+1, $year);
	}

	/**
	 * @usage Generate shortcode for html of publicly displayed tabbed representation of scheduled shows this week
	 * @param int $pic_width
	 * @return mixed
	 */
	function tzwrs_tabbed_week_coming_up($pic_width) {
		$this_hour = $this->tzwrs_get_this_hour();

		if ( $this_hour > 168 )
		{
			$this_hour = $this_hour - 168;
		}
		$last_dj_id = 0;
		
		$day_mark = gmdate( 'l', current_time( 'timestamp', 1 ) );
		$text_for_day = array('','sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
		
		$dj_array = $this->tzwrs_get_weekly_djs();
		$wrs_weekly = '';
		$wrs_weekly_shows = array();
		$wrs_weekly_shows[0] = $wrs_weekly_shows[1] = $wrs_weekly_shows[2] = $wrs_weekly_shows[3] = $wrs_weekly_shows[4] = $wrs_weekly_shows[5] = $wrs_weekly_shows[6] = '';

		$mon_start = $this->tzwrs_start_of_the_week(); //Timestamp of Monday Midnight
		$whichweek = "this";
		switch ( $whichweek ) {
			case "this":
				$slot_start = $mon_start - (60*60*24) + ( ( 1 - 1 ) * (60*60*24) ); //Timestamp of Sunday Midnight - Summer
				break;
			case "next":
				$slot_start = $mon_start - (60*60*24) + ( 7 * 24 * 60 *60 ) + ( ( $atts['the_day'] - 1 ) * (60*60*24) ); //Timestamp of Sunday Midnight - Summer
				break;
			case "upcoming":
				$slot_start = $mon_start - (60*60*24) + ( 14 * 24 * 60 *60 ) + ( ( $atts['the_day'] - 1 ) * (60*60*24) ); //Timestamp of Sunday Midnight - Summer
				break;
			case "forthcoming":
				$slot_start = $mon_start - (60*60*24) + ( 21 * 24 * 60 *60 ) + ( ( $atts['the_day'] - 1 ) * (60*60*24) ); //Timestamp of Sunday Midnight - Summer
				break;
			default:
				$slot_start = $mon_start - (60*60*24) + ( ( $dayofweek - 1 ) * (60*60*24) ); //Timestamp of Sunday Midnight - Summer
		}
			
		for ( $i = 1; $i < 168; $i++)
		{
			$onAir = $i == $this_hour ? ' onAir' : '';
			$wrs_weekly_show = '';
			if ( !empty($dj_array[$i]['user_id']) ) {  } else { $dj_array[$i]['user_id'] = 0; }
			if ( !empty($dj_array[$i]['temp_user_id']) ) {  } else { $dj_array[$i]['temp_user_id'] = 0; }
			if ( $dj_array[$i]['user_id'] != 0 && $dj_array[$i]['temp_user_id'] != 0 ) 
			{
				$amal = $dj_array[$i]['temp_user_id'];
			}
			else
			{
				$amal = $dj_array[$i]['user_id'] + $dj_array[$i]['temp_user_id'];
			}
			$PeepSoUser = false;
			if ( $amal > 0 )
			{
				if ( ( $amal == $last_dj_id ) && $last_dj_hour == $dj_array[$i]['hour'] - 1 )
				{
					$last_dj_id = $amal;
					$last_dj_hour = $dj_array[$i]['hour'];
				} 
				else 
				{
					$day_numeric = (intval ($dj_array[$i]['hour'] / 24))+1;
					$pic_width = intval( get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size') );
					if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
						$slot_user = get_user_by( 'slug', $dj_array[$i]['user_nicename']);
						$PeepSoUser = PeepSoUser::get_instance($slot_user->ID);
						$profile_url = esc_url( $PeepSoUser->get_profileurl() );
						$img = '<img itemprop="image" width="' . $pic_width . '" class="avatar avatar-' . $pic_width . '" src="' . $PeepSoUser->get_avatar() . '" />';
					}
					else 
					{
						$slot_user = get_user_by( 'slug', $dj_array[$i]['user_nicename']);
						$profile_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $slot_user->ID ) ) );
						$img = '<img itemprop="image" width="' . $pic_width . '" class="avatar avatar-' . $pic_width . ' photo" src="' . get_avatar_url( $slot_user->ID ) . '" />';
					}
					$the_time = $slot_start + ($dj_array[$i]['hour'] * 60 * 60);
					$time_valid = mktime(0, 0, 0, date("n"), 1);
					
					$durAtion = 1;
					$plural = '';
					$currentHour = $i;
					$length = 10;

					if ( $i <= 168 ) 
					{
						while ( isset( $dj_array[$currentHour+1]['user_id'] ) )
						{
							$currentHour++;
							if ( $dj_array[$currentHour]['user_id']+$dj_array[$currentHour]['temp_user_id'] == $amal )
							{
								$plural = 's';
								$durAtion++;
							}
						}
					}

					$wrs_weekly_show .= '
					<div itemscope="2222" itemtype="https://schema.org/MusicEvent" class="schedule_item item ' . $onAir . '">
						<meta itemprop="description" content="' . esc_html__( 'Radio Show', 'weekly-radio-schedule' ) . '" />';
						if ( $PeepSoUser ) { 
							$wrs_weekly_show .= '<meta itemprop="image" content="' . $PeepSoUser->get_cover() . '" />';
							$PeepSoUser = PeepSoUser::get_instance($slot_user->ID);
							$args = array('post_status'=>'publish');
							$PeepSoUser->profile_fields->load_fields($args, 'profile');
							$fields = $PeepSoUser ? $PeepSoUser->profile_fields->get_fields($slot_user->ID) : '';
							$show_field_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_show_name_field_id' ) );
							$show_name = isset($fields['peepso_user_field_' . $show_field_id]) ? ( $fields['peepso_user_field_' . $show_field_id]->value ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( $fields['peepso_user_field_' . $show_field_id]->value, intval(get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' );
						}
						else 
						{
							$userbyamal = get_user_by('id', $amal);
							$show_name = $userbyamal->user_show_name ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( $userbyamal->user_show_name, intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : 'Radio Show';
						}
						$wrs_weekly_show .= '
						<meta itemprop="name" content="' . esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( $dj_array[$i]['username'], intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) ) . ' - ' . $show_name . '" />
						<meta itemprop="eventStatus" content="EventScheduled" />
						<meta itemprop="eventAttendanceMode" content="https://schema.org/OnlineEventAttendanceMode" />
						<span class="meta" itemprop="organizer" itemscope="" itemtype="https://schema.org/Organization">
							<meta itemprop="name" content="'. esc_attr(get_bloginfo()) . '" />
							<meta itemprop="address" content="' . strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) ) . '" />
							<meta itemprop="url" content="' . get_home_url() . '" />
						</span>
						<span class="meta" itemprop="location" itemscope="" itemtype="https://schema.org/MusicVenue">
							<meta itemprop="name" content="'. esc_attr(get_bloginfo()) . '"/>
							<meta itemprop="address" content="' . strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) ) . '"/>
	  					</span>
						<span class="meta" itemprop="offers" itemscope="" itemtype="https://schema.org/Offer">
							<meta itemprop="price" content="0" />
							<meta itemprop="priceCurrency" content="USD" />
							<link itemprop="availability" href="https://schema.org/InStock" />
							<meta itemprop="url" content="' . esc_url( get_option( TZWRS_OPTION_NAME . '_wrs_audio_address' ) ) . '">
							<meta itemprop="validFrom" content="' . gmdate("c", $time_valid) . '">
						</span>
						<span itemprop="startDate" content="' . gmdate("c", $the_time) . '" class="">
						' . ucfirst($text_for_day[$day_numeric]) . ' ' . $dj_array[$i]['hour'] % 24 . ':00
						</span><span class="durAtion">' . $durAtion . 'hr' . $plural . '</span>
						<meta itemprop="endDate" content="' . gmdate("c", $the_time+($durAtion*60*60)) . '">
						<meta itemprop="name" content="' . esc_html( $this->tzwrs_str_stop( $dj_array[$i]['username'], intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) ) . '">
						<a href="' . $profile_url . '">
							<h4 class="daily_schedule_title">
							' . esc_html( $this->tzwrs_str_stop( $dj_array[$i]['username'], intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) ) . '
							</h4>
						</a>
						<div class="" itemprop="performer" itemscope="" itemtype="https://schema.org/Person">
						<meta itemprop="name" content="' . esc_html( $this->tzwrs_str_stop( $dj_array[$i]['username'], intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) ) . '" />
							<a itemprop="url" href="' . $profile_url . '">
							' . $img . '
							</a>';

					$about = $this->tzwrs_str_stop(trim(strip_tags( get_the_author_meta( 'description', $slot_user->ID ) ) ), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) );

					$wrs_weekly_show .= '<span>' . $show_name . '</span><div itemprop="description" class="show_snip">' . $about . '</div>';

					$wrs_weekly_show .= '</div></div>';
					$last_dj_id = $amal;
					$last_dj_hour = $dj_array[$i]['hour'];
				}
			}

			if ( !empty($dj_array[$i]['hour']) ) 
			{
				if ($dj_array[$i]['hour'] < 25) 
				{
					$wrs_weekly_shows[0] .= $wrs_weekly_show;
				} 
				elseif ($dj_array[$i]['hour'] < 49) 
				{
					$wrs_weekly_shows[1] .= $wrs_weekly_show;
				} 
				elseif ($dj_array[$i]['hour'] < 73) 
				{
					$wrs_weekly_shows[2] .= $wrs_weekly_show;
				} 
				elseif ($dj_array[$i]['hour'] < 97) 
				{
					$wrs_weekly_shows[3] .= $wrs_weekly_show;
				} 
				elseif ($dj_array[$i]['hour'] < 121) 
				{
					$wrs_weekly_shows[4] .= $wrs_weekly_show;
				} 
				elseif ($dj_array[$i]['hour'] < 145) 
				{
					$wrs_weekly_shows[5] .= $wrs_weekly_show;
				} 
				elseif ($dj_array[$i]['hour'] < 169) 
				{
					$wrs_weekly_shows[6] .= $wrs_weekly_show;
				}
			}
		}
		$wrs_weekly0 = $wrs_weekly1 = $wrs_weekly2 = $wrs_weekly3 = $wrs_weekly4 = $wrs_weekly5 = $wrs_weekly6 = '';

		if ( $wrs_weekly_shows[0] ) {
			$wrs_weekly0 = '<span class="day_month" colspan="7">' . gmdate("jS F", $slot_start) . '</span><div class="tabz schedule_items masonry">';
			$wrs_weekly0 .= $wrs_weekly_shows[0];
			$wrs_weekly0 .= '</div>';
			$wrs_weekly0 .= Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details();
		}
		
		if ( $wrs_weekly_shows[1] ) {
			$wrs_weekly1 = '<span class="day_month" colspan="7">' . gmdate("jS F", ( $slot_start + ( 1 * 60 * 60 * 24 ) ) ) . '</span><div class="tabz schedule_items masonry">';
			$wrs_weekly1 .= $wrs_weekly_shows[1];
			$wrs_weekly1 .= '</div>';
			$wrs_weekly1 .= Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details();
		}
			
		if ( $wrs_weekly_shows[2] ) {
			$wrs_weekly2 = '<span class="day_month" colspan="7">' . gmdate("jS F", ( $slot_start + ( 2 * 60 * 60 * 24 ) ) ) . '</span><div class="tabz schedule_items masonry">';
			$wrs_weekly2 .= $wrs_weekly_shows[2];
			$wrs_weekly2 .= '</div>';
			$wrs_weekly2 .= Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details();
		}
			
		if ( $wrs_weekly_shows[3] ) {
			$wrs_weekly3 = '<span class="day_month" colspan="7">' . gmdate("jS F", ( $slot_start + ( 3 * 60 * 60 * 24 ) ) ) . '</span><div class="tabz schedule_items masonry">';
			$wrs_weekly3 .= $wrs_weekly_shows[3];
			$wrs_weekly3 .= '</div>';
			$wrs_weekly3 .= Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details();
		}
			
		if ( $wrs_weekly_shows[4] ) {
			$wrs_weekly4 = '<span class="day_month" colspan="7">' . gmdate("jS F", ( $slot_start + ( 4 * 60 * 60 * 24 ) ) ) . '</span><div class="tabz schedule_items masonry">';
			$wrs_weekly4 .= $wrs_weekly_shows[4];
			$wrs_weekly4 .= '</div>';
			$wrs_weekly4 .= Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details();
		}
			
		if ( $wrs_weekly_shows[5] ) {
			$wrs_weekly5 = '<span class="day_month" colspan="7">' . gmdate("jS F", ( $slot_start + ( 5 * 60 * 60 * 24 ) ) ) . '</span><div class="tabz schedule_items masonry">';
			$wrs_weekly5 .= $wrs_weekly_shows[5];
			$wrs_weekly5 .= '</div>';
			$wrs_weekly5 .= Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details();
		}
			
		if ( $wrs_weekly_shows[6] ) {
			$wrs_weekly6 = '<span class="day_month" colspan="7">' . gmdate("jS F", ( $slot_start + ( 6 * 60 * 60 * 24 ) ) ) . '</span><div class="tabz schedule_items masonry">';
			$wrs_weekly6 .= $wrs_weekly_shows[6];
			$wrs_weekly6 .= '</div>';
			$wrs_weekly6 .= Tz_Weekly_Radio_Schedule_Public::tzwrs_zone_details();
		}
		
		$open0 = $open1 = $open2 = $open3 = $open4 = $open5 = $open6 = 0;

		switch ( gmdate( 'w', current_time( 'timestamp', 1 ) ) )
		{
			case 0:
				$open0 = 1;
			break;
			case 1:
				$open1 = 1;
			break;
			case 2:
				$open2 = 1;
			break;
			case 3:
				$open3 = 1;
			break;
			case 4:
				$open4 = 1;
			break;
			case 5:
				$open5 = 1;
			break;
			case 6:
				$open6 = 1;
			break;
		}
		return do_shortcode( '[tabby title="' . esc_html__( 'Sunday' ) . '" open="' . $open0 . '"]' . $wrs_weekly0 . '[tabby title="' . esc_html__( 'Monday' ) . '" open="' . $open1 . '"]' . $wrs_weekly1 . '[tabby title="' . esc_html__( 'Tuesday' ) . '" open="' . $open2 . '"]' . $wrs_weekly2 . '[tabby title="' . esc_html__( 'Wednesday' ) . '" open="' . $open3 . '"]' . $wrs_weekly3 . '[tabby title="' . esc_html__( 'Thursday' ) . '" open="' . $open4 . '"]' . $wrs_weekly4 . '[tabby title="' . esc_html__( 'Friday' ) . '" open="' . $open5 . '"]' . $wrs_weekly5 . '[tabby title="' . esc_html__( 'Saturday' ) . '" open="' . $open6 . '"]' . $wrs_weekly6 . '[tabbyending]' );
	}

	/**
	 * @usage Check if current user or given user_id is in given set of roles
	 */
	static function tzwrs_check_user_role($roles, $user_id = null) {
		if ($user_id) $user = get_userdata($user_id);
		else $user = wp_get_current_user();
		if (empty($user)) return false;
		foreach ($user->roles as $role) {
			if (in_array($role, $roles)) {
				return true;
			}
		}
		return false;
	}

	function tzwrs_team_shortcode($atts) {
		$atts = shortcode_atts( array( 'pic_size' => 96, 'max_chars' => 200 ), $atts );
		return $this->tzwrs_get_the_team($atts['pic_size'], $atts['max_chars']);
	}
	/**
	 * @usage Generate for html of publicly displayed stattion Team
	 * @param int|int ( $pic_width, $textsize )
	 * @return mixed $wrs_team
	 */
	static function tzwrs_get_the_team($picsize, $textsize) {
		global $wpdb, $wp_roles, $plugin_name;
		$current_user = wp_get_current_user();
		$users = get_users( [ 'role__in' => [ 'wrs_manager', 'wrs_dj', 'wrs_djoperator', 'wrs_operator' ] ] );
		$wrs_team = '';
		$follow_html = '';
		if ( $users )
		{
			$wrs_team .= '<div id="wrs_djs_list" class="daily_schedule_slots" data-currid="' . intval($current_user->ID) . '">	<div class="masonry our_crew">';
			foreach ($users as $user) {
				$pic_width = $picsize;
				$wrs_role = '';
				
				$emailResult = $wpdb->get_results(
					$wpdb->prepare(
						"select * from {$wpdb->prefix}wrs_author_subscribe where email=%s", $current_user->user_email
					)
				);

				$following_class = 'dj_follow';
				$following_value = 'Follow';
				$follow_html = '<div class="dj_follow_case"><input type="button" class="logged_out" value="' . strip_tags($following_value) . '" data-author="' . esc_attr($user->data->ID) . '" data-url="' . esc_url( wp_login_url( get_permalink() ) ) . '" /></div>';
				if ( isset( $emailResult[0] ) ) {
					if ($emailResult[0]->followed_authors != '') {
						$authorSubscribersArray = explode(",", $emailResult[0]->followed_authors);
						if ((in_array($user->data->ID, $authorSubscribersArray))) {
							$following_class = 'dj_following following';
							$following_value = 'Following';
						}
					}
				}
				if ( is_user_logged_in() ) {
					$follow_html = '<div class="dj_follow_case"><input type="button" class="' . strip_tags($following_class) . ' ' . intval($user->data->ID) . '" value="' . strip_tags($following_value) . '" data-author="' . esc_attr($user->data->ID) . '" /></div>';
				}
				if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
					$PeepSoUse = PeepSoUser::get_instance(intval($user->data->ID));
					$args = array('post_status'=>'publish');
					$PeepSoUse->profile_fields->load_fields($args, 'profile');
					$fields = $PeepSoUse ? $PeepSoUse->profile_fields->get_fields(intval($user->data->ID)) : '';
					$show_field_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_show_name_field_id' ) );
					
					$their_role = isset( $fields['peepso_user_field_' . $show_field_id] ) ? ( 'DJ - ' . ( $fields['peepso_user_field_' . $show_field_id]->value ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( $fields['peepso_user_field_' . $show_field_id]->value, intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' );
					
					if ( user_can( $user->data->ID, 'run_tings' ) ) {
						$their_role = isset( $fields['peepso_user_field_' . $show_field_id] ) ? ( 'Manager - ' . ( $fields['peepso_user_field_' . $show_field_id]->value ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( $fields['peepso_user_field_' . $show_field_id]->value, intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' );
					}
					if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_check_user_role(array('wrs_djoperator'), $user->data->ID ) ) {

						$their_role = isset( $fields['peepso_user_field_' . $show_field_id] ) ? ( 'DJ Operator - ' . ( $fields['peepso_user_field_' . $show_field_id]->value ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( $fields['peepso_user_field_' . $show_field_id]->value, intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' );
					}
					if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_check_user_role(array('wrs_operator'), $user->data->ID ) ) {
						$their_role = 'Operator';
					}
				}
				else 
				{
					$their_role = 'DJ - ' . ( $user->user_show_name ? $user->user_show_name : esc_html__( 'Radio Show', 'weekly-radio-schedule' ) );
					
					if ( user_can( $user->data->ID, 'run_tings' ) ) {
						$their_role = 'Manager - ' . ( $user->user_show_name ? $user->user_show_name : esc_html__( 'Radio Show', 'weekly-radio-schedule' ) );
					}
					if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_check_user_role(array('wrs_djoperator'), $user->data->ID ) ) {

						$their_role = 'DJ Operator - ' . ( $user->user_show_name ? $user->user_show_name : esc_html__( 'Radio Show', 'weekly-radio-schedule' ) );
					}
					if ( Tz_Weekly_Radio_Schedule_Public::tzwrs_check_user_role(array('wrs_operator'), $user->data->ID ) ) {

						$their_role = 'Operator';
					}
				}
				$wrs_role = '<span><strong>' . $their_role . '</strong></span>';

				if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
					$PeepSoUser = PeepSoUser::get_instance($user->data->ID);
					$profile_url = esc_url( $PeepSoUser->get_profileurl() );
					$img = '<img width="' . intval($picsize) . '" class="avatar avatar-' . intval($picsize) . ' photo tzcssca tzcssimage" src="' . esc_url($PeepSoUser->get_avatar()) . '" />';
				}
				else 
				{
					$profile_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $user->data->ID ) ) );
					$img = '<img itemprop="image" width="' . intval($picsize) . '" class="avatar avatar-' . intval($picsize) . ' photo" src="' . get_avatar_url( $user->data->ID ) . '" />';
				}
				$wrs_team .= '<div itemscope itemtype="https://schema.org/Person" class="schedule_item item"><h2 class="daily_schedule_title"><a itemprop="url" href="' . $profile_url . '"><span itemprop="name">' . esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( strip_tags($user->data->display_name), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) ) . '</span></a></h2><meta itemprop="jobTitle" content="' . esc_attr($their_role) . '" /><meta itemprop="address" content="' . esc_attr(get_bloginfo()) . '"/>' . $wrs_role . '<div class=""><a href="' . $profile_url . '">' . $img . '</a>' . $follow_html;
				
				$about = Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(trim(strip_tags( get_the_author_meta( 'description', $user->data->ID ) ) ), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) );
				$wrs_team .= '<div class="show_snip">' . $about . '</div>';
				$wrs_team .= '</div></div>';
			}
			$wrs_team .= '</div></div>';
		}
		else 
		{
			$Tz_Weekly_Radio_Schedule = new Tz_Weekly_Radio_Schedule();
			$wrs_team .= '
			<div class="no_team">
				<h2>' . esc_html__( 'Start building your Team', 'weekly-radio-schedule' ) . '</h2>
				<p>' . esc_html__( 'Weekly Radio Schedule creates 4 new roles. <strong>Manager</strong>, <strong>DJ</strong>, <strong>Operator</strong> and <strong>DJ Operator</strong>.', 'weekly-radio-schedule' ) . '
				<p>' . esc_html__( 'When users are assigned one of these roles, their names will appear here.', 'weekly-radio-schedule' ) . '
				<p>
					<a href="' . admin_url( 'users.php' ) . '">
						<span class="handy_links">
							<input class="button" type="button" value="' . esc_html__( 'Users' ) . '"></input>
						</span>
					</a>
			</div>';
		}
		
		return $wrs_team;
	}
	
	/**
	 * @usage generate html of today's scheduled shows
	 */
	function tzwrs_today_gen($atts) {

		$atts = shortcode_atts( array( 'title' => esc_html__( 'Today\'s Shows', 'weekly-radio-schedule' ), 'pic_size' => 96, 'max_chars' => 200, 'pastshows' => 1 ), $atts );
		
		return '<h4 class="today_code">' . esc_html($atts['title']) . '</h4>' . $this->tzwrs_wp_daily_coming_up(gmdate( 'w', current_time( 'timestamp', 1 ) )+1, $atts['pic_size'], $atts['max_chars'], $atts['pastshows']);
	}

	/**
	 * @usage generate html of 
	 */
	function tzwrs_shows_gen($atts) {

		$atts = shortcode_atts( array( 'nofshows' => 7, 'pic_size' => 96, 'max_chars' => 200), $atts );
		
		return $this->tzwrs_shows_coming_up(array(intval($atts['nofshows']), intval($atts['pic_size']), intval($atts['max_chars'])));
	}

	/**
	 * @usage Determine which hour of the week it currently is
	 * @return int $this_hour
	 */
	static function tzwrs_get_this_hour() {
		$local_time  = current_datetime();
		$current_time = $local_time->getTimestamp() + $local_time->getOffset();

		$this_day = isset($_GET['day']) ? $_GET['day'] : gmdate("d",$current_time);
		$this_month = isset($_GET['month']) ? $_GET['month'] : gmdate("m",$current_time);
		$this_year = isset($_GET['year']) ? $_GET['year'] : gmdate("Y",$current_time);
		$w = gmdate('w',mktime(0,0,0,$this_month,$this_day,$this_year));//Week day
			
		$this_start_date = mktime(0,0,0, $this_month, $this_day-$w+1, $this_year) - (90000-360);
		
		return intval((((current_time('timestamp')+240) - $this_start_date)/3600));
	}

	/**
	 * @usage Generate array of scheduled slots for the current week
	 * @return mixed 
	 */
	static public function tzwrs_get_weekly_djs() {
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );

		if ( $this_hour > 168 )
		{
			$this_hour = $this_hour - 168;
		}
		$local_time  = current_datetime();
		$current_time = $local_time->getTimestamp() + $local_time->getOffset();
		$now = getdate(current_time('timestamp', 1) - date('Z'));	
		$approval = intval( get_option( TZWRS_OPTION_NAME . '_wrs_need_approval' ) ) == 1 ? '1' : '0';
		$diff_dj = 0;
		global $wpdb;
		$table_name = $wpdb->prefix . 'wrs_this_week';
		$sql = "SELECT s.*, u.ID, u.user_nicename, u.display_name
			FROM " . $table_name . " s, " . $wpdb->prefix . "users u
			WHERE (s.hour > 0
				AND s.user_id = u.ID
				AND s.add_dj = 0)
			OR (s.hour > 0
				AND s.temp_user_id = u.ID
				AND s.add_dj > " . $approval . ")
				ORDER BY s.hour ASC";
		$results = $wpdb->get_results($sql);

		if (count($results) > 0) {
			foreach ($results as $res) {
				$rowset[$res->hour] = array(
					'hour'				=> ($res->hour)-1,
					'user_id'			=> $res->user_id,
					'add_dj'			=> $res->add_dj,
					'temp_user_id'		=> $res->temp_user_id,
					'username' 			=> $res->display_name,
					'slot_id' 			=> $res->slot_id,
					'user_nicename' 	=> $res->user_nicename,
				);
			}
		} 
		if (count($results) > 0) {
			return $rowset;
		}
		else
		{
			return;
		}
	}

	/**
	 * @usage Generate array of scheduled slots for next week
	 * @return mixed 
	 */
	static public function tzwrs_get_next_weekly_djs() {
		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );

		if ( $this_hour > 168 )
		{
			$this_hour = $this_hour - 168;
		}
		$local_time  = current_datetime();
		$current_time = $local_time->getTimestamp() + $local_time->getOffset();
		$now = getdate(current_time('timestamp', 1) - date('Z'));	
		$approval = intval( get_option( TZWRS_OPTION_NAME . '_wrs_need_approval' ) ) == 1 ? '1' : '0';
		$diff_dj = 0;
		global $wpdb;
		$table_name = $wpdb->prefix . 'wrs_next_week';
		$sql = "SELECT s.*, u.ID, u.user_nicename, u.display_name
			FROM " . $table_name . " s, " . $wpdb->prefix . "users u
			WHERE (s.hour > 0
				AND s.user_id = u.ID
				AND s.add_dj = 0)
			OR (s.hour > 0
				AND s.temp_user_id = u.ID
				AND s.add_dj > " . $approval . ")
				ORDER BY s.hour ASC";
		$results = $wpdb->get_results($sql);

		if (count($results) > 0) {
			foreach ($results as $res) {
				$rowset[$res->hour] = array(
					'hour'				=> ($res->hour)-1+168,
					'user_id'			=> $res->user_id,
					'add_dj'			=> $res->add_dj,
					'temp_user_id'		=> $res->temp_user_id,
					'username' 			=> $res->display_name,
					'slot_id' 			=> $res->slot_id,
					'user_nicename' 	=> $res->user_nicename,
				);
			}
		} 
		if (count($results) > 0) {
			return $rowset;
		}
		else
		{
			return;
		}
	}

	/**
	 * @usage Generate shortcode for html of publicly displayed, vertical representation of scheduled shows today
	 * @param int|int|int ( $dayofweek, $pic_width, $textsize )
	 * @return mixed $Show_list
	 */
	static public function tzwrs_wp_daily_coming_up( $dayofweek, $pic_width, $textsize ) {
		$mon_start = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_start_of_the_week()); //Timestamp of Monday Midnight
		$slot_start = $mon_start - (60*60*24) + ( ( 1 - 1 ) * (60*60*24) ); //Timestamp of Sunday Midnight - Summer

		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		if ( $this_hour > 168 )
		{
			$this_hour = $this_hour - 168;
		}
		$last_dj_id = 0;
		$last_temp_dj_id = 0;
		$last_dj_hour = '';
		$now = getdate(current_time('timestamp', 1));
		
		$starting_i = (($dayofweek*24)-23);
		$ending_i = (($dayofweek*24)+1);
	
		$day_mark = gmdate('l', current_time( 'timestamp', 1 ));
		$dj_array = array();
		$dj_array = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_weekly_djs();

		$PeepSoUser = false;
		$Show_li = '';
		$Show_list = '';
		$show_name = '';
		$nofshows = '';
						
		if ( !empty($dj_array) ) {
			for ( $i = (($dayofweek*24)-23); $i < (($dayofweek*24)+1); $i++)
			{
				$onAir = $i == $this_hour ? ' onAir' : '';
				
				if ( !empty($dj_array[$i]['user_id']) ) {  } else { $dj_array[$i]['user_id'] = 0; }
				if ( !empty($dj_array[$i]['temp_user_id']) ) {  } else { $dj_array[$i]['temp_user_id'] = 0; }
				if ( $dj_array[$i]['user_id'] != 0 && $dj_array[$i]['temp_user_id'] != 0 ) 
				{
					$amal = intval($dj_array[$i]['temp_user_id']);
				}
				else
				{
					$amal = intval($dj_array[$i]['user_id'] + $dj_array[$i]['temp_user_id']);
				}
				$PeepSoUser = false;
				if ( $amal > 0 )
				{
					if ( ( $amal == $last_dj_id ) && $last_dj_hour == $dj_array[$i]['hour'] - 1 )
					{ 
						$last_dj_id = $amal;
						$last_dj_hour = intval($dj_array[$i]['hour']);
					} 
					else 
					{
						$show_info = trim( strip_tags( get_the_author_meta( 'description', $amal ) ) );

						if ($dj_array[$i]['hour'] % 24 == gmdate("H",current_time( 'timestamp' )))
						{
							$hour_text = 'On Air';
						}
						$slot_user = get_user_by( 'slug', $dj_array[$i]['user_nicename']);
						include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
						if ( in_array('peepso-core/peepso.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {
							$PeepSoUser = PeepSoUser::get_instance($amal);
							$img = '<img itemprop="image" width="' . esc_attr($pic_width) . '" class="avatar avatar-' . esc_attr($pic_width) . ' photo" src="' . esc_url($PeepSoUser->get_avatar()) . '" />';
							$profile_url = esc_url( $PeepSoUser->get_profileurl() );
						}
						else 
						{
							$profile_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $amal ) ) );
							$img = '<img itemprop="image" width="' . esc_attr($pic_width) . '" class="avatar avatar-' . esc_attr($pic_width) . ' photo" src="' . get_avatar_url( $amal ) . '" />';
						}
						$the_time = $slot_start + ($dj_array[$i]['hour'] * 60 * 60);				
						$time_valid = mktime(0, 0, 0, date("n"), 1);

						$durAtion = 1;
						$plural = '';
						$currentHour = $i;
						if ( $i <= 168 ) 
						{
							while ( isset( $dj_array[$currentHour+1]['user_id'] ) )
							{
								$currentHour++;
								if ( $dj_array[$currentHour]['user_id']+$dj_array[$currentHour]['temp_user_id'] == $amal )
								{
									$plural = 's';
									$durAtion++;
								}
							}
						}
						$Show_li .= '
							<div itemscope="222" itemtype="https://schema.org/MusicEvent" class="schedule_item item ' . strip_tags($onAir) . '">';
							if ( $PeepSoUser ) { 
								$Show_li .= '<meta itemprop="image" content="' . esc_url($PeepSoUser->get_cover()) . '" />';
								$PeepSoUser = PeepSoUser::get_instance(intval($slot_user->ID));
								$args = array('post_status'=>'publish');
								$PeepSoUser->profile_fields->load_fields($args, 'profile');
								$fields = $PeepSoUser ? $PeepSoUser->profile_fields->get_fields($slot_user->ID) : '';
								$show_field_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_show_name_field_id' ) );
								$show_name = isset($fields['peepso_user_field_' . $show_field_id]) ? ( $fields['peepso_user_field_' . $show_field_id]->value ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( $fields['peepso_user_field_' . $show_field_id]->value, intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' );
							}
							else 
							{
								$userbyamal = get_user_by('id', $amal);
								$show_name = $userbyamal->user_show_name ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( strip_tags($userbyamal->user_show_name), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' );
							}
							$Show_li .= '
							<meta itemprop="name" content="' . esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( strip_tags($dj_array[$i]['username']), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) ) . ' - ' . $show_name . '" />
							<meta itemprop="eventStatus" content="EventScheduled" />
							<meta itemprop="eventAttendanceMode" content="https://schema.org/OnlineEventAttendanceMode" />
							<span itemprop="organizer" itemscope="" itemtype="https://schema.org/Organization">
								<meta itemprop="name" content="'. esc_attr(get_bloginfo()) . '" />
								<meta itemprop="address" content="' . strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) ) . '" />
								<meta itemprop="url" content="' . get_home_url() . '" />
							</span>
							<span itemprop="location" itemscope="" itemtype="https://schema.org/MusicVenue">
								<meta itemprop="name" content="'. esc_attr(get_bloginfo()) . '" />
								<meta itemprop="address" content="' . strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) ) . '" />
							</span>
							<span itemprop="offers" itemscope="" itemtype="https://schema.org/Offer">
								<meta itemprop="price" content="0" />
								<meta itemprop="priceCurrency" content="USD" />
								<link itemprop="availability" href="https://schema.org/InStock" />
								<meta itemprop="url" content="' . esc_url( get_option( TZWRS_OPTION_NAME . '_wrs_audio_address' ) ) . '">
								<meta itemprop="validFrom" content="' . esc_attr(gmdate("c", $time_valid)) . '">						
							</span>
							<a itemprop="url" href="' . $profile_url . '">
								<h2 class="daily_schedule_title">' . strip_tags($dj_array[$i]['username']) . '</h2>
							</a>
							<span itemprop="startDate" content="' . esc_attr(gmdate("c", $the_time)) . '" class="timeslot">
							' . $dj_array[$i]['hour'] % 24 . ':0 0 ' . '
							</span><span class="durAtion">'. $durAtion . 'hr' . $plural . '</span>
							<meta itemprop="endDate" content="' . esc_attr(gmdate("c", $the_time+($durAtion*60*60))) . '">
							<span itemprop="performer" itemscope="" itemtype="https://schema.org/Person" class="daily_schedule_pic" show_pic="' . intval($dj_array[$i]['user_id']) . '">
								<meta itemprop="name" content="' . esc_attr($dj_array[$i]['username']) . '">' . $img . '</meta>
							</span>
							<span>' . esc_html($show_name). '</span>
							<p itemprop="description" class="show_snip">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(strip_tags($show_info), $textsize) . '</p>
							<span class="daily_schedule_rule"></span>
						</div>';
						$last_dj_id = $amal;
						$last_dj_hour = intval($dj_array[$i]['hour']);
					}
				}
			}
			$Show_list .= $Show_li;
		}
		if ( $Show_list )
		{
			$Show_list = '<div class="daily_schedule_slots today"><div class="">' . $Show_list . '</div></div>';
		}
		else
		{
			$tzwrs_logo = intval( get_option('tzwrs_wrs_logo') );
			$img = '';
			if ( $tzwrs_logo ) {
				$cover_data = wp_get_attachment_image_src( $tzwrs_logo );
                $img = '<img style="width:100%"" class="avatar avatar-600 photo" src="' . esc_url( $cover_data[0] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
			}

			$tzwrs_logo = get_option('tzwrs_wrs_logo');
			if ( $tzwrs_logo ):
				$cover_data = wp_get_attachment_image_src( $tzwrs_logo );
                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url( $cover_data[0] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
				else:

				$plugin = new Tz_Weekly_Radio_Schedule();
				$plugin_admin = new Tz_Weekly_Radio_Schedule_Admin( $plugin->get_plugin_name(), $plugin->get_version() );

                $img = '<img width="96" class="avatar avatar-96 photo" src="' . esc_url( $plugin_admin->tzwrs_get_default_images()['square'] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
            endif;

			$Show_list = '
			<div class="daily_schedule_slots today">
				<div class="fix_height">
					<div id="shows" class="noshows" data-pic_width="' . intval($pic_width) . '" data-textsize="' . intval($textsize) . '" data-nofshows="' . intval($nofshows) . '">
						<div class="schedule_item item ">
							<h4 class=""><a href="#">' . esc_attr(get_bloginfo()) . '</a></h4>
							<span class="timeslot">
								Every day 
							</span>
							<span class="durAtion">24 hrs</span>
							<span class="daily_schedule_pic" show_pic="43">' . $img . '</span>
							<span class="showName">' . strip_tags(get_bloginfo( 'description' )) . '</span>
							<p itemprop="description" class="show_snip">Playing the greatest variety of old and new music</p>
							<span class="daily_schedule_rule"></span>
						</div>
					</div>
				</div>
			</div>';
		}
		return $Show_list;
	}

	/**
	 * @usage Generate shortcode for html of publicly displayed representation of the next seven scheduled shows
	 * @param int|int|int ( $numberofshows, $pic_width, $textsize )
	 * @return mixed $Show_list
	 */
	static public function tzwrs_shows_coming_up($atts) {

		$mon_start = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_start_of_the_week()); //Timestamp of Monday Midnight
		$slot_start = $mon_start - (60*60*24) + ( ( 1 - 1 ) * (60*60*24) ); //Timestamp of Sunday Midnight - Summer

		$this_hour = intval( Tz_Weekly_Radio_Schedule_Public::tzwrs_get_this_hour() );
		if ( $this_hour > 168 )
		{
			$this_hour = $this_hour - 168;
		}
		if ( isset( $_POST['pic_width'] ) )
		{
			$pic_width = isset( $_POST['pic_width'] ) ? intval( $_POST['pic_width'] ) : intval( get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size') );
			$textsize = isset( $_POST['textsize'] ) ? intval( $_POST['textsize'] ) : intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars') );
			$nofshows = isset( $_POST['nofshows'] ) ? intval( $_POST['nofshows'] ) : 7;
		}
		else
		{
			$pic_width = $atts[1] ? $atts[1] : intval( get_option( TZWRS_OPTION_NAME . '_wrs_default_avatar_size') );
			$textsize = $atts[2] ? $atts[2]: intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars') );
			$nofshows = $atts[0] ? $atts[0] : 7;
		}
		$last_dj_id = 0;
		$last_temp_dj_id = 0;
		$last_dj_hour = '';
		$now = getdate(current_time('timestamp', 1));
		
		$day_mark = gmdate('l', current_time( 'timestamp', 1 ));
		$dj_array = array();
		$dj_array_this = array();
		$dj_array_next = array();
		$dj_array_this = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_weekly_djs();
		$dj_array_next = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_next_weekly_djs();
		$dj_array = array_merge($dj_array_this, $dj_array_next);

		$PeepSoUser = false;
		$Show_list = '';
		$show_name = '';
		$shows = array();
		
		if ( !empty($dj_array) ) {
			$count = 0;
			$i = 1;
			foreach ( $dj_array as $slot )
			{
				if ( $i <= count($dj_array) )
				{
					if ( $slot['hour'] > $this_hour )
					{
						$onAir = $i == $this_hour ? ' onAir' : '';

						if ( !empty($dj_array[$i]['user_id']) ) {  } else { $dj_array[$i]['user_id'] = 0; }
						if ( !empty($dj_array[$i]['temp_user_id']) ) {  } else { $dj_array[$i]['temp_user_id'] = 0; }
						if ( $dj_array[$i]['user_id'] != 0 && $dj_array[$i]['temp_user_id'] != 0 ) 
						{
							$amal = intval($dj_array[$i]['temp_user_id']);
						}
						else
						{
							$amal = intval($dj_array[$i]['user_id']) + intval($dj_array[$i]['temp_user_id']);
						}
						$PeepSoUser = false;

						if ( $amal > 0 )
						{
							if ( ( $amal == $last_dj_id ) && $last_dj_hour == $dj_array[$i]['hour'] - 1 )
							{ 
								$last_dj_id = $amal;
								$last_dj_hour = intval($dj_array[$i]['hour']);
							} 
							else 
							{
								$show_info = trim( strip_tags( get_the_author_meta( 'description', $amal ) ) );

								if ($dj_array[$i]['hour'] % 24 == gmdate("H",current_time( 'timestamp' )))
								{
									$hour_text = 'On Air';
								}
								$slot_user = get_user_by( 'slug', $dj_array[$i]['user_nicename']);

								include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
								if ( in_array('peepso-core/peepso.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {
									$PeepSoUser = PeepSoUser::get_instance($amal);
									$img = '<img itemprop="image" width="' . esc_attr($pic_width) . '" class="avatar avatar-' . intval($pic_width) . ' photo" src="' . esc_url($PeepSoUser->get_avatar()) . '" />';
									$profile_url = esc_url( $PeepSoUser->get_profileurl() );
								}
								else 
								{
									$profile_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $amal ) ) );
									$img = '<img itemprop="image" width="' . esc_attr($pic_width) . '" class="avatar avatar-' . intval($pic_width) . ' photo" src="' . esc_url(get_avatar_url( $amal )) . '" />';
								}
								$the_time = $slot_start + ($dj_array[$i]['hour'] * 60 * 60);				
								$time_valid = mktime(0, 0, 0, date("n"), 1);

								$durAtion = 1;
								$plural = '';
								$currentHour = $i;
								if ( $i <= 168 ) 
								{

									while ( isset( $dj_array[$currentHour+1] ) && ( $dj_array[$currentHour+1]['user_id']+$dj_array[$currentHour+1]['temp_user_id'] == $amal ) )
									{
										$plural = 's';
										$durAtion++;
										$currentHour++;
									}

								}
								$Show_li = '
									<div itemscope="333" itemtype="https://schema.org/MusicEvent" class="schedule_item item ' . $onAir . '">';
									if ( $PeepSoUser ) { 
										$Show_li .= '<meta itemprop="image" content="' . esc_url($PeepSoUser->get_cover()) . '" />';
										$PeepSoUser = PeepSoUser::get_instance($slot_user->ID);
										$args = array('post_status'=>'publish');
										$PeepSoUser->profile_fields->load_fields($args, 'profile');
										$fields = $PeepSoUser ? $PeepSoUser->profile_fields->get_fields(intval($slot_user->ID)) : '';
										$show_field_id = intval( get_option( TZWRS_OPTION_NAME . '_wrs_show_name_field_id' ) );
										$show_name = $fields['peepso_user_field_' . $show_field_id]->value ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( strip_tags($fields['peepso_user_field_' . $show_field_id]->value), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' );
									}
									else 
									{
										$userbyamal = get_user_by('id', $amal);
										$show_name = $userbyamal->user_show_name ? esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( strip_tags($userbyamal->user_show_name), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_show_name_chars' ) ) ) ) : esc_html__( 'Radio Show', 'weekly-radio-schedule' );
									}
									$Show_li .= '
									<meta itemprop="name" content="' . esc_html( Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop( strip_tags($dj_array[$i]['username']), intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_name_chars' ) ) ) ) . ' - ' . $show_name . '" />
									<meta itemprop="eventStatus" content="EventScheduled" />
									<meta itemprop="eventAttendanceMode" content="https://schema.org/OnlineEventAttendanceMode" />
									<span itemprop="organizer" itemscope="" itemtype="https://schema.org/Organization">
										<meta itemprop="name" content="'. esc_attr(get_bloginfo()) . '" />
										<meta itemprop="address" content="' . strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) ) . '" />
										<meta itemprop="url" content="' . get_home_url() . '" />
									</span>
									<span itemprop="location" itemscope="" itemtype="https://schema.org/MusicVenue">
										<meta itemprop="name" content="'. esc_attr(get_bloginfo()) . '" />
										<meta itemprop="address" content="' . strip_tags( get_option( TZWRS_OPTION_NAME . '_wrs_address' ) ) . '" />
									</span>
									<span itemprop="offers" itemscope="" itemtype="https://schema.org/Offer">
										<meta itemprop="price" content="0" />
										<meta itemprop="priceCurrency" content="USD" />
										<link itemprop="availability" href="https://schema.org/InStock" />
										<meta itemprop="url" content="' . esc_url( get_option( TZWRS_OPTION_NAME . '_wrs_audio_address' ) ) . '">
										<meta itemprop="validFrom" content="' . esc_attr(gmdate("c", $time_valid)) . '">						
									</span>

									<h4 class=""><a itemprop="url" href="' . esc_url($profile_url). '">' . strip_tags($dj_array[$i]['username']) . '</a></h4>

									<span itemprop="startDate" content="' . esc_attr(gmdate("c", $the_time)) . '" class="timeslot">
									' . gmdate("l", $the_time) . ' ' . $dj_array[$i]['hour'] % 24 . ':00 ' . '
									</span><span class="durAtion">' . $durAtion . 'hr' . $plural . '</span>
									<meta itemprop="endDate" content="' . esc_attr(gmdate("c", $the_time+($durAtion*60*60))) . '">
									<span itemprop="performer" itemscope="" itemtype="https://schema.org/Person" class="daily_schedule_pic" show_pic="' . intval($dj_array[$i]['user_id']) . '">
										<meta itemprop="name" content="' . strip_tags($dj_array[$i]['username']) . '">' . $img . '</meta>
									</span>
									<span class="showName">' . $show_name . '</span>
									<p itemprop="description" class="show_snip">' . Tz_Weekly_Radio_Schedule_Public::tzwrs_str_stop(strip_tags($show_info), $textsize) . '</p>
									<span class="daily_schedule_rule"></span>
								</div>';
								$last_dj_id = $amal;
								$last_dj_hour = intval($dj_array[$i]['hour']);
								$shows[] = $Show_li;
								$count++;
								if ( $count == $nofshows ) {
									$i = count($dj_array);
								}
							}

						}
					}
					$i++;
				}
			}
		}
		$total=count($shows)-1;
		$var=mt_rand(0,$total);

		if ( isset($_POST['ajaxed'] ) ) {
			echo $shows[$var];
			die();
		}
		else 
		{
			$tzwrs_logo = intval(get_option('tzwrs_wrs_logo'));
			$img = '';
			if ( $tzwrs_logo ):
				$cover_data = wp_get_attachment_image_src( $tzwrs_logo );
                $img = '<img width="' . esc_attr($pic_width) . '" class="avatar avatar-' . intval($pic_width) . ' photo" src="' . esc_url( $cover_data[0] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
				else:

				$plugin = new Tz_Weekly_Radio_Schedule();
				$plugin_admin = new Tz_Weekly_Radio_Schedule_Admin( $plugin->get_plugin_name(), $plugin->get_version() );

                $img = '<img width="' . esc_attr($pic_width) . '" class="avatar avatar-' . intval($pic_width) . ' photo" src="' . esc_url( $plugin_admin->tzwrs_get_default_images()['square'] ) . '" title="' . esc_attr(get_bloginfo()) . '" />';
            endif;
			return '
			<div class="fix_height_sc">
				<div id="some_shows" class="some_shows" data-pic_width="' . esc_attr($pic_width) . '" data-textsize="' . esc_attr($textsize) . '" data-nofshows="' . esc_attr($nofshows) . '">
					<div class="schedule_item item ">
						<h4 class=""><a href="#">' . esc_attr(get_bloginfo()) . '</a></h4>
						<span class="timeslot">
							Every day 
						</span>
						<span class="durAtion">24 hrs</span>
						<span class="daily_schedule_pic" show_pic="43">' . $img . '</span>
						<span class="showName">' . strip_tags(get_bloginfo( 'description' )) . '</span>
						<p itemprop="description" class="show_snip">Playing the greatest variety of old and new music</p>
						<span class="daily_schedule_rule"></span>
					</div>
				</div>
			</div>';
		}
	}

	/**
	 * @usage Force adminbar to be shown for all on front end
	 */
	function tzwrs_show_admin_bar($bool) 
	{
		global $post;
		if ( $post )
		{
			if ( $post->post_name == 'wrs-audio' ) {
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	/**
	 * @usage Add user role class to front-end body tag
	 * @param $classes
	 * @return $classes
	 */
	function tzwrs_class_to_body($classes) {
		global $current_user;
		if ( is_page( get_option( TZWRS_OPTION_NAME . '_wrs_schedule_page_id' ) ) ) {
			$classes[] = 'schedule';
		}
		if ( is_page( 'wrs-crossroads' ) ) {
			$classes[] = 'crossroads';
		}
		$user_role = array_shift($current_user->roles);
		$classes[] = $user_role.' ';
		$classes[] = 'wrs';
		$classes[] = esc_html( wp_get_theme()->get( 'TextDomain' ) );
		return $classes;
	}

	function tzwrs_sticky_clock(){
		global $wpdb, $wp_query, $post;

		$day_mark = '#day' . gmdate("w",current_time( 'timestamp' ));
		?>
		<div id="id04" class="modal teammodal">
			<span onclick="document.getElementById('id04').style.display='none'" class="close" title="<?php echo esc_html__( 'Close', 'weekly-radio-schedule' ) ?>">&times;</span>
			<div class="modal-content animate">
				<div class="imgcontainer">
					<div id="team-modal-content">
						<div class="the_team_modal_wrap">
							<h2 class="team_title"><?php echo esc_attr(get_bloginfo()) . ' - ' . esc_html__( 'The Team', 'weekly-radio-schedule' ); ?></h2>
							<?php echo Tz_Weekly_Radio_Schedule_Public::tzwrs_get_the_team(86, intval( get_option( TZWRS_OPTION_NAME . '_wrs_max_desc_chars' ) ) ); ?>
							<div class="after_msg_form"><p><?php echo strip_tags(get_bloginfo( 'description' )); ?></p></div>
						</div>
					</div>
				</div>
			</div>
		</div> <?php 

		// check for plugin using plugin name
		?>
		<div id="id05" class="modal msgdj">
			<div class="modal-content animate">
				<span onclick="document.getElementById('id05').style.display='none'" class="close" title="<?php echo esc_html__( 'Close' , 'weekly-radio-schedule') ?>">&times;</span>
				<?php
				$current_user_id = '';
				$table_name = $wpdb->prefix . 'wrs_this_week';
				$row = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE slot_id = ' . $this->tzwrs_get_slotid() );
				$slot_user = intval($row->user_id);
				$temp_user_id = intval($row->temp_user_id);
				$add_dj = intval($row->add_dj);
				if ( $slot_user && $add_dj == 0 ) 
				{
					$current_user_id = $slot_user;
				}
				if ( $temp_user_id && $add_dj > 0 )
				{
					$current_user_id = $temp_user_id;
				}
				$user = get_user_by( 'id', $current_user_id );
				?>
				<div id="msg-modal-content">
					<div class="the_team_modal_wrap">
						<h2 class="onAirDJ"><?php echo esc_html__( 'Message', 'weekly-radio-schedule' ) ?> <?php echo $user ? strip_tags($user->display_name) : '' ?></h2>
						<div class="djtomsg" data-footl="999"><?php echo $user ? get_avatar( $current_user_id, 400 ) : '' ?></div>
						<div role="form" class="wpcf7">

						</div>
						<div class="after_msg_form"><p><?php echo strip_tags(get_bloginfo( 'description' )); ?></p></div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
		// Get the modal
		var modal4 = document.getElementById('id04');

		// When the user clicks anywhere outside of the modal4, close it
		window.onclick = function(event) {
			if (event.target == modal4) {
				modal4.style.display = "none";
			}
		}

		// Get the modal
		var modal5 = document.getElementById('id05');

		// When the user clicks anywhere outside of the modal5, close it
		window.onclick = function(event) {
			if (event.target == modal5) {
				modal5.style.display = "none";
			}
		}

		document.addEventListener( 'wpcf7mailsent', function( event ) {
			if ( '<?php echo intval( get_option( TZWRS_OPTION_NAME . '_wrs_cf7_message_id' ) ); ?>' == event.detail.contactFormId  ) {
				var inputs = event.detail.inputs;
				var towhom = jQuery(".djtomsg").data('footl');
				for ( var i = 0; i < inputs.length; i++ ) {
					if ( 'your-name' == inputs[i].name ) {
						var yourName = inputs[i].value;
					}
					if ( 'your-message' == inputs[i].name ) {
						var yourMessage = inputs[i].value;
					}
					if ( 'your-email' == inputs[i].name ) {
						var yourEmail = inputs[i].value;
					}
				}
				jQuery.ajax({
					url : wrsLocal.ajax_url,
					type : 'post',
					data : {
						action : 'tzwrs_sendmsgdj',
						yourName : yourName,
						yourEmail : yourEmail,
						towhom : towhom,
						yourMessage : yourMessage
					},
					success : function( response ) {
						str_array = response.split('||');
					}
				});
			}
			if ( '<?php echo intval( get_option( TZWRS_OPTION_NAME . '_wrs_cf7_join_modal_id' ) ); ?>' == event.detail.contactFormId ) {
				var inputs = event.detail.inputs;
				var towhom = jQuery(".djtomsg").data('footl');
				for ( var i = 0; i < inputs.length; i++ ) {
					if ( 'your-name' == inputs[i].name ) {
						var yourName = inputs[i].value;
					}
					if ( 'your-message' == inputs[i].name ) {
						var yourMessage = inputs[i].value;
					}
					if ( 'your-email' == inputs[i].name ) {
						var yourEmail = inputs[i].value;
					}
					if ( 'your-sounds' == inputs[i].name ) {
						var yourSounds = inputs[i].value;
					}
				}
				jQuery.ajax({
					url : wrsLocal.ajax_url,
					type : 'post',
					data : {
						action : 'tzwrs_sendmsgjoin',
						yourName : yourName,
						towhom : towhom,
						yourEmail : yourEmail,
						yourSounds : yourSounds,
						yourMessage : yourMessage
					},
					success : function( response ) {
						str_array = response.split('||');
					}
				});
			}
		}, false );

		var YTMenu = (function() {

			function init() {
				
				[].slice.call( document.querySelectorAll( '.dr-menu' ) ).forEach( function( el, i ) {

					var trigger = el.querySelector( 'div.dr-trigger' ),
						icon = trigger.querySelector( 'span.dr-icon-menu' ),
						open = false;

					trigger.addEventListener( 'click', function( event ) {
						if( !open ) {
							el.className += ' dr-menu-open';
							open = true;
						}
					}, false );

					icon.addEventListener( 'click', function( event ) {
						if( open ) {
							event.stopPropagation();
							open = false;
							el.className = el.className.replace(/\bdr-menu-open\b/,'');
							return false;
						}
					}, false );

				} );

				[].slice.call( document.querySelectorAll( '.dr-upload' ) ).forEach( function( el, i ) {

					var triggerup = el.querySelector( 'div.dr-trigger-upload' ),
						iconup = triggerup.querySelector( 'span.dr-icon-upload' ),
						openup = false;

					triggerup.addEventListener( 'click', function( event ) {
						if( !openup ) {
							el.className += ' dr-upload-open';
							openup = true;
						}
					}, false );

					iconup.addEventListener( 'click', function( event ) {
						if( openup ) {
							event.stopPropagation();
							openup = false;
							el.className = el.className.replace(/\bdr-upload-open\b/,'');
							return false;
						}
					}, false );

				} );

			}

			init();

		})();
		</script>
		<?php
	}

	// add styles to head
	function tzwrs_head_style() {
		$scheme = strip_tags(get_option(TZWRS_OPTION_NAME . '_wrs_color_scheme'));
		$scheme_array = explode( ',', $scheme );
		$our_theme = wp_get_theme();
		$stylee = ':root{--seven-opac: .71;--text-color:' . strip_tags( get_option(TZWRS_OPTION_NAME . '_wrs_text_color') ) . ';--accent-color:' . strip_tags( get_option(TZWRS_OPTION_NAME . '_wrs_accent_color') ) . ';--secondary-color:' . strip_tags( get_option(TZWRS_OPTION_NAME . '_wrs_secondary_color') ) . ';--border-color:' . strip_tags( get_option(TZWRS_OPTION_NAME . '_wrs_border_color') ) . ';--background-color:' . strip_tags( get_option(TZWRS_OPTION_NAME . '_wrs_background_color') ) . ';--header-footer-background-color:' . strip_tags( get_option(TZWRS_OPTION_NAME . '_wrs_header_footer_background_color') ) . ';}';

		echo '<style type="text/css">' . $stylee . '</style>';
	}

	/**
	 * @usage Determine the role(s) of a user
	 * @param int $user_id
	 * @return string 
	 */
	static function tzwrs_get_user_role($user_id) {
		global $wp_roles;

		$roles = array();
		$user = new WP_User( $user_id );
		if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
		foreach ( $user->roles as $role )
			$roles[] .= translate_user_role( $role );
		}
		return implode(', ',$roles);
	}

	/**
	 * @usage Trim string to $max_length characters breaking on spaces
	 * @return string 
	 */
	static function tzwrs_str_stop($string, $max_length) {
		if (strlen($string) > $max_length){
			$string = substr($string, 0, $max_length);
			$pos = strrpos($string, " ");
			if($pos === false) {
				   return substr($string, 0, $max_length)."...";
			   }
			return substr($string, 0, $pos)."...";
		}else{
			return $string;
		}
	} 

	/**
	 * Create our custom filter widget
	 *
	 * @since    1.0.0
	 */
	public function tzwrs_register_widgets() {

		register_widget( 'WRSDayWidget' );
		register_widget( 'WRSTeamWidget' );

	}

	//change author base
	function tzwrs_change_author_permalinks() {
	  global $wp_rewrite;
	  // Change the value of the author permalink base to whatever you want here
	  // $wp_rewrite->author_base = 'profile';
	  //$wp_rewrite->flush_rules();
	}

	public function tzwrs_option_exists($name, $site_wide=false){
		global $wpdb; return $wpdb->query("SELECT * FROM ". ($site_wide ? $wpdb->base_prefix : $wpdb->prefix). "options WHERE option_name ='$name' LIMIT 1");
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function tzwrs_enqueue_styles() {

		global $wp, $post;
		wp_enqueue_style( 'followCSS', plugin_dir_url( __FILE__ ) . 'css/follow.css', array(), $this->version );
		// js 
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tz-weekly-radio-schedule-public.css', array(), $this->version, 'all' );
		if ( !empty( $post->post_content ) ) { 
			if ( strpos($post->post_content,'[tab') !== false || strpos($post->post_content,'[sch') !== false || strpos($post->post_content,'[faqs') !== false ) {
				wp_enqueue_style( 'tabby', plugin_dir_url( __FILE__ ) . 'css/tabby.css', array(), $this->version );
				wp_enqueue_style( 'tabby-print', plugin_dir_url( __FILE__ ) . 'css/tabby-print.css', array(), $this->version );
			}
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function tzwrs_enqueue_scripts() {

		wp_enqueue_script( 'followJS', TZWRS_DIRECTORY_URL . '/public/js/follow.js', array( 'jquery' ), $this->version, false );
		$config_array = array('ajaxUrl' => admin_url('admin-ajax.php'), 'ajaxNonce' => wp_create_nonce('follow-nonce'),
			'currentURL' => $_SERVER['REQUEST_URI']);
		wp_localize_script('followJS', 'ajaxData', $config_array);

		wp_enqueue_script('modernizr-js', TZWRS_DIRECTORY_URL . '/public/js/modernizr.custom.js', array('jquery'), $this->version, false );
		
		wp_enqueue_script( 'tzwrsPublicJS', TZWRS_DIRECTORY_URL . '/public/js/tz-weekly-radio-schedule-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'tzwrsPublicJS', 'wrsLocal', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			));

		do_action( 'tzwrs_main_enque' );
		
	}

	/**
	 * @usage Generate text displaying time until a particular moment i.e. 2 hours to go or 30 minutes ago
	 */
	function tzwrs_time_ago2($date) {
		if(empty($date)) 
		{
			return "No date provided";
		}

		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		$now = current_time('timestamp', 1);
		$unix_date = strtotime($date);
		// check validity of date
		if(empty($unix_date)) 
		{
			return "Bad date";
		}
		// is it future date or past date
		if($now > $unix_date) 
		{
			$difference = $now - $unix_date;
			$tense = "ago";
		} 
		else 
		{
			$difference = $unix_date - $now;
			$tense = "from now";
		}

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) 
		{
			$difference /= $lengths[$j];
		}
	 
		$difference = round($difference);
	 
		if($difference != 1) 
		{
			$periods[$j].= "s";
		}
	 
		return "$difference $periods[$j] {$tense}";
	}

	/**
	 * @usage Determine the user ID of a user by nicename
	 * @param string $nicename
	 * @return int $user->ID 
	 */
	static function tzwrs_get_user_id_by_nicename( $nicename ) {
		global $wpdb;

		if ( ! $user = $wpdb->get_row( $wpdb->prepare(
			"SELECT `ID` FROM $wpdb->users WHERE `user_nicename` = %s", $nicename
		) ) )
			return false;

		return $user->ID;
	}

	/**
	 * @usage Determine the user ID of a user by display name
	 * @param string $display_name
	 * @return mixed $user->ID 
	 */
	function tzwrs_get_user_id_by_display_name( $display_name ) {
		global $wpdb;

		if ( ! $user = $wpdb->get_row( $wpdb->prepare(
			"SELECT `ID` FROM $wpdb->users WHERE `display_name` = %s", $display_name
		) ) )
			return false;

		return $user->ID;
	}

	function tzwrs_follow_wrs_djs() {
		global $wpdb, $current_user;

		$ajaxNonce = $_POST['nonce'];

		if (wp_verify_nonce($ajaxNonce, 'follow-nonce')) {
			$subscriberEmail = sanitize_email($current_user->user_email);

			$emailResult = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}wrs_author_subscribe WHERE email=%s", $subscriberEmail
				)
			);

			$subscriberID = intval( $_POST['currid'] );
			$authorId = isset($_POST['author_id']) ? intval( $_POST['author_id'] ) : '';

			/*
			 * Check if author exists and insert if not already available to follow
			 */
			$authorResult = $wpdb->get_results(
				$wpdb->prepare(
					"select * from {$wpdb->prefix}wrs_author_followers where author_id=%d", $authorId
				)
			);

			if (count($authorResult) == '0') {
				$result = $wpdb->insert($wpdb->prefix . 'wrs_author_followers', array(
					'author_id' => $authorId,
				));
			}

			if ( count( $emailResult ) == '0' ) {
				$result = $wpdb->insert($wpdb->prefix . 'wrs_author_subscribe', array(
					'email' => $subscriberEmail,
					'followed_authors' => $authorId,
				));
			}
			else 
			{
				$subscribedAuthorList = $emailResult[0]->followed_authors;
				$subscribedAuthorList = $subscribedAuthorList . ',' . $authorId;
			}
			
			/*
			 * Get Author List of the Current User
			 */
			$subscribedAuthorList = $emailResult[0]->followed_authors;
			if ($subscribedAuthorList != '') {
				$subscribedAuthorList = explode(",", $subscribedAuthorList);
			} else {
				$subscribedAuthorList = array();
			}

			if (!(in_array($authorId, $subscribedAuthorList))) {
				array_push($subscribedAuthorList, $authorId);
			}

			/*
			 * Get current author info with authors subscribers
			 */
			$authorResult = $wpdb->get_results(
				$wpdb->prepare(
					"select * from {$wpdb->prefix}wrs_author_followers where author_id=%d", $authorId
				)
			);

			if (count($authorResult) == '1') {

				if ($authorResult[0]->followers_list != '') {
					$authorSubscribersArray = explode(",", $authorResult[0]->followers_list);
				} else {
					$authorSubscribersArray = array();
				}

				if (!(in_array($subscriberID, $authorSubscribersArray))) {
					array_push($authorSubscribersArray, $subscriberID);
				}

				// User list who follows specific author
				$followersList = implode(",", $authorSubscribersArray);

				// Author list followed by specific user
				$subscribedAuthorList = implode(",", $subscribedAuthorList);

				$result = $wpdb->update(
					$wpdb->prefix . 'wrs_author_followers', 
					array( 
						'followers_list' => $followersList
					), 
					array(
						'author_id' => $authorId
					) 
				);

				$result = $wpdb->update(
					$wpdb->prefix . 'wrs_author_subscribe', 
					array( 
						'followed_authors' => $subscribedAuthorList
					), 
					array(
						'email' => $subscriberEmail
					) 
				);

				$table_name = $wpdb->prefix . 'wrs_author_followers';
				$authorResult = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE author_id = ' . $authorId );

				if (count($authorResult) == '1') {
					if ($authorResult[0]->followers_list != '') {
						$authorSubscribersArray = explode(",", $authorResult[0]->followers_list);
					} else {
						$authorSubscribersArray = array();
					}
					
					$follower_count = 0;
					foreach ($authorSubscribersArray as $key => $value) {
						if ( $value ) $follower_count = $follower_count + 1;
					}
				}
				$plurality = $follower_count > 1 ? esc_html__( 'Followers', 'weekly-radio-schedule' ) : ( $follower_count == 0 ? esc_html__( 'Followers', 'weekly-radio-schedule' ) : esc_html__( 'Follower', 'weekly-radio-schedule' ) );
				
				$this->tzwrs_notify_new_follower(strip_tags($current_user->display_name), intval($authorId));
				
				$debug = $subscriberID;

				echo json_encode(array("debug" => "{$debug}","followers_count" => "{$follower_count} {$plurality}","status" => "success"));
			}
			// } else {
				// echo json_encode(array("error" => "Please enter valid Email"));
			// }
		}
		die();
	}

	function tzwrs_notify_new_follower($follower_name, $authorId) {
		global $wpdb;

		//BUILD USER NOTIFICATION EMAIL
		//Get e-mail template
		$followee = get_user_by( 'ID', $authorId);
		if ( ! empty( $followee ) ) {
			$enabled = intval( get_option( TZWRS_OPTION_NAME . '_wrs_send_follower_notifications' ) );
			$permission = get_user_meta( $authorId, 'user_follower_notes' , true );
			$opted_in = false;
			$follower_text = sprintf( esc_html__( '%1s is following you', 'weekly-raadio-schedule' ), $follower_name );
					
			if ( ! empty( $permission ) ) {
				$opted_in = true;
			}
			if ( $enabled && $opted_in )
			{
				$icons = '<nav class="footer-navigation">
					<ul class="footer-navigation-wrapper">';
						$icons .= wp_nav_menu(
							array(
								'theme_location'	=> 'footer',
								'items_wrap'		=> '%3$s',
								'container'			=> false,
								'depth'				=> 1,
								'menu_class'		=> 'icons_case',
								'link_before'		=> '<span >',
								'link_after'		=> '</span>',
								'fallback_cb'		=> false,
								'echo'    			=> false,
							)
						);

				$icons .= '</ul><!-- .footer-navigation-wrapper -->
				</nav><!-- .footer-navigation -->';
				$connectwithus = esc_html__( 'Connect with us.', 'weekly-radio-schedule' );
				$contactus_url = get_home_url( '/contact_us/' );
				$contactus_message = sprintf( esc_html__( 'If you have questions, comments or trouble logging in, please <a href="%1s">contact us</a>.', 'weekly-radio-schedule' ), esc_url($contactus_url));

				$home_url = get_home_url();
				$privacypolicy_url = get_home_url('/privacy-policy/');
				$logo = get_theme_mod( 'custom_logo' );
				$image = wp_get_attachment_image_src( $logo, 'full' );
				$image_url = esc_url($image[0]);
				$message_template = file_get_contents(TZWRS_DIRECTORY . 'public/templates/email-follower-notification.html');
				//replace placeholders with user-specific content
				$sw_year = date('Y');
				$message = str_ireplace('[follower_text]', $follower_text, $message_template);
				$message = str_ireplace('[site_logo]', $image_url , $message);
				$message = str_ireplace('[icons]', $icons, $message);
				$message = str_ireplace('[connectwithus]', $connectwithus, $message);
				$message = str_ireplace('[home_url]', $home_url, $message);
				$message = str_ireplace('[thankyou]', esc_html__( 'Thank you', 'weekly-radio-schedule' ), $message);
				$message = str_ireplace('[year]', $sw_year, $message);
				$message = str_ireplace('[contactus_message]', $contactus_message, $message);
				$message = str_ireplace('[privacypolicy]', sprintf( '<a href="%1s">' . esc_html__( 'Privacy Policy', 'weekly-radio-schedule' ) . '</a>', $privacypolicy_url ), $message);
				$message = str_ireplace('[bloginfo]', esc_attr(get_bloginfo()), $message);
				//Prepare headers for HTML
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: ' . esc_attr(get_bloginfo()) . ' <wordpress@reggaespace.com>' . "\r\n";

				//Send user notification email
				$to      = sanitize_email($followee->user_email);
				$subject = $follower_text . '!';
				$body    = $message;

				if( wp_mail( $to, $subject, $body, $headers ) ) {
					// echo('<p>Message successfully sent!</p>');
				} else {
					// echo('<p>Message delivery failed...</p>');
				}
			}
		}
	}

	function tzwrs_get_followers() {
		global $wpdb;
		$debug = '';
		$ajaxNonce = $_POST['nonce'];
		if (wp_verify_nonce($ajaxNonce, 'follow-nonce')) {
			$currAuthor = intval( $_POST['author_id'] );
			/*
			 * Get current author info with authors subscribers
			 */
			$authorResult = $wpdb->get_results(
				$wpdb->prepare(
					"select * from {$wpdb->prefix}wrs_author_followers where author_id=%d", $currAuthor 
				)
			);
			if (count($authorResult) == '1' ) {
				if ($authorResult[0]->followers_list != '') {
					$authorSubscribersArray = explode(",", $authorResult[0]->followers_list);
				} else {
					$authorSubscribersArray = array();
				}
				$followers = '';
				foreach ( $authorSubscribersArray as $key => $value ) {
					if ( $value != '' ) {
						$current_user = get_user_by( 'ID', $value);
						
						$followers .= '<span class="afollower">' . strip_tags($current_user->display_name) . '</span>';
					}
				}
			}
			echo json_encode(array("debug" => "{$debug}","followers" => "{$followers}","status" => "success"));
		}
		die();
	}
	
	function tzwrs_get_followees() {
		global $wpdb;
		$debug = '';
		$ajaxNonce = $_POST['nonce'];
		if (wp_verify_nonce($ajaxNonce, 'follow-nonce')) {
			$currAuthor = intval( $_POST['author_id'] );
			$current_user = get_user_by( 'ID', intval( $currAuthor ) );
			$subscriberEmail = sanitize_email($current_user->user_email);
			/*
			 * Get current author info with authors subscribers
			 */
			$authorResult = $wpdb->get_results(
				$wpdb->prepare(
					"select * from {$wpdb->prefix}wrs_author_subscribe where email=%s", $subscriberEmail 
				)
			);
			if (count($authorResult) == '1' ) {
				if ($authorResult[0]->followed_authors != '') {
					$authorSubscribersArray = explode(",", $authorResult[0]->followed_authors);
				} else {
					$authorSubscribersArray = array();
				}
				$followees = '';
				foreach ( $authorSubscribersArray as $key => $value ) {
					if ( $value != '' ) {
						$current_user = get_user_by( 'ID', $value);
						
						$followees .= '<span class="afollowee">' . strip_tags($current_user->display_name) . '</span>';
					}
				}
			}
			echo json_encode(array("debug" => "{$debug}","followees" => "{$followees}","status" => "success"));
		}
		die();
	}
	
	function tzwrs_unfollow_wrs_djs() {

		global $wpdb;

		$ajaxNonce = $_POST['nonce'];

		if (wp_verify_nonce($ajaxNonce, 'follow-nonce')) {

			$current_user = get_user_by( 'ID', intval( $_POST['currid'] ) );
			$subscriberEmail = sanitize_email($current_user->user_email);

			if (is_email($subscriberEmail)) {

				$emailResult = $wpdb->get_results(
					$wpdb->prepare(
						"select * from {$wpdb->prefix}wrs_author_subscribe where email=%s", $subscriberEmail
					)
				);

				$subscriberID = intval( $_POST['currid'] );
				$authorId = isset($_POST['author_id']) ? intval( $_POST['author_id'] ) : '';

				/*
				 * Get Author List of the Current User
				 */
				$subscribedAuthorList = $emailResult[0]->followed_authors;
				if ($subscribedAuthorList != '') {
					$subscribedAuthorList = explode(",", $subscribedAuthorList);
				} else {
					$subscribedAuthorList = array();
				}

				foreach ($subscribedAuthorList as $key => $value) {
					if ($authorId == $value) {
						unset($subscribedAuthorList[$key]);
					}
				}

				/*
				 * Get current author info with authers subscribers
				 */
				$table_name = $wpdb->prefix . 'wrs_author_followers';
				$authorResult = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE author_id = ' . intval( $_POST['author_id'] ) );

				if (count($authorResult) == '1') {

					if ($authorResult[0]->followers_list != '') {
						$authorSubscribersArray = explode(",", $authorResult[0]->followers_list);
					} else {
						$authorSubscribersArray = array();
					}

					foreach ($authorSubscribersArray as $key => $value) {
						if ($subscriberID == $value) {
							unset($authorSubscribersArray[$key]);
						}
					}

					// User list who follows specific author
					$followersList = implode(",", $authorSubscribersArray);

					// Author list followed by specific user
					$subscribedAuthorList = implode(",", $subscribedAuthorList);

					$table_name = $wpdb->prefix . 'wrs_author_followers';
					$result = $wpdb->query(
					$wpdb->prepare( "UPDATE " . $table_name . " SET followers_list = %s WHERE author_id = %d;", $followersList, intval( $_POST['author_id'] ) )
					);

					$table_name = $wpdb->prefix . 'wrs_author_subscribe';
					$result = $wpdb->query(
					$wpdb->prepare( "UPDATE " . $table_name . " SET followed_authors = %s WHERE email = %s;", $subscribedAuthorList, $subscriberEmail )
					);
					
					$table_name = $wpdb->prefix . 'wrs_author_followers';
					$authorResult = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE author_id = ' . $authorId );

					if (count($authorResult) == '1') {
						if ($authorResult[0]->followers_list != '') {
							$authorSubscribersArray = explode(",", $authorResult[0]->followers_list);
						} else {
							$authorSubscribersArray = array();
						}
						
						$follower_count = 0;
						foreach ($authorSubscribersArray as $key => $value) {
							if ( $value ) $follower_count = $follower_count + 1;
						}
					}
					$plurality = $follower_count > 1 ? esc_html__( 'Followers', 'weekly-radio-schedule' ) : ( $follower_count == 0 ? esc_html__( 'Followers', 'weekly-radio-schedule' ) : esc_html__( 'Follower', 'weekly-radio-schedule' ) );

					$debug = $subscriberID;

					echo json_encode(array("debug" => "{$debug}","followers_count" => "{$follower_count} {$plurality}","status" => "success"));
				}
			} else {
				echo json_encode(array("error" => "Please enter valid Email"));
			}
		}

		die();
	}

	/**
	 * Displays the site logo, either text or image.
	 *
	 * @param array   $args Arguments for displaying the site logo either as an image or text.
	 * @param bool    $echo Echo or return the HTML.
	 * @return string Compiled HTML based on our arguments.
	 */
	static function tzwrs_site_logo( $args = array(), $echo = true ) {
		$logo       = get_custom_logo();
		$site_title = strip_tags(get_bloginfo( 'name' ));
		$contents   = '';
		$classname  = '';

		$defaults = array(
			'logo'        => '%1$s<span class="screen-reader-text">%2$s</span>',
			'logo_class'  => 'site-logo',
			'title'       => '<a href="%1$s">%2$s</a>',
			'title_class' => 'site-title',
			'home_wrap'   => '<h1 class="%1$s">%2$s</h1>',
			'single_wrap' => '<div class="%1$s faux-heading">%2$s</div>',
			'condition'   => ( is_front_page() || is_home() ) && ! is_page(),
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * Filters the arguments for `twentytwenty_site_logo()`.
		 *
		 * @param array  $args     Parsed arguments.
		 * @param array  $defaults Function's default arguments.
		 */
		// $args = apply_filters( 'twentytwenty_site_logo_args', $args, $defaults );

		if ( has_custom_logo() ) {
			$contents  = sprintf( $args['logo'], $logo, esc_html( $site_title ) );
			$classname = strip_tags($args['logo_class']);
		} else {
			$contents  = sprintf( $args['title'], esc_url( get_home_url( null, '/' ) ), esc_html( $site_title ) );
			$classname = strip_tags($args['title_class']);
		}

		$wrap = $args['condition'] ? 'home_wrap' : 'single_wrap';

		$html = sprintf( $args[ $wrap ], $classname, $contents );

		/**
		 * Filters the arguments for `twentytwenty_site_logo()`.
		 *
		 * @param string $html      Compiled HTML based on our arguments.
		 * @param array  $args      Parsed arguments.
		 * @param string $classname Class name based on current view, home or single.
		 * @param string $contents  HTML for site title or logo.
		 */
		// $html = apply_filters( 'twentytwenty_site_logo', $html, $args, $classname, $contents );

		if ( ! $echo ) {
			return $html;
		}

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/*
	 * Send Emails when followee post is published
	 */
	function tzwrs_notify_dj_followers($post) {
		if (get_post_type($post) !== 'post')
			return;
		//Don't touch anything that's not a post (i.e. ignore links and attachments and whatnot )
		//If some variety of a draft is being published, dispatch an email
		if( ( 'draft' === $old_status || 'auto-draft' === $old_status ) && $new_status === 'publish' ) {
			global $wpdb;

			$publishedPostAuthor = intval($post->post_author);

			$authorDisplayName = strip_tags(get_the_author_meta('display_name', $publishedPostAuthor));
			$newpost_text = sprintf( esc_html__( 'New post by %1s', 'weekly-radio-schedule'), $authorDisplayName);

			$authorsFollowers = $wpdb->get_results(
				$wpdb->prepare(
					"select * from {$wpdb->prefix}wrs_author_followers where author_id=%s",$publishedPostAuthor
				)
			);

			if (count($authorsFollowers) == '1') { 
				$authorsFollowersList = $authorsFollowers[0]->followers_list;

				if ($authorsFollowersList != '') {
					$sql = "select email from {$wpdb->prefix}wrs_author_subscribe WHERE id IN ('%s')";
					$authorsFollowersEmails = $wpdb->get_results($wpdb->prepare($sql,$authorsFollowersList));

					$bccList = '';
					$emailHeaders = '';

					foreach ($authorsFollowersEmails as $key => $emailObject) {
						$user = get_user_by( 'email', $emailObject->email );
						if ( ! empty( $user ) ) {
							$enabled = intval( get_option( TZWRS_OPTION_NAME . '_wrs_send_post_notifications' ) );
							$permission = get_user_meta( $user->ID, 'user_post_notes' , true );
							if ( ! empty( $permission ) ) {
								$opted_in = true;
							}
							if ( $enabled && $opted_in )
							{
								$bccList .= sanitize_email( $emailObject->email ) . ",";
							}
						}
					}

					$bccList = substr($bccList, 0, -1);

					//BUILD USER NOTIFICATION EMAIL
					$icons = '<nav class="footer-navigation">
						<ul class="footer-navigation-wrapper">';
					$icons .= wp_nav_menu(
						array(
							'theme_location'	=> 'footer',
							'items_wrap'		=> '%3$s',
							'container'			=> false,
							'depth'				=> 1,
							'menu_class'		=> 'icons_case',
							'link_before'		=> '<span >',
							'link_after'		=> '</span>',
							'fallback_cb'		=> false,
							'echo'    			=> false,
						)
					);
					$icons .= '</ul><!-- .footer-navigation-wrapper -->
					</nav><!-- .footer-navigation -->';

					//Get e-mail template
					$logo = get_theme_mod( 'custom_logo' );
					$image = wp_get_attachment_image_src( $logo , 'full' );
					$image_url = esc_url($image[0]);
					$home_url = get_home_url();
					$privacypolicy_url = get_home_url('/privacy-policy/');
					$connectwithus = esc_html__( 'Connect with us.', 'weekly-radio-schedule' );
					$contactus_url = get_home_url( '/contact_us/' );
					$contactus_message = sprintf( esc_html__( 'If you have questions, comments or trouble logging in, please <a href="%1s">contact us</a>.', 'weekly-radio-schedule1' ), $contactus_url );

					$icons = '<nav class="footer-navigation">
						<ul class="footer-navigation-wrapper">';

							$icons .= wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'items_wrap'     => '%3$s',
									'container'      => false,
									'depth'          => 1,
									'link_before'    => '<span>',
									'link_after'     => '</span>',
									'fallback_cb'    => false,
									'echo'    => false,
								)
							);

					$icons .= '</ul><!-- .footer-navigation-wrapper -->
					</nav><!-- .footer-navigation -->';

					
					$message_template = file_get_contents(TZWRS_DIRECTORY . 'public/templates/email-post-notification.html');
					//replace placeholders with user-specific content
					$sw_year = date('Y');
					$message = str_ireplace('[site_logo]', $image_url, $message);
					$message = str_ireplace('[dj_name]', $authorDisplayName, $message);
					$message = str_ireplace('[post_title]', $post->post_title, $message);
					$message = str_ireplace('[post_url]', $post->guid, $message);
					$message = str_ireplace('[connectwithus]', $connectwithus, $message);
					$message = str_ireplace('[home_url]', $home_url, $message);
					$message = str_ireplace('[thankyou]', esc_html__( 'Thank you', 'weekly-radio-schedule' ), $message);
					$message = str_ireplace('[contactus_message]', $contactus_message, $message);
					$message = str_ireplace('[newpost_text]', $newpost_text, $message);
					$message = str_ireplace('[icons]',$icons, $message);
					$message = str_ireplace('[year]', $sw_year, $message);
					$message = str_ireplace('[bloginfo]', esc_attr(get_bloginfo()), $message);
					$message = str_ireplace('[privacypolicy]', sprintf( '<a href="%1s">' . esc_html__( 'Privacy Policy', 'weekly-radio-schedule' ) . '</a>', $privacypolicy_url ), $message);
					//Prepare headers for HTML
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: ' . esc_attr(get_bloginfo()) . ' <wordpress@tuzongo.com>' . "\r\n";
					$headers .= "Bcc: $bccList" . "\r\n";
					//Send user notification email
					if (wp_mail( sanitize_email( get_option( 'admin_email' ) ), $newpost_text . '!', $message, $headers )) {
						
					}
				}
			}
		}
		//die();
	}

	function tzwrs_mail_on_publish( $new_status, $old_status, $post ) {
		if (get_post_type($post) !== 'post')
			return;    //Don't touch anything that's not a post (i.e. ignore links and attachments and whatnot )
		global $wpdb;

		//If some variety of a draft is being published, dispatch an email
		if ( get_option( TZWRS_OPTION_NAME . '_wrs_send_post_notifications' ) ) {

			if( ( 'pending' === $old_status || 'future' === $old_status || 'draft' === $old_status || 'auto-draft' === $old_status ) && $new_status === 'publish' ) {
				$publishedPostAuthor = $post->post_author;

				$authorDisplayName = strip_tags(get_the_author_meta('display_name', $publishedPostAuthor));
				$newpost_text = sprintf( esc_html__( 'New post by %1s', 'weekly-radio-schedule'), $authorDisplayName);
				
				$authorsFollowers = $wpdb->get_results(
					$wpdb->prepare(
						"select * from {$wpdb->prefix}wrs_author_followers where author_id=%s",$publishedPostAuthor
					)
				);
				if (count($authorsFollowers) == '1') { 
					$authorsFollowersList = $authorsFollowers[0]->followers_list;

					if ($authorsFollowersList != '') {
						$sql = "select email from {$wpdb->prefix}wrs_author_subscribe WHERE id IN ('%s')";
						$authorsFollowersEmails = $wpdb->get_results($wpdb->prepare($sql,$authorsFollowersList));

						$bccList = '';
						$emailHeaders = '';

						foreach ($authorsFollowersEmails as $key => $emailObject) {
							$user = get_user_by( 'email', $emailObject->email );
							if ( get_the_author_meta('user_post_notes', $user->ID)=='on' ) {
								$bccList .= sanitize_email( $emailObject->email ) . ",";
							}
						}

						$bccList = substr($bccList, 0, -1);

						//BUILD USER NOTIFICATION EMAIL
						//Get e-mail template
						$privacypolicy_url = get_home_url('/privacy-policy/');
						$connectwithus = esc_html__( 'Connect with us.', 'weekly-radio-schedule' );
						$contactus_url = get_home_url( '/contact_us/' );
						$contactus_message = sprintf( esc_html__( 'If you have questions, comments or trouble logging in, please <a href="%1s">contact us</a>.', 'weekly-radio-schedule1' ), $contactus_url );
						$icons = '<nav class="footer-navigation">
							<ul class="footer-navigation-wrapper">';
								$icons .= wp_nav_menu(
									array(
										'theme_location'	=> 'footer',
										'items_wrap'		=> '%3$s',
										'container'			=> false,
										'depth'				=> 1,
										'menu_class'		=> 'icons_case',
										'link_before'		=> '<span >',
										'link_after'		=> '</span>',
										'fallback_cb'		=> false,
										'echo'    			=> false,
									)
								);

						$icons .= '</ul><!-- .footer-navigation-wrapper -->
						</nav><!-- .footer-navigation -->';
						$logo = get_theme_mod( 'custom_logo' );
						$image = wp_get_attachment_image_src( $logo, 'full' );
						$image_url = esc_url($image[0]);
						$message_template = file_get_contents(TZWRS_DIRECTORY . 'public/templates/email-post-notification.html');
						$home_url = get_home_url();
						//replace placeholders with user-specific content
						$sw_year = date('Y');
						$message = str_ireplace('[dj_name]', $authorDisplayName, $message_template);
						$message = str_ireplace('[contactus_message]', $contactus_message, $message);
						$message = str_ireplace('[site_logo]', $image_url, $message);
						$message = str_ireplace('[connectwithus]', $connectwithus, $message);
						$message = str_ireplace('[thankyou]', esc_html__( 'Thank you', 'weekly-radio-schedule' ), $message);
						$message = str_ireplace('[newpost_text]', $newpost_text, $message);
						$message = str_ireplace('[post_title]', $post->post_title, $message);
						$message = str_ireplace('[post_url]', $post->guid, $message);
						$message = str_ireplace('[home_url]', $home_url, $message);
						$message = str_ireplace('[privacypolicy]', sprintf( '<a href="%1s">' . esc_html__( 'Privacy Policy', 'weekly-radio-schedule' ) . '</a>', $privacypolicy_url ), $message);
						$message = str_ireplace('[bloginfo]', esc_attr(get_bloginfo()), $message);
						$message = str_ireplace('[year]', $sw_year, $message);
						//Prepare headers for HTML
						$message = str_ireplace('[icons]', $icons, $message);
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: ' . esc_attr(get_bloginfo()) . ' <wordpress@reggaespace.com>' . "\r\n";
						$headers .= "Bcc: $bccList" . "\r\n";
					}
				}
				$to      = sanitize_email( get_option( 'admin_email' ) );
				$subject = $newpost_text . '!';
				$body    = $message;

				if( wp_mail( $to, $subject, $body, $headers ) ) {
					// echo('<p>Message successfully sent!</p>');
				} else {
					// echo('<p>Message delivery failed...</p>');
				}
			}
		}
	}

	function tzwrs_notify_new_slot($dj_id, $slot_id, $weekmark) {
		global $wpdb;
		
		if ( get_option( TZWRS_OPTION_NAME . '_wrs_send_slot_notifications' ) ) { 

			$table_name = $wpdb->prefix . 'wrs_' . $weekmark . '_week';
			$hour = intval($wpdb->get_var( $wpdb->prepare( "SELECT hour FROM $table_name WHERE slot_id = %d", $slot_id ) ) );

			if (($hour-1) % 24 == gmdate("H",current_time( 'timestamp' )))
			{
				$hour_text = esc_html__( 'Is On Air', 'weekly-radio-schedule' );
				$timing = $hour_text;
				$this_day = isset($_GET['day'])? $_GET['day']: gmdate("d", current_time( 'timestamp' ));
				$this_month = isset($_GET['month'])? $_GET['month']: gmdate("m", current_time( 'timestamp' ));
				$this_year = isset($_GET['year'])? $_GET['year']: gmdate("Y", current_time( 'timestamp' ));
				$w = date('w',gmmktime(0,0,0,$this_month,$this_day,$this_year));//Week day
				
				$this_start_date = gmmktime(0,0,0, $this_month, $this_day-$w, $this_year);// Timestamp of start of week
				$its_show_time = $this_start_date + (($hour-1)*60*60);
			}
			else
			{
				$this_day = isset($_GET['day'])? $_GET['day']: gmdate("d", current_time( 'timestamp' ));
				$this_month = isset($_GET['month'])? $_GET['month']: gmdate("m", current_time( 'timestamp' ));
				$this_year = isset($_GET['year'])? $_GET['year']: gmdate("Y", current_time( 'timestamp' ));
				$w = date('w',gmmktime(0,0,0,$this_month,$this_day,$this_year));//Week day
				
				$this_start_date = gmmktime(0,0,0, $this_month, $this_day-$w, $this_year);// Timestamp of start of week
				$its_show_time = $this_start_date + (($hour-1)*60*60);
				if ( $weekmark === 'next' )
					$its_show_time = $its_show_time + 604800;

				$hour_text = Tz_Weekly_Radio_Schedule_Public::tzwrs_time_ago2(gmdate("Y",$its_show_time).'-'.gmdate("m",$its_show_time).'-'.gmdate("d",$its_show_time).' '.gmdate("H",$its_show_time).':00:00');
				$timing = sprintf( esc_html__( 'will be On Air %1s', 'weekly-radio-schedule' ), $hour_text );
			}
			$current_dj = get_user_by( 'ID', $dj_id);
			
			$authorsFollowers = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}wrs_author_followers WHERE author_id=%s",$dj_id
				)
			);
			if (count($authorsFollowers) == '1') { 
				$authorsFollowersList = $authorsFollowers[0]->followers_list;

				if ($authorsFollowersList != '') {
					$sql = "select email from {$wpdb->prefix}wrs_author_subscribe WHERE id IN ('%s')";
					$authorsFollowersEmails = $wpdb->get_results($wpdb->prepare($sql,$authorsFollowersList));

					$bccList = '';
					$emailHeaders = '';

					foreach ($authorsFollowersEmails as $key => $emailObject) {
						$user = get_user_by( 'email', $emailObject->email );
						if ( get_the_author_meta('user_slot_notes', $user->ID)=='on' ) {
							$bccList .= sanitize_email( $emailObject->email ) . ",";
						}
					}

					$bccList = substr($bccList, 0, -1);

					//BUILD USER NOTIFICATION EMAIL
					//Get e-mail template
					$home_url = get_home_url();
					$privacypolicy_url = get_home_url('/privacy-policy/');
					$connectwithus = esc_html__( 'Connect with us.', 'weekly-radio-schedule' );
					$contactus_url = get_home_url( '/contact_us/' );
					$contactus_message = sprintf( esc_html__( 'If you have questions, comments or trouble logging in, please <a href="%1s">contact us</a>.', 'weekly-radio-schedule1' ), $contactus_url );
					
					$icons = '<nav class="footer-navigation">
						<ul class="footer-navigation-wrapper">';
							$icons .= wp_nav_menu(
								array(
									'theme_location'	=> 'footer',
									'items_wrap'		=> '%3$s',
									'container'			=> false,
									'depth'				=> 1,
									'menu_class'		=> 'icons_case',
									'link_before'		=> '<span >',
									'link_after'		=> '</span>',
									'fallback_cb'		=> false,
									'echo'    			=> false,
								)
							);

					$icons .= '</ul><!-- .footer-navigation-wrapper -->
					</nav><!-- .footer-navigation -->';
					$logo = get_theme_mod( 'custom_logo' );
					$image = wp_get_attachment_image_src( $logo , 'full' );
					$image_url = esc_url($image[0]);
					$contact_us = 'If you have questions, comments or trouble logging in, please <a href="[home_url]/contact-us/">contact us</a>.';
					$message_template = file_get_contents(TZWRS_DIRECTORY . 'public/templates/email-slot-notification.html');
					
					//replace placeholders with user-specific content
					$sw_year = date('Y');
					
					$message = str_ireplace('[dj_name]', $current_dj->display_name, $message_template);
					$message = str_ireplace('[site_logo]', $image_url, $message);
					$message = str_ireplace('[timing]', $timing, $message);
					$message = str_ireplace('[explanation]', $current_dj->display_name . ' has taken a new slot at ' . gmdate('h:i A l jS', $its_show_time) . ' ' . wp_timezone_string() . ' (GMT ' . ( get_option( 'gmt_offset' ) < 0 ? '' . get_option( 'gmt_offset' ) : '+' . get_option( 'gmt_offset' ) ) . ')', $message);
					$message = str_ireplace('[connectwithus]', $connectwithus, $message);
					$message = str_ireplace('[contactus_message]', $contactus_message, $message);
					$message = str_ireplace('[thankyou]', esc_html__( 'Thank you', 'weekly-radio-schedule' ), $message);
					$message = str_ireplace('[privacypolicy]', sprintf( '<a href="%1s">' . esc_html__( 'Privacy Policy', 'weekly-radio-schedule' ) . '</a>', esc_url($privacypolicy_url )), $message);
					$message = str_ireplace('[bloginfo]', esc_attr(get_bloginfo()), $message);
					$message = str_ireplace('[year]', $sw_year, $message);
					$message = str_ireplace('[icons]', $icons, $message);
					$message = str_ireplace('[home_url]', $home_url, $message); // Prepare headers for HTML
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: ' . esc_attr(get_bloginfo()) . ' <wordpress@reggaespace.com>' . "\r\n";
					$headers .= "Bcc: $bccList" . "\r\n";
				}
			}

			if (wp_mail( sanitize_email( get_option( 'admin_email' ) ), strip_tags($current_dj->display_name) . ' ' . $timing, $message, $headers )) {
				// echo('<p>Message successfully sent!</p>');
			} else {
				// echo('<p>Message delivery failed...</p>');
			}
		}
	}
}

function tzwrs_reset_schedule()
{
	global $wpdb;

	$table_this_name = $wpdb->prefix . 'wrs_this_week';
	$table_next_name = $wpdb->prefix . 'wrs_next_week';
	
	$wpdb->query("DROP TABLE IF EXISTS {$table_this_name}");

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_this_name (
	  slot_id mediumint(8) NOT NULL DEFAULT '0',
	  user_id mediumint(8) DEFAULT '0',
	  hour mediumint(8) NOT NULL DEFAULT '0',
	  add_dj tinyint(1) DEFAULT NULL,
	  temp_user_id mediumint(8) DEFAULT '0',
	  attended tinyint(1) DEFAULT '0',
	  love_count mediumint(8) DEFAULT '0',
	  PRIMARY KEY  (slot_id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	$sql = "INSERT INTO $table_this_name SELECT * FROM $table_next_name";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	$rows_affected = $wpdb->query(
		$wpdb->prepare( "UPDATE " . $table_next_name . " SET add_dj = %d, temp_user_id = %d, love_count = %d;", 0, 0, 0 )
	);
}

if ( ! wp_next_scheduled( 'tzwrs_reset_schedule_cron_hook' ) ) {
	// Get timestamp for Sunday coming at Midnight
	$local_time  = current_datetime();
	$current_time = $local_time->getTimestamp() + $local_time->getOffset();
	$day = gmdate("d",$current_time);
	$month = gmdate("m",$current_time);
	$year = gmdate("Y",$current_time);
	
	$w = gmdate('w',mktime(0,0,0,$month,$day,$year));//Week day 
	$sun_start = mktime(0, 0, 0, $month, $day-$w, $year) + 604800; //Timestamp of Sunday Midnight
    wp_schedule_event( $sun_start, 'weekly', 'tzwrs_reset_schedule_cron_hook' );
}


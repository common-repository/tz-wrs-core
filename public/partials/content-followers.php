<?php
/**
 * The template for displaying number of DJ followers
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
 
$curauth = '';
if ( $data->user_id ) {
	$author_id = intval($data->user_id);
	$curauth = get_user_by('ID', $author_id);
	$div_classes = '';
}
else 
{
	if ( isset($wp->query_vars['author_name']) ) {
		$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($wp->query_vars['author_name']));
		$curauth = get_user_by('slug', $wp->query_vars['author_name']);
	}
	else 
	{
		if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
			$url = PeepSoUrlSegments::get_instance();
			if ( $url->get(1) )
			{
				intval($author_id = Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($url->get(1)));
				$curauth = get_user_by('ID', $author_id);
			}
		}
	}
	$div_classes = 'wrs_profile_id side';
}

if ( $curauth )
{
	$current_user = wp_get_current_user();
?>
<div class="<?php echo strip_tags($div_classes); ?>"><div class="wrs_profile_id_wrap section-inner medium wrsplugin">
	<?php 
		$table_name = $wpdb->prefix . 'wrs_author_subscribe';
		$authorResult = $wpdb->get_results(
			$wpdb->prepare(
				"select * from {$table_name} where email=%s", $curauth->user_email 
			)
		);
		$followee_count = 0;
		if (count($authorResult) == '1') {
			if ($authorResult[0]->followed_authors != '') {
				$authorSubscribersArray = explode(",", $authorResult[0]->followed_authors);
			} else {
				$authorSubscribersArray = array();
			}

			foreach ($authorSubscribersArray as $key => $value) {
				if ( $value ) $followee_count = $followee_count + 1;
			}
		}
		
		$table_name = $wpdb->prefix . 'wrs_author_followers';
		$authorResult = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE author_id = ' . $curauth->ID );
		$follower_count = 0;
		if (count($authorResult) == '1') {
			if ($authorResult[0]->followers_list != '') {
				$authorSubscribersArray = explode(",", $authorResult[0]->followers_list);
			} else {
				$authorSubscribersArray = array();
			}

			foreach ($authorSubscribersArray as $key => $value) {
				if ( $value ) $follower_count = $follower_count + 1;
			}
		}
		$plurality = $follower_count > 1 ? ' ' . __( 'Followers' ) : ( $follower_count == 0 ? ' ' . __( 'Followers' ) : ' ' . __( 'Follower' ) );
		$pluralety = $followee_count > 1 ? ' ' . __( 'Following' ) : ( $followee_count == 0 ? ' ' . __( 'Following' ) : ' ' . __( 'Following' ) );
		
		$emailResult = $wpdb->get_results(
			$wpdb->prepare(
				"select * from {$wpdb->prefix}wrs_author_subscribe where email=%s", $current_user->user_email
			)
		);

		$following_class = 'dj_follow';
		$following_value = __( 'Follow', 'weekly-radio-schedule' );
		$follow_html = '<div class="dj_follow_case"><input type="button" class="logged_out" value="' . $following_value . '" data-author="' . $curauth->ID . '" data-url="' . esc_url( wp_login_url( get_permalink() ) ) . '" /></div>';
		if ( isset( $emailResult[0] ) ) {
			if ($emailResult[0]->followed_authors != '') {
				$authorSubscribersArray = explode(",", $emailResult[0]->followed_authors);
				if ((in_array($curauth->ID, $authorSubscribersArray))) {
					$following_class = 'dj_following following';
					$following_value = __( 'Following', 'weekly-radio-schedule' );
				}
			}
		}
		if ( is_user_logged_in() ) {
			$follow_html = '<div class="dj_follow_case"><input type="button" class="' . strip_tags($following_class) . ' ' . intval($curauth->ID) . '" value="' . esc_attr($following_value) . '" data-author="' . esc_attr($curauth->ID) . '" />' . ( $data->user_id ? ' <span>' . strip_tags($curauth->display_name) . '</span>' : '' ) . '</div>';
		}
		echo $follow_html;
		?>
		<span class="followers_text hiding" data-author="<?php echo esc_attr($curauth->ID); ?>" id="loadFollowers"><?php echo strip_tags($follower_count . $plurality); ?></span>
		<span class="followees_text hiding" data-author="<?php echo esc_attr($curauth->ID); ?>" id="loadFollowees"><?php echo strip_tags($followee_count . $pluralety); ?></span>
		<div class="followers"></div><div class="followees"></div>
	</div></div>
	<?php
}
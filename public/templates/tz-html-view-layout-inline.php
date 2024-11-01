<?php
/**
 * Frontend View: Layout - Inline
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$post_id = is_front_page() && ! is_page() ? '-1' : get_the_ID();

$counter = 'yes' == get_option( 'easy_social_sharing_inline_enable_share_counts' ) ? 'ess-display-counts' : 'ess-no-display-counts';

$visbility_class = array();

if ( 'rounded' == get_option( 'easy_social_sharing_inline_icon_shape' ) ) {
	$visbility_class[] = 'ess-rounded-icon';
} elseif ( 'diagonal' == get_option( 'easy_social_sharing_inline_icon_shape' ) ) {
	$visbility_class[] = 'ess-diagonal-icon';
} elseif ( 'rectangular_rounded' == get_option( 'easy_social_sharing_inline_icon_shape' ) ) {
	$visbility_class[] = 'ess-rectangular-rounded-icon';
}

if ( $inline_layout = get_option( 'easy_social_sharing_inline_layouts' ) ) {
	$visbility_class[] = 'ess-inline-layout-' . $inline_layout;
}

if ( 'no' == get_option( 'easy_social_sharing_inline_enable_share_counts' ) ) {
	$visbility_class[] = 'ess-no-share-counts';
}

if ( 'no' == get_option( 'easy_social_sharing_inline_enable_networks_label' ) ) {
	$visbility_class[] = 'ess-no-network-label';
}

$social_networks = ess_get_core_supported_social_networks();


if ( $place == 'panel' )
{
	global $wpdb;

	$slotid = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_slotid());
	$table_name = $wpdb->prefix . 'wrs_this_week';
	$row = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE slot_id = ' . $slotid );
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
	$profile_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $current_user_id ) ) );
	if ( is_plugin_active( 'easy-social-sharing/easy-social-sharing.php' ) && is_plugin_active( 'peepso-core/peepso.php' ) ) {
		$profile_url = esc_url(PeepSoUser::get_instance($current_user_id)->get_profileurl() .'&' . current_time('timestamp'));
	}
	$user = get_userdata( $current_user_id );
	$display_name = strip_tags($user->display_name);
	$title = strip_tags($display_name . ' on ' . get_bloginfo());
}
elseif ( $place == 'peepsop' )
{
	global $wp;
	
	$curauth = '';
	if ( isset($wp->query_vars['author_name']) ) {
		$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($wp->query_vars['author_name']));
		$curauth = get_user_by('slug', $wp->query_vars['author_name']);
	}
	else 
	{
		if ( is_plugin_active( 'peepso-core/peepso.php' ) ) {
			$url = esc_url(PeepSoUrlSegments::get_instance());
			if ( $url->get(1) )
			{
				$author_id = intval(Tz_Weekly_Radio_Schedule_Public::tzwrs_get_user_id_by_nicename($url->get(1)));
				$curauth = get_user_by('ID', $author_id);
			}
		}
	}
	if ( $curauth )
	{

		$profile_url = esc_url(PeepSoUser::get_instance($curauth->ID)->get_profileurl() .'?' . current_time('timestamp'));

		$display_name = $curauth->display_name;
		$title = strip_tags($display_name . ' on ' . get_bloginfo());
	}
}
else
{
	global $wp;
	
	$curauth = '';
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
	if ( $curauth )
	{
		$profile_url = esc_url( get_author_posts_url( $curauth->ID ) ) .'?' . current_time('timestamp');

		$display_name = $curauth->display_name;
		$title = strip_tags($display_name . ' on ' . get_bloginfo());
	}
}
?>

	<div id="ess-wrap-inline-networks" class="et_social_inline et_social_mobile_on et_social_inline_bottom ess-inline-networks-container ess-clear <?php echo esc_attr( implode( ' ', $visbility_class ) ); ?>">
		<ul class="ess-social-network-lists et_social_icons_container">
			<?php foreach ( $allowed_networks as $network ) :
				$pinterest = ( 'pinterest' == $network ) ? 'ess-social-share-pinterest' : 'ess-social-share'; ?>
				<li class="ess-social-networks ess-<?php echo esc_attr( $network ); ?> ess-spacing ess-social-sharing">
					<a href="<?php echo esc_url( Tz_Weekly_Radio_Schedule_Public::tzwrs_ess_share_link( $network, '', '', $profile_url, $title ) ); ?>" class="ess-social-network-link <?php echo esc_attr( $pinterest . ' ' . $counter ); ?>" rel="nofollow" data-social-name="<?php echo esc_attr( $network ); ?>" data-min-count="<?php echo esc_attr( $network_count[ $network ] ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-location="inline">
						<span class="inline-networks socicon ess-icon socicon-<?php echo esc_attr( $network ); ?>" data-tip="<?php echo ess_sanitize_tooltip( $network_desc[ $network ] ); ?>"></span>
					</a>
				</li>
			<?php endforeach; ?>
			<?php if ( 'maybe' == get_option( 'easy_social_sharing_inline_enable_all_networks' ) ) : ?>
				<li class="ess-all-networks ess-social-networks">
					<div class="ess-social-network-link">
						<span class="ess-all-networks-button"><i aria-hidden="true" class="fa fa-ellipsis-h"></i></span>
					</div>
				</li>
			<?php endif; ?>
		</ul>
	</div>


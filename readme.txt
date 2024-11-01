=== Tz Weekly Radio Schedule ===
Contributors: Lineone
Donate link: https://www.tuzongo.com/donate/
Tags: radio, schedule, weekly, pages, followers, messaging, notifications, social, social media, sharing, share, DJ, DJs, Presenter, Presenters, radio station, online radio
Requires PHP: 7.0
Requires at least: 4.6
Tested up to: 5.8
Stable tag: 1.8.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Weekly Radio Schedule provides an ajax-driven schedule page, creates Team roles, presents up-to-date schedule information, allows easy allocation of slots and allows DJs / Presenter's to manage their personal slots.

== Description ==

* Makes running your radio station schedule easy
* Removes all confusion about who is playing and when
* Drives greater listener engagement
* Makes managing time slots simple
* The Ajax-driven schedule page provides the backbone to turn a website into your online radio station hub.
* DJs / Presenters manage their slots with ease
* Schedule changes are reflected across the site in real-time

Whether you run an existing station or you want to start out on the right foot, the plugin is designed to integrate with other cool plugins so that you can create the ideal website for your station. Take a look at a fully integrated example [here](https://rdjm.tuzongo.com "Integrated Weekly Radio Schedule").

== Installation ==

Weekly Radio Schedule can be found and installed via the Plugin menu within WordPress administration (Plugins -> Add New). Alternatively, it can be downloaded from WordPress.org and installed manually...

1. Upload the entire `tz-wrs-core` folder to your `wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress administration.

Voila! It's ready to go.

== To begin ==

Require all Team members to register and allocate each to one of the roles created by Weekly Radio Schedule - DJ, DJ Operator, Operator or Manager (Dashboard -> Users). For fresh installations, consider inserting demo team data (Dashboard -> Settings -> Tz Weekly Radio Schedule). These dummy user accounts and slot allocations can be removed later and the schedule, easily updated with real Team member slots.

Consider assigning the WRS Main Menu to the main menu location.

Allocating time slots completes setup. See [Managing the Schedule](https://wrs.tuzongo.com/docs/tz-weekly-radio-schedule/managing-the-schedule/ "Managing the Schedule") for steps on how this is done.

Be sure to read the complete [documentation](https://wrs.tuzongo.com/docs/ "WRS Documentation") for Weekly Radio Schedule.

== Shortcodes ==

Today’s Shows
`[[shows_today title="What\'s on" pic_size="64"]]`

Designed for sidebar or column. Displays details of shows scheduled for today with times, avatars, show names and show descriptions. Description text limit set by WRS General Settings – Max description characters. Use the following attributes:
`title` : The title above list of shows (default: Today’s Shows)
`pic_size` : The height / width of avatar image in pixels (default: 96)

My Shows
`[[my_shows id="6"]]`

Designed for sidebar or column. Displays details of scheduled shows this week for particular DJ / Presenter with times, avatar, show name and show description. Use the following attributes:
`id` : The user ID of Team member (required)
`textsize` : The number of characters to limit description text to (default: WRS General Settings – Max description characters)

This week’s Shows
`[[tabbed_coming_up]]`

Designed for full width of page content. Displays the public view of this week’s schedule with times, avatars, show names and show descriptions.
Avatars size set by WRS General Settings – Default avatar size
Description text limit set by WRS General Settings – Max description characters

Followers
`[[wrs_followers user_id="46"]]`

Designed for sidebar or column. Displays Follow button for particular DJ / Presenter with details of followers and following.
Description text limit set by WRS General Settings – Max description characters
`user_id` : The user ID of Team member (required)

The Team
`[[the_team]]`

Designed for full width of page content. Displays station Team i.e. Manager(s), Operator(s) and DJs / Presenters with roles, avatars, show names and show descriptions.
Avatars size set by WRS General Settings – Default avatar size
Description text limit set by WRS General Settings – Max description characters

Shows Picker
`[[shows_picker]]`

Designed for sidebar or column. Initial state displays details of scheduled shows for today with times, avatars, show names and show descriptions. Allows user to select a particular DJ / Presenter to display their avatar, show name, show description and scheduled shows.
Avatars size set by WRS General Settings – Default avatar size
Description text limit set by WRS General Settings – Max description characters

Upcoming Shows
`[[shows_coming pic_size="100" max_chars="200"]]`

Designed for sidebar or column. Randomly fades between up to nofshows shows with times, avatars, show names and show descriptions. Use the following attributes:

`pic_size` : The height / width of avatar image in pixels (default: WRS General Settings – Default avatar size)
`max_chars` : The number of characters to limit description text to (default: WRS General Settings – Max description characters)
Designed for sidebar or column.

== Frequently Asked Questions ==

= The Schedule page doesn’t look right. What’s wrong? =

The Schedule page of Weekly Radio Schedule works best on pages with a single column. If your theme sets a different page layout by default, it may be possible to change the template that is selected for the Schedule page.

= Will Weekly Radio Schedule work in my time zone? =

Yes. Weekly Radio Schedule respects the time zone selected in Settings > General.

= Can Weekly Radio Schedule work with my theme? =

Yes. Weekly Radio Schedule should work with most WordPress themes. Any appearances issues can usually be solved by adding code via the Customiser ‘Additional CSS or child theme.

Any more serious issues with a particular theme or plugin nay be addressed by developers and included in future versions.

== Screenshots ==
1. Show Picker
2. Team Widget or Shortcode
3. Team Schedule
4. Non-team Schedule
5. Fly-out Info Panel
6. Settings: Colour Scheme
7. Settings: Notifications

== Changelog ==

= 1.8.0 =
Initial release.

= 1.8.1 =
Minor correction.

== Upgrade Notice ==

= 1.8.1 =
Minor correction.
=== Plugin Name ===
Contributors: crisvangeel
Donate link: http://www.yellownote.nl/blog/donate
Tags: monitor, earthquake, widget, geological
Requires at least: 3.0
Tested up to: 4.2.3
Stable tag: 1.62
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Earthquake Monitor is a customizable widget that shows an overview of earthquakes around the world from the U.S. Geological Surveys data. 

== Description ==


Earthquake Monitor is a customizable widget that shows an overview of earthquakes around the world from the U.S. Geological Surveys data. 
This widget requires at least PHP 5.2 with json_decode enabled to work.

This widget has a build in cache support.


== Installation ==


1. Upload the folder `EarthquakeMonitor` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the widget to the sidebar. 
4. Customize the widget.

== Frequently Asked Questions ==

* Changing the feed is only effective when the cache timer is expired. Adjusting the cache timer to 1 second , refresh the page where the earthquake monitor is shown and restore the cache timer setting will work.
* When applying a filter, keep in mind that it filters for city and region. So be so explicit as possible.

== Screenshots ==

1. Screenshot of front-end
2. Screenshot of the backend

== Changelog ==

= 1.62 =

* Tested for wordpress 4.2.3 
* Minor text adjustments
* Minor aesthetic improvements
* Added banner
* Minor security update


= 1.61 =

* Tested for wordpress 4.0. No new features.

= 1.6 =

* Removed some old references.
* Removed function warning (if warnings are enabled)
* Added filter functionality.

= 1.5 =

* Partly recoded the engine to be up2date with the new feed provided by USGS.
* Removed the filter.
* Removed the location Trim
* Added a more detailed location summary (i.e. 32km N of Fishhook)


= 1.4 =

* Total new display method. Using a template system. This makes it much more customizable
* Added possibility to display the time past since the quake.
* Added possibility to display the time of the last quake.
* Added possibility to display the magnitude (separated from the location)
* Added possibility to display the latitude and longitude of the quake.
* Added possibility to display the depth (Metric (KM) and Imperial (Miles) of he quake.


= 1.3 =

* Bug fix : File were not stored at tmp directory.
* Better error handling when RSS file failed to read / refresh
* Build in check if tmp folder is writeable (This application makes use of the tmp folder)


= 1.2 =

* Added a filter. Now its possible to search only for earthquakes that have a certain word in the title.

= 1.1 =

* Added cache timer to cache the feed.
* Minor bug fixes

= 1.0 =

* Initial Release

== Upgrade Notice ==

Please read the Arbitrary section for more information about the new customizable field.

== Arbitrary section ==

* The display format field is now a customizable field. You determine how the widget displays the links within the 'limits' of this widget.
* You are allowed to use HTML tags in the display format field.
* If you make the Location linkable it will link to the USGS website providing detailed information about the quake.

Some examples to show you how the template variables work

Aprox. {hrtime} ago an earthquake with the {mag} struck {locreg} (Time {time}) . The exact latitude = {lat} and the longitude = {long}. The quake was measured at {depth_m} km depth.

would be parsed into 

Aprox. 2 hours ago an earthquake with the M 0.4 struck Northern California (Time Thu 21:33:17 (UTC)) . The exact latitude = 38.8402 and the longitude = -122.8250. The quake was measured at 2.10 km depth.

All earthquakes are placed in this HTML frame 

`
<ul>
<li>Earthquake text 1</li>
<li>Earthquake text 2</li>
</ul>
`



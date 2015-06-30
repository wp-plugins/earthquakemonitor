=== Plugin Name ===
Contributors: crisvangeel
Donate link: http://www.yellownote.nl/blog/donate
Tags: monitor, earthquake, widget, geological
Requires at least: 3.0
Tested up to: 4.2.3
Stable tag: 1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Earthquake Monitor is a customizable widget that shows an overview of earthquakes around the world from the U.S. Geological Surveys data. 

== Description ==


Earthquake Monitor is a customizable widget that shows an overview of earthquakes around the world from the U.S. Geological Surveys data. 
This widget requires at least PHP 5.2 with json_decode enabled to work.

This widget has a build in cache support.


== Installation ==


1. Upload the folder `EarthquakeMonitor` to the `/wp-content/plugins/` directory or search for Earthquake from the wordpress plugin page.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the widget to the sidebar. 
4. Customize the widget.

== Frequently Asked Questions ==

* How does the filter work? => When applying a filter, keep in mind that it filters for city and region. So be as explicit as possible.

== Screenshots ==

1. Screenshot of front-end
2. Screenshot of the backend

== Changelog ==

= 1.7 =

* Need PHP v5.3 or higher 
* If the earthquake is located in the middle of no-where (not in or near a city or area) it will display the Flinn-Engdahl Region name. This prevents empty rows with only a magnitude showing.
* Added optional display of PAGER (Prompt Assessment of Global Earthquakes for Response) color codes. ( See http://earthquake.usgs.gov/research/pager/ )
* Bug fix when names with ' appeared in TITLE link.
* Prefixed function-names to be more Unique.
* JSON Feed error is only visible in source code (so it won't destroy layout)
* Cache function improved (now using database instead of /tmp directory)
* Automatic refresh cache when feed properties are changed.
* New screenshots frontend and backend.
* Uninstall script present.
* Various readme.txt adjustments.


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

Minimal version PHP 5.3 needed.
You might want to consider removing the plugin prior to 1.7 by removing them and re-download the new plugin. All initial settings will be reset.

== Arbitrary section ==

* The display format field is now a customizable field. You determine how the widget displays the links within the 'limits' of this widget.
* You are allowed to use HTML tags in the display format field.
* If you make the Location linkable it will link to the USGS website providing detailed information about the quake.
* The date format is compatible with the date() function of PHP. See http://php.net/manual/en/function.date.php for more information.
* According to the USGV website the feeds for the past hour,day and 7 days are updated every 5 minutes. The 30 day feeds are updated every 15 minutes. 
* Don't configure the cache timer too low. It will retrieve a fresh feed from an external website when the site loads. This can impact your loading times. I recommend 3600 seconds (1 hour)

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
== PAGER (Prompt Assessment of Global Earthquakes for Response) background ==

PAGER (Prompt Assessment of Global Earthquakes for Response) is an automated system that produces content concerning the impact of significant earthquakes around the world, informing emergency responders, government and aid agencies, and the media of the scope of the potential disaster.
PAGER results are generally available within 30 minutes of a significant earthquake, shortly after the determination of its location and magnitude. However, information on the extent of shaking will be uncertain in the minutes and hours following an earthquake and typically improves as additional sensor data and reported intensities are acquired and incorporated into models of the earthquake's source.

Corresponding fatality thresholds for yellow, orange, and red alert levels are 1, 100, and 1,000, respectively. For damage impact, yellow, orange, and red thresholds are triggered by estimated losses reaching $1 million, $100 million, and $1 billion respectively.

When the PAGER information is not known, the color : GREY will be used as default.

For more info about PAGER see : http://earthquake.usgs.gov/research/pager/

Look and feel of the colors and/or bullets can be adjusted in the CSS file located in the /css folder of this plugin.



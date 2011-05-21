=== Plugin Name ===
Contributors: cmgeel
Donate link: http://www.yellownote.nl/about/
Tags: monitor, earthquake, widget, geological
Requires at least: 3.0
Tested up to: 3.1.2
Stable tag: 1.3

Earthquake Monitor is a customizable widget that shows an overview of earthquakes around the world from the U.S. Geological Surveys data. 

== Description ==


Earthquake Monitor is a customizable widget that shows an overview of earthquakes around the world from the U.S. Geological Surveys data. 
This widget requires at least PHP 5.0 with SimpleXML enabled to work.

This widget has a build in cache support and filter options.


== Installation ==


1. Upload the folder `EarthquakeMonitor` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the widget to the sidebar. 
4. Customize the widget.

== Frequently Asked Questions ==

* If the filter does not work keep in mind that you can only filter on 1 word. Also keep in mind that the filter is case sensitive.
* Changing the feed is only effective when the cache timer is expired. Adjusting the cache timer to 1 second , refresh the page where the earthquake monitor is shown and restore the cache timer setting will work.


== Screenshots ==

1. Screenshot of front-end
2. Screenshot of the backend

== Changelog ==

= 1.3 =

* Bug fix : File were not stored at tmp directory.
* Better error handling when RSS file failed to read / refresh
* Build in check if tmp folder is writeable (This application makes use of the tmp folder)

= 1.2 =

* Added a filter. NOw its possible to search only for earthquakes that have a certain word in the title.

= 1.1 =

* Added cache timer to cache the feed.
* Minor bug fixes

= 1.0 =

* Initial Release

== Upgrade Notice ==

Fixes a bug with tmp directory. You must have at least PHP 5.2.1 or higher to use v1.3 of this plugin.
	
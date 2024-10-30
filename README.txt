=== Excelsea Store Locator ===
Contributors: lokaleinternetwerbung
Tags: store locator, excelsea, markee, location finder
Requires at least: 5.3.0
Tested up to: 5.9
Stable tag: 1.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugins provides your website with the store locator from Excelsea GmbH & Co. KG.

== Description ==

** This plugins requires an valid API-Key from Excelsea. If you are interested, contact us: info@excelsea.de.**

Let your visitors search for the nearest store from your company.  You can search for a city, zip or just show the nearest locations around you.
For every location you get a single detail page. The data for the locations is synced in realtime from our Markee system.


== Installation ==

1. Prepare two new pages in Wordpress. You will need one for the hitlist page and one for the detail page.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You will find the section `Excelsea Store Locator` in the WordPress settings now. Go there and enter the settings for API-URL and API-Key which you received from Excelsea.
Also provide a Google-Maps-Key (Maps JavaScript API). Select the detail page and at least one page for the hitlist.

4. Define the placement of the plugin the detail and hitlist page with a shorthand code.
In the page for hitlist enter this code:
`[LOCATION_HITLIST page=“hitlist“]`
In the page for detail enter this code:
`[LOCATION_HITLIST page=“detail“]`

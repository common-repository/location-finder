<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              lead-hub.de
 * @since             1.0.0
 * @package           Location_Finder
 *
 * @wordpress-plugin
 * Plugin Name:       Excelsea Store Locator
 * Plugin URI:        https://excelsea.de
 * Description:       Plugin for the location finder. Use [LOCATION_HITLIST page="detail"] or [LOCATION_HITLIST page="hitlist"] in content.
 * Version:           1.2.4
 * Author:            excelsea
 * Author URI:        https://excelsea.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       location-finder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('LOCATION_FINDER_VERSION', '1.2.4');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-location-finder-activator.php
 */
function activate_location_finder()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-location-finder-activator.php';
    Location_Finder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-location-finder-deactivator.php
 */
function deactivate_location_finder()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-location-finder-deactivator.php';
    Location_Finder_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_location_finder');
register_deactivation_hook(__FILE__, 'deactivate_location_finder');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-location-finder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_location_finder()
{

    $plugin = new Location_Finder();
    $plugin->run();

}

run_location_finder();

global $options;
$options = get_option('liw_settings');
$detailPath = $options['detail_path'];

/**
 * Load Templates with shortcodes
 */
add_shortcode('LOCATION_HITLIST', 'get_hitlist');
function get_hitlist($attr)
{
    ob_start();

    if (isset($attr['page']) && $attr['page'] == 'hitlist') {
        include_once dirname(__FILE__) . '/public/partials/location-finder-public-hitlist.php';
    }
    if (isset($attr['page']) && $attr['page'] == 'detail') {
        include_once dirname(__FILE__) . '/public/partials/location-finder-public-detail.php';
    }
    return ob_get_clean();
}

//add_filter('pre_get_document_title', function () {
//    return '$location';
//});


add_filter('init', 'add_rewrite_rules');
function add_rewrite_rules()
{
    global $wp_rewrite;
    add_rewrite_endpoint('location', EP_PERMALINK | EP_PAGES, 'location');
    $wp_rewrite->flush_rules();
}

add_filter('query_vars', 'conf_query_vars');
function conf_query_vars($query_vars)
{
    $query_vars[] = 'location';
    return $query_vars;
}

add_action('init', function () {
    global $detailPath;
    if (isset($detailPath) && strlen($detailPath) > 1) {
        add_rewrite_rule(
            '^' . $detailPath . '/?([^/]+)/?$',
            'index.php?pagename=' . $detailPath . '&location=$matches[1]',
            'top'
        );
    }
});

add_action('template_include', function ($template) {
    global $post;
    global $options;
    global $detailPath;

    if ($post->post_name === $detailPath) {
        include_once dirname(__FILE__) . '/public/partials/location-finder-public-detail-request.php';
    } else if (in_array(strval($post->ID), $options['hitlist_page_id'])) {
        include_once dirname(__FILE__) . '/public/partials/location-finder-public-hitlist-request.php';
    }

    return $template;
});



<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       lead-hub.de
 * @since      1.0.0
 *
 * @package    Location_Finder
 * @subpackage Location_Finder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Location_Finder
 * @subpackage Location_Finder/admin
 * @author     excelsea <info@excelsea.de>
 */
class Location_Finder_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Location_Finder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Location_Finder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

//		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/location-finder-admin.css', array(), $this->version, 'all' );


    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Location_Finder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Location_Finder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/location-finder-admin.js', array('jquery'), $this->version, false);

    }

}

add_action('admin_menu', 'liw_add_admin_menu');
add_action('admin_init', 'liw_settings_init');

add_action('admin_enqueue_scripts', 'mw_enqueue_color_picker');


function mw_enqueue_color_picker($hook_suffix)
{
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
}


function liw_add_admin_menu()
{
    add_options_page('Excelsea Store Locator', 'Excelsea Store Locator', 'manage_options', 'liw_stores', 'liw_options_page');
}


function liw_settings_init()
{

    register_setting('pluginPage', 'liw_settings');

    add_settings_section(
        'liw_pluginPage_section',
        __('Konfiguration des Filialfinders', 'wordpress'),
        'liw_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'host',
        __('URL der API', 'wordpress'),
        'host_render',
        'pluginPage',
        'liw_pluginPage_section'
    );

    add_settings_field(
        'api_key',
        __('Api-Key', 'wordpress'),
        'api_key_render',
        'pluginPage',
        'liw_pluginPage_section'
    );

    add_settings_field(
        'google_api_key',
        __('Google-Maps-Key', 'wordpress'),
        'google_api_key_render',
        'pluginPage',
        'liw_pluginPage_section'
    );

    add_settings_field(
        'take',
        __('Anzahl der Einträge pro Seite', 'wordpress'),
        'take_render',
        'pluginPage',
        'liw_pluginPage_section'
    );

    add_settings_field(
        'detail_path',
        __('Seite für Detailansicht', 'wordpress'),
        'detail_path_render',
        'pluginPage',
        'liw_pluginPage_section'
    );


    add_settings_field(
        'hitlist_page_id',
        __('Seite(n) der Trefferliste', 'wordpress'),
        'hitlist_page_render',
        'pluginPage',
        'liw_pluginPage_section'
    );

    add_settings_field(
        'primary_color',
        __('Hauptfarbe', 'wordpress'),
        'use_primary_color_render',
        'pluginPage',
        'liw_pluginPage_section'
    );


    add_settings_field(
        'show_review_widget',
        __('Bewertungswidget', 'wordpress'),
        'show_review_widget_render',
        'pluginPage',
        'liw_pluginPage_section'
    );

    add_settings_field(
        'use_autocomplete_search',
        __('Vorschläge bei Suche', 'wordpress'),
        'use_autocomplete_search_render',
        'pluginPage',
        'liw_pluginPage_section'
    );
}


function hitlist_page_render()
{
    $options = get_option('liw_settings');
    ?>
    <select id="hitlist_page" name="liw_settings[hitlist_page_id][]" multiple size="8" >
        <option disabled="disabled">Seite(n) auswählen</option>
        <?php
        if ($pages = get_pages()) {
            foreach ($pages as $page) {
                $selected = in_array($page->ID, $options['hitlist_page_id']) ? ' selected="selected" ' : '';
                echo '<option value="' . $page->ID . '" ' . $selected . '>' . $page->post_title . '</option>';
            }
        }
        ?>
    </select>
    <?php
}


function liw_pluginPage_section()
{
    $options = get_option('liw_settings');
    ?>
    <input type='text' name='liw_settings[liw_pluginPage_section]'
           value='<?php echo $options['liw_pluginPage_section']; ?>'>
    <?php
}

function host_render()
{
    $options = get_option('liw_settings');
    ?>
    <input type='text' name='liw_settings[host]' class='regular-text code' value='<?php echo $options['host']; ?>'>
    <?php
}

function api_key_render()
{
    $options = get_option('liw_settings');
    ?>
    <input type='text' name='liw_settings[api_key]' class='regular-text code'
           value='<?php echo $options['api_key']; ?>'>
    <?php
}

function google_api_key_render()
{
    $options = get_option('liw_settings');
    ?>
    <input type='text' name='liw_settings[google_api_key]' class='regular-text code'
           value='<?php echo $options['google_api_key']; ?>'>
    <?php
}

function take_render()
{
    $options = get_option('liw_settings');
    ?>
    <input type='number' name='liw_settings[take]' class='regular-text code' value='<?php echo $options['take']; ?>'>
    <?php
}
function detail_path_render()
{
    $options = get_option('liw_settings');
    ?>
    <select id="detail_path" name="liw_settings[detail_path]" >
        <option selected="selected" disabled="disabled">Seite auswählen</option>
        <?php
        if ($pages = get_pages()) {
            foreach ($pages as $page) {
                $selected = $page->post_name == $options['detail_path'] ? 'selected="selected"': '';
                echo '<option value="' .  $page->post_name . '" '. $selected . ' >' .  $page->post_title . '</option>';
            }
        }
        ?>
    </select>

    <?php
}

function show_review_widget_render()
{
    $options = get_option('liw_settings');
    ?>
    <p>
        <label for="liw_settings[show_review_widget]">Zeige Bewertungswidget</label>
        <input type="checkbox" name="liw_settings[show_review_widget]"
               value="1" <?php checked(1, $options['show_review_widget'], true); ?> />
    </p>
    <p style="margin-top:10px">
        <label for="review_widget_rating_type">Darstellungstyp</label>
        <select id="review_widget_rating_type" name="liw_settings[review_widget_rating_type]" >
            <option value="average-rating" <?php echo'average-rating'== $options['review_widget_rating_type'] ? 'selected=\'selected\'': '' ?> >average-rating</option>
            <option value="recommendation-rate" <?php echo'recommendation-rate'== $options['review_widget_rating_type'] ? 'selected=\'selected\'': '' ?> >recommendation-rate</option>
            <option value="stars-recommendation" <?php echo'stars-recommendation'== $options['review_widget_rating_type'] ? 'selected=\'selected\'': '' ?> >stars-recommendation</option>
            <option value="combined" <?php echo'combined'== $options['review_widget_rating_type'] ? 'selected=\'selected\'': '' ?> >combined</option>

        </select>

    </p>
    <?php
}

function use_autocomplete_search_render()
{
    $options = get_option('liw_settings');
    ?>
    <p>
        <label for="liw_settings[use_autocomplete_search]">Aktivieren</label>
        <input type="checkbox" name="liw_settings[use_autocomplete_search]"
               value="1" <?php checked(1, $options['use_autocomplete_search'], true); ?> /><br>
        <b>Hinweis: </b>Für die Suche mit Vorschlägen muss die Google Places Api für den verwendeten Google Key aktiv
        sein.
    </p>
    <p>
        <label for="liw_settings[autocomplete_countries]">Einbezogene Länder für Vorschläge</label>
        <input type="text" name="liw_settings[autocomplete_countries]"
               value="<?php echo $options['autocomplete_countries'] ?>"/><br>
        <small>Maximal 5 Länder. Schreibweise in ISO 3166-1 Alpha-2 Ländercode und mit ',' getrennt. (z.B. de, ch, at),
            Default: de</small>
    </p>
    <?php
}

function use_primary_color_render()
{
    $options = get_option('liw_settings');
    ?>

    <script>
        jQuery(document).ready(function ($) {
            $('.color_field').each(function () {
                $(this).wpColorPicker();
            });
        });
    </script>
    <style>
        .wp-picker-holder {
            position: absolute;
        }
    </style>

    <p>
        <label for="liw_settings[primary_color]" hidden><?php esc_attr_e('Farbe wählen'); ?></label>
        <input class="color_field" type="text" name="liw_settings[primary_color]"
               value="<?php esc_attr_e($options['primary_color']); ?>"/>
    </p>

    <?php
}

function liw_settings_section_callback()
{
//    echo __( 'Konfiguration des Filialfinders', 'wordpress' );
}

function liw_options_page()
{
    ?>
    <div class="wrap">
        <form action='options.php' method='post'>
            <h1>Excelsea Store Locator</h1>
            <?php
            settings_fields('pluginPage');
            do_settings_sections('pluginPage');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

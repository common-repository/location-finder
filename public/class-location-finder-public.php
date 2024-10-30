<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       lead-hub.de
 * @since      1.0.0
 *
 * @package    Location_Finder
 * @subpackage Location_Finder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Location_Finder
 * @subpackage Location_Finder/public
 * @author     excelsea <info@excelsea.de>
 */
class Location_Finder_Public {

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

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name . '-bootstrap-liw', plugin_dir_url( __FILE__ ) . 'css/bootstrap-liw.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-fontawesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-featherlight', plugin_dir_url( __FILE__ ) . 'css/featherlight.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-featherlight-gallery', plugin_dir_url( __FILE__ ) . 'css/featherlight.gallery.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/location-finder-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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


		wp_enqueue_script( $this->plugin_name . '-swipe-detect', plugin_dir_url( __FILE__ ) . 'js/jquery.detect_swipe.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-featherlight', plugin_dir_url( __FILE__ ) . 'js/featherlight.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-featherlight-gallery', plugin_dir_url( __FILE__ ) . 'js/featherlight.gallery.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . 'main', plugin_dir_url( __FILE__ ) . 'js/location-finder-public.js', array( 'jquery' ), $this->version, false );

	}

}

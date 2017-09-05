<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.badges4languages.com
 * @since      0.0.1
 *
 * @package    Badges_Issuer_For_Wp
 * @subpackage Badges_Issuer_For_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Badges_Issuer_For_Wp
 * @subpackage Badges_Issuer_For_Wp/admin
 * @author     My language skills team <mylanguageskills@hotmail.com>
 */
class Badges_Issuer_For_Wp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Badges_Issuer_For_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Badges_Issuer_For_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/badges-issuer-for-wp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Badges_Issuer_For_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Badges_Issuer_For_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/badges-issuer-for-wp-admin.js', array( 'jquery' ), $this->version, false );

	}

}
/**
 * SEND BADGES TO STUDENTS CUSTOM SUBMENU
 * A teacher can send certifications by mails to 1 student by the administration panel.
 *
 * @since    0.1
 */

require plugin_dir_path( __FILE__ ) . '../includes/submenu_pages/send-badges.php';


/**
 * SETTINGS PAGE OF THE PLUGIN
 *
 * @since    0.2
 */
require plugin_dir_path( __FILE__ ) . '../includes/submenu_pages/settings.php';

/**
 * STATISTICS PAGE OF THE PLUGIN
 *
 * @since    0.6
 */
require_once plugin_dir_path( __FILE__ ) . '../includes/submenu_pages/statistics.php';

<?php

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       www.badges4languages.com
	 * @since      0.6.2
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
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    0.0.1
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    0.0.1
		 *
		 * @param      string $plugin_name The name of this plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    0.0.1
		 */
		public function enqueue_styles() {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/badges-issuer-for-wp-admin.css', array(), $this->version, 'all' );
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    0.6.3
		 */
		public function enqueue_scripts() {
		    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/badges-issuer-for-wp-admin.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script("jquery");
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('jquery-ui-tabs');
		}

        /**
         * Load the #Send Badge#, #Setting#, #Statistics#  pages
         *
         * @since    0.6.3
         */
		function load_page() {
            require plugin_dir_path( __FILE__ ) . '../includes/submenu_pages/send-badges.php';
            require plugin_dir_path( __FILE__ ) . '../includes/submenu_pages/settings.php';
            require_once plugin_dir_path( __FILE__ ) . '../includes/submenu_pages/statistics.php';
        }

	}

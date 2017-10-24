<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.badges4languages.com
 * @since      0.0.1
 *
 * @package    Badges_Issuer_For_Wp
 * @subpackage Badges_Issuer_For_Wp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Badges_Issuer_For_Wp
 * @subpackage Badges_Issuer_For_Wp/public
 * @author     My language skills team <mylanguageskills@hotmail.com>
 */
class Badges_Issuer_For_Wp_Public {

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
	 * @since    0.0.1
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/badges-issuer-for-wp-public.css', array(), $this->version, 'all' );
        wp_enqueue_style("send-badges-public", plugin_dir_url(__FILE__) . '../includes/css/sendbadges-style.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/badges-issuer-for-wp-public.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script("send-badges-public", plugin_dir_url(__FILE__) . '../includes/js/sendbadge-functionality.js', array('jquery'), $this->version, false);
        wp_enqueue_script("jQuery-validation-public", 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script("steps-form", plugin_dir_url(__FILE__) . '../includes/js/jquery.steps.min.js', array('jquery'), $this->version, false);
    }

}

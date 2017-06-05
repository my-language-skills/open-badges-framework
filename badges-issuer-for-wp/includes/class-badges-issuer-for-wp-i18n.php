<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.badges4languages.com
 * @since      1.0.0
 *
 * @package    Badges_Issuer_For_Wp
 * @subpackage Badges_Issuer_For_Wp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Badges_Issuer_For_Wp
 * @subpackage Badges_Issuer_For_Wp/includes
 * @author     My language skills team <mylanguageskills@hotmail.com>
 */
class Badges_Issuer_For_Wp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'badges-issuer-for-wp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

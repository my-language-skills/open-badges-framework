<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.badges4languages.com
 * @since             0.6
 * @package           Badges_Issuer_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Badges-Issuer-for-wp
 * Plugin URI:        www.badges4languages.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.6
 * Author:            My language skills team
 * Author URI:        www.badges4languages.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       badges-issuer-for-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-badges-issuer-for-wp-activator.php
 */
function activate_badges_issuer_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badges-issuer-for-wp-activator.php';
	Badges_Issuer_For_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-badges-issuer-for-wp-deactivator.php
 */
function deactivate_badges_issuer_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badges-issuer-for-wp-deactivator.php';
	Badges_Issuer_For_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_badges_issuer_for_wp' );
register_deactivation_hook( __FILE__, 'deactivate_badges_issuer_for_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-badges-issuer-for-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.6
 */
function run_badges_issuer_for_wp() {

	$plugin = new Badges_Issuer_For_Wp();
	$plugin->run();

function badges_issuer_for_wp_init() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'badges_issuer_for_wp', false, $plugin_dir );
	}
add_action('plugins_loaded', 'badges_issuer_for_wp_init');

}
run_badges_issuer_for_wp();

?>

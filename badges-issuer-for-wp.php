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
 * @since             0.6.3
 * @package           Badges_Issuer_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Badges Issuer for wp
 * Plugin URI:        www.badges4languages.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin
 * area.
 * Version:           X.X.X
 * Author:            My language skills team
 * Author URI:        www.badges4languages.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       badges-issuer-for-wp
 * Domain Path:       /languages
 */
/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// If this file is called directly, abort!
defined('ABSPATH') or die('Hey, what are you doing here? You silly human!');

// Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Define CONSTANTS
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PLUGIN', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation
 *
 * @since x.x.x
 */
function badges_issuer_for_wp_activation() {
    Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'badges_issuer_for_wp_activation');

/**
 * The code that runs during plugin deactivation
 *
 * @since x.x.x
 */
function badges_issuer_for_wp_deactivation() {
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'badges_issuer_for_wp_deactivation');

/**
 * Initialize all the core classes of the plugin
 *
 * @since x.x.x
 */
if (class_exists('Inc\\Init')) {
    Inc\Init::register_services();

}
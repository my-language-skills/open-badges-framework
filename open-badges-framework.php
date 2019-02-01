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
 * @package           OpenBadgesFramework
 *
 * @wordpress-plugin
 * Plugin Name:       Open Badges Framework
 * Plugin URI:        www.badges4languages.com
 * Description:       Open Badges Framework allows you to distribute and receive certifications of level language skills.
 * area.
 * Version:           1.1
 * Author:            My language skills team
 * Author URI:        www.badges4languages.com
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       open-badges-framework
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
 * @since 1.0.0
 */
function open_badges_framework_activation() {
    Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'open_badges_framework_activation');

/**
 * This piece of code loads the text domain.
 * It is used for internationalization purposes.
 * It should be executed before the rest of the plugin is loaded, so all the strings of the plugin
 * are internationalized
 */
 function my_plugin_load_plugin_textdomain() {
	load_plugin_textdomain( 'open-badges-framework', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'my_plugin_load_plugin_textdomain' );


/**
 * The code that runs during plugin deactivation
 *
 * @since 1.0.0
 */
function open_badges_framework_deactivation() {
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'open_badges_framework_deactivation');

/**
 * Initialize all the core classes of the plugin
 *
 * @since 1.0.0
 */
if (class_exists('Inc\\Init')) {

    Inc\Init::register_services();
}

/**
 * Add custom fields for registration form and profile editor
 * Add custom WP Personal Data Exporter
 * @since 1.0.1
 */
require_once( "inc/Integrations/RCP-registration_fields.php" );
require_once( "inc/Integrations/wp-user-custom_fields.php" );
require_once( "inc/Integrations/wp_custom_data_exporter.php" );

/**
 * Auto update from github
 *
 * @since 1.0.0
 */
 require 'vendor/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Badges4Languages/open-badges-framework/',
	__FILE__,
	'open-badges-framework'
);

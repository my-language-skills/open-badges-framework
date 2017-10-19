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
 * @since             X.X.X
 * @package           Badges_Issuer_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Badges-Issuer-for-wp
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


defined('ABSPATH') or die('Hey, what are you doing here? You silly human!');


if (!class_exists('BadgeIssuerForWp')) {
    class BadgeIssuerForWp {

        public $path_plugin;

        public function __construct() {
            $this->path_plugin = plugin_dir_path(__FILE__);
        }

        function register() {
            add_action('plugins_loaded', array($this, 'init'));
        }

        function init() {
            load_plugin_textdomain('badges_issuer_for_wp', false, $this->path_plugin);

        }

        /**
         * The core plugin class that is used to define internationalization,
         * admin-specific hooks, and public-facing site hooks.
         */
        function start() {
            require plugin_dir_path(__FILE__) . 'includes/class-badges-issuer-for-wp.php';
            $plugin = new Badges_Issuer_For_Wp();
            $plugin->run();
        }

        function load_script() {

        }

        /**
         * The code that runs during plugin activation.
         * This action is documented in includes/class-badges-issuer-for-wp-activator.php
         */
        function activate() {
            require_once $this->path_plugin . 'includes/class-badges-issuer-for-wp-activator.php';
            Badges_Issuer_For_Wp_Activator::activate();
        }

        /**
         * The code that runs during plugin deactivation.
         * This action is documented in includes/class-badges-issuer-for-wp-deactivator.php
         */
        function deactivate() {
            require_once $this->path_plugin . 'includes/class-badges-issuer-for-wp-deactivator.php';
            Badges_Issuer_For_Wp_Deactivator::deactivate();
        }
    }

    $badge_issuer = new BadgeIssuerForWp();
    $badge_issuer->register();
    $badge_issuer->start();
    $badge_issuer->load_script();


    register_activation_hook(__FILE__, array($badge_issuer, 'activate'));
    register_deactivation_hook(__FILE__, array($badge_issuer, 'deactivate'));


}


<?php

namespace Inc\Base;

use \Inc\Base\BaseController;
use Inc\Pages\Admin;
use templates\SettingsTemp;

/**
 * The Enqueue Class load all the script and style files that we need.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Enqueue extends BaseController {

    /**
     * Initialize the enqueue of styles and scripts.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function register() {
        self::setAdminEnqueue();
        self::setPublicEnqueue();
    }

    /**
     * Call the Admin WordPress enqueue hook.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    private function setAdminEnqueue() {
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdmin'));
    }

    /**
     * Call the Public WordPress enqueue hook adding typically
     * style in the head and script in the footer.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    private function setPublicEnqueue() {
        add_action('wp_head', array($this, 'cssHead'));
        add_action('wp_footer', array($this, 'jsFooter'));
    }


    /**
     * Load all the admin styles and scripts in
     * the admin section.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function enqueueAdmin() {
        // CSS
        wp_enqueue_style('send-badges-style', $this->plugin_url . 'assets/css/send-badge.css');
        wp_enqueue_style('my-style', $this->plugin_url . 'assets/css/mystyle.css');
        // JavaScript
        wp_enqueue_script('general-js', $this->plugin_url . 'assets/js/general.js');
        wp_enqueue_script('form-send-badges', $this->plugin_url . 'assets/js/jquery.steps.min.js');
        wp_enqueue_script("jQuery-validation", 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js', array('jquery'), 0.1, false);
        wp_enqueue_script('send-badges', $this->plugin_url . 'assets/js/send-badge.js');
        wp_localize_script(
            'send-badges',
            'globalUrl',
            array(
                'ajax' => admin_url('admin-ajax.php'),
                'loader' => $this->plugin_url . "assets/gif/circle-loading.gif",
            )
        );
    }

    /**
     * All the Head styles for the public section.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function cssHead() {
        // Get badge page retrieved from the plugin setting
        $getBadgePage = get_post(
            SettingsTemp::getOption(SettingsTemp::FI_GET_BADGE)
        );

        if (is_page($getBadgePage->post_name)) {
            // Get badge page Style
            wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css');
            wp_enqueue_style('get-badge-css', $this->plugin_url . 'assets/css/get-badge.css');
        } else {
            // Otherwise Style
            wp_enqueue_style('my-style', $this->plugin_url . 'assets/css/mystyle.css');
            wp_enqueue_style('send-badges-style', $this->plugin_url . 'assets/css/send-badge.css');
        }
    }

    /**
     * All the Footer scripts for the public section.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function jsFooter() {
        // Get badge page retrieved from the plugin setting
        $getBadgePage = get_post(
            SettingsTemp::getOption(SettingsTemp::FI_GET_BADGE)
        );

        // Always
        wp_enqueue_script('form-send-badges', $this->plugin_url . 'assets/js/jquery.steps.min.js');


        if (is_page($getBadgePage->post_name)) {
            // Get badge page Scripts
            wp_enqueue_script('get-badge-js', $this->plugin_url . 'assets/js/get-badge.js');
            wp_localize_script(
                'get-badge-js',
                'globalUrl',
                array(
                    'ajax' => admin_url('admin-ajax.php'),
                    'loader' => $this->plugin_url . "assets/gif/circle-loading.gif",
                    'loaderPoint' => $this->plugin_url . "assets/gif/horizontal-loading.gif",
                )
            );
        } else {
            // Otherwise Scripts
            wp_enqueue_script('general-js', $this->plugin_url . 'assets/js/general.js');
            wp_enqueue_script("jQuery-validation", 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js', array('jquery'), 0.1, false);
            wp_enqueue_script('send-badge-js', $this->plugin_url . 'assets/js/send-badge.js');
            wp_localize_script(
                'send-badge-js',
                'globalUrl',
                array(
                    'ajax' => admin_url('admin-ajax.php'),
                    'loader' => $this->plugin_url . "assets/gif/circle-loading.gif",
                )
            );
        }
    }

}
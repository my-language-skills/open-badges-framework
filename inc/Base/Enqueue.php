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

    const DIRECTORY_CSS = 'assets/css/';
    const DIRECTORY_JS = 'assets/js/';
    const DIRECTORY_GIF = 'assets/gif/';

    const STYLE_GET_BADGE = 'get-badge.css';
    const STYLE_SEND_BADGE = 'send-badge.css';
    const STYLE_OBF = 'obf-style.css';

    const SCRIPT_GET_BADGE = 'get-badge.js';
    const SCRIPT_SEND_BADGE = 'send-badge.js';
    const SCRIPT_OBF = 'obf-script.js';

    const GIF_LOADING = 'loading.gif';

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
     * Load all the ADMIN styles and scripts in
     * the admin section.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function enqueueAdmin() {
        // CSS

        wp_enqueue_style(self::STYLE_SEND_BADGE, $this->plugin_url . self::DIRECTORY_CSS . self::STYLE_SEND_BADGE);
        wp_enqueue_style(self::STYLE_OBF, $this->plugin_url . self::DIRECTORY_CSS . self::STYLE_OBF);

        // JavaScript
        wp_enqueue_script("jQuery-validation", 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js', array('jquery'), 0.1, false);
        wp_enqueue_script('form-send-badges', $this->plugin_url . 'assets/js/jquery.steps.min.js');
        wp_enqueue_script(self::SCRIPT_SEND_BADGE, $this->plugin_url . self::DIRECTORY_JS . self::SCRIPT_SEND_BADGE);
        wp_enqueue_script(self::SCRIPT_OBF, $this->plugin_url . self::DIRECTORY_JS . self::SCRIPT_OBF);
        wp_localize_script(
            self::SCRIPT_SEND_BADGE,
            'globalUrl',
            array(
                'ajax' => admin_url('admin-ajax.php'),
                'loader' => $this->plugin_url . self::DIRECTORY_GIF .self::GIF_LOADING,
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
            // GET BADGE page Style
            wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css');
            wp_enqueue_style(self::STYLE_GET_BADGE, $this->plugin_url . self::DIRECTORY_CSS . self::STYLE_GET_BADGE);
        } else {
            // OTHERWISE Style
            wp_enqueue_style(self::STYLE_OBF, $this->plugin_url . self::DIRECTORY_CSS . self::STYLE_OBF);
            wp_enqueue_style(self::STYLE_SEND_BADGE, $this->plugin_url . self::DIRECTORY_CSS . self::STYLE_SEND_BADGE);
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
            // GET BADGE page Scripts
            wp_enqueue_script(self::SCRIPT_GET_BADGE, $this->plugin_url . self::DIRECTORY_JS . self::SCRIPT_GET_BADGE);

            wp_localize_script(
                self::SCRIPT_GET_BADGE,
                'globalUrl',
                array(
                    'ajax' => admin_url('admin-ajax.php'),
                    'loader' => $this->plugin_url . self::DIRECTORY_GIF . self::GIF_LOADING,
                )
            );
        } else {
            // OTHERWISE Scripts
            wp_enqueue_script("jQuery-validation", 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js', array('jquery'), 0.1, false);
            wp_enqueue_script(self::SCRIPT_SEND_BADGE, $this->plugin_url . self::DIRECTORY_JS . self::SCRIPT_SEND_BADGE);
            wp_enqueue_script(self::SCRIPT_OBF, $this->plugin_url . self::DIRECTORY_JS . self::SCRIPT_OBF);

            wp_localize_script(
                self::SCRIPT_SEND_BADGE,
                'globalUrl',
                array(
                    'ajax' => admin_url('admin-ajax.php'),
                    'loader' => $this->plugin_url . self::DIRECTORY_GIF . self::GIF_LOADING,
                )
            );
        }
    }

}
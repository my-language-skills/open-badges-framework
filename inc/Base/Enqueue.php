<?php
namespace Inc\Base;

use \Inc\Base\BaseController;
use Inc\Pages\Admin;
use templates\SettingsTemp;

/**
 * The Enqueue Class
 *
 * @since      x.x.x
 *
 * @package    OpenBadgesFramework
 */
class Enqueue extends BaseController {

    /**
     * Enqueue all the admin style and script throw the "add_action" function
     */
    public function register() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_action('enqueue_scripts', array($this, 'enququeFrontEnd'));
        add_action('wp_head', array($this, 'cssHead'));
        add_action('wp_footer', array($this, 'jsFooter'));
    }

    /**
     * All the admin style and script
     */
    public function enqueue() {
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
                'loader' => $this->plugin_url . "assets/gif/load.gif",
            )
        );
    }

    public function enququeFrontEnd() {
        wp_enqueue_style('frontend-obf-style', $this->plugin_url . 'assets/css/fe-obf.css');
    }

    public function cssHead() {
        $options = get_option(SettingsTemp::OPTION_NAME);
        $post = get_post($options[SettingsTemp::FI_GET_BADGE]);
        if (is_page($post->post_name)) {
            wp_enqueue_style('bootstrapp-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css');
            wp_enqueue_style('get-badge-css', $this->plugin_url . 'assets/css/get-badge.css');
        } else {
            //send badge files
            wp_enqueue_style('send-badges-style', $this->plugin_url . 'assets/css/send-badge.css');
        }
    }

    public function jsFooter() {
        $options = get_option(SettingsTemp::OPTION_NAME);
        $post = get_post($options[SettingsTemp::FI_GET_BADGE]);
        if (is_page($post->post_name)) {
            wp_enqueue_script('jquery-js', 'https://code.jquery.com/jquery-1.10.2.js');
            wp_enqueue_script('get-badge-js', $this->plugin_url . 'assets/js/get-badge.js');
            wp_localize_script(
                'get-badge-js',
                'globalUrl',
                array(
                    'ajax' => admin_url('admin-ajax.php'),
                    'loader' => $this->plugin_url . "assets/gif/loading-circle.gif",
                    'loaderPoint' => $this->plugin_url . "assets/gif/3-point.gif",
                )
            );
        } else {
            //send badge files
            wp_enqueue_script('form-send-badges', $this->plugin_url . 'assets/js/jquery.steps.min.js');
            wp_enqueue_script("jQuery-validation", 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js', array('jquery'), 0.1, false);
            wp_enqueue_script('send-badge-js', $this->plugin_url . 'assets/js/send-badge.js');
            wp_localize_script(
                'send-badge-js',
                'globalUrl',
                array(
                    'ajax' => admin_url('admin-ajax.php'),
                    'loader' => $this->plugin_url . "assets/gif/load.gif",
                )
            );
        }
    }

}
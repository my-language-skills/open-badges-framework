<?php
/**
 * The Enqueue Class
 *
 * @since      x.x.x
 *
 * @package    BadgeIssuerForWp
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController {

    /**
     * Enqueue all the admin style and script throw the "add_action" function
     */
    function register() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

    /**
     * All the admin style and script
     */
    function enqueue() {
        // enqueue all our scripts
        wp_enqueue_style('sendbadges-style', $this->plugin_url . 'assets/css/sendbadges-style.css');
        wp_enqueue_script('form-sendbadges', $this->plugin_url . 'assets/js/jquery.steps.min.js');
        wp_enqueue_script("jQuery-validation", 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js', array('jquery'), 0.1, false);
        wp_enqueue_script('sendbadges-functionality', $this->plugin_url . 'assets/js/sendbadge-functionality.js');
        wp_localize_script(
            'sendbadges-functionality',
            'globalUrl',
            array(
                'ajax' => admin_url('admin-ajax.php'),
                'loader' => $this->plugin_url."assets/gif/load.gif",
            )
        );
    }

}
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

class Enqueue extends BaseController{

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
        wp_enqueue_script('sendbadges-functionality', $this->plugin_url . 'assets/js/sendbadge-functionality.js');
        wp_enqueue_script('steps', $this->plugin_url . 'assets/js/jquery.steps.min.js');

    }

}
<?php
/**
 * The Enqueue Class
 *
 * @since      x.x.x
 *
 * @package    FlexProduct
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
        wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/mystyle.css');
        wp_enqueue_script('mypluginscript', $this->plugin_url . 'assets/myscript.js');
    }

}
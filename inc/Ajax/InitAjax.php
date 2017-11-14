<?php
/**
 * The InitAjax Class, permit to load all the ajax class and they're function
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace Inc\Ajax;


use Inc\Base\BaseController;

class InitAjax extends BaseController {

    public function __construct() {
        self::register_services();
    }

    public static function get_services() {
        return array(
            SendBadgeAjax::class
        );
    }

    public static function register_services() {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            $class_methods = get_class_methods($service);
            foreach ($class_methods as $method_name) {
                add_action("wp_ajax_$method_name", array($service, $method_name));
                add_action("wp_ajax_nopriv_$method_name", array($service, $method_name));
            }
        }
    }

    /**
     * Initialize the class
     *
     * @param class $class class form services array
     *
     * @return class instance   new instance of the class
     */
    private static function instantiate($class) {
        return new $class();
    }

}
<?php

namespace Inc\Api;

use Inc\Ajax\AdminAjax;
use Inc\Ajax\SendBadgeAjax;
use Inc\Ajax\GetBadgeAjax;
use Inc\Base\BaseController;

/**
 * Permit to load all the functions from the ajax class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class AjaxApi extends BaseController {

    /**
     * The construct call the principal function that
     * initialize the ajax class.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function __construct() {
        self::registerServices();
    }

    /**
     * That function return all the ajax class that
     * we want to initialize.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return array of ajax class
     */
    public static function getServices() {
        return array(
            SendBadgeAjax::class,
            GetBadgeAjax::class,
            AdminAjax::class
        );
    }

    /**
     * Here we're looping a list of classes, initializing
     * them and retrieving all them functions for declare
     * it as ajax function.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return array of ajax class
     */
    public static function registerServices() {
        foreach (self::getServices() as $class) {
            $service = self::instantiate($class);
            $class_methods = get_class_methods($service);
            foreach ($class_methods as $method_name) {
                add_action("wp_ajax_$method_name", array($service, $method_name));
                add_action("wp_ajax_nopriv_$method_name", array($service, $method_name));
            }
        }
    }

    /**
     * Instantiation of a class.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param class $class class form services array
     *
     * @return class instance   new instance of the class
     */
    private static function instantiate($class) {
        return new $class();
    }

}
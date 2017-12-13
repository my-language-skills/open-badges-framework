<?php

namespace Inc\Ajax;

use Inc\Base\BaseController;

/**
 * Permit to load all the ajax class and they're functions.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class InitAjax extends BaseController {

    /**
     * The construct call the principal function that
     * initialize all the ajax files.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function __construct() {
        self::registerServices();
    }

    /**
     * That function give all the ajax function that
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
            GetBadgeAjax::class

        );
    }

    /**
     * Here we're looping a list of classes, initializing
     * them and retrieving all them function for declare
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
     * Initialize the class.
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
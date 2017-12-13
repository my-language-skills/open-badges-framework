<?php
/**
 * Initial class that is called.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace Inc;

/**
 * This is the initial class that is called from WordPress.
 * Here will start all the initial class that we want to execute.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 */
final class Init {

    /**
     * Store all the classes inside an array.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return array Full list of classes
     */
    public static function get_services() {
        return array(
            Pages\Admin::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\User::class,
            Utils\Fields::class,
            Ajax\InitAjax::class,
            Utils\DisplayFunction::class,
            Utils\Statistics::class,
            Database\DbBadge::class,
        );
    }

    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public static function register_services() {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
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

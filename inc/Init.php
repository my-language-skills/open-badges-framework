<?php
/**
 * Initial class that is called.
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */

namespace Inc;

/**
 * This is the initial class that is called from WordPress.
 * Here will start all the initial class that we want to execute.
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 */
final class Init {

    /**
     * Store all the classes inside an array.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return array Full list of classes
     */
    public static function get_services() {
        return array(
            Pages\Admin::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Utils\WPUser::class,
            Base\Secondary::class,
            Utils\WPField::class,
            Api\AjaxApi::class,
            Utils\DisplayFunction::class,
            Utils\Statistics::class,
            Database\DbBadge::class,
            Database\DbUser::class,
			Base\ExtendComment::class
        );
    }

    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param class $class class form services array
     *
     * @return class instance   new instance of the class
     */
    private static function instantiate($class) {
        return new $class();
    }

}

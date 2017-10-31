<?php
/**
 * The Init Class
 *
 * @since      x.x.x
 *
 * @package    FlexProduct
 */
namespace Inc;


final class Init {

    /**
     * Store all the classes inside an array
     *
     * @return array Full list of classes
     */
    public static function get_services() {
        return [
            Pages\Admin::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class
        ];
    }

    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
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
     * Initialize the class
     *
     * @param class $class      class form services array
     *
     * @return class instance   new instance of the class
     */
    private static function instantiate($class) {
        return new $class();
    }

}

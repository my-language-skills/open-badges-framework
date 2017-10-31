<?php
/**
 * The Activate Class
 *
 * @since      x.x.x
 *
 * @package    FlexProduct
 */
namespace Inc\Base;

class Activate {
    /**
     * activate function
     */
    public static function activate() {
        flush_rewrite_rules();
    }
}
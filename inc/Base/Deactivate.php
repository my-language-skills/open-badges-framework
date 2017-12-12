<?php
namespace Inc\Base;

/**
 * The Deactivate Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Deactivate {
    /**
     * deactivate function
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
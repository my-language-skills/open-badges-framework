<?php
/**
 * The Deactivate Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgeFramework
 */

namespace Inc\Base;

class Deactivate {
    /**
     * deactivate function
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
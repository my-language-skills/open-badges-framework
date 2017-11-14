<?php
/**
 * The Activate Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
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
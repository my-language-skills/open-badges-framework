<?php

namespace Inc\Base;

/**
 * That class is called only when you activate
 * the plugin.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Activate {

    /**
     * Function that permit to execute code only
     * wen you active the plugin.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public static function activate() {
        flush_rewrite_rules();
    }
}
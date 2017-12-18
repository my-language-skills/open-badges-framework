<?php

namespace Inc\Base;

/**
 * That class is called only when you deactivate
 * the plugin.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Deactivate {

    /**
     * Function that permit to execute code only
     * wen you deactivate the plugin.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
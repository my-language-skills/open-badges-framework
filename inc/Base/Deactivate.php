<?php

namespace Inc\Base;

/**
 * That class where are stored functions that are called only
 * when you deactivate the plugin.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Deactivate {

    /**
     * Deactivation function.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
<?php

namespace Inc\Base;

/**
 * That class where are stored functions that are called only
 * when you deactivate the plugin.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Deactivate {

    /**
     * Deactivation function.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
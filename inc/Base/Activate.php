<?php

namespace Inc\Base;
use Templates\SettingsTemp;

/**
 * That class where are stored functions that are called only
 * when you activate the plugin.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Activate {

    /**
     * Function that permit to execute code only
     * wen you active the plugin.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public static function activate() {
        SettingsTemp::init();
        flush_rewrite_rules();
    }
}
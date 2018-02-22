<?php

namespace Inc\Base;
use Inc\Database\DbBadge;
use Inc\Database\DbUser;
use Templates\SettingsTemp;

/**
 * That class where are stored functions that are called only
 * when you activate the plugin.
 *
 * @author      @AleRiccardi
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Activate {

    /**
     * Function that permit to execute code only
     * wen you active the plugin.
     *
     * @author      @AleRiccardi
     * @since       x.x.x
     */
    public static function activate() {
        flush_rewrite_rules();

        # Database
        $dbUser = new DbUser();
        $dbBadge = new DbBadge();
        $dbUser->createTable();
        $dbBadge->createTable();

        # Settings
        SettingsTemp::init();

    }
}
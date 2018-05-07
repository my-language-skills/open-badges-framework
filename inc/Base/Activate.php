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
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Activate {

    /**
     * Function that permit to execute code only
     * wen you active the plugin.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     */
    public static function activate() {
        flush_rewrite_rules();
		
        # Database
        $dbUser = new DbUser();
        $dbUser->createTable();
		$dbBadge = new DbBadge();
        $dbBadge->createTable();  

        # Settings
        SettingsTemp::init();
    }
}
<?php
/**
 * The Statistics Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
namespace inc\Utils;

use Inc\Utils\Badges;

class Statistics {

    public static function getNumOfBadges(){
        $badges = new Badges();
        return count($badges->badges);
    }

}
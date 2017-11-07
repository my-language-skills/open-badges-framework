<?php
/**
 * Created by PhpStorm.
 * User: aleric
 * Date: 07/11/2017
 * Time: 17:11
 */

namespace inc\Utils;

use Inc\Utils\Badges;

class Statistics {

    public static function getNumOfBadges(){
        $badges = new Badges();
        return count($badges->badges);
    }

}
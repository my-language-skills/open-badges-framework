<?php
namespace inc\Utils;

/**
 * This is a very initial class, created to be developed
 * in the future and to increase functionality.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Statistics {

    /**
     * This function permit to retrieve the number of badge.
     *
     * @author Nicolas TORION
     * @since  x.x.x
     *
     * @return the number of badge.
     */
    public static function getNumOfBadges() {
        $badges = new Badges();
        return count($badges->badges);
    }

}
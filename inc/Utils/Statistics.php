<?php
/**
 *
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 */

namespace Inc\Utils;
use Inc\Database\DbBadge;

/**
 * Class that contain the general statistics.
 *
 * This is a very initial class, created to be developed
 * in the future and to increase functionality.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Statistics {

    /**
     * This function permit to retrieve the number c.p.t. or taxonomy.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param const $slug name of the c.p.t. or taxonomy
     *
     * @return the number of post or term.
     */
    public static function getNumberPostOrTerm($slug) {
        $posts = wp_count_posts($slug);
        $terms = wp_count_terms($slug);

        if (isset($posts) && !empty($posts->publish)) {
            return $posts->publish;
        } else if (isset($terms)) {
            return $terms;
        } else {
            return 0;
        }
    }

    /**
     * This function permit to retrieve the number of the badges
     * the are sent.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @return the number of badges the we sent
     */
    public static function getNumBadgesSent() {
        $all = DbBadge::getAll();
        return count($all);
    }

    /**
     * This function permit to retrieve the number of the badges
     * the are got from the users.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @return string the number of badges that are got
     */
    public static function getNumBadgesGot() {
        return DbBadge::getNumGot();
    }

    /**
     * This function permit to retrieve the number of the badges
     * the are got from the users in the Mozilla Open Badge platform.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @return string the number of badges that are got in the
     *                Mozilla Open Badge platform
     */
    public static function getNumBadgesGotMob() {
        return DbBadge::getNumGotMob();
    }



}
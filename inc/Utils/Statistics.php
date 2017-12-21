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
     * This function permit to retrieve the number of c.p.t.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param const $slug name of the c.p.t.
     *
     * @return the number of post
     */
    public static function getNumberPost($slug) {
        $posts = wp_count_posts($slug);

        if (isset($posts)) {
            return !empty($posts->publish) ? $posts->publish : 0;
        } else {
            return 0;
        }
    }

    /**
     * This function permit to retrieve the number of taxonomy.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param const $slug name of the  taxonomy
     *
     * @return the number of term.
     */
    public static function getNumberTerm($slug) {
        $terms = wp_count_terms($slug);

        if (isset($terms)) {
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
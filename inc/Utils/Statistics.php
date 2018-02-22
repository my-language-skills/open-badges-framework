<?php
/**
 *
 *
 * @author      @AleRiccardi
 * @since       x.x.x
 *
 */

namespace Inc\Utils;
use Inc\Database\DbBadge;

/**
 * Class that contain the general statistics.
 * This is a very initial class, created to be developed
 * in the future and to increase functionality.
 *
 * @author      @AleRiccardi
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Statistics {

    /**
     * Retrieve the number of the custom post type.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param string $slug of the custom post type.
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
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param string $slug of the taxonomy.
     *
     * @return int the number of term.
     */
    public static function getNumberTerm($slug) {
        $numTerms = wp_count_terms($slug);

        if (isset($numTerms)) {
            return $numTerms;
        } else {
            return 0;
        }
    }

    /**
     * This function permit to retrieve the number of the badges
     * the are sent.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @return int the number of badges the we sent.
     */
    public static function getNumBadgesSent() {
        $all = DbBadge::getAll();
        return count($all);
    }

    /**
     * This function permit to retrieve the number of the badges
     * the are got from the users.
     *
     * @author @AleRiccardi
     * @since  x.x.x
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
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @return string the number of badges that are got in the
     *                Mozilla Open Badge platform
     */
    public static function getNumBadgesGotMob() {
        return DbBadge::getNumGotMob();
    }



}
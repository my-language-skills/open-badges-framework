<?php
/**
 *
 *
 * @author      @AleRiccardi
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
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Statistics {

    /**
     * Retrieve the number of the custom post type.
     *
     * @author @AleRiccardi
     * @since  1.0.0
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
     * @since  1.0.0
     *
     * @param string $slug of the taxonomy.
     *
     * @return int the number of term.
     */
    public static function getNumberTerm($slug) {
        $only_parents = false;
        $count = 0;
        $terms = get_terms($slug, array('hide_empty' => false));

        foreach($terms as $term) {
            if($term->parent != 0){
                $only_parents = true;
                break;
            }
        }

        if($only_parents){
            foreach($terms as $term) {
                if ($term->parent != 0) { // avoid parent categories
                    $count++;
                }
            }
        } else {
            foreach($terms as $term) {
                $count++;
            }
        }
        

        return $count;
    }

    /**
     * This function permit to retrieve the number of the badges
     * the are sent.
     *
     * @author @AleRiccardi
     * @since  1.0.0
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
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return string the number of badges that are got in the
     *                Mozilla Open Badge platform
     */
    public static function getNumBadgesGotMob() {
        return DbBadge::getNumGotMob();
    }



}
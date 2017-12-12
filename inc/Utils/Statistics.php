<?php
/**
 *
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 */

namespace inc\Utils;

/**
 * Class that contain the general statistics.
 *
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
     * This function permit to retrieve the number c.p.t. or taxonomy.
     *
     * @author Nicolas TORION
     * @since  x.x.x
     *
     * @param const $slug name of the c.p.t. or taxonomy
     *
     * @return the number of post or term.
     */
    public static function getNumberPostOrTerm($slug) {
        $posts = wp_count_posts($slug);
        $terms = wp_count_terms($slug);

        if (!empty($posts->publish)) {
            return $posts->publish;
        } else if (!empty($terms)) {
            return $terms;
        } else {
            return 0;
        }
    }

}
<?php
/**
 * The Levels class
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgeFramework
 */


namespace inc\Utils;

use Inc\Pages\Admin;
use Inc\Base\User;

class Levels {

    public $levels = array();
    /**
     * Fields constructor.
     */
    public function __construct() {

        // Get Main
        $this->levels = get_terms(array(
            'taxonomy' => Admin::TAX_LEVELS,
            'hide_empty' => false,
        ));

    }

    /**
     * Returns the right levels.
     *
     * @author Nicolas TORION
     * @since  0.4
     * @since  0.6.3
     *
     * @param string | $rightFieldEdu field of education selected in the first step
     * @return array $levels Array of all levels found.
     */
    function getAllLevels($rightFieldEdu = "") {
        // Variables
        $levels = array();

        $badges = get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'numberposts' => -1
        ));

        foreach ($badges as $badge) {
            // Get the type of the badge (student or teacher)
            $badge_type = get_post_meta($badge->ID, "_target", true);
            // Get the level of the badge
            $level = get_the_terms($badge->ID, Admin::TAX_LEVELS)[0]->name;
            // Get the field of the badge
            $fields = get_the_terms($badge->ID, Admin::TAX_FIELDS);

            //If there is no fields of education in the badge, means that is part of
            // all the fields (category).
            if (!$fields) {
                if (!in_array($level, $levels)) {
                    if (User::check_the_rules("administrator", "editor")) {
                        $levels[] = $level;
                    } else {
                        if ($badge_type == "student") {
                            $levels[] = $level;
                        }
                    }
                }
            } else {
                foreach ($fields as $field) {
                    if (!in_array($level, $levels)) {
                        // Check if the Field of education selected in the first step
                        // is content in one of the badge of the level.
                        if ($field->name == $rightFieldEdu) {
                            if (User::check_the_rules("administrator", "editor")) {
                                $levels[] = $level;
                            } else {
                                if ($badge_type == "student") {
                                    $levels[] = $level;
                                }
                            }
                        }
                    }
                }
            }
        }

        sort($levels);
        return $levels;
    }

}
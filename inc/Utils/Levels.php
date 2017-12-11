<?php
/**
 * The Levels class
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */


namespace inc\Utils;

use Inc\Pages\Admin;
use Inc\Base\User;

class Levels {
    public $levels = array();

    /**
     * This constructor load all the level stored
     * in the db.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
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
     * @param string | $ourField field of education selected in the first step
     *
     * @return array $levels Array of all levels found.
     */
    public static function getAllLevels($ourField = "") {
        // Variables
        $levelsContainer = array();

        $badges = get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'orderby' => 'name',
            'order' => 'ASC',
            'numberposts' => -1
        ));


        foreach ($badges as $badge) {
            // Get the type of the badge (student or teacher)
            $badge_type = get_post_meta($badge->ID, "_target", true);
            // Get the level of the badge
            $level = get_the_terms($badge->ID, Admin::TAX_LEVELS)[0];
            // Get the field of the badge
            $fields = get_the_terms($badge->ID, Admin::TAX_FIELDS);

            // NO FIELD OF EDUCATION FOR THAT BADGE
            // If there is no fields of education in the badge, means that is part of
            // all the fields (category).
            if (!$fields) {
                if (!in_array($level, $levelsContainer)) {
                    if (User::check_the_rules("administrator", "editor")) {
                        $levelsContainer[] = $level;
                    } else {
                        if ($badge_type == "student") {
                            $levelsContainer[] = $level;
                        }
                    }
                }

                // FIELD\S OF EDUCATION EXISTING
            } else {
                foreach ($fields as $field) {
                    //Check if the field of education of the badge is the same of the $ourField,
                    // that mean we want to show only the levels of a specific field
                    if ($field->term_id == $ourField && !in_array($level, $levelsContainer)) {

                        if (User::check_the_rules("administrator", "editor")) {
                            $levelsContainer[] = $level;
                        } else if (User::check_the_rules("teacher")) {
                            if ($badge_type == "student" || $badge_type == "teacher") {
                                $levelsContainer[] = $level;
                            }
                        } else if (User::check_the_rules("student")) {
                            if ($badge_type == "student") {
                                $levelsContainer[] = $level;
                            }
                        }
                    }
                }
            }
        }

        return $levelsContainer;
    }

}
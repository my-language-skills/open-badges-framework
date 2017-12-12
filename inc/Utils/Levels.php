<?php

namespace inc\Utils;

use Inc\Pages\Admin;
use Inc\Base\User;

/**
 * That class permit to mange the levels from the taxonomies
 * "levels_obf_tax".
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
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
     * Returns the right filtered levels.
     *
     * @author Nicolas TORION
     * @since  0.4
     * @since  0.6.3
     *
     * @param string | $fieldId field of education that is used as a filter
     *                          (typically selected in the first step)
     *
     * @return array $levels Array of all levels found.
     */
    public static function getAllLevels($fieldId = "") {
        // Variables
        $retLevels = array();

        $allBadges = get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'orderby' => 'name',
            'order' => 'ASC',
            'numberposts' => -1
        ));

        foreach ($allBadges as $badge) {
            // Get the type of the badge (student or teacher)
            $badge_type = get_post_meta($badge->ID, "_target", true);
            // Get the level of the badge
            $badgeLevel = get_the_terms($badge->ID, Admin::TAX_LEVELS)[0];
            // Get the field of the badge
            $badgeFields = get_the_terms($badge->ID, Admin::TAX_FIELDS);

            // NO FIELD OF EDUCATION FOR THAT BADGE
            // If there is no fields of education in the badge, means that is part of
            // all the fields (category).
            if (!$badgeFields) {
                if (!in_array($badgeLevel, $retLevels)) {
                    if (User::checkTheRules("administrator", "editor")) {
                        $retLevels[] = $badgeLevel;
                    } else {
                        if ($badge_type == "student") {
                            $retLevels[] = $badgeLevel;
                        }
                    }
                }

                // FIELD\S OF EDUCATION EXISTING
            } else {
                foreach ($badgeFields as $badgeField) {
                    // Get the term array of the @param $fieldId
                    $selectedField = get_term($fieldId, Admin::TAX_FIELDS);

                    // Check if the field of education of the badge is the same of the $ourField,
                    // that mean we want to show only the levels of a specific field
                    if (($badgeField->term_id == $fieldId || $badgeField->term_id == $selectedField->parent)
                        && !in_array($badgeLevel, $retLevels)) {

                        if (User::checkTheRules("administrator", "editor")) {
                            $retLevels[] = $badgeLevel;
                        } else if (User::checkTheRules("teacher")) {
                            if ($badge_type == "student" || $badge_type == "teacher") {
                                $retLevels[] = $badgeLevel;
                            }
                        } else if (User::checkTheRules("student")) {
                            if ($badge_type == "student") {
                                $retLevels[] = $badgeLevel;
                            }
                        }
                    }
                }
            }
        }

        return $retLevels;
    }

}
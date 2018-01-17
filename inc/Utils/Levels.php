<?php

namespace Inc\Utils;

use Inc\Pages\Admin;
use Inc\Utils\Badges;

/**
 * That class permit to mange the Level taxonomies.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Levels {

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
     * @return array $levels all levels found.
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

            // Get the field of the badge
            $badgeFields = get_the_terms($badge->ID, Admin::TAX_FIELDS);

            // NO FIELD OF EDUCATION FOR THAT BADGE
            // If there is no fields of education in the badge, means that is part of
            // all the fields (category).
            if (!$badgeFields) {
                Badges::checkCapInsertBadgeOrLevel($retLevels, $badge, true);

                // FIELD\S OF EDUCATION EXISTING
            } else {
                foreach ($badgeFields as $badgeField) {
                    // Get the term array of the @param $fieldId
                    $selectedField = get_term($fieldId, Admin::TAX_FIELDS);

                    // Check if the field of education of the badge is the same of the $ourField,
                    // that mean we want to show only the levels of a specific field
                    if (($badgeField->term_id == $fieldId || $badgeField->term_id == $selectedField->parent)) {
                        Badges::checkCapInsertBadgeOrLevel($retLevels, $badge, true);
                    }
                }
            }
        }

        return $retLevels;
    }


}
<?php
/**
 * The Badges Class contain all
 * the function for the management of the badges.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace inc\Utils;

use inc\Base\User;
use Inc\Pages\Admin;

class Badges {

    public static function getAllBadges() {
        return get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'orderby' => 'name',
            'order' => 'ASC',
            'numberposts' => -1
        ));
    }

    /**
     * This function permit to filter with the field
     * and level and get the right badges that we want.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param string $field the id of the field
     * @param string $level the id of the level
     *
     * @return bool     True if have children,
     *                  False if don't have children
     */
    public static function getBadgesFiltered($field = "", $level = "") {
        $standBadges = get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'orderby' => 'name',
            'order' => 'ASC',
            'numberposts' => -1
        ));

        if ($field == "" && $level == "") {
            return $standBadges;
        } else {
            // Variable
            $allBadges = array();

            foreach ($standBadges as $badge) {
                $fieldOK = 0;
                $fields = get_the_terms($badge->ID, Admin::TAX_FIELDS);
                $badgeLevel = get_the_terms($badge->ID, Admin::TAX_LEVELS)[0]->term_id;
                foreach ($fields as $theField) {
                    if ($theField->term_id == $field) $fieldOK = 1;
                }

                // In this condition the level need to be always right but not for the field
                // of education, in this condition "(!$fields || $fieldOK)" we want to take
                // the badge that are of the right Field of Education ($fieldOK) and the badge
                // that don't have fields of education because they dont have a specific
                // classification (!$fields)
                if ((!$fields || $fieldOK) && $badgeLevel == $level && !in_array($badge, $allBadges)) {
                    $badgeCert = get_post_meta($badge->ID, '_certification', true);
                    if ($badgeCert == "certified" && User::check_the_rules("administrator", "academy", "editor")) {
                        $allBadges[] = $badge;
                    } elseif ($badgeCert != "certified") {
                        $allBadges[] = $badge;
                    }
                }
            }

            return $allBadges;
        }
    }

    /**
     * This function permit get the badge, giving the
     * id of the badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param int $id the id of the field
     *
     * @return array The badge information.
     */
    public static function getBadgeById($id) {
        $standBadges = get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'orderby' => 'name',
            'order' => 'ASC',
            'numberposts' => -1
        ));

        if (!$id) {
            return null;
        } else {
            foreach ($standBadges as $badge) {
                if ($badge->ID == $id)
                    return $badge;
            }
            return null;
        }
    }

}
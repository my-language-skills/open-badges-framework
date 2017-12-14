<?php

namespace inc\Utils;

use Inc\Base\BaseController;
use inc\Base\User;
use Inc\Pages\Admin;

/**
 * Contain all the function for the management of the badges.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Badges{

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
     * @param string $fieldId the id of the field
     * @param string $levelId the id of the level
     *
     * @return bool     True if have children,
     *                  False if don't have children
     */
    public static function getBadgesFiltered($fieldId = "", $levelId = "") {

        $allBadges = get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'orderby' => 'name',
            'order' => 'ASC',
            'numberposts' => -1
        ));

        if ($fieldId == "" && $levelId == "") {
            return $allBadges;
        } else {
            // Variable
            $retBadges = array();

            foreach ($allBadges as $badge) {

                $fieldOK = 0; // Var that determinate if the field match with the badge

                $badgeFields = get_the_terms($badge->ID, Admin::TAX_FIELDS);
                $badgeLevel = get_the_terms($badge->ID, Admin::TAX_LEVELS)[0];

                // Here is checked if the badge MATCH with the $fieldId
                foreach ($badgeFields as $badgeField) {

                    // Get the term array of the @param $fieldId
                    $selectedField = get_term($fieldId, Admin::TAX_FIELDS);

                    // In case the @param $fieldId match with one of the badges.
                    if ($badgeField->term_id == $selectedField->term_id) {
                        $fieldOK = 1;

                        // In case the parent of the @param $fieldId match with one of the badges.
                    } else if ($badgeField->term_id == $selectedField->parent) {
                        $fieldOK = 1;
                    }
                }

                // (!$badgeFields || $fieldOK)      --> if $badgeFields is empty and that means there's no
                // field of education for the badge return 1, if $fieldOK is 1 and that means the badge have
                // the same field of the @param $fieldId return 1.
                //
                // $badgeLevel->term_id == $levelId --> return 1 if the level of the badge is the same of
                // the @param $fieldId.
                //
                // !in_array($badge, $retBadges)    --> return 1 if is not already stored the badge in the
                // $retBadges array.
                if ((!$badgeFields || $fieldOK) && $badgeLevel->term_id == $levelId && !in_array($badge, $retBadges)) {
                    $badgeCert = get_post_meta($badge->ID, '_certification', true);
                    if ($badgeCert == "certified" && User::checkTheRules("administrator", "academy", "editor")) {
                        $retBadges[] = $badge;
                    } elseif ($badgeCert != "certified") {
                        $retBadges[] = $badge;
                    }
                }
            }

            return $retBadges;
        }
    }

    /**
     * This function permit to get a specific badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param int $id the id of the badge
     *
     * @return array The badge information.
     */
    public static function getPost($id) {
        return get_post($id);
    }

    /**
     * This function permit to get the image url of a badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param int $id the id of the badge
     *
     * @return string url
     */
    public static function getImage($id) {

        if (!$img = get_the_post_thumbnail_url($id)) {
            $url = BaseController::getPluginUrl();
            $img = $url . 'assets/images/default-badge.png';
        }

        return $img;
    }



}
<?php

namespace Inc\Utils;

use Inc\Base\BaseController;
use Inc\Base\User;
use Inc\Pages\Admin;
use Inc\Base\Metabox;

/**
 * Contain all the function for the management of the badges.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Badges {

    /**
     * Get all the Badges.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @return array the badges
     */
    public static function getAll() {
        return get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'orderby' => 'name',
            'order' => 'ASC',
            'numberposts' => -1
        ));
    }

    /**
     * This function permit to filter with the field
     * and level the right badges that we want.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param string $fieldId the id of the field
     * @param string $levelId the id of the level
     *
     * @return bool     True if have children,
     *                  False if don't have children
     */
    public static function getFiltered($fieldId = "", $levelId = "") {

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

                    // In case the $fieldId match with one of the badges.
                    if ($badgeField->term_id == $selectedField->term_id) {
                        $fieldOK = 1;

                        // In case the parent of the $fieldId match with one of the badges.
                    } else if ($badgeField->term_id == $selectedField->parent) {
                        $fieldOK = 1;
                    }
                }

                // (!$badgeFields || $fieldOK)      --> if $badgeFields is empty and that means there's no
                // field of education for the badge return 1, if $fieldOK is 1 and that means the badge have
                // the same field of the $fieldId return 1.
                //
                // $badgeLevel->term_id == $levelId --> return 1 if the level of the badge is the same of
                // the $fieldId.
                //
                // !in_array($badge, $retBadges)    --> return 1 if is not already stored the badge in the
                // $retBadges array.
                if ((!$badgeFields || $fieldOK) && $badgeLevel->term_id == $levelId && !in_array($badge, $retBadges)) {
                    self::checkCapInsertBadgeOrLevel($retBadges, $badge);
                }
            }

            return $retBadges;
        }
    }

    /**
     * Here we're checking if the capability of the role of the user match
     * with the kind of badge.
     * That function is probably complicated but useful for my propose and
     * remember: we're using the pointer for the first parameter.
     * for my scope.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $retContainer this array is a pointer to the main
     *                            container that we want to save the badge
     * @param       $badge        badge that we want to check
     * @param bool  $retLevel     permit to specify if insert in the array the
     *                            badge ore the level of the badge
     */
    public static function checkCapInsertBadgeOrLevel(array &$retContainer, $badge, $retLevel = false) {
        $badgeLevel = null;
        // Get the level of the badge
        if ($retLevel) $badgeLevel = get_the_terms($badge->ID, Admin::TAX_LEVELS)[0];
        // Get the type of the badge (student or teacher)
        $badgeType = get_post_meta($badge->ID, "_target", true);
        // Get the certification of the badge (certify or not-certify)
        $badgeCert = get_post_meta($badge->ID, '_certification', true);

        // Capability Teacher and Certification
        if (current_user_can(USER::CAP_TEACHER)
            && ($badgeType == Metabox::META_FIELD_TEACHER || $badgeType == Metabox::META_FIELD_STUDENT)) {
            if (current_user_can(USER::CAP_CERT)
                && ($badgeCert == Metabox::META_FIELD_CERT || $badgeCert == Metabox::META_FIELD_NOT_CERT)) {

                if ($retLevel) array_push($retContainer, $badgeLevel);
                else array_push($retContainer, $badge);

            } else if (!current_user_can(USER::CAP_CERT) && $badgeCert == Metabox::META_FIELD_NOT_CERT) {

                if ($retLevel) array_push($retContainer, $badgeLevel);
                else array_push($retContainer, $badge);

            }

            // Capability Teacher and Certification
        } else if (!current_user_can(USER::CAP_TEACHER) && $badgeType == Metabox::META_FIELD_STUDENT) {
            if (current_user_can(USER::CAP_CERT)
                && ($badgeCert == Metabox::META_FIELD_CERT || $badgeCert == Metabox::META_FIELD_NOT_CERT)) {

                if ($retLevel) array_push($retContainer, $badgeLevel);
                else array_push($retContainer, $badge);

            } else if (!current_user_can(USER::CAP_CERT) && $badgeCert == Metabox::META_FIELD_NOT_CERT) {

                if ($retLevel) array_push($retContainer, $badgeLevel);
                else array_push($retContainer, $badge);

            }
        }
    }


    /**
     * This function permit to get a specific badge.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param int $id the id of the badge
     *
     * @return array The badge information.
     */
    public static function get($id) {
        return get_post($id);
    }

    /**
     * This function permit to get the thumbnail image url of a badge.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param int $id the id of the badge
     *
     * @return string url
     */
    public static function getImage($id) {
        if (!$img = get_the_post_thumbnail_url($id, 'thumbnail')) {
            $url = BaseController::getPluginUrl();
            $img = $url . 'assets/images/default-badge.png';
        }

        return $img;
    }


}
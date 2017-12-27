<?php

namespace Inc\Utils;

use Inc\Base\User;
use Inc\Pages\Admin;

/**
 * That class permit to mange the classes of the "Job_Listing"
 * Custom Post Type.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Classes {

    /**
     * Retrieve the right classes that match with the field if
     * education passed as param.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param string $field the id of the field
     *
     * @return array    classes that match with the field of education
     *                  False if don't have children
     */
    public static function getOwnClass($field = "") {
        $allClasses = get_posts(array(
            'post_type' => Admin::POST_TYPE_CLASS_JL,
            'orderby' => 'name',
            'order' => 'ASC',
            'numberposts' => -1
        ));

        $userId = User::getCurrentUser()->ID;
        $classes = array();
        foreach ($allClasses as $class) {
            $fieldsPost = wp_get_post_terms($class->ID, Admin::TAX_FIELDS);
            if ($class->post_author == $userId) {
                if (!$fieldsPost) {
                    array_push($classes, $class);
                } else {
                    foreach ($fieldsPost as $fieldPost) {
                        $fieldPost->name == $field ? array_push($classes, $class) : 0;
                    }
                }
            }
        }

        return $classes;
    }
}
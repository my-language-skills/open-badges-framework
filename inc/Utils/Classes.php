<?php
/**
 * The Classes Class contain all
 * the function for the management of the classes.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace inc\Utils;

use inc\Base\User;
use Inc\Pages\Admin;

class Classes {

    /**
     * This function permit to understand if the "field of education"
     * have subcategory (children) or not.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @param string $field the id of the field
     *
     * @return bool     True if have children,
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
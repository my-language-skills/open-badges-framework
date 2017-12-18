<?php

namespace inc\Utils;

use Inc\Pages\Admin;

/**
 * That class permit to mange the Fields taxonomies.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Fields {
    public $main = array();
    public $sub = array();

    /**
     * This constructor load the main fields and
     * also the sub field if they exist.
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     */
    public function __construct() {

        // Get Main
        $this->main = get_terms(array(
            'taxonomy' => Admin::TAX_FIELDS,
            'hide_empty' => false,
            'parent' => 0,
        ));

        if (is_wp_error($this->main)) {
            $this->main = array();
        } elseif (self::haveChildren()) {
            foreach ($this->main as $parent) {
                //In this foreach we're getting all the childs of the parents
                $children = get_terms(array(
                    'taxonomy' => Admin::TAX_FIELDS,
                    'hide_empty' => false,
                    'child_of' => $parent->term_id
                ));
                //and punt inside an array
                $this->sub["$parent->slug"] = $children;
            }
        }
    }

    /**
     * This constructor load the main fields and
     * also the sub field if they exist.
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     *
     * @return array|int|\WP_Error
     */
    public static function getFields() {
        $sub = array();
        $main = get_terms(array(
            'taxonomy' => Admin::TAX_FIELDS,
            'hide_empty' => false,
            'parent' => 0,
        ));

        if (self::haveChildren()) {

            foreach ($main as $parent) {
                //In this foreach we're getting all the childs of the parents
                $children = get_terms(array(
                    'taxonomy' => Admin::TAX_FIELDS,
                    'hide_empty' => false,
                    'child_of' => $parent->term_id
                ));
                //and punt inside an array
                $sub["$parent->slug"] = $children;
            }
            return array($main, $sub);
        }

        return $main;
    }

    /**
     * This function permit to understand if the "field of education"
     * have subcategory (children) or not.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @return bool     True if have children,
     *                  False if don't have children
     */
    public static function haveChildren() {
        $parents = get_terms(array(
            'taxonomy' => Admin::TAX_FIELDS,
            'hide_empty' => false,
            'parent' => 0,
        ));

        foreach ($parents as $parent) {
            if (get_term_children($parent->term_id, Admin::TAX_FIELDS)) {
                return true;
            }
        }

        return false;
    }

}
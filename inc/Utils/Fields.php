<?php
/**
 * ...
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     BadgeIssuerForWp
 */

namespace inc\Utils;


use Inc\Pages\Admin;

class Fields {

    private $tax_name = Admin::TAX_FIELDS;

    /**
     * This function permit to get the fields of education.
     *
     * @author  Alessandro RICCARDI
     * @since   x.x.x
     *
     * @return  If the taxonomy don't have a father and children
     *          conformation return a simple list of oll the Fileds,
     *          else return an array of parents, inside of every
     *          parent there are children of the specific parent.
     */
    public function getAllFields() {

        if (!self::haveChildren()) {
            $fields = get_terms(array(
                'taxonomy' => $this->tax_name,
                'hide_empty' => false,
            ));

            return $fields;
        } else {
            // In case we have subcategory
            $parents = get_terms(array(
                'taxonomy' => $this->tax_name,
                'hide_empty' => false,
                'parent' => 0,
            ));

            foreach ($parents as $parent) {
                //In this foreach we're getting all the childs of the parents
                $children = get_terms(array(
                    'taxonomy' => $this->tax_name,
                    'hide_empty' => false,
                    'child_of' => $parent->term_id
                ));
                //and punt inside an array
                $parentsAndChild["$parent->slug"] = $children;
            }

            return $parentsAndChild;
        }
    }

    /**
     * This function permit to understand if the "field of education" have subcategory (children) or not.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @return bool     True if have children,
     *                  False if don't have children
     */
    function haveChildren() {
        $parents = get_terms(array(
            'taxonomy' => $this->tax_name,
            'hide_empty' => false,
            'parent' => 0,
        ));

        foreach ($parents as $parent) {
            if (get_term_children($parent->term_id, $this->tax_name)) {
                return true;
            }
        }

        return false;
    }

}
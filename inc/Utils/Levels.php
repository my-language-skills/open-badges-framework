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
use Inc\Base\User;

class Levels {

    private $tax_name;
    public $levels = array();
    public $badges = array();

    /**
     * Fields constructor.
     */
    public function __construct() {
        $this->tax_name = Admin::TAX_LEVELS;

        // Get Main
        $this->levels = get_terms(array(
            'taxonomy' => $this->tax_name,
            'hide_empty' => false,
        ));

        $this->badges = get_posts(array(
            'post_type' => $this->tax_name,
            'numberposts' => -1
        ));
        print_r($this->badges);
    }

    /**
     * Returns the right levels.
     *
     * @author Nicolas TORION
     * @since  0.4
     * @since  0.6.3
     *
     * @param string | $rightFieldEdu field of education selected in the first step
     * @return array $levels Array of all levels found.
     */
    function getAllLevels($rightFieldEdu = "") {
        // Variables
        $levels = array();
/*
        foreach ($this->badges as $badge) {
            // Get the type of the badge (student or teacher)
            $badge_type = get_post_meta($badge->ID, "_type", true);
            // Get the level of the badge
            $level = get_the_terms($badge->ID, 'level')[0]->name;
            // Get the field of the badge
            $fields = get_the_terms($badge->ID, 'field_of_education');

            //If there is no fields of education in the badge, means that is part of
            // all the fields (category).
            if (!$fields) {
                if (!in_array($level, $levels)) {
                    if (User::check_the_rules("administrator", "editor")) {
                        $levels[] = $level;
                    } else {
                        if ($badge_type == "student") {
                            $levels[] = $level;
                        }
                    }
                }
            } else {
                foreach ($fields as $field) {
                    if (!in_array($level, $levels)) {
                        // Check if the Field of education selected in the first step
                        // is content in one of the badge of the level.
                        if ($field->name == $rightFieldEdu) {
                            if (User::check_the_rules("administrator", "editor")) {
                                $levels[] = $level;
                            } else {
                                if ($badge_type == "student") {
                                    $levels[] = $level;
                                }
                            }
                        }
                    }
                }
            }
        }

        sort($levels);
        return $levels;*/
    }

}
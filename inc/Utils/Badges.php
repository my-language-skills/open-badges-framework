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

use inc\Base\User;
use Inc\Pages\Admin;

class Badges {

    public $badges = array();

    public function __construct() {
        $this->badges = get_posts(array(
            'post_type' => Admin::POST_TYPE_BADGES,
            'orderby' => 'name',
            'order' => 'DESC',
            'numberposts' => -1
        ));
    }

    public function getBadges($field = "", $level = "") {
        if ($field = "" && $level = "") {
            return $this->badges;
        } else {
            // Variable
            $allBadges = array();

            foreach ($this->badges as $badge) {
                $fieldOK = 0;
                $fields = get_the_terms($badge->ID, Admin::TAX_FIELDS);
                $badgeLevel = get_the_terms($badge->ID, Admin::TAX_LEVELS)[0]->name;

                foreach ($fields as $field) {
                    if ($field->name == $field) $fieldOK = 1;
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

    public function getBadgeById($ID) {
        if (!$ID) {
            return null;
        } else {
            foreach ($this->badges as $badge) {
                if($badge->ID == $ID) return $badge;
            }
            return null;
        }
    }

}
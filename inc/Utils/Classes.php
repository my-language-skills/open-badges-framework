<?php
/**
 * The Classes Class.
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

    public $classes = null;

    public function __construct() {
        $this->classes = get_posts(array(
            'post_type' => Admin::POST_TYPE_CLASS_JL,
            'orderby' => 'name',
            'order' => 'DESC',
            'numberposts' => -1
        ));
    }

    public function getOwnClass($field = "") {
        $userId = User::getCurrentUser()->ID;
        $classes = array();
        foreach ($this->classes as $class) {
            $fieldsPost = wp_get_post_terms( $class->ID, Admin::TAX_FIELDS );
            if ($class->post_author == $userId){
                if (!$fieldsPost){
                    array_push($classes, $class);
                } else {
                    foreach ($fieldsPost as $fieldPost) {
                        $fieldPost->name == $field ? array_push($classes, $class) : 0 ;
                    }
                }
            }
        }
        return $classes;
    }
}
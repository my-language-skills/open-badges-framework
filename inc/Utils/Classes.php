<?php
/**
 * Created by PhpStorm.
 * User: aleric
 * Date: 07/11/2017
 * Time: 11:50
 */

namespace inc\Utils;

use Inc\Pages\Admin;

class Classes {

    public $classes = null;

    public function __construct() {
        $this->classes = get_posts(array(
            'post_type' => Admin::POST_TYPE_CLASS,
            'orderby' => 'name',
            'order' => 'DESC',
            'numberposts' => -1
        ));
    }

    public function getOwnClass() {


    }
}
<?php

namespace Inc\Api;

use Inc\Pages\Admin;

/**
 * Hear are stored all callback function for the meta-box.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class MetaboxApi {

    public $cert_mtb;
    public $target_mtb;
    public $lbadge_mtb;

    public function __construct() {
        $this->cert_mtb = Admin::MTB_CERT;
        $this->target_mtb = Admin::MTB_TARGET;
        $this->lbadge_mtb = Admin::MTB_LBADGE;
        add_action('save_post', array($this, 'saveMetaboxes'));
    }

    function saveMetaboxes($post_ID) {
        if (isset($_POST['certification_input'])) {
            update_post_meta($post_ID, '_certification', esc_html($_POST['certification_input']));
        }

        if (isset($_POST['target_input'])) {
            update_post_meta($post_ID, '_target', esc_html($_POST['target_input']));
        }
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @param $post
     */
    public static function certification($post) {
        $val = get_post_meta($post->ID, '_certification', true);

        echo '<input type="radio" value="not_certified" name="certification_input"';
        self::check($val, 'not_certified');
        printf(__('> Not certified<br>', 'open-badges-framework'));

        echo '<input type="radio" value="certified" name="certification_input"';
        self::check($val, 'certified');
        printf(__('> Certified<br>', 'open-badges-framework'));
    }


    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public static function target($post) {
        $val = get_post_meta($post->ID, '_target', true);

        echo '<input type="radio" value="student" name="target_input"';
        self::check($val, 'student');
        printf(__('> Student<br>', 'open-badges-framework'));

        echo '<input type="radio" value="teacher" name="target_input"';
        self::check($val, 'teacher');
        printf(__('> Teacher<br>', 'open-badges-framework'));

    }


    /**
     * Check if the $val is equal to the $expected value.
     *
     * @author Nicolas TORION
     * @since  0.4
     *
     * @param $val      value to verify
     * @param $expected value that is confronted with the first param.
     */
    function check($val, $expected) {
        if ($val == $expected) {
            echo " checked";
        }
    }
}
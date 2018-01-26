<?php

namespace Inc\Base;

use Inc\Pages\Admin;

/**
 * Hear are stored all callback function for the meta-box.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Metabox {
    const META_FIELD_CERT = "certified";
    const META_FIELD_NOT_CERT = "not_certified";
    const META_FIELD_STUDENT = "student";
    const META_FIELD_TEACHER = "teacher";

    /**
     * Calling the save_post hook.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function __construct() {
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
     * Certification meta-box.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @param $post
     */
    public static function certification($post) {
        $val = get_post_meta($post->ID, '_certification', true);

        echo '<input type="radio" value="'.self::META_FIELD_CERT.'" name="certification_input"';
        self::check($val, self::META_FIELD_CERT);
        printf(__('> Certified<br>', 'open-badges-framework'));

        echo '<input type="radio" value="'.self::META_FIELD_NOT_CERT.'" name="certification_input"';
        self::check($val, self::META_FIELD_NOT_CERT);
        printf(__('> Not certified<br>', 'open-badges-framework'));
    }


    /**
     * Certification meta-box.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public static function target($post) {
        $val = get_post_meta($post->ID, '_target', true);

        echo '<input type="radio" value="'.self::META_FIELD_STUDENT.'" name="target_input"';
        self::check($val, self::META_FIELD_STUDENT);
        printf(__('> Student<br>', 'open-badges-framework'));

        echo '<input type="radio" value="'.self::META_FIELD_TEACHER.'" name="target_input"';
        self::check($val, self::META_FIELD_TEACHER);
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
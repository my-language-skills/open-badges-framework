<?php
/**
 * The MetaboxApi Class, hear are stored
 * all callback function for the metabox.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace Inc\Api;

use Inc\Pages\Admin;

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
     * ...
     * Not don't work
     *
     * @author Nicolas TORION
     * @since  0.6.1
     */
    function display_add_link() {
        echo '<tr>';
        echo '<td width="0%">';
        //display_fieldEdu($category = "most-important-languages", $language_selected = "", $multiple = true);
        echo '</td>';
        echo '<td width="100%">';
        echo '<center><input type="text" size="50" name="link_url[]" value="" /></center>';
        echo '</td>';
        echo '<td width="0%">';
        echo '<a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#">Remove</a>';
        echo '</td>';
        echo '</tr>';
    }

    /**
     * Meta box links
     *
     * @author Nicolas TORION
     * @since  0.6.1
     */
function meta_box_links($post) {
    if (get_post_meta($post->ID, '_badge_links', true)) {
        $badge_links = get_post_meta($post->ID, '_badge_links', true);
    } else {
        $badge_links = array();
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            jQuery('#add-row').on('click', function () {
                var content = '<?php display_add_link(); ?>';
                jQuery("#box_links tbody").append(content);
                return false;
            });

            $.fn.RemoveTr = function () {
                jQuery(this).parent('td').parent('tr').remove();
            };

            return false;
        });
    </script>

    <table id="box_links" width="100%">
        <thead>
        <tr>
            <th width="0%">Language</th>
            <th width="100%">URL</th>
            <th width="0%"></th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($badge_links as $link_lang => $link_url) {
            echo '<tr>';
            echo '<td width="0%">';
            display_fieldEdu($category = "most-important-languages", $language_selected = $link_lang, $multiple = true);
            echo '</td>';
            echo '<td width="100%">';
            echo '<center><input type="text" size="50" name="link_url[]" value="' . $link_url . '" /></center>';
            echo '</td>';
            echo '<td width="0%">';
            echo '<a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#">Remove</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '<p><a id="add-row" class="button" href="#">Add another</a></p>';
        }

        /**
         * Adds the metabox students of the class.
         *
         * @author Nicolas TORION
         * @since  0.6.2
         *
         * @param $post The post
         *
         * @return
         */
        function meta_box_class_zero_students($post) {
        if (get_post_meta($post->ID, '_class_students', true)) {
            $class_students = get_post_meta($post->ID, '_class_students', true);
        } else {
            $class_students = array();
        }

        $current_user = wp_get_current_user();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                jQuery.fn.RemoveTr = function () {
                    jQuery(this).parent('center').parent('td').parent('tr').remove();
                };
                jQuery("#publish").on("click", function () {
                    save_metabox_students();
                });
                jQuery("#add_student_job_listing").on("click", function () {
                    var input_login = jQuery("#add_student_login").val();
                    var input_mail = jQuery("#add_student_mail").val();
                    var input_level = jQuery("#add_student_level").val();
                    var input_language = jQuery("#add_student_language").val();
                    var dateObj = new Date();
                    var month = dateObj.getUTCMonth() + 1; //months from 1-12
                    var day = dateObj.getUTCDate();
                    var year = dateObj.getUTCFullYear();

                    newdate = year + "-" + month + "-" + day;

                    jQuery("#box_students tbody").append(
                        '<tr><td width="0%"><center>' +
                        input_login
                        + '</center></td><td width="0%"><center>' +
                        input_mail
                        + '</center></td><td width="0%"><center>' +
                        input_level
                        + '</center></td><td width="0%"><center>' +
                        input_language
                        + '</center></td><td width="0%"><center>' +
                        newdate
                        + '</center></td><td width="0%"><center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center></td></tr>'
                    );
                    jQuery("#add_student_login").val('');
                    jQuery("#add_student_mail").val('');
                    jQuery("#add_student_level").val('');
                    jQuery("#add_student_language").val('');
                });
                return false;
            });
        </script>

        <table id="box_students" name="<?php echo $post->ID; ?>" width="100%">
            <thead>
            <tr>
                <th width="0%"><?php _e('Student\'s login', '"open-badges-framework'); ?></th>
                <th width="0%"><?php _e('Student\'s mail', '"open-badges-framework'); ?></th>
                <th width="0%"><?php _e('Level', '"open-badges-framework'); ?></th>
                <th width="0%"><?php _e('Language', '"open-badges-framework'); ?></th>
                <th width="0%"><?php _e('Date', '"open-badges-framework'); ?></th>
                <?php
                if (in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                    ?>
                    <th width="0%"><?php _e('Action', '"open-badges-framework'); ?></th>
                    <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>
    <?php
    $i = 0;
    foreach ($class_students as $student) {
        echo '<tr>';
        echo '<td width="0%">';
        echo '<center>' . $student["login"] . '</center>';
        echo '</td>';
        echo '<td width="0%">';
        echo '<center>' . $student["mail"] . '</center>';
        echo '</td>';
        echo '<td width="0%">';
        echo '<center>' . $student["level"] . '</center>';
        echo '</td>';
        echo '<td width="0%">';
        printf(__('<center>%s</center>', '"open-badges-framework'), $student["language"]);
        echo '</td>';
        echo '<td width="0%">';
        echo '<center>' . $student["date"] . '</center>';
        echo '</td>';
        if (in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
            echo '<td width="0%">';
            printf(__('<center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center>', '"open-badges-framework'));
            echo '</td>';
        }
        echo '</tr>';
        $i++;
    }

    echo '</tbody>';

    if (in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {

        echo '<tfoot>';
        echo '<tr>';
        echo '<td width="0%">';
        echo '<center>';
        echo '<input type="text" id="add_student_login"/>';
        echo '</center>';
        echo '</td>';
        echo '<td width="0%">';
        echo '<center>';
        echo '<input type="text" id="add_student_mail"/>';
        echo '</center>';
        echo '</td>';
        echo '<td width="0%">';
        echo '<center>';
        echo '<input type="text" id="add_student_level"/>';
        echo '</center>';
        echo '</td>';
        echo '<td width="0%">';
        echo '<center>';
        echo '<input type="text" id="add_student_language"/>';
        echo '</center>';
        echo '</td>';
        echo '<td width="0%">';
        echo '</td>';
        echo '<td width="0%">';
        echo '<center>';
        echo '<a class="button" href="#" id="add_student_job_listing">Add student</a>';
        echo '</center>';
        echo '</td>';
        echo '</tr>';
        echo '</tfoot>';
    }

    echo '</table>';
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
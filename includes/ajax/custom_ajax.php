<?php
/**
 * This is the ajax file.
 *
 * @author     Nicolas TORION
 * @package    custom_ajax.php
 * @subpackage includes/ajax
 * @since      0.6.3
 */

require_once '../../../../../wp-load.php';

require_once plugin_dir_path(dirname(__FILE__)) . 'utils/functions.php';
//mimic the actuall admin-ajax
define('DOING_AJAX', true);

if (!isset($_POST['action'])) {
    die('-1');
}

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$action = esc_attr(trim($_POST['action']));

//A bit of security
$allowed_actions = array(
    'action_select_class',
    'action_select_badge',
    'action_save_metabox_students',
    'action_languages_form',
    'action_save_comment',
    'action_select_description_preview'
);

/**
 * AJAX action to save metabox of students in class job listing type.
 *
 * @author Nicolas TORION
 * @since  0.4.1
 */
add_action('CUSTOMAJAX_action_save_metabox_students', 'action_save_metabox_students');

function action_save_metabox_students() {
    $post_id = $_POST['post_id'];
    update_post_meta($post_id, '_class_students', $_POST['class_students']);
    echo $_POST['class_students'];
}


/**
 * AJAX action to load all languages in a select form
 *
 * @author Nicolas TORION
 * @since  0.6.1
 */
add_action('CUSTOMAJAX_action_languages_form', 'action_languages_form');
function action_languages_form() {
    show_all_the_language($category = $_POST['slug']);
}

/**
 * AJAX action to load a preview of the description selected in a select form
 *
 * @author Nicolas TORION
 * @since  0.6.2
 */
add_action('CUSTOMAJAX_action_select_description_preview', 'action_select_description_preview');
function action_select_description_preview() {
    $badges = get_all_badges();
    foreach ($badges as $badge) {
        if ($_POST['badge_name'] == $badge->post_name) {
            $badge_description = get_badge_descriptions($badge)[$_POST['language_description_selected']];
            echo str_replace("\n", "<br>", "<p>" . $badge_description . "</p><br>");
        }
    }
}

/**
 * AJAX action to load the classes corresponding to the level and the language selected
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
add_action('CUSTOMAJAX_action_select_class', 'action_select_class');
function action_select_class() {

    global $current_user;
    wp_get_current_user();

    if (in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
        $classes = get_all_classes_zero();
        if (is_plugin_active("wp-job-manager/wp-job-manager.php")) {
            $classes_job_listing = get_all_classes();
            $classes = array_merge($classes, $classes_job_listing);
        }
    } elseif (in_array("academy", $current_user->roles)) {
        if (is_plugin_active("wp-job-manager/wp-job-manager.php")) {
            $classes = get_classes_teacher($current_user->user_login);
        }
    }

    _e('<b>Class* : </b><br />', 'badges-issuer-for-wp');
    if (count($classes) > 1) {
        echo '<a href="#" onclick="javascript:reset_input_radio()">Reset class selection</a><br />';
    }

    $settings_id_links = get_settings_links();

    if (empty($classes)) {
        if (in_array("teacher", $current_user->roles)) {
            _e('<a href="' . get_page_link($settings_id_links["link_not_academy"]) . '" target="_blank">You need an academy account in order to create your own classes.</a>', 'badges-issuer-for-wp');
        } elseif (in_array("academy", $current_user->roles)) {
            _e('<a href="' . get_page_link($settings_id_links["link_create_new_class"]) . '" target="_blank">Don\'t you want to create a specific class for that student(s) ?</a>', 'badges-issuer-for-wp');
        }
    } else {
        if (count($classes) > 1) {
            $input_type = "radio";
        } else {
            $input_type = "checkbox";
        }

        if (in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
            echo '</br><b>Default Class:</b>';
            foreach ($classes as $class) {
                if ($class->post_type == 'class') {
                    echo '<span style="margin-left:20px;"></span>';
                    echo '<label  for="class_' . $class->ID . '">' . $class->post_title . ' </label><input name="class_for_student" id="class_' . $class->ID . '" type="' . $input_type . '" value="' . $class->ID . '"/>';
                }
            }
            echo '</br></br>';
        }
        if (in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
            echo '</br><b>Specific Class:</b>';
            foreach ($classes as $class) {
                if ($class->post_type == 'job_listing') {
                    $languages = get_the_terms($class->ID, 'job_listing_category');
                    if ((in_array("academy", $current_user->roles) && in_array($_POST['language_selected'], $languages)) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                        echo '<span style="margin-left:20px;"></span>';
                        echo '<label for="class_' . $class->ID . '">' . $class->post_title . ' </label><input name="class_for_student" id="class_' . $class->ID . '" type="' . $input_type . '" value="' . $class->ID . '"/>';
                    }
                }
            }
        }
    }
}

/**
 * AJAX action to load the badges of the level given.
 *
 * @author Nicolas TORION
 * @since  0.6.2
 */
add_action('CUSTOMAJAX_action_select_badge', 'action_select_badge');
function action_select_badge() {
    $badges = get_all_badges();

    global $current_user;
    wp_get_current_user();

    if (in_array("administrator", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("editor", $current_user->roles)) {
        $badges_corresponding = get_all_badges_level($badges, $_POST['level_selected'], $certification = true);
    } else {
        $badges_corresponding = get_all_badges_level($badges, $_POST['level_selected']);
    }

    usort($badges_corresponding, function ($a, $b) {
        return strcmp($a->post_title, $b->post_title);
    });

    _e('<br /><b>Badge* : </b><br>', 'badges-issuer-for-wp');
    $first_certified_badge = true;
    echo '<div style="display:block; width:100%; overflow:hidden;">';
    foreach ($badges_corresponding as $badge) {
        if (get_post_meta($badge->ID, '_certification', true) == "not_certified") {
            echo '<div style="float:left;">';
            echo '<center><input type="radio" name="input_badge_name" class="input-badge input-hidden" id="' . $_POST['form'] . $badge->post_title . '" value="' . $badge->post_name . '"/><label for="' . $_POST['form'] . $badge->post_title . '"><img src="';
            if (get_the_post_thumbnail_url($badge->ID)) {
                echo get_the_post_thumbnail_url($badge->ID, 'thumbnail');
                echo '" width="40px" height="40px" /></label>';
                echo '</br><b>' . $badge->post_title . '</b></center>';
            } else {
                echo plugins_url('../../assets/default-badge-thumbnail.png', __FILE__);
                echo '" width="40px" height="40px" /></label></center>';
            }
            echo "</div>";
        } elseif (get_post_meta($badge->ID, '_certification', true) == "certified") {
            echo '<div style="clear:left; float:left;">';
            echo '<br><b>Certified Badges : </b><br>';
            if ($first_certified_badge) {
                $first_certified_badge = false;
            }

            echo '<center><input type="radio" name="input_badge_name" class="input-badge input-hidden" id="' . $_POST['form'] . $badge->post_title . '" value="' . $badge->post_name . '"/><label for="' . $_POST['form'] . $badge->post_title . '"><img src="';
            if (get_the_post_thumbnail_url($badge->ID)) {
                echo get_the_post_thumbnail_url($badge->ID, 'thumbnail');
                echo '" width="40px" height="40px" /></label>';
                echo '</br><b>' . $badge->post_title . '</b></center>';
            } else {
                echo plugins_url('../../assets/default-badge-thumbnail.png', __FILE__);
                echo '" width="40px" height="40px" /></label>';
            }
            echo "</div>";
        }
    }
    echo "</div>";

    ?>
    <script>
        <?php

        /**
         *
         *
         * @author Nicolas TORION
         * @since  0.6.2
         */
        $badges = get_all_badges();

        foreach ($badges as $badge) {
            $descriptions = get_badge_descriptions($badge);
            echo 'var _' . str_replace("-", "_", $badge->post_name) . '_description_languages = [';
            $i = 0;
            foreach ($descriptions as $lang => $description) {
                echo "'" . $lang . "'";
                if ($i != (sizeof($descriptions) - 1)) {
                    echo ', ';
                }
                $i++;
            }
            echo "]; \n";
        }
        ?>

        /**
         *
         *
         * @author Nicolas TORION
         * @since  0.6.2
         */
        jQuery("#badge_form_a .input-badge").on("click", function () {
            var tab_name = "_" + jQuery("#badge_form_a .input-badge:checked").val().replace('-', '_') + "_description_languages";
            var tab = eval(tab_name);

            var content = '<label for="language_description"><b><?php _e("Language of badge description1* : ", "badges-issuer-for-wp") ?></b></label><br /><select name="language_description" id="language_description">';
            tab.forEach(function (lang) {
                content = content + '<option value="' + lang + '">' + lang + '</option>';
            });

            content = content + '</select><br>';
            jQuery("#badge_form_a #result_languages_description").html(content);

            load_description("a");
        });

        /**
         *
         *
         * @author Nicolas TORION
         * @since  0.6.2
         */
        jQuery("#badge_form_b .input-badge").on("click", function () {
            var tab_name = "_" + jQuery("#badge_form_b .input-badge:checked").val().replace('-', '_') + "_description_languages";
            var tab = eval(tab_name);

            var content = '<label for="language_description"><b><?php _e("Language of badge description* : ", "badges-issuer-for-wp") ?></b></label><br /><select name="language_description" id="language_description">';

            tab.forEach(function (lang) {
                content = content + '<option value="' + lang + '">' + lang + '</option>';
            });

            content = content + '</select><br>';
            jQuery("#badge_form_b #result_languages_description").html(content);

            load_description("b");
        });

        /**
         *
         *
         * @author Nicolas TORION
         * @since  0.6.2
         */
        jQuery("#badge_form_c .input-badge").on("click", function () {
            var tab_name = "_" + jQuery("#badge_form_c .input-badge:checked").val().replace('-', '_') + "_description_languages";
            var tab = eval(tab_name);

            var content = '<label for="language_description"><b><?php _e("Language of badge description* : ", "badges-issuer-for-wp") ?></b></label><br /><select name="language_description" id="language_description">';

            tab.forEach(function (lang) {
                content = content + '<option value="' + lang + '">' + lang + '</option>';
            });

            content = content + '</select><br>';
            jQuery("#badge_form_c #result_languages_description").html(content);

            load_description("c");
        });
    </script>
    <?php
}

/**
 * AJAX action to save the modifications made on a comment
 *
 * @author Nicolas TORION
 * @since  0.5.1
 */
add_action('CUSTOMAJAX_action_save_comment', 'action_save_comment');

function action_save_comment() {
    $comment_id = $_POST['comment_id'];
    $comment_text = $_POST['comment_text'];

    $comment_arr = array();
    $comment_arr['comment_ID'] = $comment_id;
    $comment_arr['comment_content'] = $comment_text;

    wp_update_comment($comment_arr);
}

if (in_array($action, $allowed_actions)) {
    if (is_user_logged_in()) {
        do_action('CUSTOMAJAX_' . $action);
    }
} else {
    die('-1');
}

?>

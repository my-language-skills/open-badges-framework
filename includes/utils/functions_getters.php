<?php

// GETTERS FUNCTIONS

/**
 * Returns all badges that exist
 *
 * @author Nicolas TORION
 * @since  0.3
 * @return $badges Array of all badges.
 */
function get_all_badges() {
    $badges = get_posts(array(
        'post_type' => 'badge',
        'numberposts' => -1
    ));

    return $badges;
}

/**
 * Returns all the descriptions of a badge.
 *
 * @author Nicolas TORION
 * @since  0.6.1
 *
 * @param $badge_level The level of the badge.
 *
 * @return $descriptions Array of descriptions of the badge associated to their language.
 */
function get_badge_descriptions($badge) {
    $descriptions = array();

    $descriptions["Default"] = $badge->post_content;

    $comments = get_comments(array('post_id' => $badge->ID));
    foreach ($comments as $comment) {
        $lang = get_comment_meta($comment->comment_ID, '_comment_translation_language', true);
        $descriptions[$lang] = $comment->comment_content;
    }

    return $descriptions;
}

/**
 * Returns the badge informations associated to level and language given.
 *
 * @author Nicolas TORION
 * @since  0.6.2
 * @since  0.6.4 updated the way to get the badges.
 *
 * @param $badge_name The name of the badge.
 * @param $badges     A list of badges.
 * @param $lang       The language studied by the student.
 *
 * @return Array of badge's informations (name, description, image url).
 */
function get_badge($badge_name, $lang) {
    $badges = get_all_badges();
    foreach ($badges as $badge) {
        if ($badge_name == $badge->post_name) {
            $badge_description = get_badge_descriptions($badge)[$lang];

            return array(
                "id" => $badge->ID,
                "name" => $badge->post_title,
                "description" => $badge_description,
                "image" => get_the_post_thumbnail_url($badge->ID)
            );
        }
    }
}

/**
 * Returns all levels that exist.
 *
 * @author Nicolas TORION
 * @since  0.4
 *
 * @param $badges     A list of badges.
 * @param $level_type Type wanted of levels.
 *
 * @return $levels Array of all levels found.
 */
function get_all_levels($badges, $only_student = false) {
    $levels = array();
    foreach ($badges as $badge) {
        $badge_type = get_post_meta($badge->ID, "_type", true);
        $level = get_the_terms($badge->ID, 'level')[0]->name;
        if (!in_array($level, $levels)) {
            if ($only_student) {
                if ($badge_type == "student") {
                    $levels[] = $level;
                }
            } else {
                $levels[] = $level;
            }
        }
    }
    sort($levels);

    return $levels;
}

/**
 * Returns all badges of a level.
 *
 * @author Nicolas TORION
 * @since  0.3
 *
 * @param $badges A list of badges.
 *
 * @return $level The level of bagdes to find.
 */

function get_all_badges_level($badges, $level, $certification = false) {
    $badges_corresponding = array();
    foreach ($badges as $badge) {
        if (get_the_terms($badge->ID, 'level')[0]->name == $level) {
            if (get_post_meta($badge->ID, '_certification', true) == "certified") {
                if ($certification) {
                    $badges_corresponding[] = $badge;
                }
            } else {
                $badges_corresponding[] = $badge;
            }
        }
    }

    return $badges_corresponding;
}


/**
 * This function permit to understand if the "field of education" have subcategory (children) or not.
 *
 * @author Alessandro RICCARDI
 * @since  0.6.3
 *
 * @return bool     True if don't have children
 *                  False if have children
 */
function have_no_children() {
    $taxanomyName = "field_of_education";

    $parents = get_terms(array(
        'taxonomy' => $taxanomyName,
        'hide_empty' => false,
        'parent' => 0,
    ));

    foreach ($parents as $parent) {
        if (get_term_children($parent->term_id, $taxanomyName)) {
            return false;
        }
    }

    return true;
}

/**
 * This function permit to get the languages.
 *
 * @author Alessandro RICCARDI
 * @since  0.6.3
 *
 * @return bool     If have_no_children() is true return all tha value because it's mean that there aren't
 *                  children,
 *                  else return an array of parents, inside of every parent there're children of the specific
 *                  parent.
 */
function get_languages() {

    $taxanomyName = "field_of_education";

    if (have_no_children()) {
        $languages = get_terms(array(
            'taxonomy' => $taxanomyName,
            'hide_empty' => false,
            'parent' => 0,
        ));

        return $languages;
    } else {
        // In case we have subcategory
        $parents = get_terms(array(
            'taxonomy' => $taxanomyName,
            'hide_empty' => false,
            'parent' => 0,
        ));

        foreach ($parents as $parent) {
            //In this foreach we're getting all the childs of the parents
            $childs = get_terms(array(
                'taxonomy' => $taxanomyName,
                'hide_empty' => false,
                'child_of' => $parent->term_id
            ));
            //and punt inside an array
            $parentsAndChild["$parent->slug"] = $childs;
        }

        return $parentsAndChild;
    }
}

/**
 * Returns all the parent categories for languages
 *
 * @author Muhammad Uzair
 * @since  0.6.2
 * @return $all_parent_categories
 */

function get_parent_categories() {

    $parents_categories = get_terms(array(
        'taxonomy' => 'field_of_education',
        'hide_empty' => false,
        'parent' => 0,
    ));

    $categories = array();
    $all_parent_categories = array();
    $term_children = array();

    foreach ($parents_categories as $parents_category) {

        // getting the children of each parent category
        $term_children = get_term_children($parents_category->term_id, 'field_of_education');

        // if any child exists for a category, it will be added to the array
        if (!empty($term_children) && !is_wp_error($term_children)) {
            $categories[$parents_category->slug] = array(
                $parents_category->term_id,
                $parents_category->name,
                $parents_category->slug
            );
        }
    }

    $all_parent_categories = $categories;

    return $all_parent_categories;
}


/**
 * Returns all classes that exist
 *
 * @author Nicolas TORION
 * @since  0.3
 * @return $classes Array of all classes.
 */
function get_all_classes() {
    $classes = get_posts(array(
        'post_type' => 'job_listing',
        'numberposts' => -1
    ));

    return $classes;
}

/**
 * Returns all classes zero that exist
 *
 * @author Nicolas TORION
 * @since  0.3
 * @since  0.6.4 name changed
 * @return $classes Array of all classes zero.
 */
function get_all_classes_zero() {
    $classes = get_posts(array(
        'post_type' => 'class',
        'numberposts' => -1
    ));

    return $classes;
}

/**
 * Returns all the classes of a teacher.
 *
 * @author Nicolas TORION
 * @since  0.6.1
 *
 * @param $teacher_login The login of the teacher.
 *
 * @return $classes All the classes corresponding.
 */
function get_classes_teacher($teacher_login) {
    $all_classes = get_all_classes();
    $classes = array();
    foreach ($all_classes as $class) {
        if (get_userdata($class->post_author)->user_login == $teacher_login) {
            $classes[] = $class;
        }
    }

    return $classes;
}

/**
 * Returns all the class zero of a teacher.
 *
 * @author Nicolas TORION
 * @since  0.3
 *
 * @param $teacher_login The login of the teacher.
 *
 * @return $result The class zero corresponding.
 */
function get_class_teacher($teacher_login) {
    $classes = get_all_classes_zero();
    foreach ($classes as $class) {
        if ($class->post_title == $teacher_login) {
            return $class;
        }
    }

    return null;
}

/**
 * Check if a class zero exists with the name of a teacher
 *
 * @author Nicolas TORION
 * @since  0.3
 *
 * @param $teacher_name Name of the teacher
 *
 * @return Boolean indicating if the class zero exists or not.
 */
function class_school_exists($teacher_name) {
    $classes = get_all_classes_zero();
    foreach ($classes as $class) {
        if ($class->post_title == $teacher_name) {
            return true;
        }
    }

    return false;
}

/**
 * Check if a student is in a class school.
 *
 * @author Nicolas TORION
 * @since  0.5.1
 *
 * @param $student_login Login of the student.
 * @param $class_id      The ID of the class (job_listing) post.
 *
 * @return $result A boolean indicating if the student is in the class or not.
 */
function is_student_in_class($student_login, $class_id) {
    if (get_post_meta($class_id, "_class_students", true)) {
        $class_students = get_post_meta($class_id, "_class_students", true);
    } else {
        $class_students = array();
    }
    $result = false;
    foreach ($class_students as $class_student) {
        if ($class_student['login'] == $student_login) {
            $result = true;
        }
    }

    return $result;
}

/**
 * Check if a student can write a comment for the specified class.
 *
 * @author Nicolas TORION
 * @since  0.6.2
 *
 * @param $student_login The login of the student.
 * @param $class_id      The ID of the class post.
 *
 * @return $result A boolean indicating if the student can write a comment for the specified class.
 */
function can_student_write_comment($student_login, $class_id) {
    $class_post = get_post($class_id);
    $class_students = get_post_meta($class_id, '_class_students', true);
    $student_date = null;
    foreach ($class_students as $class_student) {
        if ($class_student['login'] == $student_login) {
            $student_date = $class_students['date'];
        }
    }
    if (get_days_from_date($student_date) <= 15) {
        if ($class_post->post_type == "class" && is_student_in_class($student_login, $class_id) && !has_student_write_comment($student_login, $class_id)) {
            return true;
        } elseif ($class_post->post_type == "job_listing" && is_student_in_class($student_login, $class_id) && !has_student_write_comment($student_login, $class_id)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Calculates the days passed from a date given.
 *
 * @author Nicolas TORION
 * @since  0.4.1
 *
 * @param $date The date given.
 *
 * @return The days passed from the date.
 */
function get_days_from_date($date) {
    $datetime1 = date_create($date);
    $datetime2 = date_create(date("Y-m-d"));

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format('%d');
}

/**
 * Indicates if the student has already written or not a comment for a specified class.
 *
 * @author Nicolas TORION
 * @since  0.3
 *
 * @param $student_login The login of the student.
 * @param $class_id      The ID of the class post.
 *
 * @return $result A boolean indicating if the student has already written a comment for the specified class.
 */
function has_student_write_comment($student_login, $class_id) {
    $comments = get_comments(array(
        'post_id' => $class_id,
        'number' => -1
    ));

    foreach ($comments as $comment) {
        if ($comment->comment_author == $student_login) {
            return true;
        }
    }

    return false;
}

/**
 * Indicates if the user can write or not a reply for a specified class.
 *
 * @author Nicolas TORION
 * @since  0.4.1
 *
 * @param $user_login The login of the user.
 * @param $class_id   The ID of the class post.
 *
 * @return $result A boolean indicating if the user can write or not a reply for the specified class.
 */
function can_user_reply($user_login, $class_id) {
    $class_post = get_post($class_id);

    if ($class_post->post_type == "class") {
        if ($class_post->post_title == $user_login) {
            return true;
        } else {
            return false;
        }
    } elseif ($class_post->post_type == "job_listing") {
        $author_login = get_userdata($class_post->post_author)->user_login;

        if ($author_login == $user_login) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Returns the id links written in the corresponding json file.
 *
 * @author Nicolas TORION
 * @since  0.6.1
 * @return $settings_links The array of id links.
 */
function get_settings_links() {
    $content = file_get_contents(plugin_dir_path(dirname(__FILE__)) . '../../../uploads/settings/json/links.json');
    $settings_links = json_decode($content, true);

    return $settings_links;
}

/**
 * Returns the id links written in the corresponding json file.
 *
 * @author Nicolas TORION
 * @since  0.6.1
 * @return $settings_login_links The array of id links.
 */
function get_settings_login_links() {
    $content = file_get_contents(plugin_dir_path(dirname(__FILE__)) . '../../../uploads/settings/json/login_links.json');
    $settings_login_links = json_decode($content, true);

    return $settings_login_links;
}

/**
 * Checks if the user has already a badge
 *
 * @author Nicolas TORION
 * @since  0.6.1
 *
 * @param $hash The hash for searching the badge.
 *
 * @return boolean
 */
function check_if_user_has_already_a_badge($hash) {
    global $current_user;
    wp_get_current_user();

    $badges = get_the_author_meta('badges_received', $current_user->ID);

    return in_array($hash, $badges);
}

/**
 * Get student infos in class.
 *
 * @author Nicolas TORION
 * @since  0.6.2
 *
 * @param $student_login The login of the student.
 * @param $class_id      The ID of the class.
 *
 * @return $student_infos The infos of the student.
 */
function get_student_infos_in_class($student_login, $class_id) {
    $class_students = get_post_meta($class_id, '_class_students', true);
    $student_infos = false;

    foreach ($class_students as $class_student) {
        if ($class_student['login'] == $student_login) {
            if (strtotime($student_infos['date']) < strtotime($class_student['date'])) {
                $student_infos = array();
                $student_infos['date'] = $class_student['date'];
                $student_infos['level'] = $class_student['level'];
                $student_infos['language'] = $class_student['language'];
            }
        }
    }

    return $student_infos;
}

function check_the_rules($roles){
    $res = false;
    foreach (func_get_args() as $param) {
        if(!is_array($param)){
            $res = in_array($param, $roles)? true: $res ? true: false;
        }
    }
    return $res;
}

?>

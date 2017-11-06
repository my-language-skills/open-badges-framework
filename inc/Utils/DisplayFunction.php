<?php

// DISPLAY FUNCTIONS

namespace inc\Utils;

class DisplayFunction {

    /**
     * Displays available FIELD in a select tag. Used in the forms sending badges to students.
     *
     * @author Nicolas TORION
     * @since  0.6.1
     * @since  0.6.3 recreated the function more simply
     * @since  x.x.x
     *
     * @param string $parent permit to display the child taxonomy of the parent taxonomy (category).
     */
    public function field($p_parent = "") {
        $field = new Fields();

        $selectionContOpen = '<div class="select-language"> <select name="language" id="language"> <option value="-9999" selected disabled hidden>Select</option>';
        $selectionContClose = '</select></div>';

        if (!$field->haveChildren()) {
            $languages = $field->main;

            echo $selectionContOpen;

            foreach ($languages as $language) {
                echo '<option value="' . $language->term_id . '">';
                echo $language->name . '</option>';
            }

            echo $selectionContClose;

        } else {
            //If there parent with children

            if ($p_parent === "") {
                // Display the DEFAULT parent
                $parents = $field->sub;
                echo $selectionContOpen;

                foreach ($parents as $parent) {
                    foreach ($parent as $language) {

                        echo '<option value="' . $language->term_id . '">';
                        echo $language->name . '</option>';
                    }
                    break;
                }
                echo $selectionContClose;

            } else if ($p_parent === "all_field") {
                // Display ALL the child
                $parents = $field->sub;

                echo $selectionContOpen;

                foreach ($parents as $parent) {
                    foreach ($parent as $language) {
                        echo '<option value="' . $language->term_id . '">';
                        echo $language->name . '</option>';
                    }
                }
                echo $selectionContClose;

            } else {
                // Display the children of the right PARENT
                $parents = $field->sub;

                echo $selectionContOpen;

                foreach ((array)$parents[$p_parent] as $language) {
                    echo '<option value="' . $language->term_id . '">';
                    echo $language->name . '</option>';
                }

                echo $selectionContClose;

            }

        }
    }


    /**
     * Displays all the parents like a button that permit you
     * to change the visualization of the Fields of education
     *
     * @author Alessandro RICCARDI
     * @since  0.6.3
     *
     */
    function display_parents() {
        $parents = get_languages();
        $actual_parent = key($parents);
        $haveCat = false;

        if ($parents = get_parent_categories()) {

            echo '<div class="btns-parent-field">';

            foreach ($parents as $parent) {
                $haveCat = true;
                if ($parent[2] == $actual_parent) {
                    echo '<a class="btn btn-default btn-xs display_parent_categories active" id="' . $parent[2] . '">Display ' . $parent[1] . '</a>';
                } else {
                    echo '<a class="btn btn-default btn-xs display_parent_categories" id="' . $parent[2] . '">Display ' . $parent[1] . '</a>';
                }
            }

            // Display the link to show all the languages
            if ($haveCat) {
                echo '<a class="btn btn-default btn-xs display_parent_categories" id="all_field">Display all Fields</a>';
            }
        }
    }


    /**
     * Displays the classes of the teacher in input tags. Used in the forms sending badges to students.
     *
     * @author Nicolas TORION
     * @since  0.6
     */
    /*function display_classes_input() {
        global $current_user;
        wp_get_current_user();

        if (check_the_rules("administrator", "editor")) {
            $classes = get_all_classes();
        } else {
            $classes = get_classes_teacher($current_user->user_login);
        }

        printf(esc_html__('<b>Class* : </b><br />', 'badges-issuer-for-wp'));
        foreach ($classes as $class) {
            echo '<label for="class_' . $class->ID . '">' . $class->post_title . ' </label><input name="class_for_student" id="class_' . $class->ID . '" type="radio" value="' . $class->ID . '"/>';
        }
    }*/

    /**
     * Displays a message of success.
     *
     * @author Nicolas TORION
     * @since  0.3
     *
     * @param $message The message to display.
     */
    function display_success_message($message) {
        ?>
        <div class="message msg-success">
            <?php echo $message; ?>
        </div>
        <?php
    }

    /**
     * Displays a message of error.
     *
     * @author Nicolas TORION
     * @since  0.3
     *
     * @param $message The message to display.
     */
    function display_error_message($message) {
        ?>
        <div class="message error">
            <?php echo $message; ?>
        </div>
        <?php
    }

    function display_sendBadges_info($message) {
        echo '<div class="lead">' . $message . '</div> <hr class="hr-sb">';
    }

    /**
     * Displays a message indicating that a person is not logged. A link redirecting to the login page is also
     * displayed.
     *
     * @author Nicolas TORION
     * @since  0.6.3
     */
    function display_not_logged_message() {
        $settings_id_login_links = get_settings_login_links();
        ?>

        <center>
            <img src="<?php echo plugins_url('../../assets/b4l_logo.png', __FILE__); ?>" width="256px"
                 height="256px"/>
            <br/>
            <h1><?php _e('To get a badge, you need to be logged on the site.', 'badges-issuer-for-wp'); ?></h1>
            <br/>
            <a href="<?php echo get_page_link($settings_id_login_links["link_register"]); ?>"
               title="Register"><?php _e('Register', 'badges-issuer-for-wp'); ?></a> | <a
                    href="<?php echo get_page_link($settings_id_login_links["link_login"]); ?>"
                    title="Login"><?php _e('Login', 'badges-issuer-for-wp'); ?></a>
            <p style="color:red;">
                <?php
                _e('Once connected to the site, go back to your email and click again on the link for receiving your badge.', 'badges-issuer-for-wp');
                ?>
            </p>
        </center>
        <?php
    }
}

?>

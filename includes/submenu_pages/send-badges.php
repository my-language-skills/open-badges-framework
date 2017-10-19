<?php
/**
 * Create a submenu page in the administration menu to allow a teacher to send a badge to students.
 *
 * @author     Nicolas TORION
 * @package    badges-issuer-for-wp
 * @subpackage includes/submenu_pages
 * @since      0.3
 */


require_once plugin_dir_path(dirname(__FILE__)) . 'utils/functions.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'utils/class.badge.php';
/**
 * Adds b4l_send_badges_one_student_submenu_page to the admin menu.
 */
add_action('admin_menu', 'send_badges_submenu_page');

/**
 * Creates the submenu page.
 *
 * The capability allows superadmin, admin, editor and author to see this submenu.
 * If you change to change the permissions, use manage_options as capability (for
 * superadmin and admin).
 */
function send_badges_submenu_page() {

    add_submenu_page('edit.php?post_type=badge', 'Send Badges', 'Send Badges', 'capability_send_badge', //capability: 'edit_posts' to give automatically the access to author/editor/admin
        'send-badges', 'send_badges_page_callback');

}

/**
 * Displays the content of the submenu page
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function send_badges_page_callback() {
    global $current_user;
    wp_get_current_user();
    ?>
    <script>

    </script>

    <style>
        .tabs-inline li {
            display: inline;
            list-style: none;
        }
    </style>

    <div class="wrap">
        <br><br>
        <input type="hidden" name="sender" value="<?php echo $current_user->user_email; ?>"/>
        <h1><i><span class="dashicons dashicons-awards"></span><?php _e('Send Badges', 'badges-issuer-for-wp'); ?></i>
        </h1>
        <h3>Select the possibility to send the badge.</h3>
        <div id="tabs">
            <div id="tabs-elements">
                <div>
                    <h2 class="nav-tab-wrapper">
                        <ul class="tabs-inline">
                            <li><a href="#tabs-1">
                                    <div class="nav-tab nav-tab-active"
                                         id="nav-badge-a"><?php _e('Self', 'badges-issuer-for-wp'); ?></div>
                                </a></li>
                            <?php
                            if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                                ?>
                                <li><a href="#tabs-2">
                                        <div class="nav-tab"
                                             id="nav-badge-b"><?php _e('Issue', 'badges-issuer-for-wp'); ?></div>
                                    </a></li>
                                <?php
                                if (in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                                    ?>
                                    <li><a href="#tabs-3">
                                            <div class="nav-tab"
                                                 id="nav-badge-c"><?php _e('Multiple issue', 'badges-issuer-for-wp'); ?></div>
                                        </a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </h2>
                </div>
            </div>
            <div id="tabs-1">
                <?php tab_self(); ?>
            </div>
            <?php
            if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                ?>
                <div id="tabs-2">
                    <?php tab_issue(); ?>
                </div>
                <?php
                if (in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                    ?>
                    <div id="tabs-3">
                        <?php tab_multiple(); ?>
                    </div>
                    <?php
                }
            } ?>
        </div>
    </div>
    <?php
}

/**
 * The content of the tab for sending a badge to himself.
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function tab_self() { ?>

    <div class="tab-content">
        <form id="badge_form_a" action="" method="post">
            <?php
            global $current_user;
            wp_get_current_user();
            // get all badges that exist
            $badges = get_all_badges();
            ?>
            <div>
                <h3>Field of Education</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select your field of education:</h2></div>
                        <?php
                        $parents = get_languages();
                        $actual_parent = key($parents);
                        display_parents($actual_parent);
                        ?>
                        <div id="field_edu_a"><?php show_all_the_language("", "a"); ?></div>
                    </div>
                </section>
                <h3>Level</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <div id="languages_form_a"><?php display_levels_radio_buttons($badges, "self"); ?></div>
                    </div>
                </section>

                <h3>Badge</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the kind of badge:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="select_badge">
                            <img src="<?php echo plugins_url('../../assets/default-badge.png', __FILE__); ?>"
                                 width="72px" height="72px"/>
                        </div>
                    </div>
                </section>

                <h3>Language</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Check the language:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="result_languages_description"></div>
                        <div id="result_preview_description"></div>
                    </div>
                </section>

                <h3>Information</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Addition information:</h2></div>
                        <hr class="sep-sendbadge">
                        <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>
                    </div>
                </section>
            </div>
        </form>
    </div>

    <?php
}

/**
 * The content of the tab for sending a badge to someone.
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function tab_issue() { ?>

    <div class="tab-content">
        <form id="badge_form_b" action="" method="post">
            <?php
            global $current_user;
            wp_get_current_user();
            // get all badges that exist
            $badges = get_all_badges();
            ?>
            <div>
                <h3>Field of Education</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select your field of education:</h2></div>
                        <?php
                        $parents = get_languages();
                        $actual_parent = key($parents);
                        display_parents($actual_parent);
                        ?>
                        <div id="field_edu_b"><?php show_all_the_language("", "b"); ?></div>
                    </div>
                </section>
                <h3>Level</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <div id="languages_form_b"><?php display_levels_radio_buttons($badges, "self"); ?></div>
                    </div>
                </section>

                <h3>Badge</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the kind of badge:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="select_badge">
                            <input type="radio" name="input_badge_name" class="input-badge input-hidden" id="form_a_A1"
                                   value="a1">
                            <label for="form_a_A1">
                                <img id="img-send-badge"
                                     src="<?php echo plugins_url('../../assets/default-badge.png', __FILE__); ?>">
                            </label>
                            <br>
                            <b>A1</b>
                        </div>
                    </div>
                </section>

                <h3>Language</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Check the language:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="result_languages_description"></div>
                        <div id="result_preview_description"></div>
                    </div>
                </section>

                <h3>Class</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Class:</h2></div>
                        <hr class="sep-sendbadge">
                        <?php
                        if (in_array("academy", $current_user->roles) || in_array("teacher", $current_user->roles)) {
                            $class_zero = get_class_teacher($current_user->user_login);
                            echo '<input name="class_teacher" type="hidden" value="' . $class_zero->ID . '"/>';
                        }

                        if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                            echo '<div id="select_class"></div>';
                        }
                        ?>
                    </div>
                </section>

                <h3>Email</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Receiver's mail adress:</h2></div>
                        <hr class="sep-sendbadge">
                        <input class="regular-text try-center" type="text" name="mail" id="mail" class="mail"/>
                    </div>
                </section>

                <h3>Information</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Addition information:</h2></div>
                        <hr class="sep-sendbadge">
                        <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>
                    </div>
                </section>
            </div>
        </form>
    </div>
    <?php
}

/**
 * The content of the tab for sending a badge to several persons.
 *
 * @author Nicolas TORION
 * @since  0.6.3
 */
function tab_multiple() { ?>

    <div class="tab-content">
        <form id="badge_form_c" action="" method="post">
            <?php
            global $current_user;
            wp_get_current_user();
            // get all badges that exist
            $badges = get_all_badges();
            ?>
            <div>
                <h3>Field of Education</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select your field of education:</h2></div>
                        <?php
                        $parents = get_languages();
                        $actual_parent = key($parents);
                        display_parents($actual_parent);
                        ?>
                        <div id="field_edu_b"><?php show_all_the_language("", "b"); ?></div>
                    </div>
                </section>
                <h3>Level</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <div id="languages_form_b"><?php display_levels_radio_buttons($badges, "self"); ?></div>
                    </div>
                </section>

                <h3>Badge</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the kind of badge:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="select_badge">
                            <input type="radio" name="input_badge_name" class="input-badge input-hidden" id="form_a_A1"
                                   value="a1">
                            <label for="form_a_A1">
                                <img id="img-send-badge"
                                     src="<?php echo plugins_url('../../assets/default-badge.png', __FILE__); ?>">
                            </label>
                            <br>
                            <b>A1</b>
                        </div>
                    </div>
                </section>

                <h3>Language</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Check the language:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="result_languages_description"></div>
                        <div id="result_preview_description"></div>
                    </div>
                </section>

                <h3>Class</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Class:</h2></div>
                        <hr class="sep-sendbadge">
                        <?php
                        if (in_array("academy", $current_user->roles) || in_array("teacher", $current_user->roles)) {
                            $class_zero = get_class_teacher($current_user->user_login);
                            echo '<input name="class_teacher" type="hidden" value="' . $class_zero->ID . '"/>';
                        }

                        if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles) || in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
                            echo '<div id="select_class"></div>';
                        }
                        ?>
                    </div>
                </section>

                <h3>Email</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Receiver's mail adress:</h2></div>
                        <hr class="sep-sendbadge">
                        <p>To send multiple email, write each address per line.</p><br>
                        <textarea name="mail" id="mail" class="mail" rows="10" cols="50"></textarea>
                    </div>
                </section>

                <h3>Information</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Addition information:</h2></div>
                        <hr class="sep-sendbadge">
                        <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>
                    </div>
                </section>
            </div>
        </form>
    </div>
    <?php
}

add_shortcode('send_badge', 'send_badges_page_callback');
// Adding the shortcode to send the badge to yourself
add_shortcode('send-self', 'tab_self');
// Adding the shortcode to send a badge to a single person
add_shortcode('send-single', 'tab_issue');
// Adding the shortcode to send the badge to several persons
add_shortcode('send-multiple', 'tab_multiple');

?>

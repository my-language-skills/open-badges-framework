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
    <div class="wrap">
        <br><br>
        <input type="hidden" name="sender" value="<?php echo $current_user->user_email; ?>"/>
        <div class="title-page-admin">
            <h1>
                <i>
                    <span class="dashicons dashicons-awards"></span>
                    <?php echo get_admin_page_title();?>
                </i>
            </h1>
        </div>
        <br>
        <div class="tab">
            <button class="tablinks" onclick="openCity(event, 'London')">Self</button>
            <?php
            if (check_the_rules($current_user->roles, "academy", "teacher", "administrator", "editor")) {
                ?>
                <button class="tablinks" onclick="openCity(event, 'Paris')">Issue</button>
                <?php
                if (check_the_rules($current_user->roles, "academy", "administrator", "editor")) {
                    ?>
                    <button class="tablinks" onclick="openCity(event, 'Tokyo')">Multiple issue</button>
                <?php } ?>
            <?php } ?>
        </div>

        <div id="London" class="tabcontent">
            <?php tab_self(); ?>
        </div>
        <?php
        if (check_the_rules($current_user->roles, "academy", "teacher", "administrator", "editor")) {
            ?>
            <div id="Paris" class="tabcontent">
                <?php tab_issue(); ?>
            </div>
            <?php
            if (check_the_rules($current_user->roles, "academy", "administrator", "editor")) {
                ?>
                <div id="Tokyo" class="tabcontent">
                    <?php tab_multiple(); ?>
                </div>
                <?php
            }
        } ?>
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
            ?>
            <div>
                <h3>Field of Education</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select your field of education:</h2></div>
                        <?php display_parents(); ?>
                        <div id="field_edu_a"><?php display_fieldEdu(); ?></div>
                    </div>
                </section>
                <h3>Level</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <div id="languages_form_a"></div>
                    </div>
                </section>

                <h3>Badge</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the kind of badge:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="select_badge"></div>
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
function tab_issue() {
    global $current_user;
    wp_get_current_user();
    // get all badges that exist
    ?>

    <div class="tab-content">
        <form id="badge_form_b" action="" method="post">
            <div>
                <h3>Field of Education</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select your field of education:</h2></div>
                        <?php display_parents(); ?>
                        <div id="field_edu_b"><?php display_fieldEdu(); ?></div>
                    </div>
                </section>
                <h3>Level</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <div id="languages_form_b"></div>
                    </div>
                </section>

                <h3>Badge</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the kind of badge:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="select_badge"></div>
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
                        if (check_the_rules($current_user->roles, "academy", "teacher")) {
                            $class_zero = get_class_teacher($current_user->user_login);
                            echo '<input name="class_teacher" type="hidden" value="' . $class_zero->ID . '"/>';
                        }

                        if (check_the_rules($current_user->roles, "teacher", "academy", "administrator", "editor")) {
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
function tab_multiple() {
    global $current_user;
    wp_get_current_user();
    // get all badges that exist
    ?>

    <div class="tab-content">
        <form id="badge_form_c" action="" method="post">
            <div>
                <h3>Field of Education</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select your field of education:</h2></div>
                        <?php display_parents(); ?>
                        <div id="field_edu_c"><?php display_fieldEdu(); ?></div>
                    </div>
                </section>
                <h3>Level</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the level:</h2></div>
                        <div id="languages_form_c"></div>
                    </div>
                </section>

                <h3>Badge</h3>
                <section>
                    <div class="section-container">
                        <div class="title-form"><h2>Select the kind of badge:</h2></div>
                        <hr class="sep-sendbadge">
                        <div id="select_badge"></div>
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
                        if (check_the_rules($current_user->roles, "academy", "teacher")) {
                            $class_zero = get_class_teacher($current_user->user_login);
                            echo '<input name="class_teacher" type="hidden" value="' . $class_zero->ID . '"/>';
                        }

                        if (check_the_rules($current_user->roles, "teacher", "academy", "administrator", "editor")) {
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

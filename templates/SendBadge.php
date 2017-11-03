<?php
/**
 * ...
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     BadgeIssuerForWp
 */

namespace templates;


use inc\Base\User;
use inc\Utils\Fields;

final class SendBadge {

    public function __construct() {
        self::addShortcode();
    }

    public static function addShortcode(){
        add_shortcode('send_badge', array(SendBadge::class,'main'));
        // Adding the shortcode to send the badge to yourself
        add_shortcode('send-self', array(SendBadge::class, 'getTabSelf'));
        // Adding the shortcode to send a badge to a single person
        add_shortcode('send-single', array(SendBadge::class, 'getTabIssue'));
        // Adding the shortcode to send the badge to several persons
        add_shortcode('send-multiple', array(SendBadge::class, 'getTabMultiple'));
    }

    /**
     * Displays the content of the submenu page
     *
     * @author Nicolas TORION
     * @since  0.6.3
     */
    public static function main() {
        global $current_user;
        wp_get_current_user();
        ?>
        <div class="wrap">
            <br><br>
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
                <button class="tablinks" onclick="openCity(event, 'Self')">Self</button>
                <?php
                if (User::check_the_rules($current_user->roles, "academy", "teacher", "administrator", "editor")) {
                    ?>
                    <button class="tablinks" onclick="openCity(event, 'Issue')">Issue</button>
                    <?php
                    if (User::check_the_rules($current_user->roles, "academy", "administrator", "editor")) {
                        ?>
                        <button class="tablinks" onclick="openCity(event, 'Multiple')">Multiple issue</button>
                    <?php } ?>
                <?php } ?>
            </div>

            <div id="Self" class="tabcontent">
                <?php self::getTabSelf(); ?>
            </div>
            <?php
            if (User::check_the_rules($current_user->roles, "academy", "teacher", "administrator", "editor")) {
                ?>
                <div id="Issue" class="tabcontent">
                    <?php //self::getTabIssue(); ?>
                </div>
                <?php
                if (User::check_the_rules($current_user->roles, "academy", "administrator", "editor")) {
                    ?>
                    <div id="Multiple" class="tabcontent">
                        <?php //self::getTabMultiple(); ?>
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
    function getTabSelf() {
        global $current_user;
        wp_get_current_user();
        ?>
        <input type="hidden" name="sender" value="<?php echo $current_user->user_email; ?>"/>
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
                            <?php
                            self::displayLeadInfo("Change the visualization of the fields of education with the
                                                    below buttons an then select the field");
                            ?>
                            <?php self::displayFieldsButtons(); ?>
                            <div id="field_edu_a"><?php //display_fieldEdu(); ?></div>
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
                            <div id="select_badge"></div>
                        </div>
                    </section>

                    <h3>Language</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Check the language:</h2></div>
                            <div id="result_languages_description"></div>
                            <div id="result_preview_description"></div>
                        </div>
                    </section>

                    <h3>Information</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Addition information:</h2></div>
                            <?php self::displayLeadInfo("Write some information that will be showed in the description of badge"); ?>
                            <textarea placeholder="More than 10 letters ..." name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>
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
    function getTabIssue() {
        global $current_user;
        wp_get_current_user();
        ?>
        <input type="hidden" name="sender" value="<?php echo $current_user->user_email; ?>"/>

        <div class="tab-content">
            <form id="badge_form_b" action="" method="post">
                <div>
                    <h3>Field of Education</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select your field of education:</h2></div>
                            <?php
                            self::displayLeadInfo("Change the visualization of the fields of education with the
                                                    below buttons an then select the field");
                            ?>
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
                            <div id="select_badge"></div>
                        </div>
                    </section>

                    <h3>Language</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Check the language:</h2></div>
                            <div id="result_languages_description"></div>
                            <div id="result_preview_description"></div>
                        </div>
                    </section>

                    <h3>Class</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Class:</h2></div>
                            <?php
                            if (User::check_the_rules($current_user->roles, "academy", "teacher")) {
                                $class_zero = get_class_teacher($current_user->user_login);
                                echo '<input name="class_teacher" type="hidden" value="' . $class_zero->ID . '"/>';
                            }

                            if (User::check_the_rules($current_user->roles, "teacher", "academy", "administrator", "editor")) {
                                echo '<div id="select_class"></div>';
                            }
                            ?>
                        </div>
                    </section>

                    <h3>Email</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Receiver's mail addresses:</h2></div>
                            <?php self::displayLeadInfo("Write the emails of the receiver badge, to send multiple email, write each address per line"); ?>
                            <textarea name="mail" id="mail" class="mail" rows="10" cols="50"></textarea>
                        </div>
                    </section>

                    <h3>Information</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Addition information:</h2></div>
                            <?php self::displayLeadInfo("Write some information that will be showed in the description of badge"); ?>
                            <textarea placeholder="More than 10 letters ..."  name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>
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
    function getTabMultiple() {
        global $current_user;
        wp_get_current_user();
        ?>
        <input type="hidden" name="sender" value="<?php echo $current_user->user_email; ?>"/>

        <div class="tab-content">
            <form id="badge_form_c" action="" method="post">
                <div>
                    <h3>Field of Education</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select your field of education:</h2></div>
                            <?php
                            self::displayLeadInfo("Change the visualization of the fields of education with the
                                                    below buttons an then select the field");
                            ?>
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
                            <div id="select_badge"></div>
                        </div>
                    </section>

                    <h3>Language</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Check the language:</h2></div>
                            <div id="result_languages_description"></div>
                            <div id="result_preview_description"></div>
                        </div>
                    </section>

                    <h3>Class</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Class:</h2></div>
                            <?php
                            if (User::check_the_rules($current_user->roles, "academy", "teacher")) {
                                $class_zero = get_class_teacher($current_user->user_login);
                                echo '<input name="class_teacher" type="hidden" value="' . $class_zero->ID . '"/>';
                            }

                            if (User::check_the_rules($current_user->roles, "teacher", "academy", "administrator", "editor")) {
                                echo '<div id="select_class"></div>';
                            }
                            ?>
                        </div>
                    </section>

                    <h3>Email</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Receiver's mail addresses:</h2></div>
                            <?php self::displayLeadInfo("Write the emails of the receiver badge, to send multiple email, write each address per line"); ?>
                            <textarea name="mail" id="mail" class="mail" rows="10" cols="50"></textarea>
                        </div>
                    </section>

                    <h3>Information</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Addition information:</h2></div>
                            <?php self::displayLeadInfo("Write some information that will be showed in the description of badge"); ?>
                            <textarea placeholder="More than 10 letters ..."  name="comment" id="comment" rows="10" cols="80"></textarea><br/><br/>
                        </div>
                    </section>
                </div>
            </form>
        </div>
        <?php
   }

    private static function displayLeadInfo($message) {
        echo '<div class="lead">'.$message.'</div> <hr class="hr-sb">';
    }

    private function displayFieldsButtons() {
        $fieldsClass = new Fields();

        if($fieldsClass->haveChildren()){
            $parents = $fieldsClass->getAllFields();
            $actual_parent = key($parents);

            echo '<div class="btns-parent-field">';

            foreach ($parents as $parent) {
                if ($parent[2] == $actual_parent) {
                    echo '<a class="btn btn-default btn-xs display_parent_categories active" id="' . $parent[2] . '">Display ' . $parent[1] . '</a>';
                } else {
                    echo '<a class="btn btn-default btn-xs display_parent_categories" id="' . $parent[2] . '">Display ' . $parent[1] . '</a>';
                }
            }
            echo '<a class="btn btn-default btn-xs display_parent_categories" id="all_field">Display all Fields</a>';
        }
    }
}
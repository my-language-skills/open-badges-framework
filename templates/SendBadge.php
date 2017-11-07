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


use Inc\Base\BaseController;
use inc\Base\User;
use inc\Utils\DisplayFunction;
use inc\Utils\Fields;

final class SendBadge extends BaseController {

    public function __construct() {
        self::initialization();
    }


    public function initialization() {
        add_shortcode('send_badge', array(SendBadge::class, 'main'));
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
    public static function main() { ?>
        <div class="wrap">
            <br><br>
            <div class="title-page-admin">
                <h1>
                    <i>
                        <span class="dashicons dashicons-awards"></span>
                        <?php echo get_admin_page_title(); ?>
                    </i>
                </h1>
            </div>
            <br>
            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'Self')">Self</button>
                <?php
                if (User::check_the_rules("academy", "teacher", "administrator", "editor")) {
                    ?>
                    <button class="tablinks" onclick="openCity(event, 'Issue')">Issue</button>
                    <?php
                    if (User::check_the_rules("academy", "administrator", "editor")) {
                        ?>
                        <button class="tablinks" onclick="openCity(event, 'Multiple')">Multiple issue</button>
                    <?php } ?>
                <?php } ?>
            </div>

            <div id="Self" class="tabcontent">
                <?php self::getTabSelf(); ?>
            </div>
            <?php
            if (User::check_the_rules("academy", "teacher", "administrator", "editor")) {
                ?>
                <div id="Issue" class="tabcontent">
                    <?php self::getTabIssue(); ?>
                </div>
                <?php
                if (User::check_the_rules("academy", "administrator", "editor")) {
                    ?>
                    <div id="Multiple" class="tabcontent">
                        <?php self::getTabMultiple(); ?>
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
        self::displayEmailInput();
        ?>

        <div class="tab-content">
            <form id="badge_form_a" action="" method="post">
                <div>
                    <h3>Field of Education</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select your field of education:</h2></div>
                            <?php
                            self::displayLeadInfo("Change the visualization of the fields of education with the
                                                    below buttons an then select the field");
                            self::displayFieldsButtons(); ?>
                            <div id="field_edu_a"><?php DisplayFunction::field(""); ?></div>
                        </div>
                    </section>
                    <h3>Level</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select the level:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below levels"); ?>
                            <div id="languages_form_a"></div>
                        </div>
                    </section>

                    <h3>Badge</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select the kind of badge:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below badges"); ?>
                            <div id="select_badge"></div>
                        </div>
                    </section>

                    <h3>Language</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Check the language:</h2></div>
                            <?php self::displayLeadInfo("Select the language of the badge, if you cannot select it, the below text it will be used."); ?>
                            <div id="result_languages_description"></div>
                            <div id="result_preview_description"></div>
                        </div>
                    </section>

                    <h3>Information</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Addition information:</h2></div>
                            <?php self::displayLeadInfo("Write some information that will be showed in the description of badge"); ?>
                            <textarea placeholder="More than 10 letters ..." name="comment" id="comment" rows="10"
                                      cols="80"></textarea><br/><br/>
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
        self::displayEmailInput();
        ?>
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
                            self::displayFieldsButtons(); ?>
                            <div id="field_edu_b"><?php DisplayFunction::field(""); ?></div>
                        </div>
                    </section>
                    <h3>Level</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select the level:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below levels"); ?>
                            <div id="languages_form_b"></div>
                        </div>
                    </section>

                    <h3>Badge</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select the kind of badge:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below badges"); ?>
                            <div id="select_badge"></div>
                        </div>
                    </section>

                    <h3>Language</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Check the language:</h2></div>
                            <?php self::displayLeadInfo("Select the language of the badge, if you cannot select it, the below text it will be used."); ?>
                            <div id="result_languages_description"></div>
                            <div id="result_preview_description"></div>
                        </div>
                    </section>

                    <h3>Class</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Class:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below classes (by default is selected your default class)"); ?>
                            <div id="select_class"></div>
                        </div>
                    </section>

                    <h3>Email</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Receiver's mail addresses:</h2></div>
                            <?php self::displayLeadInfo("Write the emails of the receiver badge, to send multiple email, write each address per line"); ?>
                            <input name="mail" id="mail" class="mail" style="width: 300px; text-align: center;">
                        </div>
                    </section>

                    <h3>Information</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Addition information:</h2></div>
                            <?php self::displayLeadInfo("Write some information that will be showed in the description of badge"); ?>
                            <textarea placeholder="More than 10 letters ..." name="comment" id="comment" rows="10"
                                      cols="80"></textarea><br/><br/>
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
        self::displayEmailInput();
        ?>
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
                            self::displayFieldsButtons(); ?>
                            <div id="field_edu_c"><?php DisplayFunction::field(""); ?></div>
                        </div>
                    </section>
                    <h3>Level</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select the level:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below levels"); ?>
                            <div id="languages_form_c"></div>
                        </div>
                    </section>

                    <h3>Badge</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select the kind of badge:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below badges"); ?>
                            <div id="select_badge"></div>
                        </div>
                    </section>

                    <h3>Language</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Check the language:</h2></div>
                            <?php self::displayLeadInfo("Select the language of the badge, if you cannot select it, the below text it will be used."); ?>
                            <div id="result_languages_description"></div>
                            <div id="result_preview_description"></div>
                        </div>
                    </section>

                    <h3>Class</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Class:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below classes (by default is selected your default class)"); ?>
                            <div id="select_class"></div>
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
                            <textarea placeholder="More than 10 letters ..." name="comment" id="comment" rows="10"
                                      cols="80"></textarea><br/><br/>
                        </div>
                    </section>
                </div>
            </form>
        </div>
        <?php
    }

    private static function displayLeadInfo($message) {
        echo '<div class="lead">' . $message . '</div> <hr class="hr-sb">';
    }

    private function displayFieldsButtons() {
        $fields = new Fields();
        $i = 0;

        if ($fields->haveChildren()) {
            echo '<div class="btns-parent-field">';
            foreach ($fields->main as $parent) {
                if (!$i) {
                    $i = 1;
                    echo '<a class="btn btn-default btn-xs display_parent_categories active" id="' . $parent->slug . '">Display ' . $parent->name . '</a>';
                } else {
                    echo '<a class="btn btn-default btn-xs display_parent_categories" id="' . $parent->slug . '">Display ' . $parent->name . '</a>';
                }
            }
            echo '<a class="btn btn-default btn-xs display_parent_categories" id="all_field">Display all Fields</a>';
        }
    }

    private function displayEmailInput() {
        $user = User::getCurrentUser();
        echo "<input type='hidden' name='sender' value='$user->user_email'/>";

    }
}
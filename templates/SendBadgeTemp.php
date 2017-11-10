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

final class SendBadgeTemp extends BaseController {

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function __construct() {
        self::initialization();
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function initialization() {
        add_shortcode('send_badge', array(SendBadgeTemp::class, 'main'));
        // Adding the shortcode to send the badge to yourself
        add_shortcode('send-self', array(SendBadgeTemp::class, 'getRightForm', 'a'));
        // Adding the shortcode to send a badge to a single person
        add_shortcode('send-single', array(SendBadgeTemp::class, 'getRightForm', 'b'));
        // Adding the shortcode to send the badge to several persons
        add_shortcode('send-multiple', array(SendBadgeTemp::class, 'getRightForm', 'c'));
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
                <button class="tablinks" onclick="changeTab(event, 'tab-a')">Self</button>
                <?php
                if (User::check_the_rules("academy", "teacher", "administrator", "editor")) {
                    ?>
                    <button class="tablinks" onclick="changeTab(event, 'tab-b')">Issue</button>
                    <?php
                    if (User::check_the_rules("academy", "administrator", "editor")) {
                        ?>
                        <button class="tablinks" onclick="changeTab(event, 'tab-c')">Multiple issue</button>
                    <?php } ?>
                <?php } ?>
            </div>

            <div id="tab-a" class="tabcontent">
                <?php self::getRightForm('a'); ?>
            </div>
            <?php
            if (User::check_the_rules("academy", "teacher", "administrator", "editor")) { ?>
                <div id="tab-b" class="tabcontent">
                    <?php self::getRightForm('b'); ?>
                </div>
                <?php
                if (User::check_the_rules("academy", "administrator", "editor")) { ?>
                    <div id="tab-c" class="tabcontent">
                        <?php self::getRightForm('c'); ?>
                    </div>
                    <?php
                }
            } ?>
        </div>
        <?php
    }

    public function getRightForm($form) {
        self::displayEmailInput();
        ?>
        <div class="tab-content">
            <form id="form_<?php echo $form; ?>" action="" method="post">
                <div>
                    <h3>Field of Education</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select your field of education:</h2></div>
                            <?php
                            self::displayLeadInfo("Change the visualization of the fields of education with the
                                                    below buttons an then select the field");
                            self::displayFieldsButtons(); ?>
                            <div id="field_<?php echo $form; ?>"><?php DisplayFunction::field(""); ?></div>
                        </div>
                    </section>
                    <h3>Level</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select the level:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below levels"); ?>
                            <div id="level_<?php echo $form; ?>"></div>
                        </div>
                    </section>

                    <h3>Badge</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Select the kind of badge:</h2></div>
                            <?php self::displayLeadInfo("Select one of the below badges"); ?>
                            <div id="badge_<?php echo $form; ?>"></div>
                        </div>
                    </section>

                    <h3>Description</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Check the description:</h2></div>
                            <?php self::displayLeadInfo("This is the text of the badge."); ?>
                            <div id="desc_<?php echo $form; ?>"></div>
                        </div>
                    </section>

                    <?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    if (($form == 'b' || $form == 'c') && is_plugin_active(
                            "WP-Job-Manager-master/wp-job-manager.php")) {
                        ?>
                        <h3>Class</h3>
                        <section>
                            <div class="section-container">
                                <div class="title-form"><h2>Class:</h2></div>
                                <?php self::displayLeadInfo("Select one of yours classes."); ?>
                                <div id="class_<?php echo $form; ?>"></div>
                            </div>
                        </section>
                    <?php } ?>
                    <?php if ($form == 'b' || $form == 'c') { ?>
                        <h3>Email</h3>
                        <section>
                            <div class="section-container">
                                <div class="title-form"><h2>Receiver's mail addresses:</h2></div>
                                <?php self::displayLeadInfo("Write the emails of the receiver badge, to send multiple email, write each address per line"); ?>
                                <?php
                                if ($form == 'b') {
                                    echo "<input id='mail_$form' name='mail' class='mail' style='width: 300px; text-align: center;'>";
                                } elseif ($form == 'c') {
                                    echo "<textarea id='mail_$form' name='mail' class='mail' rows='10' cols='50' style='width: 300px; text-align: center;'></textarea>";
                                }
                                ?>
                            </div>
                        </section>
                    <?php } ?>
                    <h3>Information</h3>
                    <section>
                        <div class="section-container">
                            <div class="title-form"><h2>Addition information:</h2></div>
                            <?php self::displayLeadInfo("Write some information that will be showed in the description of badge"); ?>
                            <textarea id="comment_<?php echo $form; ?>" placeholder="More than 10 letters ..."
                                      name="comment" rows="10" cols="80"></textarea>
                        </div>
                    </section>
                </div>
            </form>
        </div>

        <?php
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    private static function displayLeadInfo($message) {
        echo '<div class="lead">' . $message . '</div> <hr class="hr-sb">';
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    private function displayFieldsButtons() {
        $fields = new Fields();
        $i = 0;

        if ($fields->haveChildren()) {
            echo '<div class="btns-parent-field">';
            foreach ($fields->main as $parent) {
                if (!$i) {
                    $i = 1;
                    echo '<a class="btn btn-default btn-xs btn-change-children active" id="' . $parent->slug . '">Display ' . $parent->name . '</a>';
                } else {
                    echo '<a class="btn btn-default btn-xs btn-change-children" id="' . $parent->slug . '">Display ' . $parent->name . '</a>';
                }
            }
            echo '<a class="btn btn-default btn-xs btn-change-children" id="all_field">Display all Fields</a>';
        }
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    private function displayEmailInput() {
        $user = User::getCurrentUser();
        echo "<input type='hidden' name='sender' value='$user->user_email'/>";

    }
}
<?php

namespace templates;

use Inc\Base\BaseController;
use inc\Base\User;
use inc\Utils\DisplayFunction;
use inc\Utils\Fields;

/**
 * Template for the Send Badge page.
 *
 * This class permit to show the steps to send the
 * badge to the student and create also a short-code
 * ([send-badge form="a/b/c"])
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
final class SendBadgeTemp extends BaseController {

    /**
     * Add the short-code [send-badge form="a/b/c"] to permit to show
     * the right section of the sending badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function __construct() {
        add_shortcode('send-badge', array(SendBadgeTemp::class, 'getShortCodeForm'));
    }

    /**
     * The first thing that will show when you load this page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public static function main() {
        ?>
        <div class="wrap">
            <h1>
                <?php echo get_admin_page_title(); ?>
            </h1>
            <br>

            <ul class="nav nav-tabs">
                <?php
                echo '<li class="active"><a href="#tab-1">Self</a></li>';

                if (User::checkTheRules("academy", "teacher", "administrator", "editor")) {

                    echo '<li class=""><a href="#tab-2">Issue</a></li>';
                }

                if (User::checkTheRules("academy", "administrator", "editor")) {
                    echo '<li class=""><a href="#tab-3">Multiple issue</a></li>';
                }
                ?>
            </ul>

            <div class="tab-content-page">
                <div id="tab-1" class="tab-pane active">
                    <?php self::getRightForm("a"); ?>
                </div>
                <div id="tab-2" class="tab-pane">
                    <?php self::getRightForm("b"); ?>
                </div>
                <div id="tab-3" class="tab-pane">
                    <?php self::getRightForm("c"); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Show the right form.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @param string $form name of the form, should be:
     *                     a -> Self
     *                     b -> Issue
     *                     c -> Multiple issue
     */
    public static function getRightForm($form) {
        echo '<div class="tab-content center-text">';

        if ($form == "a") {
            echo "<p class='text-large'>That permit you to send badges to yourself.</p>";
        } else if ($form == "b") {
            echo "<p class='text-large'>That permit you to send the badge only to another student.</p>";
        } else if ($form == "c") {
            echo "<p class='text-large'>That permit you to send the badges to more students.</p>";
        }
        ?>

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
                        <div id="desc_<?php echo $form; ?>" class="desc-badge"></div>
                    </div>
                </section>

                <?php include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                if (($form == 'b' || $form == 'c') && is_plugin_active(
                        "WP-Job-Manager-master/wp-job-manager.php")) {
                    ?>
                    <h3>Class</h3>
                    <section>
                        <div id="class-section" class="section-container">
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
                            <?php

                            if ($form == 'b') {
                                self::displayLeadInfo("Write the emails of the receiver badge");
                                echo "<input id='mail_$form' name='mail' class='mail' style='width: 300px; text-align: center;'>";
                            } elseif ($form == 'c') {
                                self::displayLeadInfo("Write the emails of the receiver badge (to send multiple email, write each address separeted by \",\")");
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
                        <?php self::displayLeadInfo("Write some information that will be showed in the description of badge *"); ?>
                        <textarea id="comment_<?php echo $form; ?>" placeholder="More than 10 letters ..."
                                  name="comment" rows="5" cols="80"></textarea>
                        <br><br>
                        <?php self::displayLeadInfo("Url of the work or of the document that the recipient did to earn the badge"); ?>
                        <input id='evidence_<?php echo $form; ?>' name='mail' class='mail'
                               placeholder="www.example.com/work" style='width: 400px; text-align: center;'>
                    </div>
                </section>
            </div>
        </form>
        </div>

        <?php
    }

    /**
     * This function is called from add_shortcode function
     * in the constructor of this class and permit to switch
     * to the different form in base of the @param form that
     * we pass in the shortcode call.
     * ex: [send-badge form="b"]
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public static function getShortCodeForm($atts) {
        $a = shortcode_atts(array(
            'form' => 'a',
        ), $atts);

        return self::getRightForm($a['form']);

    }

    /**
     * Easy way to show a massage with the lead class style.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    private static function displayLeadInfo($message) {
        echo '<div class="info-field"> <div class="lead">' . $message . '</div> <hr class="hr-sb"> </div>';
    }

    /**
     * Code that permit to display the list of buttons
     * of the parents fields of education.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    private static function displayFieldsButtons() {
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
}
<?php

namespace templates;

use Inc\Base\BaseController;
use Inc\Base\User;
use Inc\Utils\DisplayFunction;
use Inc\Utils\Fields;

/**
 * Template for the Send Badge page.
 * This class permit to show the steps to send the
 * badge to the student and create also a short-code.
 *
 * [send-badge form="a"] -> self
 * [send-badge form="b"] -> issue
 * [send-badge form="c"] -> multiple
 * [send-badge form="all"] -> all together
 *
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class SendBadgeTemp extends BaseController {
    const FORM_SELF = "a";
    const FORM_ISSUE = "b";
    const FORM_MULTIPLE = "c";

    /**
     * Add the short-code [send-badge form="a/b/c"] to permit to show
     * the right section of the sending badge.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function __construct() {
        add_shortcode('send-badge', array(SendBadgeTemp::class, 'getShortCodeForm'));
    }

    /**
     * The first thing that will show when you load this page.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public static function main() {
        ?>
        <div class="wrap">
            <h1>
                <?php echo get_admin_page_title(); ?>
            </h1>
            <br>

            <?php self::getRightForm("all"); ?>
        </div>
        <?php
    }

    /**
     * Show the right form.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param string $form name of the form, should be:
     *                     a -> Self
     *                     b -> Single
     *                     c -> Multiple
     */
    public static function getRightForm($form) {
        if ($form === "all") {
            // In the case we want to show all the 3 form all together
            // like in the admin Send Badge page.
            ?>
            <ul class="nav nav-tabs">
                <?php
                echo '<li class="active"><a href="#tab-1">Self</a></li>';

                if (current_user_can(User::CAP_SINGLE)) {

                    echo '<li class=""><a href="#tab-2">Single</a></li>';
                }

                if (current_user_can(User::CAP_MULTIPLE)) {
                    echo '<li class=""><a href="#tab-3">Multiple</a></li>';
                }
                ?>
            </ul>

            <div class="tab-content-page">
                <div id="tab-1" class="tab-pane active">
                    <?php self::getForm("a"); ?>
                </div>
                <div id="tab-2" class="tab-pane">
                    <?php self::getForm("b"); ?>
                </div>
                <div id="tab-3" class="tab-pane">
                    <?php self::getForm("c"); ?>
                </div>
            </div>

            <?php
        } else {

            if($form == self::FORM_SELF) {
                if(current_user_can(User::CAP_SELF)){
                    self::getForm($form);
                } else {
                    echo "You don't have the permission to access to this functionality.";
                }

            } else if ($form == self::FORM_ISSUE) {
                if(current_user_can(User::CAP_SINGLE)){
                    self::getForm($form);
                } else {
                    echo "You don't have the permission to access to this functionality.";
                }
            } else if ($form == self::FORM_MULTIPLE) {
                if(current_user_can(User::CAP_MULTIPLE)){
                    self::getForm($form);
                } else {
                    echo "You don't have the permission to access to this functionality.";
                }
            }
        }?>

        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="responseSent"></div>
            </div>

        </div>

        <?php
    }


    /**
     * Get the specific form.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param string $form name of the form, should be:
     *                     a -> Self
     *                     b -> Single
     *                     c -> Multiple
     */
    public static function getForm($form) {
        // When we want to show a specific tab.
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
                        <div id="field_<?php echo $form; ?>"><?php DisplayFunction::field(""); ?>
                            <p>
                                <small>Some browser can delay the opening of the field of education.</small>
                            </p>
                        </div>
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
                if (($form == 'b' || $form == 'c') && class_exists('WP_Job_Manager')) {
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
     * to the different form in base of the form that
     * we pass in the short-code call.
     * ex: [send-badge form="b"]
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param array $atts list of param passed in to the short code
     *
     * @return string the right form
     */
    public static function getShortCodeForm($atts) {
        $a = shortcode_atts(array(
            'form' => 'all',
        ), $atts);

        return self::getRightForm($a['form']);

    }

    /**
     * Easy way to show a massage with the lead class style.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param string $message message to print
     */
    private static function displayLeadInfo($message) {
        echo '<div class="info-field"> <div class="lead">' . $message . '</div> <hr class="hr-sb"> </div>';
    }

    /**
     * Code that permit to display the list of buttons
     * of the parents fields of education.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
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
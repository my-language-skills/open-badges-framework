<?php

namespace templates;

use Inc\Base\BaseController;
use Inc\Utils\WPUser;
use Inc\Utils\DisplayFunction;
use Inc\Utils\WPField;

/**
 * Template for the Send Badge page.
 * This class permits to show the steps to send the
 * badge to the student and create also a short-code.
 *
 * [send-badge form="a"] -> self
 * [send-badge form="b"] -> issue
 * [send-badge form="c"] -> multiple
 * [send-badge form="all"] -> all together
 *
 * [send-badge ... sec-form="..."] -> add a second form (a/b/c) that will be show with the first
 *
 * All the content to show in the front-end is wrapped in the __() function
 * for internationalization purposes
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class SendBadgeTemp extends BaseController {
    const FORM_SELF = "a";
    const FORM_ISSUE = "b";
    const FORM_MULTIPLE = "c";

    /**
     * Add the short-code [send-badge form="a/b/c"] to permits to show
     * the right section of the sending badge.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     */
    public function __construct() {
        add_shortcode('send-badge', array(SendBadgeTemp::class, 'getShortCodeForm'));
    }

    /**
     * The first thing that will show when you load this page.
     *
     * @author      @AleRiccardi
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
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param string $form name of the form, should be:
     *                     a -> Self
     *                     b -> Single
     *                     c -> Multiple
     * @param string $secForm add a second form (a/b/c) that will be show with the first
     */
    public static function getRightForm($form, $secForm = '') {
        if ($form === "all") {
            // In the case we want to show all the 3 form all together
            // like in the admin Send Badge page.
            ?>
            <ul class="nav nav-tabs">
                
                 <li class="active"><a href="#tab-1"><?php _e('Self','open-badges-framework');?></a></li>
				 <?php

                if (current_user_can(WPUser::CAP_SINGLE)) {
					?>
                    <li class=""><a href="#tab-2"><?php _e('Single','open-badges-framework');?></a></li>
					<?php
                }

                if (current_user_can(WPUser::CAP_MULTIPLE)) {
					?>
                    <li class=""><a href="#tab-3"><?php _e('Multiple','open-badges-framework');?></a></li>
					<?php
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
        } else if (($form == "a" || $form == "b" || $form == "c") &&
            ($secForm == "a" || $secForm == "b" || $secForm == "c")) {
            ?>
            <ul class="nav nav-tabs">
                <?php
                $active = true;
                if ($form == "a" || $secForm == "a") {
                    echo "<li class='";
                    if ($active) {
                        echo "active";
                        $active = false;
                    }
                    echo "'><a href='#tab-1'>Self</a></li>";
                }
                if (($form == "b" || $secForm == "b")) {
                    echo "<li class='";
                    if ($active) {
                        echo "active";
                        $active = false;
                    }
                    echo "'><a href='#tab-2'>Single</a></li>";
                }

                if ($form == "c" || $secForm == "c") {
                    echo "<li class='";
                    if ($active) {
                        echo "active";
                        $active = false;
                    }
                    echo "'><a href='#tab-3'>Multiple</a></li>";
                }
                ?>
            </ul>

            <div class="tab-content-page">
                <?php
                $active = true;
                if ($form == "a" || $secForm == "a") {
                    ?>
                    <div id="tab-1" class="tab-pane <?php if ($active) { echo "active"; $active = false; } ?>">
                        <?php self::getForm("a"); ?>
                    </div>
                    <?php
                }
                if ($form == "b" || $secForm == "b") {
                    ?>
                    <div id="tab-2" class="tab-pane <?php if ($active) { echo "active"; $active = false; } ?>">
                        <?php self::getForm("b"); ?>
                    </div>
                    <?php
                }
                if ($form == "c" || $secForm == "c") {
                    ?>
                    <div id="tab-3" class="tab-pane <?php if ($active) { echo "active"; $active = false; } ?>">
                        <?php self::getForm("c"); ?>
                    </div>
                <?php }
                ?>
            </div>

            <?php
        } else {
            self::getForm($form);
        } ?>

        <!-- The Modal -->
        <div id="modalSendBadge" class="modal">

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
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param string $form name of the form, should be:
     *                     a -> Self
     *                     b -> Single
     *                     c -> Multiple
     *
     * @return false if error
     */
    public static function getForm($form) {

        if ($form == self::FORM_SELF) {
            if (!current_user_can(WPUser::CAP_SELF)) {
                echo "You don't have the permission to access to this functionality.";
                return false;
            }

        } else if ($form == self::FORM_ISSUE) {
            if (!current_user_can(WPUser::CAP_SINGLE)) {
                echo "You don't have the permission to access to this functionality.";
                return false;
            }
        } else if ($form == self::FORM_MULTIPLE) {
            if (!current_user_can(WPUser::CAP_MULTIPLE)) {
                echo "You don't have the permission to access to this functionality.";
                return false;
            }
        }

        // When we want to show a specific tab.
        echo '<div class="tab-content center-text">';

        if ($form == "a") {
           
			?>
			<p class='text-large'><?php _e('This permits you to send badges to yourself.','open-badges-framework');?></p>
			<?php
			
        } else if ($form == "b") {
            
			?>
			<p class='text-large'><?php _e('This permits you to send the badge only to another student.','open-badges-framework');?></p>
			<?php
			
        } else if ($form == "c") {
           
			?>
			<p class='text-large'><?php _e('This permits you to send the badges to more students.','open-badges-framework');?></p>
			<?php			
        }
        ?>

        <form id="form_<?php echo $form; ?>" class="form-send-badge" action="" method="post">
            <div>
                <h3><?php _e('Field of Education','open-badges-framework');?></h3>
                <section>
                    <div class="title-form"><h2> <?php _e('Select your field of education:','open-badges-framework');?></h2></div>
                    <div class="fit-height-section flex-center-cont">
                        <div class="flex-center-item sb-cont">
                            <?php
                            self::displayLeadInfo(_e('Change the visualization of the fields of education with the
                                                    buttons below and then select the field','open-badges-framework'));
                            self::displayFieldsButtons(); ?>
                            <div id="field_<?php echo $form; ?>">
                                <?php DisplayFunction::field(""); ?>
                            </div>
                        </div>
                    </div>
                </section>
                <h3><?php _e('Level','open-badges-framework');?> </h3>
                <section>
                    <div class="title-form"><h2> <?php _e('Select the level:','open-badges-framework');?></h2></div>
                    <div class="fit-height-section flex-center-cont">
                        <div class="flex-center-item sb-cont">
                            <?php self::displayLeadInfo(_e('Select one of the levels below','open-badges-framework')); ?>
                            <div id="level_<?php echo $form; ?>"></div>
                        </div>
                    </div>
                </section>

                <h3><?php _e('Badge','open-badges-framework');?></h3>
                <section>
                    <div class="title-form"><h2><?php_e('Select the kind of badge:','open-badges-framework');?></h2></div>
                    <div class="fit-height-section flex-center-cont">
                        <div class="flex-center-item sb-cont">
                            <?php self::displayLeadInfo(_e('Select one of the badges below','open-badges-framework')); ?>
                            <div id="badge_<?php echo $form; ?>"></div>
                        </div>
                    </div>
                </section>

                <h3> <?php _e('Description','open-badges-framework');?></h3>
                <section>
                    <div class="title-form"><h2><?php _e('Check the description:','open-badges-framework');?></h2></div>
                    <div class="fit-height-section flex-center-cont">
                        <div class="flex-center-item sb-cont">
                            <?php self::displayLeadInfo(_e('This is the description of the badge.','open-badges-framework')); ?>
                            <div id="desc_<?php echo $form; ?>" class="desc-badge"></div>
                        </div>
                    </div>
                </section>

                <?php include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                if (($form == 'b' || $form == 'c') && class_exists('WP_Job_Manager')) {
                    ?>
                    <h3><?php _e('Class','open-badges-framework');?></h3>
                    <section>
                        <div class="title-form"><h2> <?php _e('Class:','open-badges-framework');?></h2></div>
                        <div class="fit-height-section flex-center-cont">
                            <div class="flex-center-item sb-cont">
                                <?php self::displayLeadInfo(_e('Select one of yours classes.','open-badges-framework')); ?>
                                <div id="class_<?php echo $form; ?>"></div>
                            </div>
                        </div>
                    </section>
                <?php } ?>
                <?php if ($form == 'b' || $form == 'c') { ?>
                    <h3><?php _e('Email','open-badges-framework');?></h3>
                    <section>
                        <div class="title-form"><h2><?php _e("Receiver's mail addresses:",'open-badges-framework');?></h2></div>
                        <div class="fit-height-section flex-center-cont">
                            <div class="flex-center-item sb-cont">
                                <?php

                                if ($form == 'b') {
                                    self::displayLeadInfo(_e("Write the email of the receiver badge",'open-badges-framework'));
                                    echo "<input id='mail_$form' name='mail' class='mail' style='width: 300px; text-align: center;'>";
                                } elseif ($form == 'c') {
                                    self::displayLeadInfo(_e('Write the emails of the receivers badge (to send multiple email, write each address separeted by \",\")'),'open-badges-framework');
                                    echo "<textarea id='mail_$form' name='mail' class='mail' rows='10' cols='50' style='width: 300px; text-align: center;'></textarea>";
                                }
                                ?>
                            </div>
                        </div>
                    </section>
                <?php } ?>
                <h3> <?php _e('Information','open-badges-framework');?></h3>
                <section>
                    <div class="title-form"><h2> <?php _e('Additional information:','open-badges-framework');?></h2></div>
                    <div class="fit-height-section flex-center-cont">
                        <div class="flex-center-item sb-cont">
                            <?php self::displayLeadInfo(_e('Write some information that will be showed in the description of badge *','open-badges-framework')); ?>
                            <textarea id="comment_<?php echo $form; ?>" placeholder="More than 10 letters ..."
                                      name="comment" rows="5" cols="80"></textarea>
                            <br><br>
                            <?php self::displayLeadInfo(_e('Url of the work or of the document that the recipient did to earn the badge','open-badges-framework')); ?>
                            <input id='evidence_<?php echo $form; ?>' name='mail' class='mail'
                                   placeholder="http://www.example.com/work" style='width: 400px; text-align: center;'>
                        </div>
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
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param array $atts list of param passed in to the short code
     *
     * @return string the right form
     */
    public static function getShortCodeForm($atts) {
        $a = shortcode_atts(array(
            'form' => 'all',
            'sec-form' => ''
        ), $atts);

        return self::getRightForm($a['form'], $a['sec-form']);

    }

    /**
     * Easy way to show a massage with the lead class style.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param string $message message to print
     */
    private static function displayLeadInfo($message) {
        echo '<div class="info-field"> <div class="lead">' . $message . '</div> <hr class="hr-sb"> </div>';
    }

    /**
     * Code that permits to display the list of buttons
     * of the parents fields of education.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return void
     */
    private static function displayFieldsButtons() {
        $fields = new WPField();
        $i = 0;

        if ($fields->haveChildren()) {
            echo '<div class="btns-parent-field">';
            foreach ($fields->main as $parent) {
                if (!$i) {
                    $i = 1;
                    echo '<a class="btn-change-children active" id="' . $parent->slug . '">Display ' . $parent->name . '</a>';
                } else {
                    echo '<a class="btn-change-children" id="' . $parent->slug . '">Display ' . $parent->name . '</a>';
                }
            }
            echo '<a class="btn-change-children" id="all_field">Display all Fields</a>';
        }
    }
}

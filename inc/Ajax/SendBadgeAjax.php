<?php

namespace Inc\Ajax;

use Inc\Utils\WPUser;
use Inc\Utils\DisplayFunction;
use Inc\Utils\WPClass;
use Inc\Utils\WPLevel;
use Inc\Utils\WPBadge;
use Inc\Utils\SendBadge;
use Inc\Utils\Classes;
use Inc\Base\BaseController;
use templates\SettingsTemp;

/**
 * This class is a wrap for all the public function that are
 * called as a "ajax call" and concern the send badge process.
 * This function is initialized from the InitAjax Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class SendBadgeAjax extends BaseController {

    /**
     * Show the fields of education based on the chosen parent
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxShowFields() {
        $display = new DisplayFunction();
        $display->field($_POST['slug']);
        wp_die();
    }

    /**
     * Show the levels in base a specific field of education.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxShowLevels() {
        $form = $_POST['form'];
        $fieldId = $_POST['fieldId'];
        $level = new WPLevel();
        $levels = $level->getAllLevels($fieldId);

        if ($levels) {
            // Display the level ...
            foreach ($levels as $level) {
                if ($level) {
                    echo '<div class="rdi-tab">';
                    echo "<input id='level-$level->name-form-$form' value='$level->term_id' class='radio-input level' name='level_$form' type='radio'>
                  <label for='level-$level->name-form-$form' class='radio-label'>$level->name</label>";
                    echo '</div>';
                }
            }
        } else {
            echo "There aren't badges with this field of education!";
        }

        wp_die();
    }

    /**
     * Show the badges in base of the field of education
     * that we selected in the first step and in base of
     * the level of the second step.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxShowBadges() {
        $badges = new WPBadge();
        $form = $_POST['form'];
        $field = $_POST['fieldId'];
        $level = $_POST['level'];

        $badges = $badges->getFiltered($field, $level);

        if ($badges) {
            foreach ($badges as $badge) { ?>
                <!-- HTML -->
                <div class="cont-badge-sb">
                    <label for="<?php echo "badge-$badge->ID-form-$form" ?>" class="badge-cont">
                        <input id="<?php echo "badge-$badge->ID-form-$form" ?>" type="radio"
                               name="badge_<?php echo $form; ?>"
                               class="input-badge" value="<?php echo $badge->ID; ?>"/>
                        <img class="img-badge" src=" <?php echo WPBadge::getUrlImage($badge->ID); ?>"/>
                    </label>
                    <br>
                    <b><?php echo $badge->post_title; ?></b>
                    </label>
                </div>
                <?php
            }
        } else {
            echo "There aren't badges with this field of education!";
        }

        wp_die();
    }

    /**
     * Show the description of a specific badge.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxShowDescription() {
        $badges = new WPBadge();
        $form = $_POST['form'];
        $badgeId = $_POST['badgeId'];
        $badge = $badges->get($badgeId);

        echo "<div name='desc_$form'>$badge->post_content</div>";
        wp_die();
    }

    /**
     * Show the class of the user and also permit to became premium or
     * to add a class (depending on the role).
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxShowClasses() {
        $form = $_POST['form'];
        $fieldId = $_POST['fieldId'];
        $classes = new WPClass();
        $ownClasses = $classes->getOwnClass($fieldId);

        if (count($ownClasses)) {
            echo '<h3 class="title-classes">Own class</h3>';
            foreach ($ownClasses as $class) {
                echo "<input id='class-$class->ID-form-$form' value='$class->ID' class='radio-input' name='class_$form' type='radio'>
              <label for='class-$class->ID-form-$form' class='radio-label'>$class->post_title</label>";
            }
        }

        echo "<br><br>";
        if(current_user_can(WPUser::CAP_JOB_LISTING)) {
            $addClassPage = get_post(
                SettingsTemp::getOption(SettingsTemp::FI_ADD_CLASS)
            );
            if($addClassPage) echo "<a href='". get_page_link($addClassPage->ID)."'>Add Class</a>";
        } else {
            $becamePremiumPage = get_post(
                SettingsTemp::getOption(SettingsTemp::FI_BECAME_PREMIUM)
            );
            if($becamePremiumPage) echo "<a href='". get_page_link($becamePremiumPage->ID)."'>Became Premium</a>";
        }

        wp_die();
    }

    /**
     * When called that function means that we're arrive at the
     * end of the steps, here is instanced the SendBadge class
     * with all the information and then called the function sendBadge
     * that make start the process.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxSendBadge() {
        $form = isset($_POST["form"]) ? $_POST['form'] : null;
        $badgeId = isset($_POST["badgeId"]) ? $_POST['badgeId'] : null;
        $fieldId = isset($_POST["fieldId"]) ? $_POST['fieldId'] : null;
        $levelId = isset($_POST["levelId"]) ? $_POST['levelId'] : null;
        $theClassId = isset($_POST["theClassId"]) ? $_POST['theClassId'] : null;
        $receivers = isset($_POST["receivers"]) ? $_POST['receivers'] : null;
        $info = isset($_POST["info"]) ? $_POST['info'] : null;
        $evidence = isset($_POST["evidence"]) ? $_POST['evidence'] : null;

        // For the A form the receiver is the user (Self)
        if ($form === 'a') {
            $receivers = array(
                WPUser::getCurrentUser()->user_email
            );
        }

        $badge = new SendBadge($badgeId, $fieldId, $levelId, $info, $receivers, $theClassId, $evidence);
        echo $badge->send();
        wp_die();
    }

}
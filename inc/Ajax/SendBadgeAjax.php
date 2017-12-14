<?php

namespace inc\Ajax;

use inc\Base\User;
use Inc\Utils\DisplayFunction;
use Inc\Utils\Levels;
use Inc\Utils\Badges;
use Inc\Utils\SendBadge;
use Inc\Utils\Classes;
use Inc\Base\BaseController;

/**
 * This class is a wrap for all the public function that are
 * called as a ajax call, they are all concentrated in
 * the send badge field and this function is initialized
 * from the InitAjax Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class SendBadgeAjax extends BaseController {

    /**
     * Show the fields about the parent that we
     * selected as a button in the first step.
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
     * Show the levels in base a specific field
     * of education.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxShowLevels() {
        $form = $_POST['form'];
        $fieldId = $_POST['fieldId'];
        $level = new Levels();
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
        $badges = new Badges();
        $form = $_POST['form'];
        $field = $_POST['fieldId'];
        $level = $_POST['level'];

        $rightBadges = $badges->getFiltered($field, $level);

        foreach ($rightBadges as $badge) { ?>
            <!-- HTML -->
            <div class="cont-badge-sb">
                <label for="<?php echo "badge-$badge->ID-form-$form" ?>" class="badge-cont">
                    <input id="<?php echo "badge-$badge->ID-form-$form" ?>" type="radio"
                           name="badge_<?php echo $form; ?>"
                           class="input-badge" value="<?php echo $badge->ID; ?>"/>
                    <img class="img-badge" src=" <?php echo Badges::getImage($badge->ID); ?>"/>
                </label>
                <br>
                <b><?php echo $badge->post_title; ?></b>
                </label>
            </div>
            <?php
        }

        wp_die();
    }

    /**
     * Show the description fo the badge.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxShowDescription() {
        $badges = new Badges();
        $form = $_POST['form'];
        $badgeId = $_POST['badgeId'];
        $badge = $badges->get($badgeId);

        echo "<div name='desc_$form'>$badge->post_content</div>";
        wp_die();
    }

    /**
     * Show the class of the user.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxShowClasses() {
        $form = $_POST['form'];
        $field = $_POST['fieldId'];
        $classes = new Classes();
        $ownClasses = $classes->getOwnClass($field);

        echo '<h3 class="title-classes">Own class</h3>';
        foreach ($ownClasses as $class) {
            echo "<input id='class-$class->ID-form-$form' value='$class->ID' class='radio-input' name='class_$form' type='radio'>
              <label for='class-$class->ID-form-$form' class='radio-label'>$class->post_title</label>";
        }
        wp_die();
    }

    /**
     * When called that function means that we're arrive at the
     * end of the steps, here is instanced the SendBadge class
     * with all the information and called the function sendBadge
     * that make start the process.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxSendBadge() {
        $form = $_POST['form'];
        $badgeId = $_POST['badgeId'];
        $fieldId = $_POST['fieldId'];
        $levelId = $_POST['levelId'];
        $theClassId = $_POST['theClassId'];
        $receivers = $_POST['receivers'];
        $info = $_POST['info'];
        $evidence = $_POST['evidence'];

        // For the A form the receiver is the user (Self)
        if ($form === 'a') {
            $receivers = array(
                User::getCurrentUser()->user_email
            );
        }

        $badge = new SendBadge($badgeId, $fieldId, $levelId, $info, $receivers, $theClassId, $evidence);
        echo $badge->sendBadge();
        wp_die();
    }

}
<?php
/**
 * The SendBadgeAjax Class, contain all the
 * function about the Send Badge functionality.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace inc\Ajax;

use Inc\Utils\DisplayFunction;
use Inc\Utils\Levels;
use Inc\Utils\Badges;
use Inc\Utils\SendBadge;
use Inc\Utils\Classes;
use Inc\Base\BaseController;

class SendBadgeAjax extends BaseController {

    function action_save_metabox_students() {
        $post_id = $_POST['post_id'];
        update_post_meta($post_id, '_class_students', $_POST['class_students']);
        echo $_POST['class_students'];
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxShowFields() {
        $display = new DisplayFunction();
        $display->field($_POST['slug']);
        wp_die();
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxShowLevels() {
        $form = $_POST['form'];
        $fieldId = $_POST['field'];
        $level = new Levels();
        $levels = $level->getAllLevels($fieldId);
        // Display the level ...
        foreach ($levels as $level) {
            echo '<div class="rdi-tab">';
            echo "<input id='level-$level->name-form-$form' value='$level->term_id' class='radio-input level' name='level_$form' type='radio'>
                  <label for='level-$level->name-form-$form' class='radio-label'>$level->name</label>";
            echo '</div>';
        }
        wp_die();
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxShowBadges() {
        $badges = new Badges();
        $form = $_POST['form'];
        $field = $_POST['field'];
        $level = $_POST['level'];

        $rightBadges = $badges->getBadgesFiltered($field, $level);

        foreach ($rightBadges as $badge) { ?>
            <!-- HTML -->
            <div class="cont-badge-sb">
        <label for="<?php echo "badge-$badge->ID-form-$form" ?>" class="badge-cont">
            <input id="<?php echo "badge-$badge->ID-form-$form" ?>" type="radio" name="badge_<?php echo $form; ?>"
                   class="input-badge" value="<?php echo $badge->ID; ?>"/>
            <img class="img-badge"
            src="
                <?php
            if (get_the_post_thumbnail_url($badge->ID)) {
                // Badge WITH image
                echo get_the_post_thumbnail_url($badge->ID, 'thumbnail');

            } else {
                // Badge WITHOUT image
                echo $this->plugin_url . 'assets/images/default-badge.png';
                //echo '" width="40px" height="40px" /></label> </br> <b>' . $badge->post_title . '</b>';
            }
            echo '" /> </label> </br> <b>' . $badge->post_title . '</b>';
            ?>
            </label>
            </div>
            <?php
        }

        wp_die();
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxShowDescription() {
        $badges = new Badges();
        $form = $_POST['form'];
        $id = $_POST['ID'];
        $badge = $badges->getBadgeById($id);

        echo "<div name='desc_$form'>$badge->post_content</div>";
        wp_die();
    }


    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxShowClasses() {
        $form = $_POST['form'];
        $field = $_POST['field'];
        $classes = new Classes();
        $ownClasses = $classes->getOwnClass($field);

        echo '<h3 class="title-classes">Own class</h3>';
        foreach ($ownClasses as $class) {
            echo "<input id='class-$class->ID-form-$form' value='$class->ID' class='radio-input' name='class_$form' type='radio'>
              <label for='class-$class->ID-form-$form' class='radio-label'>$class->post_title</label>";
        }
        wp_die();
    }

    function ajaxSendBadge() {
        $badgeId = $_POST['badgeId'];
        $fieldId = $_POST['fieldId'];
        $levelId = $_POST['levelId'];
        $theClassId = $_POST['theClassId'];
        $receivers = $_POST['receivers'];
        $info = $_POST['info'];
        $evidence = $_POST['evidence'];

        $badge = new SendBadge($badgeId, $fieldId, $levelId, $info, $receivers, $theClassId, $evidence);

        echo $badge->sendBadge();
        wp_die();
    }

}
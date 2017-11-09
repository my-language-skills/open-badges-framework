<?php
/**
 * ...
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     BadgeIssuerForWp
 */

namespace inc\Ajax;

use Inc\Utils\DisplayFunction;
use Inc\Utils\Levels;
use Inc\Utils\Badges;
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
        $field = $_POST['field'];
        $level = new Levels();
        $levels = $level->getAllLevels($field);
        // Display the level ...
        foreach ($levels as $level) {
            echo '<div class="rdi-tab">';
            echo "<input id='level-$level-form-$form' value='$level' class='radio-input level' name='level_$form' type='radio'>
                  <label for='level-$level-form-$form' class='radio-label'>$level</label>";
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
        $id = $_POST['ID'];
        $badge = $badges->getBadgeById($id);

        echo $badge->post_content;
        wp_die();
    }


    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxShowClasses() {
        $field = $_POST['field'];
        $classes = new Classes();
        $ownClasses = $classes->getOwnClass($field);

        echo '<h3 class="title-classes">Own class</h3>';
        foreach ($ownClasses as $class) {
            echo "<input id='class_$class->ID' value='$class->ID' class='radio-input' name='class' type='radio'>
              <label for='class_$class->ID' class='radio-label'>$class->post_title</label>";
        }
        wp_die();
    }


    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function send_message_badge() {

        /* Variables */
        $language = $_POST['language'];
        $level = $_POST['level'];
        $badge_name = $_POST['badge_name'];
        $language_description = $_POST['language_description'];
        $listings_class = $_POST['class_student'];
        $mails = $_POST['mail'];
        $comment = $_POST['comment'];
        $sender = $_POST['sender'];
        $curForm = $_POST['curForm'];

        $class = null;
        $notsent = array();
        $badge = null;

        //User default class
        $teacher_information = get_user_by('email', $sender);
        $default_class = get_class_teacher($teacher_information->user_login);
        /* JSON file */
        $url_json_files = content_url('uploads/badges-issuer/json/');
        $path_dir_json_files = plugin_dir_path(dirname(__FILE__)) . '../../../uploads/badges-issuer/json/';
        /* Check if there are sufficient param */
        if (!isset($language) || !isset($level) || !isset($badge_name) ||
            !isset($language_description) || !isset($comment) || !isset($sender)) {

            echo "No enough information";

        } else {

            /* Get badge CERTIFICATION */
            $badge_others_items = get_badge($badge_name, $language_description);
            $certification = get_post_meta($badge_others_items['id'], '_certification', true);

            /* Set the email(s) */
            if (isset($mails)) {
                $mails_list = explode("\n", $mails);
            } else {
                $mails_list[0] = $sender;
            }

            /* Set the right class */
            if (isset($listings_class)) {
                $class = get_class_by_id($listings_class);
            } elseif (isset($default_class)) {
                $class = $default_class;
            }

            /* Creation of the badge */
            $badge = new Badge($badge_others_items['name'], $level, $language, $certification, $comment,
                $badge_others_items['description'], $language_description, $badge_others_items['image'],
                $url_json_files, $path_dir_json_files);

            /* Sending all the email */
            foreach ($mails_list as $mail) {

                /* operation for system not unix */
                $mail = str_replace("\r", "", $mail);

                $badge->create_json_files($mail);

                //SENDING THE EMAIL
                if (!$badge->send_mail($mail, $class->ID)) {
                    $notsent[] = $mail;
                } else {
                    if ($curForm == "a") {
                        $badge->add_student_to_class_zero($mail);
                    } else {
                        $badge->add_student_to_class_zero($mail);
                        $badge->add_student_to_class($mail, $class->ID);
                        $badge->add_badge_to_user_profile($mail, $_POST['sender'], $class->ID);
                    }
                }
            }

            if (sizeof($notsent) > 0) {
                $message = "Badge not sent to these persons : ";
                foreach ($notsent as $notsent_mail) {
                    $message = $message . $notsent_mail . " ";
                }
                echo($message);
            } else {
                echo("Badge ($badge->name) sent to all the persons and stored in the class $class->post_title.");
            }
        }
    }

}
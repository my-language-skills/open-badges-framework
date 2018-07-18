<?php

namespace templates;

use Inc\Database\DbBadge;
use Inc\Database\DbUser;
use Inc\Pages\Admin;
use Inc\Utils\Badge;
use Inc\Utils\WPBadge;
use Inc\Utils\WPUser;

/**
 * Class tha contain all the function to show a single
 * badge, tha could be a Post or a Database badge.
 * Post it just the information of the badge saved from
 * wordpress and the Database badge is saved in the custom
 * table create from the plugin.
 *
 * All the content to show in the front-end is wrapped in the __() function
 * for internationalization purposes 
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class SingleBadgeTemp {
    const IS_POST_BADGE = 0;
    const IS_DB_BADGE = 1;
    const ERROR_LINK = 2;

    private $id = null;

    /**
     * Init the process for the backend taking the
     * information form the url.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     */
    public function main() {
        $res = $this->loadParm();

        echo "<div class='wrap'>";

        switch ($res) {
            case self::IS_DB_BADGE:
                $this->showDatabaseBadge($this->id);
                break;
            case self::IS_POST_BADGE:
                $this->showPostBadge($this->id);
                break;
            case self::ERROR_LINK:
                self::showErrorLink(self::ERROR_LINK);
                break;
        }
        echo "</div>";
		}

    /**
     * Control the parameter in the url.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return int
     */
    public function loadParm() {
        if (isset($_GET['badge']) && isset($_GET['db'])) {
            $this->id = $_GET['badge'];

            if ($_GET['db'] == self::IS_DB_BADGE) {
                if (DbBadge::getById($this->id)) {
                    return self::IS_DB_BADGE;
                } else {
                    return self::ERROR_LINK;
                }

            } else {
                if (get_post($this->id)) {
                    return self::IS_POST_BADGE;
                } else {
                    return self::ERROR_LINK;
                }
            }
        } else {
            return self::ERROR_LINK;
        }
    }

    /**
     * Show Post badge section.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param $id
     *
     * @return void
     */
    public function showPostBadge($id) {

        $badge = get_post($id);
        $levels = wp_get_post_terms($id, Admin::TAX_LEVELS);
        $fields = wp_get_post_terms($id, Admin::TAX_FIELDS);
		
        ?>
        <div class="obf-bsp-badge-image">
            <img class="circle-img" src="<?php echo WPBadge::getUrlImage($badge->ID); ?>">
        </div>
        <section>
            <h1><?php _e('Badge information','open-badges-framework.');?></h1>
            <p> <?php _e('Name: ','open-badges-framework.');?><strong><?php echo $badge->post_title; ?></strong></p>
            <p> <?php _e('Level: ','open-badges-framework.');?><strong><?php foreach ($levels as $level) echo $level->name . " "; ?></strong></p>
            <p> <?php _e('Field of education: ','open-badges-framework.');?><strong><?php foreach ($fields as $field) echo $field->name;
                    echo !$fields ? "All" : ""; ?></strong></p>
            <p> <?php _e('Description: ','open-badges-framework.');?><strong><?php echo $badge->post_content; ?></strong></p>
            <?php if (current_user_can("manage_options")) { ?>
                <a href="<?php echo get_edit_post_link($badge->ID) ?>"> <?php _e('Edit post ','open-badges-framework.');?></a>
            <?php } ?>
        </section>
        <?php
    }

    /**
     * Show Database badge section,if the teacher is an active user his info will be displayed,
	 * if he has been deleted a proper message will appear.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param $idBadge
     *
     * @return void
     */
    public static function showDatabaseBadge($idBadge) {
        $badge = new Badge();
        $badge->retrieveBadge($idBadge);
        $badgeWP = get_post($badge->idBadge);
        $student = DbUser::getById($badge->idUser);
        $studentWP = get_user_by("email", $student->email);
        $teacherWP = get_user_by('id', $badge->idTeacher);
        $level = get_term($badge->idLevel);
        $field = get_term($badge->idField);
        $badgeLink = Badge::getLinkGetBadge($badge->id);
        ?>

        <div class="obf-bsp-badge-image">
            <img class="circle-img" src="<?php echo WPBadge::getUrlImage($badgeWP->ID); ?>">
        </div>
            <section>
                <h1><?php _e('Badge information ','open-badges-framework.');?></h1>
                <p><?php _e('Name: ','open-badges-framework.');?><strong><?php echo $badgeWP->post_title; ?></strong></p>
                <p><?php _e('Level: ','open-badges-framework.');?><strong><?php echo $level->name; ?></strong></p>
                <p><?php _e('Field of education: ','open-badges-framework.');?><strong><?php echo $field->name; ?></strong></p>
                <p><?php _e('Description: ','open-badges-framework.');?><strong><?php echo $badge->description; ?></strong></p>
            </section>
            <section>
                <h3><?php _e('Teacher information','open-badges-framework.');?></h3>
				 <?php if ($teacherWP ) { ?>
					<p><?php _e('Name: ','open-badges-framework.');?><strong><?php echo $teacherWP->first_name; ?></strong></p>
					<p><?php _e('Last name: ','open-badges-framework.');?><strong><?php echo $teacherWP->last_name; ?></strong></p>
					<p><?php _e('Email: ','open-badges-framework.');?><strong><?php echo $teacherWP->user_email; ?></strong></p>
				<?php } else { ?>
					<p><strong><?php _e('Teacher no longer available!!!','open-badges-framework.');?></strong></p>
				<?php }	?>
                <p>
                    <?php _e('Evidence: ','open-badges-framework.');?>
                    <strong>
                        <?php if ($badge->evidence != "none") { ?>
                            <a href="<?php echo $badge->evidence; ?>"><?php echo $badge->evidence; ?></a>
                        <?php } else {
                            echo $badge->evidence;
                        } ?>
                    </strong>
                </p>
            </section>
            <section>
                <h3><?php _e('General information','open-badges-framework.');?></h3>
                <p><?php _e('Info: ','open-badges-framework.');?><strong><?php echo $badge->info; ?></strong></p>
                <p><?php _e('Received: ','open-badges-framework.');?><strong><?php echo date("d M Y", strtotime($badge->creationDate)); ?></strong></p>
                <p><?php _e('Earned: ','open-badges-framework.');?>
                    <strong><?php echo $badge->gotDate ? date("d M Y", strtotime($badge->gotDate)) : "on hold"; ?></strong>
                </p>
                <p><?php _e('Earned in Mozilla Open Badge: ','open-badges-framework.');?>
                    <strong><?php echo $badge->gotMozillaDate ? date("d M Y", strtotime($badge->gotMozillaDate)) : "on hold"; ?></strong>
                </p>
            </section>
            <?php

            if ($studentWP->ID === wp_get_current_user()->ID) {

                if (!$badge->gotDate) { ?>

                    <div class="obf-sbp-cont-btn">
                        <a class="btn btn-lg btn-primary" href="<?php echo $badgeLink; ?>"><?php _e('Get the badge','open-badges-framework.');?></a>
                    </div>
                    <?php
                } else if (!$badge->gotMozillaDate) { ?>
                    <div class="obf-sbp-cont-btn">
                        <a class="btn btn-lg btn-secondary" href="<?php echo $badgeLink; ?>"><?php _e('Get Mozilla Open Badge','open-badges-framework.');?></a>
                    </div>
                    <?php
                }
            }
            ?>
        <?php
    }

    /**
     * Show error section.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param $error
     *
     * @return void
     */
    public function showErrorLink($error) {

        switch ($error) {
            case self::ERROR_LINK:
                echo "<h1>error</h1> <p class='lead'> Broken link. </p>";
                break;
        }
        ?>

        <?php
    }

}
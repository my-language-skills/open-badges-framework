<?php

namespace templates;

use Inc\Database\DbBadge;
use Inc\Utils\Badges;

/**
 * Class tha contain all the function to show a single
 * badge, tha could be a Post or a Database badge.
 * Post it just the information of the badge saved from
 * wordpress and the Database badge is saved in the custom
 * table create from the plugin.
 *
 * @author      Alessandro RICCARDI
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
     * @author      Alessandro RICCARDI
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
     * @author      Alessandro RICCARDI
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
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param $id
     */
    public function showPostBadge($id) {

        $badge = get_post($id);
        ?>
        <section class="user-cont-obf">
            <h1 class="obf-title">Badge: <strong><?php echo $badge->post_title; ?></strong></h1>
            <h3>Badge information</h3>
            <p>Description: <strong><?php echo $badge->post_content; ?></strong></p>
        </section>

        <?php
    }

    /**
     * Show Database badge section
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param $id
     */
    public static function showDatabaseBadge($id) {
        $dbBadge = DbBadge::getById($id);
        $badge = get_post($dbBadge->badgeId);
        $student = get_user_by("email", $dbBadge->userEmail);
        $teacher = get_user_by('id', $dbBadge->teacherId);
        $level = get_term($dbBadge->levelId);
        $field = get_term($dbBadge->fieldId);
        $badgeLink = Badges::getLinkGetBadge($dbBadge->json, $dbBadge->badgeId, $dbBadge->fieldId, $dbBadge->levelId);
        $idsBadge = array(
            "userEmail" => $dbBadge->userEmail,
            "badgeId" => $dbBadge->badgeId,
            "fieldId" => $dbBadge->fieldId,
            "levelId" => $dbBadge->levelId
        );
        ?>
        <div class="obf-bsp-badge-image">
            <img class="circle-img" src="<?php echo Badges::getImage($badge->ID); ?>">
        </div>
        <section class="user-cont-obf">
            <h1 class="obf-title"><strong><?php echo $badge->post_title; ?></strong></h1>
            <section>
                <h3>Badge information</h3>
                <p>Name: <strong><?php echo $badge->post_title; ?></strong></p>
                <p>Level: <strong><?php echo $level->name; ?></strong></p>
                <p>Field of education: <strong><?php echo $field->name; ?></strong></p>
                <p>Description: <strong><?php echo $badge->post_content; ?></strong></p>
            </section>
            <section>
                <h3>Teacher information</h3>
                <p>Name: <strong><?php echo $teacher->first_name; ?></strong></p>
                <p>Last name: <strong><?php echo $teacher->last_name; ?></strong></p>
                <p>Email: <strong><?php echo $teacher->user_email; ?></strong></p>
                <p>Info: <strong><?php echo $dbBadge->info; ?></strong></p>
                <p>Evidence: <strong><?php echo $dbBadge->evidence; ?></strong></p>
            </section>
            <section>
                <h3>General information</h3>
                <p>Received: <strong><?php echo date("d M Y", strtotime($dbBadge->dateCreation)); ?></strong></p>
                <p>Earned:
                    <strong><?php echo $dbBadge->getDate ? date("d M Y", strtotime($dbBadge->getDate)) : "on hold"; ?></strong>
                </p>
                <p>Earned in Mozilla Open Badge:
                    <strong><?php echo $dbBadge->getMobDate ? date("d M Y", strtotime($dbBadge->getMobDate)) : "on hold"; ?></strong>
                </p>
            </section>
            <?php

            if($student->ID === wp_get_current_user()->ID) {
                if (!DbBadge::isGot($idsBadge)) { ?>
                    <div class="obf-sbp-cont-btn">
                        <a class="btn btn-lg btn-primary" href="<?php echo $badgeLink; ?>">Get it</a>
                    </div>
                    <?php
                } else if (!DbBadge::isGotMOB($idsBadge)) { ?>
                    <div class="obf-sbp-cont-btn">
                        <a class="btn btn-lg btn-secondary" href="<?php echo $badgeLink; ?>">Get Mozilla Open Badge</a>
                    </div>
                    <?php
                }
            }
            ?>
        </section>
        <?php
    }

    /**
     * Show error section.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param $error
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
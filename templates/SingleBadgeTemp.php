<?php

namespace templates;

use Inc\Database\DbBadge;
use Inc\Utils\Badges;

/**
 *
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class SingleBadgeTemp {
    const POST = 0;
    const DB = 1;
    const ERROR_LINK = 2;

    private $id = null;

    public function main() {
        $res = $this->loadParm();

        echo "<div class='wrap'>";

        switch ($res) {
            case self::DB:
                $this->showDatabaseBadge($this->id);
                break;
            case self::POST:
                $this->showPostBadge($this->id);
                break;
            case self::ERROR_LINK:
                break;
        }

        echo "</div>";

    }

    public function loadParm() {
        if (isset($_GET['badge']) && isset($_GET['db'])) {
            $this->id = $_GET['badge'];

            if ($_GET['db'] == self::DB) {
                return self::DB;
            } else {
                return self::POST;
            }

        } else {
            return self::ERROR_LINK;
        }
    }

    public function showPostBadge($id) {

        $badge = get_post($id);
        ?>
        <h1 class="obf-title">Badge: <strong><?php echo $badge->post_title; ?></strong></h1>
        <section>
            <h3>Badge information</h3>
            <p>Description: <strong><?php echo $badge->post_content; ?></strong></p>
        </section>

        <?php
    }

    public static function showDatabaseBadge($id) {
        $dbBadge = DbBadge::getById($id);
        $badge = get_post($dbBadge->badgeId);
        $teacher = get_user_by( 'id', $dbBadge->teacherId);
        $level = get_term($dbBadge->levelId);
        $field = get_term($dbBadge->fieldId);
        $badgeLink = Badges::getLinkGetBadge($dbBadge->json, $dbBadge->badgeId, $dbBadge->fieldId, $dbBadge->levelId );

        ?>
        <section class="user-cont-obf">
        <h1 class="obf-title"> <strong><?php echo $badge->post_title; ?></strong> </h1>
        <section>
            <h3>Badge information</h3>
            <p>Name: <strong><?php echo $badge->post_title; ?></strong></p>
            <p>Level: <strong><?php echo $level->name; ?></strong></p>
            <p>Field of education: <strong><?php echo $field->name; ?></strong></p>
            <p>Description: <strong><?php echo $badge->post_content; ?></strong></p>
        </section>
        <section>
            <h3>Teacher information</h3>
            <p>Name: <strong><?php echo $teacher->first_name;?></strong></p>
            <p>Last name: <strong><?php echo $teacher->last_name;?></strong></p>
            <p>Email: <strong><?php echo $teacher->user_email;?></strong></p>
            <p>Info: <strong><?php echo $dbBadge->info;?></strong></p>
            <p>Evidence: <strong><?php echo $dbBadge->evidence; ?></strong></p>
        </section>
        <section>
            <h3>General information</h3>
            <p>Received: <strong><?php echo  date("d M Y", strtotime($dbBadge->dateCreation));?></strong></p>
            <p>Earned: <strong><?php echo $dbBadge->getDate ? date("d M Y", strtotime($dbBadge->getDate)) : "on hold";?></strong></p>
            <p>Earned in Mozilla Open Badge: <strong><?php echo $dbBadge->getMobDate ? date("d M Y", strtotime($dbBadge->getMOB)) : "on hold";?></strong></p>
        </section>
        <a class="button btn-redirect wp-generate-pw" href="<?php echo $badgeLink; ?>">Get it</a>
        </section>
        <?php
    }

    public function showErrorLink($error) {

    }

}
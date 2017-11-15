<?php
/**
 * The Classes Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace templates;

use Inc\Base\BaseController;
use Inc\Pages\Admin;
use Inc\Utils\Badges;

class GetBadgeTemp extends BaseController {
    private $badge = null;
    private $field = null;
    private $level = null;


    public function main() {

        if (isset($_GET['badge']) && isset($_GET['field']) && isset($_GET['level'])) {
            $badgeId = $_GET['badge'];
            $fieldId = $_GET['field'];
            $levelId = $_GET['level'];

            $badges = new Badges();
            $this->badge = $badges->getBadgeById($badgeId);
            $this->field = get_term($fieldId, Admin::TAX_FIELDS);
            $this->level = get_term($levelId, Admin::TAX_LEVELS);

            $this->getMainPage();
        } else {
            $this->getErrorPage();
        }

    }


    private function getMainPage() {
        $this->obf_header()

        ?>
        <div id="gb-wrap">
            <div class="container gb-show-cont">
                <div class="row">
                    <div class="cont-title">New badge</div>
                </div>
                <div class="row justify-content-between gb-info">
                    <div class="col-2">
                        <img src="<?php echo get_the_post_thumbnail_url($this->badge->ID) ?>" height="auto"
                             width="100%">
                    </div>
                    <div class="col-9">
                        <h1 class="badge-title"><strong><?php echo $this->badge->post_title; ?></strong></h1>
                        <h5 class="badge-field">Field: <strong><?php echo $this->field->name; ?></strong></h5>
                        <h5 class="badge-level">Level: <strong><?php echo $this->level->name; ?></strong></h5>
                        <p><?php echo $this->badge->post_content; ?></p>
                    </div>
                </div>
                <div class="cont-button-abb">
                    <button id="getBadge" type="button" class="btn btn-primary">Get the badge</button>
                </div>
            </div>
        </div>
        <?php
        $this->obf_footer();
    }

    private function getErrorPage() {
        $this->obf_header()

        ?>
        <div class="container obf-cont">
            <div class="cont-title">
                <h1>Url error</h1>
            </div>
            <h2></h2>

        </div>
        <?php
        $this->obf_footer();
    }

    private function obf_header() {
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <title>My WordPress Plugin Front-end Page</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
                  integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ"
                  crossorigin="anonymous">
            <link rel="stylesheet" href="<?php echo $this->plugin_url; ?>assets/css/getBadge.css">
            <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
            <script src="<?php echo $this->plugin_url; ?>assets/js/getBadge.js"></script>
        </head>
        <body>
        <?php
    }

    private function obf_footer() { ?>
        </body>
        </html>

        <?php
    }

}
<?php
/**
 * ...
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace inc\Utils;

use Inc\Base\BaseController;
use Inc\OB\JsonManagement;
use Inc\Pages\Admin;
use Inc\Utils\Badges;

class SendBadge extends BaseController {
    private $badgeInfo = null;
    private $jsonMg = null;
    private $badge = null;
    private $field = null;
    private $level = null;
    private $receivers = null;
    private $class = null;
    private $evidence = null;

    /**
     * The constructor of the Badge object.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    function __construct($id, $fieldId, $levelId, $info, $receivers, $class = '', $evidence = '') {
        parent::__construct();
        $badges = new Badges();
        $this->badge = $badges->getBadgeById($id);

        $this->field = get_term($fieldId, Admin::TAX_FIELDS);
        $this->level = get_term($levelId, Admin::TAX_LEVELS);

        $this->badgeInfo = array(
            'id' => $this->badge->ID,
            'name' => $this->badge->post_title,
            'field' => $this->field->name,
            'level' => $this->level->name,
            'description' => $this->badge->post_content,
            'link' => get_permalink($this->badge),
            'image' => get_the_post_thumbnail_url($this->badge->ID),
            'tags' => array($this->field->name."", $this->level->name.""),
            'info' => $info,
            'evidence' => $evidence
        );

        $this->receivers = $receivers;
        $this->class = $class;
        $this->evidence = $evidence;

        $this->jsonMg = new JsonManagement($this->badgeInfo);
    }

    public function sendBadge() {

        $subject = "Badge: " . $this->badge->post_title . " Field: " . $this->field->name;
        //Setting headers so it"s a MIME mail and a html
        $headers = "From: badges4languages <mylanguageskills@hotmail.com>\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=utf-8\n";
        $headers .= "Reply-To: mylanguageskills@hotmail.com\n";

        if (is_array($this->receivers)) {
            foreach ($this->receivers as $receiver) {
                $hashName = $this->jsonMg->createJsonFile($receiver);
                if ($hashName != null) {
                    $body = $this->getBodyEmail($hashName);
                } else {
                    return "error json file";
                }

                if (!wp_mail($receiver, $subject, $body, $headers)) {
                    return "error email";
                }
            }
        } else {
            $hashName = $this->jsonMg->createJsonFile($this->receivers);
            if ($hashName != null) {
                $body = $this->getBodyEmail($hashName);
            } else {
                return "error json file";
            }

            if (!wp_mail($this->receivers, $subject, $body, $headers)) {
                return "error email";
            }
        }

        return "success";
    }

    private function getBodyEmail($hash_file) {
        $urlGetBadge = home_url('/' . Admin::SLUG_GETBADGE . '/');

        $badgeLink =
            $urlGetBadge .
            "?json=$hash_file" .
            "&badge=" . $this->badge->ID .
            "&field=" . $this->field->term_id .
            "&level=" . $this->level->term_id;

        $body = "
                <html>
                    <head>
                        <meta http-equiv='Content-Type' content='text/html'; charset='utf-8' />
                    </head>
                    <body>
                        <div class='container'>
                            <a href='$badgeLink'>Get the badge</a>
                        </div>
                    </body>
                </html>
                    ";
        return $body;
    }
}
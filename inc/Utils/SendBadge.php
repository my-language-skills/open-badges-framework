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
    private $receivers = null;
    private $class = null;

    /**
     * The constructor of the Badge object.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     */
    function __construct($id, $fieldId, $levelId, $info, $receivers, $class = null) {
        parent::__construct();
        $badges = new Badges();
        $badge = $badges->getBadgeById($id);
        $this->badgeInfo = array(
            'id' => $badge->ID,
            'name' => $badge->post_name,
            'fieldId' => $fieldId,
            'levelId' => $levelId,
            'description' => $badge->post_content,
            'info' => $info,
            'image' => get_the_post_thumbnail_url($badge->ID),
        );

        $this->receivers = $receivers;
        $this->class = $class;

        $this->jsonMg = new JsonManagement($this->badgeInfo);
    }

    public function sendBadge() {

        $subject = "Badge: $this->badgeId";
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
            "&badge=" . $this->badgeInfo['id'] .
            "&field=" . $this->badgeInfo['fieldId'] .
            "&level=" . $this->badgeInfo['levelId'];

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
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
use Inc\Pages\Admin;
use Inc\Utils\Badges;
use templates\SettingsTemp;

class Badge extends BaseController {
    private $id = null;
    private $field = null;
    private $level = null;
    private $info = null;
    private $receivers = null;
    private $class = null;

    /**
     * The constructor of the Badge object.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     */
    function __construct($id, $field, $level, $info, $receivers, $class = "") {
        parent::__construct();
        $this->id = $id;
        $this->field = $field;
        $this->level = $level;
        $this->info = $info;
        $this->receivers = $receivers;
        $this->class = $class != "" ? $class : null;
    }

    public function sendBadge() {
        $subject = "Badge: $this->id";
        $body = $this->getBodyEmail();
        //Setting headers so it"s a MIME mail and a html
        $headers = "From: badges4languages <mylanguageskills@hotmail.com>\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=utf-8\n";
        $headers .= "Reply-To: mylanguageskills@hotmail.com\n";


        if (is_array($this->receivers)) {
            foreach ($this->receivers as $receiver) {
                if (!$this->createJsonFile($receiver)) {
                    return "error";
                }
                if (!wp_mail($receiver, $subject, $body, $headers)) {
                    return "error";
                }
            }
        } else {
            if (!wp_mail($this->receivers, $subject, $body, $headers)) {
                return "error";
            }
            if (!$this->createJsonFile($this->receivers)) {
                return "error";
            }
        }

        return "success";
    }

    public function createJsonFile($receiver) {
        $salt = uniqid();
        $date = date('Y-m-d');
        $hash_name = hash("sha256", $receiver . $this->id);
        $hash_file = "assertion_" . $hash_name . ".json";
        $file_name_path = $this->getJsonPath() . $hash_file;
        $file_name_url = $this->getJsonUrl() . $hash_file;
        $info = $this->getJsonBadgeInfo($hash_file);

        $assertion = array(
            "uid" => $salt,
            "recipient" => array("type" => "email", "identity" => $receiver, "hashed" => false),
            "issuedOn" => $date,
            "badge" => $info,
            "verify" => array("type" => "hosted", "url" => $file_name_url)
        );

        return file_put_contents($file_name_path, json_encode($assertion, JSON_UNESCAPED_SLASHES));
    }

    private function getJsonBadgeInfo() {
        $badgeInfo = $this->getBadgeInfo($this->id);

        $desc =
            "Field: " . $badgeInfo['field'] .
            ", Level: " . $badgeInfo['level'] .
            ", Description: " . $badgeInfo['description'] .
            ", Info: " . $badgeInfo['info'];

        $jsonInfo = array(
            '@context' => 'https://w3id.org/openbadges/v1',
            "name" => $badgeInfo['name'] . " " . $badgeInfo['field'],
            "description" => $desc,
            "image" => $badgeInfo['image'],
            "field" => $badgeInfo['field'],
            "level" => $badgeInfo['level'],
            "criteria" => "http://" . $_SERVER['SERVER_NAME'] . "/badge/" . strtolower($badgeInfo['level']),
            "issuer" => ""
        );

        return $jsonInfo;
    }

    private function getBodyEmail($hash_file) {
        $badgeInfo = $this->getBadgeInfo($this->id);
        $options = get_option(SettingsTemp::OPTION_NAME);

        $badgeLink = get_page_link($options['get_badge_page']) . "?json=$hash_file&class=$this->class]";
        return "<html>
                        <head>
                                <meta http-equiv='Content-Type' content='text/html'; charset='utf-8' />
                        </head>
                        <body>
                            <div class='container'>
                            <a href='$badgeLink'>Get the badge</a>
                            </div>
                        </body>
                    </html>";
    }

    private function getBadgeInfo($id) {
        $badges = new Badges();
        $badge = $badges->getBadgeById($id);

        $badgeInfo = array(
            'id' => $badge->ID,
            'name' => $badge->post_name,
            'field' => $this->field,
            'level' => $this->level,
            'description' => $badge->post_content,
            'info' => $this->info,
            'image' => get_the_post_thumbnail_url($badge->ID),
        );

        return $badgeInfo;
    }


    function oldSendEmail() {
        $hash_name = hash("sha256", $this->receiver . $this->name . $this->language);
        $url_mail = plugins_url('./get_badge.php', __FILE__);
        $url_mail = $url_mail . "?hash=" . $hash_name . "&level=" . $this->level . "&language=" . $this->language;


        $subject = "Your teacher of $this->language just send you a badge";

        //Message displayed in the email
        $message = '<html>
                        <head>
                                <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
                        </head>
                        <body>
                            <div id="b4l-award-actions-wrap">
                                <div align="center">
                                    <h1>BADGES FOR LANGUAGES</h1>
                                    <br />
                                    <h1><b>Congratulations: You have just earned a badge
                                    <br />
                                    ' . $this->language . ' language - ' . $this->name . ' level!</b></h1>
                                    <h2>The following steps will help you to receive a badge.
                                    <br />
                                    If you allready have badges, go to the step 3</h2>
                                    <hr/ >
                                    <H1> Step One: Loggin into Badges for languages </H1>
                                    <h2>Necesary for getting the badge and to write a teacher review</h2>
                                    <center><img src="' . plugins_url("../../assets/b4l_logo.png", __FILE__) . '" width="150" height="150"/></center>
                                    <a href="' . get_page_link($settings_id_login_links["link_register"]) . '">Register</a> | <a href="' . get_page_link($settings_id_login_links["link_login"]) . '">Login</a>
                                    <br />
                                    Just registered users can receive badges.
                                    <hr/ >
                                    <H1> Step Two: Open a Mozzilla OpenBadges Backpack account </H1>
                                    <h2>Moz://a Backpack Store and Share your Open Badges.</h2>
                                    <br/ >
                                    <center><img src="' . plugins_url("../../assets/openbadges_logo_thumbnail.png", __FILE__) . '" width="300" height="100"/></center>
                                    <br/ >
                                    <a href="https://backpack.openbadges.org/backpack/signup">register</a> | <a href="https://backpack.openbadges.org/backpack/login">loggin</a>
                                    <br/ >
                                    <br/ >
                                    Badges for languages uses Mozzilla <a href="https://openbadges.org/">OpenBadges</a> and his <a href="https://backpack.openbadges.org/">backpack</a>, a tool for collecting, managing and controlling privacy settings for badges you have been awarded as an earner.</h2>
                                    <br />
                                    <hr/>
                                    <H1> Step Three: Open the link, and get the badge. </H1>
                                    <h2> Don´t forget to write a teacher review</h2>
                                    <br />
                                    <h2>' . $this->name . ' - ' . $this->language . '</h2>
                                    <a href="' . $url_mail . '">
                                        <img src="' . $this->image . '" width="150" height="150"/>
                                    </a>
                                    <br>
                                    <br />
                                    <a href="' . $url_mail . '">' . $url_mail . '</a>
                                    <br /><br />
                                    For a better education, we need to start by evaluating teacher performance. Once you receive the badge, the review section of your teacher\'s class would be open.
                                    <br/ >
                                    <div class="browserSupport"><b>Please use Firefox or Google Chrome to retrieve your badge.<b></div>
                                    <br />
                                    <hr/>
                                    <p style="font-size:9px; color:grey"><a href="http://badges4languages.com/">Badges for Languages</a> by My Language Skills, based in Valencia, Spain.
                                    More information <a href="https://mylanguageskills.wordpress.com/">here</a>.
                                    Legal information <a href="https://mylanguageskillslegal.wordpress.com/category/english/badges-for-languages-english/">here</a>.
                                    </p>
                                </div>
                            </div>
                        </body>
                    </html>';

        //Setting headers so it's a MIME mail and a html
        $headers = "From: badges4languages <mylanguageskills@hotmail.com>\n";
        $headers .= "MIME-Version: 1.0" . "\n";
        $headers .= "Content-type: text/html; charset=utf-8" . "\n";
        $headers .= "Reply-To: mylanguageskills@hotmail.com\n";

        return mail($this->receiver, $subject, $message, $headers); //Sending the emails
    }

}
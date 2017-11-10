<?php
/**
 * ...
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     BadgeIssuerForWp
 */

namespace inc\Utils;


class Badge {
    private $receivers = array();
    private $id = null;
    private $certified = null;
    private $fields = null;
    private $level = null;
    private $description = null;
    private $image = null;
    private $information = null;
    private $class = null;

    /**
     * The constructor of the Badge object.
     *
     * @author   Alessandro RICCARDI
     *
     * @param        $id
     * @param array  $receivers
     * @param string $class
     *
     */
    function __construct($id, $receivers, $class = "") {
        $this->id = $id;
        $this->receivers = $receivers;
        $this->class = $class != "" ? $class : null;
    }

    public function sendBadge() {
        $subject = "Badge: $this->id";
        $body = "$this->id" . ($this->class != "" ? $this->class : null);
        //Setting headers so it"s a MIME mail and a html
        $headers = "From: badges4languages <mylanguageskills@hotmail.com>\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=utf-8\n";
        $headers .= "Reply-To: mylanguageskills@hotmail.com\n";

        if( is_array($this->receivers)) {
            foreach ($this->receivers as $receiver) {
                if (!wp_mail($receiver, $subject, $body, $headers)) {
                    return "error";
                }
            }
        } else {
            if (!wp_mail($this->receivers, $subject, $body, $headers)) {
                return "error";
            }
        }
        return "success";

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
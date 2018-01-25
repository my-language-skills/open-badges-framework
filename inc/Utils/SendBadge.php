<?php

namespace Inc\Utils;

use Inc\Base\BaseController;
use Inc\Database\DbModel;
use Inc\Pages\Admin;
use templates\SettingsTemp;

/**
 * Class that permit to send badges.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class SendBadge extends BaseController {
    const ER_JSON_FILE = "Error json file\n";
    const ER_SEND_EMAIL = "Error email\n";
    const ER_DB_INSERT = "Db insert error.\n";
    const SUCCESS = "Email success.\n";
    const ER_GENERAL = "General error";

    private $badge = null;
    private $jsonMg = null;
    private $wpBadge = null;
    private $field = null;
    private $level = null;
    private $receivers = null;
    private $evidence = null;

    /**
     * Initialization of all the variable.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param int    $idBadge   the id of the badge
     * @param int    $idField   the id of the field
     * @param int    $idLevel   the id of the level
     * @param string $info      the additional from the teacher
     * @param array  $receivers the people that will receive the email
     * @param string $classId   the eventual class
     * @param string $evidence  the work of the student in url format
     */
    function __construct($idBadge, $idField, $idLevel, $info, $receivers, $classId = '', $evidence = '') {

        $this->badge = new Badge();
        $this->wpBadge = WPBadge::get($idBadge);
        $this->field = get_term($idField, Admin::TAX_FIELDS);
        $this->level = get_term($idLevel, Admin::TAX_LEVELS);
        $this->receivers = $receivers;
        $this->evidence = $evidence;

        //$this->badge->setIdUser($idUser); --> we will set it after for each student
        $this->badge->idBadge = $this->wpBadge->ID;
        $this->badge->idField = $this->field->term_id;
        $this->badge->idLevel = $this->level->term_id;
        $this->badge->idClass = $classId;
        $this->badge->idTeacher = WPUser::getCurrentUser()->ID;
        $this->badge->teacherRole = WPUser::getCurrentUser()->ID;
        $this->badge->creationDate = DbModel::now();
        //$this->badge->setJson($json); --> we will set it after
        $this->badge->info = $info;
        $this->badge->evidence = $evidence ? $evidence : "none";

        $this->jsonMg = new JsonManagement($this->badge);

    }

    /**
     * This class do principal four important things,
     * crate the json file, crate the body, send the email
     * and store all of that information in the database.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     * @return string to determinate the status of the process.
     */
    public function send() {
        $options = get_option(SettingsTemp::OPTION_NAME);

        $subject = "Badge: " . $this->wpBadge->post_title . " Field: " . $this->field->name;
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            "From: " . isset($options[SettingsTemp::FI_SITE_NAME_FIELD]) ? $options[SettingsTemp::FI_SITE_NAME_FIELD] : '' .
            " &lt;" . isset($options[SettingsTemp::FI_EMAIL_FIELD]) ? $options[SettingsTemp::FI_EMAIL_FIELD] : '',
        );

        if (is_array($this->receivers)) {
            foreach ($this->receivers as $email) {

                // Creation of json file
                if ($jsonName = $this->jsonMg->creation($email)) {

                    if($idDbUser = WPUser::insertUserInDB($email)){
                        if($idDbBadge = $this->badge->saveBadgeInDb($idDbUser, $jsonName)){
                            if($message = $this->getBodyEmail($idDbBadge)) {
                                // Send the email
                                $retEmail = wp_mail($email, $subject, $message, $headers);
                                if (!$retEmail) return self::ER_SEND_EMAIL;

                            } else {
                                echo "Error send email for $email \n";
                            }
                        } else {
                            echo "Error save badge in db for $email \n";
                        }
                    } else {
                        echo "Error insert user in db for $email \n";
                    }
                } else {
                    return self::ER_JSON_FILE;
                }
            }
            return self::SUCCESS;
        } else {
            return self::ER_GENERAL;
        }
    }

    /**
     * Function that permit to create the body of the email.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     * @param int $idDbBadge id of the database row of the badge.
     *
     * @return string the body of the email in html format
     */
    private function getBodyEmail($idDbBadge) {
        $badgeLink = Badge::getLinkGetBadge($idDbBadge);
        $options = get_option(SettingsTemp::OPTION_NAME);
        // wordpress var
        $compName = isset($options[SettingsTemp::FI_SITE_NAME_FIELD]) ? $options[SettingsTemp::FI_SITE_NAME_FIELD] : '';
        $compUrl = isset($options[SettingsTemp::FI_WEBSITE_URL_FIELD]) ? $options[SettingsTemp::FI_WEBSITE_URL_FIELD] : '';
        $compDesc = isset($options[SettingsTemp::FI_DESCRIPTION_FIELD]) ? $options[SettingsTemp::FI_DESCRIPTION_FIELD] : '';
        $compUrlImg = isset($options[SettingsTemp::FI_IMAGE_URL_FIELD]) ? wp_get_attachment_url($options[SettingsTemp::FI_IMAGE_URL_FIELD]) : '';
        $compEmail = isset($options[SettingsTemp::FI_EMAIL_FIELD]) ? $options[SettingsTemp::FI_EMAIL_FIELD] : '';


        $body = "
                <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                <html xmlns='http://www.w3.org/1999/xhtml'>
                    <head>
                            <meta http-equiv='Content-Type' content='text/html'; charset='utf-8' />
                    </head>
                    <body>
                        <div id='b4l-award-actions-wrap'>
                            <div align='center'>
                                <h1>BADGES FOR LANGUAGES</h1>
                                <h1><b>Congratulations you have just earned a badge!</b></h1>
                                <h2>Learn languages and get official certifications</h2>
                                <center>
                                    <a href='" . $badgeLink . "'>
                                        <img src='" . WPBadge::getUrlImage($this->wpBadge->ID) . "' width='150' height='150'/>
                                    </a>
                                </center>
                                <h2>" . $this->wpBadge->post_title . " - " . $this->field->name . "</h2>
                                <p>Open the link, and get the badge.</p>
                                <a href='" . $badgeLink . "'>$badgeLink</a>
                                <br><br><hr>
                                <p style='font-size:9px; color:grey '>Badges for $compName" . ($compDesc ? ", $compDesc" : "" ) . "
                                <br>
                                More information <a href='$compUrl'>here</a>.
                                Contact us <a href='$compEmail'>here</a>.
                                </p>
                            </div>
                        </div>
                    </body>
            </html>
                ";
        return $body;
    }
}
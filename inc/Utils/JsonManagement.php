<?php

namespace Inc\Utils;

use Inc\Base\BaseController;
use Inc\Pages\Admin;
use templates\SettingsTemp;

/**
 * Permit to manage the Json files about the badges.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class JsonManagement extends BaseController {
    private $badgeInfo;
    const ISSUER_INFO_FILE = "issuer-info.json";

    /**
     * This construct load the $badgeInfo param in the variable of the class.
     *
     * @param array $badgeInfo {
     *                         Array that contain the main information of the
     *                         badge that we want to send
     *
     * @type int            id              Id of the badge.
     * @type string         name            Name of the badge.
     * @type string         field           Field of the badge.
     * @type string         level           Level of the badge.
     * @type string         description     Description of the badge.
     * @type string         link            Link of the badge.
     * @type string         image           Image of the badge.
     * @type string         tags            Tags.
     * @type string         info            Additional info from the teacher
     * @type string         evidence        Link about the report to get the badge.
     * }
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     */
    public function __construct(array $badgeInfo) {
        parent::__construct();
        $this->badgeInfo = $badgeInfo;
    }

    /**
     * Creation of the json file.
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     *
     * @param   string $receiver the email address of the person
     *                           that is getting the badge.
     *
     * @return  string  the name of the json file already created without extension |
     *                  if there's error return false.
     */
    public function createJsonFile($receiver) {
        $hashName = hash("sha256", $receiver . $this->badgeInfo['id'] . $this->badgeInfo['field'] . $this->badgeInfo['level']);
        $hashFile = $hashName . ".json";
        $pathFile = parent::getJsonFolderPath() . $hashFile;
        $urlFile = parent::getJsonFolderUrl() . $hashFile;
        $infoUrl = $this->createBadgeInfo($hashName);

        $assertion = array(
            "uid" => uniqid(),
            "recipient" => array("type" => "email", "identity" => $receiver, "hashed" => false),
            "badge" => $infoUrl,
            "verify" => array("url" => $urlFile, "type" => "hosted"),
            "issuedOn" => date('Y-m-d'),
            "evidence" => $this->badgeInfo['evidence']
        );

        return file_put_contents($pathFile, json_encode($assertion, JSON_UNESCAPED_SLASHES)) != false ? $hashName : null;
    }

    /**
     * Creation of the json file where are stored the information
     * about the badge.
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     *
     * @param   string $hashName of the principal json file
     *
     * @return  string the url of the json file that is crated
     */
    private function createBadgeInfo($hashName) {
        $hashFile = "badge-" . $hashName . ".json";
        $pathFile = parent::getJsonFolderPath() . $hashFile;
        $urlFile = parent::getJsonFolderUrl() . $hashFile;
        $issuerUrl = $this->createIssuerInfo();

        $endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');

        $description =
            "FIELD: " . $this->badgeInfo['field'] . "  $endash  " .
            "LEVEL: " . $this->badgeInfo['level'] . "  $endash  " .
            "DESCRIPTION: " . $this->badgeInfo['description'] . "  $endash  " .
            "Additional information: " . $this->badgeInfo['info'];


        $jsonInfo = array(
            "name" => $this->badgeInfo['name'] . " " . $this->badgeInfo['field'],
            "description" => $description,
            "image" => $this->badgeInfo['image'],
            "criteria" => $this->badgeInfo['link'],
            "tags" => $this->badgeInfo['tags'],
            "issuer" => $issuerUrl,
        );

        return file_put_contents($pathFile, json_encode($jsonInfo, JSON_UNESCAPED_SLASHES)) != false ? $urlFile : null;
    }

    /**
     * Creation of the json file where are stored the information
     * about the company, that information are retrieved from the
     * settings page in the admin back-end.
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     */
    private function createIssuerInfo() {
        $hashFile = self::ISSUER_INFO_FILE;
        $pathFile = parent::getJsonFolderPath() . $hashFile;
        $urlFile = parent::getJsonFolderUrl() . $hashFile;

        $options = get_option(SettingsTemp::OPTION_NAME);
        $jsonInfo = array(
            "name" => isset($options[SettingsTemp::FI_SITE_NAME_FIELD]) ? $options[SettingsTemp::FI_SITE_NAME_FIELD] : '',
            "url" => isset($options[SettingsTemp::FI_WEBSITE_URL_FIELD]) ? $options[SettingsTemp::FI_WEBSITE_URL_FIELD] : '',
            "description" => isset($options[SettingsTemp::FI_DESCRIPTION_FIELD]) ? $options[SettingsTemp::FI_DESCRIPTION_FIELD] : '',
            "image" => isset($options[SettingsTemp::FI_IMAGE_URL_FIELD]) ? wp_get_attachment_url($options[SettingsTemp::FI_IMAGE_URL_FIELD]) : '',
            "email" => isset($options[SettingsTemp::FI_EMAIL_FIELD]) ? $options[SettingsTemp::FI_EMAIL_FIELD] : '',
        );

        return file_put_contents($pathFile, json_encode($jsonInfo, JSON_UNESCAPED_SLASHES)) != false ? $urlFile : null;
    }

    /**
     * Retrieve the information that are stored in the json file
     * as an array object.
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     *
     * @param string $json name of the principal json file
     *
     * @return array Json information
     */
    public static function getJsonObject($json) {
        $baseController = new BaseController();
        $json = file_get_contents($baseController->getJsonFolderPath() . $jsonName . '.json');
        return json_decode($json, true);
    }

    /**
     * Retrieve the email stored in a specific json file.
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     *
     * @param string $json name of the principal json file
     *
     * @return string email address
     */
    public static function getEmailFromJson($json) {
        $jsonFile = self::getJsonObject($json);
        $email = $jsonFile["recipient"]['identity'];
        return $email;
    }

    /**
     * Retrieve the url of the specific json file.
     *
     * @author   Alessandro RICCARDI
     * @since    1.0.0
     *
     * @param string $json name of the principal json file
     *
     * @return the url of the json file
     */
    public static function getJsonUrl($json) {
        $baseController = new BaseController();
        $jsonName = $jsonName . '.json';
        return file_exists($baseController->getJsonFolderPath() . $jsonName) ?
            $baseController->getJsonFolderUrl() . $jsonName : false;
    }

}
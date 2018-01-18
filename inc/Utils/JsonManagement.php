<?php

namespace inc\Utils;

use Inc\Base\BaseController;
use Inc\Pages\Admin;
use templates\SettingsTemp;

/**
 * Permit to manage the Json files about the badges.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class JsonManagement extends BaseController {
    const ISSUER_INFO_FILE = "issuer-info.json";

    /**
     * The badge to be stored in json file.
     *
     * @var Badge|null
     */
    private $badge = null;

    /**
     * This construct set instance of the Badge.
     *
     * @param Badge $badge
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    public function __construct(Badge $badge) {
        parent::__construct();

        $this->badge = $badge;
    }

    /**
     * Creation of the main json file.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     * @param   string $receiver the email address of the person that is getting the badge.
     *
     * @return  string  the name of the json file already created without extension |
     *                  if there's error return false.
     */
    public function creation($receiver) {
        // function var
        $hashName = hash("sha256", $receiver . $this->badge);
        $hashFile = $hashName . ".json";
        $pathFile = parent::getJsonFolderPath() . $hashFile;
        $urlJsonMain = parent::getJsonFolderUrl() . $hashFile;
        $urlJsonBadge = $this->createJsonBadge($hashName);
        $assertion = array(
            "uid" => uniqid(),
            "recipient" => array("type" => "email", "identity" => $receiver, "hashed" => false),
            "badge" => $urlJsonBadge,
            "verify" => array("url" => $urlJsonMain, "type" => "hosted"),
            "issuedOn" => date('Y-m-d'),
            "evidence" => $this->badge->getEvidence()
        );

        if (file_put_contents($pathFile, json_encode($assertion, JSON_UNESCAPED_SLASHES))) {
            return $hashName;
        } else {
            return null;
        }
    }

    /**
     * Creation of the json Badge file where are stored the most important
     * information about the <b>badge</b>.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     * @param $hashNameMain string name of the main json file.
     *
     * @return null|string name of the json badge file (without extension),
     *                     null if errors.
     */
    private function createJsonBadge($hashNameMain) {
        // wordpress var
        $badge = WPBadge::get($this->badge->getIdBadge());
        $field = get_term($this->badge->getIdField(), Admin::TAX_FIELDS);
        $level = get_term($this->badge->getIdLevel(), Admin::TAX_LEVELS);

        // function var
        $hashFile = "badge-" . $hashNameMain . ".json";
        $pathFile = parent::getJsonFolderPath() . $hashFile;
        $urlFile = parent::getJsonFolderUrl() . $hashFile;
        $urlIssuer = $this->createIssuerCompany();
        $badgeDesc = $this->createBadgeDescription();
        $jsonInfo = array(
            "name" => $badge->post_title . " " . $field->name,
            "description" => $badgeDesc,
            "image" => WPBadge::getUrlImage($badge->ID),
            "criteria" => get_permalink($badge->ID),
            "tags" => array($field->name . "", $level->name . ""),
            "issuer" => $urlIssuer,
        );

        if (file_put_contents($pathFile, json_encode($jsonInfo, JSON_UNESCAPED_SLASHES))) {
            return $urlFile;
        } else {
            return null;
        }
    }

    /**
     * Creation of the json Issuer file where are stored the
     * information about the <b>company</b>.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    private function createIssuerCompany() {
        // function var
        $hashFile = self::ISSUER_INFO_FILE;
        $pathFile = parent::getJsonFolderPath() . $hashFile;
        $urlFile = parent::getJsonFolderUrl() . $hashFile;
        $options = get_option(SettingsTemp::OPTION_NAME);

        // wordpress var
        $compName = isset($options[SettingsTemp::FI_SITE_NAME_FIELD]) ? $options[SettingsTemp::FI_SITE_NAME_FIELD] : '';
        $compUrl = isset($options[SettingsTemp::FI_WEBSITE_URL_FIELD]) ? $options[SettingsTemp::FI_WEBSITE_URL_FIELD] : '';
        $compDesc = isset($options[SettingsTemp::FI_DESCRIPTION_FIELD]) ? $options[SettingsTemp::FI_DESCRIPTION_FIELD] : '';
        $compUrlImg = isset($options[SettingsTemp::FI_IMAGE_URL_FIELD]) ? wp_get_attachment_url($options[SettingsTemp::FI_IMAGE_URL_FIELD]) : '';
        $compEmail = isset($options[SettingsTemp::FI_EMAIL_FIELD]) ? $options[SettingsTemp::FI_EMAIL_FIELD] : '';

        $jsonInfo = array(
            "name" => $compName,
            "url" => $compUrl,
            "description" => $compDesc,
            "image" => $compUrlImg,
            "email" => $compEmail,
        );

        if (file_put_contents($pathFile, json_encode($jsonInfo, JSON_UNESCAPED_SLASHES))) {
            return $urlFile;
        } else {
            return null;
        }
    }

    /**
     * Retrieve the text information to put inside the badge
     * issuer json file.
     *
     * @return string the description
     */
    private function createBadgeDescription() {
        // wordpress var
        $badge = WPBadge::get($this->badge->getIdBadge());
        $field = get_term($this->badge->getIdField(), Admin::TAX_FIELDS);
        $level = get_term($this->badge->getIdLevel(), Admin::TAX_LEVELS);

        // function var
        $enDash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8'); //special character
        $description =
            "FIELD: " . $field->name . "  $enDash  " .
            "LEVEL: " . $level->name . "  $enDash  " .
            "DESCRIPTION: " . $badge->post_content . "  $enDash  " .
            "TEACHER INFO: " . $this->badge->getInfo() . "  $enDash  " .
            "EVIDENCE: " . $this->badge->getEvidence();

        return $description;
    }

    /**
     * Get the json file as an object.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     * @param $jsonName
     *
     * @return array|mixed|object
     */
    public static function getJsonObject($jsonName) {
        $baseController = new BaseController();
        $json = file_get_contents($baseController->getJsonFolderPath() . $jsonName . '.json');
        return json_decode($json, true);
    }

    /**
     * Get the email of a json file.
     *
     * @param $jsonName string the json name (without extension).
     *
     * @return string the email.
     */
    public static function getEmailFromJson($jsonName) {
        $jsonFile = self::getJsonObject($jsonName);
        $email = $jsonFile["recipient"]['identity'];
        return $email;
    }

    /**
     * Get the url of a json file.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     * @param $jsonName string the json name (without extension).
     *
     * @return bool|string the url, false if errors.
     */
    public static function getJsonUrl($jsonName) {
        $baseController = new BaseController();
        $jsonName = $jsonName . '.json';
        return file_exists($baseController->getJsonFolderPath() . $jsonName) ?
            $baseController->getJsonFolderUrl() . $jsonName : false;
    }

    /**
     * Get the url of a json file.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     * @param $jsonName    string the json name (without extension).
     * @param $isMain      bool true if we want the main issuer,
     *                     false if we want the badge issuer.
     *
     * @return bool|string the url, false if errors.
     */
    public static function getJsonPath($jsonName, $isMain = true) {
        $baseController = new BaseController();
        if (!$isMain) $jsonName = $jsonName . '.json';
        else    $jsonName = "badge-" . $jsonName . '.json';
        return file_exists($baseController->getJsonFolderPath() . $jsonName) ?
            $baseController->getJsonFolderPath() . $jsonName : false;
    }

    /**
     * Delete a main issuer json file and its related badge issuer.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     *
     * @param $jsonName string the main issuer json name (without extension).
     *
     * @return bool true if everything good, false otherwise.
     */
    public static function deleteJson($jsonName) {
        $main = self::getJsonPath($jsonName, true);
        $sec = self::getJsonPath($jsonName, false);

        return unlink($main) && unlink($sec);
    }
}
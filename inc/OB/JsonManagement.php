<?php
/**
 * The Fields Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace inc\OB;

use Inc\Base\BaseController;
use Inc\Pages\Admin;
use templates\SettingsTemp;

class JsonManagement extends BaseController {
    private $badgeInfo;

    public function __construct($badgeInfo) {
        parent::__construct();
        $this->badgeInfo = $badgeInfo;
    }

    public function createJsonFile($receiver) {
        $hashName = hash("sha256", $receiver . $this->badgeInfo);
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

    private function createBadgeInfo($hashName) {
        $hashFile = "badge-" . $hashName . ".json";
        $pathFile = parent::getJsonFolderPath() . $hashFile;
        $urlFile = parent::getJsonFolderUrl() . $hashFile;
        $issuerUrl = $this->createIssuerInfo($hashName);

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

    private function createIssuerInfo($hashName) {
        $hashFile = "issuer-" . $hashName . ".json";
        $pathFile = parent::getJsonFolderPath() . $hashFile;
        $urlFile = parent::getJsonFolderUrl() . $hashFile;

        $options = get_option(SettingsTemp::OPTION_NAME);
        $jsonInfo = array(
            "name" => isset($options[SettingsTemp::SITE_NAME_FIELD]) ? $options[SettingsTemp::SITE_NAME_FIELD] : '',
            "url" => isset($options[SettingsTemp::WEBSITE_URL_FIELD]) ? $options[SettingsTemp::WEBSITE_URL_FIELD] : '',
            "description" => isset($options[SettingsTemp::DESCRIPTION_FIELD]) ? $options[SettingsTemp::DESCRIPTION_FIELD] : '',
            "image" => isset($options[SettingsTemp::IMAGE_URL_FIELD]) ? wp_get_attachment_url($options[SettingsTemp::IMAGE_URL_FIELD]) : '',
            "email" => isset($options[SettingsTemp::EMAIL_FIELD]) ? $options[SettingsTemp::EMAIL_FIELD] : '',
        );

        return file_put_contents($pathFile, json_encode($jsonInfo, JSON_UNESCAPED_SLASHES)) != false ? $urlFile : null;
    }

    public static function getJsonObject($jsonName) {
        $baseController = new BaseController();
        $json = file_get_contents($baseController->getJsonFolderPath() . $jsonName . '.json');
        return json_decode($json, true);
    }

    public static function getJsonUrl($jsonName) {
        $baseController = new BaseController();
        return $baseController->getJsonFolderUrl() . $jsonName . '.json';
    }

}
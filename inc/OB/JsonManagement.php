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

class JsonManagement extends BaseController {
    private $badgeInfo;

    public function __construct($badgeInfo) {
        parent::__construct();
        $this->badgeInfo = $badgeInfo;
    }

    public function createJsonFile($receiver) {
        $hashName = hash("sha256", $receiver . $this->badgeInfo);
        $hashFile = $hashName . ".json";
        $fileNamePath = parent::getJsonFolderPath() . $hashFile;
        $fileNameUrl = parent::getJsonFolderUrl() . $hashFile;
        $info = $this->getJsonBadgeInfo($hashFile);

        $assertion = array(
            "uid" => uniqid(),
            "recipient" => array("type" => "email", "identity" => $receiver, "hashed" => false),
            "issuedOn" => date('Y-m-d'),
            "badge" => $info,
            "verify" => array("type" => "hosted", "url" => $fileNameUrl)
        );

        return file_put_contents($fileNamePath, json_encode($assertion, JSON_UNESCAPED_SLASHES)) != false ? $hashName : null;
    }

    private function getJsonBadgeInfo() {
        $desc =
            "Field: " . $this->badgeInfo['field'] .
            ", Level: " . $this->badgeInfo['level'] .
            ", Description: " . $this->badgeInfo['description'] .
            ", Info: " . $this->badgeInfo['info'];

        $jsonInfo = array(
            '@context' => 'https://w3id.org/openbadges/v1',
            "name" => $this->badgeInfo['name'] . " " . $this->badgeInfo['field'],
            "description" => $desc,
            "image" => $this->badgeInfo['image'],
            "field" => $this->badgeInfo['field'],
            "level" => $this->badgeInfo['level'],
            "criteria" => "http://www.linkOfTheBadgeInfo.ex",
            "issuer" => ""
        );

        return $jsonInfo;
    }

    public static function getJsonObject($jsonName) {
        $baseController = new BaseController();
        $json = file_get_contents($baseController->getJsonFolderPath().$jsonName.'.json');
        return json_decode($json, true);
    }

    public static function getJsonUrl($jsonName) {
        $baseController = new BaseController();
        return $baseController->getJsonFolderUrl().$jsonName.'.json';
    }

}
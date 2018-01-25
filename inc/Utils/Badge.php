<?php
/**
 * Created by PhpStorm.
 * User: aleric
 * Date: 18/01/2018
 * Time: 09:49
 */

namespace Inc\Utils;


use Inc\Database\DbBadge;
use Inc\Database\DbUser;
use Templates\SettingsTemp;

class Badge {

    /**
     * Id of the badge.
     *
     * @var int
     */
    public $id = null;
    /**
     * Id of the user.
     *
     * @var int
     */
    public $idUser = null;

    /**
     * Id of the wordpress badge (custom-post-type).
     *
     * @var int
     */
    public $idBadge = null;

    /**
     * Id of the wordpress field (taxonomy).
     *
     * @var int
     */
    public $idField = null;

    /**
     * Id of the wordpress level (taxonomy).
     *
     * @var int
     */
    public $idLevel = null;

    /**
     * Id of the job-listing plugin Class (custom-post-type).
     *
     * @var int
     */
    public $idClass = null;

    /**
     * Id of the teacher (WP-user).
     *
     * @var int
     */
    public $idTeacher = null;

    /**
     * Role of the teacher in the moment that send the badge.
     *
     * @var string
     */
    public $teacherRole = null;

    /**
     * Date of the creation of the badge.
     *
     * @var string
     */
    public $creationDate = null;

    /**
     * Date of when the user get the badge.
     *
     * @var string
     */
    public $gotDate = null;

    /**
     * Date of when the user get the Mozilla Open Badge.
     *
     * @var string
     */
    public $gotMozillaDate = null;

    /**
     * Json name without extension.
     *
     * @var string
     */
    public $json = null;

    /**
     * Info that the teacher write when send the badge
     *
     * @var string
     */
    public $info = null;

    /**
     * Link relative to a page that certify the work that the
     * student did to earn the badge
     *
     * @var string
     */
    public $evidence = null;

    /**
     * Object of the user that received the badge
     *
     * @object WP_User
     */
    public $userWP = null;


    /**
     * Set the role of the teacher (user).
     *
     * @param $id
     *
     * @return mixed
     */
    public function setTeacherRole($id) {
        $teacher = get_user_by("id", $id);

        foreach ($teacher->roles as $role) {
            switch ($role) {
                case WPUser::ROLE_STUDENT:
                    $this->teacherRole = $role;
                    return $role;
                case WPUser::ROLE_TEACHER:
                    $this->teacherRole = $role;
                    return $role;
                case WPUser::ROLE_ACADEMY:
                    $this->teacherRole = $role;
                    return $role;
            }
        }
        $this->teacherRole = $teacher->roles[0];
    }


    /**
     * @return string
     */
    public function __toString() {
        return "Badge :" . $this->idBadge . ", Level: " . $this->idLevel . ", Field: " . $this->idField;
    }

    /**
     * @param $idDbBadge
     *
     * @return $this|bool
     */
    public function retrieveBadge($idDbBadge) {
        if ($badgeDb = DbBadge::getById($idDbBadge)) {
            $this->id = $badgeDb->id;
            $this->idUser = $badgeDb->idUser;
            $this->idBadge = $badgeDb->idBadge;
            $this->idField = $badgeDb->idField;
            $this->idLevel = $badgeDb->idLevel;
            $this->idClass = $badgeDb->idClass;
            $this->idTeacher = $badgeDb->idTeacher;
            $this->setTeacherRole($badgeDb->idTeacher);
            $this->creationDate = $badgeDb->creationDate;
            $this->gotDate = $badgeDb->gotDate;
            $this->gotMozillaDate = $badgeDb->gotMozillaDate;
            $this->json = $badgeDb->json;
            $this->info = $badgeDb->info;
            $this->evidence = $badgeDb->evidence;

            $user = DbUser::getById($this->idUser);
            $this->userWP = $user->idWP ? get_user_by("id", $user->idWP) : null;

            return $this;
        } else {
            return false;
        }
    }

    /**
     * Insert a badge in the database and retrieve its id.
     * If is already stored in the DB the function will anyway
     * return the its id.
     *
     * @param int    $idUser   id of the user.
     * @param string $jsonName json name of the file (without extension).
     *
     * @return int id of the OBF DB badge.
     */
    public function saveBadgeInDb($idUser, $jsonName) {
        $isOk = false;
        $dataBadge = array(
            'idUser' => $idUser,
            'idBadge' => $this->idBadge,
            'idField' => $this->idField,
            'idLevel' => $this->idLevel,
            'idClass' => $this->idClass,
            'idTeacher' => $this->idTeacher,
            'teacherRole' => $this->teacherRole,
            'creationDate' => $this->creationDate,
            'json' => $jsonName,
            'info' => $this->info,
            'evidence' => $this->evidence
        );

        foreach ($dataBadge as $item) {
            if ($item != "idClass") {
                $isOk = $item ? true : false;
            }
        }

        if ($isOk) {
            return DbBadge::insert($dataBadge);
        } else {
            return false;
        }
    }

    /**
     * Retrieves the URL for the Get-Badge-Page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param int $idDbBadge id of the specific badge in the Database OBF.
     *
     * @return string link to connect to the Get-Badge-Page.
     */
    public static function getLinkGetBadge($idDbBadge) {
        // Get badge page retrieved from the plugin setting
        $getBadgePage = get_post(
            SettingsTemp::getOption(SettingsTemp::FI_GET_BADGE)
        );

        $urlGetBadge = home_url('/' . $getBadgePage->post_name . '/');

        return $badgeLink = $urlGetBadge . "?v=$idDbBadge";
    }

}
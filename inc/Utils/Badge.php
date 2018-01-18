<?php
/**
 * Created by PhpStorm.
 * User: aleric
 * Date: 18/01/2018
 * Time: 09:49
 */

namespace inc\Utils;


use Inc\Base\WPUser;
use Inc\Database\DbUser;

class Badge {

    /**
     * Id of the user.
     *
     * @var null
     */
    private $idUser = null;

    /**
     * Id of the wordpress badge (custom-post-type).
     *
     * @var null
     */
    private $idBadge = null;

    /**
     * Id of the wordpress field (taxonomy).
     *
     * @var null
     */
    private $idField = null;

    /**
     * Id of the wordpress level (taxonomy).
     *
     * @var null
     */
    private $idLevel = null;

    /**
     * Id of the job-listing plugin Class (custom-post-type).
     *
     * @var null
     */
    private $idClass = null;

    /**
     * Id of the teacher (WP-user).
     *
     * @var null
     */
    private $idTeacher = null;

    /**
     * Role of the teacher in the moment that send the badge.
     *
     * @var null
     */
    private $teacherRole = null;

    /**
     * Date of the creation of the badge.
     *
     * @var null
     */
    private $creationDate = null;

    /**
     * Date of when the user get the badge.
     *
     * @var null
     */
    private $gotDate = null;

    /**
     * Date of when the user get the Mozilla Open Badge.
     *
     * @var null
     */
    private $gotMozillaDate = null;

    /**
     * Json name without extension.
     *
     * @var null
     */
    private $json = null;

    /**
     * Info that the teacher write when send the badge
     *
     * @var null
     */
    private $info = null;

    /**
     * Link relative to a page that certify the work that the
     * student did to earn the badge
     *
     * @var null
     */
    private $evidence = null;

    public function __toString() {
        return "" . print_r($this);
    }

    /**
     * Set the id of the user.
     *
     * @param $id
     */
    public function setIdUser($id) {
        $this->idUser = $id;
    }

    /**
     * Set the id of the wp badge.
     *
     * @param $id
     */
    public function setIdBadge($id) {
        $this->idBadge = $id;
    }

    /**
     * Set the id of the wp field.
     *
     * @param $id
     */
    public function setIdField($id) {
        $this->idField = $id;
    }

    /**
     * Set the id of the wp level.
     *
     * @param $id
     */
    public function setIdLevel($id) {
        $this->idLevel = $id;
    }

    /**
     * Set the id of the wp jbl class.
     *
     * @param $id
     */
    public function setIdClass($id) {
        $this->idClass = $id;
    }

    /**
     * Set the id of the teacher (user).
     *
     * @param $id
     */
    public function setIdTeacher($id) {
        $this->idTeacher = $id;
    }

    /**
     * Set the role of the teacher (user).
     *
     * @param $id
     */
    public function setTeacherRole($id) {
        $teacher = get_user_by("id", $id);

        foreach ($teacher->roles as $role) {
            switch ($role) {
                case WPUser::ROLE_STUDENT:
                    $this->teacherRole = $role;
                    break;
                case WPUser::ROLE_TEACHER:
                    $this->teacherRole = $role;
                    break;
                case WPUser::ROLE_ACADEMY:
                    $this->teacherRole = $role;
                    break;
            }
        }
    }

    /**
     * Set the creation date.
     *
     * @param $date
     */
    public function setCreationDate($date) {
        $this->creationDate = $date;
    }

    /**
     * Set the got date.
     *
     * @param $date
     */
    public function setGotDate($date) {
        $this->gotDate = $date;

    }

    /**
     * Set the got Mozilla Open Badge date.
     *
     * @param $date
     */
    public function setGotMozillaDate($date) {
        $this->gotMozillaDate = $date;

    }

    /**
     * Set the name of the Json file (without extension).
     *
     * @param $json
     */
    public function setJson($json) {
        $this->json = $json;

    }

    /**
     * Set the info of the badge that the teacher wrote.
     *
     * @param $info
     */
    public function setInfo($info) {
        $this->info = $info;

    }

    /**
     * Get the evidence.
     *
     * @param $evidence
     */
    public function setEvidence($evidence) {
        $this->evidence = $evidence;
    }


    /**
     * Get the id of the user.
     */
    /**
     * @return null|int the id, otherwise null if not set.
     */
    public function getIdUser() {
        return $this->idUser;
    }

    /**
     * Get the id of the wp badge.
     *
     * @return null|int the id, otherwise null if not set.
     */
    public function getIdBadge() {
        return $this->idBadge;
    }

    /**
     * Get the id of the wp field.
     *
     * @return null|int the id, otherwise null if not set.
     */
    public function getIdField() {
        return $this->idField;
    }

    /**
     * Get the id of the wp level.
     *
     * @return null|int the id, otherwise null if not set.
     */
    public function getIdLevel() {
        return $this->idLevel;
    }

    /**
     * Get the id of the wp jbl class.
     *
     * @return null|int the id, otherwise null if not set.
     */
    public function getIdClass() {
        return $this->idClass;
    }

    /**
     * Get the id of the teacher (user).
     *
     * @return null|int the id, otherwise null if not set.
     */
    public function getIdTeacher() {
        return $this->idTeacher;
    }

    /**
     * Get the role of the teacher (user).
     *
     * @return null|string the role, otherwise null if not set.
     */
    public function getTeacherRole() {
        return $this->teacherRole;
    }

    /**
     * Get the creation date.
     *
     * @return null|string the date, otherwise null if not set.
     */
    public function getCreationDate() {
        return $this->creationDate;
    }

    /**
     * Get the got date.
     *
     * @return null|string the date, otherwise null if not set.
     */
    public function getGotDate() {
        return $this->gotDate;

    }

    /**
     * Get the got Mozilla Open Badge date.
     *
     * @return null|string the date, otherwise null if not set.
     */
    public function getGotMozillaDate() {
        return $this->gotMozillaDate;

    }

    /**
     * Get the name of the Json file (without extension).
     *
     * @return null|string the name, otherwise null if not set.
     */
    public function getJson() {
        return $this->json;

    }

    /**
     * Get the info of the badge that the teacher wrote.
     *
     * @return null|string the info, otherwise null if not set.
     */
    public function getInfo() {
        return $this->info;

    }

    /**
     * Get the evidence.
     *
     * @return null|string the link, otherwise null if not set.
     */
    public function getEvidence() {
        return $this->evidence;
    }


}
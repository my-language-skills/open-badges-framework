<?php

namespace Inc\Database;

/**
 * That class manage the database table for the badges
 * that are sent.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class DbBadge extends DbModel {
    const ER_DONT_EXIST = "The badge don't exist.\n";
    const ER_DUPLICATE = "The badge is duplicate.\n";
    const ER_WRONG_FIELDS = "Wrong fields passed in the array.\n";
    const ER_ERROR = "There's an error in the database.\n";
    // database name
    static $tableName = 'obf_badge';

    /**
     * In that function, called from the Init class,
     * permit to create the db table.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function register() {
        global $wpdb;
        $userTable = DbUser::getTableName();
        $charset_collate = $wpdb->get_charset_collate();
        $installed_version = get_option(self::DB_NAME_VERSION);
        if ($installed_version == self::DB_VERSION) {
            $sql = "CREATE TABLE IF NOT EXISTS " . $this->getTableName() . " (
            id int(6) UNSIGNED AUTO_INCREMENT,
            idUser int(6) UNSIGNED NOT NULL,
            idBadge mediumint(9) NOT NULL,
            idField mediumint(9) NOT NULL,
            idLevel mediumint(9) NOT NULL,
            idClass mediumint(9),
            idTeacher mediumint(9) NOT NULL,
            teacherRole varchar(50) NOT NULL,
            creationDate datetime NOT NULL,
            gotDate datetime,
            gotMozillaDate datetime,
            json varchar(64) NOT NULL,
            info text,
            evidence varchar(1500),
            PRIMARY KEY (id),
            UNIQUE KEY  (idUser, idBadge, idField, idLevel),
            FOREIGN KEY (idUser) REFERENCES ". $userTable . "(id)
        ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            update_option(self::DB_NAME_VERSION, self::DB_VERSION);
        }
    }

    /**
     * Get a badge/s by the id.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data list of ids
     *
     * @return bool|Object of the badge or null if not exist.
     */
    /**
     * @param $id
     *
     * @return bool
     */
    public static function getById($id) {

        $id = array('id' => $id);
        if ($badges = parent::get($id)) {
            $badge = $badges[0]; //[0] -> permit to extract the first array (badge)
        }

        return !empty($badge) ? $badge : false;
    }

    /**
     * Get a badge by the ids.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data {
     *                    information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        idBadge          Badge Id.
     * @type string        idField          Field Id.
     * @type string        levelId          Level Id.
     *
     * @return array|bool|null|object|string the object badge, false if don't exist and
     *                                       constant string (@const ER_WRONG_FIELDS) if
     *                                       there are wrong field.
     */
    public static function getByIds(array $data) {
        $rightKeys = array(
            'userEmail',
            'idBadge',
            'idField',
            'levelId',
        );
        if (!self::checkFields($rightKeys, $data)) {
            return self::ER_WRONG_FIELDS;
        } else {
            $badge = parent::get($data);
            return !empty($badge) ? $badge : false;
        }
    }

    /**
     * Get all the badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return array|null|object array of object (badges), nul if not exist
     */
    public static function getAll() {
        return parent::get();
    }

    /**
     * Get the keys of the badge table.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return array keys
     */
    public static function getKeys() {
        $data = parent::get();
        return $data ? $data[0] : array();
    }

    /**
     * Insert a badge.
     *
     * @author        Alessandro RICCARDI
     * @since         x.x.x
     *
     * @param array $data {
     *                    information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        idBadge          Badge Id.
     * @type string        idField          Field Id.
     * @type string        levelId          Level Id.
     * @type string        classId          Class Id.
     * @type string        teacherId        Teacher Id.
     * @type string        roleSlug         Role of the teacher.
     * @type string        dateCreation     Date of the creation of the badge.
     * @type string        json             Json file name.
     * @type string        info             Information wrote from the teacher.
     * }
     *
     * @return  bool|false|int|string true if it's inserted, false otherwise and
     * @const         ER_DUPLICATE duplicate row.
     */
    public static function insert(array $data) {
        $rightKeys = array(
            'userEmail',
            'idBadge',
            'idField',
            'levelId',
            'classId',
            'teacherId',
            'roleSlug',
            'dateCreation',
            'json',
            'info'
        );

        //Check if the $data array contain the right information (keys)
        if (!self::checkFields($rightKeys, $data)) {
            return false;
        }

        $dataGetById = array(
            'userEmail' => $data['userEmail'],
            'idBadge' => $data['idBadge'],
            'idField' => $data['idField'],
            'levelId' => $data['levelId'],
        );

        if (self::getByIds($dataGetById)) {
            return self::ER_DUPLICATE;
        }

        return parent::insert($data) === false ? false : true;
    }

    /**
     * Update a badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data  Data that we want to update.
     *
     * @param array $where {
     *                     information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        idBadge          Badge Id.
     * @type string        idField          Field Id.
     * @type string        levelId          Level Id.
     * }
     *
     * @return bool|string|void true if everything is good, false, if other
     *                           errors and ER_DONT_EXIST if don't exist the badge
     */

    public static function update(array $data, array $where) {

        $dataGetById = array(
            'userEmail' => $where['userEmail'],
            'idBadge' => $where['idBadge'],
            'idField' => $where['idField'],
            'levelId' => $where['levelId'],
        );

        if (!self::getByIds($dataGetById)) {
            return self::ER_DONT_EXIST;
        }

        if (parent::update($data, $where)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a badge by own id.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data the number id of the badge
     *
     * @return bool true if everything ok, false if errors, and a number that
     *                        will be always 1 because is meaning the number of row
     *                        affected in the database.
     */
    /**
     * @param array $data
     *
     * @return bool|false|int
     */
    /**
     * @param array $data
     *
     * @return bool
     */
    public static function deleteById(array $data) {
        $rightKeys = array(
            'id',
        );
        if (!self::checkFields($rightKeys, $data)) {
            return false;
        }
        return parent::delete($data);

    }

    /**
     * Check if the array $data contain all the keys that are inside
     * the array $rightKeys.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $rightKeys
     * @param array $data
     *
     * @return bool true if everything ok, false if errors.
     */
    private static function checkFields(array $rightKeys, array $data) {
        $rightDim = count($rightKeys);
        $count = 0;

        foreach ($data as $key => $value) {
            if (!in_array($key, $rightKeys)) {
                return null;
            }
            $count++;
        }

        if ($rightDim !== $count) {
            return null;
        }
        return true;
    }

    /**
     * Permit to understand if the badge is got.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data {
     *                    information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        idBadge          Badge Id.
     * @type string        idField          Field Id.
     * @type string        levelId          Level Id.
     * }
     *
     * @return bool|string true if is got, false is not,
     * @const       ER_DONT_EXIST if the badge do no exist
     */
    public static function isGot(array $data) {
        $rightKeys = array(
            'userEmail',
            'idBadge',
            'idField',
            'levelId',
        );

        if (!self::checkFields($rightKeys, $data)) {
            return self::ER_WRONG_FIELDS;
        } else {
            $getValue = parent::get($data);

            if (empty($getValue)) {
                self::ER_DONT_EXIST;
            } else {
                return $getValue[0]->getDate ? true : false;
            }
        }
    }

    /**
     * Permit to understand if the badge is got in the Mozilla Open Badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data {
     *                    information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        idBadge          Badge Id.
     * @type string        idField          Field Id.
     * @type string        levelId          Level Id.
     * }
     *
     * @return bool|string true if is got, false is not, @const ER_DONT_EXIST if
     *                     the badge do no exist, @const ER_DONT_EXIST if the badge
     *                     doesn't exist, @const ER_WRONG_FIELDS if there are wrong
     *                     field.
     */
    public static function isGotMOB(array $data) {
        $rightKeys = array(
            'userEmail',
            'idBadge',
            'idField',
            'levelId',
        );

        if (!self::checkFields($rightKeys, $data)) {
            return self::ER_WRONG_FIELDS;
        } else {
            $getValue = parent::get($data);

            if (empty($getValue)) {
                self::ER_DONT_EXIST;
            } else {
                return $getValue[0]->getMobDate ? true : false;
            }
        }
    }

    /**
     * Permit to understand if the badge is got in the Mozilla Open Badge.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $where information about the badge that we want to update.
     * @param bool  $mob   true if I want to set the badge for MOB and for the current site as "taken";
     *                     false if we want to set as "taken" only for the current website.
     *
     * @return bool|string true if everything is ok, @const ER_DONT_EXIST if the row doesn't exist,
     * @const       ER_ERROR if there's other kind of error.
     */
    public static function setBadgeGot($where, $mob = false) {
        //
        $data = $mob === 'true' ?
            array(
                'getDate' => self::now(),
                'getMobDate' => self::now()
            )
            :
            array(
                'getDate' => self::now(),
            );

        if ($res = self::update($data, $where)) {
            return true;
        } else if ($res == self::ER_DONT_EXIST) {
            return self::ER_DONT_EXIST;
        } else if (!$res) {
            return self::ER_ERROR;
        }
    }

    /**
     * Permit retrieve the number of badges got in the past.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return mixed return the number of badges that are got.
     */
    public static function getNumGot() {
        global $wpdb;
        $query = "SELECT COUNT(*) AS num FROM " . self::getTableName() . " WHERE getDate IS NOT NULL";

        return $wpdb->get_results($query)[0]->num;
    }

    /**
     * Permit retrieve the number of badges got as a Mozilla
     * Open Badge in the past.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return mixed return the number of badges that are got.
     */
    public static function getNumGotMob() {
        global $wpdb;
        $query = "SELECT COUNT(*) AS num FROM " . self::getTableName() . " WHERE getMobDate IS NOT NULL";

        return $wpdb->get_results($query)[0]->num;
    }
}
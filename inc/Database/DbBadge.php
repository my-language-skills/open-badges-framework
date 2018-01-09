<?php

namespace Inc\Database;

/**
 * That class manage the database table for the badges
 * that are sent.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
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
     * permit to create the database.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function register() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $installed_version = get_option(self::DB_NAME_VERSION);
        if ($installed_version !== self::DB_VERSION) {
            $sql = "CREATE TABLE " . $this->getTableName() . " (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userEmail varchar(180) NOT NULL,
            badgeId mediumint(9) NOT NULL,
            fieldId mediumint(9) NOT NULL,
            levelId mediumint(9) NOT NULL,
            classId mediumint(9),
            teacherId mediumint(9) NOT NULL,
            roleSlug varchar(50) NOT NULL,
            dateCreation datetime NOT NULL,
            getDate datetime,
            getMobDate datetime,
            json varchar(64) NOT NULL,
            info text,
            evidence varchar(1500),
            UNIQUE KEY  (userEmail, badgeId, fieldId, levelId)
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
     * @since       1.0.0
     *
     * @param array $data list of ids
     *
     * @return object the badge | false, if don't exist.
     */
    public static function getById($id) {

        $id = array('id' => $id);
        $badges = parent::get($id)[0];    //[0] -> permit to extract the first array (badge)

        return !empty($badges) ? $badges : false;
    }

    /**
     * Get a badge by the ids.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $data {
     *                    Array of information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        badgeId          Badge Id.
     * @type string        fieldId          Field Id.
     * @type string        levelId          Level Id.
     *
     * @return the badge | false, if don't exist. | @const ER_WRONG_FIELDS if there are wrong field
     */
    public static function getByIds(array $data) {
        $rightKeys = array(
            'userEmail',
            'badgeId',
            'fieldId',
            'levelId',
        );
        if (!self::checkFields($rightKeys, $data)) {
            return self::ER_WRONG_FIELDS;
        } else {
            $getValue = parent::get($data);
            return !empty($getValue) ? $getValue : false;
        }
    }

    /**
     * Get badge/s.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $data list of ids
     *
     * @return
     */
    public static function get(array $data = null) {
        return parent::get($data);
    }

    /**
     * Get all the badge.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @return get function from the parent class
     */
    public static function getAll() {
        return parent::get();
    }

    /**
     * Get all the badge.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @return array of badges
     */
    public static function getKeys() {
        $data = parent::get();
        return $data ? $data[0] : array();
    }

    /**
     * Insert a badge.
     *
     * @author        Alessandro RICCARDI
     * @since         1.0.0
     *
     * @param array $data {
     *                    Array of information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        badgeId          Badge Id.
     * @type string        fieldId          Field Id.
     * @type string        levelId          Level Id.
     * @type string        classId          Class Id.
     * @type string        teacherId        Teacher Id.
     * @type string        roleSlug         Role of the teacher.
     * @type string        dateCreation     Date of the creation of the badge.
     * @type string        json             Json file name.
     * @type string        info             Information wrote from the teacher.
     * }
     *
     * @return true | @const ER_DUPLICATE duplicate row | false, if errors.
     */
    public static function insert(array $data) {
        $rightKeys = array(
            'userEmail',
            'badgeId',
            'fieldId',
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
            'badgeId' => $data['badgeId'],
            'fieldId' => $data['fieldId'],
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
     * @since       1.0.0
     *
     * @param array $data  Data that we want to update.
     *
     * @param array $where {
     *                     Array of information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        badgeId          Badge Id.
     * @type string        fieldId          Field Id.
     * @type string        levelId          Level Id.
     * }
     *
     * @return  bool    true if everything is good
     *          string  ER_DONT_EXIST if don't exist the badge
     *          bool    false, if other errors.
     */
    public static function update(array $data, array $where) {

        $dataGetById = array(
            'userEmail' => $where['userEmail'],
            'badgeId' => $where['badgeId'],
            'fieldId' => $where['fieldId'],
            'levelId' => $where['levelId'],
        );

        if (!self::getByIds($dataGetById)) {
            return self::ER_DONT_EXIST;
        }

        return parent::update($data, $where) === false ? false : true;
    }

    /**
     * Delete a badge by own id.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $data the number id of the badge
     *
     * @return  bool    true if everything ok
     *                  false if errors.
     */
    public static function deleteById(array $data) {
        $rightKeys = array(
            'id',
        );
        if (!self::checkFields($rightKeys, $data)) {
            return false;
        } else {
            return parent::delete($data);
        }
    }

    /**
     * Delete a badge.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $data {
     *                    Array of information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        badgeId          Badge Id.
     * @type string        fieldId          Field Id.
     * @type string        levelId          Level Id.
     *
     * @return  bool    true if everything ok
     *                  false if errors.
     */
    public static function deleteByIds(array $data) {
        $rightKeys = array(
            'userEmail',
            'badgeId',
            'fieldId',
            'levelId',
        );
        if (!self::checkFields($rightKeys, $data)) {
            return false;
        } else {
            return parent::deleteByIds($data);
        }
    }

    /**
     * Check that the array $data contain all the keys
     * that are inside the array $rightKeys.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $rightKeys
     * @param array $data
     *
     * @return  bool    true if everything ok
     *                  false if errors.
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
     * @since       1.0.0
     *
     * @param array $data {
     *                    Array of information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        badgeId          Badge Id.
     * @type string        fieldId          Field Id.
     * @type string        levelId          Level Id.
     * }
     *
     * @return the badge | false, if don't exist. | @const ER_DONT_EXIST if the badge doesn't exist |
     *         ER_WRONG_FIELDS if there are wrong field
     */
    public static function isGot(array $data) {
        $rightKeys = array(
            'userEmail',
            'badgeId',
            'fieldId',
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
     * @since       1.0.0
     *
     * @param array $data {
     *                    Array of information about a specific badge.
     *
     * @type string        userEmail        Email.
     * @type string        badgeId          Badge Id.
     * @type string        fieldId          Field Id.
     * @type string        levelId          Level Id.
     * }
     *
     * @return          the badge
     *         bool     false, if don't exist.
     *         const    ER_DONT_EXIST if the badge doesn't exist
     *         const    ER_WRONG_FIELDS if there are wrong field
     */
    public static function isGotMOB(array $data) {
        $rightKeys = array(
            'userEmail',
            'badgeId',
            'fieldId',
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
     * @since       1.0.0
     *
     * @param array $where information about the badge that we want to update.
     * @param bool  $mob   true if I want to set the badge for MOB and for the current site as "taken";
     *                     false if we want to set as "taken" only for the current website.
     *
     * @return true if everything is ok;
     *         ER_DONT_EXIST if the row doesn't exist;
     *         ER_ERROR if there's other kind of error.
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
     * @since       1.0.0
     *
     * @return return the number of badges that are got.
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
     * @since       1.0.0
     *
     * @return return the number of badges that are got.
     */
    public static function getNumGotMob() {
        global $wpdb;
        $query = "SELECT COUNT(*) AS num FROM " . self::getTableName() . " WHERE getMobDate IS NOT NULL";

        return $wpdb->get_results($query)[0]->num;
    }
}
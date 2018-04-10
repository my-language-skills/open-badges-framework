<?php

namespace Inc\Database;

/**
 * That class manage the database table for the badges
 * that are sent.
 *
 * @author      @AleRiccardi
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
     * Always loaded from the Init class and permit to create
     * the table if not exist.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     */
    public function register(){
        // Create table if not exist - not used
        //$this->createTable();
    }

    /**
     * In that function, called from the Init class,
     * permit to create the db table.
     *
	 * We have added "on cascade" at the foreign key(idUser),that means that when a user is deleted
	 * from the obf_user table,the badges that are related to him are also deleted from the
	 * obf_badge table.
	 *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return array Strings containing the results of the various
     *               update queries (dbDelta() function).
     */
    public function createTable() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        # =======
        global $wpdb;
        $userTable = DbUser::getTableName();
        $charset_collate = $wpdb->get_charset_collate();
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
            FOREIGN KEY (idUser) REFERENCES " . $userTable . "(id) ON DELETE CASCADE
        ) $charset_collate;";
        return dbDelta($sql);
    }

    /**
     * Get a badge/s by the id.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param int $id id of the row
     *
     * @return bool|Object of the badge or null if not exist.
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
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param array $where {
     *                     information about a specific badge.
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
    public static function getByIds(array $where) {
        $rightKeys = array(
            'idUser',
            'idBadge',
            'idField',
            'idLevel',
        );

        if (!self::checkFields($rightKeys, $where)) {
            return false;
        } else {
            if ($badges = parent::get($where)) {
                $badge = $badges[0]; //[0] -> permit to extract the first array (badge)
            }
            return !empty($badge) ? $badge : false;
        }
    }

    /**
     * Get all the badge.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return array|null|object array of object (badges), nul if not exist
     */
    public static function getAll() {
        return parent::get();
    }

    /**
     * Get the keys of the badge table.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * @author        @AleRiccardi
     * @since         1.0.0
     *
     * @param array $data the information to insert
     *
     * @return false|int The last id inserted, false on error.
     */
    public static function insert(array $data) {
        $dataGetById = array(
            'idUser' => $data['idUser'],
            'idBadge' => $data['idBadge'],
            'idField' => $data['idField'],
            'idLevel' => $data['idLevel'],
        );

        if ($badge = self::getByIds($dataGetById)) {
            return $badge->id;
        }

        return parent::insert($data);
    }


    /**
     * Delete a badge by own id.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param array $data the number id of the badge
     *
     * @return bool true if everything ok, false if errors.
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
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * Permit to understand if the badge is got in the Mozilla Open Badge.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
        $getValue = self::getByIds($data);

        if (empty($getValue)) {
            return null;
        } else {
            return $getValue->gotMozillaDate ? true : false;
        }
    }

    /**
     * Permit to understand if the badge is got in the Mozilla Open Badge.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param array $where   information about the badge that we want to update.
     * @param bool  $mozilla true if I want to set the badge for MOB and for the current site as "taken";
     *                       false if we want to set as "taken" only for the current website.
     *
     * @return bool|string true if everything is ok, @const ER_DONT_EXIST if the row doesn't exist,
     * @const       ER_ERROR if there's other kind of error.
     */
    public static function setBadgeGot($where, $mozilla = false) {
        //
        if ($mozilla) {
            $data = array(
                'gotDate' => self::now(),
                'gotMozillaDate' => self::now()
            );
        } else {
            $data = array(
                'gotDate' => self::now(),
            );
        }

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
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return mixed return the number of badges that are got.
     */
    public static function getNumGot() {
        global $wpdb;
        $query = "SELECT COUNT(*) AS num FROM " . self::getTableName() . " WHERE gotDate IS NOT NULL";

        return $wpdb->get_results($query)[0]->num;
    }

    /**
     * Permit retrieve the number of badges got as a Mozilla
     * Open Badge in the past.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return mixed return the number of badges that are got.
     */
    public static function getNumGotMob() {
        global $wpdb;
        $query = "SELECT COUNT(*) AS num FROM " . self::getTableName() . " WHERE gotMozillaDate IS NOT NULL";

        return $wpdb->get_results($query)[0]->num;
    }
}
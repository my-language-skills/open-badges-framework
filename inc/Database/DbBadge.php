<?php
/**
 * Created by PhpStorm.
 * User: aleric
 * Date: 27/11/2017
 * Time: 11:52
 */

namespace Inc\Database;

class DbBadge extends DbModel {
    const ER_DONT_EXIST = "The badge don't exist.\n";
    const ER_DUPLICATE = "The badge is duplicate.\n";
    const ER_WRONG_FIELDS = "Wrong fields passed in the array.\n";
    static $tableName = 'obf_badge';

    public function register() {
        global $wpdb;
        $wpdb->hide_errors();

        $charset_collate = $wpdb->get_charset_collate();
        $installed_version = get_option(self::DB_NAME_VERSION);

        if ($installed_version !== self::DB_VERSION) {
            $sql = "CREATE TABLE " . $this->getTableName() . " (
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
            PRIMARY KEY  (userEmail, badgeId, fieldId, levelId)
        ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            update_option(self::DB_NAME_VERSION, self::DB_VERSION);
        }
    }

    /**
     * Get a badge by the ids
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data {
     *                    Optional. Array or query string of arguments for delete a badge
     *
     * @type string        userEmail        Text.
     * @type string        badgeId          Text.
     * @type string        fieldId          Text.
     * @type string        levelId          Text.
     *
     * @return the badge | false, if don't exist. | @const ER_WRONG_FIELDS if there are wrong field
     */
    public static function getById(array $data) {
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
     * Get all the badge (Warning: never tested)
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return the badges
     */
    public static function getAll() {
        return parent::get();
    }

    /**
     * Insert a badge
     *
     * @author        Alessandro RICCARDI
     * @since         x.x.x
     *
     * @param array $data {
     *                    Optional. Array or query string of arguments for insert a badge.
     *
     * @type string        userEmail        Text.
     * @type string        badgeId          Text.
     * @type string        fieldId          Text.
     * @type string        levelId          Text.
     * @type string        classId          Text.
     * @type string        teacherId        Text.
     * @type string        roleSlug         Text.
     * @type string        dateCreation     Text.
     * @type string        json             Text.
     * @type string        info             Text.
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

        if (self::getById($dataGetById)) {
            return self::ER_DUPLICATE;
        }

        return parent::insert($data) === false ? false : true;
    }

    /**
     * Update a badge
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data  {
     *                     Optional. Array or query string of arguments for insert a badge.
     *
     * @type string        userEmail        Text.
     * @type string        badgeId          Text.
     * @type string        fieldId          Text.
     * @type string        levelId          Text.
     *
     * @param array $where , the field that you want to update
     *
     * @return true if everything is good | @const ER_DONT_EXIST if don't exist the badge | false, if other errors.
     */
    public static function update(array $data, array $where) {

        $dataGetById = array(
            'userEmail' => $where['userEmail'],
            'badgeId' => $where['badgeId'],
            'fieldId' => $where['fieldId'],
            'levelId' => $where['levelId'],
        );

        if (!self::getById($dataGetById)) {
            return self::ER_DONT_EXIST;
        }

        return parent::update($data, $where) === false ? false : true;
    }

    /**
     * Delete a badge
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data {
     *                    Optional. Array or query string of arguments for delete a badge
     *
     * @type string        userEmail        Text.
     * @type string        badgeId          Text.
     * @type string        fieldId          Text.
     * @type string        levelId          Text.
     *
     * @return true|false, if errors.
     */
    public static function delete(array $data) {
        $rightKeys = array(
            'userEmail',
            'badgeId',
            'fieldId',
            'levelId',
        );
        if (!self::checkFields($rightKeys, $data)) {
            return false;
        } else {
            return parent::delete($data);
        }
    }

    /**
     * Check that the array $data contain all the keys
     * that are inside the array $rightKeys.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $rightKeys
     * @param array $data
     *
     * @return true|false, if errors.
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
     *                    Optional. Array or query string of arguments for delete a badge
     *
     * @type string        userEmail        Text.
     * @type string        badgeId          Text.
     * @type string        fieldId          Text.
     * @type string        levelId          Text.
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
     * Permit to understand if the badge is got.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data {
     *                    Optional. Array or query string of arguments for delete a badge
     *
     * @type string        userEmail        Text.
     * @type string        badgeId          Text.
     * @type string        fieldId          Text.
     * @type string        levelId          Text.
     *
     * @return the badge | false, if don't exist. | @const ER_DONT_EXIST if the badge doesn't exist |
     *         ER_WRONG_FIELDS if there are wrong field
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

}
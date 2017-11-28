<?php
/**
 * Created by PhpStorm.
 * User: aleric
 * Date: 27/11/2017
 * Time: 11:52
 */

namespace Inc\Database;

class DbBadge extends DbModel {
    static $tableName = 'obf_badge';

    public function register() {
        global $wpdb;

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
            dateCreation date NOT NULL,
            getDate date,
            getMobDate date,
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

    public static function get(array $data = null) {
        return parent::get($data);
    }

    public static function getAll() {
        return parent::get();
    }

    /**
     * Insert a badge
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data           {
     *     Optional. Array or query string of arguments for insert a badge.
     *
     *     @type string        userEmail        Text.
     *     @type string        badgeId          Text.
     *     @type string        fieldId          Text.
     *     @type string        levelId          Text.
     *     @type string        classId          Text.
     *     @type string        teacherId        Text.
     *     @type string        roleSlug         Text.
     *     @type string        dateCreation     Text.
     *     @type string        json             Text.
     *     @type string        info             Text.
     *
     * @return true|false, if errors.
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

        if(!self::checkKeys($rightKeys, $data)) {
            return false;
        } else {
            return parent::insert($data) === false ? false : true;
        }
    }

    /**
     * Delete a badge
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data           {
     *     Optional. Array or query string of arguments for delete a badge
     *
     *     @type string        userEmail        Text.
     *     @type string        badgeId          Text.
     *     @type string        fieldId          Text.
     *     @type string        levelId          Text.
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
        if(!self::checkKeys($rightKeys, $data)) {
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
    private static function checkKeys(array $rightKeys, array $data){
        $rightDim = count($rightKeys);
        $count = 0;

        foreach($data as $key => $value) {
            if(!array_key_exists($key, $rightKeys)){
                return null;
            }
            $count ++;
        }

        if ($rightDim !== $count) {
            return null;
        }

        return true;
    }

}
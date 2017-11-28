<?php
/**
 * The DbModel Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace Inc\Database;

abstract class DbModel {
    static $primaryKey = 'id';

    protected static function getTableName() {
        global $wpdb;
        $tableName = strtolower( get_called_class() );
        $tableName = str_replace( 'obf_DbModel', 'DbModel', $tableName );
        return $wpdb->prefix . $tableName;
    }

    private static function fetch_sql($value) {
        global $wpdb;
        $sql = sprintf('SELECT * FROM %s WHERE %s = %%s', self::getTableName(), static::$primaryKey);
        return $wpdb->prepare($sql, $value);
    }

    public static function get($value) {
        global $wpdb;
        return $wpdb->get_row(self::fetch_sql($value));
    }

    public static function insert($data) {
        global $wpdb;
        $wpdb->insert(self::getTableName(), $data);
    }

    public static function update($data, $where) {
        global $wpdb;
        $wpdb->update(self::getTableName(), $data, $where);
    }

    public static function delete($value) {
        global $wpdb;
        $sql = sprintf('DELETE FROM %s WHERE %s = %%s', self::getTableName(), static::$primaryKey);
        return $wpdb->query($wpdb->prepare($sql, $value));
    }

    public static function insertId() {
        global $wpdb;
        return $wpdb->insert_id;
    }

    public static function timeToDate($time) {
        return gmdate('Y-m-d H:i:s', $time);
    }

    public static function now() {
        return self::timeToDate(time());
    }

    public static function dateToTime($date) {
        return strtotime($date . ' GMT');
    }
}
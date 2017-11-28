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

class DbModel {
    const DB_VERSION = '1.0.0';
    const DB_NAME_VERSION = 'OBF_db_version';
    static $tableName = "obf_model";

    protected static function getTableName() {
        global $wpdb;
        $tableName = static::$tableName;
        return $wpdb->prefix . $tableName;
    }

    public static function fetchSql($type = "UPDATE", array $data) {
        if ($type == "UPDATE") {
            $sql = "SELECT * FROM " . self::getTableName();
        } else if ($type == "DELETE") {
            $sql = "DELETE FROM " . self::getTableName();
        }

        if ($data == null) {
            return $sql;
        } else {

            $length = count($data) - 1;
            $sql .= " WHERE ";

            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $sql .= "$key = '$value'";
                } else {
                    $sql .= "$key = $value";
                }

                //Add 'AND' only if is not the last field
                if (array_search($key, array_keys($data)) !== $length) {
                    $sql .= " AND ";
                }
            }
            return $sql;
        }
    }

    public static function get(array $data = null) {
        global $wpdb;
        return $wpdb->get_results(self::fetchSql($data));
    }

    public static function insert(array $data) {
        global $wpdb;
        return $wpdb->insert(self::getTableName(), $data);
    }

    public static function update(array $data, array $where) {
        global $wpdb;
        $wpdb->update(self::getTableName(), $data, $where);
    }

    public static function delete(array $data) {
        global $wpdb;
        echo $ret = self::fetchSql('DELETE',$data);
        return $wpdb->query($ret);
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
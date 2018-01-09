<?php

namespace Inc\Database;

/**
 * The default class that manage the database in the
 * most basic way, without control and with the only
 * intention to inject the queries.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class DbModel {
    const DB_VERSION = '1.0.0';
    const DB_NAME_VERSION = 'OBF_db_version';
    // default database name
    static $tableName = "obf_model";

    /**
     * Retrieve the name of the database with included
     * also the prefix.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @return string the name.
     */
    protected static function getTableName() {
        global $wpdb;
        $tableName = static::$tableName;
        return $wpdb->prefix . $tableName;
    }

    /**
     * Creation of the query and injection.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param string $type of query that we want to inject
     *
     * @param array  $data list of information that will be
     *                     placed after the expression WHERE
     *
     * @return string the name.
     */
    public static function fetchSql($type, $data) {
        if ($type == "SELECT") {
            $sql = "SELECT * FROM " . self::getTableName();
        } else if ($type == "UPDATE") {
            $sql = "UPDATE * FROM " . self::getTableName();
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

    /**
     * Get a badges.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $data  list of information that will be
     *                     placed after the expression WHERE
     *
     */
    public static function get(array $data = null) {
        global $wpdb;
        return $wpdb->get_results(self::fetchSql("SELECT", $data));;
    }

    /**
     * Insert a badge.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $data  list of information that will be
     *                     placed after the expression WHERE
     *
     */
    public static function insert(array $data) {
        global $wpdb;
        $res = $wpdb->insert(self::getTableName(), $data);
        return $res;
    }

    /**
     * Update a badge.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $data  list of information contain the fields as a key and
     *                     the values as a value that we want to update
     *
     * @param array $where list of information that identify the specific badge
     *
     */
    public static function update(array $data, array $where) {
        global $wpdb;
        $wpdb->update(self::getTableName(), $data, $where);
    }

    /**
     * Delete the rows that match with the information tha we
     * passed throw param.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $data  list of information that will be
     *                     placed after the expression WHERE
     *
     */
    public static function delete(array $data) {
        global $wpdb;
        echo $ret = self::fetchSql('DELETE', $data);
        return $wpdb->query($ret);
    }

    /**
     * Get the time now.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     */
    public static function now() {
        return date('Y-m-d H:i:s');
    }

}
<?php

namespace Inc\Database;

/**
 * The default class that manage the database in the
 * most basic way, without control and with the only
 * intention to inject the queries.
 *
 * @author      @AleRiccardi
 * @since       x.x.x
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
     * @author      @AleRiccardi
     * @since       x.x.x
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
     * @author      @AleRiccardi
     * @since       x.x.x
     *
     * @param string $type of query that we want to create.
     *
     * @param array  $data list of information that will be
     *                     placed after the expression WHERE.
     *
     * @return string sql query.
     */
    public static function fetchSql($type, $data) {
        $sql = "";
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
     * Get badges.
     *
     * @author      @AleRiccardi
     * @since       x.x.x
     *
     * @param array|null $data  list of information that will be
     *                          placed after the expression WHERE.
     *
     * @return array|null|object array of object, null otherwise.
     */
    public static function get(array $data = null) {
        global $wpdb;
        return $wpdb->get_results(self::fetchSql("SELECT", $data));;
    }

    /**
     * Get a badges.
     *
     * @author      @AleRiccardi
     * @since       x.x.x
     *
     * @param array|null $data  list of information that will be
     *                          placed after the expression WHERE
     *
     * @return object|null Object of the information, null if error
     */
    public static function getSingle(array $data) {
        global $wpdb;
        $res = $wpdb->get_results(self::fetchSql("SELECT", $data));
        return $res ? $res[0] : null;
    }

    /**
     * Insert a badge and retrieve the id.
     *
     * @author      @AleRiccardi
     * @since       x.x.x
     *
     * @param array $data  list of information that will be
     *                     placed after the expression WHERE
     *
     * @return int|false The last id inserted, or false on error.
     */
    public static function insert(array $data) {
        global $wpdb;
        if ($wpdb->insert(self::getTableName(), $data)) {
            return $wpdb->insert_id;
        } else {
            return false;
        }
    }

    /**
     * Update a badge.
     *
     * @author      @AleRiccardi
     * @since       x.x.x
     *
     * @param array $data  list of information contain the fields as a key and
     *                     the values as a value that we want to update
     *
     * @param array $where list of information that identify the specific badge
     *
     * @return int|false The number of rows updated, false on error.
     */
    public static function update(array $data, array $where) {
        global $wpdb;
        return $wpdb->update(self::getTableName(), $data, $where);
    }

    /**
     * Delete the rows that match with the information tha we
     * passed throw param.
     *
     * @author      @AleRiccardi
     * @since       x.x.x
     *
     * @param array $data  list of information that will be
     *                     placed after the expression WHERE
     *
     * @return bool true if everything good, false on error
     */
    public static function delete(array $data) {
        global $wpdb;
        echo $ret = self::fetchSql('DELETE', $data);
        return $wpdb->query($ret) ? true : false;
    }

    /**
     * Get the time now.
     *
     * @author      @AleRiccardi
     * @since       x.x.x
     *
     * @return string the time.
     */
    public static function now() {
        return date('Y-m-d H:i:s');
    }

}
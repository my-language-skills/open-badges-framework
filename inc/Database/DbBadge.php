<?php
/**
 * Created by PhpStorm.
 * User: aleric
 * Date: 27/11/2017
 * Time: 11:52
 */

namespace Inc\Database;

global $OBF_db_version;
$OBF_db_version = "1.0.0";

class DbBadge{
    static $tableName = 'badge';

    public static function getTableName() {
        global $wpdb;
        $tableName = 'obf_badge';
        return $wpdb->prefix . $tableName;
    }

    public function register() {
        global $wpdb;
        global $OBF_db_version;

        $charset_collate = $wpdb->get_charset_collate();
        $installed_version = get_option('OBF_db_version');

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        if ($installed_version === $OBF_db_version) {
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

            dbDelta($sql);

            update_option('OBF_db_version', $OBF_db_version);
        }
    }



}
<?php

namespace Inc\Database;

/**
 * That class manage the database table for the users.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class DbUser extends DbModel {
    const ER_DONT_EXIST = "The user don't exist.\n";
    const ER_DUPLICATE = "The user is duplicate.\n";
    const ER_ERROR = "There's an error in the database.\n";
    // database name
    static $tableName = 'obf_user';

    /**
     * In that function, called from the Init class,
     * permit to create the db table.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function register() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $installed_version = get_option(self::DB_NAME_VERSION);
        if ($installed_version != self::DB_VERSION) {
            $sql = "CREATE TABLE IF NOT EXISTS " . $this->getTableName() . " (
            id INT(6) UNSIGNED AUTO_INCREMENT,
            email varchar(180) NOT NULL,
            idWP INT(6),
            PRIMARY KEY (id),
            UNIQUE KEY (email)
        ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            update_option(self::DB_NAME_VERSION, self::DB_VERSION);
        }
    }

    /**
     * Get a user/s by the id.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param int $id the OBF user id
     *
     * @return bool|Object of the user or false if not exist.
     */
    public static function getById($id) {

        $id = array('id' => $id);
        if ($users = parent::get($id)) {
            $user = $users[0]; //[0] -> permit to extract the first array (user)
        }

        return !empty($user) ? $user : false;
    }


    /**
     * Get all the users.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return array|null|object array of object (users), nul if not exist
     */
    public static function getAll() {
        return parent::get();
    }

    /**
     * Insert a user.
     *
     * @author        Alessandro RICCARDI
     * @since         x.x.x
     *
     * @param array $data information about a specific user.
     *
     * @return false|int The last id inserted, false on error.
     */
    public static function insert(array $data) {
        $dataGetById = ['email' => $data['email']];

        if ($user = self::get($dataGetById)) {
            return $user[0]->id;
        }

        return parent::insert($data);
    }

    /**
     * Update a user.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $data  data that we want to update.
     *
     * @param array $where information about a specific user.
     *
     * @return bool true if everything good, false on error.
     */
    public static function update(array $data, array $where) {
        return parent::update($data, $where) ? true : false;
    }

    /**
     * Delete a user by own id.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param int $id the number id of the user
     *
     * @return bool true if everything good or false on error
     */
    public static function deleteById($id) {
        $where = ["id" => $id];
        return parent::delete($where);
    }

}
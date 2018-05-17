<?php

namespace Inc\Database;

/**
 * That class manage the database table for the users.
 *
 * @author      @AleRiccardi
 * @since       1.0.0
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
     * Constructor that add filters.
     *
     * @author      @leocharlier
     * @since       1.0.1 dev
     */
    public function __construct(){
        $this->register_callbacks();
    }
    /**
     * Function called in constructor to add filter.
     * The filter added permit to use the funtion getSingle in the child theme.
     * @author      @leocharlier
     * @since       1.0.1 dev
     */
    protected function register_callbacks()
    {
        add_filter( 'theme_DbUser_get_single', array( $this, 'getSingle' ) );
    }

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
		self::deleteStudent();
		self::updateStudent();
    }
	
	 /**
     * Call the delete_user hook with our custom method my_delete_user()
     *  
     */
	private function deleteStudent() {
		
        add_action( 'delete_user', array($this, 'my_delete_user') );
		
    }

	/**
     * Call the profile_update hook with our custom method my_update_user()
     *  
     */
	private function updateStudent() {
		
		add_action( 'profile_update', array($this,'my_update_user'), 10, 2 );
		
    }	
	
	/**
     * While the user is being deleted from the Wordpress user table
     *  is also being deleted from the obf_user table
     */
	public function my_delete_user( $user_id ) {
		global $wpdb;
		$user_obj = get_userdata( $user_id );
		
		
		/*Delete Data from obf user table*/  
		$data_users= $wpdb->query("DELETE FROM ".$wpdb->prefix."obf_user where `idWP` = ".$user_obj->ID."");
		
	}
	
	/**
     * While the user upstaed his email through the Wordpress system
     * his email is also updated to the obf_user custom table
     */
	function my_update_user( $user_id, $old_user_data ) {
	 
	  $user = get_userdata( $user_id );
	  if($old_user_data->user_email != $user->user_email) {
		
		global $wpdb;
		
		$newemail = $user->user_email;
		$userid = $user->ID;
		
		$wpdb->query( $wpdb->prepare("UPDATE `".$wpdb->prefix."obf_user` SET email = %s WHERE idWP = %s",$newemail, $userid));
	  }
	 
	}
	


    /**
     * In that function, called from the Init class,
     * permit to create the db table.
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
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->getTableName() . " (
            id INT(6) UNSIGNED AUTO_INCREMENT,
            email varchar(180) NOT NULL,
            idWP INT(6),
            PRIMARY KEY (id),
            UNIQUE KEY (email)
        ) $charset_collate;";
        return dbDelta($sql);
    }

    /**
     * Get a user/s by the id.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return array|null|object array of object (users), nul if not exist
     */
    public static function getAll() {
        return parent::get();
    }

    /**
     * Insert a user.
     *
     * @author        @AleRiccardi
     * @since         1.0.0
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
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * @author      @AleRiccardi
     * @since       1.0.0
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

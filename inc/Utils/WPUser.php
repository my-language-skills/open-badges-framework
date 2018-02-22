<?php

namespace Inc\Utils;

use Inc\Database\DbUser;
use Inc\Pages\Admin;

/**
 * The User Class for the management of the users.
 *
 * @author      @AleRiccardi
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class WPUser {
    const ROLE_STUDENT = "student";
    const ROLE_TEACHER = "teacher";
    const ROLE_ACADEMY = "academy";

    const CAP_SELF = "obf_send_self";
    const CAP_SINGLE = "obf_send_single";
    const CAP_MULTIPLE = "obf_send_multiple";
    const CAP_CERT = "obf_send_certificate";
    const CAP_TEACHER = "obf_send_teacher";
    const CAP_JOB_LISTING = "obf_job_listing_integration";

    // That capability are created with the propose to allow the academy
    // role to manage the backend of our plugin, but right now we only
    // assigned the capability without give the possibility to the see
    // the backend. Propose for the future -> show the plugin section in
    // admin bar.
    /*
    const CAP_EDIT_BADGE = "edit_badge";
    const CAP_EDIT_BADGES = "edit_badges";
    const CAP_EDIT_OTHER_BADGES = "edit_other_badges";
    const CAP_EDIT_PUBLISHED_BADGES = "edit_published_badges";
    const CAP_PUBLISHED_BADGES = "publish_badges";
    const CAP_READ_BADGE = "read_badge";
    const CAP_READ_BADGES = "read_badges";
    const CAP_READ_PRIVATE_BADGES = "read_private_badges";
    const CAP_DELETE_BADGE = "delete_badge";
    const CAP_DELETE_BADGES = "delete_badges";
*/

    // List of roles that we need for our plugin.
    public $listRoles = array(
        array(
            'role' => self::ROLE_STUDENT,
            'display_name' => 'Student',
            'capabilities' => array(
                'read' => true,
                self::CAP_SELF => true,
                self::CAP_SINGLE => false,
                self::CAP_MULTIPLE => false,
                self::CAP_CERT => false,
                self::CAP_TEACHER => false,
                self::CAP_JOB_LISTING => false,

                /* self::CAP_EDIT_BADGE => false,
                 self::CAP_EDIT_BADGES => false,
                 self::CAP_EDIT_OTHER_BADGES => false,
                 self::CAP_EDIT_PUBLISHED_BADGES => false,
                 self::CAP_PUBLISHED_BADGES => false,
                 self::CAP_READ_BADGE => false,
                 self::CAP_READ_BADGES => false,
                 self::CAP_READ_PRIVATE_BADGES => false,
                 self::CAP_DELETE_BADGE => false,
                 self::CAP_DELETE_BADGES => false,
                */
            ),
        ),
        array(
            'role' => self::ROLE_TEACHER,
            'display_name' => 'Teacher',
            'capabilities' => array(
                'read' => true,
                self::CAP_SELF => true,
                self::CAP_SINGLE => true,
                self::CAP_MULTIPLE => false,
                self::CAP_CERT => false,
                self::CAP_TEACHER => false,
                self::CAP_JOB_LISTING => false,

                /*self::CAP_EDIT_BADGE => false,
                self::CAP_EDIT_BADGES => false,
                self::CAP_EDIT_OTHER_BADGES => false,
                self::CAP_EDIT_PUBLISHED_BADGES => false,
                self::CAP_PUBLISHED_BADGES => false,
                self::CAP_READ_BADGE => false,
                self::CAP_READ_BADGES => false,
                self::CAP_READ_PRIVATE_BADGES => false,
                self::CAP_DELETE_BADGE => false,
                self::CAP_DELETE_BADGES => false,*/
            ),
        ),
        array(
            'role' => self::ROLE_ACADEMY,
            'display_name' => 'Academy',
            'capabilities' => array(
                'read' => true,
                self::CAP_SELF => true,
                self::CAP_SINGLE => true,
                self::CAP_MULTIPLE => true,
                self::CAP_CERT => true,
                self::CAP_TEACHER => true,
                self::CAP_JOB_LISTING => true,

                /*self::CAP_EDIT_BADGE => true,
                self::CAP_EDIT_BADGES => true,
                self::CAP_EDIT_OTHER_BADGES => true,
                self::CAP_EDIT_PUBLISHED_BADGES => true,
                self::CAP_PUBLISHED_BADGES => true,
                self::CAP_READ_BADGE => true,
                self::CAP_READ_BADGES => true,
                self::CAP_READ_PRIVATE_BADGES => true,
                self::CAP_DELETE_BADGE => true,
                self::CAP_DELETE_BADGES => true,*/
            ),
        )
    );

    // Return messages.
    const REGIS_SUCCESS = 0;
    const REGIS_NO_MATCH_PASS = "The <strong>passwords</strong> doesn't match, please write correctly. <br>";
    const REGIS_USER_EXIST = "The <strong>username</strong> already exist, please chose another.";
    const REGIS_REGISTRATION_ERROR = "<strong>Registration error<strong>, please ask to the help desk";
    const REGIS_LOGIN_ERROR = "<strong>Login error<strong>, please ask to the help desk";

    /**
     * Call the principal function (initialize) and call the
     * WordPress hook that create the own class in Job_Listing
     * for every registration of user.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     */
    public function register() {
        $this->initializeRoleCap();
        add_action('user_register', array($this, 'registerUserClass'));
    }

    /**
     * Register all the roles that we need for the plugin.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     */
    private function initializeRoleCap() {
        // Create Roles
        foreach ($this->listRoles as $role) {
            // Resetting of the role
            if (get_role($role['role'])) {
                remove_role($role['role']);
            }
            // Creation of the role
            add_role($role['role'], $role['display_name'], $role['capabilities']);
        }

        // Now give the capability to the 'administrator' and 'author' role
        $roles[] = get_role('administrator');
        $roles[] = get_role('editor');

        foreach ($roles as $role) {
            $role->add_cap(self::CAP_SELF);
            $role->add_cap(self::CAP_SINGLE);
            $role->add_cap(self::CAP_MULTIPLE);
            $role->add_cap(self::CAP_CERT);
            $role->add_cap(self::CAP_TEACHER);
            $role->add_cap(self::CAP_JOB_LISTING);

            /*
            $role->add_cap(self::CAP_EDIT_BADGE);
            $role->add_cap(self::CAP_EDIT_BADGES);
            $role->add_cap(self::CAP_EDIT_OTHER_BADGES);
            $role->add_cap(self::CAP_EDIT_PUBLISHED_BADGES);
            $role->add_cap(self::CAP_PUBLISHED_BADGES);
            $role->add_cap(self::CAP_READ_BADGE);
            $role->add_cap(self::CAP_READ_BADGES);
            $role->add_cap(self::CAP_READ_PRIVATE_BADGES);
            $role->add_cap(self::CAP_DELETE_BADGE);
            $role->add_cap(self::CAP_DELETE_BADGES);
            */

        }
    }

    /**
     * Create the own class in Job_Listing for every new
     * user registration. But remember it will not create
     * for the user that was already registered before the
     * activation of the Job Listing plugin.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param int $userId id of the user
     */
    public function registerUserClass($userId) {
        if (!$userId > 0) {
            return;
        } else {
            $user = get_user_by('id', $userId);

            $newClass = array(
                'post_title' => $user->user_login,
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => Admin::POST_TYPE_CLASS_JL,
                'post_author' => $user->ID
            );

            wp_insert_post($newClass);
        }
    }

    /**
     * Get the current user that is logged in.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @return object the user.
     */
    public static function getCurrentUser() {
        global $current_user;
        wp_get_current_user();
        return $current_user;
    }

    /**
     * Check if the user have one of the roles that we pass
     * as a param.
     *
     * @author @AleRiccardi
     * @since  0.6.4
     *
     * @param string  roles that you can pass after the first parameter like this:
     *                check_the_rules("academy", "teacher")
     *
     * @return bool   true if have the privilege, false otherwise
     */
    public static function checkTheRules() {
        $user = self::getCurrentUser();
        $res = array();
        foreach (func_get_args() as $param) {
            if (!is_array($param)) {
                $res = in_array($param, $user->roles) ? true : $res ? true : false;
            }
        }
        return $res;
    }

    /**
     * Get a badge by the Ids.
     *
     * @author      @AleRiccardi
     * @since       x.x.x
     *
     * @param array $user {
     *                    Array with the information about the new user.
     *
     * @type string        userEmail        Email.
     * @type string        userName        Username.
     * @type string        userPass        Password.
     * @type string        userRepPass    Repeated Password.
     * @type string        firstName       First Name.
     * @type string        lastName        Last Name.
     * }
     *
     * @return int      RET_SUCCESS (0) in case of success
     *         string   RET_USER_EXIST
     *         string   RET_REGISTRATION_ERROR
     */
    public static function registerUser($user) {
        // Check if the passwords are the same
        if ($user['userPassword'] !== $user['userRepPass']) {
            return WPUser::REGIS_NO_MATCH_PASS;
        }

        // Check if there are users with the same name and email
        if (username_exists($user['userName']) || email_exists($user['userEmail'])) {
            // User already exist
            return WPUser::REGIS_USER_EXIST;
        } else {
            // 1 !¡ CREATION of the user
            $user_id = wp_create_user($user['userName'], $user['userPassword'], $user['userEmail']);

            if (is_wp_error($user_id)) {
                // Error creation
                return WPUser::REGIS_REGISTRATION_ERROR;
            } else {
                // 2 !¡ UPDATING of the first name, last name and role.
                $update = wp_update_user(
                    array(
                        'ID' => $user_id,
                        'first_name' => $user['firstName'],
                        'last_name' => $user['lastName'],
                        'role' => WPUser::ROLE_STUDENT,
                    ));

                if (is_wp_error($update)) {
                    // Error updating
                    return WPUser::REGIS_REGISTRATION_ERROR;
                } else {
                    return self::REGIS_SUCCESS;
                }
            }
        }
    }

    /**
     * Permit to log-in a specific user
     *
     * @param array $user {
     *                    Array with the information about the new user.
     *
     * @type string        userEmail        Email.
     * @type string        userPassword        Password.
     * }
     *
     * @return int      RET_SUCCESS (0) in case of success
     *         string   RET_LOGIN_ERROR error message
     */
    public static function loginUser($user) {
        // 3 !¡ SING-ON of the user
        $login = wp_signon(array(
            'user_login' => $user['userEmail'],
            'user_password' => $user['userPassword'],
        ), false);

        if (is_wp_error($login)) {
            // Error sing-on
            return WPUser::REGIS_LOGIN_ERROR;
        } else {
            // !¡!¡!¡ SUCCESS !¡!¡!¡
            return WPUser::REGIS_SUCCESS;
        }
    }

    /**
     * Insert a user in the database and retrieve its id.
     * If is already stored in the DB the function will anyway
     * return the its id.
     *
     * @param $email
     *
     * @return false|int The id of the OBF user, false on error.
     */
    public static function insertUserInDB($email) {
        $userDB = DbUser::getSingle(["email" => $email]);

        if ($userDB && $userDB->idWP) {
            return $userDB->id;
        }

        if (!$userDB) {
            DbUser::insert(["email" => $email]);
            $id = DbUser::getSingle(["email" => $email])->id;
        } else {
            $id = $userDB->id;
        }

        # if doesn't exist in the database
        if ($userWP = get_user_by("email", $email)) {
            # if already exist in the wordpress db
            $where["id"] = $id;
            $data["idWP"] = $userWP->ID;
            return DbUser::update($data, $where) ? $id : false;
        } else {
            return $id;
        }
    }

}
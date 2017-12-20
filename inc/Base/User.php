<?php

namespace Inc\Base;

use Inc\Pages\Admin;

/**
 * The User Class for the management of the users.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class User {
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
    const RET_SUCCESS = 0;
    const RET_NO_MATCH_PASS = "The <strong>passwords</strong> doesn't match, please write correctly. <br>";
    const RET_USER_EXIST = "The <strong>username</strong> already exist, please chose another.";
    const RET_REGISTRATION_ERROR = "<strong>Registration error<strong>, please ask to the help desk";

    // List of roles that we need for our plugin.
    public $listRoles = array(
        array(
            'role' => self::ROLE_STUDENT,
            'display_name' => 'Student',
            'capabilities' => array(
                'read' => true,
                'publish_posts' => false,
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
                self::CAP_TEACHER => false,
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


    /**
     * Call the principal function (initialize) and call the
     * WordPress hook that create the own class for every
     * registration of user.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     */
    public function register() {
        $this->initializeRoleCap();
        add_action('user_register', array($this, 'registerUserClass'));
    }

    /**
     * Register all the roles that we need for the plugin.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
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
        $roles[] = get_role( 'administrator' );
        $roles[] = get_role( 'editor' );

        foreach($roles as $role) {
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
     * Create the own class for every registration
     * of user.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param int $user_id id of the user
     */
    public function registerUserClass($user_id) {
        if (!$user_id > 0) {
            return;
        } else {
            $user = get_user_by('id', $user_id);

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
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @return the user
     */
    public static function getCurrentUser() {
        global $current_user;
        wp_get_current_user();

        return $current_user;
    }

    /**
     * Check if the user have one of the roles
     * that we pass as a param.
     *
     * @author Alessandro RICCARDI
     * @since  0.6.4
     *
     * @param infinity      roles that you can pass after the first parameter like this:
     *                      check_the_rules("academy", "teacher")
     *
     * @return bool         true if have the privilege, false otherwise
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
     * Get a badge by the ids.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param array $user {
     *                    Array with the information about the new user.
     *
     * @type string        userEmail        Email.
     * @type string        user_name        Username.
     * @type string        user_pass        Password.
     * @type string        user_rep_pass    Repeated Password.
     * @type string        first_name       First Name.
     * @type string        last_name        Last Name.
     * }
     *
     * @return Const error.
     */
    public static function registerUser($user){
        // Check if the passwords are the same
        if ($user['user_pass'] !== $user['user_rep_pass']) {
            return User::RET_NO_MATCH_PASS;
        }

        // Check if there are users with the same name and email
        if (username_exists($user['user_name']) || email_exists($user['user_email'])) {
            // User already exist
            return User::RET_USER_EXIST;
        } else {
            // 1 !¡ CREATION of the user
            $user_id = wp_create_user($user['user_name'], $user['user_pass'], $user['user_email']);

            if (is_wp_error($user_id)) {
                // Error creation
                return User::RET_REGISTRATION_ERROR;
            } else {
                // 2 !¡ UPDATING of the first name, last name and role.
                $update = wp_update_user(
                    array(
                        'ID' => $user_id,
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'role' => User::ROLE_STUDENT,
                    ));

                if (is_wp_error($update)) {
                    // Error updating
                    return User::RET_REGISTRATION_ERROR;
                } else {
                    return self::RET_SUCCESS;
                }
            }
        }
    }

    /**
     * @param array $user {
     *                    Array with the information about the new user.
     *
     * @type string        userEmail        Email.
     * @type string        user_pass        Password.
     * }
     *
     * @return int|string
     */
    public function loginUser($user){
        // 3 !¡ SING-ON of the user
        $login = wp_signon(array(
            'user_login' => $user['user_email'],
            'user_password' => $user['user_pass'],
        ), false);

        if (is_wp_error($login)) {
            // Error sing-on
            return User::RET_REGISTRATION_ERROR;
        } else {
            // !¡!¡!¡ SUCCESS !¡!¡!¡
            return User::RET_SUCCESS;
        }
    }
}
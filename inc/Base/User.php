<?php
/**
 * ...
 *
 * @author     Alessandro RICCARDI
 * @since      x.x.x
 *
 * @package    BadgeIssuerForWp
 */

namespace inc\Base;

use Inc\Pages\Admin;

class User {

    public $roles = array();

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function register() {
        $this->initialize();
        add_action('user_register', array($this,'registerUserClass'));
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    private function initialize() {
        $this->fillRoles();
        foreach ($this->roles as $role) {
            // Resetting of the role
            if (get_role($role['role'])) {
                remove_role($role['role']);
            }
            // Creation of the role
            add_role($role['role'], $role['display_name'], $role['capabilities']);
        }
    }

    public function fillRoles() {
        $this->roles = array(
            array(
                'role' => 'student',
                'display_name' => 'Student',
                'capabilities' => array(
                    'read' => true,
                    'edit_posts' => false,
                    'delete_posts' => false
                ),
            ),
            array(
                'role' => 'teacher',
                'display_name' => 'Teacher',
                'capabilities' => array(
                    'read' => true,
                    'edit_posts' => false,
                    'delete_posts' => false
                ),
            ),
            array(
                'role' => 'academy',
                'display_name' => 'Academy',
                'capabilities' => array(
                    'read' => true,
                    'edit_posts' => false,
                    'delete_posts' => false
                ),
            )
        );
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @param $user_id
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


    public static function getCurrentUser(){
        global $current_user;
        wp_get_current_user();

        return $current_user;
    }

    /**
     * Check the rules of the user.
     *
     * @author Alessandro RICCARDI
     * @since  0.6.4
     *
     * @param $actual_roles, the roles that the user have in this moment.
     * @param infinity roles that you can pass after the first parameter like this:
     *            check_the_rules("academy", "teacher")
     * @return bool
     */
    public static function check_the_rules(){
        $user = self::getCurrentUser();

        foreach (func_get_args() as $param) {
            if(!is_array($param)){
                $res = in_array($param, $user->roles)? true: $res ? true: false;
            }
        }
        return $res;
    }
}
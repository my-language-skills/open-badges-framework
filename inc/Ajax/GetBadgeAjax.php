<?php
/**
 * The SendBadgeAjax Class, contain all the
 * function about the Send Badge functionality.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace inc\Ajax;


use Inc\Base\BaseController;
use Inc\Base\User;
use Inc\OB\JsonManagement;
use Templates\GetBadgeTemp;

class GetBadgeAjax extends BaseController {

    const LOGIN_SUCCESS = 0;
    const USER_EXIST = 1;
    const LOGIN_ERROR = 2;

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxGbShowLogin() {
        $json = $_POST['json'];
        $getBadgeTemp = GetBadgeTemp::getInstance();
        $jsonFile = JsonManagement::getJsonObject($json);
        $email = $jsonFile["recipient"]['identity'];

        if (wp_get_current_user()->user_email === $email) {
            $getBadgeTemp = GetBadgeTemp::getInstance();
            echo $getBadgeTemp->showOpenBadgesSendIssuer();
        } else if (email_exists($email)) {
            $jsonFile = JsonManagement::getJsonObject($json);
            $email = $jsonFile["recipient"]['identity'];
            echo $getBadgeTemp->showTheLoginContent($email);
        } else {
            echo $getBadgeTemp->showRegisterPage($email);
        }

        wp_die();
    }


    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxGbLogin() {
        $creds = array(
            'user_login' => $_POST['user_email'],
            'user_password' => $_POST['user_password'],
            'remember' => $_POST['remember']
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            echo $user->get_error_message();
        } else {
            echo true;
        }

        wp_die();
    }

    function ajaxGbShowRegister() {
        $email = $_POST['user_email'];
        $getBadgeTemp = GetBadgeTemp::getInstance();

        echo $getBadgeTemp->showRegisterPage($email);

        wp_die();
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxGbRegistration() {
        $user = array(
            'user_email' => $_POST['user_email'],
            'user_name' => $_POST['user_name'],
            'user_pass' => $_POST['user_pass'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name']
        );

        $usernameRet = username_exists($user['user_name']);
        $emailRet = email_exists($user['user_email']);
        if ($usernameRet || $emailRet) {
            //USER EXIST
            echo User::RET_USER_EXIST;
        } else {
            // Creation of the user
            $user_id = wp_create_user($user['user_name'], $user['user_pass'], $user['user_email']);
            if (is_wp_error($user_id)) {
                //CREATION ERROR
                echo User::RET_REGISTRATION_ERROR;
            } else {
                // Update of the first name, last name and the role of the user
                $update = wp_update_user(
                    array(
                        'ID' => $user_id,
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'role' => User::STUDENT_ROLE,
                    ));
                if (is_wp_error($update)) {
                    //UPDATE ERROR
                    echo User::RET_REGISTRATION_ERROR;
                } else {

                    $login = wp_signon(array(
                        'user_login' => $user['user_email'],
                        'user_password' => $user['user_pass'],
                    ), false);

                    if (is_wp_error($login)) {
                        echo User::RET_REGISTRATION_ERROR;
                    } else {
                        echo User::RET_LOGIN_SUCCESS;
                    }
                }
            }
        }

        wp_die();
    }


    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxGbShowGetOpenBadges() {
        $getBadgeTemp = GetBadgeTemp::getInstance();
        echo $getBadgeTemp->showOpenBadgesSendIssuer();

        wp_die();
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxGbGetJsonUrl() {
        $json = $_POST['json'];
        echo JsonManagement::getJsonUrl($json);
        wp_die();
    }

}
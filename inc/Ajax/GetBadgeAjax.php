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
use Inc\Database\DbBadge;
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
        $data = array(
            'userEmail' => User::getCurrentUser()->user_email,
            'badgeId' => $_POST['badgeId'],
            'fieldId' => $_POST['fieldId'],
            'levelId' => $_POST['levelId'],
        );

        $getBadgeTemp = GetBadgeTemp::getInstance();
        $jsonFile = JsonManagement::getJsonObject($json);
        $email = $jsonFile["recipient"]['identity'];

        if (wp_get_current_user()->user_email === $email) {
            $getBadgeTemp = GetBadgeTemp::getInstance();
            echo $getBadgeTemp->showMozillaOpenBadges(DbBadge::isGot($data));
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
            'user_rep_pass' => $_POST['user_rep_pass'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name']
        );

        if ($user['user_pass'] !== $user['user_rep_pass']) {
            echo User::RET_NO_MATCH_PASS;
        } else {
            $usernameRet = username_exists($user['user_name']);
            $emailRet = email_exists($user['user_email']);
            if ($usernameRet || $emailRet) {
                //user exist
                echo User::RET_USER_EXIST;
            } else {
                // 1 -- CREATION of the user
                $user_id = wp_create_user($user['user_name'], $user['user_pass'], $user['user_email']);

                if (is_wp_error($user_id)) {
                    // error creation
                    echo User::RET_REGISTRATION_ERROR;
                } else {
                    // 2 -- UPDATING of the first, last name and role.
                    $update = wp_update_user(
                        array(
                            'ID' => $user_id,
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                            'role' => User::STUDENT_ROLE,
                        ));

                    if (is_wp_error($update)) {
                        // error updating
                        echo User::RET_REGISTRATION_ERROR;
                    } else {
                        // 3 -- SING-ON of the user
                        $login = wp_signon(array(
                            'user_login' => $user['user_email'],
                            'user_password' => $user['user_pass'],
                        ), false);

                        if (is_wp_error($login)) {
                            // error sing-on
                            echo User::RET_REGISTRATION_ERROR;
                        } else {
                            // SUCCESS
                            echo User::RET_LOGIN_SUCCESS;
                        }
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
        $data = array(
            'userEmail' => User::getCurrentUser()->user_email,
            'badgeId' => $_POST['badgeId'],
            'fieldId' => $_POST['fieldId'],
            'levelId' => $_POST['levelId'],
        );

        $getBadgeTemp = GetBadgeTemp::getInstance();
        echo $getBadgeTemp->showMozillaOpenBadges(DbBadge::isGot($data));

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

    function ajaxGbShowConclusion() {
        $mob = $_POST['MOB'];

        $where = array(
            'userEmail' => User::getCurrentUser()->user_email,
            'badgeId' => $_POST['badgeId'],
            'fieldId' => $_POST['fieldId'],
            'levelId' => $_POST['levelId'],
        );

        if ($mob === 'true') {
            $data = array(
                'getDate' => DbBadge::now(),
                'getMobDate' => DbBadge::now()
            );

            $res = DbBadge::update($data, $where);

            if ($res == DbBadge::ER_DONT_EXIST) {
                DbBadge::ER_DONT_EXIST;
            } else if (!$res) {
                echo "update problem";
            }

        } else {
            $data = array(
                'getDate' => DbBadge::now(),
            );

            $res = DbBadge::update($data, $where);

            if ($res == DbBadge::ER_DONT_EXIST) {
                DbBadge::ER_DONT_EXIST;
            } else if (!$res) {
                echo "update problem";
            }
        }

        $getBadgeTemp = GetBadgeTemp::getInstance();
        echo $getBadgeTemp->showConclusionPage();

        wp_die();
    }
}
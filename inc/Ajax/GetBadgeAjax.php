<?php

namespace Inc\Ajax;

use Inc\Base\BaseController;
use Inc\Base\WPUser;
use Inc\Database\DbBadge;
use Inc\Database\DbUser;
use Inc\Utils\Badge;
use Inc\Utils\JsonManagement;
use Templates\GetBadgeTemp;

/**
 * This class is a wrap for all the functions that are
 * called as a "ajax call" and concern the get badge process.
 * This functions is initialized from the InitAjax Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class GetBadgeAjax extends BaseController {

    const LOGIN_SUCCESS = 0;
    const USER_EXIST = 1;
    const LOGIN_ERROR = 2;

    /**
     * Show the login step but trying to understand from the
     * email of the json file if the user need to register
     * (show registration step), need to do the login (show
     * login step) or is already logged in (show MOB step).
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxGbShowLogin() {
        if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);
            $user = DbUser::getById($badge->getIdUser());

            $getBadgeTemp = GetBadgeTemp::getInstance();
            if (WPUser::getCurrentUser()->user_email == $user->email) {
                // User already logged in
                $getBadgeTemp = GetBadgeTemp::getInstance();
                echo $getBadgeTemp->showMozillaOpenBadges($badge->getGotMozillaDate());
            } else if (email_exists($user->email)) {
                // User registrated but not logged in
                echo $getBadgeTemp->showTheLoginContent($user->email);
            } else {
                // User is not registered
                echo $getBadgeTemp->showRegisterPage($user->email);
            }
        }
        wp_die();
    }

    /**
     * This function retrieve the user email, password and
     * remember check-box and then execute the login.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxGbLogin() {
        if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);
            $creds = array(
                'user_login' => $_POST['userEmail'],
                'user_password' => $_POST['userPassword'],
                'remember' => $_POST['remember']
            );

            $user = wp_signon($creds, false);

            if (is_wp_error($user)) {
                echo $user->get_error_message();
            } else {
                echo true;
            }
        }
        wp_die();
    }


    /**
     * This function is called when is triggered the register button.
     * Here what we're doing is principally a control about the arguments
     * that we passed. For every control that goes wrong we trigger an
     * error that have a specific message.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @return string RET_LOGIN_SUCCESS          registration success.
     *                RET_NO_MATCH_PASS          the passwords do not match.
     *                RET_REGISTRATION_ERROR     random message.
     */
    public function ajaxGbRegistration() {
        $user = array(
            'userEmail' => $_POST['userEmail'],
            'userName' => $_POST['userName'],
            'userPassword' => $_POST['userPassword'],
            'userRepPass' => $_POST['userRepPass'],
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName']
        );

        $regRet = WPUser::registerUser($user);
        if (!$regRet) {
            // connecting the user with the dbUser
            WPUser::insertUserInDB($user["userEmail"]);
            $loginRet = WPUser::loginUser($user);
            echo $loginRet;
        } else {
            echo $regRet;
        }

        wp_die();
    }

    /**
     * Show the Mozilla Open Badges step.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxGbShowMozillaOpenBadges() {
        if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);

            $getBadgeTemp = GetBadgeTemp::getInstance();
            echo $getBadgeTemp->showMozillaOpenBadges($badge->getGotMozillaDate());
        }
        wp_die();
    }

    /**
     * Permit to retrieve the url of the json file.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxGbGetJsonUrl() {
        if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);

            echo JsonManagement::getJsonUrl($badge->getJson());
        }
        wp_die();
    }

    /**
     * Show the Conclusion step.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxGbShowConclusion() {
        if (isset($_POST['idBadge']) && isset($_POST['isMozilla']) && !empty($_POST['idBadge']) ) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);
            $mozilla = $_POST['isMozilla'] ? true : false;

            $res = DbBadge::setBadgeGot(["id" => $badge->getId()], $mozilla);

            if ($res) {
                $getBadgeTemp = GetBadgeTemp::getInstance();
                echo $getBadgeTemp->showConclusionsStep();
            } else {
                // Error
                echo $res;
            }
        }
        wp_die();
    }
}
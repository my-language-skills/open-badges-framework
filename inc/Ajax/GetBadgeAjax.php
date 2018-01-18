<?php

namespace Inc\Ajax;

use Inc\Base\BaseController;
use Inc\Base\WPUser;
use Inc\Database\DbBadge;
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
        $json = $_POST['json'];
        $getBadgeTemp = GetBadgeTemp::getInstance();
        $data = self::getUserInfoPost();

        if (wp_get_current_user()->user_email === $data['userEmail']) {
            // User already logged in
            $getBadgeTemp = GetBadgeTemp::getInstance();
            echo $getBadgeTemp->showMozillaOpenBadges(DbBadge::isGot($data));
        } else if (email_exists($data['userEmail'])) {
            // User registrated but not logged in
            echo $getBadgeTemp->showTheLoginContent($data['userEmail']);
        } else {
            // User is not registered
            echo $getBadgeTemp->showRegisterPage($data['userEmail']);
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

    /**
     * Show the Register step.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxGbShowRegister() {
        $email = $_POST['user_email'];
        $getBadgeTemp = GetBadgeTemp::getInstance();

        echo $getBadgeTemp->showRegisterPage($email);

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
            'user_email'    => $_POST['user_email'],
            'user_name'     => $_POST['user_name'],
            'user_pass'     => $_POST['user_pass'],
            'user_rep_pass' => $_POST['user_rep_pass'],
            'first_name'    => $_POST['first_name'],
            'last_name'     => $_POST['last_name']
        );

        $regRet = WPUser::registerUser($user);
        if(!$regRet) {
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
        $data = self::getUserInfoPost();

        $getBadgeTemp = GetBadgeTemp::getInstance();
        echo $getBadgeTemp->showMozillaOpenBadges(DbBadge::isGot($data));

        wp_die();
    }

    /**
     * Permit to retrieve the url of the json file.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxGbGetJsonUrl() {
        $json = $_POST['json'];
        echo JsonManagement::getJsonUrl($json);
        wp_die();
    }

    /**
     * Show the Conclusion step.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function ajaxGbShowConclusion() {
        $mob = $_POST['MOB'];

        $where = self::getUserInfoPost();
        $res = DbBadge::setBadgeGot($where, $mob);

        if($res) {
            $getBadgeTemp = GetBadgeTemp::getInstance();
            echo $getBadgeTemp->showConclusionsStep();
        } else {
            // Error
            echo $res;
        }

        wp_die();
    }

    /**
     * Get param about the user that are passed from ajax.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @return array {
     *
     * @type string     userEmail           Email got from the json file.
     * @type int        badgeId             Badge ID.
     * @type int        fieldId             Field ID.
     * @type int        levelId             Level ID.
     * }
     */
    private function getUserInfoPost() {
        return array(
            'userEmail' => JsonManagement::getEmailFromJson($_POST['json']),
            'badgeId' => $_POST['badgeId'],
            'fieldId' => $_POST['fieldId'],
            'levelId' => $_POST['levelId'],
        );
    }
}
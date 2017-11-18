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
use Inc\OB\JsonManagement;
use Templates\GetBadgeTemp;

class GetBadgeAjax extends BaseController {

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
        echo $getBadgeTemp->showTheLoginContent($email);

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
            'user_login'    => $_POST['user_login' ],
            'user_password' => $_POST['user_password' ],
            'remember'      => $_POST['remember' ]
        );

        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
            echo $user->get_error_message();
        } else {
            echo true;
        }

        wp_die();
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxGbShowOpenBadgesLogin() {
        $json = $_POST['json'];
        $getBadgeTemp = GetBadgeTemp::getInstance();
        $jsonFile = JsonManagement::getJsonObject($json);
        $email = $jsonFile["recipient"]['identity'];
        echo $getBadgeTemp->showOpenBadgesLoginContent($email);

        wp_die();
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    function ajaxGbGetJsonUrl(){
        $json = $_POST['json'];
        $getBadgeTemp = GetBadgeTemp::getInstance();
        echo JsonManagement::getJsonUrl($json);

        wp_die();
    }

}
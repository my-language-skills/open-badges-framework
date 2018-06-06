<?php

namespace Inc\Ajax;

use Inc\Base\BaseController;
use Inc\Utils\WPUser;
use Inc\Database\DbBadge;
use Inc\Database\DbUser;
use Inc\Utils\Badge;
use Inc\Utils\JsonManagement;
use Templates\GetBadgeTemp;
use ReallySimpleCaptcha;

/**
 * This class is a wrap for all the functions that are
 * called as a "ajax call" and concern the get badge process.
 * This functions is initialized from the InitAjax Class.
 *
 * @author      @AleRiccardi
 * @since       1.0.0
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
     * @author @AleRiccardi
     * @since  1.0.0
     */
    public function ajaxGbShowLogin() {
        if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);
            $user = DbUser::getById($badge->idUser);

            $getBadgeTemp = GetBadgeTemp::getInstance();
            if (WPUser::getCurrentUser()->user_email == $user->email) {
                // User already logged in
                $getBadgeTemp = GetBadgeTemp::getInstance();
                $getBadgeTemp->showMozillaOpenBadges($badge->gotDate ? true : false);
            } else if (email_exists($user->email)) {
                // User registrated but not logged in
                $getBadgeTemp->showTheLoginContent($user->email);
            } else {
                // User is not registered
                $getBadgeTemp->showRegisterPage($user->email);
            }
        }
        wp_die();
    }

    /**
     * This function retrieve the user email, password and
     * remember check-box and then execute the login.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     */
    public function ajaxGbLogin() {
        if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
		
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);
			$user = DbUser::getById($badge->idUser);
			$userEmail = $user->email;
			
			$creds = array(
                'user_login' => $_POST['userEmail'],
                'user_password' => $_POST['userPassword'],
				'remember' => $_POST['remember']
            );
			
			
			//condition to see if the user is using the right email to login
			 if ( $userEmail != $creds['user_login']){
	
				echo "You have to login with the email that you opened the badge";
				
			} else {
					
				$valid = wp_signon($creds, false);
				
				if (is_wp_error($valid)) {
					echo $valid->get_error_message();
				} else {
					echo true;
				}  
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
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return string RET_LOGIN_SUCCESS          registration success.
     *                RET_NO_MATCH_PASS          the passwords do not match.
     *                RET_REGISTRATION_ERROR     random message.
     */
    public function ajaxGbRegistration() {
		if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
		
			$badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);
			$user = DbUser::getById($badge->idUser);
			$userEmail = $user->email;
		
			$user = array(
				'userEmail' => $_POST['userEmail'],
				'userName' => $_POST['userName'],
				'userPassword' => $_POST['userPassword'],
				'userRepPass' => $_POST['userRepPass'],
				'firstName' => $_POST['firstName'],
				'lastName' => $_POST['lastName'],
                'userYear' => $_POST['userYear'],
                'userCountry' => $_POST['userCountry'],
                'userCity' => $_POST['userCity'],
                'userMotherTongue' => $_POST['userMotherTongue'],
                'userPrimaryDegree' => $_POST['userPrimaryDegree'],
                'userSecondaryDegree' => $_POST['userSecondaryDegree'],
                'userTertiaryDegree' => $_POST['userTertiaryDegree']
			);
			
			
			//condition to see if the user is using the right email to register
			if ( $userEmail != $user['userEmail']){
				
				echo "Please register with the email we contacted you!";
				
			}else if ( is_plugin_active( 'really-simple-captcha' ) ){
                $captcha_instance = new ReallySimpleCaptcha();
                //Check if the user answer match with the right answer
                if( !$captcha_instance->check( $_POST['captchaPrefix'], $_POST['captchaAnswer'] ) ) {

                    echo "Please check if you're not a robot!";
                }
            }else{
				
				$regRet = WPUser::registerUser($user);

                if ( is_plugin_active( 'really-simple-captcha' ) ){
                    //Delete the temporary image and text files
                    $captcha_instance->remove( $_POST['captchaPrefix'] );
                }
				
				if (!$regRet) {
					// connecting the user with the dbUser
					WPUser::insertUserInDB($user["userEmail"]);
					$loginRet = WPUser::loginUser($user);
					echo $loginRet;
				} else {
					echo $regRet;
				}	
					
			}	
		}
		wp_die();
	}

    /**
     * Show the Mozilla Open Badges step.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     */
    public function ajaxGbShowMozillaOpenBadges() {
        if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);
            $getBadgeTemp = GetBadgeTemp::getInstance();
            $getBadgeTemp->showMozillaOpenBadges($badge->gotDate ? true : false);
        }
        wp_die();
    }

    /**
     * Permit to retrieve the url of the json file.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     */
    public function ajaxGbGetJsonUrl() {
        if (isset($_POST['idBadge']) && !empty($_POST['idBadge'])) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);

            echo JsonManagement::getJsonUrl($badge->json);
        }
        wp_die();
    }

    /**
     * Show the Conclusion step.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     */
    public function ajaxGbShowConclusion() {
        if (isset($_POST['idBadge']) && isset($_POST['isMozilla']) && !empty($_POST['idBadge']) ) {
            $badge = new Badge();
            $badge->retrieveBadge($_POST['idBadge']);
            $mozilla = $_POST['isMozilla'] ? true : false;

            $res = DbBadge::setBadgeGot(["id" => $badge->id], $mozilla);

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
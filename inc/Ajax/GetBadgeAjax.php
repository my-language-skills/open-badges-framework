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
use templates\SettingsTemp;

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
     * @author @AleRiccardi, @leocharlier
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

			if ( is_plugin_active( 'really-simple-captcha/really-simple-captcha.php' ) ){
                $captcha_instance = new ReallySimpleCaptcha();
            }
			
			//Condition to see if the user is using the right email to register
			if ( $userEmail != $user['userEmail']){
				
				echo "Please register with the email we contacted you!";
				
			}
            //Condition to see if the user passed the CAPTCHA test
            else if ( is_plugin_active( 'really-simple-captcha/really-simple-captcha.php' ) && SettingsTemp::getOption(SettingsTemp::FI_CAPTCHA)==1 && !$captcha_instance->check( $_POST['captchaPrefix'], $_POST['captchaAnswer'] ) ){

                    echo "Please check if you're not a robot!";

            }else{
				
				$regRet = WPUser::registerUser($user);
				
				if (!$regRet) {
					// connecting the user with the dbUser
					WPUser::insertUserInDB($user["userEmail"]);
					$loginRet = WPUser::loginUser($user);

                    if ( is_plugin_active( 'really-simple-captcha/really-simple-captcha.php' ) && SettingsTemp::getOption(SettingsTemp::FI_CAPTCHA)==1 ){
                        //Delete the temporary image and text files
                        $captcha_instance->remove( $_POST['captchaPrefix'] );
                    }

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
    /** API v2 ACTIONS */

     /**
     * Given section as POST parameter is checked whether was configured correctly
     * Action called by all 3 POST request before issuing the assertion to the user.
     * Data received is the assertion post request to previous API.
     * Depending on request data is retrieved and transformed for each request
     * @author  @CharalamposTheodorou
     * @since   2.0
     */
    public function ajaxBadgrRequestcheck()
    {
        //If issuer token file doesn't exists then no request can be issued.
        //This should not be an issue here. Token is previously taken care of.
        //Token information here.
        $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
        $issuer_token_info = json_decode(file_get_contents($location_file));
        if (!$issuer_token_info)
            echo 'error in issuer token file: Not found';
        else
        {
            if (!empty($_POST['section']) && !empty($_POST['data']))
            {//data received is the assertion json from previous api.
                //badgeClass file in wp-uploads. 
                $badge_file_location = parent::getJsonFolderPath().str_replace('http://dev.badges4languages.com/wp-content/uploads/open-badges-framework/json/','',$_POST['data']['badge']);
                //$badge_file_data = json_decode(file_get_contents($badge_file_location));

                //issuer Profile file in wp-uploads.
                $issuer_file_location = parent::getJsonFolderPath().'issuer-info.json';
                //echo  $badge_file_location.' : '.$issuer_file_location;
                if ($_POST['section'] == 'issuer')
                {//checking for issuer if okay to proceed.
                    $profile_data = json_decode(file_get_contents($issuer_file_location));
                    //image must be change to data base64 data URI format.
                    $image_path = wp_upload_dir()['baseurl'].substr($profile_data->image,strpos($profile_data->image,'uploads')+strlen('uploads'));
                    $image_relative_path =  str_replace('http:/','/var/www/vhosts/badges4languages.com',$image_path);  
                    
                    $data = file_get_contents($image_relative_path);
                    //image in base64 data URI format. for issuer POST request.
                    $base64 = 'data:image/png;base64,' . base64_encode($data);
                    $profile_data->image = $base64;
                    $args['headers']['Authorization'] = $issuer_token_info->token_type.' '.$issuer_token_info->access_token;
                    $args['body'] = $profile_data;
                    print_r(json_encode($args));
                    
                }
                else if ($_POST['section'] == 'BadgeClass')
                {
                    $badge_data = json_decode(file_get_contents($badge_file_location));

                    $image_path = wp_upload_dir()['baseurl'].substr($badge_data->image,strpos($badge_data->image,'uploads')+strlen('uploads'));
                    $image_relative_path =  str_replace('http:/','/var/www/vhosts/badges4languages.com',$image_path);

                    $data = file_get_contents($image_relative_path);
                    //image in base64 data URI format. for issuer POST request.
                    $base64 = 'data:image/png;base64,' . base64_encode($data);
                    
                    $badge_data->image = $base64;
                    $args['headers']['Authorization'] = $issuer_token_info->token_type.' '.$issuer_token_info->access_token;
                    $args['body'] = $badge_data;

                    print_r(json_encode($args));
                }
                else if ($_POST['section'] == "Assertion")
                {
                    $args['headers']['Authorization'] = $issuer_token_info->token_type.' '.$issuer_token_info->access_token;
                    $args['body'] = $_POST['data'];
                    print_r(json_encode($args));
                }
                else
                    echo 'fail';
            }
            else
            {
                echo 'fail';
            }
        }
            //$badge_info = json_decode(file_get_contents($badhe_file));
            /* if ($_POST['section'] == "issuer")
            {//check for issuer. if file that contains token then safe to proceed to issuer requests and checks
                $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
                $issuer_token_info = json_decode(file_get_contents($location_file));
                if (!$issuer_info)
                    echo 'token file not found';
                else
                {//get the data from uploads-folder using the data in post body.
                    if (!empty($_POST['data']))
                    {
                        $profile_file = 
                    }
                    else
                    {
                        echo 'error data not found';
                    }
                    echo 'issuer';
                }
            }
            else if ($_POST['section'] == "badgeClass")
            {
                echo 'badgeClass';
                //checks for token,issuer configured
            }
            else if ($_POST['section'] == "assertion")
            {
                echo 'assertion';
                //checks for token,issuer configured
            } */
        wp_die();
    }
    /**TOKEN ACTIONS */

    /**
     * This action is responsible to check wheather the issuer Token exists.
     * If not, returns a message "Not Found" so it can be created in the next request.
     * If exists, returns the contents of the file that contains the Token info. 
     * 
     * @author  @CharalamposTheodorou
     * @since   2.0
     */
    public function ajaxIssuerTokenExistsRequest()
    {
        $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
        $issuer_info = json_decode(file_get_contents($location_file));
        if (!$issuer_info)
            echo "Not Found";

        wp_die();
    }

     /**
     * Token exists to the folder of the installation.
     * Checks if Token is expired
     * If Expired makes request for a new one using the refrest_token value.
     * 
     * @author  @CharalamposTheodorou
     * @since   2.0
     */
    public function ajaxIssuerTokenExpiration()
    {   
        $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
        $issuer_info = json_decode(file_get_contents($location_file));
        //API request to get the user (issuer) with current Token. 
        $url = "https://api.eu.badgr.io/v2/users/self";
        $args['headers']['Authorization'] = $issuer_info->token_type.' '.$issuer_info->access_token;
        $response = json_decode(wp_remote_get($url,$args)['body']);
        $description = $response->status->description;
        if ($description != "ok")
        {//something wrong with the request. Token is expired.
            $url = "https://api.eu.badgr.io/o/token?grant_type=refresh_token&refresh_token=".$issuer_info->refresh_token;
            $response = json_decode(wp_remote_post($url)['body']);
            if (file_put_contents($location_file,json_encode($response,JSON_UNESCAPED_SLASHES)))
                 echo "saved";
            else
                echo "error";
        }
        else
            echo "saved";
        wp_die();
    }
    
    /**
     * Makes the Token request on the server side so it can be stored on the database directly
     * Checks if given email is valid before proceeding.
     * Makes POST request to create TOKEN. 
     * Username and password given in prompt box.
     * 
     * @author      @CharalamposTheodorou
     * @since       @2.0
     */
    public function ajaxIssuerTokenRequest()
    {
        $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
        if (!empty($_POST['issuer_username']) && !empty($_POST['issuer_password']))
        {     
            //checking if email given is valid.
            if (!is_email($_POST['issuer_username']))
            {
                echo "Email Not Valid";
            }
            else
            {//assuming that account is created but first time requesting token.
                //current assumption email and password given manually at the time.
                $url =  "https://api.eu.badgr.io/o/token?username=".$_POST['issuer_username']."&password=".$_POST['issuer_password'];
                $response =  json_decode(wp_remote_post($url)['body']);
                if (property_exists($response,'error'))
                {
                    echo $response->error;
                }
                else
                {
                    if (file_put_contents($location_file,json_encode($response,JSON_UNESCAPED_SLASHES)))
                        echo "saved";
                    else
                        echo "problem saving to file";
                }
            }
        }
        wp_die();
    }


    /**ISSUER ACTIONS */
    /**
     * Token exists in the installation folders.
     * Get request to badgrAPI to see if this user exists as issuer.
     * 
     * @author  @CharalamposTheodorou
     * @since   2.0
     */
    public function ajaxIssuerExistRequest()
    {
        $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
        $issuer_info = json_decode(file_get_contents($location_file));
        //this shouldn't be triggered. Token is taken cared before.
        if (!$issuer_info)
        {
            echo "Not Found";
        }
        else
        {//token file exists
            //check part if issuer exists in Badgr.
            $args['headers']['Authorization'] = $issuer_info->token_type.' '.$issuer_info->access_token;
            $url = "https://api.eu.badgr.io/v2/issuers";
            $response =  json_decode(wp_remote_get($url,$args)['body']);
            if (count($response->result)==0)
            {//results=[]. No issuer Profile for this Badgr account and Token
            //creating POST request for new issuer Profile.
                if (!empty($_POST['issuer_profile']))
                {//creates the POST request for creating a new Issuer
                    $args = $_POST['issuer_profile'];
                    $args['body']['email'] ='charalampostheodorou2@gmail.com';//this should be removed later. email given dynamically but not configured yet.
                    $response = json_decode(wp_remote_post($url,$args)['body']);
                    print_r($response);
                }
            }
            else
            {print_r($response);    echo 'already exists';}

        }
        wp_die();
/* 
        $url = "https://api.eu.badgr.io/v2/users/self";
        $args['headers']['Authorization'] = $issuer_info['token_type'].$issuer_info['access_token'];
        //$response = wp_remote_get($url,$args);
        print_r($response['body']);

        //if user exists as issuer:
        if (property_exists($response['body'],'entityId'))
        {
            echo 'exists';
        }
        else
        {
            echo 'create new issuer';
            $url = "https://api.eu.badgr.io/v2/issuers";
            $args['headers']['Authorization'] = $issuer_info['token_type'].$issuer_info['access_token'];
            $args['body'] = $_POST['issuer_profile_data'];
            //$response = wp_remote_post($url,)
        } */
    }
    
    public function ajaxImageDataURIRequest()
    {
        if (!empty($_POST['image_path']))
        {   
            $path = $_POST['image_path'];
            $data = file_get_contents($path);
            $base64 = 'data:image/png;base64,' . base64_encode($data);
            echo $base64; 
        } 
        else
            echo 'fail';  
        wp_die();
    }

}
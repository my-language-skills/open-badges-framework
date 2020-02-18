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


    /** API Badgr ACTIONS */


    /**
     * Updates the contens to the local file that stores all the info about issuer,
     * Badge Classes and Assertions to users.
     *  
     * @author  @CharalamposTheodorou
     * @since   2.0
     */
    public function updateBadgrEntitiesFile()
    {
        $ajax_response;
        if (!empty($_POST['section']) && !empty($_POST['data']))
        {
            $badgr_entities_location = parent::getJsonFolderPath()."badgr-entity_ids.json";
            $badgr_entities = json_decode(file_get_contents($badgr_entities_location));
            
            if ($_POST['section'] == "issuer")
            {//update issuer info (should only happen once)
                if (!$badgr_entities || $badgr_entities['issuer']['entity_id'] != $_POST['data']['entityId'])
                {//update badgr content file here
                    $badgr_entities['issuer']['entity_id'] = $_POST['data']['entityId'];
                    $badgr_entities['issuer']['name'] = $_POST['data']['name'];
                    if (file_put_contents($badgr_entities_location,json_encode($badgr_entities,JSON_UNESCAPED_SLASHES)))
                    {    
                        $ajax_response['update'] = "Issuer info updated to local file";
                        $ajax_response['success'] = "success";
                    }
                    else
                        $ajax_response['errors'] = "error in storing issuer info to local file";
                    
                }
            }
            else if ($_POST['section'] == "badge")
            {//update badge classes info
                //new entry to badgeClasses field
                //check if already created or should create first entry
                $ajax_response['before'] = $badgr_entities;

                $new_entry['entity_id'] = $_POST['data']['entityId'];
                $new_entry['name'] = $_POST['data']['name'];
                //if created before
                if (!property_exists($badgr_entities,"badgeClasses"))
                {//first time adding a badge class to file
                    $badgr_entities->badgeClasses = [];
                }
                //adding the new badge information to badgr content file.
                array_push($badgr_entities->badgeClasses,$new_entry);
                $ajax_response['after'] = $badgr_entities;
                if (file_put_contents($badgr_entities_location,json_encode($badgr_entities,JSON_UNESCAPED_SLASHES)))
                {
                    $ajax_response['udpate'] = "First Badge Class created and added to local file";
                    $ajax_response['success'] = "success";
                }
                else
                    $ajax_response['errors'] = "error in storing badge info to local file";
            }
            else if ($_POST['section'] == "assertion")
            {//update assertion field in badgeclasses 
                $new_entry['entity_id'] = $_POST['data']['entityId'];
                $new_entry['recipient_email'] = $_POST['data']['recipient'];
                $badge_id = $_POST['data']['badge_id'];
                $pos = 0;
                for ($pos; $pos<count($badgr_entities->badgeClasses); $pos++)
                    if ($badgr_entities->badgeClasses[$pos]->entity_id == $_POST['data']['badge_id'])
                        break;
                if ($pos == count($badgr_entities->badgeClasses))
                {
                    $ajax_response['errors'] = "Problem locating position in badgr content file";
                }
                else
                {   //check to see if first time adding an assertion to a badgeclass
                    if (!property_exists($badgr_entities->badgeClasses[$pos],"assertions"))
                    {
                        $badgr_entities->badgeClasses[$pos]->assertions = [];
                    }
                    //adding new assertion to badge class 
                    array_push($badgr_entities->badgeClasses[$pos]->assertions,$new_entry);
                    //updating file of badgr_entities
                    if (file_put_contents($badgr_entities_location,json_encode($badgr_entities,JSON_UNESCAPED_SLASHES)))
                    {
                        $ajax_response['udpate'] = "Assertion added to badge class";
                        $ajax_response['success'] = "success";
                    }
                    else
                        $ajax_response['errors'] = "error in storing assertion info to local file";
                }
                
            }

        }
        else
            array_push($ajax_response['errors']="data to store is not found");
        
        print_r(json_encode($ajax_response,JSON_UNESCAPED_SLASHES));
        wp_die();
    }
    /**
     * Creates the issuer entity for the next post request for BadgrAPI.
     * Makes all necessary checks about necessary files and properties.
     * 
     * @author  @CharalamposTheodorou
     * @since   2.0
     */
    public function checkAndCreateIssuerEntity()
    {
        $ajax_response;
        //stores the entities that need to be created via post request to the api
        $ajax_response['create'] = Array();
        //stores the errors encountered in the process of creating the requests format.
        $ajax_response['errors'] = Array();
        
        //file that stores the issuer token information.
        $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
        $issuer_token_info = json_decode(file_get_contents($location_file));
        if (!empty($_POST['data']))
        {//if data not given then nothing to do to proceed
            if ($issuer_token_info)
            {//token file exists and previous checks about refresh were already done.
                //cheching if issuer exists. POST request
                $args['headers']['Authorization'] = $issuer_token_info->token_type.' '.$issuer_token_info->access_token;
                $url = "https://api.eu.badgr.io/v2/issuers";
                $response =  json_decode(wp_remote_get($url,$args)['body']);
                if (count($response->result)==0)
                {//issuer is not created. Prepare issuer body for request.

                    //getting the issuer information from already stored json file (plugin file)
                    $issuer_file_location = parent::getJsonFolderPath().'issuer-info.json';
                    $issuer_data = json_decode(file_get_contents($issuer_file_location));
                    if ($issuer_data)
                    {//issuer plugin file exists (always true)
                        //field that lists the requests to happen.
                        array_push($ajax_response['create'],"issuer");
                        //creating body of request.

                        //image must be changed to data base64 data URI format.
                        $image_path = wp_upload_dir()['baseurl'].substr($issuer_data->image,strpos($issuer_data->image,'uploads')+strlen('uploads'));
                        $image_relative_path =  str_replace('http:/','/var/www/vhosts/badges4languages.com',$image_path);  
                        //raw format of picture.
                        $image_data  = file_get_contents($image_relative_path);
                        if (!$image_data)
                        {
                            array_push($ajax_response['errors'],"issuer image doesn't exists in ".$image_path);
                        }
                        else
                        {//image is found, creating base64 format.
                            //image in base64 data URI format. for issuer POST request.
                            $base64 = 'data:image/png;base64,' . base64_encode($image_data);
                            $issuer_data->image = $base64;
                            //all checks are completed, image created. creating the issuer request format.
                            $ajax_response['issuer']['url'] = "https://api.eu.badgr.io/v2/issuers";
                            $ajax_response['issuer']['method'] = "POST";
                            $ajax_response['issuer']['timeout'] = 0;
                            $ajax_response['issuer']['headers']['Authorization'] = $issuer_token_info->token_type.' '.$issuer_token_info->access_token;
                            $ajax_response['issuer']['headers']['Content_type'] = "application/json";
                            $ajax_response['issuer']['data'] = $issuer_data;
                           
                        }
                    }
                    else
                    {
                        array_push($ajax_response['errors'],'issuer-info.json file is missing from /uploads/open-badges-framework');
                    }
                }
                $ajax_response['data'] = $_POST['data'];//carries the data for the badge process.
            }
            else
            {//token doesn't exists
                array_push($ajax_response['errors'],'token is not configured correctly');
            }
        }
        else
        {//cannot continue with no data.
            array_push($ajax_response['errors'],'data is not received to create the requests');
        }
        
        print_r(json_encode($ajax_response,JSON_UNESCAPED_SLASHES));
        wp_die();
    }

    /**
     * Creates the badge class entity for the next post request for BadgrAPI.
     * Makes all necessary checks about necessary files and properties.
     * 
     * @author  @CharalamposTheodorou
     * @since   2.0
     */
    public function checkAndCreateBadgeClassEntity()
    {
        $ajax_response;
        //stores the entities that need to be created via post request to the api
        $ajax_response['create'] = Array();
        //stores the errors encountered in the process of creating the requests format.
        $ajax_response['errors'] = Array();

        //file that stores the issuer token information.
        $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
        $issuer_token_info = json_decode(file_get_contents($location_file));
        if (!empty($_POST['data']))
        {//if data not given then nothing to do to proceed
            if ($issuer_token_info)
            {//token file exists and previous checks about refresh were already done.
                //file that contains the ids of issuer, badge classes and assertions.
                $badgr_file_location = parent::getJsonFolderPath() . "badgr-entity_ids.json";
                $badgr_file_contents = json_decode(file_get_contents($badgr_file_location));
                if ($badgr_file_contents)
                {//badgr entities retrieved 
                    
                    $issuer_id = $badgr_file_contents->issuer->entity_id;//to be used for the POST request if required.
                    $badgeClasses = $badgr_file_contents->badgeClasses;//access all badge classes published to Badgr.
                    
                    //next step is to get the badge file (previous format) and get it's data.
                    $badge_file_location =  parent::getJsonFolderPath().substr($_POST['data']['badge'],strpos($_POST['data']['badge'],'/json/')+6);
                    $badge_file_contents = json_decode(file_get_contents($badge_file_location));
                    if ($badge_file_contents)
                    {//previous file of badge info exists. continue process
                        
                        //loop through badge contents of badge file (entities - names)
                        $found = "false";
                        if ($badgeClasses != null)
                        {//already issued some badge classes. check if already created this one.
                            for ($pos = 0 ; $pos<count($badgeClasses); $pos++)
                            {//looping through badge classes issued before (if a match is found with this request)
                                if ($badgeClasses[$pos]->name == $badge_file_contents->name)
                                {
                                    $found = "true";
                                    break;
                                }
                            }
                        }
                        if ($found == "true")
                        {//badgeClass found, nothing to do here.
                            array_push($ajax_response['update'],'badge class exists');
                        }
                        else
                        {//badge class not found, creating format for post request here
                            array_push($ajax_response['update'],'badge class not exists');
                            //creating the badge class request.
                            array_push($ajax_response['create'],"badgeClass");
                            //creating body of request.

                            //image must be changed to data base64 data URI format.
                            $image_path = wp_upload_dir()['baseurl'].substr($badge_file_contents->image,strpos($badge_file_contents->image,'uploads')+strlen('uploads'));
                            $image_relative_path =  str_replace('http:/','/var/www/vhosts/badges4languages.com',$image_path);
                            $image_data = file_get_contents($image_relative_path);
                            if (!$image_data)
                            {//image file not found
                                array_push($ajax_response['errors'],"badge image file not found");
                            }
                            else
                            {  
                                //image in base64 data URI format. for issuer POST request.
                                $base64 = 'data:image/png;base64,' . base64_encode($image_data);
                                $badge_file_contents->image = $base64;
                                $badge_file_contents->issuer = $issuer_id;
                                //all checks are completed, image created. creating the badge class request format.
                                $ajax_response['badge']['url'] = "https://api.eu.badgr.io/v2/issuers/".$issuer_id."/badgeclasses";
                                $ajax_response['badge']['method'] = "POST";
                                $ajax_response['badge']['timeout'] = 0;
                                $ajax_response['badge']['headers']['Authorization'] = $issuer_token_info->token_type.' '.$issuer_token_info->access_token;
                                $ajax_response['badge']['headers']['Content_type'] = "application/json";
                                $ajax_response['badge']['data'] = $badge_file_contents;
                            }
                        }
                    }
                    else
                    {
                        array_push($ajax_response['errors'],'badge json file not found');
                    }
                }
                else
                {//badgr file doesn't exist. MUST for getting the issuer entity id
                    array_push($ajax_response['errors'],'badgr entities file not found');
                }
                $ajax_response['data'] = $_POST['data'];//carries the data for assertion request.
            }
            else
            {//token doesn't exists
                array_push($ajax_response['errors'],'token is not configured correctly');
            }
            
        }
        else
        {//cannot continue with no data.
            array_push($ajax_response['errors'],'data is not received to create the requests');
        }

        print_r(json_encode($ajax_response,JSON_UNESCAPED_SLASHES));
        wp_die();
    }

    /**
     * Creates the assertion entity for the next post request for BadgrAPI.
     * Makes all necessary checks about necessary files and properties.
     * 
     * @author  @CharalamposTheodorou
     * @since   2.0
     */
    public function checkAndCreateAssertionEntity()
    {
        $ajax_response;
        //stores the entities that need to be created via post request to the api
        $ajax_response['create'] = Array();
        //stores the errors encountered in the process of creating the requests format.
        $ajax_response['errors'] = Array();
        
        //file that stores the issuer token information.
        $location_file = parent::getJsonFolderPath() . "issuer_token_info.json";
        $issuer_token_info = json_decode(file_get_contents($location_file));
        if (!empty($_POST['data']))
        {
            if ($issuer_token_info)
            {//token file exists and previous checks about refresh were already done.
                //cheching if issuer exists. POST request
                $args['headers']['Authorization'] = $issuer_token_info->token_type.' '.$issuer_token_info->access_token;

                //file that contains the ids of issuer, badge classes and assertions.
                $badgr_file_location = parent::getJsonFolderPath() . "badgr-entity_ids.json";
                $badgr_file_contents = json_decode(file_get_contents($badgr_file_location));
                if ($badgr_file_contents)
                {//badgr entities retrieved 
                    $issuer_id = $badgr_file_contents->issuer->entity_id;//to be used for the POST request if required.
                    $badgeClasses = $badgr_file_contents->badgeClasses;//access all badge classes published to Badgr.

                        //next step is to get the badge file (previous format) and get it's data.
                    $badge_file_location =  parent::getJsonFolderPath().substr($_POST['data']['badge'],strpos($_POST['data']['badge'],'/json/')+6);
                    $badge_file_contents = json_decode(file_get_contents($badge_file_location));
                    if ($badge_file_contents)
                    {//previous file of badge info exists. continue process
                        $badge_name = $badge_file_contents->name;//to compare with assertions in badgr file.
                        $badge_entityID ="";//to update when found or created.
                        $found = "false";
                        if ($badgeClasses !=null)
                        {//locate the badgeClass entry and check its assertions.
                            for ($pos = 0; $pos<count($badgeClasses); $pos++)
                            {//badgeClass located
                                if ($badgeClasses[$pos]->name == $badge_name)
                                {
                                    $badge_entityID = $badgeClasses[$pos]->entity_id;//getting the id for the get request later
                                    for ($pos2 = 0 ;$pos2 <count($badgeClasses[$pos]->assertions); $pos2++)
                                    {//looping through assertions to see if issued before to same user
                                        if($_POST['data']['recipient']['identity'] == $badgeClasses[$pos]->assertions[$pos2]->recipient_email)
                                        {//match found, already issued this badge to this user.
                                            $found = "true";
                                            break;
                                        }
                                    }
                                    break;//to exit first for loop
                                }

                            }
                        }
                        if ($found == "true")
                        {//Assertion for this badge and user exists
                            array_push($ajax_response['update'],'assertion exists');
                        }
                        else
                        {//Assertion for this user and badge has to be created. Prepearing assertion POST request
                            array_push($ajax_response['update'],'assertionnot exists');
                            //creating the assertion class request.
                            array_push($ajax_response['create'],"Assertion");
                            //creating body of request.

                            //get request to Badgr API to get badge class entity url for linking to assertion.
                            $url = "https://api.eu.badgr.io/v2/badgeclasses/".$badge_entityID;
                            $response = json_decode(wp_remote_get($url,$args)['body']);
                            if ($response->status->description == "ok")
                            {//badge class found with given id
                                $badge_link = $response->result[0]->openBadgeId;//to link to assertion.
                                
                                $ajax_response['assertion']['url'] = "https://api.eu.badgr.io/v2/badgeclasses/".$badge_entityID."/assertions";
                                $ajax_response['assertion']['method'] = "POST";
                                $ajax_response['assertion']['timeout'] = 0;
                                $ajax_response['assertion']['headers']['Authorization'] = $issuer_token_info->token_type.' '.$issuer_token_info->access_token;
                                $ajax_response['assertion']['headers']['Content_type'] = "application/json";
                                //change to badge field:
                                $temp = $_POST['data'];
                                $temp['badge'] = $badge_link;
                                $ajax_response['assertion']['data'] = $temp;
                            }
                            else
                            {
                                array_push($ajax_response['errors'],'Badge class not found on Badgr server with given id');
                            }
                        }

                    }
                    else
                    {
                        array_push($ajax_response['errors'],'badge json file not found');
                    }
                }
                else
                {
                    array_push($ajax_response['errors'],'badgr entities file not found');
                }
            
                
            }
            else
            {//token doesn't exists
                array_push($ajax_response['errors'],'token is not configured correctly');
            }
        }
        else
        {//cannot continue with no data.
            array_push($ajax_response['errors'],'data is not received to create the requests');
        }
        
        print_r(json_encode($ajax_response,JSON_UNESCAPED_SLASHES)); 
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
        
        $pass = SettingsTemp::getOption(SettingsTemp::FI_BADGR_PASSWORD);
        $email = SettingsTemp::getOption(SettingsTemp::FI_EMAIL_FIELD);
        if (!empty($pass) && !empty($email))
        {     
            //checking if email given is valid.
            if (!is_email($email))
            {
                echo "Email Not Valid";
            }
            else
            {//assuming that account is created but first time requesting token.
                //current assumption email and password given manually at the time.
                $url =  "https://api.eu.badgr.io/o/token?username=".$email."&password=".$pass;
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
        else
        {
            if (empty($email))
                echo "Email is empty";
            else
                echo "Password is not given";
        }
        wp_die();
    }  

}
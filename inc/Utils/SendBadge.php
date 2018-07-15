<?php
namespace Inc\Utils;
use Inc\Base\BaseController;
use Inc\Database\DbModel;
use Inc\Pages\Admin;
use templates\SettingsTemp;
/**
 * Class that permit to send badges.
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class SendBadge{
    const ER_JSON_FILE = "Error json file\n";
    const ER_SEND_EMAIL = "Error email\n";
    const ER_DB_INSERT = "Db insert error.\n";
    const SUCCESS = "Email success.\n";
    const ER_GENERAL = "General error";
    private $badge = null;
    private $jsonMg = null;
    private $wpBadge = null;
    private $field = null;
    private $level = null;
    private $receivers = null;
    private $evidence = null;
    
    /**
     * Initialization of all the variable.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param int    $idBadge   the id of the badge
     * @param int    $idField   the id of the field
     * @param int    $idLevel   the id of the level
     * @param string $info      the additional from the teacher
     * @param array  $receivers the people that will receive the email
     * @param string $classId   the eventual class
     * @param string $evidence  the work of the student in url format
     */
    function __construct($idBadge, $idField, $idLevel, $info, $receivers, $classId = '', $evidence = '',$description) {
        $this->badge = new Badge();
        $this->wpBadge = WPBadge::get($idBadge);
        $this->field = get_term($idField, Admin::TAX_FIELDS);
        $this->level = get_term($idLevel, Admin::TAX_LEVELS);
        $this->receivers = $receivers;
        $this->evidence = $evidence;
		
        //$this->badge->setIdUser($idUser); --> we will set it after for each student
        $this->badge->idBadge = $this->wpBadge->ID;
        $this->badge->idField = $this->field->term_id;
        $this->badge->idLevel = $this->level->term_id;
        $this->badge->idClass = $classId;
        $this->badge->idTeacher = WPUser::getCurrentUser()->ID;
        $this->badge->teacherRole = WPUser::getCurrentUser()->ID;
        $this->badge->creationDate = DbModel::now();
        //$this->badge->setJson($json); --> we will set it after
        $this->badge->info = $info;
        $this->badge->evidence = $evidence ? $evidence : "none";
		$this->badge->description = $description;
        $this->jsonMg = new JsonManagement($this->badge);
    }
    /**
     * This class do four important things:
     * 1) creation of the json file;
     * 2) insert or update the information of the receiver in the database;
     * 3) save the badge that is sent in the database;
     * 4) creation of the email body;
     * 5) send the email.
     *
     * @author   @AleRiccardi
     * @since    1.0.0
     *
     * @return string to determinate the status of the process.
     */
    public function send() {
        $options = get_option(SettingsTemp::OPTION_NAME);
        //$subject = "Badge: " . $this->wpBadge->post_title . " Field: " . $this->field->name;
		
		$teacherID = WPUser::getCurrentUser()->ID;
		$teacherObj =  get_userdata($teacherID);
		$teacherFirst = $teacherObj->first_name;
		$teacherLast = $teacherObj->last_name;
		$teacherUserName = $teacherObj->user_login;
		
		if(($teacherFirst) && ($teacherLast)){
			$subject =$teacherFirst." ".$teacherLast." has sent you a Badge for your ".$this->field->name." class";
		}else if((!$teacherFirst)&& (!$teacherLast)){
			$subject =$teacherUserName." has sent you a Badge for your ".$this->field->name." class";
		}else if(($teacherFirst)&& (!$teacherLast)){
			$subject =$teacherFirst." has sent you a Badge for your ".$this->field->name." class";
		}else if((!$teacherFirst)&& ($teacherLast)){
			$subject =$teacherLast." has sent you a Badge for your ".$this->field->name." class";
		}
		
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            "From: " . isset($options[SettingsTemp::FI_SITE_NAME_FIELD]) ? $options[SettingsTemp::FI_SITE_NAME_FIELD] : '' .
            " &lt;" . isset($options[SettingsTemp::FI_EMAIL_FIELD]) ? $options[SettingsTemp::FI_EMAIL_FIELD] : '',
        );
        if (is_array($this->receivers)) {
            foreach ($this->receivers as $email) {
                ### 1) creation of the json file;
                if ($jsonName = $this->jsonMg->creation($email)) {
                    ### 2) insert or update the information of the receiver in the database
                    if($idDbUser = WPUser::insertUserInDB($email)){
                        ### 3) save the badge that is sent in the database
                        if($idDbBadge = $this->badge->saveBadgeInDb($idDbUser, $jsonName)){
                            #### 4) creation of the email body
                            if($message = $this->getBodyEmail($idDbBadge)) {
                                ### 5) send the email
                                $retEmail = wp_mail($email, $subject, $message, $headers);
                                if (!$retEmail) return self::ER_SEND_EMAIL;
                            } else {
                                echo "Error send email for $email \n";
                            }
                        } else {
                            echo "Error save badge in db for $email \n";
                        }
                    } else {
                        echo "Error insert user in db for $email \n";
                    }
                } else {
                    return self::ER_JSON_FILE;
                }
            }
            return self::SUCCESS;
        } else {
            return self::ER_GENERAL;
        }
    }
    /**
     * Function that permit to create the body of the email.
     *
     * @author   @AleRiccardi
     * @since    1.0.0
     *
     * @param int $idDbBadge id of the database row of the badge.
     *
     * @return string the body of the email in html format
     */
    private function getBodyEmail($idDbBadge) {
        $badgeLink = Badge::getLinkGetBadge($idDbBadge);
        $options = get_option(SettingsTemp::OPTION_NAME);
		

		
		
        // retrieving the values of the Email Settings section and displaying to the email that we send
		
        $compName = isset($options[SettingsTemp::FI_SITE_NAME_EMAIL_FIELD]) ? $options[SettingsTemp::FI_SITE_NAME_EMAIL_FIELD] : '';
        $compUrl = isset($options[SettingsTemp::FI_WEBSITE_URL_EMAIL_FIELD]) ? $options[SettingsTemp::FI_WEBSITE_URL_EMAIL_FIELD] : '';
		$compEmail = isset($options[SettingsTemp::FI_CONTACT_EMAIL_FIELD]) ? $options[SettingsTemp::FI_CONTACT_EMAIL_FIELD] : '';
        $compUrlImg = isset($options[SettingsTemp::FI_IMAGE_URL_EMAIL_FIELD]) ? wp_get_attachment_image_src($options[SettingsTemp::FI_IMAGE_URL_EMAIL_FIELD]) : '';
        $header = isset($options[SettingsTemp::FI_HEADER_EMAIL_FIELD]) ? $options[SettingsTemp::FI_HEADER_EMAIL_FIELD] : '';
        $message = isset($options[SettingsTemp::FI_MESSAGE_EMAIL_FIELD]) ? $options[SettingsTemp::FI_MESSAGE_EMAIL_FIELD] : '';
		
		
         $body = "
            <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                <html xmlns='http://www.w3.org/1999/xhtml'>
                    <head>
                            <meta http-equiv='Content-Type' content='text/html'; charset='utf-8' />
                    </head>
                    <body>
                        <div id='b4l-award-actions-wrap'>
                            <div align='center'>
								<img src='" . $compUrlImg[0] . "/>
                                <h1>$compName</h1>
                                 $header	
                                <center>
                                    <a href='" . $badgeLink . "'>
                                        <img src='" . WPBadge::getUrlImage($this->wpBadge->ID) . "' width='150' height='150'/>
                                    </a>
                                </center>
                                <h2>" . $this->wpBadge->post_title . " - " . $this->field->name . "</h2>
                                <p>$message</p>
                                <a href='" . $badgeLink . "'>$badgeLink</a>
                                <br><br><hr>
                                <p style='font-size:9px; color:grey '>$compName </p>
                                
								<p style='font-size:9px; color:grey '>
                               More information <a href='$compUrl'>here</a>.
							   Contact us <a href='mailto:$compEmail'>here</a>.;
                               </p>
                            </div>
                        </div>
                    </body>
            </html>
                ";
        return $body; 
    }
}
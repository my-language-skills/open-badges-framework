<?php
/**
 * Class Badge which allows interactions with the school badges in the plugin.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/utils
 * @since 1.0.0
*/

class Badge
{
  /**
  *  $name The name of the badge.
  */
  var $name;
  /**
  *  $level The level of the badge.
  */
  var $level;
  /**
  *  $language The language of the badge.
  */
  var $language;
  /**
  *  $certified To know if the badge is certified or not.
  */
  var $certified;
  /**
  *  $comment The comment written by the sender about the badge.
  */
  var $comment;
  /**
  *  $description The description of the badge selected by the sender.
  */
  var $description;
  /**
  *  $description_language The language description of the badge selected by the sender.
  */
  var $description_language;
  /**
  *  $image The image of the badge.
  */
  var $image;
  /**
  *  $url_json_files The url (extern location) of the json files directory.
  */
  var $url_json_files;
  /**
  *  $path_dir_json_files The path (intern location) of the json files directory.
  */
  var $path_dir_json_files;

  /**
   * The constructor of the Badge object.
   *
   * @author Nicolas TORION
   * @since 1.0.0
   * @param $_name The name of the badge.
   * @param $_level The level of the badge.
   * @param $_language The language of the badge.
   * @param $_comment The comment written by the sender about the badge.
   * @param $_description The description of the badge selected by the sender.
   * @param $_image The image of the badge.
   * @param $_url_json_files The url (extern location) of the json files directory.
   * @param $_path_dir_json_files The path (intern location) of the json files directory.
   */
  function __construct($_name, $_level, $_language, $_certified, $_comment, $_description, $_description_language, $_image, $_url_json_files, $_path_dir_json_files) {
    $this->name = $_name;
    $this->level = $_level;
    $this->language = $_language;
    $this->certified = $_certified;
    $this->comment = $_comment;
    $this->description = $_description;
    $this->description_language = $_description_language;
    $this->image = urldecode(str_replace("\\", "", $_image));
    $this->url_json_files = $_url_json_files;
    $this->path_dir_json_files = $_path_dir_json_files;
  }

  /**
   * Creates the json files for sending the Badge into a Mozilla Backpack account
   *
   * @author Nicolas TORION
   * @since 1.0.0
   * @param $receiver The mail of the person who will receive the Badge.
   */
  function create_json_files($receiver) {
    //creates the folders recursively if they initially don't exist
    if (!file_exists($this->path_dir_json_files))
        mkdir($this->path_dir_json_files, 0777, true);

    $hash_name = hash("sha256", $receiver.$this->name.$this->language);
    $badge_filename = 'badge_'.$hash_name.'.json';
    $assertion_filename = "assertion_".$hash_name.'.json';

    $this->create_badge_json_file($badge_filename);
    $this->create_assertion_json_file($receiver, $badge_filename, $assertion_filename);
  }

  /**
   * Creates the badge json file for sending the Badge into a Mozilla Backpack account
   *
   * @author Nicolas TORION
   * @since 1.0.0
   * @param $badge_filename The name of the badge json file.
   */
  function create_badge_json_file($badge_filename) {

    $description_combined = "Language : ".$this->language.", Level : ".$this->level.", Comment : ".$this->comment.", Description : ".$this->description;

    $badge_informations = array(
      '@context'=>'https://w3id.org/openbadges/v1',
      "name"=>$this->name." ".$this->language,
      "description"=>$description_combined,
      "image"=>$this->image,
      "language"=>$this->language,
      "level"=>$this->level,
      "criteria"=>"http://".$_SERVER['SERVER_NAME']."/badge/".strtolower($this->level),
    	"issuer"=>$this->url_json_files."badge-issuer.json"
    );

    $file = $this->path_dir_json_files.$badge_filename;
    file_put_contents($file, json_encode($badge_informations, JSON_UNESCAPED_SLASHES));
  }

  /**
   * Creates the assertion json file for sending the Badge into a Mozilla Backpack account
   *
   * @author Nicolas TORION
   * @since 1.0.0
   * @param $receiver The mail of the person who will receive the Badge.
   * @param $badge_filename The name of the badge json file.
   * @param $assertion_filename The name of the assertion json file.
   */
  function create_assertion_json_file($receiver, $badge_filename, $assertion_filename) {
    $salt=uniqid();
    $date=date('Y-m-d');

    $assertion = array(
      "uid" => $salt,
      "recipient" => array("type" => "email", "identity" => $receiver, "hashed" => false),
      "issuedOn" =>  $date,
      "badge" => $this->url_json_files.$badge_filename,
      "verify" => array("type" => "hosted", "url" => $this->url_json_files.$assertion_filename)
    );

    $file = $this->path_dir_json_files.$assertion_filename;
    file_put_contents($file, json_encode($assertion, JSON_UNESCAPED_SLASHES));
  }

  /**
   * Sends a mail to the person who receive the badge, to give her the link of the page where she can get his badge.
   *
   * @author Nicolas TORION
   * @since 1.0.0
   * @param $receiver The mail of the person who will receive the Badge.
   * @param $class_id The ID of the class in which the student is.
   * @return A boolean to know if the mail has been sent.
   */
  function send_mail($receiver, $class_id) {
    $hash_name = hash("sha256", $receiver.$this->name.$this->language);
    $url_mail = plugins_url( './get_badge.php', __FILE__ );
    $url_mail = $url_mail."?hash=".$hash_name."&level=".$this->level."&language=".$this->language;
    $settings_id_login_links = get_settings_login_links();

    if(!is_null($class_id))
      $url_mail = $url_mail."&class=".$class_id;

    $subject = __("Your teacher of $this->language just send you a badge",'badges-issuer-for-wp'); //entering a subject for email

    //Message displayed in the email
    $message= __('
    <html>
            <head>
                    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
            </head>
            <body>
                <div id="b4l-award-actions-wrap">
                    <div align="center">
                        <h1>BADGES FOR LANGUAGES</h1>
                        <br />
                        <h1><b>Congratulations: You have just earned a badge
                        <br />
                        '.$this->language.' language - '.$this->name.' level!</b></h1>
                        <h2>The following steps will help you to receive a badge.
                        <br />
                        If you allready have badges, go to the step 3</h2>
                        <hr/ >
                        <H1> Step One: Loggin into Badges for languages </H1>
                        <h2>Necesary for getting the badge and to write a teacher review</h2>
                        <center><img src="'.plugins_url( "../../assets/b4l_logo.png", __FILE__ ).'" width="150" height="150"/></center>
                        <a href="'.get_page_link($settings_id_login_links["link_register"]).'">Register</a> | <a href="'.get_page_link($settings_id_login_links["link_login"]).'">Login</a>
                        <br />
                        Just registered users can receive badges.
                        <hr/ >
                        <H1> Step Two: Open a Mozzilla OpenBadges Backpack account </H1>
                        <h2>Moz://a Backpack Store and Share your Open Badges.</h2>
                        <br/ >
                        <center><img src="'.plugins_url( "../../assets/openbadges_logo_thumbnail.png", __FILE__ ).'" width="300" height="100"/></center>
                        <br/ >
                        <a href="https://backpack.openbadges.org/backpack/signup">register</a> | <a href="https://backpack.openbadges.org/backpack/login">loggin</a>
                        <br/ >
                        <br/ >
                        Badges for languages uses Mozzilla <a href="https://openbadges.org/">OpenBadges</a> and his <a href="https://backpack.openbadges.org/">backpack</a>, a tool for collecting, managing and controlling privacy settings for badges you have been awarded as an earner.</h2>
                        <br />
                        <hr/>
                        <H1> Step Three: Open the link, and get the badge. </H1>
                        <h2> Don´t forget to write a teacher review</h2>
                        <br />
                        <h2>'.$this->name.' - '.$this->language.'</h2>
                        <a href="'.$url_mail.'">
                            <img src="'.$this->image.'" width="150" height="150"/>
                        </a>
                        <br>
                        <br />
                        <a href="'.$url_mail.'">'.$url_mail.'</a>
                        <br /><br />
                        For a better education, we need to start by evaluating teacher performance. Once you receive the badge, the review section of your teacher\'s class would be open.
                        <br/ >
                        <div class="browserSupport"><b>Please use Firefox or Google Chrome to retrieve your badge.<b></div>
                        <br />
                        <hr/>
                        <p style="font-size:9px; color:grey"><a href="http://badges4languages.com/">Badges for Languages</a> by My Language Skills, based in Valencia, Spain.
                        More information <a href="https://mylanguageskills.wordpress.com/">here</a>.
                        Legal information <a href="https://mylanguageskillslegal.wordpress.com/category/english/badges-for-languages-english/">here</a>.
                        </p>
                    </div>
                </div>
            </body>
    </html>
    ','badges-issuer-for-wp');

    //Setting headers so it's a MIME mail and a html
    $headers = "From: badges4languages <mylanguageskills@hotmail.com>\n";
    $headers .= "MIME-Version: 1.0"."\n";
    $headers .= "Content-type: text/html; charset=utf-8"."\n";
    $headers .= "Reply-To: mylanguageskills@hotmail.com\n";

    return mail($receiver, $subject, $message, $headers); //Sending the emails
  }

  function console_log($content) {
    echo "<script>console.log('".$content."');</script>";
  }

  /**
   * Add the student to the class selected by the teacher.
   *
   * @author Nicolas TORION
   * @since 1.0.0
   * @param $mail The mail of the person who receive the badge.
   * @param $class_id The ID of the class post selected.
   */
  function add_student_to_class($mail, $class_id) {
    if(!is_null($class_id)) {
      $student = get_user_by_email($mail);
      if($student) {
        $student_infos = array(
          'login' => $student->user_login,
          'level' => $this->level,
          'language' => $this->language,
          'date' => date("Y-m-d")
        );
      }
      else {
        $student_infos = array(
          'login' => $mail,
          'level' => $this->level,
          'language' => $this->language,
          'date' => date("Y-m-d")
        );
      }

      if(!get_post_meta($class_id, '_class_students', true))
        $class_students = array();
      else
        $class_students = get_post_meta($class_id, '_class_students', true);

      $class_students[] = $student_infos;
      update_post_meta($class_id,'_class_students', $class_students);
    }
  }

  /**
   * Add the student to the class zero of the teacher.
   *
   * @author Nicolas TORION
   * @since 1.0.0
   * @param $mail The mail of the person who receive the badge.
   */
  function add_student_to_class_zero($mail) {
    global $current_user;
    get_currentuserinfo();

    if(in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles)) {
      $student = get_user_by_email($mail);
      $login = "no account";

      if($student)
        $login = $student->user_login;

      $student_infos = array(
        'login' => $login,
        'mail' => $mail,
        'level' => $this->level,
        'language' => $this->language,
        'date' => date("Y-m-d")
      );

      $class = get_class_zero_teacher($current_user->user_login);
      if(!get_post_meta($class->ID, '_class_students', true))
        $class_students = array();
      else
        $class_students = get_post_meta($class->ID, '_class_students', true);

      $class_students[] = $student_infos;
      update_post_meta($class->ID,'_class_students', $class_students);
    }
  }

  /**
   * Add the badge informations into the user profile of the person who receive the badge.
   *
   * @author Nicolas TORION
   * @since 1.0.0
   * @param $mail The mail of the person who receive the badge.
   * @param $sender The mail of the person who is sending the badge.
   */
  function add_badge_to_user_profile($mail, $sender, $class_id) {
    $user_informations = get_user_by_email($mail);
    $badges = get_the_author_meta( 'user_badges', $user_informations->ID );

    $user_roles = $user_informations->roles;
    if($sender=="SELF")
      $sender_type = $sender;
    else {
      if(in_array("teacher", $user_roles))
        $sender_type = "teacher";
      elseif(in_array("academy", $user_roles))
        $sender_type = "academy";
      elseif(in_array("administrator", $user_roles))
        $sender_type = "administrator";
      elseif(in_array("editor", $user_roles))
        $sender_type = "editor";
      else
        $sender_tyep = "unknown";
    }

    if(empty($badges))
      $bagdes=array();

    $badge = array(
      'name' => $this->name,
      'language' => $this->language,
      'sender' => $sender,
      'sender_type' => $sender_type,
      'certified' => $this->certified,
      'comment' => $this->comment,
      'level' => $this->level,
      'description_language' => $this->description_language,
      'date' => date("Y-m-d")
    );

    if(!is_null($class_id))
      $badge['class']=get_the_title($class_id);

    $badges[] = $badge;

    update_user_meta( $user_informations->ID, 'user_badges', $badges);
  }

}

?>

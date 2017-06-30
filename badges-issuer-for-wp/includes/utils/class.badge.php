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
  var $name;
  var $level;
  var $language;
  var $comment;
  var $description;
  var $image;
  var $url_json_files;
  var $path_dir_json_files;

  function __construct($_name, $_level, $_language, $_comment, $_description, $_image, $_url_json_files, $_path_dir_json_files) {
    $this->name = $_name;
    $this->level = $_level;
    $this->language = $_language;
    $this->comment = $_comment;
    $this->description = $_description;
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
    if (!file_exists($this->path_dir_json_files)) {
        mkdir($this->path_dir_json_files, 0777, true);
    }

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
      "criteria"=>$this->url_json_files."criteria.html",
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
   * @return A boolean to know if the mail has been sent.
   */
  function send_mail($receiver) {
    $hash_name = hash("sha256", $receiver.$this->name.$this->language);
    $url_mail = "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/utils/get_badge.php?hash=".$hash_name;

    $subject = "Badges4Languages - You have just earned a badge"; //entering a subject for email

    //Message displayed in the email
    $message= '
    <html>
            <head>
                    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
            </head>
            <body>
                <div id="b4l-award-actions-wrap">
                    <div align="center">
                        <h1>BADGES FOR LANGUAGES</h1>
                        <h2>Learn languages and get official certifications</h2>
                        <hr/>
                        <h1>Congratulations you have just earned a badge!</h1>
                        <h2>'.$this->name.' - '.$this->language.'</h2>
                        <a href="'.$url_mail.'">
                            <img src="'.$this->image.'" width="150" height="150"/>
                        </a>
                        </br>
                        <div class="browserSupport"><b>Please use Firefox or Google Chrome to retrieve your badge.<b></div>
                        <hr/>
                        <p style="font-size:9px; color:grey">Badges for Languages by My Language Skills, based in Valencia, Spain.
                        More information <a href="https://mylanguageskills.wordpress.com/">here</a>.
                        Legal information <a href="https://mylanguageskillslegal.wordpress.com/category/english/badges-for-languages-english/">here</a>.
                        </p>
                    </div>
                </div>
            </body>
    </html>
    ';

    //Setting headers so it's a MIME mail and a html
    $headers = "From: badges4languages <colomet@hotmail.com>\n";
    $headers .= "MIME-Version: 1.0"."\n";
    $headers .= "Content-type: text/html; charset=utf-8"."\n";
    $headers .= "Reply-To: colomet@hotmail.com\n";

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
    $student = get_user_by_email($mail);
    if($student) {
      if(!is_null($class_id)) {
        $student_infos = array(
          'login' => $student->user_login,
          'level' => $this->level,
          'language' => $this->language
        );
        $class_students = get_post_meta($class_id, '_class_students', true);
        $class_students[] = $student_infos;
        update_post_meta($class_id,'_class_students', $class_students);
      }
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
  function add_badge_to_user_profile($mail, $sender) {
    $user_informations = get_user_by_email($mail);
    $badges = get_the_author_meta( 'user_badges', $user_informations->ID );

    if(empty($badges))
      $bagdes=array();

    $badges[] = array(
      'name' => $this->name,
      'language' => $this->language,
      'sender' => $sender,
      'comment' => $this->comment
    );

    update_user_meta( $user_informations->ID, 'user_badges', $badges);
  }

}

?>

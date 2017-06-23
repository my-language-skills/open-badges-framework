<?php
/**
 * Util functions to use badges informations.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/utils
 * @since 1.0.0
*/
    wp_localize_script( 'some-script', 'ajaxvariable', array( 'customajax' => plugin_dir_path( dirname( __FILE__ ) ) . 'ajax/custom_ajax.php'));

// GETTERS FUNCTIONS

/**
 * Returns all badges that exist
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @return $badges Array of all badges.
*/
function get_all_badges() {
  $badges = get_posts(array(
    'post_type'   => 'badge',
    'numberposts' => -1
  ));
  return $badges;
}

/**
 * Returns all languages of description of badges given.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges Array of badges.
 * @return $descriptions_languages Array of badges names associated to the available languages of their description.
*/
function get_all_languages_description($badges) {
  $descriptions_languages = array();
  foreach ($badges as $badge) {
    foreach (array_keys(get_badge_descriptions(get_post_meta($badge->ID,"_level",true))) as $lang) {
      $descriptions_languages[$badge->post_title][] = $lang;
    }
  }
  return $descriptions_languages;
}

/**
 * Returns the description of a badge which is writed in the lines given.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badge_name The name of the badge.
 * @param $lines Lines given.
 * @return $description Content of the description of the badge.
*/
function get_badge_description($badge_level, $lines) {
  $description_begin = "==".$badge_level."==\n";
  $i=0;
  $description="";

  while($lines[$i]!=$description_begin && $i<sizeof($lines)) {
    $i++;
  }

  $i++;
  while($lines[$i]!="======\n" && $i<sizeof($lines)) {
    $description=$description.$lines[$i]."\n";
    $i++;
  }

  return $description;
}

/**
 * Returns all the descriptions of a badge.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badge_level The level of the badge.
 * @return $descriptions Array of descriptions of the badge associated to their language.
*/
function get_badge_descriptions($badge_level) {
  $descriptions_dir = plugin_dir_path( dirname( __FILE__ ) )."badges-descriptions/";
  $descriptions_files = scandir($descriptions_dir);
  $descriptions_files = array_diff($descriptions_files, array(".", "..") );
  $descriptions = array();

  foreach ($descriptions_files as $file) {
    $lines = file($descriptions_dir.$file);
    $lang = explode('.', $file)[0];
    $content = get_badge_description($badge_level, $lines);
    if(str_replace("\n", "", $content)!="")
      $descriptions[$lang] = $content;
  }

  return $descriptions;
}

/**
 * Returns the badge informations associated to level and language given.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badge_name The name of the badge.
 * @param $badges A list of badges.
 * @param $lang The language studied by the student.
 * @return Array of badge's informations (name, description, image url).
*/
function get_badge($badge_name, $badges, $lang) {
  foreach ($badges as $badge) {
    if($badge_name==$badge->post_name) {
      $badge_description = get_badge_descriptions(get_post_meta($badge->ID,"_level",true))[$lang];
      return array("name"=>$badge->post_title, "description"=>$badge_description, "image"=>get_the_post_thumbnail_url($badge->ID));
    }
  }
}

/**
 * Returns all levels that exist.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges A list of badges.
 * @return $levels Array of all levels found.
*/
function get_all_levels($badges) {
  $levels = array();
  foreach($badges as $badge){
    $level = get_post_meta($badge->ID,"_level",true);
    if( ! in_array( $level, $levels) )
      $levels[] = $level;
  }
  sort($levels);
  return $levels;
}

/**
 * Returns all badges of a level.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges A list of badges.
 * @return $level The level of bagdes to find.
*/

function get_all_badges_level($badges, $level) {
  $badges_corresponding = array();
  foreach ($badges as $badge) {
    if(get_post_meta($badge->ID,"_level",true)==$level)
      $badges_corresponding[] = $badge;
  }
  return $badges_corresponding;
}

/**
 * Returns all the languages stocked in the languages files.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @return $all_languages All the languages found.
*/
function get_all_languages() {
  $mostimportantlanguages = array();
  $languages = array();
  $handle = fopen(plugin_dir_path( dirname( __FILE__ ) )."languages/languagesSorted.tab", "r");
  $handle2 = fopen(plugin_dir_path(dirname(__FILE__))."languages/mostImportantLanguages.tab", "r");
  if ($handle && $handle2) {
    while (($line = fgets($handle)) !== false) {
      $languages[] = substr(strstr($line,"	"), 1);
    }
    while (($line = fgets($handle2)) !== false) {
      $mostimportantlanguages[] = substr(strstr($line,"	"), 1);
    }
    fclose($handle);
    fclose($handle2);
  } else {
    echo "Error : Can't open languages files !";
  }

  $all_languages = array($mostimportantlanguages, $languages);

  return $all_languages;
}

// DISPLAY FUNCTIONS

/**
 * Displays available levels in input radio tags. Used in the forms sending badges to students.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges A list of badges.
*/
function display_levels_radio_buttons($badges) {
  $levels = get_all_levels($badges);

  echo '<b>Level* :</b><br />';
  foreach ($levels as $l) {
    echo '<label for="level_'.$l.'">'.$l.' </label><input type="radio" class="level" name="level" id="level_'.$l.'" value="'.$l.'"> ';
  }
  echo '<br />';
}

/**
 * Displays available languages in select tag. Used in the forms sending badges to students.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $just_most_important_languages A boolean to know if only the most important languages must be displayed.
 * @param $language_selected The language to select.
 * @param $multiple A boolean to know if the select form must be in multiple mode.
*/
function display_languages_select_form($just_most_important_languages=false, $language_selected="", $multiple=false) {
  $all_languages = get_all_languages();
  $mostimportantlanguages = $all_languages[0];
  $languages = $all_languages[1];

  echo '<label for="language"><b>Language* : </b></label><br /><select name="language';
  if($multiple)
    echo '[]';
  echo '" id="language">';

  echo '<optgroup>';
  foreach ($mostimportantlanguages as $language) {
    $language = str_replace("\n", "", $language);
    echo '<option value="'.$language.'"';
    if($language_selected==$language)
      echo ' selected';
    echo '>'.$language.'</option>';
  }
  echo '</optgroup>';

  if(!$just_most_important_languages) {
    echo '<optgroup label="______________"';
    foreach ($languages as $language) {
      echo '<option value="'.$language.'"';
      if($language_selected==$language)
        echo ' selected';
      echo '>'.$language.'</option>';
    }
    echo '</optgroup>';
  }

  echo '</select><br>';
}

// DISPLAY MESSAGES FUNCTIONS

/**
 * Displays a message of success.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $message The message to display.
*/
function display_success_message($message) {
  ?>
  <div class="message success">
    <?php echo $message; ?>
  </div>
  <?php
}

/**
 * Displays a message of error.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $message The message to display.
*/
function display_error_message($message) {
  ?>
  <div class="message error">
    <?php echo $message; ?>
  </div>
  <?php
}

/**
 * Displays a message indicating that a person is not logged. A link redirecting to the login page is also displayed.
 *
 * @author Nicolas TORION
 * @since 1.0.0
*/
function display_not_logged_message() {
  ?>
  <center>
    <img src="https://mylanguageskills.files.wordpress.com/2015/08/badges4languages-hi.png?w=800" width="400px" height="400px"/>
    <br />
    <h1>To get a badge, you need to be logged on the site.</h1>
    <br />
    <a href="<?php echo wp_login_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>" title="Login">Login</a>
  </center>
  <?php
}

// CREATE JSON FILES FUNCTIONS

/**
 * Creates the badge json file with the badge's informations given.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $level Badge's level.
 * @param $language Badge's language.
 * @param $comment Teacher's comment for the student.
 * @param $others_items Other badge's informations (name, description, image).
 * @param $path_dir_json_files Path of the json files directory in the server.
 * @param $url_json_files Url of the json files directory in extern.
 * @param $badge_filename Name of file that will be created.
*/
function create_badge_json_file($level, $language, $comment, $others_items, $path_dir_json_files, $url_json_files, $badge_filename) {
  $name = $others_items["name"];
  $description = "Language : ".$language.", Level : ".$level.", Comment : ".$comment.", Description : ".$others_items["description"];
  $image = urldecode(str_replace("\\", "", $others_items["image"]));

  $badge_informations = array(
    '@context'=>'https://w3id.org/openbadges/v1',
    "name"=>$name." ".$language,
    "description"=>$description,
    "image"=>$image,
    "language"=>$language,
    "level"=>$level,
    "criteria"=>$url_json_files."criteria.html",
  	"issuer"=>$url_json_files."badge-issuer.json"
  );

  $file = $path_dir_json_files.$badge_filename;
  file_put_contents($file, json_encode($badge_informations, JSON_UNESCAPED_SLASHES));
}

/**
 * Creates the assertion json file with send's informations.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $mail Student's mail adress.
 * @param $path_dir_json_files Path of the json files directory in the server.
 * @param $url_json_files Url of the json files directory in extern.
 * @param $badge_filename Name of the badge json file.
 * @param $assertion_filename Name of the assertion json file that will be created.
*/
function create_assertion_json_file($mail, $path_dir_json_files, $url_json_files, $badge_filename, $assertion_filename) {
  $salt=uniqid();
  $date=date('Y-m-d');

  $assertion = array(
    "uid" => $salt,
    "recipient" => array("type" => "email", "identity" => $mail, "hashed" => false),
    "issuedOn" =>  $date,
    "badge" => $url_json_files.$badge_filename,
    "verify" => array("type" => "hosted", "url" => $url_json_files.$assertion_filename)
  );

  $file = $path_dir_json_files.$assertion_filename;
  file_put_contents($file, json_encode($assertion, JSON_UNESCAPED_SLASHES));
}

function save_badge($mail, $badge_name, $badge_language, $sender, $comment) {

  $user_informations = get_user_by_email($mail);
  $badges = get_the_author_meta( 'user_badges', $user_informations->ID );

  if(empty($badges))
    $bagdes=array();

  $badges[] = array(
    'name' => $badge_name,
    'language' => $badge_language,
    'sender' => $sender,
    'comment' => $comment
  );

  update_user_meta( $user_informations->ID, 'user_badges', $badges);
}

function tm_additional_profile_fields( $user ) {

    $user_badges = get_the_author_meta( 'user_badges', $user->ID );
    ?>
    <h3>Badges</h3>
    <table width="100%">
      <thead>
        <tr>
          <th width="0%">Badge name</th>
          <th width="0%">Badge language</th>
          <th width="0%">Sender</th>
          <th width="0%">Comment</th>
        </tr>
      </thead>
      <tbody>
    <?php

    foreach ($user_badges as $user_badge) {
      echo '<tr>';
        echo '<td width="0%">';
        echo $user_badge['name'];
        echo '</td>';
        echo '<td width="0%">';
        echo $user_badge['language'];
        echo '</td>';
        echo '<td width="0%">';
        echo $user_badge['sender'];
        echo '</td>';
        echo '<td width="0%">';
        echo $user_badge['comment'];
        echo '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';
}

add_action( 'show_user_profile', 'tm_additional_profile_fields' );
add_action( 'edit_user_profile', 'tm_additional_profile_fields' );


// SEND MAIL FUNCTION

/**
 * Sends a mail to the student in order to give him a link where he can get his badge.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $mail Student's adress mail.
 * @param $badge_name Badge's name.
 * @param $badge_language Badge's language.
 * @param $badge_image Badge's image.
 * @param $url Page's URL where the student can get his badge.
 * @return A boolean to know if the mail has been sent.
*/
function send_mail($mail, $badge_name, $badge_language, $badge_image, $url){
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
                        <h2>'.$badge_name.' - '.$badge_language.'</h2>
                        <a href="'.$url.'">
                            <img src="'.$badge_image.'" width="150" height="150"/>
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

    return mail($mail, $subject, $message, $headers); //Sending the emails
}

// CSS STYLES FUNCTIONS

/**
 * Applies css styles of some elements.
 *
 * @author Nicolas TORION
 * @since 1.0.0
*/
function apply_css_styles() {
  ?>

  <style>

  #tabs-elements {
    display: block;
  }

  .active {
    border-width: 2px;
    border-style: solid;
    border-color: #FFF;
    box-shadow: 1px 1px 12px #555;
    transition-duration: 0.3s;
  }

  .tab-element {
    margin-top: -5px;
    background-color: #F7004A;
    font-size: 20px;
    color:#FFF;
    padding: 10px;
    max-width: 200px;
    float: left;
  }

  .tab:hover {
    background-color: #B50036;
  }

  .tab-content {
    display: block;
    background-color: #FFF;
    border-width: 5px;
    border-color: #F7004A;
    border-style: solid;
    padding: 20px;
    margin: 5px;
    border-radius: 20px;
  }

  .input-hidden {
    position: absolute;
    left: -9999px
  }

  input[type=radio]:checked + label>img {
    border: 1px solid #fff;
    box-shadow: 0 0 0px 4px red;
  }

  input[type=radio] + label>img {
    border: 1px solid transparent;
    width: 70px;
    height: 70px;
    transition: 500ms all;
    border-radius: 50%;
    margin: 5px;
  }

  .message {
    padding: 10px;
    border-width: 1px;
    border-radius: 10px;
    border-style: solid;
    font-size: 20px;
    position: absolute;
    top:0;
  }

  .success {
    background-color: #A7DFA9;
    border-color: #2F7D31;
    color: #2F7D31;
  }

  .error {
    background-color: #F66C7A;
    border-color: #D80D21;
    color: #D80D21;
  }
  </style>
  <?php
}

// JAVASCRIPT & JQUERY FUNCTIONS

add_action( 'admin_footer', 'js_form' ); // Write our JS below here
add_action( 'wp_footer', 'js_form' );
/**
 * Loads and displays the available languages of badge's description according to the badge selected.
 *
 * @author Nicolas TORION
 * @since 1.0.0
*/
function js_form() {
  ?>

  <script>
  jQuery("#badge_form_a .level").on("click", function() {

    jQuery("#badge_form_a #select_badge").html("<br /><img src='http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/plugins/badges-issuer-for-wp/images/load.gif' width='50px' height='50px' />");

    var data = {
			'action': 'action_select_badge',
      'form': 'form_a_',
			'level_selected': jQuery("#badge_form_a .level:checked").val()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post("<?php echo "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/ajax/custom_ajax.php"; ?>", data, function(response) {
			jQuery("#badge_form_a #select_badge").html(response);
		});
  });

  jQuery("#badge_form_b .level").on("click", function() {

    jQuery("#badge_form_b #select_badge").html("<br /><img src='http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/plugins/badges-issuer-for-wp/images/load.gif' width='50px' height='50px' />");

    var data = {
			'action': 'action_select_badge',
      'form': 'form_b_',
			'level_selected': jQuery("#badge_form_b .level:checked").val()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post("<?php echo "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/ajax/custom_ajax.php"; ?>", data, function(response) {
			jQuery("#badge_form_b #select_badge").html(response);
		});
  });

  jQuery("#badge_form_c .level").on("click", function() {

    jQuery("#badge_form_c #select_badge").html("<br /><img src='http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/plugins/badges-issuer-for-wp/images/load.gif' width='50px' height='50px' />");

    var data = {
			'action': 'action_select_badge',
      'form': 'form_c_',
			'level_selected': jQuery("#badge_form_c .level:checked").val()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post("<?php echo "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/ajax/custom_ajax.php"; ?>", data, function(response) {
			jQuery("#badge_form_c #select_badge").html(response);
		});
  });
  </script>
  <?php
}

add_action( 'admin_footer', 'js_send_badge_form' ); // Write our JS below here
add_action( 'wp_footer', 'js_send_badge_form' );

function js_send_badge_form() {
  ?>
  <script>
    setInterval(function(){check_badge_form();}, 500);

    function check_mails(mails) {

      var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

      for (var i = 0; i < mails.length; i++) {
        if(!testEmail.test(mails[i])) {
          return false;
        }
      }
      return true;
    }

    function check_badge_form() {
      var badge_a = jQuery("#badge_form_a .input-badge");

      var mails_b = jQuery("#badge_form_b .mail").val().split("\n");
      var badge_b = jQuery("#badge_form_b .input-badge");

      var mails_c = jQuery("#badge_form_c .mail").val().split("\n");
      var badge_c = jQuery("#badge_form_c .input-badge");

      if(!badge_a.is(':checked')) {
        jQuery('#submit_button_a').prop('disabled', true);
      }
      else {
        jQuery('#submit_button_a').prop('disabled', false);
      }

      if(!check_mails(mails_b) || !badge_b.is(':checked')) {
        jQuery('#submit_button_b').prop('disabled', true);
      }
      else {
        jQuery('#submit_button_b').prop('disabled', false);
      }

      if(!check_mails(mails_c) || !badge_c.is(':checked')) {
        jQuery('#submit_button_c').prop('disabled', true);
      }
      else {
        jQuery('#submit_button_c').prop('disabled', false);
      }
    }
  </script>
<?php
}

?>

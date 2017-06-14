<?php

function get_all_badges() {
  $badges = get_posts(array(
    'post_type'   => 'badge',
    'numberposts' => -1
  ));
  return $badges;
}

function get_all_languages_description($badges) {
  $descriptions_languages = array();
  foreach ($badges as $badge) {
    foreach (array_keys(get_badge_descriptions($badge->post_title)) as $lang) {
      $descriptions_languages[$badge->post_title][] = $lang;
    }
  }
  return $descriptions_languages;
}

function get_badge_description($badge_name, $lines) {
  $description_begin = "==".$badge_name."==\n";
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

function get_badge_descriptions($badge_name) {
  $descriptions_dir = plugin_dir_path( dirname( __FILE__ ) )."badges-descriptions/";
  $descriptions_files = scandir($descriptions_dir);
  $descriptions_files = array_diff($descriptions_files, array(".", "..") );
  $descriptions = array();

  foreach ($descriptions_files as $file) {
    $lines = file($descriptions_dir.$file);
    $lang = explode('.', $file)[0];
    $content = get_badge_description($badge_name, $lines);
    if(str_replace("\n", "", $content)!="")
      $descriptions[$lang] = $content;
  }

  return $descriptions;
}

function get_badge($level, $badges, $lang) {
  foreach ($badges as $badge) {
    $badge_level = get_post_meta($badge->ID,"_level",true);
    $badge_description = get_badge_descriptions($badge->post_title)[$lang];
    if($badge_level==$level)
      return array("name"=>$badge->post_title, "description"=>$badge_description, "image"=>get_the_post_thumbnail_url($badge->ID));
  }
}

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

function display_levels_radio_buttons($badges) {
  $levels = get_all_levels($badges);

  echo '<b>Level* :</b><br />';
  foreach ($levels as $l) {
    $badge = get_badge($l, $badges);
    echo '<input type="radio" class="level input-hidden" name="level" id="'.$l.'" value="'.$l.'"><label for="'.$l.'"><img src="'.$badge['image'].'" width="70px" height="70px" /></label>';
  }
  echo '<br />';
}

function display_languages_select_form() {
  $all_languages = get_all_languages();
  $mostimportantlanguages = $all_languages[0];
  $languages = $all_languages[1];

  echo '<label for="language"><b>Language* : </b></label><br /><select name="language" id="language">';

  echo '<optgroup>';
  foreach ($mostimportantlanguages as $language) {
    echo '<option value="'.$language.'">'.$language.'</option>';
  }
  echo '</optgroup>';

  echo '<optgroup label="______________"';
  foreach ($languages as $language) {
    echo '<option value="'.$language.'">'.$language.'</option>';
  }
  echo '</optgroup>';

  echo '</select><br>';
}

function display_languages_description_select_form($badge_name) {
  $translations_description = array_keys(get_badge_descriptions($badge_name));

  echo '<label for="language_description"><b>Language of badge description* : </b></label><br /><select name="language_description" id="language_description">';
  foreach ($translations_description as $lang) {
    echo '<option value="'.$lang.'">'.$lang.'</option>';
  }
  echo '</select><br>';
}

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

function display_success_message($message) {
  ?>
  <div class="message success">
    <?php echo $message; ?>
  </div>
  <?php
}

function display_error_message($message) {
  ?>
  <div class="message error">
    <?php echo $message; ?>
  </div>
  <?php
}

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

function apply_css_styles() {
  ?>
  <style>
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

add_action( 'admin_footer', 'ajax_form' ); // Write our JS below here

function ajax_form() { ?>
  <script>
  <?php
  $badges = get_all_badges();
  $descriptions_languages = get_all_languages_description($badges);

  foreach ($badges as $badge){
    $langs = $descriptions_languages[$badge->post_title];
    echo 'var '.$badge->post_title.'_description_languages = [';
    $i = 0;
    foreach ($langs as $lang) {
      echo "'".$lang."'";
      if($i!=(sizeof($langs)-1))
        echo ', ';
      $i++;
    }
    echo "]; \n";
  }
  ?>
  jQuery(".level").on("click", function() {
    var tab_name = jQuery(".level:checked").val() + "_description_languages";
    var tab = eval(tab_name);

    var content = '<label for="language_description"><b>Language of badge description* : </b></label><br /><select name="language_description" id="language_description">';
    tab.forEach(function(lang) {
      content = content + '<option value="' + lang + '">' + lang + '</option>';
    });

    content = content + '</select><br>';
    jQuery("#result_languages_description").html(content);
  });
  </script>
  <?php
}

?>

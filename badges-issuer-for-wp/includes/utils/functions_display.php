<?php
// DISPLAY FUNCTIONS

/**
 * Displays available levels in input radio tags. Used in the forms sending badges to students.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges A list of badges.
*/
function display_levels_radio_buttons($badges) {
  global $current_user;
  get_currentuserinfo();

  if($current_user->roles[0]!="administrator")
    $levels = get_all_levels($badges, true);
  else
    $levels = get_all_levels($badges);

  echo '<b>Level* :</b><br />';
  foreach ($levels as $l) {
    echo '<label for="level_'.$l.'">'.$l.' </label><input type="radio" class="level" name="level" id="level_'.$l.'" value="'.$l.'"> ';
  }
  echo '<br />';
}

/**
 * Displays available languages in a select tag. Used in the forms sending badges to students.
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

/**
 * Displays the classes of the teacher in input tags. Used in the forms sending badges to students.
 *
 * @author Nicolas TORION
 * @since 1.0.0
*/
function display_classes_input() {
  global $current_user;
  get_currentuserinfo();

  if($current_user->roles[0]=='administrator')
    $classes = get_all_classes();
  else
    $classes = get_classes_teacher($current_user->user_login);

  echo '<b>Class* : </b><br />';
  $i = 1;
  foreach ($classes as $class) {
    echo '<label for="class_'.$class->ID.'">'.$class->post_title.' </label><input name="class_for_student" id="class_'.$class->ID.'" type="radio" value="'.$class->ID.'"';
    if($i==1)
      echo " checked";
    echo '/>';
    $i++;
  }
}
?>

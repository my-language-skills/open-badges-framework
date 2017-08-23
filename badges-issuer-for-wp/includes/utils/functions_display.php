<?php
// DISPLAY FUNCTIONS

/**
 * Displays available levels in input radio tags. Used in the forms sending badges to students.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges A list of badges.
*/
function display_levels_radio_buttons($badges, $context) {
  global $current_user;
  get_currentuserinfo();

  if(in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles))
    $levels = get_all_levels($badges);
  else {
    if($context=="self") {
      if(in_array("student", $current_user->roles))
        $levels = get_all_levels($badges, true);
      elseif (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles))
        $levels = get_all_levels($badges);
    }
    elseif ($context=="send") {
      if (in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles))
        $levels = get_all_levels($badges, true);
    }
  }

  _e('<b> Level* : </b></br>','badges-issuer-for-wp');
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
 * @param $_most_important_languages A boolean to know if only the most important languages must be displayed.
 * @param $language_selected The language to select.
 * @param $multiple A boolean to know if the select form must be in multiple mode.
*/
//function to display langugaes
function display_languages_select_form($category="most-important-languages", $language_selected="", $multiple=false) {
  $all_languages = get_all_languages();
  $language_to_display = $all_languages[$category];
  _e('<label for="language"><b> Field of Education* : </b></label></br>','badges-issuer-for-wp');

  // Showing the most important languages
  echo '<select name="language';
  if($multiple)
    echo '[]';
  echo '" id="language">';
  echo '<optgroup>';
  foreach ($language_to_display as $language) {
    $language = str_replace("\n", "", $language);
    echo '<option value="'.$language.'"';
    if($language_selected==$language)
      echo ' selected';
    echo '>'.$language.'</option>';
  }
  echo '</optgroup>';
  echo '</select>';

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
    <h1><?php _e('To get a badge, you need to be logged on the site.','badges-issuer-for-wp'); ?></h1>
    <br />
    <a href="<?php echo wp_registration_url(); ?>" title="Register"><?php _e('Register','badges-issuer-for-wp'); ?></a> | <a href="<?php echo wp_login_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>" title="Login"><?php _e('Login','badges-issuer-for-wp'); ?></a>
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

  if($in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles))
    $classes = get_all_classes();
  else
    $classes = get_classes_teacher($current_user->user_login);

  printf(esc_html__( '<b>Class* : </b><br />','badges-issuer-for-wp'));
  foreach ($classes as $class) {
    echo '<label for="class_'.$class->ID.'">'.$class->post_title.' </label><input name="class_for_student" id="class_'.$class->ID.'" type="radio" value="'.$class->ID.'"/>';
  }
}
?>

<?php

    require_once '../../../../../wp-load.php';

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';
    //mimic the actuall admin-ajax
    define('DOING_AJAX', true);

    if (!isset( $_POST['action']))
        die('-1');

    //Typical headers
    header('Content-Type: text/html');
    send_nosniff_header();

    //Disable caching
    header('Cache-Control: no-cache');
    header('Pragma: no-cache');

    $action = esc_attr(trim($_POST['action']));

    //A bit of security
    $allowed_actions = array(
        'action_select_class',
        'action_select_badge',
        'action_save_metabox_students',
        'action_languages_form'
    );

    /* AJAX action to save metabox of students in class job listing type*/

    add_action('CUSTOMAJAX_action_save_metabox_students', 'action_save_metabox_students');

    function action_save_metabox_students() {
      $post_id = $_POST['post_id'];
      update_post_meta($post_id, '_class_students', $_POST['class_students']);
    }

    /* AJAX action to load all languages in a select form*/

    add_action('CUSTOMAJAX_action_languages_form', 'action_languages_form');

    function action_languages_form() {
      display_languages_select_form();
    }

    /* AJAX action to load the classes correspondong to the level and the language selected */

    add_action( 'CUSTOMAJAX_action_select_class', 'action_select_class' );

    function action_select_class() {
      $level = $_POST['level_selected'];
      $language = $_POST['language_selected'];

      global $current_user;
      get_currentuserinfo();

      if($current_user->roles[0]=='administrator')
        $classes = get_all_classes();
      else {
        $classes_teacher = get_classes_teacher($current_user->user_login);
        $classes = array();
        foreach ($classes_teacher as $class) {
          $class_level = get_post_meta($class->ID,'_class_level',true);
          $class_language = get_post_meta($class->ID,'_class_language',true);
          if(empty($class_level) && empty($class_language))
            $classes[] = $class;
          elseif ($class_level==$level && $class_language==$language) {
            $classes[] = $class;
          }
        }
      }

      echo '<b>Class* : </b><br />';
      $i = 1;
      foreach ($classes as $class) {
        echo '<label for="class_'.$class->ID.'">'.$class->post_title.' </label><input name="class_for_student" id="class_'.$class->ID.'" type="radio" value="'.$class->ID.'"';
        if($i==1)
          echo " checked";
        echo '/>';
        $i++;
      }
      if($i==2)
        echo ' <a href="http://'.$_SERVER['SERVER_NAME'].'/wp-admin/post-new.php?post_type=job_listing">Don\'t you want to create a specific class for that student(s) ?</a>';
    }

    /* AJAX action to load the badges of the level given */

    add_action( 'CUSTOMAJAX_action_select_badge', 'action_select_badge' );

    function action_select_badge() {
      $badges = get_all_badges();

      global $current_user;
      get_currentuserinfo();

      if($current_user->roles[0]=="administrator" || $current_user->roles[0]=="academy")
        $badges_corresponding = get_all_badges_level($badges, $_POST['level_selected'], $certification=true);
      else
        $badges_corresponding = get_all_badges_level($badges, $_POST['level_selected']);

      usort($badges_corresponding, function($a, $b) {
        return strcmp($a->post_title, $b->post_title);
      });

      echo "<br /><b>Badge* : </b><br>";
      foreach ($badges_corresponding as $badge) {
        echo '<input type="radio" name="input_badge_name" class="input-badge input-hidden" id="'.$_POST['form'].$badge->post_title.'" value="'.$badge->post_name.'"/><label for="'.$_POST['form'].$badge->post_title.'"><img src="'.get_the_post_thumbnail_url($badge->ID).'" width="40px" height="40px" /></label>';
      }

      ?>
      <script>
        <?php
          $badges = get_all_badges();
          $descriptions_languages = get_all_languages_description($badges);

          foreach ($badges as $badge){
            $langs = $descriptions_languages[$badge->post_title];
            echo 'var '.str_replace("-", "_", $badge->post_name).'_description_languages = [';
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
        jQuery("#badge_form_a .input-badge").on("click", function() {
          var tab_name = jQuery("#badge_form_a .input-badge:checked").val().replace('-', '_') + "_description_languages";
          var tab = eval(tab_name);

          var content = '<label for="language_description"><b>Language of badge description* : </b></label><br /><select name="language_description" id="language_description">';
          tab.forEach(function(lang) {
            content = content + '<option value="' + lang + '">' + lang + '</option>';
          });

          content = content + '</select><br>';
          jQuery("#badge_form_a #result_languages_description").html(content);
        });

        jQuery("#badge_form_b .input-badge").on("click", function() {
          var tab_name = jQuery("#badge_form_b .input-badge:checked").val().replace('-', '_') + "_description_languages";
          var tab = eval(tab_name);

          var content = '<label for="language_description"><b>Language of badge description* : </b></label><br /><select name="language_description" id="language_description">';
          tab.forEach(function(lang) {
            content = content + '<option value="' + lang + '">' + lang + '</option>';
          });

          content = content + '</select><br>';
          jQuery("#badge_form_b #result_languages_description").html(content);
        });

        jQuery("#badge_form_c .input-badge").on("click", function() {
          var tab_name = jQuery("#badge_form_c .input-badge:checked").val().replace('-', '_') + "_description_languages";
          var tab = eval(tab_name);

          var content = '<label for="language_description"><b>Language of badge description* : </b></label><br /><select name="language_description" id="language_description">';
          tab.forEach(function(lang) {
            content = content + '<option value="' + lang + '">' + lang + '</option>';
          });

          content = content + '</select><br>';
          jQuery("#badge_form_c #result_languages_description").html(content);
        });
      </script>
      <?php
    }

    if(in_array($action, $allowed_actions)) {
        if(is_user_logged_in())
            do_action('CUSTOMAJAX_'.$action);
    } else {
        die('-1');
    }

?>

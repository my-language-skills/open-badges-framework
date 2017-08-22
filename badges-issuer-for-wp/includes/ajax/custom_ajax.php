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
        'action_languages_form',
        'action_mi_languages_form',
        'action_save_comment'
    );

    /* AJAX action to save metabox of students in class job listing type*/

    add_action('CUSTOMAJAX_action_save_metabox_students', 'action_save_metabox_students');

    function action_save_metabox_students() {
      $post_id = $_POST['post_id'];
      update_post_meta($post_id, '_class_students', $_POST['class_students']);
      echo $_POST['class_students'];
    }

    /* AJAX action to load all languages in a select form*/

    add_action('CUSTOMAJAX_action_languages_form', 'action_languages_form');

    function action_languages_form() {
      display_languages_select_form();
      _e(' <a href="#" id="display_mi_languages_'.$_POST['form'].'">Just display most important languages</a>', 'badges-issuer-for-wp');
      printf(__(' (Can take few seconds to load.)','badges-issuer-for-wp'));
    }

    add_action('CUSTOMAJAX_action_mi_languages_form', 'action_mi_languages_form');

    function action_mi_languages_form() {
      display_languages_select_form($just_most_important_languages=true);
      _e('<a href="#" id="display_languages_'.$_POST['form'].'">Display all languages</a> (Can take few seconds to load.)','badges-issuer-for-wp');
    }

    /* AJAX action to load the classes corresponding to the level and the language selected */

    add_action( 'CUSTOMAJAX_action_select_class', 'action_select_class' );

    function action_select_class() {

        global $current_user;
        get_currentuserinfo();

        if(in_array("administrator", $current_user->roles)) {
          $classes = get_all_classes_zero();
          if(is_plugin_active("wp-job-manager/wp-job-manager.php")) {
            $classes_job_listing = get_all_classes();
            $classes = array_merge($classes, $classes_job_listing);
          }
        }
        else {
          if(is_plugin_active("wp-job-manager/wp-job-manager.php"))
            $classes = get_classes_teacher($current_user->user_login);
        }

       _e( '<b>Class* : </b><br />','badges-issuer-for-wp');

        $settings_id_links = get_settings_links();

        if(empty($classes)) {
          if(in_array("teacher", $current_user->roles))
            _e('<a href="'.get_page_link($settings_id_links["link_not_academy"]).'">You need an academy account in order to create your own classes.</a>','badges-issuer-for-wp');
          elseif(in_array("academy", $current_user->roles))
            _e('<a href="'.get_page_link($settings_id_links["link_create_new_class"]).'">Don\'t you want to create a specific class for that student(s) ?</a>', 'badges-issuer-for-wp');
        }
        else {
          foreach ($classes as $class) {
            echo '<label for="class_'.$class->ID.'">'.$class->post_title.' </label><input name="class_for_student" id="class_'.$class->ID.'" type="radio" value="'.$class->ID.'"/>';
          }
        }
    }

    /* AJAX action to load the badges of the level given */

    add_action( 'CUSTOMAJAX_action_select_badge', 'action_select_badge' );

    function action_select_badge() {
      $badges = get_all_badges();

      global $current_user;
      get_currentuserinfo();

      if(in_array("administrator", $current_user->roles) || in_array("academy", $current_user->roles))
        $badges_corresponding = get_all_badges_level($badges, $_POST['level_selected'], $certification=true);
      else
        $badges_corresponding = get_all_badges_level($badges, $_POST['level_selected']);

      usort($badges_corresponding, function($a, $b) {
        return strcmp($a->post_title, $b->post_title);
      });

      _e('<br /><b>Badge* : </b><br>','badges-issuer-for-wp');
      $first_certified_badge = true;
      echo '<div style="display:block; width:100%; overflow:hidden;">';
      foreach ($badges_corresponding as $badge) {
        if(get_post_meta($badge->ID,'_certification',true)=="not_certified") {
          echo '<div style="float:left;">';
          echo '<center><input type="radio" name="input_badge_name" class="input-badge input-hidden" id="'.$_POST['form'].$badge->post_title.'" value="'.$badge->post_name.'"/><label for="'.$_POST['form'].$badge->post_title.'"><img src="';
          if(get_the_post_thumbnail_url($badge->ID)){
            echo get_the_post_thumbnail_url($badge->ID);
            echo '" width="40px" height="40px" /></label>';
            echo '</br><b>'.$_POST['language_selected']. " "  . $badge->post_title . '</b></center>';
          }
          else{
            echo plugins_url( '../../images/default-badge.png', __FILE__ );
            echo '" width="40px" height="40px" /></label></center>';
          }
          echo "</div>";
        }
        elseif(get_post_meta($badge->ID,'_certification',true)=="certified") {
          if($first_certified_badge) {
            echo '<br><b>Certified Badges : </b><br>';
            $first_certified_badge = false;
          }
          echo '<input type="radio" name="input_badge_name" class="input-badge input-hidden" id="'.$_POST['form'].$badge->post_title.'" value="'.$badge->post_name.'"/><label for="'.$_POST['form'].$badge->post_title.'"><img src="';
          if(get_the_post_thumbnail_url($badge->ID))
            echo get_the_post_thumbnail_url($badge->ID);
          else
            echo plugins_url( '../../images/default-badge.png', __FILE__ );
          echo '" width="40px" height="40px" /></label>';
        }
      }
      echo "</div>";

      ?>
      <script>
        <?php
          $badges = get_all_badges();

          foreach ($badges as $badge){
            $descriptions = get_badge_descriptions($badge);
            echo 'var _'.str_replace("-", "_", $badge->post_name).'_description_languages = [';
            $i = 0;
            foreach ($descriptions as $lang=>$description) {
              echo "'".$lang."'";
              if($i!=(sizeof($descriptions)-1))
                echo ', ';
              $i++;
            }
            echo "]; \n";
          }
        ?>
        jQuery("#badge_form_a .input-badge").on("click", function() {
          var tab_name = "_" + jQuery("#badge_form_a .input-badge:checked").val().replace('-', '_') + "_description_languages";
          var tab = eval(tab_name);

          var content = '<label for="language_description"><b><?php _e("Language of badge description* : ","badges-issuer-for-wp") ?></b></label><br /><select name="language_description" id="language_description"><option value="English"> Default </option>';
          tab.forEach(function(lang) {
            content = content + '<option value="' + lang + '">' + lang + '</option>';
          });

          content = content + '</select><br>';
          jQuery("#badge_form_a #result_languages_description").html(content);
        });

        jQuery("#badge_form_b .input-badge").on("click", function() {
          var tab_name = "_" + jQuery("#badge_form_b .input-badge:checked").val().replace('-', '_') + "_description_languages";
          var tab = eval(tab_name);

          var content = '<label for="language_description"><b><?php _e("Language of badge description* : ","badges-issuer-for-wp") ?></b></label><br /><select name="language_description" id="language_description"><option value="English"> Default </option>';

          tab.forEach(function(lang) {
            content = content + '<option value="' + lang + '">' + lang + '</option>';
          });

          content = content + '</select><br>';
          jQuery("#badge_form_b #result_languages_description").html(content);
        });

        jQuery("#badge_form_c .input-badge").on("click", function() {
          var tab_name = "_" + jQuery("#badge_form_c .input-badge:checked").val().replace('-', '_') + "_description_languages";
          var tab = eval(tab_name);

          var content = '<label for="language_description"><b><?php _e("Language of badge description* : ","badges-issuer-for-wp") ?></b></label><br /><select name="language_description" id="language_description"><option value="English"> Default </option>';

          tab.forEach(function(lang) {
            content = content + '<option value="' + lang + '">' + lang + '</option>';
          });

          content = content + '</select><br>';
          jQuery("#badge_form_c #result_languages_description").html(content);
        });
      </script>
      <?php
    }

    /* AJAX action to save the modifications made on a comment*/

    add_action('CUSTOMAJAX_action_save_comment', 'action_save_comment');

    function action_save_comment() {
      $comment_id = $_POST['comment_id'];
      $comment_text = $_POST['comment_text'];

      $comment_arr = array();
      $comment_arr['comment_ID'] = $comment_id;
      $comment_arr['comment_content'] = $comment_text;

      wp_update_comment($comment_arr);
    }

    if(in_array($action, $allowed_actions)) {
        if(is_user_logged_in())
            do_action('CUSTOMAJAX_'.$action);
    } else {
        die('-1');
    }

?>

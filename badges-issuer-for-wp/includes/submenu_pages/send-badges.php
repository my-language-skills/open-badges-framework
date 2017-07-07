<?php
/**
 * Create a submenu page in the administration menu to allow a teacher to send a badge to students.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/submenu_pages
 * @since 1.0.0
*/

    wp_enqueue_script("jquery");
    wp_enqueue_script('jquery-ui');
    wp_enqueue_script('jquery-ui-tabs');

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/class.badge.php';
    /**
    * Adds b4l_send_badges_one_student_submenu_page to the admin menu.
    */
    add_action('admin_menu', 'send_badges_submenu_page');

    /**
    * Creates the submenu page.
    *
    * The capability allows superadmin, admin, editor and author to see this submenu.
    * If you change to change the permissions, use manage_options as capability (for
    * superadmin and admin).
    */
    function send_badges_submenu_page() {

        add_submenu_page(
            'edit.php?post_type=badge',
            'Send Badges',
            'Send Badges',
            'capability_send_badge', //capability: 'edit_posts' to give automatically the access to author/editor/admin
            'send-badges',
            'send_badges_page_callback'
        );

    }

    /**
     * Displays the content of the submenu page
     *
     * @author Nicolas TORION
     * @since 1.0.0
     */
    function send_badges_page_callback() {
    ?>
      <script>
        jQuery(document).ready(function(jQuery) {
          jQuery('#tabs').tabs();
          jQuery(".tab-element").click(function(){
            jQuery(".tab-element").removeClass("active");
            jQuery(this).addClass("active");
          });
        });
      </script>

      <div id="tabs">
        <div id="tabs-elements">
          <ul>
            <li><a href="#tabs-1"><div class="tab-element">Self</div></a></li>
            <li><a href="#tabs-2"><div class="tab-element">Issue</div></a></li>
            <li><a href="#tabs-3"><div class="tab-element">Multiple issue</div></a></li>
          </ul>
        </div>
        <div id="tabs-1">
          <?php tab_self(); ?>
        </div>
        <div id="tabs-2">
          <?php tab_issue(); ?>
        </div>
        <div id="tabs-3">
          <?php tab_multiple_issues(); ?>
        </div>
      </div>
      <?php
      // Traitement of form, a mail is sent to the student.
      if(isset($_POST['level']) && isset($_POST['sender']) && isset($_POST['input_badge_name']) && isset($_POST['language']) && isset($_POST['mail']) && isset($_POST['comment']) && isset($_POST['language_description'])) {

        $url_json_files = "http://".$_SERVER['SERVER_NAME']."/wp-content/uploads/badges-issuer/json/";
        $path_dir_json_files = plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/badges-issuer/json/';

        $badges = get_all_badges();
        $badge_others_items = get_badge($_POST['input_badge_name'], $badges, $_POST['language_description']);

        $mails = $_POST['mail'];
        $mails_list = explode("\n", $mails);

        global $current_user;
        get_currentuserinfo();

        $class = null;
        if($current_user->roles[0]=="teacher" || $current_user->roles[0]=="administrator") {
          if(isset($_POST['class_for_student']))
            $class = $_POST['class_for_student'];
        }

        $notsent = array();

        $badge = new Badge($badge_others_items['name'], $_POST['level'], $_POST['language'], $_POST['comment'], $badge_others_items['description'], $badge_others_items['image'], $url_json_files, $path_dir_json_files);

        foreach ($mails_list as $mail) {
          $mail = str_replace("\r", "", $mail);

          $badge->create_json_files($mail);

          if(!$badge->send_mail($mail))
            $notsent[] = $mail;
          else {
            $badge->add_student_to_class($mail, $class);
            $badge->add_badge_to_user_profile($mail, $_POST['sender']);
          }

        }

        if(sizeof($notsent)>0) {
          $message = "Badge not sent to these persons : ";
          foreach ($notsent as $notsent_mail) {
            $message = $message.$notsent_mail." ";
          }
          display_error_message($message);
        }
        else
          display_success_message("Badge sent to all persons.");
      }
    }

    /**
     * The content of the tab for sending a badge to himself.
     *
     * @author Nicolas TORION
     * @since 1.0.0
     */
    function tab_self() {
      ?>

      <div class="tab-content">
      <br /><br />
      <h2>Send a badge to yourself</h2>
      <form id="badge_form_a" action="" method="post">
        <?php
        global $current_user;
        get_currentuserinfo();
        // get all badges that exist
        $badges = get_all_badges();

        display_levels_radio_buttons($badges);
        echo '<div id="select_badge"></div>';

        echo '<br /><br />';
        display_languages_select_form();
        echo '<br /><br />';
        ?>
        <input type="hidden" name="mail" value="<?php echo $current_user->user_email; ?>" />
        <input type="hidden" name="sender" value="SELF" />

        <label for="comment"><b>Comment : </b></label><br />
        <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br />

        <div id="result_languages_description"></div>
        <br /><br />
        <input type="submit" id="submit_button_a" class="button-primary" value="Send a badge"/>
      </form>
      </div>
      <?php
    }

    /**
     * The content of the tab for sending a badge to someone.
     *
     * @author Nicolas TORION
     * @since 1.0.0
     */
    function tab_issue() {

        apply_css_styles();

        ?>

        <div class="tab-content">
          <br /><br />
        <h2>Send a badge</h2>

        <form id="badge_form_b" action="" method="post">
          <?php
          global $current_user;
          // get all badges that exist
          $badges = get_all_badges();

          display_levels_radio_buttons($badges);
          echo '<div id="select_badge"></div>';

          echo '<br /><br />';
          display_languages_select_form();
          echo '<br /><br />';
          ?>
          <label for="mail"><b>Receiver's mail adress* : </b></label><br />
          <input type="text" name="mail" id="mail" class="mail"/>
          <br /><br />
          <?php
            if($current_user->roles[0]=="teacher" || $current_user->roles[0]=="academy" || $current_user->roles[0]=="administrator") {
                echo '<div id="select_class"></div>';
                echo '<br /><br />';
            }
          ?>
          <input type="hidden" name="sender" value="<?php echo $current_user->user_email; ?>" />
          <label for="comment"><b>Comment : </b></label><br />
          <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br />

          <div id="result_languages_description"></div>
          <br /><br />
          <input type="submit" id="submit_button_b" class="button-primary" value="Send a badge"/>
        </form>

        </div>
        <?php
    }

    /**
     * The content of the tab for sending a badge to several persons.
     *
     * @author Nicolas TORION
     * @since 1.0.0
     */
    function tab_multiple_issues() {

        apply_css_styles();
        ?>
        <div class="tab-content">
          <br /><br />
        <h2>Send a badge to several persons</h2>

        <form id="badge_form_c" action="" method="post">
          <?php
          global $current_user;
          // get all badges that exist
          $badges = get_all_badges();

          display_levels_radio_buttons($badges);
          echo '<div id="select_badge"></div>';

          echo '<br /><br />';
          display_languages_select_form();
          ?>
          <br /><br />
          <label for="mail"><b>Receivers' mail adresses* (one mail adress per line) : </b></label><br />
          <textarea name="mail" id="mail" class="mail" rows="10" cols="50"></textarea>
          <br /><br />
          <?php
            if($current_user->roles[0]=="teacher" || $current_user->roles[0]=="academy" || $current_user->roles[0]=="administrator") {
                echo '<div id="select_class"></div>';
                echo '<br /><br />';
            }
          ?>

          <input type="hidden" name="sender" value="<?php echo $current_user->user_email; ?>" />
          <br /><br />
          <label for="comment"><b>Comment : </b></label><br />
          <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br />

          <div id="result_languages_description"></div>
          <br /><br />
          <input type="submit" id="submit_button_c" class="button-primary" value="Send a badge"/>
        </form>

        </div>
        <?php
    }

    add_shortcode( 'send_badge', 'send_badges_page_callback' );

?>

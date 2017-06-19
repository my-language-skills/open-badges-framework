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

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

    /**
    * Adds b4l_send_badges_one_student_submenu_page to the admin menu.
    */
    add_action('admin_menu', 'send_badges_to_more_than_one_student_submenu_page');

    /**
    * Creates the submenu page.
    *
    * The capability allows superadmin, admin, editor and author to see this submenu.
    * If you change to change the permissions, use manage_options as capability (for
    * superadmin and admin).
    */
    function send_badges_to_more_than_one_student_submenu_page() {

        add_submenu_page(
            'edit.php?post_type=badge',
            'Send Badges To More Than One Student',
            'Send Badges To More Than One Student',
            'manage_options', //capability: 'edit_posts' to give automatically the access to author/editor/admin
            'send-badges-to-more-than-one-student-page',
            'send_badges_to_more_than_one_student_page_callback'
        );

    }

    /**
     * Displays the content of the submenu page
     *
     * @author Nicolas TORION
     * @since 1.0.0
     */
    function send_badges_to_more_than_one_student_page_callback() {

        apply_css_styles();
        ?>

        <h2>Send a badge to more than one student</h2>

        <form id="badge_form" action="" method="post">
          <?php
          // get all badges that exist
          $badges = get_all_badges();

          display_levels_radio_buttons($badges);
          echo '<div id="select_badge"></div>';

          echo '<br /><br />';
          display_languages_select_form();
          ?>
          <br /><br />
          <label for="mail"><b>Students' mail adresses* (one mail adress per line) : </b></label><br />
          <textarea name="mail" id="mail" rows="10" cols="50"></textarea>

          <br /><br />
          <label for="comment"><b>Comment : </b></label><br />
          <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br />

          <div id="result_languages_description"></div>
          <br /><br />
          <input type="submit" id="submit_button" class="button-primary" value="Send a badge"/>
        </form>

        <?php
        // Traitement of form, a mail is sent to the student.
        if(isset($_POST['level']) && isset($_POST['input_badge_name']) && isset($_POST['language']) && isset($_POST['mail']) && isset($_POST['comment']) && isset($_POST['language_description'])) {
          $url_json_files = "http://".$_SERVER['SERVER_NAME']."/wp-content/uploads/badges-issuer/json/";
          $path_dir_json_files = plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/badges-issuer/json/';

          //creates the folders recursively if they initially don't exist
          if (!file_exists($path_dir_json_files)) {
              mkdir($path_dir_json_files, 0777, true);
          }

          $badges = get_all_badges();
          $badge_others_items = get_badge($_POST['input_badge_name'], $badges, $_POST['language_description']);

          $mails = $_POST['mail'];
          $mails_list = explode("\n", $mails);

          $notsent = array();

          foreach ($mails_list as $mail) {
            $mail = str_replace("\r", "", $mail);

            $hash_name = hash("sha256", $mail.$badge_others_items['name'].$_POST['language']);
            $badge_filename = 'badge_'.$hash_name.'.json';
            $assertion_filename = "assertion_".$hash_name.'.json';

            $url_mail = "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/utils/get_badge.php?hash=".$hash_name;

            create_badge_json_file($_POST['level'], $_POST['language'], $_POST['comment'], $badge_others_items, $path_dir_json_files, $url_json_files, $badge_filename);
            create_assertion_json_file($mail, $path_dir_json_files, $url_json_files, $badge_filename, $assertion_filename);

            if(!send_mail($mail, $badge_others_items['name'], $_POST['language'], $badge_others_items['image'], $url_mail))
              $notsent[] = $mail;

          }

          if(sizeof($notsent)>0) {
            $message = "Badge not sent to these students : ";
            foreach ($notsent as $notsent_mail) {
              $message = $message.$notsent_mail." ";
            }
            display_error_message($message);
          }
          else
            display_success_message("Badge sent to all students.");
        }
    }

?>

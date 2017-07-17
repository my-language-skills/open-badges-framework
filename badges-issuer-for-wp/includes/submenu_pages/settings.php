<?php
/**
 * Create a submenu page in the administration menu to change settings of the Badge School plugin.
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

    /**
    * Adds b4l_send_badges_one_student_submenu_page to the admin menu.
    */
    add_action('admin_menu', 'settings_submenu_page');

    /**
    * Creates the submenu page.
    *
    * The capability allows superadmin, admin, editor and author to see this submenu.
    * If you change to change the permissions, use manage_options as capability (for
    * superadmin and admin).
    */
    function settings_submenu_page() {

        add_submenu_page(
            'edit.php?post_type=badge',
            'Settings',
            'Settings',
            'capability_settings',
            'settings',
            'settings_callback'
        );

    }

    /**
     * Displays the content of the submenu page
     *
     * @author Nicolas TORION
     * @since 1.0.0
     */
    function settings_callback() {
      ?>
      <h1>Settings</h1>
      <br/>

      <div style="width:400px;">
        <h2>Change the badges issuer informations</h2>
        <br/>

        <form id="settings_form_badges_issuer" action="" method="post">
          <label for="badges_issuer_name">Name : </label><input type="text" id="badges_issuer_name" name="badges_issuer_name" value="" style="float:right"/><br /><br />
          <label for="badges_issuer_image">Image URL : </label><input type="text" id="badges_issuer_image" name="badges_issuer_image" value="" placeholder="http://example.com/image.jpg" style="float:right"/><br /><br />
          <label for="badges_issuer_website">Website URL : </label><input type="text" id="badges_issuer_website" name="badges_issuer_website" value="" placeholder="http://example.com/" style="float:right"/><br /><br />
          <label for="badges_issuer_mail">Backpack account (mail) : </label><input type="text" id="badges_issuer_mail" name="badges_issuer_mail" value="" style="float:right"/><br /><br /><br />
          <input type="submit" id="settings_submit_badges_issuer" class="button-primary" value="Change badges issuer informations" />
        </form>
      </div>
      <?php

      if(isset($_POST['badges_issuer_name']) && isset($_POST['badges_issuer_image']) && isset($_POST['badges_issuer_website']) && isset($_POST['badges_issuer_mail'])) {
        change_badges_issuer_informations($_POST['badges_issuer_name'], $_POST['badges_issuer_image'] , $_POST['badges_issuer_website'], $_POST['badges_issuer_mail']);
      }
    }

    function change_badges_issuer_informations($name, $image, $website, $mail) {
      $badges_issuer_file_content = array(
        "name"  => $name,
        "image" => urldecode(str_replace("\\", "", $image)),
        "url"   => $website,
        "email" => $mail
      );

      $path_dir_json_files = plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/badges-issuer/json/';

      if (!file_exists($path_dir_json_files)) {
          mkdir($path_dir_json_files, 0777, true);
      }

      $file = $path_dir_json_files."badges-issuer.json";

      file_put_contents($file, json_encode($badges_issuer_file_content, JSON_UNESCAPED_SLASHES));
    }

?>

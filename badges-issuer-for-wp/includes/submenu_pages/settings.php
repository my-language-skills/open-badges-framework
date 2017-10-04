<?php
/**
 * Create a submenu page in the administration menu to change settings of the Badge School plugin.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/submenu_pages
 * @since 0.6.2
*/

wp_enqueue_script("jquery");
wp_enqueue_script('jquery-ui');
wp_enqueue_script('jquery-ui-tabs');
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/class.badge-issuer.php';

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
     * @since 0.6.2
     */
    function settings_callback() {

      $badge_issuer = new BadgeIssuer();

      if(isset($_POST['badges_issuer_name']) && isset($_POST['badges_issuer_image']) && isset($_POST['badges_issuer_website']) && isset($_POST['badges_issuer_mail'])) {
        $badge_issuer->change_informations($_POST['badges_issuer_name'], $_POST['badges_issuer_image'], $_POST['badges_issuer_website'], $_POST['badges_issuer_mail']);
      }

      if(isset($_POST["link_not_academy"]) && isset($_POST["link_create_new_class"]))
        set_settings_links(array("link_not_academy" => $_POST["link_not_academy"], "link_create_new_class" => $_POST["link_create_new_class"]));

      if(isset($_POST["link_login"]) && isset($_POST["link_register"]))
        set_settings_login_links(array("link_login" => $_POST["link_login"], "link_register" => $_POST["link_register"]));

      $settings_id_links = get_settings_links();
      $settings_id_login_links = get_settings_login_links();

      ?>
      <h1>Settings</h1>
      <br/>

      <div style="width:400px;">
        <h2>Change the badges issuer informations</h2>
        <br/>

        <form id="settings_form_badges_issuer" action="" method="post">
          <label for="badges_issuer_name">Site name : </label><input type="text" id="badges_issuer_name" name="badges_issuer_name" value="<?php echo $badge_issuer->name; ?>" style="float:right"/><br /><br />
          <label for="badges_issuer_image">Image URL : </label><input type="text" id="badges_issuer_image" name="badges_issuer_image" value="<?php echo $badge_issuer->image; ?>" placeholder="http://example.com/image.jpg" style="float:right"/><br /><br />
          <label for="badges_issuer_website">Website URL : </label><input type="text" id="badges_issuer_website" name="badges_issuer_website" value="<?php echo $badge_issuer->url; ?>" placeholder="http://example.com/" style="float:right"/><br /><br />
          <label for="badges_issuer_mail">Backpack account (mail) : </label><input type="text" id="badges_issuer_mail" name="badges_issuer_mail" value="<?php echo $badge_issuer->email; ?>" style="float:right"/><br /><br /><br />
          <input type="submit" id="settings_submit_badges_issuer" class="button-primary" value="Change badges issuer informations" />
        </form>
      </div>

      <br /><br />

      <div style="width:400px;">
        <h2>Change issuer badges page links</h2>
        <br/>

        <form id="settings_form_links" action="" method="post">

          <div style="display:inline-block;">
            <label for="link_not_academy">Change the role : </label>
            <div style="float:right; margin-left:10px;">
              <?php wp_dropdown_pages(array('name' => 'link_not_academy', 'selected' => $settings_id_links["link_not_academy"])); ?>
              <p class="description" id="tagline-description">From issues badges page to change the role page.</p>
            </div>
          </div>

          <div style="display:inline-block;">
            <label for="link_create_new_class">New Class : </label>
            <div style="float:right; margin-left:10px;">
              <?php wp_dropdown_pages(array('name' => 'link_create_new_class', 'selected' => $settings_id_links["link_create_new_class"])); ?>
              <p class="description" id="tagline-description">Redirection page to creating a new class page.</p>
            </div>
          </div>
          <br /><br />
          <input type="submit" id="settings_submit_links" class="button-primary" value="Change links" />

        </form>
      </div>

      <br /><br />

      <div style="width:400px;">
        <h2>Change register and login pages links</h2>
        <br/>

        <form id="settings_form_login_links" action="" method="post">

          <div style="display:inline-block;">
            <label for="link_login">Login page : </label>
            <div style="float:right; margin-left:10px;">
              <?php wp_dropdown_pages(array('name' => 'link_login', 'selected' => $settings_id_login_links["link_login"])); ?>
              <p class="description" id="tagline-description">Link to the login page.</p>
            </div>
          </div>

          <div style="display:inline-block;">
            <label for="link_register">Register page : </label>
            <div style="float:right; margin-left:10px;">
              <?php wp_dropdown_pages(array('name' => 'link_register', 'selected' => $settings_id_login_links["link_register"])); ?>
              <p class="description" id="tagline-description">Link to the register page.</p>
            </div>
          </div>
          <br /><br />
          <input type="submit" id="settings_submit_login_links" class="button-primary" value="Change links" />
          <br /><br />
        </form>
      </div>
      <?php
    }

?>

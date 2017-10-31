<?php
/**
 * Create a submenu page in the administration menu to change settings of the Badge School plugin.
 *
 * @author     Nicolas TORION
 * @package    badges-issuer-for-wp
 * @subpackage includes/submenu_pages
 * @since      0.6.2
 */

require_once plugin_dir_path(dirname(__FILE__)) . 'utils/functions.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'utils/class.badge-issuer.php';

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

    add_submenu_page('edit.php?post_type=badge', 'Settings', 'Settings', 'capability_settings', 'settings', 'settings_callback');

}

/**
 * Displays the content of the submenu page
 *
 * @author Nicolas TORION
 * @since  0.6.2
 */
function settings_callback() {

    $badge_issuer = new BadgeIssuer();
    $save_setting = new SaveSetting();

    if (isset($_POST['badges_issuer_name']) && isset($_POST['badges_issuer_image'])
        && isset($_POST['badges_issuer_website']) && isset($_POST['badges_issuer_mail'])) {
        $badge_issuer->change_informations(
            $_POST['badges_issuer_name'],
            $_POST['badges_issuer_image'],
            $_POST['badges_issuer_website'],
            $_POST['badges_issuer_mail']
        );
    }


    if (isset($_POST["link_not_academy"]) && isset($_POST["link_create_new_class"])) {
        $save_setting->set_settings_links(
            array(
                "link_not_academy" => $_POST["link_not_academy"],
                "link_create_new_class" => $_POST["link_create_new_class"]
            )
        );
    }


    if (isset($_POST["link_login"]) && isset($_POST["link_register"])) {
        $save_setting->get_settings_login_links(
            array(
                "link_login" => $_POST["link_login"],
                "link_register" => $_POST["link_register"]
            )
        );
    }

    $settings_id_links = $save_setting->get_settings_links();
    $settings_id_login_links = $save_setting->get_settings_login_links();

    ?>
    <h1>Settings</h1>
    <br/>

    <form id="settings_form_badges_issuer" action="" method="post">
        <h2>Change the badges issuer informations</h2>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="blogname">Site name:</label></th>
                <td>
                    <input type="text" id="badges_issuer_name"
                           name="badges_issuer_name"
                           value="<?php echo $badge_issuer->name; ?>"
                    />
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="blogname">Image URL:</label></th>
                <td>
                    <input type="text" id="badges_issuer_image"
                           name="badges_issuer_image"
                           value="<?php echo $badge_issuer->image; ?>"
                           placeholder="http://example.com/image.jpg"
                    />

                </td>
            </tr>
            <tr>
                <th scope="row"><label for="blogname">Website URL:</label></th>
                <td>
                    <input type="text" id="badges_issuer_website"
                           name="badges_issuer_website"
                           value="<?php echo $badge_issuer->url; ?>"
                           placeholder="http://example.com/"
                    />
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="blogname">Backpack account (mail):</label></th>
                <td>
                    <input type="text"
                           id="badges_issuer_mail"
                           name="badges_issuer_mail"
                           value="<?php echo $badge_issuer->email; ?>"
                    />
                </td>
            </tr>
            </tbody>
        </table>

        <br><br>

        <h2>Change issuer badges page links</h2>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="blogname">Change the role:</label></th>
                <td>
                    <?php
                    wp_dropdown_pages(array(
                        'name' => 'link_not_academy',
                        'selected' => $settings_id_links["link_not_academy"]
                    ));
                    ?>
                    <p class="description" id="tagline-description">From issues badges page to change the role
                        page.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="blogname">New Class:</label></th>
                <td>
                    <?php wp_dropdown_pages(array('name' => 'link_create_new_class',
                        'selected' => $settings_id_links["link_create_new_class"]
                    )); ?>
                    <p class="description" id="tagline-description">Redirection page to creating a new class
                        page.</p>
                </td>
            </tr>
            </tbody>
        </table>

        <br><br>

        <h2>Change register and login pages links</h2>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="blogname">Login page:</label></th>
                <td>
                    <?php wp_dropdown_pages(array('name' => 'link_login',
                        'selected' => $settings_id_login_links["link_login"]
                    )); ?>
                    <p class="description" id="tagline-description">Link to the login page.</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="blogname">Register page:</label></th>
                <td>
                    <?php wp_dropdown_pages(array('name' => 'link_register',
                        'selected' => $settings_id_login_links["link_register"]
                    )); ?>
                    <p class="description" id="tagline-description">Link to the register page.</p>
                </td>
            </tr>
            </tbody>
        </table>
        <br><br>
        <input type="submit" id="settings_submit_login_links" class="button-primary" value="Save settings"/>
    </form>
    <?php
}

?>

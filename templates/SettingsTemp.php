<?php
/**
 * The Classes Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgeFramework
 */

namespace templates;


class SettingsTemp {

    const OPTION_GROUP = "option_group";
    const OPTION_NAME = "option_name";
    const SECTION_NAME = "setting_admin";

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property
        $this->options = get_option(self::OPTION_NAME);
        ?>
        <div class="wrap">
            <h1>Settings</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields(self::OPTION_GROUP);
                do_settings_sections(self::SECTION_NAME);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {
        register_setting(
            self::OPTION_GROUP, // Option group
            self::OPTION_NAME, // Option name
            array($this, 'sanitize') // Sanitize
        );

        $settings_section_id = 'general_information_id';
        /* #GENERAL INFORMATION________________________________ */
        add_settings_section(
            $settings_section_id, // ID
            'General information', // Title
            array($this, 'print_section_info'), // Callback
            self::SECTION_NAME // Page
        );
        /* --> Site Name______________ */
        add_settings_field(
            'site_name', // ID
            'Site Name', // Title
            array($this, 'siteNameCallback'), // Callback
            self::SECTION_NAME, // Page
            $settings_section_id // Section
        );

        /* --> Image URL______________ */
        add_settings_field(
            'image_url',
            'Image URL',
            array($this, 'imageUrlCallback'),
            self::SECTION_NAME,
            $settings_section_id
        );

        /* --> WebSite URL______________ */
        add_settings_field(
            'website_url',
            'Website URL',
            array($this, 'websiteUrlCallback'),
            self::SECTION_NAME,
            $settings_section_id
        );

        /* --> Backpack Email Account____ */
        add_settings_field(
            'backpack_email_account',
            'Backpack Email Account',
            array($this, 'backpackEmailAccountCallback'),
            self::SECTION_NAME,
            $settings_section_id
        );


        /* #PAGES LINKS_________________________________________ */
        $settings_section_id = 'pages_links_id';
        add_settings_section(
            $settings_section_id, // ID
            'Pages Links', // Title
            array($this, 'printPageLinksInfo'), // Callback
            self::SECTION_NAME // Page
        );

        /* --> Became Premium Page____*/
        add_settings_field(
            'became_premium_page', // ID
            'Became Premium Page', // Title
            array($this, 'becamePremiumPageCallback'), // Callback
            self::SECTION_NAME, // Page
            $settings_section_id
        );

        /* --> Add Class Page________ */
        add_settings_field(
            'add_class_page',
            'Add Class Page',
            array($this, 'addClassPageCallback'),
            self::SECTION_NAME,
            $settings_section_id
        );

        /* --> Login Page____________ */
        add_settings_field(
            'login_page',
            'Login page',
            array($this, 'loginPageCallback'),
            self::SECTION_NAME,
            $settings_section_id
        );

        /* --> Register Page__________ */
        add_settings_field(
            'register_page',
            'Register Page',
            array($this, 'registerPageCallback'),
            self::SECTION_NAME,
            $settings_section_id
        );

        /* --> Register Page__________ */
        add_settings_field(
            'get_badge_page',
            'Get Badge Page',
            array($this, 'getBadgePageCallback'),
            self::SECTION_NAME,
            $settings_section_id
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     *
     * @return array .
     */
    public function sanitize($input) {
        $new_input = array();
        if (isset($input['site_name']))
            $new_input['site_name'] = sanitize_text_field($input['site_name']);

        if (isset($input['image_url']))
            $new_input['image_url'] = sanitize_text_field($input['image_url']);

        if (isset($input['website_url']))
            $new_input['website_url'] = sanitize_text_field($input['website_url']);

        if (isset($input['backpack_email_account']))
            $new_input['backpack_email_account'] = sanitize_text_field($input['backpack_email_account']);


        if (isset($input['became_premium_page']))
            $new_input['became_premium_page'] = sanitize_text_field($input['became_premium_page']);

        if (isset($input['add_class_page']))
            $new_input['add_class_page'] = sanitize_text_field($input['add_class_page']);

        if (isset($input['login_page']))
            $new_input['login_page'] = sanitize_text_field($input['login_page']);

        if (isset($input['register_page']))
            $new_input['register_page'] = sanitize_text_field($input['register_page']);

        if (isset($input['get_badge_page']))
            $new_input['get_badge_page'] = sanitize_text_field($input['get_badge_page']);

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info() {
        print 'Explanation of this fields:';
    }

    /**
     * Print the Section text
     */
    public function printPageLinksInfo() {
        print 'Another explanation of this fields:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function siteNameCallback() {
        printf(
            '<input type="text" id="site_name" name="%s[site_name]" value="%s" />',
            self::OPTION_NAME,
            isset($this->options['site_name']) ? esc_attr($this->options['site_name']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function imageUrlCallback() {
        printf(
            '<input type="text" id="id_number" name="%s[image_url]" value="%s" />',
            self::OPTION_NAME,
            isset($this->options['image_url']) ? esc_attr($this->options['image_url']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function websiteUrlCallback() {
        printf(
            '<input type="text" id="id_number" name="%s[website_url]" value="%s" />',
            self::OPTION_NAME,
            isset($this->options['website_url']) ? esc_attr($this->options['website_url']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function backpackEmailAccountCallback() {
        printf(
            '<input type="text" id="id_number" name="%s[backpack_email_account]" value="%s" />',
            self::OPTION_NAME,
            isset($this->options['backpack_email_account']) ? esc_attr($this->options['backpack_email_account']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function becamePremiumPageCallback() {
        wp_dropdown_pages(array(
            'id' => 'became_premium_page',
            'name' => self::OPTION_NAME . '[became_premium_page]',
            'selected' => isset($this->options['became_premium_page']) ? esc_attr($this->options['became_premium_page']) : ''
        ));
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function addClassPageCallback() {
        wp_dropdown_pages(array(
            'id' => 'add_class_page',
            'name' => self::OPTION_NAME . '[add_class_page]',
            'selected' => isset($this->options['add_class_page']) ? esc_attr($this->options['add_class_page']) : ''
        ));
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function loginPageCallback() {
        wp_dropdown_pages(array(
            'id' => 'login_page',
            'name' => self::OPTION_NAME . '[login_page]',
            'selected' => isset($this->options['login_page']) ? esc_attr($this->options['login_page']) : ''
        ));
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function registerPageCallback() {
        wp_dropdown_pages(array(
            'id' => 'register_page',
            'name' => self::OPTION_NAME . '[register_page]',
            'selected' => isset($this->options['register_page']) ? esc_attr($this->options['register_page']) : ''
        ));
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function getBadgePageCallback() {
        wp_dropdown_pages(array(
            'id' => 'get_badge_page',
            'name' => self::OPTION_NAME . '[get_badge_page]',
            'selected' => isset($this->options['get_badge_page']) ? esc_attr($this->options['get_badge_page']) : ''
        ));
    }
}

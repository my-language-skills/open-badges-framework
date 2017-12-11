<?php
/**
 * Template for the Settings page.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace templates;

/**
 * This class create and manage the settings page.
 * It's look little bit complicated but with calm and patient you can understand
 * everything. The 'pageInit' function is the core of this class because permit to
 * instantiate all the sections and the relative fields (SECTION: company_profile_sect,
 * page_link_sect; FIELDS: site_name_field, website_url_field ...).
 * it can be fix watching this tutorial: https://www.youtube.com/watch?v=QYt5Ry3os88
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 */
final class SettingsTemp {
    const OPTION_GROUP = "option_group";
    const OPTION_NAME = "option_name";

    // SETTINGS PAGE
    const PAGE_PROFILE = "setting_page";
    const PAGE_LINKS = "links_page";

    //SECTIONS
    CONST SECT_COMPANY_PROFILE = 'company_profile_sect';
    CONST SECT_PAGE_REF = 'page_link_sect';

    // FIELDS
    const FI_SITE_NAME_FIELD = "site_name_field";
    const FI_WEBSITE_URL_FIELD = 'website_url_field';
    const FI_IMAGE_URL_FIELD = 'image_url_field';
    const FI_TELEPHONE_FIELD = 'telephone_field';
    const FI_DESCRIPTION_FIELD = 'information_field';
    const FI_EMAIL_FIELD = 'email_field';

    const FI_ADD_CLASS = 'add_class_page';
    const FI_BECAME_PREMIUM = 'became_premium_page';
    const FI_GET_BADGE = 'get_badge_page';

    private $options;


    /**
     * The construct allow to call th admin_init hook initializing the
     * settings and set the default information retrieved form the default
     * info of WordPress.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function __construct() {
        add_action('admin_init', array($this, 'pageInit'));

        $defaults = array(
            self::FI_SITE_NAME_FIELD => get_bloginfo('name'),
            self::FI_WEBSITE_URL_FIELD => get_bloginfo('url'),
        );

        //update_option(self::OPTION_NAME, $defaults);
    }

    /**
     * This is the function that is typically loaded at
     * the beginning.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function main() {
        // Set class property
        $this->options = get_option(self::OPTION_NAME);
        ?>
        <div class="wrap">
            <h1>Settings</h1>
            <br>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-1">Profile</a></li>
                <li class=""><a href="#tab-2">Links</a></li>
            </ul>
            <form method="post" action="options.php">
                <?php
                wp_enqueue_media();
                ?>
                <div class="tab-content-page">
                    <div id="tab-1" class="tab-pane active">
                        <?php
                        // This prints out all hidden setting fields
                        settings_fields(self::OPTION_GROUP);
                        do_settings_sections(self::PAGE_PROFILE);
                        ?>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <?php
                        // This prints out all hidden setting fields
                        settings_fields(self::OPTION_GROUP);
                        do_settings_sections(self::PAGE_LINKS);
                        ?>
                    </div>
                </div>
                <?php
                submit_button( 'Save Settings', 'primary', 'wpdocs-save-settings' );
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Initializing of all the settings information
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function pageInit() {
        register_setting(
            self::OPTION_GROUP, // Option group
            self::OPTION_NAME, // Option name
            array($this, 'sanitize') // Sanitize
        );

        /* #GENERAL INFORMATION________________________________ */
        add_settings_section(
            self::SECT_COMPANY_PROFILE, // ID
            'Company Profile', // Title
            array($this, 'printSectionInfo'), // Callback
            self::PAGE_PROFILE // Page
        );
        /* --> Site Name______________ */
        add_settings_field(
            '' . self::FI_SITE_NAME_FIELD . '', // ID
            'Site Name', // Title
            array($this, 'siteNameCallback'), // Callback
            self::PAGE_PROFILE, // Page
            self::SECT_COMPANY_PROFILE // Section
        );

        /* --> WebSite URL______________ */
        add_settings_field(
            self::FI_WEBSITE_URL_FIELD,
            'Website URL',
            array($this, 'websiteUrlCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );

        /* --> Telephone______________ */
        add_settings_field(
            self::FI_TELEPHONE_FIELD,
            'Telephone',
            array($this, 'telephoneCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );

        /* --> Description______________ */
        add_settings_field(
            self::FI_DESCRIPTION_FIELD,
            'Description',
            array($this, 'descriptionCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );

        /* --> Image URL______________ */
        add_settings_field(
            self::FI_IMAGE_URL_FIELD,
            'Image of the Entity',
            array($this, 'imageUrlCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );

        /* --> Email______________ */
        add_settings_field(
            self::FI_EMAIL_FIELD,
            'Email',
            array($this, 'emailCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );


        /* #PAGES LINKS_________________________________________ */
        add_settings_section(
            self::SECT_PAGE_REF, // ID
            'Pages Links', // Title
            array($this, 'printPageLinksInfo'), // Callback
            self::PAGE_LINKS // Page
        );

        /* --> Became Premium Page____*/
        add_settings_field(
            self::FI_BECAME_PREMIUM, // ID
            'Became Premium', // Title
            array($this, 'becamePremiumPageCallback'), // Callback
            self::PAGE_LINKS, // Page
            self::SECT_PAGE_REF
        );

        /* --> Add Class Page________ */
        add_settings_field(
            self::FI_ADD_CLASS,
            'Add Class',
            array($this, 'addClassPageCallback'),
            self::PAGE_LINKS,
            self::SECT_PAGE_REF
        );

        /* --> Register Page__________ */
        add_settings_field(
            self::FI_GET_BADGE,
            'Get Badge',
            array($this, 'getBadgePageCallback'),
            self::PAGE_LINKS,
            self::SECT_PAGE_REF
        );
    }

    /**
     * Sanitize each setting field as needed.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param array $input Contains all settings fields as array keys.
     *
     * @return array Contains all settings fields as array keys but sanitized.
     */
    public function sanitize($input) {
        $new_input = array();
        if (isset($input[self::FI_SITE_NAME_FIELD]))
            $new_input[self::FI_SITE_NAME_FIELD] =
                sanitize_text_field(
                    $input[self::FI_SITE_NAME_FIELD] && $input[self::FI_SITE_NAME_FIELD] != '' ?
                        $input[self::FI_SITE_NAME_FIELD] : get_bloginfo('name')
                );

        if (isset($input[self::FI_IMAGE_URL_FIELD]))
            $new_input[self::FI_IMAGE_URL_FIELD] = sanitize_text_field($input[self::FI_IMAGE_URL_FIELD]);

        if (isset($input[self::FI_WEBSITE_URL_FIELD]))
            $new_input[self::FI_WEBSITE_URL_FIELD] =
                sanitize_text_field(
                    $input[self::FI_WEBSITE_URL_FIELD] && $input[self::FI_WEBSITE_URL_FIELD] != '' ?
                        $input[self::FI_WEBSITE_URL_FIELD] : get_bloginfo('url')
                );

        if (isset($input[self::FI_TELEPHONE_FIELD]))
            $new_input[self::FI_TELEPHONE_FIELD] = sanitize_text_field($input[self::FI_TELEPHONE_FIELD]);

        if (isset($input[self::FI_DESCRIPTION_FIELD]))
            $new_input[self::FI_DESCRIPTION_FIELD] = sanitize_text_field($input[self::FI_DESCRIPTION_FIELD]);

        if (isset($input[self::FI_EMAIL_FIELD]))
            $new_input[self::FI_EMAIL_FIELD] = sanitize_text_field($input[self::FI_EMAIL_FIELD]);

        if (isset($input[self::FI_BECAME_PREMIUM]))
            $new_input[self::FI_BECAME_PREMIUM] = sanitize_text_field($input[self::FI_BECAME_PREMIUM]);

        if (isset($input[self::FI_ADD_CLASS]))
            $new_input[self::FI_ADD_CLASS] = sanitize_text_field($input[self::FI_ADD_CLASS]);

        if (isset($input[self::FI_GET_BADGE])) {
            $new_input[self::FI_GET_BADGE] = sanitize_text_field($input[self::FI_GET_BADGE]);
        }

        return $new_input;
    }

    /**
     * Print the Section text.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function printSectionInfo() {
        print 'A Profile is a collection of information that describes the entity or organization using Open Badges.';
    }

    /**
     * Print the Link text.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function printPageLinksInfo() {
        print 'Create and select the page that you will use for these options:';
    }

    /**
     * Get the settings option array and print the Site Name value.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function siteNameCallback() {
        printf(
            '<input id="%s" class="regular-text" type="text" name="%s[%s]" value="%s" />',
            self::FI_SITE_NAME_FIELD,
            self::OPTION_NAME,
            self::FI_SITE_NAME_FIELD,
            isset($this->options[self::FI_SITE_NAME_FIELD]) ? esc_attr($this->options[self::FI_SITE_NAME_FIELD]) : ''
        );
    }

    /**
     * Get the settings option array and print the url of the image of the website.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function imageUrlCallback() {

        $name = self::OPTION_NAME . "[" . self::FI_IMAGE_URL_FIELD . "]";
        $value = isset($this->options[self::FI_IMAGE_URL_FIELD]) ? esc_attr($this->options[self::FI_IMAGE_URL_FIELD]) : '';
        $core = '';
        $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
        $display = 'none'; // display state ot the "Remove image" button

        if ($image_attributes = wp_get_attachment_image_src($value, $image_size)) {
            // $image_attributes[0] - image URL
            // $image_attributes[1] - image width
            // $image_attributes[2] - image height

            $core = '<a href="#" class="upload-image-obf-settings">
                        <img class="image-setting-prev" src="' . $image_attributes[0] . '" />
                     </a>';
            $display = 'inline-block';
        } else {
            $core = '<a href="#" class="upload-image-obf-settings button">Upload image</a>';
        }

        echo '<div>
                ' . $core . '
                <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
                <a href="#" class="remove-image-obf-settings" style="display: inline-block; display:' . $display . '">Remove image</a>
              </div>';
        echo '<p class="description" id="tagline-description">Upload an image that represent your company.</p>';


    }

    /**
     * Get the settings option array and print the url of the website.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function websiteUrlCallback() {
        printf(
            '<input id="%s" class="regular-text" type="text" name="%s[%s]" value="%s"/>',
            self::FI_WEBSITE_URL_FIELD,
            self::OPTION_NAME,
            self::FI_WEBSITE_URL_FIELD,
            isset($this->options[self::FI_WEBSITE_URL_FIELD]) ? esc_attr($this->options[self::FI_WEBSITE_URL_FIELD]) : ''
        );
    }

    /**
     * Get the settings option array and print the telephone of the website.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function telephoneCallback() {
        printf(
            '<input id="%s" class="" type="text" name="%s[%s]" value="%s"/>',
            self::FI_TELEPHONE_FIELD,
            self::OPTION_NAME,
            self::FI_TELEPHONE_FIELD,
            isset($this->options[self::FI_TELEPHONE_FIELD]) ? esc_attr($this->options[self::FI_TELEPHONE_FIELD]) : ''
        );
    }

    /**
     * Get the settings option array and print the description of the website.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function descriptionCallback() {
        printf(
            '<textarea id="%s" class="regular-text" type="text" name="%s[%s]" rows="10" cols="50"> %s </textarea>',
            self::FI_DESCRIPTION_FIELD,
            self::OPTION_NAME,
            self::FI_DESCRIPTION_FIELD,
            isset($this->options[self::FI_DESCRIPTION_FIELD]) ? esc_attr($this->options[self::FI_DESCRIPTION_FIELD]) : ''
        );
    }

    /**
     * Get the settings option array and print the email of the website.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function emailCallback() {
        printf(
            '<input id="%s" class="regular-text" type="text" name="%s[%s]" value="%s"/>',
            self::FI_EMAIL_FIELD,
            self::OPTION_NAME,
            self::FI_EMAIL_FIELD,
            isset($this->options[self::FI_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_EMAIL_FIELD]) : ''
        );
    }

    /**
     * Get the settings option array and print the become premium page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function becamePremiumPageCallback() {
        $val = isset($this->options[self::FI_BECAME_PREMIUM]) ? esc_attr($this->options[self::FI_BECAME_PREMIUM]) : '';
        wp_dropdown_pages(array(
            'id' => self::FI_BECAME_PREMIUM,
            'name' => self::OPTION_NAME . '[' . self::FI_BECAME_PREMIUM . ']',
            'selected' => $val,
            'show_option_none' => 'None', // string
        ));

        echo self::showPreviewLink($val);
    }

    /**
     * Get the settings option array and print the add class page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function addClassPageCallback() {
        $val = isset($this->options[self::FI_ADD_CLASS]) ? esc_attr($this->options[self::FI_ADD_CLASS]) : '';

        wp_dropdown_pages(array(
            'id' => self::FI_ADD_CLASS,
            'name' => self::OPTION_NAME . '[' . self::FI_ADD_CLASS . ']',
            'selected' => $val,
            'show_option_none' => 'None', // string
        ));
        echo self::showPreviewLink($val);

    }


    /**
     * Get the settings option array and print the get badge page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function getBadgePageCallback() {
        $val = isset($this->options[self::FI_GET_BADGE]) ? esc_attr($this->options[self::FI_GET_BADGE]) : '';

        wp_dropdown_pages(array(
            'id' => self::FI_GET_BADGE,
            'name' => self::OPTION_NAME . '[' . self::FI_GET_BADGE . ']',
            'selected' => $val,
            'show_option_none' => 'None', // string
        ));

        echo self::showPreviewLink($val);

    }

    /**
     * Create a link from an id of a page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param int $idPage id of the page.
     *
     * @return string of the html <a> link.
     */
    public function showPreviewLink($idPage) {
        $value = $idPage ?
            "<a href='" . get_page_link($idPage) . "' target='_blank' style='margin-left:3em;'>Preview</a>" : '';
        return $value;

    }

    /**
     * Retrieve the slug of the Get Badge page that we connected in
     * the link section.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public static function getSlugGetBadgePage() {
        $options = get_option(self::OPTION_NAME);
        $id = isset($options[SettingsTemp::FI_GET_BADGE]) ? $options[SettingsTemp::FI_GET_BADGE] : '';
        return get_post($id)->post_name;
    }

}

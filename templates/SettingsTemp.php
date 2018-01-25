<?php

namespace templates;

use Inc\Base\Secondary;

/**
 * Template for the Settings page, this class create and manage the settings page.
 *
 * @todo        It's look little bit complicated but with calm and patient you can understand
 * @todo        everything. The 'pageInit' function is the core of this class and permit to
 * @todo        instantiate all the sections and the relative fields (SECTION: company_profile_sect,
 * @todo        page_link_sect; FIELDS: site_name_field, website_url_field ...).
 * @todo        To make it more is it can be possible watching this tutorial:
 * @todo        https://www.youtube.com/watch?v=QYt5Ry3os88
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
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
    const FI_TELEPHONE_FIELD = 'telephone_field';
    const FI_DESCRIPTION_FIELD = 'information_field';
    const FI_IMAGE_URL_FIELD = 'image_url_field';
    const FI_EMAIL_FIELD = 'email_field';

    const FI_ADD_CLASS = 'add_class_page';
    const FI_BECAME_PREMIUM = 'became_premium_page';
    const FI_GET_BADGE = 'get-badge-page';

    private $options;


    /**
     * The construct allow to call th admin_init hook initializing the
     * settings.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function __construct() {
        add_action('admin_init', array($this, 'pageInit'));
    }

    /**
     * Setting of the default information with also the creation of the
     * get_badge_page that will be used as a container for the GetBadgeTemp
     * Class.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public static function init() {
        $options = get_option(self::OPTION_NAME);
        $fiName = $options[self::FI_SITE_NAME_FIELD];
        $fiWebUrl = $options[self::FI_WEBSITE_URL_FIELD];
        $fiTel = $options[self::FI_TELEPHONE_FIELD];
        $fiDesc = $options[self::FI_DESCRIPTION_FIELD];
        $fiImageUrl = $options[self::FI_IMAGE_URL_FIELD];
        $fiEmail = $options[self::FI_EMAIL_FIELD];
        $fiClass = $options[self::FI_ADD_CLASS];
        $fiPremium = $options[self::FI_BECAME_PREMIUM];
        $fiBadge = $options[self::FI_GET_BADGE];

        if (!$fiBadge && current_user_can('activate_plugins')) {
            // Verify if the page doesn't exist
            if (!get_page_by_title(self::FI_GET_BADGE)) {

                $current_user = wp_get_current_user();

                $page = array(
                    'post_title' => self::FI_GET_BADGE,
                    'post_name' => self::FI_GET_BADGE,
                    'post_status' => 'publish',
                    'post_author' => $current_user->ID,
                    'post_type' => 'page',
                );

                // insert the get_badge_page into the database
                wp_insert_post($page);
            }

            // insert the id of the get_badge_page into variable
            $fiBadge = get_page_by_title(self::FI_GET_BADGE)->ID;
        }

        $defaults = array(
            self::FI_SITE_NAME_FIELD => $fiName ? $fiName : get_bloginfo('name'),
            self::FI_WEBSITE_URL_FIELD => $fiWebUrl ? $fiWebUrl : get_bloginfo('url'),
            self::FI_TELEPHONE_FIELD => $fiTel ? $fiTel : '',
            self::FI_DESCRIPTION_FIELD => $fiDesc ? $fiDesc : '',
            self::FI_IMAGE_URL_FIELD => $fiImageUrl ? $fiImageUrl : '',
            self::FI_EMAIL_FIELD => $fiEmail ? $fiEmail : get_bloginfo('admin_email'),
            self::FI_ADD_CLASS => $fiClass ? $fiClass : '',
            self::FI_BECAME_PREMIUM => $fiPremium ? $fiPremium : '',
            self::FI_GET_BADGE => $fiBadge ? $fiBadge : '',
        );

        update_option(self::OPTION_NAME, $defaults);
    }

    /**
     * This is the function that is typically loaded at the beginning.
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
                submit_button('Save Settings', 'primary', 'wpdocs-save-settings');
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
            $new_input[self::FI_EMAIL_FIELD] = sanitize_text_field(
                $input[self::FI_EMAIL_FIELD] ? $input[self::FI_EMAIL_FIELD] : get_bloginfo('admin_email'));

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
     *
     * @return void
     */
    public function printSectionInfo() {
        print 'A Profile is a collection of information that describes the entity or organization using Open Badges.';
    }

    /**
     * Print the Link text.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
     */
    public function printPageLinksInfo() {
        print 'Create and select the page that you will use for these options:';
    }

    /**
     * Print the Site Name field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
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
     * Print the Url of the image field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
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
     * Print the Website Url field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
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
     * Print the Telephone field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
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
     * Print the Company Description field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
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
     * Print the Company Email field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
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
     * Print the become premium page field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
     */
    public function becamePremiumPageCallback() {
        $val = isset($this->options[self::FI_BECAME_PREMIUM]) ? esc_attr($this->options[self::FI_BECAME_PREMIUM]) : '';

        if (Secondary::isJobManagerActive()) {
            wp_dropdown_pages(array(
                'id' => self::FI_BECAME_PREMIUM,
                'name' => self::OPTION_NAME . '[' . self::FI_BECAME_PREMIUM . ']',
                'selected' => $val,
                'show_option_none' => 'None', // string
                'show_option_no_change ' => '-1',
            ));
            echo self::showPreviewLink($val);
        } else { ?>
            <select id="<?php echo self::FI_BECAME_PREMIUM ?>"
                    name="<?php echo self::OPTION_NAME . '[' . self::FI_BECAME_PREMIUM . ']' ?>" disabled>
                <option>None</option>
            </select>
            <p class="description" id="tagline-description">WP Job Listing debilitated.</p>
            <?php
        }
    }

    /**
     * Print the add class page field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
     */
    public function addClassPageCallback() {
        $val = isset($this->options[self::FI_ADD_CLASS]) ? esc_attr($this->options[self::FI_ADD_CLASS]) : '';

        if (Secondary::isJobManagerActive()) {
            wp_dropdown_pages(array(
                'id' => self::FI_ADD_CLASS,
                'name' => self::OPTION_NAME . '[' . self::FI_ADD_CLASS . ']',
                'selected' => $val,
                'show_option_none' => 'None', // string
            ));
            echo self::showPreviewLink($val);
        } else { ?>
            <select id="<?php echo self::FI_BECAME_PREMIUM ?>"
                    name="<?php echo self::OPTION_NAME . '[' . self::FI_BECAME_PREMIUM . ']' ?>" disabled>
                <option>None</option>
            </select>
            <p class="description" id="tagline-description">WP Job Listing debilitated.</p>
            <?php
        }
    }


    /**
     * Print the get badge page field with also the value (if exist).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return void
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

        echo '<p class="description" id="tagline-description">Select a page that will be used as a container for the Get Badge process.</p>';
    }

    /**
     * Retrieve the link from thw id of a the page.
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
            "<a href='" . get_page_link($idPage) . "?preview=1' target='_blank' style='margin-left:3em;'>Preview</a>" : '';
        return $value;

    }

    /**
     * Get option variable where are stored information of the
     * setting page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param string $field_option a constant of this class that refer
     *                             to a field in setting page
     *
     * @return string the information of a specific field
     */
    public static function getOption($field_option) {
        $options = get_option(SettingsTemp::OPTION_NAME);
        return $options[$field_option] ? $options[$field_option] : null;
    }
}

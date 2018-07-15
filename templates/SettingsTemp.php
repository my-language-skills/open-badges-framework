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
 * All the content to show in the front-end is wrapped in the __() function
 * for internationalization purposes
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class SettingsTemp {
    const OPTION_GROUP = "option_group";
    const OPTION_NAME = "option_name";
    // SETTINGS PAGE
    const PAGE_PROFILE = "setting_page";
    const PAGE_LINKS = "links_page";
	const PAGE_EMAIL_SETTINGS = "email_settings_page";
    const PAGE_EXTENSIONS = "extensions_page";
    //SECTIONS
    CONST SECT_COMPANY_PROFILE = 'company_profile_sect';
    CONST SECT_PAGE_REF = 'page_link_sect';
	CONST SECT_EMAIL_SETTINGS = 'page_link_sect';
    CONST SECT_EXTENSIONS = 'page_extensions_sect';
    CONST SECT_CAPTCHA = 'captcha_sect';
    CONST SECT_WP_JOBMANAGER = 'wp_jobmanage_sect';
    CONST SECT_RCP = 'rcp_sect';
    // PROFILE FIELDS

    const FI_SITE_NAME_FIELD = "site_name_field";
    const FI_WEBSITE_URL_FIELD = 'website_url_field';
    const FI_TELEPHONE_FIELD = 'telephone_field';
    const FI_DESCRIPTION_FIELD = 'information_field';
    const FI_IMAGE_URL_FIELD = 'image_url_field';
    const FI_EMAIL_FIELD = 'email_field';

	// LINK FIELDS
    const FI_BECOME_PREMIUM = 'become_premium_page';
    const FI_GET_BADGE = 'get-badge-page';

	//EMAIL SETTINGS FIELDS
	const FI_HEADER_EMAIL_FIELD = "header_email_field";
	const FI_SITE_NAME_EMAIL_FIELD = "site_name_email_field";
    const FI_WEBSITE_URL_EMAIL_FIELD = 'website_url_email_field';

	const FI_IMAGE_URL_EMAIL_FIELD = 'image_url_email_field';
	const FI_CONTACT_EMAIL_FIELD = 'contact_email_field';
	const FI_MESSAGE_EMAIL_FIELD = 'message_email_field';

    //EXTENSIONS FIELDS
    const FI_CAPTCHA = "captcha_field";
    const FI_ADD_CLASS = 'add_class_page';
    const FI_RCP = 'rcp_field';

    private $options;
    /**
     * The construct allow to call th admin_init hook initializing the
     * settings.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     */
    public function __construct() {
        add_action('admin_init', array($this, 'pageInit'));
    }
    /**
     * Setting of the default information with also the creation of the
     * get_badge_page that will be used as a container for the GetBadgeTemp
     * Class.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     */
    public static function init() {
        $options = get_option(self::OPTION_NAME);

        $fiName = $options[self::FI_SITE_NAME_FIELD];
        $fiWebUrl = $options[self::FI_WEBSITE_URL_FIELD];
        $fiTel = $options[self::FI_TELEPHONE_FIELD];
        $fiDesc = $options[self::FI_DESCRIPTION_FIELD];
        $fiImageUrl = $options[self::FI_IMAGE_URL_FIELD];
        $fiEmail = $options[self::FI_EMAIL_FIELD];


        $emailFiName = $options[self::FI_SITE_NAME_EMAIL_FIELD];
        $emailFiWebUrl = $options[self::FI_WEBSITE_URL_EMAIL_FIELD];
		$emailFiEmail = $options[self::FI_CONTACT_EMAIL_FIELD];
        $emailFiImageUrl = $options[self::FI_IMAGE_URL_EMAIL_FIELD];
		$emailFiHeader = $options[self::FI_HEADER_EMAIL_FIELD];
		$emailFiMessage = $options[self::FI_MESSAGE_EMAIL_FIELD];

        $fiClass = $options[self::FI_ADD_CLASS];
        $fiPremium = $options[self::FI_BECOME_PREMIUM];
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
            self::FI_BECOME_PREMIUM => $fiPremium ? $fiPremium : '',
            self::FI_GET_BADGE => $fiBadge ? $fiBadge : '',
            self::FI_SITE_NAME_EMAIL_FIELD => $emailFiName ? $emailFiName : get_bloginfo('name'),
			self::FI_WEBSITE_URL_EMAIL_FIELD => $emailFiWebUrl ? $emailFiWebUrl : get_bloginfo('url'),
            self::FI_CONTACT_EMAIL_FIELD => $emailFiEmail ? $emailFiEmail : get_bloginfo('admin_email'),
			self::FI_IMAGE_URL_EMAIL_FIELD => $emailFiImageUrl ? $emailFiImageUrl : '',
			self::FI_HEADER_EMAIL_FIELD => $emailFiHeader ? $emailFiHeader : '',
			self::FI_MESSAGE_EMAIL_FIELD => $emailFiMessage ? $emailFiMessage : '',
        );
        update_option(self::OPTION_NAME, $defaults);
    }
    /**
     * This is the function that is typically loaded at the beginning.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     */
    public function main() {
        // Set class property
        $this->options = get_option(self::OPTION_NAME);
        ?>
        <div class="wrap">
            <h1>Settings</h1>
            <br>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-1"><?php _e('Profile','open-badges-framework');?></a></li>
                <li class=""><a href="#tab-2"><?php _e('Links','open-badges-framework');?></a></li>
				<li class=""><a href="#tab-3"><?php _e('Email Settings','open-badges-framework');?></a></li>
                <li class=""><a href="#tab-4"><?php _e('Extensions','open-badges-framework');?></a></li>
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
                    <div id="tab-3" class="tab-pane">
                        <?php
                        // This prints out all hidden setting fields
                        settings_fields(self::OPTION_GROUP);
                        do_settings_sections(self::PAGE_EMAIL_SETTINGS);
                        ?>
                    </div>
                    <div id="tab-4" class="tab-pane">
                        <?php
                        // This prints out all hidden setting fields
                        settings_fields(self::OPTION_GROUP);
                        do_settings_sections(self::PAGE_EXTENSIONS);
                        ?>
                    </div>
                </div>
				<?php
					submit_button(__('Save Settings','open-badges-framework'), 'primary', 'wpdocs-save-settings');
                ?>
            </form>
        </div>
        <?php
    }
    /**
     * Initializing of all the settings information
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
			__('Company Profile','open-badges-framework'),// Title
            array($this, 'printSectionInfo'), // Callback
            self::PAGE_PROFILE // Page
        );
        /* --> Site Name______________ */
        add_settings_field(
            '' . self::FI_SITE_NAME_FIELD . '', // ID
			__('Site Name','open-badges-framework'),// Title
            array($this, 'siteNameCallback'), // Callback
            self::PAGE_PROFILE, // Page
            self::SECT_COMPANY_PROFILE // Section
        );
        /* --> WebSite URL______________ */
        add_settings_field(
            self::FI_WEBSITE_URL_FIELD,
			__('Website URL','open-badges-framework'),// Title
            array($this, 'websiteUrlCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );
        /* --> Telephone______________ */
        add_settings_field(
            self::FI_TELEPHONE_FIELD,
			__('Telephone','open-badges-framework'),// Title
            array($this, 'telephoneCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );
        /* --> Description______________ */
        add_settings_field(
            self::FI_DESCRIPTION_FIELD,
			__('Description','open-badges-framework'),// Title
            array($this, 'descriptionCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );
        /* --> Image URL______________ */
        add_settings_field(
            self::FI_IMAGE_URL_FIELD,
			__('Company Logo','open-badges-framework'),// Title
            array($this, 'imageUrlCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );
        /* --> Email______________ */
        add_settings_field(
            self::FI_EMAIL_FIELD,
            __('Email','open-badges-framework'),// Title
            array($this, 'emailCallback'),
            self::PAGE_PROFILE,
            self::SECT_COMPANY_PROFILE
        );
        /* #PAGES LINKS_________________________________________ */
        add_settings_section(
            self::SECT_PAGE_REF, // ID
            __('Pages Links','open-badges-framework'),// Title
            array($this, 'printPageLinksInfo'), // Callback
            self::PAGE_LINKS // Page
        );
        /* --> Become Premium Page____*/
        add_settings_field(
            self::FI_BECOME_PREMIUM, // ID
            __('Become Premium','open-badges-framework'),// Title
            array($this, 'becomePremiumPageCallback'), // Callback
            self::PAGE_LINKS, // Page
            self::SECT_PAGE_REF
        );
        /* --> Register Page__________ */
        add_settings_field(
            self::FI_GET_BADGE,
            __('Get Badge Page','open-badges-framework'),// Title
            array($this, 'getBadgePageCallback'),
            self::PAGE_LINKS,
            self::SECT_PAGE_REF
        );

        /* #EMAIL SETTINGS________________________________ */
        add_settings_section(
            self::SECT_EMAIL_SETTINGS, // ID
            __('Email Settings','open-badges-framework'), // Title
            array(), // Callback
            self::PAGE_EMAIL_SETTINGS // Page
        );
        /* --> Site Name______________ */
        add_settings_field(
            '' . self::FI_SITE_NAME_EMAIL_FIELD . '', // ID
            __('Site Name','open-badges-framework'), // Title
            array($this, 'siteNameCallbackEmailSec'), // Callback
            self::PAGE_EMAIL_SETTINGS, // Page
            self::SECT_EMAIL_SETTINGS // Section
        );
        /* --> More information URL______________ */
        add_settings_field(
            self::FI_WEBSITE_URL_EMAIL_FIELD,
            __('More information URL','open-badges-framework'),
            array($this, 'websiteUrlCallbackEmailSec'),
            self::PAGE_EMAIL_SETTINGS,
            self::SECT_EMAIL_SETTINGS
        );
         /* --> Email______________ */
        add_settings_field(
            self::FI_CONTACT_EMAIL_FIELD,
            __('Email Contact','open-badges-framework'),
            array($this, 'emailCallbackEmailSec'),
            self::PAGE_EMAIL_SETTINGS,
            self::SECT_EMAIL_SETTINGS
        );

        /* --> Image URL______________ */
        add_settings_field(
            self::FI_IMAGE_URL_EMAIL_FIELD,
            __('Email Logo','open-badges-framework'),
            array($this, 'imageUrlCallbackEmailSec'),
            self::PAGE_EMAIL_SETTINGS,
            self::SECT_EMAIL_SETTINGS
        );

		 /* --> Header______________ */
        add_settings_field(
            self::FI_HEADER_EMAIL_FIELD,
            __('Header','open-badges-framework'),
            array($this, 'headerTextCallbackEmailSec'),
            self::PAGE_EMAIL_SETTINGS,
            self::SECT_EMAIL_SETTINGS
        );

		 /* --> Message______________ */
        add_settings_field(
            self::FI_MESSAGE_EMAIL_FIELD,
            __('Message','open-badges-framework'),
            array($this, 'messageTextCallbackEmailSec'),
            self::PAGE_EMAIL_SETTINGS,
            self::SECT_EMAIL_SETTINGS
        );
        /* #PAGE EXTENSIONS_________________________________________ */
        add_settings_section(
            self::SECT_EXTENSIONS, // ID
            __('Extensions','open-badges-framework'),// Title
            array(), // Callback
            self::PAGE_EXTENSIONS // Page
        );
        //Only if the ReallySimpleCaptcha plugin is active
        if ( is_plugin_active( 'really-simple-captcha/really-simple-captcha.php') ){
            add_settings_section(
                self::SECT_CAPTCHA, // ID
                'Really Simple Captcha',// Title
                array(), // Callback
                self::PAGE_EXTENSIONS // Page
            );
            /* --> Captcha____*/
            add_settings_field(
                self::FI_CAPTCHA, // ID
                __('Enable Captcha','open-badges-framework'),// Title
                array($this, 'captchaCallback'), // Callback
                self::PAGE_EXTENSIONS, // Page
                self::SECT_CAPTCHA
            );
        }
        /* --> Add Class Page________ */
        if( is_plugin_active( 'wp-job-manager/wp-job-manager.php' ) ){
            add_settings_section(
                self::SECT_WP_JOBMANAGER, // ID
                'WP Job Manager',// Title
                array(), // Callback
                self::PAGE_EXTENSIONS // Page
            );
            add_settings_field(
                self::FI_ADD_CLASS,
                __('Add Class Page','open-badges-framework'),// Title,
                array($this, 'addClassPageCallback'),
                self::PAGE_EXTENSIONS,
                self::SECT_WP_JOBMANAGER
            );
        }

        if( is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' ) ){
            add_settings_section(
                self::SECT_RCP, // ID
                'Restrict Content Pro',// Title
                array(), // Callback
                self::PAGE_EXTENSIONS // Page
            );
            add_settings_field(
                self::FI_RCP,
                __('Restrict Access','open-badges-framework'),// Title,
                array($this, 'rcpCallback'),
                self::PAGE_EXTENSIONS,
                self::SECT_RCP
            );
        }
    }
    /**
     * Sanitize each setting field as needed.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
        if (isset($input[self::FI_BECOME_PREMIUM]))
            $new_input[self::FI_BECOME_PREMIUM] = sanitize_text_field($input[self::FI_BECOME_PREMIUM]);
        if (isset($input[self::FI_ADD_CLASS]))
            $new_input[self::FI_ADD_CLASS] = sanitize_text_field($input[self::FI_ADD_CLASS]);
        if (isset($input[self::FI_GET_BADGE])) {
            $new_input[self::FI_GET_BADGE] = sanitize_text_field($input[self::FI_GET_BADGE]);
        }
        if (isset($input[self::FI_SITE_NAME_EMAIL_FIELD]))
            $new_input[self::FI_SITE_NAME_EMAIL_FIELD] =
                sanitize_text_field(
                    $input[self::FI_SITE_NAME_EMAIL_FIELD] && $input[self::FI_SITE_NAME_EMAIL_FIELD] != '' ?
                        $input[self::FI_SITE_NAME_EMAIL_FIELD] : get_bloginfo('name')
                );
        if (isset($input[self::FI_IMAGE_URL_EMAIL_FIELD]))
            $new_input[self::FI_IMAGE_URL_EMAIL_FIELD] = sanitize_text_field($input[self::FI_IMAGE_URL_EMAIL_FIELD]);
        if (isset($input[self::FI_WEBSITE_URL_EMAIL_FIELD]))
            $new_input[self::FI_WEBSITE_URL_EMAIL_FIELD] =
                sanitize_text_field(
                    $input[self::FI_WEBSITE_URL_EMAIL_FIELD] && $input[self::FI_WEBSITE_URL_EMAIL_FIELD] != '' ?
                        $input[self::FI_WEBSITE_URL_EMAIL_FIELD] : get_bloginfo('url')
                );
		if (isset($input[self::FI_HEADER_EMAIL_FIELD]))
            $new_input[self::FI_HEADER_EMAIL_FIELD] = sanitize_text_field($input[self::FI_HEADER_EMAIL_FIELD]);
		if (isset($input[self::FI_MESSAGE_EMAIL_FIELD]))
            $new_input[self::FI_MESSAGE_EMAIL_FIELD] = sanitize_text_field($input[self::FI_MESSAGE_EMAIL_FIELD]);
        if (isset($input[self::FI_CONTACT_EMAIL_FIELD]))
            $new_input[self::FI_CONTACT_EMAIL_FIELD] = sanitize_text_field(
                $input[self::FI_CONTACT_EMAIL_FIELD] ? $input[self::FI_CONTACT_EMAIL_FIELD] : get_bloginfo('admin_email'));
        if (isset($input[self::FI_CAPTCHA])) {
            $new_input[self::FI_CAPTCHA] = sanitize_text_field($input[self::FI_CAPTCHA]);
        }
        return $new_input;
    }
    /**
     * Print the Section text.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public function printSectionInfo() {
		_e( 'A Profile is a collection of information that describes the entity or organization using Open Badges.', 'open-badges-framework' );
    }
    /**
     * Print the Link text.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public function printPageLinksInfo() {
		_e( 'Create and select the page that you will use for the following options:', 'open-badges-framework' );
    }

    /**
     * Print the Site Name field with also the value (if exist).
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * Print the Site Name field with also the value (if exist) for the Email Settings section.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */

	public function siteNameCallbackEmailSec() {
        printf(
            '<input id="%s" class="regular-text" type="text" name="%s[%s]" value="%s" />',
            self::FI_SITE_NAME_EMAIL_FIELD,
            self::OPTION_NAME,
            self::FI_SITE_NAME_EMAIL_FIELD,
            isset($this->options[self::FI_SITE_NAME_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_SITE_NAME_EMAIL_FIELD]) : ''
        );
		?>
		<p class="description" id="tagline-description"><?php _e('Enter the name of your company.','open-badges-framework.');?></p>
		<?php
    }
    /**
     * Print the Url of the image field with also the value (if exist).
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
            $core = '<a href="#" class="upload-image-obf-settings">
                        <img class="image-setting-prev" src="' . plugin_dir_url() . 'open-badges-framework/assets/images/default-badge.png" />
                     </a>';
            $display = 'inline-block';
        }
        echo '<div>
                ' . $core . '
                <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
                <a href="#" class="remove-image-obf-settings" style="display: inline-block; display:' . $display . '">Remove image</a>
              </div>';
		?>
		<p class="description" id="tagline-description"><?php _e('Upload an image that represents your company.','open-badges-framework.');?></p>
		<?php
    }

    /**
     * Print the Url of the image field with also the value (if exist) for the Email Settings section.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
	public function imageUrlCallbackEmailSec() {
        $name = self::OPTION_NAME . "[" . self::FI_IMAGE_URL_EMAIL_FIELD . "]";
        $value = isset($this->options[self::FI_IMAGE_URL_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_IMAGE_URL_EMAIL_FIELD]) : '';
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
		?>
		<p class="description" id="tagline-description"><?php _e('Upload an image that represents your company.','open-badges-framework.');?></p>
		<?php
    }
    /**
     * Print the Website Url field with also the value (if exist).
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * Print the More information URL field with also the value (if exist) for the Email Settings section.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
	 public function websiteUrlCallbackEmailSec() {
        printf(
            '<input id="%s" class="regular-text" type="text" name="%s[%s]" value="%s"/>',
            self::FI_WEBSITE_URL_EMAIL_FIELD,
            self::OPTION_NAME,
            self::FI_WEBSITE_URL_EMAIL_FIELD,
            isset($this->options[self::FI_WEBSITE_URL_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_WEBSITE_URL_EMAIL_FIELD]) : ''
        );
		?>
		<p class="description" id="tagline-description"><?php _e('Enter the URL where users can find more information.','open-badges-framework');?></p>
		<?php
    }
    /**
     * Print the Telephone field with also the value (if exist).
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * Print the Contact Company Email field with also the value (if exist) for Email Settings section.
     *
     *
     * @return void
     */

	public function emailCallbackEmailSec() {
        printf(
            '<input id="%s" class="regular-text" type="text" name="%s[%s]" value="%s"/>',
            self::FI_CONTACT_EMAIL_FIELD,
            self::OPTION_NAME,
            self::FI_CONTACT_EMAIL_FIELD,
            isset($this->options[self::FI_CONTACT_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_CONTACT_EMAIL_FIELD]) : ''
        );
		?>
		<p class="description" id="tagline-description"><?php _e('Enter the contact email of your company.','open-badges-framework.');?></p>
		<?php
    }

    /**
     * Print the Header field with also the value (if exist) for Email Settings section.
     *
     * @return void
     */

	public function headerTextCallbackEmailSec() {

		$headerText = isset($this->options[self::FI_HEADER_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_HEADER_EMAIL_FIELD]) : '';
		$args = array('textarea_name' => 'option_name[header_email_field]');


		wp_editor( $headerText, self::FI_HEADER_EMAIL_FIELD, $args );
		?>
		<p class="description" id="tagline-description"><?php _e('The text before the badge.','open-badges-framework.');?></p>
		<?php
       /*  printf(
            '<input id="%s" class="regular-text" type="text" name="%s[%s]" value="%s"/>',
            self::FI_HEADER_EMAIL_FIELD,
            self::OPTION_NAME,
            self::FI_HEADER_EMAIL_FIELD,
            isset($this->options[self::FI_HEADER_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_HEADER_EMAIL_FIELD]) : ''
        );		 */

		//$editor_id = 'mycustomeditor';

		//wp_editor( isset($this->options[self::FI_HEADER_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_HEADER_EMAIL_FIELD]) : '', $editor_id );
    }


	/**
     * Print the Message field with also the value (if exist) for Email Settings section.
     *
     * @return void
     */
	public function messageTextCallbackEmailSec() {

		$messageText = isset($this->options[self::FI_MESSAGE_EMAIL_FIELD]) ? esc_attr($this->options[self::FI_MESSAGE_EMAIL_FIELD]) : '';
		$args = array('textarea_name' => 'option_name[message_email_field]');


		wp_editor( $messageText, self::FI_MESSAGE_EMAIL_FIELD, $args );
		?>
		<p class="description" id="tagline-description"><?php _e('Text after the badge.','open-badges-framework.');?></p>
		<?php
	}



    /**
     * Print the become premium page field with also the value (if exist).
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public function becomePremiumPageCallback() {
        $val = isset($this->options[self::FI_BECOME_PREMIUM]) ? esc_attr($this->options[self::FI_BECOME_PREMIUM]) : '';
            wp_dropdown_pages(array(
                'id' => self::FI_BECOME_PREMIUM,
                'name' => self::OPTION_NAME . '[' . self::FI_BECOME_PREMIUM . ']',
                'selected' => $val,
                'show_option_none' => 'None', // string
                'show_option_no_change ' => '-1',
            ));
            echo self::showPreviewLink($val); ?>
            <p class="description" id="tagline-description"><?php _e('Select the page where you’ve used a shortcode from a membership Plugin. This lets the plugin know the location of the form.','open-badges-framework.');?></p>
            <?php
    }
    /**
     * Print the add class page field with also the value (if exist).
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
            echo self::showPreviewLink($val); ?>
            <p class="description" id="tagline-description"><?php _e('Select the page where you’ve used the [submit_job_form] shortcode from WP Job Manager Plugin. This lets the plugin know the location of the form.','open-badges-framework.');?></p>
            <?php
        } else { ?>
            <select id="<?php echo self::FI_BECOME_PREMIUM ?>"
                    name="<?php echo self::OPTION_NAME . '[' . self::FI_BECOME_PREMIUM . ']' ?>" disabled>
                <option>None</option>
            </select>
            <p class="description" id="tagline-description">WP Job Manager deactivated.</p>
            <?php
        }
    }
    /**
     * Print the get badge page field with also the value (if exist).
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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

		?>
		<p class="description" id="tagline-description"><?php _e('Select a page that will be used for the Get Badge process. This lets the plugin know the location of the form.','open-badges-framework.');?></p>
		<?php
    }
    /**
     * Print the captcha settings field (checked or not).
     *
     * @author      @leocharlier
     * @since       1.0.1
     *
     * @return void
     */
    public function captchaCallback() { ?>

        <input type="checkbox" value="1" name="<?php echo self::OPTION_NAME . '[' . self::FI_CAPTCHA . ']' ?>" id="<?php echo self::FI_CAPTCHA ?>" <?php if( isset( $this->options[self::FI_CAPTCHA] ) ) checked('1', $this->options[self::FI_CAPTCHA] ); ?> />
        <span class="description"><?php _e( 'Check this to enable Captcha on the registration form.', 'open-badges-framework.' ); ?></span>

        <?php
    }
    /**
     * Print the restrict content pro settings.
     *
     * @author      @leocharlier
     * @since       1.0.1
     *
     * @return void
     */
    public function rcpCallback() { ?>

        <a href="/wp-admin/admin.php?page=rcp-restrict-post-type-open-badge" class="button button-primary">Restrict Access Page</a>
        <p class="description">Restrict the access of the Open Badges posts to certain roles or subscription levels.</p>

        <?php
    }

    /**
     * Retrieve the link from thw id of a the page.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
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
     * @author      @AleRiccardi
     * @since       1.0.0
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

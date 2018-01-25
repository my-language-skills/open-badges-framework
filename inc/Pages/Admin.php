<?php

namespace Inc\Pages;

use Inc\Base\Metabox;
use Inc\Base\BaseController;
use Inc\Api\SettingApi;
use Inc\Utils\WPUser;
use Templates\BadgesTemp;
use Templates\DashboardTemp;
use Templates\GetBadgeTemp;
use Templates\SendBadgeTemp;
use Templates\SettingsTemp;
use Templates\SingleBadgeTemp;
use Templates\UserTemp;

/**
 * The WordPress Admin generator.
 * This class allow to create array that will be pass
 * to the SettingApi class that will initialize them.
 *
 * @todo       Restrict Access linked to open-badge-framework -
 * @todo       go to Class:SettingApi Function:setCurrentMenu()
 * @todo       to manage it
 *
 * @author     Alessandro RICCARDI
 * @since      x.x.x
 *
 * @package    OpenBadgesFramework
 */
class Admin extends BaseController {
    const SLUG_PLUGIN = "open_badges_framework";

    const POST_TYPE_BADGES = "open-badge";
    const POST_TYPE_CLASS_JL = "job_listing";
    const TAX_FIELDS = "field_of_education";
    const TAX_LEVELS = "level";
    const MTB_CERT = "certification_obf_mtb";
    const MTB_TARGET = "target_obf_mtb";
    const MTB_LBADGE = "lbadge_obf_mtb";
    const PAGE_SEND_BADGE = 'send-badge_obf';
    const PAGE_SETTINGS = 'settings-obf';
    const PAGE_USER = 'user-obf';
    const PAGE_BADGES = 'badges-obf';
    const PAGE_SINGLE_BADGES = 'single-badge-obf';

    private $settings;
    private $pages;
    private $subpages = array();

    /**
     * This function permit to load all the array in the instance
     * of SettingApi and execute the final "register()" function.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    function register() {
        $this->settings = new SettingApi();

        $this->setPages();
        $this->setSubpages();

        $this->setCustomPostTypes();
        $this->setTaxonomies();
        $this->setMetaboxes();
        $this->setFrontEndPages();

        $this->settings->loadPages($this->pages)->withSubPage('Action control')->loadSubPages($this->subpages)->register();
    }

    /**
     * This function permit store in a variable the principal page.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    public function setPages() {
        $this->pages = array(
            array(
                'page_title' => 'Open Badge',
                'menu_title' => 'Open Badge',
                'capability' => 'manage_options',
                'menu_slug' => self::SLUG_PLUGIN,
                'callback' => array(DashboardTemp::class, 'main'),
                'icon_url' => 'dashicons-awards',
                'position' => '110'
            )
        );
    }

    /**
     * This function permit store in an array all the sub-pages.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    public function setSubpages() {
        $sendbadgeTemp = new SendBadgeTemp();
        $settingTemp = new SettingsTemp();
        $userTemp = new UserTemp();
        $badgesTemp = new BadgesTemp();
        $singleBadgesTemp = new SingleBadgeTemp();

        $this->subpages = array(
            // ## Badges ##
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Badges',
                'menu_title' => 'Badges',
                'capability' => 'manage_options',
                'menu_slug' => 'edit.php?post_type=' . self::POST_TYPE_BADGES,
                'callback' => ''
            ),
            // ## Fields ##
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Fields of education',
                'menu_title' => 'Fields of education',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy=' . self::TAX_FIELDS . '&post_type=' . self::POST_TYPE_BADGES,
                'callback' => ''
            ),
            // ## Levels ##
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Levels',
                'menu_title' => 'Levels',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy=' . self::TAX_LEVELS . '&post_type=' . self::POST_TYPE_BADGES,
                'callback' => ''
            ),
            // ## Send Badges ##
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Send Badges',
                'menu_title' => 'Send Badges',
                'capability' => 'read',
                'menu_slug' => self::PAGE_SEND_BADGE,
                'callback' => array($sendbadgeTemp, 'main')
            ),
            // ## Settings ##
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Settings',
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'menu_slug' => self::PAGE_SETTINGS,
                'callback' => array($settingTemp, 'main')
            ),
            // ## User ##
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'User',
                'menu_title' => 'User',
                'capability' => 'read',
                'menu_slug' => self::PAGE_USER,
                'callback' => array($userTemp, 'main')
            ),
            // ## All Badges ##
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'All Badges',
                'menu_title' => 'All Badges',
                'capability' => 'read',
                'menu_slug' => self::PAGE_BADGES,
                'callback' => array($badgesTemp, 'main')
            ),
            // ## Single Badge ##
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Badge',
                'menu_title' => null,
                'capability' => 'read',
                'menu_slug' => self::PAGE_SINGLE_BADGES,
                'callback' => array($singleBadgesTemp, 'main')
            ),
        );


        // ## Restrict Access ##
        // Be careful HERE, this sub-page is created only for rcp-restrict-post-type
        $this->subpages[] = array(
            'parent_slug' => self::SLUG_PLUGIN,
            'page_title' => 'Restrict Access',
            'menu_title' => 'Restrict Access',
            'capability' => 'manage_options',
            'menu_slug' => 'admin.php?page=rcp-restrict-post-type-' . self::POST_TYPE_BADGES,
            'callback' => ''
        );
    }

    /**
     * This function permit load in the SettingApi the Custom Post Type.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    public function setCustomPostTypes() {
        $args = array(
            // ## Badges ##
            array(
                'post_type' => self::POST_TYPE_BADGES,
                'args' => array(
                    'labels' => array(
                        'name' => 'Badges',
                        'singular_name' => 'Badge',
                        'add_new' => 'Add New',
                        'add_new_item' => 'Add New Badge',
                        'edit' => 'Edit',
                        'edit_item' => 'Edit Badge',
                        'new_item' => 'New Badge',
                        'view' => 'View',
                        'view_item' => 'View Badge',
                        'search_items' => 'Search Badges',
                        'not_found' => 'No Badges found',
                        'not_found_in_trash' => 'No Badges found in Trash',
                        'parent' => 'Parent Badges'
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'show_ui' => true,
                    'show_in_menu' => false, // adding to custom menu manually
                    'supports' => array('title', 'editor', 'author', 'thumbnail',),
                    // Capabilities that are debilitated waiting a solution
                    // already explained in the User class.
                    /*
                    'capabilities' => array(
                        'edit_post' => User::CAP_EDIT_BADGE,
                        'edit_posts' => User::CAP_EDIT_BADGES,
                        'edit_others_posts' => User::CAP_EDIT_OTHER_BADGES,
                        'edit_published_posts' => User::CAP_EDIT_PUBLISHED_BADGES,
                        'publish_posts' => User::CAP_PUBLISHED_BADGES,
                        'read_post' => User::CAP_READ_BADGE,
                        'read_posts' => User::CAP_READ_BADGES,
                        'read_private_posts' => User::CAP_READ_BADGES,
                        'delete_post' => User::CAP_DELETE_BADGE,
                        'delete_posts' => User::CAP_DELETE_BADGES,
                    )*/
                )
            ),
        );

        $this->settings->loadCustomPostTypes($args);
    }

    /**
     * This function permit load in the SettingApi the Taxonomies.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    public function setTaxonomies() {
        // ## TAXONOMIES ##
        $args = array(
            // ## Fields ##
            array(
                'taxonomy' => self::TAX_FIELDS,
                'object_type' => array(self::POST_TYPE_BADGES),
                'args' => array(
                    'labels' => array(
                        'name' => _x('Fields of education', 'taxonomy general name'),
                        'singular_name' => _x('Field of education', 'taxonomy singular name'),
                        'search_items' => __('Search Fields of education'),
                        'all_items' => __('All Fields of education'),
                        'parent_item' => __('Parent Field'),
                        'parent_item_colon' => __('Parent Field:'),
                        'edit_item' => __('Edit Field'),
                        'update_item' => __('Update Field'),
                        'add_new_item' => __('Add New Field'),
                        'new_item_name' => __('New Field Name'),
                        'menu_name' => __('Field of Education'),
                    ),
                    'rewrite' => array('slug' => self::TAX_FIELDS),
                    'hierarchical' => true,
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'query_var' => true
                )
            ),
            // ## Levels ##
            array(
                'taxonomy' => self::TAX_LEVELS,
                'object_type' => self::POST_TYPE_BADGES,
                'args' => array(
                    'labels' => array(
                        'name' => _x('Levels', 'taxonomy general name'),
                        'singular_name' => _x('Levels', 'taxonomy singular name'),
                        'search_items' => __('Search Levels'),
                        'all_items' => __('All Levels'),
                        'parent_item' => __('Parent Level'),
                        'parent_item_colon' => __('Parent Level:'),
                        'edit_item' => __('Edit Level'),
                        'update_item' => __('Update Level'),
                        'add_new_item' => __('Add New Level'),
                        'new_item_name' => __('New Level Name'),
                        'menu_name' => __('Level of Education'),
                    ),
                    'rewrite' => array('slug' => self::TAX_LEVELS),
                    'hierarchical' => false,
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'query_var' => true
                )
            ),
        );

        $this->settings->loadTaxonomies($args);
    }

    /**
     * This function permit load in the SettingApi the Metaboxes.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    public function setMetaboxes() {
        $metaboxTemp = new Metabox();

        $args = array(
            // ## Certification ##
            array(
                'id' => self::MTB_CERT,
                'title' => 'Certification Type',
                'callback' => array($metaboxTemp, 'certification'),
                'screen' => self::POST_TYPE_BADGES,
                'context' => 'side',
                'priority' => 'high',
            ),
            // ## Target ##
            array(
                'id' => self::MTB_TARGET,
                'title' => 'Target Type',
                'callback' => array($metaboxTemp, 'target'),
                'screen' => self::POST_TYPE_BADGES,
                'context' => 'side',
                'priority' => 'high',
            ),
        );

        $this->settings->loadMetaBoxes($args);
    }

    /**
     * This function permit to load al the front-end page
     * that is set in the setting page.
     *
     * @author   Alessandro RICCARDI
     * @since    x.x.x
     */
    public function setFrontEndPages() {
        // Get badge page retrieved from the plugin setting
        $getBadgePage = get_post(
            SettingsTemp::getOption(SettingsTemp::FI_GET_BADGE)
        );

        if ($getBadgePage) {
            $args = array(
                // # GET BADGE PAGE
                array(
                    'slug' => $getBadgePage->post_name,
                    'class' => GetBadgeTemp::class,
                ),
            );
            $this->settings->loadFrontEndPages($args);
        }
    }


}


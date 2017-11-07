<?php
/**
 * The Admin Class.
 *
 * @author     Alessandro RICCARDI
 * @since      x.x.x
 *
 * @package    BadgeIssuerForWp
 */

namespace Inc\Pages;

use Inc\Api\MetaboxApi;
use \Inc\Base\BaseController;
use \Inc\Api\SettingApi;
use Templates\Dashboard;
use Templates\SendBadge;

class Admin extends BaseController {
    const SLUG_PLUGIN = "badge_issuer";
    const POST_TYPE_BADGES = "badges_issuer_cpt";
    const POST_TYPE_CLASS = "classes_issuer_cpt";
    const TAX_FIELDS = "fields_issuer_tax";
    const TAX_LEVELS = "levels_issuer_tax";
    const MTB_CERT = "certification_mtb";
    const MTB_TARGET = "type_issuer_mtb";
    const MTB_LBADGE = "lbadge_issuer_mtb";


    private $settingApi;
    private $pages;
    private $subpages = array();
    private $custom_post_types = array();
    private $taxonomies = array();
    private $metaboxes = array();

    /**
     * Admin constructor.
     */
    public function __construct() {
        $this->settingApi = new SettingApi();
        $sendbadge = new SendBadge();
        $metabox = new MetaboxApi();

        /* #PAGE */
        $this->pages = array(
            array(
                'page_title' => 'Badge Issuer',
                'menu_title' => 'Badge Issuer',
                'capability' => 'manage_options',
                'menu_slug' => self::SLUG_PLUGIN,
                'callback' => array(Dashboard::class, 'main'),
                'icon_url' => 'dashicons-awards',
                'position' => '110'
            )
        );

        /* #SUBPAGE */
        $this->subpages = array(
            /* ## Badges ## */
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Badges',
                'menu_title' => 'Badges',
                'capability' => 'manage_options',
                'menu_slug' => 'edit.php?post_type='.self::POST_TYPE_BADGES,
                'callback' => ''
            ),
            /* ## Class ## */
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Classes',
                'menu_title' => 'Classes',
                'capability' => 'manage_options',
                'menu_slug' => 'edit.php?post_type='.self::POST_TYPE_CLASS,
                'callback' => ''
            ),
            /* ## Fields ## */
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Fields of education',
                'menu_title' => 'Fields of education',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.self::TAX_FIELDS.'&post_type='.self::POST_TYPE_BADGES,
                'callback' => ''
            ),
            /* ## Levels ## */
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Levels',
                'menu_title' => 'Levels',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy='.self::TAX_LEVELS.'&post_type='.self::POST_TYPE_BADGES,
                'callback' => ''
            ),
            /* ## Send Badges ## */
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Send Badges',
                'menu_title' => 'Send Badges',
                'capability' => 'manage_options',
                'menu_slug' => 'send_badge_issuer',
                'callback' => array($sendbadge, 'main')
            ),
            /* ## Settings ## */
            array(
                'parent_slug' => self::SLUG_PLUGIN,
                'page_title' => 'Settings',
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'menu_slug' => 'Settings_issuer',
                'callback' => function () {
                    echo '<h1>Settings </h1>';
                }
            ),
        );

        /* #CUSTOM-POST-TYPE */
        $this->custom_post_types = array(
            /* ## Badges ## */
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
                )
            ),
            /* ## Classes ## */
            array(
                'post_type' => self::POST_TYPE_CLASS,
                'args' => array(
                    'labels' => array(
                        'name' => 'Classes',
                        'singular_name' => 'Class',
                        'add_new' => 'Add New',
                        'add_new_item' => 'Add New Class',
                        'edit' => 'Edit',
                        'edit_item' => 'Edit Class',
                        'new_item' => 'New Class',
                        'view' => 'View',
                        'view_item' => 'View Class',
                        'search_items' => 'Search Class',
                        'not_found' => 'No Class found',
                        'not_found_in_trash' => 'No Class found in Trash',
                        'parent' => 'Parent Class'
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'show_ui' => true,
                    'show_in_menu' => false, // adding to custom menu manually
                ),
            )
        );

        /* ## TAXONOMIES ## */
        $this->taxonomies = array(
            /* ## Fields ## */
            array(
                'taxonomy' => self::TAX_FIELDS,
                'object_type' => array(self::POST_TYPE_BADGES, self::POST_TYPE_CLASS),
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
            /* ## Levels ## */
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

        /* #METABOX */
        $this->metaboxes = array(
            /* ## Certification ## */
            array(
                'id' => self::MTB_CERT,
                'title' => 'Certification Type',
                'callback' => array($metabox,'certification'),
                'screen' => self::POST_TYPE_BADGES,
                'context' => 'side',
                'priority' => 'high',
            ),
            /* ## Target ## */
            array(
                'id' => self::MTB_TARGET,
                'title' => 'Target Type',
                'callback' => array($metabox,'target'),
                'screen' => self::POST_TYPE_BADGES,
                'context' => 'side',
                'priority' => 'high',
            ),
            /* ## Links ## */
            array(
                'id' => 'id_meta_box_links',
                'title' =>  'Badge Criteria (doesn\'t work, function: display_add_link in class: MetaboxApi)',
                'callback' => array($metabox,'display_add_link'),
                'screen' => self::POST_TYPE_BADGES,
                'context' => 'normal',
                'priority' => 'high'
            ),
            /* ## Badge of class ## */
            array(
                'id' => self::MTB_LBADGE,
                'title' =>  'List of Badge',
                'callback' => array($metabox,'meta_box_class_zero_students'),
                'screen' => self::POST_TYPE_CLASS,
                'context' => 'normal',
                'priority' => 'high'
            )
        );

    }

    /**
     * Register the admin menu page
     */
    public function register() {
        $this->settingApi->loadPages($this->pages)->withSubPage('Dashboard')->loadSubPages($this->subpages)
            ->loadCustomPostTypes($this->custom_post_types)->loadTaxonomies($this->taxonomies)
            ->loadMetaBoxes($this->metaboxes)->register();

    }
}

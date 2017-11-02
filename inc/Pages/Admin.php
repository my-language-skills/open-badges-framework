<?php
/**
 * The Admin Class.
 *
 * @since      x.x.x
 *
 * @package    FlexProduct
 */

namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Api\SettingApi;

class Admin extends BaseController {

    public $setting;
    public $pages;
    public $subpages = array();
    public $custom_post_types = array();
    public $taxonomies = array();

    /**
     * Admin constructor.
     */
    public function __construct() {
        $this->setting = new SettingApi();
        $this->pages = array(
            array(
                'page_title' => 'Badge Issuer',
                'menu_title' => 'Badge Issuer',
                'capability' => 'manage_options',
                'menu_slug' => 'badge_issuer',
                'callback' => function () {
                    echo '<h1>Badge plugin</h1>';
                },
                'icon_url' => 'dashicons-awards',
                'position' => '110'
            )
        );

        $this->subpages = array(
            array(
                'parent_slug' => 'badge_issuer',
                'page_title' => 'Badges',
                'menu_title' => 'Badges',
                'capability' => 'manage_options',
                'menu_slug' => 'edit.php?post_type=badges_cpt'
            ),
            array(
                'parent_slug' => 'badge_issuer',
                'page_title' => 'Class',
                'menu_title' => 'Class',
                'capability' => 'manage_options',
                'menu_slug' => 'edit.php?post_type=class_cpt'
            ),
            array(
                'parent_slug' => 'badge_issuer',
                'page_title' => 'Fields of education',
                'menu_title' => 'Fields of education',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy=fields_issuer&post_type=badges_cpt'
            ),
            array(
                'parent_slug' => 'badge_issuer',
                'page_title' => 'Levels',
                'menu_title' => 'Levels',
                'capability' => 'manage_options',
                'menu_slug' => 'edit-tags.php?taxonomy=levels_issuer&post_type=badges_cpt'
            ),
            array(
                'parent_slug' => 'badge_issuer',
                'page_title' => 'Send Badges',
                'menu_title' => 'Send Badges',
                'capability' => 'manage_options',
                'menu_slug' => 'send_badge_issuer',
                'callback' => function () {
                    echo '<h1>Send Badges</h1>';
                }
            ),
            array(
                'parent_slug' => 'badge_issuer',
                'page_title' => 'Settings',
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'menu_slug' => 'Settings_issuer',
                'callback' => function () {
                    echo '<h1>Settings </h1>';
                }
            ),
        );

        $this->custom_post_types = array(
            array(
                'post_type' => 'badges_cpt',
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
            array(
                'post_type' => 'class_cpt',
                'args' => array(
                    'labels' => array(
                        'name' => 'Class',
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

        $this->taxonomies = array(
            array(
                'taxonomy' => 'fields_issuer',
                'object_type' => 'badges_cpt',
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
                    'rewrite' => array('slug' => 'level_issuer'),
                    'hierarchical' => true,
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'query_var' => true
                )
            ),
            array(
                'taxonomy' => 'levels_issuer',
                'object_type' => 'badges_cpt',
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
                    'rewrite' => array('slug' => 'levels_issuer'),
                    'hierarchical' => true,
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'query_var' => true
                )
            ),
        );
    }

    /**
     * Register the admin menu page
     */
    function register() {
        $this->setting->loadPages($this->pages)->withSubPage('Dashboard')->loadSubPages($this->subpages)
            ->loadCustomPostTypes($this->custom_post_types)->loadTaxonomy($this->taxonomies)->register();
    }


}

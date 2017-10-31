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
            )
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
                    'public'       => true,
                    'has_archive'  => true,
                    'show_ui'      => true,
                    'show_in_menu' => false, // adding to custom menu manually
                    'taxonomies'   => array(
                        'nwcm_news_category'
                    )
                ),
            )
        );
    }

    /**
     * Register the admin menu page
     */
    function register() {
        $this->setting->loadPages($this->pages)->withSubPage('Dashboard')->loadSubPages($this->subpages)
            ->loadCustomPostTypes($this->custom_post_types)->register();
    }


}

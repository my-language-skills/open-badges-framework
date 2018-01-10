<?php

namespace Inc\Api;

use Inc\Pages\Admin;

/**
 * This class permit to load all the wordpress component
 * (page, sub-pages, custom post type, ...).
 *
 * @author     Alessandro RICCARDI
 * @since      1.0.0
 *
 * @package     OpenBadgesFramework
 */
class SettingApi {

    public $admin_pages = array();
    public $admin_subpages = array();
    public $cpts = array();
    public $taxonomies = array();
    public $metaboxes = array();
    public $frontEndPages = array();

    /**
     * Function that permit to call the specific wordpress hooks
     * to initialize all the component
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     */
    public function register() {
        /* PAGES */
        if (!empty($this->admin_pages) && !empty($this->admin_subpages)) {
            add_action('admin_menu', array($this, 'addAdminMenu'));
        }
        /* CUSTOM-POST-TYPE && TAXONOMY*/
        if (!empty($this->cpts) && !empty($this->taxonomies)) {
            add_action('init', array($this, 'addInit'));
            add_filter('parent_file', array($this, 'setCurrentMenu'));
        }
        /* METABOX */
        if (!empty($this->metaboxes)) {
            add_action('add_meta_boxes', array($this, 'addMetaBoxes'));
        }
        /* FRONT-END-PAGES */
        if (!empty($this->frontEndPages)) {
            add_action('wp', array($this, 'addFrontEndPages'));
        }

    }

    /**
     * Permit to store the pages param in the param
     * of the class.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param array $pages Array content the menus
     *
     * @return $this The instance of tha class
     */
    public function loadPages(array $pages) {
        $this->admin_pages = $pages;

        return $this;
    }

    /**
     * Permit to add the first sub-page with a specific name to the
     * sub pages param of the class.
     *
     * @param null $title The name of the principal page of the plugin
     *
     * @return $this The instance of tha class
     */
    public function withSubPage($title = null) {
        if (empty($this->admin_pages)) {
            return $this;
        }

        $admin_page = $this->admin_pages[0];

        $subpages = array(
            array(
                'parent_slug' => $admin_page['menu_slug'],
                'page_title' => $admin_page['page_title'],
                'menu_title' => ($title) ? $title : $admin_page['menu_title'],
                'capability' => $admin_page['capability'],
                'menu_slug' => $admin_page['menu_slug'],
                'callback' => $admin_page['callback']
            )
        );

        $this->admin_subpages = $subpages;

        return $this;
    }

    /**
     * Load all the sub-pages inside the variable of the class.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param array $pages Array of pages
     *
     * @return $this The instance of tha class
     */
    public function loadSubPages(array $pages) {
        $this->admin_subpages = array_merge($this->admin_subpages, $pages);

        return $this;
    }

    /**
     * Load all the custom post type inside the variable of the
     * class.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param array $cpts Array of custom post type
     *
     * @return $this The instance of tha class
     */
    public function loadCustomPostTypes(array $cpts) {
        $this->cpts = $cpts;

        return $this;
    }

    /**
     * Load all the taxonomies inside the variable of the class.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param array $taxonomies Array of taxonomies
     *
     * @return $this The instance of tha class
     */
    public function loadTaxonomies(array $taxonomies) {
        $this->taxonomies = $taxonomies;

        return $this;
    }

    /**
     * Load all the meta-boxes inside the variable of the class.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param array $metaboxes Array of metaboxes
     *
     * @return $this The instance of tha class
     */
    public function loadMetaBoxes(array $metaboxes) {
        $this->metaboxes = $metaboxes;

        return $this;
    }

    /**
     * Load all the front-end-pages inside the variable of the class.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param array $frontEndPages Array of front-end-pages
     *
     * @return $this The instance of tha class
     */
    public function loadFrontEndPages(array $frontEndPages) {
        $this->frontEndPages = $frontEndPages;

        return $this;
    }

    /**
     * Loops all the $admin_pages adding at the "add_menu_page"
     * function all the menus.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     */
    public function addAdminMenu() {
        foreach ($this->admin_pages as $page) {
            add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'],
                $page['callback'], $page['icon_url'], $page['position']);
        }

        foreach ($this->admin_subpages as $page) {
            add_submenu_page($page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'],
                $page['menu_slug'], $page['callback']);
        }
    }

    /**
     * Loops the function "register_post_type" and
     * "register_taxonomy" to permit to add the custom
     * post type and taxonomy.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     */
    public function addInit() {
        foreach ($this->cpts as $cpt) {
            register_post_type($cpt['post_type'], $cpt['args']);
        }

        foreach ($this->taxonomies as $taxonomy) {
            register_taxonomy($taxonomy['taxonomy'], $taxonomy['object_type'], $taxonomy['args']);
        }
    }

    /**
     * Loops all the $metaboxes adding at the "add_meta_box"
     * function all the menus.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     */
    public function addMetaBoxes() {
        foreach ($this->metaboxes as $metabox) {
            add_meta_box(
                $metabox['id'],
                $metabox['title'],
                $metabox['callback'],
                $metabox['screen'],
                $metabox['context'],
                $metabox['priority']
            );
        }
    }

    /**
     * Loop through the classes, initialize them, and call the main()
     * method if it exists.
     * Throw the 'slug' we can understand when the page is loaded
     * and then when that specific page is active we load the 'main'
     * function of the class that refer in the param $this->frontEndPages.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     */
    public function addFrontEndPages() {
        foreach ($this->frontEndPages as $frontEndPage) {
            if (is_page($frontEndPage['slug'])) {
                $service = self::instantiate($frontEndPage['class']);
                if (method_exists($service, 'main')) {
                    $service->main();
                }
                die();
            }
        }

    }


    /**
     * Instantiation of a class.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param class $class class form services array
     *
     * @return class instance   new instance of the class
     */
    private static function instantiate($class) {
        return new $class();
    }

    /**
     * This function permit to set the current menu
     * for each post type and taxonomy.
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     *
     * @param $parent_file Of the plugin
     *
     * @return string The $parent_file variable that was passed like an argument
     */
    public function setCurrentMenu($parent_file) {
        global $submenu_file, $current_screen, $pagenow;

        if ($current_screen->post_type == Admin::POST_TYPE_BADGES) {

            if ($pagenow == 'post.php') {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ($pagenow == 'post-new.php') {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ($pagenow == 'edit-tags.php') {
                if ($current_screen->taxonomy == Admin::TAX_FIELDS) {
                    $submenu_file = 'edit-tags.php?taxonomy=' . Admin::TAX_FIELDS . '&post_type=' . $current_screen->post_type;
                } elseif ($current_screen->taxonomy == Admin::TAX_LEVELS) {
                    $submenu_file = 'edit-tags.php?taxonomy=' . Admin::TAX_LEVELS . '&post_type=' . $current_screen->post_type;
                }
            }



            $parent_file = Admin::SLUG_PLUGIN;

        } else if ($current_screen->taxonomy == Admin::TAX_FIELDS) {
            // Not working.
            if ($pagenow == 'term.php') {
                $submenu_file = 'edit-tags.php?taxonomy=' . Admin::TAX_FIELDS . '&post_type=' . Admin::POST_TYPE_BADGES;
            }

            $parent_file = Admin::SLUG_PLUGIN;

        }

        return $parent_file;

    }
}
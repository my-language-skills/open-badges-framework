<?php
namespace Inc\Api;

use Inc\Pages\Admin;

/**
 * The SettingApi Class, this class permit to load
 * all the $pages that we want to create
 *
 * @author     Alessandro RICCARDI
 * @since      x.x.x
 */
class SettingApi {

    public $admin_pages = array();
    public $admin_subpages = array();
    public $cpts = array();
    public $taxonomies = array();
    public $metaboxes = array();
    public $frontEndPages = array();

    /**
     * When called the function add extra submenus and
     * menu options to the admin panel's menu structure.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
     * Permit to add $pages in the "local" variable and
     * then is possible to call the class register().
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
     * This function permit to add the first subpage
     * to the plugin menu.
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
     * Load all the subpages inside the variable of
     * the class.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
     * Load all the custom post type inside the variable
     * of the class.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
     * Load all the taxonomies inside the variable of
     * the class.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
     * Load all the metaboxes inside the variable of
     * the class.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @param array $metaboxes Array of metaboxes
     *
     * @return $this The instance of tha class
     */
    public function loadMetaBoxes(array $metaboxes) {
        $this->metaboxes = $metaboxes;

        return $this;
    }

    public function loadFrontEndPages(array $frontEndPages) {
        $this->frontEndPages = $frontEndPages;

        return $this;
    }

    /**
     * Loops all the $admin_pages adding at the
     * "add_menu_page" function all the menus
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
     * post type and taxonomy
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
     * Loops all the $metaboxes adding at the
     * "add_meta_box" function all the menus
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
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
     * @since  x.x.x
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
     * Initialize the class
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
     * @param $parent_file Of the plugin
     *
     * @return string The $parent_file variable that was passed like an argument
     */
    public function setCurrentMenu($parent_file) {
        global $submenu_file, $current_screen, $pagenow;

        # Set the submenu as active/current while anywhere in your Custom Post Type (nwcm_news)
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

        } elseif ($current_screen->post_type == Admin::POST_TYPE_CLASS_JL) {

            if ($pagenow == 'post.php') {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ($pagenow == 'post-new.php') {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ($pagenow == 'edit-tags.php') {
                //
            }

            $parent_file = Admin::SLUG_PLUGIN;

        }

        return $parent_file;

    }
}
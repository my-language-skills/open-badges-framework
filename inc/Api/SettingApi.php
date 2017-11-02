<?php
/**
 * The SettingApi Class, this class permit to load
 * all the $pages that we want to create
 *
 * @since      x.x.x
 *
 * @package    FlexProduct
 */

namespace Inc\Api;


class SettingApi {

    public $admin_pages = array();
    public $admin_subpages = array();
    public $cpts = array();
    public $taxonomies = array();
    public $metaboxes = array();

    /**
     * When called the function add extra submenus and
     * menu options to the admin panel's menu structure.
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function register() {
        if (!empty($this->admin_pages) && !empty($this->admin_subpages)) {
            add_action('admin_menu', array($this, 'addAdminMenu'));

            if (!empty($this->cpts) && !empty($this->taxonomies)) {
                add_action('init', array($this, 'addInit'));
                //Set te current menu in the admin visualization
                add_filter( 'parent_file', array($this,'setCurrentMenu' ));

                if(!empty($this->metaboxes)){
                    //add_action('add_meta_boxes', 'addMetaBoxes');
                }
            }
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
    public function loadTaxonomy(array $taxonomies) {
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
     * ...
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
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function addMetaBoxes() {
        foreach ($this->metaboxes as $metabox) {
            add_meta_box(
                'id_meta_box_class_zero_students',
                'Class Students',
                'meta_box_class_zero_students',
                'class',
                'normal',
                'high'
            );
        }
    }

    /**
     * @param $parent_file Of the plugin
     *
     * @return string The $parent_file variable that was passed like an argument
     */
    function setCurrentMenu($parent_file ) {
        global $submenu_file, $current_screen, $pagenow;
        # Set the submenu as active/current while anywhere in your Custom Post Type (nwcm_news)
        if ( $current_screen->post_type == 'badges_cpt' ) {

            if ( $pagenow == 'post.php' ) {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ( $pagenow == 'edit-tags.php' ) {
                if ($current_screen->taxonomy == 'fields_issuer') {
                    $submenu_file = 'edit-tags.php?taxonomy=fields_issuer&post_type=' . $current_screen->post_type;
                } elseif ($current_screen->taxonomy == 'levels_issuer') {
                    $submenu_file = 'edit-tags.php?taxonomy=levels_issuer&post_type=' . $current_screen->post_type;
                }
            }

            $parent_file = 'badge_issuer';

        }

        return $parent_file;

    }

}
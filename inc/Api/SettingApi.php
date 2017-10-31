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
    public $metaboxes = array();

    /**
     * When called the function add extra submenus and
     * menu options to the admin panel's menu structure
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function register() {
        if (!empty($this->admin_pages)) {
            add_action('admin_menu', array($this, 'addAdminMenu'));

            if (!empty($this->cpts)) {
                add_action('init', array($this, 'addCustomPostTypes'));

                if(!empty($this->metaboxes)){
                    //add_action('add_meta_boxes', 'addMetaBoxes');
                }
            }
        }
    }

    /**
     * Permit to add $pages in the "local" variable and
     * then is possible to call the class register()
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     *
     * @param array $pages Array content all the menus
     *
     * @return $this The entire class
     */
    public function loadPages(array $pages) {
        $this->admin_pages = $pages;

        return $this;
    }

    public function withSubPage(string $title = null) {
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

    public function loadSubPages(array $pages) {
        $this->admin_subpages = array_merge($this->admin_subpages, $pages);

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
    public function loadCustomPostTypes(array $cpts) {
        $this->cpts = $cpts;

        return $this;
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function addCustomPostTypes() {
        foreach ($this->cpts as $cpt) {
            register_post_type($cpt['post_type'], $cpt['args']);
        }
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function loadMetaBoxes(array $metaBoxes) {
        $this->metaboxes = $metaBoxes;

        return $this;
    }

    /**
     * ...
     *
     * @author Alessandro RICCARDI
     * @since  x.x.x
     */
    public function addMetaBoxes() {
        foreach ($this->metaboxes as $metabox) {
            add_meta_box('id_meta_box_class_zero_students', 'Class Students', 'meta_box_class_zero_students', 'class', 'normal', 'high');
        }
    }

}
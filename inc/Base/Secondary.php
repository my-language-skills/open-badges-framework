<?php

namespace Inc\Base;

use Inc\Pages\Admin;

/**
 * Secondary class that allow to add feature to the plugin.
 *
 * @todo make this more intelligent.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class Secondary {

    /**
     * Trying to add the Restrict Content Pro extension in our plugin,
     * but it's not working.
     * Documentation: http://docs.restrictcontentpro.com/article/1750-rcp-metabox-post-types
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function register() {
        //add_filter('rcp_metabox_post_types', array($this, 'ag_rcp_metabox_post_types'));

    }

    public function ag_rcp_metabox_post_types($post_types) {
        $post_types[] = Admin::POST_TYPE_BADGES;
        return $post_types;
    }

    public static function isJobManagerActive() {
        return class_exists("WP_Job_Manager");
    }

    public static function isRCPActive() {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        $plugins = get_plugins();
        foreach ($plugins as $key => $value){
            if(strpos($key, 'restrict-content-pro') != false){
                return true;
            }
        }
        // default
        return false;
    }

    public static function isRCPIntegratedJBM() {
        if(self::isJobManagerActive()) {
            if (listify_has_integration('wp-job-manager')) {
                return true;
            }
        }
        // default
        return false;
    }
}

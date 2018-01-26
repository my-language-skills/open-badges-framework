<?php

namespace Inc\Base;

use Inc\Pages\Admin;

/**
 * Secondary class that allow to add feature to the plugin.
 * @todo make this more intelligent.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class Secondary {

    /**
     * Trying to add the Restrict Content Pro extension in our plugin (doesn't work).
     * Presume a bug of Restrict Content Pro
     * Documentation: http://docs.restrictcontentpro.com/article/1750-rcp-metabox-post-types
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function register() {
        //add_filter('rcp_metabox_post_types', array($this, 'ag_rcp_metabox_post_types'));

    }

    /**
     * Allow Restrict Content Pro to see our plugin as part of its
     * extension (doesn't work).
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param $post_types
     *
     * @return array
     */
    public function ag_rcp_metabox_post_types($post_types) {
        $post_types[] = Admin::POST_TYPE_BADGES;
        return $post_types;
    }

    /**
     * Is Job Manager Activated?
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return bool true if activated, otherwise false.
     */
    public static function isJobManagerActive() {
        return class_exists("WP_Job_Manager");
    }

    /**
     * Is Restrict Content Pro activated?
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return bool true if activated, otherwise false.
     */
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

    /**
     * Is Restrict Content Pro integrated with Job Manager?
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @return bool true if integrated, otherwise false.
     */
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

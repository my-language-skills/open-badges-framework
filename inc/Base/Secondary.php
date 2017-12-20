<?php

namespace Inc\Base;

use Inc\Pages\Admin;

/**
 * Secondary class that allow to add feature to the plugin.
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
}

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
     * ...
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function register() {
        add_filter('rcp_metabox_post_types', array($this, 'ag_rcp_metabox_post_types'));

    }

    public function ag_rcp_metabox_post_types($post_types) {
        $post_types[] = Admin::SLUG_PLUGIN;
        return $post_types;
    }
}

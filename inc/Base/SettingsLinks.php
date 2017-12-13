<?php

namespace Inc\Base;

use Inc\Pages\Admin;

/**
 * That class create the link for the setting and
 * make it visible in the plugin admin page.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class SettingsLinks extends BaseController {

    /**
     * Add the setting link in the plugin preview of
     * the plugin admin page.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function register() {
        add_filter("plugin_action_links_$this->plugin", array($this, 'settingsLink'));
    }

    /**
     * Create the link to the setting page .
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     *
     * @param string $links
     *
     * @return string $links The right link
     */
    public function settingsLink($links) {
        $settings_link = "<a href='admin.php?page=" . Admin::SLUG_PLUGIN . "'>Settigns</a>";
        array_push($links, $settings_link);
        return $links;

    }

}
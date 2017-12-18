<?php

namespace Inc\Base;

use Inc\Pages\Admin;

/**
 * That class create the setting link for
 * the plugin admin page.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class SettingsLinks extends BaseController {

    /**
     * Add the setting link in the plugin preview of
     * the plugin admin page.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function register() {
        add_filter("plugin_action_links_$this->plugin", array($this, 'settingsLink'));
    }

    /**
     * Create the link to the setting page .
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @param string $links
     *
     * @return string $links The right link
     */
    public function settingsLink($links) {
        $settings_link = "<a href='admin.php?page=" . Admin::PAGE_SETTINGS . "'>Settings</a>";
        array_push($links, $settings_link);
        return $links;

    }

}
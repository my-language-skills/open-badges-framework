<?php
/**
 * The SettingsLinks Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace Inc\Base;

use Inc\Base\BaseController;
use Inc\Pages\Admin;

class SettingsLinks extends BaseController{

    /**
     * Add the setting link in the plugin page of WordPress.
     */
    public function register() {
        add_filter("plugin_action_links_$this->plugin", array($this, 'settingsLink'));
    }

    /**
     * Create the link to the main page of the plugin
     *
     * @param $links
     *
     * @return string $links The right link
     */
    public function settingsLink($links) {
        $settings_link = "<a href='admin.php?page=". Admin::SLUG_PLUGIN ."'>Settigns</a>";
        array_push( $links, $settings_link);
        return $links;

    }

}
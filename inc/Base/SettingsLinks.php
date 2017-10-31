<?php
/**
 * The SettingsLinks Class
 *
 * @since      x.x.x
 *
 * @package FlexProduct
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

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
        $settings_link = '<a href="admin.php?page=badges_issuer_for_wp">Settigns</a>';
        array_push( $links, $settings_link);
        return $links;

    }

}
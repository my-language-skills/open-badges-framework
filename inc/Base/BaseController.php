<?php
/**
 * The BaseController Class
 *
 * @since      x.x.x
 *
 * @package    BadgeIssuerForWp
 */

namespace Inc\Base;

class BaseController {
    public $plugin_path;
    public $plugin_url;
    public $plugin;
    /**
     * BaseController constructor.
     */
    public function __construct() {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/badges-issuer-for-wp.php';
    }

    public function getJsonPath() {
        $path = wp_upload_dir()['basedir'] . '/badge-issuer-for-wp/json/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }


        return $path;
    }

    public function getJsonUrl() {
        $path = wp_upload_dir()['baseurl'] . '/badge-issuer-for-wp/json/';

        return $path;
    }
}
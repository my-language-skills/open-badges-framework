<?php
namespace Inc\Base;

/**
 * The BaseController Class.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
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
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/open-badges-framework.php';
    }

    public function getJsonFolderPath() {
        $path = wp_upload_dir()['basedir'] . '/open-badges-framework/json/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }


        return $path;
    }

    public function getJsonFolderUrl() {
        $path = wp_upload_dir()['baseurl'] . '/open-badges-framework/json/';

        return $path;
    }
}
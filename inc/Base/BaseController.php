<?php

namespace Inc\Base;

/**
 * The BaseController class permit to retrieve information
 * about the plugin path, plugin url and plugin initial
 * function.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class BaseController {
    public $plugin_path;
    public $plugin_url;
    public $plugin;

    /**
     * Here are initialized main variables.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public function __construct() {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/open-badges-framework.php';
    }

    /**
     * Get the path of the plugin.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @return string the path
     */
    public static function getPluginPath() {
        return plugin_dir_path(dirname(__FILE__, 2));
    }

    /**
     * Get the url of the plugin.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @return string the url
     */
    public static function getPluginUrl() {
        return plugin_dir_url(dirname(__FILE__, 2));
    }

    /**
     * Retrieve the path of the folder that we will
     * save the json file, if is not existing we will
     * create it.
     * path = ... /wp-content/uploads/open-badges-framework/json/
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @return string the path of the json folder.
     */
    public function getJsonFolderPath() {
        $path = wp_upload_dir()['basedir'] . '/open-badges-framework/json/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * Retrieve the url of the folder that are saved
     * the json file.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     *
     * @return string the url of the json folder.
     */
    public function getJsonFolderUrl() {
        $path = wp_upload_dir()['baseurl'] . '/open-badges-framework/json/';

        return $path;
    }
}
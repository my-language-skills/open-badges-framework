<?php

namespace Inc\Base;

/**
 * The BaseController class is a very useful because
 * principally permit to retrieve information about
 * the plugin path, plugin url and plugin initial
 * function.
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
     * Are initialized main variable.
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
     */
    public function __construct() {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/open-badges-framework.php';
    }

    /**
     * Retrieve the path of the folder that we will
     * save the json file, if is not existing we will
     * create it.
     * path = ... /wp-content/uploads/open-badges-framework/json/
     *
     * @author      Alessandro RICCARDI
     * @since       x.x.x
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
     * @since       x.x.x
     *
     * @return string the url of the json folder.
     */
    public function getJsonFolderUrl() {
        $path = wp_upload_dir()['baseurl'] . '/open-badges-framework/json/';

        return $path;
    }
}
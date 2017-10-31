<?php
/**
 * Class Save_setting
 *
 * @author Alessandro RICCARDI
 * @package badges-issuer-for-wp
 * @subpackage includes/utils
 * @since 0.6.3
 */

class SaveSetting {

    private $jsonPath = "";
    private $linksPath = "";
    private $loginLinksPath = "";

    /**
     * Construct that initialize the class.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    public function __construct() {
        $this->initialization();
    }

    /**
     * Initialization of the setting folder where will be stored the information.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    private function initialization() {
        // Saving the main information.
        $this->jsonPath = wp_upload_dir()['basedir'] . '/badges-issuer-for-wp/json/';
        $this->linksPath = $this->jsonPath . "links.json";
        $this->loginLinksPath = $this->jsonPath . "login_links.json";

        $linkDefaultInfo = array(
            "link_not_academy" => 0,
            "link_create_new_class" => 0
        );

        $loginLinkDefaultInfo = array(
            "link_login" => 0,
            "link_register" => 0
        );

        if (!file_exists($this->jsonPath)) {
            mkdir($this->jsonPath, 0777, true);
        }

        if (file_exists($this->linksPath) && !file_get_contents($this->linksPath)) {
            file_put_contents($this->linksPath, json_encode($linkDefaultInfo, JSON_UNESCAPED_SLASHES));
        } else if (!file_exists($this->linksPath)) {
            file_put_contents($this->linksPath, json_encode($linkDefaultInfo, JSON_UNESCAPED_SLASHES));
        }

        if (file_exists($this->loginLinksPath) && !file_get_contents($this->loginLinksPath)) {
            file_put_contents($this->loginLinksPath, json_encode($loginLinkDefaultInfo, JSON_UNESCAPED_SLASHES));
        } else if (!file_exists($this->loginLinksPath)) {
            file_put_contents($this->loginLinksPath, json_encode($loginLinkDefaultInfo, JSON_UNESCAPED_SLASHES));
        }
    }

    /**
     * Setting the information about the links.
     *
     * @author Alessandro RICCARDI
     * @param array $info
     * @since 0.6.3
     */
    public function set_settings_links(array $info) {
        file_put_contents(
            $this->linksPath,
            json_encode($info, JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Setting the information about the login links.
     *
     * @author Alessandro RICCARDI
     * @param array $info
     * @since 0.6.3
     */
    public function set_settings_login_links(array $info) {
        file_put_contents(
            $this->loginLinksPath,
            json_encode($info, JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Get the information about the links.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    function get_settings_links() {
        $content = file_get_contents($this->linksPath);
        $settings_links = json_decode($content, true);

        return $settings_links;
    }

    /**
     * Get the information about the login links.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    function get_settings_login_links() {
        $content = file_get_contents($this->loginLinksPath);
        $settings_login_links = json_decode($content, true);

        return $settings_login_links;
    }

}
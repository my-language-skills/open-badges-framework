<?php
/**
 * ...
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     BadgeIssuerForWp
 */

namespace inc\Utils;


class Badge {
    /**
     *  $name The name of the badge.
     */
    var $name;
    /**
     *  $level The level of the badge.
     */
    var $level;
    /**
     *  $language The language of the badge.
     */
    var $language;
    /**
     *  $certified To know if the badge is certified or not.
     */
    var $certified;
    /**
     *  $comment The comment written by the sender about the badge.
     */
    var $comment;
    /**
     *  $description The description of the badge selected by the sender.
     */
    var $description;
    /**
     *  $description_language The language description of the badge selected by the sender.
     */
    var $description_language;
    /**
     *  $image The image of the badge.
     */
    var $image;
    /**
     *  $url_json_files The url (extern location) of the json files directory.
     */
    var $url_json_files;
    /**
     *  $path_dir_json_files The path (intern location) of the json files directory.
     */
    var $path_dir_json_files;

    /**
     * The constructor of the Badge object.
     *
     * @author Nicolas TORION
     * @since  0.6.2
     *
     * @param $_name                The name of the badge.
     * @param $_level               The level of the badge.
     * @param $_language            The language of the badge.
     * @param $_comment             The comment written by the sender about the badge.
     * @param $_description         The description of the badge selected by the sender.
     * @param $_image               The image of the badge.
     * @param $_url_json_files      The url (extern location) of the json files directory.
     * @param $_path_dir_json_files The path (intern location) of the json files directory.
     */
    function __construct($_name, $_level, $_language, $_certified, $_comment, $_description, $_description_language, $_image, $_url_json_files, $_path_dir_json_files) {
        $this->name = $_name;
        $this->level = $_level;
        $this->language = $_language;
        $this->certified = $_certified;
        $this->comment = $_comment;
        $this->description = $_description;
        $this->description_language = $_description_language;
        $this->image = urldecode(str_replace("\\", "", $_image));
        $this->url_json_files = $_url_json_files;
        $this->path_dir_json_files = $_path_dir_json_files;
    }

}
<?php

/**
 * Creation of the badges issuer file.
 *
 * @since 0.6
 */

$badges_issuer_file_content = array(
    "name" => "",
    "image" => "",
    "url" => "",
    "email" => ""
);

$path_dir_json_files = plugin_dir_path(dirname(__FILE__)) . '../../../uploads/badges-issuer/json/';

if (!file_exists($path_dir_json_files)) {
    mkdir($path_dir_json_files, 0777, true);
}

$file = $path_dir_json_files . "badge-issuer.json";

if (!file_get_contents($file)) {
    file_put_contents($file, json_encode($badges_issuer_file_content, JSON_UNESCAPED_SLASHES));
}

?>

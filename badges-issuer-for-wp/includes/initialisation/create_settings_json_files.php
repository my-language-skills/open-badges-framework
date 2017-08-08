<?php

/*

CREATE SETTINGS LINKS JSON FILE

*/

$settings_links_file_content = array(
  "link_not_academy"  => 0,
  "link_create_new_class" => 0
);

$path_dir_json_files = plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/settings/json/';

if (!file_exists($path_dir_json_files)) {
    mkdir($path_dir_json_files, 0777, true);
}

$file_settings_links = $path_dir_json_files."links.json";

if(!file_get_contents($file_settings_links))
  file_put_contents($file_settings_links, json_encode($settings_links_file_content, JSON_UNESCAPED_SLASHES));


?>

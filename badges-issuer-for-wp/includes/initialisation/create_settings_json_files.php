<?php

/*
 * Create settinngs links json file.
 *
 * @since 0.6.1
*/

$settings_links_file_content = array(
  "link_not_academy"  => 0,
  "link_create_new_class" => 0
);

$settings_login_links_file_content = array(
  "link_login"  => 0,
  "link_register" => 0
);

$path_dir_json_files = plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/settings/json/';

if (!file_exists($path_dir_json_files)) {
    mkdir($path_dir_json_files, 0777, true);
}

$file_settings_links = $path_dir_json_files."links.json";
$file_settings_login_links = $path_dir_json_files."login_links.json";

if(!file_get_contents($file_settings_links))
  file_put_contents($file_settings_links, json_encode($settings_links_file_content, JSON_UNESCAPED_SLASHES));

if(!file_get_contents($file_settings_login_links))
  file_put_contents($file_settings_login_links, json_encode($settings_login_links_file_content, JSON_UNESCAPED_SLASHES));


?>

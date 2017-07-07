<?php

$badges_issuer_file_content = array(
  "name"  => "My Language Skills",
  "image" => "https://mylanguageskills.files.wordpress.com/2015/08/badges4languages-hi.png?w=800",
  "url"   => "https://mylanguageskills.files.wordpress.com/",
  "email" => "nicolastorion@gmail.com"
);

$path_dir_json_files = plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/badges-issuer/json/';

if (!file_exists($path_dir_json_files)) {
    mkdir($path_dir_json_files, 0777, true);
}

$file = $path_dir_json_files."badges-issuer.json";

file_put_contents($file, json_encode($badges_issuer_file_content, JSON_UNESCAPED_SLASHES));

?>

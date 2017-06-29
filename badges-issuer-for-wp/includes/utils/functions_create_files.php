<?php

// CREATE JSON FILES FUNCTIONS

/**
 * Creates the badge json file with the badge's informations given.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $level Badge's level.
 * @param $language Badge's language.
 * @param $comment Teacher's comment for the student.
 * @param $others_items Other badge's informations (name, description, image).
 * @param $path_dir_json_files Path of the json files directory in the server.
 * @param $url_json_files Url of the json files directory in extern.
 * @param $badge_filename Name of file that will be created.
*/
function create_badge_json_file($level, $language, $comment, $others_items, $path_dir_json_files, $url_json_files, $badge_filename) {
  $name = $others_items["name"];
  $description = "Language : ".$language.", Level : ".$level.", Comment : ".$comment.", Description : ".$others_items["description"];
  $image = urldecode(str_replace("\\", "", $others_items["image"]));

  $badge_informations = array(
    '@context'=>'https://w3id.org/openbadges/v1',
    "name"=>$name." ".$language,
    "description"=>$description,
    "image"=>$image,
    "language"=>$language,
    "level"=>$level,
    "criteria"=>$url_json_files."criteria.html",
  	"issuer"=>$url_json_files."badge-issuer.json"
  );

  $file = $path_dir_json_files.$badge_filename;
  file_put_contents($file, json_encode($badge_informations, JSON_UNESCAPED_SLASHES));
}

/**
 * Creates the assertion json file with send's informations.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $mail Student's mail adress.
 * @param $path_dir_json_files Path of the json files directory in the server.
 * @param $url_json_files Url of the json files directory in extern.
 * @param $badge_filename Name of the badge json file.
 * @param $assertion_filename Name of the assertion json file that will be created.
*/
function create_assertion_json_file($mail, $path_dir_json_files, $url_json_files, $badge_filename, $assertion_filename) {
  $salt=uniqid();
  $date=date('Y-m-d');

  $assertion = array(
    "uid" => $salt,
    "recipient" => array("type" => "email", "identity" => $mail, "hashed" => false),
    "issuedOn" =>  $date,
    "badge" => $url_json_files.$badge_filename,
    "verify" => array("type" => "hosted", "url" => $url_json_files.$assertion_filename)
  );

  $file = $path_dir_json_files.$assertion_filename;
  file_put_contents($file, json_encode($assertion, JSON_UNESCAPED_SLASHES));
}

 ?>

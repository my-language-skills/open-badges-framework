<?php
/**
 * Class Badge Issuer which allows interactions with the badge issuer informations in the plugin.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/utils
 * @since 1.0.0
*/

class BadgeIssuer
{
  var $name;
  var $image;
  var $url;
  var $email;

  function __construct() {
    $content = file_get_contents(plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/badges-issuer/json/badge-issuer.json');
    $badge_issuer_informations = json_decode($content, true);

    $this->name = $badge_issuer_informations['name'];
    $this->image = $badge_issuer_informations['image'];
    $this->url = $badge_issuer_informations['url'];
    $this->email = $badge_issuer_informations['email'];
  }

  function change_informations($name, $image, $url, $email) {
    $this->name = $name;
    $this->image = $image;
    $this->url = $url;
    $this->email = $email;

    $this->save_informations();
  }

  function save_informations() {
    $badges_issuer_file_content = array(
      "name"  => $this->name,
      "image" => urldecode(str_replace("\\", "", $this->image)),
      "url"   => $this->url,
      "email" => $this->email
    );

    file_put_contents(plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/badges-issuer/json/badge-issuer.json', json_encode($badges_issuer_file_content, JSON_UNESCAPED_SLASHES));
  }

}

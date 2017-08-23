<?php

/**
 * When the student get his badge, the files corresponding to this badge are removed from the server.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/utils
 * @since 1.0.0
*/

require_once("../../../../../wp-load.php");
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

if(is_user_logged_in()) {
  if(isset($_GET['hash'])) {
    $upload_dir = wp_upload_dir();
    $json_files_dir = $upload_dir['basedir']."/badges-issuer/json/";
    $url_assertion = $json_files_dir."assertion_".$_GET['hash'].".json";
    $url_badge = $json_files_dir."badge_".$_GET['hash'].".json";

    if(unlink($url_assertion) && unlink($url_badge)) {
      set_badge_as_received($_GET['hash']);
      echo "<center>";
      printf( _e('<h2>You received your badge ! <br /> You are going to be redirected to the class page corresponding to the badge or you can close this page. <br /> Thanks for using Badges For Languages.</h2>','badges-issuer-for-wp'));
      if(isset($_GET['class']))
        echo '<script>window.location.href = "'.get_permalink($_GET['class']).'";</script>';
      echo "<script>window.close();</script>";
      echo "</center>";
    }
  }
}
else
  printf(esc_html__('Not connected!','badges-issuer-for-wp'));

?>

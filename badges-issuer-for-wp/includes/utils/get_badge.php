<?php

/**
 * Allows a student to get his badge.
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
    $url_json_files = "http://".$_SERVER['SERVER_NAME']."/wp-content/uploads/badges-issuer/json/";
    $url = $url_json_files."assertion_".$_GET['hash'].".json";
    $url_delete = "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/utils/delete_badge.php?hash=".$_GET['hash'];
    ?>
    <style>
    button {
      padding: 5px;
      font-size: 20px;
      border-radius: 10px;
    }
    </style>
    <center>
            <h1>Get your badge</h1><br />
            <img src="http://dougbelshaw.com/blog/wp-content/uploads/2013/03/openbadges-600px.jpg" width="300px" height="150px"/>
            <p>
              To get your badge, you need a Mozilla OpenBadges account.<br />
              If you don't have a Mozilla OpenBadges account, you can create one <a href="https://backpack.openbadges.org/backpack/signup" target="_blank">here</a>.
            </p>
            <br /><br />
            <button onclick="getBadge()">Get badge</button>
    </center>

    <?php

    echo '<script src="https://backpack.openbadges.org/issuer.js"></script>';
    echo "
    <script>

    function isInArray(value, array) {
      return array.indexOf(value) > -1;
    }

    function getBadge() {
      OpenBadges.issue(['".$url."'], function(errors, successes) {
        if(isInArray('".$url."', successes)) {
          window.location.href='".$url_delete."';
        }
      });
    }

    </script>";

  }
}
else
  display_not_logged_message();

?>

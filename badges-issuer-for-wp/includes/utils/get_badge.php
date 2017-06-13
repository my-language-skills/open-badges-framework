<?php

require_once("../../../../../wp-load.php");
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

if(is_user_logged_in()) {
  if(isset($_GET['hash'])) {
    $url_json_files = "http://".$_SERVER['SERVER_NAME']."/wp-content/uploads/badges-issuer/json/";
    $url = $url_json_files."assertion_".$_GET['hash'].".json";
    $url_delete = "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/utils/delete_badge.php?hash=".$_GET['hash'];
    ?>

    <center>
            <h1>Get your badge</h1><br />
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

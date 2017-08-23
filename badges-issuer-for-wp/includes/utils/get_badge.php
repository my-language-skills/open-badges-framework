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
    $url_json_files = content_url('uploads/badges-issuer/json/');
    $url = $url_json_files."assertion_".$_GET['hash'].".json";
    $url_delete = plugins_url( 'delete_badge.php', __FILE__ );
    $url_delete = $url_delete."?hash=".$_GET['hash'];
    if($_GET['class'])
      $url_delete = $url_delete."&class=".$_GET['class'];

    ?>
    <style>
    button {
      padding: 5px;
      font-size: 20px;
      border-radius: 10px;
    }
    </style>
    <center>
            <h1><?php _e( 'Get your badge','badges-issuer-for-wp' ); ?></h1><br/>
            <img src="<?php echo plugins_url( '../../images/b4l_logo.png', __FILE__ ); ?>" width="200px" height="200px"/>
            <p>
              <?php _e('
              To get your badge, you need a Mozilla OpenBadges account.<br />
              If you don\'t have a Mozilla OpenBadges account, you can create one <a href="https://backpack.openbadges.org/backpack/signup" target="_blank">here</a>.
              <br /><br />
              <b>By accepting the badge, you will have access to the class page in order to post a review.</b>
              ','badges-issuer-for-wp' );
              ?>
            </p>
            <?php if(check_if_user_has_already_a_badge($_GET['hash'])){ ?>
            <p style="color:red;">
              <?php
              _e('You already have this badge in your user profile ! <br /> Maybe that OpenBadges will refuse to add this badge to your Backpack account a second time.', 'badges-issuer-for-wp');
              ?>
            </p>
            <?php } ?>
            <img src="<?php echo plugins_url( '../../images/openbadges_logo.jpg', __FILE__ ); ?>" width="300px" height="150px"/>
            <br /><br />
            <button onclick="getBadge()"><?php _e( 'Get badge','badges-issuer-for-wp' ); ?></button>
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

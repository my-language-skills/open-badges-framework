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
            <h1><?php _e( 'Please, read before to start','badges-issuer-for-wp' ); ?></h1>
            <br/ >
            <hr />
            <br />
            <h3>
              <?php _e('
              <b>By accepting the badge, you will have access to the class page where you can post a review of your teacher.</b>
              <br />
              <br />
              <br />
              Before to get your first badge, you need a Mozilla OpenBadges account. Please, <a href="https://backpack.openbadges.org/backpack/signup" target="_blank">register here</a>
              <br />
              <br />
              If you allready have an account, just get the badge.
              <br />
              <br />
              ','badges-issuer-for-wp' );
              ?>
            </h3>
            <?php if(check_if_user_has_already_a_badge($_GET['hash'])){ ?>
            <p style="color:red;">
              <?php
              _e('You already have this badge in your user profile ! <br /> Maybe that OpenBadges will refuse to add this badge to your Backpack account a second time.', 'badges-issuer-for-wp');
              ?>
            </p>
            <?php } ?>
            <button onclick="getBadge()"><?php _e( 'Get badge '.$_GET['level'].' '.$_GET['language'],'badges-issuer-for-wp' ); ?></button>
            <br />
            <br />
            <hr >
            <br />
            <img src="<?php echo plugins_url( '../../assets/b4l_logo.png', __FILE__ ); ?>" width="150px" height="150px"/>
            <img src="<?php echo plugins_url( '../../assets/openbadges_logo_thumbnail.png', __FILE__ ); ?>" width="300px" height="100px"/>
            <hr/>
            <p style="font-size:10px; color:grey">
            <?php
            _e('<a href="http://badges4languages.com/" target="_blank">Badges for Languages</a> by My Language Skills, based in <a href="https://youtu.be/W_HghoAlZKo" target="_blank">Valencia</a>, Spain.
            More information <a href="https://mylanguageskills.wordpress.com/" target="_blank">here</a>.
            Legal information <a href="https://mylanguageskillslegal.wordpress.com/category/english/badges-for-languages-english/" target="_blank">here</a>.', 'badges-issuer-for-wp');
            ?>
            </p>
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

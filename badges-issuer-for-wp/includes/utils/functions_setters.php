<?php

/**
 * Change the id links in the corresponding json file.
 *
 * @author Nicolas TORION
 * @since 0.6.1
 * @param $settings_links The array of the new id links values.
*/
function set_settings_links($settings_id_links) {
    file_put_contents(plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/settings/json/links.json', json_encode($settings_id_links, JSON_UNESCAPED_SLASHES));
}

function set_settings_login_links($settings_id_login_links) {
    file_put_contents(plugin_dir_path( dirname( __FILE__ ) ) . '../../../uploads/settings/json/login_links.json', json_encode($settings_id_login_links, JSON_UNESCAPED_SLASHES));
}

function set_badge_as_received($hash) {
  global $current_user;
  get_currentuserinfo();

  $badges = get_the_author_meta( 'badges_received', $current_user->ID );

  if(!$badges)
    $badges=array();

  $badges[] = $hash;

  update_user_meta( $current_user->ID, 'badges_received', $badges);
}

?>

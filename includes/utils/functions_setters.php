<?php


/**
 * Change the id links in the corresponding json file.
 *
 * @author Nicolas TORION
 * @since  0.6.1
 *
 * @param $settings_links The array of the new id links values.
 */

function set_badge_as_received($hash) {
    global $current_user;
    wp_get_current_user();

    $badges = get_the_author_meta('badges_received', $current_user->ID);

    if (!$badges) {
        $badges = array();
    }

    $badges[] = $hash;

    update_user_meta($current_user->ID, 'badges_received', $badges);
}

?>

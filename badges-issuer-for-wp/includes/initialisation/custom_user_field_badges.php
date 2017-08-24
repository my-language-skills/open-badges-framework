<?php

/**
 * Adds a badges field in the user profile. Displays the badges of an user in his own user profile.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/initialisation
 * @since 1.0.0
*/

function user_field_badges( $user ) {
    if(get_the_author_meta( 'user_badges', $user->ID ))
      $user_badges = get_the_author_meta( 'user_badges', $user->ID );
    else
      $user_badges = array();
    ?>
    <h3><?php _e( 'Badges', 'badges-issuer-for-wp' ); ?></h3>
    <table width="100%">
      <thead>
        <tr>
          <th width="0%"><?php _e( 'Badge name', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%"><?php _e( 'Badge language', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%"><?php _e( 'Sender', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%"><?php _e( 'Comment', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%"><?php _e( 'Level', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%"><?php _e( 'Date', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%"><?php _e( 'Class', 'badges-issuer-for-wp' ); ?></th>
        </tr>
      </thead>
      <tbody>
    <?php
    foreach ($user_badges as $user_badge) {
      echo '<tr>';
        echo '<td width="0%">';
        echo $user_badge['name'];
        echo '</td>';
        echo '<td width="0%">';
        echo $user_badge['language'];
        echo '</td>';
        echo '<td width="0%">';
        echo $user_badge['sender'];
        echo '</td>';
        echo '<td width="0%">';
        echo $user_badge['comment'];
        echo '</td>';
        echo '<td width="0%">';
        echo $user_badge['level'];
        echo '</td>';
        echo '<td width="0%">';
        echo $user_badge['date'];
        echo '</td>';
        echo '<td width="0%">';
        if(array_key_exists('class', $user_badge))
          echo $user_badge['class'];
        echo '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';
}
add_action( 'show_user_profile', 'user_field_badges' );
add_action( 'edit_user_profile', 'user_field_badges' );

?>

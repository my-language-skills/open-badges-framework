<?php

/**
 * Adds a badges field in the user profile. Displays the badges of an user in his own user profile.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/initialisation
 * @since 0.6.3
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
          <!-- changes made to these lines by uzair-->
          <th width="0%" align="left"><?php _e( 'Date', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Badge name', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Badge language', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Level', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Description Language', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Sender', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Sender Type', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Certification', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Class', 'badges-issuer-for-wp' ); ?></th>
          <th width="0%" align="left"><?php _e( 'Comment', 'badges-issuer-for-wp' ); ?></th>
          <!-- /changes made to these lines by uzair-->
        </tr>
      </thead>
      <tbody>
    <?php
    foreach ($user_badges as $user_badge) {
      echo '<tr>';
        echo '<td width="0%">';
          echo $user_badge['date'];
        echo '</td>';
        echo '<td width="0%">';
          echo $user_badge['name'];
        echo '</td>';
        echo '<td width="0%">';
          echo $user_badge['language'];
        echo '</td>';
        echo '<td width="0%">';
          echo $user_badge['level'];
        echo '</td>';
        echo '<td width="0%">';
          echo $user_badge['description_language'];
        echo '</td>';
        echo '<td width="0%">';
          echo $user_badge['sender'];
        echo '</td>';
        echo '<td width="0%">';
          echo $user_badge['sender_type'];
        echo '</td>';
        echo '<td width="0%">';
          echo $user_badge['certified'];
        echo '</td>';
        echo '<td width="0%">';
        if(array_key_exists('class', $user_badge))
          echo $user_badge['class'];
        echo '</td>';
        echo '<td width="0%">';
          echo $user_badge['comment'];
        echo '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';
}
add_action( 'show_user_profile', 'user_field_badges' );
add_action( 'edit_user_profile', 'user_field_badges' );

?>

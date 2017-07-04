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
    $user_badges = get_the_author_meta( 'user_badges', $user->ID );
    ?>
    <h3>Badges</h3>
    <table width="100%">
      <thead>
        <tr>
          <th width="0%">Badge name</th>
          <th width="0%">Badge language</th>
          <th width="0%">Sender</th>
          <th width="0%">Comment</th>
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
      echo '</tr>';
    }
    echo '</tbody></table>';
}
add_action( 'show_user_profile', 'user_field_badges' );
add_action( 'edit_user_profile', 'user_field_badges' );

?>

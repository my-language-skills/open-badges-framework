<?php

function my_plugin_exporter( $email_address, $page = 1 ) {

  //$number = 500; // Limit us to avoid timing out
  $page = (int) $page;
 
  $export_items = array();

  $user = get_user_by( 'email', $email_address);

  $year_of_birth = get_the_author_meta( 'year_of_birth', $user->ID );
  $country = get_the_author_meta( 'country', $user->ID );
  $city = get_the_author_meta( 'city', $user->ID );
  $mother_tongue = get_the_author_meta( 'mother_tongue', $user->ID );
  $primary_degree = get_the_author_meta( 'primary_degree', $user->ID );
  $secondary_degree = get_the_author_meta( 'secondary_degree', $user->ID );
  $tertiary_degree = get_the_author_meta( 'tertiary_degree', $user->ID );

  $data = array(
    array(
      'name' => __( 'User year of birth' ),
      'value' => $year_of_birth
    ),
    array(
      'name' => __( 'User country' ),
      'value' => $country
    ),
    array(
      'name' => __( 'User city' ),
      'value' => $city
    ),
    array(
      'name' => __( 'User mother tongue' ),
      'value' => $mother_tongue
    ),
    array(
      'name' => __( 'User primary degree' ),
      'value' => $primary_degree
    ),
    array(
      'name' => __( 'User secondary degree' ),
      'value' => $secondary_degree
    ),
    array(
      'name' => __( 'User tertiary degree' ),
      'value' => $tertiary_degree
    )
  );

  $export_items[] = array(
    'group_id' => 'user',
    'data' => $data,
  );
 
  // Tell core if we have more comments to work on still
  return array(
    'data' => $export_items,
    'done' => 1,
  );
}

function register_my_plugin_exporter( $exporters ) {
  $exporters['open-badges-framework'] = array(
    'exporter_friendly_name' => __( 'Open Badges Framework' ),
    'callback' => 'my_plugin_exporter',
  );
  return $exporters;
}
 
add_filter( 'wp_privacy_personal_data_exporters', 'register_my_plugin_exporter', 10 );

/*foreach ( (array) $comments as $comment ) {
    $latitude = get_comment_meta( $comment->comment_ID, 'latitude', true );
    $longitude = get_comment_meta( $comment->comment_ID, 'longitude', true );
 
    // Only add location data to the export if it is not empty
    if ( ! empty( $latitude ) ) {
      // Most item IDs should look like postType-postID
      // If you don't have a post, comment or other ID to work with,
      // use a unique value to avoid having this item's export
      // combined in the final report with other items of the same id
      $item_id = "comment-{$comment->comment_ID}";
 
      // Core group IDs include 'comments', 'posts', etc.
      // But you can add your own group IDs as needed
      $group_id = 'user';
 
      // Optional group label. Core provides these for core groups.
      // If you define your own group, the first exporter to
      // include a label will be used as the group label in the
      // final exported report
      $group_label = __( 'User' );
 
      // Plugins can add as many items in the item data array as they want
      $data = array(
        array(
          'name' => __( 'User year of birth' ),
          'value' => $latitude
        ),
        array(
          'name' => __( 'User country' ),
          'value' => $longitude
        )
      );
 
      $export_items[] = array(
        'group_id' => $group_id,
        'group_label' => $group_label,
        'item_id' => $item_id,
        'data' => $data,
      );
    }
  }*/
<?php

function my_plugin_exporter( $email_address, $page = 1 ) {
  $page = (int) $page;
 
  $export_items = array();

  $user = get_user_by( 'email', $email_address);

  $data = array();

  $year_of_birth = get_the_author_meta( 'year_of_birth', $user->ID );
  $country = get_the_author_meta( 'country', $user->ID );
  $city = get_the_author_meta( 'city', $user->ID );
  $mother_tongue = get_the_author_meta( 'mother_tongue', $user->ID );
  $primary_degree = get_the_author_meta( 'primary_degree', $user->ID );
  $secondary_degree = get_the_author_meta( 'secondary_degree', $user->ID );
  $tertiary_degree = get_the_author_meta( 'tertiary_degree', $user->ID );

  if( ! empty( $year_of_birth ) ){
    array_push( $data, array( 'name' => __( 'User year of birth' ), 'value' => $year_of_birth ) );
  }

  if( ! empty( $country ) ){
    array_push( $data, array( 'name' => __( 'User country' ), 'value' => $country ) );
  }

  if( ! empty( $city ) ){
    array_push( $data, array( 'name' => __( 'User city' ), 'value' => $city ) );
  }

  if( ! empty( $mother_tongue ) ){
    array_push( $data, array( 'name' => __( 'User mother tongue' ), 'value' => $mother_tongue ) );
  }

  if( ! empty( $primary_degree ) ){
    array_push( $data, array( 'name' => __( 'User primary degree' ), 'value' => $primary_degree ) );
  }

  if( ! empty( $secondary_degree ) ){
    array_push( $data, array( 'name' => __( 'User secondary degree' ), 'value' => $secondary_degree ) );
  }

  if( ! empty( $tertiary_degree ) ){
    array_push( $data, array( 'name' => __( 'User tertiary degree' ), 'value' => $tertiary_degree ) );
  }

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
<?php

use Inc\Database\DbBadge;
use Inc\Database\DbUser;
use Inc\Base\Secondary;
use Inc\Utils\Badge;

/**
 * Create a custom WP Personal Data Exporter to export the custom data created
 *
 * @author @leocharlier
 * @since 1.0.1
 */
function my_plugin_exporter( $email_address, $page = 1 ) {
  $page = (int) $page;
 
  $export_items = array();

  //User information
  $user = get_user_by( 'email', $email_address);

  $data = array();

  $year_of_birth = get_the_author_meta( 'year_of_birth', $user->ID );
  $country = get_the_author_meta( 'country', $user->ID );
  $city = get_the_author_meta( 'city', $user->ID );
  $mother_tongue = get_the_author_meta( 'mother_tongue', $user->ID );
  $primary_degree = get_the_author_meta( 'primary_degree', $user->ID );
  $secondary_degree = get_the_author_meta( 'secondary_degree', $user->ID );
  $tertiary_degree = get_the_author_meta( 'tertiary_degree', $user->ID );

  //Badges earned information
  $badges_earned = array();

  $userDb = DbUser::getSingle( ["idWP" => $user->ID] );
  $dbBadgesEarned = DbBadge::get( Array( "idUser" => $userDb->id ) );

  //Display the class only if WP Job Manager is activated
  if ( !Secondary::isJobManagerActive() ) {
    foreach ($dbBadgesEarned as $dbBadge) {
      $badge = new Badge();
      $badge->retrieveBadge( $dbBadge->id );
      if ( $dbBadge->gotDate ) {
        //Check if the badge is self sent or not
        ( $badge->idTeacher == $user->ID ) ? array_push( $badges_earned, get_the_title( $badge->idBadge ) . ' (Self sent)' ) : array_push( $badges_earned, get_the_title( $badge->idBadge ) );
      }
    }
  } else{
    foreach ($dbBadgesEarned as $dbBadge) {
      $badge = new Badge();
      $badge->retrieveBadge( $dbBadge->id );
      if ( $dbBadge->gotDate ) {
        if( get_post( $dbBadge->idClass ) ){
          array_push( $badges_earned, get_the_title( $dbBadge->idBadge ) . ' (Class: ' . get_post( $dbBadge->idClass )->post_title . ')');
        }else{
          //Check if the badge is self sent or not
          ( $badge->idTeacher == $user->ID ) ? array_push( $badges_earned, get_the_title( $badge->idBadge ) . ' (Self sent)' ) : array_push( $badges_earned, get_the_title( $badge->idBadge ) );
        }
      }
    }
  }

  //Badges sent information
  $badges_sent = array();

  $dbBadgesSent = DbBadge::get( Array( "idTeacher" => $user->ID ) );

  //Display the class only if WP Job Manager is activated
  if ( !Secondary::isJobManagerActive() ) {
    foreach ($dbBadgesSent as $dbBadge) {
      $badge = new Badge();
      $badge->retrieveBadge( $dbBadge->id );
      array_push( $badges_sent, get_the_title( $badge->idBadge ) );
    }
  } else{
    foreach ($dbBadgesSent as $dbBadge) {
      if( get_post( $dbBadge->idClass ) ){
        array_push( $badges_sent, get_the_title( $dbBadge->idBadge ) . ' (Class: ' . get_post( $dbBadge->idClass )->post_title . ')');
      }else{
        array_push( $badges_sent, get_the_title( $dbBadge->idBadge ) );
      }
    }
  }
  
  // Stores user information
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

  if( ! empty( $badges_earned ) ){
    array_push( $data, array( 'name' => __( 'Badges earned' ), 'value' => implode( ', ', $badges_earned ) ) );
  }

  if( ! empty( $badges_sent ) ){
    array_push( $data, array( 'name' => __( 'Badges Sent' ), 'value' => implode( ', ', $badges_sent ) ) );
  }

  $export_items[] = array(
    'group_id' => 'user', //Section where the data will be display
    'data' => $data, //Data exported
  );
 
  // Tell core if we have more comments to work on still
  return array(
    'data' => $export_items,
    'done' => 1,
  );
}

/**
 * Register the custom WP Personal Data Exporter for the OBF plugin
 *
 * @author @leocharlier
 * @since 1.0.1
 */
function register_my_plugin_exporter( $exporters ) {
  $exporters['open-badges-framework'] = array(
    'exporter_friendly_name' => __( 'Open Badges Framework' ),
    'callback' => 'my_plugin_exporter',
  );
  return $exporters;
}
 
add_filter( 'wp_privacy_personal_data_exporters', 'register_my_plugin_exporter', 10 );
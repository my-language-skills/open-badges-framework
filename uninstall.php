<?php
/**
 * Trigger this file on Plugin uninstall
 *
 * @package  BadgeIssuerForWp
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}
// Clear Database stored data
$products = get_posts( array( 'post_type' => 'product', 'numberposts' => -1 ) );
foreach($products as $product ) {
    wp_delete_post( $product->ID, true );
}
// Access the database via SQL
global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'product'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );
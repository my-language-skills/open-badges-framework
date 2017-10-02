<?php

/**
 * This file creates a custom post type badge.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/initialisation
 * @since 0.6.2
*/

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

/**
* Register the custom post type.
*
* @author Nicolas TORION
* @since 0.4
*/
add_action('init', 'register_badge');
function register_badge()
{
    register_post_type('badge',
        array(
            'labels' => array(
                'name' => 'Badge School',
                'singular_name' => 'Badge',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Badge',
                'edit' => 'Edit',
                'edit_item' => 'Edit Badge',
                'new_item' => 'New Badge',
                'view' => 'View',
                'view_item' => 'View Badge',
                'search_items' => 'Search Badges',
                'not_found' => 'No Badges found',
                'not_found_in_trash' => 'No Badges found in Trash',
                'parent' => 'Parent Badges'
            ),

            'public' => true,
            'menu_position' => 15,
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array(''),
            'menu_icon' => plugins_url('../../assets/icon.png', __FILE__),
            'has_archive' => true
        )
    );
}

/**
* Check if the $val is equal to the $expected value.
*
* @author Nicolas TORION
* @since 0.4
* @param $val value to verify
* $expected value that is confronted with the first param.
*/
function check($val, $expected) {
  if($val==$expected)
    echo " checked";
}

/**
* Adds the metabox certification into the badge custom post type.
*
* @author Nicolas TORION
* @since 0.5
*/
add_action('add_meta_boxes','add_meta_box_certification');
function add_meta_box_certification(){
  add_meta_box('id_meta_box_certification', 'Certification Type', 'meta_box_certification', 'badge', 'side', 'high');
}

/**
* ...
*
* @author Nicolas TORION
* @since 0.5
* @param $post 
*/
function meta_box_certification($post){
  $val = get_post_meta($post->ID,'_certification',true);

  echo '<input type="radio" value="not_certified" name="certification_input"';
  check($val, 'not_certified');
  printf(__('> Not certified<br>','badges-issuer-for-wp'));

  echo '<input type="radio" value="certified" name="certification_input"';
  check($val, 'certified');
  printf(__('> Certified<br>','badges-issuer-for-wp'));

}

/**
* Adds the metabox type into the badge custom post type.
*
* @author Nicolas TORION
* @since 0.5
*/
add_action('add_meta_boxes','add_meta_box_type');
function add_meta_box_type(){
  add_meta_box('id_meta_box_type', 'Target Type', 'meta_box_type', 'badge', 'side', 'high');
}

/**
* ...
*
* @author Nicolas TORION
* @since 0.5
*/
function meta_box_type($post){
  $val = get_post_meta($post->ID,'_type',true);

  echo '<input type="radio" value="student" name="type_input"';
  check($val, 'student');
  printf(__('> Student<br>','badges-issuer-for-wp'));

  echo '<input type="radio" value="teacher" name="type_input"';
  check($val, 'teacher');
  printf(__('> Teacher<br>','badges-issuer-for-wp'));

}

/**
* Adds the metabox links into the badge custom post type.
*
* @author Nicolas TORION
* @since 0.5
*/
add_action('add_meta_boxes','add_meta_box_links');
function add_meta_box_links(){
  add_meta_box('id_meta_box_links', 'Badge Criteria', 'meta_box_links', 'badge', 'normal', 'high');
}

/**
 * Display add link
 *
 * @author Nicolas TORION
 * @since 0.6.1
*/
function display_add_link(){
  echo '<tr>';
  echo '<td width="0%">';
  display_languages_select_form($category="most-important-languages", $language_selected="", $multiple=true);
  echo '</td>';
  echo '<td width="100%">';
  echo '<center><input type="text" size="50" name="link_url[]" value="" /></center>';
  echo '</td>';
  echo '<td width="0%">';
  echo '<a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#">Remove</a>';
  echo '</td>';
  echo '</tr>';
}

/**
 * Meta box links
 *
 * @author Nicolas TORION
 * @since 0.6.1
*/
function meta_box_links($post) {
  if(get_post_meta($post->ID, '_badge_links', true))
    $badge_links = get_post_meta($post->ID, '_badge_links', true);
  else
    $badge_links = array();
  ?>
  <script type="text/javascript">
	jQuery(document).ready(function( $ ){
		jQuery( '#add-row' ).on('click', function() {
      var content = '<?php display_add_link(); ?>';
			jQuery("#box_links tbody").append(content);
			return false;
		});

    $.fn.RemoveTr = function() {
      jQuery(this).parent('td').parent('tr').remove();
    };

		return false;
	});
	</script>

  <table id="box_links" width="100%">
    <thead>
      <tr>
        <th width="0%">Language</th>
        <th width="100%">URL</th>
        <th width="0%"></th>
      </tr>
    </thead>
    <tbody>
  <?php

  foreach ($badge_links as $link_lang => $link_url) {
    echo '<tr>';
      echo '<td width="0%">';
        display_languages_select_form($category="most-important-languages", $language_selected=$link_lang, $multiple=true);
      echo '</td>';
      echo '<td width="100%">';
        echo '<center><input type="text" size="50" name="link_url[]" value="'.$link_url.'" /></center>';
      echo '</td>';
      echo '<td width="0%">';
      echo '<a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#">Remove</a>';
      echo '</td>';
    echo '</tr>';
  }
  echo '</tbody></table>';
  echo '<p><a id="add-row" class="button" href="#">Add another</a></p>';
}

/**
* Saves the metaboxes.
*
* @author Nicolas TORION
* @since 0.4.1
* @param $comment_id 
*/
add_action('save_post','save_metaboxes');
function save_metaboxes($post_ID){
  if(isset($_POST['certification_input'])){
    update_post_meta($post_ID,'_certification', esc_html($_POST['certification_input']));
  }
  if(isset($_POST['type_input'])){
    update_post_meta($post_ID,'_type', esc_html($_POST['type_input']));
  }
  if(isset($_POST['language']) && isset($_POST['link_url'])){
    $count = count( $_POST['language'] );
    $new = array();
    for ( $i = 0; $i < $count; $i++ ) {
      $new[$_POST['language'][$i]] = $_POST['link_url'][$i];
    }
  	update_post_meta( $post_ID, '_badge_links', $new );
  }
}

/**
 * Creates languages taxonomy.
 *
 * @author Nicolas TORION
 * @since 0.6.1
*/
add_action( 'init', 'create_field_of_education_tax' );
function create_field_of_education_tax() {
  register_taxonomy(
    'field_of_education',
    'badge',
    array(
      'label' => __( 'Field of education' ),
      'rewrite' => array( 'slug' => 'field_of_education' ),
      'hierarchical' => true,
    )
  );
}

/**
* Adds the taxonomy level into the badge custom post type.
*
* @author Nicolas TORION
* @since 0.5
* @param $comment_id 
*/
add_action( 'init', 'add_badge_levels_tax' );
function add_badge_levels_tax() {
	register_taxonomy(
		'level',
		'badge',
		array(
			'label' => __( 'Level' ),
			'rewrite' => array( 'slug' => 'level' ),
			'hierarchical' => true,
		)
	);
}

/**
* Load the custom template for a single badge.
*
* @author Nicolas TORION
* @since 0.5
* @param $template_path The path of the template.
* @return $template_path The path of the template.
*/
add_filter( 'template_include', 'badge_template', 1 );
function badge_template( $template_path ) {
  if ( get_post_type() == 'badge' ) {
    if ( is_single() ) {
      if ( $theme_file = locate_template( array ( 'badge_template.php' ) ) ) {
        $template_path = $theme_file;
      } else {
       $template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/badge_template.php';
      }
    }
  }
  return $template_path;
}

/**
* ...
*
* @author Nicolas TORION
* @since 0.5
* @param $comment_id 
*/
add_action( 'comment_post', 'save_comment_meta_data' );
function save_comment_meta_data( $comment_id ) {
  echo "<script>console.log('save comment');</script>";
  if (( isset( $_POST['language'] )) && ($_POST['language'] != ''))
    add_comment_meta( $comment_id, '_comment_translation_language', $_POST['language'] );
}

/**
* ...
*
* @author Nicolas TORION
* @since 0.6
* @param $commentdata 
* @return $commentdata
*/
add_filter( 'preprocess_comment', 'verify_comment_meta_data' );
function verify_comment_meta_data( $commentdata ) {
  if (isset($_POST['badge_translation_comment']) && !isset($_POST['language']))
    wp_die( __( 'Error: You did not add a language. Hit the Back button on your Web browser and resubmit your comment with a language.' ) );
  return $commentdata;
}

?>

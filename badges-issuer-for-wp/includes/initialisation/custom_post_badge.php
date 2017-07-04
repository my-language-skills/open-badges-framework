<?php

/**
 * This file creates a custom post type badge.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/initialisation
 * @since 1.0.0
*/

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

/* Register the custom post type. */

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
            'menu_icon' => plugins_url('../../images/icon.png', __FILE__),
            'has_archive' => true
        )
    );
}

function check($val, $expected) {
  if($val==$expected)
    echo " checked";
}

/* Adds the metabox certification into the badge custom post type */

add_action('add_meta_boxes','add_meta_box_certification');

function add_meta_box_certification(){
  add_meta_box('id_meta_box_certification', 'Certification', 'meta_box_certification', 'badge', 'side', 'high');
}

function meta_box_certification($post){
  $val = get_post_meta($post->ID,'_certification',true);

  echo '<input type="radio" value="not_certified" name="certification_input"';
  check($val, 'not_certified');
  echo '> Not certified<br>';

  echo '<input type="radio" value="certified" name="certification_input"';
  check($val, 'certified');
  echo '> Certified<br>';

}

/* Adds the metabox type into the badge custom post type */

add_action('add_meta_boxes','add_meta_box_type');

function add_meta_box_type(){
  add_meta_box('id_meta_box_type', 'Type', 'meta_box_type', 'badge', 'side', 'high');
}

function meta_box_type($post){
  $val = get_post_meta($post->ID,'_type',true);

  echo '<input type="radio" value="student" name="type_input"';
  check($val, 'student');
  echo '> Student<br>';

  echo '<input type="radio" value="teacher" name="type_input"';
  check($val, 'teacher');
  echo '> Teacher<br>';

}

/* Adds the metabox level into the badge custom post type */

add_action('add_meta_boxes','add_meta_box_level');

function add_meta_box_level(){
  add_meta_box('id_meta_box_level', 'Level', 'meta_box_level', 'badge', 'side', 'high');
}

function meta_box_level($post){
  $val = get_post_meta($post->ID,'_level',true);

  echo '<input type="radio" value="A1" name="level_input"';
  check($val, 'A1');
  echo '> A1<br>';
  echo '<input type="radio" value="A2" name="level_input"';
  check($val, 'A2');
  echo '> A2<br>';
  echo '<input type="radio" value="B1" name="level_input"';
  check($val, 'B1');
  echo '> B1<br>';
  echo '<input type="radio" value="B2" name="level_input"';
  check($val, 'B2');
  echo '> B2<br>';
  echo '<input type="radio" value="C1" name="level_input"';
  check($val, 'C1');
  echo '> C1<br>';
  echo '<input type="radio" value="C2" name="level_input"';
  check($val, 'C2');
  echo '> C2<br>';

  echo '<input type="radio" value="T1" name="level_input"';
  check($val, 'T1');
  echo '> T1<br>';
  echo '<input type="radio" value="T2" name="level_input"';
  check($val, 'T2');
  echo '> T2<br>';
  echo '<input type="radio" value="T3" name="level_input"';
  check($val, 'T3');
  echo '> T3<br>';
  echo '<input type="radio" value="T4" name="level_input"';
  check($val, 'T4');
  echo '> T4<br>';
  echo '<input type="radio" value="T5" name="level_input"';
  check($val, 'T5');
  echo '> T5<br>';
  echo '<input type="radio" value="T6" name="level_input"';
  check($val, 'T6');
  echo '> T6<br>';

}

/* Adds the metabox descriptions into the badge custom post type */

add_action('add_meta_boxes','add_meta_box_descriptions');

function add_meta_box_descriptions(){
  add_meta_box('id_meta_box_descriptions', 'Descriptions', 'meta_box_descriptions', 'badge', 'normal', 'high');
}

function meta_box_descriptions($post){
  $descriptions = get_badge_descriptions($post->post_title);

  foreach ($descriptions as $lang => $content) {
    echo $lang." :<br />";
    echo '<textarea name="description_'.$lang.'" rows="10" cols="100">'.$content.'</textarea><br />';
  }
}

/* Adds the metabox links into the badge custom post type */

add_action('add_meta_boxes','add_meta_box_links');

function add_meta_box_links(){
  add_meta_box('id_meta_box_links', 'Badge Information', 'meta_box_links', 'badge', 'normal', 'high');
}

function display_add_link(){
  echo '<tr>';
  echo '<td width="0%">';
  display_languages_select_form(true, "", true);
  echo '</td>';
  echo '<td width="100%">';
  echo '<center><input type="text" size="50" name="link_url[]" value="" /></center>';
  echo '</td>';
  echo '<td width="0%">';
  echo '<a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#">Remove</a>';
  echo '</td>';
  echo '</tr>';
}

function meta_box_links($post) {
  $badge_links = get_post_meta($post->ID, '_badge_links', true);
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
        display_languages_select_form(true, $link_lang, true);
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

/* Saves the metaboxes */

add_action('save_post','save_metaboxes');

function save_metaboxes($post_ID){
  if(isset($_POST['level_input'])){
    update_post_meta($post_ID,'_level', esc_html($_POST['level_input']));
  }
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

?>

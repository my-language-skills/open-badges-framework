<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

add_action('init', 'register_class');

function register_class()
{
    register_post_type('class',
        array(
            'labels' => array(
                'name' => 'Class School',
                'singular_name' => 'Class',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Class',
                'edit' => 'Edit',
                'edit_item' => 'Edit Class',
                'new_item' => 'New Class',
                'view' => 'View',
                'view_item' => 'View Class',
                'search_items' => 'Search Classes',
                'not_found' => 'No Classes found',
                'not_found_in_trash' => 'No Classes found in Trash',
                'parent' => 'Parent Classes'
            ),

            'public' => true,
            'menu_position' => 15,
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array(''),
            'menu_icon' => plugins_url('../../images/icon.png', __FILE__),
            'has_archive' => true,
            'capabilities' => array(
              'edit_post' => 'edit_class',
              'edit_posts' => 'edit_classes',
              'edit_others_posts' => 'edit_other_classes',
              'edit_published_posts' => 'edit_published_classes',
              'publish_posts' => 'publish_classes',
              'read_post' => 'read_class',
              'read_posts' => 'read_classes',
              'read_private_posts' => 'read_private_classes',
              'delete_post' => 'delete_class'
          )
        )
    );
}

//METABOX STUDENTS

add_action('add_meta_boxes','add_meta_box_class_students');

function add_meta_box_class_students(){
  add_meta_box('id_meta_box_class_students', 'Class students', 'meta_box_class_students', 'class', 'normal', 'high');
}

function display_add_student(){
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

function meta_box_class_students($post) {
  $class_students = get_post_meta($post->ID, '_class_students', true);
  ?>
  <script type="text/javascript">
	jQuery(document).ready(function( $ ){
		jQuery( '#add-row' ).on('click', function() {
      var content = '<?php display_add_link(); ?>';
			jQuery("#box_students tbody").append(content);
			return false;
		});

    $.fn.RemoveTr = function() {
      jQuery(this).parent('td').parent('tr').remove();
    };

		return false;
	});
	</script>

  <table id="box_students" width="100%">
    <thead>
      <tr>
        <th width="0%">Student's login</th>
        <th width="0%">Level</th>
        <th width="0%">Language</th>
        <th width="0%">Remove</th>
      </tr>
    </thead>
    <tbody>
  <?php

  foreach ($class_students as $student) {
    echo '<tr>';
      echo '<td width="0%">';
        echo '<center>'.$student['login'].'</center>';
      echo '</td>';
      echo '<td width="0%">';
        echo '<center>'.$student['level'].'</center>';
      echo '</td>';
      echo '<td width="0%">';
        echo '<center>'.$student['language'].'</center>';
      echo '</td>';
      echo '<td width="0%">';
      echo '<a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#">Remove</a>';
      echo '</td>';
    echo '</tr>';
  }
  echo '</tbody></table>';
}

add_action('add_meta_boxes','add_meta_box_class_level');

function add_meta_box_class_level(){
  global $current_user;
  get_currentuserinfo();

  if($current_user->roles[0]=="academy")
    add_meta_box('id_meta_box_class_level', 'Class level', 'meta_box_class_level', 'class', 'side', 'high');
}

function meta_box_class_level($post){
  $val = get_post_meta($post->ID,'_class_level',true);

  echo '<input type="radio" value="A1" name="class_level_input"';
  check($val, 'A1');
  echo '> A1<br>';
  echo '<input type="radio" value="A2" name="class_level_input"';
  check($val, 'A2');
  echo '> A2<br>';
  echo '<input type="radio" value="B1" name="class_level_input"';
  check($val, 'B1');
  echo '> B1<br>';
  echo '<input type="radio" value="B2" name="class_level_input"';
  check($val, 'B2');
  echo '> B2<br>';
  echo '<input type="radio" value="C1" name="class_level_input"';
  check($val, 'C1');
  echo '> C1<br>';
  echo '<input type="radio" value="C2" name="class_level_input"';
  check($val, 'C2');
  echo '> C2<br>';
}

//METABOX LANGUAGE

add_action('add_meta_boxes','add_meta_box_class_language');

function add_meta_box_class_language(){
  global $current_user;

  if($current_user->roles[0]=="academy")
    add_meta_box('id_meta_box_class_language', 'Class language', 'meta_box_class_language', 'class', 'side', 'high');
}

function meta_box_class_language($post){
  $val = get_post_meta($post->ID,'_class_language',true);
  display_languages_select_form($language_selected=$val);
}

add_action('save_post','save_metaboxes_class');

function save_metaboxes_class($post_ID){
  if(isset($_POST['class_level_input'])){
    update_post_meta($post_ID,'_class_level', esc_html($_POST['class_level_input']));
  }
  if(isset($_POST['language'])){
    update_post_meta($post_ID,'_class_language', esc_html($_POST['language']));
  }
}

?>

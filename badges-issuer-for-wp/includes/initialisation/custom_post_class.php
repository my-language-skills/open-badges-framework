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

add_action('add_meta_boxes', 'add_meta_boxes_class_zero');

function add_meta_boxes_class_zero() {
  $current_user = wp_get_current_user();

  add_meta_box( 'id_meta_box_class_zero_students', 'Class Students', 'meta_box_class_zero_students', 'class', 'normal', 'high' );

  if($current_user->roles[0]=="academy")
    add_meta_box('id_meta_box_class_zero_language', 'Class language', 'meta_box_class_zero_language', 'class', 'side', 'high');

  if($current_user->roles[0]=="academy")
    add_meta_box('id_meta_box_class_zero_level', 'Class level', 'meta_box_class_zero_level', 'class', 'side', 'high');

  add_action('save_post', 'save_metaboxes_class_zero');
}

/* Adds the metabox students of the class.*/

function meta_box_class_zero_students($post) {
  $class_students = get_post_meta($post->ID, '_class_students', true);

  $current_user = wp_get_current_user();
  ?>
  <script type="text/javascript">
  jQuery(document).ready(function( $ ){
    jQuery.fn.RemoveTr = function() {
      jQuery(this).parent('center').parent('td').parent('tr').remove();
    };
    jQuery("#publish").on("click", function() {
      save_metabox_students();
    });
    jQuery("#add_student_job_listing").on("click", function() {
      var input_login = jQuery("#add_student_login").val();
      var input_level = jQuery("#add_student_level").val();
      var input_language = jQuery("#add_student_language").val();

      jQuery("#box_students tbody").append(
        '<tr><td width="0%"><center>' +
        input_login
         + '</center></td><td width="0%"><center>'+
        input_level
         +'</center></td><td width="0%"><center>'+
        input_language
         +'</center></td><td width="0%"><center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center></td></tr>'
      );
      jQuery("#add_student_login").val('');
      jQuery("#add_student_level").val('');
      jQuery("#add_student_language").val('');
    });
    return false;
  });
  </script>

  <table id="box_students" name="<?php echo $post->ID; ?>" width="100%">
    <thead>
      <tr>
        <th width="0%">Student's login</th>
        <th width="0%">Level</th>
        <th width="0%">Language</th>
        <?php
        if($current_user->roles[0]=='administrator') {
          ?>
          <th width="0%">Action</th>
          <?php
        }
         ?>
      </tr>
    </thead>
    <tbody>
  <?php
  $i = 0;
  foreach ($class_students as $student) {
    echo '<tr>';
      echo '<td width="0%">';
      echo '<center>'.$student["login"].'</center>';
      echo '</td>';
      echo '<td width="0%">';
        echo '<center>'.$student["level"].'</center>';
      echo '</td>';
      echo '<td width="0%">';
        printf(__('<center>%s</center>','badges-issuer-for-wp'),$student["language"]);
      echo '</td>';
      if($current_user->roles[0]=='administrator') {
        echo '<td width="0%">';
        printf(__('<center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center>','badges-issuer-for-wp'));
        echo '</td>';
      }
    echo '</tr>';
    $i++;
  }

  echo '</tbody>';

  if($current_user->roles[0]=='administrator') {

  echo '<tfoot>';
  echo '<tr>';
  echo '<td width="0%">';
  echo '<center>';
  echo '<input type="text" id="add_student_login"/>';
  echo '</center>';
  echo '</td>';
  echo '<td width="0%">';
  echo '<center>';
  echo '<input type="text" id="add_student_level"/>';
  echo '</center>';
  echo '</td>';
  echo '<td width="0%">';
  echo '<center>';
  echo '<input type="text" id="add_student_language"/>';
  echo '</center>';
  echo '</td>';
  echo '<td width="0%">';
  echo '<center>';
  echo '<a class="button" href="#" id="add_student_job_listing">Add student</a>';
  echo '</center>';
  echo '</td>';
  echo '</tr>';
  echo '</tfoot>';
  }

  echo '</table>';
}

/* Adds the metabox level of the class.*/

function meta_box_class_zero_level($post){
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

function meta_box_class_zero_language($post){
  $val = get_post_meta($post->ID,'_class_language',true);
  display_languages_select_form($language_selected=$val);
}

/* Saves the job listing metaboxes.*/
function save_metaboxes_class_zero($post_ID){
  if(isset($_POST['class_level_input'])){
    update_post_meta($post_ID,'_class_level', esc_html($_POST['class_level_input']));
  }
  if(isset($_POST['language'])){
    update_post_meta($post_ID,'_class_language', esc_html($_POST['language']));
  }
}

/*Creates languages taxonomy*/

add_action( 'init', 'create_field_of_education_tax' );

function create_field_of_education_tax() {
	register_taxonomy(
		'field_of_education',
		'class',
		array(
			'label' => __( 'Field of education' ),
			'rewrite' => array( 'slug' => 'field_of_education' ),
			'hierarchical' => true,
		)
	);
}

?>

<?php

/**
 * This file modifies the Job Listing post type of WP Job Manager in order to use it for Badge School plugin.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/initialisation
 * @since 1.0.0
*/

wp_enqueue_script("jquery");
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

add_action('init', 'load_job_listing_class_metaboxes');

function load_job_listing_class_metaboxes() {

  if ( ! class_exists( 'WP_Job_Manager_Writepanels' ) ) {
    require_once( JOB_MANAGER_PLUGIN_DIR . '/includes/admin/class-wp-job-manager-writepanels.php' );
  }

  class WP_Job_Manager_Class_Writepanels extends WP_Job_Manager_Writepanels {

    public function __construct() {
      add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
      add_filter( 'template_include', array($this, 'job_listing_template'), 1 );
    }

    /* Adds the metaboxes.*/

    public function add_meta_boxes() {
      global $current_user;
      get_currentuserinfo();

      add_meta_box( 'id_meta_box_class_students', 'Class Students', array( $this, 'meta_box_class_students' ), 'job_listing', 'normal', 'high' );

      if($current_user->roles[0]=="academy")
        add_meta_box('id_meta_box_class_language', 'Class language', array($this, 'meta_box_class_language'), 'job_listing', 'side', 'high');

      if($current_user->roles[0]=="academy")
        add_meta_box('id_meta_box_class_level', 'Class level', array($this, 'meta_box_class_level'), 'job_listing', 'side', 'high');

      add_action('save_post', array($this, 'save_metaboxes_class'));
    }

    /* Adds the metabox students of the class.*/

    function meta_box_class_students($post) {
      $class_students = get_post_meta($post->ID, '_class_students', true);
      global $current_user;
      get_currentuserinfo();
      ?>
      <script type="text/javascript">
    	jQuery(document).ready(function( $ ){
        jQuery.fn.RemoveTr = function() {
          jQuery(this).parent('center').parent('td').parent('tr').remove();
        };
        jQuery("#publish").on("click", function() {
          save_metabox_students();
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
              <th width="0%">Remove</th>
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
            echo '<center>'.$student['login'].'</center>';
          echo '</td>';
          echo '<td width="0%">';
            echo '<center>'.$student['level'].'</center>';
          echo '</td>';
          echo '<td width="0%">';
            echo '<center>'.$student['language'].'</center>';
          echo '</td>';
          if($current_user->roles[0]=='administrator') {
            echo '<td width="0%">';
            echo '<center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center>';
            echo '</td>';
          }
        echo '</tr>';
        $i++;
      }
      echo '</tbody></table>';
    }

    /* Adds the meatbox level of the class.*/

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

    function meta_box_class_language($post){
      $val = get_post_meta($post->ID,'_class_language',true);
      display_languages_select_form($language_selected=$val);
    }

    /* Saves the job listing metaboxes.*/
    function save_metaboxes_class($post_ID){
      if(isset($_POST['class_level_input'])){
        update_post_meta($post_ID,'_class_level', esc_html($_POST['class_level_input']));
      }
      if(isset($_POST['language'])){
        update_post_meta($post_ID,'_class_language', esc_html($_POST['language']));
      }
    }

    /**
     * Load the custom template for a single job Listing.
     *
     * @author Nicolas TORION
     * @since 1.0.0
     * @param $template_path The path of the template.
     * @return $template_path The path of the template.
    */
    function job_listing_template( $template_path ) {
        if ( get_post_type() == 'job_listing' ) {
            if ( is_single() ) {
                if ( $theme_file = locate_template( array ( 'job_listing_template.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/job_listing_template.php';
                }
            }
        }
        return $template_path;
    }

  }

  $writepanel = new WP_Job_Manager_Class_Writepanels;

}
?>

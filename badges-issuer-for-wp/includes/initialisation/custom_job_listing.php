<?php

/**
 * This file modifies the Job Listing post type of WP Job Manager in order to use it for Badge School plugin.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/initialisation
 * @since 0.2
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
    }

    /* Adds the metabox students of the class.*/

    function meta_box_class_students($post) {
      if(get_post_meta($post->ID, '_class_students', true))
        $class_students = get_post_meta($post->ID, '_class_students', true);
      else
        $class_students = array();

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
        jQuery("#add_student_job_listing").on("click", function() {
          var input_login = jQuery("#add_student_login").val();
          var input_mail = jQuery("#add_student_mail").val();
          var input_level = jQuery("#add_student_level").val();
          var input_language = jQuery("#add_student_language").val();
          var dateObj = new Date();
          var month = dateObj.getUTCMonth() + 1; //months from 1-12
          var day = dateObj.getUTCDate();
          var year = dateObj.getUTCFullYear();

          newdate = year + "-" + month + "-" + day;

          jQuery("#box_students tbody").append(
            '<tr><td width="0%"><center>' +
            input_login
             +'</center></td><td width="0%"><center>'+
            input_mail
             +'</center></td><td width="0%"><center>'+
            input_level
             +'</center></td><td width="0%"><center>'+
            input_language
             +'</center></td><td width="0%"><center>'+
            newdate
             +'</center></td><td width="0%"><center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center></td></tr>'
          );
          jQuery("#add_student_login").val('');
          jQuery("#add_student_mail").val('');
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
            <th width="0%">Student's email</th>
            <th width="0%">Level</th>
            <th width="0%">Language</th>
            <th width="0%">Date</th>
            <?php
            if(in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
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
          echo '<center>'.$student["mail"].'</center>';
          echo '</td>';
          echo '<td width="0%">';
            echo '<center>'.$student["level"].'</center>';
          echo '</td>';
          echo '<td width="0%">';
            printf(__('<center>%s</center>','badges-issuer-for-wp'),$student["language"]);
          echo '</td>';
          echo '<td width="0%">';
            echo '<center>'.$student["date"].'</center>';
          echo '</td>';
          if(in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {
            echo '<td width="0%">';
            printf(__('<center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center>','badges-issuer-for-wp'));
            echo '</td>';
          }
        echo '</tr>';
        $i++;
      }

      echo '</tbody>';

      if(in_array("administrator", $current_user->roles) || in_array("editor", $current_user->roles)) {

        echo '<tfoot>';
        echo '<tr>';
        echo '<td width="0%">';
        echo '<center>';
        echo '<input type="text" id="add_student_login"/>';
        echo '</center>';
        echo '</td>';
        echo '<td width="0%">';
        echo '<center>';
        echo '<input type="text" id="add_student_mail"/>';
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

    /**
     * Load the custom template for a single job Listing.
     *
     * @author Nicolas TORION
     * @since 0.2
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

  $writepanel = new WP_Job_Manager_Class_Writepanels();

}
?>

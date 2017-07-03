<?php
/**
 * Add and publish a class post associated to a teacher.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $teacher_name Name of the teacher
*/
function add_teacher_class_post($teacher_name) {
  // Create post object
  $class_school_post = array(
    'post_title'    => $teacher_name,
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_type'     => 'job_listing'
  );
  // Insert the post into the database
  wp_insert_post($class_school_post);
}
?>

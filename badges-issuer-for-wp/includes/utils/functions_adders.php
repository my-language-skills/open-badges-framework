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
    'post_type'     => 'class'
  );
  // Insert the post into the database
  wp_insert_post($class_school_post);
}

/**
 * Add a student to a class.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $student_mail The mail of the student to indentify him.
 * @param $level The level obtained by the student.
 * @param $language The language for which the student has obtained a badge.
 * @param $class_id The ID of the class post where the student is added.
*/
function add_student_to_class($student_mail, $level, $language, $class_id) {
  $student = get_user_by_email($student_mail);
  if($student) {
    if(!is_null($class_id)) {
      $student_infos = array(
        'login' => $student->user_login,
        'level' => $level,
        'language' => $language
      );
      $class_students = get_post_meta($class_id, '_class_students', true);
      $class_students[] = $student_infos;
      update_post_meta($class_id,'_class_students', $class_students);
    }
  }
}

/**
 * Add a badge to the user profile.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $mail The mail of the user.
 * @param $badge_name The name of the badge.
 * @param $badge_language The language of the badge.
 * @param $sender The person who sent the badge.
 * @param $comment The comment given by the sender.
*/
function add_badge_to_user_profile($mail, $badge_name, $badge_language, $sender, $comment) {

  $user_informations = get_user_by_email($mail);
  $badges = get_the_author_meta( 'user_badges', $user_informations->ID );

  if(empty($badges))
    $bagdes=array();

  $badges[] = array(
    'name' => $badge_name,
    'language' => $badge_language,
    'sender' => $sender,
    'comment' => $comment
  );

  update_user_meta( $user_informations->ID, 'user_badges', $badges);
}
?>

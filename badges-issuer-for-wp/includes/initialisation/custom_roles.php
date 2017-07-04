<?php

/**
 * This file allow to create roles and capabilities.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/initialisation
 * @since 1.0.0
*/


require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

/*
Create available roles for the users of the website.
*/

$result = add_role( 'student', 'Student', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false
));

$result = add_role( 'teacher', 'Teacher', array(
    'read' => true,
    'edit_posts' => true,
    'delete_posts' => false
));

$result = add_role( 'academy', 'Academy', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false
));

/*
Add capabilities to the existing roles.
*/

function add_capabilities() {
    // STUDENT ROLE
    $student = get_role('student');
    $student->add_cap('send_student_badge');

    // TEACHER ROLE
    $teacher = get_role('teacher');
    $teacher->add_cap('send_student_badge');
    $teacher->add_cap( 'read' );
    $teacher->add_cap( 'read_class');
    $teacher->add_cap( 'read_classes' );
    $teacher->add_cap( 'edit_published_classes' );
    $teacher->add_cap('capability_send_badge');
    $teacher->add_cap("read_job_listing");

    // ACADEMY ROLE
    $academy = get_role('academy');
    $academy->add_cap('send_student_badge');
    $academy->add_cap('send_teacher_badge');
    $academy->add_cap('capability_send_badge');
    $academy->add_cap("read_job_listing");
    $academy->add_cap("publish_job_listings");
    $academy->add_cap("edit_published_job_listings");

    // ADMINISTRATOR ROLE
    $administrator = get_role('administrator');
    $administrator->add_cap('edit_class');
    $administrator->add_cap('edit_classes');
    $administrator->add_cap('edit_other_classes');
    $administrator->add_cap('edit_published_classes');
    $administrator->add_cap('publish_classes');
    $administrator->add_cap('read_class');
    $administrator->add_cap('read_classes');
    $administrator->add_cap('read_private_classes');
    $administrator->add_cap('delete_class');
    $administrator->add_cap('send_student_badge');
    $administrator->add_cap('send_teacher_badge');
    $administrator->add_cap('capability_send_badge');
}

add_action( 'init', 'add_capabilities');

/*
Create a class for the teacher when he loggin for the first time.
*/

function create_teacher_class() {
  $current_user = wp_get_current_user();
  if($current_user->roles[0]=='teacher') {
    $name = $current_user->user_login;

    if(!class_school_exists($name))
      add_teacher_class_post($name);
  }
}

add_action('init', 'create_teacher_class');

?>

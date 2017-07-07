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
    $teacher->add_cap('capability_send_badge');
    $teacher->add_cap('job_listing');
    $teacher->add_cap("edit_private_job_listings");
    $teacher->add_cap("edit_published_job_listings");

    // ACADEMY ROLE
    $academy = get_role('academy');
    $academy->add_cap('send_student_badge');
    $academy->add_cap('send_teacher_badge');
    $academy->add_cap('capability_send_badge');
    $academy->add_cap("read_published_job_listings");
    $academy->add_cap("publish_job_listings");
    $academy->add_cap("delete_published_job_listings");
    $academy->add_cap("edit_published_job_listings");

    // ADMINISTRATOR ROLE
    $administrator = get_role('administrator');
    $administrator->add_cap('send_student_badge');
    $administrator->add_cap('send_teacher_badge');
    $administrator->add_cap('capability_send_badge');
    $administrator->add_cap('capability_settings');
    $administrator->add_cap('job_listing');
    $administrator->add_cap("edit_job_listing");
    $administrator->add_cap("read_job_listing");
    $administrator->add_cap("delete_job_listing");
    $administrator->add_cap("edit_job_listings");
    $administrator->add_cap("edit_others_job_listings");
    $administrator->add_cap("publish_job_listings");
    $administrator->add_cap("read_private_job_listings");
    $administrator->add_cap("delete_job_listings");
    $administrator->add_cap("delete_private_job_listings");
    $administrator->add_cap("delete_published_job_listings");
    $administrator->add_cap("delete_others_job_listings");
    $administrator->add_cap("edit_private_job_listings");
    $administrator->add_cap("edit_published_job_listings");
    $administrator->add_cap("manage_job_listing_terms");
    $administrator->add_cap("edit_job_listing_terms");
    $administrator->add_cap("delete_job_listing_terms");
    $administrator->add_cap("assign_job_listing_terms");
}

add_action( 'admin_init', 'add_capabilities');

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
/*
Add a filter for checking if the user can only see these own job listings (classes)
*/
function posts_for_current_author($query) {
	global $pagenow;

	if( 'edit.php' != $pagenow || !$query->is_admin )
	    return $query;

	if( !current_user_can( 'edit_others_posts' ) && $query->get('post_type')=="job_list") {
		global $user_ID;
		$query->set('author', $user_ID );
	}
	return $query;
}
add_filter('pre_get_posts', 'posts_for_current_author');

?>

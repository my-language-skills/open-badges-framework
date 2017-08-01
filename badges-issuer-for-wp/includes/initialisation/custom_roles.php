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

$result2 = add_role( 'teacher', 'Teacher', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false
));

$result3 = add_role( 'academy', 'Academy', array(
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

    // TEACHER ROLE
    $teacher = get_role('teacher');
    $teacher->add_cap('edit_class', false);
    $teacher->add_cap('edit_classes', false);
    $teacher->add_cap('edit_other_classes', false);
    $teacher->add_cap('edit_published_classes', false);
    $teacher->add_cap('publish_classes', false);
    $teacher->add_cap('read_class', false);
    $teacher->add_cap('read_classes', false);
    $teacher->add_cap('read_private_classes', false);
    $teacher->add_cap('delete_class', false);

    $teacher->add_cap('manage_job_listings', false);
    $teacher->add_cap('job_listing', false);
    $teacher->add_cap('edit_job_listing', false);
    $teacher->add_cap('read_job_listing', false);
    $teacher->add_cap('delete_job_listing', false);
    $teacher->add_cap('edit_job_listings', false);
    $teacher->add_cap('publish_job_listings', false);
    $teacher->add_cap('read_private_job_listings', false);
    $teacher->add_cap('delete_job_listings', false);
    $teacher->add_cap('delete_published_job_listings', false);
    $teacher->add_cap('delete_others_job_listings', false);
    $teacher->add_cap('edit_private_job_listings', false);
    $teacher->add_cap('edit_published_job_listings', false);
    $teacher->add_cap('manage_job_listing_terms', false);
    $teacher->add_cap('edit_job_listing_terms', false);
    $teacher->add_cap('delete_job_listing_terms', false);
    $teacher->add_cap('assign_job_listing_terms', false);

    // ACADEMY ROLE
    $academy = get_role('academy');

    $academy->add_cap('edit_class', false);
    $academy->add_cap('edit_classes', false);
    $academy->add_cap('edit_other_classes', false);
    $academy->add_cap('edit_published_classes', false);
    $academy->add_cap('publish_classes', false);
    $academy->add_cap('read_class', false);
    $academy->add_cap('read_classes', false);
    $academy->add_cap('read_private_classes', false);
    $academy->add_cap('delete_class', false);

    $academy->add_cap('manage_job_listings', false);
    $academy->add_cap('job_listing', false);

    $teacher->add_cap('edit_job_listing', false);
    $teacher->add_cap('delete_job_listing', false);
    $teacher->add_cap('delete_job_listings', false);
    $teacher->add_cap('delete_others_job_listings', false);

    $academy->add_cap('manage_job_listing_terms', false);
    $academy->add_cap('edit_job_listing_terms', false);
    $academy->add_cap('delete_job_listing_terms', false);
    $academy->add_cap('assign_job_listing_terms', false);
}

add_action( 'init', 'add_capabilities');

/*
Create a class for the teacher when he loggin for the first time.
*/

function create_teacher_class_zero() {
  $current_user = wp_get_current_user();
  if($current_user->roles[0]=='teacher' || $current_user->roles[0]=='academy') {
    $name = $current_user->user_login;

    if(!class_school_exists($name))
      add_teacher_class_zero_post($name);
  }
}

add_action('init', 'create_teacher_class_zero');
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

<?php

/**
 * This file allow to create roles and capabilities.
 *
 * @author Nicolas TORION
 * @package badges-issuer-for-wp
 * @subpackage includes/initialisation
 * @since 0.3
*/


require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

/*
Add capabilities to the existing roles.
*/

function add_roles() {

    if(get_role( 'student' ))
        remove_role('student');

    if(get_role( 'teacher' ))
        remove_role('teacher');

    if(get_role( 'academy' ))
        remove_role('academy');

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

    // STUDENT ROLE
    $student = get_role('student');
    $student->add_cap('capability_send_badge');
    $student->add_cap('send_badge');

    // TEACHER ROLE
    $teacher = get_role('teacher');
    $teacher->add_cap('capability_send_badge');
    $teacher->add_cap('send_badge');

    // ACADEMY ROLE
    $academy = get_role('academy');
    $academy->add_cap('capability_send_badge');
    $academy->add_cap('send_badge');

    $academy->add_cap('job_listing');

    $academy->add_cap('manage_job_listings', false);
    $academy->add_cap('publish_job_listings');
    $academy->add_cap('read_private_job_listings');
    $academy->add_cap('delete_private_job_listings');
    $academy->add_cap('delete_published_job_listings');
    $academy->add_cap('edit_private_job_listings');
    $academy->add_cap('edit_published_job_listings');
    $academy->add_cap('edit_job_listing', false);
    $academy->add_cap('read_job_listing');
    $academy->add_cap('delete_job_listing', false);
    $academy->add_cap('edit_job_listings');
    $academy->add_cap('edit_others_job_listings', false);
    $academy->add_cap('delete_job_listings', false);
    $academy->add_cap('delete_others_job_listings', false);
    $academy->add_cap('manage_job_listing_terms', false);
    $academy->add_cap('edit_job_listing_terms', false);
    $academy->add_cap('delete_job_listing_terms', false);
    $academy->add_cap('assign_job_listing_terms', false);

    // ADMINISTRATOR ROLE
    $admin = get_role('administrator');
    $admin->add_cap('capability_send_badge');
    $admin->add_cap('send_badge');

    $admin->add_cap('job_listing');

    $admin->add_cap('edit_class');
    $admin->add_cap('edit_classes');
    $admin->add_cap('edit_other_classes');
    $admin->add_cap('edit_published_classes');
    $admin->add_cap('publish_classes');
    $admin->add_cap('read_class');
    $admin->add_cap('read_classes');
    $admin->add_cap('read_private_classes');
    $admin->add_cap('delete_class');

    $admin->add_cap('capability_settings');
    $admin->add_cap('capability_statistics');

    // EDITOR ROLE
    $editor = get_role('editor');
    $editor->add_cap('capability_send_badge');
    $editor->add_cap('send_badge');

    $editor->add_cap('job_listing');

    $editor->add_cap('edit_class');
    $editor->add_cap('edit_classes');
    $editor->add_cap('edit_other_classes');
    $editor->add_cap('edit_published_classes');
    $editor->add_cap('publish_classes');
    $editor->add_cap('read_class');
    $editor->add_cap('read_classes');
    $editor->add_cap('read_private_classes');
    $editor->add_cap('delete_class');

    $editor->add_cap('capability_settings');
    $editor->add_cap('capability_statistics');
}

add_action( 'init', 'add_roles');

/*
Create a class for the teacher when he loggin for the first time.
*/

function create_teacher_class_zero() {
  $current_user = wp_get_current_user();
  if(in_array("teacher", $current_user->roles) || in_array("academy", $current_user->roles)) {
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

<?php

$result = add_role( 'student', 'Student', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false
));

$result = add_role( 'teacher', 'Teacher', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false
));

$result = add_role( 'academy', 'Academy', array(
    'read' => true,
    'edit_posts' => false,
    'delete_posts' => false
));

function add_capabilities() {
    // STUDENT ROLE
    $student = get_role('student');
    $student->add_cap('send_student_badge');

    // TEACHER ROLE
    $teacher = get_role('teacher');
    $teacher->add_cap('send_student_badge');

    // ACADEMY ROLE
    $academy = get_role('academy');
    $academy->add_cap('send_student_badge');
    $academy->add_cap('send_teacher_badge');

}

add_action( 'admin_init', 'add_capabilities');

?>

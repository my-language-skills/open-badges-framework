<?php

// GETTERS FUNCTIONS

/**
 * Returns all badges that exist
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @return $badges Array of all badges.
*/
function get_all_badges() {
  $badges = get_posts(array(
    'post_type'   => 'badge',
    'numberposts' => -1
  ));
  return $badges;
}

/**
 * Returns all languages of description of badges given.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges Array of badges.
 * @return $descriptions_languages Array of badges names associated to the available languages of their description.
*/
function get_all_languages_description($badges) {
  $descriptions_languages = array();
  foreach ($badges as $badge) {
    foreach (array_keys(get_badge_descriptions(get_post_meta($badge->ID,"_level",true))) as $lang) {
      $descriptions_languages[$badge->post_title][] = $lang;
    }
  }
  return $descriptions_languages;
}

/**
 * Returns the description of a badge which is writed in the lines given.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badge_name The name of the badge.
 * @param $lines Lines given.
 * @return $description Content of the description of the badge.
*/
function get_badge_description($badge_level, $lines) {
  $description_begin = "==".$badge_level."==\n";
  $i=0;
  $description="";

  while($lines[$i]!=$description_begin && $i<sizeof($lines)) {
    $i++;
  }

  $i++;
  while($lines[$i]!="======\n" && $i<sizeof($lines)) {
    $description=$description.$lines[$i]."\n";
    $i++;
  }

  return $description;
}

/**
 * Returns all the descriptions of a badge.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badge_level The level of the badge.
 * @return $descriptions Array of descriptions of the badge associated to their language.
*/
function get_badge_descriptions($badge_level) {
  $descriptions_dir = plugin_dir_path( dirname( __FILE__ ) )."badges-descriptions/";
  $descriptions_files = scandir($descriptions_dir);
  $descriptions_files = array_diff($descriptions_files, array(".", "..") );
  $descriptions = array();

  foreach ($descriptions_files as $file) {
    $lines = file($descriptions_dir.$file);
    $lang = explode('.', $file)[0];
    $content = get_badge_description($badge_level, $lines);
    if(str_replace("\n", "", $content)!="")
      $descriptions[$lang] = $content;
  }

  return $descriptions;
}

/**
 * Returns the badge informations associated to level and language given.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badge_name The name of the badge.
 * @param $badges A list of badges.
 * @param $lang The language studied by the student.
 * @return Array of badge's informations (name, description, image url).
*/
function get_badge($badge_name, $badges, $lang) {
  foreach ($badges as $badge) {
    if($badge_name==$badge->post_name) {
      $badge_description = get_badge_descriptions(get_post_meta($badge->ID,"_level",true))[$lang];
      return array("name"=>$badge->post_title, "description"=>$badge_description, "image"=>get_the_post_thumbnail_url($badge->ID));
    }
  }
}

/**
 * Returns all levels that exist.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges A list of badges.
 * @param $level_type Type wanted of levels.
 * @return $levels Array of all levels found.
*/
function get_all_levels($badges, $only_student=false) {
  $levels = array();
  foreach($badges as $badge){
    $badge_type = get_post_meta($badge->ID,"_type",true);
    $level = get_post_meta($badge->ID,"_level",true);
    if( ! in_array( $level, $levels) ) {
      if($only_student) {
          if($badge_type=="student")
            $levels[] = $level;
      }
      else
        $levels[] = $level;
    }
  }
  sort($levels);
  return $levels;
}

/**
 * Returns all badges of a level.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $badges A list of badges.
 * @return $level The level of bagdes to find.
*/

function get_all_badges_level($badges, $level, $certification=false) {
  $badges_corresponding = array();
  foreach ($badges as $badge) {
    if(get_post_meta($badge->ID,"_level",true)==$level) {
      if(get_post_meta($badge->ID,'_certification',true)=="certified") {
        if($certification)
          $badges_corresponding[] = $badge;
      }
      else
        $badges_corresponding[] = $badge;
    }
  }
  return $badges_corresponding;
}

/**
 * Returns all the languages stocked in the languages files.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @return $all_languages All the languages found.
*/
function get_all_languages() {
  $mostimportantlanguages = array();
  $languages = array();

  $term_mil = get_term_by('slug', 'most-important-languages', 'job_listing_category');
  $id_mil = $term_mil->term_id;
  $term_ol = get_term_by('slug', 'other-languages', 'job_listing_category');
  $id_ol = $term_ol->term_id;

  $languages_mil = get_terms( array(
    'taxonomy' => 'job_listing_category',
    'hide_empty' => false,
    'child_of' => $id_mil
  ));

  foreach ($languages_mil as $language_mil) {
    $mostimportantlanguages[] = $language_mil->name;
  }

  $languages_ol = get_terms( array(
    'taxonomy' => 'job_listing_category',
    'hide_empty' => false,
    'child_of' => $id_ol
  ));

  foreach ($languages_ol as $language_ol) {
    $languages[] = $language_ol->name;
  }

  $all_languages = array($mostimportantlanguages, $languages);

  return $all_languages;
}

/**
 * Returns all classes that exist
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @return $classes Array of all classes.
*/
function get_all_classes() {
  $classes = get_posts(array(
    'post_type'   => 'job_listing',
    'numberposts' => -1
  ));
  return $classes;
}

/**
 * Returns all the classes of a teacher.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $teacher_login The login of the teacher.
 * @return $classes All the classes corresponding.
*/
function get_classes_teacher($teacher_login) {
  $all_classes = get_all_classes();
  $classes = array();
  foreach ($all_classes as $class) {
    if($class->post_title==$teacher_login)
      $classes[]=$class;
  }
  return $classes;
}

/**
 * Check if a class exists with the name of a teacher
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $teacher_name Name of the teacher
 * @return $exists Boolean indicating if the class exists or not.
*/
function class_school_exists($teacher_name) {
  $classes = get_all_classes();
  $exists = false;
  foreach ($classes as $class) {
    if($class->post_title==$teacher_name)
      $exists = true;
  }
  return $exists;
}

/**
 * Check if a student is in a class school.
 *
 * @author Nicolas TORION
 * @since 1.0.0
 * @param $student_login Login of the student.
 * @param $class_id The ID of the class (job_listing) post.
 * @return $result A boolean indicating if the student is in the class or not.
*/
function is_student_in_class($student_login, $class_id) {
  $class_students = get_post_meta($class_id,"_class_students",true);
  $result = false;
  foreach ($class_students as $class_student) {
    if($class_student['login']==$student_login)
      $result = true;
  }
  return $result;
}

?>

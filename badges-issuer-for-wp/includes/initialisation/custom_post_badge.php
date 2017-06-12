<?php

add_action('init', 'register_badge');

function register_badge()
{
    register_post_type('badge',
        array(
            'labels' => array(
                'name' => 'Badge School',
                'singular_name' => 'Badge',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Badge',
                'edit' => 'Edit',
                'edit_item' => 'Edit Badge',
                'new_item' => 'New Badge',
                'view' => 'View',
                'view_item' => 'View Badge',
                'search_items' => 'Search Badges',
                'not_found' => 'No Badges found',
                'not_found_in_trash' => 'No Badges found in Trash',
                'parent' => 'Parent Badges'
            ),

            'public' => true,
            'menu_position' => 15,
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array(''),
            'menu_icon' => plugins_url('../../images/icon.png', __FILE__),
            'has_archive' => true
        )
    );
}

//METABOX LEVEL

add_action('add_meta_boxes','add_meta_box_level');

function add_meta_box_level(){
  add_meta_box('id_meta_box_level', 'Level', 'meta_box_level', 'badge', 'side', 'high');
}

function meta_box_level($post){
  $val = get_post_meta($post->ID,'_level',true);

  echo '<input type="radio" value="A1" name="level_input"';
  checkLevel($val, 'A1');
  echo '> A1<br>';
  echo '<input type="radio" value="A2" name="level_input"';
  checkLevel($val, 'A2');
  echo '> A2<br>';
  echo '<input type="radio" value="B1" name="level_input"';
  checkLevel($val, 'B1');
  echo '> B1<br>';
  echo '<input type="radio" value="B2" name="level_input"';
  checkLevel($val, 'B2');
  echo '> B2<br>';
  echo '<input type="radio" value="C1" name="level_input"';
  checkLevel($val, 'C1');
  echo '> C1<br>';
  echo '<input type="radio" value="C2" name="level_input"';
  checkLevel($val, 'C2');
  echo '> C2<br>';

}

function checkLevel($val, $level) {
  if($val==$level)
    echo " checked";
}

add_action('save_post','save_metaboxes');
function save_metaboxes($post_ID){
  if(isset($_POST['level_input'])){
    update_post_meta($post_ID,'_level', esc_html($_POST['level_input']));
  }
}

?>

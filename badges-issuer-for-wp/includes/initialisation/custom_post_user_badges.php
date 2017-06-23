<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

add_action('init', 'register_user_badges');

function register_user_badges()
{
    register_post_type('user_badges',
        array(
            'labels' => array(
                'name' => 'User Badges',
                'singular_name' => 'User Badge',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New user Badge',
                'edit' => 'Edit',
                'edit_item' => 'Edit User Badge',
                'new_item' => 'New User Badge',
                'view' => 'View',
                'view_item' => 'View User Badge',
                'search_items' => 'Search User Badges',
                'not_found' => 'No User Badges found',
                'not_found_in_trash' => 'No User Badges found in Trash',
                'parent' => 'Parent User Badges'
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

//METABOX BADGES
add_action('add_meta_boxes','add_meta_box_badges');

function add_meta_box_badges(){
  add_meta_box('id_meta_box_badges', 'Badges', 'meta_box_badges', 'user_badges', 'normal', 'high');
}

function meta_box_badges($post) {
  $user_badges = get_post_meta($post->ID, '_badges', true);
  ?>

  <table width="100%">
    <thead>
      <tr>
        <th width="0%">Badge name</th>
        <th width="0%">Badge language</th>
        <th width="0%">Sender</th>
        <th width="0%">Comment</th>
      </tr>
    </thead>
    <tbody>
  <?php

  foreach ($user_badges as $user_badge) {
    echo '<tr>';
      echo '<td width="0%">';
      echo $user_badge['name'];
      echo '</td>';
      echo '<td width="0%">';
      echo $user_badge['language'];
      echo '</td>';
      echo '<td width="0%">';
      echo $user_badge['sender'];
      echo '</td>';
      echo '<td width="0%">';
      echo $user_badge['comment'];
      echo '</td>';
    echo '</tr>';
  }
  echo '</tbody></table>';
}

?>

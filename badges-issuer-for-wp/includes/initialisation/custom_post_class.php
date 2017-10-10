<?php
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/functions.php';

	add_action( 'init', 'register_class' );

	/**
	 * ...
	 *
	 * @author Nicolas TORION
	 * @since  0.4.1
	 */
	function register_class() {
		register_post_type( 'class', array(
				'labels'       => array(
					'name'               => 'Class School',
					'singular_name'      => 'Class',
					'add_new'            => 'Add New',
					'add_new_item'       => 'Add New Class',
					'edit'               => 'Edit',
					'edit_item'          => 'Edit Class',
					'new_item'           => 'New Class',
					'view'               => 'View',
					'view_item'          => 'View Class',
					'search_items'       => 'Search Classes',
					'not_found'          => 'No Classes found',
					'not_found_in_trash' => 'No Classes found in Trash',
					'parent'             => 'Parent Classes'
				),
				'public'       => true,
				'show_in_menu' => 'edit.php?post_type=badge',
				'supports'     => array(
					'title',
					'editor',
					'thumbnail',
					'comments'
				),
				'taxonomies'   => array( '' ),
				'has_archive'  => true,
				'capabilities' => array(
					'edit_post'            => 'edit_class',
					'edit_posts'           => 'edit_classes',
					'edit_others_posts'    => 'edit_other_classes',
					'edit_published_posts' => 'edit_published_classes',
					'publish_posts'        => 'publish_classes',
					'read_post'            => 'read_class',
					'read_posts'           => 'read_classes',
					'read_private_posts'   => 'read_private_classes',
					'delete_post'          => 'delete_class'
				)
			) );
	}

	add_action( 'add_meta_boxes', 'add_meta_boxes_class_zero' );

	/**
	 * ...
	 *
	 * @author Nicolas TORION
	 * @since  0.4.1
	 */
	function add_meta_boxes_class_zero() {
		$current_user = wp_get_current_user();

		add_meta_box( 'id_meta_box_class_zero_students', 'Class Students', 'meta_box_class_zero_students', 'class', 'normal', 'high' );
	}

	/**
	 * Adds the metabox students of the class.
	 *
	 * @author Nicolas TORION
	 * @since  0.6.2
	 *
	 * @param $post The post
	 *
	 * @return
	 */

	function meta_box_class_zero_students( $post ) {
	if ( get_post_meta( $post->ID, '_class_students', true ) ) {
		$class_students = get_post_meta( $post->ID, '_class_students', true );
	} else {
		$class_students = array();
	}

	$current_user = wp_get_current_user();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        jQuery.fn.RemoveTr = function () {
            jQuery(this).parent('center').parent('td').parent('tr').remove();
        };
        jQuery("#publish").on("click", function () {
            save_metabox_students();
        });
        jQuery("#add_student_job_listing").on("click", function () {
            var input_login = jQuery("#add_student_login").val();
            var input_mail = jQuery("#add_student_mail").val();
            var input_level = jQuery("#add_student_level").val();
            var input_language = jQuery("#add_student_language").val();
            var dateObj = new Date();
            var month = dateObj.getUTCMonth() + 1; //months from 1-12
            var day = dateObj.getUTCDate();
            var year = dateObj.getUTCFullYear();

            newdate = year + "-" + month + "-" + day;

            jQuery("#box_students tbody").append(
                '<tr><td width="0%"><center>' +
                input_login
                + '</center></td><td width="0%"><center>' +
                input_mail
                + '</center></td><td width="0%"><center>' +
                input_level
                + '</center></td><td width="0%"><center>' +
                input_language
                + '</center></td><td width="0%"><center>' +
                newdate
                + '</center></td><td width="0%"><center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center></td></tr>'
            );
            jQuery("#add_student_login").val('');
            jQuery("#add_student_mail").val('');
            jQuery("#add_student_level").val('');
            jQuery("#add_student_language").val('');
        });
        return false;
    });
</script>

<table id="box_students" name="<?php echo $post->ID; ?>" width="100%">
    <thead>
    <tr>
        <th width="0%"><?php _e( 'Student\'s login', 'badges-issuer-for-wp' ); ?></th>
        <th width="0%"><?php _e( 'Student\'s mail', 'badges-issuer-for-wp' ); ?></th>
        <th width="0%"><?php _e( 'Level', 'badges-issuer-for-wp' ); ?></th>
        <th width="0%"><?php _e( 'Language', 'badges-issuer-for-wp' ); ?></th>
        <th width="0%"><?php _e( 'Date', 'badges-issuer-for-wp' ); ?></th>
		<?php
			if ( in_array( "administrator", $current_user->roles ) || in_array( "editor", $current_user->roles ) ) {
				?>
                <th width="0%"><?php _e( 'Action', 'badges-issuer-for-wp' ); ?></th>
				<?php
			}
		?>
    </tr>
    </thead>
    <tbody>
	<?php
		$i = 0;
		foreach ( $class_students as $student ) {
			echo '<tr>';
			echo '<td width="0%">';
			echo '<center>' . $student["login"] . '</center>';
			echo '</td>';
			echo '<td width="0%">';
			echo '<center>' . $student["mail"] . '</center>';
			echo '</td>';
			echo '<td width="0%">';
			echo '<center>' . $student["level"] . '</center>';
			echo '</td>';
			echo '<td width="0%">';
			printf( __( '<center>%s</center>', 'badges-issuer-for-wp' ), $student["language"] );
			echo '</td>';
			echo '<td width="0%">';
			echo '<center>' . $student["date"] . '</center>';
			echo '</td>';
			if ( in_array( "administrator", $current_user->roles ) || in_array( "editor", $current_user->roles ) ) {
				echo '<td width="0%">';
				printf( __( '<center><a class="button remove-row" onclick="jQuery(this).RemoveTr();" href="#id_meta_box_class_students">Remove</a></center>', 'badges-issuer-for-wp' ) );
				echo '</td>';
			}
			echo '</tr>';
			$i ++;
		}

		echo '</tbody>';

		if ( in_array( "administrator", $current_user->roles ) || in_array( "editor", $current_user->roles ) ) {

			echo '<tfoot>';
			echo '<tr>';
			echo '<td width="0%">';
			echo '<center>';
			echo '<input type="text" id="add_student_login"/>';
			echo '</center>';
			echo '</td>';
			echo '<td width="0%">';
			echo '<center>';
			echo '<input type="text" id="add_student_mail"/>';
			echo '</center>';
			echo '</td>';
			echo '<td width="0%">';
			echo '<center>';
			echo '<input type="text" id="add_student_level"/>';
			echo '</center>';
			echo '</td>';
			echo '<td width="0%">';
			echo '<center>';
			echo '<input type="text" id="add_student_language"/>';
			echo '</center>';
			echo '</td>';
			echo '<td width="0%">';
			echo '</td>';
			echo '<td width="0%">';
			echo '<center>';
			echo '<a class="button" href="#" id="add_student_job_listing">Add student</a>';
			echo '</center>';
			echo '</td>';
			echo '</tr>';
			echo '</tfoot>';
		}

		echo '</table>';
		}

		add_filter( 'template_include', 'class_template', 1 );

		/**
		 * Load the custom template for a single class.
		 *
		 * @author Nicolas TORION
		 * @since  1.0.0
		 *
		 * @param $template_path The path of the template.
		 *
		 * @return $template_path The path of the template.
		 */
		function class_template( $template_path ) {
			if ( get_post_type() == 'class' ) {
				if ( is_single() ) {
					if ( $theme_file = locate_template( array( 'class_template.php' ) ) ) {
						$template_path = $theme_file;
					} else {
						$template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/class_template.php';
					}
				}
			}

			return $template_path;
		}

		/**
		 * Saven the comment meta data
		 *
		 * @author Nicolas TORION
		 * @since  0.6.1
		 *
		 * @param $comment_id that we want to save.
		 */
		add_action( 'comment_post', 'save_comment_meta_data_class' );
		function save_comment_meta_data_class( $comment_id ) {
			if ( isset( $_POST['student_level'] ) && isset( $_POST['student_language'] ) && isset( $_POST['student_date'] ) ) {
				add_comment_meta( $comment_id, 'student_level', $_POST['student_level'] );
				add_comment_meta( $comment_id, 'student_language', $_POST['student_language'] );
				add_comment_meta( $comment_id, 'student_date', $_POST['student_date'] );
			}
		}

	?>

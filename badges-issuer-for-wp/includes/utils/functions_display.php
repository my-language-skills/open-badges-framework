<?php
	// DISPLAY FUNCTIONS

	/**
	 * Displays available levels in input radio tags. Used in the forms sending badges to students.
	 *
	 * @author Nicolas TORION
	 * @since  0.6
	 *
	 * @param $badges A list of badges.
	 */
	function display_levels_radio_buttons( $badges, $context ) {
		global $current_user;
		get_currentuserinfo();

		if ( in_array( "administrator", $current_user->roles ) || in_array( "editor", $current_user->roles ) ) {
			$levels = get_all_levels( $badges );
		} else {
			if ( $context == "self" ) {
				if ( in_array( "student", $current_user->roles ) ) {
					$levels = get_all_levels( $badges, true );
				} elseif ( in_array( "teacher", $current_user->roles ) || in_array( "academy", $current_user->roles ) ) {
					$levels = get_all_levels( $badges );
				}
			} elseif ( $context == "send" ) {
				if ( in_array( "teacher", $current_user->roles ) || in_array( "academy", $current_user->roles ) ) {
					$levels = get_all_levels( $badges, true );
				}
			}
		}

		_e( '<b> Level* : </b></br>', 'badges-issuer-for-wp' );
		foreach ( $levels as $l ) {
			echo '<label for="level_' . $l . '">' . $l . ' </label><input type="radio" class="level" name="level" id="level_' . $l . '" value="' . $l . '"> ';
		}
		echo '<br />';
	}

	/**
	 * Displays available languages in a select tag. Used in the forms sending badges to students.
	 *
	 * @author Nicolas TORION
	 * @since  0.6.1
	 * @since  0.6.3 recreated the function more simply
	 *
	 * @param string $parent permit to display the child taxonomy of the parent taxonomy (category).
	 */
	function show_all_the_language( $p_parent = "" ) {

		_e( '<label for="language"><b> Field of Education* : </b></label>', 'badges-issuer-for-wp' );

		if ( have_no_children() ) {
			$languages = get_languages();

			echo '<select name="language';
			echo '" id="language">';

			foreach ( $languages as $language ) {
				echo '<option value="' . $language->term_id . '">';
				echo $language->name . '</option>';
			}

			echo '</select>';

		} else {
			//If there parent with children

			if ( $p_parent === "" ) {
				// Display the default parent

				$parents       = get_languages();
				$actual_parent = key( $parents );

				echo '<select name="language" id="language">';
				foreach ( $parents as $parent ) {

					foreach ( $parent as $language ) {

						echo '<option value="' . $language->term_id . '">';
						echo $language->name . '</option>';
						break;
					}
				}

				echo '</select>';
				display_parents( $actual_parent );
			} else if ( $p_parent === "all_field" ) {
				// Display all the child

				$parents = get_languages();

				echo '<select name="language" id="language">';
				foreach ( $parents as $parent ) {
					foreach ( $parent as $language ) {
						echo '<option value="' . $language->term_id . '">';
						echo $language->name . '</option>';
					}
				}
				echo '</select>';
				display_parents( $p_parent );

			} else {
				// Display the children of the right parent

				$parents = get_languages();

				echo '<select name="language" id="language">';

				foreach ( $parents["$p_parent"] as $language ) {
					echo '<option value="' . $language->term_id . '">';
					echo $language->name . '</option>';
				}
				echo '</select>';
				display_parents( $p_parent );

			}

		}
	}

	/**
	 * Displays all the parents whit the possibility to change the visualization of the children.
	 *
	 * @author Alessandro RICCARDI
	 * @since  0.6.3
	 *
	 * @param string $p_parent permit to understand the active parent
	 */
	function display_parents( $p_parent = "" ) {
		$parents = get_parent_categories();
		foreach ( $parents as $parent ) {
			if ( $parent[2] == $p_parent ) {
				echo '<a href="#" class="btn btn-default btn-xs display_parent_categories active" id="' . $parent[2] . '">Display ' . $parent[1] . '</a>';
			} else {
				echo '<a href="#" class="btn btn-default btn-xs display_parent_categories" id="' . $parent[2] . '">Display ' . $parent[1] . '</a>';
			}
		}
		// Display the link to show all the languages
		if ( $p_parent === "all_field" ) {
			echo '<a href="#" class="btn btn-default btn-xs display_parent_categories active" id="all_field">Display Field</a>';
		} else {
			echo '<a class="btn btn-default btn-xs display_parent_categories" id="all_field">Display Field</a>';
		}

	}

	/**
	 * Displays a message of success.
	 *
	 * @author Nicolas TORION
	 * @since  0.3
	 *
	 * @param $message The message to display.
	 */
	function display_success_message( $message ) {
		?>
        <div class="message success">
			<?php echo $message; ?>
        </div>
		<?php
	}

	/**
	 * Displays a message of error.
	 *
	 * @author Nicolas TORION
	 * @since  0.3
	 *
	 * @param $message The message to display.
	 */
	function display_error_message( $message ) {
		?>
        <div class="message error">
			<?php echo $message; ?>
        </div>
		<?php
	}

	/**
	 * Displays a message indicating that a person is not logged. A link redirecting to the login page is also
	 * displayed.
	 *
	 * @author Nicolas TORION
	 * @since  0.6.3
	 */
	function display_not_logged_message() {
		$settings_id_login_links = get_settings_login_links();
		?>

        <center>
            <img src="<?php echo plugins_url( '../../assets/b4l_logo.png', __FILE__ ); ?>" width="256px"
                 height="256px"/>
            <br/>
            <h1><?php _e( 'To get a badge, you need to be logged on the site.', 'badges-issuer-for-wp' ); ?></h1>
            <br/>
            <a href="<?php echo get_page_link( $settings_id_login_links["link_register"] ); ?>"
               title="Register"><?php _e( 'Register', 'badges-issuer-for-wp' ); ?></a> | <a
                    href="<?php echo get_page_link( $settings_id_login_links["link_login"] ); ?>"
                    title="Login"><?php _e( 'Login', 'badges-issuer-for-wp' ); ?></a>
            <p style="color:red;">
				<?php
					_e( 'Once connected to the site, go back to your email and click again on the link for receiving your badge.', 'badges-issuer-for-wp' );
				?>
            </p>
        </center>
		<?php
	}

	/**
	 * Displays the classes of the teacher in input tags. Used in the forms sending badges to students.
	 *
	 * @author Nicolas TORION
	 * @since  0.6
	 */
	function display_classes_input() {
		global $current_user;
		get_currentuserinfo();

		if ( $in_array( "administrator", $current_user->roles ) || in_array( "editor", $current_user->roles ) ) {
			$classes = get_all_classes();
		} else {
			$classes = get_classes_teacher( $current_user->user_login );
		}

		printf( esc_html__( '<b>Class* : </b><br />', 'badges-issuer-for-wp' ) );
		foreach ( $classes as $class ) {
			echo '<label for="class_' . $class->ID . '">' . $class->post_title . ' </label><input name="class_for_student" id="class_' . $class->ID . '" type="radio" value="' . $class->ID . '"/>';
		}
	}

?>

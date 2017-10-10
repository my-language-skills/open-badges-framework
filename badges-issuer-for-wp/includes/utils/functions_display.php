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
	 *
	 * @param string $category          permit to display the child taxonomy of the parent taxonomy (category).
	 * @param string $language_selected The language to select.
	 * @param bool   $multiple          A boolean to know if the select form must be in multiple mode.
	 */
	function display_languages_select_form( $category = "", $language_selected = "", $multiple = false ) {

		_e( '<label for="language"><b> Field of Education* : </b></label>', 'badges-issuer-for-wp' );

		if ( have_only_parent_education() ) {
			$languages = get_languages();

			echo '<select name="language';
			echo '" id="language">';

			foreach ( $languages as $language ) {
				echo '<option value="' . $language->term_id . '">';
				echo $language->name . '</option>';
			}

			echo '</select>';

		} else {

			$parents = get_languages();
			echo '<select name="language" id="language">';

			foreach ( $parents[2] as $language ) {
				echo '<option value="' . $language->term_id . '">';
				echo $language->name . '</option>';
			}

			echo '</select>';
		}

//////////////////////////////////////////////////
		/*
			$all_languages = get_all_languages();

		  _e('<label for="language"><b> Field of Education* : </b></label>‚','badges-issuer-for-wp');

		  //To display the first parent category as default language
		  if($category == ""){
			$cat_count = 0;
			$language_to_display = $all_languages;
			echo '<select name="language';
			if($multiple)
			  echo '[]';
			echo '" id="language">';
			echo '<optgroup>';
			foreach ($language_to_display as $language => $children) {
			  if($cat_count == 0){
			  foreach($children as $key => $value){
				  $value = str_replace("\n", "", $value);
				  echo '<option value="'.$value.'"';
				  if($language_selected==$value)
					echo ' selected';
					echo '>'.$value.'</option>';
				}
			  }
				$cat_count++;
			  }
			echo '</optgroup>';
			echo '</select>';
		  }


		  // Display all the languages if the user click the link to display all the languages
		  else if($category == "all_languages"){
			  $language_to_display = $all_languages;
			  echo '<select name="language';
			  if($multiple)
				echo '[]';
			  echo '" id="language">';
			  echo '<optgroup>';
			  foreach ($language_to_display as $language ) {
				  foreach($language as $children){
					$children = str_replace("\n", "", $children);
					echo '<option value="'.$children.'"';
					if($language_selected==$children)
					  echo ' selected';
					  echo '>'.$children.'</option>';
					}
				  }
			  echo '</optgroup>';
			  echo '</select>';
		  }

		  // Display the language category if the user click any other link
		  else {
			  $language_to_display = $all_languages[$category];
				echo '<select name="language';
				if($multiple)
					echo '[]';
				  echo '" id="language">';
				  echo '<optgroup>';
				  foreach ($language_to_display as $language) {
					$language = str_replace("\n", "", $language);
					echo '<option value="'.$language.'"';
					if($language_selected==$language)
					  echo ' selected';
					echo '>'.$language.'</option>';
				  }
				  echo '</optgroup>';
				  echo '</select>';
		  }
		*/
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

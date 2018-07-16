<?php

/**
 * Add custom fields on the Restrict Content Pro Registration form.
 *
 * @author @leocharlier
 * @since 1.0.1
 */
function pw_rcp_add_user_registration_fields() {

    //Prepare all post meta (if they are set)
    $year = get_the_author_meta( 'year_of_birth', get_current_user_id() );
    $country = get_the_author_meta( 'country', get_current_user_id() );
    $city = get_the_author_meta( 'city', get_current_user_id() );
    $primary_degree = get_the_author_meta( 'primary_degree', get_current_user_id() );
    $secondary_degree = get_the_author_meta( 'secondary_degree', get_current_user_id() );
    $tertiary_degree = get_the_author_meta( 'tertiary_degree', get_current_user_id() );
    $mother_tongue = get_the_author_meta( 'mother_tongue', get_current_user_id() );

    //Add custom fields only if the user is not log in (first registration)
    //If is logged in, he can modify this information in the 'Edit Profile' form
    if( !is_user_logged_in() ){
  ?>
    <h1>Personal Information</h1>

    <!-- Year of birth (between 1920 and the current year) -->
    <p>
        <label for="year_of_birth"><?php _e( 'Year of birth', 'rcp' ); ?></label>
        <select name="year_of_birth" id="year_of_birth">
            <?php
                for ($i = date("Y"); $i >= 1920; $i--) {
                    if( $i == intval( esc_attr( $year ) ) ){
                        echo '<option selected="selected" value="' . $i . '">' . $i . '</option>';
                    } else{
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                }
            ?>
        </select>
    </p>

    <?php
        //Prepare datas for the countries (found this list on GitHub)
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

            
    ?>

   <!--  Country -->
    <p>
        <label for="country"><?php _e( 'Country', 'rcp' ); ?></label>
        <select name="country" id="country">
            <?php
                foreach ($countries as $country_option){
                    if( $country_option == esc_attr( $country ) ){
                        echo '<option selected="selected" value="' . $country_option . '">' . $country_option . '</option>';
                    } else{
                        echo '<option value="' . $country_option . '">' . $country_option . '</option>';
                    }
                }
            ?>
        </select>
    </p>

    <!-- City -->
    <p>
        <label for="city"><?php _e( 'City', 'rcp' ); ?></label>
        <input name="city" id="city" type="text" value="<?php echo esc_attr( $city ); ?>"/>
    </p>

    <!-- Mother toungue -->
    <p>
        <label for="mother_tongue"><?php _e( 'Mother Tongue', 'rcp' ); ?></label>
        <input name="mother_tongue" id="mother_tongue" type="text" value="<?php echo esc_attr( $mother_tongue ); ?>"/>
    </p>

    <!-- Primary degree -->
    <p>
        <label for="primary_degree"><?php _e( 'Primary Degree', 'rcp' ); ?></label>
        <input name="primary_degree" id="primary_degree" type="text" value="<?php echo esc_attr( $primary_degree ); ?>"/>
    </p>

    <!-- Secondary degree -->
    <p>
        <label for="secondary_degree"><?php _e( 'Secondary Degree', 'rcp' ); ?></label>
        <input name="secondary_degree" id="secondary_degree" type="text" value="<?php echo esc_attr( $secondary_degree ); ?>"/>
    </p>

    <!-- Tertiary degree -->
    <p>
        <label for="tertiary_degree"><?php _e( 'Tertiary Degree', 'rcp' ); ?></label>
        <input name="tertiary_degree" id="tertiary_degree" type="text" value="<?php echo esc_attr( $tertiary_degree ); ?>"/>
    </p>

  <?php
  }
}
add_action( 'rcp_before_subscription_form_fields', 'pw_rcp_add_user_registration_fields' );

/**
 * Add custom fields on the Restrict Content Pro Profile editor forms
 *
 * @author @leocharlier
 * @since 1.0.1
 */
function pw_rcp_add_user_editor_fields() {

    //Prepare all post meta
    $year = get_the_author_meta( 'year_of_birth', get_current_user_id() );
    $country = get_the_author_meta( 'country', get_current_user_id() );
    $city = get_the_author_meta( 'city', get_current_user_id() );
    $primary_degree = get_the_author_meta( 'primary_degree', get_current_user_id() );
    $secondary_degree = get_the_author_meta( 'secondary_degree', get_current_user_id() );
    $tertiary_degree = get_the_author_meta( 'tertiary_degree', get_current_user_id() );
    $mother_tongue = get_the_author_meta( 'mother_tongue', get_current_user_id() );

    $user_data = get_userdata( get_current_user_id() );
    $website = $user_data->user_url;
    $facebook = get_the_author_meta( 'facebook', get_current_user_id() );
    $twitter = get_the_author_meta( 'twitter', get_current_user_id() );
    $googleplus = get_the_author_meta( 'googleplus', get_current_user_id() );
    $pinterest = get_the_author_meta( 'pinterest', get_current_user_id() );
    $linkedin = get_the_author_meta( 'linkedin', get_current_user_id() );
    $github = get_the_author_meta( 'github', get_current_user_id() );
    $instagram = get_the_author_meta( 'instagram', get_current_user_id() );

  ?>
    <h1>Personal Information</h1>

    <!-- Year of birth (between 1920 and the current year) -->
    <p>
        <label for="year_of_birth"><?php _e( 'Year of birth', 'rcp' ); ?></label>
        <select name="year_of_birth" id="year_of_birth">
            <?php
                for ($i = date("Y"); $i >= 1920; $i--) {
                    if( $i == intval( esc_attr( $year ) ) ){
                        echo '<option selected="selected" value="' . $i . '">' . $i . '</option>';
                    } else{
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                }
            ?>
        </select>
    </p>

    <?php
        //Prepare datas for the countries (found this list on GitHub)
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

            
    ?>

    <!--  Country -->
    <p>
        <label for="country"><?php _e( 'Country', 'rcp' ); ?></label>
        <select name="country" id="country">
            <?php
                foreach ($countries as $country_option){
                    if( $country_option == esc_attr( $country ) ){
                        echo '<option selected="selected" value="' . $country_option . '">' . $country_option . '</option>';
                    } else{
                        echo '<option value="' . $country_option . '">' . $country_option . '</option>';
                    }
                }
            ?>
        </select>
    </p>

    <!--  City -->
    <p>
        <label for="city"><?php _e( 'City', 'rcp' ); ?></label>
        <input name="city" id="city" type="text" value="<?php echo esc_attr( $city ); ?>"/>
    </p>

    <!--  Mother toungue -->
    <p>
        <label for="mother_tongue"><?php _e( 'Mother Tongue', 'rcp' ); ?></label>
        <input name="mother_tongue" id="mother_tongue" type="text" value="<?php echo esc_attr( $mother_tongue ); ?>"/>
    </p>

    <!--  Primary degree -->
    <p>
        <label for="primary_degree"><?php _e( 'Primary Degree', 'rcp' ); ?></label>
        <input name="primary_degree" id="primary_degree" type="text" value="<?php echo esc_attr( $primary_degree ); ?>"/>
    </p>

    <!--  Secondary degree -->
    <p>
        <label for="secondary_degree"><?php _e( 'Secondary Degree', 'rcp' ); ?></label>
        <input name="secondary_degree" id="secondary_degree" type="text" value="<?php echo esc_attr( $secondary_degree ); ?>"/>
    </p>

    <!--  Tertiary degree -->
    <p>
        <label for="tertiary_degree"><?php _e( 'Tertiary Degree', 'rcp' ); ?></label>
        <input name="tertiary_degree" id="tertiary_degree" type="text" value="<?php echo esc_attr( $tertiary_degree ); ?>"/>
    </p>

    <h1>Social links</h1>
    <p>Enter the URL of your social link</p>
    <!--  Web site -->
    <p>
        <label for="website"><?php _e( 'Web site', 'rcp' ); ?></label>
        <input name="website" id="website" type="text" value="<?php echo esc_attr( $website ); ?>"/>
    </p>

    <!--  Facebook -->
    <p>
        <label for="facebook"><?php _e( 'Facebook', 'rcp' ); ?></label>
        <input name="facebook" id="facebook" type="text" value="<?php echo esc_attr( $facebook ); ?>"/>
    </p>

    <!--  Twitter -->
    <p>
        <label for="twitter"><?php _e( 'Twitter', 'rcp' ); ?></label>
        <input name="twitter" id="twitter" type="text" value="<?php echo esc_attr( $twitter ); ?>"/>
    </p>

    <!--  Google + -->
    <p>
        <label for="googleplus"><?php _e( 'Google +', 'rcp' ); ?></label>
        <input name="googleplus" id="googleplus" type="text" value="<?php echo esc_attr( $googleplus ); ?>"/>
    </p>

    <!--  Pinterest -->
    <p>
        <label for="pinterest"><?php _e( 'Pinterest', 'rcp' ); ?></label>
        <input name="pinterest" id="pinterest" type="text" value="<?php echo esc_attr( $pinterest ); ?>"/>
    </p>

    <!--  LinkedIn -->
    <p>
        <label for="linkedin"><?php _e( 'LinkedIn', 'rcp' ); ?></label>
        <input name="linkedin" id="linkedin" type="text" value="<?php echo esc_attr( $linkedin ); ?>"/>
    </p>

    <!--  GitHub -->
    <p>
        <label for="github"><?php _e( 'GitHub', 'rcp' ); ?></label>
        <input name="github" id="github" type="text" value="<?php echo esc_attr( $github ); ?>"/>
    </p>

    <!--  Instagram -->
    <p>
        <label for="instagram"><?php _e( 'Instagram', 'rcp' ); ?></label>
        <input name="instagram" id="instagram" type="text" value="<?php echo esc_attr( $instagram ); ?>"/>
    </p>

  <?php
}
add_action( 'rcp_profile_editor_after', 'pw_rcp_add_user_editor_fields' );

/**
 * Stores the information submitted during registration
 *
 * @author @leocharlier
 * @since 1.0.1
 */
function pw_rcp_save_user_fields_on_register( $posted, $user_id ) {

  if( ! empty( $posted['year_of_birth'] ) ) {
    update_user_meta( $user_id, 'year_of_birth', sanitize_text_field( $posted['year_of_birth'] ) );
  }
  if( ! empty( $posted['country'] ) ) {
    update_user_meta( $user_id, 'country', sanitize_text_field( $posted['country'] ) );
  }
  if( ! empty( $posted['city'] ) ) {
    update_user_meta( $user_id, 'city', sanitize_text_field( $posted['city'] ) );
  }
  if( ! empty( $posted['mother_tongue'] ) ) {
    update_user_meta( $user_id, 'mother_tongue', sanitize_text_field( $posted['mother_tongue'] ) );
  }
  if( ! empty( $posted['primary_degree'] ) ) {
    update_user_meta( $user_id, 'primary_degree', sanitize_text_field( $posted['primary_degree'] ) );
  }
  if( ! empty( $posted['secondary_degree'] ) ) {
    update_user_meta( $user_id, 'secondary_degree', sanitize_text_field( $posted['secondary_degree'] ) );
  }
  if( ! empty( $posted['tertiary_degree'] ) ) {
    update_user_meta( $user_id, 'tertiary_degree', sanitize_text_field( $posted['tertiary_degree'] ) );
  }
}
add_action( 'rcp_form_processing', 'pw_rcp_save_user_fields_on_register', 10, 2 );

/**
 * Stores the information submitted profile update
 *
 * @author @leocharlier
 * @since 1.0.1
 */
function pw_rcp_save_user_fields_on_profile_save( $user_id ) {

  if( ! empty( $_POST['year_of_birth'] ) ) {
    update_user_meta( $user_id, 'year_of_birth', sanitize_text_field( $_POST['year_of_birth'] ) );
  }

  if( ! empty( $_POST['country'] ) ) {
    update_user_meta( $user_id, 'country', sanitize_text_field( $_POST['country'] ) );
  }
  if( ! empty( $_POST['city'] ) ) {
    update_user_meta( $user_id, 'city', sanitize_text_field( $_POST['city'] ) );
  }
  if( ! empty( $_POST['mother_tongue'] ) ) {
    update_user_meta( $user_id, 'mother_tongue', sanitize_text_field( $_POST['mother_tongue'] ) );
  }
  if( ! empty( $_POST['primary_degree'] ) ) {
    update_user_meta( $user_id, 'primary_degree', sanitize_text_field( $_POST['primary_degree'] ) );
  }
  if( ! empty( $_POST['secondary_degree'] ) ) {
    update_user_meta( $user_id, 'secondary_degree', sanitize_text_field( $_POST['secondary_degree'] ) );
  }
  if( ! empty( $_POST['tertiary_degree'] ) ) {
    update_user_meta( $user_id, 'tertiary_degree', sanitize_text_field( $_POST['tertiary_degree'] ) );
  }

  if( ! empty( $_POST['website'] ) ) {
    update_user_meta( $user_id, 'user_url', sanitize_text_field( $_POST['website'] ) );
  }
  if( ! empty( $_POST['facebook'] ) ) {
    update_user_meta( $user_id, 'facebook', sanitize_text_field( $_POST['facebook'] ) );
  }
  if( ! empty( $_POST['twitter'] ) ) {
    update_user_meta( $user_id, 'twitter', sanitize_text_field( $_POST['twitter'] ) );
  }
  if( ! empty( $_POST['googleplus'] ) ) {
    update_user_meta( $user_id, 'googleplus', sanitize_text_field( $_POST['googleplus'] ) );
  }
  if( ! empty( $_POST['pinterest'] ) ) {
    update_user_meta( $user_id, 'pinterest', sanitize_text_field( $_POST['pinterest'] ) );
  }
  if( ! empty( $_POST['linkedin'] ) ) {
    update_user_meta( $user_id, 'linkedin', sanitize_text_field( $_POST['linkedin'] ) );
  }
  if( ! empty( $_POST['github'] ) ) {
    update_user_meta( $user_id, 'github', sanitize_text_field( $_POST['github'] ) );
  }
  if( ! empty( $_POST['instagram'] ) ) {
    update_user_meta( $user_id, 'instagram', sanitize_text_field( $_POST['instagram'] ) );
  }
}

add_action( 'rcp_user_profile_updated', 'pw_rcp_save_user_fields_on_profile_save', 10 );
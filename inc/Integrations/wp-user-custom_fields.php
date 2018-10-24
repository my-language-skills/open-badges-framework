<?php

/**
 * Add custom fields on the WP user profile page and profile editor
 *
 * @author @leocharlier
 * @since 1.0.1
 */
function crf_show_extra_profile_fields( $user ) {

    //Prepare all post meta (if they are set)
    $year = get_the_author_meta( 'year_of_birth', $user->ID );
    $country = get_the_author_meta( 'country', $user->ID );
    $city = get_the_author_meta( 'city', $user->ID );
    $primary_degree = get_the_author_meta( 'primary_degree', $user->ID );
    $secondary_degree = get_the_author_meta( 'secondary_degree', $user->ID );
    $tertiary_degree = get_the_author_meta( 'tertiary_degree', $user->ID );
    $mother_tongue = get_the_author_meta( 'mother_tongue', $user->ID );

    ?>
    <h3><?php esc_html_e( 'Open Badges', 'crf' ); ?></h3>

    <table class="form-table">
        <tr>
            <th><label for="year_of_birth"><?php esc_html_e( 'Year of birth', 'crf' ); ?></label></th>
            <td>
                 <!-- Year of birth (between 1920 and the current year) -->
                <select name="year_of_birth" id="year_of_birth">
                    <option value="none">Select</option>
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
                <p class="description"><?php _e( 'The member\'s year of birth', 'rcp' ); ?></p>
            </td>
        </tr>
        <tr>
            <!--  Country -->
            <th><label for="country"><?php esc_html_e( 'Country', 'crf' ); ?></label></th>
            <td>
                <?php
                    //Prepare datas for the countries (found this list on GitHub)
                    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

                        
                    ?>
                <select name="country" id="country">
					<option value="none">Select</option>
                    <?php
                        foreach ($countries as $country_option){ ?>
                            
                            <?php if( $country_option == esc_attr( $country ) ){
                                echo '<option selected="selected" value="' . $country_option . '">' . $country_option . '</option>';
                            } else{
                                echo '<option value="' . $country_option . '">' . $country_option . '</option>';
                            }
                        }
                    ?>
                </select>
                <p class="description"><?php _e( 'The member\'s country', 'rcp' ); ?></p>
            </td>
        </tr>
        <tr>
            <!--  City -->
            <th><label for="city"><?php esc_html_e( 'City', 'crf' ); ?></label></th>
            <td>
                <input name="city" id="city" type="text" value="<?php echo esc_attr( $city ); ?>"/>
                <p class="description"><?php _e( 'The member\'s city', 'rcp' ); ?></p>
            </td>
        </tr>
        <tr>
            <!--  Mother toungue -->
            <th><label for="mother_tongue"><?php esc_html_e( 'Mother Tongue', 'crf' ); ?></label></th>
            <td>
                <input name="mother_tongue" id="mother_tongue" type="text" value="<?php echo esc_attr( $mother_tongue ); ?>"/>
                <p class="description"><?php _e( 'The member\'s mother tongue', 'rcp' ); ?></p>
            </td>
        </tr>
        <tr>
            <!-- Degrees -->
            <th><label for="degrees"><?php esc_html_e( 'Education', 'crf' ); ?></label></th>
            <!--  Primary degree -->
            <td>
                <input name="primary_degree" id="primary_degree" type="text" value="<?php echo esc_attr( $primary_degree ); ?>"/>
                <p class="description"><?php _e( 'Primary degree', 'rcp' ); ?></p>
            </td>
            
        </tr>
        <tr>
            <th></th>
            <!--  Secondary degree -->
            <td>
                <input name="secondary_degree" id="secondary_degree" type="text" value="<?php echo esc_attr( $secondary_degree ); ?>"/>
                <p class="description"><?php _e( 'Secondary degree', 'rcp' ); ?></p>
            </td>
        </tr>
        <tr>
            <th></th>
            <!--  Tertiary degree -->
            <td>
                <input name="tertiary_degree" id="tertiary_degree" type="text" value="<?php echo esc_attr( $tertiary_degree ); ?>"/>
                <p class="description"><?php _e( 'Tertiary degree', 'rcp' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'crf_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'crf_show_extra_profile_fields' );

/**
 * Stores the information submitted by editting the profile
 *
 * @author @leocharlier
 * @since 1.0.1
 */
function crf_update_profile_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    if ( ! empty( $_POST['year_of_birth'] ) ) {
        update_user_meta( $user_id, 'year_of_birth', intval( $_POST['year_of_birth'] ) );
    }

    if ( ! empty( $_POST['country'] ) ) {
        update_user_meta( $user_id, 'country', $_POST['country'] );
    }

    if ( ! empty( $_POST['city'] ) ) {
        update_user_meta( $user_id, 'city', $_POST['city'] );
    }

    if ( ! empty( $_POST['primary_degree'] ) ) {
        update_user_meta( $user_id, 'primary_degree', $_POST['primary_degree'] );
    }

    if ( ! empty( $_POST['secondary_degree'] ) ) {
        update_user_meta( $user_id, 'secondary_degree', $_POST['secondary_degree'] );
    }

    if ( ! empty( $_POST['tertiary_degree'] ) ) {
        update_user_meta( $user_id, 'tertiary_degree', $_POST['tertiary_degree'] );
    }

    if ( ! empty( $_POST['mother_tongue'] ) ) {
        update_user_meta( $user_id, 'mother_tongue', $_POST['mother_tongue'] );
    }
}
add_action( 'personal_options_update', 'crf_update_profile_fields' );
add_action( 'edit_user_profile_update', 'crf_update_profile_fields' );
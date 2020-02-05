<?php

namespace templates;

use Inc\Base\BaseController;
use Inc\Utils\WPUser;
use Inc\Pages\Admin;
use Inc\Utils\Badge;
use Inc\Utils\JsonManagement;
use Inc\Utils\WPBadge;
use ReallySimpleCaptcha;
use templates\SettingsTemp;

/**
 * Template for the Get Badge page.
 * In this class are defined all the function that permits
 * you to follow all the step to get a badge.
 *
 * All the content to show in the front-end is wrapped in the __() function
 * for internationalization purposes
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class GetBadgeTemp extends BaseController {
    const START = 0;
    const ERROR_JSON = 1;
    const ERROR_LINK = 2;
    const GOT = 3;
    const PREVIEW = 4;

    private $badgeDB = null;

    private $badgeWP = null;
    private $fieldWP = null;
    private $levelWP = null;

    /**
     * Singleton function to get the instance of the class.
     *
     * @return null|GetBadgeTemp
     */
    public static function getInstance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new Self();
        }
        return $inst;
    }

    /**
     * In this function, for the first thing are check the parameters in
     * the url string and then based on the kind of return it will show
     * the right view.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public function main() {
        $res = $this->loadParm();

        switch ($res) {
            case self::START:
                $this->getStartingStep();
                break;
            case self::GOT:
                $this->showBadgeGot();
                break;
            case self::PREVIEW:
                $this->showMessage(self::PREVIEW);
                break;
            case self::ERROR_JSON:
                $this->showMessage(self::ERROR_JSON);
                break;
            case self::ERROR_LINK:
                $this->showMessage(self::ERROR_LINK);
                break;
        }
    }

    /**
     * Check the parameters if they're right and then load the
     * information in variables.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return string   START when we can start with the procedure;
     *                  GOT if is already got the badge;
     *                  PREVIEW if the page is only a preview to show that works;
     *                  ERROR_JSON if the json is not stored in the server;
     *                  ERROR_LINK if the link have problems.
     */
    private function loadParm() {

        if (isset($_GET['v']) && !empty($_GET['v'])) {
            $this->badgeDB = new Badge();
            $this->badgeDB->retrieveBadge($_GET['v']);
            if ($this->badgeDB->creationDate) {
                $this->badgeWP = WPBadge::get($this->badgeDB->idBadge);
                $this->fieldWP = get_term($this->badgeDB->idField, Admin::TAX_FIELDS);
                $this->levelWP = get_term($this->badgeDB->idLevel, Admin::TAX_LEVELS);

                if (!$this->badgeDB->gotMozillaDate) {
                    //Everything OK
                    return self::START;
                } else {
                    //Badge already GOT
                    return self::GOT;
                }
            } else {
                return self::ERROR_LINK;
            }

        } else {
            //Formation of the link not for get the badge
            if (isset($_GET['preview']) && $_GET['preview'] == 1) {
                // Preview mode
                return self::PREVIEW;
            } else {
                // Broken link
                return self::ERROR_LINK;
            }
        }
    }

    /**
     * Show the starting step to get the badge.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public function getStartingStep() {
        $this->obf_header();
        ?>
        <header class="masthead mb-auto">
            <?php $this->getInfoHeader(); ?>
            <div class="container inner">
                <div class="cont-title"><?php _e('New badge','open-badges-framework');?></div>
            </div>
        </header>

        <main role="main" class="inner cover">
            <div class="container">
                <h1 class="badge-title-obf cover-heading">
                    <strong><?php echo $this->badgeWP->post_title; ?></strong>
                </h1>
                <h5 class="badge-field"> <?php _e('Field of education: ','open-badges-framework')?><strong><?php echo $this->fieldWP->name; ?></strong> </br>
					<?php _e('Level:','open-badges-framework')?>
                    <strong><?php echo $this->levelWP->name; ?></strong></h5>
                <p class="lead">
                    <?php //echo $this->badgeWP->post_content; 
							echo $this->badgeDB->description;
					?>
                </p>
                <div class="logo-badge-cont">
					<a class="continueLink">
						<img src="<?php echo WPBadge::getUrlImage($this->badgeWP->ID); ?>">
					</a>
                </div> 
            </div>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner" id="footer_login">
                <p class="lead">
                    <button id="gb-continue" class="btn btn-lg btn-secondary continueLink" type="submit"><?php _e('Continue','open-badges-framework');?></button>
                </p>
            </div>
        </footer>  


        <?php
        $this->obf_footer();
    }

    /**
     * Show login step.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param string $email that the user need to use for the login
     *
     * @return void
     */
    public function showTheLoginContent($email) { ?>

        <header class="masthead mb-auto">
            <?php $this->getInfoHeader(); ?>
        </header>
		<?php wp_logout(); ?>
        <main role="main" class="inner cover">
            <div class="container cont-form">
                <form method="post" id="gb-form-login" class="gb-form">
                    <h2 class="form-signin-heading"><?php _e('Please sign in','open-badges-framework')?></h2>
                    <label for="inputEmail" class="sr-only"><?php _e('Email address / Username','open-badges-framework')?></label>
                    <input type="email"  class="form-control" id="staticEmail"
                            placeholder="<?php _e('Email','open-badges-framework')?>" required>
                    <label for="inputPassword" class="sr-only"><?php _e('Password','open-badges-framework')?></label>
                    <input type="password" id="inputPassword" class="form-control" placeholder="<?php _e('Password','open-badges-framework')?>"
                           required>
                    <?php
                        if( is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' ) ){
                    ?>
                        <a href= <?php echo '"' . get_permalink( get_page_by_path( 'sign-in' ) ) . '?rcp_action=lostpassword"' ?> class="aDecor">Lost Password ?</a>
                    <?php } ?>
                    <div class="checkbox">
                        <label>
                            <input id="inputRemember" type="checkbox" value="remember-me"> <?php _e('Remember me','open-badges-framework')?>
                        </label>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit"><?php _e('Sign in','open-badges-framework')?>
                    </button>
                </form>
				<div class="mastfoot mt-auto">
					<div class="inner">
						<div id="gb-resp-login">

						</div>
					</div>
				</div>
            </div>
        </main>


        <?php
    }

    /**
     * Show register page step.
     *
     * @author      @AleRiccardi, @leocharlier
     * @since       1.0.0
     *
     * @param string $email that the user need to use for the registration
     *
     * @return void
     */
    public function showRegisterPage($email) { ?>
        <header class="masthead mb-auto">
            <?php $this->getInfoHeader(); ?>
            <div class="inner">
                <div class="cont-title"><?php _e('Registration','open-badges-framework')?></div>
            </div>
        </header>

        <main role="main" class="inner cover registration">
            <div class="container">
                <form id="gb-form-registration" id="needs-validation" novalidate>

                    <h3>Login Information</h3>
                    <!-- USERNAME -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="reg-user-name" placeholder="<?php _e('Username','open-badges-framework')?>*" required>
                        </div>
                    </div>

                     <!-- EMAIL -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="reg-email" placeholder="<?php _e('Email','open-badges-framework')?>*"
                                required>
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="password" class="form-control" id="reg-pass" placeholder="<?php _e('Password','open-badges-framework')?>*" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="password" class="form-control" id="reg-repeat-pass" placeholder="Repeat password*" required>
                        </div>
                    </div>

                    <h3>Personal Information</h3>

                    <!-- FIRST NAME -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="reg-first-name" placeholder="<?php _e('First name','open-badges-framework')?>*" required>
                        </div>
                    </div>

                    <!-- LAST NAME -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="reg-last-name" placeholder="<?php _e('Last name','open-badges-framework')?>*" required>
                        </div>
                    </div>

                    <!-- YEAR OF BIRTH -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <select name="reg-year" id="reg-year" class="form-control">
                                <option value="none">Year of birth</option>
                                <?php
                                    //Year of birth (between 1920 and the current year)
                                    for ($i = date("Y"); $i >= 1920; $i--) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- COUNTRY -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php

                                //Countries list (found on GitHub)
                                $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

                                ?>
                            <select name="country" id="reg-country" class="form-control">
                                <option value="none">Country</option>
                                <?php
                                    foreach ($countries as $country_option){
                                            echo '<option value="' . $country_option . '">' . $country_option . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- CITY -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="reg-city" placeholder="<?php _e('City','open-badges-framework')?>">
                        </div>
                    </div>

                    <h3>Other Information</h3>

                    <!-- MOTHER TOUNGUE -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="reg-mother-tongue" placeholder="<?php _e('Mother tongue','open-badges-framework')?>">
                        </div>
                    </div>

                    <!-- PRIMARY DEGREE -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="reg-primary-degree" placeholder="<?php _e('Primary degree','open-badges-framework')?>">
                        </div>
                    </div>

                    <!-- SECONDARY DEGREE -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="reg-secondary-degree" placeholder="<?php _e('Secondary degree','open-badges-framework')?>">
                        </div>
                    </div>

                    <!-- TERTIARY DEGREE -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="reg-tertiary-degree" placeholder="<?php _e('Tertiary degree','open-badges-framework')?>">
                        </div>
                    </div>

                    <!-- CAPTCHA -->
                    <?php
                        //Check if the ReallySimpleCaptcha plugin is active and if the capthca is enabled in the settings page
                        if( is_plugin_active( 'really-simple-captcha/really-simple-captcha.php' ) && SettingsTemp::getOption(SettingsTemp::FI_CAPTCHA)==1 )  {
                            //Instantiate a ReallySimpleCaptcha object
                            $captcha_instance = new ReallySimpleCaptcha();
                            //Set the captcha image size
                            $captcha_instance->img_size = array( 175, 50 );
                            //Ajust the place of the captcha image
                            $captcha_instance->base = array( 6, 40 );
                            //Set the size of the captcha font size
                            $captcha_instance->font_size = 39;
                            //Set the captcha font width
                            $captcha_instance->font_char_width = 42;
                            //Generate a random word
                            $word = $captcha_instance->generate_random_word();
                            //Generate a random prefix for the files (.png and .txt)
                            $prefix = mt_rand();
                            //Generate the image and text files to use captcha
                            $captcha_instance->generate_image( $prefix, $word );
                            ?>

                            <div class="form-group row" id="div-captcha">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="reg-captcha-answer" placeholder="<?php _e('Copy the word below','open-badges-framework')?>*" required>
                                    <!-- Permit to keep the files prefix in data (we need them to call the RSC functions in other files) -->
                                    <input id="reg-captcha-prefix" name="prefix" type="hidden" value="<?php echo $prefix ?>">
                                    <img src="<?php echo plugin_dir_url() . 'really-simple-captcha/tmp/' . $prefix . '.png'?>">
                                </div>
                            </div>

                    <?php } ?>

                    <div class="cont-btn-form-reg">
                        <p class="description">*Mandatory fields
                    </div>

                    <div class="cont-btn-form-reg">
                        <div id="gb-resp-register">

                        </div>
                        <input type="submit" id="submit-form" style="display: none;"/>
                    </div>
                </form>
            </div>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <label id="lbl-submit-form" class="btn btn-primary btn-lg" for="submit-form"
                       tabindex="0"><?php _e('Register','open-badges-framework')?></label>
            </div>
        </footer>

        <?php
    }

    /**
     * Show Mozilla Open Badge step.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param $isGot      true if he got the badge but without certification from Mozilla Open Badge |
     *                    False if he need to take also the partial.
     *
     * @return void
     */
    public function showMozillaOpenBadges($isGot = false) { ?>

        <header class="masthead mb-auto">
            <?php $this->getInfoHeader(); ?>
            <div class="inner container">
                <div class="ob-menu">
                    <span class="ob-cont-title">Mozilla Open Badges</span>
                    <span class="ob-user-info">
                            <?php echo get_avatar(WPUser::getCurrentUser()->ID); ?>
                            <?php 
							if (WPUser::getCurrentUser()->first_name){
									echo WPUser::getCurrentUser()->first_name;
								}
							else
							 {echo WPUser::getCurrentUser()->user_login;}
							?>
                    </span>
                </div>
            </div>
        </header>

        <main role="main" class="inner cover">
            <div class="container">
                <div class="jumbotron jumbotron-fluid jumb-obm">
                    <p class="lead">
                        <?php _e('Mozilla Open Badges give you the opportunity to store your badges in its platform which
                        permit you to share your progress with all the community.','open-badges-framework');?>
                        <br><br>
                        <?php _e('If you don’t have an Open Badge account, please click','open-badges-framework');?>
                        <a href="https://eu.badgr.com/auth/login" target="_blank"
                           style="font-size: 25px;"><?php _e('here','open-badges-framework');?></a>
                        <?php _e('and create a new account with the same email address of the registration of this website and
                        then','open-badges-framework');?> <strong><?php _e('get the badge','open-badges-framework');?></strong>.

                    </p>
                    <div class="cont-btn-standar">
                        <button id="gb-ob-get-badge" class="btn btn-lg btn-primary" type="submit"><?php _e('Get the Badge','open-badges-framework');?>
                        </button>
                    </div>
                    <div id="gb-ob-response">
                    </div>
                </div>
                <?php
                if ($isGot) {
                    ?>
                    <a class="btn-link" href="<?php echo get_bloginfo('url'); ?>"
                       role="button"><?php _e('or go to the home page','open-badges-framework');?></a>
                    <?php
                } else { ?>
                    <button id="gb-get-standard" class="btn-link" type="submit">
                        <?php _e('or skip the process and get anyway the Badge','open-badges-framework');?>

                </button>
                <?php
            }
            ?>
            </div>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <div class="logo-open-badges">
                    <img src="<?php echo $this->plugin_url; ?>/assets/images/open-badges-mz-logo.png">
                </div>
            </div>
        </footer>


        <?php
    }

    /**
     * Show Conclusions step.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public function showConclusionsStep() {
        ?>

        <header class="masthead mb-auto">
            <?php $this->getInfoHeader(); ?>
            <div class="inner container">
                <div class="cont-title"><?php _e('Congratulations!','open-badges-framework');?></div>
            </div>
        </header>

        <main role="main" class="inner cover">
            <div class="container">
                <h1 class="cong-title-obf cover-heading">
                    <?php 
							if (WPUser::getCurrentUser()->first_name){
									echo WPUser::getCurrentUser()->first_name . ", "; _e('You just added a new badge!','open-badges-framework');
								}
							else
							 {echo WPUser::getCurrentUser()->user_login . ", "; _e('You just added a new badge!','open-badges-framework');}
					?>
                </h1>
                <div class="container cont-button-redirect">
                    <div class="row justify-content-around">
                        <div class="col-3">
                            <a class="btn btn-redirect" href="<?php echo get_bloginfo('url'); ?>"
                               role="button"> <?php _e('Home','open-badges-framework');?></a>
                        </div>
                    </div>
                </div>
                <div class="container">
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">

            </div>
        </footer>
        <?php
    }

    /**
     * Show Badge Got step to inform that you're already took the badge.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    private function showBadgeGot() {
        $this->obf_header()
        ?>
        <header class="masthead mb-auto">
            <?php $this->getInfoHeader(); ?>
            <div class="inner container">
            </div>
        </header>

        <main role="main" class="inner cover">
            <div class="container">
                <div class="logo-badge-got-cont">
                    <img src="<?php echo get_the_post_thumbnail_url($this->badgeWP->ID) ?>" height="100px"
                         width="100px">
                </div>

                <h4 class="">
                    <strong><?php echo $this->badgeWP->post_title; ?></strong>
                </h4>
                <h5 class="badge-field"><?php _e('Field: ','open-badges-framework');?><strong><?php echo $this->fieldWP->name; ?></strong> -
                     <?php _e('Level: ','open-badges-framework');?>
                    <strong><?php echo $this->levelWP->name; ?></strong></h5>
                <h2 class="badge-got-title">
                    <?php _e('Badge already got! ','open-badges-framework');?>
                </h2>

            </div>
        </main>
        <footer class="mastfoot mt-auto">
            <div class="inner">
                <div id="gb-resp-login"></div>
            </div>
        </footer>

        <?php
        $this->obf_footer();
    }

    /**
     * Show the error that we discovered in the loadParm() function.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param string $error constant that contain the kind of error
     *
     * @return void
     */
    private function showMessage($error) {
        $this->obf_header()
        ?>
        <header class="masthead mb-auto">
            <?php $this->getInfoHeader(); ?>
            <div class="inner container">
            </div>
        </header>

        <main role="main" class="inner cover">
            <div class="container">
                <?php
                if ($error == self::PREVIEW) { ?>
                    <h1><?php _e('GET BADGE PAGE PREVIEW','open-badges-framework');?></h1>
                    <p class="lead">                       
						<?php _e('his page is set as a default page that permits you to get the badge.','open-badges-framework');?>
                    </p>
                    <?php
                } elseif ($error == self::ERROR_JSON) { ?>
                    <h1><?php _e('BADGE ERROR','open-badges-framework');?></h1>
                    <p class="lead">                       
						<?php _e('Your badge is not anymore stored in our server.','open-badges-framework');?>
                    </p>
                    <?php
                } else if ($error == self::ERROR_LINK) {
                    ?>
                    <h1>URL ERROR</h1>
                    <p class="lead">
						<?php _e("There's something wrong with the link, ask to the help desk to fix the problem!",'open-badges-framework');?>
                    </p>
                    <?php
                }
                ?>
            </div>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <div id="gb-resp-login"></div>
            </div>
        </footer>

        <?php
        $this->obf_footer();
    }

    /**
     * Contain the header of the page.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    private function obf_header() {
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php _e('Get the Badge','open-badges-framework');?></title>
            <script src="https://backpack.openbadges.org/issuer.js"></script>

            <?php wp_head(); ?>
        </head>
        <body>
        <div id="gb-wrap" class="cover-container d-flex h-100 mx-auto flex-column">
        <?php
    }

    /**
     * Contain the footer of the page.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    private function obf_footer() { ?>

        </div>
        <?php wp_footer(); ?>
        </body>
        </html>

        <?php
    }

    /**
     * Contain the info of the website that are show in the top of the page.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    function getInfoHeader() {
        ?>
        <div class="info-header-obf">
            <div class="container">
                <a href="<?php echo get_bloginfo('url'); ?>"><?php
                    $options = get_option(SettingsTemp::OPTION_NAME);
                    echo $options[SettingsTemp::FI_SITE_NAME_FIELD];
                    ?>
                </a>
            </div>
        </div>
        <?php
    }
}
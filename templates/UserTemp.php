<?php

namespace templates;

use Inc\Base\Secondary;
use Inc\Utils\WPUser;
use Inc\Database\DbBadge;
use Inc\Database\DbUser;
use Inc\Pages\Admin;
use Inc\Utils\Badge;
use Inc\Utils\WPBadge;

/**
 *  Permit to wrap all the function that take care of the user and
 * the badges that he earned
 *
 * All the content to show in the front-end is wrapped in the __() function
 * for internationalization purposes 	
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class UserTemp {

    /**
     *  Make start the process only for the back-end.
     */
    public function main() {
        $user = WPUser::getCurrentUser();
        ?>
        <div class="wrap">
            <?php self::getUserPage($user->ID); ?>
        </div>
        <?php

    }

    /**
     * Show all the information about the user.
     *
     *
     *
     * @param int  $idUser  id of the user that we want to show.
     * @param bool $isAdmin understand if we are in the admin area or in the front-end.
     *
     * @return void
     */
    public static function getUserPage($idUser, $isAdmin = true) {
        $userData = get_userdata($idUser);
        $urlImg = esc_url(get_avatar_url($idUser));
        $rcp_options = get_option('rcp_settings');
        ?>

        <!-- User Description -->

        <h1>
            <?php 
            if( !empty( $userData->first_name ) && !empty( $userData->last_name ) ){
                echo "User Profile: " . $userData->first_name; ?>&nbsp;<?php echo $userData->last_name; 
            } else{
                echo "User Profile: " . get_the_author_meta( 'display_name', $idUser );
            }?>
        </h1>
        <?php
        if( !empty( get_the_author_meta( 'description', $idUser ) ) ){ ?>
            <div style = "margin-top: 10px;">
                <h2 style = "display: inline;">Bio : </h2>
                <p style="font-size: 17px; display: inline;"><?php echo get_the_author_meta( 'description', $idUser ); ?></p>
            </div>
        <?php } ?>


        <!-- User Information -->
        <section>
            <!-- Display for tablets and large screens -->
            <div class="user-info-admin flex-container user-info-large-screen">
                <div class="img-user flex-item">
                    <img class="circle-img" src="<?php echo $urlImg; ?>">
                </div>

                <div class="username-user center-container flex-item">
                    <div class="txt-info center-item">
                        <ul>
                            <li>
                                <span class="dashicons dashicons-admin-users"></span>
                                <?php echo $userData->display_name; ?>
                            </li>
                            <li>
                                <span class="dashicons dashicons-calendar"></span>
                                <span> <?php _e('Member since: ','open-badges-framework'); echo date("d M Y", strtotime($userData->user_registered)); ?></span>
                            </li>
                            <li>
                                <span class="dashicons dashicons-email-alt"></span>
                                <?php echo $userData->user_email; ?>
                            </li>
                            <li>
                                <span class="dashicons dashicons-admin-tools"></span>

                                <?php 
                                    //If Restrict Content Pro plugin is activated, we display the user subscription
                                    if (is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' ) && rcp_get_subscription( get_queried_object_id() ) != null ){
                                        echo rcp_get_subscription( get_queried_object_id() );
                                    } 
                                    //If not, we display the WP roles
                                    else{
                                        echo implode(', ', $userData->roles);
                                    }
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="username-user center-container flex-item">
                    <div class="txt-info center-item">
                        <ul>
                            <?php 
                            if( get_the_author_meta( 'year_of_birth', $idUser ) && get_the_author_meta( 'year_of_birth', $idUser ) != 'none' ){ ?>
                                <li>
                                    <span class="dashicons dashicons-info"></span>
                                    <?php echo __('Year of birth : ','open-badges-framework') . get_the_author_meta( 'year_of_birth', $idUser ); ?>
                                </li>
                            <?php }
                            if( get_the_author_meta( 'country', $idUser ) && get_the_author_meta( 'country', $idUser ) != 'none' && get_the_author_meta( 'city', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-flag"></span>
                                    <?php echo get_the_author_meta( 'country', $idUser ) . ' - ' . get_the_author_meta( 'city', $idUser ); ?>
                                </li>
                            <?php } else if( ( get_the_author_meta( 'country', $idUser ) && get_the_author_meta( 'country', $idUser ) != 'none' ) || get_the_author_meta( 'city', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-flag"></span>
                                    <?php echo get_the_author_meta( 'country', $idUser ) . get_the_author_meta( 'city', $idUser ); ?>
                                </li>
                            <?php }
                            if( get_the_author_meta( 'mother_tongue', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-translation"></span>
                                    <?php echo __('Mother tongue : ','open-badges-framework') . get_the_author_meta( 'mother_tongue', $idUser ); ?>
                                </li>
                            <?php }
                            if( get_the_author_meta( 'primary_degree', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-info"></span>
                                    <?php echo get_the_author_meta( 'primary_degree', $idUser );
                                    if( !empty( get_the_author_meta( 'secondary_degree', $idUser ) ) ){
                                        echo ' - ' . get_the_author_meta( 'secondary_degree', $idUser );
                                    }
                                    if( !empty( get_the_author_meta( 'tertiary_degree', $idUser ) ) ){
                                        echo ' - ' . get_the_author_meta( 'tertiary_degree', $idUser );
                                    } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Display for phone screens -->
            <div class="user-info-admin flex-container user-info-little-screen">
                <div class="username-user center-container flex-item">
                    <div class="txt-info center-item">
                        <ul>
                            <li>
                                <span class="dashicons dashicons-admin-users"></span>
                                <?php echo $userData->display_name; ?>
                            </li>
                            <li>
                                <span class="dashicons dashicons-calendar"></span>
                                <span> <?php _e('Member since: ','open-badges-framework'); echo date("d M Y", strtotime($userData->user_registered)); ?></span>
                            </li>
                            <li>
                                <span class="dashicons dashicons-email-alt"></span>
                                <?php echo $userData->user_email; ?>
                            </li>
                            <li>
                                <span class="dashicons dashicons-admin-tools"></span>
                                <?php echo implode(', ', $userData->roles); ?>
                            </li>
                            <?php 
                            if( get_the_author_meta( 'year_of_birth', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-info"></span>
                                    <?php echo __('Year of birth : ','open-badges-framework') . get_the_author_meta( 'year_of_birth', $idUser ); ?>
                                </li>
                            <?php }
                            if( get_the_author_meta( 'country', $idUser ) != 'none' && get_the_author_meta( 'city', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-flag"></span>
                                    <?php echo get_the_author_meta( 'country', $idUser ) . ' - ' . get_the_author_meta( 'city', $idUser ); ?>
                                </li>
                            <?php } else if( get_the_author_meta( 'country', $idUser ) != 'none' || get_the_author_meta( 'city', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-flag"></span>
                                    <?php echo get_the_author_meta( 'country', $idUser ) . get_the_author_meta( 'city', $idUser ); ?>
                                </li>
                            <?php }
                            if( get_the_author_meta( 'mother_tongue', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-translation"></span>
                                    <?php echo __('Mother tongue : ','open-badges-framework') . get_the_author_meta( 'mother_tongue', $idUser ); ?>
                                </li>
                            <?php }
                            if( get_the_author_meta( 'primary_degree', $idUser ) ){ ?>
                                <li>
                                    <span class="dashicons dashicons-info"></span>
                                    <?php echo get_the_author_meta( 'primary_degree', $idUser );
                                    if( !empty( get_the_author_meta( 'secondary_degree', $idUser ) ) ){
                                        echo ' - ' . get_the_author_meta( 'secondary_degree', $idUser );
                                    }
                                    if( !empty( get_the_author_meta( 'tertiary_degree', $idUser ) ) ){
                                        echo ' - ' . get_the_author_meta( 'tertiary_degree', $idUser );
                                    } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <?php
            $theme = wp_get_theme(); // gets the current theme
            if ( 'Listify' == $theme->name || 'Listify' == $theme->parent_theme ) {
                if( !empty( $userData->user_url ) || !empty( get_the_author_meta( 'facebook', $idUser ) ) || !empty( get_the_author_meta( 'twitter', $idUser ) ) || !empty( get_the_author_meta( 'googleplus', $idUser ) ) || !empty( get_the_author_meta( 'pinterest', $idUser ) ) || !empty( get_the_author_meta( 'linkedin', $idUser ) ) || !empty( get_the_author_meta( 'github', $idUser ) ) || !empty( get_the_author_meta( 'instagram', $idUser ) ) ){
                ?>
                    <!-- User Social Links -->
                    <h2 class="social-links-title"><?php _e('Find me on :','open-badges-framework'); ?></h2>
                    <div class="user-info-admin flex-container">
                        <div class="username-user center-container flex-item">
                            <div class="txt-info center-item">
                                <ul>
                                    <?php
                                    if( !empty( $userData->user_url ) ){ ?>
                                        <li>
                                            <span class="dashicons dashicons-admin-site"></span>
                                            <?php echo '<a href="<?php echo $userData->user_url; ?>">Website</a>'; ?>
                                        </li>
                                    <?php }
                                    if( !empty( get_the_author_meta( 'facebook', $idUser ) ) ){ ?>
                                        <li>
                                            <span class="dashicons dashicons-facebook"></span>
                                            <?php echo '<a href="'. get_the_author_meta( 'facebook', $idUser ) .'">Facebook</a>'; ?>
                                        </li>
                                    <?php }
                                    if( !empty( get_the_author_meta( 'twitter', $idUser ) ) ){ ?>
                                        <li>
                                            <span class="dashicons dashicons-twitter"></span>
                                            <?php echo '<a href="'. get_the_author_meta( 'twitter', $idUser ) .'">Twitter</a>'; ?>
                                        </li>
                                    <?php }
                                    if( !empty( get_the_author_meta( 'googleplus', $idUser ) ) ){ ?>
                                        <li>
                                            <span class="dashicons dashicons-googleplus"></span>
                                            <?php echo '<a href="'. get_the_author_meta( 'googleplus', $idUser ) .'">Google+</a>'; ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="username-user center-container flex-item">
                            <div class="txt-info center-item">
                                <ul>
                                    <?php
                                    if( !empty( get_the_author_meta( 'pinterest', $idUser ) ) ){ ?>
                                        <li>
                                            <span class="dashicons dashicons-share"></span>
                                            <?php echo '<a href="'. get_the_author_meta( 'pinterest', $idUser ) .'">Pinterest</a>'; ?>
                                        </li>
                                    <?php }
                                    if( !empty( get_the_author_meta( 'linkedin', $idUser ) ) ){ ?>
                                        <li>
                                            <span class="dashicons dashicons-admin-links"></span>
                                            <?php echo '<a href="'. get_the_author_meta( 'linkedin', $idUser ) .'">LinkedIn</a>'; ?>
                                        </li>
                                    <?php }
                                    if( !empty( get_the_author_meta( 'github', $idUser ) ) ){ ?>
                                        <li>
                                            <span class="dashicons dashicons-businessman"></span>
                                            <?php echo '<a href="'. get_the_author_meta( 'github', $idUser ) .'">GitHub</a>'; ?>
                                        </li>
                                    <?php }
                                    if( !empty( get_the_author_meta( 'instagram', $idUser ) ) ){ ?>
                                        <li>
                                            <span class="dashicons dashicons-camera"></span>
                                            <?php echo '<a href="'. get_the_author_meta( 'instagram', $idUser ) .'">Instagram</a>'; ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                if( !empty( $userData->user_url ) ){ ?>
                    <h2 class="social-links-title"><?php _e('Find me on my ','open-badges-framework'); echo '<a href="<?php echo $userData->user_url; ?>">website</a>.'; ?></h2>
                <?php }
            }

            
            
            if ($userData->ID == wp_get_current_user()->ID) {
                if ( esc_url( get_permalink( $rcp_options['edit_profile'] ) ) && is_plugin_active('restrict-content-pro/restrict-content-pro.php') ) {
                    ?>
                    <div class="btn-update-container" style="text-align: center;">
                        <a href="<?php echo esc_url(get_permalink($rcp_options['edit_profile'])); ?>"
                           class="btn btn-secondary"><?php _e('Edit your profile','open-badges-framework'); ?></a>
                    </div>
                    <?php
                }
            } ?>
        </section>
        <?php
        self::showBadgeEarned($userData->ID, $isAdmin);
    }

    /**
     * Show all the information about the user badges.
     *
     * @param int  $idUser
     * @param bool $isAdmin
     *
     * @return void
     */
    public static function showBadgeEarned($idUser, $isAdmin = true) {
        $userDb = DbUser::getSingle(["idWP" => $idUser]);
		
		if (!$userDb){
			$dbBadges = null;
			
		}else{
			$dbBadges = DbBadge::get(Array("idUser" => $userDb->id));
		}
        
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
        $toAccept = 0;
        ?>
        <section class="user-badges-cont">
            <div class="user-badges flex-container">
                <div class="title-badges-cont">
                    <h3><?php _e('Badges earned','open-badges-framework'); ?> &nbsp;<span class="dashicons dashicons-yes"></span></h3>
                </div>
                <?php
                if ($dbBadges) {
                    foreach ($dbBadges as $dbBadge) {
                        $badge = new Badge();
                        $badge->retrieveBadge($dbBadge->id);
                        $badgeWP = WPBadge::get($badge->idBadge);
                        if (!$badge->gotDate) $toAccept = 1;
                        if ($badge->gotDate) {

                            ?>
                            <div class="badge flex-item <?php echo !$isAdmin ? "badge-earned" : ""; ?>"
                                 data-id="<?php echo "" . $badge->id; ?>">
                                <a class="wrap-link" <?php
                                if ($isAdmin) {
                                    echo "href='" . admin_url('admin.php?page=' . Admin::PAGE_SINGLE_BADGES, $protocol) . "&badge=" . $badge->id . "&db=1'";
                                } ?>">
                                <div class="cont-img-badge">
                                    <img class="circle-img"
                                         src="<?php echo WPBadge::getUrlImage($badge->idBadge); ?>">
                                </div>
                                <div>
                                    <span><?php echo $badgeWP->post_title; ?></span>
                                </div>
                                </a>
                            </div>
                            <?php
                        }
                    }
                } else {
					?>
                     <p class='lead'><br/>&nbsp;&nbsp;&nbsp;&nbsp; <?php _e('No badges earned','open-badges-framework');?></p>
					 <?php
                }
                ?>
            </div>
            <?php
            if ($toAccept) { ?>
                <div class="obf-badges-to-accept user-badges flex-container">
                    <div class="title-badges-cont">
                        <h4><?php _e('To be accepted','open-badges-framework'); ?></h4>
                    </div>
                    <?php
                    foreach ($dbBadges as $dbBadge) {
                        $badge = new Badge();
                        $badge->retrieveBadge($dbBadge->id);
                        $badgeWP = WPBadge::get($badge->idBadge);
                        if (!$badge->gotDate) {
                            ?>
                            <div class="badge flex-item <?php echo !$isAdmin ? "badge-earned" : ""; ?>"
                                 data-id="<?php echo "" . $badge->id; ?>">
                                <a class="wrap-link" <?php
                                if ($isAdmin) {
                                    echo "href='" . admin_url('admin.php?page=' . Admin::PAGE_SINGLE_BADGES, $protocol) . "&badge=" . $badge->id . "&db=1'";
                                } ?>">
                                <div class="cont-img-badge">
                                    <img class="circle-img"
                                         src="<?php echo WPBadge::getUrlImage($badge->idBadge); ?>">
                                </div>
                                <div>
                                    <span><?php echo $badgeWP->post_title; ?></span>
                                </div>
                                </a>
                            </div>
                            <?php
                        }
                    } ?>
                </div>
                <?php

            }
            ?>

        </section>
        <!-- The Modal -->
        <div id="modalShowBadge" class="modal">
            <!-- Modal content -->
            <div id="responseSent" class="modal-content obf-sbp-conatiner-badge"></div>
        </div>
        <?php
    }
}

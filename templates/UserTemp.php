<?php

namespace templates;

use Inc\Base\BaseController;
use Inc\Base\User;

/**
 * Template for the Dashboard page.
 *
 * Provide an admin area view. This file is used to
 * markup the admin-facing aspects of the plugin.
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class UserTemp extends BaseController {

    public function main() {
        $user = User::getCurrentUser();
        $urlImg = esc_url( get_avatar_url( $user->ID ) );
        $userdata = get_userdata($user->ID);
        ?>
        <div class="wrap">
            <h1 class="obf-title">User
            </h1>
            <section>
                <div class="user-info-admin flex-container">
                    <div class="img-user flex-item">
                        <img class="circle-img" src="<?php echo $urlImg; ?>">
                    </div>
                    <div class="username-user center-container flex-item">
                        <div class="center-item">
                            <h2><?php echo $user->user_login; ?></h2>
                            <span>Registered the <?php echo date( "M Y", strtotime( $userdata->user_registered )); ?></span>
                        </div>
                    </div>
                </div>
                <div class="user-badges flex-container">

                </div>
            </section>
            <section class="user-badges-cont">
                <div class="title-badges-cont">
                    <h3>Badges</h3>
                </div>
                <div class="user-badges flex-container">
                    <div class="badge flex-item">

                    </div>
                </div>
            </section>
        </div>
        <?php
    }
}
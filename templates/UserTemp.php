<?php

namespace templates;

use Inc\Base\User;
use Inc\Database\DbBadge;
use Inc\Pages\Admin;
use Inc\Utils\Badges;

/**
 *
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class UserTemp{

    public function main() {
        $user = User::getCurrentUser();
        $urlImg = esc_url(get_avatar_url($user->ID));
        $userData = get_userdata($user->ID);
        $dbBadges = DbBadge::get(Array("UserEmail" => $userData->user_email));
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
        ?>
        <div class="wrap">
            <h1 class="obf-title">User</h1>
            <section>
                <div class="user-info-admin flex-container">
                    <div class="img-user flex-item">
                        <img class="circle-img" src="<?php echo $urlImg; ?>">
                    </div>
                    <div class="username-user center-container flex-item">
                        <div class="center-item">
                            <h2><?php echo $user->user_login; ?></h2>
                            <span>Registered the <?php echo date("M Y", strtotime($userData->user_registered)); ?></span>
                        </div>
                    </div>
                </div>
            </section>
            <section class="user-badges-cont">
                <div class="title-badges-cont">
                    <h3>Badges earned</h3>
                </div>
                <div class="user-badges flex-container">
                    <?php
                    foreach ($dbBadges as $dbBadge) { ?>
                        <div class="badge flex-item">
                            <a class="wrap-link" href="<?php echo admin_url('admin.php?page=' . Admin::PAGE_SINGLE_BADGES, $protocol) . "&badge=$dbBadge->id&db=1"; ?>">
                            <div class="cont-img-badge">
                                <img class="circle-img" src="<?php echo Badges::getImage($dbBadge->badgeId); ?>">
                            </div>
                            <div>
                                <span><?php echo Badges::get($dbBadge->badgeId)->post_title; ?></span>
                            </div>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </section>
        </div>
        <?php
    }
}
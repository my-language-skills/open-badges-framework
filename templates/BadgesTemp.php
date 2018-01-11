<?php

namespace templates;

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
class BadgesTemp {

    public function main() {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
        $badges = Badges::getAll();
        ?>
        <div class="wrap">
            <h1 class="obf-title">Badges</h1>

        <section class="user-badges-cont">
            <div class="title-badges-cont">
                <h3>Number badges: <?php echo sizeof($badges)?></h3>
            </div>
            <div class="user-badges flex-container">
                <?php


                foreach ($badges as $badge) { ?>
                    <div class="badge flex-item">
                        <a class="wrap-link"
                           href="<?php echo admin_url('admin.php?page=' . Admin::PAGE_SINGLE_BADGES, $protocol) . "&badge=$badge->ID&db=0"; ?>">
                            <div class="cont-img-badge">
                                <img class="circle-img" src="<?php echo Badges::getImage($badge->ID); ?>">
                            </div>
                            <div>
                                <span><?php echo $badge->post_title; ?></span>
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
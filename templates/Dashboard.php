<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     BadgeIssuerForWp
 */

namespace templates;


use Inc\Utils\Statistics;

final class Dashboard {

    public static function main() {
        ?>
        <div class="wrap">
            <h1 class="obf-title">Badge Issuer</h1>
            <?php echo Statistics::getNumOfBadges(); ?>
        </div>
        <?php
    }
}


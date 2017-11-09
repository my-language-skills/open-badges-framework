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

final class DashboardTemp {

    public static function main() {
        ?>
        <div class="wrap">
            <h1 class="obf-title">Badge Issuer</h1>
            <div class="container admin">
                <div class="intro">
                    <div class="cont-title-dash">
                        <h2>Action control</h2>
                        <p class="lead">
                            This is a simple hero unit, a simple jumbotron-style component for calling extra attention
                            to featured content or information.
                        </p>
                    </div>

                    <div class="">
                        <div class="add-new-dash">
                            <div class="col-dash">
                                <h4>Badges</h4>
                            </div>
                            <div class="col-dash">
                                <p>Add New Badge</p>
                            </div>
                        </div>
                        <div class="add-new-dash">
                            <div class="col-dash">
                                <h4>Fields</h4>
                            </div>
                            <div class="col-dash">
                                <p>Add New Field</p>
                            </div>
                        </div>
                        <div class="add-new-dash">
                            <div class="col-dash">
                                <h4>Levels</h4>
                            </div>
                            <div class="col-dash">
                                <p>Add New Level</p>
                            </div>
                        </div>
                        <div class="add-new-dash">
                            <div class="col-dash">
                                <h4>Classes</h4>
                            </div>
                            <div class="col-dash">
                                <p>Add New Class</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
}


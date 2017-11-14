<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the
 * admin-facing aspects of the plugin.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace templates;

use Inc\Pages\Admin;
use Inc\Base\BaseController;

final class DashboardTemp extends BaseController {

    public function main() {
        ?>
        <div class="wrap">
        <h1 class="obf-title">Open Badge <small>Framework</small></h1>
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
                    <div class="row-dash">
                        <div class="col-dash">
                            <h4>Badges</h4>
                        </div>
                        <div class="col-dash">
                            <div class="vert-hr" ></div>
                        </div>
                        <div class="col-dash">
                            Number of badge:
                            <span class="number-stc"><?php echo wp_count_posts(Admin::POST_TYPE_BADGES)->publish; ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit.php?post_type=" . Admin::POST_TYPE_BADGES); ?>"
                               class="manage-link">Manage</a>
                        </div>
                    </div>
                    <div class="row-dash">
                        <div class="col-dash">
                            <h4>Fields</h4>
                        </div>
                        <div class="col-dash">
                            <div class="vert-hr" ></div>
                        </div>
                        <div class="col-dash">
                            Number of Fields:
                            <span class="number-stc"><?php echo wp_count_terms(Admin::TAX_FIELDS); ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit-tags.php?taxonomy=" . Admin::TAX_FIELDS . "&post_type=" . Admin::POST_TYPE_BADGES); ?>"
                               class="manage-link">Manage</a>
                        </div>
                    </div>
                    <div class="row-dash">
                        <div class="col-dash">
                            <h4>Levels</h4>
                        </div>
                        <div class="col-dash">
                            <div class="vert-hr" ></div>
                        </div>
                        <div class="col-dash">
                            Number of Levels:
                            <span class="number-stc"><?php echo wp_count_terms(Admin::TAX_LEVELS); ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit-tags.php?taxonomy=" . Admin::TAX_LEVELS . "&post_type=" . Admin::POST_TYPE_BADGES); ?>"
                               class="manage-link">Manage</a>
                        </div>
                    </div>
                    <?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    if(is_plugin_active("WP-Job-Manager-master/wp-job-manager.php")) { ?>

                    <div class="row-dash">
                        <div class="col-dash">
                            <h4>Classes</h4>
                        </div>
                        <div class="col-dash">
                            <div class="vert-hr" ></div>
                        </div>
                        <div class="col-dash">
                            Number of Classes:
                            <span class="number-stc"><?php echo wp_count_posts(Admin::POST_TYPE_CLASS_JL)->publish; ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit.php?post_type=" . Admin::POST_TYPE_CLASS_JL); ?>"
                               class="manage-link">Manage</a>
                        </div>
                    </div>

                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
}


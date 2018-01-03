<?php

namespace templates;

use Inc\Base\Secondary;
use Inc\Database\DbBadge;
use Inc\Pages\Admin;
use Inc\Base\BaseController;
use Inc\Utils\DisplayFunction;
use Inc\Utils\Statistics;

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
final class DashboardTemp extends BaseController {

    /**
     * First function that show the template.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public static function main() {
        ?>
        <div class="wrap">
            <h1 class="obf-title">Open Badge Framework
            </h1>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-1">Action</a></li>
                <li class=""><a href="#tab-2">Badges</a></li>
                <li class=""><a href="#tab-3">About</a></li>
            </ul>

            <div class="tab-content-page">
                <div id="tab-1" class="tab-pane active">
                    <?php self::actionTab(); ?>
                </div>
                <div id="tab-2" class="tab-pane">
                    <?php self::badgesTab(); ?>
                </div>
                <div id="tab-3" class="tab-pane">
                    <?php self::aboutTab(); ?>
                </div>
            </div>
        </div>

        <?php
    }

    /**
     * The action tab, loaded as a first tab.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public static function actionTab() {
        ?>
        <div class="container admin">
            <div class="intro-dash">
                <div class="cont-title-dash">
                    <h2>Action control</h2>
                    <p class="lead">
                        Here you can have the possibility to see all the custom post type and taxonomies that permit you
                        to manage the badges information.
                    </p>
                </div>

                <div class="content-dash">
                    <div class="row-dash">
                        <div class="col-dash">
                            <h4>Badges</h4>
                        </div>
                        <div class="col-dash">
                            <div class="vert-hr"></div>
                        </div>
                        <div class="col-dash">
                            Number of badge:
                            <span class="number-stc"><?php echo Statistics::getNumberPost(Admin::POST_TYPE_BADGES); ?></span>
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
                            <div class="vert-hr"></div>
                        </div>
                        <div class="col-dash">
                            Number of Fields:
                            <span class="number-stc"><?php echo Statistics::getNumberTerm(Admin::TAX_FIELDS); ?></span>
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
                            <div class="vert-hr"></div>
                        </div>
                        <div class="col-dash">
                            Number of Levels:
                            <span class="number-stc"><?php echo Statistics::getNumberTerm(Admin::TAX_LEVELS); ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit-tags.php?taxonomy=" . Admin::TAX_LEVELS . "&post_type=" . Admin::POST_TYPE_BADGES); ?>"
                               class="manage-link">Manage</a>
                        </div>
                    </div>
                    <?php
                    if (Secondary::isJobManagerActive()) {
                        ?>

                        <div class="row-dash">
                            <div class="col-dash">
                                <h4>Classes</h4>
                            </div>
                            <div class="col-dash">
                                <div class="vert-hr"></div>
                            </div>
                            <div class="col-dash">
                                Number of Classes:
                                <span class="number-stc"><?php echo Statistics::getNumberPost(Admin::POST_TYPE_CLASS_JL); ?></span>
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

    /**
     * The badges tab.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public static function badgesTab() {

        ?>
        <div class="container admin">
            <div class="intro-dash">
                <div class="cont-title-dash">
                    <h2>Badges list</h2>
                    <p class="lead">
                        In this section we have the possibility to see all the badges that are sent.

                </div>
                <div id="form-badges-list" class="content-dash badges-list-dash">
                    <?php DisplayFunction::badgesTable();?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * The about tab.
     *
     * @author      Alessandro RICCARDI
     * @since       1.0.0
     */
    public static function aboutTab() {
        ?>
        <div class="container admin">
            <div class="intro-dash">
                <div class="cont-title-dash">
                    <h2>About us</h2>
                    <p class="lead">
                        The main functionality of this plugin is to create, manage and send badges. <br>
                        Once a badge has been sent, the receiver has the possibility to take his badge through a link
                        that is inserted in the email.<br>
                        After a succession of steps, the user can get his own badge also having the possibility to send
                        it to the <a href="https://openbadges.org/">Mozilla Open Badges</a> platform.
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}


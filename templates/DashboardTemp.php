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
 * All the content to show in the front-end is wrapped in the __() function
 * for internationalization purposes
 *
 * @author      @AleRiccardi
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
final class DashboardTemp extends BaseController {

    /**
     * First function that show the template.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public static function main() {
        ?>
        <div class="wrap">
            <h1><?php _e('Open Badges Framework','open-badges-framework'); ?></h1>

            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-1"><?php _e('Action','open-badges-framework'); ?></a></li>
                <li class=""><a href="#tab-2"> <?php _e('Badges','open-badges-framework'); ?></a></li>
                <li class=""><a href="#tab-3"> <?php _e('Teachers','open-badges-framework'); ?></a></li>
            </ul>

            <div class="tab-content-page">
                <div id="tab-1" class="tab-pane active">
                    <?php self::actionTab(); ?>
                </div>
                <div id="tab-2" class="tab-pane">
                    <?php self::badgesTab(); ?>
                </div>
                <div id="tab-3" class="tab-pane">
                    <?php self::teachersTab(); ?>
                </div>

            </div>
        </div>

        <?php
    }

    /**
     * The action tab, loaded as a first tab.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public static function actionTab() {
        ?>
        <div class="container admin">
            <div class="intro-dash">
                <div class="cont-title-dash">
                    <h3 style="margin-bottom: 0px;"><?php _e('Statistics','open-badges-framework'); ?></h3>
                    <p style="margin-bottom: 0px; margin-top: 0px;">
                        <?php _e('Here you can see the statistics of the Badges.','open-badges-framework'); ?>
                    </p>
                </div>

                <div class="content-dash">
                    <div class="row-dash">
                        <div class="col-dash">
                            <h3><?php _e('Badges','open-badges-framework'); ?></h3>
                        </div>
                        <div class="col-dash">
                            <div class="vert-hr"></div>
                        </div>
                        <div class="col-dash">
                            <?php _e('Number of badge:','open-badges-framework'); ?>
                            <span class="number-stc"><?php echo Statistics::getNumberPost(Admin::POST_TYPE_BADGES); ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit.php?post_type=" . Admin::POST_TYPE_BADGES); ?>"
                               class="manage-link"><?php _e('Manage','open-badges-framework'); ?></a>
                        </div>
                    </div>
                    <div class="row-dash">
                        <div class="col-dash">
                            <h3><?php _e('Fields of education','open-badges-framework'); ?></h3>
                        </div>
                        <div class="col-dash">
                            <div class="vert-hr"></div>
                        </div>
                        <div class="col-dash">
                            <?php _e('Number of Fields:','open-badges-framework'); ?>
                            <span class="number-stc"><?php echo Statistics::getNumberTerm(Admin::TAX_FIELDS); ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit-tags.php?taxonomy=" . Admin::TAX_FIELDS . "&post_type=" . Admin::POST_TYPE_BADGES); ?>"
                               class="manage-link"><?php _e('Manage','open-badges-framework'); ?></a>
                        </div>
                    </div>
                    <div class="row-dash">
                        <div class="col-dash">
                            <h3><?php _e('Levels','open-badges-framework'); ?></h3>
                        </div>
                        <div class="col-dash">
                            <div class="vert-hr"></div>
                        </div>
                        <div class="col-dash">
                            <?php _e('Number of Levels','open-badges-framework'); ?>
                            <span class="number-stc"><?php echo Statistics::getNumberTerm(Admin::TAX_LEVELS); ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit-tags.php?taxonomy=" . Admin::TAX_LEVELS . "&post_type=" . Admin::POST_TYPE_BADGES); ?>"
                               class="manage-link"><?php _e('Manage','open-badges-framework'); ?></a>
                        </div>
                    </div>
                    <?php
                    if (Secondary::isJobManagerActive()) {
                        ?>

                        <div class="row-dash">
                            <div class="col-dash">
                                <h4><?php _e('Classes','open-badges-framework'); ?></h4>
                            </div>
                            <div class="col-dash">
                                <div class="vert-hr"></div>
                            </div>
                            <div class="col-dash">
                                <?php _e('Number of Classes:','open-badges-framework'); ?>
                                <span class="number-stc"><?php echo Statistics::getNumberPost(Admin::POST_TYPE_CLASS_JL); ?></span>
                            </div>
                            <div class="col-dash">
                                <a href="<?php echo admin_url("edit.php?post_type=" . Admin::POST_TYPE_CLASS_JL); ?>"
                                   class="manage-link"><?php _e('Manage','open-badges-framework'); ?></a>
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
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */
    public static function badgesTab() {

        ?>
        <div class="container admin">
            <div class="intro-dash">
                <div class="cont-title-dash">
                    <h3 style="margin-bottom: 0px;"><?php _e('Badges list','open-badges-framework'); ?></h3>
                    <p style="margin-bottom: 0px; margin-top: 0px;"><?php _e('In this section you can see all sent badges.','open-badges-framework'); ?></p>
                </div>
                <div id="form-badges-list" class="content-dash badges-list-dash">
                    <?php DisplayFunction::badgesTable(); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * The about tab.
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @return void
     */

    public static function teachersTab() {

        ?>
        <div class="container admin">
            <div class="intro-dash">
                <div class="cont-title-dash">
                    <h3 style="margin-bottom: 0px;"><?php _e('Teachers list','open-badges-framework'); ?></h3>
                    <p style="margin-bottom: 0px; margin-top: 0px;">
                        <?php _e('In this section you can see the number of badges sent by teachers.','open-badges-framework'); ?>
                    </p>
                </div>
                <div id="form-badges-list" class="content-dash badges-list-dash">
                    <!--?php DisplayFunction::badgesTable(); ?> -->
					<?php DisplayFunction::usersTable(); ?>
                </div>
            </div>
        </div>
        <?php
    }



}

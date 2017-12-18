<?php

namespace templates;

use Inc\Database\DbBadge;
use Inc\Pages\Admin;
use Inc\Base\BaseController;
use inc\Utils\Statistics;

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
     * This is the function that is typically loaded at the beginning.
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
                            <span class="number-stc"><?php echo Statistics::getNumberPostOrTerm(Admin::POST_TYPE_BADGES); ?></span>
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
                            <span class="number-stc"><?php echo Statistics::getNumberPostOrTerm(Admin::TAX_FIELDS); ?></span>
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
                            <span class="number-stc"><?php echo Statistics::getNumberPostOrTerm(Admin::TAX_LEVELS); ?></span>
                        </div>
                        <div class="col-dash">
                            <a href="<?php echo admin_url("edit-tags.php?taxonomy=" . Admin::TAX_LEVELS . "&post_type=" . Admin::POST_TYPE_BADGES); ?>"
                               class="manage-link">Manage</a>
                        </div>
                    </div>
                    <?php include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                    if (is_plugin_active("WP-Job-Manager-master/wp-job-manager.php")) { ?>

                        <div class="row-dash">
                            <div class="col-dash">
                                <h4>Classes</h4>
                            </div>
                            <div class="col-dash">
                                <div class="vert-hr"></div>
                            </div>
                            <div class="col-dash">
                                Number of Classes:
                                <span class="number-stc"><?php echo Statistics::getNumberPostOrTerm(Admin::POST_TYPE_CLASS_JL); ?></span>
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
        $table = DbBadge::getKeys();
        ?>
        <div class="container admin">
            <div class="intro-dash">
                <div class="cont-title-dash">
                    <h2>Badges list</h2>
                    <p class="lead">
                        In this section we have the possibility to see all the badges that are sent.
                        <?php
                        $all = DbBadge::getAll();
                        $numBadges = count($all);
                        ?>
                    <p>SENT: <?php echo $numBadges ?> –  GOT: <?php echo DbBadge::getNumGot();?> – GOT MOB: <?php echo DbBadge::getNumGotMob();?>
                    </p>
                </div>
                <div class="content-dash badges-list-dash">
                    <?php
                    if ($table) {

                        ?>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <?php
                                foreach ($table as $key => $value) { ?>
                                    <th scope="col"><?php echo $key; ?></th>
                                    <?php
                                }
                                ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            $table = DbBadge::getAll();
                            foreach ($table as $row) {
                                echo "<tr>";
                                echo "<th scope='row'>" . $i++ . "</th>";

                                foreach ($row as $key => $value) { ?>
                                    <td>
                                        <?php
                                        if ($key == "badgeId") {
                                            echo "<a href='" . get_edit_post_link($value) . "'>$value</a>";
                                        } else if ($key == "fieldId") {
                                            echo "<a href='" . get_edit_term_link($value) . "'>$value</a>";
                                        } else if ($key == "levelId") {
                                            echo "<a href='" . get_edit_term_link($value) . "'>$value</a>";
                                        } else if ($key == "classId") {
                                            echo "<a href='" . get_edit_post_link($value) . "'>$value</a>";
                                        } else if ($key == "teacherId") {
                                            echo "<a href='" . get_edit_user_link($value) . "'>$value</a>";
                                        } else {
                                            echo $value;
                                        }
                                        ?>
                                    </td>
                                    <?php
                                }
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
                        echo "<p>No badge sent. Click <a href='" . admin_url('admin.php?page=' . Admin::PAGE_SEND_BADGE, $protocol) . "'>here</a> to send a badge.</p>";
                    }
                    ?>
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
                        Fill information about the plugin.
                    </p>
                </div>
                <div class="content-dash">
                    <p>Random text.</p>
                </div>
            </div>
        </div>
        <?php
    }
}


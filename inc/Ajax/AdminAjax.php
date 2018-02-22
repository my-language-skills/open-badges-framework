<?php

namespace Inc\Ajax;

use Inc\Base\BaseController;
use Inc\Database\DbBadge;
use Inc\Utils\DisplayFunction;
use Inc\Utils\JsonManagement;
use templates\SingleBadgeTemp;

/**
 * AdminAjax class that contain all the the general ajax function.
 * This functions is initialized from the InitAjax Class.
 *
 * @author      @AleRiccardi
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class AdminAjax extends BaseController {

    /**
     * Show the table about all sent badges.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     */
    public function ajaxShowBadgesTable() {
        DisplayFunction::badgesTable();
        wp_die();
    }

    /**
     * Delete a specific badge throw the id.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     */
    public function ajaxDeleteBadge() {
        $ids = $_POST['ids'];
        $badges = array();

        foreach ($ids as $id) {
            $badges[] = DbBadge::getById($id);
        }

        foreach ($badges as $badge) {
            echo JsonManagement::deleteJson($badge->json);
            echo DbBadge::deleteById(array('id' => $badge->id));
        }

        wp_die();
    }

    /**
     * Show the information about a specific badge that
     * a user earned.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     */
    public function ajaxShowBadgeEarned() {
        $id = $_POST['id'];
        SingleBadgeTemp::showDatabaseBadge($id);

        wp_die();
    }
}
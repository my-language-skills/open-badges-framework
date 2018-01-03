<?php

namespace Inc\Ajax;

use Inc\Base\BaseController;
use Inc\Database\DbBadge;
use Inc\Utils\DisplayFunction;
use Inc\Utils\JsonManagement;

/**
 *
 *
 * @author      Alessandro RICCARDI
 * @since       1.0.0
 *
 * @package     OpenBadgesFramework
 */
class AdminAjax extends BaseController {

    /**
     *
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     */
    public function ajaxShowBadgesTable() {
        echo DisplayFunction::badgesTable();

        wp_die();
    }

    /**
     *
     *
     * @author Alessandro RICCARDI
     * @since  1.0.0
     */
    public function ajaxDeleteBadge() {
        $ids = $_POST['ids'];
        $badges = DbBadge::getById($ids);
        foreach ($badges as $badge) {
            echo JsonManagement::deleteJson($badge->json);
            echo DbBadge::deleteById(array('id' => $badge->id));
        }

        wp_die();
    }

}
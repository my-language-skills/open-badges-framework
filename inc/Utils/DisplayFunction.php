<?php

namespace Inc\Utils;

use Inc\Base\Secondary;
use Inc\Database\DbBadge;
use Inc\Database\DbUser;
use Inc\Pages\Admin;

/**
 * Contain all the function to show some information.
 * I created that class because in the last version of the
 * plugin there was a php file with all function like this,
 * so this still survive right now because I didn't have
 * time to fix that but the purpose for the future is to
 * delete that and create it better.
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */
class DisplayFunction {

    /**
     * Displays available FIELD in a select tag. Used in the forms sending badges to students.
     *
     * @author Nicolas TORION
     * @since  0.6.1
     * @since  0.6.3 recreated the function more simply
     * @since  x.x.x
     *
     * @param string $p_parent permit to display the child taxonomy of the parent taxonomy (category).
     */
    public static function field($p_parent = "") {
        $fieldsInstance = new WPField();

        $selectionContOpen = '<div class="select-field"> <select name="field" id="field"> <option value="Select" selected disabled hidden>Select</option>';
        $selectionContClose = '</select></div>';

        if (!$fieldsInstance->haveChildren()) {
            $languages = $fieldsInstance->main;

            echo $selectionContOpen;

            foreach ($languages as $language) {
                echo '<option value="' . $language->term_id . '">';
                echo $language->name . '</option>';
            }

            echo $selectionContClose;

        } else {
            //If there parent with children
            if ($p_parent === "") {
                // Display the DEFAULT parent
                $parents = $fieldsInstance->sub;
                echo $selectionContOpen;

                foreach ($parents as $parent) {
                    foreach ($parent as $language) {
                        echo '<option value="' . $language->term_id . '">';
                        echo $language->name . '</option>';
                    }
                    break;
                }
                echo $selectionContClose;

            } else if ($p_parent === "all_field") {
                // Display ALL the child
                $parents = $fieldsInstance->sub;

                echo $selectionContOpen;

                foreach ($parents as $parent) {
                    foreach ($parent as $language) {
                        echo '<option value="' . $language->term_id . '">';
                        echo $language->name . '</option>';
                    }
                }
                echo $selectionContClose;

            } else {
                // Display the children of the right PARENT
                $parents = $fieldsInstance->sub;

                echo $selectionContOpen;
                foreach ((array)$parents[$p_parent] as $language) {
                    echo '<option value="' . $language->term_id . '">';
                    echo $language->name . '</option>';
                }

                echo $selectionContClose;
            }
        }
    }

    /**
     * @return void
     */
    public static function badgesTable() {
        $table = DbBadge::get();
        if ($table) {

            ?>
            <form id="badges-list" method="post">

                <?php self::showActionSection("2"); ?>

                <div class="scroll-hor">
                    <table class="wp-list-table widefat striped pages">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Badge</th>
                            <th scope="col">Field</th>
                            <th scope="col">Level</th>
                            <?php if (Secondary::isJobManagerActive()) { ?>
                                <th scope="col">Class</th>
                            <?php } ?>
                            <th scope="col">Teacher</th>
                            <th scope="col">Creation</th>
                            <th scope="col">Got</th>
                            <th scope="col">Mozilla OB</th>
                            <?php /*
                            foreach ($table as $key => $value) { ?>
                                <th scope="col"><?php echo $key; ?></th>
                                <?php
                            }*/
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($table = DbBadge::get()) {
                            foreach ($table as $row) {
                                $badge = new Badge();
                                $badge->retrieveBadge($row->id);
                                $student = DbUser::getById($badge->getIdUser());

                                echo "<tr>";
                                echo "<td><input id='bd-select-$row->id' type='checkbox' name='badge[]' value='$row->id'></td>";
                                echo "<td>" . (get_user_by('id', $student->idWP) ? "<a href='" . get_edit_user_link(get_user_by('id', $student->idWP)->ID) . "'>" .  $student->email . "</a>" : "<span> $student->email</span>") . "</td>";
                                echo "<td><a href='" . get_edit_post_link($badge->getIdBadge()) . "'>" . (get_post($badge->getIdBadge()) ? get_post($badge->getIdBadge())->post_title : "") . "</a></td>";
                                echo "<td><a href='" . get_edit_term_link($badge->getIdField()) . "'>" . (get_term($badge->getIdField()) ? get_term($badge->getIdField())->name : "") . "</a></td>";
                                echo "<td><a href='" . get_edit_term_link($badge->getIdLevel()) . "'>" . (get_term($badge->getIdLevel()) ? get_term($badge->getIdLevel())->name : "") . "</a></td>";
                                if (Secondary::isJobManagerActive()) {
                                    echo "<td><a href='" . get_edit_post_link($badge->getIdClass()) . "'>" . (get_post($badge->getIdClass()) ? get_post($badge->getIdClass())->post_title : "") . "</a></td>";
                                }
                                echo "<td><a href='" . get_edit_user_link($badge->getIdTeacher()) . "'>" . (get_user_by('id', $badge->getIdTeacher()) ? get_user_by('id', $badge->getIdTeacher())->userEmail : "") . "</a></td>";
                                echo "<td>".$badge->getCreationDate()."</td>";
                                echo "<td>".$badge->getGotDate()."</td>";
                                echo "<td>".$badge->getGotMozillaDate()."</td>";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php self::showActionSection("2"); ?>
            </form>
            <?php
        } else {
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
            echo "<p>No badge sent. Click <a href='" . admin_url('admin.php?page=' . Admin::PAGE_SEND_BADGE, $protocol) . "'>here</a> to send a badge.</p>";
        }

    }

    /**
     * @param string $number
     */
    public static function showActionSection($number = "") { ?>
        <div class="tablenav bottom">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
                <select name="action<?php echo $number; ?>" id="bulk-action-selector-bottom">
                    <option value="-1">Bulk Actions</option>
                    <option value="trash">Move to Trash</option>
                </select>
                <input type="submit" id="doaction<?php echo $number; ?>" class="button action" value="Apply">
            </div>
            <div class="alignleft actions">
            </div>
            <div class="tablenav-pages one-page">
                <span class="displaying-num">
                    <?php echo Statistics::getNumBadgesSent(); ?> items,
                    <?php echo Statistics::getNumBadgesGot(); ?> got,
                    <?php echo Statistics::getNumBadgesGotMob(); ?> mob
                </span>
                <br class="clear">
            </div>
        </div>
        <?php
    }
}
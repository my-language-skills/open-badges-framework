<?php

namespace Inc\Utils;

use Inc\Database\DbBadge;
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
 * @since       1.0.0
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
     * @since  1.0.0
     *
     * @param string $p_parent permit to display the child taxonomy of the parent taxonomy (category).
     */
    public static function field($p_parent = "") {
        $fieldsInstance = new Fields();

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

    public static function badgesTable() {
        $table = DbBadge::getKeys();
        if ($table) {

            ?>
            <p>
                SENT: <?php echo Statistics::getNumBadgesSent(); ?> –
                GOT: <?php echo Statistics::getNumBadgesGot(); ?> –
                GOT MOB: <?php echo Statistics::getNumBadgesGotMob(); ?>
            </p>
            <form id="badges-list" method="post">
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
                    if($table = DbBadge::getAll()) {
                        foreach ($table as $row) {
                            echo "<tr>";
                            echo "<td><input id='bd-select-$row->id' type='checkbox' name='badge[]' value='$row->id'></td>";
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
                    }
                    ?>
                    </tbody>
                </table>
                <div class="alignleft actions bulkactions">
                    <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
                    <select name="action2" id="bulk-action-selector-bottom">
                        <option value="-1">Bulk Actions</option>
                        <option value="trash">Move to Trash</option>
                    </select>
                    <input type="submit" id="doaction2" class="button action" value="Apply">
                </div>
            </form>
            <?php
        } else {
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
            echo "<p>No badge sent. Click <a href='" . admin_url('admin.php?page=' . Admin::PAGE_SEND_BADGE, $protocol) . "'>here</a> to send a badge.</p>";
        }

    }
}
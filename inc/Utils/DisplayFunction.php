<?php
/**
 * The DisplayFunction Class, contain all the function to show
 * some information. I created that class because in the last
 * version of the plugin there was a php file with all function
 * like this, so this still survive right now because I didn't
 * have time to fix that but the purpose for the future is to
 * delete that and create it better.
 *
 *
 * @author      Alessandro RICCARDI
 * @since       x.x.x
 *
 * @package     OpenBadgesFramework
 */

namespace inc\Utils;

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
        $field = new Fields();

        $selectionContOpen = '<div class="select-field"> <select name="field" id="field"> <option value="Select" selected disabled hidden>Select</option>';
        $selectionContClose = '</select></div>';

        if (!$field->haveChildren()) {
            $languages = $field->main;

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
                $parents = $field->sub;
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
                $parents = $field->sub;

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
                $parents = $field->sub;

                echo $selectionContOpen;
                foreach ((array)$parents[$p_parent] as $language) {
                    echo '<option value="' . $language->term_id . '">';
                    echo $language->name . '</option>';
                }

                echo $selectionContClose;

            }

        }
    }

}
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
 * All the content to show in the front-end is wrapped in the __() function
 * for internationalization purposes
 *
 * @author      @AleRiccardi
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

        ?>
        <p>
            <small><?php _e('Some browsers may delay the opening of the fields of education.','open-badges-framework');?></small>
        </p>
        <?php
    }

    /**
     * Show the Table of all the OBF badges.
     *
     * @return void
     */
    public static function badgesTable() {
        $table = DbBadge::get();
        if ($table) {

            ?>
            <form id="badges-list" method="post">

                <?php self::actionSection("2"); ?>

                <div class="scroll-hor">
                    <table class="wp-list-table widefat striped pages">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"><?php _e('User','open-badges-framework');?></th>
                            <th scope="col"><?php _e('Badge','open-badges-framework');?></th>
                            <th scope="col"><?php _e('Field','open-badges-framework');?></th>
                            <th scope="col"><?php _e('Level','open-badges-framework');?></th>
                            <?php if (Secondary::isJobManagerActive()) { ?>
                                <th scope="col"><?php _e('Class','open-badges-framework');?></th>
                            <?php } ?>
                            <th scope="col"><?php _e('Teacher','open-badges-framework');?></th>
                            <th scope="col"><?php _e('Creation','open-badges-framework');?></th>
                            <th scope="col"><?php _e('Got','open-badges-framework');?></th>
                            <th scope="col"><?php _e('Mozilla OB','open-badges-framework');?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($table = DbBadge::get()) {
                            foreach ($table as $row) {
                                $badge = new Badge();
                                $badge->retrieveBadge($row->id);
                                $student = DbUser::getById($badge->idUser);

                                echo "<tr>";
                                echo "<td><input id='bd-select-$row->id' type='checkbox' name='badge[]' value='$row->id'></td>";
                                echo "<td>" . (get_user_by('id', $student->idWP) ? "<a href='" . get_edit_user_link(get_user_by('id', $student->idWP)->ID) . "'>" . $student->email . "</a>" : "<span> $student->email</span>") . "</td>";
                                echo "<td><a href='" . get_edit_post_link($badge->idBadge) . "'>" . (get_post($badge->idBadge) ? get_post($badge->idBadge)->post_title : "") . "</a></td>";
                                echo "<td><a href='" . get_edit_term_link($badge->idField) . "'>" . (get_term($badge->idField) ? get_term($badge->idField)->name : "") . "</a></td>";
                                echo "<td><a href='" . get_edit_term_link($badge->idLevel) . "'>" . (get_term($badge->idLevel) ? get_term($badge->idLevel)->name : "") . "</a></td>";
                                if (Secondary::isJobManagerActive()) {
                                    echo "<td><a href='" . get_edit_post_link($badge->idClass) . "'>" . (get_post($badge->idClass) ? get_post($badge->idClass)->post_title : "") . "</a></td>";
                                }

                                if( $badge->idTeacher == get_user_by('id', $student->idWP)->ID ){
                                    echo "<td>Self sent</td>";
                                }else{
                                    if( $teacher = get_user_by('id', $badge->idTeacher) ){
                                        if( !empty( $teacher->first_name ) && !empty( $teacher->last_name ) ){
                                            echo "<td><a href='" . get_edit_user_link($teacher->ID) . "'>" . $teacher->first_name . ' ' . $teacher->last_name . "</a></td>";
                                        }else{
                                            echo "<td><a href='" . get_edit_user_link($teacher->ID) . "'>" . $teacher->display_name . "</a></td>";
                                        }
                                    }
                                }

                                echo "<td>" . $badge->creationDate . "</td>";
                                echo "<td>" . $badge->gotDate . "</td>";
                                echo "<td>" . $badge->gotMozillaDate . "</td>";
                            }
                        } ?>
                        </tbody>
                    </table>
                </div>
                <?php self::actionSection("2"); ?>
            </form>
            <?php
        } else {
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
            echo "<p>No badge sent. Click <a href='" . admin_url('admin.php?page=' . Admin::PAGE_SEND_BADGE, $protocol) . "'>here</a> to send a badge.</p>";
        }

    }
	
	
	
	
	
	public static function usersTable() {
        $table = DbBadge::get();
        if ($table) {

            ?>
                <div class="scroll-hor">
                    <table class="wp-list-table widefat striped pages">
                        <thead>
                        <tr>

                            <th scope="col"><?php _e('Teacher','open-badges-framework');?></th>
                            <th scope="col"><?php _e('Badges Sent','open-badges-framework');?></th>
                            <th scope="col"><?php _e('Active Since','open-badges-framework');?></th>
							<th scope="col"><?php _e('Nothing','open-badges-framework');?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
/*                         if ($table = DbBadge::get()) {
                            foreach ($table as $row) {
                                $badge = new Badge();
                                $badge->retrieveBadge($row->id);
                                $student = DbUser::getById($badge->idUser);

                                echo "<tr>";
                                echo "<td><input id='bd-select-$row->id' type='checkbox' name='badge[]' value='$row->id'></td>";
                                echo "<td>" . (get_user_by('id', $student->idWP) ? "<a href='" . get_edit_user_link(get_user_by('id', $student->idWP)->ID) . "'>" . $student->email . "</a>" : "<span> $student->email</span>") . "</td>";
                                echo "<td><a href='" . get_edit_post_link($badge->idBadge) . "'>" . (get_post($badge->idBadge) ? get_post($badge->idBadge)->post_title : "") . "</a></td>";
                                echo "<td><a href='" . get_edit_term_link($badge->idField) . "'>" . (get_term($badge->idField) ? get_term($badge->idField)->name : "") . "</a></td>";
                                echo "<td><a href='" . get_edit_term_link($badge->idLevel) . "'>" . (get_term($badge->idLevel) ? get_term($badge->idLevel)->name : "") . "</a></td>";
                                if (Secondary::isJobManagerActive()) {
                                    echo "<td><a href='" . get_edit_post_link($badge->idClass) . "'>" . (get_post($badge->idClass) ? get_post($badge->idClass)->post_title : "") . "</a></td>";
                                }
                                echo "<td><a href='" . get_edit_user_link($badge->idTeacher) . "'>" . (get_user_by('id', $badge->idTeacher) ? get_user_by('id', $badge->idTeacher)->userEmail : "") . "</a></td>";
                                echo "<td>" . $badge->creationDate . "</td>";
                                echo "<td>" . $badge->gotDate . "</td>";
                                echo "<td>" . $badge->gotMozillaDate . "</td>";
                            }
                        } */
						
						$args = array(
							'role__in'     => array('administrator','teacher','academy'),
						); 
						
						$blogusers = get_users( $args );
						
						foreach ( $blogusers as $user ) {
							 $userData = get_userdata($user->ID);
							 $date = date("d-m-Y", strtotime($userData->user_registered));
							 global $wpdb;
							 $countBadges = $wpdb->get_var("SELECT COUNT(idTeacher) FROM ".$wpdb->prefix."obf_badge WHERE `idTeacher`=".$user->ID.";");
							 echo "<tr>";						 
							 echo "<td><a href='" . get_edit_user_link($user->ID) . "'>" . $user->user_email . "</a></td>";
							 echo "<td>" . $countBadges . "</td>";
							 echo "<td>" . $date . "</td>";
							  echo "<td>-</td>";
						}
						
                        ?>
						
						
						
                        </tbody>
                    </table>
                </div>
               
            
            <?php
        } else {
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
            echo "<p>No badge sent. Click <a href='" . admin_url('admin.php?page=' . Admin::PAGE_SEND_BADGE, $protocol) . "'>here</a> to send a badge.</p>";
        }

    }

    /**
     * Show the action section situated in the DashboardTemp class.
     *
     * @param string $number
     */
    public static function actionSection($number = "") { ?>
        <div class="tablenav bottom">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-bottom" class="screen-reader-text"><?php _e('Select bulk action','open-badges-framework');?></label>
                <select name="action<?php echo $number; ?>" id="bulk-action-selector-bottom">
                    <option value="-1"><?php _e('Bulk Actions','open-badges-framework');?></option>
                    <option value="trash"><?php _e('Move to Trash','open-badges-framework');?></option>
                </select>
                <input type="submit" id="doaction<?php echo $number; ?>" class="button action" value="<?php _e('Apply','open-badges-framework');?>">
            </div>
            <div class="alignleft actions">
            </div>
            <div class="tablenav-pages one-page">
                <span class="displaying-num">
                    <?php echo Statistics::getNumBadgesSent(); _e('items','open-badges-framework');?> ,
                    <?php echo Statistics::getNumBadgesGot(); _e('got','open-badges-framework');?>,
                    <?php echo Statistics::getNumBadgesGotMob(); _e('mob','open-badges-framework');?> 
                </span>
                <br class="clear">
            </div>
        </div>
        <?php
    }
}
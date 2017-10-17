<?php

/**
 * Loads and displays the available languages of badge's description according to the badge selected.
 *
 * @author Nicolas TORION
 * @since  0.6.2
 */

// JAVASCRIPT & JQUERY FUNCTIONS

add_action('admin_footer', 'js_form'); // Write our JS below here
add_action('wp_footer', 'js_form');



/**
 * This function permit to create the ajax call far the page send-badge.php.
 *
 * @author Alessandro RICCARDI
 * @since 0.6.3
 */
function js_form() {
    ?>

    <script>
         var loaderGif = "<?php echo plugins_url('../../assets/load.gif', __FILE__); ?>";
         var ajaxFile  = "<?php echo plugins_url('../ajax/custom_ajax.php', __FILE__); ?>";

    </script>


    <script>
        /**
         *  (Only in the tab ISSUE of the page send-badge.php) This function is called when the user select the class by
         *  clicking the class .select_class .
         *
         * @author Alessandro RICCARDI
         * @since 0.6.3
         *
         * @param form
         */
        function load_classes(form) {
            jQuery("#badge_form_" + form + " #select_class").html("<br /><img src=' <?php echo plugins_url('../../assets/load.gif', __FILE__); ?>' width='50px' height='50px' />");

            var data = {
                'action': 'action_select_class',
                'form': 'form_' + form + '_',
                'level_selected': jQuery("#badge_form_" + form + " .level:checked").val(),
                'language_selected': jQuery("#badge_form_" + form + " #language option:selected").text()
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post("<?php echo plugins_url('../ajax/custom_ajax.php', __FILE__); ?>", data, function (response) {
                jQuery("#badge_form_" + form + " #select_class").html(response);
            });
        }

        /**
         *  This function is called when the user select the level of the badge and then is loaded the information about
         *  its.
         *
         * @author Alessandro RICCARDI
         * @since 0.6.3
         *
         * @param form
         */
        function load_description(form) {
            jQuery("#badge_form_" + form + " #result_preview_description").html("<br /><img src='<?php echo plugins_url('../../assets/load.gif', __FILE__); ?>' width='50px' height='50px' />");

            console.log(jQuery("#badge_form_" + form + " #language_description option:selected").text());
            console.log(jQuery("#badge_form_" + form + " input[name=input_badge_name]").val());
            var data = {
                'action': 'action_select_description_preview',
                'language_description_selected': jQuery("#badge_form_" + form + " #language_description option:selected").text(),
                'badge_name': jQuery("#badge_form_" + form + " input[name=input_badge_name]").val()
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post("<?php echo plugins_url('../ajax/custom_ajax.php', __FILE__); ?>", data, function (response) {
                jQuery("#badge_form_" + form + " #result_preview_description").html(response);
            });
        }
    </script>
    <?php
}

add_action('admin_footer', 'js_save_metabox_students'); // Write our JS below here
add_action('wp_footer', 'js_save_metabox_students');

/**
 * Saves the metabox students in the class job listing post type.
 *
 * @author Nicolas TORION
 * @since  0.3
 */
function js_save_metabox_students() {
    ?>
    <script>
        function save_metabox_students() {

            console.log("CHANGE DETECTED");
            var post_id = jQuery("#box_students").attr('name');
            var students = {};
            var i = 0;
            jQuery("#box_students tbody").find("tr").each(function () {
                var student_infos = [];
                jQuery(this).find("td").each(function () {
                    student_infos.push(jQuery(this).find("center").html());
                });

                var login = student_infos[0];
                var level = student_infos[1];
                var language = student_infos[2];
                var date = student_infos[3];

                var student = {
                    'login': login,
                    'level': level,
                    'language': language,
                    'date': date
                };

                students[i] = student;
                i = i + 1;
            });

            var data = {
                'action': 'action_save_metabox_students',
                'class_students': students,
                'post_id': post_id
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post("<?php echo plugins_url('../ajax/custom_ajax.php', __FILE__); ?>", data, function (response) {
                console.log(response);
            });
        };
    </script>
    <?php
}


add_action('admin_footer', 'edit_comment_translation');
add_action('wp_footer', 'edit_comment_translation');

/**
 *
 * JAVASCRIPT code to allow a teacher of the academy to edit his translations.
 *
 * @author Nicolas TORION
 * @since  0.3
 */
function edit_comment_translation() {
    ?>
    <script>
        jQuery("#edit_comment_link").on("click", function () {
            var comment_content = jQuery(this).parent();
            var comment_id = comment_content.attr("id");
            var comment_text = comment_content.find("#comment_text");
            var comment_text_value = comment_text.text();

            var content = '<textarea id="textarea_edit_comment" rows="6" cols="40">' + comment_text_value + '</textarea>'
                + '<a href="#" id="save_comment_link">Save your modifications</a>';
            comment_content.html(content);

            jQuery("#save_comment_link").on("click", function () {
                var comment_text_value = comment_content.find("textarea").val();

                var data = {
                    'action': 'action_save_comment',
                    'comment_id': comment_id,
                    'comment_text': comment_text_value
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post("<?php echo plugins_url('../ajax/custom_ajax.php', __FILE__); ?>", data, function (response) {
                    console.log(response);
                });

                var content = comment_text_value + '<br /><br /><a href="#" id="edit_comment_link">Edit your translation</a>';
                comment_content.html(content);
            });
        });
    </script>
    <?php
}


?>

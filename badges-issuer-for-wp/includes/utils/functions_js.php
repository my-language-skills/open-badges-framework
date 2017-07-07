<?php

// JAVASCRIPT & JQUERY FUNCTIONS

add_action( 'admin_footer', 'js_form' ); // Write our JS below here
add_action( 'wp_footer', 'js_form' );
/**
 * Loads and displays the available languages of badge's description according to the badge selected.
 *
 * @author Nicolas TORION
 * @since 1.0.0
*/
function js_form() {
  $forms = ["a", "b", "c"];

  foreach ($forms as $form) {
    ?>
    <script>
    jQuery("#badge_form_<?php echo $form; ?> .level").on("click", function() {

      jQuery("#badge_form_<?php echo $form; ?> #select_badge").html("<br /><img src='http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/plugins/badges-issuer-for-wp/images/load.gif' width='50px' height='50px' />");

      var data = {
  			'action': 'action_select_badge',
        'form': 'form_<?php echo $form; ?>_',
  			'level_selected': jQuery("#badge_form_<?php echo $form; ?> .level:checked").val()
  		};

  		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
  		jQuery.post("<?php echo "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/ajax/custom_ajax.php"; ?>", data, function(response) {
  			jQuery("#badge_form_<?php echo $form; ?> #select_badge").html(response);
  		});
    });
    </script>
    <?php
  }

  ?>
  <script>
  function load_classes(form) {
    jQuery("#badge_form_"+form+" #select_class").html("<br /><img src='http://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/plugins/badges-issuer-for-wp/images/load.gif' width='50px' height='50px' />");

    var data = {
      'action': 'action_select_class',
      'form': 'form_'+form+'_',
      'level_selected': jQuery("#badge_form_"+form+" .level:checked").val(),
      'language_selected': jQuery("#badge_form_"+form+" #language option:selected").text()
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post("<?php echo "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/ajax/custom_ajax.php"; ?>", data, function(response) {
      jQuery("#badge_form_"+form+" #select_class").html(response);
    });
  }
  </script>
  <?php

  $forms_class = ["b", "c"];
  foreach ($forms_class as $form) {
  ?>
    <script>
    jQuery("#badge_form_<?php echo $form; ?> .level").on("click", function() {
      load_classes('<?php echo $form; ?>');
    });
    jQuery("#badge_form_<?php echo $form; ?> #language").change(function() {
      load_classes('<?php echo $form; ?>');
    });
    </script>
  <?php
  }
}

add_action( 'admin_footer', 'js_save_metabox_students' ); // Write our JS below here
add_action( 'wp_footer', 'js_save_metabox_students' );

/**
 * Saves the metabox students in the class job listing post type.
 *
 * @author Nicolas TORION
 * @since 1.0.0
*/
function js_save_metabox_students() {
  ?>
  <script>
  function save_metabox_students() {

    console.log("CHANGE DETECTED");
    var post_id = jQuery("#box_students").attr('name');
    var students = {};
    var i = 0;
    jQuery("#box_students tbody").find("tr").each(function(){
      var student_infos = [];
      jQuery(this).find("td").each(function(){
        student_infos.push(jQuery(this).find("center").html());
      });

      var login = student_infos[0];
      var level = student_infos[1];
      var language = student_infos[2];

      var student = {
        'login': login,
        'level': level,
        'language': language
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
		jQuery.post("<?php echo "http://".$_SERVER['SERVER_NAME']."/wp-content/plugins/badges-issuer-for-wp/includes/ajax/custom_ajax.php"; ?>", data, function(response) {
      console.log(response);
    });
  };
  </script>
  <?php
}

add_action( 'admin_footer', 'js_send_badge_form' ); // Write our JS below here
add_action( 'wp_footer', 'js_send_badge_form' );

/**
 * Check if different forms for sending badges are completed well.
 * If it's the case, the submit buttons are activated.
 *
 * @author Nicolas TORION
 * @since 1.0.0
*/
function js_send_badge_form() {
  ?>
  <script>
    setInterval(function(){check_badge_form();}, 500);

    function check_mails(mails) {

      if(typeof mails !== 'undefined') {
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

        for (var i = 0; i < mails.length; i++) {
          if(!testEmail.test(mails[i])) {
            return false;
          }
        }
        return true;
      }
      else {
        return false;
      }
    }

    function check_badge_form() {
      var badge_a = jQuery("#badge_form_a .input-badge");

      if(typeof jQuery("#badge_form_b .mail").val() !== 'undefined')
        var mails_b = jQuery("#badge_form_b .mail").val().split("\n");

      var badge_b = jQuery("#badge_form_b .input-badge");

      if(typeof jQuery("#badge_form_c .mail").val() !== 'undefined')
        var mails_c = jQuery("#badge_form_c .mail").val().split("\n");

      var badge_c = jQuery("#badge_form_c .input-badge");

      if(!badge_a.is(':checked')) {
        jQuery('#submit_button_a').prop('disabled', true);
      }
      else {
        jQuery('#submit_button_a').prop('disabled', false);
      }

      if(!check_mails(mails_b) || !badge_b.is(':checked')) {
        jQuery('#submit_button_b').prop('disabled', true);
      }
      else {
        jQuery('#submit_button_b').prop('disabled', false);
      }

      if(!check_mails(mails_c) || !badge_c.is(':checked')) {
        jQuery('#submit_button_c').prop('disabled', true);
      }
      else {
        jQuery('#submit_button_c').prop('disabled', false);
      }
    }
  </script>
<?php
}
 ?>

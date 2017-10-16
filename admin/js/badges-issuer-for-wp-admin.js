/*
setInterval(function () {
    check_badge_form();
}, 500);
setInterval(function () {
    check_settings_badges_issuer_form();
}, 500);

function check_mails(mails) {

    if (typeof mails !== 'undefined') {
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

        for (var i = 0; i < mails.length; i++) {
            if (!testEmail.test(mails[i])) {
                return false;
            }
        }
        return true;
    }
    else {
        return false;
    }
}

function check_urls(urls) {
    var pattern = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    if (typeof urls !== 'undefined') {
        for (var i = 0; i < urls.length; i++) {
            if (!pattern.test(urls[i])) {
                return false;
            }
        }
        return true;
    }
    else {
        return false;
    }
}

function check_settings_badges_issuer_form() {
    var name = jQuery("#settings_form_badges_issuer #badges_issuer_name").val();
    var image = jQuery("#settings_form_badges_issuer #badges_issuer_image").val();
    var website = jQuery("#settings_form_badges_issuer #badges_issuer_website").val();
    var mail = jQuery("#settings_form_badges_issuer #badges_issuer_mail").val();

    if (check_mails([mail]) && name != "" && check_urls([image, website])) {
        jQuery('#settings_form_badges_issuer #settings_submit_badges_issuer').prop('disabled', false);
    }
    else {
        jQuery('#settings_form_badges_issuer #settings_submit_badges_issuer').prop('disabled', true);
    }
}

function check_badge_form() {
    var tabA = jQuery("#nav-badge-a");
    var tabB = jQuery("#nav-badge-b");
    var tabC = jQuery("#nav-badge-c");

    if (tabA.hasClass("nav-tab-active")) {
        var badge_a = jQuery("#badge_form_a .input-badge");
        var badge_a_comment = jQuery("#badge_form_a #comment");

        if (badge_a.is(':checked') && badge_a_comment.val()) {
            jQuery('#submit_button_a').prop('disabled', false);
        }
        else {
            jQuery('#submit_button_a').prop('disabled', true);
        }

    } else if (tabB.hasClass("nav-tab-active")) {
        var badge_b = jQuery("#badge_form_b .input-badge");
        var badge_b_comment = jQuery("#badge_form_b #comment");

        if (typeof jQuery("#badge_form_b .mail").val() !== 'undefined') {
            var mails_b = jQuery("#badge_form_b .mail").val().split("\n");
        }

        if (check_mails(mails_b) && badge_b.is(':checked') && badge_b_comment.val()) {
            jQuery('#submit_button_b').prop('disabled', false);
        }
        else {
            jQuery('#submit_button_b').prop('disabled', true);
        }

    } else if (tabC.hasClass("nav-tab-active")) {
        var badge_c = jQuery("#badge_form_c .input-badge");
        var badge_c_comment = jQuery("#badge_form_c #comment");

        if (typeof jQuery("#badge_form_c .mail").val() !== 'undefined') {
            var mails_c = jQuery("#badge_form_c .mail").val().split("\n");
        }

        if (check_mails(mails_c) && badge_c.is(':checked') && badge_c_comment.val()) {
            jQuery('#submit_button_c').prop('disabled', false);
        }
        else {
            jQuery('#submit_button_c').prop('disabled', true);
        }
    }
}*/




window.onload = function() {

    var form = jQuery("#badge_form_a");
    form.validate({
        errorPlacement: function errorPlacement(error, element) { element.before(error); },
        rules: {
            confirm: {
                equalTo: "#password"
            }
        }
    });

    form.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex)
        {

            switch (currentIndex) {
                case 0:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                case 1:
                    var check1 = false;
                    jQuery("#badge_form_a input[name='level']")  // for all checkboxes
                        .each(function() {  // first pass, create name mapping
                            if(jQuery(this).is(':checked')) {
                                check1 = true;
                            }
                        });

                    if(check1){
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }
                    break;
                case 2:
                    var check2 = false;
                    jQuery("#badge_form_a input[name='input_badge_name']")  // for all checkboxes
                        .each(function() {  // first pass, create name mapping
                            if(jQuery(this).is(':checked')) {
                                check2 = true;
                            }
                        });
                    if(check2){
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }

                    break;
                case 3:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
            }



        },
        onFinishing: function (event, currentIndex)
        {
            form.validate().settings.ignore = ":disabled";
            return form.valid();

        },
        onFinished: function (event, currentIndex)
        {
            alert("Submitted!");
        }
    });


    /**
     * When you click on the .display_parent_categories to see the other "Field of Education" category (parent),
     * the function call the "action_languages_form" in the other file.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    jQuery("#languages_form_a").on("click", ".display_parent_categories", function () {
        jQuery("#languages_form_a").html("<br />" +
            "<img src='"+loaderGif+"' width='50px' height='50px' />");

        var id_lan = jQuery(this).attr('id');
        id_lan = id_lan.replace(/\s/g, '');
        var data = {
            'action': 'action_languages_form',
            'form': 'a',
            'slug': id_lan
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                jQuery("#languages_form_a").html(response);
            }
        );
    });

    /**
     * When the Level is selected (<input class="level") and the user click on it, under is loaded the image
     * of the current badge.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    jQuery("#badge_form_a .level").on("click", function () {

        jQuery("#badge_form_a #select_badge").html("<br /><img src='"+loaderGif+"' width='50px' height='50px' />");

        var data = {
            'action': 'action_select_badge',
            'form': 'form_a_',
            'level_selected': jQuery("#badge_form_a .level:checked").val(),
            'language_selected': jQuery("#badge_form_a #language").val()
        };

        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                jQuery("#badge_form_a #select_badge").html(response);
            }
        );

    });

};



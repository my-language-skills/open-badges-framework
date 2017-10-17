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

var currentForm;

window.onload = function () {

    /**** CODE for the tab in the page send badge ****/
    jQuery('#tabs').tabs();
    jQuery(".nav-tab").click(function () {
        jQuery(".nav-tab").removeClass("nav-tab-active");
        jQuery(this).addClass("nav-tab-active");
    });


    /* =====================================
        BADGE FORM # A #
       ===================================== */
    var form = jQuery("#badge_form_a");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
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
        onStepChanging: function (event, currentIndex, newIndex) {

            if (newIndex < currentIndex) {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            }

            switch (currentIndex) {

                /* FIELD OF EDUCATION */
                case 0:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;

                /* LEVEL */
                case 1:
                    var check1 = false;
                    jQuery("#badge_form_a input[name='level']")
                        .each(function () {
                            if (jQuery(this).is(':checked')) {
                                check1 = true;
                            }
                        });

                    if (check1) {
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }
                    break;

                /* KIND OF BADGE */
                case 2:
                    var check2 = false;
                    jQuery("#badge_form_a input[name='input_badge_name']")  // for all checkboxes
                        .each(function () {  // first pass, create name mapping
                            if (jQuery(this).is(':checked')) {
                                check2 = true;
                            }
                        });
                    if (check2) {
                        //Load description of language for the next page
                        load_description("a");
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }

                    break;

                /* LANGUAGE */
                case 3:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;

                /* INFORMATION */
                case 4:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
            }
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();

        },
        onFinished: function (event, currentIndex) {
            sendMessageBadge();
        }
    });


    /* =====================================
         BADGE FORM # B #
       ===================================== */
    var form2 = jQuery("#badge_form_b");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
        rules: {
            confirm: {
                equalTo: "#password"
            }
        }
    });

    form2.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {

            if (newIndex < currentIndex) {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            }

            switch (currentIndex) {
                /* FIELD OF EDUCATION */
                case 0:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                /* LEVEL */
                case 1:
                    var check1 = false;
                    jQuery("#badge_form_b input[name='level']")  // for all checkboxes
                        .each(function () {  // first pass, create name mapping
                            if (jQuery(this).is(':checked')) {
                                check1 = true;
                            }
                        });

                    if (check1) {
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }
                    break;
                /* KIND OF BADGE */
                case 2:
                    var check2 = false;
                    jQuery("#badge_form_b input[name='input_badge_name']")  // for all checkboxes
                        .each(function () {  // first pass, create name mapping
                            if (jQuery(this).is(':checked')) {
                                check2 = true;
                            }
                        });
                    if (check2) {
                        //Load description of language for the next page
                        load_description("b");
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }

                    break;
                /* LANGUAGE */
                case 3:
                    load_class("b");
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                /* CLASS */
                case 4:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                /* EMAIL */
                case 5:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                /* INFORMATION */
                case 6:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
            }

        },
        onFinishing: function (event, currentIndex) {
            form2.validate().settings.ignore = ":disabled";
            return form2.valid();

        },
        onFinished: function (event, currentIndex) {
            alert("Finish");
        }
    });

    /**
     * TO LOAD THE FIELD OF EDUCATION (PARENT)
     * When you click on the .display_parent_categories to see the other "Field of Education" category (parent),
     * the function call the "action_languages_form" in the other file.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    jQuery(".display_parent_categories").click(function () {
        currentForm = checkForm(this);

        //Remove the class 'active' to the old button of the field of education.
        jQuery("#badge_form_" + currentForm + " .display_parent_categories.active").removeClass("active");
        //Add the class 'active' to the actual button.
        jQuery(this).addClass("active");

        jQuery("#field_edu_" + currentForm ).html("<br />" +
            "<img src='" + loaderGif + "' width='50px' height='50px' />");

        var id_lan = jQuery(this).attr('id');
        id_lan = id_lan.replace(/\s/g, '');
        var data = {
            'action': 'action_languages_form',
            'form': currentForm,
            'slug': id_lan
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                jQuery("#field_edu_" + currentForm).html(response);
            }
        );
    });

    /**
     * TO LOAD THE BADGE, DESCRIPTION and CLASS
     * When the Level is selected (<input class="level") do the code below.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    jQuery(".level").click(function () {

        /* ========================
            Load the IMAGE of the current badge.
         */
        currentForm = checkForm(this);

        jQuery("#badge_form_" + currentForm + "  #select_badge").html("<br /><img src='" + loaderGif + "' width='50px' height='50px' />");

        var data = {
            'action': 'action_select_badge',
            'form': "form_" + currentForm + "_",
            'level_selected': jQuery("#badge_form_" + currentForm + "  .level:checked").val(),
            'language_selected': jQuery("#badge_form_" + currentForm + "  #language").val()
        };

        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                jQuery("#badge_form_" + currentForm + "  #select_badge").html(response);
            }
        );

    });

    /**
     * LOAD DESCRIPTION
     *
     * @author Alessandro RICCARDI
     * @since 0.6.4
     */
    function load_description(curForm) {

        jQuery("#badge_form_" + curForm + " #result_preview_description").html("<br /><img src='" + loaderGif + "' width='50px' height='50px' />");

        var tab_name = "_" + jQuery("#badge_form_" + curForm + " .input-badge:checked").val().replace('-', '_') + "_description_languages";
        var tab = eval(tab_name);

        var content = '<label for="language_description"></label><br /><select name="language_description" id="language_description">';
        tab.forEach(function (lang) {
            content = content + '<option value="' + lang + '">' + lang + '</option>';
        });

        content = content + '</select><br>';
        jQuery("#badge_form_" + curForm + " #result_languages_description").html(content);

        var data = {
            'action': 'action_select_description_preview',
            'language_description_selected': jQuery("#badge_form_" + curForm + " #language_description option:selected").text(),
            'badge_name': jQuery("#badge_form_" + curForm + " input[name=input_badge_name]").val()
        };

        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                jQuery("#badge_form_" + curForm + " #result_preview_description").html(response);
            }
        );
    }

    /**
     * LOAD DESCRIPTION
     *
     * @author Alessandro RICCARDI
     * @since 0.6.4
     */
    function load_class(curForm){
        // To load the class if in the tab B or C
        if (curForm == "b" || curForm == "c") {
            jQuery("#badge_form_" + curForm + "  #select_class").html("<br /><img src='" + loaderGif + "' width='50px' height='50px' />");

            var data = {
                'action': 'action_select_class',
                'form': "form_" + curForm + "_",
                'level_selected': jQuery("#badge_form_" + curForm + "  .level:checked").val(),
                'language_selected': jQuery("#badge_form_" + curForm + "  #language option:selected").text()
            };
            jQuery.post(
                ajaxFile,
                data,
                function (response) {
                    jQuery("#badge_form_" + curForm + "  #select_class").html(response);
                }
            );
        }
    }

    /**
     * TO SEND THE BADGE
     * This function make an ajax call to permit to send the badge to the right person and also to store in the server.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.4
     */
    function sendMessageBadge() {

        var level = jQuery("#badge_form_a input[name='level']").val(),
            sender = jQuery("#badge_form_a input[name='sender']").val(),
            input_badge_name = jQuery("#badge_form_a input[name='input_badge_name']").val(),
            language = jQuery("#badge_form_a #language :selected").text(),
            mail = jQuery("#badge_form_a input[name='mail']").val(),
            comment = jQuery("#badge_form_a #comment").val(),
            language_description = jQuery("#badge_form_a #language_description").val();

        var data = {
            'action': 'send_message_badge',
            'form': 'form_a_',
            'level': level,
            'sender': sender,
            'input_badge_name': input_badge_name,
            'language': language,
            'mail': mail,
            'comment': comment,
            'language_description': language_description
        };

        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                console.log(response);
            }
        );
    }


    /**
     * This function permit to check the current form and save into a variable.
     * @param event of the event about the click
     *
     * @author Alessandro RICCARDI
     * @since 0.6.4
     */
    function checkForm(event) {
        if (jQuery(event).parents('#badge_form_a').length == 1) {
            return "a";
        } else if (jQuery(event).parents('#badge_form_b').length == 1) {
            return "b";
        } else if (jQuery(event).parents('#badge_form_c').length == 1) {
            return "c";
        } else {
            throw "There aren't other Form."
        }

    }
};




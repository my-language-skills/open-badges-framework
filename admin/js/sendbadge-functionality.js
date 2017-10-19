var currentForm;

window.onload = function () {
    /* Variables */
    var isLocalhost = false;


    if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
        isLocalhost = true;
        jQuery(".wrap").append(
            '<div class="message msg-insuccess">' +
            '<strong>Sending badge not available</strong> because WordPress is running in localhost!' +
            '</div>');
    }
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

                /******* (0) FIELD OF EDUCATION */
                case 0:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;

                /******* (1) LEVEL */
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

                /******* (2) KIND OF BADGE */
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

                /******* (3) LANGUAGE */
                case 3:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;

                /******* (4) INFORMATION */
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
            sendMessageBadge("a");
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
                /******* (0) FIELD OF EDUCATION */
                case 0:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                /******* (1) LEVEL */
                case 1:
                    var check = false;
                    jQuery("#badge_form_b input[name='level']")  // for all checkboxes
                        .each(function () {  // first pass, create name mapping
                            if (jQuery(this).is(':checked')) {
                                check = true;
                            }
                        });

                    if (check) {
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }
                    break;
                /******* (2) KIND OF BADGE */
                case 2:
                    var check = false;
                    jQuery("#badge_form_b input[name='input_badge_name']")  // for all checkboxes
                        .each(function () {  // first pass, create name mapping
                            if (jQuery(this).is(':checked')) {
                                check = true;
                            }
                        });
                    if (check) {
                        //Load description of language for the next page
                        load_description("b");
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }
                    break;
                /******* (3) LANGUAGE */
                case 3:
                    load_class("b");
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                /******* (4) CLASS */
                case 4:
                    var check = false;
                    jQuery("#badge_form_b input[name='class_for_student']")  // for all checkboxes
                        .each(function () {  // first pass, create name mapping
                            if (jQuery(this).is(':checked')) {
                                check = true;
                            }
                        });
                    if (check) {
                        //Load description of language for the next page
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }
                    break;
                /******* (5) EMAIL */
                case 5:
                    var emails = jQuery("#badge_form_b #mail").val();
                    if (check_mails(emails)) {
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    }

                    return false;
                    break;
                /******* (6) INFORMATION */
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
            sendMessageBadge("b");
        }
    });


    /* =====================================
         BADGE FORM # C #
       ===================================== */

    var form3 = jQuery("#badge_form_c");
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

    form3.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {

            if (newIndex < currentIndex) {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            }

            switch (currentIndex) {
                /******* (0) FIELD OF EDUCATION */
                case 0:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                /******* (1) LEVEL */
                case 1:
                    var check1 = false;
                    jQuery("#badge_form_c input[name='level']")  // for all checkboxes
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
                /******* (2) KIND OF BADGE */
                case 2:
                    var check2 = false;
                    jQuery("#badge_form_c input[name='input_badge_name']")  // for all checkboxes
                        .each(function () {  // first pass, create name mapping
                            if (jQuery(this).is(':checked')) {
                                check2 = true;
                            }
                        });
                    if (check2) {
                        //Load description of language for the next page
                        load_description("c");
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }

                    break;
                /******* (3) LANGUAGE */
                case 3:
                    load_class("c");
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;
                /******* (4) CLASS */
                case 4:
                    var check = false;
                    jQuery("#badge_form_c input[name='class_for_student']")  // for all checkboxes
                        .each(function () {  // first pass, create name mapping
                            if (jQuery(this).is(':checked')) {
                                check = true;
                            }
                        });
                    if (check) {
                        //Load description of language for the next page
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    } else {
                        return false;
                    }
                    break;
                /******* (5) EMAIL */
                case 5:
                    var emails = jQuery("#badge_form_c #mail").val();
                    if (check_mails(emails)) {
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    }

                    return false;
                    break;
                /******* (6) INFORMATION */
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
            sendMessageBadge("c");
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

        jQuery("#field_edu_" + currentForm).html("<br />" +
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
     * @since X.X.X
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
     * LOAD CLASS
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
     */
    function load_class(curForm) {
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
     * @since X.X.X
     */
    function sendMessageBadge(curForm) {
        if(!isLocalhost) {
            var language = jQuery("#badge_form_" + curForm + " #language :selected").text(),
                level = jQuery("#badge_form_" + curForm + " input[name='level']:checked").val(),
                badge_name = jQuery("#badge_form_" + curForm + " input[name='input_badge_name']:checked").val(),
                language_description = jQuery("#badge_form_" + curForm + " #language_description").val(),
                class_student = jQuery("#badge_form_" + curForm + " input[name='class_for_student']:checked").val(),
                class_teacher = jQuery("#badge_form_" + curForm + " input[name='class_teacher']:checked").val(),
                mail = jQuery("#badge_form_" + curForm + " input[name='mail']").val(),
                comment = jQuery("#badge_form_" + curForm + " #comment").val(),
                sender = jQuery("input[name='sender']").val();

            alert(language + ", " + level + ", " + badge_name + ", " + language_description + ", " + class_student + ", " + class_teacher + ", " + mail + ", " + comment + ", " + sender);


            var data = {
                'action': 'send_message_badge',
                'curForm': curForm,
                'language': language,
                'level': level,
                'badge_name': badge_name,
                'language_description': language_description,
                'class_student': class_student,
                'class_teacher': class_teacher,
                'mail': mail,
                'comment': comment,
                'sender': sender,
            };

            jQuery.post(
                ajaxFile,
                data,
                function (response) {
                    jQuery("#badge_form_" + curForm).append(response);
                    jQuery('html, body').animate({ scrollTop: 0 }, 'fast');
                }
            );
        }
    }


    /**
     * This function permit to check the current form and save into a variable.
     * @param event of the event about the click
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
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

    /**
     * Check if the the @param contain only email
     * @param mails, contain all the email
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
     */
    function check_mails(mails) {
        if (mails) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            mails = mails.split("\n");

            for(var i = 0; i < mails.length; i++){
                if(!re.test(mails[i])) return false;
            }
            // Everything good
            return true;
        } else {
            // No text
            return false;
        }
    }
};




var currentForm;

window.onload = function () {
    /* Variables */
    var isLocalhost = false;


    if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
        isLocalhost = true;
    }

    /**** CODE for the tab in the page send badge ****/
    if (jQuery('#tabs').length) {
        jQuery('#tabs').tabs();
        jQuery(".nav-tab").click(function () {
            jQuery(".nav-tab").removeClass("nav-tab-active");
            jQuery(this).addClass("nav-tab-active");
        });
    }


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
            var currentForm = "a";

            if (newIndex < currentIndex) {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            }

            switch (currentIndex) {
                /******* (0) FIELD OF EDUCATION */
                case 0:
                    return load_levels(currentForm, form);
                    break;
                /******* (1) LEVEL */
                case 1:
                    return load_badges(currentForm, form);
                    break;

                /******* (2) KIND OF BADGE */
                case 2:
                    return load_description(currentForm, form);
                    break;

                /******* (3) LANGUAGE */
                case 3:
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                    break;

                /******* (4) INFORMATION */
                case 4:
                    return check_information(currentForm, form);
                    break;
            }
        },
        onFinishing: function (event, currentIndex) {
            return check_information("a", form);
        },
        onFinished: function (event, currentIndex) {
            sendMessageBadge("a");
        }
    });


    /* =====================================
         BADGE FORM # B #
       ===================================== */

    var form = jQuery("#badge_form_b");
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
            //Variables
            var currentForm = "b";

            if (newIndex < currentIndex) {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            }

            switch (currentIndex) {
                /******* (0) FIELD OF EDUCATION */
                case 0:
                    return load_levels(currentForm, form);
                    break;
                /******* (1) LEVEL */
                case 1:
                    return load_badges(currentForm, form);
                    break;

                /******* (2) KIND OF BADGE */
                case 2:
                    return load_description(currentForm, form);
                    break;
                /******* (3) LANGUAGE */
                case 3:
                    return load_class(currentForm, form);
                    break;
                /******* (4) CLASS */
                case 4:
                    return check_class(currentForm, form);
                    break;
                /******* (5) EMAIL */
                case 5:
                    return check_mails(currentForm, form);
                    break;
                /******* (6) INFORMATION */
                case 6:
                    return check_information(currentForm, form);
                    break;
            }

        },
        onFinishing: function (event, currentIndex) {
            return check_information("b", form);

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
            //Variables
            var currentForm = "c";


            if (newIndex < currentIndex) {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            }

            switch (currentIndex) {
                /******* (0) FIELD OF EDUCATION */
                case 0:
                    return load_levels(currentForm, form);
                    break;
                /******* (1) LEVEL */
                case 1:
                    return load_badges(currentForm, form);
                    break;

                /******* (2) KIND OF BADGE */
                case 2:
                    return load_description(currentForm, form);
                    break;
                /******* (3) LANGUAGE */
                case 3:
                    return load_class(currentForm, form);
                    break;
                /******* (4) CLASS */
                case 4:
                    return check_class(currentForm, form);
                    break;
                /******* (5) EMAIL */
                case 5:
                    return check_mails(currentForm, form);
                    break;
                /******* (6) INFORMATION */
                case 6:
                    return check_information(currentForm, form);
                    break;
            }

        },
        onFinishing: function (event, currentIndex) {
            return check_information("c", form);

        },
        onFinished: function (event, currentIndex) {
            sendMessageBadge("c");
        }
    });

    /**
     * To load the FIELD OF EDUCATION (PARENT)
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
     * To load the LEVEL
     * When the Field of education is selected, the levels are loaded.
     *
     * @param currentForm, contain the letter of the form
     * @param form, contain the form
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
     */

    function load_levels(currentForm, form) {
        var fieldEdu = jQuery("#badge_form_" + currentForm + " #language :selected").text();

        if (fieldEdu == "Select") {
            return false;
        }

        jQuery("#badge_form_" + currentForm + "  #languages_form_" + currentForm).html("<br /><img src='" + loaderGif + "' width='50px' height='50px' />");

        var data = {
            'action': 'get_right_levels',
            'fieldEdu': fieldEdu
        };

        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                jQuery("#badge_form_" + currentForm + "  #languages_form_" + currentForm).html(response);

            }
        );

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    }

    /**
     * To load the BADGE
     * When the Level is selected (<input class="level") do the code below.
     *
     * @author Alessandro RICCARDI
     * @since 0.6.3
     */
    function load_badges(currentForm, form) {
        var check = false;
        var fieldEdu = jQuery("#badge_form_" + currentForm + " #language :selected").text();
        var levelValue = "";

        jQuery("#badge_form_" + currentForm + " input[name='level']")
            .each(function () {
                if (jQuery(this).is(':checked')) {
                    check = true;
                    levelValue = jQuery(this).val();
                }
            });

        if (!check) {
            return false;
        }

        jQuery("#badge_form_" + currentForm + "  #select_badge").html("<br /><img src='" + loaderGif + "' width='50px' height='50px' />");

        var data = {
            'action': 'action_select_badge',
            'fieldEdu': fieldEdu,
            'level': levelValue,
        };

        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                jQuery("#badge_form_" + currentForm + "  #select_badge").html(response);

            }
        );

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    }

    /**
     * To load the DESCRIPTION
     *
     * @param currentForm, contain the letter of the form
     * @param form, contain the form
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
     */
    function load_description(currentForm, form) {
        var check = false;
        var badgeName = "";
        var langSelected = "Default";

        // Check if we selected a badge to permit to switch the page
        jQuery("#badge_form_" + currentForm + " input[name='input_badge_name']")  // check if one badge is selected
            .each(function () {  // first pass, create name mapping
                if (jQuery(this).is(':checked')) {
                    check = true;
                    badgeName = jQuery(this).val();
                }
            });
        // Badge no selected
        if (!check) {
            return false;
        }

        // LOAD the GIF
        jQuery("#badge_form_" + currentForm + " #result_preview_description").html("<br /><img src='" + loaderGif + "' width='50px' height='50px' />");

        // Data for the AJAX call
        var data = {
            'action': 'action_select_description_preview',
            'language_description': langSelected,
            'badge_name': badgeName
        };

        // AJAX call
        jQuery.post(
            ajaxFile,
            data,
            function (response) {
                jQuery("#badge_form_" + currentForm + " #result_preview_description").html(response);
            }
        );

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    }

    /**
     * To load the CLASS
     *
     * @param currentForm, contain the letter of the form
     * @param form, contain the form
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
     */
    function load_class(currentForm, form) {
        // To load the class if in the tab B or C
        if (currentForm == "b" || currentForm == "c") {
            jQuery("#badge_form_" + currentForm + "  #select_class").html("<br /><img src='" + loaderGif + "' width='50px' height='50px' />");

            var data = {
                'action': 'action_select_class',
                'form': "form_" + currentForm + "_",
                'level_selected': jQuery("#badge_form_" + currentForm + "  .level:checked").val(),
                'language_selected': jQuery("#badge_form_" + currentForm + "  #language option:selected").text()
            };
            jQuery.post(
                ajaxFile,
                data,
                function (response) {
                    jQuery("#badge_form_" + currentForm + "  #select_class").html(response);
                }
            );
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        }
    }

    /**
     * Check if is selected the class.
     * @param currentForm, contain the letter of the form
     * @param form, contain the form
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
     */
    function check_class(currentForm, form) {
        var check = false;
        jQuery("#badge_form_"+currentForm+" input[name='class_for_student']")  // for all checkboxes
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
    }


    /**
     * Check if the the email/s contain only email and not garbage.
     *
     * @param currentForm, contain the letter of the form
     * @param form, contain the form
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
     */
    function check_mails(currentForm, form) {
        var mails = jQuery("#badge_form_"+currentForm+" #mail").val();
        if (mails) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            mails = mails.split("\n");

            for (var i = 0; i < mails.length; i++) {
                if (!re.test(mails[i])) return false;
            }
            // Everything good
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        } else {
            // No text
            return false;
        }
    }

    /**
     * Check if there are information with text more long than 10 letter and less than 1000
     * @param currentForm, contain the letter of the form
     * @param form, contain the form
     *
     * @author Alessandro RICCARDI
     * @since X.X.X
     */
    function check_information(currentForm, form) {
        var info = jQuery("#badge_form_" + currentForm + " #comment").val();

        if (info.length > 10 && info.length < 1000) {
            // Everything good
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        } else {
            // No text
            return false;
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
        if (!isLocalhost) {
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
                    jQuery('html, body').animate({scrollTop: 0}, 'fast');
                }
            );
        } else {
            jQuery(".wrap").append(
                '<div class="message msg-insuccess">' +
                '<strong>Sending badge not available</strong> because WordPress is running in localhost!' +
                '</div>');

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

};




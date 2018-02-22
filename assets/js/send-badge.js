window.onload = function () {
    /* Variables */
    var currentForm;
    var clickedSendBadge = false;

    /* ----> POP-UP modal  */
    // Get the modal
    var modal = document.getElementById('modalSendBadge');
    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];


    // Prevent "enter" pressing when filling the text fields
    jQuery(window).keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    /**
     * Get the html loader that show a gif image.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @return {string} the html code.
     */
    function getLoaderHtml() {
        return "<center style='padding: 50px'><img src='" + globalUrl.loader + "' width='50px' height='50px' /></center>";
    }

    /* =====================================
        BADGE FORM # A #
       ===================================== */
    var form_a = jQuery("#form_a");
    if (form_a.length) {
        form_a.validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password"
                }
            }
        });

        form_a.children("div").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                var currentForm = "a";

                if (newIndex < currentIndex) {
                    disableTab(form_a, newIndex, currentIndex);
                    form_a.validate().settings.ignore = ":disabled,:hidden";
                    return form_a.valid();
                }

                switch (currentIndex) {
                    /******* (0) FIELD OF EDUCATION */
                    case 0:
                        return load_levels(currentForm, form_a);
                        break;
                    /******* (1) LEVEL */
                    case 1:
                        return load_badges(currentForm, form_a);
                        break;

                    /******* (2) KIND OF BADGE */
                    case 2:
                        return load_description(currentForm, form_a);
                        break;

                    /******* (3) LANGUAGE */
                    case 3:
                        form_a.validate().settings.ignore = ":disabled,:hidden";
                        return form_a.valid();
                        break;

                    /******* (4) INFORMATION */
                    case 4:
                        return check_information(currentForm, form_a);
                        break;
                }
            },
            onFinishing: function (event, currentIndex) {
                if ($res = check_information("a", form_a)) {
                    modal.style.display = "block";
                    jQuery('#responseSent').html(getLoaderHtml());
                    return $res;
                } else {
                    return false;
                }
            },
            onFinished: function (event, currentIndex) {
                sendMessageBadge("a");
            }
        });
    }


    /* =====================================
         BADGE FORM # B #
       ===================================== */
    var form_b = jQuery("#form_b");
    if (form_b.length) {
        form_b.validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password"
                }
            }
        });

        form_b.children("div").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                //Variables
                var currentForm = "b";

                if (newIndex < currentIndex) {
                    disableTab(form_b, newIndex, currentIndex);
                    form_b.validate().settings.ignore = ":disabled,:hidden";
                    return form_b.valid();
                }

                switch (currentIndex) {
                    /******* (0) FIELD OF EDUCATION */
                    case 0:
                        return load_levels(currentForm, form_b);
                        break;
                    /******* (1) LEVEL */
                    case 1:
                        return load_badges(currentForm, form_b);
                        break;

                    /******* (2) KIND OF BADGE */
                    case 2:
                        return load_description(currentForm, form_b);
                        break;
                    /******* (3) LANGUAGE */
                    case 3:
                        return load_classes(currentForm, form_b);
                        break;
                    /******* (4) CLASS */
                    case 4:
                        return check_class(currentForm, form_b);
                        break;
                    /******* (5) EMAIL */
                    case 5:
                        return check_mails(currentForm, form_b);
                        break;
                    /******* (6) INFORMATION */
                    case 6:
                        return check_information(currentForm, form_b);
                        break;
                }

            },
            onFinishing: function (event, currentIndex) {
                if ($res = check_information("b", form_b)) {
                    modal.style.display = "block";
                    jQuery('#responseSent').html(getLoaderHtml());
                    return $res;
                } else {
                    return false;
                }
            },
            onFinished: function (event, currentIndex) {
                sendMessageBadge("b");
            }
        });
    }


    /* =====================================
         BADGE FORM # C #
       ===================================== */
    var form_c = jQuery("#form_c");
    if (form_c.length) {
        form_c.validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password"
                }
            }
        });

        form_c.children("div").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                //Variables
                var currentForm = "c";


                if (newIndex < currentIndex) {
                    disableTab(form_c, newIndex, currentIndex);
                    form_c.validate().settings.ignore = ":disabled,:hidden";
                    return form_c.valid();
                }

                switch (currentIndex) {
                    /******* (0) FIELD OF EDUCATION */
                    case 0:
                        return load_levels(currentForm, form_c);
                        break;
                    /******* (1) LEVEL */
                    case 1:
                        return load_badges(currentForm, form_c);
                        break;

                    /******* (2) KIND OF BADGE */
                    case 2:
                        return load_description(currentForm, form_c);
                        break;
                    /******* (3) LANGUAGE */
                    case 3:
                        return load_classes(currentForm, form_c);
                        break;
                    /******* (4) CLASS */
                    case 4:
                        return check_class(currentForm, form_c);
                        break;
                    /******* (5) EMAIL */
                    case 5:
                        return check_mails(currentForm, form_c);
                        break;
                    /******* (6) INFORMATION */
                    case 6:
                        return check_information(currentForm, form_c);
                        break;
                }

            },
            onFinishing: function (event, currentIndex) {
                if ($res = check_information("c", form_c)) {
                    modal.style.display = "block";
                    jQuery('#responseSent').html(getLoaderHtml());
                    return $res;
                } else {
                    return false;
                }
            },
            onFinished: function (event, currentIndex) {
                sendMessageBadge("c");
            }
        });
    }


    /**
     * @description To load the FIELD OF EDUCATION (PARENT)
     *              When you click on the .display_parent_categories to see the other "Field of Education" category (parent),
     *              the function call the "action_languages_form" in the other file.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {event} e of the event about the click
     */
    jQuery(".btn-change-children").click(function (e) {
        e.preventDefault();

        currentForm = checkForm(this);
        //Remove the class 'active' to the old button of the field of education.
        jQuery("#form_" + currentForm + " .btn-change-children.active").removeClass("active");
        //Add the class 'active' to the actual button.
        jQuery(this).addClass("active");

        jQuery("#field_" + currentForm).html(getLoaderHtml());

        var id_lan = jQuery(this).attr('id');
        id_lan = id_lan.replace(/\s/g, '');

        var data = {
            'action': 'ajaxShowFields',
            'form': currentForm,
            'slug': id_lan,
        };

        // since 2.8 globalUrl.ajax is always defined in the admin header and points to admin-ajax.php
        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                jQuery("#field_" + currentForm).html(response);
            }
        );
    });

    /**
     * @description To load the LEVEL.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {char} currentForm , contain the letter of the form.
     * @return {array} form the current form.
     */
    function load_levels(currentForm, form) {
        var fieldId = jQuery("#form_" + currentForm + " #field :selected").val();

        if (fieldId == "Select") {
            return false;
        }

        jQuery("#level_" + currentForm).html(getLoaderHtml());

        var data = {
            'action': 'ajaxShowLevels',
            'form': currentForm,
            'fieldId': fieldId
        };

        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                jQuery("#level_" + currentForm).html(response);

            }
        );

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    }

    /**
     * @description To load the BADGE.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {char} currentForm , contain the letter of the form.
     * @return {array} form the current form.
     */
    function load_badges(currentForm, form) {
        var check = false;
        var fieldId = jQuery("#form_" + currentForm + " #field :selected").val();
        var levelValue = "";

        jQuery("input[name='level_" + currentForm + "']")
            .each(function () {
                if (jQuery(this).is(':checked')) {
                    check = true;
                    levelValue = jQuery(this).val();
                }
            });

        if (!check) {
            return false;
        }

        jQuery("#badge_" + currentForm).html(getLoaderHtml());

        var data = {
            'action': 'ajaxShowBadges',
            'form': currentForm,
            'fieldId': fieldId,
            'level': levelValue
        };

        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                jQuery("#badge_" + currentForm).html(response);

            }
        );

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    }

    /**
     * @description To load the DESCRIPTION.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {char} currentForm , contain the letter of the form,
     * @return {array} form the current form.
     */
    function load_description(currentForm, form) {
        var badgeId = "";

        // Check if we selected a badge to permit to switch the page
        jQuery("input[name='badge_" + currentForm + "']")  // check if one badge is selected
            .each(function () {  // first pass, create name mapping
                if (jQuery(this).is(':checked')) {
                    badgeId = jQuery(this).val();
                }
            });

        // Badge no selected
        if (!badgeId) {
            return false;
        }

        // LOAD the GIF
        jQuery("#desc_" + currentForm).html(getLoaderHtml());

        // Data for the AJAX call
        var data = {
            'action': 'ajaxShowDescription',
            'form': currentForm,
            'badgeId': badgeId
        };

        // AJAX call
        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                jQuery("#desc_" + currentForm).html(response);
            }
        );

        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    }

    /**
     * @description To load the CLASS.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {char} currentForm , contain the letter of the form.
     * @return {array} form the current form.
     */
    function load_classes(currentForm, form) {
        var fieldId = jQuery("#form_" + currentForm + " #field :selected").val();
        var level = "";

        jQuery("input[name='level_" + currentForm + "']")
            .each(function () {
                if (jQuery(this).is(':checked')) {
                    level = jQuery(this).val();
                }
            });

        jQuery("#class_" + currentForm).html(getLoaderHtml());

        var data = {
            'action': 'ajaxShowClasses',
            'form': currentForm,
            'fieldId': fieldId,
            'level': level
        };
        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                jQuery("#class_" + currentForm).html(response);
            }
        );
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    }

    /**
     * @description Check if is selected the class.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {char} currentForm , contain the letter of the form.
     * @return {array} form the current form.
     */
    function check_class(currentForm, form) {
        var check = false;

        jQuery("input[name='class_" + currentForm + "']")  // for all checkboxes
            .each(function () {  // first pass, create name mapping
                    // I'm setting this var to understand if the class section is
                    // activated (WP Job Listing)
                    if (jQuery(this).is(':checked')) {
                        check = true;
                    }
                }
            );

        if (check || !check_class) {
            //Load description of language for the next page
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        } else if (!jQuery("#class-section").length) {
            return check_mails(currentForm, form);
        } else {
            return false;
        }
    }

    /**
     * @description Check if the the email/s contain only email and not garbage.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {char} currentForm , contain the letter of the form.
     * @return {array} form the current form.
     */
    function check_mails(currentForm, form) {
        var res = false;
        var mails = [jQuery("#mail_" + currentForm).val()];

        if (mails) {
            var patEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            if (currentForm == 'c') {
                mails = mails[0].split(",");
                for (var i = 0; i < mails.length; i++) {
                    mails[i] = mails[i].replace(/ /g, '')
                }
            }

            for (var i = 0; i < mails.length; i++) {
                res = patEmail.test(mails[i]);
                if (!res && mails[i] <= 220) return false;
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
     * @description Check if there are information with text more long
     *              than 10 letter and less than 1000.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {char} currentForm , contain the letter of the form.
     * @return {array} form the current form.
     */
    function check_information(currentForm, form) {
        var info = jQuery("#comment_" + currentForm).val();
        var evidence = jQuery("#evidence_" + currentForm).val();

        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i'); // fragment locator

        if ((info.length >= 10 && info.length < 1000) && (!evidence || pattern.test(evidence))) {
            // Everything good
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        } else {
            // No text
            return false;
        }
    }

    /**
     * @description TO SEND THE BADGE
     *              This function make an ajax call to permit to send the
     *              badge to the right person and also to store in the server.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {char} currentForm , contain the letter of the form.
     * @return {array} form the current form.
     */
    function sendMessageBadge(currentForm) {
        if (!clickedSendBadge) {
            clickedSendBadge = true;
            var fieldId;
            var levelId;
            var badgeId;
            var theClassId;
            var receivers;
            var info;
            var evidence;

            /* # FIELD ID # */
            fieldId = jQuery("#form_" + currentForm + " #field :selected").val();
            /* # LEVEL ID # */
            jQuery("input[name='level_" + currentForm + "']")
                .each(function () {
                    if (jQuery(this).is(':checked')) {
                        levelId = jQuery(this).val();
                    }
                });
            /* # BADGE ID # */
            jQuery("input[name='badge_" + currentForm + "']")
                .each(function () {
                    if (jQuery(this).is(':checked')) {
                        badgeId = jQuery(this).val();
                    }
                });
            /* # CLASS ID # */
            jQuery("input[name='class_" + currentForm + "']")
                .each(function () {
                    if (jQuery(this).is(':checked')) {
                        theClassId = jQuery(this).val();
                    }
                });
            /* # MAIL # */
            receivers = [jQuery("#mail_" + currentForm).val()];
            if (currentForm == 'c') {
                receivers = receivers[0].split(",");
                for (var i = 0; i < receivers.length; i++) {
                    receivers[i] = receivers[i].replace(/ /g, '')
                }
            }
            /* # INFO # */
            info = jQuery("#comment_" + currentForm).val();
            // EVIDENCE
            evidence = jQuery("#evidence_" + currentForm).val();

            var data = {
                'action': 'ajaxSendBadge',
                'form': currentForm,
                'badgeId': badgeId,
                'fieldId': fieldId,
                'levelId': levelId,
                'theClassId': theClassId,
                'info': info,
                'evidence': evidence,
                'receivers': receivers,
            };

            jQuery.post(
                globalUrl.ajax,
                data)
                .done(
                    function (response) {
                        jQuery('#responseSent').html(response);

                        // When the user clicks on <span> (x), close the modal
                        if (span) span.onclick = function () {
                            modal.style.display = "none";
                            location.reload();
                        }

                        // When the user clicks anywhere outside of the modal, close it
                        window.onclick = function (event) {
                            if (event.target == modal) {
                                modal.style.display = "none";
                                location.reload();
                            }
                        }
                    }
                )
                .fail(
                    function (xhr, textStatus, errorThrown) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                );
        }
        clickedSendBadge = false;
    }

    /**
     * @description This function permit to check the current form and save into a variable.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {event} event of the event about the click.
     */
    function checkForm(event) {
        if (jQuery(event).parents('#form_a').length == 1) {
            return "a";
        } else if (jQuery(event).parents('#form_b').length == 1) {
            return "b";
        } else if (jQuery(event).parents('#form_c').length == 1) {
            return "c";
        } else {
            throw "There aren't other Form."
        }

    }

    /**
     * @description Disable the selection of the tab from the
     *              new index until the current index.
     *
     * @author @AleRiccardi
     * @since  x.x.x
     *
     * @param {array} form the current.
     * @param {int} newIndex, contain the letter of the form.
     * @param {int} currentIndex, contain the letter of the form.
     */
    function disableTab(form, newIndex, currentIndex) {
        var doneDiv = form.find(".done");
        for (var i = newIndex; i < currentIndex; i++) {
            jQuery(doneDiv[i]).removeClass("done");
            jQuery(doneDiv[i]).addClass("disabled");
        }
        form.find(".current").each(function () {
            jQuery(this).removeClass("done");
            jQuery(this).addClass("disabled");
        });
    }


}
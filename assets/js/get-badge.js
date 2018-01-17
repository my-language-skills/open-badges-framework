/* =========================
    jQuery
   ========================= */
jQuery(function (event) {
    var clicked = false;

    /**
     * @description Get the param from the url.
     *
     * @param strinf name, the name of the param that we want to take
     */
    var urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    /**
     * @description Here's wrap the code that permit to simplify an
     *              ajax call.
     *
     * @param {array} data, that will send with the ajax call
     * @param {function} func, that will be execute after the
     *                         success of the ajax call
     */
    var ajaxCall = function (data, func) {
        jQuery.post(
            globalUrl.ajax,
            data)
            .done(
                function (response) {
                    func(response);
                    clicked = false;
                }
            )
            .fail(
                function (xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            );
    }

    /**
     * @description Build the html structure to show the loading gif.
     *
     * @return string html format
     */
    var loadingPage = function () {
        return ("<div class='cover-container'><header class='masthead clearfix'>" +
            "</header><main role='main' class='inner cover'>" +
            "<img src='" + globalUrl.loader + "' width='80px' height='auto'/>" +
            "</main>" +
            "<footer class='mastfoot'></footer></div>");
    }

    /**
     * @description Permit to check if inside an <input> exist a value, if
     *              exist add the class "is-valid", if not add "is-invalid".
     *
     *
     * @param {jQuery} input <input> tag with a 'value'
     * @return
     */
    var checkValue = function (input) {
        if (input.val() == "") {
            input.addClass("is-invalid");
            input.on("input", function (event) {
                if (input.val() != "") {
                    input.removeClass("is-invalid");
                    input.addClass("is-valid");
                } else {
                    input.removeClass("is-valid");
                    input.addClass("is-invalid");
                }
            });
        }
    }

    /**
     * @description Show the Mozilla Open Badge step inside the #gb-wrap <div>
     */
    var showGetMOBOpenBadges = function () {
        jQuery("#gb-wrap").fadeOut(400, function (event) {
            jQuery("#gb-wrap").html(loadingPage(event));

            // If the user have an account in Open Badge BackPack,
            // have also the permission to get the badge.
            var data = {
                'action': 'ajaxGbShowMozillaOpenBadges',
                'json': urlParam('json'),
                'badgeId': urlParam('badge'),
                'fieldId': urlParam('field'),
                'levelId': urlParam('level'),
            };

            var func = function (response) {
                jQuery("#gb-wrap").html(response);
            }

            ajaxCall(data, func);

        }).delay(400).fadeIn(400);
    }

    /**
     * @description Get definitely the badge and after show the conclusion
     *              step inside the #gb-wrap <div>
     *
     * @param {bool} mozOpenBadge
     * @return
     */
    var showConclusion = function (mozOpenBadge = false) {
        jQuery("#gb-wrap").fadeOut(400, function (event) {
            jQuery("#gb-wrap").html(loadingPage(event));

            var data = {
                'action': 'ajaxGbShowConclusion',
                'MOB': mozOpenBadge,
                'json': urlParam('json'),
                'badgeId': urlParam('badge'),
                'fieldId': urlParam('field'),
                'levelId': urlParam('level'),
            };

            var func = function (response) {
                jQuery("#gb-wrap").html(response);
            }

            ajaxCall(data, func);

        }).delay(400).fadeIn(400);
    }

    /**
     * @description Click event of the button continue of the first step.
     *
     * @param {array} event of the click
     * @return
     */
    var btnContinue = "#gb-continue";
    jQuery(document).on("click", btnContinue, function (event) {
        jQuery(btnContinue).prop('disabled', true);

        jQuery("#gb-wrap").fadeOut(400, function (event) {
            jQuery("#gb-wrap").html(loadingPage());

            var data = {
                'action': 'ajaxGbShowLogin',
                'json': urlParam('json'),
                'badgeId': urlParam('badge'),
                'fieldId': urlParam('field'),
                'levelId': urlParam('level'),
            };

            var func = function (response) {
                jQuery("#gb-wrap").html(response);
                jQuery(btnContinue).prop('disabled', false);
            }

            ajaxCall(data, func);

        }).delay(400).fadeIn(400);
    });

    /**
     * @description Click event of the button login.
     *
     * @param {array} event of the click
     */
    var btnLogin = "#gb-form-login";
    jQuery(document).on("submit", btnLogin, function (event) {
        jQuery(btnLogin).prop('disabled', true);
        event.preventDefault();

        var email = jQuery("#staticEmail").val();
        var password = jQuery("#inputPassword").val();
        var remember = jQuery("#inputRemember").is(':checked');
        var data = {
            'action': 'ajaxGbLogin',
            'badgeId': urlParam('badge'),
            'fieldId': urlParam('field'),
            'levelId': urlParam('level'),
            'user_email': email,
            'user_password': password,
            'remember': remember,

        };

        var func = function (response) {
            if (response == true) {
                showGetMOBOpenBadges();
            } else {
                jQuery("#gb-resp-login").html(response);
            }
            jQuery(btnLogin).prop('disabled', false);
        }

        ajaxCall(data, func);
    });

    /**
     * @description Click event of the button registration.
     *
     * @param {array} event of the click
     */
    var formRegister = "#gb-form-registration";
    var responseRegister = "#gb-resp-register";
    var btnRegister = "#submit-form";
    var lblRegister = "#lbl-submit-form";
    jQuery(document).on("submit", formRegister, function (event) {
        jQuery(btnRegister).prop('disabled', true);
        jQuery(lblRegister).addClass("disabled");

        event.preventDefault();
        jQuery(responseRegister).html("");

        var inputFields = [
            jQuery(this).find("#reg-email"),
            jQuery(this).find("#reg-user-name"),
            jQuery(this).find("#reg-first-name"),
            jQuery(this).find("#reg-last-name"),
            jQuery(this).find("#reg-pass"),
            jQuery(this).find("#reg-repeat-pass"),
        ];

        if (this.checkValidity() === false) {
            event.stopPropagation();
            inputFields.forEach(function (field) {
                checkValue(field);
            });
            jQuery(btnRegister).prop('disabled', false);
            jQuery(lblRegister).removeClass("disabled");
        } else {

            var data = {
                'action': 'ajaxGbRegistration',
                'json': urlParam('json'),
                'badgeId': urlParam('badge'),
                'fieldId': urlParam('field'),
                'levelId': urlParam('level'),
                'user_email': inputFields[0].val(),
                'user_name': inputFields[1].val(),
                'first_name': inputFields[2].val(),
                'last_name': inputFields[3].val(),
                'user_pass': inputFields[4].val(),
                'user_rep_pass': inputFields[5].val(),
            };

            var func = function (response) {
                if (response == 0) {
                    showGetMOBOpenBadges();
                } else if (response) {
                    jQuery(responseRegister).html(response);
                }
                jQuery(btnRegister).prop('disabled', false);
                jQuery(lblRegister).removeClass("disabled");

            }
            ajaxCall(data, func);
        }
        this.classList.add('was-validated');
    });

    /**
     * @description Click event of get badge button in Mozilla Open badge step.
     *
     * @param {array} event of the click
     */
    var btnGetBadgeMob = "#gb-ob-get-badge"
    jQuery(document).on("click", btnGetBadgeMob, function (event) {
        jQuery(btnGetBadgeMob).prop('disabled', true);

        var data = {
            'action': 'ajaxGbGetJsonUrl',
            'json': urlParam('json'),
            'badgeId': urlParam('badge'),
            'fieldId': urlParam('field'),
            'levelId': urlParam('level'),
        };

        var func = function (response) {
            OpenBadges.issue([response], function (errors, successes) {
                if (successes.length) {
                    showConclusion(true);
                } else if (errors.length) {
                    jQuery("#gb-ob-response").html("Badge not sent!")
                }
                jQuery(btnGetBadgeMob).prop('disabled', false);

            });
        }
        ajaxCall(data, func);

    });

    /**
     * @description Click event of skip get Mozilla Open Badge.
     *
     * @param {array} event  of the click
     */
    var btnGetBadgeStandard = "#gb-get-standard";
    jQuery(document).on("click", btnGetBadgeStandard, function (event) {
        jQuery(btnGetBadgeStandard).prop('disabled', false);
        showConclusion(false);
        jQuery(btnGetBadgeStandard).prop('disabled', true);
    });
});

/* =========================
    jQuery
   ========================= */
$(function (event) {
    var clickedGetBadge = false;
    var urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    /**
     * @description Here's wrap all the code to make an ajax and
     *              we need to worry about only 2 variable.
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
                }
            )
            .fail(
                function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            );
    }

    /**
     * @description It's building the html structure to show the loading gif.
     *
     * @param {array} event, of the click
     * @return string html format
     */
    var loadingPage = function (event) {
        return ("<div class='cover-container'><header class='masthead clearfix'>" +
            "</header><main role='main' class='inner cover'>" +
            "<img src='" + globalUrl.loader + "' width='200px' />" +
            "</main>" +
            "<footer class='mastfoot'></footer></div>");
    }

    /**
     * @description Permit to check if inside an <input> exist a value, if
     *              exist add the class "is-valid", if not add "is-invalid".
     *
     *
     * @param {$} input <input> tag with a 'value'
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
     * @description Show the Mozilla Open Badge
     *
     * @param {array} event
     * @return
     */
    var showGetMOBOpenBadges = function () {
        $("#gb-wrap").fadeOut(400, function (event) {
            $("#gb-wrap").html(loadingPage());

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
                $("#gb-wrap").html(response);
            }

            ajaxCall(data, func);

        }).delay(400).fadeIn(400);
    }

    /**
     * @description Show the conclusion step
     *
     * @param {array} event
     * @return
     */
    var showConclusion = function (mozOpenBadge = false) {
        $("#gb-wrap").fadeOut(400, function (event) {
            $("#gb-wrap").html(loadingPage());

            var data = {
                'action': 'ajaxGbShowConclusion',
                'MOB': mozOpenBadge,
                'badgeId': urlParam('badge'),
                'fieldId': urlParam('field'),
                'levelId': urlParam('level'),
            };

            var func = function (response) {
                $("#gb-wrap").html(response);
            }

            ajaxCall(data, func);

        }).delay(400).fadeIn(400);
    }

    /**
     * @description Click event of the button continue
     *
     * @param {array} event of the click
     * @return
     */
    $(document).on("click", "#gb-continue", function (event) {
        $("#gb-wrap").fadeOut(400, function (event) {
            $("#gb-wrap").html(loadingPage());

            var data = {
                'action': 'ajaxGbShowLogin',
                'json': urlParam('json'),
                'badgeId': urlParam('badge'),
                'fieldId': urlParam('field'),
                'levelId': urlParam('level'),
            };

            var func = function (response) {
                $("#gb-wrap").html(response);
            }

            ajaxCall(data, func);

        }).delay(400).fadeIn(400);
    });

    /**
     * @description Click event of the button login
     *
     * @param {array} event of the click
     */
    $(document).on("submit", "#gb-form-login", function (event) {
        event.preventDefault();

        var email = $("#staticEmail").val();
        var password = $("#inputPassword").val();
        var remember = $("#inputRemember").is(':checked');

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
            if (response != true) {
                $("#gb-resp-login").html(response);
            } else {
                showGetMOBOpenBadges();
            }
        }

        ajaxCall(data, func);
    });

    /**
     * @description Click event of the button registration
     *
     * @param {array} event of the click
     */
    $(document).on("submit", "#gb-form-registration", function (event) {
        event.preventDefault();
        $("#gb-resp-register").html("");

        var inputFields = [
            $(this).find("#reg-email"),
            $(this).find("#reg-user-name"),
            $(this).find("#reg-first-name"),
            $(this).find("#reg-last-name"),
            $(this).find("#reg-pass"),
            $(this).find("#reg-repeat-pass"),
        ];


        if (/*checkPasswords(passwFields) == false || */this.checkValidity() === false) {

            event.stopPropagation();

            inputFields.forEach(function (field) {
                checkValue(field);
            });

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
                    $("#gb-resp-register").html(response);
                }
            }

            ajaxCall(data, func);
        }
        this.classList.add('was-validated');
    });

    /**
     * @description Click event of get badge button in Mozilla Open badge Step
     *
     * @param {array} event of the click
     */
    $(document).on("click", "#gb-ob-get-badge", function (event) {
        if (!clickedGetBadge) {
            clickedGetBadge = true;
            var thisBtn = $(this);
            thisBtn.html("<img src='" + globalUrl.loaderPoint + "' width='150px' />");

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
                        $("#gb-ob-response").html("Badge not sent!")
                        thisBtn.html("Get the badge");
                    }
                    clickedGetBadge = false;
                });
            }
            ajaxCall(data, func);
        }
    });

    /**
     * @description Click event of skip get Mozilla Open Badge.
     *
     * @param {array} event  of the click
     */
    $(document).on("click", "#gb-get-standard", function (event) {
        showConclusion(false);
    });


});

/* =========================
    Classes
   ========================= */

class Rectangle {
    constructor(height, width) {
        this.height = height;
        this.width = width;
    }

    // Getter
    get area() {
        return this.calcArea();
    }

    // Method
    calcArea() {
        return this.height * this.width;
    }
}


/* =========================
    jQuery
   ========================= */
$(function () {
    var urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    var loadingPage = function () {
        return ("<div id='wrap-login' class='site-wrapper-inner'>" +
            "<div class='cover-container'><header class='masthead clearfix'>" +
            "</header><main role='main' class='inner cover'>" +
            "<img src='" + globalUrl.loader + "' width='200px' />" +
            "</main>" +
            "<footer class='mastfoot'></footer></div></div>");
    }

    var checkValue = function (input) {
        if (input.val() == "") {
            input.addClass("is-invalid");
            input.on("input", function () {
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

    var checkPasswords = function (arrayOfFields) {
        if (arrayOfFields[0].val() != "") {
            if (arrayOfFields[0].val() != arrayOfFields[1].val()) {
                arrayOfFields[1].addClass("is-invalid");
                arrayOfFields[1].on("input", function () {
                    if (arrayOfFields[0].val() != "" && arrayOfFields[0] != arrayOfFields[1]) {
                        input.removeClass("is-invalid");
                        input.addClass("is-valid");
                    } else {
                        input.removeClass("is-valid");
                        input.addClass("is-invalid");
                    }
                });
            }
        }
    }

    $(document).on("click", "#getBadge", function () {
        $("#gb-wrap").fadeOut(400, function () {
            $("#gb-wrap").html(loadingPage());

            var data = {
                'action': 'ajaxGbShowLogin',
                'json': urlParam('json'),
            };

            jQuery.post(
                globalUrl.ajax,
                data,
                function (response) {
                    $("#gb-wrap").html(response);
                }
            );
        }).delay(400).fadeIn(400);
    });

    /*
     * LOGIN page
     */

    $(document).on("submit", "#gb-form-login", function () {
        event.preventDefault();

        var email = $("#staticEmail").val();
        var password = $("#inputPassword").val();
        var remember = $("#inputRemember").is(':checked');

        var data = {
            'action': 'ajaxGbLogin',
            'user_email': email,
            'user_password': password,
            'remember': remember,
        };

        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                if (response != true) {
                    $("#gb-resp-login").html(response);
                } else {
                    loginApproved();
                }

            }
        );
    });

    function loginApproved() {
        $("#gb-wrap").fadeOut(400, function () {
            $("#gb-wrap").html(loadingPage());
            var data = {
                'action': 'ajaxGbShowOpenBadgesLogin',
                'json': urlParam('json'),
            };

            jQuery.post(
                globalUrl.ajax,
                data,
                function (response) {
                    $("#gb-wrap").html(response);
                }
            );
        }).delay(400).fadeIn(400);
    }

    $(document).on("click", "#gb-register-link", function () {
        var email = $("#staticEmail").val();

        var data = {
            'action': 'ajaxGbShowRegister',
            'user_email': email,
        };

        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                $("#gb-wrap").html(response);
            }
        );
    });

    /*
     * REGISTER page
     */
    $(document).on("submit", "#gb-form-registration", function () {
        event.preventDefault();

        console.log(event);

        if (this.checkValidity() === false) {
            event.stopPropagation();

            var inputFields = [
                $(this).find("#firstName"),
                $(this).find("#lastName"),
                $(this).find("#username"),
            ];

            var passwFields = [
                $(this).find("#inputPassword"),
                $(this).find("#inputRepeatPassword"),
            ];

            inputFields.forEach(function (input) {
                checkValue(input);
            });

            checkValue(passwFields[0]);
            checkPasswords(passwFields);

            /*
            if (!$(this).find("#firstName").val()) {
                $(this).find("#firstName").addClass( "is-invalid" );
            } else {
                $(this).find("#firstName").removeClass("is-invalid");
            }

            if (!$(this).find("#lastName").val()) {
                $(this).find("#lastName").addClass( "is-invalid" );
            } else {
                $(this).find("#lastName").removeClass("is-invalid");
            }

            if (!$(this).find("#username").val()) {
                $(this).find("#username").addClass( "is-invalid" );
            } else {
                $(this).find("#username").removeClass("is-invalid");
            }
            */


        } else {

        }
        this.classList.add('was-validated');

        /*
        var email = $("#staticEmail").val();
        var password = $("#inputPassword").val();
        var remember = $("#inputRemember").is(':checked');

        var data = {
            'action': 'ajaxGbLogin',
            'user_email': email,
            'user_password': password,
            'remember': remember,
        };

        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                if (response != true) {
                    $("#gb-resp-login").html(response);
                } else {
                    registrationApproved();
                }

            }
        );*/
    });

    function registrationApproved() {
        $("#gb-wrap").fadeOut(400, function () {
            /*$("#gb-wrap").html(loadingPage());
            var data = {
                'action': 'ajaxGbShowOpenBadgesLogin',
                'json': urlParam('json'),
            };

            jQuery.post(
                globalUrl.ajax,
                data,
                function (response) {
                    $("#gb-wrap").html(response);
                }
            );*/
        }).delay(400).fadeIn(400);
    }

    /*
     * GET BADGE page
     */

    $(document).on("click", "#gb-ob-get-badge", function () {

        var data = {
            'action': 'ajaxGbGetJsonUrl',
            'json': urlParam('json'),
        };

        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                OpenBadges.issue([response], function (errors, successes) {
                    console.log("Successes" + successes + " \n Errors" + errors);
                });
            }
        );


    });


});

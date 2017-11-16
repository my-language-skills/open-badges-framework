$(function () {
    var urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    $("#getBadge").click(function () {
        $("#gb-wrap").fadeOut(400, function () {
            $("#gb-wrap").html("<div id='wrap-login' class='site-wrapper-inner'><div class='cover-container'><header class='masthead clearfix'></header><main role='main' class='inner cover'><img src='" + globalUrl.loader + "' width='200px' /></main><footer class='mastfoot'></footer></div></div>");


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


    $(document).on("submit", "#gb-form-login", function () {
        event.preventDefault();

        var email = $("#staticEmail").val();
        var password = $("#inputPassword").val();
        var remember = $("#inputRemember").is(':checked');

        var data = {
            'action': 'ajaxGbLogin',
            'user_login': email,
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

    $(document).on("submit", "#gb-form-open-badges-login", function () {
        event.preventDefault();

        var data = {
            'action': 'ajaxGbGetJsonUrl',
            'json': urlParam('json'),
        };

        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                /*OpenBadges.issue([response], function (errors, successes) {
                    alert("Errors:" + errors + " Successes:" + successes)
                });*/
                OpenBadges.issue_no_modal(response);
            }
        );
    });

})
;

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
            $("#gb-wrap").html("<div id='wrap-login' class='site-wrapper-inner'><div class='cover-container'><header class='masthead clearfix'></header><main role='main' class='inner cover'><img src='" + globalUrl.loader + "' width='200px' /></main><footer class='mastfoot'></footer></div></div>");

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

        OpenBadges.connect({
            callback: window.location.href,
            scope: ['issue']
        });
    });


    $(document).on("click", "#gb-button", function () {
        var error = "";
        var access = urlParam('access_token');//use this to push to the earner Backpack
        var refresh = urlParam('refresh_token');
        var expiry = urlParam('expires');
        var api = urlParam('api_root');
        var json = urlParam('json');

        var data = {
            'action': 'ajaxGbGetJsonUrl',
            'json': urlParam('json'),
        };

        jQuery.post(
            globalUrl.ajax,
            data,
            function (response) {
                json = response;
            }
        );

        if (json) {
            var requestOptions = {
                host: 'backpack.openbadges.org',//adjust for your api root
                path: '/api/issue',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + b64enc(access),
                    'Content-Type': 'application/json',
                    'Content-Length': Buffer.byteLength(json)
                }
            };

            var postRequest = http.request(requestOptions, function (pushResponse) {
                var response = [];
                pushResponse.setEncoding('utf8');

                //store data
                pushResponse.on('data', function (responseData) {
                    response.push(responseData);
                });

                pushResponse.on('end', function () {
                    var pushData = JSON.parse(response.join(''));
                    //...
                });
            });

            postRequest.on('error', function (e) {
                console.error(e);
            });

            // post the data
            postRequest.write(assertionData);
            postRequest.end();
        }
    });

});



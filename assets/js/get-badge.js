/* =========================
    jQuery
   ========================= */
jQuery(function (event) {
    var clicked = false;

    /**
     * @description Get the param from the url.
     *
     * @param strinf name, the name of the param that we want to take.
     */
    var urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    /**
     * @description Here's wrap the code that permit to simplify an ajax call.
     *
     * @param {array} data, that will send with the ajax call.
     * @param {function} func, that will be execute after the success of the ajax call,
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
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return string html format.
     */
    var loadingPage = function () {
        return ("<header class='masthead mb-auto'>" +
            "</header><main role='main' class='inner cover'>" +
            "<img src='" + globalUrl.loader + "' width='80px' height='auto'/>" +
            "</main>" +
            "<footer class='mastfoot mt-auto'></footer>");
    }

    /**
     * @description Permit to check if inside an <input> exist a value, if
     *              exist add the class "is-valid", if not add "is-invalid".
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param {jQuery} input <input> tag with a 'value'.
     *
     * @return void
     */
    var checkValue = function (input) {
        if (input.val() == "" && input.prop('required')) {
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

    var validateEmail = function (email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if( !re.test(email.val() ) ){
            email.addClass("is-invalid");
            email.on("input", function (event) {
                if( re.test(email.val() ) ){
                    email.removeClass("is-invalid");
                    email.addClass("is-valid");
                } else {   
                    email.removeClass("is-valid");
                    email.addClass("is-invalid");
                }
            });
        }
    }

    /**
     * @description Show the Mozilla Open Badge step inside the #gb-wrap <div>.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return void
     */
    var showGetMOBOpenBadges = function () {
        jQuery("#gb-wrap").fadeOut(400, function (event) {
            jQuery("#gb-wrap").html(loadingPage(event));

            // If the user have an account in Open Badge BackPack,
            // have also the permission to get the badge.
            var data = {
                'action': 'ajaxGbShowMozillaOpenBadges',
                'idBadge': urlParam('v'),
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
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param {boolean} mozOpenBadge
     * @return void
     */
    var showConclusion = function (mozOpenBadge = 0) {
        jQuery("#gb-wrap").fadeOut(400, function (event) {
            jQuery("#gb-wrap").html(loadingPage(event));
            var data = {
                'action': 'ajaxGbShowConclusion',
                'isMozilla': mozOpenBadge,
                'idBadge': urlParam('v'),
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
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param {array} event of the click
     * @return vodi
     */
    var btnContinue = ".continueLink";
    jQuery(document).on("click", btnContinue, function (event) {
        jQuery(btnContinue).prop('disabled', true);

        jQuery("#gb-wrap").fadeOut(400, function (event) {
            jQuery("#gb-wrap").html(loadingPage());

            var data = {
                'action': 'ajaxGbShowLogin',
                'idBadge': urlParam('v'),
            };

            var func = function (response) {
                jQuery("#gb-wrap").html(response);
                jQuery(btnContinue).prop('disabled', false);
            }

            ajaxCall(data, func);

        }).delay(400).fadeIn(400);
    });

    /**
     * @description Submit event of the button login.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param {array} event of the click
     *
     * @return void
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
            'idBadge': urlParam('v'),
            'userEmail': email,
            'userPassword': password,
            'remember': remember,

        };

        var func = function (response) {
            if (response == true) {
                showGetMOBOpenBadges();
            } else {
				
                jQuery("#gb-resp-login").html(response);
				jQuery("#gb-resp-login").addClass("alert alert-warning");
            }
            jQuery(btnLogin).prop('disabled', false);
        }

        ajaxCall(data, func);
    });

    /**
     * @description Click event of the button registration.
     *
     * @author @AleRiccardi, @leocharlier
     * @since  1.0.0
     *
     * @param {array} event of the click.
     *
     * @return void
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
            jQuery(this).find("#reg-year"),
            jQuery(this).find("#reg-country"),
            jQuery(this).find("#reg-city"),
            jQuery(this).find("#reg-mother-tongue"),
            jQuery(this).find("#reg-primary-degree"),
            jQuery(this).find("#reg-secondary-degree"),
            jQuery(this).find("#reg-tertiary-degree"),
            jQuery(this).find("#reg-captcha-answer"),
            jQuery(this).find("#reg-captcha-prefix")
        ];

        if (this.checkValidity() === false) {
            event.stopPropagation();
            inputFields.forEach(function (field) {
                checkValue(field);
            });
            mail = jQuery(this).find("#reg-email");
            validateEmail(mail);
            jQuery(btnRegister).prop('disabled', false);
            jQuery(lblRegister).removeClass("disabled");
        } else {

            var data = {
                'action': 'ajaxGbRegistration',
                'idBadge': urlParam('v'),
                'userEmail': inputFields[0].val(),
                'userName': inputFields[1].val(),
                'firstName': inputFields[2].val(),
                'lastName': inputFields[3].val(),
                'userPassword': inputFields[4].val(),
                'userRepPass': inputFields[5].val(),
                'userYear': inputFields[6].val(),
                'userCountry': inputFields[7].val(),
                'userCity': inputFields[8].val(),
                'userMotherTongue': inputFields[9].val(),
                'userPrimaryDegree': inputFields[10].val(),
                'userSecondaryDegree': inputFields[11].val(),
                'userTertiaryDegree': inputFields[12].val(),
                'captchaAnswer': inputFields[13].val(),
                'captchaPrefix': inputFields[14].val()
            };

            var func = function (response) {
                if (response == 0) {
                    showGetMOBOpenBadges();
                } else if (response) {
                    jQuery(responseRegister).html(response);
					jQuery(responseRegister).addClass("alert alert-warning");
                }
                jQuery(btnRegister).prop('disabled', false);
                jQuery(lblRegister).removeClass("disabled");

            }
            ajaxCall(data, func);
        }
        this.classList.add('was-validated');
    });
    
    /**
     * @description Click event of skip get Mozilla Open Badge.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param {array} event  of the click
     *
     * @return void
     */
    var btnGetBadgeStandard = "#gb-get-standard";
    jQuery(document).on("click", btnGetBadgeStandard, function (event) {
        jQuery(btnGetBadgeStandard).prop('disabled', false);
        showConclusion(0);
        jQuery(btnGetBadgeStandard).prop('disabled', true);
    });

    /**
     * @description Click event of get badge button in Mozilla Open badge step.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param {array} event of the click
     *
     * @return void
     */
    var btnGetBadgeMob = "#gb-ob-get-badge"
    jQuery(document).on("click", btnGetBadgeMob, function (event) {
        jQuery(btnGetBadgeMob).prop('disabled', true);
        
        var data = {
            'action': 'ajaxGbGetJsonUrl',
            'idBadge': urlParam('v'),
        };

        var func = function (response) {
            //gets the issuer.json from uploads.
            jQuery.getJSON(response,function(data){
                //creates the requests format for the new API
                restructureForNewApi(data);
            })
            .fail(function()
            {
                console.log("failed to retrieve issuer json file from uploads");
            });
            //previous API call
            /* OpenBadges.issue([response], function (errors, successes) {
                if (successes.length) {
                    showConclusion(1);
                } else if (errors.length) {
                    jQuery("#gb-ob-response").html("Badge not sent!")
                }
                jQuery(btnGetBadgeMob).prop('disabled', false);

            });  */
        }
        ajaxCall(data, func); 

    });
    /**
     * @description Gets the URL of the picture and creates a data URI for the picture
     * 
     * @author      @CharalamposTheodorou
     * @since       @2.0
     * 
     * @param {String} imageURL: url of the request image. 
     * 
     * @return      data IRI of image
     */
    var imageTransformation = async function(imageURL)
    {
        let blob = await fetch(imageURL).then(r => r.blob());
        let dataUrl = await new Promise(resolve => {
        let reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.readAsDataURL(blob);
        });
        //image format for the POST request
        return JSON.stringify(dataUrl);
        
        //return dataUrl;
    }

    /**
     * @description Creates the new format of the request for the new API and calls the
     *              functions for making the requests and all necessary checks.
     * @author      @CharalamposTheodorou
     * @since       @2.0
     * 
     * @param {String} response 
     * 
     * @return      void
     */
    var restructureForNewApi = function(data)
    {//we create the request for the assertion if badgeclass is not created we create it, if issuer to setup, we setup..
        console.log(data);
        //token actions for creating/refreshing token.
        token();
        var check_to_proceed = function(response)
        {
            //fail scenario for previous checks
            if (response == "fail")
            {    
                console.log("Fail in accessing Badgr requests");
                return;
            }
             //current data format (settings for each POST request to the backpack)
            var data = JSON.parse(response);
            //response will have error or data to send for each case.
            if (data.body.type == "Profile")
            {
                console.log(data);
                //issuer(data);
            }
            else if (data.body.type == "BadgeClass")
            {
                console.log(data);
                //badgeClass();
            }
            else if (data.body.type == "Assertion")
            {
                console.log(data);
                //assertion();
            }
            else
            {
                console.log("error:"+response);
            } 
        }
        //checks if token exists. creates data format for new request
        ajaxCall({'action':'ajaxBadgrRequestcheck','section':'issuer','data':data,},check_to_proceed);
        //checks if issuer exists. creates data format for new request
        ajaxCall({'action':'ajaxBadgrRequestcheck','section':'BadgeClass','data':data,},check_to_proceed);
        //checks if badgeclass exists. creates data format for new request
        ajaxCall({'action':'ajaxBadgrRequestcheck','section':'Assertion','data':data,},check_to_proceed);

        jQuery("#gb-ob-response").html("So far so good!")
        jQuery(btnGetBadgeMob).prop('disabled', false);
    }
    
    /**
     * @description Checks if Token exists and if still valid, requests new Token activation.
     *              Token is stored on the uploads folder of the installation. Can be accessed for all the requests.
     * @author      @CharalamposTheodorou
     * @since       @2.0
     * 
     * @return      void
     */
    var token = function()
    {
        var token_configured = function(response)
        {//Here token is configured and checked if expired (Requested again if expired).
            console.log(    );
            if (response!="saved")
            {//something is wrong with requests for the token. disabling the form.
                jQuery("#gb-ob-response").html("Badge not sent!");
                jQuery(btnGetBadgeMob).prop('disabled', false);
            }

        }
        var token_exists_reply = function(response)
        {
            if (response == "Not Found")
            {//token json file doesn't exists. No account is registered. Assumption that badgr account exists for issuer
                console.log("Token doesn't exists.Moving to Token creation");
                console.log("Token will be created for eu badgr server");
                var username = prompt("Enter email here for issuer account creation:");
                while (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(username))
                {
                    console.log("Not valid email");
                    username = prompt("Enter email here for issuer account creation:");
                }
                var password = prompt("Enter password here for issuer account creation:");
                ajaxCall({'action':'ajaxIssuerTokenRequest','issuer_username':username,'issuer_password':password,},token_configured);
            }
            else
            {//token exists check if expired or not.
                //make simple request to get the issuer user. to see if returned code is okay
                ajaxCall({'action':'ajaxIssuerTokenExpiration',},token_configured); 
                //here the token is configured.
            }
        }
        ajaxCall({'action':'ajaxIssuerTokenExistsRequest',},token_exists_reply);
    }

  
    /**
     * @description Makes all necessary checks for the issuer, requests for new issuer if not configured.
     * 
     * @author      @CharalamposTheodorou
     * @since       @2.0
     * s
     * @return      void
     */
    var issuer = function(data)
    {
       var issuer_reply = function(response)
        {//request for if user exists as Issuer, if not then requested here.
            console.log(response);
            if (response == "Not Found")
            {//issuer token file doesn't exist. This should not be triggered. Token is being taker of before.
                console.log("token not found");
                //issuer token doesn't exists.
            }
            else
            {//token file exists.
                console.log("Issuer is created");
            }
        }
        ajaxCall({'action':'ajaxIssuerExistRequest','issuer_profile':data},issuer_reply);
        
    }

    var badgeClass = function(data)
    {
        console.log(data);
        /* var badgeClass_reply = function(response)
        {//request for if user exists as Issuer, if not then requested here.
            console.log(response);
            if (response == "Not Found")
            {//issuer token file doesn't exist. This should not be triggered. Token is being taker of before.
                console.log("token not found");
                //issuer token doesn't exists.
            }
            else
            {//token file exists.
                console.log("Issuer is created");
            }
        }
        ajaxCall({'action':'ajaxBadgeClassExistRequest','BadgeClass':data},badgeClass_reply); */
    }
    /**
     * @description Makes the assertion request. In this step of the process all necessary checks for
     *              issuer and badgeclasses have already happen. last request to send the badge to student backpack.
     * @author      @CharalamposTheodorou
     * @since       @2.0
     * 
     * @param {JSON Object} data_obj json object for the data of the assertion POST request. 
     * 
     * @return      void
     */
    var assertionRequest = function(data_obj)
    {
        var settings = {
            "url": "https://api.badge.io/v2/badgeclasses/"+BADGECLASS_ENTITY_ID+"/assertions",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Authorization": "Bearer "+ISSUER_TOKEN_ID,
                "Content-Type": "application/json"
            },
            "data": JSON.stringify(data_obj),
        };

        jQuery.ajax(settings).done(function(response) {
            console.log(response);
        }).fail(function(response,error)
        {   
            console.log(response+":"+error);
        });
    }

     /**
     * @description Makes the badgeClass request. In this step of the process all necessary checks for
     *              issuer and badgeclasses have already happen. BadgeClass created and stored on backpack only once.
     * @author      @CharalamposTheodorou
     * @since       @2.0
     * 
     * @param {JSON Object} data_obj json object for the data of the BadgeClass POST request. 
     * 
     * @return      void
     */
    var badgeClassRequest = function(data_obj)
    {
        var settings = {
            "url": "https://api.badgr.io/v2/issuers/"+ISSUER_ENTITY_ID+"/badgeclasses",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Authorization": "Bearer "+ISSUER_TOKEN_ID,
            },
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": data_obj
        };
        jQuery.ajax(settings).done(function(response){
            console.log(response);
        }).fail(function(response,error)
        {
            console.log(response+":"+error);
        });
    }

     /**
     * @description Makes the assertion request. In this step of the process all necessary checks for
     *              issuer. Issuer request happens only once, very first time the get badge is pressed.
     * @author      @CharalamposTheodorou
     * @since       @2.0
     * 
     * @param {JSON Object} data_obj json object for the data of the issuer POST request. 
     * 
     * @return      void
     */
    var issuerRequest = function()
    {
        var settings = {
            "url": "https://api.badgr.io/v2/issuers",
            "method": "POST",
            "timeout": 0,
            "headers": {
              "Authorization": "Bearer 1682dPZvNZF7Xo3wKQmhvFaQXQouuf"
            },
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": data_obj
          };
          
          jQuery.ajax(settings).done(function (response) {
            console.log(response);
          }).fail(function(response,error){
              console.log(response+":"+error);
          });

          //this should return the token id created previously and then we create the user an issuer
          var issuer_request = function(response) {

          }
          ajaxCall()

    }

    
});
 
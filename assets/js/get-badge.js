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
        
        //token actions for creating/refreshing token.
        token();
        //initiating the new API process. Checks if Issuer created, then if current badge created and finally checks and creates the Assertion.
        issuer_process(data);
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
     * @description Checks for any issuer problems/erros and makes the post request for new issuer if
     *              not found.
     * @author      @CharalamposTheodorou
     * @since       @2.0
     *  
     * @return      void
     */
    function issuer_process(data)
    {
        var check_to_proceed = function(response)
        {
            var data = JSON.parse(response);
            if (data.errors.length == 0)
            {//checking if everything was okay with the issuer creation. 

                //check if any uppdates happen to issuer (post request for new issuer).
                if (data.create.includes("issuer"))
                {
                    var settings = {
                        "url": data.issuer.url,
                        "method": data.issuer.method,
                        "timeout": data.issuer.timeout,
                        "headers": {
                            "Authorization": data.issuer.headers.Authorization,
                            "Content-Type": data.issuer.headers.Content_type,
                        },
                        "data": JSON.stringify(data.issuer.data),
                    };
                    jQuery.ajax(settings).done(function (response) {
                        if (response.status.description == "ok")
                        {    
                            console.log("Issuer POST request Success");
                            //data to store to file.
                            var data_to_store= new Object();
                            data_to_store.entityId = response.result[0].entityId;
                            data_to_store.name = response.result[0].name;
                            //issuer is registered here
                            //updating badgr file contents
                            var update_reply = function(response)
                            {
                                var data_response = JSON.parse(response);
                                if(data_response.success == "success")
                                {    
                                    //issuer process is done, safe to proceed to badge class process
                                    jQuery("#gb-ob-response").html("Issuer Created")
                                    badgeClass_process(data.data);
                                }
                                else
                                    console.log('problem with issuer data stored to badgr file');
                            }
                            ajaxCall({'action':'updateBadgrEntitiesFile','section':'issuer','data':data_to_store,},update_reply);

                        }
                        else
                        {
                            console.log("Issuer POST request failure:"+response.status.description);
                            jQuery("#gb-ob-response").html("Issuer POST request failed");
                        }
                      });
                }
                else
                {    
                    //issuer process is done, safe to proceed to badge class process
                    jQuery("#gb-ob-response").html("Issuer Already created before");
                    badgeClass_process(data.data);
                }
            }
            else
                console.log("errors existing:"+data.errors);
        }
        ajaxCall({'action':'checkAndCreateIssuerEntity','data':data,},check_to_proceed);
    }

    /**
     * @description Checks for any badge class problems/erros and makes the post request for new 
     *              badge class if not found.
     * @author      @CharalamposTheodorou
     * @since       @2.0
     *  
     * @return      void
     */
    function badgeClass_process(data)
    {
        var check_to_proceed = function(response)
        {
            var data = JSON.parse(response);
            if(data.errors.length == 0)
            {//checking if everything was okay with the badge creation.
                
                //check if any uppdates happen to issuer (post request for new issuer).
                if (data.create.includes("badgeClass"))
                {
                    var settings = {
                        "url": data.badge.url,
                        "method": data.badge.method,
                        "timeout": data.badge.timeout,
                        "headers": {
                            "Authorization": data.badge.headers.Authorization,
                            "Content-Type": data.badge.headers.Content_type,
                        },
                        "data": JSON.stringify(data.badge.data),
                    };
                    jQuery.ajax(settings).done(function (response) {
                        if (response.status.description == "ok")
                        {
                            console.log("Badge Class POST request Success");
                            //data to store to the identities file.
                            var data_to_store = new Object();
                            data_to_store.entityId = response.result[0].entityId;
                            data_to_store.name = response.result[0].name;
                            var update_reply = function(response)
                            {
                                var data_response = JSON.parse(response);
                                if (data_response.success == "success")
                                {
                                    //badge class process is done, safe to proceed to assertion process
                                    jQuery("#gb-ob-response").html("Badge Class Created")
                                    assertion_process(data.data);
                                }
                                else
                                    console.log("problem with badge data stored to badgr file")
                            }
                            ajaxCall({'action':'updateBadgrEntitiesFile','section':'badge','data':data_to_store,},update_reply);

                        }
                        else
                        {
                            console.log("Badge Class POST request failure:"+response.status.description);
                            jQuery("#gb-ob-response").html("BadgeClass POST request failed");
                        }
                    });
                }
                else
                {    
                   //issuer process is done, safe to proceed to assertion process
                   jQuery("#gb-ob-response").html("Badge Class already created before")
                   assertion_process(data.data);
                }
            }
            else
                    console.log("errors existing:"+data.errors);
        }
        ajaxCall({'action':'checkAndCreateBadgeClassEntity','data':data,},check_to_proceed);
    }

    /**
     * @description Checks for any assertion problems/erros and makes the post request for new assertion if
     *              not found.
     * @author      @CharalamposTheodorou
     * @since       @2.0
     *  
     * @return      void
     */
    function assertion_process(data)
    {
        var check_to_proceed = function(response)
        {
            var data = JSON.parse(response);
            if (data.errors.length ==0)
            {//okay to proceed
                //check if any uppdates happen to issuer (post request for new issuer).
                if (data.create.includes("Assertion"))
                { 
                     var settings = {
                        "url": data.assertion.url,
                        "method": data.assertion.method,
                        "timeout": data.assertion.timeout,
                        "headers": {
                            "Authorization": data.assertion.headers.Authorization,
                            "Content-Type": data.assertion.headers.Content_type,
                        },
                        "data": JSON.stringify(data.assertion.data),
                    };
                    jQuery.ajax(settings).done(function (response) {
                        if (response.status.description == "ok")
                        {
                            console.log("Assertion POST request Success");
                            //data to store to the identities file.
                            var data_to_store = new Object();
                            data_to_store.entityId = response.result[0].entityId;
                            data_to_store.recipient = response.result[0].recipient.identity;
                            data_to_store.badge_id = response.result[0].badgeclass;
                            var update_reply = function(response)
                            {
                                var data_response = JSON.parse(response);
                                if (data_response.success == "success")
                                {
                                    jQuery("#gb-ob-response").html("Assertion Completed")
                                    jQuery(btnGetBadgeMob).prop('disabled', false);
                                }
                                else
                                    console.log("problem with badge data stored to badgr file")
                            }
                            ajaxCall({'action':'updateBadgrEntitiesFile','section':'assertion','data':data_to_store,},update_reply);

                        }
                        else
                        {
                            console.log("Assertion POST request failure:"+response.status.description);
                            jQuery("#gb-ob-response").html("Assertion POST request failed");
                        }
                    });
                }
                else
                {//no request to create new Assertion for this receiver. -> Already received for this badge
                    console.log("Assertion done. Badge was already received.");
                    jQuery("#gb-ob-response").html("Assertion Already created before")
                }
            }
            else
            {
                console.log("assertion errors: "+data.errors);
            } 
        }
        ajaxCall({'action':'checkAndCreateAssertionEntity','data':data,},check_to_proceed);
    }
});
 
/* =========================
    Tab switcher
   ========================= */

window.addEventListener("load", function () {
    var tabs = document.querySelectorAll("ul.nav-tabs > li");

    for (i = 0; i < tabs.length; i++) {
        tabs[i].addEventListener("click", switchTab);
    }

    /**
     * @description Switch tab function
     *
     * @param event of the tab
     *
     * @return void
     */
    function switchTab(event) {
        event.preventDefault();

        document.querySelector("ul.nav-tabs > li.active").classList.remove("active");
        document.querySelector(".tab-pane.active").classList.remove("active");

        var tabSelected = event.currentTarget;
        var anchor = event.target;
        var activePaneId = anchor.getAttribute("href");


        tabSelected.classList.add("active");
        document.querySelector(activePaneId).classList.add("active");
    }
});


jQuery(function ($) {

    /* ### General functions ### */

    /**
     * @description Here's wrap the code that permit to simplify an
     *              ajax call.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @param {array} data, that will send with the ajax call.
     * @param {function} func, that will be execute after the
     *                         success of the ajax call.
     *
     * @return void
     */
    function ajaxCall(data, func) {
        $.post(
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

    /* =========================
        Action control
       ========================= */

    var formBadges = "#badges-list";
    var contForm = "#form-badges-list";

    /**
     * @description Submit event of the form for the badge list table that
     * permit to delete a list of badges.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return void
     */
    $(document).on("submit", formBadges, function (event) {
        event.preventDefault();

        var ids = new Array();
        $("input[name='badge[]']").each(function () {
            if ($(this).attr('checked')) ids.push($(this).val());
        });

        // If is not selected anything we will not do nothing
        if (ids) {

            //Select the right action, ex: trash
            var btn = $("input[type=submit][clicked=true]")['context']['activeElement'];
            var option = btn.previousElementSibling.children;
            var action;
            for (var i = 0; i < option.length; i++) {
                if (option[i].selected) {
                    action = option[i].value;
                }
            }


            switch (action) {
                case "trash":

                    // Delete badge/s
                    var data = {
                        'action': 'ajaxDeleteBadge',
                        'ids': ids
                    };

                    // Reload the table
                    var func = function (response) {
                        console.log(response);
                        var data = {
                            'action': 'ajaxShowBadgesTable',
                        };

                        var func = function (response) {
                            $(contForm).html(response);
                        };

                        ajaxCall(data, func);
                    }

                    ajaxCall(data, func);
                    break;

                default:
                    break;
            }
        }
    });

    /**
     * @description Click event for the Image uploader used in the settings page.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return void
     */
    $('body').on('click', '.upload-image-obf-settings', function (e) {
        e.preventDefault();

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library: {
                    // uncomment the next line if you want to attach image to the current post
                    // uploadedTo : wp.media.view.settings.post.id,
                    type: 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function () { // it also has "open" and "close" events
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $(button).removeClass('button').html('<img class="image-setting-prev" src="' + attachment.url + '" />').next().val(attachment.id).next().show();
            }).open();
    });

    /**
     * @description Click event for remove an image from the image uploader in the settings page.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return void
     */
    $('body').on('click', '.remove-image-obf-settings', function () {
        $(this).hide().prev().val('').prev().addClass('button').html('Upload image');
    });


    /* =========================
       POP-UP badge
      ========================= */

    /* Variables */
    // Get the modal
    var modal = document.getElementById('modalShowBadge');
    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    /**
     * @description Click event for the earned badge.
     *
     * @author @AleRiccardi
     * @since  1.0.0
     *
     * @return void
     */
    $(document).on('click', '.badge-earned', (function (e) {
        jQuery('#responseSent').html("<center style='padding: 100px'><img src='" + globalUrl.loader + "' width='50px' height='50px' /></center>");

        modal.style.display = "block";

        var data = {
            'action': 'ajaxShowBadgeEarned',
            'id': $(this).data("id")
        };

        var func = function (response) {
            $('#responseSent').html(response);

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        };

        ajaxCall(data, func);
    }));
	
});
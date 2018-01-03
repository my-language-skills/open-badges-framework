/* =========================
    Tab switcher
   ========================= */

window.addEventListener("load", function () {
    var tabs = document.querySelectorAll("ul.nav-tabs > li");

    for (i = 0; i < tabs.length; i++) {
        tabs[i].addEventListener("click", switchTab);
    }

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
    /* General func */
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
    $(document).on("submit", formBadges, function (event) {
        event.preventDefault();

        var ids = new Array();
        $("input[name='badge[]']").each(function () {
            if($(this).attr('checked')) ids.push($(this).val());
        });

        var trash = $("#bulk-action-selector-bottom :selected").val();

        /* Delete badge */
        if (trash === "trash") {

            var data = {
                'action': 'ajaxDeleteBadge',
                'ids': ids
            };

            var func = function (response) {
                console.log(response);
                /* Show Table */
                var data = {
                    'action': 'ajaxShowBadgesTable',
                };

                var func = function (response) {
                    $(contForm).html(response);
                }

                ajaxCall(data, func);
            }

            ajaxCall(data, func);
        }


    });


    /* =========================
        Image uploader
       ========================= */
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

    /*
     * Remove image event
     */
    $('body').on('click', '.remove-image-obf-settings', function () {
        $(this).hide().prev().val('').prev().addClass('button').html('Upload image');
        return false;
    });

});
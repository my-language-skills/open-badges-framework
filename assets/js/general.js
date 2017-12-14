
/* =========================
    Tab switcher
   ========================= */

window.addEventListener("load", function () {
    var tabs = document.querySelectorAll("ul.nav-tabs > li");

    for(i = 0; i < tabs.length; i++) {
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

/* =========================
    Image uploader
   ========================= */
jQuery(function ($) {
    /*
     * Select/Upload image(s) event
     * Source: https://rudrastyh.com/wordpress/customizable-media-uploader.html
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
                /* if you sen multiple to true, here is some code for getting the image IDs
                var attachments = frame.state().get('selection'),
                    attachment_ids = new Array(),
                    i = 0;
                attachments.each(function(attachment) {
                     attachment_ids[i] = attachment['id'];
                    console.log( attachment );
                    i++;
                });
                */
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
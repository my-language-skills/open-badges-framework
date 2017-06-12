(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

   setInterval(function(){check_badge_form();}, 500);

	 function check_mails(mails) {

		 var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

		 for (var i = 0; i < mails.length; i++) {
			 if(!testEmail.test(mails[i])) {
				 return false;
			 }
		 }
		 return true;
	 }

   function check_badge_form() {
     var mails = jQuery("#badge_form #mail").val().split("\n");
     var level = jQuery("#badge_form .level");

     if(!check_mails(mails) || !level.is(':checked')) {
       jQuery('#submit_button').prop('disabled', true);
     }
     else {
       jQuery('#submit_button').prop('disabled', false);
     }
   }

})( jQuery );

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
	$(document).on( 'click', '.nav-tab-wrapper.bitwise-tabs a', function() {
		var tab = $(this).data('tab-id');

		$('.nav-tab').removeClass('nav-tab-active');
		$('.content').removeClass('content-tab-active');
		
		$('#'+tab).addClass('nav-tab-active');
		$('#content-'+tab).addClass('content-tab-active');
		return false;
	})

	$(function() {
		if(show_msg=="show") {
			swal("Hey there!", "It has been a long time.\nLet's quickly go through these topics again!");
		}
	});

})( jQuery );

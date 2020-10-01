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
$(document).on( 'click', '.nav-tab-wrapper.bitwise-tabs-inner a', function() {
		$('#plugin-settings').addClass('nav-tab-active');
		$('#content-plugin-settings').addClass('content-tab-active');
		var tab = $(this).data('tab-id');

		$('.nav-tab.inner').removeClass('nav-tab-active');
		$('.content.inner').removeClass('content-tab-active');
		
		$('#'+tab+'-inner').addClass('nav-tab-active');
		$('#content-'+tab+'-inner').addClass('content-tab-active');
		return false;
	});

$("#settings_button").on('click',function(event){
        event.preventDefault();
        console.log("HIIII");
        var host = $('#host_name').val();
        $('#host_result').text('');

        jQuery.ajax({
            type: "GET",
            url:host,
            dataType: 'json',
            
            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                            if(obj.reply==true){
                                $('#host_result').text("Valid")
                            }

                            }
                          else {
                                console.log(obj.error);
                          }
                    },
            error: function (xhr, obj){
                          if(xhr.status==404){
                $('#host_result').text("Invalid")
                }
                  
            }
        });
    })
})( jQuery );

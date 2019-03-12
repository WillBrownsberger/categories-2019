/*
* File: responsive-tabs-utilities.js
* 
* Description: Minor utility functions for the theme
*  -- menu show/hide
* 	-- manages column widths on load for older browsers (if don't support css calc)
*  -- protects against wordpress comment text loss possibility with older browsers
*	-- supports show/hide of info splash
*
*
* @package responsive
*/ 
// drops down the menu if it is hidden 
function toggleMainMenu() {
	     
	var menu  = document.getElementById ( "main-menu" ); 
	var button = document.getElementById ( "main-menu-button");
	var display = menu.style.display;
	if ( "block" == display ) {
		menu.style.display = "none";
		button.innerHTML = '<span class="dashicons dashicons-menu"></span>';
	} else {
		menu.style.display = "block";
		button.innerHTML = '<span class="dashicons dashicons-dismiss"></span>';
	} 
	if ( !document.getElementsByClassName( 'gsc-input' ).length ) { console.log ( 'loading item')
		var gcse = document.createElement('script');
		gcse.type = 'text/javascript';
		gcse.async = true;
		gcse.src = 'https://cse.google.com/cse.js?cx=' + responsive_tabs_ajax_object.gcse_search_id;
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(gcse, s);
	}
}


/*
* Infinite scroll calls
*/

( function () {  // namespace wrapper -- anonymous self executing function 

	var scrollCallOutstanding = 0;
	var lastScrollResponseNonEmpty = true;

	var ajaxWidgetParms; 
	jQuery(document).ready(function($) {
		ajaxWidgetParms = JSON.parse ( jQuery( ".responsive_tabs_infinite_scroll_parms" ).text() );
		// set up scroll event
		$(window).scroll( function(){
			doScrollCall ();			
		});
		doScrollCall();
	});

	function doScrollCall () { 				
		regionBottom = jQuery ( "#responsive-tabs-ajax-insert" ).offset().top + jQuery ( "#responsive-tabs-ajax-insert" ).height();
		if ( ( regionBottom < ( jQuery(window).height() + jQuery(document).scrollTop() + 500 ) ) && 0 == scrollCallOutstanding && lastScrollResponseNonEmpty ) {
			scrollCallOutstanding = 1;
			ajaxSpinner = jQuery( "#responsive-tabs-post-list-ajax-loader" );
			ajaxSpinner.show();
			var postData = {
				action: 'responsive_tabs', // see namespacing in functions.php 
				responsive_tabs_ajax_nonce: responsive_tabs_ajax_object.responsive_tabs_ajax_nonce,
				data: JSON.stringify( ajaxWidgetParms )
			};
			jQuery.post( responsive_tabs_ajax_object.ajax_url, postData, function( response ) {
				jQuery( "#responsive-tabs-ajax-insert" ).append (response);
				if ( '' == response || response.indexOf('page out of range') > -1 ) {
					lastScrollResponseNonEmpty = false;
				}
				ajaxWidgetParms.page++;
				scrollCallOutstanding = 0;
				ajaxSpinner.hide();
				// check for more only if got posts and no error on last call
				if ( -1 != response.indexOf('OK-responsive-tabs-AJAX-response') ) { // this string is only returned when have posts/comments
					doScrollCall();  //keep getting more until bottom no longer visible 
				}
				if ( undefined != window.addComment ) {
					window.addComment.init()
				}
			});
		}
	}

})(); // close namespace wrapper
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

window.onresize = OnWindowResize;
window.onload = ResponsiveTabs; 

// tests for correct browser reading of an element in the footer
function TestSupportCalc() {
	
	var testCalc = document.getElementById ( "calctest" ); 
	var testCalcWidth = testCalc.offsetWidth;
	if ( 3 == testCalcWidth ) {
			return true;
	} else {
		return false;
	}
}

// drops down the menu if it is hidden 
function toggleSideMenu() {
	     
	var sideMenu  = document.getElementById ( "side-menu" ); 
	var display = sideMenu.style.display;
	var menuButton	= document.getElementById ( "side-menu-button");

	if ( "block" == display ) {
		sideMenu.style.display = "none";
		ResetSideMenu();
	} else {
		sideMenu.style.display = "block";
		menuButton.innerHTML = "HIDE";
	} 
}

// on load function
function ResponsiveTabs() {
		
	ResetSideMenu();
	if ( ! TestSupportCalc() ) {
		ResizeMajorContentAreas();
	}
	
}

// this handles case where user opens menu and then resizes window with menu open
function OnWindowResize() {
	if( TestSupportCalc() ) { // if browser supports calc (don't handle this case for IE<9; generates loop)
		ResetSideMenu();
	}
}




function getFirstChildWithTagName( element, tagName ) {
      for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( tagName == element.childNodes[i].nodeName ) return element.childNodes[i];
      }
}

	
function rtgetCookie(cname) { /* http://www.w3schools.com/js/js_cookies.asp */
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
} 


/*
* Infinite scroll calls
*/

( function () {  // namespace wrapper -- anonymous self executing function 

	var scrollCallOutstanding = 0;
	var lastScrollResponseNonEmpty = true;

	var ajaxWidgetParms; 
	jQuery(document).ready(function($) {
		parmsCount	= jQuery( ".responsive_tabs_infinite_scroll_parms" ).length;
		if ( parmsCount > 1 ) {
			alert ( responsiveTabsErrorObject.dupScrollErrorString ); // localized in theme functions.php		
		} else if ( 1 == parmsCount ) {
			ajaxWidgetParms = JSON.parse ( jQuery( ".responsive_tabs_infinite_scroll_parms" ).text() );
			// set up scroll event
			$(window).scroll( function(){
					doScrollCall ();			
			});
			doScrollCall();
		}
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
/*
* File: responsive-tabs-utilities.js
* 
* Description: Minor utility functions for the theme
*  -- menu show/hide
*  -- front page accordion show/hide
* 	-- manages column widths on load for older browsers (if don't support css calc)
*  -- covers what appears to be wordpress bug
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

// manages appearance of menu -- either as a left sidebar or as a dropdown
function ResetSideMenu() {  
		
	var innerWindowWidth = document.body.clientWidth; // note window.innerWidth seems to be less predictable wrt scroll bars
	
	var sideMenu  = document.getElementById ( "side-menu" ); 
	var menuButton	= document.getElementById ( "side-menu-button" );
	var headerBarContentSpacer = document.getElementById ( "header-bar-content-spacer" );
	var homeButton = document.getElementById ( "home-button");	
	
	menuButton.innerHTML = "MENU";	
	
	if ( undefined == homeButton ) {	// don't invoke this logic for retina-width templates
		
		if ( innerWindowWidth > 1579 ) {
			menuButton.style.display = "none";	
			sideMenu.style.display = "block"; 
			headerBarContentSpacer.style.display = "block"; 
			sideMenu.className = "sidebar-menu";
		} else { 
			menuButton.style.display = "block";
			sideMenu.style.display = "none"; 
			headerBarContentSpacer.style.display = "none"; 
			sideMenu.className = "dropdown-menu";		
		}
	}
}

// on load function
function ResponsiveTabs() {
		
	AccordionInit();
	ResetSideMenu();
	if ( ! TestSupportCalc() ) {
		ResizeMajorContentAreas();
	}
	splashSiteInfo();
	
}

// this handles case where user opens menu and then resizes window with menu open
function OnWindowResize() {
	if( TestSupportCalc() ) { // if browser supports calc (don't handle this case for IE<9; generates loop)
		ResetSideMenu();
	}
}

/* legacy support for browsers that do not support calc function 
/* should parallel exactly all media queries in style.css to support IE<10, but not for smallest screen sizes (won't be runnning IE8 anyway) */
/* resize only one load do not do resize on window resize -- hard to reliably control looping in earlier browsers caused by resize upon the resize */
function ResizeMajorContentAreas() { 

	var innerWindowWidth = document.body.clientWidth; // note window.innerWidth seems to be less predictable wrt scroll bars  

	var wrapper  = document.getElementById ( "wrapper" );
	var headerBar = document.getElementById ( "header-bar");

	wrapperWidth = Math.min( innerWindowWidth - 120, 1460 );
	wrapper.style.width = wrapperWidth + "px"; // fix at appropriate width 
	headerBar.style.width = wrapperWidth + "px";	
	var wrapperOffsetWidth = wrapper.offsetWidth; // should equal wrapperWidth + 120 b/c includes padding
		
	// additional elements whose widths we need to control in order of appearance
	var viewFrame  								= document.getElementById ( "view-frame" ); 
	var contentWrapper  							= document.getElementById ( "content-wrapper" ); 
	var rightSidebarWrapper 					= document.getElementById ( "right-sidebar-wrapper" );
	var headerBarWidgetWrapperSideMenuCopy	= document.getElementById ( "header-bar-widget-wrapper-side-menu-copy" );
	var headerBarWidgetWrapper 				= document.getElementById ( "header-bar-widget-wrapper" );

	if ( wrapperOffsetWidth > 1579 )	{	

		viewFrameWidth = wrapperWidth - 320;
		viewFrame.style.width = viewFrameWidth + "px"; 
		
		if (undefined != contentWrapper ) {
			contentWrapper.style.width = "740px";
		} 
	        
		rightSidebarWrapperWidth = viewFrameWidth - 740;
		if ( undefined != rightSidebarWrapper ) {
			rightSidebarWrapper.style.width= rightSidebarWrapperWidth + "px";
		} 
	} else if ( wrapperOffsetWidth > 1279) { 
		viewFrameWidth = wrapperWidth;
		viewFrame.style.width = viewFrameWidth + "px"; 
		
		if ( undefined != contentWrapper )  {
			contentWrapper.style.width = "740px";
		} 
	        
		rightSidebarWrapperWidth = viewFrameWidth - 740;

		if ( undefined != rightSidebarWrapper ) {
			rightSidebarWrapper.style.width= rightSidebarWrapperWidth + "px";
		} 
	} else {
		
		if ( undefined != headerBarWidgetWrapperSideMenuCopy  ) {
			headerBarWidgetWrapperSideMenuCopy.style.display = "block";
		}
		if ( undefined != headerBarWidgetWrapper ) {
			headerBarWidgetWrapper.style.display = "none";
		}
		
		viewFrameWidth = wrapperWidth;
		viewFrame.style.width = viewFrameWidth + "px"; 
		
		if ( undefined != contentWrapper )  {
			contentWrapper.style.width = "58%";
		} 
	        
		if ( undefined != rightSidebarWrapper ) {
			rightSidebarWrapper.style.width= "42%";
		}  
	}
	
   if ( wrapperOffsetWidth < 840 )	{
	
		if (undefined != contentWrapper )  {contentWrapper.style.width = "100%";
					contentWrapper.style.border = "0"} 
	        
		if (undefined != rightSidebarWrapper ) {rightSidebarWrapper.style.width= "100%";}  

	}		
    
}

// accordion set up from http://www.elated.com/articles/javascript-accordion/
var accordionItems = new Array();

function AccordionInit() {

      // Grab the accordion items from the page
      var divs = document.getElementsByTagName( 'div' );
      for ( var i = 0; i < divs.length; i++ ) {
        if ( 'accordionItem' == divs[i].className ) accordionItems.push( divs[i] );
      }

      // Assign onclick events to the accordion item headings
      for ( var i = 0; i < accordionItems.length; i++ ) {
        var h2 = getFirstChildWithTagName( accordionItems[i], 'H2' );
        h2.onclick = toggleItem;
      }

      // Hide all accordion item bodies except the first
      for ( var i = 0; i < accordionItems.length; i++ ) {
        accordionItems[i].className = 'accordionItem hide';
      }
}

function toggleItem() {
      var itemClass = this.parentNode.className;

      // Hide all items
      for ( var i = 0; i < accordionItems.length; i++ ) {
        accordionItems[i].className = 'accordionItem hide';
      }

      // Show this item if it was previously hidden
      if ( 'accordionItem hide' == itemClass ) {
        this.parentNode.className = 'accordionItem';
      }
}

function getFirstChildWithTagName( element, tagName ) {
      for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( tagName == element.childNodes[i].nodeName ) return element.childNodes[i];
      }
}

/* Notifies user who has entered text to non-entry of name and email -- can proceed to submit comment anyway, but 
   may lose comment text if name and email are set as required in settings>discussion (browsers do not consistently protect against 
   loss of entered data on back button use )  */
    
function checkNameEmailOnComments() {  
	
   commentform = document.getElementById('commentform');	
	
	author 	= commentform.author.value;	
	email 	= commentform.email.value;
	
	atpos 	= email.indexOf("@");
	dotpos 	= email.lastIndexOf(".");
	
	if ( author.length < 1 ) { 
		alert ( 'Please enter a Name.' );
	} else if ( atpos< 1 || dotpos<atpos+2 || dotpos+2>= email.length ) {
		alert ( 'Please enter a valid email address.' );
	}

}    

/* operates site info toggle */
function toggleSiteInfo() {

	var splash = document.getElementById( 'welcome-splash' );
	var display = splash.style.display;

	if ( "block" == display || '' == display ) {
		splash.style.display = "none";
	} else {
		splash.style.display = "block";
	} 
}

/* manages display of site info based on last-visited cookie */
function splashSiteInfo() {

	var lastVisit 				= rtgetCookie( 'responsive-tabs-last-visit' );
	var splashDelay 			= rtgetCookie( 'responsive-tabs-splash-delay' );
	var splashExpire 			= rtgetCookie( 'responsive-tabs-splash-expire' );	
	var splash 					= document.getElementById( 'welcome-splash' );
	var t 						= new Date().getTime() / 1000;
   var delayExpire			= parseFloat( lastVisit ) + parseFloat ( splashDelay );
   
	var documentCookieString = 'responsive-tabs-last-visit=' + t + '; expires=' + splashExpire + '; path=/';	
	document.cookie = documentCookieString;
	
	if ( '' == lastVisit ) {
		splash.style.display = "block";			
	} else if ( delayExpire < parseFloat(t) ) {
		splash.style.display = "block";
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
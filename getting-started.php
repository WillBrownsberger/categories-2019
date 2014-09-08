<?php
/*
* File: getting-started.php
*
* Description: This template will be invoked front page content if a tab-title is equal to "getting started" 
* 
* @package responsive-tabs 
* 
*/ 

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." );
 ?>
 <!-- getting started.php -->
<div class = "responsive-tabs-notice">
	<h1> <?php _e('Welcome to Responsive Tabs!', 'responsive-tabs' ); ?> </h1> 
 	<?php 	_e( 	'<h4>Overview of Responsive Tabs</h4>
 			<p>The Responsive Tabs theme gives you great structural flexibility in defining and redefining your front page content. 
 			Visit <a href="http://responsive-tabs-wordpress-theme.com">responsive-tabs-wordpress-theme.com</a> for more help and examples of this theme in action.  
 			Visit <a href = "http://WillBrownsberger.com">WillBrownsberger.com</a> for a more fully-developed implementation of this theme.  
 			Please feel free to email <a href="mailto:help@responsive-tabs-wordpress-theme.com">help@responsive-tabs-wordpress-theme.com</a> 
 			with any questions.</p>
			<h4>Basic Setup</h4>    		  	
 		  	<ol>
 		  		<li>Enter the titles you want, separated by commas, in Appearance>Customize>Tab Titles, like so: <br />
 		  		<code>Favorites, Latest Posts, Latest Comments</code></li>
 		  		<li>You will see your new tabs momentarily in the customizer.  Click on a tab title, while still in the customizer, and the Widget area 
 		  			for that Tab will show as a section in the customizer (likely near the bottom of the section list). </li>
 		  		<li>Populate the widget and repeat for each tab.</li>
 		  		<li>If you want people to land on something other than the left most tab (Tab 0), enter the number for that tab.</li>
 		  		<li><em>Save Changes</em></li>
 		  		<li>You can set all other theme options in  Appearance>Customize.</li>
 		  	</ol>'   , 'responsive-tabs');
 	_e( 	'<h4>More about Content Options in Front Page Tabs</h4>
 			<ul>
 		  		<li>For a newspaper look, populate your landing tab widget area with 10 or 15 copies of the Front Page Post Summary widget.  
	 		  		Use the Front Page Post Summary Widget to bring a post excerpt, featured-image and/or content to the front page -- 
	 		  		either in full-width or 4-to-a-row format. In 4-to-a-row format, the widgets will show as rows of 2 to 4 tiles (depending on browser width) 
	 		  		in desktop view but will reshuffle into a column in mobile view.</li>
				<li>Use the other included Front Page widgets for a responsive category list, comment list or archive list 
					formatted to take advantage of all screen sizes.</li>     		  		
 		  		<li>To show the standard latest posts list, just use Latest Posts as a tab title and leave the widget area for the tab empty.  
 		  			You can also use a different tab title and insert the Front Page Latest Posts widget.</li>
 		  		<li>To show plugin content with a shortcode in a tab, drop a text widget in the tab and put the shortcode in the text widget. 
 		  			Short codes that can look good in tabs include <a href="http://www.nextgen-gallery.com/nextgen-gallery-shortcodes/" target = "_blank">NextGen Gallery</a>,  
 		  			a <a href="http://tablepress.org/documentation/"  target = "_blank">TablePress table</a>, or 
  						a <a href="http://codex.bbpress.org/shortcodes/"  target = "_blank">bbPress forum</a>.</li> 
 		  	</ul>'   , 'responsive-tabs');   	
 	_e(	'<h4>Note: This page will disappear when you change this tab\'s title or content, but you can always get help in Appearance>Customize>Tab Titles or at ', 'responsive-tabs' );
	echo '<a href="http://responsive-tabs-wordpress-theme.com/" target = "_blank" >responsive-tabs-wordpress-theme.com</a>.  The website offers additional examples.</h4>'; ?>
</div><?php

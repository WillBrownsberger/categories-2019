<?php
/*
* File: getting-started.php
*
* Description: This template is invoked in the theme options menu.
* 
* @package responsive-tabs 
* 
*/ 


 ?>
 <!-- getting started.php -->
<div class = "responsive-tabs-notice">
	<h1> <?php _e('Welcome to Responsive Tabs!', 'responsive-tabs' ); ?> </h1> 
 		 	<h4><?php _e( 'Overview', 'responsive-tabs' ); ?></h4> 
 		 	<p><?php _e( 'All theme options for Responsive Tabs are set in Appearance>Customize so you can see dynamically how changes to the theme will look.  
 				For more ideas visit', 'responsive-tabs' ); ?> <a href="http://responsive-tabs-theme-for-wp.com"><?php _e('the theme home page.', 'responsive-tabs' ); ?></a></p>
 
 			<h4><?php _e( 'Basic Setup', 'responsive-tabs' ); ?></h4>    		  	
 		  	<ol>
 		  	   <li><?php _e( 'Go to Appearance>Customize>Site Title & Tagline. Choose a relatively brief site title and tagline (roughly 20 characters each) and a 2-3 character "Site Short Title". 
 		  	   As you narrow the screen width, you will see the tagline disappear and then, at smart phone width, the short title replace the full site title.', 'responsive-tabs' ); ?>
 		  	   <li><?php _e( 'If desired, add additional branding/identity information in one or both of two ways:', 'responsive-tabs' ); ?>
 		  	   <ul><li><?php _e( 'Populate the Site Info Splash widget area (appears when a "?" on the header bar is clicked) -- enable this under 
 		  	   Appearance>Customize>Site Info Page ?', 'responsive-tabs' ); ?></li><li><?php _e( 'Populate the Front Page Highlight Area -- either by adding a widget ( possible graphics ) to the Highlight Area or 
 		  	   directly enter text for this area in Appearance>Customize>Front Page Highlight.', 'responsive-tabs' ); ?></li></ul></li> 
 		  		<li><?php _e( 'Enter the tab titles you want, separated by commas, in Appearance>Customize>Tab Titles, like so: ', 'responsive-tabs' ); ?><br />
 		  		<code><?php _e( 'Favorites, Latest Posts, Latest Comments', 'responsive-tabs' ); ?></code></li>
 		  		<li<?php _e( '>You will see your new tabs momentarily in the customizer.  Click on a tab title, while still in the customizer, and the Widget area 
 		  			for that Tab will show as a section in the customizer (likely near the bottom of the section list). ', 'responsive-tabs' ); ?></li>
 		  		<li><?php _e( 'Populate the widget and repeat for each tab.', 'responsive-tabs' ); ?></li>
 		  		<li><?php _e( 'If you want people to land on something other than the left most tab (Tab 0), enter the number for that tab.', 'responsive-tabs' ); ?></li>
 		  		<li><em><?php  _e( 'Save Changes', 'responsive-tabs' ); ?></em></li>
 		  		<li><?php _e( 'You can set all other theme options in  Appearance>Customize.', 'responsive-tabs' ); ?></li>
 		  	</ol>
 	<h4><?php _e( 'More about Content Options in Front Page Tabs', 'responsive-tabs' ); ?></h4>
 			<ul>
 		  		<li><?php _e( 'For a newspaper look, populate your landing tab widget area with 10 or 15 copies of the Front Page Post Summary widget.  
	 		  		Use the Front Page Post Summary Widget to bring a post excerpt, featured-image and/or content to the front page -- 
	 		  		either in full-width or 4-to-a-row format. In 4-to-a-row format, the widgets will show as rows of 2 to 4 tiles (depending on browser width) 
	 		  		in desktop view but will reshuffle into a column in mobile view.', 'responsive-tabs' ); ?></li>
				<li><?php _e( 'To show the standard latest posts list, put the Front Page Latest Posts widget into a tab.  The widget allows you to include or exclude categories.  
 		  			You could put multiple Front Page Latest Posts widgets under different tabs to break out special categories of emerging content.', 'responsive-tabs' ); ?></li>
 		  		<li><?php _e( 'For a list of links, put the Front Page Links List widget in a tab.  Note: The link list grabs the first link (href) in the post.  
 		  		   To make a post/link appear in the list, you need to select Link in the Format box while editing the post.', 'responsive-tabs' ); ?></li> 	  	
				<li><?php _e( 'Use the other included Front Page widgets for a responsive category list, comment list or archive list 
					formatted to take advantage of all screen sizes.', 'responsive-tabs' ); ?></li>     		  		
 		  		<li><?php _e( 'Note that some plugins that look good on a page will also look good if placed within a Front Page widget set for full width. This theme
 		  			includes language enabling shortcodes to run in widgets.', 'responsive-tabs' ); ?></li>
  				<li><?php _e( 'The theme includes responsive styling for bbPress forums and topics.', 'responsive-tabs' ); ?></li> 
 		  	</ul>   	
 	<h4><?php _e( 'For more ideas visit', 'responsive-tabs') ?> <a href="http://responsive-tabs-theme-for-wp.com"><?php _e( 'the theme home page', 'responsive-tabs' ); ?>.
	
</div><?php

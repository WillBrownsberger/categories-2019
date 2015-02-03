<?php
/*
* File: getting-started.php
*
* Description: This template will be invoked front page content if a tab-title is equal to "getting started" 
* 
* @package responsive-tabs 
* 
*/ 


 ?>
 <!-- getting started.php -->
<div class = "responsive-tabs-notice">
	<h1> <?php _e('Welcome to Responsive Tabs!', 'responsive-tabs' ); ?> </h1> 
 	<?php 	_e( 	'<h4>Overview of Responsive Tabs</h4>
 
 			<h4>Basic Setup</h4>    		  	
 		  	<ol>
 		  	   <li>Go to Appearance>Customize>Site Title & Tagline. Choose a relatively brief site title and tagline (roughly 20 characters each) and a 2-3 character "Site Short Title". Overlong
 		  	   titles will overflow and drop out of  the convenient fixed title bar, especially on smaller screens.  Check how your titles perform in different window widths.
 		  	   As you narrow the screen width, you will see the tagline disappear and then, at smarthphone width, the short title replace the full site title.
 		  	   <li>Add additional branding/identity information in one or both of two ways which allow an unlimited amount of content in a responsive format:
 		  	   (A) Populate the Site Info Splash widget area and configure it either as a dropdown (under a "?" on the header bar) or as a first-time visitor splash (settings for this are under 
 		  	   Appearance>Customize>Welcome Splash Page). (B) Populate the Front Page Highlight Area -- either by adding a widget ( possible graphics ) to the Highlight Area or 
 		  	   directly enter text for this area in Appearance>Customize>Front Page Highlight.</li> 
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
				<li>To show the standard latest posts list, put the Front Page Latest Posts widget into a tab.  The widget allows you to include or exclude categories.  
 		  			You could put multiple Front Page Latest Posts widgets under different tabs to break out special categories of emerging content.</li>
 		  		<li>For a list of links, put the Front Page Links List widget in a tab.  Note: The link list grabs the first link (href) in the post.  
 		  		   To make a post/link appear in the list, you need to select Link in the Format box while editing the post.</li> 	  	
 		  		<li>For a newspaper look, populate your landing tab widget area with 10 or 15 copies of the Front Page Post Summary widget.  
	 		  		Use the Front Page Post Summary Widget to bring a post excerpt, featured-image and/or content to the front page -- 
	 		  		either in full-width or 4-to-a-row format. In 4-to-a-row format, the widgets will show as rows of 2 to 4 tiles (depending on browser width) 
	 		  		in desktop view but will reshuffle into a column in mobile view.</li>
				<li>Use the other included Front Page widgets for a responsive category list, comment list or archive list 
					formatted to take advantage of all screen sizes.</li>     		  		
 		  		<li>Note that some plugins that look good on a page will also look good if placed within a front page widget set for full width. This theme
 		  			includes language enabling shortcodes to run in widgets.</li>
  				<li>The theme includes responsive styling for bbPress forums and topics.</li> 
 		  	</ul>'   , 'responsive-tabs');   	
 	_e(	'<h4>Note: This page will disappear when you change this tab\'s title or content, but you can always get help in Appearance>Customize>Tab Titles.', 'responsive-tabs' ); ?>
	
</div><?php

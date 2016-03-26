=== Responsive-Tabs ===
Contributors: Will Brownsberger (development), Jane Winsor (Graphic Design)
Tags:  light, responsive-layout, fluid-layout, custom-background, custom-colors, featured-images, flexible-header, full-width-template, sticky-post, theme-options, threaded-comments, translation-ready, right-sidebar, infinite-scroll
Requires at least: 4.4
Tested up to: 4.5
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Description: Responsive Tabs is a fully responsive theme with optional infinite scroll for both post lists and comments.  
It is especially suited to websites that are or intend to become strong on content. 
It takes advantage of all the power of Wordpress to organize content transparently. 
The Responsive Tabs front page is entirely widgetized and the theme supports up to 16 tabbed content folders. 
The theme includes a set of custom Front Page widgets that can be configured for full width or tiled for a newspaper look.  
Either approach scales down well to mobile screens. 
Responsive Tabs is visually elegant, allows free choice of colors and fonts, and handles media content consistently with Wordpress standards. 
Responsive Tabs facilitates attractive use of text for low page weight, but images can easily be included in the various front page widget areas.

== Description ==
Responsive Tabs is a fully responsive theme with optional infinite scroll for both post lists and comments. It is especially suited to websites that are or intend to become strong on content.
It takes advantage of all the power of Wordpress to organize content transparently. 

Responsive Tabs is visually elegant, allows free choice of colors and fonts, and handles media content consistently with Wordpress standards.  
Responsive Tabs facilitates attractive use of text for low page weight, but images can easily be included in the various front page widget areas,
Responsive Tabs includes custom widgets to bring "featured images" from selected posts to the front page.  

The design premise of the theme is that (a) less can be more visually -- users should be able to focus on what they came to see; (b) at the same time, it should be obvious where to find any content even on a site with a wide range of content.  

= Major Design Features=
* Fully responsive -- looks good on smart phones, but takes full advantage of wide monitors as well
* Tabbed design -- let the user know what the site contains without overwhelming them.
* Great flexibility with a fully widgetized front page.
* Fast load times due to disciplined approach to minimization of page weight.
* Infinite scroll further minimizes pages weight for all post lists, post/pages with comments and front page widgets for latest posts, comments and links
* Full use of Wordpress's new customizer interface to allow instant reorganization of the front page as well as easy font and color changes.
* Elegant tiled approach for featuring favorite site content on the front page -- tiles for each favorite post will line up in rows of 4 in wide desktop view, but will reshuffle into rows of 3, 2 or 1 as the screenwidth decreases.
* Custom widgets for front page use -- latest posts with category include/exclude, post summaries with images, wide-format category and comment lists, wide-format post archives.
* Custom templates supporting navigation for all types of Wordpress archive.
* Full support for the Wordpress "link" post-format, with a special front page widget for displaying links to news items, etc.
* Dropdown menu for the routine links like about, contact, etc. -- the things that users know to look for on every site.  Expands to a left sidebar on very wide screens.
* Footer accordion for standard reference content.
* Wide format options for both pages and posts to accommodate  tables and other wide format content.
* Standard plugin hooks with extra support for key plugins -- bbPress, popular Breadcrumb plugins, FrontEnd Post No Spam, Clippings.
* Scrupulous attention to Wordpress design and coding standards to maximize compatibility and transparency.


== Installation ==
Setting up your tabbed front page is straightforward using Wordpress widgets.

1.	Standard theme install -- install the theme files in a subdirectory called responsive-tabs in the wp-content/themes subdirectory.
2. Go to Appearance>Customize>Site Title & Tagline. Choose a relatively brief site title and tagline (roughly 20 characters each) and a 2-3 character "Site Short Title". 
   As you narrow the screen width, you will see the tagline disappear and then, at smarthphone width, the short title replace the full site title.
3. Add additional branding/identity information in one or both of two ways which allow an unlimited amount of content in a responsive format:
   (A) Populate the Site Info Splash widget area  (which appears when a "?" on the header bar is clicked) -- settings for this are under 
   Appearance>Customize>Welcome Splash Page. (B) Populate the Front Page Highlight Area -- either by adding a widget ( possible graphics ) to the Highlight Area or 
   directly enter text for this area in Appearance>Customize>Front Page Highlight.
4. From the Wordpress administrative dashboard go to Appearance>Themes to activate Responsive Tabs.
5. To set up your front page, enter the titles you want, separated by commas, in Appearance>Customize>Tab Titles, like so:
   Favorites, Latest Posts, Latest Comments
6. You will see your new tabs momentarily in the customizer. Click on one and the Widget area for that Tab will show as a section in the customizer.
7. Populate the widget and repeat for each tab.
8. If you want people to land on something other than the left most tab (Tab 0), enter the number for that tab.
9. Save Changes
10.You can set all other theme options in Appearance>Customize.
-- Go to Appearance>Customize>Navigation to select a menu to put in the Main Menu location that will appear in the left sidebar (in widescreen view) or under the drop down (in screens less than 1580 pixels wide).  Or go to Appearance>Menus to create a menu if you are starting from scratch.  Check "Show Login Links in Main Menu" to optionally append profile, dashboard and login/out links to the main menu. Check Show Theme Breadcrumbs to see breadcrumb navigation on pages other than the front page.  (The theme will recognize also installation of popular breadcrumb plugins and ignore theme breadcrumb settings.)
-- If you are not seeing your front page, check Appearance>Customize>Static Front Page -- make sure that it is set to Your Latest Posts. That setting will invoke the Responsive Tabs tabbed front page.  However, you can also choose A Static Page and that will bypass the Responsive Tabs front page.
-- If you are an experienced user, can add custom CSS and scripts.
-- Set up Accordions in page footers for static reference materials.
-- Set up site identity header and optional headlines or headline widget. 
-- Change colors, fonts and images to achieve the look you want.

Let us know if you have questions or concerns -- help@responsive-tabs-theme-for-wp.com.


== Changelog ==
Version 2.26 (2016-03-26)
+  Fix stray div tag in support for breadcrumb plugins
+  Eliminate defunct "Highlight Headline Small Screen" option
+  Test with release candidate 4.5
Version 2.25 (2015-11-26)
+  Amend comment Ajax calls for consistency with new comment objects in 4.4
+  Eliminate name/email js validation for consistency with new comment form in 4.4
Version 2.24 (2015-11-08)
+  Add author filter option to Front Page Post Widget
Version 2.23 (2015-11-06)
+  Translation domain corrections.
Version 2.22 (2015-11-06)
+  4.3 compatibility: In theme customizer, move theme controls in deprecated 'nav' section to theme specific section.
+  Fix: Use site_url to properly support installations in subdirectories
Version 2.21 (2015-06-14)
+  Adjust infinite scroll standard page length for comments
Version 2.2 (2015-06-12)
+  Add Infinite Scroll for comments
+  Internationalize error message for too many infinite scroll widgets
Version 2.1 (2015-05-29)
+  Add Infinite Scroll to all templates.
Version 2.0 (2015-05-25)
+  Add Infinite Scroll to Latest Posts, Latest Comments, Links Widgets
+	Eliminate extra CSS lines
+	Fix last page link bug on latest posts widget
Version 1.33 (2015-02-07)
+  Eliminated add_filter to allow shortcodes in widgets
+  Changed title of tab in case of blank tab title option
Version 1.32 (2015-02-05)
+  Updated screenshot
Version 1.31 (2015-02-05) -- Responses to review feedback
+	Moved getting-started.php from a default front page display to a menu item under Appearance
+  Removed unnecessary script registration
Version 1.3	(2015-02-02) -- Responses to review feedback
+  Removed javascript back button from 404.php
+  Removed logic in footer.php and responsive-tab-utilities.js that used cookies to display a welcome splash for new or long-gone visitors
+  Also removed settings from customizer related to the welcome splash.  Reconfigured as only a widget display option.
+  Removed all links from getting-started.php
+  Removed favicon display from header.php
Version 1.2		(2014-12-23)	-- Responses to review feedback
+  Remove test of ABSPATH definition in all modules (unnecessary)
+	Modify nonce checking in post width metabox ( functions.php ) to eliminate bad reference to site_url(__FILE__) and clean up logic
+  Move enqueue of comments-reply from header and retina-header to setup
+	Add declaration of text domain to style.css ( text domain already used in all output ) 
+	Eliminate unnecessary query and array match in responsive_tabs_author_dropdown
+	Eliminate references to publication custom taxonomy
+	Review all modules for sanitization, alter some sanitization functions, and fix some instances of missing output sanitization 
+	Add parentheses to clarify $nav_tab_active class selection
+	Eliminate unnecessary error generating calls to ResetSideMenu in case of retina header display
+	Styling tweaks
+	Add new theme support for title tags
Version 1.1 	(2014-09-24)   -- Enhancements and improved documentation 
+  Improved consistency of options in widgets 
+  Add by-user include/exclude option and changed query mechanism for comments widget
+  Added featured images to page templates
+  Added optional welcome splash widget area for first-time visitors or non-recent visitors; also configurable as drop down from title bar
+  Changed initialization values for new website installs. 
Version 1.01	(2014-09-13) 	-- Changed projet URL's to comply with Wordpress trademark policy
Version 1.0 	(2014-09-11) 	-- Initial Submission to Wordpress 

== Upgrade Notice ==
Upgrade to 1.1 recommended for better widget functionality.
+
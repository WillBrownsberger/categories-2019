=== Responsive-Tabs ===
Contributors: Will Brownsberger (development), Jane Winsor (Graphic Design)
Tags:  light, responsive-layout, fluid-layout, custom-background, custom-colors, featured-images, flexible-header, full-width-template, sticky-post, theme-options, threaded-comments, translation-ready, right-sidebar
Requires at least: 3.9
Tested up to: 4.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Description: Responsive Tabs is a fully responsive theme that is especially suited to websites that are or intend to be strong on content.  It takes advantage of all the power of Wordpress to organize content transparently. The front page content is entirely widgetized and the theme supports up to 16 tabbed content folders. It is visually elegant, allows free choice of colors and fonts, and handles media content consistently with Wordpress standards, but it does not maximize image impact in the front page design.  Images can, however, easily be included in the various front page widget areas. The theme minimizes unnecessary page weight to assure good mobile response. Note: The theme layout does not accommodate an extra long Wordpress site title, but there is plenty of room for identity information in the optional “highlight” area immediately below the header which can be populated with bold text or with a widget which could include a banner or rotating images.

== Description ==
Responsive Tabs is a fully responsive theme that is especially suited to websites that are or intend to be strong on content.  It takes advantage of all the power of Wordpress to organize content transparently. The front page content is entirely widgetized and the theme supports up to 16 tabbed content folders. It is visually elegant, allows free choice of colors and fonts, and handles media content consistently with Wordpress standards, but it does not maximize image impact in the front page design.  Images can, however, easily be included in the various front page widget areas. The theme minimizes unnecessary page weight to assure good mobile response. Note: The theme layout does not accommodate an extra long Wordpress site title, but there is plenty of room for identity information in the optional “highlight” area immediately below the header which can be populated with bold text or with a widget which could include a banner or rotating images.  

The design premise of the theme is that (a) less can be more visually -- users should be able to focus on what they came to see; (b) at the same time, it should be obvious where to find any content even on a site with a wide range of content.  

Major Design Features
+++ Fully responsive -- looks good on smart phones, but takes full advantage of wide monitors as well
+++ Tabbed design -- let the user know what the site contains without overwhelming them.  
+++ Great flexibility with a fully widgetized front page.
+++ Fast load times due to disciplined approach to minimization of  page weight.
+++ Full use of Wordpress's new customizer interface to allow instant reorganization of the front page as well as easy font and color changes.
+++ Elegant tiled approach for featuring favorite site content on the front page -- tiles for each favorite post will line up in rows of 4 in wide desktop view, but will reshuffle into rows of 3, 2 or 1 as the screenwidth decreases.
+++ Custom widgets for front page use -- latest posts with category include/exclude, post summaries with images, wide-format category and comment lists, wide-format post archives.
+++ Custom templates supporting navigation for all types of Wordpress archive.
+++ Full support for the Wordpress "link" post-format, with a special front page widget for displaying links to news items, etc.
+++ Dropdown menu for the routine links like about, contact, etc. -- the things that users know to look for on every site.  Expands to a left sidebar on very wide screens.
+++ Footer accordion for standard reference content.
+++ Wide format options for both pages and posts to accommodate  tables and other wide format content.
+++ Standard plugin hooks with extra support for key plugins -- bbPress, popular Breadcrumb plugins, FrontEnd Post No Spam, Clippings.
+++ Scrupulous attention to Wordpress design and coding standards to maximize compatibility and transparency.


== Installation ==
Setting up your tabbed front page is straightforward using Wordpress widgets.

1.	Standard theme install -- install the theme files in a subdirectory called responsive-tabs in the wp-content/themes subdirectory.
2. Go to Appearance>Customize>Site Title & Tagline. Choose a relatively brief site title and tagline (roughly 20 characters each) and a 2-3 character "Site Short Title". Overlong
   titles will overflow and drop out of  the convenient fixed title bar, especially on smaller screens.  Check how your titles perform in different window widths.
   As you narrow the screen width, you will see the tagline disappear and then, at smarthphone width, the short title replace the full site title.
3. Add additional branding/identity information in one or both of two ways which allow an unlimited amount of content in a responsive format:
   (A) Populate the Site Info Splash widget area and configure it either as a dropdown (under a "?" on the header bar) or as a first-time visitor splash (settings for this are under 
   Appearance>Customize>Welcome Splash Page). (B) Populate the Front Page Highlight Area -- either by adding a widget ( possible graphics ) to the Highlight Area or 
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
Version 1.0 	(2014-09-11) 	-- Initial Submission to Wordpress 
Version 1.01	(2014-09-13) 	-- Changed projet URL's to comply with Wordpress trademark policy
Version 1.1 	(2014-09-24)   -- Enhancements and improved documentation 
+ Improved consistency of options in widgets 
+ Add by-user include/exclude option and changed query mechanism for comments widget
+ Added featured images to page templates
+ Added optional welcome splash widget area for first-time visitors or non-recent visitors; also configurable as drop down from title bar
+ Changed initialization values for new website installs. 
Version 1.2		(2014-12-23)	-- Responses to review feedback
+	Remove test of ABSPATH definition in all modules (unnecessary)
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
== Upgrade Notice ==
Upgrade to 1.1 recommended for better widget functionality.
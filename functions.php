<?php
/*
* File: functions.php
* Description: This file sets up theme
* -- includes theme customizer and widget files
* -- registers and enqueues javascripts ( layout and comments-reply )
* -- minimizes retrieval of latest posts on home page (so not waste resources in requery in widgets)
* -- optionally suppresses bbpress breadcrumbs
* -- registers menu
* -- registers sidebars
* -- adds theme support for header, background, thumbnails, html5, feeds, post-format (link), title-tags
* -- adds shortcode filter to widgets
* -- adds metabox to allow control of layout of posts (normal, wide, extra-wide)
* -- adds streamlined function to create archive drop down of authors
* -- adds several special-purpose sanitize callback functions for use with theme customizer
* -- adds editor style
* -- adds 2011 url grabber function
*
* @package responsive-tabs
*
*/



/*
* include theme customizer scripts
*/
include get_template_directory() . '/includes/responsive-tabs-customization.php';  		// note: it is apparently necessary to include this call outside is_admin() condition to allow refresh to work in customizer 
include get_template_directory() . '/includes/responsive-tabs-customization-css.php'; 	// assembles css for wp_head from theme customization selections

/*
* include widgets
*/
include get_template_directory() . '/includes/responsive-tabs-widgets.php'; 				

/*
* enqueue script for layout -- menu control and legacy browser-width ( and also enqueue comment-reply )
*/ 
function responsive_tabs_theme_setup() {
	if ( !is_admin() ) {
		wp_register_script(
		  'responsive-tabs-utilities',
		 	get_template_directory_uri() . '/js/responsive-tabs-utilities.js'
		);
		wp_enqueue_script('responsive-tabs-utilities');
		
		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );		
		} 
	} 		
}
add_action('wp_enqueue_scripts', 'responsive_tabs_theme_setup');

/*
* initialize key mods on first install in case user does not first enter customizer before viewing front page
*
*/
$tt_mod = get_theme_mod( 'tab_titles' ) ;

if ( false === $tt_mod ) {
	set_theme_mod( "site_short_title"	, __( 'Set mobile short title', 'responsive-tabs' ) );
	set_theme_mod( "highlight_headline"	, '<p>' . __( 'Responsive Tabs', 'responsive-tabs' ) . '</p><p>' . __( 'Theme Setup', 'responsive-tabs' ) . '</p>' );
	set_theme_mod( "highlight_subhead"	, '<p>'. __( 'Set up your theme in Appearance>Customize', 'responsive-tabs') . '</p>' );
	set_theme_mod( "highlight_headline_small_screen" 	, __( 'Highlight Headline Small Screen', 'responsive-tabs' ) );
	set_theme_mod( "tab_titles"			, __( 'This is Tab 0, Getting Started', 'responsive-tabs') );
	set_theme_mod( "landing_tab"			, "0" );
	set_theme_mod( "show_login_links"	, true );
	set_theme_mod( "show_breadcrumbs"	, true );
	set_theme_mod( "category_home"		, "0" );
	set_theme_mod( "date_home"				, "0" );
	set_theme_mod( "author_home"			, "0" );
	set_theme_mod( "search_home"			, "0" );
	set_theme_mod( "tag_home"				, "0" );
	set_theme_mod( "page_home"				, "0" );
	set_theme_mod( "header_image"			, get_template_directory_uri() . "/images/initial-header.png");
}

/*
* minimize home page main query because will not be showing posts from this query
* note that show_on_front == false is the condition under which a static front page other than the 
* 	main responsive-tabs front page is shown
*/
function minimize_home_page_post_list( $query ) { 
	
    if ( is_admin() || ! $query->is_main_query() || 'posts' != get_option( 'show_on_front' ) )
        return;

    if ( is_home() ) {
        // Retrieve only one for the original blog archive main query (minimize db access)
        $query->set( 'posts_per_page', '1' );
        $query->set( 'ignore_sticky_posts', true ); 
        return;
    }

}
add_action( 'pre_get_posts', 'minimize_home_page_post_list', 1 );
 
/*
*  optionally suppress bbpress bread crumbs on bbp template forms -- since may be loading broader breadcrumb plugins or offering own
*/ 
if ( true === get_theme_mod( 'suppress_bbpress_breadcrumbs' ) ) { 
		add_filter( 'bbp_no_breadcrumb', '__return_true' );
}

/*
* set up menu
*/
function responsive_tabs_register_menus() {
	register_nav_menu( 'main-menu' ,__( 'Main Menu', 'responsive-tabs' ));
}
add_action( 'init', 'responsive_tabs_register_menus' );

/*
 * Register sidebars
*/
function responsive_tabs_widgets_init() {
	
	register_sidebar( array(
		'name' 				=> __( 'Site Info Splash', 'responsive-tabs' ),
		'description' 		=> __( 'Widget area for site info dropdown and/or welcome splash for new visitors (display settings under Customize menu) ', 'responsive-tabs' ),
		'id' 					=> 'welcome_splash_widget',
		'class' 				=> '',
		'before_widget' 	=> '<div class = "welcome-splash-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h1 class = "welcome-splash-title">',
		'after_title' 		=> '</h1>',
	) );

	for ( $index = 0; $index <= 15; $index++ ) { // register widget areas for each tab
		register_sidebar( array(
				'name' 				=> __( 'Tab ', 'responsive-tabs' ) . $index,
				'description' 		=> __( 'Widget area for Tab content', 'responsive-tabs' ),
				'id' 					=> 'home_widget_' . $index,
				'class' 				=> '',
				'before_widget' 	=> '',
				'after_widget' 	=> '',
				'before_title' 	=> '<h2 class = "widgettitle">',
				'after_title' 		=> '</h2>',
			) );
	}
	
	register_sidebar( array(
		'name' 				=> __( 'Highlight Area', 'responsive-tabs' ),
		'description' 		=> __( 'Front Page highlight area (if not used, theme will show highlight headlines, if any, from Appearance > Customize > Front Page Highlight ) ', 'responsive-tabs' ),
		'id' 					=> 'highlight_area_widget',
		'class' 				=> '',
		'before_widget' 	=> '<div class = "highlight-area-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2 class = "widgettitle">',
		'after_title' 		=> '</h2>',
	) );
	
	register_sidebar( array(
		'name' 				=> __( 'Header Bar Widget', 'responsive-tabs' ),
		'description' 		=> __( 'Widget on Header Bar (recommended for a search widget ) ', 'responsive-tabs' ),
		'id' 					=> 'header_bar_widget',
		'class' 				=> '',
		'before_widget' 	=> '<div class = "header-bar-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2 class = "widgettitle">',
		'after_title' 		=> '</h2>',
	) );

	register_sidebar( array(
		'name' 				=> __( 'Side Menu Widget', 'responsive-tabs' ),
		'description' 		=> __( 'Widget on Side Menu Bar (recommended for menu extender like category links)', 'responsive-tabs' ),
		'id' 					=> 'side_menu_widget',
		'class' 				=> '',
		'before_widget' 	=> '<div class = "side-menu-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2 class = "widgettitle">',
		'after_title' 		=> '</h2>',
	) );
	
	register_sidebar( array(
		'name' 				=> __( 'Post Sidebar', 'responsive-tabs' ),
		'description' 		=> __( 'Displayed with Posts', 'responsive-tabs' ),
		'id' 					=> 'post_sidebar',
		'before_widget' 	=> '<div class = "sidebar-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2 class = "widgettitle">',
		'after_title' 		=> '</h2>',
	) );

	register_sidebar( array(
		'name' 				=> __( 'Page Sidebar',  'responsive-tabs' ),
		'description' 		=> __( 'Displayed with Pages', 'responsive-tabs' ),
		'id' 					=> 'page_sidebar',
		'before_widget' 	=> '<div class = "sidebar-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2 class = "widgettitle">',
		'after_title' 		=> '</h2>',
	) );


	register_sidebar( array(
		'name' 				=> __( 'BBPress Sidebar', 'responsive-tabs' ),
		'description' 		=> __( 'Displayed with BBPress Topics', 'responsive-tabs' ),
		'id' 					=> 'bbpress_sidebar',
		'before_widget' 	=> '<div class = "sidebar-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2 class = "widgettitle">',
		'after_title' 		=> '</h2>',
	) );

	register_sidebar( array(
		'name' 				=> __( 'Fine Print Bottom Widget', 'responsive-tabs' ),
		'description' 		=> __( 'Site credit, copyrights, etc.', 'responsive-tabs' ),
		'id' 					=> 'bottom_sidebar',
		'before_widget' 	=> '<div class = "bottom-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class = widgettitle>',
		'after_title' 		=> '</h3>',
	) );
}

add_action( 'widgets_init', 'responsive_tabs_widgets_init' );


/*
* theme support items
*
*/
function responsive_tabs_theme_support_setup() {
	
	$header_default = array(
		'width'         => 300,
		'height'        => 250,
		'header-text'   => false,
		'uploads'       => true,
		'default'		 => get_template_directory_uri() . '/images/initial-header.png',
	);
	add_theme_support( 'custom-header', $header_default ); // note -- installed as background image in theme customizer
	
	$background_default = array(
		'default-color'          => 'C6C2BA',
		'default-image'          => '',
		'wp-head-callback'       => '_custom_background_cb',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''
	);
	add_theme_support( 'custom-background', $background_default );
	
	add_theme_support( 'post-thumbnails', array ( 'post', 'page'));
		add_image_size( 'front-page-thumb', 267, 200 ); 
		add_image_size( 'front-page-half-thumb', 133, 100 ); 
		add_image_size( 'post-content-width', 640, 480 ); // fits post content width on desktop
		add_image_size( 'full-width', 1140, 855 ); // fits full width window (as on front page in single widget row) in desktop in maximum 
	
	if ( ! isset( $content_width ) ) { // http://codex.wordpress.org/Content_Width
		$content_width = 560;
	}
	
	add_theme_support( 'html5', array( 'search-form' ) );
	
	add_theme_support( 'automatic-feed-links' );
	
	add_theme_support( 'post-formats', array( 'link'  ) );
	
	/* https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1 */
   add_theme_support( 'title-tag' );
   
}
add_action( 'after_setup_theme', 'responsive_tabs_theme_support_setup' );

/* support short codes in widgets */
add_filter( 'widget_text', 'do_shortcode' );

/*
* add metabox for post width (see nonce technique at http://www.wproots.com/complex-meta-boxes-in-wordpress/) 
*/
function responsive_tabs_call_meta_box( $post_type, $post ) {
   add_meta_box(
       'responsive_tabs_post_width_setting_box',
       __( 'Post Display Width', 'responsive_tabs' ),
       'responsive_tabs_post_width_meta_box',
       'post',
       'side',
       'high'
   );
}
add_action( 'add_meta_boxes', 'responsive_tabs_call_meta_box', 10, 2 );

/* display meta box post width options */
function responsive_tabs_post_width_meta_box( $post, $args ) {

	wp_nonce_field( 'responsive_tabs_post_width', 'responsive_tabs_meta_box_noncename' );
              
	$post_width_options = array(
		'0' => array(
			'value' =>	'normal',
			'label' =>   __( 'Normal (show sidebar)', 'responsive-tabs'),
		),
		'1' => array(
			'value' =>	'wide',
			'label' =>  __( 'Wide (hide sidebar)' , 'responsive-tabs'),
		),
		'2' => array(
			'value' => 'extra_wide',
			'label' => __( 'Extra Wide (maximize content)', 'responsive-tabs'),
		),
	);	 
    
	$selected = ( null !== get_post_meta( $post->ID, '_twcc_post_width', true ) ) ? get_post_meta( $post->ID, '_twcc_post_width', true ) : '';
   echo '<select id = "_twcc_post_width" name = "_twcc_post_width">';

	$p = '';
	$r = '';
	foreach ( $post_width_options as $option ) {
	  	$label = $option['label'];
		if ( $selected == $option['value'] ) { // Make selected first in list
		     $p = '<option selected = "selected" value="' . $option['value'] . '">' . esc_html( $label) . '</option>';
		} else { 
			$r .= '<option value="' . $option['value'] . '">' .  esc_html( $label ) .  '</option>';
		}
	}
 	echo $p . $r. '</select>';

}

/* save width from meta box */
function responsive_tabs_save_meta_box( $post_id, $post ) {

   if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
       return;
	}
	
	if( ! current_user_can('edit_post', $post_id)) {
      	return;
	}

   if( isset ($_POST['_twcc_post_width'] )  ) { // test whether metabox form has been displayed -- action save_post is fired on new post creation before there is a nonce in the form
      check_admin_referer(  'responsive_tabs_post_width', 'responsive_tabs_meta_box_noncename' ); /* will die if not verified */
   	update_post_meta( $post_id, '_twcc_post_width', $_POST['_twcc_post_width'] );
   } 
   
   return;
   
}
add_action( 'save_post', 'responsive_tabs_save_meta_box', 10, 2 );

/*
*	Author drop down menu 
*/

function responsive_tabs_author_dropdown() {

	global $wpdb;

	$author_count_array = $wpdb->get_results(
		"
		SELECT DISTINCT post_author, display_name, COUNT(p.ID) AS post_count 
		FROM $wpdb->posts p INNER JOIN $wpdb->users u on u.id = p.post_author 
		WHERE post_type = 'post' AND post_status = 'publish' 
		GROUP BY post_author 
		ORDER BY display_name
		" 
		); 

   $return = 
   '<select id="author-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">' .
  		'<option value="">' . __( 'Select Author', 'responsive-tabs' ) . '</option>';

		foreach ( $author_count_array as $author_count_object ) {
			$name 	= $author_count_object->display_name;
			$return 	.= '<option value = "' . get_author_posts_url( $author_count_object->post_author ) . '"> ';
				$return .= esc_html( $name ) . ' ('. $author_count_object->post_count . ')';
		  	$return 	.= '</option>';
		}
		
	$return .= '</select>';

	return ( $return );
}
	

/*
* function to sanitize a list of post id's to a comma separated string of numerics ( used for theme customizer sanitize callback )
*
*/
function responsive_tabs_clean_post_list($post_list)  { 
   	
   $post_list_array = explode( ',', $post_list);
	$post_list_clean = '';      
   foreach( $post_list_array as $post_list_entry ) {
      $post_list_addition = is_numeric( trim( $post_list_entry ) ) ? trim( $post_list_entry ) . ',' : '';
	   $post_list_clean .= $post_list_addition;
   }		
	if( $post_list_clean > '' ) { // no trailing commas
		$post_list_clean = rtrim( $post_list_clean, ',' );
	}

	return $post_list_clean;
}

/*
* function to sanitize a boolean field ( used for theme customizer sanitize callback )
*/

function responsive_tabs_sanitize_boolean ( $value ) {
	return ( is_bool( $value ) ? $value : false );
	
}

/*
* function to sanitize a value to an integer greater than zero ( used for theme customizer sanitize callback )
*/
function responsive_tabs_int_greater_than_zero( $value ) {
	$value_int = intval( $value );
	$return_int =  $value_int < 1 ? 1  : $value_int;
	return $return_int;
}

/*
* function to sanitize a list of alphanumerics in comma separated string ( used for theme customizer sanitize callback )
*
*/
function responsive_tabs_title_list( $title_list )  { 
   	
   $title_list_array = explode( ',', $title_list);
	$title_list_clean = '';      
   foreach( $title_list_array as $title_list_entry ) {
      $title_list_addition = esc_attr( trim( $title_list_entry ) ) > '' ? esc_attr( trim( $title_list_entry ) ) . ', ' : '';
	   $title_list_clean .= $title_list_addition;
   }		
	if( $title_list_clean > '' ) { // no trailing comma-spaces
		$title_list_clean = rtrim( $title_list_clean, ', ' );
	}

	return $title_list_clean;
}

/*
* Null function to pass through scripts/css with minimal sanitization ( used for theme customizer sanitize callback )
*/
function responsive_tabs_pass_through( $unfiltered ) {
	return force_balance_tags( $unfiltered );
}

/*
* Hack to cover title for home page -- http://codex.wordpress.org/Function_Reference/wp_title > Covering Homepage
*/
add_filter( 'wp_title', 'baw_hack_wp_title_for_home' );
function baw_hack_wp_title_for_home( $title )
{
  if( empty( $title ) && ( is_home() || is_front_page() ) ) {
    return get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' ) ;
  }
  return $title;
}

/*
* Editor Styling using main style sheet
*/
function responsive_tabs_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'after_setup_theme', 'responsive_tabs_add_editor_styles' );

/*
* From twentyeleven -- first link grabber
* http://wordpress.org/support/topic/grab-first-link-from-post-for-external-link
*/
function responsive_tabs_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return ( $matches[1] );
}


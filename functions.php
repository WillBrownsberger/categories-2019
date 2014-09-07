<?php
/*
* File: functions.php
* Description: This file sets up theme
* -- includes auxiliary files (theme customization, widgets, theme front page options)
* -- registers and enqueues javascript for layout
* -- optionally suppresses bbpress breadcrumbs
* -- registers menu
* -- registers sidebars
* -- adds theme support for header, background, thumbnails, html5
* -- adds metabox to allow control of layout of posts (normal, wide, extra-wide)
* -- adds functions to create archive drop downs by authors and category
* -- adds function to sanitize a list of post id's
* -- adds function to sanitize css/scripts (only balance tags)
*
* @package responsive-tabs
*
*/

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." );

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
* enqueue script for layout -- menu control and legacy browser-width
*/ 
function responsive_tabs_theme_setup() {
	if ( !is_admin() ) {
		wp_register_script(
		  'responsive-tabs-utilities',
		 	get_template_directory_uri() . '/js/responsive-tabs-utilities.js'
		);
		wp_enqueue_script('responsive-tabs-utilities');
	} 		
}
add_action('wp_enqueue_scripts', 'responsive_tabs_theme_setup');

/*
* initialize key mods on first install in case user does not first enter customizer before viewing front page
*
*/
$tt_mod = get_theme_mod( 'tab_titles' ) ;

if ( false === $tt_mod ) {
	set_theme_mod( "site_short_title"	, "Set mobile short title" );
	set_theme_mod( "highlight_headline"	, "<p>Responsive Tabs</p><p>Theme Initialization</p>" );
	set_theme_mod( "highlight_subhead"	, "<p>Set up your theme in Appearance>Customize</p>" );
	set_theme_mod( "highlight_headline_small_screen" 	, "Highlight Headline Small Screen" );
	set_theme_mod( "tab_titles"			, "Getting Started, Latest Posts" );
	set_theme_mod( "landing_tab"			, "1" );
	set_theme_mod( "show_login_links"	, true );
	set_theme_mod( "show_breadcrumbs"	, true );
	set_theme_mod( "category_home"		, "1" );
	set_theme_mod( "date_home"				, "1" );
	set_theme_mod( "author_home"			, "1" );
	set_theme_mod( "search_home"			, "1" );
	set_theme_mod( "tag_home"				, "1" );
	set_theme_mod( "page_home"				, "1" );
	set_theme_mod( "header_image"			, get_template_directory_uri() . "/images/initial-header.png");
}
 
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
		'description' 		=> __( 'Widget on Side Menu Bar', 'responsive-tabs' ),
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
		'description' 		=> __( 'Displayed with Pages (except full-width pages)', 'responsive-tabs' ),
		'id' 					=> 'page_sidebar',
		'before_widget' 	=> '<div class = "sidebar-widget-wrapper"> ',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2 class = "widgettitle">',
		'after_title' 		=> '</h2>',
	) );


	register_sidebar( array(
		'name' 				=> __( 'BBPress Sidebar', 'responsive-tabs' ),
		'description' 		=> __( 'Displayed with BBPress Topics and Posts', 'responsive-tabs' ),
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
	add_image_size( 'front-page-thumb', 270, 200 ); //270 pixels wide (and unlimited height)
	add_image_size( 'front-page-half-thumb', 135, 100 ); //135 pixels wide (and unlimited height)
	add_image_size( 'post-content-width', 560, 420); // fits post content width on desktop
	add_image_size( 'full-width', 1140, 855); // fits full width window (as on front page in single widget row) in desktop

add_theme_support( 'html5', array( 'search-form' ) );

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
function responsive_tabs_post_width_meta_box($post, $args) {
   
	wp_nonce_field( site_url(__FILE__), 'responsive_tabs_meta_box_noncename' );
              
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
		
   if( isset( $_POST['responsive_tabs_meta_box_noncename'] ) && wp_verify_nonce( $_POST['responsive_tabs_meta_box_noncename'], site_url( __FILE__ ) ) && check_admin_referer( site_url( __FILE__) , 'responsive_tabs_meta_box_noncename' ) ) {
   	update_post_meta( $post_id, '_twcc_post_width', $_POST['_twcc_post_width'] );
   }
   return;
   
}
add_action( 'save_post', 'responsive_tabs_save_meta_box', 10, 2 );

/*
*
* functions to create archive drop downs
*
*/

/* 
* author drop down derived from:
*	https://core.trac.wordpress.org/browser/tags/3.9.1/src/wp-includes/author-template.php#L0
*	http://codex.wordpress.org/Template_Tags/wp_list_authors
*/

function responsive_tabs_author_dropdown($args = '') {

	global $wpdb;
	
	$query_args = array(
		'orderby' => 'name', 
		'order' => 'ASC', 
		'number' => '',
		'fields' => 'ids', 
	);

	$authors = get_users( $query_args );

	$author_count = array();
	foreach ( (array) $wpdb->get_results("SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE post_type = 'post' and post_status = 'publish' GROUP BY post_author") as $row ) {{}
	   $author_count[$row->post_author] = $row->count;
	}

   $return = 
   '<select id="author-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">' .
  		'<option value="">' . __( 'Select Author', 'responsive-tabs' ) . '</option>';

		foreach ( $authors as $author_id ) {
			$author = get_userdata( $author_id );
			$posts = isset( $author_count[$author->ID] ) ? $author_count[$author->ID] : 0;
			if ( !$posts ) {
				continue;
			}
			$link 	= '';
			$name 	= $author->display_name;
			$return 	.= '<option value = "';
			$link 	= get_author_posts_url( $author->ID, $author->user_nicename ) . '"> ' . $name . ' ('. $posts . ')';
			$return 	.= $link;
		  	$return 	.= '</option>';
		}
	$return .= '</select>';

	echo  $return;

}
	
/* function not actually used in release version of theme */
function responsive_tabs_category_dropdown(){

	echo strtolower(wp_dropdown_categories('echo=0&orderby=NAME&hierarchical=1&hide_if_empty=1&show_count=1&show_option_none=Select category')); 
	?>	
	<script type="text/javascript"><!--
	    var dropdown = document.getElementById("cat");
	    function onCatChange() {
			if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
				location.href = "<?php echo get_option('home');
				?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
			}
	    }
	    dropdown.onchange = onCatChange;
	--></script>
	<?php
} 
/*
* function to sanitize a list of post id's to a comma separated string of numerics
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
* function to sanitize a list of alphanumerics in comma separated string
*
*/
function responsive_tabs_title_list($title_list)  { 
   	
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
* Null function to pass through scripts/css with minimal sanitization
*/
function responsive_tabs_pass_through($unfiltered) {
	return force_balance_tags($unfiltered);
}
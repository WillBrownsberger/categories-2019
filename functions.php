<?php
/*
* File: functions.php
* Description: This file sets up theme
* -- includes theme customizer and widget files
* -- registers and enqueues javascripts ( utilities and comments-reply ), also namespaces ajax call in utilities
* -- minimizes retrieval of latest posts on home page (so not waste resources in requery in widgets)
* -- registers menu
* -- registers sidebars
* -- adds theme support for header, background, thumbnails, html5, feeds, post-format (link), title-tags
* -- adds metabox to allow control of layout of posts (normal, wide, extra-wide)
* -- adds streamlined function to create archive drop down of authors
* -- adds several special-purpose sanitize callback functions for use with theme customizer
* -- adds 2011 url grabber function
*
* @package responsive-tabs
*
*/



/*
* include theme customizer scripts and ajax handler
*/
include get_template_directory() . '/includes/responsive-tabs-customization.php';  		// note: it is apparently necessary to include this call outside is_admin() condition to allow refresh to work in customizer 
include get_template_directory() . '/includes/responsive-tabs-customization-css.php'; 	// assembles css for wp_head from theme customization selections
include get_template_directory() . '/includes/responsive-tabs-ajax-handler.php'; 
/*
* enqueue script for layout -- menu control and legacy browser-width ( and also enqueue comment-reply )
*/ 
function responsive_tabs_theme_setup() {
		wp_enqueue_script(
			'responsive-tabs-utilities',
		 	get_template_directory_uri() . '/js/responsive-tabs-utilities.js',
		 	array( 'jquery' ),
		 	false,
		 	false			
			);
		wp_enqueue_style( 'dashicons' ); // no speed penalty
			// name spacing the AJAX URL in script by putting it into js global object and setting nonce
			// name spacing matches calls in responsive-tabs-utilities.js
		wp_localize_script( 'responsive-tabs-utilities', 'responsive_tabs_ajax_object',
            array( 
            	'ajax_url' 			=> admin_url( 'admin-ajax.php' ),
            	'responsive_tabs_ajax_nonce' 	=> wp_create_nonce ( 'responsive_tabs_ajax_nonce' ), 
            	'gcse_search_id' =>  get_theme_mod( 'google_custom_search_id' ), 
            ) 
			);	
		
		
		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );		
		} 
}
// wp_enqueue_scripts only works on the front end
add_action('wp_enqueue_scripts', 'responsive_tabs_theme_setup');



/*
* make page length compatible with infinite scroll for WP queries
*
* loading excerpts -- more is actually faster per page speed (load of second bunch gets counted) -- went from 1 to 12 for front page 
*/
function responsive_tabs_manage_posts_per_page( $query ) { 

 	// do nothing on admin side and nothing to queries other than main query;	
	if ( is_admin() || ! $query->is_main_query() ) {
		return; 
	}
    $query->set( 'posts_per_page', '20' ); // 

}
add_action( 'pre_get_posts', 'responsive_tabs_manage_posts_per_page', 1 );
 

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
		'name' 				=> __( 'Search', 'responsive-tabs' ),
		'description' 		=> __( 'Widget area for Search Box (will be ignored if Google Search Id is supplied in customization)', 'responsive-tabs' ),
		'id' 				=> 'search_widget',
		'class' 			=> '',
		'before_widget' 	=> '',
		'after_widget' 		=> '',
		'before_title' 		=> '<h2 class = "widgettitle">',
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

	add_theme_support( 'custom-logo', array(
		'height'      => 36,
		'width'       => 36,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );

	add_theme_support( 'post-thumbnails', array ( 'post', 'page'));
		add_image_size( 'post-list-thumb', 100, 100 ); 
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
*
* function to assemble category list -- want to maximize speed
*
* cannot avoid get_category_link -- assembly of link depends on permalink structure
*/
function category_list_two_deep () {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$term_taxonomy = $wpdb->prefix . 'term_taxonomy';
	$terms = $wpdb->prefix . 'terms';
	$sql = "
		SELECT t1.name as parent_cat, t1.term_id as parent_cat_id, t2.name as child_cat, t2.term_id as child_cat_id FROM 
		( $term_taxonomy  tt1 INNER JOIN $terms t1 on tt1.term_id = t1.term_id ) left join 
		( $term_taxonomy  tt2 INNER JOIN $terms t2 on tt2.term_id = t2.term_id ) on 
			tt2.parent = tt1.term_id 
			WHERE tt1.parent = 0 and tt1.taxonomy = 'category' 
			order by t1.name, t2.name
	";
	$cats = $wpdb->get_results ( $sql );
	$last_cat_id = '';
	foreach ( $cats as $cat ) {
		if ( $last_cat_id != $cat->parent_cat_id ) {
			if ( '' != $last_cat_id  ) {
				echo '<div class="horbar-clear-fix"></div></div>';
			}
			echo '<div class="main-menu-category-parent">
				<div class="main-menu-category-parent-link">' . 
					'<a  href="' . esc_url( get_category_link ( $cat->parent_cat_id ) ) . '">' . $cat->parent_cat . '</a>' . 
				'</div>';
			$last_cat_id = $cat->parent_cat_id;
		}
		echo '<div class="main-menu-category-child">' .
				'<div class="main-menu-category-child-link">' . 
					'<a  href="' . esc_url( get_category_link( $cat->child_cat_id ) ) . '">' . $cat->child_cat . '</a>' . 
				'</div>' .
		'</div>';	

	}
	echo '<div class="horbar-clear-fix"></div></div>'; // close last div
}


/*
* From twentyeleven -- first link grabber
* http://wordpress.org/support/topic/grab-first-link-from-post-for-external-link
*/
function responsive_tabs_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return ( $matches[1] );
}

/* set up response to  ajax calls */
$responsive_tabs_ajax = new Responsive_Tabs_Ajax_Handler;
add_action( 'wp_ajax_responsive_tabs', array ( $responsive_tabs_ajax, 'route_ajax' ));
add_action( 'wp_ajax_nopriv_responsive_tabs', array ( $responsive_tabs_ajax, 'route_ajax' ));
// add functions to override comment pagination options if doing infinite scroll 
add_filter ( 'pre_option_comments_per_page' , array ( $responsive_tabs_ajax, 'set_comments_per_page_for_ajax' ) );
add_filter ( 'pre_option_page_comments' , array ( $responsive_tabs_ajax, 'set_page_comments_for_ajax' ) );

<?php
/*
 * File: breadcrumbs.php
 * Description: template part used to display theme controlled breadcrumbs (or plugin breadcrumbs if present) on all except front pages
 *
 * @package responsive-tabs
 *
 *
 */

/* test for installed breadcrumb plugins and display them -- credit for these lines to Cyberchimps Responsive */
if ( function_exists( 'bcn_display' ) ) {  
	echo '<div id="breadcrumbs">';		
		bcn_display();
	echo 'div';
} elseif ( function_exists( 'breadcrumb_trail' ) ) {
	echo '<div id="breadcrumbs">';		
		breadcrumb_trail();
	echo 'div';
} elseif ( function_exists( 'yoast_breadcrumb' ) ) {
	echo '<div id="breadcrumbs">';		
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	echo 'div';
} elseif ( true === get_theme_mod( 'show_breadcrumbs' ) ) {
   echo '<div id="breadcrumbs">';		  			
		global $wp_locale; 
		/* construct breadcrumbs for templates */	
	   if ( is_page() && ! is_front_page() ) {
			$home_link =  '<a href="/?frontpagetab=' .  get_theme_mod( 'page_home' )  .'">' . __( 'home', 'responsive-tabs' ) . '</a> &raquo; ';
			echo $home_link;   	
	   	$id = get_queried_object_id();
	   	$ancestors = get_ancestors( $id, 'page' );
	     	krsort( $ancestors );
	   	foreach( $ancestors as $ancestor ){
	   		$ancestor_title = get_the_title( $ancestor );
			 	echo '<a href="' . get_permalink( $ancestor )
	    			. ' ' . '" title="' . $ancestor_title . '" >' . strtolower($ancestor_title)
	    			. '</a> &raquo; ';   	
	   	}
	   } elseif ( is_single() ) {
			echo '<a href="/?frontpagetab=' .  get_theme_mod( 'category_home' )  .'">' . __( 'home', 'responsive-tabs' ) . '</a> &raquo; ';
			$categories = get_the_category();
			if( $categories ) {
				foreach( $categories as $category )	{
						echo strtolower(get_category_parents( $category->term_id, true, ' &raquo; ' ));
						break; /* selecting only the first found category for breadcrumbs */
				}
			}
		} elseif ( is_category() ) {
			$home_link =  '<a href="/?frontpagetab=' .  get_theme_mod( 'category_home' ) .'">' . __( 'home', 'responsive-tabs' ) . '</a> &raquo; ';
			echo $home_link;
			echo strtolower( get_category_parents( $cat, true, ' &raquo; ' ) ); 	
		} elseif ( is_date() ) {
			$home_link 	= '<a href="/?frontpagetab=' .  get_theme_mod( 'date_home' ) .'">' . __( 'home', 'responsive-tabs' ) . '</a> &raquo; ';
			$year = get_query_var( 'year' );
			$year_link = '<a href="'. get_year_link( $year ) . '">'. $year . '</a> &raquo; ' ;
			$monthnum = get_query_var( 'monthnum' );
			if ( $monthnum > 0 ) {
				$month_link = '<a href="'. get_month_link( $year, $monthnum ).'">'. $wp_locale->get_month( $monthnum )   . '</a> &raquo; ';
			} else $month_link = '';
			$day = get_query_var( 'day');
			if ( $day > 0 ) {
				$day_link = ' <a href="'. get_day_link($year, $monthnum, $day).'">'. $day. '</a> &raquo;  '; 		
			} else $day_link = '';
			echo $home_link. $year_link . $month_link . $day_link;		
		} elseif ( is_author() ) {
			$home_link =  '<a href="/?frontpagetab=' . get_theme_mod( 'author_home' ) . '">' . __( 'home', 'responsive-tabs' ) . '</a> &raquo; ';
			echo $home_link . __( 'posts by author &raquo; ', 'responsive-tabs'); 	
		} elseif ( is_search() ) {
			$home_link =  '<a href="/?frontpagetab=' . get_theme_mod( 'search_home' ) . '">' . __( 'home', 'responsive-tabs' ) . '</a> &raquo; ';
			echo $home_link . __( 'string search of titles and content &raquo;', 'responsive_tabs' ); 	
		} elseif ( is_tag() ) {
			$home_link =  '<a href="/?frontpagetab=' . get_theme_mod( 'tag_home' )  . '">' . __( 'home', 'responsive-tabs' ) . '</a> &raquo; ';
			echo $home_link . __( 'search by tag &raquo;', 'responsive_tabs' ); 	
		}
	echo '</div>';
} /* close else for breadcrumbs options set */
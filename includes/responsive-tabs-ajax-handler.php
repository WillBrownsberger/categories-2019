<?php
/*
*
* Handles AJAX Calls -- checks call parameters, nonce and capabilities required and routes them to appropriate logic
* Included in functions.php.  Routing function invoked on ajax actions by functions.php.
*
*/

class Responsive_Tabs_Ajax_Handler {
	
	public function route_ajax() { 

		$widget_parms = json_decode ( stripslashes( $_POST['data'] ) );		

		// first make sure that action and subaction are present as expected
		if ( ! isset ( $_POST['action'] ) || ! isset ( $widget_parms->widget_type ) ) {
			die ( __( 'Bad call to Responsive_Tabs_Ajax_Handler', 'responsive-tabs' ) );		
		}		

		// now check nonce -- nonce set in script localization, see WIC_Admin_Setup::add_wic_scripts
		// not updating nonce -- relying on availability for some hours, long enough for user to probably refresh screen
		$nonce = $_POST['responsive_tabs_ajax_nonce'];
		if ( ! wp_verify_nonce ( $nonce, 'responsive_tabs_ajax_nonce' ) ) {
			 die ( __( 'Bad nonce in AJAX call to Responsive_Tabs_Ajax_Handler', 'responsive-tabs' ) );		
		}

		$next_function = $widget_parms->widget_type;		
		if (  'comment_query' == $next_function ) {
			self::$next_function ( 
				$widget_parms->include_string, 
				$widget_parms->page
				);
		} elseif ( 'latest_comments' == $next_function ) {
			$output = self::$next_function ( 
				$widget_parms->page 
			);	
			echo $output;
		} elseif ( 'non_widget_query' === $next_function ) {
				self::$next_function ( 
					$widget_parms->include_string, 
					$widget_parms->page, 
					$widget_parms->query_type 
				);
		}
		wp_die();
	}	


	// used to serve the browse comments page
	public static function latest_comments (  $comment_page ) {
 	
		$number = 12; // rapid first response and then keep scrolling			
		$scroll_marker = ' id="responsive-tabs-ajax-insert" ';	
 		
		$offset	= $comment_page * $number;
		
		global $wpdb;
		$widget_comment_query = 
			"
			SELECT comment_id, comment_date, comment_post_ID, comment_content, post_title 
			FROM $wpdb->comments c INNER JOIN $wpdb->posts p on c.comment_post_ID = p.ID 
			WHERE 
				post_status = 'publish' AND
				comment_approved = 1 AND
				( comment_type is NULL or comment_type = '')  
			ORDER BY comment_date_gmt DESC  
			LIMIT  $offset , 
			" . ( $number + 1 );

		$widget_comments = $wpdb->get_results( $widget_comment_query ); 

		$output = '';
		if ( $widget_comments ) {
	
			// comment list items			
			$count 			= 1; 	// for row style alternation and to test possibility that a page had only admin comments on it
			$found_count 	= 0; // for next page switch	
							
			foreach ( (array) $widget_comments as $comment) {
				
				$found_count = $found_count + 1;

				if( $found_count < $number + 1 ) { // don't over shoot to next page 
				  	$count = $count + 1;
				  	if( $count % 2 == 0 ) {
				  		$row_class = "pl-even";
				  	} else {
				  		$row_class = "pl-odd";
				  	}
				   $comment_date_time = new DateTime( $comment->comment_date );
				   $output .=  
				   '<li class="' . $row_class . '">' . 
   					'<ul class="responsive-tabs-front-page-comment-list-item">' . 
							'<li class="responsive-tabs-front-page-comment-author">'. get_comment_author_link($comment->comment_id) .  '</li>' . 
							'<li class="responsive-tabs-front-page-comment-post">' . 
								'<a href="' . get_permalink( $comment->comment_post_ID ) . '#comments">' .	
									esc_html( $comment->post_title ) . 
								'</a>'.
							'</li>' .
							'<li class="responsive-tabs-front-page-comment-date-time">' . 
								date_i18n( get_option( 'date_format' ), strtotime( $comment->comment_date ) ) . 
							'</li>' .
						'</ul>' .
						'<div class = "responsive-tabs-front-page-comment-excerpt">' . 
							apply_filters( 'comment_text', self::get_long_comment_excerpt( $comment->comment_content ) ) . '<br />' .
							'<a href="'. get_permalink( $comment->comment_post_ID ) . '#comments">' . 
								__( 'View Comment in Context &raquo;', 'responsive-tabs' ) . 
							'</a>' .
						'</div>' .
					'</li>';
	    		}
			}
			$output .= '</ul>';  // .responsive-tabs-front-page-comment-list
			
			// set up widget parms to pass as hidden value to widget ajax caller		
			$widget_parms = new Widget_Ajax_Parms ( 
				'latest_comments', 
				'',
				1 // page 1 is second page for comments sql; incremented from there js side as js variable
			);
			$widget_parms_string = json_encode( $widget_parms );						
			$output .= '<div class="responsive_tabs_infinite_scroll_parms" id="responsive_tabs_infinite_scroll_parms">' . $widget_parms_string . '</div>';
			$output .= '<span id = "responsive-tabs-post-list-ajax-loader">' .
				'<img src="' . get_stylesheet_directory_uri() . '/images/ajax-loader.gif' .
			'"></span>'; 
			$output .= '<span id="OK-responsive-tabs-AJAX-response"></span>';			
 		} else {
 			$output .= '<h3>' . __('No approved comments selected!', 'responsive-tabs' ) . '</h3>';
 		}		

		return ( $output );
	}

	/*
	* long comment excerpt method
	* derived from http://developer.wordpress.org/reference/functions/get_comment_excerpt/
	*/
	public static function get_long_comment_excerpt( $comment_content ) {
		
		$clean_content = strip_tags( $comment_content, '<b><code><em><i><strike><strong>');

		$excerpt_array = explode( ' ', $clean_content );
		
		if( count ( $excerpt_array ) > 100) {
			$k = 100;
			$use_dotdotdot = 1;
		} else {
			$k = count( $excerpt_array );
			$use_dotdotdot = 0;
		}
	    
		$excerpt = '';
		for ( $i=0; $i<$k; $i++ ) {
			$excerpt .= $excerpt_array[$i] . ' ';
		}

		$excerpt .= ( $use_dotdotdot ) ? '&hellip;' : '';

		$excerpt = force_balance_tags( $excerpt );

		return apply_filters( 'get_comment_excerpt', $excerpt );
		
	}



	/* general handler for wp_queries -- other archives*/
	public static function non_widget_query (  
	 		$include_string, // the WP query details 
			$page, 
			$query_type // the WP query type  -- author, category, date, search, tag, index
		) {
			
		$query_args = array(
			'posts_per_page' 	=> 12, // standardized #of posts to retrieve if infinite scroll enabled
			'paged' 			=> $page,
			$query_type			=> $include_string, // query type should be named to equal WP_Query parameter
		);
		
		
		if ( 'date_query' == $query_type ) {
			// recast inner object as array
			$query_args['date_query'] = array( (array) $include_string[0] );
		}
		
		/* compatibility hook for plugins that filter posts globally using pre_get_posts */
		$query_args = apply_filters ( 'responsive_tabs_ajax_pre_get_posts', $query_args ); 		
		
		$ajax_main_query_next_page = new WP_Query ( $query_args );
		/* post list */
		global $responsive_tabs_post_list_line_count;  
		$responsive_tabs_post_list_line_count = 1; 
		$count = 0;
		$found_any_posts = false;
		while ( $ajax_main_query_next_page->have_posts() ) : 
				$ajax_main_query_next_page->the_post(); 
				$responsive_tabs_post_list_line_count = $responsive_tabs_post_list_line_count + 1;
				get_template_part( 'post', 'listitems' );
				$found_any_posts = true;
		endwhile;  
		if ( $found_any_posts ) {
			echo '<span id="OK-responsive-tabs-AJAX-response"></span>';
		}		
		// no reset post data -- always dying at end
	}			


	public static function comment_query ( $include_string, $page ) {
 
 		/*
		* In 4.4, pagination controlled through WP_Comment_Query, so have to override discussion settings to do AJAX pagination.
		* Set filters in functions.php (pointing back to methods further below) to set page_comments = 1 and comments_per_page = 10
		* Also need to set page for WP_Comment_Query ( invoked in comments_template ) by overrideing query var. 
		*
		* Note: $page starts at 0 and is incremented in js after response from each ajax call; 
 		*/

		// point $wp_query global to the single post for which we are retrieving comments (no need to reset; will die before reuse);
 		global $wp_query;
 		$args = array (
			'p' => $include_string, 		
 		); 
 		$wp_query = new WP_Query ( $args ); 

		// still have to ask for the right page although pages will be set up by comments_template below
		// first set a top-level comment count.
		$count_query = new WP_Comment_Query();
		$top_level_count_args = array(
				'count'   => true,
				'orderby' => false,
				'post_id' => $include_string,
				'parent'  => 0, // note that if threading is disabled after threaded comments are posted
								// walker will still put them on same page as parent, even if exceeds per_page
								// so no point in checking whether threading before setting this arg
			);

		$top_level_count = $count_query->query( $top_level_count_args );

		if ( 'desc' == get_option( 'comment_order' ) ) {
			$page_needed = ceil ( $top_level_count / 10 ) - $page ; 
 		} else {
 			$page_needed = $page + 1 ;
 		}
		// go no further if have already shown all pages;
		if ( 0 >= $page_needed || $page_needed > ceil ( $top_level_count / 10 ) ) {
			echo '<!-- responsive-tabs-ajax-handler.php -- page out of range-->';
			die; 
		} 
		set_query_var( 'cpage' , $page_needed );

		// invoke comments_template in the loop ( pagination parameters for comments implemented in comments_template )
		// comments_template sets up a lot of variables for the query passed to WP_Comments_Query and tucks results into $wp_query
		// could go directly to comment query and wp_list_comment, but would duplicate much of the comments_template code
 		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();
			// following shouldn't be necessary, but is_single() is not performing in 4.4 as it did previously in this context
			// comments_template exits after a first test as to whether is appropriate to look for comments_open
			// comments_template looks for one of following to be true: is_single, is_page or $withcomments 
			global $withcomments;
			$withcomments = true; 
			comments_template( '/comments-ajax.php' );
 		}
 
	}

	/*
	* The following two functions are filters for the get_option function
	*/
	// set per page comments at 12 -- reasonable load and even number to assure color alternation works
	public function set_comments_per_page_for_ajax() {
		return 12;
	}
	// goal of ajax loading is speed of initial load, so do want to paginate to be able to handle large comment counts
	public function set_page_comments_for_ajax() {
		return 12;
	}
	
}


class Widget_Ajax_Parms  {

	public $widget_type;
	public $include_string;
	public $page;
	public $query_type;
	
	public function __construct ( $widget_type, $include_string = '',  $page = 0, $query_type = '' ) {
		$this->widget_type = $widget_type;	
		$this->include_string = $include_string;
		$this->page = $page;
		$this->query_type = $query_type;
	}
}


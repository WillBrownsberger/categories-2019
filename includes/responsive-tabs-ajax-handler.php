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
			 die ( __( 'Bad nonce in AJAX call to Responsive_Tabs_Ajax_Handler', 'responsive_tabs' ) );		
		}

		$next_function = $widget_parms->widget_type;		
		if ( 'latest_posts' == $next_function || 'latest_links' == $next_function ) {
			self::$next_function ( $widget_parms->include_string, $widget_parms->exclude_string, $widget_parms->page, false, 0 );
		} elseif ( 'latest_comments' == $next_function ) {
			$output = self::$next_function ( 
				$widget_parms->include_string, 
				$widget_parms->exclude_string, 
				$widget_parms->page, 
				false, // active tab false says not in widget mode -- doing a a straight AJAX call 
				0, 	 // infinite scroll not disabled if doing this call (screened out in js)
				8 )	 // number of comments to retrieve in each call
				;	
			echo $output;
		}
		wp_die();	
						
	}	


	/* generates list items for latest post list -- used by Front Page Latest Posts widget and by AJAX calls */
	public static function latest_posts ( $include_string, $exclude_string, $page, $widget_mode, $disable_infinite_scroll ) {

		$query_args = array (
			'post_status'            => 'publish',
			'pagination'             => true,
		 	'paged'                  => $page,
			'ignore_sticky_posts'    => false,
			'order'                  => 'DESC',
			'orderby'                => 'date',
		); 

		$include_cats_list = $include_string > '' ? explode ( ',', $include_string ) : '';
   	$exclude_cats_list = $exclude_string > '' ? explode ( ',', $exclude_string ) : '';
		if ( isset ( $include_cats_list[0] ) )	{
			if ( $include_cats_list[0] > '' ) {
				$query_args['category__in'] = $include_cats_list;
			}		
		}      
		if ( isset ( $exclude_cats_list[0] ) )	{
			if ( $exclude_cats_list[0] > '' ) {
				$query_args['category__not_in'] = $exclude_cats_list;
			}		
		}      

		$scroll_marker = '';
		if ( 0 == $disable_infinite_scroll ) {
			$query_args['posts_per_page'] = 8; // fix this to assure even number and likely first scroll move, also consistency on # from first to 2+ pages	
			$scroll_marker = 'id="responsive-tabs-ajax-insert"';	
		}

			     
		$latest_posts_query = new WP_Query($query_args); 

		if ( $latest_posts_query->have_posts() ) {

			if ( $widget_mode ) { // show header and start ul when coming from widget, not from ajax			
				echo '<ul class="post-list" ' . $scroll_marker . ' >';
				get_template_part( 'post', 'listheader' );
			}

			/* post list -- like post-list.php */ 
			global $responsive_tabs_post_list_line_count;  
			$responsive_tabs_post_list_line_count = 1; 			 
			
			while ( $latest_posts_query->have_posts() ) {

				$responsive_tabs_post_list_line_count = $responsive_tabs_post_list_line_count + 1;
				$latest_posts_query->the_post();		
				get_template_part ( 'post', 'listitems' );				

			} 
			
			// if coming from widget
			if ( $widget_mode ) {
				echo '</ul>'; // close post list
				// if infinite scroll not disabled, add scroll parms
				if ( 0 == $disable_infinite_scroll ) {
		// set up widget parms to pass as hidden value to widget ajax caller		
					$widget_parms = new Widget_Ajax_Parms ( 
						'latest_posts', 
						$include_string,
						$exclude_string,
						2 // page 2 is second page; pagination is incremented after retrieval;
					);
					$widget_parms_string = json_encode( $widget_parms );						
					echo '<div class="responsive_tabs_infinite_scroll_parms" id="responsive_tabs_infinite_scroll_parms">' . $widget_parms_string . '</div>';
				// if infinite scroll is disabled, do the usual page links 							
				} else { 		   
			   ?>
					<div id = "next-previous-links">
						<div id="previous-posts-link"><?php
							previous_posts_link('<strong>&laquo; Newer Entries </strong>');
						?> </div> 
						<div id="next-posts-link">  <?php
							next_posts_link('<strong>Older Entries &raquo; </strong>');
						?> </div>
					</div>
					<div class = "horbar-clear-fix"></div><?php
			  	}
			}
		// handle not found conditions only in widget mode -- just stop in AJAX		
		} elseif ( $widget_mode ) {	?>
			<div id="not-found">
				<h3><?php _e( 'No posts found. Check widget category settings.', 'responsive-tabs' ) ?></h3>
			</div>
		<?php	}

	// Restore original Post Data
	wp_reset_postdata();	
	
	
	} 

	public static function latest_comments ( $include_string, $exclude_string, $comment_page, $active_tab, $disable_infinite_scroll, $number ) {
 	
		if ( 0 == $disable_infinite_scroll ) {
			$number = 8; // always pull same number of records; avoid inconsistency between first page and second page counts	
		} 	
 		
		$offset					= $comment_page * $number;
		$include_clause = $include_string > '' ? ( ' AND user_id IN (' . $include_string . ') ' ) : ''; 
		$exclude_clause = $exclude_string > '' ? ( ' AND user_id NOT IN (' . $exclude_string . ') ' ) : '';
		
		global $wpdb;
		$widget_comment_query = 
			"
			SELECT comment_id, comment_date, comment_content, post_title 
			FROM $wpdb->comments c INNER JOIN $wpdb->posts p on c.comment_post_ID = p.ID 
			WHERE 
				post_status = 'publish' AND
				comment_approved = 1 AND
				( comment_type is NULL or comment_type = '')  
			$include_clause   
			$exclude_clause 
			ORDER BY comment_date_gmt DESC  
			LIMIT  $offset , 
			" . ( $number + 1 );

		$widget_comments = $wpdb->get_results( $widget_comment_query ); 

		$scroll_marker = '';
		if ( 0 == $disable_infinite_scroll ) {
			$scroll_marker = 'id="responsive-tabs-ajax-insert"';	
		}
		
		$output = '';
		if ( $widget_comments ) {

			if ( false !== $active_tab ) { // 0 is a valid tab value; if false, then just doing AJAX, so no <ul>
				$output .= '<ul class = "responsive-tabs-front-page-comment-list"' . $scroll_marker . '>';
				$output .= 
				'<li class="pl-odd">' .
					'<ul class="responsive-tabs-front-page-comment-list-headers">' .
						'<li class="responsive-tabs-front-page-comment-author">' . __( 'Commenter', 'responsive-tabs' ) . '</li>'. 
						'<li class="responsive-tabs-front-page-comment-post">' . __( 'Commenting on', 'responsive-tabs' ). '</li>' .
						'<li class="responsive-tabs-front-page-comment-date-time">' . __( 'Date, time', 'responsive-tabs' ) . '</li>' .
					'</ul>' .
				'</li>';		
			}
			
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
								'<a href="' . get_comment_link( $comment->comment_id ) . '">' .	
									esc_html( $comment->post_title ) . 
								'</a>'.
							'</li>' .
							'<li class="responsive-tabs-front-page-comment-date-time">' . 
								date_i18n( get_option( 'date_format' ), strtotime( $comment->comment_date ) ) . 
							'</li>' .
						'</ul>' .
						'<div class = "responsive-tabs-front-page-comment-excerpt">' . 
							apply_filters( 'comment_text', self::get_long_comment_excerpt( $comment->comment_content ) ) . '<br />' .
							'<a href="'. get_comment_link( $comment->comment_id ) . '">' . 
								__( 'View Comment in Context &raquo;', 'responsive-tabs' ) . 
							'</a>' .
						'</div>' .
					'</li>';
	    		}
			}
			if ( false !== $active_tab ) {	
				$output .= '</ul>';  // .responsive-tabs-front-page-comment-list
				
				if ( 0 == $disable_infinite_scroll ) {
					// set up widget parms to pass as hidden value to widget ajax caller		
					$widget_parms = new Widget_Ajax_Parms ( 
						'latest_comments', 
						$include_string,
						$exclude_string,
						1 // page 1 is second page for comments sql
					);
					$widget_parms_string = json_encode( $widget_parms );						
					echo '<div class="responsive_tabs_infinite_scroll_parms" id="responsive_tabs_infinite_scroll_parms">' . $widget_parms_string . '</div>';
				} else {
					// next previous comments list with same styles as next previous posts links 
					// note that have to use own query string here b/c comment-page query var does not work with home page and paged query var could conflict with latest posts widget 
					$output .=	'<div id = "next-previous-links">'; 
						if ( $comment_page > 0 ) {	
							$output .= '<div id="previous-posts-link">' .
									'<strong><a href="/?frontpagetab=' . $active_tab . '&comment_widget_page=' . ( $comment_page - 1 ) . '">&laquo; ' . __( 'newer comments', 'responsive-tabs' ) . '</a></strong>' .					 
							'</div>';
						} 
						if (  $number + 1 == $found_count ) { 
							$output .=	'<div id="next-posts-link">' .
									'<strong><a href="/?frontpagetab=' . $active_tab . '&comment_widget_page=' . ( $comment_page + 1 ) . '">' . __( 'older comments', 'responsive-tabs' ) . ' &raquo;</a></strong>' .
							'</div>'; 
						}
					$output .= '</div>';
					$output .=	'<div class = "horbar-clear-fix"></div>';
				}					
			}
 		} elseif ( false !== $active_tab) {
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

	public static function latest_links( $include_string, $exclude_string, $page, $widget_mode, $disable_infinite_scroll ) {
	
		// WP_Query arguments
		$args = array ( // notes that posts_per_page driven by post setting -- cannot seem to drive separately, despite literature to the contrary
			'tax_query'					 => array(
													array(
										            'taxonomy' => 'post_format',
										            'field' => 'slug',
										            'terms' => array( 'post-format-link' )
													)
													),
			'post_status'            => 'publish',
			'pagination'             => true,
		 	'paged'                  => $page,
			'ignore_sticky_posts'    => false,
			'order'                  => 'DESC',
			'orderby'                => 'date',
		);
	

		$scroll_marker = '';
		if ( 0 == $disable_infinite_scroll ) {
			$args['posts_per_page'] = 8; // fix this to assure even number and likely first scroll move	
			$scroll_marker = 'id="responsive-tabs-ajax-insert"';	
		}
	
	
		// The Query
		$link_query = new WP_Query( $args );
	
		if ( $link_query->have_posts() ) {
			if ( $widget_mode ) {
				echo '<!-- responsive-tabs-widgets.php -->' . // echoing to avoid white spaces in inline block
					'<ul class="post-list" ' . $scroll_marker . '>' . 
					'<li class = "pl-odd">' .
						'<ul class = "pl-headers">' .
							'<li class = "pl-post-title">' 	 . __( 'Headline', 'responsive-tabs' ) . '</li>' . 
							'<li class = "pl-post-author">' 	 . __( 'Categories, Tags', 'responsive' ) . ' </li>' .
							'<li class = "pl-post-date-time">'. __( 'Date', 'responsive' ) . '</li>' .
						'</ul>' .
					'</li>';
			}	
		
			$count = 1; 
			while ( $link_query->have_posts() ) {
				$link_query->the_post();
				$link 	= esc_url( responsive_tabs_url_grabber() );
				$title 	= get_the_title();
				$excerpt	= get_the_content();
				$read_more_pointer = ( 
					comments_open() ? 
						( '<a href="' . get_the_permalink() . '" rel="bookmark" ' . 
								'title="'. __( 'View the link with comments on this site ', 'responsive_tabs' ) . '">' . 
								__( 'Comment Here', 'responsive-tabs' ) .	'</a>'. __( ' or ', 'responsive-tabs' ) ) 
						: '' ) . 
					'<a href="' .  $link . '">' . __( 'Go to Link', 'responsive-tabs' ) . ' &raquo;</a>'; 
				$count = $count + 1;
				if( $count % 2 == 0 ) {
					$row_class = "pl-even";
				} else {
					$row_class = "pl-odd";
				}

				echo '<li ' ;
					post_class( $row_class ); 
					echo '>' .
					'<ul class="pl-post-item">' . 			
						'<li class="pl-post-title">' .
							'<a href="'  .  $link  . '" rel="bookmark" ' . 
									'title="'  .  __( 'View item', 'responsive-tabs' )  . '"> '  .  
									$title . ' ('. get_comments_number()  . ')' .
							'</a>' . 
						'</li>' .
						'<li class="pl-post-author">';
							the_category(', '); 
							the_tags( ', ', ', ', '' ); 
						echo '</li>' . 
						'<li class="pl-post-date-time">'. get_the_date() .'</li>'.
		         '</ul>' .
					'<div class="pl-post-excerpt">' .
						$excerpt . '<br />' . 
						$read_more_pointer .  
					'</div>' .         
 				'</li>';
			} // close while loop 
			if ( $widget_mode ) {
				if ( 0 == $disable_infinite_scroll ) {
					// set up widget parms to pass as hidden value to widget ajax caller		
						$widget_parms = new Widget_Ajax_Parms ( 
							'latest_links', 
							$include_string,
							$exclude_string,
							2 // page 2 is second page, next retrieved
						);
						$widget_parms_string = json_encode( $widget_parms );						
						echo '<div class="responsive_tabs_infinite_scroll_parms" id="responsive_tabs_infinite_scroll_parms">' . $widget_parms_string . '</div>';
					// if infinite scroll is disabled, do the usual page links 							
					echo '</ul><!-- close links list -->'; 
				} else { 
					echo '</ul><!-- close links list -->'; 
					// show multipost pagination links
					?> 
					<div id = "next-previous-links">
						<div id="previous-posts-link"> <?php
							previous_posts_link('<strong>&laquo; Newer Links </strong>');
						?> </div> <?php
						?> <div id="next-posts-link">  <?php
							next_posts_link('<strong>Older Links &raquo; </strong>', $link_query->max_num_pages);
						?> </div>
						<div class = 'horbar-clear-fix'></div>
					</div> <?php
				}
			}	
		// handle not found conditions		
		} elseif ( $widget_mode ) {	?>
			<div id="not-found">
				<h3><?php _e( 'No links found.', 'responsive-tabs' ) ?></h3>
			</div>
		<?php	}
	
		// Restore original Post Data
		wp_reset_postdata();
	}

}


class Widget_Ajax_Parms  {

	public $widget_type;
	public $include_string;
	public $exclude_string; 
	public $page;
	
	public function __construct ( $widget_type, $include_string = '', $exclude_string = '', $page = 0 ) {
		$this->widget_type = $widget_type;	
		$this->include_string = $include_string;
		$this->exclude_string = $exclude_string;
		$this->page = $page;
	}
}




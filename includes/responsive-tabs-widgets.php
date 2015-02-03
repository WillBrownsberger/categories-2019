<?php
/*
*	File: responsive-tabs-widgets.php
*	Description: Adds wide-format widgets for use in tabs on front page
*	-- Front Page Category List
*  -- Front Page Comment List
*  -- Front Page Post Summary
*  -- Front Page Text Widget
*  -- Front Page Archives
*  -- Front Page Latest Posts
*  -- Front Page Links List
*
*	@package responsive-tabs  
*/



/*
* Register Widgets
*/
add_action( 'widgets_init', 'register_responsive_tabs_widgets' );

function register_responsive_tabs_widgets() {
	register_widget ( 'Front_Page_Category_List' );
	register_widget ( 'Front_Page_Comment_List' );
	register_widget ( 'Front_Page_Post_Summary' );
	register_widget ( 'Front_Page_Text_Widget' );
	register_widget ( 'Front_Page_Archives' );
	register_widget ( 'Front_Page_Latest_Posts' );
	register_widget ( 'Front_Page_Links_List' );
}

/*
* Front_Page_Category_List widget
* http://codex.wordpress.org/Widgets_API
* see also http://code.tutsplus.com/articles/building-custom-wordpress-widgets--wp-25241
*/

class Front_Page_Category_List extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_category_list', // Base ID
			__( 'Front Page Category List', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Top and second level categories in wide (responsive) format for front page tabs or highlight area.', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );	
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		echo '<!-- responsive-tabs front page category list widget -->' . $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title; 	
		} 		

			// Category list headers
			echo  '<ul class = "responsive-tabs-front-page-category-list">' .
		     	'<li class = "pl-odd">' .
		     		'<ul class = "rtfpcl-category-headers">' .
			     		'<li class="rtfpcl-category-name">' . __( 'Category (post count)', 'responsive-tabs' ) . '</li>' . 
			     		'<li class = "rtfpcl-subcategory-list">' . __( 'Subcategories', 'responsive-tabs' ) . '</li>' .
		     		'</ul>' . 
		     	'</li>';
	
			// Category list items
				$args = array(
				  'orderby'		=> 'name',
				  'order' 		=> 'ASC',
				  'hide_empty'	=> 0,
				  'pad_counts'	=> 1
				  );	     	
				$categories = get_categories( $args );
				$categories = wp_list_filter( $categories, array( 'parent' => 0) ); // have list of parent categories with padded counts
				$count = 1;
				foreach( $categories as $category ) {
				  	$count = $count+1;
				  	if( $count % 2 == 0 ) {
				  		$row_class = "pl-even"; // alternating colors same as post list
				  	} else {
				  		$row_class = "pl-odd";
				  	} 
				   echo '<li class = '. $row_class .' >
				   	<ul class = "responsive-tabs-front-page-category-list-item">' .
							'<li class="rtfpcl-category-name">
								<a href="' . get_category_link( $category->term_id ) . 
				   	 			'" title="' . sprintf( __( "View all posts in %s", 'responsive-tabs' ), $category->name ) . '" ' . '>' . strtolower ( $category->name ) . ' (' .  $category->count . ')' .
				   	 			'</a>' .
				   	 	'</li>';
						  	$subargs = array(
							  'orderby' 	=> 'name',
							  'order' 		=> 'ASC',
							  'hide_empty' => 0,
							  'parent'		=>$category->cat_ID,
			  				);	 
							$subcategories = get_categories( $subargs );
			// Horizontal list of subcategories within subcategory 
							echo '<li class = "rtfpcl-subcategory-list">';
								 $sc_count = 0;		 
								 foreach( $subcategories as $subcategory ) {
								 	if ( $sc_count > 0) {
								 		echo ', ';
								 	} 
							    	echo '<a href="' . get_category_link( $subcategory->term_id ) . '" 
							      	title="' . sprintf( __( "View all posts in %s", 'responsive-tabs' ), $subcategory->name ) . '" ' . '>' . strtolower ( $subcategory->name ) . 
							      '</a>';
								 	$sc_count = $sc_count+1; 
								 }
							echo '</li>'; 
						echo '</ul>' . // rtfpcl-subcategory-list
					'</li>'; // responsive-tabs-front-page-category-list-item    
				} // for each category loop
			echo "</ul>"; // responsive-tabs-front-page-category-list
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = apply_filters( 'title_save_pre', $new_instance['title'] ); // no tags in title
		return $instance;
	}

	function form( $instance ) {
		
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (usually unnecessary in front page tab):', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	 	<?php
	} 	

}

/**
 * Recent comments widget -- with include/exclude of authors and pagination
 * Format is wide and includes longer excerpts than standard
 */
class Front_Page_Comment_List extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_comment_list', // Base ID
			__( 'Front Page Comment List', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Recent comment list with excerpts in wide (responsive) format for front page tabs or highlight area.', 'responsive-tabs' ), ) // Args
		);
	}
	
	function widget( $args, $instance ) {

 		extract( $args, EXTR_SKIP );
      
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
		if ( ! $number ) {
 			$number = 10;
		}

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$include_list = isset( $instance['include_users_list'] ) ? $instance['include_users_list'] : '' ;
		$exclude_list = isset( $instance['exclude_users_list'] ) ? $instance['exclude_users_list'] : '';
		$include_clause = $include_list > '' ? ( ' AND user_id IN (' . $include_list . ') ' ) : ''; 
		$exclude_clause = $exclude_list > '' ? ( ' AND user_id NOT IN (' . $exclude_list . ') ' ) : '';
				
		/* pagination and link variables */
		$active_tab 			= isset( $_GET[ 'frontpagetab' ] )  ? $_GET[ 'frontpagetab' ] : 0;
		$comment_page			= isset( $_GET[ 'comment_widget_page' ] )  ? $_GET[ 'comment_widget_page' ] : 0;
		$offset					= $comment_page * $number;
		
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

		$output = '<!-- responsive-tabs Front_Page_Comment_List Widget, includes/responsive-tabs-widgets.php -->';		
		$output .= $before_widget  ;										// is blank in home widget areas
		if ( $title ) {
			$output .= $before_title . $title . $after_title; 	
		} 		

		$output .= '<ul class = "responsive-tabs-front-page-comment-list">';
		if ( $widget_comments ) {

			// comment list header
			$output .= 
			'<li class="pl-odd">' .
				'<ul class="responsive-tabs-front-page-comment-list-headers">' .
					'<li class="responsive-tabs-front-page-comment-author">' . __( 'Commenter', 'responsive-tabs' ) . '</li>'. 
					'<li class="responsive-tabs-front-page-comment-post">' . __( 'Commenting on', 'responsive-tabs' ). '</li>' .
					'<li class="responsive-tabs-front-page-comment-date-time">' . __( 'Date, time', 'responsive-tabs' ) . '</li>' .
				'</ul>' .
			'</li>';		
			
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
							apply_filters( 'comment_text', $this->get_long_comment_excerpt( $comment->comment_content ) ) . '<br />' .
							'<a href="'. get_comment_link( $comment->comment_id ) . '">' . 
								__( 'View Comment in Context &raquo;', 'responsive-tabs' ) . 
							'</a>' .
						'</div>' .
					'</li>';
	    		}
			}
	
			$output .= '</ul>';  // .responsive-tabs-front-page-comment-list
			
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
			
 		} else {
 			$output .= '<h3>' . __('No approved comments selected!', 'responsive-tabs' ) . '</h3>';
 		}		

		$output .= $after_widget;

		echo $output; 
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']  = apply_filters( 'title_save_pre', $new_instance['title'] );
		$instance['number'] = absint( $new_instance['number'] );
		$instance['include_users_list'] = responsive_tabs_clean_post_list( $new_instance['include_users_list'] );
		$instance['exclude_users_list'] = responsive_tabs_clean_post_list( $new_instance['exclude_users_list'] );
		return $instance; 
	}

	function form( $instance ) {
		
		$number 								= isset( $instance['number'] ) ? absint( $instance['number'] ) : 10;
		$title  								= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$include_users_list 				= isset( $instance['include_users_list'] ) ? responsive_tabs_clean_post_list( $instance['include_users_list'] ) : '';
		$exclude_users_list 				= isset( $instance['exclude_users_list'] ) ? responsive_tabs_clean_post_list( $instance['exclude_users_list'] ) : '';
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (usually unnecessary in front page tab):', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show per page:', 'responsive-tabs' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	
		<p><label for="<?php echo $this->get_field_id( 'include_users_list' ); ?>"><?php _e( 'ID number(s) of users to <em>include</em>:<br /> (single or multiple separated by commas<br /> -- leave blank for all)<br />', 'responsive-tabs' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'include_users_list' ); ?>" name="<?php echo $this->get_field_name( 'include_users_list' ); ?>" type="text" value="<?php echo $include_users_list; ?>" size="30" /></p>

		<p><label for="<?php echo $this->get_field_id( 'exclude_users_list' ); ?>"><?php _e( 'ID number(s) of users to <em>exclude</em>:<br />', 'responsive-tabs' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'exclude_users_list' ); ?>" name="<?php echo $this->get_field_name( 'exclude_users_list' ); ?>" type="text" value="<?php echo $exclude_users_list; ?>" size="30" /></p>
		<?php 	
	}

	/*
	* long comment excerpt method
	* derived from http://developer.wordpress.org/reference/functions/get_comment_excerpt/
	*/
	function get_long_comment_excerpt( $comment_content ) {
		
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
}

/*
*
* Widget to ease population of front page widget area -- creates essentially text widgets with links and images based on post numbers selected
*
*/

class Front_Page_Post_Summary extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_post_summary', // Base ID
			__( 'Front Page Post Summary', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Variable width widget for tiling of post links, excerpt and content in front page tabs or highlight area.', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
 		extract( $args, EXTR_SKIP );
 		
 		$output = '<!-- responsive-tabs Front_Page_Post_Summary widget, includes/responsive-tabs-widgets.php -->';
		$output .=  $before_widget;										// is blank in home widget areas
		
		$responsive_tabs_widget_width = isset( $instance['responsive_tabs_widget_width'] ) ? $instance['responsive_tabs_widget_width'] : 'pebble';			

		/* set up for variable widget widget */
		if ( 'pebble' == $responsive_tabs_widget_width ) {
			$output .= '<div class = "home-narrow-widget-wrapper">'; // div limits height and width and floats the widgets left
			$responsive_tabs_image_width		= 'front-page-thumb';
			$responsive_tabs_both_image_width 	= 'front-page-half-thumb';
		} else {
			$output .= '<div class = "home-wide-widget-wrapper">';
			$responsive_tabs_image_width 		= 'full-width';
			$responsive_tabs_both_image_width 	= 'post-content-width';
		}		

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		if ( $title ) {
			$output .= $before_title . $title . $after_title; 		// <h2 class = 'widgettitle'> . . . </h2>
		} 		

		$post_list 				= isset( $instance['post_list'] ) 				? $instance['post_list'] : '';
		$single_display_mode = isset( $instance['single_display_mode'] ) 	? $instance['single_display_mode'] : '';
		
		$post_list_array 		= explode( ',', $post_list );

		if ( count( $post_list_array ) > 1) { // if have a list of post id's, output a <ul> list of links
			$output .= '<ul class="front-page-widget-post-list">';
				foreach ( $post_list_array as $post_ID ) {
					$permalink 	= get_permalink( $post_ID );
					$title 		= get_the_title( $post_ID );
					if( $title && $permalink ) {
						$output .= '<li><a href="'. $permalink . '" title = "' . __( 'Read post ', 'responsive-tabs' ) . $title . '">' . $title .'</a></li>';
					} else {
						$output .= 'Check post list IDs in Front Page Post Summary widget';
					}			
				}		
			$output .= '</ul>';
		} elseif( 1 == count( $post_list_array ) ) { // if have exactly one, show excerpt or image or both according to options set
			foreach ( $post_list_array as $post_ID ) {
					$permalink 	= get_permalink( $post_ID );
					$post 		= get_post( $post_ID );
					$title 		= get_the_title( $post_ID );
					
					if ( ! is_null ( $post ) ) {
						if( 'excerpt' == $single_display_mode ) {				
							$output .= '<div class = "bulk-text-padding-wrapper">' . apply_filters( 'the_excerpt', $post->post_excerpt ) . '<a href="'. $permalink . '" title = "' . __( 'Read post ', 'responsive-tabs' )  . $title . '">' . __( 'Read More', 'responsive-tabs') . '&raquo;</a>'  . '</div>';	
						}	elseif( 'image' == $single_display_mode ) {				
							$output .=  '<a href="'. $permalink . '" title = "' . __( 'Read post ', 'responsive-tabs' ) . $title . '"> ' . get_the_post_thumbnail( $post_ID, $responsive_tabs_image_width ) . '</a>';		  
				      } elseif( 'both' == $single_display_mode ) {
					      $output .=  '<div class = "bulk-text-padding-wrapper">' .  '<div class = "bulk-image-float-left"><a href="'. $permalink . '" title = "' . __( 'Read post ', 'responsive-tabs' )  . $title . '"> ' . get_the_post_thumbnail($post_ID, $responsive_tabs_both_image_width) . '</a></div>' .
					       	apply_filters( 'the_excerpt', $post->post_excerpt )  . '<a href="'. $permalink . '" title = "' . __( 'Read post ', 'responsive-tabs' ) . $title . '">' . __( 'Read More', 'responsive-tabs') . '&raquo;</a>'  . '</div>';	
						} elseif( 'content' == $single_display_mode ) {
							$output .=  '<div class = "bulk-text-padding-wrapper">' . apply_filters( 'the_content', $post->post_content ) . '</div>';
						} elseif( 'all' == $single_display_mode ) {
							$output .=  '<div class = "bulk-text-padding-wrapper">' . '<div class = "bulk-image-float-left-large">' . get_the_post_thumbnail( $post_ID, $responsive_tabs_both_image_width ) . '</div>';  								
							$output .= apply_filters( 'the_content', $post->post_content ) . '</div>';
						}
					} else { 
						$output .= 'Check settings of Front Page Post Summary Widget';
					}			  
			  }     
		} else { 
			$output .= 'Check settings of Front Page Post Summary Widget';
		}	
		
		if ( 'full' == $responsive_tabs_widget_width ) { // pull down background color 
			$output .= '<div class = "horbar-clear-fix"></div>';			
		}				
		
		$output .= '</div>'; // close textwidget or home_bulk_widget
		 		
		$output .= $after_widget ;									// is blank in home widget areas

		echo $output;
	}

	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['title'] 								= apply_filters( 'title_save_pre', $new_instance['title'] ); // no tags in title
		$instance['post_list'] 							= responsive_tabs_clean_post_list( $new_instance['post_list'] );
		$instance['single_display_mode'] 			= strip_tags( $new_instance['single_display_mode'] );
		$instance['responsive_tabs_widget_width'] = strip_tags( $new_instance['responsive_tabs_widget_width'] );
		
		return $instance;
	}


	function form( $instance ) {
		
		$title  								= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$post_list 							= isset( $instance['post_list'] ) ? responsive_tabs_clean_post_list( $instance['post_list'] ) : '';
		$single_display_mode 			= isset( $instance['single_display_mode'] ) ? strip_tags( $instance['single_display_mode'] ) : 'excerpt';
		$responsive_tabs_widget_width = isset( $instance['responsive_tabs_widget_width'] ) ? strip_tags( $instance ['responsive_tabs_widget_width'] ) : 'pebble';
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'post_list' ); ?>"><?php _e( 'ID number(s) of post(s) to show:<br /> (single or multiple separated by commas)<br />', 'responsive-tabs' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'post_list' ); ?>" name="<?php echo $this->get_field_name( 'post_list' ); ?>" type="text" value="<?php echo $post_list; ?>" size="30" /></p>
		
		<?php 
      $single_display_options = array(
			'0' => array(
				'value' =>	'excerpt',
				'label' =>  __( 'Show excerpt for single', 'responsive-tabs' ),
			),
			'1' => array(
				'value' =>	'image',
				'label' =>  __( 'Show image for single ', 'responsive-tabs' ),
			),
			'2' => array(
				'value' => 'both',
				'label' => __( 'Show image and excerpt for single', 'responsive-tabs' ),
			),
			'3' => array(
				'value' => 'content',
				'label' => __( 'Show content for single', 'responsive-tabs' ),
			),
			'4' => array(
				'value' => 'all',
				'label' => __( 'Show featured image and content for single', 'responsive-tabs' ),
			),
		);
		$selected = $single_display_mode;  
   	?>
 		<label for="single_display_mode"><?php _e('Display mode for single posts: <br/>(if multiple, widget will only show titles)<br />', 'responsive-tabs' ); ?> </label>
		<select id="<?php echo $this->get_field_id( 'single_display_mode' ); ?>" name="<?php echo $this->get_field_name( 'single_display_mode' ); ?>">    	
		<?php 
			$p = '';
			$r = '';
			foreach (  $single_display_options as $option ) {
		    	$label = $option['label'];
				if ( $selected == $option['value'] ) { // Make selected first in list
			   	$p = '<option selected="selected" value="' . $option['value']  . '">' . $label . '</option>';
				} else {
					$r .= '<option value="' . $option['value'] . '">' . $label . '</option>';
	         }
		   } 
		 	echo $p . $r . 
	 	'</select><br /><br />'; 
	 	
	 	$responsive_tabs_widget_width_options = array(
			'0' => array(
				'value' =>	'pebble',
				'label' =>  __( 'Show 4 widgets per row on desk top ', 'responsive-tabs' ),
			),
			'1' => array(
				'value' =>	'full',
				'label' =>  __( 'Show 1 widget per row on all screens', 'responsive-tabs' ),
			),
		);
		
		$selected = $responsive_tabs_widget_width;  
   	?>
 		<label for="responsive_tabs_widget_width"><?php _e('Widget Width:<br />', 'responsive-tabs' ); ?> </label>
		<select id="<?php echo $this->get_field_id( 'responsive_tabs_widget_width' ); ?>" name="<?php echo $this->get_field_name( 'responsive_tabs_widget_width' ); ?>">    	
		<?php 
			$p = '';
			$r = '';
			foreach (  $responsive_tabs_widget_width_options as $option ) {
		    	$label = $option['label'];
				if ( $selected == $option['value'] ) { // Make selected first in list
			   	$p = '<option selected="selected" value="' . $option['value']  . '">' . $label . '</option>';
				} else {
					$r .= '<option value="' . $option['value'] . '">' . $label . '</option>';
	         }
		   } 
		 	echo $p . $r . 
	 	'</select><br />';
	} 
}

/*
* Front Page Text Widget -- text widgets with user optional width settings for tab population
*/
class Front_Page_Text_Widget extends WP_Widget {  

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_text_widget', // Base ID
			__( 'Front Page Text Widget', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Variable width text widget for tiling of arbitrary content in front page tabs or highlight area.', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
 		extract( $args, EXTR_SKIP );
 		
 		$output = '<!-- responsive-tabs Front_Page_Text_Widget, includes/responsive-tabs-widgets.php -->';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base  );
		$output .=  $before_widget;										// is blank in home widget areas
		
		$responsive_tabs_widget_width = isset( $instance['responsive_tabs_widget_width'] ) ? $instance['responsive_tabs_widget_width'] : 'pebble';			

		// set up for variable widget widget 
		if ( 'pebble' == $responsive_tabs_widget_width ) {
			$output .= '<div class = "home-narrow-widget-wrapper">'; // div limits height and width and floats the widgets left
		} else {
			$output .= '<div class = "home-wide-widget-wrapper">';
		}		

		if ( $title ) {
			$output .= $before_title . $title . $after_title; 		// <h2 class = 'widgettitle'> . . . </h2>
		} 		

		$free_form_text 		= isset( $instance['free_form_text'] ) ? $instance['free_form_text'] : '';
		if ( $free_form_text > '' ) {
			$output .=  '<div class = "home-text-widget">' . apply_filters( 'widget_text', $free_form_text ) . '</div>';		
		} 	

		$output .= '</div>'; // close textwidget or home_bulk_widget
		 		
		$output .= $after_widget ;									// is blank in home widget areas

		echo $output;
	}

	function update( $new_instance, $old_instance ) {
		$instance 											= $old_instance;
		$instance['title'] 								= apply_filters( 'title_save_pre',  $new_instance['title'] ); // no tags in title
		$instance['responsive_tabs_widget_width'] = strip_tags( $new_instance['responsive_tabs_widget_width'] );
		$instance['free_form_text'] 					= apply_filters( 'content_save_pre', $new_instance['free_form_text'] ) ;
		return $instance;
	}


	function form( $instance ) {
		
		$title  								= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$responsive_tabs_widget_width = isset( $instance['responsive_tabs_widget_width'] ) ? strip_tags( $instance ['responsive_tabs_widget_width'] ) : 'pebble';
		$free_form_text 					= isset( $instance['free_form_text'] ) ? apply_filters ( 'widget_text', $instance ['free_form_text'] ) : '';		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		 	
		<?php 	$responsive_tabs_widget_width_options = array(
			'0' => array(
				'value' =>	'pebble',
				'label' =>  __( 'Show 4 widgets per row on desk top ', 'responsive-tabs' ),
			),
			'1' => array(
				'value' =>	'full',
				'label' =>  __( 'Show 1 widget per row on all screens', 'responsive-tabs' ),
			),
		);
		
		$selected = $responsive_tabs_widget_width;  
   	?>
 		<label for="responsive_tabs_widget_width"><?php _e('Widget Width:<br />', 'responsive-tabs' ); ?> </label>
		<select id="<?php echo $this->get_field_id( 'responsive_tabs_widget_width' ); ?>" name="<?php echo $this->get_field_name( 'responsive_tabs_widget_width' ); ?>">    	
		<?php 
			$p = '';
			$r = '';
			foreach (  $responsive_tabs_widget_width_options as $option ) {
		    	$label = $option['label'];
				if ( $selected == $option['value'] ) { // Make selected first in list
			   	$p = '<option selected="selected" value="' . $option['value']  . '">' . $label . '</option>';
				} else {
					$r .= '<option value="' . $option['value'] . '">' . $label . '</option>';
	         }
		   } 
		 	echo $p . $r . 
	 	'</select><br />';?>

		<p><label for="<?php echo $this->get_field_id( 'free_form_text' ); ?>"><?php _e( 'Free form text (may include html as in a post)<br />(text widget with width control)<br />','responsive-tabs' ); ?></label>
		<textarea type="text" rows = "5" cols = "20" id="<?php echo $this->get_field_id( 'free_form_text' ); ?>" name="<?php echo $this->get_field_name( 'free_form_text' ); ?>"><?php echo $free_form_text; ?></textarea></p> 	 	
	 	<?php
	} 
}


/*
* Front Page Archive Widget
*/

class Front_Page_Archives extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_archives', // Base ID
			__( 'Front Page Archives', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Archive widget in wide (responsive) format.', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
 		extract( $args, EXTR_SKIP );
 		
 		$output = '<!-- responsive-tabs Front_Page_Archives, includes/responsive-tabs-widgets.php -->';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ;
		
		$output .=  $before_widget  ;										// is blank in home widget areas
		
		if ( $title ) {
			$output .= $before_title . $title . $after_title; 		// <h2 class = 'widgettitle'> . . . </h2>
		} 		

		/* get counts */		
		global $wpdb;
		$month_counts = $wpdb->get_results( 
			"
			SELECT YEAR(post_date) AS YEAR, MONTH(post_date) AS MONTH, count(ID) AS POST_COUNT 
			FROM $wpdb->posts 
			WHERE post_status = 'publish' AND post_type = 'post' 
			GROUP BY YEAR(post_date), MONTH(post_date)
			" 
			, OBJECT 
			);
		
		/* prepare to display counts */
		$month_count_array 	= array();
		$first_year 			= 9999; 
		$last_year				= 0;
		
		foreach ( $month_counts as $month_count ) { /* tabulate month counts in array and identify first and last years */
			$month_count_array[$month_count->YEAR][$month_count->MONTH] = $month_count->POST_COUNT;
			//$output .= '<p>year:' . $month_count->YEAR . ' month: ' . $month_count->MONTH . ' count: ' . $month_count->POST_COUNT . '</p>'; 		
			if ( $month_count->YEAR < $first_year ) {
				$first_year = $month_count->YEAR;			
			} 
			if ( $month_count->YEAR > $last_year ) {
				$last_year = $month_count->YEAR;			
			} 
		}	
	 
	 	/* display counts including zero counts in range */
		if ( $last_year > 0 ) {
			$output .=  '<ul class = "responsive-tabs-front-page-archives">' . /* use styling consistent with category list */
		     	'<li class = "pl-odd">' .
		     		'<ul class = "rtfpcl-category-headers">' .
			     		'<li class="rtfpa-year">' . __( 'Posts', 'responsive-tabs' ) . '</li>'; 
						for ( $month_index = 1; $month_index <= 12; $month_index++ ) {
			     			$output .= '<li class="rtfpa-month">' . substr( date_i18n( "F Y ", mktime( 0, 0, 0, $month_index, 10 ) ), 0, 1 ) . '</li>';
			     		}
		     		$output .= '</ul>' . 
		     	'</li>';
	
			global $month; 
			$count = 1;
			for ( $year_index = $last_year; $year_index >= $first_year; $year_index-- ) {
			  	$count = $count+1;
			  	if( $count % 2 == 0 ) {
			  		$row_class = "pl-even"; // alternating colors same as post list
			  	} else {
			  		$row_class = "pl-odd";
			  	} 
			  	$output .= '<li class = "' . $row_class . '">' .
			  		'<ul class = "responsive-tabs-front-page-archives-list-item">' . 
			  			'<li class="rtfpa-year">' . $year_index . '</li>';

						for ( $month_index = 1; $month_index <= 12; $month_index++ ) {
								if ( isset ( $month_count_array[$year_index][$month_index] ) ) {
									$display =  '<a href = "' . get_month_link ( $year_index, $month_index ) . '" ' . 
										'title = "'  .  __( 'View all posts from ', 'responsive-tabs' ) . date_i18n( "F Y ", mktime( 0, 0, 0, $month_index, 10, $year_index ) ) . '">' .   
										$month_count_array[$year_index][$month_index] . 
										'</a>'; 
								} else {
									$display = '0';								
								}
								$output .= '<li class = "rtfpa-month">' . $display . '</li>'; 					
						}
				
				$output .= '</ul></li>';
			}
			$output .= '</ul>';	
		} 	

		$output .= $after_widget ;									// is blank in home widget areas

		echo $output;
	}

	function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title'] 	= apply_filters( 'title_save_pre',  $new_instance['title'] ); // no tags in title
		return $instance;
	}


	function form( $instance ) {
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (usually unnecessary in front page tab):', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	 	<?php
	} 
}

/*
* Front Page Latest Posts Widget -- post list in wide format with include/exclude options by category
*/

class Front_Page_Latest_Posts extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_latest_posts', // Base ID
			__( 'Front Page Latest Posts', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Latest posts widget in wide (responsive) format for front page tabs or highlight area. Can include or exclude categories.', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
 		extract( $args, EXTR_SKIP );
 		
 		echo '<!-- responsive-tabs Front_Page_Latest_Posts, includes/responsive-tabs-widgets.php -->';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$include_cats_list = isset( $instance['include_cats_list'] ) ? explode(',', $instance['include_cats_list'] ) : '' ;
		$exclude_cats_list = isset( $instance['exclude_cats_list'] ) ? explode(',', $instance['exclude_cats_list'] ) : '';		
		
		echo $before_widget  ;										// is blank in home widget areas
		
		if ( $title ) {
			echo $before_title . $title . $after_title; 		// <h2 class = 'widgettitle'> . . . </h2>
		} 		

		/* do query for posts meeting category screen */
		$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$query_args = array (
			'post_status'            => 'publish',
			'pagination'             => true,
		 	'paged'                  => $page,
			'ignore_sticky_posts'    => false,
			'order'                  => 'DESC',
			'orderby'                => 'date',
		); 
   	
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
      
		$latest_posts_query = new WP_Query($query_args); 

		if ( $latest_posts_query->have_posts() ) {
		
			/* post list headers -- echoing to avoid white spaces in inline-block styling*/ 
			echo '<!-- latest posts from /responsive-tabs/includes/responsive-tabs-widgets.php -->' . 
			'<ul class="post-list">';
			
				get_template_part( 'post', 'listheader' ); 


			/* post list -- tracks post-list.php,but has different query base */ 
			global $responsive_tabs_post_list_line_count;  
			$responsive_tabs_post_list_line_count = 1; 			 
			
			while ( $latest_posts_query->have_posts() ) {

				$responsive_tabs_post_list_line_count = $responsive_tabs_post_list_line_count + 1;
				$latest_posts_query->the_post();		
				get_template_part ( 'post', 'listitems' );				

			} ?> 
			
			</ul> <!-- post-list -->
		   
			<div id = "next-previous-links">
				<div id="previous-posts-link"><?php
					previous_posts_link('<strong>&laquo; Newer Entries </strong>');
				?> </div> 
				<div id="next-posts-link">  <?php
					next_posts_link('<strong>Older Entries &raquo; </strong>');
				?> </div>
			</div>
			<div class = "horbar-clear-fix"></div><?php
		  
		// handle not found conditions		
		} else {	?>
			<div id="not-found">
				<h3><?php _e( 'No posts found. Check widget category settings.', 'responsive-tabs' ) ?></h3>
		</div>
		<?php	}

	// Restore original Post Data
	wp_reset_postdata();
		
		echo $after_widget ;									// is blank in home widget areas
	}

	function update( $new_instance, $old_instance ) {
		$instance 							 = $old_instance;
		$instance['title'] 				 = apply_filters( 'title_save_pre',  $new_instance['title'] ); // no tags in title
		$instance['include_cats_list'] = responsive_tabs_clean_post_list( $new_instance['include_cats_list'] );
		$instance['exclude_cats_list'] = responsive_tabs_clean_post_list( $new_instance['exclude_cats_list'] );
		return $instance;
	}

	function form( $instance ) {
		
		$title  								= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$include_cats_list 				= isset( $instance['include_cats_list'] ) ? responsive_tabs_clean_post_list( $instance['include_cats_list'] ) : '';
		$exclude_cats_list 				= isset( $instance['exclude_cats_list'] ) ? responsive_tabs_clean_post_list( $instance['exclude_cats_list'] ) : '';
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (usually unnecessary in front page tab):', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	 	
		<p><label for="<?php echo $this->get_field_id( 'include_cats_list' ); ?>"><?php _e( 'ID number(s) of categories to <em>include</em>:<br /> (single or multiple separated by commas -- will not include subcategories; leave blank for all)<br />', 'responsive-tabs' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'include_cats_list' ); ?>" name="<?php echo $this->get_field_name( 'include_cats_list' ); ?>" type="text" value="<?php echo $include_cats_list; ?>" size="30" /></p>

		<p><label for="<?php echo $this->get_field_id( 'exclude_cats_list' ); ?>"><?php _e( 'ID number(s) of categories to <em>exclude</em>:<br />', 'responsive-tabs' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'exclude_cats_list' ); ?>" name="<?php echo $this->get_field_name( 'exclude_cats_list' ); ?>" type="text" value="<?php echo $exclude_cats_list; ?>" size="30" /></p>
		 	
	 	
	 	<?php
	} 
}


/*
*
* Create link list widget
*/

class Front_Page_Links_List extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_links_list', // Base ID
			__( 'Front Page Links List', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Show links (i.e., posts with post-format of link) with categories and tags in wide (responsive) format', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
		extract( $args, EXTR_SKIP );	
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		echo $before_widget;
		
		if ( $title ) {
			echo $before_title . $title . $after_title; 		// <h2 class = 'widgettitle'> . . . </h2>
		} 		
		
		
		$this->show_latest_links();
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title']  	= apply_filters( 'title_save_pre',  $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (usually unnecessary in front page tab):', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<?php	 	
	}
	

	function show_latest_links() {
	
		$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	
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
	
		// The Query
		$link_query = new WP_Query( $args );
	
		if ( $link_query->have_posts() ) {
	
			echo '<!-- responsive-tabs-widgets.php -->' . // echoing to avoid white spaces in inline block
				'<ul class="post-list">' . 
				'<li class = "pl-odd">' .
					'<ul class = "pl-headers">' .
						'<li class = "pl-post-title">' 	 . __( 'Headline', 'responsive-tabs' ) . '</li>' . 
						'<li class = "pl-post-author">' 	 . __( 'Categories, Tags', 'responsive' ) . ' </li>' .
						'<li class = "pl-post-date-time">'. __( 'Date', 'responsive' ) . '</li>' .
					'</ul>' .
				'</li>';
				
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
				
		// handle not found conditions		
		} else {	?>
			<div id="not-found">
				<h3><?php _e( 'No links found.', 'responsive-tabs' ) ?></h3>
		</div>
		<?php	}
	
		// Restore original Post Data
		wp_reset_postdata();
	}
}
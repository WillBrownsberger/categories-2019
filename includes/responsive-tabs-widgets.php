<?php
/*
*	File: responsive-tabs-widgets.php
*	Description: Adds widgets for use in tabs on front page
*	-- Front Page Category List
*  -- Front Page Comment List
*  -- Front Page Post Summary
*  -- Front Page Text Widget
*  -- Front Page Archives
*
*	@package responsive-tabs  
*/

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." );

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
			array( 'description' => __( 'Top and second level categories in wide format', 'responsive-tabs' ), ) // Args
		);
	}


	function widget( $args, $instance ) {
		
		extract( $args );	// Note -- no options for this widget (title equivalent is front page tab head), but arguments include $before_widget and $after_widget
 
		echo '<!-- responsive-tabs front page category list widget -->' . $before_widget;
 	 		
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
}

/**
 * Recent_Comments widget class derived (with changes) from WP_widget_comment_list in standard package default-widgets.php
 * Simplified widget and formats wide and adds fuller excerpts
 */
class Front_Page_Comment_List extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_comment_list', // Base ID
			__( 'Front Page Comment List', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Recent comment list in wide format with excerpts.  Excludes admin and editor comments.', 'responsive-tabs' ), ) // Args
		);
	}
	
	function widget( $args, $instance ) {

		global $comments, $comment;

 		extract( $args, EXTR_SKIP );
 		$output = '<!-- responsive-tabs Front_Page_Comment_List Widget, includes/responsive-tabs-widgets.php -->';

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 50;
		if ( ! $number ) {
 			$number = 50;
		}

		$exclude_editorial_comments = ( isset( $instance['exclude_editorial_comments'] ) ) ? $instance['exclude_editorial_comments'] : false;

		$args = array( 
			'comment_type' => '', 
			'number' 		=> $number, 
			'status' 		=> 'approve', 
			'post_status' 	=> 'publish',
			'orderby' 		=> 'post_ID',
			'order' 			=> 'desc', 
		);
				
		// comment query
		$comments_query = new WP_Comment_Query;
		$comments = $comments_query->query( $args );

		$output .= $before_widget;

		$output .= '<ul class = "responsive-tabs-front-page-comment-list">';
		if ( $comments ) {

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
			$count = 1; 			
			foreach ( (array) $comments as $comment) {
						
				$editor = FALSE;
				if( $comment->user_id > 0 ) {
					$editor = user_can( $comment->user_id, 'delete_others_posts' );
				} 
				if( ! $editor || ! $exclude_editorial_comments ) { // limit comment output to user comments, exclude editor and admin replies 
				  	$count = $count+1;
				  	if( $count % 2 == 0 ) {
				  		$row_class = "pl-even";
				  	} else {
				  		$row_class = "pl-odd";
				  	}
				  		 
				   $comment_date_time = new DateTime( $comment->comment_date );
				   $output .=  
				   '<li class="' . $row_class . '">' . 
   					'<ul class="responsive-tabs-front-page-comment-list-item">' . 
							'<li class="responsive-tabs-front-page-comment-author">'. get_comment_author_link() .  '</li>' . 
							'<li class="responsive-tabs-front-page-comment-post">' . 
								'<a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '">' .	
									esc_html( get_the_title( $comment->comment_post_ID ) ) . 
								'</a>'.
							'</li>' .
							'<li class="responsive-tabs-front-page-comment-date-time">' . 
								date_i18n( get_option( 'date_format' ), strtotime( $comment->comment_date ) ) . 
							'</li>' .
						'</ul>' .
						'<div class = "responsive-tabs-front-page-comment-excerpt">' . 
							wp_kses_data( $this->get_long_comment_excerpt( $comment->comment_ID ) ) . '<br />' .
							'<a href="'. esc_url( get_comment_link( $comment->comment_ID ) ) . '">' . 
								__( 'View Comment in Context &raquo;', 'responsive-tabs' ) . 
							'</a>' .
						'</div>' .
					'</li>';
	    		}
			}
 		}
		$output .= '</ul>';  // .responsive-tabs-front-page-comment-list
		$output .= $after_widget;

		echo $output;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']  = esc_attr( $new_instance['title'] );
		$instance['number'] = absint( $new_instance['number'] );
		$instance['exclude_editorial_comments'] = absint( $new_instance['exclude_editorial_comments'] );		
		return $instance; 
	}

	function form( $instance ) {
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 50;
		$exclude_editorial_comments = ( isset( $instance['exclude_editorial_comments'] ) ) ? $instance['exclude_editorial_comments'] : false;
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show:', 'responsive-tabs' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	
	   <p><label for="<?php echo $this->get_field_id( 'exclude_editorial_comments' ); ?>"><?php _e( 'Exclude admin/editorial comments (will not be excluded from count): ', 'responsive-tabs' ); ?></label><?php
	   echo  '<input type="checkbox" id="'. $this->get_field_id('exclude_editorial_comments')  .'" name="'. $this->get_field_name('exclude_editorial_comments')  .'" value="1" ' . checked( '1',  $exclude_editorial_comments  , false ) .'/></p>';
	}

	/*
	* long comment excerpt method
	* derived from http://developer.wordpress.org/reference/functions/get_comment_excerpt/
	*/
	function get_long_comment_excerpt( $comment_ID = 0 ) {
	    $comment = get_comment( $comment_ID );
	    $comment_text = strip_tags( $comment->comment_content );
	    $excerpt_array = explode( ' ', $comment_text );
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
	
	    return apply_filters( 'get_comment_excerpt', $excerpt );
	}
}

/*
* Widget to ease population of front page widget area -- creates essentially text widgets with links and images based on post numbers selected
*
*/

class Front_Page_Post_Summary extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_post_summary', // Base ID
			__( 'Front Page Post Summary', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Post links, excerpt or content for front page', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
 		extract( $args, EXTR_SKIP );
 		
 		$output = '<!-- responsive-tabs Front_Page_Post_Summary widget, includes/responsive-tabs-widgets.php -->';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
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
		
		$instance['title'] 								= strip_tags( $new_instance['title'] ); // no tags in title
		$instance['post_list'] 							= responsive_tabs_clean_post_list( $new_instance['post_list'] );
		$instance['single_display_mode'] 			= strip_tags( $new_instance['single_display_mode'] );
		$instance['responsive_tabs_widget_width'] = strip_tags( $new_instance['responsive_tabs_widget_width'] );
		$instance['free_form_text'] 					= wp_kses_post( $new_instance['free_form_text'] );
		
		return $instance;
	}


	function form( $instance ) {
		
		$title  								= isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : '';
		$post_list 							= isset( $instance['post_list'] ) ? strip_tags( $instance['post_list'] ) : '';
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
* Front Page Text Widget
*/

class Front_Page_Text_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_text_widget', // Base ID
			__( 'Front Page Text Widget', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Variable width text widget to populate front page', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
 		extract( $args, EXTR_SKIP );
 		
 		$output = '<!-- responsive-tabs Front_Page_Text_Widget, includes/responsive-tabs-widgets.php -->';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$output .=  $before_widget;										// is blank in home widget areas
		
		$responsive_tabs_widget_width = isset( $instance['responsive_tabs_widget_width'] ) ? $instance['responsive_tabs_widget_width'] : 'pebble';			

		/* set up for variable widget widget */
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
			$output .=  apply_filters( 'the_content', $free_form_text );		
		} 	

		$output .= '</div>'; // close textwidget or home_bulk_widget
		 		
		$output .= $after_widget ;									// is blank in home widget areas

		echo $output;
	}

	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['title'] 								= strip_tags( $new_instance['title'] ); // no tags in title
		$instance['responsive_tabs_widget_width'] = strip_tags( $new_instance['responsive_tabs_widget_width'] );
		$instance['free_form_text'] 					= wp_kses_post( $new_instance['free_form_text'] );
		return $instance;
	}


	function form( $instance ) {
		
		$title  								= isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : '';
		$responsive_tabs_widget_width = isset( $instance['responsive_tabs_widget_width'] ) ? strip_tags( $instance ['responsive_tabs_widget_width'] ) : 'pebble';
		$free_form_text 					= isset( $instance['free_form_text'] ) ? wp_kses_post( $instance ['free_form_text'] ) : '';		
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
* Front Archive Widget
*/

class Front_Page_Archives extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_front_page_archives', // Base ID
			__( 'Front Page Archives', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Wide format archive widget for front page use', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
 		extract( $args, EXTR_SKIP );
 		
 		$output = '<!-- responsive-tabs Front_Page_Archives, includes/responsive-tabs-widgets.php -->';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$output .=  $before_widget  ;										// is blank in home widget areas
		
		if ( $title ) {
			$output .= $before_title . $title . $after_title; 		// <h2 class = 'widgettitle'> . . . </h2>
		} 		

		/* get counts */		
		global $wpdb;
		$month_counts = $wpdb->get_results( 
			'SELECT YEAR(post_date) AS YEAR, MONTH(post_date) AS MONTH, count(ID) AS POST_COUNT from wp_posts 
			WHERE post_status = "publish" AND post_type = "post" 
			GROUP BY YEAR(post_date), MONTH(post_date)' 
			, OBJECT );
		
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
			     		'<li class="rtfpa-year">' . __( 'Posts by month', 'responsive-tabs' ) . '</li>'; 
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
		
		$instance = $old_instance;
		
		$instance['title'] 								= strip_tags( $new_instance['title'] ); // no tags in title
		return $instance;
	}


	function form( $instance ) {
		
		$title  								= isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : '';
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (usually unnecessary in front page tab):', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	 	<?php
	} 
}

/*
* Front Page Latest Posts Widget
*/

class Front_Page_Latest_Posts extends WP_Widget {

	function __construct() {
		parent::__construct(
			'responsive_tabs_latest_posts', // Base ID
			__( 'Front Page Latest Posts', 'responsive-tabs' ), // Name
			array( 'description' => __( 'Wide format latest posts widget for front page use', 'responsive-tabs' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		
 		extract( $args, EXTR_SKIP );
 		
 		echo '<!-- responsive-tabs Front_Page_Latest_Posts, includes/responsive-tabs-widgets.php -->';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		echo $before_widget  ;										// is blank in home widget areas
		
		if ( $title ) {
			echo $before_title . $title . $after_title; 		// <h2 class = 'widgettitle'> . . . </h2>
		} 		

		/* get counts */		
		get_template_part('post', 'list');
		
		echo $after_widget ;									// is blank in home widget areas
	}

	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['title'] 								= strip_tags( $new_instance['title'] ); // no tags in title
		return $instance;
	}


	function form( $instance ) {
		
		$title  								= isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : '';
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (usually unnecessary in front page tab):', 'responsive-tabs' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	 	<?php
	} 
}




<?php
/*
*	File: responsive-tabs-widgets.php
*	Description: Adds wide-format widgets for use in tabs on front page
*	-- Front Page Category List
*  -- Front Page Post Summary
*  -- Front Page Text Widget
*  -- Front Page Archives
*  -- Front Page Links List
*
*	@package responsive-tabs  
*/




/*
* Register Widgets
*/
add_action( 'widgets_init', 'register_responsive_tabs_widgets' );

function register_responsive_tabs_widgets() {
	register_widget ( 'Front_Page_Post_Summary' );
	register_widget ( 'Front_Page_Text_Widget' );
	register_widget ( 'Front_Page_Archives' );
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
			array( 'description' => __( 'Variable width widget for tiling of post links, excerpt and content in front page tabs.', 'responsive-tabs' ), ) // Args
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
			array( 'description' => __( 'Variable width text widget for tiling of arbitrary content in front page tabs.', 'responsive-tabs' ), ) // Args
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


<?php
/*
* File: front-page.php
*
* Description: this template will be invoked as the front page if Admin>Settings>Reading>Front page displays is set to "Your Latest Posts" 
* 
* @package responsive-tabs 
* 
*
*/ 



// support settings>reading>front page displays -- see http://codex.wordpress.org/Creating_a_Static_Front_Page#Configuration_of_front-page.php
if ( 'posts' != get_option( 'show_on_front' ) ) { // use page template

    get_template_part ( 'page' );

} else { // use this template

	get_header();

	// do post list or comments list?
	$show_comments = isset( $_GET[ 'show_comments' ] )  ? $_GET[ 'show_comments' ] : 'no';
	$comment_page  = isset( $_GET[ 'comment_widget_page' ] )  ? $_GET[ 'comment_widget_page' ] : 0;
	
	if ( 'yes' === $show_comments ) {
				$scroll_marker = ' id="responsive-tabs-ajax-insert" ';	

		echo '<ul class = "responsive-tabs-front-page-comment-list"  id="responsive-tabs-ajax-insert" >';
	 	echo'<li class="pl-odd">' .
				'<ul class="responsive-tabs-front-page-comment-list-headers">' .
					'<li class="responsive-tabs-front-page-comment-author">' . __( 'Commenter', 'responsive-tabs' ) . '</li>'. 
					'<li class="responsive-tabs-front-page-comment-post">' . __( 'Commenting on', 'responsive-tabs' ). '</li>' .
					'<li class="responsive-tabs-front-page-comment-date-time">' . __( 'Date, time', 'responsive-tabs' ) . '</li>' .
				'</ul>' .
			'</li>';	
		echo Responsive_Tabs_Ajax_Handler::latest_comments ( $comment_page ); 
	
	} else {


		// set up parameters to be passed to ajax call as hidden value if not infinite sroll not disabled ( done in post-list.php)
		global $responsive_tabs_infinite_scroll_ajax_parms;
		$widget_parms = new Widget_Ajax_Parms ( 
			'non_widget_query', 			// widget_type
			'dummy_not_value_value',	// $include_string -- in this case, sending dummy string -- no string needed
			'', 								// $exclude_string,
			2, 								// page 2 is second page; pagination is incremented after retrieval;
			'dummy_not_valid_value'		// $query_type -- in this case, sending dummy type -- will be ignored by wp_query in ajax handler
		);
		$responsive_tabs_infinite_scroll_ajax_parms = json_encode( $widget_parms );	

		get_header();

		if ( isset ( $query_vars['tax_query'] ) ) {
			echo '<h3>' . __( 'Warning: The Responsive Tabs theme does not support custom taxonomy queries.		
			You can code a custom template including a passed tax_query array as the $include_string in $widget_parms 
				( see category.php as a model ).', 'responsive-tabs' ) 
				. '</h3>';	
		}

		?><!-- category-2019 front-page.php -->



		<div id = "content-header"> <?php
			if( is_active_sidebar( 'home_widget_0' ) ) { 
				// display sidebar
				dynamic_sidebar( 'home_widget_0');
				echo '<div class="horbar-clear-fix"></div>'; 
			} 
			?>

			<div id = "post-list-wrapper">

				<?php get_template_part( 'post', 'list' ); ?>
	
			</div> <!-- post-list-wrapper-->
	
			 <!-- empty bar to clear formatting -->
			<div class="horbar-clear-fix"></div>		
		
		</div><!-- close content header --><?php
	}
	get_footer();
} // close use this template condition




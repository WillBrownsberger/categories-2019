<?php
/*
 * File: post-list.php
 * Description: template part used to display list of posts in full width mode  
 *
 * @package responsive-tabs
 *
 */


global $responsive_tabs_infinite_scroll_ajax_parms;

if ( have_posts() ) { 

	echo '<!-- responsive-tabs post-list.php -->';
	
	$scroll_marker = ' id="responsive-tabs-ajax-insert" ';	

	// open main post list with marker as appropriate
	echo '<ul class="post-list" '. $scroll_marker .' >';
	
		get_template_part('post', 'listheader' );	
	
		/* post list */
		global $responsive_tabs_post_list_line_count;  
		$responsive_tabs_post_list_line_count = 1; 
		
		while (have_posts()) : the_post();	
	
			$responsive_tabs_post_list_line_count = $responsive_tabs_post_list_line_count + 1;
	
			get_template_part( 'post', 'listitems' );
			
		endwhile;  ?>

	</ul> <!-- post-list -->
	<?php
	echo '<span id = "responsive-tabs-post-list-ajax-loader">' .
		'<img src="' . get_stylesheet_directory_uri() . '/images/ajax-loader.gif' .
	'"></span>'; 
	echo '<div class="responsive_tabs_infinite_scroll_parms" id="responsive_tabs_infinite_scroll_parms">' . $responsive_tabs_infinite_scroll_ajax_parms . '</div>';	
}	else {   // closes if found condition 
	?>	<div id="not-found">
		<h3><?php _e( 'No posts found matching your search.', 'responsive-tabs' ) ?></h3>
	</div><?php
}

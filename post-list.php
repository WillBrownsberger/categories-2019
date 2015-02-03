<?php
/*
 * File: post-list.php
 * Description: template part used to display list of posts in full width mode  
 *
 * @package responsive-tabs
 *
 */



if ( have_posts() ) { 

	echo '<!-- responsive-tabs post-list.php -->'; 
	echo '<ul class="post-list">';
	
		get_template_part('post', 'listheader' );	
	
	/* post list */
	global $responsive_tabs_post_list_line_count;  
	$responsive_tabs_post_list_line_count = 1; 
	
	while (have_posts()) : the_post();	

		$responsive_tabs_post_list_line_count = $responsive_tabs_post_list_line_count + 1;

		get_template_part( 'post', 'listitems' );
		
	endwhile;  ?>

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
}	else {   // closes if found condition 
	?>	<div id="not-found">
		<h3><?php _e( 'No posts found matching your search.', 'responsive-tabs' ) ?></h3>
	</div><?php
}

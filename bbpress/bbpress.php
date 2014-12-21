<?php
/**
* File: bbpress.php
*
* Description: used for all bbPress multi-item listings in responsive-tabs theme  
* 
* note: bbpress template hierarchy per http://bbpress.org/forums/topic/understanding-templating/
*  1. bbpress.php (this file -- catches bbpress lists for wide/responsive display)
*  2. forum.php (not-present in this theme) 
*  3. page.php (bypassed in the hierarchy by the first if condition below -- in this theme, serves only non-bbpress content)
*  4. single.php (present in responsive-tabs theme to serve all bbpress requests not caught by this file)
*  5. index.php
*
* @package responsive-tabs 
*
*
*/



if ( ! ( // if NOT a bbpress list . . .
		bbp_is_forum_archive() || 
		bbp_is_topic_archive() ||
		bbp_is_topic_tag() || 
		bbp_is_single_forum() || 
		bbp_is_topics_created() || 
		bbp_is_replies_created()||
		bbp_is_single_user_edit() ||  	// not a listing per se, but topics and replies created are on menu
		bbp_is_single_user() ||   			// not a listing per se, but topics and replies created are on menu
		bbp_is_favorites() || 
		bbp_is_subscriptions() || 
		bbp_is_search_results() ) 
 	) 	 {   
	get_template_part('single');  //  . . . jump down bbpress template hierarchy to single.php
} else { // for bbpress lists use this template

	get_header();
	
	echo '<!-- responsive-tabs/bbpress/bbpress.php -->';

	?> <div id="bbpress-list-wrapper"> <?php

	get_template_part( 'breadcrumbs' );
	
	// single simple post handler for multiple and single;
	if ( have_posts() ) : while (have_posts()) : the_post();	
	
			the_title( '<h1>', '</h1>' );
		
		// display content for bbpress archive
			the_content();
	
		// close the main loop		
		endwhile; 
			
		// handle not found conditions		
		else:   
				?>
		<h1><?php _e( 'No bbPress content found matching your search.', 'responsive-tabs' ) ?> <h1>
		<?php
		
	endif;
	
	?> </div><?php // note start immediately to create space in inline-block series
	
	// no sidebar for archives -- use full width
	
	?><?php  
	
	 // empty bar to clear formatting -->
	?><div class="horbar-clear-fix"></div><?php 
	 
	get_footer();
	
}
<?php
/*
 * File: comments-ajax.php
 * Description: echo comments list for requested comment page  
 * 	Invoked by comments_template call in ajax handler
 *
 */
 
echo '<!-- responsive-tabs comments-ajax.php -->';
// if no comments, do nothing
if ( ! empty ( $wp_query->comments ) ) { 
	// no need to do pagination because handled by WP_Comment_Query since 4.4
	wp_list_comments();
	// if am within the current if condition ( had a page of comments ) and reached this point without error, then AJAX call is OK response
	echo '<span id="OK-responsive-tabs-AJAX-response"></span>';			
		
} // close test whether any comments




<?php
/*
 * File: comments-ajax.php
 * Description: echo comments list for requested comment page  
 * 	Invoked by comments_template call in ajax handler
 *
 */
 
echo '<!-- responsive-tabs comments-ajax.php -->';

/* 
* comment page starts at 0 and is incremented in js by each ajax call; 
* global is set to the comment page from the ajax call in Responsive_Tabs_Ajax_Handler::comment_query
*/ 
global $responsive_tabs_ajax_comment_page; 

// if no comments, do nothing
if ( ! empty ( $wp_query->comments ) ) {

	// doing page count computation directly via Walker_Comment 
	// avoids vulnerability to user unsetting page_comments ( get_comment_page_count() fixes page_count to 1 in this case )
	if ( 1 == get_option('thread_comments') ) {
		$walker = new Walker_Comment;
		// standardizing retrieval count at 10 comments for ajax calls -- let comments_per_page option setting drive only the non-infinite-scroll processing
		// use of even number assures color alternation consistency
		$responsive_tabs_ajax_comment_pages_count = ceil( $walker->get_number_of_root_elements( $wp_query->comments ) / 10 );
	} else {
		$responsive_tabs_ajax_comment_pages_count = ceil( count( $wp_query->comments ) / 10 );
	}

	// comment page will be retrieved in range from 1 to pages count or reverse, but will not be retrieved if <= zero 
	// $responsive_tabs_ajax_comment_page starts at 0 and is incremented by each ajax call
	if ( 	$responsive_tabs_ajax_comment_page < $responsive_tabs_ajax_comment_pages_count )  {  
	
		$comment_page_to_get = ( 'desc' == get_option('comment_order') ) ? 
			( $responsive_tabs_ajax_comment_pages_count - $responsive_tabs_ajax_comment_page ) : 
			$responsive_tabs_ajax_comment_page + 1 ;		
		$list_args = array (
			'page'              => $comment_page_to_get, 	
			'per_page'          => 10,	// standardized per_page   
		);
		
		wp_list_comments( $list_args );
		
		// if am within the current if condition ( had a page of comments ) and reached this point without error, then AJAX call is OK response
		echo '<span id="OK-responsive-tabs-AJAX-response"></span>';			
		
	}
	
} // close test whether any comments




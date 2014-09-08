<?php
/*
 * File: post-list.php
 * Description: template part used to display list of posts in full width mode -- other templates handle header and page title 
 *
 * @package responsive-tabs
 *
 */

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." );

if ( have_posts() ) { 

	/* post list headers -- echoing to avoid white spaces in inline-block styling*/ 

	echo '<!-- responsive-tabs post-list.php -->' . 
	'<ul class="post-list">' . 
	  '<li class = "pl-odd">' .
	  		'<ul class = "pl-headers">' .
	  			'<li class="pl-post-title">' . __( 'Topic (comment count)', 'responsive-tabs' ) . '</li>' .
	  			'<li class = "pl-post-author">' . __( 'Started by', 'responsive-tabs' ) . '</li>' .
	  			'<li class = "pl-post-date-time">' . __( 'Date', 'responsive-tabs') .'</li>' .
	  		'</ul>' .
	  	'</li>'; 

	/* post list */ 
	$count = 1; 
	while (have_posts()) : the_post();	
		
		$count = $count+1;
		$row_class = ( 0 == $count % 2 ) ? "pl-even" : "pl-odd";
		if ( is_sticky() && is_home() ) {
			$row_class .= " sticky";		
		}
		$post_type = get_post_type();	
      
      			
		if( "post" == $post_type || "page" == $post_type ) { 
			$link 	= get_permalink();
  			$title 	= get_the_title();
  			$excerpt	= get_the_excerpt(); 
		} elseif ( 'twcc_clipping' == $post_type ) { /* supports inclusion of twcc clippings plugin content in consolidated search */ 
			$link 	= get_post_meta( get_the_id(), '_clipping_link', true );
			$title 	= __( 'News Item: ', 'responsive-tabs' ) . get_the_title(); 
			$excerpt	= get_the_content();
		}
					
		$guest_author = get_post_meta( get_the_ID(), 'twcc_post_guest_author', true ); /* supports inclusion of twcc front-end-post-no-spam plugin author information */
		if ( '' === $guest_author )	{
			$author_entry = 	'<li class="pl-post-author"><a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) )  . '" title = "' . __('View all posts by', 'responsive-tabs') . get_the_author_meta( 'display_name' ) .'">' . get_the_author_meta('display_name') . '</a></li>';
		} else {
			$author_entry = '<li class="pl-post-author">'. esc_html( $guest_author ) . '</li>'; 
		}
		/* output list item -- echoing to show structure and avoid white spaces in inline-block styling */
		echo '<li class ="' . $row_class .'">' .
			'<ul class="pl-post-item">' . 			
				'<li class="pl-post-title">' .
					'<a href="'  .  $link  . '" rel="bookmark" ' . 
						'title="'  .  __( 'View item', 'responsive-tabs' )  . '"> '  .  
						$title . ' ('. get_comments_number()  . ')' .
					'</a>' . 
				'</li>' .
				$author_entry  . 
				'<li class="pl-post-date-time">' .
					'<a href="'  .  get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) ) . '" ' . 
						'title = "'  .  __( 'View all posts from ', 'responsive-tabs' ) . get_post_time( 'F', false, null, true ) . ' ' . get_post_time( 'Y', false, null, true )  . '"> ' .
						 get_post_time('F', false, null, true )  . 
					'</a> ' .
					'<a href="'  .  get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ) . '" ' . 
						'title = "'  .  __( 'View posts from same day', 'responsive-tabs')  . '">' .
						get_post_time('jS', false, null, true )  . 
					'</a>, ' . 
		      	'<a href="'  .  get_year_link( get_post_time( 'Y' ) )  . '" ' . 
		      		'title = "'  .  __( 'View all posts from ', 'responsive-tabs' ) . get_post_time( 'Y' )   . '">' .
		      		get_post_time( 'Y' )  . 
		      	'</a>' .
		      '</li>' .
	      '</ul>' .
			'<div class="pl-post-excerpt">' .
				$excerpt . '<br />' . 
				'<a href="' . $link .'" rel="bookmark" ' . 
					'title="'. __( 'Read the rest of this post', 'responsive_tabs' ) . '">' .
					__( 'Read More', 'responsive-tabs' ) . '&raquo;' . 
				'</a></div>' .         
	 '</li>
	 ';
	
	endwhile; ?> 
	
	</ul> <!-- post-list -->

	<div id = "next-previous-links">
		<div id="previous-posts-link"><?php
			previous_posts_link('<strong>&laquo; Newer Entries </strong>');
		?> </div> 
		<div id="next-posts-link">  <?php
			next_posts_link('<strong>Older Entries &raquo; </strong>');
		?> </div>
	</div> <?php
}	else {   
	?>	<div id="not-found">
		<h3>No posts found matching your search.</h3>
	</div><?php
}

<?php
/*
 * File: post-listitems.php
 * Description: template part used to display the actual post items in a list of posts in full width mode  
 *
 * @package responsive-tabs
 *
 */



global $responsive_tabs_post_list_line_count;  

$row_class = ( 0 == $responsive_tabs_post_list_line_count % 2 ) ? "pl-even" : "pl-odd";
$post_format = get_post_format();	

if ( 'link' == $post_format ) {
	$link 	= esc_url( responsive_tabs_url_grabber() );
	$title 	= __( 'Link: ', 'responsive-tabs' ) . get_the_title(); 
	$excerpt	= get_the_content();
	} else { 
	$link 	= get_permalink();
	$title 	= get_the_title();
	$excerpt	= get_the_excerpt();
} 

$comments_count = get_comments_number();
$comments_phrase = '';
if ( 0 < $comments_count ) {
	$comments_phrase = ' (' .  $comments_count . ')';
}
/* output list item -- echoing to show structure and avoid white spaces in inline-block styling */
echo '<li class="pl-post-item-li ' . implode ( get_post_class( $row_class),  ' ' ) . '">' .

	// thumb nail if available
	( has_post_thumbnail() ? ( 
		'<div class = "pl-featured-image"><a href="' . $link . '">' . get_the_post_thumbnail( null, 'post-list-thumb') . '</a></div>' ) : '' 
	) .	
	// post title
	'<span class="pl-post-title">' .
		'<a href="'  .  $link  . '" rel="bookmark" ' . 
			'title="'  .  __( 'View item', 'responsive-tabs' )  . '"> '  .  
			$title . $comments_phrase .
		'</a>' . 
	'</span>' . 
	// excerpt
	'<br/><span class="pl-post-excerpt">' .
		'<a href="'  .  $link  . '" rel="bookmark" ' . 
			'title="'  .  __( 'View item', 'responsive-tabs' )  . '"> '  .  
			$excerpt .
		'</a>' . 
	'</span>' .         
 '</li>';
	
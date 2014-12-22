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
	$read_more_pointer = ( 
			comments_open() ? 
				( '<a href="' . get_the_permalink() . '" rel="bookmark" ' . 
						'title="'. __( 'View the link with comments on this site ', 'responsive_tabs' ) . '">' . 
						__( 'Comment Here', 'responsive-tabs' ) .	'</a>'. __( ' or ', 'responsive-tabs' ) ) 
				: '' ) . 
			'<a href="' . esc_url( responsive_tabs_url_grabber() ) . '">' . __( 'Go to Link', 'responsive-tabs' ) . ' &raquo;</a>'; 
} else { 
	$link 	= get_permalink();
	$title 	= get_the_title();
	$excerpt	= get_the_excerpt();
	$read_more_pointer = '<a href="' . $link .'" rel="bookmark" ' . 
			'title="'. __( 'Read the rest of this post', 'responsive_tabs' ) . '">' . __( 'Read More', 'responsive-tabs' ) . ' &raquo; </a>'; 
} 
			
$guest_author = get_post_meta( get_the_ID(), 'twcc_post_guest_author', true ); /* supports inclusion of twcc front-end-post-no-spam plugin author information */
if ( '' === $guest_author )	{
	$author_entry = 	'<li class="pl-post-author"><a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) )  . '" title = "' . __('View all posts by', 'responsive-tabs') . get_the_author_meta( 'display_name' ) .'">' . get_the_author_meta('display_name') . '</a></li>';
} else {
	$author_entry = '<li class="pl-post-author">'. esc_html( $guest_author ) . '</li>'; 
}
/* output list item -- echoing to show structure and avoid white spaces in inline-block styling */
echo '<li ' ;
	post_class( $row_class ); 
	echo '>' .
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
		$read_more_pointer .  
		'</div>' .         
 '</li>';
	
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
	$author_entry = '';
	} else { 
	$link 	= get_permalink();
	$title 	= get_the_title();
	$excerpt	= get_the_excerpt();
	$author_entry = 	'<span class="pl-post-author">, <a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) )  . '" title = "' . __('View all posts by', 'responsive-tabs') . get_the_author_meta( 'display_name' ) .'">' . get_the_author_meta('display_name') . '</a></span>';
} 
			
$comments_count = get_comments_number();
$comments_phrase = '';
if ( 1 == $comments_count ) {
	$comments_phrase =  __( '1 Comment', 'responsive-tabs' );
} elseif ( 1 < $comments_count ) {
	$comments_phrase = $comments_count . ' ' . __( 'Comments', 'responsive-tabs' );

}
/* output list item -- echoing to show structure and avoid white spaces in inline-block styling */
echo '<li ' ;
	post_class( $row_class ); 
	echo '>' .
	'<div class="pl-post-item">' . 			
		'<span class="pl-post-title">' .
			'<a href="'  .  $link  . '" rel="bookmark" ' . 
				'title="'  .  __( 'View item', 'responsive-tabs' )  . '"> '  .  
				$title .
			'</a>' . 
		'</span>' .
		$author_entry  . 
		'<span class="pl-post-date-time">, ' .
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
      '</span>' .
      ( $comments_phrase ? ( ', <span class="pl-comment-count"><a href="'  .  $link  . '#comments" rel="bookmark" ' . 
				'title="'  .  __( 'View comments on item', 'responsive-tabs' )  . '"> '  .  
				$comments_phrase .
			'</a>
		</span>' ) : '' ) . 
    '</div>' .
	'<div class="pl-post-excerpt">' .
		$excerpt . '<br />' . 
		'</div>' .         
 '</li>';
	
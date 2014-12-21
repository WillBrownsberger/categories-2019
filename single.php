<?php
/*
 * File: single.php
 * Description: Display single post 
 * 
 * @package responsive-tabs
 *
 */



// note: no "not found" condition for single.php -- handled by 404.php
while ( have_posts() ) : the_post();	
	
	// set bbpress switch
	$bbpress_switch = 0;
	if( class_exists( 'bbpress' ) ) {
		$bbpress_switch = ( is_bbpress() ) ? 1 : 0; 
	}
	// get theme supported custom field identifying wide posts (supports tablepress plugin and any wide format)
	$post_width = get_post_meta( get_the_id(), '_twcc_post_width', true );
	// http://codex.wordpress.org/Template_Tags/the_content (override the more logic to display whole post/topic in this view)	
	global $more;
	$more = 1;  

	// get header depending on post width
	if( 'extra_wide' == $post_width ) {
		get_header( 'retina' );
	} else {
		get_header();
	}
		
	// content title
	?><!--single.php -->
	<div id="content-header">
		<?php get_template_part( 'breadcrumbs' ); ?> 
		<h1><?php 
			$comment_count = get_comments_number();
			if ( $comment_count > 0 ) {
				printf( '%1$s<span class="post-response-count"> (' . _n( 'One Response', '%2$d Responses', $comment_count, 'responsive-tabs' ) . ')</span>',
										get_the_title(), number_format_i18n( $comment_count ) );
			} else {
				the_title();
			}?>
		</h1>
	</div><?php
	
	// set up content wrapper based on post width
	if ( 'wide' == $post_width ) {
		echo '<div id="full-width-content-wrapper">';
	} elseif ( 'extra_wide' == $post_width ) {
		echo '<div id="retina-full-width-content-wrapper">';
	} else {
		echo '<div id="content-wrapper">';
	};
	echo '<!--division wraps the non-sidebar, non-footer content-->';
	
		// display meta information, comments and pagination if not in bbpress
		if ( ! $bbpress_switch ) { ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>   
				<div class = "post-info"> <?php 
					_e( 'By', 'responsive-tabs' ) ?> 
						<span class="post-author">
							<?php $guest_author = get_post_meta( get_the_ID(), 'twcc_post_guest_author', true );
								if ( '' === $guest_author ){ // supports twcc frontend-post-no-spam plugin
									the_author_posts_link();				
								} else {
									echo esc_html( $guest_author ); 
								}
						?></span>, <?php
					_e( 'on', 'responsive-tabs' ); 
		 				echo '<a href="'  .  get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) ) . '" ' . 
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
			      	'</a>'; ?> 	
					<span class= "post-cats">
						<?php _e( 'In', 'responsive-tabs' ) ?>: 
							<?php the_category(', '); ?>. 
							<?php the_tags( __( ' Tagged', 'responsive-tabs' ) . ': ', ', ','.'); ?>
							<?php	wp_link_pages( array(
								'before'      => '<div class="upper-page-links"><span class="page-links-title">' . __( 'Paged:', 'responsive-tabs' ) . '</span>',
								'after'       => '.</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
								) );	?>
					</span>

				</div><!-- post-info --> 
				
				<?php
				the_post_thumbnail('post-content-width');
				the_content();
				?><div class="horbar-clear-fix"></div><?php

				wp_link_pages( array(
					'before'      => '<div class="lower-page-links"><span class="page-links-title">' . __( 'Read more &raquo;', 'responsive-tabs' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					) );				

				edit_post_link( 'Edit Post #' . get_the_id(), '<br />', ''); 
			
			/* always do comments template -- it will check and show message if not open (contrast page.php) */		
			?> <div id="comments"> <?php		
				comments_template();
			?> </div> <?php
				
			?> <div id="previous-post-link"> <?php
				previous_post_link( '<strong>&laquo; %link </strong>', __( 'previous post', 'responsive-tabs' ) );  
			?> </div> <?php
	
			?> <div id="next-post-link">  <?php
				next_post_link( '<strong>%link &raquo; </strong>', __( 'next post', 'responsive-tabs' ) ); 
			?> </div> 
			
			<div class="horbar-clear-fix"></div>	

			</div><!-- post -->		<?php

		} else { // bbpress handles all its own meta information and replies listing 
			the_content(); 
		}
	?></div><!-- content-wrapper --><?php // note start immediately to create space in inline-block series
endwhile; //close the main loop (single entry)  

// show post side bar if not using a wide format

if( ! $bbpress_switch && is_active_sidebar( 'post_sidebar' ) && ( '' == $post_width || NULL == $post_width || 'normal' == $post_width ))
{	
	echo '<div id="right-sidebar-wrapper">';
		dynamic_sidebar( 'post_sidebar' ); 
		wp_meta();	// hook for bottom of sidebar content
	echo '</div>';
}
// show post side bar for all forms of bbpress
elseif ( $bbpress_switch && is_active_sidebar ( 'bbpress_sidebar' ) )
{	
	echo '<div id="right-sidebar-wrapper">';
		dynamic_sidebar( 'bbpress_sidebar' ); 
		wp_meta(); // hook for bottom of sidebar content	
	echo '</div>';
}

// empty bar to clear formatting -->
?><div class="horbar-clear-fix"></div><?php 
 
get_footer();
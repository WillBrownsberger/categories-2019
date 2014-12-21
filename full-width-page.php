<?php
/*
 * Template Name: Full Width Page
 * File: full-width-page.php
 * Description: Display single page in wide format (no sidebar, full 1280)
 *
 * @package responsive-tabs
 *
 *
 */



get_header();

while ( have_posts() ) : the_post(); // no not found condition -- goes to 404.php ?>	

	<!--responsive-tabs full-width-page.php -->
	
	<div id="content-header">

		<?php get_template_part( 'breadcrumbs' ); ?>
   	
		<?php the_title( '<h1 class="post-title">', ' </h1> '); ?>
		
		<h4>
			<?php /* list child pages if any below title */
			$page_child_query  = new WP_Query( 'post_type=page&orderby=title&order=asc&post_parent=' . $post->ID );
				if ( $page_child_query->have_posts() ) {
					$child_count = 0;
					while ( $page_child_query->have_posts() ) {
						$page_child_query->the_post();
						if ( $child_count > 0 ) {
							 	  		echo ', ';
							 	  } 
						echo '<a href="' . get_the_permalink() . 
							'" title = "' . sprintf( __( 'View child page %s', 'responsive-tabs' ) , get_the_title() ) . '">' . 
							get_the_title() . '</a>';
						$child_count = $child_count + 1;
					}
				} else {
					// no children found, do nothing
				}
				/* Restore original Post Data */
				wp_reset_postdata();
			?>	
		</h4>

	</div>
		
	<div id="full-width-content-wrapper">   

		<?php // http://codex.wordpress.org/Template_Tags/the_content (override the more logic to display whole post/topic in this view)
		global $more;
		$more = 1; 
		?>
		<div id = "wp-single-content">

			<?php	the_post_thumbnail('post-content-width'); ?>	
	
			<?php the_content(); ?>
			
			<div class="horbar-clear-fix"></div>			

			<?php	wp_link_pages( array(
					'before'      => '<div class="lower-page-links"><span class="page-links-title">' . __( 'Read more &raquo;', 'responsive-tabs' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					) );				
			?>
					
			<?php edit_post_link( __( 'Edit Page #', 'responsive-tabs') . get_the_id() , '<p>', '</p>' ); ?>
		</div>
		
		<?php if ( comments_open() || get_comments_number() ) {			
			comments_template();
		}?>
		
	</div><?php 
			
endwhile; // close the main loop 

// no side bar
// empty bar to clear formatting -->
?><div class="horbar-clear-fix"></div><?php 
 
get_footer();
<?php
/*
 * Template Name: Full Width Page
 * File: full-width-page.php
 * Description: Display single page in wide format (no sidebar, full 1280)
 *
 * @package responsive-tabs
 *
 */

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

get_header();

while ( have_posts() ) : the_post(); // no not found condition -- goes to 404.php ?>	

	<!--responsive-tabs full-width-page.php -->
	
	<?php if ( ! is_front_page() ) { ?> // suppress breadcrumbs and title if user selects page as front page in admin>settings>reading 		
	
		<div id="content-header">
	
			<?php get_template_part( 'breadcrumbs' ); ?>
	   	
			<?php the_title( '<h1 class="post-title">', ' </h1> '); ?>
		
		</div>
		
	<?php } ?>	
		
	<div id="full-width-content-wrapper">   

		<?php // http://codex.wordpress.org/Template_Tags/the_content (override the more logic to display whole post/topic in this view)
		global $more;
		$more = 1; 
		?>
		<div id = "wp-single-content">
			<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit Page', 'responsive-tabs' ), '<p>', '</p>' ); ?> 
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
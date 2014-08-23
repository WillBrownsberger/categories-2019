<?php
/*
 * File: page.php
 * Description: Display single page 
 *
 * @package responsive-tabs
 *
 */

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." );

get_header();

while ( have_posts() ) : the_post(); // no not found condition -- goes to 404.php ?>	

	<!-- responsive-tabs 	page.php -->

	<?php if ( ! is_front_page() ) {  // suppress breadcrumbs and title if user selects page as front page in admin>settings>reading ?> 	
	
		<div id="content-header">
	
			<?php get_template_part( 'breadcrumbs' ); ?>
	   	
			<?php the_title( '<h1 class="post-title">', ' </h1> '); ?>
		
		</div>
	
	<?php } ?>
		
	<div id="content-wrapper">   

		<?php // http://codex.wordpress.org/Template_Tags/the_content (override the more logic to display whole post/topic in this view)
		global $more;
		$more = 1; 
		?>
		<div id = "wp-single-content">
			<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit Page', 'responsive-tabs') , '<p>', '</p>' ); ?> 
		</div>
		
		<?php if ( comments_open() || get_comments_number() ) {			
			comments_template();
		}?>
		
	</div><?php // close content-wrapper and start php immediately so as to not create space in inline-block series
			
endwhile; // close the main loop 



// show page sidebar
if( is_active_sidebar( 'page_sidebar' ) ) {
	echo '<div id="right-sidebar-wrapper">';
		dynamic_sidebar( 'page_sidebar' );
		wp_meta();	
	echo '</div>';
}
 // empty bar to clear formatting -->
?><div class="horbar-clear-fix"></div><?php 
 
get_footer();
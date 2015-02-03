<?php
/*
 * File: 404.php
 * Description: Displayed when material not found
 *
 * @package responsive-tabs
 *
 */

get_header();

?>

<!--responsive-tabs/404.php -->

<div id="full-width-content-wrapper" > 
	
	<h1><?php _e( 'Sorry! We cannot find the content you requested.', 'responsive-tabs' )?></h1>
	
	<h3><?php printf( __( 'If you wish, you can return to the <a href="%1$s">front page of %2$s</a>.', 'responsive-tabs' ), home_url( '/' ), get_bloginfo( 'name') ); ?></h3>
					
</div>

<?php get_footer();
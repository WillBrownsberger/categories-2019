<?php
/*
 * File: index.php
 * Description: catchall template -- should never actually be accessed except for new taxonomies without appropriate support or if admin selects
 *              admin>settings>reading static page -- in that case, this template will display latest posts as the posts page
 *					 New taxonomies required their own template if infinite scroll is enabled.
 *
 * @package responsive-tabs
 *
 *
 */


// set up parameters to be passed to ajax call as hidden value if not infinite sroll not disabled ( done in post-list.php)
global $responsive_tabs_infinite_scroll_ajax_parms;
$widget_parms = new Widget_Ajax_Parms ( 
	'non_widget_query', 			// widget_type
	'dummy_not_value_value',	// $include_string -- in this case, sending dummy string -- no string needed
	'', 								// $exclude_string,
	2, 								// page 2 is second page; pagination is incremented after retrieval;
	'dummy_not_valid_value'		// $query_type -- in this case, sending dummy type -- will be ignored by wp_query in ajax handler
);
$responsive_tabs_infinite_scroll_ajax_parms = json_encode( $widget_parms );	



get_header();

if ( isset ( $query_vars['tax_query'] ) ) {
	echo '<h3>' . __( 'Warning: The Responsive Tabs theme does not support custom taxonomy queries.		
	You can code a custom template including a passed tax_query array as the $include_string in $widget_parms 
		( see category.php as a model ).', 'responsive-tabs' ) 
		. '</h3>';	
}


?><!-- responsive-tabs index.php -->

<div id = "content-header">

	<?php get_template_part('breadcrumbs'); ?> 

 	<h1><?php _e( 'Latest Posts', 'responsive-tabs' ); ?> </h1>

</div> <!-- content-header -->   

<div id = "post-list-wrapper">

	<?php get_template_part( 'post', 'list' ); ?>
	
</div> <!-- post-list-wrapper-->
	
 <!-- empty bar to clear formatting -->
<div class="horbar-clear-fix"></div>

<?php get_footer();
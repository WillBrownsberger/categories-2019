<?php
/*
 * File: index.php
 *
 */
// do header
get_header();

// abort if tax query
if ( isset ( $query_vars['tax_query'] ) ) {
	echo '<h3>' . __( 'Warning: The Responsive Tabs theme does not support custom taxonomy queries.		
	You can code a custom template including a passed tax_query array as the $include_string in $widget_parms 
		( see category.php as a model ).', 'responsive-tabs' ) 
		. '</h3>';	
	exit;
}

// produce appropriate header if showing index (or comment list) on back page
$show_comments = isset( $_GET[ 'show_comments' ] ) ? $_GET[ 'show_comments' ] : 'no';
// showing title for back page post list -- use comments
if ( is_home() && ! is_front_page() ) : 
	$title = single_post_title( '', false ); ?>
	<h1 class="page-title"><?php echo $title ?></h1>
<?php endif; ?>

<?php 
//do infinite scroll index

// set up parameters to be passed to ajax call as hidden value if not infinite sroll not disabled ( done in post-list.php)
global $responsive_tabs_infinite_scroll_ajax_parms;
$widget_parms = new Widget_Ajax_Parms ( 
	'non_widget_query', 		// widget_type
	'dummy_not_value_value',	// $include_string -- in this case, sending dummy string -- no string needed
	2, 							// page 2 is second page; pagination is incremented after retrieval;
	'dummy_not_valid_value'		// $query_type -- in this case, sending dummy type -- will be ignored by wp_query in ajax handler
);
$responsive_tabs_infinite_scroll_ajax_parms = json_encode( $widget_parms );	


?><!-- category-2019 index.php -->

<div id = "post-list-wrapper">

	<?php get_template_part( 'post', 'list' ); ?>

</div> <!-- post-list-wrapper-->

 <!-- empty bar to clear formatting -->
<div class="horbar-clear-fix"></div>

<?php 

get_footer();
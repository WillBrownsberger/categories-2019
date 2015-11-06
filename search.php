<?php
/*
 * File: search.php
 * Description: template used to display theme category search results
 *
 * @package responsive-tabs
 *
 *
 */



get_header();

/* set up title for author search */
global $wp_query;
$total_results = $wp_query->found_posts;
$query_vars = $wp_query->query_vars;

// set up parameters to be passed to ajax call as hidden value if not infinite sroll not disabled ( done in post-list.php)
global $responsive_tabs_infinite_scroll_ajax_parms;
$widget_parms = new Widget_Ajax_Parms ( 
	'non_widget_query', 			// widget_type
	$query_vars['s'],				// $include_string,
	'', 								// $exclude_string,
	2, 								// page 2 is second page; pagination is incremented after retrieval;
	's'								// $query_type
);
$responsive_tabs_infinite_scroll_ajax_parms = json_encode( $widget_parms );	

?>

<!-- responsive-tabs search.php -->
<div id = "content-header">
	
	<?php get_template_part( 'breadcrumbs' ); ?> 
	
 	<h1> <?php printf( __( 'Search for "%1$s" found %2$s posts.', 'responsive-tabs' ), esc_html( $query_vars['s'] ), $total_results ) ?></h1>
 	
	<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
		<input type="search" class="search-field" placeholder="<?php _e( 'Search', 'responsive-tabs') ?> &hellip;" value="<?php echo esc_html( $query_vars['s'] ); ?>" name="s" />
	</form>

</div> <!-- content-header -->   

<div id = "post-list-wrapper">

	<?php get_template_part( 'post', 'list' ); ?>
	
</div> <!-- post-list-wrapper-->
	
 <!-- empty bar to clear formatting -->
<div class="horbar-clear-fix"></div>

<?php get_footer();
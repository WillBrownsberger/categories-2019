<?php
/*
 * File: date.php
 * Description: template used to display theme category search results
 *
 * @package responsive-tabs
 *
 *
 */



get_header();

/* set up title for date search -- note maintaining US formatted dates, but localizing month; consistent with date link formatting */
$m = get_query_var( 'm' );
$year = get_query_var( 'year' );
$monthnum = get_query_var( 'monthnum' );
$day = get_query_var( 'day') ;
$display_month = $monthnum ? $wp_locale->get_month( $monthnum ) . ' ' : ''; 
$display_day = $day ? $day . ', ' : '';
$display_date = $display_month . $display_day . $year;

// parameters to be passed to ajax call as hidden value 
global $responsive_tabs_infinite_scroll_ajax_parms;

// put date query parameters into single array
$date_query = array();
if ( $year > 0 ) {
	$date_query['year'] = $year;
}
if ( $monthnum > 0 ) {
	$date_query['month'] = $monthnum;
}
if ( $day > 0 ) {
	$date_query['day'] = $day;
}
$widget_parms = new Widget_Ajax_Parms ( 
	'non_widget_query', 	// widget_type
	array ( 					// $include_string,
		$date_query
	),
	'', 						// $exclude_string,
	2, 						// page 2 is second page; pagination is incremented after retrieval;
	'date_query' 						// $query_type
);
$responsive_tabs_infinite_scroll_ajax_parms = json_encode( $widget_parms );	





?><!-- responsive-tabs date.php -->

<div id = "content-header">
	
	<?php get_template_part( 'breadcrumbs' ); ?> 

   <h1><?php echo __( 'Posts from ', 'responsive-tabs' ) . $display_date; ?> </h1>

	<?php $args = array(
		'type'            => 'monthly',
		'limit'           => '',
		'format'          => 'option', 
		'before'          => '',
		'after'           => '',
		'show_post_count' => 1,
		'echo'            => 1,
		'order'           => 'DESC'
	); ?>
	<select name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
	  	<option value=""><?php _e( 'Select Month', 'responsive-tabs' ); ?></option> 
		<?php wp_get_archives ( $args ); ?>
	</select>

</div> <!-- content-header -->   

<div id = "post-list-wrapper">

	<?php get_template_part( 'post', 'list' ); ?>
	
</div> <!-- post-list-wrapper-->
	
 <!-- empty bar to clear formatting -->
<div class="horbar-clear-fix"></div>

<?php get_footer();
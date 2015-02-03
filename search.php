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
?>

<!-- responsive-tabs search.php -->
<div id = "content-header">
	
	<?php get_template_part( 'breadcrumbs' ); ?> 
	
 	<h1> <?php printf( __( 'Search for "%1$s" found %2$s posts.' ), esc_html( $query_vars['s'] ), $total_results ) ?></h1>
 	
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
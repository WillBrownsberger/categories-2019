<?php
/*
 * File: tag.php
 * Description: template used to display theme category search results
 *
 * @package responsive-tabs
 *
 *
 */



get_header();

/* set up title for tag search */
$t = get_query_var('tag');

?><!-- responsive-tabs tag.php -->

<div id = "content-header">

	<?php get_template_part('breadcrumbs'); ?> 

 	<h1><?php echo __( 'Posts tagged', 'responsive-tabs' ) . ' "' . esc_html( $tag ); ?>"</h1>

</div> <!-- content-header -->   

<div id = "post-list-wrapper">

	<?php get_template_part( 'post', 'list' ); ?>
	
</div> <!-- post-list-wrapper-->
	
 <!-- empty bar to clear formatting -->
<div class="horbar-clear-fix"></div>

<?php get_footer();
<?php
/*
 * File: author.php
 * Description: Displayed for author archive searches
 *
 * @package responsive-tabs
 *
 *
 */



get_header();

/* set up title for author search */

$curauth = ( get_query_var( 'author_name' ) ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
?>

<!-- responsive-tabs author.php -->

<div id = "content-header">

	<?php get_template_part( 'breadcrumbs' ); ?>
	 
 	<h1><?php echo $curauth->display_name; ?> </h1>

	<?php echo responsive_tabs_author_dropdown (); ?>
	
</div>

<div id = "post-list-wrapper">
 
	<?php get_template_part( 'post', 'list' ); ?>
	
</div> 
	
<!-- empty bar to clear formatting -->
<div class="horbar-clear-fix"></div>

<?php get_footer();
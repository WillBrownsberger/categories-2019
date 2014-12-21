<?php
/*
 * File: index.php
 * Description: catchall template -- should never actually be accessed except for new taxonomies without appropriate support or if admin selects
 *              admin>settings>reading static page -- in that case, this template will display latest posts as the posts page
 *
 * @package responsive-tabs
 *
 *
 */



get_header();

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
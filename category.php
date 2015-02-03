<?php
/*
 * File: category.php
 * Description: template used to display theme category search results
 *
 * @package responsive-tabs
 *
 *
 */



get_header();
?>
<!-- responsive-tabs category.php -->

<div id = "content-header">  
	
	<?php get_template_part( 'breadcrumbs' ); ?> 
   
   <h1><?php single_cat_title(); ?></h1> 

 	<h4><?php 		
		$subargs = array(
		  'orderby'		=> 'name',
		  'order' 		=> 'ASC',
	     'hide_empty' => 0,
		  'parent' 		=> $cat, 
		);	 
		$subcategories = get_categories( $subargs );
		if ( $subcategories ) {		
			$sc_count = 0;		 
			foreach( $subcategories as $subcategory ) {
			 	  if ( $sc_count > 0 ) {
			 	  		echo ', ';
			 	  } 
		        echo '<a href="' . get_category_link( $subcategory->term_id ) . '" 
		        title="' . sprintf( esc_attr__( "View all posts in %s", 'responsive-tabs' ), $subcategory->name ) . '" ' . '>' . esc_html( strtolower( $subcategory->name ) ).'</a>';
			 	  $sc_count = $sc_count + 1; 
			}		
		} ?>
	</h4>
	
</div> <!-- content-header -->   

<div id = "post-list-wrapper">

	<?php get_template_part( 'post', 'list' ); ?>
	
</div> <!-- post-list-wrapper-->
	
 <!-- empty bar to clear formatting -->
<div class="horbar-clear-fix"></div>

<?php get_footer();
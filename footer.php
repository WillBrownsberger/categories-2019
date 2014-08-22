<?php
/*
 * File: footer.php
 * Description: page footer, with front page accordion area, also including standard hook and closure of divs opened in header 
 *
 * @package responsive-tabs
 *
 */

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

global $responsive_tabs_theme_options_array; 

echo '<!-- responsive-tabs footer.php -->';
/*
* accordion footer 
*
*/

if ( is_front_page() ) {
	$accordion_posts_list = $responsive_tabs_theme_options_array['front_page_accordion_posts'];
} elseif ( is_page() ) {
	$accordion_posts_list = $responsive_tabs_theme_options_array['page_accordion_posts'];
} elseif ( is_single() ) {
	$accordion_posts_list = $responsive_tabs_theme_options_array['post_accordion_posts'];
} elseif ( is_archive() ) {
	$accordion_posts_list = $responsive_tabs_theme_options_array['archive_accordion_posts'];
} else {
	$accordion_posts_list = '';
}

if (  $accordion_posts_list > '') {
 	
	$accordion_posts_array = explode( ',', $accordion_posts_list ); 
 	
	echo ' <div id = "accordion-wrapper">';
	
		foreach ( $accordion_posts_array as $fold_content ) {
			$post_f = get_post( $fold_content );
			if ($post_f) {
				$post_content = apply_filters( 'the_content', $post_f->post_content );
				$post_title = apply_filters( 'the_title', $post_f->post_title );			
				echo '<div class="accordionItem">' .
			 		'<h2 class=accordion-header>' . $post_title . '</h2>' . 
					'<div class="accordion-content">' . $post_content . '</div>' .
				'</div>';
			}
		};

	echo '</div>';
}

?>

<div class = "horbar-clear-fix"></div>


<?php if( is_active_sidebar( 'bottom_sidebar' ) ) { ?>
	<div id = "bottom_widget_area">
		<?php dynamic_sidebar( 'bottom_sidebar' )  ?>
	</div>
<?php } ?>

</div><!-- view-frame from header -->
</div> <!-- wrapper from header -->
<div id="calctest"></div><!--for testing browser capabilities -- see style.css and resize.js -->

<?php wp_footer(); ?>

</body>
</html>
<?
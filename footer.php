<?php
/*
 * File: footer.php
 * Description: page footer, with front page accordion area, also including standard hook and closure of divs opened in header 
 *
 * @package responsive-tabs
 *
 *
 */



echo '<!-- responsive-tabs footer.php -->';
/*
* accordion footer 
*
*/

if ( is_front_page() ) {
	$accordion_posts_list = get_theme_mod( 'front_page_accordion' );
} elseif ( is_page() ) {
	$accordion_posts_list = get_theme_mod( 'page_accordion' );
} elseif ( is_single() ) {
	$accordion_posts_list = get_theme_mod( 'post_accordion' );
} elseif ( is_archive() ) {
	$accordion_posts_list = get_theme_mod( 'archive_accordion' );
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
			} else {
				_e( '<h6>Check setting at &raquo; Customize &raquo; Appearance &raquo; Footer Accordions.</h6>', 'responsive-tabs' );			
			}
		};

	echo '</div>';
}

?>

<div class = "horbar-clear-fix"></div>


<?php if( is_active_sidebar( 'bottom_sidebar' ) ) { ?>
	<div id = "bottom-widget-area">
		<?php dynamic_sidebar( 'bottom_sidebar' )  ?>
	</div>
<?php } ?>

</div><!-- view-frame from header -->
</div> <!-- wrapper from header -->
<div id="calctest"></div><!--for testing browser capabilities (see style.css and resize.js) -->

<?php 
echo '<div id="welcome-splash-admin-adj">' . is_admin_bar_showing() . '</div>';

/*
* Display optional site info widget if selected in customize.  Display controlled on client -- initial css is display:none.
* This widget is displayed with absolute position css.  Putting it in footer just prevents it from being the featured phrase in all facebook posts.
*/

if ( get_theme_mod( 'welcome_splash_site_info_on' ) ) { 
	if( is_active_sidebar( 'welcome_splash_widget' ) ) { ?> 
	 <div id="welcome-splash"><div id="welcome-splash-content-wrapper">
			<?php dynamic_sidebar( 'welcome_splash_widget' ); ?> 
			<?php wp_meta();	// hook for bottom of sidebar content ?>
			<button id="welcome-splash-close" onclick="toggleSiteInfo()">Thanks. Got it.</button>
		</div></div>
	<?php }
} 

 
wp_footer(); 
?>
</body>
</html>
<?php

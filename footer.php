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

<?php // set latest visit control cookies (last visit is set in javascript to avoid possible caching problems)  */
$show_welcome_splash		= get_theme_mod( 'welcome_splash_on' );
echo '<div id="welcome-splash-show">' . $show_welcome_splash . '</div>';
$expire_days 		= get_theme_mod( 'welcome_splash_expire' );
$utc_of_expiry 	= time() + $expire_days * 60 * 60 * 24;
echo '<div id="welcome-splash-utc-of-expiry">' . $utc_of_expiry . '</div>';
$delay_days 		= get_theme_mod( 'welcome_splash_delay' );
$delay_seconds		= $delay_days * 60 * 60 * 24; 
echo '<div id="welcome-splash-delay-seconds">' . $delay_seconds . '</div>';
echo '<div id="welcome-splash-admin-adj">' . is_admin_bar_showing() . '</div>';
echo '<div id="welcome-splash-alert-class">' . get_theme_mod( 'welcome_splash_alert_class' ) . '</div>';
 
wp_footer(); 
?>
</body>
</html>
<?php

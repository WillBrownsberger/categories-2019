<?php
/*
 * File: new-visit.php
 * Description: Displays header image and welcome widget content if new visit 
 *
 * @package responsive-tabs
 *
 *
 */
 
/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." ); 

$welcome_splash_page_id = get_theme_mod( 'welcome_splash_page' ); 
if ( $welcome_splash_page_id > 0 ) {
	$welcome_splash_page		= get_post( $welcome_splash_page_id );
}
?>

<div id = "welcome-splash"  >
	<div id = "welcome-splash-content-wrapper">
		<?php if ( null == $welcome_splash_page ) {
			echo '<h3>' . __( 'Check settings at Customize &raquo; Welcome Splash Page', 'responsive-tabs' ) . '</h3>';	
		} else { ?>
			<div id = "welcome-splash-title">
				<?php echo '<h1>' . apply_filters( 'the_title',  $welcome_splash_page->post_title ) . '</h1>' ?>
			</div>
			<div id = "welcome-splash-content">
				<?php echo apply_filters( 'the_content',  $welcome_splash_page->post_content ) ?>
			</div>
			<button id="welcome-splash-close" onclick="rtDestroyElement( 'welcome-splash' )">Thanks. Got it.</button>	
		<?php } ?>	
	</div>
</div>
<?


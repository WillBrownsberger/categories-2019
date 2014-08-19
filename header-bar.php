<?php 
/*
*  File: header-bar.php
*  Description: displays menu/identity header bar that carries across all theme pages
*
* @package responsive-tabs
*/

/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
?>
<!-- responsive-tabs header-bar.php -->
<div id="header-bar-spacer"></div>
<div id="header-bar-wrapper"  class = "
	<?php if ( is_admin_bar_showing()) {
		echo 'admin-bar-showing';
	} else { 
		echo 'no-admin-bar';
	}
	?>"> 	
 	<div id="header-bar">
		<div id = "header-bar-content-spacer"></div>
			<button id = "side-menu-button" onclick = "toggle_side_menu()"><?php _e( 'MENU', 'responsive-tabs' ); ?></button>

			<?php if ( is_active_sidebar ( 'header_bar_widget' ) ) { ?>	
				<div id = "header-bar-widget-wrapper">
					<?php dynamic_sidebar( 'header_bar_widget' );  ?>
				</div>
			<?php } ?>
			
			<ul id = "site-info-wrapper">
				<li class="site-title site-title-long">
					 <a href="<?php echo( home_url( '/' ) ); ?>" title="<?php _e( 'Go to front page', 'responsive-tabs' ); ?>">
					 <?php esc_html( bloginfo( 'name' ) ); ?> </a>
				</li>
				<li class = "site-title site-title-short">
					 <a href="<?php echo( home_url( '/' ) ); ?>" title="<?php _e( 'Go to front page', 'responsive-tabs' ); ?>">
					 <?php echo esc_html( get_theme_mod( 'site_short_title' ) ); ?> </a>
				</li>
				<li class="site-description"> 
					<?php esc_html( bloginfo( 'description' ) ); ?>
				</li>
			</ul>
			<div class="horbar-clear-fix"></div>  
	</div><!-- header-bar -->
</div><!-- header-bar wrapper-->
<?php

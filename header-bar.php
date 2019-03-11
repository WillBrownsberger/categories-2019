<?php 
/*
*  File: header-bar.php
*  Description: displays menu/identity header bar that carries across all theme pages
*
* @package responsive-tabs
*
*/


?>
<!-- responsive-tabs header-bar.php -->
<div id="header-bar">
	<button id = "side-menu-button" onclick = "toggleSideMenu()"><?php _e( 'MENU', 'responsive-tabs' ); ?></button>
	<ul id = "site-info-wrapper">
		<li class="site-title">
			 <a href="<?php echo( home_url( '/' ) ); ?>" class="site-title-long" title="<?php _e( 'Go to front page', 'responsive-tabs' ); ?>"><?php esc_html( trim( bloginfo( 'name' ) ) ); ?></a>
			 <a href="<?php echo( home_url( '/' ) ); ?>" class="site-title-short" title="<?php _e( 'Go to front page', 'responsive-tabs' ); ?>"><?php echo esc_html( trim( get_theme_mod( 'site_short_title' ) ) ); ?></a>
		</li>
		<li class="site-description"><?php esc_html( bloginfo( 'description' ) ); ?></li>
	</ul>
	<div class="horbar-clear-fix"></div>  
</div><!-- header-bar -->
<?php

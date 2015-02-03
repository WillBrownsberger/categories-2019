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
		<button id = "side-menu-button" onclick = "toggleSideMenu()"><?php _e( 'MENU', 'responsive-tabs' ); ?></button>

		<?php if ( is_active_sidebar ( 'header_bar_widget' ) ) { ?>	
			<div id = "header-bar-widget-wrapper">
				<?php dynamic_sidebar( 'header_bar_widget' );  ?>
			</div>
		<?php } ?>
		
		<ul id = "site-info-wrapper">
			<li class="site-title">
				 <a href="<?php echo( home_url( '/' ) ); ?>" class="site-title-long" title="<?php _e( 'Go to front page', 'responsive-tabs' ); ?>"><?php esc_html( trim( bloginfo( 'name' ) ) ); ?></a>
				 <a href="<?php echo( home_url( '/' ) ); ?>" class="site-title-short" title="<?php _e( 'Go to front page', 'responsive-tabs' ); ?>"><?php echo esc_html( trim( get_theme_mod( 'site_short_title' ) ) ); ?></a>
			</li>
			<li class="site-description"><?php esc_html( bloginfo( 'description' ) ); ?></li>
			<?php if( get_theme_mod( 'welcome_splash_site_info_on' ) ) { ?>
				<li class = "welcome-splash-site-info">
					<button id = "welcome-splash-site-info-button" onclick = "toggleSiteInfo()">?</button>
				</li>
			<?php } ?>
		</ul>
		<div class="horbar-clear-fix"></div>  
	</div><!-- header-bar -->
</div><!-- header-bar wrapper-->
<?php

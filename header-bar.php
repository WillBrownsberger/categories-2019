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

	<div id="site-title">
		 <a href="<?php echo( home_url( '/' ) ); ?>" class="site-title-long" title="<?php _e( 'Go to front page', 'responsive-tabs' ); ?>">
		 	<?php esc_html( trim( bloginfo( 'name' ) ) ); ?></a>
			<span id="site-description"><?php esc_html( bloginfo( 'description' ) ); ?></span>
		 </a>
	</div>

	<button id = "side-menu-button" onclick = "toggleSideMenu()"><span class="dashicons dashicons-menu"></span></button>

	<div id="main-menu" >
		
		<ul id = "main-menu-and-login-ul"><?php 
			$args = array (
					 'theme_location' 	=> 'main-menu', 
					 'container'			=> false,
					 'items_wrap'			=> '%3$s', // no items wrap
			 		); 
			wp_nav_menu( $args ); 
			
			if (get_theme_mod('show_login_links'))	{
								
				$redirect_to = is_home() ? home_url() : get_permalink();  // from home, get_permalink() returns latest post   
				
				if ( is_user_logged_in() ) {
           		$current_user = wp_get_current_user();
           		if ( current_user_can( 'edit_others_posts' ) ) {
						echo '<li><a href="' . site_url() . '/wp-admin">' . __( 'dashboard', 'responsive-tabs' ) . '</a></li>';
					} else {
						$profile_link = site_url() . '/wp-admin/profile.php';
		    			echo '<li><a href="'. $profile_link . '" title="'. __( 'profile for ', 'responsive-tabs' ) . esc_attr( $current_user->display_name ). '">' . __('view profile', 'responsive-tabs' ) . '</a></li>';
		    		}
					echo '<li><a href="' . wp_logout_url( $redirect_to ) . '">' . __( 'logout', 'responsive-tabs' ) . '</a></li>';
		 		} else {
					echo '<li><a href="' . wp_login_url( $redirect_to ) . '">' . __( 'login', 'responsive-tabs' ) . '</a></li>';
				}
				
			} // if show_login_links
		?></ul> <?php // combined menu and login links
		
		if ( is_active_sidebar( 'side_menu_widget' ) ) { ?>
			<div id = "side-menu-widget-area" >
				<?php dynamic_sidebar('side_menu_widget'); ?>
			</div>
		<?php } ?>

	</div><!--side-menu-->





	<div class="horbar-clear-fix"></div>  
</div><!-- header-bar -->
<?php

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
	<div id="main-menu" >
		<?php 
			// search widget
			$search_engine_id = get_theme_mod( 'google_custom_search_id' );
			if ( ! $search_engine_id ){
				if( is_active_sidebar( 'search_widget' ) ) { 
					// display sidebar
					dynamic_sidebar( 'search_widget');
					echo '<div class="horbar-clear-fix"></div>'; 
				}
			} else {
				echo '<h2>' . __( 'Google Site Search', 'responsive-tabs' ) . '</h2>';
				echo '<gcse:search></gcse:search>';
			}
		?>
		<h2><?php echo __( 'Quick Links', 'responsive-tabs' ) ?></h2>
		<ul id = "main-menu-and-login-ul">
			<?php 
			// main menu
			$args = array (
				'theme_location' 	=> 'main-menu', 
				'items_wrap'		=> '%3$s', // no items wrap
			); 
			wp_nav_menu( $args );
			// comment link
			echo '<li><a href="' . site_url() . '?show_comments=yes">' . __( 'discussion', 'responsive-tabs' ) . '</a></li>';

			
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
			echo '</ul>';

			// category list two deep
			echo  '<h2>' . __( 'Posts by Category', 'responsive-tabs' ) . '</h2>';
			category_list_two_deep ();
			
		?> 

	</div><!--main-menu-->

	<button id = "main-menu-button" onclick = "toggleMainMenu()" title="Site Map"><span class="dashicons dashicons-menu"></span></button>

	<div id="site-title">
		 <a href="<?php echo( home_url( '/' ) ); ?>" class="site-title-long" title="<?php _e( 'Go to front page', 'responsive-tabs' ); ?>">
		 	<?php esc_html( trim( bloginfo( 'name' ) ) ); ?>
		 	<span id="site-description"> -- <?php esc_html( bloginfo( 'description' ) ); ?></span>
		 	<?php 
		 	$custom_logo_id = get_theme_mod( 'custom_logo' );
			$custom_logo_url = wp_get_attachment_image_url( $custom_logo_id , array( 36,36 ) );
			echo '<img width=36 height=36 src="' . esc_url( $custom_logo_url ) . '" alt="">';
		 	?>
		</a>	 	
	</div>

	<div class="horbar-clear-fix"></div>  
</div><!-- header-bar -->
<?php

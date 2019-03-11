<?php
/*
 * File: header.php
 * Description: html header and body header, including login bar, banner area and sidemenu.  Sets up major body divisions too. 
 *
 * @package responsive-tabs
 *
 *
 */
 
 

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_head(); ?>
    </head>
     
<body <?php body_class(); ?>> 

<!-- responsive-tabs header.php -->
<?php

/*
* Now construct login bar, banner area and sidemenu
*
*/      
get_template_part('header','bar');
	
$view_frame_class = (is_front_page()) ? 'front-page-view' : 'back-page-view'; 
	
?>

<div id = "wrapper"><!-- sets boundaries on sidebar expansion -->

	<div id="side-menu" class = "sidebar-menu">
		
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

	<div id="view-frame" class = "<?php echo $view_frame_class;?>">

		<?php if ( ! is_front_page() ) {
			echo '<div id="color-splash"></div>';
		}
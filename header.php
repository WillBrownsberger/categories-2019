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
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" /> 
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_head(); ?>
    </head>
     
<body <?php body_class(); ?>> 

<!-- responsive-tabs header.php -->
<?php
/*
* Format optional welcome splash or site info message if selected in customize.  Display controlled on client -- initial css is display:none.
* Display options are passed as hidden areas in footer.php
*
*/
 
if ( get_theme_mod( 'welcome_splash_on' ) || get_theme_mod( 'welcome_splash_site_info_on' ) ) { 
	if( is_active_sidebar( 'welcome_splash_widget' ) ) { ?> 
	 <div id="welcome-splash"><div id="welcome-splash-content-wrapper">
			<?php dynamic_sidebar( 'welcome_splash_widget' ); ?> 
			<?php wp_meta();	// hook for bottom of sidebar content ?>
			<button id="welcome-splash-close" onclick="toggleSiteInfo()">Thanks. Got it.</button>
		</div></div>
	<?php }
} 

/*
* Now construct login bar, banner area and sidemenu
*
*/      
get_template_part('header','bar');
	
$view_frame_class = (is_front_page()) ? 'front-page-view' : 'back-page-view'; 
	
?>

<div id = "wrapper"><!-- sets boundaries on sidebar expansion -->

	<div id="side-menu" class = "sidebar-menu">

		<?php if( is_active_sidebar( 'header_bar_widget' ) ) { ?>
			<div id = "header-bar-widget-wrapper-side-menu-copy" >
				<?php dynamic_sidebar( 'header_bar_widget' ); ?>
			</div>
		<?php } ?>
		
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
						echo '<li><a href="/wp-admin">' . __( 'dashboard', 'responsive-tabs' ) . '</a></li>';
					} else {
		           	if (class_exists('bbPress')) { //  is_plugin_active('bbpress/bbpress.php')
		           		$profile_link = esc_url( '/forums/users/'. $current_user->user_login );
		           	} else {
		   				$profile_link = '/wp-admin/profile.php';
		   			}
		    			echo '<li><a href="'. $profile_link . '" title="'. __( 'profile for ', 'responsive-tabs' ) . esc_attr( $current_user->display_name ). '">' . __('view profile', 'responsive-tabs' ) . '</a></li>';
		    		}
					echo '<li><a href="' . wp_logout_url( $redirect_to ) . '">' . __( 'logout', 'responsive_tabs' ) . '</a></li>';
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
<?php
/*
* File: class-responsive-tabs-customization-css.php
*			
* Description: outputs css from theme customizer and any custom css or scripts from front page options
*  
* @package responsive-tabs
*/
/* assure that will die if accessed directly */ 
defined( 'ABSPATH' ) or die( "Unauthorized direct script access." );

add_action( 'customize_register', 'responsive_tabs_theme_customizer' );

function responsive_tabs_customize_css() { ?>   
	<!-- theme customizer css output via responsive-tabs-customization-css.php-->
	<style type="text/css">
	
		body {
			color: <?php echo get_theme_mod( 'body_text_color' ); ?>;
			font-family: <?php echo get_theme_mod( 'body_text_font_family' ); ?>;
	  		font-size: <?php echo get_theme_mod( 'body_text_font_size' ); ?>;
	  	}
	  	
		#bbpress-forums .bbp-reply-content {
	  		font-size: <?php echo get_theme_mod( 'body_text_font_size' ); ?>;
	  	}
	  	
		a {
			color: <?php echo get_theme_mod( 'body_link_color' ); ?>;
		}
		
		a:hover {
			color: <?php echo get_theme_mod( 'body_link_hover_color' ); ?>;
		}
	  
		h1, 
		h2, 
		h3, 
		h4, 
		h5, 
		h6  {
			color: <?php echo get_theme_mod( 'body_header_color' ); ?>;
		}
	  
		.site-title a,
		.site-description,
		#main-tabs .main-tabs-headers li a,
	  	#home_bulk_widgets .home-bulk-widget-wrapper h2.widgettitle	{
	 			color: <?php echo get_theme_mod( 'home_widgets_title_color' ); ?>;
	 	}
	
		#front-page-mobile-color-splash,     			
		#highlight-text-area,
		#color-splash { 
			background: <?php echo get_theme_mod( 'highlight_color' ); ?>; 
		}
	  	
		.post-list li.sticky {
			border-left: 8px solid <?php echo get_theme_mod( 'sticky_post_border_color' ); ?>;
		}	  	
	  			
		#highlight-headline,
		#highlight-subhead	{
			color: <?php echo get_theme_mod( 'highlight_headline_color' ); ?>;
			font-family: <?php echo get_theme_mod( 'highlight_headline_font_family' ); ?>;
		}
	
		#highlight-headline	{
			font-size: <?php echo get_theme_mod( 'highlight_headline_font_size' ); ?>;
		}
			
		#highlight-headline a	{
			color: <?php echo get_theme_mod( 'highlight_headline_link_color' ); ?>;
		}
	
		#highlight-headline a:hover	{
			color: <?php echo get_theme_mod( 'highlight_headline_link_hover_color' ); ?>;
		}
	
	 	div#side-menu.sidebar-menu {
	 		background-image: url("<?php header_image(); ?>");
	 	}
	  
	 	.site-title a,
		.site-description,
		.site-title-short a {
			font-family: <?php echo get_theme_mod( 'site_info_font_family' ); ?>;
		}
	
		@media only screen and (max-width: 840px) {
			#highlight-headline	{
				font-size: <?php echo get_theme_mod( 'highlight_headline_font_size_small_screen' ); ?>;
			}
		}
	
		<?php	if( get_theme_mod( 'custom_css' ) > ''  ) {	
			echo '/* responsive-tab css directly input in admin>appearance>customize(echoed in responsive-tabs-customization-css.php) */
			' . esc_html( get_theme_mod( 'custom_css' ) ) ;
	   } ?>
	</style>
 <?php
}
add_action( 'wp_head', 'responsive_tabs_customize_css' );

function responsive_tabs_output_header_scripts() {
	if ( ! is_user_logged_in() && get_theme_mod( 'header_scripts' ) > '') {
		echo  '<!-- responsive-tab script directly input in admin>appearance>customize (echoed in responsive-tabs-customization-css.php)-->' .
			get_theme_mod( 'header_scripts' );
	}
}
add_action( 'wp_head', 'responsive_tabs_output_header_scripts', 999 );


function responsive_tabs_output_footer_scripts() {  
	if ( ! is_user_logged_in() && get_theme_mod( 'footer_scripts' ) > '') {
		echo  '<!-- responsive-tab script directly input in admin>appearance>customize (echoed in responsive-tabs-customization-css.php)-->' .  
			get_theme_mod( 'footer_scripts' );
	}
}
add_action( 'wp_footer', 'responsive_tabs_output_footer_scripts' );
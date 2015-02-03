<?php
/*
 * File: header-retina.php
 * 
 * Description: header omitting  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
 * 	-- allows screen to be resized by retina device user
 *		-- also omits menu and limits top material to maximize screen size 
 *
 * See: http://codex.wordpress.org/Theme_Development#Document_Head_.28header.php.29
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
     <link rel="profile" href="http://gmpg.org/xfn/11" />
     <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
     <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
     <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>> 

<?php	get_template_part( 'retina-header-bar' ); ?>

	<div id = "wrapper">
	<div id="retina-view-frame"> <!-- will be closed by view frame close -->
	<div id="color-splash"></div><?php
	
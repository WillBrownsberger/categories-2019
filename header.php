<?php
/*
 * File: header.php
 * Description: html header and body header, including login bar, banner area and sidemenu.  Sets up major body divisions too. 
 *
 * @package responsive-tabs
 *
 * OMITS: <meta name="viewport" content="width=device-width, initial-scale=1.0"> -- allows resize
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

<!-- responsive-tabs header.php -->
<?php

/*
* Now construct login bar, banner area and sidemenu
*
*/      
get_template_part('header','bar');
	
?>

<div id = "wrapper">

	<div id="color-splash"></div>

<?php
/*
 * File: post-listheader.php
 * Description: template part used to display the consistent header for list of posts in full width mode  
 *
 * @package responsive-tabs
 *
 */



echo ' <!-- post-listheader.php start list -->'. /* post list headers -- echoing to avoid white spaces in inline-block styling*/
  '<li class = "pl-odd">' .
  		'<ul class = "pl-headers">' .
  			'<li class="pl-post-title">' . __( 'Post (comment count)', 'responsive-tabs' ) . '</li>' .
  			'<li class = "pl-post-author">' . __( 'Author', 'responsive-tabs' ) . '</li>' .
  			'<li class = "pl-post-date-time">' . __( 'Date', 'responsive-tabs') .'</li>' .
  		'</ul>' .
  	'</li>'; 

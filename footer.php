<?php
/*
 * File: footer.php
 * Description: page footer, also including standard hook and closure of divs opened in header 
 *
 * @package responsive-tabs
 *
 *
 */



echo '<!-- responsive-tabs footer.php -->';


?>

<div class = "horbar-clear-fix"></div>


<?php if( is_active_sidebar( 'bottom_sidebar' ) ) { ?>
	<div id = "bottom-widget-area">
		<?php dynamic_sidebar( 'bottom_sidebar' )  ?>
	</div>
<?php } ?>

</div> <!-- wrapper from header -->

<?php 
 
wp_footer(); 
?>
</body>
</html>
<?php

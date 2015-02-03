<?php
/*
* File: front-page.php
*
* Description: this template will be invoked as the front page if Admin>Settings>Reading>Front page displays is set to "Your Latest Posts" 
* 
* @package responsive-tabs 
* 
*
*/ 



// support settings>reading>front page displays -- see http://codex.wordpress.org/Creating_a_Static_Front_Page#Configuration_of_front-page.php
if ( 'posts' != get_option( 'show_on_front' ) ) { // use page template
    get_template_part ( 'page' );
} else { // use this template
 
	get_header();
	
	
	/* 
	*
	* Highlighted area 
	*
	*/

	if( is_active_sidebar( 'highlight_area_widget' ) ) { 
		// display sidebar
			dynamic_sidebar( 'highlight_area_widget' );
	} else { // show headlines if no widget in highlight_area_widget
	
		$highlight_headline = get_theme_mod( 'highlight_headline' );
		$highlight_subhead =  get_theme_mod( 'highlight_subhead' );
		$highlight_headline_small_screen = get_theme_mod( 'highlight_headline_small_screen' );
		
		if ( $highlight_headline > '  ' || $highlight_subhead > '  ' ) {
		  	echo '<div id = "highlight-text-area">';
				if( $highlight_headline > '    ' )	{
			      echo '<div  id="highlight-headline">' .
			 		  	$highlight_headline  .  
			      '</div>';
				}
				if( $highlight_subhead > '    ' ) {          
					echo '<div  id="highlight-subhead">' .
						$highlight_subhead .  
					'</div>';
				} 
				if( $highlight_headline_small_screen > '    ' ) {          
			      echo '<div  id="highlight-headline-small-screen">' .
			      	$highlight_headline_small_screen .  
			      '</div>';
				}        
			echo '</div>
			<div class = "horbar-clear-fix"></div>'; 
		} else {
			echo '<div id = "color-splash"></div>';
		}
	echo '<div id="front-page-mobile-color-splash"></div>'; // displays only in mobile mode (not show highlighted message in mobile)		
	}	
	
	/*
	* tabs area
	*
	*/
	$mods = get_theme_mods();
		
	$default_active_tab 	=  get_theme_mod( 'landing_tab' );  

	$active_tab 			= isset( $_GET[ 'frontpagetab' ] )  ? $_GET[ 'frontpagetab' ] : $default_active_tab;

	$tab_titles 			= get_theme_mod( 'tab_titles' );
	
	if ( ! $tab_titles > '' ) {
		$tab_titles 		= 'getting started, latest posts';
	}
	$tab_titles_array 	= explode( ',', $tab_titles );
	
	?>
	
   <div id = "main-tabs-wrapper">
		
		<!-- desktop tabs -->   	
   	<div id="main-tabs">
   		<ul class = "main-tabs-headers"><?php
		   	$tab_title_count = 0;
		    	foreach ( $tab_titles_array as $tab_title ) {
		    		$nav_tab_active = ( $active_tab == $tab_title_count ) ? 'nav-tab-active' : 'nav-tab-inactive';
					echo '<li class="' . $nav_tab_active . '"><a href="/?frontpagetab=' . $tab_title_count . '"> '. esc_html( trim( $tab_title ) )  .'</a></li>';
					$tab_title_count = $tab_title_count + 1;    			
				} ?> 
        </ul>
                
        <!-- mobile tabs -->
        <div id = "main-tabs-dropdown-wrapper">
				<select id = "main-tabs-dropdown-id" name = "main-tabs-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
        			<?php $tab_title_count = 0;
					foreach ( $tab_titles_array as $tab_title ) {
 						if( $active_tab == $tab_title_count ) {
							echo '<option value="">' . esc_html( $tab_title )  . '</option>';
						}
						$tab_title_count = $tab_title_count + 1;   
					} 
					$tab_title_count = 0;
					foreach ( $tab_titles_array as $tab_title ) {
    					if( $active_tab != $tab_title_count ) {    		
							echo '<option value="/?frontpagetab=' . $tab_title_count . '"> ' . esc_html( $tab_title ) . '</option>';
						}
						$tab_title_count = $tab_title_count + 1;    			
					}?> 
        		</select>
        	</div>
    
		<!-- display content for active tab-->
			<div class="main-tab-content"><?php
				if( is_active_sidebar( 'home_widget_' . ( $active_tab ) ) ) { 
					// display sidebar
					dynamic_sidebar( 'home_widget_' . ( $active_tab ) );
					echo '<div class="horbar-clear-fix"></div>'; 
				} else { 
					if ( strtolower( trim( $tab_titles_array[$active_tab] ) ) == "getting started" ) {
						get_template_part( 'getting', 'started');					
					} else { ?>
						<div class = "responsive-tabs-notice">
							<h3> <?php printf ( __( 'Nothing yet in the widget area for tab %d.', 'responsive-tabs' ), $active_tab ); ?> </h3> 
							<h4> <?php printf ( __( 'To populate, please go to Dashboard>Appearance>Customize>Widgets: Tab %d.', 'responsive-tabs' ), $active_tab ); ?> </h4>
							<h4> <?php printf ( __( 'Note: When viewing the front page in Customize, to see Widgets: Tab %d, click on this tab, titled "%s."', 'responsive-tabs' ), $active_tab, trim( $tab_titles_array[$active_tab] ) ); ?> </h4>						
						</div>							
					<?php }							
				}
				?>
			</div><!-- close main-tab-content -->
		</div><!-- close main-tabs -->
	</div><!-- close main-tabs-wrapper --><?php


	get_footer();
} // close use this template condition
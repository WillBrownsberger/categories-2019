<?php
/*
* File: responsive-tabs-customization.php
*			
* Handles ALL theme options through theme customizer interface
*
* @package responsive-tabs
*
*/

$font_family_array = array (  
	'Arial, "Helvetica Neue", Helvetica, sans-serif' 														=> 'Arial',
	'"Arial Black", "Arial Bold", Gadget, sans-serif'														=> 'Arial Black',
	'"Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace' 			=> 'Courier',
	'Copperplate, "Copperplate Gothic Light", fantasy' 													=> 'Copperplate',
	'Garamond, Baskerville, "Baskerville Old Face", "Hoefler Text", "Times New Roman", serif' => 'Garamond',
	'Georgia, Times, "Times New Roman", serif' 																=> 'Georgia',
	'"Lucida Bright", Georgia, serif' 																			=> 'Lucida Bright',
	'Rockwell, "Courier Bold", Courier, Georgia, Times, "Times New Roman", serif' 				=> 'Rockwell',
	'"Brush Script MT", cursive' 																					=> 'Script',
	'TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif' 							=> 'Times New Roman',
	'Verdana, Geneva, sans-serif' 																				=> 'Verdana'
);

$font_size_array = array (
	'12px'	=> '12px',
	'14px'	=> '14px',
	'16px'	=> '16px',
	'17px'	=> '17px',
	'18px'	=> '18px',
	'20px'	=> '20px',		
	'24px'	=> '24px',
	'32px'	=> '32px',
	'40px'	=> '40px',
	'44px'	=> '44px',
	'52px'	=> '52px',
	'60px'	=> '60px',
);

$landing_tab_options_array = array (
	'0'		=> '0',
	'1'		=> '1',
	'2'		=> '2',	
	'3'		=> '3',
	'4'		=> '4',
	'5'		=> '5',
	'6'		=> '6',
	'7'		=> '7',
	'8'		=> '8',
	'9'		=> '9',
	'10'		=> '10',
	'11'		=> '11',
	'12'		=> '12',
	'13'		=> '13',
	'14'		=> '14',
	'15'		=> '15',
);
							
function responsive_tabs_theme_customizer( $wp_customize ) {

	global $font_family_array;
	global $font_size_array;
	global $landing_tab_options_array;

	/* create custom call back for text area */
	class Responsive_Tabs_Textarea_Control extends WP_Customize_Control { // http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
		public $type = 'textarea';
 		public function render_content() { ?>
 			<label>
				<span class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</span>
				<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
		<?php }
	}

	
	/* tab titles */
	$wp_customize->add_section( 'tab_titles_section' , array(
	    'title'      => __( 'Tab Titles', 'responsive-tabs' ),
	    'priority'   => 35,
	    'description' => 'Enter tab titles separated by commas, like so:<code>Favorites, Latest Posts, Comments</code>. 
	    	Then enter content for each tab widget.  The widget for a tab shows below in this menu  when you click on the tab title.
	    	<a href="http://responsive-tabs-theme-for-wp.com/">More help &raquo;</a>' , 
	) );	
	
	$wp_customize->add_setting( 'tab_titles', array(
	    'default' => __( 'This is Tab 0, Getting Started','responsive-tabs' ),
	    'sanitize_callback' => 'responsive_tabs_title_list'
	) );
	
	$wp_customize->add_setting( 'landing_tab', array(
	    'default' => '0',
	   'sanitize_callback' => 'sanitize_text_field'
	) );
	
	/* color settings */
	
	$wp_customize->add_setting( 'home_widgets_title_color', array(
	    'default' => '#555',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	
	$wp_customize->add_setting( 'body_text_color', array(
	    'default' => '#000',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'body_header_color', array(
	    'default' => '#000',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'body_link_color', array(
	    'default' => '#555',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'body_link_hover_color', array(
	    'default' => '#777',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'sticky_post_border_color', array(
	    'default' => '#333',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'list_odd_rows', array(
	    'default' => '#f0f0f0',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'list_even_rows', array(
	    'default' => '#fafafa',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );



	/* fonts */
	
	$wp_customize->add_section( 'responsive_tabs_fonts' , array(
	    'title'      => __( 'Fonts', 'responsive-tabs' ),
	    'priority'   => 50,
	) );
	
	$wp_customize->add_setting( 'site_info_font_family', array(
	    'default' =>  'Arial, "Helvetica Neue", Helvetica, sans-serif' ,
	    'sanitize_callback' => 'sanitize_text_field'
	) );
	
	$wp_customize->add_setting( 'body_text_font_size', array(
	    'default' => '16px',
	    'sanitize_callback' => 'sanitize_text_field'
	) );
		
	$wp_customize->add_setting( 'body_text_font_family', array(
	    'default' => 'Arial, "Helvetica Neue", Helvetica, sans-serif' ,
	    'sanitize_callback' => 'sanitize_text_field'
	) );
	
	$wp_customize->add_setting( 'highlight_headline_font_family', array(
	    'default' =>  'Rockwell, "Courier Bold", Courier, Georgia, Times, "Times New Roman", serif',
	    'sanitize_callback' => 'sanitize_text_field'
	) );
	
	$wp_customize->add_setting( 'highlight_headline_font_size', array(
	    'default' => '52px',
	    'sanitize_callback' => 'sanitize_text_field'
	) );
	
	/* note: formerly in nav section, removed in 4.3 */
	$wp_customize->add_section( 'responsive_tabs_navigation_section' , array(
	    'title'      => __( 'Theme Navigation', 'responsive-tabs' ),
	    'priority'   => 105,
	    'description' => __( 'Set Responsive Tabs navigation options', 'responsive-tabs' ), 
	) );	

	/* login links in sidemenu bar */
	
	$wp_customize->add_setting( 'show_login_links', array(
	    'default' => true,
	    'sanitize_callback' => 'responsive_tabs_sanitize_boolean',
	) );
	
	
	/* custom css/scripts */
	$wp_customize->add_section( 'css_scripts_section' , array(
	    'title'      => __( 'Custom CSS and Scripts', 'responsive-tabs' ),
	    'priority'   => 200,
	    'description' => __( 'If you are an experienced user, you can enter custom css or scripts below.  Use caution -- the script entries are not filtered.', 'responsive-tabs' ), 
	) );	
	
	$wp_customize->add_setting( 'custom_css', array(
	    'default' => '',
	    'sanitize_callback' => 'responsive_tabs_pass_through'
	) );

	$wp_customize->add_setting( 'header_scripts', array(
	    'default' => '',
	    'sanitize_callback' => 'responsive_tabs_pass_through'
	) );

	$wp_customize->add_setting( 'footer_scripts', array(
	    'default' => '',
	    'sanitize_callback' => 'responsive_tabs_pass_through'
	) );
	

	/* infinite scroll */ 
	$wp_customize->add_section( 'disable_infinite_scroll_global_section' , array(
	    'title'      => __( 'Infinite Scroll', 'responsive-tabs' ),
	    'priority'   => 221,
	    'description' => __( 'Check to DISable infinite scroll for queries and use standard WP pagination.', 'responsive-tabs' ) 
	) );	
	
	$wp_customize->add_setting( 'disable_infinite_scroll_global', array(
	    'default' => 0,
	    'sanitize_callback' => 'responsive_tabs_sanitize_boolean',
	) );

	$wp_customize->add_setting( 'disable_infinite_scroll_comments', array(
	    'default' => 0,
	    'sanitize_callback' => 'responsive_tabs_sanitize_boolean',
	) );
	
	/* CONTROLS
	-------------------------------------------------------*/	
	
	/* tab titles control */		
	$wp_customize->add_control( new Responsive_Tabs_Textarea_Control( $wp_customize, 'tab_titles', array(
		'label'      => __( 'Tab Titles', 'responsive-tabs' ),
		'section'    => 'tab_titles_section',
		'settings'   => 'tab_titles',
	   'priority'   => 1
	) ) );
	
	$wp_customize->add_control( 'landing_tab', array(
	    'label'   	 => __( 'Landing Tab', 'responsive-tabs' ),
	    'section'   => 'tab_titles_section',
	    'type'      => 'select',
	    'settings'  => 'landing_tab',
	    'choices'   => $landing_tab_options_array
	) );

/* color controls */


	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'home_widgets_title_color', array(
		'label'     => __( 'Tab Titles Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'home_widgets_title_color',
		'priority' 	=> 60,
	) ) );  
		  
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_text_color', array(
		'label'     => __( 'Body Text Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'body_text_color',
		'priority'	=> 70,
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_header_color', array(
		'label'     => __( 'Body Header Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'body_header_color',
		'priority'	=> 80,
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_link_color', array(
		'label'     => __( 'Body Link Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'body_link_color',
		'priority'	=> 90,
	) ) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_link_hover_color', array(
		'label'     => __( 'Body Link Hover Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'body_link_hover_color',
		'priority'	=> 100,
	) ) );  

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sticky_post_border_color', array(
		'label'     => __( 'Sticky Post Border Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'sticky_post_border_color',
		'priority'	=> 105,
	) ) );  

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'list_even_rows', array(
		'label'     => __( 'List Even Row Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'list_even_rows',
		'priority'	=> 106,
	) ) );  
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'list_odd_rows', array(
		'label'     => __( 'List Odd Row Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'list_odd_rows',
		'priority'	=> 107,
	) ) );  
	
	/*  fonts  */
	$wp_customize->add_control( 'body_text_font_size', array(
	    'label'   	=> __('Body Font Size', 'responsive-tabs' ),
	    'section' 	=> 'responsive_tabs_fonts',
	    'type'    	=> 'select',
	    'settings'	=> 'body_text_font_size',
	    'choices' 	=> $font_size_array,
	    'priority'	=> 10,
	) );
	
	$wp_customize->add_control( 'body_text_font_family', array(
	    'label'   	=> __( 'Body Font Family:', 'responsive-tabs' ),
	    'section' 	=> 'responsive_tabs_fonts',
	    'type'    	=> 'select',
	    'settings' => 'body_text_font_family',
	    'choices'  => $font_family_array,
	    'priority' => 20,
	) );
		
	
	$wp_customize->add_control( 'site_info_font_family', array(
	    'label'    => __( 'Site Title Font Family:', 'responsive-tabs' ),
	    'section'  => 'responsive_tabs_fonts',
	    'type'     => 'select',
	    'settings' => 'site_info_font_family',
	    'choices'  => $font_family_array,
	    'priority' => 30,
	) );
	
		
	$wp_customize->add_control( 'highlight_headline_font_family', array(
	    'label'   	=> __( 'Highlight Headline Font Family', 'responsive-tabs' ),
	    'section' 	=> 'responsive_tabs_fonts',
	    'type'    	=> 'select',
	    'settings'	=> 'highlight_headline_font_family',
	    'choices' 	=> $font_family_array,
	    'priority'	=> 40,
	) );
	
	$wp_customize->add_control( 'highlight_headline_font_size', array(
	    'label'   	=> __( 'Highlight Headline Font Size', 'responsive-tabs' ),
	    'section' 	=> 'responsive_tabs_fonts',
	    'type'    	=> 'select',
	    'settings'	=> 'highlight_headline_font_size',
	    'choices' 	=> $font_size_array,
	    'priority'	=> 50,
	) );

	/* login link control */
	
	$wp_customize->add_control( 'show_login_links', array(
	    'settings' => 'show_login_links',
	    'label'    => __( 'Show Login Links in Main Menu', 'responsive-tabs' ),
	    'section'  => 'responsive_tabs_navigation_section',
	    'type'     => 'checkbox',
	    'priority'	=>	30,
	) );


	/* custom css & scripts controls */
	$wp_customize->add_control( new Responsive_Tabs_Textarea_Control( $wp_customize, 'custom_css', array(
		'label'      => __( 'Custom CSS for Header', 'responsive-tabs' ),
		'section'    => 'css_scripts_section',
		'settings'   => 'custom_css',
	   'priority'   => 10,
	) ) );

	
	$wp_customize->add_control( new Responsive_Tabs_Textarea_Control( $wp_customize, 'header_scripts', array(
		'label'      => __( 'Header Scripts', 'responsive-tabs' ),
		'section'    => 'css_scripts_section',
		'settings'   => 'header_scripts',
	   'priority'   => 20,
	) ) );

	
	$wp_customize->add_control( new Responsive_Tabs_Textarea_Control( $wp_customize, 'footer_scripts', array(
		'label'      => __( 'Footer Scripts', 'responsive-tabs' ),
		'section'    => 'css_scripts_section',
		'settings'   => 'footer_scripts',
	   'priority'   => 30,
	) ) );
	

	/* disable_infinite_scroll */

	$wp_customize->add_control( 'disable_infinite_scroll_global', array(
	    'settings' => 'disable_infinite_scroll_global',
	    'label'    => __( '(Controls main, not widget, queries.)', 'responsive-tabs' ),
	    'section'  => 'disable_infinite_scroll_global_section',
	    'type'     => 'checkbox',
	    'priority'	=>	10,
	) );

	$wp_customize->add_control( 'disable_infinite_scroll_comments', array(
	    'settings' => 'disable_infinite_scroll_comments',
	    'label'    => __( '(Controls comments on posts, pages.)', 'responsive-tabs' ),
	    'section'  => 'disable_infinite_scroll_global_section',
	    'type'     => 'checkbox',
	    'priority'	=>	20,
	) );


	/* end of controls section */
}

add_action('customize_register', 'responsive_tabs_theme_customizer');
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
	
	/* short title for mobile added to main site info section*/
	
	$wp_customize->add_setting( 'site_short_title', array(
	    'default' => __( 'Set mobile short title', 'responsive-tabs' ),
	    'sanitize_callback' => 'sanitize_text_field',
	) );
	
	/* front page headline */
	
	$wp_customize->add_section( 'responsive_tabs_highlight' , array(
	    'title'      => __( 'Front Page Highlight', 'responsive-tabs' ),
	    'priority'   => 30,
  	    'description' => 'Enter additional branding information or short a message to highlight for front page users.  Or make these areas blank to just show a color bar. 
  	    	You can also put a widget in the highlight area.  Set Colors and Fonts (not applicable to widget content) in the Colors and Fonts sections of the customizer. 
	    	<a href="http://responsive-tabs-wordpress-theme.com/">More help &raquo;</a>' 
	) );
	
	$wp_customize->add_setting( 'highlight_headline', array(
	    'default' => __( 'Highlight Headline','responsive-tabs' ),
	    'sanitize_callback' => 'wp_kses_post'
	) );
	
	$wp_customize->add_setting( 'highlight_subhead', array(
	    'default' => __( 'Highlight Subhead', 'responsive-tabs' ),
	    'sanitize_callback' => 'wp_kses_post'
	) );
	
	$wp_customize->add_setting( 'highlight_headline_small_screen', array(
	    'default' => __( 'Highlight Headline Small Screen', 'responsive-tabs' ),
	    'sanitize_callback' => 'wp_kses_post'
	) );
	
	/* tab titles */
	$wp_customize->add_section( 'tab_titles_section' , array(
	    'title'      => __( 'Tab Titles', 'responsive-tabs' ),
	    'priority'   => 35,
	    'description' => 'Enter tab titles separated by commas, like so:<code>Favorites, Latest Posts, Comments</code>. 
	    	Then enter content for each tab widget.  The widget for a tab shows below in this menu  when you click on the tab title.
	    	<a href="http://responsive-tabs-wordpress-theme.com/">More help &raquo;</a>' , 
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
	
	$wp_customize->add_setting( 'highlight_color', array(
	    'default' => '#436A88',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'highlight_headline_color', array(
	    'default' => '#fff',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'highlight_headline_link_color', array(
	    'default' => '#fff',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'highlight_headline_link_hover_color', array(
	    'default' => '#ccc',
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

	$wp_customize->add_setting( 'welcome_splash_background_color', array(
	    'default' => '#c6c2ba',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'welcome_splash_body_color', array(
	    'default' => '#ffffff',
	    'sanitize_callback' => 'sanitize_hex_color'
	) );
	
	$wp_customize->add_setting( 'welcome_splash_border_color', array(
	    'default' => '#909090',
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
	
	$wp_customize->add_setting( 'highlight_headline_font_size_small_screen', array(
	    'default' => '24px',
	    'sanitize_callback' => 'sanitize_text_field'
	) );
	
	/* login links in sidemenu bar */
	
	$wp_customize->add_setting( 'show_login_links', array(
	    'default' => true,
	    'sanitize_callback' => 'responsive_tabs_sanitize_boolean',
	) );
	
	/* breadcrumbs control -- overridden if major breadcrumb plugin is installed */

	$wp_customize->add_setting( 'show_breadcrumbs', array(
	    'default' => true,
	    'sanitize_callback' => 'responsive_tabs_sanitize_boolean', 
	) );
	
	$wp_customize->add_setting( 'suppress_bbpress_breadcrumbs', array(
	    'default' => true,
	    'sanitize_callback' => 'responsive_tabs_sanitize_boolean',
	) );

	$wp_customize->add_setting( 'category_home' , array(
	    'default' => '0',
	   'sanitize_callback' => 'sanitize_text_field'
	) );

	$wp_customize->add_setting( 'date_home' , array(
	    'default' => '0',
	   'sanitize_callback' => 'sanitize_text_field'
	) );

	$wp_customize->add_setting( 'author_home' , array(
	    'default' => '0',
	   'sanitize_callback' => 'sanitize_text_field'
	) );	

	$wp_customize->add_setting( 'search_home' , array(
	    'default' => '0',
	   'sanitize_callback' => 'sanitize_text_field'
	) );	
	
	$wp_customize->add_setting( 'tag_home' , array(
	    'default' => '0',
	   'sanitize_callback' => 'sanitize_text_field'
	) );	

	$wp_customize->add_setting( 'page_home' , array(
	    'default' => '0',
	   'sanitize_callback' => 'sanitize_text_field'
	) );
	
	/* accordions */
	$wp_customize->add_section( 'footer_accordions_section' , array(
	    'title'      	=> __( 'Footer Accordions', 'responsive-tabs' ),
	    'priority'   	=> 100,
	    'description'	=> __( 'Enter ID numbers of Posts or Pages separated by commas, like so <code>348,11,592</code>. 
	    						Titles will be accordion titles.  Content will appear when clicked.  
	    						<a href="http://responsive-tabs-wordpress-theme.com/">More help &raquo;</a>', 'responsive-tabs' ),
	) );	
		
	$wp_customize->add_setting( 'front_page_accordion', array(
	    'sanitize_callback' => 'responsive_tabs_clean_post_list'
	) );
		
	$wp_customize->add_setting( 'page_accordion', array(
	    'sanitize_callback' => 'responsive_tabs_clean_post_list'
	) );
	
	$wp_customize->add_setting( 'post_accordion', array(
	    'sanitize_callback' => 'responsive_tabs_clean_post_list'
	) );
	
	$wp_customize->add_setting( 'archive_accordion', array(
	    'sanitize_callback' => 'responsive_tabs_clean_post_list'
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
	
	/* welcome splash page */
	$wp_customize->add_section( 'welcome_splash_page_section' , array(
	    'title'      => __( 'Welcome Splash Page', 'responsive-tabs' ),
	    'priority'   => 210,
	    'description' => __( 'These switches control whether widgets in the Site Info Splash widget area are shown.', 'responsive-tabs' ) 
	) );	

	$wp_customize->add_setting( 'welcome_splash_site_info_on', array(
	    'default' => 0,
	    'sanitize_callback' => 'responsive_tabs_sanitize_boolean',
	) );

	$wp_customize->add_setting( 'welcome_splash_on', array(
	    'default' => 0,
	    'sanitize_callback' => 'responsive_tabs_sanitize_boolean',
	) );

	$wp_customize->add_setting( 'welcome_splash_expire', array(
	    'default' => 365,
	    'sanitize_callback' => 'responsive_tabs_int_greater_than_zero'
	) );

	$wp_customize->add_setting( 'welcome_splash_delay', array(
	    'default' => 365,
	    'sanitize_callback' => 'responsive_tabs_int_greater_than_zero'
	) );

		
	$wp_customize->add_setting( 'welcome_splash_alert_class', array(
	    'default' => '',
	    'sanitize_callback' => 'sanitize_text_field'
	) );	
	
	
	/* CONTROLS
	-------------------------------------------------------*/	
	
	/* short title control*/
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'site_short_title', array(
		'label'      => __( 'Site Short Title (for mobile)', 'responsive-tabs' ),
		'section'    => 'title_tagline',
		'settings'   => 'site_short_title',
	   'priority'   => 50
	) ) );
	
	/* highlight headlines */
	
	$wp_customize->add_control( new Responsive_Tabs_Textarea_Control( $wp_customize, 'highlight_headline', array(
		'label'      => __( 'Highlight Headline', 'responsive-tabs' ),
		'section'    => 'responsive_tabs_highlight',
		'settings'   => 'highlight_headline',
	   'priority'   => 1
	) ) );
	
	$wp_customize->add_control( new Responsive_Tabs_Textarea_Control( $wp_customize, 'highlight_subhead', array(
		'label'      => __( 'Highlight SubHead', 'responsive-tabs' ),
		'section'    => 'responsive_tabs_highlight',
		'settings'   => 'highlight_subhead',
	   'priority'   => 2
	) ) );
	
	$wp_customize->add_control( new Responsive_Tabs_Textarea_Control( $wp_customize, 'highlight_headline_small_screen', array(
		'label'      => __( 'Highlight Headline Small Screen', 'responsive-tabs' ),
		'section'    => 'responsive_tabs_highlight',
		'settings'   => 'highlight_headline_small_screen',
	   'priority'   => 2
	) ) );

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

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'highlight_color', array(
		'label'     => __( 'Highlight Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'highlight_color',
		'priority'	=> 40,
	) ) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'highlight_headline_color', array(
		'label'     => __( 'Headline Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'highlight_headline_color',
		'priority' 	=> 50,
	) ) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'highlight_headline_link_color', array(
		'label'     => __( 'Headline Link Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'highlight_headline_link_color',
		'priority' 	=> 52,
	) ) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'highlight_headline_link_hover_color', array(
		'label'     => __( 'Headline Link Hover Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'highlight_headline_link_hover_color',
		'priority' 	=> 54,
	) ) );

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
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, '', array(
		'label'     => __( 'Welcome Splash Background Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'welcome_splash_background_color',
		'priority'	=> 108,
	) ) );  

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'welcome_splash_body_color', array(
		'label'     => __( 'Welcome Splash Body Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'welcome_splash_body_color',
		'priority'	=> 109,
	) ) );  
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'welcome_splash_border_color', array(
		'label'     => __( 'Welcome Splash Border Color', 'responsive-tabs' ),
		'section'   => 'colors',
		'settings'  => 'welcome_splash_border_color',
		'priority'	=> 110,
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
	
	$wp_customize->add_control( 'highlight_headline_font_size_small_screen', array(
	    'label'   	=> __( 'Highlight Headline Font Size -- Screen < 840px', 'responsive-tabs' ),
	    'section' 	=> 'responsive_tabs_fonts',
	    'type'    	=> 'select',
	    'settings' => 'highlight_headline_font_size_small_screen',
	    'choices'  => $font_size_array,
	    'priority'	=> 60,
	) );
		
	/* login link control */
	
	$wp_customize->add_control( 'show_login_links', array(
	    'settings' => 'show_login_links',
	    'label'    => __( 'Show Login Links in Main Menu', 'responsive-tabs' ),
	    'section'  => 'nav',
	    'type'     => 'checkbox',
	    'priority'	=>	30,
	) );

	/* breadcrumb controls */
	
	$wp_customize->add_control( 'show_breadcrumbs', array(
	    'settings' => 'show_breadcrumbs',
	    'label'    => __( 'Show Theme Breadcrumbs', 'responsive-tabs' ),
	    'section'  => 'nav',
	    'type'     => 'checkbox',
	    'priority'	=> 40
	) );
		
	$wp_customize->add_control( 'category_home', array(
	    'label'   	=> __( 'Home tab for category breadcrumb', 'responsive-tabs' ),
	    'section' 	=> 'nav',
	    'type'    	=> 'select',
	    'settings' => 'category_home',
	    'choices'  => $landing_tab_options_array,
	    'priority'	=> 50
	) );

	$wp_customize->add_control( 'date_home', array(
	    'label'   	=> __( 'Home tab for date breadcrumb', 'responsive-tabs' ),
	    'section' 	=> 'nav',
	    'type'    	=> 'select',
	    'settings' => 'date_home',
	    'choices'  => $landing_tab_options_array,
	    'priority'	=> 55,
	) );

	$wp_customize->add_control( 'author_home', array(
	    'label'   	=> __( 'Home tab for author breadcrumb', 'responsive-tabs' ),
	    'section' 	=> 'nav',
	    'type'    	=> 'select',
	    'settings' => 'author_home',
	    'choices'  => $landing_tab_options_array,
	    'priority' => 60,
	) );

	$wp_customize->add_control( 'search_home', array(
	    'label'   	=> __( 'Home tab for search breadcrumb', 'responsive-tabs' ),
	    'section' 	=> 'nav',
	    'type'    	=> 'select',
	    'settings'	=> 'search_home',
	    'choices' 	=> $landing_tab_options_array,
	    'priority'	=> 70,
	) );

	$wp_customize->add_control( 'tag_home', array(
	    'label'   	=> __( 'Home tab for tag breadcrumb', 'responsive-tabs' ),
	    'section' 	=> 'nav',
	    'type'    	=> 'select',
	    'settings' => 'tag_home',
	    'choices'  => $landing_tab_options_array,
	    'priority'	=> 75,
	) );

	$wp_customize->add_control( 'page_home', array(
	    'label'   	=> __( 'Home tab for page breadcrumb', 'responsive-tabs' ),
	    'section' 	=> 'nav',
	    'type'    	=> 'select',
	    'settings' => 'page_home',
	    'choices'  => $landing_tab_options_array,
	    'priority'	=>	80,
	) );

	$wp_customize->add_control( 'suppress_bbpress_breadcrumbs', array(
	    'settings' => 'suppress_bbpress_breadcrumbs',
	    'label'    => __( 'Suppress bbPress Breadcrumbs', 'responsive-tabs' ),
	    'section'  => 'nav',
	    'type'     => 'checkbox',
	    'priority'	=> 100,
	) );

	/* footer accordion controls */
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'front_page_accordion', array(
		'label'      => __( 'Front page', 'responsive-tabs' ),
		'section'    => 'footer_accordions_section',
		'settings'   => 'front_page_accordion',
	   'priority'   => 10,
	) ) );	
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'page_accordion', array(
		'label'      => __( 'Other pages', 'responsive-tabs' ),
		'section'    => 'footer_accordions_section',
		'settings'   => 'page_accordion',
	   'priority'   => 20,
	) ) );	
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'post_accordion', array(
		'label'      => __( 'Posts', 'responsive-tabs' ),
		'section'    => 'footer_accordions_section',
		'settings'   => 'post_accordion',
	   'priority'   => 30,
	) ) );	
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'archive_accordion', array(
		'label'      => __( 'Archive Listings', 'responsive-tabs' ),
		'section'    => 'footer_accordions_section',
		'settings'   => 'archive_accordion',
	   'priority'   => 40,
	) ) );

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
	
	/* welcome splash page */

	$wp_customize->add_control( 'welcome_splash_site_info_on', array(
	    'settings' => 'welcome_splash_site_info_on',
	    'label'    => __( 'Show site info as drop down under "?" in menu bar ', 'responsive-tabs' ),
	    'section'  => 'welcome_splash_page_section',
	    'type'     => 'checkbox',
	    'priority'	=>	10,
	) );
	
	$wp_customize->add_control( 'welcome_splash_on', array(
	    'settings' => 'welcome_splash_on',
	    'label'    => __( 'Show site info as welcome splash', 'responsive-tabs' ),
	    'section'  => 'welcome_splash_page_section',
	    'type'     => 'checkbox',
	    'priority'	=>	20,
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'welcome_splash_delay', array(
		'label'      => __( 'Display to visitor if absent for (days)' , 'responsive-tabs' ),
		'section'    => 'welcome_splash_page_section',
		'settings'   => 'welcome_splash_delay',
	   'priority'   => 30,
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'welcome_splash_expire', array(
		'label'      => __( 'Forget visitor after (days)', 'responsive-tabs' ),
		'section'    => 'welcome_splash_page_section',
		'settings'   => 'welcome_splash_expire',
	   'priority'   => 40,
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'welcome_splash_alert_class', array(
		'label'      => __( 'Enter a css classname which, if present as form response in the splash page, should force display of the page.' , 'responsive-tabs' ),
		'section'    => 'welcome_splash_page_section',
		'settings'   => 'welcome_splash_alert_class',
	   'priority'   => 50,
	) ) );


}

add_action('customize_register', 'responsive_tabs_theme_customizer');
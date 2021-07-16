<?php
/**
 * XShop Theme Customizer
 *
 * @package XShop
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function xshop_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	//select sanitization function
	function xshop_sanitize_select( $input, $setting ){
		$input = sanitize_key($input);
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                
		  
	}

	$wp_customize->add_panel( 'xshop_settings', array(
		 'priority'       => 50,
		  'title'          => __('XShop Theme settings', 'xshop'),
		  'description'    => __('All XShop theme settings', 'xshop'),
		  ) );
    $wp_customize->add_section('xshop_header', array(
		'title' => __('XShop Header Settings', 'xshop'),
		'capability'     => 'edit_theme_options',
		'description'     => __('XShop theme header settings', 'xshop'),
		'panel'    => 'xshop_settings',
	
	));
	$wp_customize->add_setting('xshop_header_address1', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('xshop_header_address1', array(
        'label'      => __('Header Address One', 'xshop'),
        'section'    => 'xshop_header',
        'settings'   => 'xshop_header_address1',
        'type'       => 'text',
    ));
	$wp_customize->add_setting('xshop_header_address2', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('xshop_header_address2', array(
        'label'      => __('Header Address Two', 'xshop'),
        'section'    => 'xshop_header',
        'settings'   => 'xshop_header_address2',
        'type'       => 'text',
    ));
	//xshop blog settings
    $wp_customize->add_section('xshop_blog', array(
		'title' => __('XShop Blog Settings', 'xshop'),
		'capability'     => 'edit_theme_options',
		'description'     => __('XShop theme blog settings', 'xshop'),
		'panel'    => 'xshop_settings',
	
	));
	$wp_customize->add_setting('xshop_blog_container', array(
        'default'        => 'container',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'xshop_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('xshop_blog_container', array(
        'label'      => __('Container type', 'xshop'),
        'description'=> __('You can set standard container or full width container. ', 'xshop'),
        'section'    => 'xshop_blog',
        'settings'   => 'xshop_blog_container',
        'type'       => 'select',
        'choices'    => array(
            'container' => __('Standard Container', 'xshop'),
            'container-fluid' => __('Full width Container', 'xshop'),
        ),
    ));
    $wp_customize->add_setting('xshop_blog_layout', array(
        'default'        => 'rightside',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'xshop_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('xshop_blog_layout', array(
        'label'      => __('Select Blog Layout', 'xshop'),
        'description'=> __('Right and Left sidebar only show when sidebar widget is available. ', 'xshop'),
        'section'    => 'xshop_blog',
        'settings'   => 'xshop_blog_layout',
        'type'       => 'select',
        'choices'    => array(
            'rightside' => __('Right Sidebar', 'xshop'),
            'leftside' => __('Left Sidebar', 'xshop'),
            'fullwidth' => __('No Sidebar', 'xshop'),
        ),
    ));
	$wp_customize->add_setting('xshop_blog_style', array(
        'default'        => 'grid',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'xshop_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('xshop_blog_style', array(
        'label'      => __('Select Blog Style', 'xshop'),
        'section'    => 'xshop_blog',
        'settings'   => 'xshop_blog_style',
        'type'       => 'select',
        'choices'    => array(
            'grid' => __('Grid Style', 'xshop'),
            'classic' => __('Classic Style', 'xshop'),
        ),
    ));
		//xshop page settings
		$wp_customize->add_section('xshop_page', array(
			'title' => __('XShop Page Settings', 'xshop'),
			'capability'     => 'edit_theme_options',
			'description'     => __('XShop theme blog settings', 'xshop'),
			'panel'    => 'xshop_settings',
		
		));
		$wp_customize->add_setting('xshop_page_container', array(
			'default'        => 'container',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'sanitize_callback' => 'xshop_sanitize_select',
			'transport' => 'refresh',
		));
		$wp_customize->add_control('xshop_page_container', array(
			'label'      => __('Page Container type', 'xshop'),
			'description'=> __('You can set standard container or full width container for page. ', 'xshop'),
			'section'    => 'xshop_page',
			'settings'   => 'xshop_page_container',
			'type'       => 'select',
			'choices'    => array(
				'container' => __('Standard Container', 'xshop'),
				'container-fluid' => __('Full width Container', 'xshop'),
			),
		));	
		$wp_customize->add_setting('xshop_page_header', array(
			'default'        => 'show',
			'capability'     => 'edit_theme_options',
			'type'           => 'theme_mod',
			'sanitize_callback' => 'xshop_sanitize_select',
			'transport' => 'refresh',
		));
		$wp_customize->add_control('xshop_page_header', array(
			'label'      => __('Show Page header', 'xshop'),
			'section'    => 'xshop_page',
			'settings'   => 'xshop_page_header',
			'type'       => 'select',
			'choices'    => array(
				'show' => __('Show all pages', 'xshop'),
				'hide-home' => __('Hide Only Front Page', 'xshop'),
				'hide' => __('Hide All Pages', 'xshop'),
			),
		));	




	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'xshop_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'xshop_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'xshop_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function xshop_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function xshop_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function xshop_customize_preview_js() {
	wp_enqueue_script( 'xshop-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), xshop_VERSION, true );
}
add_action( 'customize_preview_init', 'xshop_customize_preview_js' );

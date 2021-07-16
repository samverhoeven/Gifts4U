<?php
/**
 * XShop woocommerce Customizer
 *
 * @package XShop
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function xshop_shop_customize_register( $wp_customize ) {

	//xshop blog settings
    $wp_customize->add_section('xshop_woo', array(
		'title' => __('XShop Shop Settings', 'xshop'),
		'capability'     => 'edit_theme_options',
		'description'     => __('XShop theme shop settings', 'xshop'),
		'panel'    => 'xshop_settings',
	
	));
	$wp_customize->add_setting('xshop_woo_container', array(
        'default'        => 'container',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'xshop_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('xshop_woo_container', array(
        'label'      => __('Shop Container Type', 'xshop'),
        'description'=> __('You can set standard container or full width container for shop page. ', 'xshop'),
        'section'    => 'xshop_woo',
        'settings'   => 'xshop_woo_container',
        'type'       => 'select',
        'choices'    => array(
            'container' => __('Standard Container', 'xshop'),
            'container-fluid' => __('Full width Container', 'xshop'),
        ),
    ));
    $wp_customize->add_setting('xshop_woo_layout', array(
        'default'        => 'rightside',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'xshop_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('xshop_woo_layout', array(
        'label'      => __('Select Shop Layout', 'xshop'),
        'description'=> __('Right and Left sidebar only show when sidebar widget is available. ', 'xshop'),
        'section'    => 'xshop_woo',
        'settings'   => 'xshop_woo_layout',
        'type'       => 'select',
        'choices'    => array(
            'rightside' => __('Right Sidebar', 'xshop'),
            'leftside' => __('Left Sidebar', 'xshop'),
            'fullwidth' => __('No Sidebar', 'xshop'),
        ),
    ));
	
	
}
add_action( 'customize_register', 'xshop_shop_customize_register' );

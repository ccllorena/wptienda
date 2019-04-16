<?php
/**
 * Easy Store General Settings panel at Theme Customizer
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

add_action( 'customize_register', 'easy_store_general_settings_register' );

function easy_store_general_settings_register( $wp_customize ) {

	$wp_customize->get_section( 'title_tagline' )->panel = 'easy_store_general_settings_panel';
    $wp_customize->get_section( 'title_tagline' )->priority = '5';
    $wp_customize->get_section( 'colors' )->panel    = 'easy_store_general_settings_panel';
    $wp_customize->get_section( 'colors' )->priority = '10';
    $wp_customize->get_section( 'background_image' )->panel = 'easy_store_general_settings_panel';
    $wp_customize->get_section( 'background_image' )->priority = '15';

    /**
     * Add General Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'easy_store_general_settings_panel',
	    array(
	        'priority'       => 5,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'General Settings', 'easy-store' ),
	    )
    );

/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Checkbox for show home content
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_homepage_content_status',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => true,
            'sanitize_callback' => 'easy_store_sanitize_checkbox',
        )
    );
    $wp_customize->add_control(
        'easy_store_homepage_content_status',
        array(
            'label'         => __( 'Show HomePage Content', 'easy-store' ),
            'description'   => __( 'Check this to show page content in Home page.', 'easy-store' ),
            'section'       => 'static_front_page',
            'settings'      => 'easy_store_homepage_content_status',
            'type'          => 'checkbox',
            'priority'      => 15
        )
    );

/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Color option for primary theme color
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_primary_theme_color',
        array(
            'default'     => '#27B6D4',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    ); 
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
            'easy_store_primary_theme_color',
            array(
                'label'      => __( 'Primary Theme Color', 'easy-store' ),
                'section'    => 'colors',
                'settings'   => 'easy_store_primary_theme_color',
                'priority'   => 5
            )
        )
    );

    /**
     * Color option for secondary theme color
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_secondary_theme_color',
        array(
            'default'     => '#DD1F26',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    ); 
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
            'easy_store_secondary_theme_color',
            array(
                'label'      => __( 'Secondary Theme Color', 'easy-store' ),
                'section'    => 'colors',
                'settings'   => 'easy_store_secondary_theme_color',
                'priority'   => 5
            )
        )
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Site layout
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_site_layout_section',
        array(
            'priority'       => 50,
            'panel'          => 'easy_store_general_settings_panel',
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => __( 'Site Layout', 'easy-store' )
        )
    );

    /**
     * Select field for site layout
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_site_layout',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => 'fullwidth',
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control(
        'easy_store_site_layout',
        array(
            'label'         => __( 'Website Layout', 'easy-store' ),
            'description'   => __( 'Choose layout for entire website.', 'easy-store' ),
            'section'       => 'easy_store_site_layout_section',
            'settings'      => 'easy_store_site_layout',
            'type'          => 'select',
            'priority'      => 5,
            'choices'       => array(
                'boxed'     => __( 'Boxed Layout', 'easy-store' ),
                'fullwidth' => __( 'FullWidth Layout', 'easy-store' )
            )
        )
    );

}
<?php
/**
 * Easy Store Design Settings panel.
 *
 * This file contains all innerpages design related like archive, page, post layouts and their sidebars
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

add_action( 'customize_register', 'easy_store_design_settings_register' );

function easy_store_design_settings_register( $wp_customize ) {

	// Register the radio image control class as a JS control type.
    $wp_customize->register_control_type( 'Easy_Store_Customize_Control_Radio_Image' );

	/**
     * Add Design Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'easy_store_design_settings_panel',
	    array(
	        'priority'       => 20,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'Design Settings', 'easy-store' ),
	    )
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Archive Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_archive_settings_section',
        array(
            'priority'  	 => 5,
            'panel'     	 => 'easy_store_design_settings_panel',
            'capability'     => 'edit_theme_options',
        	'theme_supports' => '',
            'title'     	 => __( 'Archive Settings', 'easy-store' )
        )
    );      

    /**
     * Image Radio field for archive sidebar
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_archive_sidebar',
        array(
        	'capability'     	=> 'edit_theme_options',
            'default'           => 'right_sidebar',
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Customize_Control_Radio_Image(
        $wp_customize,
        'easy_store_archive_sidebar',
            array(
                'label'			=> __( 'Archive Sidebars', 'easy-store' ),
                'description' 	=> __( 'Choose sidebar from available layouts', 'easy-store' ),
                'section'  		=> 'easy_store_archive_settings_section',
                'settings'		=> 'easy_store_archive_sidebar',
                'priority' 		=> 5,
                'choices'  		=> array(
                    'left_sidebar' 		=> array(
                        'label' 		=> __( 'Left Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/left-sidebar.png'
                    ),
                    'right_sidebar' 	=> array(
                        'label' 		=> __( 'Right Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/right-sidebar.png'
                    ),
                    'no_sidebar'		=> array(
                        'label' 		=> __( 'No Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/no-sidebar.png'
                    ),
                    'no_sidebar_center' => array(
                        'label' 		=> __( 'No Sidebar Center', 'easy-store' ),
                        'url'   		=> '%s/assets/images/no-sidebar-center.png'
                    )
    			)
            )
        )
    );

    /**
     * Text field for archive read more
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_archive_read_more',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => __( 'Read More', 'easy-store' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'easy_store_archive_read_more',
        array(
            'label'         => __( 'Read More Text', 'easy-store' ),
            'section'       => 'easy_store_archive_settings_section',
            'settings'      => 'easy_store_archive_read_more',
            'type'          => 'text',
            'priority'      => 15
        )
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Page Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_page_settings_section',
        array(
            'priority'  	 => 10,
            'panel'     	 => 'easy_store_design_settings_panel',
            'capability'     => 'edit_theme_options',
        	'theme_supports' => '',
            'title'     	 => __( 'Page Settings', 'easy-store' )
        )
    );      

    /**
     * Image Radio field for page sidebar
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_global_page_sidebar',
        array(
        	'capability'     	=> 'edit_theme_options',
            'default'           => 'right_sidebar',
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Customize_Control_Radio_Image(
        $wp_customize,
        'easy_store_global_page_sidebar',
            array(
                'label'			=> __( 'Page Sidebars', 'easy-store' ),
                'description' 	=> __( 'Choose sidebar from available layouts', 'easy-store' ),
                'section'  		=> 'easy_store_page_settings_section',
                'settings'		=> 'easy_store_global_page_sidebar',
                'priority' 		=> 5,
                'choices'  		=> array(
                    'left_sidebar' 		=> array(
                        'label' 		=> __( 'Left Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/left-sidebar.png'
                    ),
                    'right_sidebar' 	=> array(
                        'label' 		=> __( 'Right Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/right-sidebar.png'
                    ),
                    'no_sidebar'		=> array(
                        'label' 		=> __( 'No Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/no-sidebar.png'
                    ),
                    'no_sidebar_center' => array(
                        'label' 		=> __( 'No Sidebar Center', 'easy-store' ),
                        'url'   		=> '%s/assets/images/no-sidebar-center.png'
                    )
    			)
            )
        )
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Post Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_post_settings_section',
        array(
            'priority'  	 => 10,
            'panel'     	 => 'easy_store_design_settings_panel',
            'capability'     => 'edit_theme_options',
        	'theme_supports' => '',
            'title'     	 => __( 'Post Settings', 'easy-store' )
        )
    );      

    /**
     * Image Radio field for post sidebar
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_global_post_sidebar',
        array(
        	'capability'     	=> 'edit_theme_options',
            'default'           => 'right_sidebar',
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Customize_Control_Radio_Image(
        $wp_customize,
        'easy_store_global_post_sidebar',
            array(
                'label'			=> __( 'Post Sidebars', 'easy-store' ),
                'description' 	=> __( 'Choose sidebar from available layouts', 'easy-store' ),
                'section'  		=> 'easy_store_post_settings_section',
                'settings'		=> 'easy_store_global_post_sidebar',
                'priority' 		=> 5,
                'choices'  		=> array(
                    'left_sidebar' 		=> array(
                        'label' 		=> __( 'Left Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/left-sidebar.png'
                    ),
                    'right_sidebar' 	=> array(
                        'label' 		=> __( 'Right Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/right-sidebar.png'
                    ),
                    'no_sidebar'		=> array(
                        'label' 		=> __( 'No Sidebar', 'easy-store' ),
                        'url'   		=> '%s/assets/images/no-sidebar.png'
                    ),
                    'no_sidebar_center' => array(
                        'label' 		=> __( 'No Sidebar Center', 'easy-store' ),
                        'url'   		=> '%s/assets/images/no-sidebar-center.png'
                    )
    			)
            )
        )
    );

}
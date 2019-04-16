<?php
/**
 * Easy Store Footer Settings panel.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

add_action( 'customize_register', 'easy_store_footer_settings_register' );

function easy_store_footer_settings_register( $wp_customize ) {

	/**
     * Add Footer Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'easy_store_footer_settings_panel',
	    array(
	        'priority'       => 25,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'Footer Settings', 'easy-store' ),
	    )
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Footer Widgets Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_footer_widget_section',
        array(
            'priority'  	 => 5,
            'panel'     	 => 'easy_store_footer_settings_panel',
            'capability'     => 'edit_theme_options',
        	'theme_supports' => '',
            'title'     	 => __( 'Footer Widget Area', 'easy-store' )
        )
    );

    /**
     * Image Radio field for footer widget column
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_footer_widget_column',
        array(
        	'capability'     	=> 'edit_theme_options',
            'default'           => 'columns_three',
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Customize_Control_Radio_Image(
        $wp_customize,
        'easy_store_footer_widget_column',
            array(
                'label'			=> __( 'Widget Area Column', 'easy-store' ),
                'description' 	=> __( 'Choose number of column at footer widget area.', 'easy-store' ),
                'section'  		=> 'easy_store_footer_widget_section',
                'settings'		=> 'easy_store_footer_widget_column',
                'priority' 		=> 5,
                'choices'  		=> array(
                    'column_one' 		=> array(
                        'label' 		=> __( 'One Column', 'easy-store' ),
                        'url'   		=> '%s/assets/images/footer-1.png'
                    ),
                    'columns_two' 	=> array(
                        'label' 		=> __( 'Two Columns', 'easy-store' ),
                        'url'   		=> '%s/assets/images/footer-2.png'
                    ),
                    'columns_three'		=> array(
                        'label' 		=> __( 'Three Columns', 'easy-store' ),
                        'url'   		=> '%s/assets/images/footer-3.png'
                    ),
                    'columns_four' => array(
                        'label' 		=> __( 'Four Columns', 'easy-store' ),
                        'url'   		=> '%s/assets/images/footer-4.png'
                    )
    			)
            )
        )
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Bottom Footer Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_bottom_footer_section',
        array(
            'priority'       => 10,
            'panel'          => 'easy_store_footer_settings_panel',
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => __( 'Bottom Footer', 'easy-store' )
        )
    );
    
    /**
     * Text field for copyright text
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_copyright_text',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => __( 'Easy Store', 'easy-store' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'easy_store_copyright_text',
        array(
            'label'         => __( 'Copyright Text', 'easy-store' ),
            'section'       => 'easy_store_bottom_footer_section',
            'settings'      => 'easy_store_copyright_text',
            'type'          => 'text',
            'priority'      => 5
        )
    );
}
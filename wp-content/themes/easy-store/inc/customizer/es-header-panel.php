<?php
/**
 * Easy Store Header Settings panel at Theme Customizer
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

add_action( 'customize_register', 'easy_store_header_settings_register' );

function easy_store_header_settings_register( $wp_customize ) {

    $wp_customize->get_section( 'header_image' )->priority = '20';
    $wp_customize->get_section( 'header_image' )->title    = __( 'Innerpages Header Image', 'easy-store' );
    $wp_customize->get_section( 'header_image' )->panel    = 'easy_store_header_settings_panel';

	/**
     * Add Header Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'easy_store_header_settings_panel',
	    array(
	        'priority'       => 10,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'Header Settings', 'easy-store' ),
	    )
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
	/**
     * Top Header section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_top_header_section',
        array(
        	'priority'       => 5,
        	'panel'          => 'easy_store_header_settings_panel',
        	'capability'     => 'edit_theme_options',
        	'theme_supports' => '',
            'title'          => __( 'Top Header Section', 'easy-store' ),
            'description'    => __( 'Managed the content display at top header section.', 'easy-store' ),
        )
    );

    /**
     * Switch option for Top Header
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_top_header_option',
        array(
        	'capability'     	=> 'edit_theme_options',
            'default' 			=> 'show',
            'sanitize_callback' => 'easy_store_sanitize_switch_option',
        )
    );
    $wp_customize->add_control( new Easy_Store_Customize_Switch_Control(
        $wp_customize,
            'easy_store_top_header_option',
            array(
                'label'     	=> __( 'Top Header Option', 'easy-store' ),
                'description'   => __( 'Show/Hide option for top header section.', 'easy-store' ),
                'section'   	=> 'easy_store_top_header_section',
                'settings'		=> 'easy_store_top_header_option',
                'type'      	=> 'switch',
                'priority'  	=> 5,
                'choices'   	=> array(
                    'show'  		=> __( 'Show', 'easy-store' ),
                    'hide'  		=> __( 'Hide', 'easy-store' )
                )
            )
        )
    );

    /**
     * Repeater field for top header items
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 
        'easy_store_top_header_items', 
        array(
            'capability'        => 'edit_theme_options',            
            'default'           => json_encode(array(
                    array(
                        'mt_item_icon' => 'fa fa-map-marker',
                        'mt_item_text' => '',
                    )
                )
            ),
            'sanitize_callback' => 'easy_store_sanitize_repeater'
        )
    );
    $wp_customize->add_control( new Easy_Store_Repeater_Controler(
        $wp_customize, 
            'easy_store_top_header_items', 
            array(
                'label'           => __( 'Top Header Items', 'easy-store' ),
                'section'         => 'easy_store_top_header_section',
                'settings'        => 'easy_store_top_header_items',
                'priority'        => 10,
                'easy_store_box_label'       => __( 'Single Item','easy-store' ),
                'easy_store_box_add_control' => __( 'Add Item','easy-store' )
            ),
            array(
                'mt_item_icon' => array(
                    'type'        => 'icon',
                    'label'       => __( 'Item Icon', 'easy-store' ),
                    'description' => __( 'Choose icon for single item from available lists.', 'easy-store' )
                ),
                'mt_item_text' => array(
                    'type'        => 'text',
                    'label'       => __( 'Item Info', 'easy-store' ),
                    'description' => __( 'Enter short info for single item.', 'easy-store' )
                )
            )
        ) 
    );

    /**
     * Right side content type
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_top_header_right_content',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => 'social',
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control(
        'easy_store_top_header_right_content',
        array(
            'label'         => __( 'Top Header Right Content', 'easy-store' ),
            'description'   => __( 'Select suitable option for top header right side.', 'easy-store' ),
            'section'       => 'easy_store_top_header_section',
            'settings'      => 'easy_store_top_header_right_content',
            'type'          => 'select',
            'priority'      => 15,
            'choices'       => array(
                'social'        => __( 'Social Icon', 'easy-store' ),
                'menu'          => __( 'Menu', 'easy-store' )
            )
        )
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Extra Option
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_header_extra_option_section',
        array(
            'priority'       => 15,
            'panel'          => 'easy_store_header_settings_panel',
            'capability'     => 'edit_theme_options',
            'theme_supports' => '',
            'title'          => __( 'Extra Options', 'easy-store' ),
            'description'    => __( 'Managed the several extra option in header section.', 'easy-store' ),
        )
    );

    /**
     * Switch option for shopping cart icon
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_header_cart_option',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => 'show',
            'sanitize_callback' => 'easy_store_sanitize_switch_option',
        )
    );
    $wp_customize->add_control( new Easy_Store_Customize_Switch_Control(
        $wp_customize,
            'easy_store_header_cart_option',
            array(
                'label'         => __( 'Header Cart Option', 'easy-store' ),
                'description'   => __( 'Show/Hide option for shopping cart at header section.', 'easy-store' ),
                'section'       => 'easy_store_header_extra_option_section',
                'settings'      => 'easy_store_header_cart_option',
                'type'          => 'switch',
                'priority'      => 5,
                'choices'       => array(
                    'show'          => __( 'Show', 'easy-store' ),
                    'hide'          => __( 'Hide', 'easy-store' )
                )
            )
        )
    );

    /**
     * Switch option for WishtList icon
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_header_wishlist_option',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => 'show',
            'sanitize_callback' => 'easy_store_sanitize_switch_option',
        )
    );
    $wp_customize->add_control( new Easy_Store_Customize_Switch_Control(
        $wp_customize,
            'easy_store_header_wishlist_option',
            array(
                'label'         => __( 'Header WishtList Option', 'easy-store' ),
                'description'   => __( 'Show/Hide option for whishlist icon primary menu section.', 'easy-store' ),
                'section'       => 'easy_store_header_extra_option_section',
                'settings'      => 'easy_store_header_wishlist_option',
                'type'          => 'switch',
                'priority'      => 10,
                'choices'       => array(
                    'show'          => __( 'Show', 'easy-store' ),
                    'hide'          => __( 'Hide', 'easy-store' )
                )
            )
        )
    );

    /**
     * Text field for wishlist text
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_wishlist_text',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => __( 'Wishlist', 'easy-store' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'easy_store_wishlist_text',
        array(
            'label'         => __( 'WishList Text', 'easy-store' ),
            'section'       => 'easy_store_header_extra_option_section',
            'settings'      => 'easy_store_wishlist_text',
            'type'          => 'text',
            'priority'      => 15,
            'active_callback' => 'easy_store_wishlist_callback'
        )
    );
    
    /**
     * Switch option for primary menu sticky
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_primary_menu_sticky',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => 'show',
            'sanitize_callback' => 'easy_store_sanitize_switch_option',
        )
    );
    $wp_customize->add_control( new Easy_Store_Customize_Switch_Control(
        $wp_customize,
            'easy_store_primary_menu_sticky',
            array(
                'label'         => __( 'Sticky Option', 'easy-store' ),
                'description'   => __( 'Option to active primary menu sticky.', 'easy-store' ),
                'section'       => 'easy_store_header_extra_option_section',
                'settings'      => 'easy_store_primary_menu_sticky',
                'type'          => 'switch',
                'priority'      => 20,
                'choices'       => array(
                    'show'          => __( 'Enable', 'easy-store' ),
                    'hide'          => __( 'Disable', 'easy-store' )
                )
            )
        )
    );

}
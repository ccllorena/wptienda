<?php
/**
 * Easy Store Additional Settings panel at Theme Customizer
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

add_action( 'customize_register', 'easy_store_additional_settings_register' );

function easy_store_additional_settings_register( $wp_customize ) {

	/**
     * Add Additional Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'easy_store_additional_settings_panel',
	    array(
	        'priority'       => 15,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'Additional Settings', 'easy-store' ),
	    )
    );

/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Social Icons Section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_social_icons_section',
        array(
            'title'     => esc_html__( 'Social Icons', 'easy-store' ),
            'panel'     => 'easy_store_additional_settings_panel',
            'priority'  => 5,
        )
    );

    /**
     * Repeater field for social media icons
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 
        'social_media_icons', 
        array(
            'sanitize_callback' => 'easy_store_sanitize_repeater',
            'default' => json_encode(array(
                array(
                    'mt_item_social_icon' => 'fa fa-facebook-f',
                    'mt_item_url'         => '',
                    )
            ))
        )
    );
    $wp_customize->add_control( new Easy_Store_Repeater_Controler(
        $wp_customize, 
            'social_media_icons', 
            array(
                'label'             => __( 'Social Media Icons', 'easy-store' ),
                'section'           => 'easy_store_social_icons_section',
                'settings'          => 'social_media_icons',
                'priority'          => 5,
                'easy_store_box_label'       => __( 'Social Media Icon','easy-store' ),
                'easy_store_box_add_control' => __( 'Add Icon','easy-store' )
            ),
            array(
                'mt_item_social_icon' => array(
                    'type'        => 'social_icon',
                    'label'       => __( 'Social Media Logo', 'easy-store' ),
                    'description' => __( 'Choose social media icon.', 'easy-store' )
                ),
                'mt_item_url' => array(
                    'type'        => 'url',
                    'label'       => __( 'Social Icon Url', 'easy-store' ),
                    'description' => __( 'Enter social media url.', 'easy-store' )
                )
            )
        ) 
    );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * Promo section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_promo_section',
        array(
        	'priority'       => 15,
        	'panel'          => 'easy_store_additional_settings_panel',
        	'capability'     => 'edit_theme_options',
        	'theme_supports' => '',
            'title'          => __( 'Promo Section', 'easy-store' ),
            'description'    => '',
        )
    );

    /**
     * Repeater field for top header items
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 
        'easy_store_promo_items', 
        array(
            'capability'       => 'edit_theme_options',            
            'default'          => json_encode(array(
                    array(
                        'mt_item_icon'  => 'fa fa-star-o',
                        'mt_item_title' => '',
                        'mt_item_text'  => ''
                    )
                )
            ),
            'sanitize_callback' => 'easy_store_sanitize_repeater'
        )
    );
    $wp_customize->add_control( new Easy_Store_Repeater_Controler(
        $wp_customize, 
            'easy_store_promo_items', 
            array(
                'label'           => __( 'Promo Items', 'easy-store' ),
                'description'     => __( 'All promo items will be display via ES: Promo Items widget.', 'easy-store' ),
                'section'         => 'easy_store_promo_section',
                'settings'        => 'easy_store_promo_items',
                'priority'        => 5,
                'easy_store_box_label'       => __( 'Single Promo','easy-store' ),
                'easy_store_box_add_control' => __( 'Add Promo','easy-store' )
            ),
            array(
                'mt_item_icon' => array(
                    'type'        => 'icon',
                    'label'       => __( 'Promo Icon', 'easy-store' ),
                    'description' => __( 'Choose icon for single promo from available lists.', 'easy-store' )
                ),
                'mt_item_title' => array(
                    'type'        => 'text',
                    'label'       => __( 'Promo Title', 'easy-store' ),
                    'description' => __( 'Enter title for single promo.', 'easy-store' )
                ),
                'mt_item_text' => array(
                    'type'        => 'text',
                    'label'       => __( 'Promo Info', 'easy-store' ),
                    'description' => __( 'Enter short info for single promo.', 'easy-store' )
                )
            )
        ) 
    );

/*---------------------------------------------------------------------------------------------------------------*/
    /**
     * Sponsors Section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section(
        'easy_store_sponsors_section',
        array(
            'title'     => esc_html__( 'Our Sponsors', 'easy-store' ),
            'panel'     => 'easy_store_additional_settings_panel',
            'priority'  => 20,
        )
    );

    /**
     * Repeater field for sponsors
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 
        'our_sponsors', 
        array(
            'sanitize_callback' => 'easy_store_sanitize_repeater',
            'default' => json_encode(array(
                array(
                    'mt_item_upload' => '',
                )
            ))
        )
    );
    $wp_customize->add_control( new Easy_Store_Repeater_Controler(
        $wp_customize, 
            'our_sponsors', 
            array(
                'label'             => __( 'Our Sponsors', 'easy-store' ),
                'section'           => 'easy_store_sponsors_section',
                'settings'          => 'our_sponsors',
                'priority'          => 5,
                'easy_store_box_label'       => __( 'Single Sponsor','easy-store' ),
                'easy_store_box_add_control' => __( 'Add Sponsor','easy-store' ),
            ),
            array(
                'mt_item_upload' => array(
                    'type'        => 'upload',
                    'label'       => __( 'Sponsor Logo', 'easy-store' ),
                    'description' => __( 'Upload logo for sponsor.', 'easy-store' )
                )
            )
        )
    );

}
<?php
/**
 * Easy Store Theme Customizer
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function easy_store_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'easy_store_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'easy_store_customize_partial_blogdescription',
		) );
	}

	/**
     * Register custom section types.
     *
     * @since 1.0.5
     */
    $wp_customize->register_section_type( 'Easy_Store_Customize_Section_Upsell' );

    /**
     * Register theme upsell sections.
     *
     * @since 1.0.5
     */
    $wp_customize->add_section( new Easy_Store_Customize_Section_Upsell(
        $wp_customize,
            'theme_upsell',
            array(
                'title'    => esc_html__( 'Easy Store Pro', 'easy-store' ),
                'pro_text' => esc_html__( 'Buy Pro', 'easy-store' ),
                'pro_url'  => 'https://mysterythemes.com/wp-themes/easy-store-pro/',
                'priority'  => 1,
            )
        )
    );

}
add_action( 'customize_register', 'easy_store_customize_register' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function easy_store_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function easy_store_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function easy_store_customize_preview_js() {
	wp_enqueue_script( 'easy-store-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20180123', true );
}
add_action( 'customize_preview_init', 'easy_store_customize_preview_js' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue required scripts/styles for customizer panel
 *
 * @since 1.0.0
 */
function easy_store_customize_backend_scripts() {

    global $easy_store_version;

    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/library/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );
    
    wp_enqueue_style( 'easy_store_admin_customizer_style', get_template_directory_uri() . '/assets/css/es-customizer-style.css', array(), esc_attr( $easy_store_version ) );

    wp_enqueue_script( 'easy_store_admin_customizer', get_template_directory_uri() . '/assets/js/es-customizer-controls.js', array( 'jquery', 'customize-controls' ), esc_attr( $easy_store_version ), true );
}
add_action( 'customize_controls_enqueue_scripts', 'easy_store_customize_backend_scripts', 10 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Load customizer required panels.
 */
require get_template_directory() . '/inc/customizer/es-general-panel.php';
require get_template_directory() . '/inc/customizer/es-header-panel.php';
require get_template_directory() . '/inc/customizer/es-design-panel.php';
require get_template_directory() . '/inc/customizer/es-additional-panel.php';
require get_template_directory() . '/inc/customizer/es-footer-panel.php';


require get_template_directory() . '/inc/customizer/es-custom-classes.php';
require get_template_directory() . '/inc/customizer/es-customizer-sanitize.php';

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Active callback functions
 *
 * @since 1.0.0
 */

// callback function for wishlist text
function easy_store_wishlist_callback( $control ) {
    if ( $control->manager->get_setting( 'easy_store_header_wishlist_option' )->value() == 'show' ) {
        return true;
    } else {
        return false;
    }
}
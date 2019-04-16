<?php
/**
 * Files to managed the all function related to widgets
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 *
 */

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function easy_store_widgets_init() {
	
	/**
	 * register default sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'easy-store' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'easy-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	/**
	 * register left sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'easy-store' ),
		'id'            => 'left_sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'easy-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );


	/**
	 * register header area sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Header Area', 'easy-store' ),
		'id'            => 'header_area_sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'easy-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	/**
	 * Register home page section area
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Home Page Section', 'easy-store' ),
		'id'            => 'front_page_section_area',
		'description'   => esc_html__( 'Add widgets here.', 'easy-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="es-block-title">',
		'after_title'   => '</h2>',
	) );

	/**
	 * register shop sidebar
	 *
	 * @since 1.0.0
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'easy-store' ),
		'id'            => 'easy_store_shop_sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'easy-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	/**
	 * Register 4 different footer area 
	 *
	 * @since 1.0.0
	 */
	register_sidebars( 4 , array(
		'name'          => esc_html__( 'Footer %d', 'easy-store' ),
		'id'            => 'easy_store_footer_sidebar',
		'description'   => esc_html__( 'Added widgets are display at Footer Widget Area.', 'easy-store' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	
}
add_action( 'widgets_init', 'easy_store_widgets_init' );
/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Register various widgets
 *
 * @since 1.0.0
 */

function easy_store_register_grid_layout_widget() {
    
    // Slider Widget
    register_widget( 'Easy_Store_Slider' );

    // Promo Widget
    register_widget( 'Easy_Store_Promo_Items' );

    // Latest Posts
    register_widget( 'Easy_Store_Latest_Posts' );

    // Testimonials
    register_widget( 'Easy_Store_Testimonials' );

    // Call to action
    register_widget( 'Easy_Store_Call_To_Action' );

    //Advance product search
    register_widget( 'Easy_Store_Advance_Product_Search' );

    // Sponsors
    register_widget( 'Easy_Store_Sponsors' );

    // Social Media
    register_widget( 'Easy_Store_Social_Media' );

    if( is_woocommerce_activated() ) {
    	// Featured Products
    	register_widget( 'Easy_Store_Featured_Products' );

    	// Categories Collection
    	register_widget( 'Easy_Store_Categories_Collection' );

    	// Category Products
    	register_widget( 'Easy_Store_Category_Products' );
    }
}

add_action( 'widgets_init', 'easy_store_register_grid_layout_widget' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Load important files for widgets
 *
 * @since 1.0.0
 */
require get_template_directory() . '/inc/widgets/es-widget-fields.php';
require get_template_directory() . '/inc/widgets/es-slider.php';
require get_template_directory() . '/inc/widgets/es-promo-items.php';
require get_template_directory() . '/inc/widgets/es-latest-posts.php';
require get_template_directory() . '/inc/widgets/es-testimonials.php';
require get_template_directory() . '/inc/widgets/es-call-to-action.php';
require get_template_directory() . '/inc/widgets/es-advance-product-search.php';
require get_template_directory() . '/inc/widgets/es-sponsors.php';
require get_template_directory() . '/inc/widgets/es-social-media.php';


if( is_woocommerce_activated() ) {

	require get_template_directory() . '/inc/widgets/es-categories-collection.php';
	require get_template_directory() . '/inc/widgets/es-featured-products.php';
	require get_template_directory() . '/inc/widgets/es-category-products.php';

}
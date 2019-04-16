<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php
		/**
	     * easy_store_before_page hook
	     *
	     * @since 1.0.0
	     */
	    do_action( 'easy_store_before_page' );
	?>
<div id="page" class="site">
	<?php 
		$easy_store_top_header_option = get_theme_mod( 'easy_store_top_header_option', 'show' );
		if( $easy_store_top_header_option == 'show' ) {
			
			/**
		     * easy_store_top_header hook
		     *
		     * @hooked - easy_store_top_header_start - 5
		     * @hooked - easy_store_top_left_section - 10
		     * @hooked - easy_store_top_right_section - 15
		     * @hooked - easy_store_top_header_end - 20
		     *
		     * @since 1.0.0
		     */
		    do_action( 'easy_store_top_header' );
		}
	?>

	<?php
			
		/**
	     * easy_store_header hook
	     *
	     * @hooked - easy_store_header_start - 5
	     * @hooked - easy_store_header_logo_section_start - 10
	     * @hooked - easy_store_site_branding - 15
	     * @hooked - easy_store_header_search_section_start - 20
	     * @hooked - easy_store_header_search - 25
	     * @hooked - easy_store_woocommerce_header_cart - 30
	     * @hooked - easy_store_header_search_section_end - 35
	     * @hooked - easy_store_header_logo_section_end - 40
	     * @hooked - easy_store_main_menu_section - 45
	     * @hooked - easy_store_header_end - 50
	     *
	     * @since 1.0.0
	     */
	    do_action( 'easy_store_header' );
	?>

	<?php
			
		/**
	     * easy_store_page_title hook
	     *
	     * @hooked - easy_store_innerpage_title_content - 5
	     *
	     * @since 1.0.0
	     */
	    do_action( 'easy_store_page_title' );
	?>

	<div id="content" class="site-content">
		<div class="mt-container">
			<?php
				/**
			     * easy_store_before_content hook
			     *
			     * @hooked - easy_store_front_page_widget_area - 5
			     *
			     * @since 1.0.0
			     */
			    do_action( 'easy_store_before_content' );
			?>

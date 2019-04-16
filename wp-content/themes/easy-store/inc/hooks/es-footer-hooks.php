<?php
/**
 * Custom hooks functions are define about footer section.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Footer start
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_footer_start' ) ) :
	function easy_store_footer_start() {
		echo '<footer id="colophon" class="site-footer" role="contentinfo">';
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Footer widget section
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_footer_widget_section' ) ) :
	function easy_store_footer_widget_section() {
		get_sidebar( 'footer' );
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Bottom footer start
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_bottom_footer_start' ) ) :
	function easy_store_bottom_footer_start() {
		echo '<div class="bottom-footer es-clearfix">';
		echo '<div class="mt-container">';
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Bottom footer side info
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_footer_site_info_section' ) ) :
	function easy_store_footer_site_info_section() {
?>
		<div class="site-info">
			<span class="es-copyright-text">
				<?php 
					$easy_store_copyright_text = get_theme_mod( 'easy_store_copyright_text', __( 'Easy Store', 'easy-store' ) );
					echo esc_html( $easy_store_copyright_text );
				?>
			</span>
			<span class="sep"> | </span>
			<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'easy-store' ), 'Easy Store', '<a href="https://mysterythemes.com/">Mystery Themes</a>' );
			?>
		</div><!-- .site-info -->
<?php
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Bottom footer menu
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_footer_menu_section' ) ) :
	function easy_store_footer_menu_section() {
?>
		<nav id="footer-navigation" class="footer-navigation" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'easy_store_footer_menu', 'menu_id' => 'footer-menu', 'fallback_cb' => false ) );
			?>
		</nav><!-- #site-navigation -->
<?php
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Bottom footer end
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_bottom_footer_end' ) ) :
	function easy_store_bottom_footer_end() {
		echo '</div><!-- .mt-container -->';
		echo '</div> <!-- bottom-footer -->';
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Footer end
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_footer_end' ) ) :
	function easy_store_footer_end() {
		echo '</footer><!-- #colophon -->';
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Go to Top Icon
 *
 * @since 1.0.0
 */

if( ! function_exists( 'easy_store_go_top' ) ) :
	function easy_store_go_top() {
		echo '<div id="es-scrollup" class="animated arrow-hide"><i class="fa fa-chevron-up"></i></div>';
	}
endif;

/*-----------------------------------------------------------------------------------------------------------------------*/
/**
 * Managed functions for footer hook
 *
 * @since 1.0.0
 */
add_action( 'easy_store_footer', 'easy_store_footer_start', 5 );
add_action( 'easy_store_footer', 'easy_store_footer_widget_section', 10 );
add_action( 'easy_store_footer', 'easy_store_bottom_footer_start', 15 );
add_action( 'easy_store_footer', 'easy_store_footer_site_info_section', 20 );
add_action( 'easy_store_footer', 'easy_store_footer_menu_section', 25 );
add_action( 'easy_store_footer', 'easy_store_bottom_footer_end', 30 );
add_action( 'easy_store_footer', 'easy_store_footer_end', 35 );
add_action( 'easy_store_footer', 'easy_store_go_top', 40 );
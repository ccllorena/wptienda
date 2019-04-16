<?php
/**
 * Custom hooks functions are define about header section.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Top header start
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_top_header_start' ) ) :
	function easy_store_top_header_start() {
		echo '<div class="es-top-header-wrap es-clearfix">';
		echo '<div class="mt-container">';
	}
endif;

/**
 * Top header left section
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_top_left_section' ) ) :
	function easy_store_top_left_section() {
		echo '<div class="es-top-left-section-wrapper">';
			easy_store_top_header_items();
		echo '</div><!-- .es-top-left-section-wrapper -->';
	}
endif;

/**
 * Top header right section
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_top_right_section' ) ) :
	function easy_store_top_right_section() {
		$easy_store_top_right_content = get_theme_mod( 'easy_store_top_header_right_content', 'social' );
?>
		<div class="es-top-right-section-wrapper">
			<?php
				if( $easy_store_top_right_content == 'social' ) {
					easy_store_social_media_content();
				} else {
			?>
					<nav id="top-navigation" class="top-navigation" role="navigation">
						<?php wp_nav_menu( array( 'theme_location' => 'easy_store_top_menu', 'fallback_cb' => false, 'menu_id' => 'top-menu' ) );
						?>
					</nav><!-- #site-navigation -->
			<?php
				}
			?>
		</div><!-- .es-top-right-section-wrapper -->
<?php
	}
endif;

/**
 * Top header end
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_top_header_end' ) ) :
	function easy_store_top_header_end() {
		echo '</div><!-- .mt-container -->';
		echo '</div><!-- .es-top-header-wrap -->';
	}
endif;

/**
 * Managed functions for top header hook
 *
 * @since 1.0.0
 */
add_action( 'easy_store_top_header', 'easy_store_top_header_start', 5 );
add_action( 'easy_store_top_header', 'easy_store_top_left_section', 10 );
add_action( 'easy_store_top_header', 'easy_store_top_right_section', 15 );
add_action( 'easy_store_top_header', 'easy_store_top_header_end', 20 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Main header start
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_header_start' ) ) :
	function easy_store_header_start() {
		echo '<header id="masthead" class="site-header">';
	}
endif;

/**
 * Logo section start
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_header_logo_section_start' ) ) :
	function easy_store_header_logo_section_start() {
		echo '<div class="es-header-logo-wrapper es-clearfix"><div class="mt-container">';
	}
endif;

/**
 * Site branding
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_site_branding' ) ):
	function easy_store_site_branding() {
?>
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() || is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
			endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>
		</div><!-- .site-branding -->
<?php
	}
endif;

/**
 * Header area section start
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_header_area_section_start' ) ) :
	function easy_store_header_area_section_start() {
		echo '<div class="es-header-area-cart-wrapper">';
	}
endif;


/**
 * Header area
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_header_area_content' ) ) :
	function easy_store_header_area_content() {
		if ( is_active_sidebar( 'header_area_sidebar' ) ) :
			dynamic_sidebar( 'header_area_sidebar' );
		endif;
	}
endif;



/**
 * Header area section end
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_header_area_section_end' ) ) :
	function easy_store_header_area_section_end() {
		echo '</div><!-- .es-header-area-wrapper -->';
	}
endif;

/**
 * Logo section end
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_header_logo_section_end' ) ) :
	function easy_store_header_logo_section_end() {
		echo '</div><!-- .mt-container --></div><!-- .es-header-logo-wrapper -->';
	}
endif;

/**
 * Main menu section
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_main_menu_section' ) ) :
	function easy_store_main_menu_section() {
?>
		<div class="es-main-menu-wrapper">
			<div class="mt-container">
				<div class="es-home-icon">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <i class="fa fa-home"> </i> </a>
				</div><!-- .np-home-icon -->
                <a href="javascript:void(0)" class="menu-toggle hide"> <i class="fa fa-navicon"> </i> </a>
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'easy_store_primary_menu', 'menu_id' => 'primary-menu' ) );
					?>
				</nav><!-- #site-navigation -->
				
				<?php
					$easy_store_header_wishlist_option = get_theme_mod( 'easy_store_header_wishlist_option', 'show' );
	                if ( function_exists( 'YITH_WCWL' ) && $easy_store_header_wishlist_option == 'show' ) {
	                $easy_store_wishlist_url = YITH_WCWL()->get_wishlist_url();
	                $easy_store_wishlist_text = get_theme_mod( 'easy_store_wishlist_text', __( 'Wishlist', 'easy-store' ) );
            	?>
	            		<div class="es-wishlist-wrap">
		                    <a class="es-wishlist-btn" href="<?php echo esc_url( $easy_store_wishlist_url ); ?>" title="<?php esc_attr_e( 'Wishlist Tab', 'easy-store' ); ?>">
		                    	<i class="fa fa-heart"> </i>
		                        <?php echo esc_html( $easy_store_wishlist_text ); ?><span class="es-wl-counter"><?php printf( esc_html( '(%s)', 'easy-store' ), yith_wcwl_count_products() ); ?></span>
		                    </a>
						</div><!-- .es-wishlist-wrap -->
				<?php } ?>
			</div><!-- .mt-container -->
		</div><!-- .es-main-menu-wrapper -->
<?php
	}
endif;


/**
 * Main header end
 *
 * @since 1.0.0
 */
if( ! function_exists( 'easy_store_header_end' ) ) :
	function easy_store_header_end() {
		echo '</header><!-- #masthead -->';
	}
endif;

/**
 * Managed functions for header hook
 *
 * @since 1.0.0
 */
add_action( 'easy_store_header', 'easy_store_header_start', 5 );
add_action( 'easy_store_header', 'easy_store_header_logo_section_start', 10 );
add_action( 'easy_store_header', 'easy_store_site_branding', 15 );
add_action( 'easy_store_header', 'easy_store_header_area_section_start', 20 );
add_action( 'easy_store_header', 'easy_store_header_area_content', 25 );
if( is_woocommerce_activated() ) {
	add_action( 'easy_store_header', 'easy_store_woocommerce_header_cart', 30 );
}
add_action( 'easy_store_header', 'easy_store_header_area_section_end', 35 );
add_action( 'easy_store_header', 'easy_store_header_logo_section_end', 40 );
add_action( 'easy_store_header', 'easy_store_main_menu_section', 45 );
add_action( 'easy_store_header', 'easy_store_header_end', 50 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Page title for innerpages
 *
 * @since 1.0.0
 */

if( ! function_exists( 'easy_store_innerpage_title_content' ) ) :
	function easy_store_innerpage_title_content() {
		if( !is_front_page() ) {
			$inner_header_attribute = '';
			$inner_header_attribute = apply_filters( 'easy_store_inner_header_style_attribute', $inner_header_attribute );
			if( !empty( $inner_header_attribute ) ) {
				$header_class = 'has-bg-img';
			} else {
				$header_class = 'no-bg-img';
			}
	?>
			<div class="custom-header <?php echo esc_attr( $header_class ); ?>" <?php echo ( ! empty( $inner_header_attribute ) ) ? ' style="' . esc_attr( $inner_header_attribute ) . '" ' : ''; ?>>
	            <div class="mt-container">
	    			<?php
	    				if( is_single() || is_page() ) {
	    					the_title( '<h1 class="entry-title">', '</h1>' );
	    				}elseif( is_home() ) {
	    				   echo '<h1 class="entry-title">'. apply_filters( 'the_title', get_the_title( get_option( 'page_for_posts' ) ) ) .'</h1>';
	    				} elseif( is_archive() ) {
	    					if( is_woocommerce_activated() && is_shop() && wc_get_page_id( 'shop' ) != -1 ) {
	    				?>
	    						<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	    				<?php
	    					} else {
	    						the_archive_title( '<h1 class="page-title">', '</h1>' );
	    						the_archive_description( '<div class="taxonomy-description">', '</div>' );
	    					}
	    				} elseif( is_search() ) {
	    			?>
	    					<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'easy-store' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
	    			<?php
	    				} elseif( is_404() ) {
	    					echo '<h1 class="entry-title">'. esc_html( '404 Error', 'easy-store' ) .'</h1>';
	    				}
	    			?>
	    			<?php easy_store_inner_breadcrumb(); ?>
	            </div><!-- .mt-container -->
			</div><!-- .custom-header -->
	<?php
		}
	}
endif;

add_action( 'easy_store_page_title', 'easy_store_innerpage_title_content', 5 );
<?php
/**
 * Custom hook file contains general hooks and functions.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 *
 */

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Managed sidebar.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'easy_store_add_sidebar' ) ) :

	function easy_store_add_sidebar() {

		global $post;

	    if( 'post' === get_post_type() ) {
	        $sidebar_meta_option = get_post_meta( $post->ID, 'easy_store_sidebar_layout', true );
	    }

	    if( 'page' === get_post_type() ) {
	        $sidebar_meta_option = get_post_meta( $post->ID, 'easy_store_sidebar_layout', true );
	    }
	     
	    if( is_home() ) {
	        $set_id = get_option( 'page_for_posts' );
	        $sidebar_meta_option = get_post_meta( $set_id, 'easy_store_sidebar_layout', true );
	    }
	    
	    if( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
	        $sidebar_meta_option = 'default_sidebar';
	    }
	    
	    $archive_sidebar      = get_theme_mod( 'easy_store_archive_sidebar', 'right_sidebar' );
	    $page_default_sidebar = get_theme_mod( 'easy_store_global_page_sidebar', 'right_sidebar' );
	    $post_default_sidebar = get_theme_mod( 'easy_store_global_post_sidebar', 'right_sidebar' );
	    
	    if( $sidebar_meta_option == 'default_sidebar' ) {
	        if( is_single() ) {
	            if( $post_default_sidebar == 'right_sidebar' ) {
	                get_sidebar();
	            } elseif( $post_default_sidebar == 'left_sidebar' ) {
	                get_sidebar( 'left' );
	            }
	        } elseif( is_page() ) {
	            if( $page_default_sidebar == 'right_sidebar' ) {
	                get_sidebar();
	            } elseif( $page_default_sidebar == 'left_sidebar' ) {
	                get_sidebar( 'left' );
	            }
	        } elseif( $archive_sidebar == 'right_sidebar' ) {
	            get_sidebar();
	        } elseif( $archive_sidebar == 'left_sidebar' ) {
	            get_sidebar( 'left' );
	        }
	    } elseif( $sidebar_meta_option == 'right_sidebar' ) {
	        get_sidebar();
	    } elseif( $sidebar_meta_option == 'left_sidebar' ) {
	        get_sidebar( 'left' );
	    }
	}

endif;

add_action( 'easy_store_sidebar', 'easy_store_add_sidebar', 5 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Managed the homepage widget area
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'easy_store_front_page_widget_area' ) ) :
	function easy_store_front_page_widget_area() {

		if ( is_front_page() ) {
			echo '<div id="es-front-page-widgets" class="front-page-widgets-area">';
			if ( is_active_sidebar( 'front_page_section_area' ) ) {
				dynamic_sidebar( 'front_page_section_area' );
			}
			else {
				do_action( 'easy_store_default_front_page_section_area' );
			}
			echo '</div><!-- #es-front-page-widgets -->';
		}

	}
endif;

add_action( 'easy_store_before_content', 'easy_store_front_page_widget_area', 5 );

<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Build Page Action Start
 *
 * @since 1.3.8
 */
if( ! function_exists( 'agama_customize_build_page_action_start' ) ) {
    function agama_customize_build_page_action_start() {
        global $post;
        
        $widget = 'page-widget-'. esc_attr( $post->ID );
        
        if( is_customize_preview() && is_page() && ! is_active_sidebar( $widget ) ) {
            
            $html  = '<div class="agama-build-page-wrapper clearfix">';
                $html .= '<div class="agama-build-page-action" data-id="sidebar-widgets-page-widget-'. esc_attr( $post->ID ) .'">';
                    $html .= esc_html__( 'You can replace this page with Agama Widgets.', 'agama' );
                    $html .= '<a class="add-new-widget">'. esc_html__( 'Add Widgets', 'agama' ) .'</a>';
                $html .= '</div>';
        
            echo $html;
            
        }
    }
}
add_action( 'agama_customize_build_page_action_start', 'agama_customize_build_page_action_start' );

/**
 * Build Page Action End
 *
 * @since 1.3.8
 */
if( ! function_exists( 'agama_customize_build_page_action_end' ) ) {
    function agama_customize_build_page_action_end() {
        global $post;
        
        $widget = 'page-widget-'. esc_attr( $post->ID );
        
        if( is_customize_preview() && is_page() && ! is_active_sidebar( $widget ) ) {
            
            echo '</div><!-- Agama Build Page Wrapper End -->';
            
        }
    }
}
add_action( 'agama_customize_build_page_action_end', 'agama_customize_build_page_action_end' );

/**
 * Get Page Permalink via Ajax
 *
 * @since 1.3.8
 */
if( ! function_exists( 'agama_ajax_get_permalink' ) ) {
    function agama_ajax_get_permalink() {
        $permalink = get_permalink( intval( $_REQUEST['id'] ) );
        echo esc_url( $permalink );
        die();
    }
}
add_action( 'wp_ajax_agama_ajax_get_permalink', 'agama_ajax_get_permalink' );
add_action( 'wp_ajax_nopriv_agama_ajax_get_permalink', 'agama_ajax_get_permalink' );

/**
 * Render Blog Post Date & Icon
 *
 * @since 1.0.1
 */
if( ! function_exists( 'agama_render_blog_post_date' ) ) {
	function agama_render_blog_post_date() {
		global $post;
		if( get_theme_mod( 'agama_blog_post_meta', true ) ) {
            // Display blog post date only on posts loop page.
            if( ! is_single() && get_theme_mod( 'agama_blog_post_date', true ) ) {
                echo '<div class="entry-date">';
                    echo '<div class="date-box updated">';
                        printf( '<span class="date">%s</span>', get_the_time('d') ); // Get day
                        printf( '<span class="month-year">%s</span>', get_the_time('m, Y') ); // Get month, year
                    echo '</div>';
                    echo '<div class="format-box">';
                        printf( '%s', Agama::post_format() );
                    echo '</div>';
                echo '</div>';
            }
        }
	}
}
add_action( 'agama_blog_post_date_and_format', 'agama_render_blog_post_date', 10 );

/**
 * Social Share Icons
 *
 * @since 1.3.7
 */
if( ! function_exists( 'agama_social_share' ) ) {
    function agama_social_share() {
        $enabled    = esc_attr( get_theme_mod( 'agama_share_box', true ) );
        $visibility = esc_attr( get_theme_mod( 'agama_share_box_visibility', 'posts' ) );
        $icons      = get_theme_mod( 
            'agama_social_share_icons',
            array( 'facebook', 'twitter', 'pinterest', 'gplus', 'linkedin', 'rss', 'email' )
        );
        
        // Check if Social Share is Enabled
        if( $enabled && $visibility == 'posts' && is_single() ) {
            $enabled = true;
        } 
        else
        if( $enabled && $visibility == 'pages' && is_page() ) {
            $enabled = true;
        }
        else
        if( $enabled && $visibility == 'all' ) {
            $enabled = true;  
        } else {
            $enabled = false;
        }
        
        // Translation
        if( is_page() ) {
            $translate = esc_html__( 'Share this Page', 'agama' );
        }
        else
        if( is_single() ) {
            $translate = esc_html__( 'Share this Post', 'agama' );
        } else {
            $translate = esc_html__( 'Share', 'agama' );
        }
        
        if( $enabled ) {
            echo '<div class="si-share">';
                echo '<span>'. $translate .'</span>';
                echo '<div>';
                foreach( $icons as $icon ) {
                    
                    // Set default values.
                    $title  = ucfirst( $icon );
                    $fa     = $icon;
                    
                    // Set Parameters
                    switch( $icon ) {
                        case 'facebook':
                            $url    = sprintf( 'https://www.facebook.com/sharer/sharer.php?u=%s', get_permalink() );
                        break;
                        case 'twitter':
                            $url    = sprintf( 'https://twitter.com/intent/tweet?url=%s', get_permalink() );
                        break;
                        case 'pinterest':
                            $url    = sprintf( 
                                            'http://pinterest.com/pin/create/button/?url=%s&media=%s', 
                                            get_permalink(), 
                                            get_the_post_thumbnail_url() 
                                        );
                        break;
                        case 'gplus':
                            $url    = sprintf( 'https://plus.google.com/share?url=%s', get_permalink() );
                            $title  = esc_html__( 'Google+', 'agama' );
                            $fa     = 'google-plus';
                        break;
                        case 'linkedin':
                            $url    = sprintf( 'http://www.linkedin.com/shareArticle?mini=true&url=%s', get_permalink() );
                            $title  = esc_html__( 'LinkedIn', 'agama' );
                        break;
                        case 'rss':
                            $url    = sprintf( '%s?feed=rss2&withoutcomments=1', get_permalink() );
                            $title  = strtoupper( $title );
                        break;
                        case 'email':
                            $url    = sprintf( 'mailto:?&subject=%s&body=%s', rawurlencode( get_the_title() ), get_permalink() );
                            $fa     = 'at';
                        break;
                    }
                    
                    // Output Share Icons
                    printf( 
                        '<a href="%s" class="social-icon si-borderless %s" data-toggle="tooltip" data-placement="top" title="%s" target="_blank"><i class="fa fa-%s"></i><i class="fa fa-%s"></i></a>', 
                        esc_url( $url ),
                        'si-' . esc_attr( $icon ),
                        esc_html( $title ),
                        esc_attr( $fa ),
                        esc_attr( $fa )
                    );
                }
                echo '</div>';
            echo '</div>';
        }
    }
}
add_action( 'agama_social_share', 'agama_social_share' );

/**
 * Render Blog Post Meta
 *
 * @since 1.0.1
 */
if( ! function_exists( 'agama_render_blog_post_meta' ) ) {
	function agama_render_blog_post_meta() {
		if( get_theme_mod( 'agama_blog_post_meta', true ) ) {
            echo '<p class="single-line-meta">';
                // Display blog post author.
                if( get_theme_mod( 'agama_blog_post_author', true ) ) {
                    printf( 
                        '%s <span class="vcard"><span class="fn">%s</span></span>', 
                        '<i class="fa fa-user"></i>', 
                        get_the_author_link() 
                    );
                }

                // Display blog post publish date.
                if( get_theme_mod( 'agama_blog_post_date', true ) ) {
                    printf( 
                        '%s %s <span>%s</span>',
                        '<span class="inline-sep">/</span>',				
                        '<i class="fa fa-calendar"></i>', 
                        get_the_time('F j, Y') 
                    );
                }

                // Display next details only on list blog layout or on single post page.
                if( get_theme_mod('agama_blog_layout', 'list') == 'list' || is_single() ) {
                    // Display post category.
                    if( get_theme_mod( 'agama_blog_post_category', true ) ) {
                        printf( 
                            '%s %s %s', 
                            '<span class="inline-sep">/</span>',
                            '<i class="fa fa-folder-open"></i>', 
                            get_the_category_list(', ') 
                        );
                    }

                    // Display post comment counter.
                    if( comments_open() && get_theme_mod( 'agama_blog_post_comments', true ) ) {
                        printf( 
                            '%s %s <a href="%s">%s</a>', 
                            '<span class="inline-sep">/</span>',
                            '<i class="fa fa-comments"></i>', 
                            get_comments_link(), 
                            get_comments_number().__( ' comments', 'agama' ) 
                        );
                    }
                }
            echo '</p>';
		}
	}
}
add_action( 'agama_blog_post_meta', 'agama_render_blog_post_meta', 10 );

/**
 * Agama Credits
 *
 * @since 1.0.1
 */
if( ! function_exists( 'agama_render_credits' ) ) {
	function agama_render_credits() {
		echo html_entity_decode( get_theme_mod( 'agama_footer_copyright', sprintf( __( '2015 &copy; Powered by %s.', 'agama' ), '<a href="http://www.theme-vision.com" target="_blank">Theme-Vision</a>' ) ) );
	}
}
add_action( 'agama_credits', 'agama_render_credits' );

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

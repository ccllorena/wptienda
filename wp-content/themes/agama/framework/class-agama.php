<?php 

// Prevent direct access to the file
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Agama Class
 *
 * @since 1.1.1
 */
if( ! class_exists( 'Agama' ) ) {
	class Agama {
		
		/**
		 * Class Constructor
		 *
		 * @since 1.1.1
		 */
		function __construct() {
			
			// Extends body class init
			add_filter( 'body_class', array( $this, 'body_class' ) );
			
			// Excerpt lenght init
			add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 999 );
			
			// Excerpt "more" link init
			add_filter('excerpt_more', array( $this, 'excerpt_more' ) );
			
			// Add button class to post edit links init
			add_filter( 'edit_post_link', array( $this, 'edit_post_link' ) );
			
			// Add button class to comment edit links init
			add_filter( 'edit_comment_link', array( $this, 'edit_comment_link' ) );
		}
		
		/**
		 * Extend the default WordPress body classes.
		 *
		 * @since Agama 1.0.0
		 * @param array $classes Existing class values.
		 * @return array Filtered class values.
		 */
		function body_class( $classes ) {
			$background_color 	= esc_attr( get_background_color() );
			$background_image 	= esc_url( get_background_image() );
			$header 			= esc_attr( get_theme_mod( 'agama_header_style', 'transparent' ) );
			$sidebar_position	= esc_attr( get_theme_mod( 'agama_sidebar_position', 'right' ) );
			$blog_layout 		= esc_attr( get_theme_mod('agama_blog_layout', 'list') );
            
            if( is_customize_preview() ) {
                $classes[] = 'customize-preview';
            }
            
            if( is_404() ) {
                $classes[] = 'vision-404';
            }
			
			// Apply header style class.
			switch( $header ) {
				case 'transparent':
					$classes[] = 'header_v1';
				break;
				case 'default':
					$classes[] = 'header_v2';
				break;
				case 'sticky':
					$classes[] = 'header_v3 sticky_header';
				break;
			}
			
			// Apply sidebar position class.
			if( $sidebar_position == 'left' ) {
				$classes[] = 'sidebar-left';
			}
			
			// Apply blog layout class.
			switch( $blog_layout ) {
				case 'small_thumbs':
					$classes[] = 'blog-small-thumbs';
				break;
				case 'grid':
					$classes[] = 'blog-grid';
				break;
			}
			
			// Apply template full-width class.
			if ( is_page_template( 'page-templates/full-width.php' ) ) { 
				$classes[] = 'full-width'; 
			}
			
			// Apply front page class.
			if ( is_page_template( 'page-templates/front-page.php' ) ) {
				$classes[] = 'template-front-page';
				if ( has_post_thumbnail() )
					$classes[] = 'has-post-thumbnail';
			}
			
			// Apply empty background class.
			if ( empty( $background_image ) ) {
				if ( empty( $background_color ) )
					$classes[] = 'custom-background-empty';
				elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
					$classes[] = 'custom-background-white';
			}
			
			// Apply single author class.
			if ( ! is_multi_author() )
				$classes[] = 'single-author';

			return $classes;
		}
		
		/**
		 * Header Style Class
		 *
		 * @since 1.2.0
		 */
		static function header_class() {
			$header     = esc_attr( get_theme_mod( 'agama_header_style', 'transparent' ) );
            $desktop    = esc_url( get_theme_mod( 'agama_logo', '' ) );
            $tablet     = esc_url( get_theme_mod( 'agama_tablet_logo', '' ) );
            $mobile     = esc_url( get_theme_mod( 'agama_mobile_logo', '' ) );
            $device     = array();
            
            if( ! empty( $desktop ) ) {
                $device[] = 'has_desktop';
            }
            
            if( ! empty( $tablet ) ) {
                $device[] = 'has_tablet';
            }
            
            if( ! empty( $mobile ) ) {
                $device[] = 'has_mobile';
            }
            
			switch( $header ):
				case 'transparent':
					 $class = 'header_v1 ' . implode( ' ', $device );
				break;
				case 'default':
					 $class = 'header_v2 ' . implode( ' ', $device );
				break;
				case 'sticky':
					 $class = 'header_v3 ' . implode( ' ', $device );
				break;
			endswitch;
			echo $class;
		}
		
		/**
		 * Bootstrap Content Wrapper Class
		 *
		 * @since 1.1.7
		 */
		static function bs_class() {
			if( is_active_sidebar( 'sidebar-1' ) ) {
				$class = 'col-md-9';
			} else {
				$class = 'col-md-12';
			}
			return esc_attr( $class );
		}
		
		/**
		 * Excerpt Lenght
		 *
		 * @since 1.0.0
		 */
		function excerpt_length( $length ) {
            $custom = esc_attr( get_theme_mod( 'agama_blog_excerpt', '60' ) );
			return $length = intval( $custom );
		}
		
		/**
		 * Replaces Excerpt "more" Text by Link
		 *
		 * @since 1.1.1
		 */
		function excerpt_more( $more ) {
			global $post;
			if( get_theme_mod('agama_blog_readmore_url', true) ) {
				return sprintf('<br><br><a class="more-link" href="%s">%s</a>', get_permalink($post->ID), __('Read More', 'agama'));
			}
			return;
		}
		
		/**
		 * Add Class to Post Edit Link
		 *
		 * @since 1.1.1
		 */
		function edit_post_link( $output ) {
			$output = str_replace('class="post-edit-link"', 'class="button button-3d button-mini button-rounded"', $output);
			return $output;
		}
		
		/**
		 * Add Class to Post Edit Comment Link
		 *
		 * @since 1.1.1
		 */
		function edit_comment_link( $output ) {
			$output = str_replace('class="comment-edit-link"', 'class="button button-3d button-mini button-rounded"', $output);
			return $output;
		}
		
		/**
		 * Render Menu Content
		 *
		 * @since 1.1.1
		 */
		public static function menu( $location = false, $class = false ) {
			
			// If location not set
			if( ! $location ) {
				return;
            }
			
			$args = array(
				'theme_location' => $location,
				'menu_class'     => $class,
				'container'      => false,
				'echo'           => '0'
			);
			
			$menu = wp_nav_menu( $args );
			
			return $menu;
		}
		
		/**
		 * Render Social Icons
		 *
		 * @since 1.1.1
		 * @updated @since 1.1.2
		 */
		public static function sociali( $tip_position = false, $style = false ) {
			
			// Url target
			$_target = esc_attr( get_theme_mod('agama_social_url_target', '_self') );
			
			// Social icons
			$social  = array(
				'Facebook'	=> esc_url( get_theme_mod('social_facebook', '') ),
				'Twitter'	=> esc_url( get_theme_mod('social_twitter', '') ),
				'Flickr'	=> esc_url( get_theme_mod('social_flickr', '') ),
				'Vimeo'		=> esc_url( get_theme_mod('social_vimeo', '') ),
				'Youtube'	=> esc_url( get_theme_mod('social_youtube', '') ),
				'Instagram'	=> esc_url( get_theme_mod('social_instagram', '') ),
				'Pinterest'	=> esc_url( get_theme_mod('social_pinterest', '') ),
				'Tumblr'	=> esc_url( get_theme_mod('social_tumblr', '') ),
				'Google'	=> esc_url( get_theme_mod('social_google', '') ),
				'Dribbble'	=> esc_url( get_theme_mod('social_dribbble', '') ),
				'Digg'		=> esc_url( get_theme_mod('social_digg', '') ),
				'Linkedin'	=> esc_url( get_theme_mod('social_linkedin', '') ),
				'Blogger'	=> esc_url( get_theme_mod('social_blogger', '') ),
				'Skype'		=> esc_attr( get_theme_mod('social_skype', '') ),
				'Myspace'	=> esc_url( get_theme_mod('social_myspace', '') ),
				'Deviantart'=> esc_url( get_theme_mod('social_deviantart', '') ),
				'Yahoo'		=> esc_url( get_theme_mod('social_yahoo', '') ),
				'Reddit'	=> esc_url( get_theme_mod('social_reddit', '') ),
				'PayPal'	=> esc_url( get_theme_mod('social_paypal', '') ),
                'Phone'     => esc_attr( get_theme_mod('social_phone' ) ),
				'Dropbox'	=> esc_url( get_theme_mod('social_dropbox', '') ),
				'Soundcloud'=> esc_url( get_theme_mod('social_soundcloud', '') ),
				'VK'		=> esc_url( get_theme_mod('social_vk', '') ),
				'Email'		=> esc_attr( get_theme_mod('social_email', '') ),
				'RSS'		=> esc_url( get_theme_mod('social_rss', get_bloginfo('rss2_url')) )
			);
			if( $style == 'animated' ):
				echo '<ul>';
				foreach( $social as $name => $url ) {
					if( ! empty( $url ) ) {
                        
                        if( $name == 'Phone' ) {
                            $url = 'tel:' . $url;
                        }
                        if( $name == 'Skype' ) {
                            $url = 'skype:' . $url . '?call';
                        }
                        if( $name == 'Email' ) {
                            $url = 'mailto:' . $url;
                        }
                        
                        $fa_class = 'fa-' . strtolower( $name );
                        
                        if( $name == 'Email' ) {
                            $fa_class = 'fa-at';
                        }
                        
						echo sprintf
						(
							'<li><a class="tv-%s" href="%s" target="%s"><span class="tv-icon"><i class="fa %s"></i></span><span class="tv-text">%s</span></a></li>', 
							esc_attr( strtolower($name) ), $url, $_target, esc_attr( $fa_class ), esc_attr( $name )
						);
					}
				}
				echo '</ul>';
			else:
				foreach( $social as $name => $url ) {
					if( ! empty( $url ) ) {
                        
                        if( $name == 'Phone' ) {
                            $url = 'tel:' . $url;
                        }
                        if( $name == 'Skype' ) {
                            $url = 'skype:' . $url . '?call';
                        }
                        if( $name == 'Email' ) {
                            $url = 'mailto:' . $url;
                        }
                        
                        $fa_class = 'fa-' . strtolower( $name );
                        
                        if( $name == 'Email' ) {
                            $fa_class = 'fa-at';
                        }
                        
						echo sprintf
						(
							'<a class="social-icons %s" href="%s" target="%s" data-toggle="tooltip" data-placement="%s" title="%s"></a>', 
							esc_attr( strtolower($name) ), $url, $_target, esc_attr( $tip_position ), esc_attr( $name )
						);
					}
				}
			endif;
		}
		
		/**
		 * Get Post Format
		 *
		 * @since 1.1.1
		 */
		public static function post_format() {
			$post_format = get_post_format();
			
			switch( $post_format ) {

				case 'aside':
					$icon = '<i class="fa fa-outdent"></i>';
				break;
				
				case 'chat':
					$icon = '<i class="fa fa-wechat"></i>';
				break;
				
				case 'gallery':
					$icon = '<i class="fa fa-photo"></i>';
				break;
				
				case 'link':
					$icon = '<i class="fa fa-link"></i>';
				break;
				
				case 'image':
					$icon = '<i class="fa fa-image"></i>';
				break;
				
				case 'quote':
					$icon = '<i class="fa fa-quote-left"></i>';
				break;
				
				case 'status':
					$icon = '<i class="fa fa-check-circle"></i>';
				break;
				
				case 'video':
					$icon = '<i class="fa fa-video-camera"></i>';
				break;
				
				case 'audio':
					$icon = '<i class="fa fa-volume-up"></i>';
				break;
				
				default: $icon = '<i class="fa fa-camera-retro"></i>';
				
			}
			
			return $icon;
		}
		
		/**
		 * Count Comments
		 *
		 * @since 1.1.1
		 */
		public static function comments_count() {
			$comments = 0;
			
			if( comments_open() ) {
				$comments = sprintf('<a href="%s">%s</a>', get_comments_link(), get_comments_number() . __( ' comments', 'agama' ) );
			}
			
			return $comments;
		}
		
		/**
		 * Next | Previous - Post Links
		 *
		 * @since 1.0.0
		 */
		public static function post_prev_next_links() {
			if( get_previous_post_link() || get_next_post_link() ) { ?>
				<!-- Posts Navigation -->
				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'agama' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'agama' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'agama' ) . '</span>' ); ?></span>
				</nav><!-- Post Navigation End -->
			<?php
			}
		}
		
		/**
		 * Render About Author on Single Posts
		 *
		 * @since 1.1.1
		 */
		public static function about_author() { ?>
			<?php 
			if ( 
				 is_singular() && 
				 get_the_author_meta( 'description' ) && 
			     get_theme_mod( 'agama_blog_about_author', true ) 
				) : ?>
				
			<div class="author-info">
				<div class="author-avatar">
					<?php
					/** This filter is documented in author.php */
					$author_bio_avatar_size = apply_filters( 'agama_author_bio_avatar_size', 68 );
					echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
					?>
				</div>
				<div class="author-description">
					<h2><?php printf( __( 'About %s', 'agama' ), get_the_author() ); ?></h2>
					<p><?php the_author_meta( 'description' ); ?></p>
					<div class="author-link">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
							<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'agama' ), get_the_author() ); ?>
						</a>
					</div>
				</div>
			</div>
			
		<?php endif; ?>
		<?php
		}
	}
	new Agama;
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

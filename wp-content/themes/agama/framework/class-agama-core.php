<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/**
 * Agama Core Class
 *
 * @since 1.0.1
 */
if( ! class_exists( 'Agama_Core' ) ) {
    class Agama_Core {

        /**
         * Theme Version
         *
         * @since 1.1.5
         */
        public static $version;

        /**
         * Development Mode
         *
         * @since 1.2.9
         */
       public static $development;

        /**
         * Class Constructor
         */
        function __construct() {

            /**
             * If development mode is "On" generate -
             * unique id for scripts 'n styles version.
             *
             * @since 1.2.9
             */
            if( self::$development ) {
                self::$version = esc_attr( uniqid() );
            }

            $this->defines();
            $this->migrate_options();
            
            add_action( 'wp_head', [ $this, 'IE_Scripts' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'scripts_styles' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
            add_action( 'after_setup_theme', [ $this, 'agama_setup' ] );
            add_action( 'tgmpa_register', [ $this, 'tgmpa_register' ] );
            add_action( 'wp_footer', [ $this, 'footer_scripts' ] );
        }

        /**
         * Agama Defines
         *
         * @since Agama v1.0.1
         */
        function defines() {

            // Theme Version
            if( ! defined( 'agama_version' ) ) {
                define( 'agama_version', self::$version );
            }

            // Set up Agama text domain
            if( ! defined( 'AGAMA_DOMAIN' ) ) {
                define( 'AGAMA_DOMAIN', 'agama' );
            }

            // Defina Agama URI
            if( ! defined( 'AGAMA_URI' ) ) {
                define( 'AGAMA_URI', get_template_directory_uri() . '/' );
            }

            // Define Agama DIR
            if( ! defined( 'AGAMA_DIR' ) ) {
                define( 'AGAMA_DIR', get_template_directory() . '/' );
            }

            // Define Agama framework DIR
            if( !defined( 'AGAMA_FMW' ) ) {
                define( 'AGAMA_FMW', AGAMA_DIR . 'framework/' );
            }

            // Define Agama INC
            if( ! defined( 'AGAMA_INC' ) ) {
                define( 'AGAMA_INC', AGAMA_DIR . 'includes/' );
            }

            // Define Agama CSS
            if( ! defined( 'AGAMA_CSS' ) ) {
                define( 'AGAMA_CSS', AGAMA_URI . 'assets/css/' );
            }

            // Define Agama JS
            if( ! defined( 'AGAMA_JS' ) ) {
                define( 'AGAMA_JS', AGAMA_URI . 'assets/js/' );
            }

            // Define Agama IMG
            if( ! defined( 'AGAMA_IMG' ) ) {
                define( 'AGAMA_IMG', AGAMA_URI . 'assets/img/' );
            }

            // Define Agama Modules DIR
            if( ! defined( 'AGAMA_MODULES_DIR' ) ) {
                define( 'AGAMA_MODULES_DIR', AGAMA_FMW . 'admin/modules/' );
            }

            // Define Agama Modules URI
            if( ! defined( 'AGAMA_MODULES_URI' ) ) {
                define( 'AGAMA_MODULES_URI', AGAMA_URI . 'framework/admin/modules/' );
            }
            
            if ( ! defined( 'ELEMENTOR_PARTNER_ID' ) ) {
                define( 'ELEMENTOR_PARTNER_ID', 2132 );
            }
            
            if( ! defined( 'MONSTERINSIGHTS_SHAREASALE_ID' ) ) {
                define( 'MONSTERINSIGHTS_SHAREASALE_ID', 69975 );
            }
        }

        /**
         * Enqueue scripts and styles for front-end.
         *
         * @since Agama 1.0
         */
        function scripts_styles() {
            global $wp_styles;
            
            /*
             * Adds JavaScript to pages with the comment form to support
             * sites with threaded comments (when in use).
             */
            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }

            /**
             * FontAwesome Icons
             */
            wp_enqueue_style( 'agama-font-awesome', AGAMA_CSS . 'font-awesome.min.css', array(), '4.7.0' );

            /**
             * WooCommerce Stylesheet
             */
            if( class_exists('Woocommerce') ) {
                wp_register_style( 'agama-woocommerce', AGAMA_CSS . 'woocommerce.css', array(), self::$version );
                wp_enqueue_style( 'agama-woocommerce' );
            }
            
            /**
             * Child Theme Stylesheet
             */ 
            $deps = false;
            if( is_child_theme() ) {
                
                $deps = array( 'agama-parent-style' );
                
                // Load Agama stylesheet if child theme active
                wp_enqueue_style( 
                    'agama-parent-style', 
                    trailingslashit( get_template_directory_uri() ) . 'style.css', 
                    false, 
                    self::$version 
                );
            }

            /**
             * Agama || Child Theme Stylesheet
             */
            wp_enqueue_style( 'agama-style', get_stylesheet_uri(), $deps, self::$version );

            /**
             * Dark Skin Stylesheet
             */
            if( get_theme_mod('agama_skin', 'light') == 'dark' ) {
                wp_register_style( 'agama-dark', AGAMA_CSS.'skins/dark.css', array(), self::$version );
                wp_enqueue_style( 'agama-dark' );
            }

            /**
             * Loads the Internet Explorer specific stylesheet.
             */
            wp_enqueue_style( 'agama-ie', AGAMA_CSS.'ie.min.css', array( 'agama-style' ), self::$version );
            $wp_styles->add_data( 'agama-ie', 'conditional', 'lt IE 9' );

            /**
             * Agama Slider Stylesheet
             */
            if( get_theme_mod( 'agama_slider_image_1', AGAMA_IMG . 'header_img.jpg' ) || 
                get_theme_mod( 'agama_slider_image_2', AGAMA_IMG . 'header_img.jpg' ) ) {
                wp_register_style( 'agama-slider', AGAMA_CSS . 'camera.min.css', array(), self::$version );
                wp_enqueue_style( 'agama-slider' );
            }

            /**
             * Animate Stylesheet
             */
            wp_register_style( 'agama-animate', AGAMA_CSS . 'animate.min.css', array(), '3.5.1' );
            wp_enqueue_style( 'agama-animate' );

            /**
             * Particles JS
             */
            if( get_theme_mod( 'agama_slider_particles', true ) || get_theme_mod( 'agama_header_image_particles', true ) ) {
                wp_register_script( 'agama-particles', AGAMA_JS . 'min/particles.min.js', array(), self::$version );
                wp_enqueue_script( 'agama-particles' );
            }

            /**
             * jQuery Plugins
             */
            wp_register_script( 'agama-plugins', AGAMA_JS . 'plugins.js', array('jquery'), self::$version );
            wp_enqueue_script( 'agama-plugins' );

            /**
             * jQuery Functions
             */
            wp_register_script( 'agama-functions', AGAMA_JS . 'functions.js', array(), self::$version, true );
            $translation_array = array(
                'is_admin_bar_showing'			=> esc_attr( is_admin_bar_showing() ),
                'is_home'						=> is_home(),
                'is_front_page'					=> is_front_page(),
                'headerStyle'					=> esc_attr( get_theme_mod( 'agama_header_style', 'transparent' ) ),
                'headerImage'					=> esc_attr( get_header_image() ),
                'top_navigation'				=> esc_attr( get_theme_mod( 'agama_top_navigation', true ) ),
                'background_image'				=> esc_attr( get_header_image() ),
                'primaryColor' 					=> esc_attr( get_theme_mod( 'agama_primary_color', '#FE6663' ) ),
                'header_top_margin'				=> esc_attr( get_theme_mod( 'agama_header_top_margin', '0' ) ),
                'slider_particles'				=> esc_attr( get_theme_mod( 'agama_slider_particles', true ) ),
                'slider_enable'					=> esc_attr( get_theme_mod( 'agama_slider_enable', true ) ),
                'slider_height'					=> esc_attr( get_theme_mod( 'agama_slider_height', '0' ) ),
                'slider_time'					=> esc_attr( get_theme_mod( 'agama_slider_time', '7000' ) ),
                'slider_particles_circle_color'	=> esc_attr( get_theme_mod( 'agama_slider_particles_circle_color', '#FE6663' ) ),
                'slider_particles_lines_color'	=> esc_attr( get_theme_mod( 'agama_slider_particles_lines_color', '#FE6663' ) ),
                'header_image_particles'		=> esc_attr( get_theme_mod( 'agama_header_image_particles', true ) ),
                'header_img_particles_c_color'	=> esc_attr( get_theme_mod( 'agama_header_image_particles_circle_color', '#FE6663' ) ),
                'header_img_particles_l_color'	=> esc_attr( get_theme_mod( 'agama_header_image_particles_lines_color', '#FE6663' ) ),
                'blog_layout'                   => esc_attr( get_theme_mod( 'agama_blog_style', 'list' ) ),
                'infinite_scroll'               => esc_attr( get_theme_mod( 'agama_blog_infinite_scroll', false ) ),
                'infinite_trigger'              => esc_attr( get_theme_mod( 'agama_blog_infinite_trigger', 'button' ) )
            );
            wp_localize_script( 'agama-functions', 'agama', $translation_array );
            wp_enqueue_script( 'agama-functions' );
        }
        
        /**
         * Admin Scripts
         *
         * Enqueue admin scripts and styles.
         *
         * @since 1.4.1
         */
        function admin_scripts() {
            
            wp_enqueue_script( 'agama-admin', AGAMA_JS . 'admin.js', [ 'jquery' ], self::$version );
            
        }
        
        /**
         * Agama Setup
         *
         * @since 1.0
         */
        function agama_setup() {
            /*
             * Makes Agama available for translation.
             */
            load_theme_textdomain( 'agama', AGAMA_DIR . 'languages' );

            // This theme styles the visual editor with editor-style.css to match the theme style.
            add_editor_style();

            // Adds support for title tag
            add_theme_support( 'title-tag' );

            // Adds RSS feed links to <head> for posts and comments.
            add_theme_support( 'automatic-feed-links' );
            
            // Customize Selective Refresh Widgets
            add_theme_support( 'customize-selective-refresh-widgets' );

            // This theme supports a variety of post formats.
            add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

            register_nav_menu( 'top', __( 'Top Menu', 'agama' ) );
            register_nav_menu( 'primary', __( 'Primary Menu', 'agama' ) );
            
            $custom_header_args = array(
                'default-text-color'     => '515151',
                'default-image'          => '',
                'height'                 => 960,
                'width'                  => 1920,
                'max-width'              => 2000,
                'flex-height'            => true,
                'flex-width'             => true,
                'random-default'         => true,
            );

            add_theme_support( 'custom-header', $custom_header_args );

            /*
             * This theme supports custom background color and image,
             * and here we also set up the default background color.
             */
            add_theme_support( 'custom-background', array(
                'default-color' => 'e6e6e6',
            ) );

            // This theme uses a custom image size for featured images, displayed on "standard" posts.
            add_theme_support( 'post-thumbnails' );
            set_post_thumbnail_size( 800, 9999 ); // Unlimited height, soft crop

            // Register custom image sizes
            add_image_size( 'agama-blog-large', 776, 310, true );
            add_image_size( 'agama-blog-medium', 320, 202, true );
            add_image_size( 'agama-blog-small', 400, 300, true );
            add_image_size( 'agama-related-img', 180, 138, true );
            add_image_size( 'agama-recent-posts', 700, 441, true );

            /*
             * Declare WooCommerce Support
             */
            add_theme_support( 'woocommerce' );
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
        
        /**
         * Register the required plugins for this theme.
         *
         * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
         *
         * @since 1.3.5
         */
        function tgmpa_register() {

            /**
             * Array of plugin arrays. Required keys are name and slug.
             * If the source is NOT from the .org repo, then source is also required.
             */
            $plugins = array(
                array(
                    'name'              => 'Elementor',
                    'slug'              => 'elementor',
                    'required'          => false,
                    'force_activation'  => false
                )
            );
            
            /**
             * If Elementor plugin active let's suggest a Gold Addons
             * for Elementor plugin.
             */
            if( is_plugin_active( 'elementor/elementor.php' ) ) {
                $additional = array(
                    array(
                        'name'              => 'Gold Addons for Elementor',
                        'slug'              => 'gold-addons-for-elementor',
                        'required'          => false,
                        'force_activation'  => false
                    )
                );
                
                $plugins = array_merge( $plugins, $additional );
            }

            /*
             * Array of configuration settings. Amend each line as needed.
             *
             * TGMPA will start providing localized text strings soon. If you already have translations of our standard
             * strings available, please help us make TGMPA even better by giving us access to these translations or by
             * sending in a pull-request with .po file(s) with the translations.
             *
             * Only uncomment the strings in the config array if you want to customize the strings.
             */
            $config = array(
                'id'           => 'agama',                 // Unique ID for hashing notices for multiple instances of TGMPA.
                'default_path' => '',                      // Default absolute path to bundled plugins.
                'menu'         => 'tgmpa-install-plugins', // Menu slug.
                'has_notices'  => true,                    // Show admin notices or not.
                'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
                'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
                'is_automatic' => false,                   // Automatically activate plugins after installation or not.
                'message'      => '',                      // Message to output right before the plugins table.
                'strings'      => array(
                    'page_title'                      => esc_html__( 'Install Required Plugins', 'agama' ),
                    'menu_title'                      => esc_html__( 'Install Plugins', 'agama' ),
                    'installing'                      => esc_html__( 'Installing Plugin: %s', 'agama' ),
                    'updating'                        => esc_html__( 'Updating Plugin: %s', 'agama' ),
                    'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'agama' ),
                    'notice_can_install_required'     => _n_noop(
                        'This theme requires the following plugin: %1$s.',
                        'This theme requires the following plugins: %1$s.',
                        'agama'
                    ),
                    'notice_can_install_recommended'  => _n_noop(
                        'This theme recommends the following plugin: %1$s.',
                        'This theme recommends the following plugins: %1$s.',
                        'agama'
                    ),
                    'notice_ask_to_update'            => _n_noop(
                        'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                        'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                        'agama'
                    ),
                    'notice_ask_to_update_maybe'      => _n_noop(
                        'There is an update available for: %1$s.',
                        'There are updates available for the following plugins: %1$s.',
                        'agama'
                    ),
                    'notice_can_activate_required'    => _n_noop(
                        'The following required plugin is currently inactive: %1$s.',
                        'The following required plugins are currently inactive: %1$s.',
                        'agama'
                    ),
                    'notice_can_activate_recommended' => _n_noop(
                        'The following recommended plugin is currently inactive: %1$s.',
                        'The following recommended plugins are currently inactive: %1$s.',
                        'agama'
                    ),
                    'install_link'                    => _n_noop(
                        'Begin installing plugin',
                        'Begin installing plugins',
                        'agama'
                    ),
                    'update_link' 					  => _n_noop(
                        'Begin updating plugin',
                        'Begin updating plugins',
                        'agama'
                    ),
                    'activate_link'                   => _n_noop(
                        'Begin activating plugin',
                        'Begin activating plugins',
                        'agama'
                    ),
                    'return'                          => __( 'Return to Required Plugins Installer', 'agama' ),
                    'plugin_activated'                => __( 'Plugin activated successfully.', 'agama' ),
                    'activated_successfully'          => __( 'The following plugin was activated successfully:', 'agama' ),
                    'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'agama' ),
                    'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'agama' ),
                    'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'agama' ),
                    'dismiss'                         => __( 'Dismiss this notice', 'agama' ),
                    'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'agama' ),
                    'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'agama' ),

                    'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
                )
            );

            tgmpa( $plugins, $config );
        }

        /**
         * Enqueue Script for IE versions
         *
         * @since Agama v1.0.2
         */
        function IE_Scripts() {
            global $wp_scripts;
            echo '<!--[if lt IE 9]><script src="' . AGAMA_JS . 'min/html5.min.js"></script><![endif]-->'; // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions.
        }

        /**
         * Enqueue Footer Scripts
         *
         * @since Agama v1.0.3
         */
        function footer_scripts() {
            if( get_theme_mod( 'agama_nicescroll', false ) ) {
                echo '
                <script>
                jQuery(document).ready(function($) {
                    $("html").niceScroll({
                        cursorwidth:"10px",
                        cursorborder:"1px solid #333",
                        zindex:"9999"
                    });
                });
                </script>
                ';
            }
        }

        /**
         * Migrate Theme Options
         *
         * @since 1.3.0
         */
        function migrate_options() {
            // If current theme version is bigger than "1.2.9.1" apply next updates.
            if( version_compare( '1.2.9.1', self::$version, '<' ) ) {
                if( ! get_option( '_agama_1291_migrated' ) ) {
                    $nav_color 			= esc_attr( get_theme_mod( 'agama_header_nav_color', '#fe6663' ) );
                    $nav_hover_color	= esc_attr( get_theme_mod( 'agama_header_nav_hover_color', '#000' ) );
                    set_theme_mod( 'agama_nav_top_color', $nav_color );
                    set_theme_mod( 'agama_nav_top_hover_color', $nav_hover_color );
                    set_theme_mod( 'agama_nav_primary_color', $nav_color );
                    set_theme_mod( 'agama_nav_primary_hover_color', $nav_hover_color );
                    update_option( '_agama_1291_migrated', true );
                }
            }
            // If current theme version is bigger than "1.3.0" apply next updates.
            // Migrate Custom CSS code to WP Additional CSS.
            if( version_compare( '1.3.0', self::$version, '<' ) ) {
                if( ! get_option( '_agama_130_migrated' ) ) {
                    $custom_css = esc_attr( get_theme_mod( 'agama_custom_css', '' ) );
                    if( ! empty( $custom_css ) ) {
                        wp_update_custom_css_post( $custom_css );
                        update_option( '_agama_130_migrated', true );
                    }
                }
            }
        }
    }
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

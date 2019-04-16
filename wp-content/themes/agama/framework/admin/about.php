<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * About Agama Page under Appearances
 *
 * @since Agama v1.0.1
 */
if( ! class_exists( 'Agama_About' ) ) {
	class Agama_About
	{
		/**
		 * Class Constructor
		 *
		 * @since Agama v1.0.1
		 */
		public function __construct() {
            
			add_action('admin_menu', array( $this, 'register_page' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            
		}
        
        function admin_enqueue_scripts() {
            $screen = get_current_screen();
            if( $screen->base == 'appearance_page_about-agama' ) {
                wp_enqueue_style( 'agama-about', AGAMA_CSS . 'backend-about.css', array(), agama_version );
            }
        }
		
		/**
		 * Register 'About Agama' Page
		 *
		 * @since Agama v1.0.1
		 */
		public function register_page() {
			add_theme_page( 
				__( 'About Agama', 'agama' ), 
				__( 'About Agama', 'agama' ), 
				'edit_theme_options', 
				'about-agama', 
				array( $this, 'render_page' )
			);
		}
		
		/**
		 * Render 'About Agama' Page
		 *
		 * @since Agama v1.0.1
		 */
		public function render_page() {
            $FuryScreenURI = AGAMA_IMG . 'promo/themevision-logo.jpg';
            $readme = AGAMA_DIR . 'README.txt';
			echo '<div class="wrap about-wrap">';
				echo '<h1>'.sprintf( __( 'Welcome to Agama v%s', 'agama' ), agama_version ).'</h1>';
				
				echo '<div class="about-text">';
					echo __( 'Thank you for using Agama theme!', 'agama' ).'<br>';
					echo __( 'Consider supporting us with any desired amount donation.', 'agama' ).'<br>';
					echo __( 'Even the smallest donation amount means a lot for us.', 'agama' ).'<br>';
					echo __( 'With donations you are helping us for faster theme development & updates.', 'agama' ).'<br><br>';
					echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
							<div class="paypal-donations">
							<input type="hidden" name="cmd" value="_donations">
							<input type="hidden" name="bn" value="TipsandTricks_SP">
							<input type="hidden" name="business" value="paypal@theme-vision.com">
							<input type="hidden" name="return" value="https://www.theme-vision.com/thank-you/">
							<input type="hidden" name="item_name" value="Agama development support.">
							<input type="hidden" name="rm" value="0">
							<input type="hidden" name="currency_code" value="USD">
							<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online.">
							<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" style="height: 1px;">
							</div>
						</form>';
					
					echo __( 'or', 'agama' ).'<br>';
					
					echo sprintf( __( 'You can support us by Buying an %s theme with allot new features & extensions!', 'agama' ), '<a href="http://theme-vision.com/agama-pro/" title="Agama Pro" target="_blank">AgamaPRO</a>' );
				echo '</div>';
				
				echo '<h2 class="nav-tab-wrapper">';
					echo '<a class="nav-tab nav-tab-active">'.__( 'What\'s New', 'agama' ).'</a>';
				echo '</h2>';
				
				echo '<div class="changelog point-releases">';
					echo '<h3>'. esc_html__( 'Theme Changelog', 'agama' ) .'</h3>';
                    if( file_exists( $readme ) ) {
                        echo '<textarea rows="15" cols="170">';
                            foreach( file( $readme ) as $text ) {
                                echo $text;
                            }
                        echo '</textarea>';
                    }
				echo '</div>';
            
                echo '<h2 class="nav-tab-wrapper vision-themes">';
					echo '<a class="nav-tab nav-tab-active">'.__( 'Our Themes', 'agama' ).'</a>';
				echo '</h2>';
            
                echo '<div id="vision-install-plugins" class="vision-important-notice vision-other-products">';
                    echo '<div class="under-the-hood three-col">';
                        echo '<div class="plugin  col">';
                            echo '<div class="plugin-wrapper">';
                                echo '<div class="plugin-screenshot">';
                                    echo '<img src="'. esc_url( $FuryScreenURI ) .'" alt="'. esc_attr__( 'Fury Theme', 'agama' ) .'">';
                                    echo '<div class="plugin-info">';
                                        echo '<a href="http://furytheme.com/" target="_blank">'. esc_html__( 'Demo', 'agama' ) .'</a>';
                                    echo '</div>';
                                echo '</div>';
                                echo '<h3 class="plugin-name">'. esc_html__( 'Fury Theme', 'agama' ) .'</h3>';
                                echo '<div class="plugin-actions"><a href="https://wordpress.org/themes/fury/" target="_blank" class="button button-primary">'. esc_html__( 'Download', 'agama' ) .'</a></div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
				
			echo '</div>';
		}
	}
	new Agama_About;
}
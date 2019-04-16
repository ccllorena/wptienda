<?php
/**
 * Plugin Name:	Storefront Mega Menus
 * Plugin URI: http://woothemes.com/products/storefront-mega-menus/
 * Description:	Create enhanced full width dropdowns that seamlessly tie into your Storefront powered WooCommerce shop.
 * Version:	1.2.0
 * Author: WooThemes
 * Author URI: http://woothemes.com/
 * Requires at least: 4.3.0
 * Tested up to: 4.3.1
 * License: GPL v3
 *
 * Text Domain: storefront-mega-menus
 * Domain Path: /languages/
 *
 * @package Storefront_Mega_Menus
 * @category Core
 * @author Tiago Noronha
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '27c387cee97bb0158bd67fff737a2f5e', '1323786' );

/**
 * Returns the main instance of Storefront_Mega_Menus to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Storefront_Mega_Menus
 */
function Storefront_Mega_Menus() {
	return Storefront_Mega_Menus::instance();
} // End Storefront_Mega_Menus()

Storefront_Mega_Menus();

/**
 * Main Storefront_Mega_Menus Class
 *
 * @class Storefront_Mega_Menus
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Mega_Menus
 */
final class Storefront_Mega_Menus {
	/**
	 * Storefront_Mega_Menus The single instance of Storefront_Mega_Menus.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token		= 'storefront-mega-menus';
		$this->plugin_url	= plugin_dir_url( __FILE__ );
		$this->plugin_path	= plugin_dir_path( __FILE__ );
		$this->version		= '1.2.0';

		// Register activation hook.
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Include all the necessary files.
		$this->setup();
	}

	/**
	 * Main Storefront_Mega_Menus Instance
	 *
	 * Ensures only one instance of Storefront_Mega_Menus is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Storefront_Mega_Menus()
	 * @return Main Storefront_Mega_Menus instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'storefront-mega-menus', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();

		// Get theme customizer url.
		$url = admin_url() . 'customize.php?';
		$url .= 'url=' . urlencode( site_url() . '?storefront-mega-menus=true' );
		$url .= '&return=' . urlencode( admin_url() . 'plugins.php' );
		$url .= '&storefront-mega-menus=true';
		$notices 		= get_option( 'sd_activation_notice', array() );
		$notices[]		= sprintf( __( '%sThanks for installing the Storefront Mega Menus extension. To get started, visit the %sCustomizer%s.%s', 'storefront-mega-menus' ), '<p>', '<a href="' . $url . '">', '</a>', '</p>' );
		update_option( 'smm_activation_notice', $notices );
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Include all the necessary files.
	 * Only executes if Storefront or a child theme using Storefront as a parent is active and the extension specific filter returns true.
	 * Child themes can disable this extension using the storefront_mega_menus_supported filter
	 * @access  public
	 * @since   1.0.0
	 */
	public function setup() {
		$theme = wp_get_theme();

		/*
		Include admin all the time so that the SMM Sidebar stays
		registered even if you switch to a non supported theme.
		*/
		include_once( 'includes/class-smm-admin.php' );

		if ( 'Storefront' === $theme->name || 'storefront' === $theme->template && apply_filters( 'storefront_mega_menus_supported', true ) ) {
			add_action( 'admin_notices', array( $this, 'customizer_notice' ) );

			include_once( 'includes/class-smm-customizer.php' );
			include_once( 'includes/class-smm-frontend.php' );
		}
	}

	/**
	 * Display a notice linking to the Customizer
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function customizer_notice() {
		$notices = get_option( 'smm_activation_notice' );
		if ( $notices = get_option( 'smm_activation_notice' ) ) {
			foreach ( $notices as $notice ) {
				echo '<div class="updated">' . $notice . '</div>';
			}
			delete_option( 'smm_activation_notice' );
		}
	}
} // End Class

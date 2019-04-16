<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Storefront Mega Menus Customizer.
 *
 * @package  Storefront_Mega_Menus/Customizer
 * @category Class
 * @author   Tiago Noronha
 */
class SMM_Customizer {
	/**
	 * Constructor.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_scripts' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_configurator' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_templates' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'load_data' ) );
		add_action( 'init', array( $this, 'ajax_actions' ) );

		add_action( 'customize_register', array( $this, 'customizer_preview' ), 1 );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ), 999 );

		add_filter( 'pre_update_option_sidebars_widgets', array( $this, 'sidebars_widgets_save' ), 10, 2 );
		add_filter( 'option_sidebars_widgets', array( $this, 'sidebars_widgets_load' ) );
	}

	/**
	 * Register Ajax actions.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function ajax_actions() {
		add_action( 'wp_ajax_smm-save-data', array( $this, 'save_data' ) );
	}

	/**
	 * Class file and filters to build dynamic settings to be used on the site preview.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function customizer_preview() {
		include_once( 'class-smm-setting.php' );

		add_filter( 'customize_dynamic_setting_args', array( $this, 'filter_dynamic_setting_args' ), 10, 2 );
		add_filter( 'customize_dynamic_setting_class', array( $this, 'filter_dynamic_setting_class' ), 10, 3 );
	}

	/**
	 * Enqueue scripts and stylesheets.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_customizer_scripts() {
		// Style.
		wp_enqueue_style( 'storefront-mega-menus-gridstack', Storefront_Mega_Menus()->plugin_url . 'assets/css/gridstack.css', array(), Storefront_Mega_Menus()->version, 'all' );
		wp_enqueue_style( 'storefront-mega-menus-customizer', Storefront_Mega_Menus()->plugin_url . 'assets/css/customizer.css', array(), Storefront_Mega_Menus()->version, 'all' );

		// Vendor.
		wp_enqueue_script( 'storefront-mega-menus-customizer-gridstack', Storefront_Mega_Menus()->plugin_url . 'assets/js/vendor/gridstack.min.js', array( 'jquery', 'jquery-ui-draggable', 'jquery-ui-resizable', 'jquery-ui-widget', 'jquery-ui-mouse', 'underscore' ), Storefront_Mega_Menus()->version );

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			wp_enqueue_script( 'storefront-mega-menus-customizer-model-widget', Storefront_Mega_Menus()->plugin_url . 'assets/js/models/widget.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
			wp_enqueue_script( 'storefront-mega-menus-customizer-collection-widgets', Storefront_Mega_Menus()->plugin_url . 'assets/js/collections/widgets.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
			wp_enqueue_script( 'storefront-mega-menus-customizer-model-mega-menu', Storefront_Mega_Menus()->plugin_url . 'assets/js/models/mega-menu.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
			wp_enqueue_script( 'storefront-mega-menus-collection-mega-menus', Storefront_Mega_Menus()->plugin_url . 'assets/js/collections/mega-menus.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
			wp_enqueue_script( 'storefront-mega-menus-customizer-view-customizer', Storefront_Mega_Menus()->plugin_url . 'assets/js/views/customizer.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
			wp_enqueue_script( 'storefront-mega-menus-customizer-view-configurator', Storefront_Mega_Menus()->plugin_url . 'assets/js/views/configurator.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
			wp_enqueue_script( 'storefront-mega-menus-customizer-view-widget', Storefront_Mega_Menus()->plugin_url . 'assets/js/views/widget.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
			wp_enqueue_script( 'storefront-mega-menus-customizer-view-add-widgets', Storefront_Mega_Menus()->plugin_url . 'assets/js/views/add-widgets.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
			wp_enqueue_script( 'storefront-mega-menus-customizer-js', Storefront_Mega_Menus()->plugin_url . 'assets/js/storefront-mega-menus.js', array( 'jquery', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
		} else {
			wp_enqueue_script( 'storefront-mega-menus-customizer-js', Storefront_Mega_Menus()->plugin_url . 'assets/js/storefront-mega-menus.min.js', array( 'jquery', 'jquery-ui-draggable', 'jquery-ui-resizable', 'jquery-ui-widget', 'jquery-ui-mouse', 'underscore', 'wp-backbone', 'customize-controls', 'nav-menu' ), Storefront_Mega_Menus()->version );
		}
	}

	/**
	 * Add hooks for the Customizer preview.
	 * @access 	public
	 * @since 	1.0.0
	 * @return 	void
	 */
	public function customize_preview_init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'customize_preview_enqueue' ) );
	}

	/**
	 * Enqueue scripts for the Customizer preview.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function customize_preview_enqueue() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script( 'storefront-mega-menus-customizer-preview-js', Storefront_Mega_Menus()->plugin_url . 'assets/js/storefront-mega-menus-preview' . $suffix . '.js', array( 'jquery','customize-preview' ), Storefront_Mega_Menus()->version, true );
	}

	/**
	 * Container for configurator panel.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function print_configurator() {
	?>
		<div id="smm-configurator">
			<div class="smm-actions">
			</div>

			<div class="smm-gridstack-wrapper smm-grid-empty">
				<p class="smm-grid-empty-notice"><?php esc_attr_e( 'Add a widget to start. Drag and drop to re-arrange their display order. Adjust the width of each widget by dragging its edges.', 'storefront-mega-menus' ); ?></p>
				<div class="grid-stack smm-gridstack"></div>
			</div>

			<button type="button" class="button-secondary smm-add-new-widget"><span><?php esc_attr_e( 'Add a Widget', 'storefront-mega-menus' ); ?></span></button>
			<div class="smm-add-widgets">
				<div class="smm-widgets-filter">
					<label class="screen-reader-text" for="smm-widgets-filter"><?php esc_attr_e( 'Search Widgets', 'storefront-mega-menus' ); ?></label>
					<input type="search" id="smm-widgets-filter" placeholder="<?php esc_attr_e( 'Search widgets&hellip;', 'storefront-mega-menus' ); ?>" />
				</div>
				<div class="smm-widgets-list"></div>
			</div>
		</div>
	<?php
	}

	/**
	 * Template for the configurator side panel.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function print_templates() {
		?>
		<script type="text/html" id="tmpl-smm-configurator">
			<div class="smm-section-title">
				<h1 class="smm-item-title"><?php printf( esc_html__( '%sMega Menu%s: {{ data.item_title }}', 'storefront-mega-menus' ), '<strong>', '</strong>' ); ?></h1>
				<span class="smm-enable-checkbox">
					<label for="smm-enable-mega-menu-{{ data.item_id }}">
						<input type="checkbox" id="smm-enable-mega-menu-{{ data.item_id }}" class="smm-enable-mega-menu" value="_blank" name="smm-enable-mega-menu" {{ data.checked }}>
						<?php esc_attr_e( 'Enable', 'storefront-mega-menus' ); ?>
					</label>
				</span>
			</div>
			<p class="smm-notice"><?php esc_attr_e( 'A Mega Menu can only be added to a top level menu item. This Mega Menu will not be displayed.', 'storefront-mega-menus' ); ?></p>
		</script>
		<?php
	}

	/**
	 * Load saved data.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_data() {
		$savedMegaMenus = array();

		$savedData = get_option( 'SMM_DATA' );
		$active_sidebars = get_option( 'sidebars_widgets' );

		if ( $savedData ) {
			foreach ( $savedData as $item => $menu ) {
				if ( is_nav_menu_item( $item ) ) {
					$widgets = array();

					if ( is_array( $menu['widgets'] ) ) {
						foreach ( $menu['widgets'] as $widget ) {
							if ( in_array( $widget['id'], $active_sidebars['smm-sidebar'] ) ) {
								$widgets[] = $widget;
							}
						}
					}

					$savedMegaMenus[] = array(
						'item_id'	=> $item,
						'active'	=> $menu['active'],
						'widgets'	=> $widgets,
					);
				}
			}
		}

		$settings = array(
			'nonce'				=> wp_create_nonce( 'smm-ajax-nonce' ),
			'savedMegaMenus'	=> $savedMegaMenus,
			'l10n'				=> array(
				'mega_menu'		=> __( 'Mega Menu', 'storefront-mega-menus' ),
			),
		);

		$data = sprintf( 'var _wpCustomizeSMMSettings = %s;', wp_json_encode( $settings ) );
		wp_scripts()->add_data( 'storefront-mega-menus-customizer-js', 'data', $data );
	}

	/**
	 * Save data.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function save_data() {
		if ( ! is_user_logged_in() ) {
			wp_die( 0 );
		}

		check_ajax_referer( 'smm-ajax-nonce', 'nonce' );

		if ( ! isset( $_POST['data'] ) ) {
			return;
		}

		$savedData = get_option( 'SMM_DATA', array() );

		// Loop through data and also sanitize it.
		foreach ( wp_unslash( $_POST['data'] ) as $k => $v ) {
			if ( ! isset( $v['item_id'] ) || '' === $v['item_id'] ) {
				continue;
			}

			$item_id = intval( $v['item_id'] );

			$mega_menu = array();

			if ( isset( $v['active'] ) && true === json_decode( $v['active'] ) ) {
				$mega_menu['active'] = true;
			} else {
				$mega_menu['active'] = false;
			}

			$mega_menu['widgets'] = array();

			if ( isset( $v['widgets'] ) && is_array( $v['widgets'] ) ) {
				foreach ( $v['widgets'] as $widget ) {
					if ( ! isset( $widget['id'] ) || '' === sanitize_text_field( $widget['id'] ) ) {
						continue;
					}

					$mega_menu['widgets'][] = array(
						'id'	=> sanitize_text_field( $widget['id'] ),
						'x'		=> intval( $widget['x'] ),
						'y'		=> intval( $widget['y'] ),
						'w'		=> intval( $widget['w'] ),
						'h'		=> intval( $widget['h'] ),
					);
				}
			}

			if ( array_key_exists( $item_id, $savedData ) ) {
				unset( $savedData[ $item_id ] );
			}

			$savedData[ $item_id ] = $mega_menu;
		}

		if ( ! empty( $savedData ) ) {
			update_option( 'SMM_DATA', $savedData );
		}

		wp_send_json_success();
	}

	/**
	 * Stops WordPress from saving our custom sidebar data into the default
	 * sidebars_widgets option.
	 * @access  public
	 * @since   1.2.0
	 * @return  array
	 */
	public function sidebars_widgets_save( $sidebars ) {
		if ( array_key_exists( 'smm-sidebar', $sidebars ) ) {
			unset( $sidebars['smm-sidebar'] );
		}

		return $sidebars;
	}

	/**
	 * Load our custom data when looking for Sidebars and Widgets.
	 * @access  public
	 * @since   1.2.0
	 * @return  array
	 */
	public function sidebars_widgets_load( $sidebars ) {
		$smm_widgets = $this->get_smm_widgets();

		if ( ! empty( $smm_widgets ) ) {
			$sidebars['smm-sidebar'] = $smm_widgets;

			if ( array_key_exists( 'wp_inactive_widgets', $sidebars ) ) {
				foreach ( $sidebars['wp_inactive_widgets'] as $key => $widget ) {
					if ( in_array( $widget, $smm_widgets ) ) {
						unset( $sidebars['wp_inactive_widgets'][ $key ] );
					}
				}
			}
		}

		return $sidebars;
	}

	/**
	 * Get all widgets assigned to a Mega Menu location.
	 * @access  public
	 * @since   1.0.0
	 * @return  array
	 */
	public function get_smm_widgets() {
		$smm_widgets = array();

		$savedData = get_option( 'SMM_DATA' );

		if ( $savedData ) {
			foreach ( $savedData as $item => $menu ) {
				if ( is_nav_menu_item( $item ) ) {
					$widgets = $menu['widgets'];
					if ( is_array( $widgets ) ) {
						foreach ( $widgets as $widget ) {
							$smm_widgets[] = $widget['id'];
						}
					}
				}
			}
		}

		return $smm_widgets;
	}

	/**
	 * Filter a dynamic setting's constructor args.
	 *
	 * For a dynamic setting to be registered, this filter must be employed
	 * to override the default false value with an array of args to pass to
	 * the WP_Customize_Setting constructor.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @param 	false|array $setting_args The arguments to the WP_Customize_Setting constructor.
	 * @param 	string      $setting_id   ID for dynamic setting, usually coming from `$_POST['customized']`.
	 * @return 	array|false
	 */
	public function filter_dynamic_setting_args( $setting_args, $setting_id ) {
		if ( preg_match( SMM_Mega_Menu_Setting::ID_PATTERN, $setting_id ) ) {
			$setting_args = array(
				'type' => SMM_Mega_Menu_Setting::TYPE,
			);
		}
		return $setting_args;
	}

	/**
	 * Allow non-statically created settings to be constructed with custom WP_Customize_Setting subclass.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @param 	string $setting_class WP_Customize_Setting or a subclass.
	 * @param 	string $setting_id    ID for dynamic setting, usually coming from `$_POST['customized']`.
	 * @param 	array  $setting_args  WP_Customize_Setting or a subclass.
	 * @return 	string
	 */
	public function filter_dynamic_setting_class( $setting_class, $setting_id, $setting_args ) {
		unset( $setting_id );

		if ( ! empty( $setting_args['type'] ) && SMM_Mega_Menu_Setting::TYPE === $setting_args['type'] ) {
			$setting_class = 'SMM_Mega_Menu_Setting';
		}
		return $setting_class;
	}
}

new SMM_Customizer();

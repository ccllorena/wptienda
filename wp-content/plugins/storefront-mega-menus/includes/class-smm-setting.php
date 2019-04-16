<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Mega Menu Setting class.
 *
 * @package  Storefront_Mega_Menus/Setting
 * @category Class
 * @author   Tiago Noronha
 */
class SMM_Mega_Menu_Setting extends WP_Customize_Setting {
	const ID_PATTERN = '/^mega_menu\[(?P<id>-?\d+)\]$/';

	const TYPE = 'mega_menu';

	/**
	 * Setting type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $type = self::TYPE;

	/**
	 * Default transport.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $transport = 'refresh';

	/**
	 * Whether or not preview() was called.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var bool
	 */
	protected $is_previewed = false;

	/**
	 * Storage of pre-setup menu item to prevent wasted calls to wp_setup_nav_menu_item().
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array
	 */
	protected $value;

	/**
	 * Id of the menu item being previewed.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array
	 */
	protected $item_id;

	/**
	 * Handle previewing the setting.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see WP_Customize_Manager::post_value()
	 */
	public function preview() {
		if ( $this->is_previewed ) {
			return;
		}

		$this->is_previewed = true;

		$undefined  = new stdClass(); // Symbol.

		$post_value = $this->post_value( $undefined );

		if ( $undefined === $post_value ) {
			$value = $this->_original_value;
		} else {
			$value = $post_value;
		}

		$this->value = $value;

		if ( ! isset( $this->value['item_id'] ) || '' === $this->value['item_id'] ) {
			return;
		}

		$this->item_id = intval( $this->value['item_id'] );

		add_filter( 'default_option_SMM_DATA', array( $this, 'filter_smm_data' ) );
		add_filter( 'option_SMM_DATA', array( $this, 'filter_smm_data' ) );
		add_filter( 'nav_menu_css_class', array( $this, 'add_dropdown_preview_class' ), 10, 4 );
	}

	/**
	 * Nothing to update.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see WP_Customize_Manager::update()
	 */
	public function update( $value ) {}

	/**
	 * Sanitize data from customizer to be used on a filter.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function filter_smm_data( $data ) {
		if ( ! $data ) {
			$smm_data = array();
		} else {
			$smm_data = $data;
		}

		$preview_data = $this->value;

		$mega_menu = array();

		if ( isset( $preview_data['active'] ) && true === $preview_data['active'] ) {
			$mega_menu['active'] = true;
		} else {
			$mega_menu['active'] = false;
		}

		$mega_menu['widgets'] = array();

		if ( isset( $preview_data['widgets'] ) && is_array( $preview_data['widgets'] ) ) {
			foreach ( $preview_data['widgets'] as $widget ) {
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

		if ( ! empty( $mega_menu ) ) {
			if ( array_key_exists( $this->item_id, $smm_data ) ) {
				unset( $smm_data[ $this->item_id ] );
			}

			$smm_data[ $this->item_id ] = $mega_menu;
		}

		return $smm_data;
	}

	public function add_dropdown_preview_class( $classes, $item, $args, $depth ) {
		if ( $this->item_id === $item->ID ) {
			$classes[] = 'smm-doing-preview';
		}

		return array_filter( $classes );
	}
}

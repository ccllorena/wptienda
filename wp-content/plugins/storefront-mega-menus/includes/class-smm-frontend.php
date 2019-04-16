<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Storefront Mega Menus Frontend.
 *
 * @package  Storefront_Mega_Menus/Frontend
 * @category Class
 * @author   Tiago Noronha
 */
class SMM_Frontend {
	/**
	 * Constructor.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		add_filter( 'walker_nav_menu_start_el', array( $this, 'nav_menu_start_el' ), 10, 4 );
		add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 10, 2 );
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ), 10, 4 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_storefront_specific_styles' ), 130 );
	}

	/**
	 * Enqueue frontend scripts.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_style( 'storefront-mega-menus-frontend', Storefront_Mega_Menus()->plugin_url . 'assets/css/frontend.css', array(), Storefront_Mega_Menus()->version, 'all' );
	}

	/**
	 * Adds the Storefront specific styles
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_storefront_specific_styles() {
		$theme 	= wp_get_theme();

		if ( 'Storefront' === $theme->name || 'storefront' === $theme->template ) {
			$header_background_color 		= get_theme_mod( 'storefront_header_background_color', apply_filters( 'storefront_default_header_background_color', '#2c2d33' ) );
			$header_text_color 				= get_theme_mod( 'storefront_header_text_color', apply_filters( 'storefront_default_header_text_color', '#9aa0a7' ) );
			$header_link_color 				= get_theme_mod( 'storefront_header_link_color', apply_filters( 'storefront_default_header_link_color', '#ffffff' ) );
			$button_background_color 		= get_theme_mod( 'storefront_button_background_color', apply_filters( 'storefront_default_button_background_color', '#60646c' ) );
			$button_text_color 				= get_theme_mod( 'storefront_button_text_color', apply_filters( 'storefront_default_button_text_color', '#ffffff' ) );
			$button_alt_background_color 	= get_theme_mod( 'storefront_button_alt_background_color', apply_filters( 'storefront_default_button_alt_background_color', '#96588a' ) );
			$button_alt_text_color 			= get_theme_mod( 'storefront_button_alt_text_color', apply_filters( 'storefront_default_button_alt_text_color', '#ffffff' ) );

			$brighten_factor 				= apply_filters( 'storefront_brighten_factor', 25 );
			$darken_factor 					= apply_filters( 'storefront_darken_factor', -25 );

			$style 							= '
			.smm-mega-menu {
				background-color: ' . $header_background_color . ';
			}

			.main-navigation ul li.smm-active .smm-mega-menu a.button {
				background-color: ' . $button_background_color . ' !important;
				border-color: ' . $button_background_color . ' !important;
				color: ' . $button_text_color . ' !important;
			}

			.main-navigation ul li.smm-active .smm-mega-menu a.button:hover {
				background-color: ' . storefront_adjust_color_brightness( $button_background_color, $darken_factor ) . ' !important;
				border-color: ' . storefront_adjust_color_brightness( $button_background_color, $darken_factor ) . ' !important;
				color: ' . $button_text_color . ' !important;
			}

			.main-navigation ul li.smm-active .smm-mega-menu a.added_to_cart {
				background-color: ' . $button_alt_background_color . ' !important;
				border-color: ' . $button_alt_background_color . ' !important;
				color: ' . $button_alt_text_color . ' !important;
			}

			.main-navigation ul li.smm-active .smm-mega-menu a.added_to_cart:hover {
				background-color: ' . storefront_adjust_color_brightness( $button_alt_background_color, $darken_factor ) . ' !important;
				border-color: ' . storefront_adjust_color_brightness( $button_alt_background_color, $darken_factor ) . ' !important;
				color: ' . $button_alt_text_color . ' !important;
			}

			.main-navigation ul li.smm-active .widget h3.widget-title {
				color: ' . $header_text_color . ';
			}


			.main-navigation ul li.smm-active li:hover a {
				color: ' . $header_link_color . ' !important;
			}

			/**
			 * @todo remove this when Storefront 1.6.0 is live.
			 */
			.site-header {
				color: ' . $header_text_color . ';
			}';

			wp_add_inline_style( 'storefront-mega-menus-frontend', $style );
		}
	}

	/**
	 * Add a Mega Menu to a specific menu item.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function nav_menu_start_el( $item_output, $item, $depth, $args ) {
		if ( $this->_is_mega_menu( $item->ID ) && 0 === $depth && 'primary' === $args->theme_location ) {
			$html = $this->create_mega_menu( $item->ID );
			if ( '' !== $html ) {
				$item_output .= $html;
			}
		}

		return $item_output;
	}

	/**
	 * Build Mega Menu output.
	 * @access  public
	 * @since   1.0.0
	 * @return  string
	 */
	public function create_mega_menu( $id ) {
		$html		= '';
		$rows_html	= '';
		$mega_menu	= $this->_get_mega_menu( $id );
		$rows		= $this->_get_rows( $mega_menu['widgets'] );

		foreach ( $rows as $row ) {
			if ( empty( $row ) ) {
				continue;
			}

			$row_html		= '';
			$count			= 0;
			$columns		= 0;
			$max_columns	= 12;
			$widget_count	= count( $row );
			$widgets		= $this->_sort_wigets_by_position( $row );

			foreach ( $widgets as $widget ) {
				$widget_content = $this->_do_widget( $widget['id'] );

				if ( $widget_content ) {
					$count++;
				} else {
					$widget_count--;
					continue;
				}

				// Used to calculate empty columns between widgets.
				$empty = 0;

				// Init array for widget row classes.
				$classes = array();

				// Calculate empty space between columns.
				if ( $columns < intval( $widget['x'] ) ) {
					$empty = intval( $widget['x'] ) - $columns;
				}

				// Add pre class and add empty columns to $columns var.
				if ( 0 < $empty ) {
					$classes[] = 'smm-pre-' . $empty;
					$columns = $columns + $empty;
				}

				$columns = $columns + intval( $widget['w'] );

				$classes[] = 'smm-span-' . intval( $widget['w'] );

				if ( $widget_count === $count ) {
					if ( $max_columns === $columns ) {
						$classes[] = 'smm-last';
					} else {
						$classes[] = 'smm-post-' . ( $max_columns - $columns );
					}

					$count = 0;
				}

				$row_html .= '<div class="' . implode( ' ', $classes ) . '">' . $widget_content . '</div>';
			}

			if ( '' !== $row_html ) {
				$rows_html .= '<div class="smm-row">' . $row_html . '</div>';
			}
		}

		if ( '' !== $rows_html ) {
			$html = '<ul class="sub-menu">
						<li>
							<div class="smm-mega-menu">
								' . $rows_html . '
							</div>
						</li>
					</ul>';
		}

		return $html;
	}

	/**
	 * Remove sub items that may be under a Mega Menu.
	 * @access  public
	 * @since   1.0.0
	 * @return  array
	 */
	public function wp_nav_menu_objects( $sorted_menu_items, $args ) {
		$parents = array();
		$to_remove = array();

		// Get all parents that are mega menus.
		foreach ( $sorted_menu_items as $item ) {
			if ( 0 === intval( $item->menu_item_parent ) && $this->_is_mega_menu( $item->ID ) ) {
				$parents[] = $item;
			}
		}

		// Get all items that are sub items of a parent.
		foreach ( $parents as $parent ) {
			$to_remove = array_merge( $to_remove, $this->_look_for_subitems( $parent->ID, $sorted_menu_items ) );
		}

		// Now remove these items from the main array.
		foreach ( $sorted_menu_items as $key => $item ) {
			if ( in_array( $item->ID, $to_remove ) ) {
				unset( $sorted_menu_items[ $key ] );
			}
		}

		$sorted_menu_items = array_values( $sorted_menu_items );

		return $sorted_menu_items;
	}

	/**
	 * Add a 'menu-item-has-children' class to mega menu item, if it doens't have it yet.
	 * @access  public
	 * @since   1.0.0
	 * @return  array
	 */
	public function nav_menu_css_class( $classes, $item, $args, $depth ) {
		if ( 0 === $depth && $this->_is_mega_menu( $item->ID ) ) {
			$classes[] = 'smm-active';

			if ( ! in_array( 'menu-item-has-children', $classes ) ) {
				$classes[] = 'menu-item-has-children';
			}
		}

		return array_filter( $classes );
	}

	/**
	 * Get a widget by widget id.
	 * Based on the "Widget Shortcode" plugin available at https://wordpress.org/plugins/widget-shortcode/
	 * @access  private
	 * @since   1.0.0
	 * @return  string
	 */
	private function _do_widget( $widget_id ) {
		global $_wp_sidebars_widgets, $wp_registered_widgets, $wp_registered_sidebars;

		$args = apply_filters( 'smm_do_widget_args', array(
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'	=> '<h3 class="widget-title">',
			'after_title'	=> '</h3>',
		) );

		if ( empty( $widget_id ) || ! isset( $wp_registered_widgets[ $widget_id ] ) ) {
			return;
		}

		// Get the widget instance options.
		$widget_number				= preg_replace( '/[^0-9]/', '', $widget_id );
		$options					= get_option( $wp_registered_widgets[ $widget_id ]['callback'][0]->option_name );
		$instance					= $options[ $widget_number ];
		$class						= get_class( $wp_registered_widgets[ $widget_id ]['callback'][0] );
		$widgets_map				= $this->_widget_shortcode_get_widgets_map();
		$_original_widget_position	= $widgets_map[ $widget_id ];

		// Maybe the widget is removed or deregistered.
		if ( ! $class ) {
			return;
		}

		if ( ! isset( $wp_registered_sidebars[ $_original_widget_position ] ) ) {
			return;
		}

		$params = array(
			'name'			=> $wp_registered_sidebars[ $_original_widget_position ]['name'],
			'id'			=> $wp_registered_sidebars[ $_original_widget_position ]['id'],
			'description'	=> $wp_registered_sidebars[ $_original_widget_position ]['description'],
			'before_widget'	=> $args['before_widget'],
			'before_title'	=> $args['before_title'],
			'after_title'	=> $args['after_title'],
			'after_widget'	=> $args['after_widget'],
			'widget_id'		=> $widget_id,
			'widget_name'	=> $wp_registered_widgets[ $widget_id ]['name'],
		);

		// Substitute HTML id and class attributes into before_widget.
		$class_name = '';

		foreach ( (array) $wp_registered_widgets[ $widget_id ]['classname'] as $cn ) {
			if ( is_string( $cn ) ) {
				$class_name .= '_' . $cn;
			} elseif ( is_object( $cn ) ) {
				$class_name .= '_' . get_class( $cn );
			}
		}

		$class_name = ltrim( $class_name, '_' );

		$params['before_widget'] = sprintf( $params['before_widget'], $widget_id, $class_name );

		// Render the widget.
		ob_start();
		the_widget( $class, $instance, $params );
		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Returns an array of all widgets as the key, their position as the value.
	 * @access  private
	 * @since   1.0.0
	 * @return  array
	 */
	private function _widget_shortcode_get_widgets_map() {
		$sidebars_widgets = wp_get_sidebars_widgets();
		$widgets_map = array();
		if ( ! empty( $sidebars_widgets ) ) {
			foreach ( $sidebars_widgets as $position => $widgets ) {
				if ( ! empty( $widgets ) ) {
					foreach ( $widgets as $widget ) {
						$widgets_map[ $widget ] = $position;
					}
				}
			}
		}

		return $widgets_map;
	}

	/**
	 * Given a menu item id, return the relevant option.
	 * @access  private
	 * @since   1.0.0
	 * @return  array
	 */
	private function _get_mega_menu( $item_id ) {
		if ( ! $item_id ) {
			return false;
		}

		$savedData = get_option( 'SMM_DATA' );

		if ( $savedData && array_key_exists( intval( $item_id ), $savedData ) ) {
			return $savedData[ intval( $item_id ) ];
		}

		return false;
	}

	/**
	 * Sort widgets by row.
	 * @access  private
	 * @since   1.0.0
	 * @return  array
	 */
	private function _get_rows( $mega_menu ) {
		$widget_rows = array();

		foreach ( $mega_menu as $widget ) {
			$widget_rows[ $widget['y'] ][] = $widget;
		}

		ksort( $widget_rows );

		return $widget_rows;
	}

	/**
	 * Sort widgets by their position on the grid.
	 * @access  private
	 * @since   1.0.0
	 * @return  array
	 */
	private function _sort_wigets_by_position( $widgets = array() ) {
		$ordered_widgets = array();

		foreach ( $widgets as $key => $widget ) {
			$ordered_widgets[ $key ] = $widget['x'];
		}

		array_multisort( $ordered_widgets, SORT_ASC, $widgets );

		return $widgets;
	}

	/**
	 * Given a menu item id, tell if it is a mega menu. Also returns false for mega menus that are not active.
	 * @access  private
	 * @since   1.0.0
	 * @return  boolean
	 */
	private function _is_mega_menu( $item_id ) {
		if ( ! $item_id ) {
			return false;
		}

		$savedData = get_option( 'SMM_DATA' );

		if ( $savedData && array_key_exists( intval( $item_id ), $savedData ) ) {
			$mega_menu = $savedData[ intval( $item_id ) ];

			if ( false === $mega_menu['active'] ) {
				return false;
			}

			if ( empty( $mega_menu['widgets'] ) ) {
				return false;
			}

			$widgets = $mega_menu['widgets'];

			if ( empty( $widgets ) ) {
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * Look for all the sub items of a parent item.
	 * @access  private
	 * @since   1.0.0
	 * @return  array
	 */
	private function _look_for_subitems( $parent, $items ) {
		$remove = array();
		foreach ( $items as $item ) {
			if ( $parent === intval( $item->menu_item_parent ) ) {
				$remove[] = $item->ID;
				$remove = array_merge( $remove, $this->_look_for_subitems( $item->ID, $items ) );
			}
		}
		return $remove;
	}
}

new SMM_Frontend();

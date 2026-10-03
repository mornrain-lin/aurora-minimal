<?php
/**
 * Aurora Minimal 导航 Walker 类。
 *
 * 继承 Walker_Nav_Menu，输出极简风格的菜单结构（两级下拉 + 移动端折叠）。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Aurora_Minimal_Nav_Walker' ) ) {

	/**
	 * 极简风格的主导航 Walker。
	 */
	class Aurora_Minimal_Nav_Walker extends Walker_Nav_Menu {

		/**
		 * 菜单项起始标签。
		 *
		 * @param string   $output 输出缓冲（按引用）。
		 * @param int      $depth  当前深度。
		 * @param stdClass $args   wp_nav_menu() 参数。
		 * @return void
		 */
		public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
			$item    = $data_object;
			$indent  = str_repeat( "\t", $depth );
			$classes = array();

			if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
				$classes[] = 'is-current';
			}

			if ( in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
				$classes[] = 'has-children';
			}

			$class_names = $classes ? ' class="' . esc_attr( implode( ' ', $classes ) ) . '"' : '';

			$atts           = array();
			$atts['title']  = isset( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = isset( $item->target ) ? $item->target : '';
			$atts['rel']    = isset( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = isset( $item->url ) ? $item->url : '';

			if ( '_blank' === $atts['target'] && '' === $atts['rel'] ) {
				$atts['rel'] = 'noopener noreferrer';
			}

			$attributes = '';

			foreach ( $atts as $key => $value ) {
				if ( '' !== $value ) {
					$attributes .= ' ' . $key . '="' . esc_attr( $value ) . '"';
				}
			}

			$title = apply_filters( 'the_title', $item->title, $item->ID );

			$item_output  = isset( $args->before ) ? $args->before : '';
			$item_output .= '<a' . $attributes . '>';
			$item_output .= isset( $args->link_before ) ? $args->link_before : '';
			$item_output .= esc_html( $title );
			$item_output .= isset( $args->link_after ) ? $args->link_after : '';
			$item_output .= '</a>';

			if ( isset( $args->after ) ) {
				$item_output .= $args->after;
			}

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		/**
		 * 子菜单起始标签：移动端默认折叠。
		 *
		 * @param string   $output 输出缓冲（按引用）。
		 * @param int      $depth  当前深度。
		 * @param stdClass $args   wp_nav_menu() 参数。
		 * @return void
		 */
		public function start_lvl( &$output, $depth = 0, $args = null ) {
			$indent  = str_repeat( "\t", $depth );
			$output .= "\n" . $indent . '<ul class="sub-menu">' . "\n";
		}

		/**
		 * 子菜单结束标签。
		 *
		 * @param string   $output 输出缓冲（按引用）。
		 * @param int      $depth  当前深度。
		 * @param stdClass $args   wp_nav_menu() 参数。
		 * @return void
		 */
		public function end_lvl( &$output, $depth = 0, $args = null ) {
			$output .= str_repeat( "\t", $depth ) . "</ul>\n";
		}
	}
}

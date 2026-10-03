<?php
/**
 * Aurora Minimal 辅助函数。
 *
 * 放置「不产生输出」的纯逻辑：选项读取、动态 CSS 生成、常用判断。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'aurora_minimal_get_option' ) ) {
	/**
	 * 读取主题设置，带默认值兜底。
	 *
	 * 所有 Customizer 读取都应经过此函数，避免 option 不存在时产生 notice。
	 *
	 * @param string $key     设置键名（不带主题前缀）。
	 * @param mixed  $default 默认值。
	 * @return mixed
	 */
	function aurora_minimal_get_option( $key, $default = null ) {
		$defaults = aurora_minimal_option_defaults();

		if ( null !== $default ) {
			$defaults[ $key ] = $default;
		}

		if ( ! isset( $defaults[ $key ] ) ) {
			return $default;
		}

		$value = get_theme_mod( 'aurora_minimal_' . $key, $defaults[ $key ] );

		if ( '' === $value && null === $default ) {
			return $defaults[ $key ];
		}

		return $value;
	}
}

if ( ! function_exists( 'aurora_minimal_option_defaults' ) ) {
	/**
	 * 主题全部设置项的默认值表。
	 *
	 * @return array<string,mixed>
	 */
	function aurora_minimal_option_defaults() {
		return array(
			'minimal_mode'          => false,
			'accent_color'          => '',
			'custom_background_color' => '',
			'tagline_visible'       => true,
			'reading_progress'      => true,
			'excerpt_length'        => 42,
			'sidebar_position'      => 'right',
			'footer_widgets'        => true,
			'back_to_top'           => true,
			'archive_layout'        => 'list',
			'container_width'       => 1120,
			'sticky_header'         => false,
		);
	}
}

if ( ! function_exists( 'aurora_minimal_is_minimal_mode' ) ) {
	/**
	 * 判断「极简模式」是否开启。
	 *
	 * 开启后隐藏侧栏、放大留白、加大区块间距。
	 *
	 * @return bool
	 */
	function aurora_minimal_is_minimal_mode() {
		/**
		 * 过滤极简模式开关状态。
		 *
		 * @param bool $enabled 是否开启。
		 */
		return (bool) apply_filters( 'aurora_minimal_minimal_mode', (bool) aurora_minimal_get_option( 'minimal_mode' ) );
	}
}

if ( ! function_exists( 'aurora_minimal_build_dynamic_css' ) ) {
	/**
	 * 根据 Customizer 设置生成 CSS 变量块。
	 *
	 * 输出形式为 `:root{--am-custom-accent:#xxx;}`，由 wp_add_inline_style 注入。
	 *
	 * @return string 生成的 CSS；无设置时返回空字符串。
	 */
	function aurora_minimal_build_dynamic_css() {
		$vars = array();

		$accent = aurora_minimal_get_option( 'accent_color' );

		if ( $accent ) {
			$accent = sanitize_hex_color( $accent );

			if ( $accent ) {
				$vars['--am-custom-accent'] = $accent;
				$vars['--am-custom-accent-soft'] = aurora_minimal_hex_to_rgba( $accent, 0.08 );
				$vars['--am-color-accent'] = $accent;
				$vars['--am-color-accent-soft'] = aurora_minimal_hex_to_rgba( $accent, 0.08 );
			}
		}

		$bg = aurora_minimal_get_option( 'custom_background_color' );

		if ( $bg ) {
			$bg = sanitize_hex_color( $bg );

			if ( $bg ) {
				$vars['--am-custom-bg'] = $bg;
			}
		}

		$width = (int) aurora_minimal_get_option( 'container_width' );

		if ( $width >= 720 && $width <= 1600 ) {
			$vars['--am-container'] = $width . 'px';
		}

		$sidebar_position = aurora_minimal_get_option( 'sidebar_position' );

		if ( 'left' === $sidebar_position ) {
			$vars['--am-sidebar-side'] = 'left';
		}

		if ( empty( $vars ) ) {
			return '';
		}

		$declarations = '';

		foreach ( $vars as $name => $value ) {
			$declarations .= $name . ':' . $value . ';';
		}

		return ':root{' . $declarations . '}';
	}
}

if ( ! function_exists( 'aurora_minimal_hex_to_rgba' ) ) {
	/**
	 * 把 #RRGGBB 转换为 rgba() 字符串。
	 *
	 * @param string $hex    十六进制颜色。
	 * @param float  $alpha  透明度（0-1）。
	 * @return string
	 */
	function aurora_minimal_hex_to_rgba( $hex, $alpha = 0.1 ) {
		$hex = ltrim( (string) $hex, '#' );

		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
			return 'rgba(43, 108, 176, ' . (float) $alpha . ')';
		}

		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		return 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . (float) $alpha . ')';
	}
}

if ( ! function_exists( 'aurora_minimal_has_excerpt' ) ) {
	/**
	 * 判断当前文章是否手写了摘要。
	 *
	 * 手写摘要时显示摘要，否则自动截取正文。
	 *
	 * @param int $post_id 文章 ID。
	 * @return bool
	 */
	function aurora_minimal_has_excerpt( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_the_ID();

		if ( ! $post_id ) {
			return false;
		}

		$post = get_post( $post_id );

		if ( ! $post instanceof WP_Post ) {
			return false;
		}

		return '' !== trim( (string) $post->post_excerpt );
	}
}

if ( ! function_exists( 'aurora_minimal_get_the_archive_title' ) ) {
	/**
	 * 获取归档页标题（自动去掉「分类：」等前缀）。
	 *
	 * @return string
	 */
	function aurora_minimal_get_the_archive_title() {
		$title = get_the_archive_title();

		if ( '' === $title ) {
			$title = esc_html__( '归档', 'aurora-minimal' );
		}

		return wp_strip_all_tags( $title );
	}
}

if ( ! function_exists( 'aurora_minimal_post_thumbnail_sizes' ) ) {
	/**
	 * 根据布局返回合适的缩略图尺寸名。
	 *
	 * @param string $context 上下文：card / horizontal / single。
	 * @return string
	 */
	function aurora_minimal_post_thumbnail_sizes( $context = 'card' ) {
		switch ( $context ) {
			case 'single':
				return 'aurora-wide';
			case 'horizontal':
				return 'aurora-thumb';
			default:
				return 'aurora-card';
		}
	}
}

if ( ! function_exists( 'aurora_minimal_pagination' ) ) {
	/**
	 * 输出一致的分页标记。
	 *
	 * @param WP_Query|null $query 可选查询对象，默认主查询。
	 * @return void
	 */
	function aurora_minimal_pagination( $query = null ) {
		$target = $query instanceof WP_Query ? $query : $GLOBALS['wp_query'];

		if ( ! $target instanceof WP_Query ) {
			return;
		}

		if ( $target->max_num_pages < 2 ) {
			return;
		}

		$links = paginate_links(
			array(
				'total'     => $target->max_num_pages,
				'current'   => max( 1, (int) get_query_var( 'paged' ) ),
				'type'      => 'array',
				'mid_size'  => 1,
				'prev_text' => esc_html__( '上一页', 'aurora-minimal' ),
				'next_text' => esc_html__( '下一页', 'aurora-minimal' ),
			)
		);

		if ( ! $links ) {
			return;
		}

		echo '<nav class="am-pagination" aria-label="' . esc_attr__( '分页导航', 'aurora-minimal' ) . '">';
		echo '<div class="nav-links">';

		foreach ( $links as $link ) {
			echo wp_kses_post( $link );
		}

		echo '</div></nav>';
	}
}

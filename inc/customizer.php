<?php
/**
 * Aurora Minimal 自定义器（Customizer）配置。
 *
 * 与 theme.json 双通道：theme.json 提供默认值与块编辑器配色，
 * Customizer 提供细粒度开关（极简模式、阅读进度条等）。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'aurora_minimal_customize_register' ) ) {
	/**
	 * 注册所有 Customizer 设置项。
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer 实例（按引用）。
	 * @return void
	 */
	function aurora_minimal_customize_register( $wp_customize ) {

		// 使用 get_theme_mod() 读取时统一加前缀 aurora_minimal_。
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.am-brand__title a',
					'render_callback' => 'aurora_minimal_customize_blogname',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.am-brand__tagline',
					'render_callback' => 'aurora_minimal_customize_blogdescription',
				)
			);
		}

		/**
		 * 主题设置分组。
		 */
		$wp_customize->add_panel(
			'aurora_minimal_panel',
			array(
				'title'       => esc_html__( '主题设置', 'aurora-minimal' ),
				'description' => esc_html__( 'Aurora Minimal 的全部主题选项。', 'aurora-minimal' ),
				'priority'    => 20,
			)
		);

		/* ------------------------------------------------------------------
		 * 分区一：外观
		 * ------------------------------------------------------------------ */
		$wp_customize->add_section(
			'aurora_minimal_appearance',
			array(
				'title' => esc_html__( '外观与配色', 'aurora-minimal' ),
				'panel' => 'aurora_minimal_panel',
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_accent_color',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_accent_color',
			array(
				'label'       => esc_html__( '强调色', 'aurora-minimal' ),
				'description' => esc_html__( '留空则使用主题默认强调色（#2b6cb0）。', 'aurora-minimal' ),
				'section'     => 'aurora_minimal_appearance',
				'type'        => 'color',
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_custom_background_color',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_custom_background_color',
			array(
				'label'       => esc_html__( '自定义背景色', 'aurora-minimal' ),
				'description' => esc_html__( '留空则使用纯白背景。', 'aurora-minimal' ),
				'section'     => 'aurora_minimal_appearance',
				'type'        => 'color',
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_container_width',
			array(
				'default'           => 1120,
				'sanitize_callback' => 'aurora_minimal_sanitize_range',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_container_width',
			array(
				'label'       => esc_html__( '内容最大宽度（像素）', 'aurora-minimal' ),
				'description' => esc_html__( '可选范围 720 - 1600。', 'aurora-minimal' ),
				'section'     => 'aurora_minimal_appearance',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 720,
					'max'  => 1600,
					'step' => 10,
				),
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_tagline_visible',
			array(
				'default'           => true,
				'sanitize_callback' => 'aurora_minimal_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_tagline_visible',
			array(
				'label'   => esc_html__( '显示站点描述', 'aurora-minimal' ),
				'section' => 'aurora_minimal_appearance',
				'type'    => 'checkbox',
			)
		);

		/* ------------------------------------------------------------------
		 * 分区二：布局
		 * ------------------------------------------------------------------ */
		$wp_customize->add_section(
			'aurora_minimal_layout',
			array(
				'title' => esc_html__( '布局', 'aurora-minimal' ),
				'panel' => 'aurora_minimal_panel',
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_minimal_mode',
			array(
				'default'           => false,
				'sanitize_callback' => 'aurora_minimal_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_minimal_mode',
			array(
				'label'       => esc_html__( '极简模式', 'aurora-minimal' ),
				'description' => esc_html__( '开启后隐藏侧栏并放大上下留白，适合工具页与落地页。', 'aurora-minimal' ),
				'section'     => 'aurora_minimal_layout',
				'type'        => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_sidebar_position',
			array(
				'default'           => 'right',
				'sanitize_callback' => 'aurora_minimal_sanitize_sidebar_position',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_sidebar_position',
			array(
				'label'   => esc_html__( '侧栏位置', 'aurora-minimal' ),
				'section' => 'aurora_minimal_layout',
				'type'    => 'select',
				'choices' => array(
					'right' => esc_html__( '右侧', 'aurora-minimal' ),
					'left'  => esc_html__( '左侧', 'aurora-minimal' ),
					'hide'  => esc_html__( '不显示', 'aurora-minimal' ),
				),
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_archive_layout',
			array(
				'default'           => 'list',
				'sanitize_callback' => 'aurora_minimal_sanitize_archive_layout',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_archive_layout',
			array(
				'label'   => esc_html__( '归档列表布局', 'aurora-minimal' ),
				'section' => 'aurora_minimal_layout',
				'type'    => 'select',
				'choices' => array(
					'list'       => esc_html__( '纵向列表', 'aurora-minimal' ),
					'horizontal' => esc_html__( '横向图文', 'aurora-minimal' ),
					'grid'       => esc_html__( '网格卡片', 'aurora-minimal' ),
				),
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_footer_widgets',
			array(
				'default'           => true,
				'sanitize_callback' => 'aurora_minimal_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_footer_widgets',
			array(
				'label'   => esc_html__( '显示页脚 Widget 区域', 'aurora-minimal' ),
				'section' => 'aurora_minimal_layout',
				'type'    => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_sticky_header',
			array(
				'default'           => false,
				'sanitize_callback' => 'aurora_minimal_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_sticky_header',
			array(
				'label'   => esc_html__( '吸顶头部', 'aurora-minimal' ),
				'section' => 'aurora_minimal_layout',
				'type'    => 'checkbox',
			)
		);

		/* ------------------------------------------------------------------
		 * 分区三：阅读
		 * ------------------------------------------------------------------ */
		$wp_customize->add_section(
			'aurora_minimal_reading',
			array(
				'title' => esc_html__( '阅读体验', 'aurora-minimal' ),
				'panel' => 'aurora_minimal_panel',
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_reading_progress',
			array(
				'default'           => true,
				'sanitize_callback' => 'aurora_minimal_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_reading_progress',
			array(
				'label'   => esc_html__( '显示阅读进度条', 'aurora-minimal' ),
				'section' => 'aurora_minimal_reading',
				'type'    => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_excerpt_length',
			array(
				'default'           => 42,
				'sanitize_callback' => 'aurora_minimal_sanitize_range',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_excerpt_length',
			array(
				'label'       => esc_html__( '摘要长度（词/字）', 'aurora-minimal' ),
				'description' => esc_html__( '可选范围 10 - 200。', 'aurora-minimal' ),
				'section'     => 'aurora_minimal_reading',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_setting(
			'aurora_minimal_back_to_top',
			array(
				'default'           => true,
				'sanitize_callback' => 'aurora_minimal_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'aurora_minimal_back_to_top',
			array(
				'label'   => esc_html__( '显示回到顶部按钮', 'aurora-minimal' ),
				'section' => 'aurora_minimal_reading',
				'type'    => 'checkbox',
			)
		);
	}
}
add_action( 'customize_register', 'aurora_minimal_customize_register' );

if ( ! function_exists( 'aurora_minimal_customize_blogname' ) ) {
	/**
	 * 站点标题的即时刷新回调。
	 *
	 * @return void
	 */
	function aurora_minimal_customize_blogname() {
		bloginfo( 'name', 'display' );
	}
}

if ( ! function_exists( 'aurora_minimal_customize_blogdescription' ) ) {
	/**
	 * 站点描述的即时刷新回调。
	 *
	 * @return void
	 */
	function aurora_minimal_customize_blogdescription() {
		bloginfo( 'description', 'display' );
	}
}

if ( ! function_exists( 'aurora_minimal_customize_preview_js' ) ) {
	/**
	 * Customizer 右侧实时预览脚本。
	 *
	 * 只做文本节点替换，不请求任何外部资源。
	 *
	 * @return void
	 */
	function aurora_minimal_customize_preview_js() {
		wp_enqueue_script(
			'aurora-minimal-customizer-preview',
			get_template_directory_uri() . '/assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			aurora_minimal_asset_version( 'assets/js/customizer-preview.js' ),
			true
		);
	}
}
add_action( 'customize_preview_init', 'aurora_minimal_customize_preview_js' );

if ( ! function_exists( 'aurora_minimal_sanitize_checkbox' ) ) {
	/**
	 * 复选框清洗。
	 *
	 * @param mixed $checked 输入值。
	 * @return bool
	 */
	function aurora_minimal_sanitize_checkbox( $checked ) {
		return ( isset( $checked ) && true === (bool) $checked );
	}
}

if ( ! function_exists( 'aurora_minimal_sanitize_range' ) ) {
	/**
	 * 数值区间清洗（按控件的 input_attrs 上下限裁剪）。
	 *
	 * @param mixed $value 输入值。
	 * @return int
	 */
	function aurora_minimal_sanitize_range( $value ) {
		$value = (int) $value;

		if ( $value < 1 ) {
			$value = 1;
		}

		return $value;
	}
}

if ( ! function_exists( 'aurora_minimal_sanitize_sidebar_position' ) ) {
	/**
	 * 侧栏位置白名单。
	 *
	 * @param mixed $value 输入值。
	 * @return string
	 */
	function aurora_minimal_sanitize_sidebar_position( $value ) {
		$allowed = array( 'right', 'left', 'hide' );
		$value   = is_string( $value ) ? $value : 'right';

		return in_array( $value, $allowed, true ) ? $value : 'right';
	}
}

if ( ! function_exists( 'aurora_minimal_sanitize_archive_layout' ) ) {
	/**
	 * 归档布局白名单。
	 *
	 * @param mixed $value 输入值。
	 * @return string
	 */
	function aurora_minimal_sanitize_archive_layout( $value ) {
		$allowed = array( 'list', 'horizontal', 'grid' );
		$value   = is_string( $value ) ? $value : 'list';

		return in_array( $value, $allowed, true ) ? $value : 'list';
	}
}

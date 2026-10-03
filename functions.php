<?php
/**
 * Aurora Minimal 主题函数入口。
 *
 * 本文件仅负责「装配」：声明主题支持、加载 inc/ 下的模块、注册挂载点。
 * 具体实现拆分到 inc/ 子文件中，便于维护。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 主题版本号。同时用于资源版本缓存与脚本依赖。
 */
if ( ! defined( 'AURORA_MINIMAL_VERSION' ) ) {
	define( 'AURORA_MINIMAL_VERSION', '1.0.0' );
}

/**
 * 主题文本域，与 style.css 头部保持一致。
 */
if ( ! defined( 'AURORA_MINIMAL_TEXT_DOMAIN' ) ) {
	define( 'AURORA_MINIMAL_TEXT_DOMAIN', 'aurora-minimal' );
}

/**
 * 读取主题资源的版本号，用于前端缓存 busting。
 *
 * 优先使用文件的最后修改时间，文件不可读时回落到主题版本号，
 * 保证开发期改动能立即生效，发布后仍保持稳定。
 *
 * @param string $relative_path 相对主题根目录的路径。
 * @return string
 */
function aurora_minimal_asset_version( $relative_path ) {
	$file = get_template_directory() . '/' . ltrim( $relative_path, '/' );

	if ( file_exists( $file ) ) {
		$mtime = filemtime( $file );

		if ( $mtime ) {
			return (string) $mtime;
		}
	}

	return AURORA_MINIMAL_VERSION;
}

require_once get_template_directory() . '/inc/extras.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/nav-walker.php';
require_once get_template_directory() . '/inc/customizer.php';


if ( ! function_exists( 'aurora_minimal_setup' ) ) {
	/**
	 * 声明主题基础能力。
	 *
	 * @return void
	 */
	function aurora_minimal_setup() {
		/*
		 * 让 WordPress 输出 title-tag，而不是主题手写 <title>。
		 */
		load_theme_textdomain( 'aurora-minimal', get_template_directory() . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-font-sizes' );


		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 60,
				'width'       => 240,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => array( 'site-title', 'site-description' ),
			)
		);

		add_theme_support(
			'post-formats',
			array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' )
		);

		// 自定义图片尺寸：按名称注册，模板中用 the_post_thumbnail( 'aurora-card' ) 调用。
		add_image_size( 'aurora-card', 1120, 630, true );
		add_image_size( 'aurora-wide', 1600, 500, true );
		add_image_size( 'aurora-thumb', 320, 200, true );

		// 让大图在编辑器里也可用。
		add_editor_style( 'assets/css/editor-style.css' );

		register_nav_menus(
			array(
				'primary' => esc_html__( '主导航', 'aurora-minimal' ),
				'footer'  => esc_html__( '页脚导航', 'aurora-minimal' ),
				'social'  => esc_html__( '社交链接', 'aurora-minimal' ),
			)
		);
	}
}
add_action( 'after_setup_theme', 'aurora_minimal_setup' );

if ( ! function_exists( 'aurora_minimal_content_width' ) ) {
	/**
	 * 根据侧栏是否显示动态设置内容宽度。
	 *
	 * @global int $content_width
	 * @return void
	 */
	function aurora_minimal_content_width() {
		$width = 720;

		if ( ! is_active_sidebar( 'sidebar-1' ) || aurora_minimal_is_minimal_mode() ) {
			$width = 820;
		}

		/**
		 * 过滤正文内容宽度（像素）。
		 *
		 * @param int $width 内容宽度。
		 */
		$width = (int) apply_filters( 'aurora_minimal_content_width', $width );

		$GLOBALS['content_width'] = $width;
	}
}
add_action( 'after_setup_theme', 'aurora_minimal_content_width', 0 );


if ( ! function_exists( 'aurora_minimal_widgets_init' ) ) {
	/**
	 * 注册侧栏 2 个 + 页脚 3 个 Widget 区域。
	 *
	 * @return void
	 */
	function aurora_minimal_widgets_init() {
		$defaults = array(
			'before_widget' => '<section id="%1$s" class="am-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="am-widget__title">',
			'after_title'   => '</h2>',
		);

		// 侧栏 1：主侧栏。
		register_sidebar(
			array_merge(
				$defaults,
				array(
					'name'        => esc_html__( '侧栏 1（主侧栏）', 'aurora-minimal' ),
					'id'          => 'sidebar-1',
					'description' => esc_html__( '显示在文章/归档页右侧。若启用「极简模式」则自动隐藏。', 'aurora-minimal' ),
				)
			)
		);

		// 侧栏 2：附加侧栏，可单独分配到某些页面模板。
		register_sidebar(
			array_merge(
				$defaults,
				array(
					'name'        => esc_html__( '侧栏 2（附加）', 'aurora-minimal' ),
					'id'          => 'sidebar-2',
					'description' => esc_html__( '备用侧栏，可在模板中调用 aurora_minimal_sidebar( \'sidebar-2\' )。', 'aurora-minimal' ),
				)
			)
		);

		// 页脚 3 栏。
		for ( $i = 1; $i <= 3; $i++ ) {
			register_sidebar(
				array_merge(
					$defaults,
					array(
						/* translators: %d：页脚栏序号。 */
						'name'        => sprintf( esc_html__( '页脚 %d', 'aurora-minimal' ), $i ),
						'id'          => 'footer-' . $i,
						'description' => sprintf(
							/* translators: %d：页脚栏序号。 */
							esc_html__( '页脚第 %d 栏的 Widget 区域。', 'aurora-minimal' ),
							$i
						),
					)
				)
			);
		}
	}
}
add_action( 'widgets_init', 'aurora_minimal_widgets_init' );


if ( ! function_exists( 'aurora_minimal_scripts' ) ) {
	/**
	 * 加载前端样式与脚本。
	 *
	 * 全部使用 defer / 底部加载，不阻塞首屏渲染。
	 *
	 * @return void
	 */
	function aurora_minimal_scripts() {
		wp_enqueue_style(
			'aurora-minimal-style',
			get_stylesheet_uri(),
			array(),
			aurora_minimal_asset_version( 'style.css' )
		);

		wp_style_add_data( 'aurora-minimal-style', 'rtl', 'replace' );

		wp_enqueue_script(
			'aurora-minimal-navigation',
			get_template_directory_uri() . '/assets/js/navigation.js',
			array(),
			aurora_minimal_asset_version( 'assets/js/navigation.js' ),
			true
		);

		wp_enqueue_script(
			'aurora-minimal-progress',
			get_template_directory_uri() . '/assets/js/reading-progress.js',
			array(),
			aurora_minimal_asset_version( 'assets/js/reading-progress.js' ),
			true
		);

		wp_localize_script(
			'aurora-minimal-navigation',
			'auroraMinimalL10n',
			array(
				'toggleNav'   => esc_html__( '切换导航菜单', 'aurora-minimal' ),
				'expandSub'   => esc_html__( '展开子菜单', 'aurora-minimal' ),
				'backToTop'   => esc_html__( '回到顶部', 'aurora-minimal' ),
				'copyLink'    => esc_html__( '复制链接', 'aurora-minimal' ),
				'linkCopied'  => esc_html__( '已复制', 'aurora-minimal' ),
			)
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'aurora_minimal_scripts' );

if ( ! function_exists( 'aurora_minimal_body_classes' ) ) {
	/**
	 * 为 <body> 追加主题状态类，供 CSS 选择器使用。
	 *
	 * @param array $classes 现有类名数组。
	 * @return array
	 */
	function aurora_minimal_body_classes( $classes ) {
		$classes[] = 'am-site';

		if ( is_active_sidebar( 'sidebar-1' ) && ! aurora_minimal_is_minimal_mode() ) {
			$classes[] = 'am-has-sidebar';
		} else {
			$classes[] = 'am-no-sidebar';
		}

		if ( aurora_minimal_is_minimal_mode() ) {
			$classes[] = 'am-minimal-mode';
		}

		if ( aurora_minimal_get_option( 'custom_background_color' ) ) {
			$classes[] = 'am-custom-bg';
		}

		if ( aurora_minimal_get_option( 'accent_color' ) ) {
			$classes[] = 'am-custom-accent';
		}

		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'am-no-widgets';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'aurora_minimal_body_classes' );

if ( ! function_exists( 'aurora_minimal_excerpt_length' ) ) {
	/**
	 * 中文站点摘要按字符截断更合适，过滤 excerpt 长度。
	 *
	 * @param int $length 默认长度（词）。
	 * @return int
	 */
	function aurora_minimal_excerpt_length( $length ) {
		$custom = aurora_minimal_get_option( 'excerpt_length' );

		return $custom ? (int) $custom : (int) $length;
	}
}
add_filter( 'excerpt_length', 'aurora_minimal_excerpt_length' );

if ( ! function_exists( 'aurora_minimal_excerpt_more' ) ) {
	/**
	 * 去掉默认的 […] 摘要省略号，使用自定义省略符。
	 *
	 * @param string $more 默认省略符。
	 * @return string
	 */
	function aurora_minimal_excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}

		return ' &hellip;';
	}
}
add_filter( 'excerpt_more', 'aurora_minimal_excerpt_more' );

if ( ! function_exists( 'aurora_minimal_pingback_header' ) ) {
	/**
	 * 单一文章页输出 pingback 链接，供移动端客户端抓取。
	 *
	 * @return void
	 */
	function aurora_minimal_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}
add_action( 'wp_head', 'aurora_minimal_pingback_header' );

if ( ! function_exists( 'aurora_minimal_reading_progress' ) ) {
	/**
	 * 在 single 页输出阅读进度条容器。
	 *
	 * @return void
	 */
	function aurora_minimal_reading_progress() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		if ( ! aurora_minimal_get_option( 'reading_progress' ) ) {
			return;
		}

		echo '<div class="am-progress" role="progressbar" aria-label="' . esc_attr__( '阅读进度', 'aurora-minimal' ) . '" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="am-progress__bar"></div></div>';
	}
}
add_action( 'wp_body_open', 'aurora_minimal_reading_progress', 10 );

if ( ! function_exists( 'aurora_minimal_dynamic_css' ) ) {
	/**
	 * 输出由 Customizer 设置生成的 CSS 变量。
	 *
	 * 使用 wp_add_inline_style 追加到主样式表末尾，保证可覆盖静态 CSS。
	 *
	 * @return void
	 */
	function aurora_minimal_dynamic_css() {
		$css = aurora_minimal_build_dynamic_css();

		if ( '' === $css ) {
			return;
		}

		wp_add_inline_style( 'aurora-minimal-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'aurora_minimal_dynamic_css', 20 );

if ( ! function_exists( 'aurora_minimal_woocommerce_declared' ) ) {
	/**
	 * 仅声明 WooCommerce 支持，未做模板适配。
	 *
	 * 声明后 WooCommerce 会提示「主题未适配」，但不会因缺少函数而报错。
	 *
	 * @return void
	 */
	function aurora_minimal_woocommerce_declared() {
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 320,
				'single_image_width'    => 720,
			)
		);
	}
}
add_action( 'after_setup_theme', 'aurora_minimal_woocommerce_declared' );

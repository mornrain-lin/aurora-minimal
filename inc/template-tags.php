<?php
/**
 * Aurora Minimal 模板标签。
 *
 * 所有「会输出 HTML」的辅助函数集中在这里，模板中只做调用。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'aurora_minimal_site_branding' ) ) {
	/**
	 * 输出站点标题 / 描述 / 自定义 Logo。
	 *
	 * 优先使用自定义 Logo，其次是站点标题文字，最后回落到站点名称。
	 *
	 * @return void
	 */
	function aurora_minimal_site_branding() {
		$show_tagline = (bool) aurora_minimal_get_option( 'tagline_visible' );

		if ( has_custom_logo() ) {
			echo '<div class="am-brand">';
			the_custom_logo();
			echo '</div>';
			return;
		}

		$title_tag = ( is_front_page() && is_home() ) ? 'h1' : 'p';

		printf(
			'<div class="am-brand"><div class="am-brand__text"><%1$s class="am-brand__title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
			esc_attr( $title_tag ),
			esc_url( home_url( '/' ) ),
			esc_html( get_bloginfo( 'name', 'display' ) )
		);

		if ( $show_tagline && get_bloginfo( 'description', 'display' ) ) {
			echo '<p class="am-brand__tagline">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';
		}

		echo '</div></div>';
	}
}

if ( ! function_exists( 'aurora_minimal_posted_on' ) ) {
	/**
	 * 生成文章发布日期与作者的 HTML 片段。
	 *
	 * @return string 已转义的 HTML。
	 */
	function aurora_minimal_posted_on() {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string .= '<time class="updated screen-reader-text" datetime="%3$s">%4$s</time>';
		}

		$time_html = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		return sprintf(
			'<a href="%1$s" rel="bookmark">%2$s</a><span class="am-post-meta__sep" aria-hidden="true">·</span>%3$s',
			esc_url( get_permalink() ),
			$time_html,
			esc_html( get_the_author() )
		);
	}
}

if ( ! function_exists( 'aurora_minimal_posted_meta' ) ) {
	/**
	 * 输出完整元信息：日期、作者、分类、评论数。
	 *
	 * @return void
	 */
	function aurora_minimal_posted_meta() {
		echo '<ul class="am-post-meta">';

		echo '<li>' . aurora_minimal_posted_on() . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 内部已转义。

		$categories = get_the_category_list( ', ' );

		if ( $categories ) {
			echo '<li><span class="am-post-meta__cats">' . wp_kses_post( $categories ) . '</span></li>';
		}

		$comments = get_comments_number();

		if ( comments_open() || $comments ) {
			echo '<li><span class="am-post-meta__comments">';
			printf(
				/* translators: %s：评论数量。 */
				esc_html( _n( '%s 条评论', '%s 条评论', $comments, 'aurora-minimal' ) ),
				esc_html( number_format_i18n( $comments ) )
			);
			echo '</span></li>';
		}

		echo '</ul>';
	}
}

if ( ! function_exists( 'aurora_minimal_render_tool_cards' ) ) {
	/**
	 * 把指定页面的直接子页面渲染为工具卡片网格。
	 *
	 * 仅在「极简模式」下调用，用于工具集合型站点的首页。
	 * 子页面的摘要取自子页面自身的摘要，没有摘要时留空。
	 *
	 * @param int $parent_id 父页面 ID。
	 * @return void
	 */
	function aurora_minimal_render_tool_cards( $parent_id ) {
		$parent_id = (int) $parent_id;

		if ( $parent_id <= 0 ) {
			return;
		}

		$children = get_posts(
			array(
				'post_type'      => 'page',
				'post_parent'    => $parent_id,
				'post_status'    => 'publish',
				'posts_per_page' => 24,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);

		if ( empty( $children ) ) {
			return;
		}

		echo '<section class="am-tools">';
		echo '<h2 class="am-tools__title">' . esc_html__( '全部工具', 'aurora-minimal' ) . '</h2>';
		echo '<ul class="am-tool-grid">';

		foreach ( $children as $child ) {
			$permalink = get_permalink( $child );

			if ( ! $permalink ) {
				continue;
			}

			echo '<li class="am-tool-card">';
			printf(
				'<h3 class="am-tool-card__title"><a href="%1$s">%2$s</a></h3>',
				esc_url( $permalink ),
				esc_html( get_the_title( $child ) )
			);

			$excerpt = '';

			if ( has_excerpt( $child ) ) {
				$excerpt = wp_strip_all_tags( get_the_excerpt( $child ) );
			}

			if ( '' !== $excerpt ) {
				printf( '<p class="am-tool-card__desc">%s</p>', esc_html( wp_trim_words( $excerpt, 22, '…' ) ) );
			}

			printf(
				'<span class="am-tool-card__more">%s</span>',
				esc_html__( '打开 →', 'aurora-minimal' )
			);
			echo '</li>';
		}

		echo '</ul></section>';
	}
}

if ( ! function_exists( 'aurora_minimal_entry_meta' ) ) {
	/**
	 * 输出文章顶部的元信息条。
	 *
	 * @return void
	 */
	function aurora_minimal_entry_meta() {
		echo '<div class="am-entry__meta">';
		aurora_minimal_posted_meta();
		echo '</div>';
	}
}

if ( ! function_exists( 'aurora_minimal_entry_footer' ) ) {
	/**
	 * 输出文章底部的分类标签与上一篇/下一篇导航。
	 *
	 * @return void
	 */
	function aurora_minimal_entry_footer() {
		$categories = get_the_category_list( ', ' );

		if ( $categories ) {
			echo '<div class="am-entry__tags-wrap">';
			printf( '<span class="am-entry__tags-label">%s</span>', esc_html__( '分类：', 'aurora-minimal' ) );
			echo '<ul class="am-entry__tags">';
			echo wp_kses_post( $categories );
			echo '</ul></div>';
		}

		$tags = get_the_tag_list( '', '' );

		if ( $tags && ! is_wp_error( $tags ) ) {
			echo '<div class="am-entry__tags-wrap">';
			printf( '<span class="am-entry__tags-label">%s</span>', esc_html__( '标签：', 'aurora-minimal' ) );
			echo '<ul class="am-entry__tags">' . wp_kses_post( $tags ) . '</ul></div>';
		}

		aurora_minimal_post_navigation();
	}
}

if ( ! function_exists( 'aurora_minimal_post_navigation' ) ) {
	/**
	 * 输出上一篇 / 下一篇导航。
	 *
	 * @return void
	 */
	function aurora_minimal_post_navigation() {
		$previous = get_previous_post();
		$next     = get_next_post();

		if ( ! $previous && ! $next ) {
			return;
		}

		echo '<nav class="am-entry__nav" aria-label="' . esc_attr__( '文章导航', 'aurora-minimal' ) . '">';

		if ( $previous ) {
			echo '<a class="am-entry__nav-link am-entry__nav-link--prev" href="' . esc_url( get_permalink( $previous ) ) . '" rel="prev">';
			printf( '<span>%s</span>', esc_html__( '上一篇', 'aurora-minimal' ) );
			echo esc_html( get_the_title( $previous ) );
			echo '</a>';
		}

		if ( $next ) {
			echo '<a class="am-entry__nav-link am-entry__nav-link--next" href="' . esc_url( get_permalink( $next ) ) . '" rel="next">';
			printf( '<span>%s</span>', esc_html__( '下一篇', 'aurora-minimal' ) );
			echo esc_html( get_the_title( $next ) );
			echo '</a>';
		}

		echo '</nav>';
	}
}

if ( ! function_exists( 'aurora_minimal_post_thumbnail' ) ) {
	/**
	 * 输出文章缩略图；无图时输出占位块保持布局。
	 *
	 * @param string $size   图片尺寸名。
	 * @param bool   $linked 是否用图片链接到文章。
	 * @return void
	 */
	function aurora_minimal_post_thumbnail( $size = 'aurora-card', $linked = true ) {
		if ( post_password_required() || is_attachment() ) {
			return;
		}

		$classes = 'am-post-card__thumbnail';

		if ( ! has_post_thumbnail() ) {
			printf(
				'<div class="%1$s am-post-card__thumbnail--empty"><span>%2$s</span></div>',
				esc_attr( $classes ),
				esc_html__( '暂无封面', 'aurora-minimal' )
			);
			return;
		}

		if ( $linked ) {
			printf(
				'<figure class="%1$s"><a href="%2$s" aria-hidden="true" tabindex="-1">',
				esc_attr( $classes ),
				esc_url( get_permalink() )
			);
			the_post_thumbnail(
				$size,
				array(
					'loading'  => 'lazy',
					'decoding' => 'async',
					'alt'      => the_title_attribute( array( 'echo' => false ) ),
				)
			);
			echo '</a></figure>';
			return;
		}

		printf( '<figure class="%s">', esc_attr( $classes ) );
		the_post_thumbnail(
			$size,
			array(
				'loading'  => 'lazy',
				'decoding' => 'async',
				'alt'      => the_title_attribute( array( 'echo' => false ) ),
			)
		);
		echo '</figure>';
	}
}

if ( ! function_exists( 'aurora_minimal_entry_thumbnail' ) ) {
	/**
	 * 输出文章页顶部大图。
	 *
	 * @return void
	 */
	function aurora_minimal_entry_thumbnail() {
		if ( ! has_post_thumbnail() || post_password_required() ) {
			return;
		}

		echo '<figure class="am-entry__thumbnail">';
		the_post_thumbnail( 'aurora-wide', array( 'decoding' => 'async' ) );
		echo '</figure>';
	}
}

if ( ! function_exists( 'aurora_minimal_post_card' ) ) {
	/**
	 * 输出归档列表中的一张文章卡片。
	 *
	 * 布局由 Customizer 的「归档布局」决定：list（纵向）/ grid（网格）。
	 *
	 * @return void
	 */
	function aurora_minimal_post_card() {
		$layout = aurora_minimal_get_option( 'archive_layout' );

		if ( 'grid' === $layout ) {
			$classes = 'am-post-card am-post-card--grid';
		} elseif ( 'horizontal' === $layout ) {
			$classes = 'am-post-card am-post-card--horizontal';
		} else {
			$classes = 'am-post-card';
		}

		echo '<article id="post-' . esc_attr( (string) get_the_ID() ) . '" class="' . esc_attr( $classes ) . '">';

		aurora_minimal_post_thumbnail( aurora_minimal_post_thumbnail_sizes( 'horizontal' === $layout ? 'horizontal' : 'card' ) );

		echo '<div class="am-post-card__body">';

		echo '<h2 class="am-post-card__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . esc_html( get_the_title() ) . '</a></h2>';

		aurora_minimal_posted_meta();

		$excerpt = aurora_minimal_get_excerpt();

		if ( '' !== $excerpt ) {
			echo '<p class="am-post-card__excerpt">' . esc_html( $excerpt ) . '</p>';
		}

		echo '</div></article>';
	}
}

if ( ! function_exists( 'aurora_minimal_get_excerpt' ) ) {
	/**
	 * 获取文章摘要文本。
	 *
	 * 有手写摘要时优先使用手写摘要，否则用 wp_trim_words 截取。
	 *
	 * @param int $post_id 文章 ID。
	 * @return string 已去除标签的纯文本。
	 */
	function aurora_minimal_get_excerpt( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_the_ID();

		if ( ! $post_id ) {
			return '';
		}

		$post = get_post( $post_id );

		if ( ! $post instanceof WP_Post ) {
			return '';
		}

		$length = (int) aurora_minimal_get_option( 'excerpt_length' );

		if ( $length < 5 ) {
			$length = 42;
		}

		if ( '' !== trim( (string) $post->post_excerpt ) ) {
			return wp_strip_all_tags( strip_shortcodes( $post->post_excerpt ) );
		}

		$content = $post->post_password_required() ? '' : $post->post_content;
		$content = strip_shortcodes( $content );
		$content = excerpt_remove_blocks( $content );

		return wp_trim_words( wp_strip_all_tags( $content ), $length, '&hellip;' );
	}
}

if ( ! function_exists( 'aurora_minimal_breadcrumb' ) ) {
	/**
	 * 输出面包屑导航。
	 *
	 * 只在非首页显示，首页不重复展示站点名称。
	 *
	 * @return void
	 */
	function aurora_minimal_breadcrumb() {
		if ( is_front_page() ) {
			return;
		}

		$items = array(
			array(
				'url'   => home_url( '/' ),
				'label' => esc_html__( '首页', 'aurora-minimal' ),
			),
		);

		if ( is_singular() ) {
			$post_id = (int) get_the_ID();
			$ancestors = array_reverse( (array) get_post_ancestors( $post_id ) );

			foreach ( $ancestors as $ancestor_id ) {
				$items[] = array(
					'url'   => get_permalink( $ancestor_id ),
					'label' => get_the_title( $ancestor_id ),
				);
			}

			$items[] = array(
				'url'   => '',
				'label' => get_the_title( $post_id ),
			);
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();

			if ( $term instanceof WP_Term ) {
				$items[] = array(
					'url'   => get_term_link( $term ),
					'label' => $term->name,
				);
			}
		} elseif ( is_search() ) {
			$items[] = array(
				'url'   => '',
				/* translators: %s：搜索关键词。 */
				'label' => sprintf( esc_html__( '搜索「%s」', 'aurora-minimal' ), get_search_query() ),
			);
		} elseif ( is_404() ) {
			$items[] = array(
				'url'   => '',
				'label' => esc_html__( '页面未找到', 'aurora-minimal' ),
			);
		} elseif ( is_home() && ! is_front_page() ) {
			$page_for_posts = (int) get_option( 'page_for_posts' );

			if ( $page_for_posts ) {
				$items[] = array(
					'url'   => get_permalink( $page_for_posts ),
					'label' => get_the_title( $page_for_posts ),
				);
			} else {
				$items[] = array(
					'url'   => '',
					'label' => esc_html__( '文章', 'aurora-minimal' ),
				);
			}
		} elseif ( is_archive() ) {
			$items[] = array(
				'url'   => '',
				'label' => aurora_minimal_get_the_archive_title(),
			);
		}

		$last = count( $items ) - 1;

		echo '<nav class="am-breadcrumb" aria-label="' . esc_attr__( '面包屑导航', 'aurora-minimal' ) . '"><ol>';

		foreach ( $items as $index => $item ) {
			echo '<li>';

			if ( $index === $last || empty( $item['url'] ) ) {
				printf( '<span aria-current="page">%s</span>', esc_html( $item['label'] ) );
			} else {
				printf( '<a href="%1$s">%2$s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
			}

			echo '</li>';
		}

		echo '</ol></nav>';
	}
}

if ( ! function_exists( 'aurora_minimal_page_header' ) ) {
	/**
	 * 输出归档/搜索页的标题区。
	 *
	 * @return void
	 */
	function aurora_minimal_page_header() {
		echo '<header class="am-archive-header">';

		if ( is_home() && ! is_front_page() ) {
			echo '<h1 class="am-page-header__title">' . esc_html( aurora_minimal_get_blog_title() ) . '</h1>';
		} elseif ( is_search() ) {
			printf(
				'<h1 class="am-page-header__title">%s</h1>',
				/* translators: %s：搜索关键词。 */
				sprintf( esc_html__( '搜索结果：%s', 'aurora-minimal' ), esc_html( get_search_query() ) )
			);
		} else {
			echo '<h1 class="am-page-header__title">' . esc_html( aurora_minimal_get_the_archive_title() ) . '</h1>';
		}

		$description = get_the_archive_description();

		if ( $description ) {
			echo '<div class="am-archive-header__description">' . wp_kses_post( wpautop( $description ) ) . '</div>';
		}

		echo '</header>';
	}
}

if ( ! function_exists( 'aurora_minimal_get_blog_title' ) ) {
	/**
	 * 获取「文章页」标题（优先使用静态 front page 的标题）。
	 *
	 * @return string
	 */
	function aurora_minimal_get_blog_title() {
		$page_for_posts = (int) get_option( 'page_for_posts' );

		if ( $page_for_posts ) {
			return get_the_title( $page_for_posts );
		}

		return esc_html__( '最新文章', 'aurora-minimal' );
	}
}

if ( ! function_exists( 'aurora_minimal_sidebar' ) ) {
	/**
	 * 输出指定 Widget 区域。
	 *
	 * @param string $sidebar_id Widget 区域 ID。
	 * @return void
	 */
	function aurora_minimal_sidebar( $sidebar_id = 'sidebar-1' ) {
		if ( ! is_active_sidebar( $sidebar_id ) ) {
			return;
		}

		$classes = 'am-sidebar';

		if ( aurora_minimal_is_minimal_mode() ) {
			return;
		}

		echo '<aside class="' . esc_attr( $classes ) . '" aria-label="' . esc_attr__( '侧栏', 'aurora-minimal' ) . '">';
		dynamic_sidebar( $sidebar_id );
		echo '</aside>';
	}
}

if ( ! function_exists( 'aurora_minimal_footer_widgets' ) ) {
	/**
	 * 输出页脚 Widget 区域。
	 *
	 * @return void
	 */
	function aurora_minimal_footer_widgets() {
		if ( ! aurora_minimal_get_option( 'footer_widgets' ) ) {
			return;
		}

		if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) ) {
			return;
		}

		echo '<div class="am-footer-widgets"><div class="am-container">';

		for ( $i = 1; $i <= 3; $i++ ) {
			$id = 'footer-' . $i;

			if ( ! is_active_sidebar( $id ) ) {
				continue;
			}

			echo '<div class="am-footer-widgets__col">';
			dynamic_sidebar( $id );
			echo '</div>';
		}

		echo '</div></div>';
	}
}

if ( ! function_exists( 'aurora_minimal_tag_cloud' ) ) {
	/**
	 * 输出标签云（限制 30 个标签，按热度排序）。
	 *
	 * @return void
	 */
	function aurora_minimal_tag_cloud() {
		$tags = get_tags(
			array(
				'number'     => 30,
				'orderby'    => 'count',
				'order'      => 'DESC',
				'hide_empty' => true,
			)
		);

		if ( empty( $tags ) || is_wp_error( $tags ) ) {
			return;
		}

		echo '<div class="am-tag-cloud">';

		foreach ( $tags as $tag ) {
			printf(
				'<a href="%1$s" rel="tag">%2$s<span class="am-tag-cloud__count">%3$s</span></a>',
				esc_url( get_tag_link( $tag ) ),
				esc_html( $tag->name ),
				esc_html( number_format_i18n( $tag->count ) )
			);
		}

		echo '</div>';
	}
}

if ( ! function_exists( 'aurora_minimal_archive_term_bar' ) ) {
	/**
	 * 归档页顶部分类筛选条。
	 *
	 * @param string $taxonomy 分类法。
	 * @return void
	 */
	function aurora_minimal_archive_term_bar( $taxonomy = 'category' ) {
		$queried = get_queried_object();
		$current = 0;

		if ( $queried instanceof WP_Term ) {
			$current = (int) $queried->term_id;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
				'number'     => 16,
			)
		);

		if ( is_wp_error( $terms ) || count( $terms ) < 2 ) {
			return;
		}

		echo '<ul class="am-term-list">';

		printf(
			'<li class="%1$s"><a href="%2$s">%3$s</a></li>',
			0 === $current ? 'is-active' : '',
			esc_url( get_post_type_archive_link( 'post' ) ? get_post_type_archive_link( 'post' ) : home_url( '/' ) ),
			esc_html__( '全部', 'aurora-minimal' )
		);

		foreach ( $terms as $term ) {
			$link = get_term_link( $term );

			if ( is_wp_error( $link ) ) {
				continue;
			}

			printf(
				'<li class="%1$s"><a href="%2$s">%3$s</a></li>',
				(int) $term->term_id === $current ? 'is-active' : '',
				esc_url( $link ),
				esc_html( $term->name )
			);
		}

		echo '</ul>';
	}
}

if ( ! function_exists( 'aurora_minimal_404_suggestions' ) ) {
	/**
	 * 404 页面的「最新文章」推荐列表。
	 *
	 * @param int $limit 数量。
	 * @return void
	 */
	function aurora_minimal_404_suggestions( $limit = 5 ) {
		$query = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => (int) $limit,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		if ( ! $query->have_posts() ) {
			return;
		}

		echo '<div class="am-404__suggestions">';
		printf( '<h2>%s</h2>', esc_html__( '最新文章', 'aurora-minimal' ) );
		echo '<ul>';

		while ( $query->have_posts() ) {
			$query->the_post();
			printf(
				'<li><a href="%1$s">%2$s</a></li>',
				esc_url( get_permalink() ),
				esc_html( get_the_title() )
			);
		}

		echo '</ul></div>';
		wp_reset_postdata();
	}
}

if ( ! function_exists( 'aurora_minimal_back_to_top' ) ) {
	/**
	 * 输出回到顶部按钮（内联 SVG 图标，无外部依赖）。
	 *
	 * @return void
	 */
	function aurora_minimal_back_to_top() {
		if ( ! aurora_minimal_get_option( 'back_to_top' ) ) {
			return;
		}

		echo '<button type="button" class="am-to-top" data-am-to-top aria-label="' . esc_attr__( '回到顶部', 'aurora-minimal' ) . '">';
		echo '<svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path d="M8 3.2 2.8 8.4l1.06 1.06L8 5.32l4.14 4.14 1.06-1.06L8 3.2Z"/><path d="M2.8 11.2h10.4v1.2H2.8z"/></svg>';
		echo '<span class="am-screen-reader-text">' . esc_html__( '回到顶部', 'aurora-minimal' ) . '</span>';
		echo '</button>';
	}
}
add_action( 'wp_footer', 'aurora_minimal_back_to_top', 20 );

if ( ! function_exists( 'aurora_minimal_archive_class' ) ) {
	/**
	 * 根据归档布局返回列表容器的 class。
	 *
	 * @return string
	 */
	function aurora_minimal_archive_class() {
		$layout = aurora_minimal_get_option( 'archive_layout' );

		if ( 'grid' === $layout ) {
			return 'am-post-list am-post-list--grid';
		}

		return 'am-post-list';
	}
}

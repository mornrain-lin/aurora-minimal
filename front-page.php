<?php
/**
 * 首页模板（front-page.php）。
 *
 * 「工具模式」：若站点存在页面且已被指定为静态首页，则渲染区块式落地页；
 * 否则退回普通文章列表。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

get_header();

$am_is_static_front = is_front_page() && ! is_home();
?>

<div class="am-container">

	<?php
	if ( $am_is_static_front && get_option( 'show_on_front' ) === 'page' ) :
		?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'am-entry am-front-content' ); ?>>
				<div class="am-entry__content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>

			<?php
			// 工具模式：极简模式下把子页面渲染成工具卡片网格。
			if ( aurora_minimal_is_minimal_mode() ) {
				aurora_minimal_render_tool_cards( (int) get_the_ID() );
			}

			// 最新文章区块：极简模式下更突出，工具模式下作为次要区块。
			$am_latest = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 6,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		if ( $am_latest->have_posts() ) :
			?>
			<section class="am-latest">
				<h2 class="am-latest__title"><?php esc_html_e( '最新文章', 'aurora-minimal' ); ?></h2>

				<?php if ( 'grid' === aurora_minimal_get_option( 'archive_layout' ) ) : ?>
					<div class="am-post-list am-post-list--grid">
				<?php else : ?>
					<div class="am-post-list">
				<?php endif; ?>

					<?php
					while ( $am_latest->have_posts() ) {
						$am_latest->the_post();
						aurora_minimal_post_card();
					}
					?>
				</div>
			</section>
			<?php
		endif;

		wp_reset_postdata();

	elseif ( have_posts() ) :
		?>
		<?php aurora_minimal_page_header(); ?>

		<div class="<?php echo esc_attr( aurora_minimal_archive_class() ); ?>">
			<?php
			while ( have_posts() ) {
				the_post();
				aurora_minimal_post_card();
			}
			?>
		</div>

		<?php aurora_minimal_pagination(); ?>

	<?php else : ?>

		<div class="am-no-results">
			<h2 class="am-no-results__title"><?php esc_html_e( '欢迎使用 Aurora Minimal', 'aurora-minimal' ); ?></h2>
			<p><?php esc_html_e( '还没有发布内容。在后台创建第一篇文章，或先配置一下自定义器。', 'aurora-minimal' ); ?></p>
			<?php get_search_form(); ?>
		</div>

	<?php endif; ?>

</div>

<?php
get_sidebar();

get_footer();

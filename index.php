<?php
/**
 * 主模板文件（fallback template）。
 *
 * 当没有更具体的模板（single/page/archive/search/404）匹配时使用。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

get_header();
?>

<div class="am-container">
	<?php aurora_minimal_breadcrumb(); ?>

	<div class="am-content-area">
		<div class="am-primary">
			<?php if ( have_posts() ) : ?>

				<?php
				if ( is_home() && ! is_front_page() ) {
					aurora_minimal_page_header();
				}
				?>

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
					<h2 class="am-no-results__title"><?php esc_html_e( '暂时没有内容', 'aurora-minimal' ); ?></h2>
					<p><?php esc_html_e( '这个位置还没有发布任何内容，换个关键词或从首页开始浏览。', 'aurora-minimal' ); ?></p>
					<p>
						<a class="am-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php esc_html_e( '返回首页', 'aurora-minimal' ); ?>
						</a>
					</p>
				</div>

				<?php get_search_form(); ?>

			<?php endif; ?>
		</div>

		<?php aurora_minimal_sidebar( 'sidebar-1' ); ?>
	</div>
</div>

<?php
get_footer();

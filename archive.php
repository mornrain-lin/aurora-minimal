<?php
/**
 * 归档模板：分类、标签、日期、作者、自定义分类法归档。
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

				<?php aurora_minimal_page_header(); ?>

				<?php
				$am_taxonomies = array( 'category', 'post_tag' );

				if ( is_category() || is_tag() ) {
					aurora_minimal_archive_term_bar( 'category' );
				} elseif ( is_tax( $am_taxonomies ) ) {
					$am_current_tax = get_queried_object();
					if ( $am_current_tax instanceof WP_Term ) {
						aurora_minimal_archive_term_bar( $am_current_tax->taxonomy );
					}
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
					<h2 class="am-no-results__title"><?php esc_html_e( '该归档暂无内容', 'aurora-minimal' ); ?></h2>
					<p><?php esc_html_e( '试试浏览全部分类，或使用搜索功能查找内容。', 'aurora-minimal' ); ?></p>
					<?php get_search_form(); ?>
				</div>

			<?php endif; ?>
		</div>

		<?php aurora_minimal_sidebar( 'sidebar-1' ); ?>
	</div>
</div>

<?php
get_footer();

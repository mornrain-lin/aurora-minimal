<?php
/**
 * 搜索结果模板。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

get_header();
?>

<div class="am-container">
	<div class="am-content-area">
		<div class="am-primary">

			<header class="am-search-header">
				<h1 class="am-page-header__title">
					<?php
					printf(
						/* translators: %s：搜索关键词。 */
						esc_html__( '搜索结果：%s', 'aurora-minimal' ),
						esc_html( get_search_query() )
					);
					?>
				</h1>

				<?php if ( have_posts() ) : ?>
					<p class="am-search-summary">
						<?php
						global $wp_query;

						$am_found = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;

						printf(
							/* translators: %s：结果数量。 */
							esc_html( _n( '共找到 %s 条结果。', '共找到 %s 条结果。', $am_found, 'aurora-minimal' ) ),
							esc_html( number_format_i18n( $am_found ) )
						);
						?>
					</p>
				<?php endif; ?>
			</header>

			<?php if ( have_posts() ) : ?>

				<div class="am-post-list">
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
					<h2 class="am-no-results__title"><?php esc_html_e( '没有找到相关内容', 'aurora-minimal' ); ?></h2>
					<p><?php esc_html_e( '请尝试更换关键词，或使用更简短的词组。', 'aurora-minimal' ); ?></p>
					<?php get_search_form(); ?>
				</div>

			<?php endif; ?>

		</div>

		<?php aurora_minimal_sidebar( 'sidebar-1' ); ?>
	</div>
</div>

<?php
get_footer();

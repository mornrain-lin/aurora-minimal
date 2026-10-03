<?php
/**
 * 404 未找到页面模板。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

get_header();
?>

<div class="am-container">
	<div class="am-404">
		<p class="am-404__code">404</p>
		<h1 class="am-404__title"><?php esc_html_e( '页面走丢了', 'aurora-minimal' ); ?></h1>
		<p class="am-404__text"><?php esc_html_e( '你访问的地址不存在，可能已被移动或删除。', 'aurora-minimal' ); ?></p>

		<div class="am-404__actions">
			<a class="am-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '返回首页', 'aurora-minimal' ); ?></a>
			<button type="button" class="am-button am-button--ghost" data-am-focus-search><?php esc_html_e( '站内搜索', 'aurora-minimal' ); ?></button>
		</div>

		<?php get_search_form(); ?>

		<?php aurora_minimal_404_suggestions( 5 ); ?>
	</div>
</div>

<?php
get_footer();

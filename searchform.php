<?php
/**
 * 搜索表单模板。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

$am_search_id = 'am-search-' . wp_rand( 1000, 9999 );
?>

<form role="search" method="get" class="am-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="am-screen-reader-text" for="<?php echo esc_attr( $am_search_id ); ?>">
		<?php esc_html_e( '搜索本站内容', 'aurora-minimal' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $am_search_id ); ?>"
		class="am-search-form__field"
		placeholder="<?php esc_attr_e( '输入关键词…', 'aurora-minimal' ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s"
	>
	<button type="submit" class="am-search-form__submit">
		<?php esc_html_e( '搜索', 'aurora-minimal' ); ?>
	</button>
</form>

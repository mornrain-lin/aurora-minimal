<?php
/**
 * 侧栏模板。
 *
 * 供需要独立侧栏结构的模板使用；极简模式下不输出。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

aurora_minimal_sidebar( 'sidebar-1' );

/**
 * Aurora Minimal 阅读进度条脚本。
 *
 * 计算正文区域（.am-entry__content 或 main）的阅读进度，
 * 写入进度条宽度并同步 aria-valuenow，保证无障碍可读。
 */
( function () {
	'use strict';

	function initReadingProgress() {
		var bar = document.querySelector( '.am-progress__bar' );
		var container = document.querySelector( '.am-progress' );

		if ( ! bar || ! container ) {
			return;
		}

		var target = document.querySelector( '.am-entry__content' ) || document.querySelector( 'main' );

		if ( ! target ) {
			return;
		}

		function computeProgress() {
			var rect = target.getBoundingClientRect();
			var viewport = window.innerHeight || document.documentElement.clientHeight;

			// 正文顶部滚出视口之前视为 0%。
			if ( rect.top >= viewport ) {
				return 0;
			}

			// 需要滚动的总距离：正文高度减去一屏。
			var total = rect.height - viewport;

			if ( total <= 0 ) {
				return 100;
			}

			var scrolled = -rect.top;

			return Math.min( 100, Math.max( 0, ( scrolled / total ) * 100 ) );
		}

		function update() {
			var progress = computeProgress();
			var rounded = Math.round( progress * 10 ) / 10;

			bar.style.width = rounded + '%';
			container.setAttribute( 'aria-valuenow', String( Math.round( progress ) ) );
		}

		var ticking = false;

		function onScroll() {
			if ( ticking ) {
				return;
			}

			ticking = true;
			window.requestAnimationFrame( function () {
				update();
				ticking = false;
			} );
		}

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		window.addEventListener( 'resize', onScroll, { passive: true } );
		window.addEventListener( 'load', update );

		update();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', initReadingProgress );
	} else {
		initReadingProgress();
	}
} )();

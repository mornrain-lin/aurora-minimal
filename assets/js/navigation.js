/**
 * Aurora Minimal 导航交互脚本。
 *
 * 纯原生 JS，无 jQuery 依赖，文件以 defer 方式在页脚加载。
 * 负责：移动端菜单展开、二级菜单折叠、回到顶部、404 页搜索框聚焦。
 */
( function () {
	'use strict';

	/**
	 * 移动端主导航展开/收起。
	 */
	function initNavToggle() {
		var toggle = document.querySelector( '[data-am-nav-toggle]' );

		if ( ! toggle ) {
			return;
		}

		var nav = document.getElementById( toggle.getAttribute( 'aria-controls' ) );

		if ( ! nav ) {
			return;
		}

		toggle.addEventListener( 'click', function () {
			var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';

			toggle.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );
			nav.classList.toggle( 'is-open', ! expanded );
		} );

		// 点击菜单外部区域时收起。
		document.addEventListener( 'click', function ( event ) {
			if ( nav.classList.contains( 'is-open' ) === false ) {
				return;
			}

			if ( nav.contains( event.target ) || toggle.contains( event.target ) ) {
				return;
			}

			nav.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		} );

		// ESC 收起。
		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' !== event.key ) {
				return;
			}

			nav.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		} );
	}

	/**
	 * 移动端二级菜单：点击父项展开/收起。
	 */
	function initSubmenuToggle() {
		var items = document.querySelectorAll( '.am-primary-nav li.has-children' );

		if ( ! items.length ) {
			return;
		}

		Array.prototype.forEach.call( items, function ( item ) {
			var link = item.querySelector( ':scope > a' );
			var submenu = item.querySelector( ':scope > .sub-menu' );

			if ( ! link || ! submenu ) {
				return;
			}

			// 桌面端用hover，移动端点击时切换。
			link.addEventListener( 'click', function ( event ) {
				if ( window.matchMedia( '(min-width: 783px)' ).matches ) {
					return;
				}

				var expanded = item.classList.toggle( 'is-expanded' );

				event.preventDefault();
				link.setAttribute( 'aria-expanded', expanded ? 'true' : 'false' );
			} );
		} );
	}

	/**
	 * 回到顶部按钮：滚动超过一屏后出现，点击平滑回到顶部。
	 */
	function initBackToTop() {
		var button = document.querySelector( '[data-am-to-top]' );

		if ( ! button ) {
			return;
		}

		function updateVisibility() {
			var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
			button.classList.toggle( 'is-visible', scrollTop > 400 );
		}

		var ticking = false;

		window.addEventListener(
			'scroll',
			function () {
				if ( ticking ) {
					return;
				}

				ticking = true;
				window.requestAnimationFrame( function () {
					updateVisibility();
					ticking = false;
				} );
			},
			{ passive: true }
		);

		button.addEventListener( 'click', function () {
			var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
			window.scrollTo( { top: 0, behavior: reduce ? 'auto' : 'smooth' } );
		} );

		updateVisibility();
	}

	/**
	 * 404 页：点击「站内搜索」聚焦搜索框。
	 */
	function initSearchFocus() {
		var button = document.querySelector( '[data-am-focus-search]' );

		if ( ! button ) {
			return;
		}

		button.addEventListener( 'click', function () {
			var field = document.querySelector( '.am-404 .am-search-form__field' );

			if ( field ) {
				field.focus();
				return;
			}

			var navField = document.querySelector( '.am-site-header .am-search-form__field' );

			if ( navField ) {
				navField.focus();
			}
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			initNavToggle();
			initSubmenuToggle();
			initBackToTop();
			initSearchFocus();
		} );
	} else {
		initNavToggle();
		initSubmenuToggle();
		initBackToTop();
		initSearchFocus();
	}
} )();

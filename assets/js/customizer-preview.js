/**
 * Aurora Minimal Customizer 实时预览脚本。
 *
 * 只做文本节点与 class 的即时更新，不发起任何网络请求。
 */
( function ( $ ) {
	'use strict';

	if ( ! window.wp || ! window.wp.customize ) {
		return;
	}

	var api = window.wp.customize;

	api( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			$( '.am-brand__title a' ).text( to );
		} );
	} );

	api( 'blogdescription', function ( value ) {
		value.bind( function ( to ) {
			$( '.am-brand__tagline' ).text( to );
		} );
	} );
} )( window.jQuery );

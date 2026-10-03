<?php
/**
 * 站点头部模板。
 *
 * 输出 <!DOCTYPE html> 到 <body> 以及站点头部（Logo / 导航 / 搜索）。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<a class="am-skip-link" href="#am-content"><?php esc_html_e( '跳到正文', 'aurora-minimal' ); ?></a>

<div id="page" class="am-site">

	<header id="masthead" class="<?php echo esc_attr( 'am-site-header' . ( aurora_minimal_get_option( 'sticky_header' ) ? ' am-site-header--sticky' : '' ) ); ?>">
		<div class="am-container">
			<div class="am-site-header__inner">

				<?php aurora_minimal_site_branding(); ?>

				<button
					type="button"
					class="am-nav-toggle"
					aria-expanded="false"
					aria-controls="am-primary-nav"
					data-am-nav-toggle
				>
					<span class="am-nav-toggle__bars" aria-hidden="true"></span>
					<span class="am-nav-toggle__label"><?php esc_html_e( '菜单', 'aurora-minimal' ); ?></span>
				</button>

				<nav id="am-primary-nav" class="am-primary-nav" aria-label="<?php esc_attr_e( '主导航', 'aurora-minimal' ); ?>">
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_id'        => 'am-primary-menu',
								'menu_class'     => 'am-primary-menu',
								'container'      => false,
								'depth'          => 2,
								'walker'         => new Aurora_Minimal_Nav_Walker(),
								'fallback_cb'    => false,
							)
						);
					} else {
						echo '<ul class="am-primary-menu">';
						printf(
							'<li><a href="%1$s">%2$s</a></li>',
							esc_url( home_url( '/' ) ),
							esc_html__( '首页', 'aurora-minimal' )
						);
						wp_list_pages(
							array(
								'title_li' => '',
								'depth'    => 1,
								'number'   => 5,
							)
						);
						echo '</ul>';
					}
					?>
				</nav>

				<?php if ( has_nav_menu( 'social' ) ) : ?>
					<nav class="am-social-nav" aria-label="<?php esc_attr_e( '社交链接', 'aurora-minimal' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'social',
								'container'      => false,
								'menu_class'     => 'am-social-menu',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				<?php endif; ?>

			</div>
		</div>
	</header>

	<main id="am-content" class="am-main" tabindex="-1">

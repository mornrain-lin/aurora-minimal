<?php
/**
 * 站点页脚模板。
 *
 * 关闭 <main>，输出页脚 Widget、站点信息，并触发 wp_footer。
 *
 * @package AuroraMinimal
 * @since   1.0.0
 */

?>
	</main><!-- #am-content -->

	<footer id="colophon" class="am-site-footer">
		<?php aurora_minimal_footer_widgets(); ?>

		<div class="am-site-info">
			<div class="am-container">
				<div class="am-site-info__inner">
					<p class="am-site-info__copyright">
						<?php
						printf(
							/* translators: 1：年份，2：站点名称。 */
							esc_html__( '© %1$s %2$s', 'aurora-minimal' ),
							esc_html( gmdate( 'Y' ) ),
							esc_html( get_bloginfo( 'name', 'display' ) )
						);
						?>
					</p>

					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<nav class="am-footer-nav" aria-label="<?php esc_attr_e( '页脚导航', 'aurora-minimal' ); ?>">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'container'      => false,
									'menu_class'     => 'am-footer-menu',
									'depth'          => 1,
									'fallback_cb'    => false,
								)
							);
							?>
						</nav>
					<?php endif; ?>

					<p class="am-site-info__credit">
						<?php
						printf(
							/* translators: %s：主题名称。 */
							esc_html__( '由 %s 强力驱动', 'aurora-minimal' ),
							'<span class="am-site-info__theme">' . esc_html__( 'Aurora Minimal', 'aurora-minimal' ) . '</span>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 内部已转义。
						);
						?>
					</p>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>

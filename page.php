<?php
/**
 * 单页面模板。
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
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'am-entry' ); ?>>

					<header class="am-entry__header">
						<h1 class="am-entry__title"><?php the_title(); ?></h1>
					</header>

					<?php aurora_minimal_entry_thumbnail(); ?>

					<div class="am-entry__content">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before'      => '<nav class="am-page-links">' . esc_html__( '页面：', 'aurora-minimal' ),
								'after'       => '</nav>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
							)
						);
						?>
					</div>

					<footer class="am-entry__footer">
						<?php
						edit_post_link(
							sprintf(
								/* translators: %s：页面标题。 */
								esc_html__( '编辑「%s」', 'aurora-minimal' ),
								esc_html( get_the_title() )
							),
							'<span class="am-edit-link">',
							'</span>'
						);
						?>
					</footer>

				</article>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>

			<?php
			endwhile;
			?>
		</div>

		<?php aurora_minimal_sidebar( 'sidebar-1' ); ?>
	</div>
</div>

<?php
get_footer();

<?php
/**
 * 单篇文章模板。
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
						<?php
						if ( aurora_minimal_has_excerpt() ) {
							$lead = get_the_excerpt();
							printf( '<p class="am-entry__lead">%s</p>', esc_html( $lead ) );
						}
						?>
						<h1 class="am-entry__title"><?php the_title(); ?></h1>
						<?php aurora_minimal_entry_meta(); ?>
					</header>

					<?php aurora_minimal_entry_thumbnail(); ?>

					<div class="am-entry__content">
						<?php
						the_content(
							sprintf(
								/* translators: %s：文章标题。 */
								esc_html__( '继续阅读「%s」', 'aurora-minimal' ),
								esc_html( get_the_title() )
							)
						);

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
						<?php aurora_minimal_entry_footer(); ?>
					</footer>

				</article>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

				// 相关阅读：同分类，排除当前文章，最多 3 篇。
				$category_ids = wp_get_post_categories( get_the_ID() );

				if ( ! empty( $category_ids ) ) {
					$related_query = new WP_Query(
						array(
							'post_type'           => 'post',
							'post_status'         => 'publish',
							'posts_per_page'      => 3,
							'post__not_in'        => array( get_the_ID() ),
							'category__in'        => $category_ids,
							'ignore_sticky_posts' => true,
							'no_found_rows'       => true,
						)
					);

					if ( $related_query->have_posts() ) {
						?>
						<section class="am-related">
							<h2 class="am-related__title"><?php esc_html_e( '相关阅读', 'aurora-minimal' ); ?></h2>
							<ul class="am-post-list">
								<?php
								while ( $related_query->have_posts() ) {
									$related_query->the_post();
									aurora_minimal_post_card();
								}
								?>
							</ul>
						</section>
						<?php
					}

					wp_reset_postdata();
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

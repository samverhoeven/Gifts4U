<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package XShop
 */

$xshop_blog_style = get_theme_mod( 'xshop_blog_style', 'grid' );
if( $xshop_blog_style == 'grid' ):
	get_template_part( 'template-parts/content', 'grid' );
else:
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="xpost-item shadow pb-5 mb-5">

		<?php xshop_post_thumbnail(); ?>
		<div class="xpost-text p-3">
			<header class="entry-header text-center pb-4">
				<?php
				if ( is_singular() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;

				if ( 'post' === get_post_type() ) :
					?>
					<div class="entry-meta">
						<?php
						xshop_posted_on();
						xshop_posted_by();
						?>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
				the_excerpt(  );
				?>
				</div><!-- .entry-content -->
			<footer class="entry-footer">
				<?php xshop_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
<?php endif; ?>
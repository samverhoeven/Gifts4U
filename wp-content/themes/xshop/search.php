<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package XShop
 */

$xshop_blog_container = get_theme_mod( 'xshop_blog_container', 'container' );
$xshop_blog_layout = get_theme_mod( 'xshop_blog_layout', 'rightside' );
$xshop_blog_style = get_theme_mod( 'xshop_blog_style', 'grid' );
if ( is_active_sidebar( 'sidebar-1' ) && $xshop_blog_layout != 'fullwidth' ) {
	$xshop_blog_column = 'col-lg-9';
}else{
	$xshop_blog_column = 'col-lg-12';
}
get_header();
?>

<div class="<?php echo esc_attr($xshop_blog_container); ?> mt-5 mb-5 pt-5 pb-5">
			<div class="row">
			<?php if ( is_active_sidebar( 'sidebar-1' ) && $xshop_blog_layout == 'leftside' ): ?>
				<div class="col-lg-3">
					<?php get_sidebar(); ?>
				</div>
				<?php endif; ?>
				<div class="<?php echo esc_attr($xshop_blog_column); ?>">
					<main id="primary" class="site-main">


		<?php if ( have_posts() ) : ?>

			<header class="page-header search-header shadow p-4 mb-5 text-center">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'xshop' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->
			<?php if( $xshop_blog_style == 'grid' ): ?>
					<div class="bplus-gridh mb-5">
						<div class="grid row" data-masonry='{"percentPosition": true }'>
				<?php endif; ?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );

			endwhile;
			?>
				<?php if( $xshop_blog_style == 'grid' ): ?>
						</div>
					</div>
				<?php endif; ?>
			<?php

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

</main><!-- #main -->
				</div>
				<?php if ( is_active_sidebar( 'sidebar-1' ) && $xshop_blog_layout == 'rightside' ): ?>
				<div class="col-lg-3">
					<?php get_sidebar(); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>

<?php
get_footer();

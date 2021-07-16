<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package XShop
 */

$xshop_woo_container = get_theme_mod( 'xshop_woo_container', 'container' );
$xshop_woo_layout = get_theme_mod( 'xshop_woo_layout', 'rightside' );
if( !is_single() ){
	$xshop_shop_container = $xshop_woo_container;
}else{
	$xshop_shop_container = 'container';
}
if ( is_active_sidebar( 'shop-sidebar' ) && $xshop_woo_layout != 'fullwidth' && !is_single() ) {
	$xshop_column_set = 'col-lg-9';
}else{
	$xshop_column_set = 'col-lg-12';
}

get_header();
?>
	<div class="<?php echo esc_attr($xshop_shop_container); ?> mt-3 mb-5 pt-5 pb-3">
		<div class="row">
		<?php if ( is_active_sidebar( 'shop-sidebar' ) && $xshop_woo_layout == 'leftside' && !is_single() ): ?>
				<div class="col-lg-3">
				<?php dynamic_sidebar( 'shop-sidebar' ); ?>
				</div>
				<?php endif; ?>
			<div class="<?php echo esc_attr($xshop_column_set); ?>">
				<div id="primary" class="content-area">
				<main id="main" class="site-main">

					<?php woocommerce_content(); ?>

				</main><!-- #main -->
				</div><!-- #primary -->
			</div><!-- #primary -->
			<?php if ( is_active_sidebar( 'shop-sidebar' ) && $xshop_woo_layout == 'rightside' && !is_single() ): ?>
				<div class="col-lg-3">
				<?php dynamic_sidebar( 'shop-sidebar' ); ?>
				</div>
			<?php endif; ?>
	</div>
</div>
<?php
get_footer();
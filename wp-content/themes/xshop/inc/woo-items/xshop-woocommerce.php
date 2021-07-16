<?php
/*
*
* Xshop woocommerce related functions
*
*
*/

require get_template_directory() . '/inc/woo-items/shop-customizer.php';


function xshop_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'xshop_woocommerce_setup' );

function xshop_woocommerce_scripts() {
	wp_enqueue_style( 'xshop-woocommerce-style', get_stylesheet_directory_uri() . '/assets/css/xshop-woocommerce.css' );

}
add_action( 'wp_enqueue_scripts', 'xshop_woocommerce_scripts' );

if ( ! function_exists( 'xshop_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function xshop_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		xshop_woocommerce_cart_link();
		$fragments['.xshoping-bag'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'xshop_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'xshop_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function xshop_woocommerce_cart_link() {
		
		$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '+%d', '+%d', WC()->cart->get_cart_contents_count(), 'xshop' ),
				WC()->cart->get_cart_contents_count()
			);
		?>
		<div class="xshoping-bag" data-toggle="modal" data-target="#cartModal">
			<div class="xshoping-inner-bag">
				<i  class="fa fa-shopping-basket"></i>
				<span class="count cart-contents"><?php echo esc_html( $item_count_text ); ?></span>
			</div> 
		</div> 
		

		<?php
	}
}

if ( ! function_exists( 'xshop_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function xshop_woocommerce_header_cart() {

		if ( is_cart() || is_checkout() ) {
			$xshop_class = 'current-menu-item xcart-page d-none';
		} else {
			$xshop_class = 'not-cart-page';
		}

		?>
		<div class="xshoping-cart <?php echo esc_attr($xshop_class); ?>">
		<?php xshop_woocommerce_cart_link(); ?>
		<!-- Modal -->
		<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="xcartTitle" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="xcartTitle"><?php echo esc_html__( 'Shopping Cart ','xshop' ); ?></h5>
			      </div>
			      <div class="modal-body">
			        <?php
							$instance = array(
								'title' => '',
							);

							the_widget( 'WC_Widget_Cart', $instance );
							?>
				
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php esc_html_e( 'Close', 'xshop' ); ?>
				 </button>
			      </div>
			    </div>
			  </div>
			</div>

		</div>
		<?php
	}
}

function xshop_shop_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'xshop' ),
		'id'            => 'shop-sidebar',
		'description'   => esc_html__( 'Add shop widgets here.', 'xshop' ),
		'before_widget' => '<section id="%1$s" class="widget shadow mb-4 p-3 %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
}
add_action( 'widgets_init', 'xshop_shop_widgets_init' );


function xshop_before_single_product_div(){
	echo '<div class="xshop-single-product" ?>';
}
add_action('woocommerce_before_single_product_summary','xshop_before_single_product_div',5);

function xshop_after_single_product_div(){
	echo '</div>';
}
add_action('woocommerce_after_single_product_summary','xshop_after_single_product_div',5);





function xshop_woo_body_classes( $classes ) {
	
	if ( ! is_active_sidebar( 'shop-sidebar' ) && is_shop() ) {
		$classes[] = 'no-shop-widget';
	}
	if ( is_front_page() && is_shop() ) {
		$classes[] = 'xfront-shop';
	}

	return $classes;
}
add_filter( 'body_class', 'xshop_woo_body_classes' );


/**
 * Change number or products per row 
 */
add_filter('loop_shop_columns', 'xshop_loop_columns', 999);
if (!function_exists('xshop_loop_columns')) {
	function xshop_loop_columns() {
		if( is_active_sidebar( 'shop-sidebar' ) ){
			return 3; // 3 products per row
		}else{
			return 4; // 3 products per row
		}
	}
}

add_filter( 'woocommerce_output_related_products_args', 'xshop_related_products_args', 20 );
  function xshop_related_products_args( $args ) {
	if( is_active_sidebar( 'shop-sidebar' ) ){
		$args['posts_per_page'] = 3; // 4 related products
		$args['columns'] = 3; // arranged in 2 columns
	}else{
		$args['posts_per_page'] = 4; // 4 related products
	}
	return $args;
}
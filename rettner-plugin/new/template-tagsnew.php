<?php
/**
 * Custom template tags used to integrate this theme with WooCommerce.
 * Deletes header cart and search bar in header
 * @package storefront
 */

/**
 * Cart Link
 * Displayed a link to the cart including the number of items present and the cart total
 * @param  array $settings Settings
 * @return array           Settings
 * @since  1.0.0
 */
if ( ! function_exists( 'storefront_cart_link' ) ) {
	function storefront_cart_link() {
		?>
			<a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php _e( 'View your shopping cart', 'storefront' ); ?>">
				<?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?> <span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'storefront' ), WC()->cart->get_cart_contents_count() ) );?></span>
			</a>
		<?php
	}
}



/**
 * Upsells
 * Replace the default upsell function with our own which displays the correct number product columns
 * @since   1.0.0
 * @return  void
 * @uses    woocommerce_upsell_display()
 */
if ( ! function_exists( 'storefront_upsell_display' ) ) {
	function storefront_upsell_display() {
		woocommerce_upsell_display( -1, 3 );
	}
}

/**
 * Sorting wrapper
 * @since   1.4.3
 * @return  void
 */
function storefront_sorting_wrapper() {
	echo '<div class="storefront-sorting">';
}

/**
 * Sorting wrapper close
 * @since   1.4.3
 * @return  void
 */
function storefront_sorting_wrapper_close() {
	echo '</div>';
}

/**
 * Storefront shop messages
 * @since   1.4.4
 * @uses    do_shortcode
 */
function storefront_shop_messages() {
	if ( ! is_checkout() ) {
		echo wp_kses_post( storefront_do_shortcode( 'woocommerce_messages' ) );
	}
}

/**
 * Storefront WooCommerce Pagination
 * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
 * but since Storefront adds pagination before that function is excuted we need a separate function to
 * determine whether or not to display the pagination.
 * @since 1.4.4
 */
if ( ! function_exists( 'storefront_woocommerce_pagination' ) ) {
	function storefront_woocommerce_pagination() {
		if ( woocommerce_products_will_display() ) {
			woocommerce_pagination();
		}
	}
}

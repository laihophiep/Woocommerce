add_filter( 'woocommerce_cart_item_name', array($this,'allowEditProductOnCheckoutPage'), 10, 3 );
function allowEditProductOnCheckoutPage( $product_name, $cart_item, $cart_item_key ) {
		if ( is_checkout() ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
	
			$remove_link = apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
				'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">×</a>',
				esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
				__( 'Remove this item', 'woocommerce' ),
				esc_attr( $product_id ),
				esc_attr( $_product->get_sku() )
			), $cart_item_key );
	
			return '<span>' . $remove_link . '</span> <span>' . $product_name . '</span>';
		}
	
		return $product_name;
	}

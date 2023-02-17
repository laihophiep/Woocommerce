function action_woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

$webengage_action = "Added To Cart";
$cartitems = WC()->cart->get_cart();
$cart_item = $cartitems[$cart_item_key];
$product = $cart_item['data'];
$product_name = $product->get_name();
$product_sku = $product->get_sku();
$product_id = $cart_item['product_id'];
$quantity = $cart_item['quantity'];
$price = WC()->cart->get_product_price( $product );
$subtotal = WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] );

$get_variation = wc_get_product($variation_id);
$variation_name = $variation->get_formatted_name();

$event_data = array {
'product_id' => $product_id,
'price' => $price,
'quantity' => $quantity,
'sku_code' => $product_sku,
'product_name' => $variation_name
'currency' => 'INR',
}
$data = array(
'userId' => get_current_user_id(),
'eventName' => $webengage_action,
'eventTime' => current_time('Y-m-d'),
'eventData' => $event_data
)
$webengage_API = "PUT_API_HERE";
$webengage_url = "https://api.webengage.com/v1/accounts/" .$webengage_API ."/events";
$response = wp_remote_post( $webengage_url, array( 'data' => $data ) );
}
add_action( 'woocommerce_add_to_cart', 'action_woocommerce_add_to_cart', 10, 6 );

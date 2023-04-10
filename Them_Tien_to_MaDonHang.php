add_filter( 'woocommerce_order_number', 'change_woocommerce_order_number' );
function change_woocommerce_order_number( $order_id ) {
    $prefix = 'TECH_000000';
    $new_order_id = $prefix . $order_id ;
    return $new_order_id;
}

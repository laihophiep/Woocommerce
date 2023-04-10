foreach ( $order->get_items() as $item_id => $item ) {
    $product_id = $item->get_product_id();
    $variation_id = $item->get_variation_id();
    $product = $item->get_product();
    $product_name = $item->get_name();
    $quantity = $item->get_quantity();
    $subtotal = $item->get_subtotal();
    $total = $item->get_total();
    $price = $subtotal / $quantity;
    $tax = $item->get_subtotal_tax();
    $taxclass = $item->get_tax_class();
    $taxstat = $item->get_tax_status();
    $allmeta = $item->get_meta_data();
    $somemeta = $item->get_meta( '_whatever', true );
    $product_type = $item->get_type();
    $permalink = get_permalink($product_id);
}

thêm thông tin giỏ hàng vào trang thanh toán
add_action( 'woocommerce_before_checkout_form', 'add_cart_on_checkout', 5 );
 
function add_cart_on_checkout() {
 if ( is_wc_endpoint_url( 'order-received' ) ) return;
 echo do_shortcode('[woocommerce_cart]'); // WooCommerce cart page shortcode
}
chuyển hướng giỏ hàng sang thanh toán
add_action( 'template_redirect', function() {
// Replace "cart"  and "checkout" with cart and checkout page slug if needed
    if ( is_page( 'cart' ) ) {
        wp_redirect( '/checkout/' );
        die();
    }
} );
Bước 3: Chuyển hướng trang thanh toán WooCommerce sang trang cửa hàng.
Phần này chỉ cần thiết nếu bạn đã thêm chuyển hướng từ trang giỏ hàng WooCommerce sang trang thanh toán ở bước 2. Nếu bạn chưa làm điều đó thì bạn có thể bỏ qua phần này.

Nếu bạn đã thực hiện bước 2 thì phần này rất quan trọng và bạn cần chú ý. Nếu không bạn sẽ gặp lỗi “Too many redirects” trên trang thanh toán sau khi xóa sản phẩm khỏi giỏ hàng.

Hãy xem qua slug “shop” bên trong đoạn mã. Thay thế nó bằng slug phù hợp theo website của bạn, ví dụ như “cua-hang”.

add_action( 'template_redirect', 'redirect_empty_checkout' );
 
function redirect_empty_checkout() {
    if ( is_checkout() && 0 == WC()->cart->get_cart_contents_count() && ! is_wc_endpoint_url( 'order-pay' ) && ! is_wc_endpoint_url( 'order-received' ) ) {
   wp_safe_redirect( get_permalink( wc_get_page_id( 'shop' ) ) ); 
        exit;
    }
}

Tùy chọn thay thế: Hiển thị nút “Quay lại cửa hàng” trong trang thanh toán trống.
Nếu bạn không muốn chuyển hướng khách hàng của mình đến trang cửa hàng mà thay vào đó muốn hiển thị nút “Quay lại cửa hàng”. Hãy bỏ qua đoạn mã trước đó và sử dụng đoạn mã này tại đây.

add_filter( 'woocommerce_checkout_redirect_empty_cart', '__return_false' );
add_filter( 'woocommerce_checkout_update_order_review_expired', '__return_false' );
1
2
Bước 4: Tùy chỉnh trang thanh toán WooCommerce.
Cuối cùng, hãy thêm một số đoạn CSS tuỳ chỉnh để giao diện của bạn được hoàn chỉnh hơn.

Và đây là thành quả những gì bạn có thể đạt được:


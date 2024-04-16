<?php 
add_action( 'woocommerce_cart_calculate_fees', 'discount_based_on_cart_total', 10, 1 );
function discount_based_on_cart_total( $cart_object ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    $cart_total = $cart_object->cart_contents_total; // Cart total

    if ( $cart_total > 150.00 )
        $percent = 15; // 15%
    elseif ( $cart_total >= 100.00 && $cart_total < 150.00 ) $percent = 10; // 10% 
    elseif ( $cart_total >= 50.00 && $cart_total < 100.00 ) $percent = 5; // 5% 

    else $percent = 0; if ( $percent != 0 ) { $discount = $cart_total * $percent / 100; 
    $cart_object->add_fee( "Discount ($percent%)", -$discount, true );
    }
}

//Add to cart with meta item
//meta item đây chính là domain name

WC()->cart->add_to_cart( $_POST['id'], 1, 0, array(), array( 'domain_name' => $_POST['domain_name']) );
Với $_POST[‘domain_name’] chính là tên miền

Cập nhật product name trong cart, checkout, order
function customDomainName( $cart_object ) {
	
		foreach ( $cart_object->get_cart() as $item ) {
	
			if( array_key_exists( 'domain_name', $item ) ) {
				$item[ 'data' ]->set_name( $item[ 'domain_name' ] );
			}
		  
		}
		
	}
add_action( 'woocommerce_before_calculate_totals', array($this, 'customDomainName') );

Lưu ý code trên dùng trong class

xong rồi đó

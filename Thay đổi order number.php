function change_woocommerce_order_number( $order_id ) {
    $count = strlen($order_id);
    $prefix = '';
    switch($count){
        case 1:
            $prefix = "HD00000";
            break;
        case 2:
            $prefix = "HD0000";
            break;	
        case 3:
            $prefix = "HD000";
            break;	
        case 4:
            $prefix = "HD00";
            break;	
        case 5:
            $prefix = "HD0";
            break;	
        case 6:
            $prefix = "HD";
            break;	
        default:
        break;
    }
    $new_order_id = $prefix . $order_id ;
    return $new_order_id;
}
add_filter( 'woocommerce_order_number', array($this,'change_woocommerce_order_number') );

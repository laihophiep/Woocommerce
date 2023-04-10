function create_sku_from_product_id($product_id){
    $sku = '';
    if(strlen($product_id) == 1){
        $sku = 'A00'.$product_id;
    }elseif(strlen($product_id) == 2){
        $sku = 'A0'.$product_id;
    }else{
        $sku = 'A'.$product_id;
    }
    return $sku;
}
function auto_create_sku_after_post_product( $post_id, $post ) {
    if($post->post_type == "product"){
        $sku = create_sku_from_product_id($post_id);
        update_post_meta($post_id,'_sku',$sku);
    }
}
add_action( 'save_post','auto_create_sku_after_post_product', 20, 2 );

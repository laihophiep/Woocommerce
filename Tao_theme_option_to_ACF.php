// hien thi o menu
if( function_exists('acf_add_options_page') ) {
     
    acf_add_options_page(array(
        'page_title'    => 'Theme option custom PTTUAN',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'  => false
    ));
}

///tc : Tất cả
lt : Loại trừ
dc : Được chọn
///

function show_thong_tin(){
    global $post;
    $thong_tin = get_field('thong_tin','option');
    $loai_hien_thi = get_field('loai_hien_thi','option');
    $danh_sach = get_field('danh_sach','option');
    if($danh_sach){
        $id_array = array();
         
        foreach( $danh_sach as $id_post ): 
        $id_array[] = $id_post->ID;
        endforeach;
        if($loai_hien_thi == 'lt' && !is_single($id_array)){
        echo '<div class="promotion-ptt">'.$thong_tin.'</div>';
        }
        if($loai_hien_thi == 'dc' && is_single($id_array)){
            echo '<div class="promotion-ptt">'.$thong_tin.'</div>';
        }
    }
    if($loai_hien_thi == 'tc'){
        echo '<div class="promotion-ptt">'.$thong_tin.'</div>';
    }
     
}
add_action('woocommerce_single_product_summary','show_thong_tin');
.promotion-ptt {
    border-radius: 8px;
    border: #eee solid 1px;
    box-shadow: 0 6px 12px 0 rgb(0 0 0 / 5%);
    padding: 8px 15px;
    background: #effeff;
}

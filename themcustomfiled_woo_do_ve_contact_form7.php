function cf7_select_product_list($tag, $unused ) {  
  
    if ( $tag['name'] != 'size-sanpham' )  // Tên tag để get dữ liệu cf7 nếu bạn chưa hiểu ở đây đừng quan tâm tí nữa sẽ thấy :D
        return $tag;  
    
    $args = array (); 
    
    $loop = new WP_Query($args);
        
    while( have_rows('re_size') ) : the_row(); // re_size là tên Repeater size 
        
        $id = get_sub_field('pr_size'); // field size
        $title = get_sub_field('pr_size'); 
        
        $tag['raw_values'][] = $id;
        $tag['labels'][] = $title;
        
    endwhile;
    
    $pipes = new WPCF7_Pipes($tag['raw_values']);
    $tag['values'] = $pipes->collect_befores();
    $tag['pipes'] = $pipes;
    
    wp_reset_query();
    
    return $tag;
}  
add_filter( 'wpcf7_form_tag', 'cf7_select_product_list', 10, 2);
// custom field 2
function cf7_select_combo_list($tag, $unused ) {  
  
    if ( $tag['name'] != 'combo-sanpham' )  
        return $tag;  
    
    $args = array (); 
    
    $loop = new WP_Query($args);
        
    while( have_rows('pr_combo') ) : the_row();
        
        $id = get_sub_field('pr_combo_name');
        $title = get_sub_field('pr_combo_name');
        
        $tag['raw_values'][] = $id;
        $tag['labels'][] = $title;
        
    endwhile;
    
    $pipes = new WPCF7_Pipes($tag['raw_values']);
    $tag['values'] = $pipes->collect_befores();
    $tag['pipes'] = $pipes;
    
    wp_reset_query();
    
    return $tag;
}  
add_filter( 'wpcf7_form_tag', 'cf7_select_combo_list', 10, 2);

/** add combo to cf 7 */
function cf7_select_color_list($tag, $unused ) {  
  
    if ( $tag['name'] != 'color-sanpham' )  
        return $tag;  
    
    $args = array (); 
    
    $loop = new WP_Query($args);
        
    while( have_rows('re_color') ) : the_row();
        
        $id = get_sub_field('pr_color');
        $title = get_sub_field('pr_color');
        
        $tag['raw_values'][] = $id;
        $tag['labels'][] = $title;
        
    endwhile;
    
    $pipes = new WPCF7_Pipes($tag['raw_values']);
    $tag['values'] = $pipes->collect_befores();
    $tag['pipes'] = $pipes;
    
    wp_reset_query();
    
    return $tag;
}  
add_filter( 'wpcf7_form_tag', 'cf7_select_color_list', 10, 2);
<div class="formdathang">
[text* hoten id:your-name placeholder "Họ và tên"]
[tel* sdt minlength:9 maxlength:13 id:your-phone placeholder "Nhập số điện thoại"]
[text* diachi id:your-address placeholder "Địa chỉ giao hàng"]
[select* combo-sanpham first_as_label "Chọn Combo"]
[checkbox* color-sanpham]
[select* size-sanpham first_as_label:1 "Chọn Size"]
[radio thanhtoan label_first default:1 "COD" "Chuyển khoản ngân hàng"]
<p style="font-size:14px">Quý khách vui lòng kiểm tra đúng SDT trước khi nhấn đặt hàng</p>
[submit "Đặt hàng"]
</div>
Bạn có thể tùy chỉnh dạng hiện thị field form nhé ở đây mình chọn select và checkbox, nếu bạn đổi checkbox thành select cũng được không vấn đề gì

Giải thích: [select* size-sanpham first_as_label:1 “Chọn Size”]
Select: dạng hiện thị
size-sanpham: là field mình đã tạo ở function gán vào để lấy dữ liệu sản phẩm
first_as_label:1 ” Chọn size” : là mình sử dụng Chọn size để hiện thị đầu tiên còn checkbox thì không cần.

Về phần lưu lại dữ liệu của khách hàng khi đăng ký vào form bạn của thể sử dụng plugin này

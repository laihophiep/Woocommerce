<?php
<?php 
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; // trang dữ hiện tại cần lấy dữ liệu
$posts_per_page = 12; // chia mỗi trang có bao nhiêu kết quả
$args_filter = array(
    'post_type' => array('product'), // post_type trong WooCommerce là product
    'post_status' => array('publish'), // Chỉ lấy dữ liệu đã xuất bản, {pending, draft, auto-draft, future, private, inherit, trash, any}
    'meta_key' => 'total_sales',
    'orderby' => 'meta_value_num',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged
);
$the_query = new WP_query($args_filter);
if($the_query->have_posts()):
?>
<section id="query-results">
    <?php
    while ($the_query->have_posts()) : $the_query->the_post();
            //Truy xuất thông tin tại đây
            $product_id = get_the_ID();
            $product = wc_get_product($product_id);
            echo $product->get_title();
            echo $product->get_price();
    endwhile;
    wp_reset_postdata();
    ?>
</section>
<?php 
endif;
?>
// neu chi co id product
<?php
// Lấy sản phẩm thông qua ID
$product = wc_get_product( $product_id );
  
// Bây giờ bạn có thể truy xuất thông tin như mục 1
$product->get_type();
$product->get_name();
// neu chi co post
<?php
// Lấy sản phẩm thông qua $post
$product = wc_get_product( $post );
  
// Bây giờ bạn có thể truy xuất thông tin như mục 1
$product->get_type();
$product->get_name();

// Lấy Id của Sản phẩm
$product->get_id();
  
// Lấy thông tin cơ bản của Sản phẩm
$product->get_type();                    // Loại sản phẩm
$product->get_name();                    // Tên
$product->get_slug();                    // Đường dẫn slug, vd: ao-thun-gia-re-2021
$product->get_date_created();            // Ngày tạo 
$product->get_date_modified();            // Ngày sửa
$product->get_status();                    // Trạng thái
$product->get_featured();                // Nổi bật
$product->get_catalog_visibility();        // Danh mục
$product->get_description();            // Mô tả
$product->get_short_description();        // Mô tả ngắn
$product->get_sku();                    // Mã SKU
get_permalink( $product->get_id() );    // Đường dẫn sản phẩm
  
// Lấy thông tin giá
$product->get_price();
$product->get_regular_price();
$product->get_sale_price();
$product->get_date_on_sale_from();
$product->get_date_on_sale_to();
$product->get_total_sales();
  
// Lấy thông tin thuế, Shipping & Tồn kho
$product->get_tax_status();
$product->get_tax_class();
$product->get_manage_stock();
$product->get_stock_quantity();
$product->get_stock_status();
$product->get_backorders();
$product->get_sold_individually();
$product->get_purchase_note();
$product->get_shipping_class_id();
  
// Lấy thông tin thuộc tính cơ bản
$product->get_weight();
$product->get_length();
$product->get_width();
$product->get_height();
$product->get_dimensions();
  
// Lấy thông tin sản phẩm liên quan
$product->get_upsell_ids();
$product->get_cross_sell_ids();
$product->get_parent_id();
  
// Lấy thông tin thuộc tính và biến
$product->get_children(); 
$product->get_attributes();
$product->get_default_attributes();
$product->get_attribute( 'attributeid' );
  
// Lấy thông tin danh mục
$product->get_categories();
$product->get_category_ids();
$product->get_tag_ids();
  
// Lấy thông tin tải xuống
$product->get_downloads();
$product->get_download_expiry();
$product->get_downloadable();
$product->get_download_limit();
  
// Lấy thông tin hình ảnh
$product->get_image_id();
$product->get_image();
$product->get_gallery_image_ids();
  
// Lấy thông tin đánh giá
$product->get_reviews_allowed();
$product->get_rating_counts();
$product->get_average_rating();
$product->get_review_count();

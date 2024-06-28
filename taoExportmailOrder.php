// hien thi danh sach cot email o trang oder woocommerce webantam.com
// Thêm cột tùy chỉnh vào bảng đơn hàng
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
function custom_shop_order_column( $columns ) {
    $new_columns = (is_array($columns)) ? $columns : array();
    
    // Chèn cột "Customer Email" vào sau cột "Order Date"
    $new_columns['customer_email'] = __( 'Customer Email', 'your_text_domain' );

    return $new_columns;
}
// Hiển thị nội dung cho cột tùy chỉnh
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 10, 2 );
function custom_orders_list_column_content( $column, $post_id ) {
    if ( $column == 'customer_email' ) {
        $order = wc_get_order( $post_id );
        $email = $order->get_billing_email();
        
        if ( $email ) {
            echo '<a href="mailto:' . $email . '">' . $email . '</a>';
        } else {
            echo __( 'No email found', 'your_text_domain' );
        }
    }
}
// hien thi danh sach cot email o trang oder woocommerce webantam.com end
// Thêm nút "Export Emails" vào trang đơn hàng của WooCommerce webantam.com
add_action('restrict_manage_posts', 'add_export_emails_button');
function add_export_emails_button() {
    global $typenow;
    if ($typenow == 'shop_order') {
        ?>
        <input type="submit" name="export_emails" id="export-emails" class="button" value="<?php _e('Export Emails', 'your_text_domain'); ?>">
        <?php
    }
}
// Xử lý yêu cầu xuất email
add_action('admin_init', 'handle_export_emails');
function handle_export_emails() {
    if (isset($_GET['export_emails'])) {
        export_customer_emails();
    }
}
// Hàm xuất email khách hàng
if (!function_exists('export_customer_emails')) {  
	function export_customer_emails() {
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    global $wpdb;
    // Xác định trạng thái đơn hàng dựa trên cột hiện tại
    $current_status = isset($_GET['post_status']) ? sanitize_text_field($_GET['post_status']) : 'all';
    $emails = []; 
    if ($current_status == 'all' || $current_status == '') {
        $order_statuses = array('wc-completed', 'wc-processing', 'wc-on-hold','wc-pending-payment','wc-cancelled','wc-refunded','wc-failed','wc-draft','wc-trash');
    } else {
        $order_statuses = array($current_status);
    }
    foreach ($order_statuses as $status) {
        $orders = $wpdb->get_results($wpdb->prepare(
            "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'shop_order' AND post_status = %s",
            $status
        ));
        foreach ($orders as $order_post) {
            $order = wc_get_order($order_post->ID);
            $email = $order->get_billing_email();
            if ($email && !in_array($email, $emails)) {
                $emails[] = $email;
            }
        }
    }
    // Xuất dữ liệu ra file CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=customer-emails.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Customer Email']);
    foreach ($emails as $email) {
        fputcsv($output, [$email]);
    }
    fclose($output);
    exit();
}
}
// Thêm nút "Export Emails" vào trang đơn hàng của WooCommerce end webantam.com

//
Trong quá trình tối ưu hóa website thì việc làm sạch các postmeta, các thẻ metadata là điều mà bắt buộc ai cũng phải làm. Tuy nhiên việc này đòi hỏi bạn phải biết về kĩ thuật cũng như phải thực hiện backup trước khi làm các công việc này.

Đoạn function dưới đây (Mình sưu tầm) sẽ giúp cho các bạn có thể làm sạch các thẻ wp_attachment_metadata sau khi xóa 1 bức ảnh nào đó trong thư viện ảnh. Việc này sẽ giúp cho các bạn có thể tối ưu được database rất nhiều, gián tiếp giúp website có thể load nhanh hơn (Vì query trong bảng nhanh hơn).

//
function isa_cleanup_attachment_metadata(){
    if ( get_option( 'my_run_only_once_1' ) != 'completed' ) {
    
        $upload_dir   = wp_upload_dir();
        $upload_basedir = $upload_dir['basedir'];
        $attachment_post_ids = array();
   
        // get all attachment ids for any _wp_attachment_metadata that exists in the wp_postmeta table
           
        // @todo edit database details: 
   
        $mysqli = new mysqli('DB_HOST', 'DB_USER', 'DB_USER_PASSWORD', 'DATABASE_NAME');
   
        if( ! $res = $mysqli->query( "SELECT * FROM wp_postmeta WHERE meta_key = '_wp_attachment_metadata'" ) ) {
            error_log($mysqli->error);
        } else {
            while ($row = $res->fetch_assoc()) {
                $attachment_post_ids[] = $row['post_id'];
            }
            $res->close();
        }
        $mysqli->close();
   
        foreach( $attachment_post_ids as $attachment_id ) {
    
            $data = wp_get_attachment_metadata( $attachment_id );
   
            $original_file_name = $data['file'] ?? '';
   
            $new_data = $data;
   
            // get all filenames from the data
  
            $all_sizes_filenames = wp_list_pluck( $data['sizes'], 'file' );
  
            foreach( $all_sizes_filenames as $size => $filename ) {
   
            $delete_flag = true;
                $update_flag = false;
  
                // check that each one doesn't exist, if it does, then don't delete the whole attachment, just update it
  
                // get month/year for filename
                $p = strrpos($original_file_name, '/');
                $path = ($p !== false) ? substr($original_file_name, 0, $p+1) : '';
      
                if ( file_exists( $upload_basedir . '/' . $path . $filename ) ){
   
                    $delete_flag = false;
  
                } else {
  
                    error_log($path . $filename .' DOES NOT EXIST');
   
                    unset( $new_data['sizes'][ $size ] );// remove that size from the array
   
                    $update_flag = true;
   
                }
               
            }
    
            // if there's no original file, it is not an image, so don't delete the attachment, just update it to remove the sizes
    
            if ( $original_file_name && $delete_flag ) {
   
                // none of the sized images exist, now check if original file exists
   
                if ( ! file_exists( $upload_basedir . '/' . $original_file_name ) ) {
               
                    error_log('DELETING attachment meta id ' . $attachment_id . ' :');
                    error_log(print_r($data, true));
   
                    wp_delete_attachment( $attachment_id );
   
                }
            } 
              
            if ($update_flag) {
   
                if ( count( $new_data ) === 1 && empty( $new_data['sizes'] ) ) {
                    $new_data = array();// to make sure empty _wp_attachment_metadata is deleted
                }
   
                error_log('UPDATING attachment meta id ' . $attachment_id . ' :');
                error_log(print_r($new_data, true));
   
                wp_update_attachment_metadata( $attachment_id, $new_data );
    
            }
   
   
        }// ends foreach attachment
   
   
        update_option( 'my_run_only_once_1', 'completed' );
       
   
    }// end run only once
   
}
   
add_action('admin_init', 'isa_cleanup_attachment_metadata');

// lưu ý
DB_HOST
DB_USER
DB_USER_PASSWORD
DATABASE_NAME

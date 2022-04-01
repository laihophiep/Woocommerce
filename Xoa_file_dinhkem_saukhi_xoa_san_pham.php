function delete_all_attached_media( $post_id ) {
     if ( get_post_type($post_id) == "product" ) {
          $attachments = get_attached_media( '', $post_id );
          foreach ($attachments as $attachment) {
               wp_delete_attachment( $attachment->ID, 'true' );
          }
     }
}
add_action( 'before_delete_post', 'delete_all_attached_media' );

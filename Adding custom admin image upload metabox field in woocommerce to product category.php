<?php // add to function.php
//call for woocommerce custom admin image code    
require get_template_directory() . '/inc/woo-meta-category.php';

/*--------------------------------------------------------------------------------------
    Uploader JS
----------------------------------------------------------------------------------------*/
function my_admin_scripts() {
    wp_enqueue_media();

    wp_register_script( 'wina_classic-uadmin-js', get_template_directory_uri() . '/js/uploader.js', 
                                        array('jquery','media-upload','thickbox'), '20130115', true );
    wp_enqueue_script( 'wina_classic-uadmin-js');
}

add_action('admin_print_scripts', 'my_admin_scripts');

/*--------------------------------------------------------------------------------------*/

//Add Media uploader Javascript in js/uploader.js
jQuery(document).ready( function($){

    var mediaUploader_woo;
    if ( '0' === $( '#category-meta-woo' ).val() ) {
                        $( '.remove-img-cat' ).hide();
                    }
    $('#upload-button-woo').on('click',function(e) {
        e.preventDefault();
        if( mediaUploader_woo ){
            mediaUploader_woo.open();
            return;
        }

        mediaUploader_woo = wp.media.frames.file_frame = wp.media({
            title: 'Choose an Image',
            button: { text: 'Choose Image'},
            multiple: false
        });

        mediaUploader_woo.on('select', function(){
            attachment = mediaUploader_woo.state().get('selection').first().toJSON();
            $('#category-meta-woo').val(attachment.url);
            $('#category-header-preview').attr('src', ''+ attachment.url + '' );
            $( '.remove-img-cat' ).show();
        });

        mediaUploader_woo.open();
    }); 
    jQuery( document ).on( 'click', '.remove-img-cat', function() {
                        $( '.img-preview-cat' ).find( 'img' ).attr( 'src', '/wantrading/uploads/woocommerce-placeholder-300x300.png' );
                        $( '#category-meta-woo' ).val( '' );
                        $( '.remove-img-cat' ).hide();
                        return false;
                    });   

});
<?php

/*-------------------------------------------------------------------
    Add Custom metabox for woocommerce Category page
---------------------------------------------------------------------*/

function product_cat_add_cat_head_field_rj() {  ?>
    <div class="form-field">
        <label for="term_meta[cat_bg_link]"><?php _e( 'Category Page Image', 'wantrading-classic' ); ?></label>
        <input type="text" name="term_meta[cat_bg_link]" id="term_meta[cat_bg_link]" value="">
        <p class="description"><?php _e( 'Upload Category Page Image','wantrading-classic' ); ?></p>
    </div>
<?php }

function product_cat_edit_cat_head_field_rj($term) {
    $t_id = $term->term_id; $term_meta = get_option( "taxonomy_$t_id" ); ?>

    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[cat_bg_link]"><?php _e( 'Category Page Image', 'wantrading-classic' ); ?></label></th>
        <td>
            <div class="img-preview-cat">
            <img src="<?php echo esc_attr( $term_meta['cat_bg_link'] ) ? esc_attr( $term_meta['cat_bg_link'] ) : '/wantrading/uploads/woocommerce-placeholder-300x300.png'; ?>" height="60" width="120" id="category-header-preview" />
            </div>
            <input type="hidden" name="term_meta[cat_bg_link]" id="category-meta-woo" value="<?php echo esc_attr( $term_meta['cat_bg_link'] ) ? esc_attr( $term_meta['cat_bg_link'] ) : ''; ?>" style="margin-left: 0px; margin-right: 0px; width: 50%;" />
            <input type="button" class="button button-secondary" value="Upload Image" id="upload-button-woo" />
            <input type="button" class="remove-img-cat button button-secondary" value="Remove Image Cat"></input>
            <p class="description"><?php _e( 'Upload Category Page Image','wantrading-classic' ); ?></p>
        </td>
    </tr>
<?php
}

// this action use for add field in add form of taxonomy 
add_action( 'product_cat_add_form_fields', 'product_cat_add_cat_head_field_rj', 10, 2 );
// this action use for add field in edit form of taxonomy 
add_action( 'product_cat_edit_form_fields', 'product_cat_edit_cat_head_field_rj', 10, 2 );

function product_cat_cat_bg_link_save( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
       update_option( "taxonomy_$t_id", $term_meta );
    }
}

// this action use for save field value of edit form of taxonomy 
add_action( 'edited_product_cat', 'product_cat_cat_bg_link_save', 10, 2 );  
// this action use for save field value of add form of taxonomy 
add_action( 'create_product_cat', 'product_cat_cat_bg_link_save', 10, 2 );

// hien thi ngoai font end
global $post;

//for product cat archive page only
$term = get_queried_object();
$cutomPageImageOption = get_option('taxonomy_' . $term->term_id);
$cutomPageImage = $cutomPageImageOption['cat_bg_link'];

if ($cutomPageImage > 1) { echo "Please add a category head image in the admin panel"; }
?>

<section id="page-header" class="page-header" style="background-image: url('<?php echo $cutomPageImage; ?>">
    <div class="container">
        <div class="row">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>

        </div>
    </div>
</section>

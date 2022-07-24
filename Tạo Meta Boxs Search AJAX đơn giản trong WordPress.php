Nội Dung Bài Viết
Bước 1: Tạo Metabox trong post WordPress
Bước 2: Thêm thư viện Select2
Bước 3: Khởi tạo Select2
Bước 4: Tạo function tìm kiếm post trong PHP
Thủ Thuật WordPress - Hướng dẫn Tto Meta Boxs Search AJAX đơn giản trong WordPress với thư viện Select2.

Select 2 là một thư viện JavaScipt nhỏ đơn giản giúp bạn tùy biến Select box dễ dàng, giúp hỗ trợ việc tìm kiếm, tagging, lấy dữ liệu từ nguồn khác nhau...

Để tải Select2 bạn có thể vào https://github.com/select2/select2/tags hoặc dùng qua CDN.

Bước 1: Tạo Metabox trong post WordPress
Đặt đoạn code dưới vào functions.php để tạo một metabox trong wordpress


add_action('admin_menu', 'devwk_metabox_for_select2');
add_action('save_post', 'devwk_save_metaboxdata', 10, 2);

/*
 * Add a metabox
 * I hope you're familiar with add_meta_box() function, so, nothing new for you here
 */
function devwk_metabox_for_select2()
{
    add_meta_box('devwk_select2', 'My metabox for select2 testing', 'devwk_display_select2_metabox', 'post', 'normal', 'default');
}

/*
 * Display the fields inside it
 */
function devwk_display_select2_metabox($post_object)
{

    // do not forget about WP Nonces for security purposes

    // I decided to write all the metabox html into a variable and then echo it at the end
    $html = '';

    // always array because we have added [] to our <select> name attribute
    $appended_tags = get_post_meta($post_object->ID, 'devwk_select2_tags', true);
    $appended_posts = get_post_meta($post_object->ID, 'devwk_select2_posts', true);

    /*
	 * It will be just a multiple select for tags without AJAX search
	 * If no tags found - do not display anything
	 * hide_empty=0 means to show tags not attached to any posts
	 */
    if ($tags = get_terms('post_tag', 'hide_empty=0')) {
        $html .= '<p><label for="devwk_select2_tags">Tags:</label><br /><select id="devwk_select2_tags" name="devwk_select2_tags[]" multiple="multiple" style="width:99%;max-width:25em;">';
        foreach ($tags as $tag) {
            $selected = (is_array($appended_tags) && in_array($tag->term_id, $appended_tags)) ? ' selected="selected"' : '';
            $html .= '<option value="' . $tag->term_id . '"' . $selected . '>' . $tag->name . '</option>';
        }
        $html .= '<select></p>';
    }

    /*
	 * Select Posts with AJAX search
	 */
    $html .= '<p><label for="devwk_select2_posts">Posts:</label><br /><select id="devwk_select2_posts" name="devwk_select2_posts[]" multiple="multiple" style="width:99%;max-width:25em;">';

    if ($appended_posts) {
        foreach ($appended_posts as $post_id) {
            $title = get_the_title($post_id);
            // if the post title is too long, truncate it and add "..." at the end
            $title = (mb_strlen($title) > 50) ? mb_substr($title, 0, 49) . '...' : $title;
            $html .=  '<option value="' . $post_id . '" selected="selected">' . $title . '</option>';
        }
    }
    $html .= '</select></p>';

    echo $html;
}


function devwk_save_metaboxdata($post_id, $post)
{

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    // if post type is different from our selected one, do nothing
    if ($post->post_type == 'post') {
        if (isset($_POST['devwk_select2_tags']))
            update_post_meta($post_id, 'devwk_select2_tags', $_POST['devwk_select2_tags']);
        else
            delete_post_meta($post_id, 'devwk_select2_tags');

        if (isset($_POST['devwk_select2_posts']))
            update_post_meta($post_id, 'devwk_select2_posts', $_POST['devwk_select2_posts']);
        else
            delete_post_meta($post_id, 'devwk_select2_posts');
    }
    return $post_id;
}
Kết quả nhận được như sau:

Image
Tạo metabox search AJax trong WordPress
Để hiểu hơn về Metabox trong WordPress bạn xem thêm "Meta Boxes trong Wordpress".

Bước 2: Thêm thư viện Select2
Sử dụng hook admin_enqueue_scripts  để thêm thư viện Select2 bằng cách thêm snippet bên dưới vào functions.php

add_action( 'admin_enqueue_scripts', 'devwk_select2_enqueue' );
function devwk_select2_enqueue(){

	wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
	wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );
	
	// please create also an empty JS file in your theme directory and include it too
	wp_enqueue_script('mycustom', get_stylesheet_directory_uri() . '/mycustom.js', array( 'jquery', 'select2' ) ); 
	
}
Bước 3: Khởi tạo Select2
Trong file custom.js khai báo ở trên, thêm đoạn code bên dưới

jQuery(function($){
	// simple multiple select
	$('#devwk_select2_tags').select2();

	// multiple select with AJAX search
	$('#devwk_select2_posts').select2({
  		ajax: {
    			url: ajaxurl, // AJAX URL is predefined in WordPress admin
    			dataType: 'json',
    			delay: 250, // delay in ms while typing when to perform a AJAX search
    			data: function (params) {
      				return {
        				q: params.term, // search query
        				action: 'mishagetposts' // AJAX action for admin-ajax.php
      				};
    			},
    			processResults: function( data ) {
				var options = [];
				if ( data ) {
			
					// data is the array of arrays, and each of them contains ID and the Label of the option
					$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
						options.push( { id: text[0], text: text[1]  } );
					});
				
				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 3 // the minimum of symbols to input before perform a search
	});
});
Bước 4: Tạo function tìm kiếm post trong PHP
Trong file functions.php, tiếp tục tạo function xử lý để lấy post khi AJAX được call lên như sau

add_action( 'wp_ajax_mishagetposts', 'devwk_get_posts_ajax_callback' ); // wp_ajax_{action}
function devwk_get_posts_ajax_callback(){

	// we will pass post IDs and titles to this array
	$return = array();

	// you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
	$search_results = new WP_Query( array( 
		's'=> $_GET['q'], // the search query
		'post_status' => 'publish', // if you don't want drafts to be returned
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 50 // how much to show at once
	) );
	if( $search_results->have_posts() ) :
		while( $search_results->have_posts() ) : $search_results->the_post();	
			// shorten the title a little
			$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
			$return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
		endwhile;
	endif;
	echo json_encode( $return );
	die;
}
Bây giờ bạn có thể xem thành quả được rồi

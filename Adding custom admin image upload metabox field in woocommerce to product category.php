// hien thi ngoai font end
global $post;

//for product cat archive page only
$term = get_queried_object();
$cutomPageImageOption = get_option('taxonomy_' . $term->term_id);
$cutomPageImage = $cutomPageImageOption['cat_head_link'];

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

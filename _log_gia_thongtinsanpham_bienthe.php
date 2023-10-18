add_action( 'woocommerce_before_single_variation','action_wc_before_single_variation' );
function action_wc_before_single_variation() {
    ?>
    <script type="text/javascript">
    (function($){

         $(document).ready(function() {
            var PriceGoiQua = parseInt($('#goiquadacbiet').val());
                $.fn.currencyFormat = function() {
    return this.each(function() {
      var value = $(this).text();
      var formatted_value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
      $(this).text(formatted_value + "₫");
    });
  };
     var variations = JSON.parse($(".variations_form").attr("data-product_variations"));
         $("#goiquadacbiet").click(function() {
               var variation_ids = $('input[name=variation_id]').val();
                // Duyệt qua mỗi phần tử trong đối tượng jsonObj
                for (var i = 0; i < variations.length; i++) {
                  var item = variations[i];
                  // So sánh variation_id của mỗi phần tử với targetVariationId
                  if (item.variation_id === parseInt(variation_ids)) {
                    // Hiển thị display_price tương ứng
                    var giatien =  item.display_price;
                    break; // Thoát khỏi vòng lặp nếu tìm thấy
                  }
                }
                if(giatien){
            var product_price =  giatien + PriceGoiQua;
            $('.hdev_upprice_cart .total-price bdi').text(product_price).currencyFormat();
            }
    if ( $('#goiquadacbiet').attr('checked')) {
        $('#goiquadacbiet').attr('checked', false);
         $('.contentgif').hide(200);
         if (giatien){
            $('.hdev_upprice_cart .total-price bdi').text(giatien).currencyFormat();
         }
    } else {
        $('#goiquadacbiet').attr('checked', 'checked');
        $('.contentgif').show(200);
    }
});

     
        $('form.variations_form').on('show_variation', function(event, data){
            // console.log( data.variation_id );      // The variation Id  <===  <===

            // console.log( data.attributes );        // The variation attributes
            // console.log( data.availability_html ); // The formatted stock status
            // console.log( data.dimensions );        // The dimensions data
            // console.log( data.dimensions_html );   // The formatted dimensions
            console.log( 'Giá gốc' + data.display_price );     // The raw price (float)
            console.log('Giá sale' +  data.display_regular_price ); // The raw regular price (float)
            // console.log( data.image );             // The image data
            // console.log( data.image_id );          // The image ID (int)
            // console.log( data.is_downloadable );   // Is downloadable (boolean)
            // console.log( data.is_in_stock );       // Is in stock (boolean)
            // console.log( data.is_purchasable );    // Is purchasable (boolean)
            // console.log( data.is_sold_individually ); // Is sold individually (yes or no)
            // console.log( data.is_virtual );        // Is vistual (boolean)
            // console.log( data.max_qty );           // Max quantity (int)
            // console.log( data.min_qty );           // Min quantity (int)
            // console.log( data.price_html );        // Formatted displayed price
            // console.log( data.sku );               // The variation SKU
            // console.log( data.variation_description ); // The variation description
            // console.log( data.variation_is_active );   // Is variation active (boolean)
            // console.log( data.variation_is_visible );  // Is variation visible (boolean)
            // console.log( data.weight );            // The weight (float)
            // console.log( data.weight_html );       // The formatted weight
             var giathanh = data.display_price;
            if ($("#goiquadacbiet").prop("checked")) { 
  var product_price =  giathanh + PriceGoiQua;
  $('.hdev_upprice_cart .total-price bdi').text(product_price).currencyFormat();
} else {  $('.hdev_upprice_cart .total-price bdi').text(giathanh).currencyFormat();
}
        });
        });
    })(jQuery);
    </script>
    <?php
}

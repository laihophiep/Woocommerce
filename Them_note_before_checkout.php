function not_checkout(){;?>
    <div class="area_checkout_note_pt">
        <div class="note_text_pt">
            CẢM ƠN CÁC BẠN ĐÃ TIN TƯỞNG CHÚNG TÔI TRONG SUỐT THỜI GIAN QUA
        </div>
    </div>
<?php }
add_action('woocommerce_before_checkout_form','not_checkout');
.note_text_pt{
    padding: 30px;
    text-align: center;
    background: #e03232;
    border-radius: 5px;
    font-size: 18px;
    color: #fff;
    font-weight: bold;
    margin-bottom: 15px;
}

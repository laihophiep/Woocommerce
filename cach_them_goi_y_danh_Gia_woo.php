function add_text_comment(){
if(is_product()){;?>
	<div class="text-comment-pt">
		<div class="text-comment-pt_item">Sản phẩm khá tốt</div>
		<div class="text-comment-pt_item">Sản phẩm tuyệt vời</div>
		<div class="text-comment-pt_item">Mình rất thích</div>
		<div class="text-comment-pt_item">Cái này dùng rất tốt</div>
		<div class="text-comment-pt_item">Dùng nó như thế nào?</div>
	</div>
<?php }
}
add_action('comment_form_top','add_text_comment');
.text-comment-pt{
    display:flex;
    flex-wrap:wrap;
    margin-bottom:15px;
}
.text-comment-pt>*{
    margin-right: 10px;
    padding: 2px 5px;
    border-radius: 5px;
    border: 1px solid #c1c1c1;
    font-size: 14px;
    cursor:pointer;
    margin-bottom:5px;
}
.text-comment-pt>*:hover{
    color: #fff;
    background: #fe2c6d;
    border-color: #fe2c6d;
}
<script>
jQuery(document).ready(function($) {
    $(".text-comment-pt_item").on("click", function(e){
        var text = $(this).text();
        $('#comment').text(text);
    });
});
</script>

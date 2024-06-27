<?php // Thông báo đẩy
function display_push_notifications() {
    $push_notifications = get_field('thong_bao_ao', 'option'); //Nhớ thay bằng tên field của bạn
    if ($push_notifications) {
        $count = 0;
        foreach ($push_notifications as $notification) {
            $title = $notification['tieu_de']; //Nhớ thay bằng tên field của bạn
            $content = $notification['noi_dung']; //Nhớ thay bằng tên field của bạn
            echo '<div class="push-notification" style="display: none;" data-order="' . $count . '">';
            echo '<i class="fa-solid fa-envelope-circle-check"></i>';
            echo '<div class="noi-dung-tb">';
            echo '<h3>' . $title . '</h3>';
            echo '<p>' . $content . '</p>';
            echo '</div>';
            echo '</div>';
 
            $count++;
        }
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.push-notification').each(function(index) {
            var $notification = $(this);
            var delay = index * 5000;
            setTimeout(function() {
                if (index !== 0) {
                    $('.push-notification').eq(index - 1).fadeOut();
                }
                $notification.fadeIn();
                setTimeout(function() {
                    $notification.fadeOut();
                }, 3000);
            }, delay + (index === 0 ? 3000 : 5000));
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'display_push_notifications');
<style>    width: max-content;
    background: #fff;
    padding: 10px;
    border-radius: 5px;
    position: fixed;
    z-index: 2;
    left: 10px;
    bottom: 10px;
    display: flex;
    align-items: center;
}
.push-notification i {
    font-size:44px;
}
.noi-dung-tb {
    margin-left: 10px;
}
.push-notification h3 {
    font-size: 16px;
    margin:0;
}
.push-notification p {
    font-size: 14px;
    margin-bottom: 0px;
}</style>

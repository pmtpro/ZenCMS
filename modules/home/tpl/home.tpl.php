<?php load_header() ?>
    <div class="detail_content">

        <h1 class="title border_orange">Khám phá</h1>

        <?php load_message() ?>

        <div class="content" style="line-height: 30px;">
            <div class="item_non_border">
                Cảm ơn bạn đã chọn ZenCMS.<br/>
                Click <b><a href="<?php echo _HOME ?>/login" target="_blank">vào đây</a></b> để đăng nhập tài khoản. <br/>
                Sau khi đăng nhập mời bạn ghé thăm <b><a href="<?php echo _HOME ?>" target="_blank">trang chủ</a></b> của
                mình<br/>
                Ok. Bây giờ hãy tiếp tục ghé thăm trang <b><a href="<?php echo _HOME ?>/admin" target="_blank">quản lí</a></b> nhé<br/>
            </div>
            <div class="item_non_border">
                Còn chờ gì nữa mà không thử với những bài viết đầu tiên :D<br/>
                Để viết những bài đầu tiên bạn có thể ra <b><a href="<?php echo _HOME ?>" target="_blank">trang chủ</a></b>
                đã có hướng dẫn sẵn rồi nhá.<br/>
                Hoặc bạn có thể truy cập theo link sau: <br/>
                <code>Admin CP -> Modules cpanel -> Trang bài viết -> Quản lí nội dung</code><br/>
                Chắc bạn cũng đã hiểu phần nào cấu trúc website rồi chứ ^_^<br/>

            </div>
            <div class="item_non_border">
                Nếu có bất kì thắc mắc gì vui lòng liên hệ với chúng tôi qua địa chỉ
                <a href="http://zencms.vn" target="_blank" title="ZenCMS - Web developers">http://zencms.vn</a> để nhận được hỗ trợ nhé<br/>
            </div>

        </div>
    </div>

    <div class="detail_content">
        <div class="title border_blue">
            Danh sách địa chỉ nên nhớ
        </div>

        <div class="item">
            <?php echo icon('item') ?> <a href="http://zencms.vn" target="_blank" title="ZenCMS - Web developers">Trang
                chủ ZenCMS</a>
        </div>
        <div class="item">
            <?php echo icon('item') ?> <a href="http://zenthang.com" target="_blank" title="ZenThang">Trang thông
                tin</a>
        </div>
        <div class="item">
            <?php echo icon('item') ?> <a href="http://zencms.vn/zenmarket" target="_blank" title="ZenMarket">Chợ ứng
                dụng</a>
        </div>
    </div>
<?php load_footer() ?>
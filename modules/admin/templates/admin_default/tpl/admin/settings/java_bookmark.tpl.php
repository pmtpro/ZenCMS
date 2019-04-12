<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="title border_blue">Cài đặt bản bookmark java</h2>

        <?php load_message() ?>

        <div class="tip">
            Tính năng này tự tạo bookmark trên mỗi ứng dụng java<br/>
            Url bookmark phải gồm <?php echo $length_url_bookmark ?> kí tự. Bạn có thể vào <b><a href="http://goo.gl" target="_blank">http://goo.gl</a></b> để rút gọn url<br/>
        </div>
        <form method="post">
            <div class="item">
                Url bookmark<br/>
                <input type="text" name="url_bookmark_java" value="<?php echo get_config('url_bookmark_java') ?>" />
            </div>
            <div class="item">
                Tiêu đề bookmark<br/>
                <input type="text" name="title_bookmark_java" value="<?php echo get_config('title_bookmark_java') ?>"/>
            </div>
            <div class="item">
                <input type="submit" name="sub" value="Lưu thay đổi" class="button BgBlue"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>
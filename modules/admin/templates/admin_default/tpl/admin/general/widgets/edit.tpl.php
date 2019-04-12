<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>

        <div class="tip">Bỏ trống title để chỉ hiển thị nội dung</div>

        <form method="POST">
            <div class="item">
                Tiêu đề:<br/>
                <input type="text" name="title" value="<?php echo $widget_data['title'] ?>"/>
            </div>
            <div class="item">
                Nội dung (Sử dụng html, js, css ...):<br/>
                <textarea name="content"><?php echo $widget_data['content'] ?></textarea>
            </div>
            <div class="item">
                <input type="submit" name="sub_edit" value="Lưu thay đổi" class="button BgBlue"/>
                <a href="<?php echo _HOME ?>/admin/general/widgets/<?php echo urlencode($wg) ?>" class="button BgGreen">Trở lại</a>
            </div>
        </form>

    </div>

<?php load_footer() ?>
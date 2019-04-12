<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Xóa cache</div>
        <?php load_message() ?>
        <div class="tip">
            Hệ thống đã tìm thấy <b><?php echo $total_cache ?></b> file cache chiếm <b><?php echo get_size($total_cache_size) ?></b>
        </div>
        <form method="post">
            <div class="item">
                <input type="submit" name="sub_delete_cache" value="Xóa cache" class="button BgRed"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>
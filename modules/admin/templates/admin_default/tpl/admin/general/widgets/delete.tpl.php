<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>

        <div class="tip">Xóa widget đi bạn sẽ không thể khôi phục</div>

        <form method="POST">
            <div class="item">
                <input type="submit" name="sub_delete" value="Xóa" class="button BgBlue"/>
                <a href="<?php echo _HOME ?>/admin/general/widgets/<?php echo urlencode($wg) ?>" class="button BgGreen">Trở lại</a>
            </div>
        </form>

    </div>

<?php load_footer() ?>
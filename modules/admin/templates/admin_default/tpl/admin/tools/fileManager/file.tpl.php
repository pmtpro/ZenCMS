<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí file</div>
        <div class="item_non_border">
            <?php foreach ($public_cpanel as $url => $cp): ?>
                <a href="<?php echo $url ?>" class="button BgBlue"><?php echo $cp ?></a>
            <?php endforeach ?>
        </div>
    </div>

    <div class="detail_content">
        <div class="sub_title border_blue">Sửa: <?php echo $fileinfo['name'] ?></div>

        <div class="path">
            <code><?php echo $file ?></code>
        </div>

        <?php load_layout('fileinfo') ?>

        <?php load_message() ?>

        <div class="tip">
            Cẩn thận với những file PHP. Chỉ sửa khi chắc chắn mình đúng
        </div>

        <form method="POST">
            <div class="content">
                <textarea name="content" id="content" style="width: 100%;"><?php echo $fileinfo['content'] ?></textarea>
            </div>
            <div class="item">
                <input type="hidden" name="token_save_code" value="<?php echo $token_save_code ?>"/>
                <input type="submit" name="sub_save" value="Lưu thay đổi" class="button BgBlack"/>
                <a href="<?php echo _HOME ?>/admin/tools/fileManager?file=<?php echo $path_levelup ?>" class="button BgBlack">Hủy</a>
            </div>
        </form>

    </div>

<?php load_footer() ?>
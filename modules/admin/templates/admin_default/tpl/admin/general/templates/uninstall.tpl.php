<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>
        <div class="tip">
            Bạn có muốn hủy template này?
        </div>
        <?php load_message() ?>
        <form method="POST">
            <div class="item">
                <input type="submit" name="sub_uninstall" value="Uninstall" class="button BgBlue"/>
                <a href="<?php echo _HOME ?>/admin/general/templates/list" class="button BgRed">Hủy</a>
            </div>
        </form>
    </div>

<?php load_footer() ?>
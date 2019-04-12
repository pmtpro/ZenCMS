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
        <div class="sub_title border_blue">Chmod</div>

        <div class="path">
            <?php echo $file ?>
        </div>

        <?php load_message() ?>

        <div class="content">

            <form method="POST">

                <?php foreach ($selected as $item): ?>

                    <?php if (isset($failure[$item])): ?>
                        <div class="mini_notice">
                            <?php echo $failure[$item] ?>
                        </div>
                    <?php endif ?>

                    <code class="ad_list">
                        <?php echo base64_decode($item) ?>:
                        <?php echo $info[$item]['perms'] ?>
                    </code>

                <?php endforeach ?>

                <input type="hidden" name="token_delete" value="<?php echo $token_delete ?>" />
                <input type="submit" name="sub_do_delete" value="Xóa" class="button BgRed"/>
                <a href="<?php echo _HOME ?>/admin/tools/fileManager?file=<?php echo $file ?>" class="button BgBlack">Hủy</a>
            </form>

        </div>
    </div>

<?php load_footer() ?>
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
        <div class="sub_title border_blue">Unzip</div>

        <div class="path">
            <?php echo $file ?>
        </div>

        <?php load_message() ?>

        <div class="content">

            <form method="POST">

                <code class="ad_list">
                    <?php echo base64_decode($selected) ?>:
                    <?php echo $info[$selected]['perms'] ?>
                </code>
                <div class="item">
                    <input type="text" value="Root" readonly  size = "1"/><input type="text" name="extract_path" value="<?php echo $file ?>" />
                </div>
                <input type="hidden" name="token_unzip" value="<?php echo $token_unzip ?>"/>
                <input type="submit" name="sub_do_unzip" value="Unzip" class="button BgRed"/>
                <a href="<?php echo _HOME ?>/admin/tools/fileManager?file=<?php echo $file ?>" class="button BgBlack">Hủy</a>
            </form>

        </div>
    </div>

<?php load_footer() ?>
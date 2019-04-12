<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h1 class="title border_blue">Smile của bạn (<?php echo $count_my_smiles ?>) | <a href="<?php echo _HOME ?>/account/settings/smiles">Chọn smile</a></h1>

        <?php load_message() ?>
        <?php echo $smiles_pagination ?>

        <form method="post">
            <?php foreach ($smiles as $smile): ?>
                <div class="item">

                    <input type="checkbox" name="smile[]" value="<?php echo $smile ?>"/>

                    <?php echo scan_smiles($smile) ?>
                </div>
            <?php endforeach; ?>

            <div class="item">
                <input type="submit" name="sub_delete" value="Xóa" class="button BgRed"/>
            </div>
        </form>

        <?php echo $smiles_pagination ?>
    </div>

<?php load_footer() ?>
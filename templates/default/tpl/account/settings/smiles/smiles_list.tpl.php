<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h1 class="title border_blue">Chọn smile | <a href="<?php echo _HOME ?>/account/settings/my_smiles">My smiles</a> (<?php echo $count_my_smiles ?>)</h1>

        <?php load_message() ?>
        <?php echo $smiles_pagination ?>

        <form method="post">
            <?php foreach ($smiles as $key => $smile): ?>
                <div class="item">

                    <?php if (!in_array($key, $user['smiles'])) :?>
                    <input type="checkbox" name="smile[]" value="<?php echo $key ?>"/>
                    <?php endif ?>

                    <img src="<?php echo $smile ?>"/> <?php echo $key ?>
                </div>
            <?php endforeach; ?>
            <div class="item">
                <input type="submit" name="sub_add" value="Thêm vào danh sách" class="button BgBlue"/>
            </div>
        </form>

        <?php echo $smiles_pagination ?>
    </div>

<?php load_footer() ?>
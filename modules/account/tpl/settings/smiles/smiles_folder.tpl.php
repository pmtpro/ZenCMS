<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h1 class="title border_blue">Chọn smile | <a href="<?php echo _HOME ?>/account/settings/my_smiles">My smiles</a> (<?php echo $count_my_smiles ?>)</h1>

        <?php load_message() ?>

        <?php foreach($cats as $cat): ?>
        <div class="item">
            <?php echo icon('item') ?><a href="<?php echo _HOME ?>/account/settings/smiles/<?php echo $cat ?>"><?php echo $cat ?></a>
        </div>
        <?php endforeach; ?>
    </div>

<?php load_footer() ?>
<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h1 class="title border_blue">Hộp thư</h1>

        <?php load_message() ?>

        <?php foreach ($menus as $menu): ?>
            <div class="item" style="padding:10px;">
                <span class="icon"><?php echo icon('account|'.$menu['icon'], 'vertical-align: middle;') ?></span>
                <a href="<?php echo $menu['full_url'] ?>"><?php echo $menu['name'] ?></a>
            </div>
        <?php endforeach; ?>

    </div>

<?php load_footer() ?>
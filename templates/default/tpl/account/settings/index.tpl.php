<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="sub_title border_orange">Cài đặt</h2>

        <?php load_message() ?>
        <?php foreach ($menus as $menu): ?>
            <div class="item">
                <?php echo icon('account|'.$menu['icon']) ;?>
                <a href="<?php echo $menu['full_url'] ?>" title="<?php echo $menu['name'] ;?>">
                    <?php echo $menu['name'] ;?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

<?php load_footer() ?>
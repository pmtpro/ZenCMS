<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="title border_blue">Cài đặt</h2>
        <?php foreach ($menus as $menu): ?>
            <div class="item">
                <?php echo icon($menu['icon']) ?>
                <a href="<?php echo $menu['full_url'] ?>"><?php echo $menu['name'] ?></a>
            </div>
        <?php endforeach ?>
    </div>

<?php load_footer() ?>
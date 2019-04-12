<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <?php foreach ($menus as $menu): ?>
        <div class="detail_content">
            <div class="sub_title border_blue">
                <a href="<?php echo $menu['full_url'] ?>"><?php echo $menu['name'] ?></a>
            </div>
            <?php foreach ($menu['sub_menus'] as $sub): ?>
                <div class="item">
                    <?php echo icon($sub['icon']) ?>
                    <a href="<?php echo $sub['full_url'] ?>"><?php echo $sub['name'] ?></a>
                </div>
            <?php endforeach ?>
        </div>
    <?php endforeach ?>

<?php load_footer() ?>
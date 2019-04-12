<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>

            <?php foreach ($list_extends_modules as $mod): ?>
                <div class="item">
                    <?php echo icon('item') ?>
                    <a href="<?php echo $mod['full_url'] ?>" title="<?php echo $mod['name'] ?> - <?php echo $mod['title'] ?>"><?php echo $mod['title'] ?></a>
                </div>
            <?php endforeach; ?>
    </div>

<?php load_footer() ?>
<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Tường nhà</h1>

        <?php load_layout('display_wall') ?>

    </div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange">Thông tin</h2>
        <?php foreach ($actions as $action): ?>
            <div class="item"><?php echo icon('item') ?> <?php echo $action ?></div>
        <?php endforeach ?>

        <div class="item">
            <?php echo icon('item') ?> Tên thật: <?php echo $wall['fullname'] ?>
        </div>
        <div class="item">
            <?php echo icon('item') ?> Sinh nhật: <?php echo $wall['birth'] ?>
        </div>
    </div>

<?php load_footer() ?>
<ul class="list-group sb_category">
    <?php foreach(ZenView::$layout_data['block/list-group-menu'] as $item): ?>
    <li class="list-group-item title">
        <?php echo (!empty($item['badge'])?'<span class="badge pull-right">' . $item['badge'] . '</span>':'') ?>
        <?php echo (!empty($item['icon'])?'<span class="' . $item['icon'] . '"></span> ':'') ?><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a>
    </li>
    <?php endforeach ?>
</ul>
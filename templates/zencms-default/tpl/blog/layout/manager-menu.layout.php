<?php $menu = ZenView::get_menu('manager') ?>
<?php if ($menu['menu']): ?>
    <h2><?php echo $menu['name'] ?></h2>
    <ul class="nav sidebar-manager margin-bottom-40">
        <?php foreach ($menu['menu'] as $item): ?>
            <li><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><span class="<?php echo $item['icon'] ?>"></span> <?php echo $item['name'] ?></a></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
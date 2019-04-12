<h2 class="no-top-space">Danh mục</h2>
<ul class="nav sidebar-categories margin-bottom-40">
    <?php $cats = model()->list_custom_cat(0) ?>
    <?php foreach ($cats as $item): ?>
        <li><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a></li>
    <?php endforeach ?>
</ul>
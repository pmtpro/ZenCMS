<div class="menu">Tìm kiếm blog</div>
<div class="pa">
    <form method="POST" name="SearchPushUp">
        <div class="form-group">
            <label for="search-key">Nhập từ cần tìm</label>
            <input type="text" class="form-control" id="search-key" name="key" placeholder="Nhập từ cần tìm"/>
        </div>
        <input type="submit" class="btn btn-primary" name="submit-search" value="Tìm kiếm"/>
    </form>
</div>
<?php ZenView::display_message('search-result'); ?>
<div class="menu"><h1><?php echo $keyword ?></h1></div>
<?php foreach ($result as $item): ?>
    <?php ZenView::load_layout('block/app-item', array('data' => $item)) ?>
<?php endforeach ?>
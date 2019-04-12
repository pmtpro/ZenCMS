<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title title-info">Tìm kiếm blog</div>
    </div>
    <div class="panel-body">
        <div class="padded">
            <form method="POST" name="SearchPushUp">
                <div class="form-group">
                    <label for="search-key">Nhập từ cần tìm</label>
                    <input type="text" class="form-control" id="search-key" name="key" placeholder="Nhập từ cần tìm"/>
                </div>
                <input type="submit" class="btn btn-primary" name="submit-search" value="Tìm kiếm"/>
            </form>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title title-info"><?php echo $keyword ?></h1>
    </div>
    <div class="panel-body">
        <div class="padded">
            <?php ZenView::display_message('search-result'); ?>
            <?php foreach ($result as $item): ?>
                <?php ZenView::load_layout('block/app-item', array('data' => $item)) ?>
            <?php endforeach ?>
        </div>
    </div>
</div>